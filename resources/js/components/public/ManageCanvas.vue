<template>
    <div>
        
            <form method="post" >
                <h3 class="text-center form-title"><span class="dblUnderlined">PURCHASING CANVAS SHEET</span></h3>
                <div class="col-md-12">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="datefiled" name="date-filed" readonly="true">
                                <label class="form-field__label">Date Filed</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <div class="mdb-table-overflow" >
                        <table width="100%" class="table table-hover mdb-table">
                            <thead>
                                <tr>
                                    <th class="text-center" width="200px">BRAND</th>
                                    <th class="text-center">ITEM DESCRIPTION</th>
                                    <th class="text-center">UNIT COST</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-center">TOTAL</th>
                                    <th class="text-center">REMARKS</th>
                                </tr>
                                <tr v-if="!$parent.disabledinput">
                                    <td>
                                        <div class="mdb-form-field form-group-limit">
                                            <div class="form-field__control form-field--is-filled">
                                                <input type="text" name="Brand" v-model="itembrand" v-validate="'required'" class="form-field__input inline-input" >
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('Brand') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mdb-form-field form-group-limit">
                                            <div class="form-field__control form-field--is-filled">
                                                <input type="text" name="Item Description" v-model="itemdesc" v-validate="'required'" class="form-field__input inline-input" >
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('Item Description') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mdb-form-field form-group-limit">
                                            <div class="form-field__control form-field--is-filled">
                                                <input type="text" name="Unit Cost" v-model="itemcost" v-validate="'required|numeric'" class="form-field__input inline-input" >
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('Unit Cost') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mdb-form-field form-group-limit">
                                            <div class="form-field__control form-field--is-filled">
                                                <input type="text" name="QTY" v-model="qty" v-validate="'required|numeric'" class="form-field__input inline-input" >
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('QTY') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mdb-form-field form-group-limit">
                                            <div class="form-field__control form-field--is-filled">
                                                <input type="text" name="total" v-model="total" v-validate="'required|decimal'" class="form-field__input inline-input" >
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('total') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mdb-form-field form-group-limit">
                                            <div class="form-field__control form-field--is-filled">
                                                <input type="text" name="remarks" v-model="itemremarks" v-validate="'required'" class="form-field__input inline-input" @keydown.enter.prevent="appendTable">
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors">{{ errors.first('remarks') }}</span>
                                        </div>
                                        <input type="submit" style="position: absolute; left: -999999px;">
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in entries" :key="index">
                                    <td>
                                        <div>
                                            <span style="padding: 0 12px;">
                                                <a v-if="!$parent.disabledinput" @click="removeRow(index)"><i class="fas fa-trash text-danger"></i></a>
                                            </span>
                                                {{item.itembrand}}
                                        </div>
                                    </td>
                                    <td>
                                        {{item.itemdesc}}
                                    </td>
                                    <td>
                                        {{item.itemcost}}
                                    </td>
                                    <td>
                                        {{item.qty}}
                                    </td>
                                    <td>
                                        {{item.total}}
                                    </td>
                                    <td>
                                        {{item.itemremarks}}
                                    </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <h5 class="form-subtitle"><em>Supplier info</em></h5>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="daysdeliver" v-validate="'required|numeric|max_value:360'" name="delivery-days" >
                                <label class="form-field__label">Days of Delivery</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('delivery-days') }}</span>
                        </div>
                </div>
                <div class="col-md-8">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="terms" v-validate="'required'" name="terms" >
                                <label class="form-field__label">Terms</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('terms') }}</span>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6">
                        <div class="mdb-form-field">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="compname" v-validate="'required'" name="company" >
                                <label class="form-field__label">Company Name</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('company') }}</span>
                        </div>
                </div>
                <div class="col-md-6">
                        <div class="mdb-form-field ">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="contactperson" v-validate="'required'" name="contact-person" >
                                <label class="form-field__label">Contact Person</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('contact-person') }}</span>
                        </div>
                </div>
                <div class="col-md-12">
                    <h5 class="form-subtitle"><em>With Quotation Submitted?</em></h5>
                </div>
                <div class="col-md-12">
                        <div class="col-md-2 col-xs-6">
                                <div>
                                        <label class="mdblblradio">
                                        <span class="checklbl">YES</span> 
                                        <input :disabled="$parent.disabledinput" type="radio" value="1" v-model="withquotation" name="quoatation"> 
                                        <span rf="" online="" class="checkmark"></span>
                                        </label>
                                </div>
                        </div>
                        <div class="col-md-10 col-xs-6">
                                <div>
                                        <label class="mdblblradio">
                                        <span class="checklbl">NO</span> 
                                        <input :disabled="$parent.disabledinput" type="radio" value="2" v-model="withquotation" name="quoatation"> 
                                        <span rf="" online="" class="checkmark"></span>
                                        </label>
                                </div> 
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <h5 class="form-subtitle"><em>Canvas request for</em></h5>
                </div>
                <div class="col-md-12">
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">NTMC</span>
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="brand" value="1" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">APBW</span>
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="brand" value="2" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">PHILCREST</span>
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="brand" value="3" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">TYREPLUS</span>
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="brand" value="4" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>FOR APPROVER</em></h5>
                </div>
                <!-- status is rejected or approve -->
                <div v-if="$parent.disabledinput">
                    <div class="col-lg-12">
                        <h5 class="form-subtitle"></h5>
                        <div class="mdb-form-field">
                                <div class="form-field__control mdb-bgcolor">
                                    <textarea :disabled="$parent.$data.forapprover != 'approval'" class="form-field__textarea" id="" cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
                                    <label class="form-field__label">Remarks</label>
                                    <div class="form-field__bar"></div>
                                </div>
                        </div>
                    </div>
                    <div class="col-lg-12" v-if="$parent.disabledinput">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="approvedby" name="branch" readonly="true">
                                <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Approved/Rejected By' }}</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12" v-if="!$parent.disabledinput">
                    <p class="text-info">
                        LIST OF APPROVERS: 
                    </p>
                    <div class="clearfix"></div>
                    <span class="approverlist alert-info" v-for="(approver, key) in $parent.approvers" :key="key">{{approver.approvers}}</span>
                    <br><br>
                </div>
                <div class="clearfix"></div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addCanvas" :disabled="isDisable || !isFormValid || !hasEntries" v-if="submitBtn">
                        <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateCanvas" :disabled="isDisable || !isFormValid || !hasEntries" v-if="updateDeleteBtn">
                        <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteCanvas" :disabled="isDisable" v-if="updateDeleteBtn">
                        <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionCanvas(1)" v-if="approveRejecBtn">
                        <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionCanvas(2)" v-if="approveRejecBtn">
                        <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionCanvas(0)" v-if="cancelBtn"> -->
                    </div>
            </form>
    </div>
