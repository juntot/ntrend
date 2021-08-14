<template>
    <div>

        <form method="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">WORK REQUEST FORM</span></h3>
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
                    <div class="mdb-form-field form-group-limitx form-field--is-filled">
                        <div class="form-field__control">
                            <select id="formStat" :disabled="true" v-model="status" class="form-field__input" aria-required="true" aria-invalid="false">
                                <option value="0">Pending</option>
                                <option value="1">Approved</option>
                                <option value="2">Rejected</option>
                                <option value="3">Executed</option>
                                <option value="4">Confirmed</option>
                            </select>
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors"></span>
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
                <div class="clearfix"></div>
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
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedBranch" name="Name" :readonly="true">
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx form-field--is-filled">
                        <div class="form-field__control">
                            <select id="priority" :disabled="$parent.disabledinput" name="urgency" v-model="urgency" class="form-field__input"
                                aria-required="true" aria-invalid="false">
                                <option value="Highest">Highest (1-2 Hours)</option>
                                <option value="High">High (1-2 Days)</option>
                                <option value="Medium">Medium (3-6 Days)</option>
                                <option value="Low">Low (1-2 Weeks)</option>
                                <option value="Lowest">Lowest (3-4 Weeks)</option>
                            </select>
                            <label
                            :class="
                                urgency=='Highest'? 'redwine form-field__label':
                                urgency=='High'? 'munsell form-field__label':
                                urgency=='Medium'? 'redorange form-field__label':
                                urgency=='Low'? 'darkgrey form-field__label':
                                'lightgrey form-field__label'
                            "
                            >Urgency</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors"></span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <label class="mdblblradio">
                        <span class="checklbl">Temporary</span>
                        <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="1" name="radio" >
                        <span class="checkmark"></span>
                    </label>
                    <label class="mdblblradio">
                        <span class="checklbl">Permanent</span>
                        <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="2" name="radio" >
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="clearfix"></div>
                <div v-show="worktype==1">
                    <div class="col-md-4">
                            <div class="mdb-form-field form-group-limit">
                                            <Datepicker :disabled="$parent.disabledinput" :value="date_from" @selected="selectDateFrom" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                            <label slot="afterDateInput" class="form-field__label">Date From</label>
                                            <div slot="afterDateInput" class="form-field__bar"></div>
                                            <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                            </Datepicker>
                            </div>
                    </div>
                    <div class="col-md-4">
                            <div class="mdb-form-field form-group-limit">
                                            <Datepicker :disabled="$parent.disabledinput" :value="date_to" @selected="selectDateTo" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                            <label slot="afterDateInput" class="form-field__label">Date To</label>
                                            <div slot="afterDateInput" class="form-field__bar"></div>
                                            <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                            </Datepicker>
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
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">System Access (SAP, HRIS etc.)</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="1" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">System Access
                            <input :disabled="$parent.disabledinput" type="checkbox" value="System Access" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Borrow item</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="2" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Borrow item
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Borrow item" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">System Autorization</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="3" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">System Autorization
                            <input :disabled="$parent.disabledinput" type="checkbox" value="System Autorization" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">RDP Access</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="4" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">RDP Access
                            <input :disabled="$parent.disabledinput" type="checkbox" value="RDP Access" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Password Reset</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="5" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Password Reset
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Password Reset" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Internet Access</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="6" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Internet Access
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Internet Access" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Email Setup</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="7" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Email Setup
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Email Setup" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Install APPS (Spark, Skype etc.)</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="8" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Install APPS (Spark, Skype etc.)
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Install APPS (Spark, Skype etc.)" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Setup Workstation</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="9" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Setup Workstation
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Setup Workstation" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Setup Printer</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="10" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Setup Printer
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Setup Printer" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Setup Telephone</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="11" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Setup Telephone
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Setup Telephone" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Cleaning / Maintenance</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="12" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Cleaning / Maintenance
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Cleaning / Maintenance" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Repair</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="13" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Repair
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Repair" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Format</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="14" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Format
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Format" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">System Report</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="15" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">System Report
                            <input :disabled="$parent.disabledinput" type="checkbox" value="System Report" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">System Layout</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="16" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">System Layout
                            <input :disabled="$parent.disabledinput" type="checkbox" value="System Layout" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">GPS Report</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="17" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">GPS Report
                            <input :disabled="$parent.disabledinput" type="checkbox" value="GPS Report" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">Conversation History</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="18" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">Conversation History
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Conversation History" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">CCTV Report</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="19" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">CCTV Report
                            <input :disabled="$parent.disabledinput" type="checkbox" value="CCTV Report" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <!-- <label class="mdblblradio">
                            <span class="checklbl">File & Data Recovery</span>
                            <input :disabled="$parent.disabledinput" type="radio" v-model="worktype" value="20" name="radio" >
                            <span class="checkmark"></span>
                            </label> -->
                            <label class="mdblbl inline-blocklbl mdblblradio">File & Data Recovery
                            <input :disabled="$parent.disabledinput" type="checkbox" value="File & Data Recovery" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl mdblblradio">Assistance
                            <input :disabled="$parent.disabledinput" type="checkbox" value="Assistance" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
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
                    <!-- <img id="work-attachments" :src="'public/images/priemer_jacket.jpg'" alt="Avatar"> -->
                    <label for="work__attachment" id="work-attachments">
                        <span class="profile-cam-icon" style="position: relative;"><i class="fas fa-camera"></i></span>
                         <span style="position: relative; left: 10px; bottom: 3px;">
                             <span v-show="!work_attachment" id="file-label">Attach Image</span>
                             <a id="file-label2" :href="'storage/app/'+work_attachment" target="_blank" v-show="work_attachment">image - {{work_attachment | filter_attachment}}</a>
                        </span>
                    </label>
                    <input id="work__attachment" type="file" @change="attachFile" style="display:none;">
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
                    <!-- Digital Signatures -->
                    <div class="col-md-4" v-if="$parent.disabledinput">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="computedfullname" name="branch" readonly="true">
                                <label class="form-field__label">Requested By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="$parent.disabledinput">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="selected.approvedby_ || ''" name="branch" readonly="true">
                                <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Approved/Rejected By' }}</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="$parent.disabledinput">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="selected.executedby_ || ''" name="branch" readonly="true">
                                <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Executed By' }}</label>
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


                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addWorkRequest" :disabled="isDisable || !isFormValid || !request_type.length > 0" v-if="!workID && $parent.$data.forapprover != 'approval'">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateWorkRequest" :disabled="isDisable || !isFormValid" v-if="workID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteWorkRequest" :disabled="isDisable" v-if="workID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionWorkRequest(1)" v-if="workID && $parent.$data.forapprover == 'approval' && selected.status == 0">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionWorkRequest(2)" v-if="workID && $parent.$data.forapprover == 'approval' && selected.status == 0">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionWorkRequest(0)" v-if="workID && $parent.$data.forapprover == 'approval' && (selected.status == 1  || selected.status == 2) && !is_IT_DEPT">
                    <!-- confirm -->
                    <input type="submit" class="btn btn-primary" value="Executed" @click.prevent="requestActionWorkRequest(3)" :disabled="isDisable || !isFormValid" v-if="($parent.$data.forapprover == 'approval' && selected.status == 1) && is_IT_DEPT">
                    <!-- IT rejection -->
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionWorkRequest(2)" v-if="($parent.$data.forapprover == 'approval' && selected.status == 1) && is_IT_DEPT">
                    <!-- <input type="submit" class="btn btn-primary" value="Back to Pending" @click.prevent="requestActionWorkRequest(5)" :disabled="isDisable || !isFormValid" v-if="$parent.$data.forapprover != 'approval' && $parent.disabledinput && selected.status == 3"> -->
                    <input type="submit" class="btn btn-primary" value="Confirm" @click.prevent="requestActionWorkRequest(4)" :disabled="isDisable || !isFormValid" v-if="$parent.$data.forapprover != 'approval' && $parent.disabledinput && selected.status == 3">

                </div>
        </form>

    </div>
