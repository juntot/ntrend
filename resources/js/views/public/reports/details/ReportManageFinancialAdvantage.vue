<template>
    <div>

        <form action="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">FINANCIAL ADVANCE REPORT</span></h3>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="datefiled" name="datefiled" readonly="true">
                            <label class="form-field__label">Date Filed</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedfullname" name="requestor" readonly="true">
                            <label class="form-field__label">Requestor</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="faID" name="faID" readonly="true">
                            <label class="form-field__label">FA#</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedposname" name="position" readonly="true">
                            <label class="form-field__label">Position</label>
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
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computeddeptname" name="department" readonly="true">
                            <label class="form-field__label">Department</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Financial Advance Details</em></h5>
            </div>
            <div class="col-md-4">
                <div class="form-group-limit">
                        <Datepicker  :value="inclusiveDateFrom" :disabled="true" @selected="selectDateFrom" wrapper-class="mdb-form-field" input-class="form-field__input datePicker"
                        :typeable="false" :format="'MM/dd/yyyy'">
                        <label slot="afterDateInput" class="form-field__label">Includsive Date From</label>
                        <div slot="afterDateInput" class="form-field__bar"></div>
                        <span slot="afterDateInput" class="errors">{{ errors.first('date from') }}</span>
                        </Datepicker>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-limit">
                        <Datepicker  :value="inclusiveDateTo" :disabled="true" @selected="selectDateTo" wrapper-class="mdb-form-field" input-class="form-field__input datePicker"
                        :typeable="false" :format="'MM/dd/yyyy'">
                        <label slot="afterDateInput" class="form-field__label">Inclusive Date To</label>
                        <div slot="afterDateInput" class="form-field__bar"></div>
                        <span slot="afterDateInput" class="errors">{{ errors.first('date to') }}</span>
                        </Datepicker>
                </div>
            </div>
            <div class="col-md-2">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <!-- max of 9 characters -->
                            <input :disabled="true" type="text" v-model="amount" class="form-field__input"  name="amount">
                            <label class="form-field__label">Amount</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-10">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" v-model="amountwords" class="form-field__input"  name="amount-word">
                            <label class="form-field__label">Amount in word</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <div class="mdb-form-field">
                    <div class="form-field__control mdb-bgcolor">
                        <textarea :disabled="true" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="reason" name="additional-info"></textarea>
                        <label class="form-field__label">Reason</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Mode of Release</em></h5>
            </div>
            <div class="col-md-12">
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">RF ONLINE</span>
                                <input :disabled="true" type="radio" v-model="modtype"  value="1" name="radio">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">CHECK</span>
                                <input :disabled="true" type="radio" v-model="modtype" value="2" name="radio" >
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">CASH</span>
                                <input :disabled="true" type="radio" v-model="modtype" value="3" name="radio">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">PETTY CASH</span>
                                <input :disabled="true" type="radio" v-model="modtype" value="4" name="radio">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
            </div>
            <div class="cearflix"></div>
            <br><br><br>
            <div class="col-md-12">
                <div class="form-group-limit" style="margin: 0 auto;">
                        <Datepicker :value="liqDate" :disabled="true" @selected="selectDateLiq" wrapper-class="mdb-form-field" input-class="form-field__input datePicker"
                        :typeable="false" :format="'MM/dd/yyyy'">
                        <label slot="afterDateInput" class="form-field__label">Date of liquidation (after 3 working days)</label>
                        <div slot="afterDateInput" class="form-field__bar"></div>
                        <span slot="afterDateInput" class="errors">{{ errors.first('date to') }}</span>
                        </Datepicker>
                </div>
            </div>
            <br><br>
            <div class="clearfix"></div>
            <div class="col-lg-12 text-center">
                <p style="line-height: 2.1">
                <span class="font-weight-bold">Authority to Deduct</span><br>
                <span class="font-weight-bold">This is to autorize the payroll in-charge of</span>
                Trend Group of Companies <br>
                <span class="font-weight-bold">to deduct from my salary to total amount as specified above</span> 
                if not liquidated <br> 
                within three (3) working days after the activity is completed.
                </p>
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
                                    <textarea :disabled="true" class="form-field__textarea"  cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
                                    <label class="form-field__label">Remarks</label>
                                    <div class="form-field__bar"></div>
                                </div>
                        </div>
                    </div>
                    <div class="col-lg-12" v-if="true">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" :value="approvedby" name="branch" readonly="true">
                                <label class="form-field__label">Approved/Rejected By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- <div class="col-lg-12" v-if="!true">
                <p class="text-info">
                    LIST OF APPROVERS: 
                </p>
                <div class="clearfix"></div>
                <span class="approverlist alert-info" v-for="(approver, key) in $parent.approvers" :key="key">{{approver.approvers}}</span>
                <br><br>
            </div> -->
            <div class="clearfix"></div>
                <div class="modal-footer">
                    <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addFinancialAdvantage" :disabled="isDisable || !isFormValid" v-if="!faID && true">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateFinancialAdvantage" :disabled="isDisable || !isFormValid" v-if="faID && true && !true">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteFinancialAdvantage" :disabled="isDisable" v-if="faID && true && !true ">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionFinancialAdvantage(1)" v-if="faID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel ">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionFinancialAdvantage(2)" v-if="faID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionFinancialAdvantage(0)" v-if="faID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel"> -->
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

        // empID_: '',
        datefiled: moment(new Date()).format('YYYY-MM-DD'),
		modtype: 1, 
        reason: '',
        amount: '',
        amountwords: '',
        isDisable: false,
        faID: '',
        approvedby: '',
        remarks: '',
        inclusiveDateFrom: moment(new Date()).format('MM/DD/YYYY'),
        inclusiveDateTo: moment(new Date()).format('MM/DD/YYYY'),
        liqDate: moment(new Date()).format('MM/DD/YYYY'),
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        selectDateFrom(val){
            this.inclusiveDateFrom = moment(val).format('MM/DD/YYYY');
        },
        selectDateTo(val){
            this.inclusiveDateTo = moment(val).format('MM/DD/YYYY');
        },
        selectDateLiq(val){
            this.liqDate = moment(val).format('MM/DD/YYYY');
        },
        addFinancialAdvantage(){
            if(this.isFormValid){
                this.isDisable = true;
                let params = this.$data;
                axios.post('api/addFinancialAdvantage', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateFinancialAdvantage(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateFinancialAdvantage', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteFinancialAdvantage(){
            this.isDisable = true;
            axios.get('api/deleteFinancialAdvantage/'+this.faID) .then((response)=>{
                this.$parent.deleteRow(this.faID) ;
            }).catch((err)=>{
                console.log(err);
            });
            
        },
        // ACTIONS FOR FinancialAdvantage I.E APPROVE / REJECT / CANCEL
        requestActionFinancialAdvantage(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformFinancialAdvantage', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },
        selectDateNeed(val){
            this.dateneed = moment(val).format('YYYY-MM-DD');
        },

        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{

                if(key != 'datefiled' && key != 'dateneed' && key != 'worktype'){
                    this.$data[key] =  '';
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
            });
            $("#reportFAModal").modal("hide");
        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'modtype')
                this.$data[i] = (modtype.indexOf(data[i]) + 1);
                
            }
            
            this.MDBINPUT();
            $("#reportFAModal").modal("show");
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
        bus.$off('setupdateFA', this.test)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#reportFAModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateFA', this.setDataForEdit);

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