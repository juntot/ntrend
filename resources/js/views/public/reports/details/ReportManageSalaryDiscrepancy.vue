<template>
    <div>
        
            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">SALARY DISCREPANCY</span></h3>
                <div class="col-md-12">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="datefiled" readonly="true">
                            <label class="form-field__label">Date Filed</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedID" name="branch" readonly="true" >
                            <label class="form-field__label">ID no</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                            <div class="form-group-limit">
                                        <Datepicker :disabled="true" :value="discrepancydate" @selected="selectDateStart" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                                        <label slot="afterDateInput" class="form-field__label">Discrepancy date</label>
                                        <div slot="afterDateInput" class="form-field__bar"></div>
                                        <span slot="afterDateInput" class="errors">{{ errors.first('disrepancy date') }}</span>
                                        </Datepicker> 		
                            </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedfullname" name="branch" readonly="true" >
                            <label class="form-field__label">Full Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input"  :value="computedposname" name="branch" readonly="true" >
                            <label class="form-field__label">Position</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedbranchname" name="branch" readonly="true" >
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5> 
                    <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="true" class="form-field__textarea" cols="4" rows="4" v-validate="'required'" v-model="reason" name="additional-info"></textarea>
                            <label class="form-field__label">Please specify here...</label>
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
                                    <textarea :disabled="true" class="form-field__textarea" cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
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
                    <span class="approverlist alert-info" v-for="approver in $parent.approvers">{{approver.approvers}}</span>
                    <br><br>
                </div> -->
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addSalaryDiscrepancy" :disabled="isDisable || !isFormValid" v-if="submitBtn">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateSalaryDiscrepancy" :disabled="isDisable || !isFormValid" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteSalaryDiscrepancy" :disabled="isDisable" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionSalaryDiscrepancy(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionSalaryDiscrepancy(2)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionSalaryDiscrepancy(0)" v-if="cancelBtn"> -->
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
            // empID_: '',
            isDisable: false,
            datefiled: moment(new Date()).format('YYYY-MM-DD'),
            discrepancydate: moment(new Date()).format('YYYY-MM-DD'),
            reason: '',
            saldiscID: '',
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
        addSalaryDiscrepancy(){
            if(this.isFormValid){
                this.isDisable = true;
                this.empID_ = this.userinfo.empID;
                this.totalhrs = this.getDiffTime;

                let params = this.$data;
                axios.post('api/addsalarydiscrepancy', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateSalaryDiscrepancy(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                this.totaldays = this.getDiff;
                let params = this.$data;
                axios.post('api/updatesalarydiscrepancy', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteSalaryDiscrepancy(){
            this.isDisable = true;
            axios.get('api/deletesalarydiscrepancy/'+this.saldiscID).then((response)=>{
                this.$parent.deleteRow(this.saldiscID);
            }).catch((err)=>{
                console.log(err);
            });
            
        },
        // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        requestActionSalaryDiscrepancy(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformSalaryDiscrepancy', params).then((response)=>{
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
            $("#salDiscreModal").modal("show");
        },
        selectDateStart(val){
            this.discrepancydate = moment(val).format('YYYY-MM-DD');
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{

                if(key != 'datefiled' && key != 'starttime' && key != 'endtime' && key != 'discrepancydate'){
                    this.$data[key] =  '';
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
            });
            $("#salDiscreModal").modal("hide");
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
        submitBtn()
        {
            return !this.saldiscID && this.true;
        },
        updateDeleteBtn(){
            return this.saldiscID && this.true && !this.true 
        },
        approveRejecBtn(){
            return this.saldiscID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.saldiscID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        },
        computedID(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.empID_  : this.userinfo.empID;
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
        bus.$off('setupdateSalDis', this.test)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#salDiscreModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateSalDis', this.setDataForEdit);

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