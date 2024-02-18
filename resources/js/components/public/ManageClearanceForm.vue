<template>
    <div>
        <form method="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">CLEARANCE FORM</span></h3>
            <div class="col-md-4">
            <div class="mdb-form-field form-group-limit">
                <div class="form-field__control">
                    <input type="text" class="form-field__input" :value="datefiled" name="Date filed" readonly="true">
                    <label class="form-field__label">Date filed</label>
                    <div class="form-field__bar"></div>
                </div>
            </div>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="userinfo.fullname" name="Name" readonly="true">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="userinfo.posname" name="Position" readonly="true">
                        <label class="form-field__label">Position</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="userinfo.deptname" name="Department" readonly="true">
                        <label class="form-field__label">Department</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="userinfo.branchname" name="Branch" readonly="true">
                        <label class="form-field__label">Branch</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group-limit">
                          <Datepicker :disabled="$parent.disabledinput" :value="lastday" @selected="selectLastDay" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                          <label slot="afterDateInput" class="form-field__label">Date Needed</label>
                          <div slot="afterDateInput" class="form-field__bar"></div>
                          <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                          </Datepicker> 		
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>IMMEDIATE SUPERRIOR/HEAD</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>CRO (optional)</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>WAREHOUSE DEPT</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>RMS (optional)</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>AREA MANAGER</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>CREDIT & COLLECTION</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>FINANCE/ACCOUNTING DEPT</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>AUDIT DEPT</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>IT DEPT</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>HR DEPT</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>EVP & CHIEF OPERATIONS OFFICER</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>EVP & CHIEF FINANCE OFFICER</em></h5>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Name">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" name="Status">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="Submit" :disabled="disabledIfNoApprover || isDisable || !isFormValid">
            </div>
        </form>
    </div>
</template>
<script>
export default {
    props: ['userinfo'],
    data() {
		return {
			isDisable: false,
			datefiled: moment(new Date()).format('YYYY-MM-DD'),
			lastday: new Date(moment(new Date()).add(30, 'days')),
			totaldays: 1,
			leavetype: 1, //1 sickleave, 2birth leave, 3leave without pay, 4breavementleave, 5vacationleave, 6others
            reason: '',
            leaveID: '',
            approvedby: '',
            remarks: ''
            
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
            
            }
            
            this.MDBINPUT();
            $("#myModal").modal("show");
        },

        selectLastDay(val){
            this.lastday = moment(val).format('YYYY-MM-DD');
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
                return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        submitBtn()
        {
            return !this.leaveID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.leaveID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput 
        },
        approveRejecBtn(){
            return this.leaveID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.leaveID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
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