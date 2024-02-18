<style scoped>
textarea::-webkit-scrollbar{
    width: 15px !important;
}

.show_more_btn{
    cursor: pointer;
    text-decoration: underline;
    color: blue;
    font-style: italic;
    float: right;
}
span#file-label{
    cursor: pointer;
}
ul.attachments-list{
    padding-left:0px !important;
}
ul.attachments-list li:first-child{
    margin-left: 0px;
}
ul.attachments-list li{
    display: inline-block;
    padding: 10px;
    margin-left: 20px;
    background: aliceblue;
    border-radius: 8px;
}

.IR-rem-attachment{
    position: absolute; 
    top: -7px; 
    font-size: 21px;  
    color: #c20e0e; 
    cursor: pointer;
}

</style>
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
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="text" class="form-field__input" v-model="designation" v-validate="'required'" name="designation" :readonly="true">
                        <label class="form-field__label">Designation</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('designation') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="text" class="form-field__input" v-model="branch" v-validate="'required'" name="branch" :readonly="true">
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
                        <label class="form-field__label">IR Reference #</label>
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
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Inventory Discrepancy" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Habitual Tardiness</span>
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Habitual Tardiness" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Habitual Absences</span>
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Habitual Absences" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Absence w/o official leave</span>
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Absence w/o official leave" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Insubordination</span>
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Insubordination" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Non-compliance to policies/procedures</span>
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Non-compliance to policies/procedures" v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Delivery Discrepancy</span>
                        <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Delivery Discrepancy" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Theft</span>
                        <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Theft" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div>
                    <label class="mdblblradio">
                        <span class="checklbl">Falsification/Tampering of Documents</span>
                        <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Falsification/Tampering of Documents" v-model="incidenttype" name="incident type">
                        <span class="checkmark"></span>
                    </label>
                </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Loss/Damage of Company Property</span>
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Loss/Damage of Company Property"  v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Non remittance/short of collections</span>
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="Non remittance/short of collections"  v-model="incidenttype" name="incident type">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Others</span>
                            <input :disabled="explanation || $parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" type="radio" value="others"  v-model="incidenttype" name="incident type">
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
                        <!-- <div v-show="showBlockMessageDetailSection" style="white-space: pre-line; padding: 15px;">
                            {{ details }}
                        </div> -->
                        <div>
                            <textarea :disabled="$parent.disabledinput || (userinfo.empID !== selected.empID_ && selected.empID_)" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="details" name="additional-details"></textarea>
                            <span v-show="showBlockMessageDetailSection" class="show_more_btn" @click="showMore">show more</span>
                            <label class="form-field__label">(Complete description and details of the incident specifically how and why the incident occurred.)</label>
                            <div class="form-field__bar"></div>
                        </div>
                        
                    </div>
                    <em>Important Note: All pertinent documents supporting the occurrence of the incident should be attached in this form.</em>
                    <h6><span class="errors">{{ errors.first('additional-details') }}</span></h6>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- creator attachment -->
            <div class="col-lg-12 col-md-12 col-sm-12">
                <!-- <img id="work-attachments" :src="'public/images/priemer_jacket.jpg'" alt="Avatar"> -->
                <label for="prog__attachment_creator" v-if="(!$parent.disabledinput && userinfo.empID == selected.empID_ && creator_attach.length < 3) || !incidentID && creator_attach.length < 3">
                    <span class="profile-cam-icon" style="position: relative;"><i class="fas fa-camera"></i></span>
                        <span style="position: relative; left: 10px; bottom: 3px;">
                            <span id="file-label">Attach File</span>
                            <!-- <a id="file-label1" :href="'storage/app/'+progmechanic_attachment" target="_blank" v-show="progmechanic_attachment">attachment - {{'Program Mechanics'}}</a> -->
                    </span>
                </label>
                <ul class="nostyle-list attachments-list"  style="padding-left: 20px;">
                    <li v-for="(item, key) in creator_attach" :key="key" class="relative-pos">
                        <a class="fas fa-link" :href="'storage/app/'+item" target="_blank"> Attachment: {{key + 1}}</a>
                        <i v-if="(!$parent.disabledinput && userinfo.empID == selected.empID_) || !incidentID" class="fas fa-times-circle IR-rem-attachment" @click.prevent="remAttachment(key, 'creator attachment')"></i>
                    </li>
                </ul>
                <input id="prog__attachment_creator" class="program-attachment" type="file" @change="attachFile" style="display:none;">
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Explanation from {{search_employee || 'person involve'}}</em></h5>
            </div>
            <div class="col-md-12">
                <div class="mdb-form-field">
                    <div class="form-field__control mdb-bgcolor">
                        <div>
                            <textarea :disabled="$parent.disabledinput || userinfo.empID != selected.personsinvolve || !selected.personsinvolve" class="form-field__textarea"  cols="4" rows="4" v-model="explanation" name="additional-info"></textarea>
                            <span v-show="showBlockMessageExplanationSection" class="show_more_btn" @click="showMore">show more</span>
                            <label class="form-field__label">Add explanation here</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                    <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                </div>
            </div>
            <div class="clearfix"></div>
            <!-- attachment for person involve -->
            <div class="col-lg-12 col-md-12 col-sm-12">
                <!-- <img id="work-attachments" :src="'public/images/priemer_jacket.jpg'" alt="Avatar"> -->
                <label for="prog__attachment_person_involve" v-if="!$parent.disabledinput && userinfo.empID == selected.personsinvolve && selected.personsinvolve && personinvolve_attach.length < 3">
                    <span class="profile-cam-icon" style="position: relative;"><i class="fas fa-camera"></i></span>
                        <span style="position: relative; left: 10px; bottom: 3px;">
                            <span id="file-label">Attach File</span>
                            <!-- <a id="file-label1" :href="'storage/app/'+progmechanic_attachment" target="_blank" v-show="progmechanic_attachment">attachment - {{'Program Mechanics'}}</a> -->
                    </span>
                </label>
                <ul class="nostyle-list attachments-list"  style="padding-left: 20px;">
                    <li v-for="(item, key) in personinvolve_attach" :key="key" class="relative-pos">
                        <a class="fas fa-link" :href="'storage/app/'+item" target="_blank"> Attachment: {{key + 1}}</a>
                        <i v-if="!$parent.disabledinput && userinfo.empID == selected.personsinvolve" class="fas fa-times-circle IR-rem-attachment" @click.prevent="remAttachment(key, 'personinvolve')"></i>
                    </li>
                </ul>
                <input id="prog__attachment_person_involve" class="program-attachment" type="file" @change="attachFile" style="display:none;">
            </div>
            <div class="clearfix"></div>
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
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limitx">
                        <div class="form-field__control">
                            <select id="posname" v-model="disciplinaryaction" name="disciplinary action" v-validate="'required'" class="form-field__input" 
                            :disabled="actionTaken != 'For Due Process' ||  (selected.approvedby && userinfo.empID != selected.approvedby)" >
                                <option :value="'N/A'">N/A</option>
                                <option :value="item" v-for="(item, key) in disciplinaryactionOptions" :key="key">{{ item }}</option>
                            </select>
                            <label class="form-field__label">Disciplinary Action</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('disciplinary action') }}</span>
                    </div>
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
                        <input type="text" class="form-field__input" v-model="deduction_amt" v-validate="" name="deduct amount"
                        :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)">
                        <label class="form-field__label">Total Deduction Amount</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <!-- <span class="errors">{{ errors.first('deduct amount') }}</span> -->
                </div>
            </div>
            <div v-if="$parent.disabledinput || true">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <!-- <div v-show="showBlockMessageInitialApproverRemarks" style="white-space: pre-line; padding: 15px;">
                                    {{remarks}}
                                </div> -->
                                <div >
                                    <textarea class="form-field__textarea"  cols="4" rows="4" v-model="remarks" name="additional-info"
                                    :disabled="$parent.$data.forapprover != 'approval' || (selected.approvedby && userinfo.empID != selected.approvedby)"></textarea>
                                    <span v-show="showBlockMessageInitialApproverRemarks" class="show_more_btn" @click="showMore">show more</span>
                                    <label class="form-field__label">{{actionTaken}} Remarks</label>
                                    <div class="form-field__bar"></div>
                                </div>
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
            <div v-show="status >= 1 && status<= 3 && endorse1">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>First Endorser</em></h5>
                </div>
                <div class="clearfix"></div>
                    <div class="col-lg-12">
                        <h5 class="form-subtitlex"><em><sup class="tr-executed">Initial Action Taken</sup></em></h5>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <label class="mdblblradio">
                                <span class="checklbl">Coaching</span>
                                <input 
                                :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse1)) 
                                && (selected.endorse1 && !selected.endorse2 && userinfo.posID != 16)"
                                type="radio" value="Coaching" v-model="actionTaken1" name="action taken1">
                                <span class="checkmark"></span>
                            </label>
                            <label class="mdblblradio">
                                <span class="checklbl">Coaching and Immediate Deduction</span>
                                <input 
                                :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse1)) 
                                && (selected.endorse1 && !selected.endorse2 && userinfo.posID != 16)"
                                type="radio" value="Coaching and Immediate Deduction" v-model="actionTaken1" name="action taken1">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="mdblblradio">
                                <span class="checklbl">Further Investigation</span>
                                <input 
                                :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse1)) || userinfo.posID == 16 "
                                type="radio" value="Further Investigation" v-model="actionTaken1" name="action taken1">
                                <span class="checkmark"></span>
                            </label>
                            <label class="mdblblradio">
                                <span class="checklbl">For Due Process</span>
                                <input 
                                :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse1)) 
                                && (selected.endorse1 && !selected.endorse2 && userinfo.posID != 16)"
                                type="radio" value="For Due Process" v-model="actionTaken1" name="action taken1">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <select id="posname" v-model="disciplinaryaction1" name="disciplinary action1" v-validate="'required'" class="form-field__input" 
                                    :disabled="actionTaken1 != 'For Due Process' || (userinfo.empID != selected.endorse1)
                                    && userinfo.posID != 16
                                    " >
                                        <option :value="'N/A'">N/A</option>
                                        <option :value="item" v-for="(item, key) in disciplinaryactionOptions" :key="key">{{ item }}</option>
                                    </select>
                                    <label class="form-field__label">Disciplinary Action</label>
                                    <div class="form-field__bar"></div>
                                </div>
                                <span class="errors">{{ errors.first('disciplinary action1') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12" v-show="actionTaken1 == 'Further Investigation'">
                            <div class="mdb-form-field form-group-limit">
                                <div class="relative-pos">
                                    <div class="form-field__control">
                                        <input 
                                        :disabled="$parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse1)" 
                                        type="text" v-model="search_endorse_employee2"  v-validate="'required'" name="2nd Endorsement"  class="form-field__input"
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
                                <input 
                                :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse1)) && userinfo.posID != 16"  
                                type="text" class="form-field__input" v-model="deduction_amt1" v-validate="'required'" name="deduct amount">
                                <label class="form-field__label">Deduction Amount</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <!-- <span class="errors">{{ errors.first('deduct amount') }}</span> -->
                        </div>
                    </div>
                    <div v-if="$parent.disabledinput || true">
                        <div class="col-lg-12">
                            <h5 class="form-subtitle"></h5>
                            <div class="mdb-form-field">
                                    <div class="form-field__control mdb-bgcolor">
                                        <!-- <div v-show="showBlockMessageEndorserRemarks" style="white-space: pre-line; padding: 15px;">
                                            {{endorse1_remarks}}
                                        </div> -->
                                        <div>
                                            <textarea 
                                            :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse1)) && userinfo.posID != 16" 
                                            class="form-field__textarea"  cols="4" rows="4" v-model="endorse1_remarks" name="additional-info"></textarea>
                                            <span v-show="showBlockMessageEndorserRemarks" class="show_more_btn" @click="showMore">show more</span>
                                            <label class="form-field__label">{{actionTaken1}} Remarks</label>
                                            <div class="form-field__bar"></div>
                                        </div>
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
            <div v-show="status >= 2 && status <=3 && endorse2">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Second Endorser</em></h5>
                </div>
                <div class="clearfix"></div>
                
                <div class="col-lg-12">
                    <h5 class="form-subtitlex"><em><sup class="tr-executed">Initial Action Taken</sup></em></h5>
                </div>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <label class="mdblblradio">
                            <span class="checklbl">Coaching</span>
                            <input 
                            :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse2))
                            && (selected.endorse2 && userinfo.posID != 16)" 
                            type="radio" value="Coaching" v-model="actionTaken2" name="action taken2">
                            <span class="checkmark"></span>
                        </label>
                        <label class="mdblblradio">
                            <span class="checklbl">Coaching and Immediate Deduction</span>
                            <input 
                            :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse2))
                            && (selected.endorse2 && userinfo.posID != 16)" 
                            type="radio" value="Coaching and Immediate Deduction" v-model="actionTaken2" name="action taken2">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="mdblblradio">
                            <span class="checklbl">Further Investigation</span>
                            <input 
                            :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse2))
                            && (selected.endorse2 && userinfo.posID != 16)" 
                            type="radio" value="Further Investigation" v-model="actionTaken2" name="action taken2">
                            <span class="checkmark"></span>
                        </label>
                        <label class="mdblblradio">
                            <span class="checklbl">For Due Process</span>
                            <input 
                            :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse2))
                            && (selected.endorse2 && userinfo.posID != 16)" 
                            type="radio" value="For Due Process" v-model="actionTaken2" name="action taken2">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <div class="mdb-form-field form-group-limitx">
                            <div class="form-field__control">
                                <select id="posname" v-model="disciplinaryaction2" name="disciplinary action2" v-validate="'required'" class="form-field__input" 
                                :disabled="actionTaken2 != 'For Due Process' || (userinfo.empID != selected.endorse2)
                                && (selected.endorse2 && userinfo.posID != 16)" >
                                    <option :value="'N/A'">N/A</option>
                                    <option :value="item" v-for="(item, key) in disciplinaryactionOptions" :key="key">{{ item }}</option>
                                </select>
                                <label class="form-field__label">Disciplinary Action</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors">{{ errors.first('disciplinary action2') }}</span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12" v-show="actionTaken2 == 'Coaching and Immediate Deduction'">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input 
                            :disabled="$parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse2)" 
                             type="text" class="form-field__input" v-model="deduction_amt2" v-validate="'required'" name="deduct amount">
                            <label class="form-field__label">Deduction Amount</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <!-- <span class="errors">{{ errors.first('deduct amount') }}</span> -->
                    </div>
                </div>
                <div v-if="$parent.disabledinput || true">
                    <div class="col-lg-12">
                        <h5 class="form-subtitle"></h5>
                        <div class="mdb-form-field">
                                <div class="form-field__control mdb-bgcolor">
                                    <!-- <div v-show="showBlockMessageEndorserRemarks" style="white-space: pre-line; padding: 15px;">
                                        {{endorse2_remarks}}
                                    </div> -->
                                    <div>
                                        <textarea 
                                        :disabled="($parent.$data.forapprover != 'approval' || (userinfo.empID != selected.endorse2)) &&
                                        (selected.endorse2 && userinfo.posID != 16)" 
                                        class="form-field__textarea"  cols="4" rows="4" v-model="endorse2_remarks" name="additional-info"></textarea>
                                        <span v-show="showBlockMessageEndorserRemarks" class="show_more_btn" @click="showMore">show more</span>
                                        <label class="form-field__label">{{actionTaken2}} Remarks</label>
                                        <div class="form-field__bar"></div>
                                    </div>
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
                    <span class="approverlist alert-info">HR Department</span>
                    <br><br>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <span class="errors">Note: All attachements will be automatically deleted after 60days</span>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addIncidentReport" :disabled="
                     disabledIfNoApprover || isDisable || !isFormValid || !submitBtn" v-if="!incidentID && $parent.$data.forapprover != 'approval'">
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
                    
                    <!-- first level approve main approver-->
                    <input type="submit" class="btn btn-primary" value="Approve" 
                    :disabled="!actionTaken || 
                    (actionTaken == 'Further Investigation' && !endorse1) || 
                    (actionTaken == 'Coaching and Immediate Deduction' && !deduction_amt)
                    " 
                    @click.prevent="requestActionIncidentReport(1)" 
                    v-if="(incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && status == 0) || (incidentID && $parent.$data.forapprover == 'approval' && userinfo.posID == 16 && status == 0) ">
                    
                    <!-- reject main approver -->
                    <input type="submit" class="btn btn-primary" value="Reject" 
                        @click.prevent="requestActionIncidentReport(4)" 
                        v-if="(incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && status == 0) || (incidentID && $parent.$data.forapprover == 'approval' && userinfo.posID == 16 && status == 0) 
                    ">

                    <!-- second level endorse -->
                    <input type="submit" class="btn btn-primary" value="Approve." 
                    :disabled="!actionTaken1 || 
                    (actionTaken1 == 'Further Investigation' && !endorse2) || 
                    (actionTaken1 == 'Coaching and Immediate Deduction' && !deduction_amt1)
                    " 
                    @click.prevent="requestActionIncidentReport(1)" 
                    v-if="(incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.empID == selected.endorse1 && status == 1) || (incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.posID == 16 && status == 1)">
                    
                    <!-- reject 2nd endorser-->
                    <input type="submit" class="btn btn-primary" value="Reject" 
                        @click.prevent="requestActionIncidentReport(4)" 
                        v-if="(incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.empID == selected.endorse1 && status == 1) || (incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.posID == 16 && status == 1)">

                    <!-- end level and final endorser -->
                    <input type="submit" class="btn btn-primary" value="Approve.." 
                    :disabled="(!actionTaken2 ||
                    (actionTaken2 == 'Coaching and Immediate Deduction' && !deduction_amt2)) && userinfo.posID != 16 && status > 1" 
                    @click.prevent="requestActionIncidentReport(3)" 
                    v-if="(incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.empID == selected.endorse2 && status == 2 ) || (incidentID && $parent.$data.forapprover == 'approval' && userinfo.posID == 16 && status > 1)">
                    
                    <!-- reject final endorser-->
                    <input type="submit" class="btn btn-primary" value="Reject" 
                        @click.prevent="requestActionIncidentReport(4)" 
                        v-if="(incidentID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel && userinfo.empID == selected.endorse2 && status >= 2 ) || (incidentID && $parent.$data.forapprover == 'approval' && userinfo.posID == 16 && status > 1)">
                    
                    
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
    'disciplinaryactionOptions',
    // 'creator_attach',
    // 'personinvolve_attach',
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
			incidenttype: 'others', // nature of inciddent
            details: '',
            explanation: '',
            refs: '',
            
            creator_attach: [],
            personinvolve_attach: [],

            approvedby: '',
            actionTaken: '',
            disciplinaryaction: 'N/A',
            disciplinaryactionOptions: [
                'Verbal Warning',
                'Written Warning',
                'Probation',
                'Suspension',
                'Demotion',
                'Financial Penalties',
                'Training or Counseling',
                'Performance Improvement',
                'Plan (PIP)',
                'Transfer',
                'Termination',
            ],
            remarks: '',

            // endorse 1
            search_endorse_employee: '',
            endorse1: '',
            endorse1_email:'',
            actionTaken1: '',
            deduction_amt1: '',
            endorse1_remarks: '',
            disciplinaryaction1: 'N/A',
            // endorse 2
            search_endorse_employee2: '',
            endorse2: '',
            endorse2_email:'',
            actionTaken2: '',
            deduction_amt2: '',
            endorse2_remarks: '',
            disciplinaryaction2: 'N/A',

			// reqstat: 0, //0 pending, 1//approve //2 declined
			status: 0, //0 open, 1//close
			
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        },
        actionTaken(val, old){
            if(val != 'For Due Process')
            this.disciplinaryaction = 'N/A';
        },
        actionTaken1(val, old){
            if(val != 'For Due Process')
            this.disciplinaryaction1 = 'N/A';
        },
        actionTaken2(val, old){
            if(val != 'For Due Process')
            this.disciplinaryaction2 = 'N/A';
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
            // this.updateIncidentReport();
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = {};
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        params[key] = this.$data[key];
                    }
                    
                }
                params['creator_attach'] = params.creator_attach.toString();
                params['personinvolve_attach'] = params.personinvolve_attach.toString();
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addIncidentReport', params).then((response)=>{
                    this.$parent.addRow({
                        ...response.data, 
                        datefiled: moment(response.data.datefiled).format('MM/DD/YYYY'),  
                        reportedby: this.computedfullname, 
                        search_employee: this.search_employee,
                        creator_attach: params.creator_attach.split(','),
                        personinvolve_attach: params.personinvolve_attach.split(',')
                    });
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
                
                params['creator_attach'] = params.creator_attach.toString();
                params['personinvolve_attach'] = params.personinvolve_attach.toString();
                params['incidentID'] = this.selected.incidentID;
                axios.post('api/updateIncidentReport', params).then((response)=>{
                    this.$parent.updateRow({...response.data, datefiled: this.datefiled, reportedby:this.computedfullname, search_employee: this.search_employee});
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        attachFile(e){
            let isCreator = e.target.id == 'prog__attachment_creator'? true: false;
            
            if(e.target.value != ''){
			    let file    = e.target.files[0]; //sames as here

				if (file) {

                     const config = {
                        onUploadProgress: function(progressEvent) {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                        // console.log(percentCompleted)
                        }
                    }
                    
                    const formData = new FormData();
                    formData.append('attachment[]', file );
                    axios.post('api/IncidentReportattachment', formData, config)
                    .then(res=>{
                        if(isCreator){
                            this.creator_attach.push(res.data);
                        }else{
                            
                            this.personinvolve_attach.push(res.data);
                        }
                        

                    })
                    .catch(er=>console.log(er))
				}
			}else{

				return null;
			}
        },
        remAttachment(index, type = 'personinvolve'){
            if(type == 'personinvolve'){
                this.personinvolve_attach.splice(index, 1);
            }else{
                this.creator_attach.splice(index, 1);
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
            params['disciplinaryaction'] =  this.disciplinaryaction;
            params['remarks'] = this.remarks;

            params['actionTaken1'] =  this.actionTaken1;
            params['deduction_amt1'] =  this.deduction_amt1;

            params['actionTaken2'] =  this.actionTaken2;
            
            params['personsinvolve_email'] = this.personsinvolve_email;
            params['endorse1_email'] = this.endorse1_email;
            params['endorse2_email'] = this.endorse2_email;
            params['deduction_amt'] =  this.deduction_amt;
            params['deduction_amt2'] =  this.deduction_amt2;
            params['empID_'] = this.selected.empID_;
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
                params['empID_'] = this.selected.empID_;
                // params['deduction_amt'] =  '';
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
            // if approve and action taken is not endorse2
            if(status === 1 && this.status == 1 && this.actionTaken1 != 'Further Investigation'){
                params['status'] = 3;
                params['empID_'] = this.selected.empID_;
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

            // console.log(status,this.status,this.actionTaken, params);
            // return;
            // change approve by to username;
            axios.post('api/actionformIncidentReport', params).then((response)=>{
                // if(params.status == 0)
                this.$parent.updateRow({...this.selected, ...response.data, approvedby: this.userinfo.fullname});

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
                if(i != 'isDisable' && i!= 'employeeList' && i != 'disciplinaryactionOptions' && 
                   i != 'creator_attach' && i != 'personinvolve_attach'
                )
                this.$data[i] = data[i];

                if((i == 'creator_attach' || i == 'personinvolve_attach') && data[i])
                this.$data[i] = (data[i]+'').split(',');
            
            }
            
            
            $("#myModal").modal("show");
            this.MDBINPUT();
        },

        selectDateOccured(val){
            this.dateoccured = moment(val).format('YYYY-MM-DD');
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'dateoccured' && key != 'incidenttime'
                && key != 'incidenttype' && key != 'disciplinaryactionOptions'){
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
                if(key == 'disciplinaryaction' || key == 'disciplinaryaction1' || key == 'disciplinaryaction2'){
                    this.$data[key] = 'N/A';
                }
                if(key == 'creator_attach' || key == 'personinvolve_attach')
                this.$data[key] = [];
                
            });
            
            $("#myModal").modal("hide");

            $('textarea').height('100');
            
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
        },

        showMore(e){
            let element = $(e.target).prev('textarea')[0];
            // $(element).css("height", element.scrollHeight+3+"px");
            $(element).css("height", "200px");
            $(e.target).css("display", "none");
        },

        showLess(e){
            let element = $(e.target).prev('textarea')[0];
            $(element).css("height", element.scrollHeight+"px");
        },


    },
    computed:{
        showBlockMessageDetailSection(){
            return this.details && (this.$parent.disabledinput || (this.userinfo.empID !== this.selected.empID_ && this.selected.empID_));
        },
        showBlockMessageExplanationSection(){
            return this.explanation && (this.$parent.disabledinput || (this.userinfo.empID != this.selected.personsinvolve || !this.selected.personsinvolve));
        },

        showBlockMessageInitialApproverRemarks(){
            return this.$parent.$data.forapprover != 'approval' || (this.selected.approvedby && this.userinfo.empID != this.selected.approvedby);
        },
        
        showBlockMessageEndorserRemarks(){
            return this.$parent.disabledinput || this.$parent.$data.forapprover != 'approval';
        },

        disabledIfNoApprover(){
            return this.$parent.$data.forapprover != 'approval' && this.$parent.approvers && this.$parent.approvers.length < 1;
        },

        isFormValid(){
                return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        submitBtn()
        {
            return this.personsinvolve;
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