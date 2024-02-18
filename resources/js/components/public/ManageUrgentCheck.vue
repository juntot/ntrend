<template>
    <div>
        <form method="get">
            <h3 class="text-center form-title"><span class="dblUnderlined">URGENT CHECK</span></h3>
            <div class="col-md-12">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="datefiled" name="datefiled" readonly="true">
                        <label class="form-field__label">Date Filed</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group-limit">
                        <Datepicker :disabled="$parent.disabledinput" wrapper-class="mdb-form-field" name="date" :value="datereleased" @selected="selectedDate" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                        <label slot="afterDateInput" class="form-field__label">Date Released</label>
                        <div slot="afterDateInput" class="form-field__bar"></div>
                        </Datepicker> 		
                </div>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="vendor" name="vendor">
                        <label class="form-field__label">Payee/Vendor Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="amount" name="amount">
                        <label class="form-field__label">Amount</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="bankname" name="bank-name">
                        <label class="form-field__label">Bank Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="bankaccount" name="account-number">
                        <label class="form-field__label">Bank Account Number</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h5 class="form-subtitle"></h5>
                <div class="mdb-form-field">
                    <div class="form-field__control mdb-bgcolor">
                        <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="reason" name="additional-info"></textarea>
                        <label class="form-field__label">Reasons for request</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                </div>
            </div>
            <!-- <div class="col-md-12">
                <h5 class="form-subtitle"></h5>
                <div class="mdb-form-field">
                    <div class="form-field__control mdb-bgcolor">
                        <textarea class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="remarks" name="additional-info"></textarea>
                        <label class="form-field__label">Remarks</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                </div>
            </div> -->
            <div class="col-md-12">
            <h5 class="form-subtitle"></h5>
            </div>
            <div class="col-md-12">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedfullname" name="requested-by" readonly="readonly" required>
                        <label class="form-field__label">Requested By</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input"  name="approved-by"  required>
                        <label class="form-field__label">Approved By</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input"  name="endorse-by"  required>
                        <label class="form-field__label">Endorse By</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div> -->
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
                <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addUrgentCheck" :disabled="disabledIfNoApprover || isDisable || !isFormValid" v-if="!urgentID && $parent.$data.forapprover != 'approval'">
                <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateUrgentCheck" :disabled="isDisable || !isFormValid" v-if="urgentID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteUrgentCheck" :disabled="isDisable" v-if="urgentID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionUrgentCheck(1)" v-if="urgentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel ">
                <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionUrgentCheck(2)" v-if="urgentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel">
                <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionUrgentCheck(0)" v-if="urgentID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel"> -->
            </div>
        </form>
    </div>
</template>
<script>
let modtype = ['RF ONLINE', 'CHECK', 'CASH', 'PETTY CASH']; 
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {

        datefiled: moment(new Date()).format('MM/DD/YYYY'),
        datereleased: moment(new Date()).format('YYYY-MM-DD'),
        vendor: '',
        bankname: '',
        bankaccount: '',
        amount: '',
        reason: '',
        remarks: '',
        isDisable: false,
        urgentID: '',
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
        addUrgentCheck(){
            if(this.isFormValid){
                this.isDisable = true;
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addUrgentCheck', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateUrgentCheck(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateUrgentCheck', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteUrgentCheck(){
            this.isDisable = true;
            axios.get('api/deleteUrgentCheck/'+this.urgentID) .then((response)=>{
                this.$parent.deleteRow(this.urgentID) ;
            }).catch((err)=>{
                console.log(err);
            });
            
        },
        // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        requestActionUrgentCheck(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformUrgentCheck', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        selectedDate(val){
            this.datereleased = moment(val).format('YYYY-MM-DD');
        },

        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{

                if(key != 'datefiled' && key != 'datereleased'){
                    this.$data[key] =  '';
                }
                if(key == 'datefiled'){
                    this.$data[key] = moment(new Date()).format('MM/DD/YYYY');
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
            });
            $("#myModal").modal("hide");
        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                // if(i == 'modtype')
                // this.$data[i] = (modtype.indexOf(data[i]) + 1);
                
            }
            
            this.MDBINPUT();
            $("#myModal").modal("show");
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
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
    },

    created(){
        VeeValidate.Validator.extend('is_time', {
            getMessage: field => `The format must be HH:MM AM/PM`,
            validate: (value) => new Promise(resolve => {
                // let regex = new RegExp("([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])");
                // let regex = new RegExp("^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$");
                let regex = new RegExp("^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9])|(24:?00))");
                resolve({
                    valid: value && regex.test(value)
                });
            })
        });
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