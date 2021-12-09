<template>
    <div>
        
        <form method="post">
            <h4 class="text-right" style="color: #3f51b5">{{incidentID ? 'IR# '+incidentID: ''}}</h4>
            <h3 class="text-center form-title"><span class="dblUnderlined">INCIDENT REPORT</span></h3>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :readonly="true" type="text" class="form-field__input" :value="datefiled" name="reference">
                        <label class="form-field__label">Date Filed</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group-limit">
                        <Datepicker :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_  && selected.hasOwnProperty('incidentID'))" :value="dateoccured" @selected="selectDateOccured" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                        <label slot="afterDateInput" class="form-field__label">Date Occured</label>
                        <div slot="afterDateInput" class="form-field__bar"></div>
                        </Datepicker> 		
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="time" class="form-field__input" v-model="incidenttime" v-validate="'required'" name="incidenttime">
                        <label class="form-field__label">Incident Time</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('incidenttime') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :readonly="true" type="text" class="form-field__input" :value="computedStatus" name="reference">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-3">
                <!-- <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="text" class="form-field__input" v-model="personsinvolve" v-validate="'required'" name="persons involved">
                        <label class="form-field__label">Person/s Involved</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('persons involved') }}</span>
                </div> -->
                    <div class="mdb-form-field form-group-limit">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="text" v-model="search_employee"  v-validate="'required'" name="person involved"  class="form-field__input"
                                @keyup.prevent="searchEmployee"
                                >
                                <label class="form-field__label">Person Involved:</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors" v-if="!search_employee">{{ errors.first('person involved') }}</span>
                            <div class="absolute-pos bg-white suggestion_filter" 
                                v-if="search_employee && !personsinvolve && employeeList.length > 0
                            ">
                                <!-- loader and err msg -->
                                <div v-if="loader">
                                    <i class="fas fa-spinner fa-spin"></i> 
                                    <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                </div>
                                <ul>
                                    <li v-for="emp in employeeList" 
                                    @click.prevent="selectedEmployee(emp)"
                                    :key="emp.empID">
                                        {{emp.fullname}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="text" class="form-field__input" v-model="designation" v-validate="'required'" name="designation">
                        <label class="form-field__label">Designation</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('designation') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="text" class="form-field__input" v-model="branch" v-validate="'required'" name="branch">
                        <label class="form-field__label">Branch</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('branch') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="text" class="form-field__input" v-model="refs" name="reference">
                        <label class="form-field__label">IR Reference</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Nature of Incident</em></h5>
            </div>
            <div class="col-lg-12">
                <div class="col-lg-6 col-md-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Inventory Discrepancy</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Inventory Discrepancy" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Habitual Tardiness</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Habitual Tardiness" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Habitual Absences</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Habitual Absences" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Absence w/o official leave</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Absence w/o official leave" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Insubordination</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Insubordination" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Non-compliance to policies/procedures</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Non-compliance to policies/procedures" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Delivery Discrepancy</span>
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Delivery Discrepancy" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Theft</span>
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Theft" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Falsification/Tampering of Documents</span>
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Falsification/Tampering of Documents" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Loss/Damage of Company Property</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Loss/Damage of Company Property"  v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Non remittance/short of collections</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Non remittance/short of collections"  v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Others</span>
                            <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="others"  v-model="incidenttype" name="incident type">
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
                        <textarea :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="details" name="additional-details"></textarea>
                        <label class="form-field__label">(Complete description and details of the incident specifically how and why the incident occurred.)</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <em>Important Note: All pertinent documents supporting the occurrence of the incident should be attached in this form.</em>
                    <h6><span class="errors">{{ errors.first('additional-details') }}</span></h6>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Explanation from {{search_employee || 'person involve'}}</em></h5>
            </div>
            <div class="col-md-12">
                <div class="mdb-form-field">
                    <div class="form-field__control mdb-bgcolor">
                        <textarea :disabled="$parent.disabledinput || userinfo.empID != selected.personsinvolve || !selected.personsinvolve" class="form-field__textarea"  cols="4" rows="4" v-model="explanation" name="additional-info"></textarea>
                        <label class="form-field__label">Add explanation here</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="text" class="form-field__input" :value="computedfullname" name="reported-by" readonly>
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
             <div class="col-lg-12">
                <h5 class="form-subtitlex"><em><sup class="tr-executed">Initial Action Taken</sup></em></h5>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <label class="mdblblradio">
                        <span class="checklbl">Coaching</span>
                        <input type="radio" value="Coaching" v-model="actionTaken" name="action taken"
                        :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)">
                        <span class="checkmark"></span>
                    </label>
                    <label class="mdblblradio">
                        <span class="checklbl">Coaching and Immediate Deduction</span>
                        <input type="radio" value="Coaching and Immediate Deduction" v-model="actionTaken" name="action taken"
                        :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="col-md-6">
                    <label class="mdblblradio">
                        <span class="checklbl">Further Investigation</span>
                        <input type="radio" value="Further Investigation" v-model="actionTaken" name="action taken"
                        :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)">
                        <span class="checkmark"></span>
                    </label>
                    <label class="mdblblradio">
                        <span class="checklbl">For Due Process</span>
                        <input type="radio" value="For Due Process" v-model="actionTaken" name="action taken"
                        :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)">
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12" v-show="actionTaken == 'Further Investigation'">
                    <div class="mdb-form-field form-group-limit">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input type="text" v-model="search_endorse_employee"  v-validate="'required'" name="1rst Endorsement"  class="form-field__input"
                                :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)"
                                @keyup.prevent="searchEmployeeEndorse1"
                                >
                                <label class="form-field__label">1rst Endorsement:</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors" v-if="!search_endorse_employee">{{ errors.first('1rst Endorsement') }}</span>
                            <div class="absolute-pos bg-white suggestion_filter" 
                                v-if="search_endorse_employee && !endorse1 && employeeList.length > 0
                            ">
                                <!-- loader and err msg -->
                                <div v-if="loader">
                                    <i class="fas fa-spinner fa-spin"></i> 
                                    <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                </div>
                                <ul>
                                    <li v-for="emp in employeeList" 
                                    @click.prevent="selectedEmployeeEndorse1(emp)"
                                    :key="emp.empID">
                                        {{emp.fullname}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-lg-12" v-show="actionTaken == 'Coaching and Immediate Deduction'">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" v-model="deduction_amt" v-validate="'required'" name="deduct amount"
                        :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)">
                        <label class="form-field__label">Total Deduction Amount</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('deduct amount') }}</span>
                </div>
            </div>
            <div v-if="$parent.disabledinput || true">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea class="form-field__textarea"  cols="4" rows="4" v-model="remarks" name="additional-info"
                                :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)"></textarea>
                                <label class="form-field__label">{{actionTaken}} Remarks</label>
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
            
            <!-- FIRST ENDORSER -->
            <div v-show="status >= 1 && status<= 3">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>First Endorser</em></h5>
                </div>
                <div class="clearfix"></div>
                    <div class="col-lg-12">
                        <h5 class="form-subtitlex"><em><sup class="tr-executed">Initial Action Taken</sup></em></h5>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <label class="mdblblradio">
                                <span class="checklbl">Coaching</span>
                                <input :disabled="$parent.$data.forapprover != 'approval'" type="radio" value="Coaching" v-model="actionTaken1" name="action taken1">
                                <span class="checkmark"></span>
                            </label>
                            <label class="mdblblradio">
                                <span class="checklbl">Coaching and Immediate Deduction</span>
                                <input :disabled="$parent.$data.forapprover != 'approval'" type="radio" value="Coaching and Immediate Deduction" v-model="actionTaken1" name="action taken1">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="mdblblradio">
                                <span class="checklbl">Further Investigation</span>
                                <input :disabled="$parent.$data.forapprover != 'approval'" type="radio" value="Further Investigation" v-model="actionTaken1" name="action taken1">
                                <span class="checkmark"></span>
                            </label>
                            <label class="mdblblradio">
                                <span class="checklbl">For Due Process</span>
                                <input :disabled="$parent.$data.forapprover != 'approval'" type="radio" value="For Due Process" v-model="actionTaken1" name="action taken1">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12" v-show="actionTaken1 == 'Further Investigation'">
                            <div class="mdb-form-field form-group-limit">
                                <div class="relative-pos">
                                    <div class="form-field__control">
                                        <input :disabled="$parent.$data.forapprover != 'approval'" type="text" v-model="search_endorse_employee2"  v-validate="'required'" name="2nd Endorsement"  class="form-field__input"
                                        @keyup.prevent="searchEmployeeEndorse2"
                                        >
                                        <label class="form-field__label">2nd Endorsement:</label>
                                        <div class="form-field__bar"></div>
                                    </div>
                                    <span class="errors" v-if="!search_endorse_employee2">{{ errors.first('2nd Endorsement') }}</span>
                                    <div class="absolute-pos bg-white suggestion_filter" 
                                        v-if="search_endorse_employee2 && !endorse2 && employeeList.length > 0
                                    ">
                                        <!-- loader and err msg -->
                                        <div v-if="loader">
                                            <i class="fas fa-spinner fa-spin"></i> 
                                            <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                        </div>
                                        <ul>
                                            <li v-for="emp in employeeList" 
                                            @click.prevent="selectedEmployeeEndorse2(emp)"
                                            :key="emp.empID">
                                                {{emp.fullname}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-12" v-show="actionTaken1 == 'Coaching and Immediate Deduction'">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="$parent.$data.forapprover != 'approval'"  type="text" class="form-field__input" v-model="deduction_amt1" v-validate="'required'" name="deduct amount">
                                <label class="form-field__label">Deduction Amount</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('deduct amount') }}</span>
                        </div>
                    </div>
                    <div v-if="$parent.disabledinput || true">
                        <div class="col-lg-12">
                            <h5 class="form-subtitle"></h5>
                            <div class="mdb-form-field">
                                    <div class="form-field__control mdb-bgcolor">
                                        <textarea :disabled="$parent.$data.forapprover != 'approval'" class="form-field__textarea"  cols="4" rows="4" v-model="endorse1_remarks" name="additional-info"></textarea>
                                        <label class="form-field__label">{{actionTaken1}} Remarks</label>
                                        <div class="form-field__bar"></div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-lg-12" v-if="$parent.disabledinput">
                            <div class="mdb-form-field form-group-limit">
                                <div class="form-field__control">
                                    <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="search_endorse_employee" name="branch" readonly="true">
                                    <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Approved/Rejected by 1rst Endorser' }}</label>
                                    <div class="form-field__bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- END -->
            
            <!-- SECONDS ENDORSER -->
            <div v-show="status >= 2 && status <= 3">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Second Endorser</em></h5>
                </div>
                <div class="clearfix"></div>
                
                <div class="col-lg-12">
                    <h5 class="form-subtitlex"><em><sup class="tr-executed">Initial Action Taken</sup></em></h5>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <label class="mdblblradio">
                            <span class="checklbl">Coaching</span>
                            <input :disabled="$parent.$data.forapprover != 'approval'" type="radio" value="Coaching" v-model="actionTaken2" name="action taken2">
                            <span class="checkmark"></span>
                        </label>
                        <label class="mdblblradio">
                            <span class="checklbl">Coaching and Immediate Deduction</span>
                            <input :disabled="$parent.$data.forapprover != 'approval'" type="radio" value="Coaching and Immediate Deduction" v-model="actionTaken2" name="action taken2">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="mdblblradio">
                            <span class="checklbl">Further Investigation</span>
                            <input :disabled="$parent.$data.forapprover != 'approval'" type="radio" value="Further Investigation" v-model="actionTaken2" name="action taken2">
                            <span class="checkmark"></span>
                        </label>
                        <label class="mdblblradio">
                            <span class="checklbl">For Due Process</span>
                            <input :disabled="$parent.$data.forapprover != 'approval'" type="radio" value="For Due Process" v-model="actionTaken2" name="action taken2">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12" v-show="actionTaken2 == 'Coaching and Immediate Deduction'">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.$data.forapprover != 'approval'"  type="text" class="form-field__input" v-model="deduction_amt2" v-validate="'required'" name="deduct amount">
                            <label class="form-field__label">Deduction Amount</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('deduct amount') }}</span>
                    </div>
                </div>
                <div v-if="$parent.disabledinput || true">
                    <div class="col-lg-12">
                        <h5 class="form-subtitle"></h5>
                        <div class="mdb-form-field">
                                <div class="form-field__control mdb-bgcolor">
                                    <textarea :disabled="$parent.$data.forapprover != 'approval'" class="form-field__textarea"  cols="4" rows="4" v-model="endorse2_remarks" name="additional-info"></textarea>
                                    <label class="form-field__label">{{actionTaken2}} Remarks</label>
                                    <div class="form-field__bar"></div>
                                </div>
                        </div>
                    </div>
                    <div class="col-lg-12" v-if="$parent.disabledinput">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="search_endorse_employee2" name="branch" readonly="true">
                                <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Approved/Rejected by 2nd Endorser' }}</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END -->

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
                    <!-- update -->
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateIncidentReport" 
                    :disabled="isDisable || !isFormValid" 
                    v-if="incidentID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                    
                    <!-- delete -->
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteIncidentReport" 
                        :disabled="isDisable" 
                        v-if="incidentID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput &&
                        userinfo.empID != selected.personsinvolve
                    ">
                    <!-- main approver buttons -->
                    <!-- first level -->
                    <input type="submit" class="btn btn-primary" value="Approve" 
                    :disabled="!actionTaken || 
                    (actionTaken == 'Further Investigation' && !endorse1) || 
                    (actionTaken == 'Coaching and Immediate Deduction' && !deduction_amt)
                    " 
                    @click.prevent="requestActionIncidentReport(1)" 
                    v-if="incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && status == 0 ">

                    <input type="submit" class="btn btn-primary" value="Reject" 
                        @click.prevent="requestActionIncidentReport(4)" 
                        v-if="incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && status == 0 
                    ">

                    <!-- first level endorse -->
                    <input type="submit" class="btn btn-primary" value="Approve" 
                    :disabled="!actionTaken1 || 
                    (actionTaken1 == 'Further Investigation' && !endorse2) || 
                    (actionTaken1 == 'Coaching and Immediate Deduction' && !deduction_amt1)
                    " 
                    @click.prevent="requestActionIncidentReport(1)" 
                    v-if="incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.empID == selected.endorse1 && status == 1 ">

                    <input type="submit" class="btn btn-primary" value="Reject" 
                        @click.prevent="requestActionIncidentReport(4)" 
                        v-if="incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.empID == selected.endorse1 && status == 1 ">

                    <!-- end level and final endorser -->
                    <input type="submit" class="btn btn-primary" value="Approve" 
                    :disabled="!actionTaken2 ||
                    (actionTaken2 == 'Coaching and Immediate Deduction' && !deduction_amt2)" 
                    @click.prevent="requestActionIncidentReport(3)" 
                    v-if="incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.empID == selected.endorse2 && status == 2 ">
                    
                    <input type="submit" class="btn btn-primary" value="Reject" 
                        @click.prevent="requestActionIncidentReport(4)" 
                        v-if="incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.empID == selected.endorse2 && status >= 2 ">
                    
                    
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionIncidentReport(0)" v-if="incidentID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel"> -->
            </div>
        </form>
        
    </div>
