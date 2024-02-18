<template>
    <div>
        
            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">PURCHASE REQUISITION FORM</span></h3>
                <div class="col-md-12">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="datefiled" name="date filed" readonly="true">
                                <label class="form-field__label">Date filed</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <div class="mdb-table-overflow">
                        
                                <table width="100%" class="table table-hover mdb-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="200px">ITEM DESCRIPTION</th>
                                                <th class="text-center">UOM</th>
                                                <th class="text-center">QTY</th>
                                                <th class="text-center">ALLOCATED BUDGET</th>
                                                <th class="text-center">REMARKS</th>
                                                <th class="text-center">ACCOUNTABLE TO</th>
                                            </tr>
                                            <tr v-if="!$parent.disabledinput">
                                                <td>
                                                    <div class="mdb-form-field form-group-limit">
                                                        <div class="form-field__control form-field--is-filled">
                                                            <input type="text" name="item description" v-model="itemdesc" v-validate="'required'" class="form-field__input inline-input" >
                                                            <div class="form-field__bar"></div>
                                                        </div>
                                                        <span class="errors">{{ errors.first('item description') }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mdb-form-field form-group-limit">
                                                        <div class="form-field__control form-field--is-filled">
                                                            <input type="text" name="uom" v-model="uom" v-validate="'required'" class="form-field__input inline-input" >
                                                            <div class="form-field__bar"></div>
                                                        </div>
                                                        <span class="errors">{{ errors.first('uom') }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mdb-form-field form-group-limit">
                                                        <div class="form-field__control form-field--is-filled">
                                                            <input type="text" name="qty" v-model="qty" v-validate="'required|numeric'" class="form-field__input inline-input" >
                                                            <div class="form-field__bar"></div>
                                                        </div>
                                                        <span class="errors">{{ errors.first('qty') }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mdb-form-field form-group-limit">
                                                        <div class="form-field__control form-field--is-filled">
                                                            <input type="text" name="allocated budget" v-model="allocatedbudget" v-validate="'required'" class="form-field__input inline-input" >
                                                            <div class="form-field__bar"></div>
                                                        </div>
                                                        <span class="errors">{{ errors.first('allocated budget') }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mdb-form-field">
                                                        <div class="form-field__control">
                                                            <input type="text" class="form-field__input inline-input" v-model="reason" v-validate="'required'" name="item remarks">
                                                            <div class="form-field__bar"></div>
                                                        </div>
                                                        <span class="errors">{{ errors.first('item remarks') }}</span>
                                                    </div>
                                                
                                                </td>
                                                <td>
                                                    <div class="mdb-form-field">
                                                        <div class="form-field__control">
                                                            <input type="text" class="form-field__input inline-input" v-model="accountableto" v-validate="'required'" name="accountable to" @keydown.enter.prevent="appendTable">
                                                            <div class="form-field__bar"></div>
                                                        </div>
                                                        <span class="errors">{{ errors.first('accountable to') }}</span>
                                                    </div>
                                                
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
                                            </tr>
                                                
                                        </tbody>
                                </table>

                    </div>
                </div>
                <div class="col-md-12">
                    <h5 class="form-subtitle"><em>Purpose</em></h5>
                </div>
                <div class="col-lg-12">
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="purpose" name="additional-info"></textarea>
                                <label class="form-field__label">Please indicate here</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
                </div>
                <div class="col-md-12">
                    <h5 class="form-subtitle"><em>Requestor Information</em></h5>
                </div>
                <div class="col-md-12">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="computedfullname" name="fullname" readonly="true">
                                <label class="form-field__label">Full Name</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="computedposname" name="designation" readonly="true">
                                <label class="form-field__label">Designation</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="computeddeptname" name="department" readonly="true">
                                <label class="form-field__label">Department</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="computedbranchname" name="branch" readonly="true">
                                <label class="form-field__label">Branch</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <!-- <div class="col-md-12">
                    <h5 class="form-subtitle"><em>Fixed Asset</em></h5>
                </div>
                <div class="col-md-12"> 
                        <div class="col-md-2 col-xs-3">
                                <div class="inline-formx">
                                    <label class="mdblblradio">
                                    <span class="checklbl">NO</span> 
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="fixedasset" value="2" name="fixed asset" @click="accountableto=''"> 
                                    <span rf="" online="" class="checkmark"></span>
                                    </label>
                                </div>
                                
                        </div>
                        <div class="col-md-10 col-xs-9">
                                <div class="inline-formx">
                                    <label class="mdblblradio">
                                    <span class="checklbl">YES</span> 
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="fixedasset" value="1" name="fixed asset" @click="validateAccountable"> 
                                    <span rf="" online="" class="checkmark"></span>
                                    </label>
                                </div>
                        </div>
                </div> -->
                <div class="clearfix"></div>
                <!-- <div class="col-md-12" v-show="fixedasset == 1">
                    <div class="col-md-12">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" v-model="accountableto" v-validate="fixedasset != 1?'':'required'" class="form-field__input" name="accountable" >
                                <label class="form-field__label">If YES, accountable to....</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('accountable') }}</span>
                        </div>
                    </div>
                </div> -->
                
                <div class="col-md-12">
                    <h5 class="form-subtitle"><em>Inventory</em></h5>
                </div>

                <div class="col-md-12">
                        <div class="col-md-3">
                                <div class="inline-form">
                                    <label class="mdblblradio">
                                    <span class="checklbl">INVENTORIABLE</span> 
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="inventoriable" value="1" name="inventoriable"> 
                                    <span rf="" online="" class="checkmark"></span>
                                    </label>
                                </div>
                        </div> 
                        <div class="col-md-3">
                                <div class="inline-form">
                                    <label class="mdblblradio">
                                    <span class="checklbl">NON-INVENTORIABLE</span> 
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="inventoriable" value="2" name="inventoriable"> 
                                    <span rf="" online="" class="checkmark"></span>
                                    </label>
                                </div>
                        </div>
                        <div class="col-md-3">
                                <div class="inline-form">
                                    <label class="mdblblradio">
                                    <span class="checklbl">MARKETING</span> 
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="inventoriable" value="3" name="inventoriable"> 
                                    <span rf="" online="" class="checkmark"></span>
                                    </label>
                                </div>
                        </div>
                        <div class="col-md-3">
                                <div class="inline-form">
                                    <label class="mdblblradio">
                                    <span class="checklbl">BDP</span> 
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="inventoriable" value="4" name="inventoriable"> 
                                    <span rf="" online="" class="checkmark"></span>
                                    </label>
                                </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <h5 class="form-subtitle">
                    <h5></h5>
                </h5>
                <div class="col-md-12">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" v-model="MRFBDPno" v-validate="'required'" class="form-field__input" name="mrf/bdp" >
                                <label class="form-field__label">MRF/BDP#</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('mrf/bdp') }}</span>
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
                                    <textarea :disabled="$parent.$data.forapprover != 'approval'" class="form-field__textarea"  cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
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
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addPRF" :disabled="disabledIfNoApprover || isDisable || !isFormValid || !hasEntries" v-if="submitBtn">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updatePRF" :disabled="isDisable || !isFormValid || !hasEntries" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deletePRF" :disabled="isDisable" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionPRF(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionPRF(2)" v-if="approveRejecBtn">
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionPRF(0)" v-if="cancelBtn"> -->
                </div>
            </form>
        
        
    </div>
</template>
<script>
let inventoriable = ['Inventoriable', 'Non Inventoriable', 'Marketing', 'BDP'];
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
			isDisable: false,
			datefiled: moment(new Date()).format('MM/DD/YYYY'),
            purpose: '',
            // fixedasset: 2,
            accountableto: '',
            inventoriable: 1,
            MRFBDPno:'',
            itemdesc: '',
            uom: '',
            qty: '',
            allocatedbudget: '',
            reason: '',
            entries: [],
            prfID: '',
            approvedby: '',
            remarks: ''
            
			// reqstat: 0, //0 pending, 1//approve //2 declined
			// status: 0, //0 open, 1//close
			
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        },
        
    },
    methods:{
        addPRF(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                this.totaldays = this.getDiff;
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addPRF', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updatePRF(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updatePRF', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deletePRF(){
            this.isDisable = true;
            axios.get('api/deletePRF/'+this.prfID).then((response)=>{
                this.$parent.deleteRow(this.prfID);
            }).catch((err)=>{ console.log(err); });
        },
        // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        requestActionPRF(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            // if(approver == 'cancel'){
            //     params['approvedby'] = null;   
            // }
            
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformPRF', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'inventoriable')
                    this.$data[i] = (inventoriable.indexOf(data[i]) + 1);
            
            }
            
            this.MDBINPUT();
            $("#myModal").modal("show");
        },

        selectDateStart(val){
            this.datestart = val;
        },
        selectDateEnd(val){
            this.dateend = val;
        },
        appendTable(){
                this.$validator.validate('item description', this.itemdesc);
                this.$validator.validate('uom', this.uom);
                this.$validator.validate('qty', this.qty);
                this.$validator.validate('allocated budget', this.allocatedbudget);
                this.$validator.validate('item remarks', this.reason);

                if(this.itemdesc !='' && this.uom != '' && this.qty != '' 
                    && this.allocatedbudget !='' && this.reason !='')
                {
                    this.entries.push({ 
                        itemdesc: this.itemdesc, uom: this.uom, qty: this.qty,
                        allocatedbudget: this.allocatedbudget, reason: this.reason,
                        accountableto:  this.accountableto
                    });
                                         
                }

        },
        removeRow(index)
        {
            this.entries.splice(index, 1);
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key == 'reason' || key == 'prfID' || key == 'accountableto'){
                    this.$data[key] = '';
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                if(key === 'entries')
                {
                    this.$data[key] = [];
                }
                
            });
            
            $("#myModal").modal("hide");
            
        },
        validateAccountable()
        {
            this.$validator.validate('accountable', this.accountableto);
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
        disabledIfNoApprover(){
            return this.$parent.$data.forapprover != 'approval' && this.$parent.approvers && this.$parent.approvers.length < 1;
        },
        getDiff(){
            this.datestart =  moment(this.datestart).format('YYYY-MM-DD');
            this.dateend = moment(this.dateend).format('YYYY-MM-DD');
            let startDate = moment(this.datestart, 'YYYY-MM-DD');
            let endDate = moment(this.dateend, 'YYYY-MM-DD');
            let totaldays = endDate.diff(startDate, 'days');
            this.totaldays = totaldays;
            return totaldays;
        },
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
        submitBtn()
        {
            return !this.prfID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.prfID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput 
        },
        approveRejecBtn(){
            return this.prfID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.prfID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        },
        computeddeptname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.deptname  : this.userinfo.deptname;
        }
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