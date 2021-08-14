<template>
    <div>

            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">OVERTIME APPLICATION FORM</span></h3>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="datefiled" name="datefiled" readonly="true" >
                            <label class="form-field__label">Date Filed</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                            <div class="form-group-limit">
                                        <Datepicker :disabled="true" :value="date_overtime" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                        <label slot="afterDateInput" class="form-field__label">Date Overtime</label>
                                        <div slot="afterDateInput" class="form-field__bar"></div>
                                        <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                        </Datepicker>
                            </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="computedfullname" name="fullname" readonly="readonly" >
                            <label class="form-field__label">Full Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('fullname') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="computedposname" name="position" readonly="readonly" >
                            <label class="form-field__label">Position</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('position') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="computedbranchname" name="branch" readonly="readonly" >
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('branch') }}</span>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    <h5 class="form-subtitle"><em>Time/s of Undertime</em></h5>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control form-field--is-filled">
                            <input :disabled="true" type="time" name="starttime" v-model="starttime" v-validate="'is_time|required'" class="form-field__input" >
                            <label class="form-field__label">From</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('starttime') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control form-field--is-filled">
                            <input :disabled="true" type="time" name="endtime" v-model="endtime" v-validate="'is_time|required'" class="form-field__input" >
                            <label class="form-field__label">To</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('endtime') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" :value="getDiffTime" name="total(hrs)" readonly="readonly" >
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
                            <textarea :disabled="true" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="reason" name="additional-info"></textarea>
                            <label class="form-field__label">Reasons to undertime</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
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
                                <input :disabled="true" type="text" class="form-field__input" :value="approvedby" name="approvedby" readonly="true">
                                <!-- <label class="form-field__label">Approved/Rejected By</label> -->
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
                    <span class="approverlist alert-info" v-for="approver in $parent.approvers">{{approver.approvers}}</span>
                    <br><br>
                </div> -->
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addUnderTime" :disabled="isDisable || !isFormValid" v-if="!undertimeID && true">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateUnderTime" :disabled="isDisable || !isFormValid" v-if="undertimeID && true && !true">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteUnderTime" :disabled="isDisable" v-if="undertimeID && true && !true ">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionUnderTime(1)" v-if="undertimeID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel ">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionUnderTime(2)" v-if="undertimeID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionUnderTime(0)" v-if="undertimeID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel"> -->
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
        datefiled: moment(new Date()).format('YYYY-MM-DD'),
        date_overtime: moment(new Date()).format('YYYY-MM-DD'),
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
        addUnderTime(){
            if(this.isFormValid){
                this.isDisable = true;
                this.empID_ = this.userinfo.empID;
                this.totalhrs = this.getDiffTime;
                let params = this.$data;
                axios.post('api/addundertime', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateUnderTime(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                this.totaldays = this.getDiff;
                let params = this.$data;
                axios.post('api/updateundertime', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteUnderTime(){
            this.isDisable = true;
            axios.get('api/deleteundertime/'+this.undertimeID).then((response)=>{
                this.$parent.deleteRow(this.undertimeID);
            }).catch((err)=>{
                console.log(err);
            });

        },
        // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        requestActionUnderTime(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformUndertime', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{

                if(key != 'datefiled' && key != 'starttime' && key != 'endtime'){
                    this.$data[key] =  '';
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
            });
            $("#overtimeModal").modal("hide");
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
            $("#overtimeModal").modal("show");
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
        bus.$off('setupdateOvertime', this.setDataForEdit)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#overtimeModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateOvertime', this.setDataForEdit);

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