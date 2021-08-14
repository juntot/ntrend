<template>
    <div>
        
        <form method="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">INCIDENT REPORT</span></h3>
            <div class="col-md-4">
                <div class="form-group-limit">
                        <Datepicker :disabled="$parent.disabledinput" :value="dateoccured" @selected="selectDateOccured" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                        <label slot="afterDateInput" class="form-field__label">Date Occured</label>
                        <div slot="afterDateInput" class="form-field__bar"></div>
                        </Datepicker> 		
                </div>
            </div>
            <div class="col-md-8">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="incidenttime" v-validate="'required'" name="incidenttime">
                        <label class="form-field__label">Incident Time</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('incidenttime') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="personsinvolve" v-validate="'required'" name="persons involved">
                        <label class="form-field__label">Person/s Involved</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('persons involved') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="designation" v-validate="'required'" name="designation">
                        <label class="form-field__label">Designation</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('designation') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="branch" v-validate="'required'" name="branch">
                        <label class="form-field__label">Branch</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('branch') }}</span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Nature of Incident</em></h5>
            </div>
            <div class="col-lg-12">
                <div class="col-lg-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Inventory Discrepancy</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="1" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Habitual Tardiness</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="2" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Habitual Absences</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="3" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Absence w/o official leave</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="4" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Insubordination</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="5" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Non-compliance to policies/procedures</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="6" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-6">
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Delivery Discrepancy</span>
                        <input :disabled="$parent.disabledinput" type="radio" value="7" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Theft</span>
                        <input :disabled="$parent.disabledinput" type="radio" value="8" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Falsification/Tampering of Documents</span>
                        <input :disabled="$parent.disabledinput" type="radio" value="9" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                </div>
                <div class="col-lg-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Loss/Damage of Company Property</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="10" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Non remittance/short of collections</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="11" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Others</span>
                            <input :disabled="$parent.disabledinput" type="radio" value="12" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Details of the Incident/Concern</em></h5>
            </div>
            <div class="col-md-12">
                <div class="mdb-form-field">
                    <div class="form-field__control mdb-bgcolor">
                        <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="details" name="additional-details"></textarea>
                        <label class="form-field__label">(Complete description and details of the incident specifically how and why the incident occurred.)</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <em>Important Note: All pertinent documents supporting the occurrence of the incident should be attached in this form.</em>
                    <h6><span class="errors">{{ errors.first('additional-details') }}</span></h6>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Initial Action Taken</em></h5>
            </div>
            <div class="col-md-12">
                <div class="mdb-form-field">
                    <div class="form-field__control mdb-bgcolor">
                        <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="actiontaken" name="additional-info"></textarea>
                        <label class="form-field__label">Add details here</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="computedfullname" name="reported-by">
                        <label class="form-field__label">Reported by</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>FOR APPROVERS</em></h5>
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
                <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addIncidentReport" :disabled="isDisable || !isFormValid" v-if="!incidentID && $parent.$data.forapprover != 'approval'">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateIncidentReport" :disabled="isDisable || !isFormValid" v-if="incidentID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteIncidentReport" :disabled="isDisable" v-if="incidentID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionIncidentReport(1)" v-if="incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel ">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionIncidentReport(2)" v-if="incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel">
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionIncidentReport(0)" v-if="incidentID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel"> -->
            </div>
        </form>
        
    </div>
</template>
<script>
let incidenttype = [
        'Inventory Discrepancy', 'Habitual Tardiness', 'Habitual Absences', 'TYREPLUSAbsence w/o official leave', 'Insubordination',
        'Non-compliance to policies/procedures', 'Delivery Discrepancy', 'Theft', 'Falsification/Tampering of Documents', 'Loss/Damage of Company Property',
        'Non remittance/short of collections', 'Others'
    ];
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
			isDisable: false,
			datefiled: moment(new Date()).format('YYYY-MM-DD'),
			dateoccured: moment(new Date()).format('YYYY-MM-DD'),
            incidenttime: moment(new Date()).format('HH:mm'),
            personsinvolve: '',
            designation: '',
            branch: '',
			incidenttype: 1, //1 sickleave, 2birth leave, 3leave without pay, 4breavementleave, 5vacationleave, 6others
            incidentID: '',
            approvedby: '',
            details: '',
            actiontaken: '',
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
        addIncidentReport(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addIncidentReport', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateIncidentReport(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                this.totaldays = this.getDiff;
                let params = this.$data;
                axios.post('api/updateIncidentReport', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteIncidentReport(){
            this.isDisable = true;
            axios.get('api/deleteIncidentReport/'+this.incidentID).then((response)=>{
                this.$parent.deleteRow(this.incidentID);
            }).catch((err)=>{ console.log(err); });
        },
        // ACTIONS FOR IncidentReport I.E APPROVE / REJECT / CANCEL
        requestActionIncidentReport(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformIncidentReport', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'incidenttype')
                    this.$data[i] = (incidenttype.indexOf(data[i]) + 1);
            
            }
            
            this.MDBINPUT();
            $("#myModal").modal("show");
        },

        selectDateOccured(val){
            this.dateoccured = moment(val).format('YYYY-MM-DD');
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'dateoccured' && key != 'incidenttime'){
                    this.$data[key] =  '';
                }
                if(key == 'incidenttype' || key == 'totaldays')
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

        isFormValid(){
                return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        submitBtn()
        {
            return !this.supID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.supID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput 
        },
        approveRejecBtn(){
            return this.supID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.supID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
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