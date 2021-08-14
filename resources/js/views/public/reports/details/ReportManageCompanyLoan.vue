<template>
    <div>
        
        <form method="post">
        <h3 class="text-center form-title"><span class="dblUnderlined">COMPANY LOAN</span></h3>
        <div class="col-md-12">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" :value="datefiled" name="date filed" readonly="true">
                    <label class="form-field__label">Date filed</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('date filed') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" :value="computedfullname" name="name" readonly="true">
                    <label class="form-field__label">Full Name</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('name') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" :value="computedposname"  name="position" readonly="true">
                    <label class="form-field__label">Position</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('position') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" :value="computedContact" name="contactnum" readonly="true">
                    <label class="form-field__label">Contact #</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('contactnum') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" :value="computedAddress" name="address" readonly="true">
                    <label class="form-field__label">Address</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('address') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" :value="userinfo.dhired"  name="date-hired" readonly="true">
                    <label class="form-field__label">Date hired</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('date-hired') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" :value="computeddept_branchname" name="dept/branch" readonly="true">
                    <label class="form-field__label">Dept/Branch</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('dept/branch') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" v-model="bankname" v-validate="'required'" name="bankname">
                    <label class="form-field__label">Bank Name</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('bankname') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" v-model="bankaccount" v-validate="'required'" name="banck-acount-number">
                    <label class="form-field__label">Bank Account #</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('banck-acount-number') }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input :disabled="true" type="text" class="form-field__input" v-model="loanamount" v-validate="'required'" name="loan-amount" >
                    <label class="form-field__label">Loan Amount (₱)</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('loan-amount') }}</span>
            </div>
        </div>
        <div class="col-md-12">
            <h5 class="form-subtitle"><em>REASON: (Please select appropriate box) </em></h5>
        </div>
        <div class="col-md-12">
            <div>
                <label class="mdblblradio">
                <span class="checklbl">MEDICAL (not covered by Philhealth and the Company’s Medical Assistance Program)</span> 
                <input :disabled="true" type="radio" v-model="loantype" value="1" name="loantype"> 
                <span class="checkmark"></span>
                </label>
                <div class="col-md-12 blockindent">
                        <div><em>Requirements</em></div>
                        <ul>
                            <li>Hospital Bill / Statement of Account</li>
                            <li>Doctor’s Order or Certification</li>
                            <li>Official Receipt (to be submitted 3 days from release of cash/check)</li>
                        </ul>
                </div>
                <div class="clearfix"></div>
            </div>
            <div>
                <label class="mdblblradio">
                <span class="checklbl">EDUCATION (for Tuition Fee only; can be availed up to twice a year per employee only)</span> 
                <input :disabled="true" type="radio" v-model="loantype" value="2" name="loantype"> 
                <span class="checkmark"></span>
                </label>
                <div class="col-md-12 blockindent">
                        <div><em>Requirements</em></div>
                        <ul>
                            <li>School Assessment / Statement of Account</li>
                            <li>Official Receipt (to be submitted 3 days from release of cash/check)</li>
                        </ul>
                </div>
                <div class="clearfix"></div>
            </div>
            <div>
                <label class="mdblblradio">
                <span class="checklbl">FAMILY EMERGENCY (hospitalization and other immediate family emergencies only)</span> 
                <input :disabled="true" type="radio" v-model="loantype" value="3" name="loantype" @blur="MDBINPUT"> 
                <span class="checkmark"></span>
                </label>
                <div class="col-md-12 blockindent">
                        <div><em>Requirements</em></div>
                        <ul>
                            <li>Any valid proof</li>
                            <li>Official Receipt (to be submitted 3 days from release of cash/check)</li>
                        </ul>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <h5></h5>
                </div>
                <div class="col-md-12">
                        <div class="mdb-form-field form-group-limitx">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" v-model="specialsituation" name="situation" >
                                <label class="form-field__label">Please specify situation...</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('situation') }}</span>
                        </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div>
                <label class="mdblblradio">
                <span class="checklbl">REPAIR OR INSTALLATION OF HOUSE (caused by natural calamities: flood, earthquake, typhoon, fire, etc.</span> 
                <input :disabled="true" type="radio" v-model="loantype" value="4" name="loantype"> 
                <span class="checkmark"></span>
                </label>
                <div class="col-md-12 blockindent">
                        <div><em>Requirements</em></div>
                        <ul>
                            <li>Cost Breakdown (Labor & materials)</li>
                            <li>Pictures before and after repair</li>
                            <li>Summary of Expenses (Labor & materials)</li>
                            <li>Official Receipt (to be submitted 3 days from release of cash/check)</li>
                        </ul>
                </div>
                <div class="clearfix"></div>
            </div>
            <div>
                <label class="mdblblradio">
                <span class="checklbl">PURCHASE OF MOTORCYCLE & OTHER VEHICLE (can be availed one time per employee; employee must bear the name of the receipt)</span> 
                <input :disabled="true" type="radio" v-model="loantype" value="5" name="loantype"> 
                <span class="checkmark"></span>
                </label>
                <div class="col-md-12 blockindent">
                        <div><em>Requirements</em></div>
                        <ul>
                            <li>Cost / Quotation from store or supplier</li>
                            <li>Pictures before and after repair</li>
                            <li>Official Receipt (to be submitted 3 days from release of cash or check)</li>
                        </ul>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <h5></h5>
                </div>
                <div class="col-md-6">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" v-model="paymentperiod" name="payment-period">
                                <label class="form-field__label">Suggested Payment per Period</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('payment-period') }}</span>
                        </div>
                </div>
                <div class="col-md-6">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" v-model="incentives" name="incentives">
                                <label class="form-field__label">Incentives</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('incentives') }}</span>
                        </div>
                </div>
                <div class="clearfix"></div>
            </div>
            </div>
    
            <div class="col-md-12">
                <div class="group">
                    <label class="mdblbl">
                    <span class="checklbl form-note">I hereby agree to the guidelines and conditions set in this application form and allows North Trend Marketing Corporation to deduct from my salary the corresponding amount as payment for my applied loan</span> 
                    <input :disabled="true" type="checkbox" v-model="eula" value="true"> 
                    <span class="mdbcheckmark"></span>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>FOR APPROVER</em></h5>
            </div>
            <!-- status is rejected or approve -->
            <div v-if="true">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="true" class="form-field__textarea" cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
                                <label class="form-field__label">Remarks</label>
                                <div class="form-field__bar"></div>
                            </div>
                    </div>
                </div>
                <div class="col-lg-12" v-if="true">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="approvedby" name="branch" readonly="true">
                            <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Approved/Rejected By' }}</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="clearfix"></div>
            <div class="col-lg-12" v-if="!true">
                    <p class="text-info">
                        LIST OF APPROVERS: 
                    </p>
                    <div class="clearfix"></div>
                    <span class="approverlist alert-info" v-for="(approver, key) in $parent.approvers" :key="key">{{approver.approvers}}</span>
                    <br><br>
            </div> -->
            <div class="clearfix"></div>
            <div class="modal-footer">
                <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addLoan" :disabled="isDisable || !isFormValid || !eula" v-if="submitBtn">
                <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateLoan" :disabled="isDisable || !isFormValid || !eula" v-if="updateDeleteBtn">
                <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteLoan" :disabled="isDisable" v-if="updateDeleteBtn">
                <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionLoan(1)" v-if="approveRejecBtn">
                <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionLoan(2)" v-if="approveRejecBtn">
                <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionLoan(0)" v-if="cancelBtn"> -->
            </div>
    </form>

    </div>
