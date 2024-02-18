<template>
    <div>

            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">OVERTIME APPLICATION FORM</span></h3>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="datefiled" name="datefiled" readonly="true" >
                            <label class="form-field__label">Date Filed</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                            <div class="form-group-limit">
                                        <Datepicker :disabled="$parent.disabledinput" :value="date_overtime" @selected="selectDateOvertime" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                        <label slot="afterDateInput" class="form-field__label">Date Overtime</label>
                                        <div slot="afterDateInput" class="form-field__bar"></div>
                                        <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                        </Datepicker>
                            </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="overtimeID" name="datefiled" readonly="true" >
                            <label class="form-field__label">Overtime #</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="computedfullname" name="fullname" readonly="readonly" >
                            <label class="form-field__label">Full Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('fullname') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="computedposname" name="position" readonly="readonly" >
                            <label class="form-field__label">Position</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('position') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="computedbranchname" name="branch" readonly="readonly" >
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('branch') }}</span>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    <h5 class="form-subtitle"><em>Time/s of Overtime</em></h5>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control form-field--is-filled">
                            <input :disabled="$parent.disabledinput" type="time" name="starttime" v-model="starttime" v-validate="'is_time|required'" class="form-field__input" >
                            <label class="form-field__label">From</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('starttime') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control form-field--is-filled">
                            <input :disabled="$parent.disabledinput" type="time" name="endtime" v-model="endtime" v-validate="'is_time|required'" class="form-field__input" >
                            <label class="form-field__label">To</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('endtime') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="getDiffTime" name="total(hrs)" readonly="readonly" >
                            <label class="form-field__label">hrs/min</label>
                            <div class="form-field__bar"></div>
                            <span class="errors">{{ errors.first('total(hrs)') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="reason" name="additional-info"></textarea>
                            <label class="form-field__label">Reasons to Overtime</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
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
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="approvedby" name="approvedby" readonly="true">
                                <!-- <label class="form-field__label">Approved/Rejected By</label> -->
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
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addOvertime" :disabled="disabledIfNoApprover || isDisable || !isFormValid" v-if="!overtimeID && $parent.$data.forapprover != 'approval'">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateOvertime" :disabled="isDisable || !isFormValid" v-if="overtimeID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteOvertime" :disabled="isDisable" v-if="overtimeID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionOvertime(1)" v-if="overtimeID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel ">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionOvertime(2)" v-if="overtimeID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionOvertime(0)" v-if="overtimeID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel">
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

        empID_: '',
        datefiled: moment(new Date()).format('MM/DD/YYYY'),
        date_overtime: moment(new Date()).format('MM/DD/YYYY'),
        starttime: moment(new Date()).format('hh:mm'),
        endtime: moment(new Date()).add(30, 'minutes').format('hh:mm'),
        totalhrs: 0,
        reason: '',
        isDisable: false,
        overtimeID:'',
        remarks: '',
        approvedby: '',

		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        selectDateOvertime(val){
            this.date_overtime = moment(val).format('MM/DD/YYYY');
        },
        addOvertime(){
            if(this.isFormValid){
                this.isDisable = true;
                this.empID_ = this.userinfo.empID;
                this.totalhrs = this.getDiffTime;
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addovertime', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateOvertime(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                this.totaldays = this.getDiff;
                let params = this.$data;
                axios.post('api/updateovertime', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteOvertime(){
            this.isDisable = true;
            axios.get('api/deleteovertime/'+this.overtimeID).then((response)=>{
                this.$parent.deleteRow(this.overtimeID);
            }).catch((err)=>{
                console.log(err);
            });

        },
        // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        requestActionOvertime(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformOvertime', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{

                if(key != 'datefiled' && key != 'starttime' && key != 'endtime' && key != 'date_overtime'){
                    this.$data[key] =  '';
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }

                if(key == 'datefile' || key == 'date_overtime'){
                    this.datefiled = moment(new Date()).format('MM/DD/YYYY');
                    this.date_overtime = moment(new Date()).format('MM/DD/YYYY');
                }
            });
            $("#myModal").modal("hide");
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

        getDiffTime(){
            let startTime = moment(this.starttime, "HH:mm:ss");
            let endTime = moment(this.endtime, "HH:mm:ss");
            let totalhrs = endTime.diff(startTime, 'hours')+':'+(endTime.diff(startTime, 'minutes')%60);
            // let totalhrs = moment.duration(endTime.diff(startTime)).as('minutes');
            return totalhrs;
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