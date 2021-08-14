<template>
    <div>
        
            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">PURCHASE RECEIVING SLIP</span></h3>
                <div class="col-md-12">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" v-model="datefiled" name="date" >
                                <label class="form-field__label">Date filed</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <!-- <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input"  name="received-from" >
                                <label class="form-field__label">Received From</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input"  name="requested-by" >
                                <label class="form-field__label">Requested By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input"  name="po/prf-ref" >
                                <label class="form-field__label">PO/PRF REF</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div> -->
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <div class="mdb-table-overflow">
                        <table width="100%" class="table table-hover mdb-table">
                            <thead>
                                    <tr>
                                            <th class="text-center" width="200px">DESCRIPTION</th>
                                            <th class="text-center">UOM</th>
                                            <th class="text-center">QTY</th>
                                            <!-- <th class="text-center">UNIT COST</th>
                                            <th class="text-center">TOTAL COST</th>
                                            <th class="text-center">REMARKS</th> -->
                                            <th class="text-center">ALLOCATED BUDGET</th>
                                            <th class="text-center">REMARKS</th>
                                            <th class="text-center">ACCOUNTABLE TO</th>
                                            <th class="text-center">RECIEVED</th>
                                    </tr>
                                    
                            </thead>
                            <tbody>
                                    <tr v-for="(item, index) in entries">
                                        <td>
                                            <div>
                                                <span style="padding: 0 12px;">
                                                    <a v-if="!$parent.disabledinput" @click="removeRow(index)"><i class="fas fa-trash text-danger"></i></a>
                                                </span>
                                                {{item.itemdesc}}
                                            </div>
                                        </td>
                                        <td>
                                            {{item.uom}}
                                        </td>
                                        <td>
                                            {{item.qty}}
                                        </td>
                                        <td>
                                            {{item.allocatedbudget}}
                                        </td>
                                        <td>
                                            {{item.reason}}
                                        </td>
                                        <td>
                                            {{item.accountableto}}
                                        </td>
                                        <td class="text-center">
                                            <div v-if="item.receivedby && item.receiveddate != datetoday || $parent.$data.forapprover && item.receivedby" class="text-primary">
                                                ITEM RECIEVED
                                            </div>
                                            <div v-if="$parent.$data.forapprover == 'approval' && !item.receivedby" class="text-danger">
                                                TO BE RECEIVED
                                            </div>
                                            <div v-if="$parent.$data.forapprover != 'approval'">
                                                <div v-if="!item.receivedby || item.receivedby && item.receiveddate == datetoday">
                                                    <input type="submit" class="btn btn-primary" value="Received" @click.prevent="actionRecievedPRS(index, item.prfdetailsID)" v-if="!item.receivedby">
                                                    <!-- <input type="submit" class="btn btn-warning padding-20" value="Cancel" @click.prevent="actionRecievedPRS(index, item.prfdetailsID, true)" v-if="item.receivedby"> -cancel -->
                                                </div>
                                            </div>
                                            <!--  -->
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <h5 class="form-subtitle"></h5>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <h5 class="form-subtitle"><em>Purpose</em></h5>
                </div>
                <div class="col-lg-12">
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="$parent.disabledinput" class="form-field__textarea" id="" cols="4" rows="4" v-validate="'required'" v-model="purpose" name="additional-info"></textarea>
                                <label class="form-field__label">Please indicate here</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </form>
        
        
    </div>
</template>
<script>
let inventoriable = ['Inventoriable', 'Non Inventoriable', 'Marketing', 'BDP'];
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo'],
    data() {
		return {
            isDisable: false,
            datetoday: moment(new Date()).format('YYYY-MM-DD'),
			datefiled: moment(new Date()).format('YYYY-MM-DD'),
            purpose: '',
            entries: [],
            prfID: '',
            approvedby: '',
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        },
        
    },
    methods:{
        // RECIEVED / NOT RECIEVED ITEM
        actionRecievedPRS(index = null, id = null, isCancel = false){
            let params = {};
            if(isCancel){
                params['prfdetailsID'] = id;
                params['receivedby']  = null;
            }else{
                params['prfdetailsID'] = id;
                params['receivedby']  = this.$root.$data.userinfo.empID;
            }
            axios.post('api/actionReceivedPRS', params)
            .then((response)=>{
                this.entries[index].receivedby = params.receivedby;
                // this.closeModal();
            })
            .catch((err)=>{ console.log(err); });

        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable' && i != 'datetoday')
                this.$data[i] = data[i];
                if(i == 'inventoriable')
                    this.$data[i] = (inventoriable.indexOf(data[i]) + 1);
            
            }
            // console.log(data);
            this.MDBINPUT();
            $("#myModal").modal("show");
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                
                if(key === 'entries')
                {
                    this.$data[key] = [];
                }
                
            });
            
            $("#myModal").modal("hide");
            
        },
        MDBINPUT(){
            this.$nextTick(() => {
                [].forEach.call(
                        document.querySelectorAll('.form-field__input, .form-field__textarea'),
                        (el) => {
                            el.onblur = () => {
                                setActive(el, false)
                            }
                            el.onfocus = () => {
                                setActive(el, true)
                            }
                            if(el.value !='')
                            {
                                // console.log(el);
                                if(!el.parentNode.classList.contains('form-field__control')){
                                    el.parentNode.classList.add('form-field__control');
                                }
                                el.parentNode.parentNode.classList.add('form-field--is-filled');
                            }
                        }
                    );
             });
        }

    },
    computed:{
        isFormValid(){
            return !Object.keys(this.fields).some((key )=>{ 
                
                if(key != 'item description' && key != 'uom' && key != 'qty' && key !='allocated budget' && key != 'item remarks')
                {
                   return this.fields[key].invalid
                }
            });
        },
        hasEntries(){
            return this.entries.length > 0;
        },
    },
    beforeDestroy(){
        bus.$off('setupdate', this.test)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdate', this.setDataForEdit);

    }
}

    const setActive = (el, active) => {
        const formField = el.parentNode.parentNode
        if (active) {
            formField.classList.add('form-field--is-active')
        } else {
            formField.classList.remove('form-field--is-active')
            el.value === '' ? 
            formField.classList.remove('form-field--is-filled') : 
            formField.classList.add('form-field--is-filled')
        }
    }
</script>