</template>
<script>
// let worktype = ['System Access (SAP, HRIS etc.)',
//                 'Borrow item',
//                 'System Autorization',
//                 'RDP Access',
//                 'Password Reset',
//                 'Internet Access',
//                 'Email Setup',
//                 'Install Apps(Spark, Skype, etc.)',
//                 'Setup Workstation',
//                 'Setup Printer'
//                 ,'Setup Telephone',
//                 'Cleaning / Maintenance',
//                 'Repair',
//                 'Format',
//                 'System Report',
//                 'System Layout',
//                 'GPS Report',
//                 'Conversation History',
//                 'CCTV Report',
//                 'File & Data Recovery'];

let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
        status: 0,
        empID_: '',
        work_attachment: '',
        datefiled: moment(new Date()).format('MM/DD/YYYY'),
		dateneed: moment(moment(new Date()).add(1, 'days')).format('MM/DD/YYYY'),
        date_from: moment(new Date()).format('MM/DD/YYYY'),
        date_to: moment(new Date()).format('MM/DD/YYYY'),
		worktype: 1, //1 sickleave, 2birth leave, 3leave without pay, 4breavementleave, 5vacationleave, 6others
        reason: '',
        isDisable: false,
        workID:'',
        approvedby: '',
        remarks: '',
        urgency: 'Medium',
        request_type: [],

		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }

    },
    filters:{
        filter_attachment(val){
            if(val && typeof val == 'string'){
                return  (val.split("/")[3]);
            }

            return val;
        },
    },
    methods:{
        attachFile(){
            if(document.querySelector('input#work__attachment[type=file]').value != ''){
			    let file    = document.querySelector('input#work__attachment[type=file]').files[0]; //sames as here

			    let type = file['type'];
			    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid Image type');
				      return false;
				}
				if(file.size >= 2000000)
				{
					alert('Filesize exceed 2MB');
				    return false;
				}

				if (file) {
                    document.querySelector('#file-label').innerHTML = file.name;
                    document.querySelector('#file-label2').innerHTML = file.name;
				    // let reader  = new FileReader();
				    //       reader.onloadend = function () {
				    //       preview.src = reader.result;
				    //  }
				    //  reader.readAsDataURL(file);
				     return file;
				}
			}else{

				return null;
			}
        },
        selectDateFrom(val){
            this.date_from = moment(val).format('YYYY-MM-DD');
        },
        selectDateTo(val){
            this.date_to = moment(val).format('YYYY-MM-DD');
        },
        addWorkRequest(){
            if(this.isFormValid){
                this.isDisable = true;
                let params = this.$data;
                // params['reciever_emails'] = this.$parent.reciever_emails;


                let file = this.attachFile();
				const formData = new FormData();

                if(file)
				formData.append('attachment[]', file );
                let emails = this.$parent.reciever_emails;
                emails.forEach(email => {
                    formData.append('reciever_emails[]', email);
                });

                Object.keys(params).forEach((keys)=>{
                    // if(keys != 'positions' && keys != 'deptnames' && keys != 'branchnames'
                    //     && keys != 'compnames' && keys != 'selected')
					formData.append( keys,  params[keys]);
                });


                axios.post('api/addworkrequest', formData).then((response)=>{
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

                let file = this.attachFile();
				const formData = new FormData();

                if(file)
				formData.append('attachment[]', file );

                Object.keys(params).forEach((keys)=>{
                    // if(keys != 'positions' && keys != 'deptnames' && keys != 'branchnames'
                    //     && keys != 'compnames' && keys != 'selected')
					formData.append( keys, params[keys]);
                });

                axios.post('api/updateworkrequest', formData).then((response)=>{
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

            params['status'] = parseInt(status);
            params['approvedby'] = this.$root.$data.userinfo.empID;
            if(status <=3){
                params['empID_'] = this.$parent.$data.empID_;
                params['email'] = this.$parent.$data.selected.email;
            }else{
                // this is for work request not approver work request
                // work around status 5 for back to pending
                
                params['empID_'] = this.$parent.selected.empID_;
                params['reciever_emails'] = this.$parent.$data.reciever_emails;
            }
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

                if(key != 'datefiled' && key != 'dateneed' && key != 'worktype'
                && key != 'urgency' && key != 'date_from' && key != 'date_to'
                && key != 'request_type'
                ){
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
                if(i == 'request_type' && typeof data[i] == 'string')
                this.$data[i] = (data[i]).split(",");


                // if(i == 'worktype')
                // this.$data[i] = (worktype.indexOf(data[i]) + 1);

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
        },
        computedBranch(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        },

        is_IT_DEPT(){
            if(this.userinfo.hasOwnProperty('deptname')){
                let dept = (this.userinfo.deptname).toLowerCase();
                return (dept).includes('it dept') || (dept).includes('it department') ||
                   (dept).includes('it') || (dept).includes('i.t') ||
                   (dept).includes('i.t department') ||
                   (dept).includes('information techonology') ||
                   (dept).includes('info tech');;
            }
            return false;
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