</template>
<script>
// let incidenttype = [
//         'Inventory Discrepancy', 'Habitual Tardiness', 'Habitual Absences', 'TYREPLUSAbsence w/o official leave', 'Insubordination',
//         'Non-compliance to policies/procedures', 'Delivery Discrepancy', 'Theft', 'Falsification/Tampering of Documents', 'Loss/Damage of Company Property',
//         'Non remittance/short of collections', 'Others'
//     ];
// let status = ['Pending', 'Approved', 'Rejected'];
const excludeBody = [
    'incidentID',
    'employeeList',
    'isDisable',
    'status',
    'search_employee',
    'search_endorse_employee',
    'search_endorse_employee2',
    'reportedby',
    'datefiled',
];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
            incidentID: '',
            reportedby: '',
			isDisable: false,
			datefiled: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
			dateoccured: moment(new Date()).format('YYYY-MM-DD'),
            incidenttime: moment(new Date()).format('HH:mm'),
            deduction_amt: '',

            employeeList: [],
            search_employee: '',

            personsinvolve: '',
            personsinvolve_email: '',
            designation: '',
            branch: '',
			incidenttype: 'Inventory Discrepancy', // nature of inciddent
            details: '',
            explanation: '',
            refs: '',

            approvedby: '',
            actionTaken: '',
            remarks: '',

            // endorse 1
            search_endorse_employee: '',
            endorse1: '',
            endorse1_email:'',
            actionTaken1: '',
            deduction_amt1: '',
            endorse1_remarks: '',

            // endorse 2
            search_endorse_employee2: '',
            endorse2: '',
            endorse2_email:'',
            actionTaken2: '',
            deduction_amt2: '',
            endorse2_remarks: '',

			// reqstat: 0, //0 pending, 1//approve //2 declined
			status: 0, //0 open, 1//close
			
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        searchEmployee(){
            this.loader = false;
			let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
			let regex = RegExp(validSearch);
            this.personsinvolve = '';
            this.branch = '';
            this.designation = '';
            this.personsinvolve_email = '';
            this.errMsg = '';
            
			if(regex.test((this.search_employee))){
				
                // FOR UPDATE GET ONLY THE LAST NAME
                let search = '';
                if(this.selected.overrideID) {
                    search = (this.search_employee).split(", ");
                    search = search[0];
                    // return;
                }else{
                    search = this.search_employee;
                }
				axios.post('api/search-transmittal-emp',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.employeeList = res.data;
					}
                    this.loader = false;
				})
				.catch(err => this.errMsg = 'Network problem please contact your IT-Department');
			}

		},
        selectedEmployee(val) {
            this.search_employee = val.fullname;
            this.personsinvolve = val.empID;
            this.branch = val.receiver_branch;
            this.designation = val.receiver_pos;
            this.personsinvolve_email = val.email;
            this.MDBINPUT();
            
        },
        searchEmployeeEndorse1(){
            this.loader = false;
			let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
			let regex = RegExp(validSearch);
            this.endorse1 = '';
            this.errMsg = '';
            
			if(regex.test((this.search_endorse_employee))){
				
                // FOR UPDATE GET ONLY THE LAST NAME
                let search = '';
                if(this.selected.overrideID) {
                    search = (this.search_endorse_employee).split(", ");
                    search = search[0];
                    // return;
                }else{
                    search = this.search_endorse_employee;
                }
				axios.post('api/search-transmittal-emp',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.employeeList = res.data;
					}
                    this.loader = false;
				})
				.catch(err => this.errMsg = 'Network problem please contact your IT-Department');
			}

		},
        selectedEmployeeEndorse1(val) {
            this.search_endorse_employee = val.fullname;
            this.endorse1 = val.empID;
            // this.branch = val.receiver_branch;
            // this.designation = val.receiver_pos;
            this.endorse1_email = val.email;
            this.MDBINPUT();
            
        },
        searchEmployeeEndorse2(){
            this.loader = false;
			let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
			let regex = RegExp(validSearch);
            this.endorse2 = '';
            this.errMsg = '';
            
			if(regex.test((this.search_endorse_employee2))){
				
                // FOR UPDATE GET ONLY THE LAST NAME
                let search = '';
                if(this.selected.overrideID) {
                    search = (this.search_endorse_employee2).split(", ");
                    search = search[0];
                    // return;
                }else{
                    search = this.search_endorse_employee2;
                }
				axios.post('api/search-transmittal-emp',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.employeeList = res.data;
					}
                    this.loader = false;
				})
				.catch(err => this.errMsg = 'Network problem please contact your IT-Department');
			}

		},
        selectedEmployeeEndorse2(val) {
            this.search_endorse_employee2 = val.fullname;
            this.endorse2 = val.empID;
            // this.branch = val.receiver_branch;
            // this.designation = val.receiver_pos;
            this.endorse2_email = val.email;
            this.MDBINPUT();
            
        },
        addIncidentReport(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = {};
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        params[key] = this.$data[key];
                    }
                }
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addIncidentReport', params).then((response)=>{
                    this.$parent.addRow({...response.data, reportedby: this.computedfullname, search_employee: this.search_employee});
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateIncidentReport(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                // this.totaldays = this.getDiff;
                let params = {};
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        params[key] = this.$data[key];
                    }
                }
                
                params['incidentID'] = this.selected.incidentID;
                axios.post('api/updateIncidentReport', params).then((response)=>{
                    this.$parent.updateRow({...response.data, reportedby:this.computedfullname, search_employee: this.search_employee});
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
            let params = {};
            params['incidentID'] = this.selected.incidentID;
            // for (const key in this.$data) {
            //     if (!excludeBody.includes(key)) {
            //         params[key] = this.$data[key];
            //     }
            // }
            if(this.status == 0)
            params['approvedby'] = this.userinfo.empID;
            // params['empID_'] = this.$parent.$data.empID_;
            // params['email'] = this.selected.email;
            

            params['status'] = status;
            params['actionTaken'] =  this.actionTaken;
            params['remarks'] = this.remarks;

            params['actionTaken1'] =  this.actionTaken1;
            params['deduction_amt1'] =  this.deduction_amt1;

            params['actionTaken2'] =  this.actionTaken2;
            
            params['personsinvolve_email'] = this.personsinvolve_email;
            params['endorse1_email'] = this.endorse1_email;
            params['endorse2_email'] = this.endorse2_email;
            params['deduction_amt'] =  this.deduction_amt;
            params['deduction_amt2'] =  this.deduction_amt2;
            /**
             * 0 pending
             * 1 endorse 1
             * 2 endorse 2
             * 3 close
             * 4 rejected
            */
            //  first approver
            // if approve and action taken is not to be endorsed close the status
            if(status === 1 && this.status == 0 && this.actionTaken != 'Further Investigation'){
                params['status'] = 3;
                params['deduction_amt'] =  '';
                params['deduction_amt2'] =  '';
            }
            // 2nd approver catherine
            // if approve and action taken is endorse1
            if(status === 1 && this.status == 0 && this.actionTaken == 'Further Investigation'){
                params['status'] = 1;
                params['endorse1'] = this.endorse1;
                params['endorse1_email'] = this.endorse1_email;
                params['endorse1_remarks'] = this.endorse1_remarks;
                params['deduction_amt'] =  '';
                params['deduction_amt2'] =  '';

            }
            // last approver emi
            // if approve and action taken is endorse2
            if(status === 1 && this.status == 1 && this.actionTaken1 == 'Further Investigation'){
                params['status'] = 2;
                params['endorse2'] = this.endorse2;
                params['endorse2_email'] = this.endorse2_email;
                params['endorse1_remarks'] = this.endorse1_remarks;
                params['deduction_amt'] =  '';
                params['deduction_amt2'] =  '';
            }
            
            params['endorse2_remarks'] = this.endorse2_remarks;

            // console.log(params);
            // return;
            // change approve by to username;
            axios.post('api/actionformIncidentReport', params).then(async (response)=>{
                if(params.status == 0)
                await this.$parent.updateRow({...response.data, ...this.selected, approvedby: this.userinfo.fullname});

                // if(params.status == 1)
                // await this.$parent.updateRow({...response.data, ...this.selected, approvedby: this.userinfo.fullname});

                // if(params.status == 2)
                // await this.$parent.updateRow({...response.data, ...this.selected, approvedby: this.userinfo.fullname});

                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable' && i!= 'employeeList')
                this.$data[i] = data[i];
            
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
                if(key != 'datefiled' && key != 'dateoccured' && key != 'incidenttime'
                && key != 'incidenttype'){
                    this.$data[key] =  '';
                }
                if(key == 'datefiled' && key == 'dateoccured' && key == 'incidenttime') {
                    this.$data[key] = moment(new Date()).format('YYYY-MM-DD');
                    this.$data[key] = moment(new Date()).format('YYYY-MM-DD');
                    this.$data[key] = moment(new Date()).format('HH:mm');
                }
                if(key == 'isDisable'){
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
            return this.selected.hasOwnProperty('reportedby') ? this.selected.reportedby  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        },
        computedStatus(){
            return this.status == 0 ? 'Pending':
                   this.status == 1 ? '1st Endorsed':
                   this.status == 2 ? '2nd Endorsed': 
                   this.status == 3 ? 'Closed': 'Rejected';
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