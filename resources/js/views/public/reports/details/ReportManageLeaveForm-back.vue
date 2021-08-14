<template>
    <div>

            <form method="post" >
                <h3 class="text-center form-title"><span class="dblUnderlined" >LEAVE APPLICATION FORM</span></h3>
                <div class="col-md-12">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="datefiled" name="datefiled" readonly="true">
                            <label class="form-field__label">Date Filed</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="computedfullname" name="fullname" readonly="true">
                            <label class="form-field__label">Full Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="computedposname" name="position" readonly="true">
                            <label class="form-field__label">Position</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="computedbranchname" name="branch" readonly="true">
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="col-md-12 col-lg-12">
                        <h5 class="form-subtitle"><em>Date/s of Leave</em></h5>
                </div>
                <div class="col-md-4">
                            <div class="form-group-limit">
                                        <Datepicker :disabled="true" :value="datestart" @selected="selectDateStart" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                        <label slot="afterDateInput" class="form-field__label">Date Start</label>
                                        <div slot="afterDateInput" class="form-field__bar"></div>
                                        <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                        </Datepicker>
                            </div>
                </div>
                <div class="col-md-4">
                            <div class="form-group-limit">
                                    <Datepicker :disabled="true" :value="dateend" @selected="selectDateEnd" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                    <label slot="afterDateInput" class="form-field__label">Date End</label>
                                    <div slot="afterDateInput" class="form-field__bar"></div>
                                    <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                    </Datepicker>
                            </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" :value="getDiff" name="branch" readonly="true">
                                <label class="form-field__label">Total (days)</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Type of Leave: (Please check (/) appropriate leave to file)</em></h5>
                </div>
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Sick Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="1" name="radio" checked="checked">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Birthday Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="2" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Leave without pay</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="3" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Bereavement Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="4" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Vacation Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="5" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Descritionary Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="6" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Solo Parent Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="7" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Paternity Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="8" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Others (please specify)</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType" type="radio" v-model="leavetype" value="9" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="true" class="form-field__textarea" cols="4" rows="4" v-validate="'required'" v-model="reason" name="additional-info"></textarea>
                                <label class="form-field__label">Reasons to leave</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
                </div>
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
                                <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Approved/Rejected By' }}</label>
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
                </div>
                <div class="clearfix"></div> -->
                <div class="modal-footer">
                    <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addLeave" :disabled="isDisable || !isFormValid" v-if="submitBtn">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateLeave" :disabled="isDisable || !isFormValid" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteLeave" :disabled="isDisable" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionLeave(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionLeave(2)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionLeave(0)" v-if="cancelBtn"> -->
                </div>
            </form>


    </div>
</template>
<script>
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
			isDisable: false,
			datefiled: moment(new Date()).format('YYYY-MM-DD'),
			datestart: moment(new Date()).format('YYYY-MM-DD'),
			dateend: new Date(moment(new Date()).add(1, 'days')),
			totaldays: 1,
			leavetype: 1, //1 sickleave, 2birth leave, 3leave without pay, 4breavementleave, 5vacationleave, 6others
            reason: '',
            leaveID: '',
            approvedby: '',
            remarks: '',

			// reqstat: 0, //0 pending, 1//approve //2 declined
			// status: 0, //0 open, 1//close

		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        addLeave(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                this.totaldays = this.getDiff;
                let params = this.$data;
                axios.post('api/addleave', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateLeave(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                this.totaldays = this.getDiff;
                let params = this.$data;
                axios.post('api/updateleave', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteLeave(){
            this.isDisable = true;
            axios.get('api/deleteleave/'+this.leaveID).then((response)=>{
                this.$parent.deleteRow(this.leaveID);
            }).catch((err)=>{ console.log(err); });
        },
        // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        requestActionLeave(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformleave', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'leavetype')
                    this.$data[i] = (leavetype.indexOf(data[i]) + 1);

            }

            this.MDBINPUT();
            $("#leaveModal").modal("show");
        },

        selectDateStart(val){
            this.datestart = val;
        },
        selectDateEnd(val){
            this.dateend = val;
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'datestart' && key != 'dateend'){
                    this.$data[key] =  '';
                }
                if(key == 'leavetype' || key == 'totaldays')
                {
                    this.$data[key] = 1;
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }

            });

            $("#leaveModal").modal("hide");

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

        getDiff(){
            this.datestart =  moment(this.datestart).format('YYYY-MM-DD');
            this.dateend = moment(this.dateend).format('YYYY-MM-DD');
            let startDate = moment(this.datestart, 'YYYY-MM-DD');
            let endDate = moment(this.dateend, 'YYYY-MM-DD');
            let totaldays = endDate.diff(startDate, 'days');
            totaldays += 1;
            this.totaldays = totaldays;
            return totaldays;
        },
        isFormValid(){
                return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        submitBtn()
        {
            return !this.leaveID && this.true;
        },
        updateDeleteBtn(){
            return this.leaveID && this.true && !this.true
        },
        approveRejecBtn(){
            return this.leaveID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.leaveID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        }
    },
    beforeDestroy(){
        bus.$off('setupdateLeave', this.test)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#leaveModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateLeave', this.setDataForEdit);

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