</template>
<script>
let brand = ['NTMC', 'APBW', 'PHILCREST', 'TYREPLUS'];
let status = ['Pending', 'Approved', 'Rejected'];
let regex = new RegExp("^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9])|(24:?00))");
export default {
    props: ['userinfo' , 'selected'],
    data() {
		return {
            
			isDisable: false,
            datefiled: moment(new Date()).format('MM/DD/YYYY'),
            itembrand: '',
            itemdesc: '',
            itemcost: '',
            qty: '',
            total: '',
            itemremarks: '',
            daysdeliver: '',
            terms: '',
            compname: '',
            contactperson: '',
            withquotation: 1,
            brand: 1,
            entries: [],
            canvasID: '',
            approvedby: '',
            remarks: ''
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        addCanvas(){
            if(this.isFormValid)
            {
                
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addCanvas', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateCanvas(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateCanvas', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteCanvas(){
            this.isDisable = true;
            axios.get('api/deleteCanvas/'+this.canvasID).then((response)=>{
                this.$parent.deleteRow(this.canvasID);
            }).catch((err)=>{ console.log(err); });
        },
        appendTable(){
            
                this.$validator.validate('Brand', this.itembrand);
                this.$validator.validate('Item Description', this.itemdesc);
                this.$validator.validate('Unit Cost', this.itemcost);
                this.$validator.validate('QTY', this.qty);
                this.$validator.validate('total', this.total);
                this.$validator.validate('remarks', this.itemremarks);

                if(this.itemremarks !='')
                {
                    this.entries.push({ 
                        itembrand: this.itembrand, itemdesc: this.itemdesc, itemcost: this.itemcost,
                        qty: this.qty, total: this.total, itemremarks: this.itemremarks
                    });
                                         
                }

        },
        removeRow(index)
        {
            this.entries.splice(index, 1);
        },
        // ACTIONS FOR Canvas I.E APPROVE / REJECT / CANCEL
        requestActionCanvas(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$root.$data.userinfo.empID;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformCanvas', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                    this.$data[i] = data[i];
                if(i == 'brand')
                    this.$data[i] = (brand.indexOf(data[i]) + 1);
            }
            
            this.MDBINPUT();
            $("#myModal").modal("show");
        },
        selectSupDate(val){
            // this.supdate = moment(val).format('YYYY-MM-DD');
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key == 'witnesses' || key == 'canvasID'){
                    this.$data[key] = '';
                }
                if(key == 'brand')
                {
                    this.$data[key] = 1;
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                if(key === 'entries')
                {
                    this.$data[key] = [];
                }
                if(key == 'datefiled'){
                    this.$data[key] = moment(new Date()).format('MM/DD/YYYY');
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

                if(key != 'Brand' && key != 'Item Description' && key != 'Unit Cost' 
                   && key != 'QTY' && key != 'total' && key != 'remarks'){
                    return this.fields[key].invalid
                }
            });
        },
        hasEntries(){
            return this.entries.length > 0;
        },
        submitBtn()
        {
            return !this.canvasID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.canvasID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput 
        },
        approveRejecBtn(){
            return this.canvasID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.canvasID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        }
    },
    created(){
        VeeValidate.Validator.extend('is_time', {
            getMessage: field => `The format must be HH:MM AM/PM`,
            validate: (value) => new Promise(resolve => {
                // let regex = new RegExp("([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])");
                // let regex = new RegExp("^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$");
                
                resolve({
                    valid: value && regex.test(value)
                });
            })
        });
        
    },
    beforeDestroy(){
        bus.$off('setupdate', this.setDataForEdit)
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