</template>
<script>
let loantype = [
        'Medical', 'Education', 'Family Emergency', 
        'Repair or Installation', 'Purchase Of Motorcycle & Other Vehicle'
    ];
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
			isDisable: false,
			datefiled: moment(new Date()).format('YYYY-MM-DD'),
            loantype: 1, //1 sickleave, 2birth leave, 3leave without pay, 4breavementleave, 5vacationleave, 6others
            bankname: '',
            bankaccount: '',
            loanamount: '',
            specialsituation: '',
            paymentperiod: '',
            incentives: '',
            loanID: '',
            approvedby: '',
            remarks: '',
            eula: false

		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        addLoan(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                axios.post('api/addLoan', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateLoan(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateLoan', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteLoan(){
            this.isDisable = true;
            axios.get('api/deleteLoan/'+this.loanID).then((response)=>{
                this.$parent.deleteRow(this.loanID);
            }).catch((err)=>{ console.log(err); });
        },
        // ACTIONS FOR Loan I.E APPROVE / REJECT / CANCEL
        requestActionLoan(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformLoan', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'loantype')
                    this.$data[i] = (loantype.indexOf(data[i]) + 1);
                if(i == 'eula')
                    this.$data[i] = true;
            }
            
            this.MDBINPUT();
            $("#reportLoanModal").modal("show");
        },

        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'datestart' && key != 'dateend'){
                    this.$data[key] =  '';
                }
                if(key == 'loantype' || key == 'totaldays')
                {
                    this.$data[key] = 1;
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                
            });
            
            $("#reportLoanModal").modal("hide");
            
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
                return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        submitBtn()
        {
            return !this.loanID && this.true;
        },
        updateDeleteBtn(){
            return this.loanID && this.true && !this.true 
        },
        approveRejecBtn(){
            return this.loanID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.loanID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computeddept_branchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.deptname || this.selected.branchname  : this.userinfo.deptname || this.userinfo.branchname;
        },
        computedContact(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.mobile : this.userinfo.mobile;
        },
        computedAddress(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.address : this.userinfo.address;
        }
    },
    beforeDestroy(){
        bus.$off('setupdateLoan', this.test)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#reportLoanModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateLoan', this.setDataForEdit);

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