<template>
    <div>

        <form method="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">WORK REQUEST FORM (FOR IT CONCERNS)</span></h3>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="datefiled" name="Date requested" readonly="true">
                            <label class="form-field__label">Date requested</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                                        <Datepicker :disabled="$parent.disabledinput" :value="dateneed" @selected="selectDateNeed" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                        <label slot="afterDateInput" class="form-field__label">Date Needed</label>
                                        <div slot="afterDateInput" class="form-field__bar"></div>
                                        <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                        </Datepicker>
                        </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedfullname +' '+userinfo.mname" name="Name" :readonly="true">
                            <label class="form-field__label">Full Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedcontact" name="Contact num" :readonly="true">
                            <label class="form-field__label">Contact num</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedposname" name="Position" :readonly="true">
                            <label class="form-field__label">Position</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computeddeptname" name="Department" :readonly="true">
                            <label class="form-field__label">Department</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedposname" name="Position" :readonly="true">
                            <label class="form-field__label">Urgency</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedposname" name="Position" :readonly="true">
                            <label class="form-field__label">Urgency</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computeddeptname" name="Department" :readonly="true">
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Type of Request</em></h5>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">System Access (SAP, HRIS etc.)
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">System Access (SAP, HRIS etc.)</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="1" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Borrow item
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Borrow item</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="2" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">System Autorization
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">System Autorization</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="3" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">RDP Access
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">RDP Access</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="4" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Password Reset
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Password Reset</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="5" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Internet Access
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Internet Access</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="6" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Email Setup
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Email Setup</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="7" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Install APPS (Spark, Skype etc.)
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Install APPS (Spark, Skype etc.)</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="8" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Setup Workstation
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Setup Workstation</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="9" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Setup Printer
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Setup Printer</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="10" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Setup Telephone
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Setup Telephone</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="11" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Cleaning / Maintenance
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Cleaning / Maintenance</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="12" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Repair
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Repair</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="13" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Format
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Format</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="14" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">System Report
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">System Report</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="15" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">System Layout
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">System Layout</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="16" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">GPS Report
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">GPS Report</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="17" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Conversation History
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Conversation History</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="18" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">CCTV Report
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">CCTV Report</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="19" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">File & Data Recovery
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">File & Data Recovery</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="20" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl normal-weight">
                                <input type="checkbox" value="1">Assistance
                                <span class="mdbcheckmark"></span>
                            </label>
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Assistance</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="20" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="reason" name="request-details"></textarea>
                                <label class="form-field__label">Request Details</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('request-details') }}</span></h6>
                    </div>
                </div>
                <div class="col-lg-12">
                    <label for="workrequest_files" class="workrequestfile_label">
                        <i class="far fa-images"></i> &nbsp;&nbsp;Attach Images
                        <input type="file" id="workrequest_files" class="hide" multiple>
                    </label>

                </div>
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
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addWorkRequest" :disabled="isDisable || !isFormValid" v-if="!workID && $parent.$data.forapprover != 'approval'">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateWorkRequest" :disabled="isDisable || !isFormValid" v-if="workID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteWorkRequest" :disabled="isDisable" v-if="workID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionWorkRequest(1)" v-if="workID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel ">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionWorkRequest(2)" v-if="workID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel">
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionWorkRequest(0)" v-if="workID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel"> -->
                </div>
        </form>

    </div>
</template>
<script>
let worktype = ['System Access (SAP, HRIS etc.)',
                'Borrow item',
                'System Autorization',
                'RDP Access',
                'Password Reset',
                'Internet Access',
                'Email Setup',
                'Install Apps(Spark, Skype, etc.)',
                'Setup Workstation',
                'Setup Printer'
                ,'Setup Telephone',
                'Cleaning / Maintenance',
                'Repair',
                'Format',
                'System Report',
                'System Layout',
                'GPS Report',
                'Conversation History',
                'CCTV Report',
                'File & Data Recovery'];

let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {

        empID_: '',
        datefiled: moment(new Date()).format('MM/DD/YYYY'),
		dateneed: moment(moment(new Date()).add(1, 'days')).format('MM/DD/YYYY'),
		worktype: 1, //1 sickleave, 2birth leave, 3leave without pay, 4breavementleave, 5vacationleave, 6others
        reason: '',
        isDisable: false,
        workID:'',
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
        addWorkRequest(){
            if(this.isFormValid){
                this.isDisable = true;
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addworkrequest', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateWorkRequest(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateworkrequest', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteWorkRequest(){
            this.isDisable = true;
            axios.get('api/deleteworkrequest/'+this.workID).then((response)=>{
                this.$parent.deleteRow(this.workID);
            }).catch((err)=>{
                console.log(err);
            });

        },
         // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        requestActionWorkRequest(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformWorkRequest', params).then((response)=>{
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
            $("#myModal").modal("hide");
        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'worktype')
                this.$data[i] = (worktype.indexOf(data[i]) + 1);

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
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computeddeptname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.deptname  : this.userinfo.deptname;
        },
        computedcontact(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.mobile  : this.userinfo.mobile;
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