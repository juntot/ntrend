<style scoped>
.meeting-input{
    outline: 1px solid gray;
    padding: 6px;
    border-radius: 6px;
    width: 100%;
}
button i.fa-plus-circle{
  font-size: 1.5em;
  color: rgba(13, 110, 253, 1)
}
.mdb-table.table>tbody>tr>td{
  white-space: initial !important;
}
.attendee-list{
    display: inline-block;
    padding: 8px;
    border-radius: 8px;
    margin-left: 10px;
    position: relative;
}
.border-success{
    border: 1px solid green;
}

.border-danger{
    border: 1px solid red;
}

.attendee-list-btn{
    /* content: '\00D7'; */
    padding: 0px 6px;
    margin: 0;
    position: absolute;
    background: white;    
    color: rgba(0, 0, 0, .4);
    text-shadow: 0 1px 0 #fff;
    border-radius: 8px;
    cursor: pointer;
    top: -9px;
    right: -7px;
    -webkit-box-shadow: -1px 3px 8px -4px rgba(66, 68, 90, 1);
    -moz-box-shadow: -1px 3px 8px -4px rgba(66, 68, 90, 1);
    box-shadow: -1px 3px 8px -4px rgba(66, 68, 90, 1);
}
.float-right{
    float: right;
}

.table>thead>tr>th {
    min-width: 80px !important;
}
::-webkit-scrollbar:horizontal {
  height: 10px !important;
}

</style>
<template>
    <div>

            <form method="post">
                <h3 class="text-center form-title"><span class="dblUnderlined">{{ ($router.currentRoute.name).toUpperCase() }}</span></h3>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput || (selected.empID_ && userinfo.empID != selected.empID_)" type="text" class="form-field__input" v-model="meetingname" name="meeting name" v-validate="'required'">
                            <label class="form-field__label">Meeting Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('meeting name') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="datefiled | customDateFormat" name="name" readonly="true">
                            <label class="form-field__label">Date Created</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedActionStatus" name="name" readonly="true">
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedfullname" name="name" readonly="true">
                            <label class="form-field__label">Note Taker</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-limit">
                            <Datepicker :disabled="$parent.disabledinput || (selected.empID_ && userinfo.empID != selected.empID_)" :value="meetingdate" @selected="selectMeetingDate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                            <label slot="afterDateInput" class="form-field__label">Meeting Date</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                            </Datepicker>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput || (selected.empID_ && userinfo.empID != selected.empID_)" type="text" class="form-field__input" v-model="location" name="location" v-validate="'required'" >
                            <label class="form-field__label">Meeting Location</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('location') }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control form-field--is-filled">
                            <input :disabled="$parent.disabledinput || (selected.empID_ && userinfo.empID != selected.empID_)" type="time" name="starttime" v-model="starttime" class="form-field__input" >
                            <label class="form-field__label">Start Time</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('starttime') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control form-field--is-filled">
                            <input :disabled="$parent.disabledinput || (selected.empID_ && userinfo.empID != selected.empID_)" type="time" name="endtime" v-model="endtime" class="form-field__input" >
                            <label class="form-field__label">End Time</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('endtime') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="duration" name="name" readonly="true">
                            <label class="form-field__label">Duration</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <select v-model="meetingtype" name="meetingtype" v-validate="'required'" class="form-field__input" >
                                <option :value="item" v-for="(item, key) in meetingtypelist" :key="key">{{ item }}</option>
                            </select>
                            <label class="form-field__label">Meeting Type</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('meetingtype') }}</span>
                    </div>
                </div>
                <div class="col-md-8" v-if="!meetingID || userinfo.empID == selected.empID_">
                        <div class="mdb-form-field form-group-limitx">
                            <div class="relative-pos">
                                <div class="form-field__control">
                                    <input :disabled="$parent.disabledinput" type="text" v-model="search_employee" name="To"  class="form-field__input"
                                    @keyup.prevent="searchEmployee"
                                    >
                                    <label class="form-field__label">Attendees:</label>
                                    <div class="form-field__bar"></div>
                                </div>
                                <span class="errors" v-if="!search_employee">{{ errors.first('To') }}</span>
                                <div class="absolute-pos bg-white suggestion_filter" 
                                    v-if="search_employee && employeeList && employeeList.length > 0
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
                                            <p></p>
                                            {{emp.fullname}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <div v-if="userinfo.empID != selected.empID_">
                        <br>
                        <div class="form-field--is-filled form-field__label attendees">
                            <label class="form-field__label padding-15">List of Attendees:</label>
                        </div>
                    </div>
                    <div class="clearfix"></div>


                  <div :class="alist.acknowledge ? 'attendee-list bg-success': 'attendee-list bg-danger'" v-for="(alist, i) in attendeelist" :key="i">
                    {{ alist.fullname }}
                    <p v-if="userinfo.empID == selected.empID_ || !meetingID" class="attendee-list-btn" @click.prevent="removeAttendee(alist)">&times;</p>
                  </div>
                </div>
                <div class="clearfix"></div>

                <!-- 
                    ACKNOWELDGEMENT SECTION
                -->
                <div v-show="isAcknowledge">
                    <div class="col-md-12">
                        <h5 class="form-subtitle">
                            <em>AGENDA ITEMS</em>
                        </h5>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12">
                    <div class="mdb-table-overflow">
                        <table width="100%" class="table table-hover mdb-table" style="margin-bottom: 0">
                        <thead>
                            <tr>
                            <th>Agenda Item</th>
                            <th>Presenter Name</th>
                            <th>Remarks</th>
                            <th>Added By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(a, i) in agenda" :key="i">
                            <td>{{ a.agendaitem }}</td>
                            <td>{{ a.agendapresenter }}</td>
                            <td>
                                <a class="ann" href="#" @click.prevent="showPopOverAgendaRemarks(a, i)">
                                    <i class='fas fa-comment-alt' ></i>
                                </a>
                            </td>
                            <td>{{ a.agendaaddedby }}
                                <a v-if="userinfo.empID == selected.empID_ || !meetingID" @click="removeRow(i, 'agenda')" class="float-right">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            </td>
                            </tr>
                            <tr v-if="!$parent.disabledinput">
                            <td>
                                <input type="text" v-model="agendaitem" placeholder="Agenda Item" class="meeting-input">
                            </td>
                            <td>
                                <input type="text" v-model="agendapresenter" placeholder="Presenter" class="meeting-input">
                            </td>
                            <td>
                                <input type="text" v-model="agendaremarks" placeholder="Remarks" class="meeting-input">
                            </td>
                            
                            <td class="comment-actionx">
                                <button class="btn btn-primary" @click.prevent="addAgenda">
                                Add
                                </button>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>
                    <!-- ACTION ITEMS -->
                    <div class="col-md-12">
                        <h5 class="form-subtitle">
                            <em>ACTION ITEMS</em>
                        </h5>
                    </div>
                    <div class="col-md-12">
                        <div class="mdb-table-overflow">
                        <table width="100%" class="table table-hover mdb-table" style="margin-bottom: 0">
                        <thead>
                            <tr>
                            <th>Description</th>
                            <th>Responsible</th>
                            <th>Deadline</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Added By</th>
                            <th v-if="actionitems.length>0" >Options</th>
                            </tr>
                        </thead>
                        <tbody>  
                            <tr v-for="(a, i) in actionitems" :key="i">
                                <td>{{ a.actionitem }}</td>
                                <td>{{ a.actionresponsible }}</td>
                                <td>{{ a.actionduedate }}</td>
                                <td>
                                    <!-- action remarks -->
                                    <a class="ann" href="#" @click.prevent="showPopOverRemarks(a, i)">
                                        <i class='fas fa-comment-alt' ></i>
                                    </a>
                                </td>
                                <td>
                                    <select :disabled="userinfo.empID != selected.empID_" name="status" class="meeting-input" v-model="a.actionstatus" @change="changeSelectedActionObj(a, i)" placeholder="status">
                                        <option value="0">Pending</option>
                                        <option value="1">On Going</option>
                                        <option value="2">Completed</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    {{ a.actionaddedby }}
                                </td>
                                <td v-if="actionitems.length>0">
                                    <div class="text-center">
                                            <a class="ann" href="#" @click.prevent="showPopOver(a, i)">
                                                <!-- additional remarks -->
                                                <i class="fas fa-sticky-note"></i>
                                            </a>
                                            <a v-if="!meetingID || userinfo.empID == selected.empID_" @click="removeRow(i, 'actionitems')">
                                                <!-- remove -->
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!$parent.disabledinput || userinfo.empID == selected.empID_">
                            <td><input type="text" v-model="actionitem" placeholder="Item Description" class="meeting-input"></td>
                            <td><input type="text" v-model="actionresponsible" placeholder="Responsible" class="meeting-input"></td>
                            <td>
                                <Datepicker :disabled="$parent.disabledinput && userinfo.empID != selected.empID_" :value="actionduedate" wrapper-class="meeting-input" input-class="datePicker inline-input" placeholder="Select Date"
                                @selected="selectActionDueDate" :typeable="false" :format="'MM/dd/yyyy'">
                                    <!-- <div slot="afterDateInput" class="form-field__bar"></div> -->
                                </Datepicker>
                            </td>
                            <td colspan="">
                                <input type="text" v-model="actionremarks" placeholder="Remarks" class="meeting-input">
                            </td>
                            <td colspan="">
                                <select name="status" class="meeting-input" v-model="actionstatus" placeholder="status">
                                    <option value="0">Pending</option>
                                    <option value="1">On Going</option>
                                    <option value="2">Completed</option>
                                </select>
                                
                            </td>
                            <td class="comment-actionx" colspan="2">
                                <!-- <button class="btn btn-primary" @click.prevent="showPopOver">
                                Comments
                                </button> -->
                                <!-- <a class="ann" href="#" @click.prevent="showPopOver">
                                    <i class='fas fa-comment-alt' ></i>
                                </a> -->
                                <button class="btn btn-primary" @click.prevent="addActionItem">
                                Add
                                </button>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <h5 class="form-subtitle">
                            <em>ADDITIONAL NOTES</em>
                        </h5>
                    </div>
                    <div class="col-lg-12">
                        <h5 class="form-subtitle"></h5>
                    </div>
                    <div class="col-md-12">
                        <div class="mdb-form-field">
                                <div class="form-field__control mdb-bgcolor">
                                    <textarea class="form-field__textarea" cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
                                    <label class="form-field__label">Additional Notes</label>
                                    <div class="form-field__bar"></div>
                                </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <!-- <em style="color: red;" v-if="!witnesses_list">No witness is set. please contact HR Department &nbsp;&nbsp;</em> -->
                        <input type="submit" class="btn btn-primary fx-left" value="Print" @click.prevent="printForm">

                        <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addMeetingMinute" :disabled="disabledIfNoApprover || isDisable || !isFormValid " v-if="!meetingID">
                        <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateMeetingMinute" :disabled="isDisable || !isFormValid" v-if="meetingID && userinfo.empID == selected.empID_ && $parent.$data.forapprover != 'approval'">
                        <input type="submit" class="btn btn-primary" value="Acknowledge" @click.prevent="acknowledgeMeeting" :disabled="isDisable || !isFormValid" v-if="meetingID && userinfo.empID != selected.empID_ && $parent.$data.forapprover != 'approval'">
                        <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteMeetingMinute" :disabled="isDisable" v-if="!$parent.disabledinput && userinfo.empID == selected.empID_">
                        <!-- <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionSupplementary(2)" v-if="meetingID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel " :disabled="selected.status < 1">
                        <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionSupplementary(3)" v-if="meetingID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel" :disabled="selected.status < 1">
                        <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionSupplementary(1)" v-if="meetingID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel" :disabled="selected.status < 1"> -->
                    </div>


                    <!-- acknowledge failoverbutton -->
                    
                </div>
                <div class="modal-footer" v-if="!isAcknowledge">
                    <input type="submit" class="btn btn-primary" value="Acknowledge" @click.prevent="acknowledgeAttendance" >
                    <input type="submit" class="btn btn-primary" value="Decline" @click.prevent="declineAttendance" >
                </div>
                <!-- 
                    ACKNOWELDGEMENT SECTION END
                -->
            </form>


            <!-- pop over Agenda Remarks -->
            
            <div id="overlay-popup-agendaremarks" class="hide" style="z-index: 1100; position: fixed;top: 50%; right: 0;bottom: 0;left: 0;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="hidePopOverAgendaRemarks">&times;</button>
                            <h4 class="modal-title">Agenda Items Remarks</h4>
                        </div>
                        <div class="container"></div>
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label for="comment">Details:</label>
                                <textarea class="form-control" v-model="agendaremarkspopup" rows="5"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-default" @click.prevent="hidePopOverAgendaRemarks">Close</a>
                            <a href="#" class="btn btn-primary" @click.prevent="saveAgendaRemarks">Save</a>
                        </div>
                    </div>
                </div>
            </div>
             <!-- pop over Remarks-->
            <div id="overlay-popup-remarks" class="hide" style="z-index: 1100; position: fixed;top: 50%; right: 0;bottom: 0;left: 0;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="hidePopOverRemarks">&times;</button>
                            <h4 class="modal-title">Remarks</h4>
                        </div>
                        <div class="container"></div>
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label for="comment">Details:</label>
                                <textarea class="form-control" v-model="actionremarkspopup" rows="5"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-default" @click.prevent="hidePopOverRemarks">Close</a>
                            <a href="#" class="btn btn-primary" @click.prevent="saveRemarks">Save</a>
                        </div>
                    </div>
                </div>
            </div>


            <!-- pop over Additional Remarks-->
            <div id="overlay-popup" class="hide" style="z-index: 1100; position: fixed;top: 50%; right: 0;bottom: 0;left: 0;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="hidePopOver">&times;</button>
                            <h4 class="modal-title">Additional Remarks</h4>
                        </div>
                        <div class="container"></div>
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label for="comment">Details:</label>
                                <textarea class="form-control" v-model="actionadditionalremarks" rows="5"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-default" @click.prevent="hidePopOver">Close</a>
                            <a href="#" class="btn btn-primary" @click.prevent="saveAdditionalRemarks">Save</a>
                        </div>
                    </div>
                </div>
            </div>



<!-- print section -->
            <div id="printArea">
                <div class="col-lg-12">
                    <div class="text-center">
                        <img src="storage/app/public/images/comp_logo.png" alt="compay logo" style="width: 300px; height: auto;"/>
                        <br><br>
                        <p class="font-weight-bold" style="font-size: 25px;">{{ ($router.currentRoute.name).toUpperCase() }}</p>
                        <br><br>
                    </div>
                <h5 class="font-weight-bold">
                    <div style="color:gray;">    
                        <span style="display:inline-block; padding-right: 100px;">
                            DATE CLOSE: {{datetimeclose | customDateFormat}}
                        </span>
                        <span>
                            STATUS DURATION: {{ computedDateStatDuration }}
                        </span>
                    </div>
                </h5>
                </div>
                <table width="100%" class="transmittalTblFormPrint no-border-input">
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="usr">Meeting Name:</label>
                                <input type="text" class="form-control" :value="meetingname" :readonly="true">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="usr">Date Created:</label>
                                <input type="text" class="form-control" :value="datefiled | customDateFormat" :readonly="true">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="usr">Status:</label>
                                <input type="text" class="form-control" :value="computedActionStatus" :readonly="true">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="usr">Note Taker:</label>
                                <input type="text" class="form-control" :value="computedfullname" :readonly="true">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="usr">Meeting Date:</label>
                                <input type="text" class="form-control" :value="meetingdate" :readonly="true">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="usr">Meeting Location:</label>
                                <input type="text" class="form-control" :value="location" :readonly="true">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="usr">Start Time:</label>
                                <input type="text" class="form-control" :value="starttime" :readonly="true">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="usr">End Time:</label>
                                <input type="text" class="form-control" :value="endtime" :readonly="true">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="usr">Duration:</label>
                                <input type="text" class="form-control" :value="duration" :readonly="true">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="form-group">
                                <label for="usr">Meeting Type:</label>
                                <input type="text" class="form-control" :value="meetingtype" :readonly="true">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <label for="">Attendees:</label>
                            <div class="clearfix"></div>
                            <div :class="alist.acknowledge ? 'attendee-list border-success': 'attendee-list border-danger'" v-for="(alist, i) in attendeelist" :key="i">
                                {{ alist.fullname }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div class="bold-weight">
                                <label for="usr">AGENDA:</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="bordersx">
                        <td class="bold-weight">Agenda Item</td>
                        <td class="bold-weight">Presenter Name</td>
                        <td class="bold-weight">Remarks</td>
                        <td class="bold-weight">Added By</td>
                    </tr>
                    <tr v-for="(a, i) in agenda" :key="i" class="bordersx">
                            <td>{{ a.agendaitem }}</td>
                            <td>{{ a.agendapresenter }}</td>
                            <td>{{ a.agendaremarks }}</td>
                            <td>{{ a.agendaaddedby }}</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div class="bold-weight">
                                <label for="usr">ACTION ITEMS:</label>
                            </div>
                        </td>
                    </tr>
                    
                    <tr v-for="(a, i) in actionitems" :key="'a'+i" class="bordersx">
                        <td colspan="4">
                            <table width="100%" class="transmittalTblFormPrint no-border-input">
                            <tr class="bordersx">
                                <td class="bold-weight">Description</td>
                                <td class="bold-weight">Responsible</td>
                                <td class="bold-weight">Deadline</td>
                                <td class="bold-weight">Date Closed</td>
                                <td class="bold-weight">Aging</td>
                                <td class="bold-weight">Status</td>
                            </tr>
                            <tr>
                                
                                <td>{{ a.actionitem }}</td>
                                <td>{{ a.actionresponsible }}</td>
                                <td>{{ a.actionduedate }}</td>
                                <td>{{ a.actionitemclosedate || ''}}</td>
                                <td>{{  computedActionItemPrintAging(a) }}</td>
                                <td>{{ a.actionstatus == 1? 'On Going' : a.actionstatus == '2' ? 'Completed' : 'Pending' }}</td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <br>
                                    <div>
                                        
                                        <div>
                                            <label class="bold-weight" for="">Added By:</label>
                                            {{ a.actionaddedby }}
                                        </div>
                                        <label class="bold-weight" for="">Remarks:</label>
                                        {{ a.actionremarks }}
                                        <div>
                                            <label class="bold-weight" for="">Additional Remarks:</label>
                                            {{a.actionadditionalremarks || ''}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </table>
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="form-group">
                                <label for="usr" class="bold-weight">Remarks:</label>
                                <p>
                                    {{remarks}}
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
                
            </div>
    </div>
</template>
<script>

let excludeBody = [
            'isDisable',
            'agendaitem',
            'agendapresenter',
            'agendaremarks',
            'actionitem',
            'actionresponsible',
            'actionduedate',
            'actionremarks',
            'employeeList',
            'search_employee',
            'actionstatus',
            'showMeetingDetails',
            'actionadditionalremarks',
            'actionindex',
            'datetimeclose',
            'meetingtypelist',
            'agendaremarkspopup',
            'actionremarkspopup'

        ];
// let status = ['Pending', 'Approved', 'Rejected'];
let regex = new RegExp("^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9])|(24:?00))");
let witness_emails = [];
export default {
    props: ['userinfo', 'selected'],
    data() {
        return {
          isDisable: false,
          meetingname: '',
          datefiled: moment(new Date()).format('MM/DD/YYYY'),
          meetingdate: moment(new Date()).format('YYYY-MM-DD'),
          location: '',
          starttime: moment(new Date()).format('hh:mm'),
          endtime: moment(new Date()).add(30, 'minutes').format('hh:mm'),
          meetingtype: '',
          meetingtypelist: [
            'Weekly',
            'Monthly',
            'Quarterly',
            'Semi-Annual',
            'Marketing',
            'Sales',
            'Supply Chain',
            'Product Management',
            'Warehouse & Logistics',
            'Finance',
            'Admin',
            'HR',
            'Others'
          ],
          agenda: [],
          agendaitem:'',
          agendapresenter:'',
          agendaremarks:'',
          agendaremarkspopup: '',

          actionitems: [],
          actionitem:'',
          actionresponsible:'',
          actionduedate: '',
          actionremarks: '',
          actionremarkspopup: '',
          actionindex: '',
          actionadditionalremarks:'',

          employeeList: [],
          attendeelist:[],
          search_employee: '',

          remarks: '',
          meetingID: '',
          actionstatus: 0,
          status: 0,
          showMeetingDetails: false,
          datetimeclose: '',
        }
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    filters: {
        customDateFormat: function (value) {
            if (!value) return ''
            value = new Date(value);
            return moment(value).format('MM/DD/YYYY');
        },
        filterActionStatus: function (value) {
            return value < 1? 'Pending': value == 1? 'Ongoing': 'Completed';
        },

    },
    methods:{
        async printForm(){
            let elem = document.getElementById("printArea");
            let domClone = elem.cloneNode(true);
            
            let $printSection = document.createElement("div");
            $printSection.id = "printSection";
            
            // let $printSection = document.getElementById("printSection");

            await $printSection.appendChild(domClone);
            document.body.appendChild($printSection);

            window.print();
        },

        acknowledgeAttendance(){
            let params = {};
            this.attendeelist.map(obj=>{
                if(obj.empID == this.userinfo.empID)
                obj.acknowledge = true;
                return obj;
            });

            for (const key in this.$data) {
                if (!excludeBody.includes(key)) {
                    if(key == 'attendeelist' || key == 'check_tbl' || 
                        key == 'agenda' || key == 'actionitems'
                    ){
                        params[key] = JSON.stringify(this.$data[key]);
                    }else {
                        params[key] = this.$data[key];
                    }
                    
                }
            }
            this.showMeetingDetails = true;
            params['fullname'] = this.computedfullname;
            // params['empID_'] = this.selected.empID_;
            params['reciever_emails'] = this.attendeelist.map(obj=>obj.email);
            params['attendeelistId'] = this.attendeelistId.toString();
            // params['status'] = (this.computedActionStatus).toLowerCase() == 'open'? 0: 1;
            
            axios.post('api/acknow-minute-meeting', params).then((response)=>{
                    this.$parent.updateRow(params);
                    // this.closeModal();
            }).catch((err)=>{console.log(err);});
        },
        declineAttendance(){
            this.attendeelist = this.attendeelist.filter(obj=>obj.empID != this.userinfo.empID);
            // update the request
            this.isDisable = true;
            let params = {};
            this.attendeelist.map(obj=>{
                if(obj.empID == this.userinfo.empID)
                obj.acknowledge = true;
                return obj;
            });

            for (const key in this.$data) {
                if (!excludeBody.includes(key)) {
                    if(key == 'attendeelist' || key == 'check_tbl' || 
                        key == 'agenda' || key == 'actionitems'
                    ){
                        params[key] = JSON.stringify(this.$data[key]);
                    }else {
                        params[key] = this.$data[key];
                    }
                    
                }
            }
            params['fullname'] = this.computedfullname;
            params['reciever_emails'] = this.attendeelist.map(obj=>obj.email);
            params['attendeelistId'] = this.attendeelistId.toString();
            axios.post('api/rem-attendance-meeting', params).then((response)=>{
                this.$parent.deleteRow(this.meetingID);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },
        selectActionDueDate(val){
            this.actionduedate = moment(val).format('MM/DD/YYYY');
        },
        selectMeetingDate(val){
            this.meetingdate = moment(val).format('YYYY-MM-DD');

        },
        
        searchEmployee(){
          this.loader = false;
          let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
          let regex = RegExp(validSearch);
                this.sales_employee = '';
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
                    
                    
            axios.post('api/search-meeting-emp',{keyword: search}).then(res=>{
              if(res.data.length > 0 && res.status == 200){
                this.employeeList = res.data;
              }
                        this.loader = false;
            })
            .catch(err => this.errMsg = 'Network problem please contact your IT-Department');
          }

        },
        selectedEmployee(val) {
            
                this.search_employee = '';
                if(!this.attendeelist.find(obj=>obj.empID == val.empID))
                this.attendeelist.push({...val, acknowledge: false});
                
        },
        removeAttendee(val){
          this.attendeelist = this.attendeelist.filter(obj=>obj.empID != val.empID);
        },
        addAgenda(){
          if(!this.agendaitem || !this.agendapresenter || !this.agendaremarks)
          return;
          let items = {
            agendaitem: this.agendaitem,
            agendapresenter: this.agendapresenter,
            agendaremarks: this.agendaremarks,
            agendaaddedby: this.userinfo.fullname,
            agendaaddedbyID: this.userinfo.empID,
          };
          
          this.agenda.push(items);
          this.agendaitem = '';
          this.agendapresenter = '';
          this.agendaremarks = '';
        },
        addActionItem(){
          if(!this.actionitem || !this.actionresponsible || !this.actionduedate || !this.actionremarks)
          return;

          let actionclosedate = '';
          if(Number(this.actionstatus) > 1)
          actionclosedate = moment(new Date()).format('MM/DD/YYYY');


          let items = {
            actionitem: this.actionitem,
            actionresponsible: this.actionresponsible,
            actionduedate: this.actionduedate,
            actionremarks: this.actionremarks,
            actionstatus: this.actionstatus,
            actionaddedby: this.userinfo.fullname,
            actionaddedbyID: this.userinfo.empID,
            actionitemclosedate: actionclosedate,
          };
          
          this.actionitems.push(items);
          this.actionitem = '';
          this.actionresponsible = '';
          this.actionduedate = '';
          this.actionremarks = '';
          this.actionstatus = 0;
        },
        changeSelectedActionObj(obj, index){
            let actionclosedate = '';
            if(Number(obj.actionstatus) > 1){
                obj['actionitemclosedate'] = moment(new Date()).format('MM/DD/YYYY');
            }else{
                obj['actionitemclosedate'] = '';
            }
            
            this.actionitems[index] = obj;
        },
        removeRow(index, entries = 'agenda')
        {
            if(entries == 'agenda')
            this.agenda.splice(index, 1);

            if(entries == 'actionitems')
            this.actionitems.splice(index, 1);
        },
        confirmWitness(){
          
            let data = {
                meetingID: this.meetingID,
            };
            if(this.witnesses && (this.witnesses.slice(2)).split(',').length > 0){
                data['approver_count'] = 2;
            }
            data['empID_'] = this.selected.empID_;
            data['reciever_emails'] = this.$parent.reciever_emails;
            axios.post('api/confirm-as-witness', data )
            .then(response=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            })
            .catch(er=>alert('Server Error'));
        },
        addMeetingMinute(){
            
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = {};
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        if(key == 'attendeelist' || key == 'check_tbl' || 
                           key == 'agenda' || key == 'actionitems'
                        ){
                            params[key] = JSON.stringify(this.$data[key]);
                        }else {
                            params[key] = this.$data[key];
                        }
                        
                    }
                }
                params['fullname'] = this.computedfullname;
                params['reciever_emails'] = this.attendeelist.map(obj=>obj.email);
                params['attendeelistId'] = this.attendeelistId.toString();
                params['status'] = (this.computedActionStatus).toLowerCase() == 'open'? 0: 1;
                axios.post('api/add-minute-meeting', params).then((response)=>{
                    // this.isDisable = false;
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{
                    // this.isDisable = false;
                    console.log(err);
                });
            }
        },
        updateMeetingMinute(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = {};
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        if(key == 'attendeelist' || key == 'check_tbl' || 
                           key == 'agenda' || key == 'actionitems'
                        ){
                            params[key] = JSON.stringify(this.$data[key]);
                        }else {
                            params[key] = this.$data[key];
                        }
                        
                    }
                }
                params['empID_'] = this.selected.empID_;
                params['fullname'] = this.computedfullname;
                params['reciever_emails'] = this.attendeelist.map(obj=>obj.email);
                params['attendeelistId'] = this.attendeelistId.toString();
                params['status'] = (this.computedActionStatus).toLowerCase() == 'open'? 0: 1;
                
                axios.post('api/update-minute-meeting', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        acknowledgeMeeting(){

            this.isDisable = true;
            let params = {};
            this.attendeelist.map(obj=>{
                if(obj.empID == this.userinfo.empID)
                obj.acknowledge = true;
                return obj;
            });

            for (const key in this.$data) {
                if (!excludeBody.includes(key)) {
                    if(key == 'attendeelist' || key == 'check_tbl' || 
                        key == 'agenda' || key == 'actionitems'
                    ){
                        params[key] = JSON.stringify(this.$data[key]);
                    }else {
                        params[key] = this.$data[key];
                    }
                    
                }
            }
            params['empID_'] = this.selected.empID_;
            params['fullname'] = this.computedfullname;
            params['reciever_emails'] = this.attendeelist.map(obj=>obj.email);
            params['attendeelistId'] = this.attendeelistId.toString();
            params['status'] = (this.computedActionStatus).toLowerCase() == 'open'? 0: 1;
            
            axios.post('api/acknow-minute-meeting', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
            }).catch((err)=>{console.log(err);});
        },
        deleteMeetingMinute(){
            this.isDisable = true;
            axios.get('api/del-minute-meeting/'+this.meetingID).then((response)=>{
                this.$parent.deleteRow(this.meetingID);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },
        appendTable(){

        },
        // ACTIONS FOR Supplementary I.E APPROVE / REJECT / CANCEL
        requestActionSupplementary(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$root.$data.userinfo.empID;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformSupplementary', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i == 'meetingtypelist')
                continue; 

                if(i == 'agenda' || i == 'attendeelist' || i == 'actionitems'){
                    this.$data[i] = JSON.parse(data[i]);
                }
                else if(i == 'actionstatus'){
                    this.$data[i] == 0;

                }
                else{
                    this.$data[i] = data[i];
                }
            }

            this.MDBINPUT();
            $("#myModal").modal("show");
        },
        closeModal(){
            let obj = this.$data;
            $('#printSection').remove();
            Object.keys(obj).forEach((key)=>{
                if(key == 'witnesses' || key == 'meetingID' || key == 'meetingname' || 
                   key == 'location' || key == 'remarks'){
                    this.$data[key] = '';
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                if(key == 'agenda' || key == 'attendeelist' || key == 'actionitems' || 
                   key == 'employeeList')
                {
                    this.$data[key] = [];
                }
                if(key === 'datefiled'){
                    this.$data[key] = moment(new Date()).format('MM/DD/YYYY');
                }
                if(key === 'actionstatus' || key == 'status'){
                    this.$data[key] = 0;
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
        },
        // agenda Remarks popup
        showPopOverAgendaRemarks(item, index){
            this.actionindex = index;
            this.agendaremarkspopup = item.agendaremarks || '';
            $('#overlay-popup-agendaremarks').toggleClass('hide');
        },
        hidePopOverAgendaRemarks(){
            $('#overlay-popup-agendaremarks').toggleClass('hide');
        },
        saveAgendaRemarks(){
            this.agenda[this.actionindex]['agendaremarks'] = this.agendaremarkspopup;
            $('#overlay-popup-agendaremarks').toggleClass('hide');
        },
        // pop up action remarks
        showPopOverRemarks(item, index){
            this.actionindex = index;
            this.actionremarkspopup = item.actionremarks || '';
            $('#overlay-popup-remarks').toggleClass('hide');
        },
        hidePopOverRemarks(){
            $('#overlay-popup-remarks').toggleClass('hide');
        },
        saveRemarks(){
            this.actionitems[this.actionindex]['actionremarks'] = this.actionremarkspopup;
            $('#overlay-popup-remarks').toggleClass('hide');
        },
        
        // addiontal remarks
        showPopOver(item, index){
            this.actionindex = index;
            this.actionadditionalremarks = item.actionadditionalremarks || '';
            $('#overlay-popup').toggleClass('hide');
        },
        hidePopOver(){
            $('#overlay-popup').toggleClass('hide');
        },
        saveAdditionalRemarks(){
            this.actionitems[this.actionindex]['actionadditionalremarks'] = this.actionadditionalremarks;
            $('#overlay-popup').toggleClass('hide');
        },
        computedActionItemPrintAging(action = ''){
            if(action.actionduedate == '')
            return;

            if((action.actionstatus == '2' || action.actionstatus == 2) && !action.actionitemclosedate)
            return;

            if((action.actionstatus == '2' || action.actionstatus == 2) && action.actionitemclosedate)
            return parseInt(moment.duration(moment(new Date(action.actionitemclosedate)).diff(moment(new Date(action.actionduedate)))).asDays());

            return parseInt(moment.duration(moment(new Date()).diff(moment(new Date(action.actionduedate)))).asDays());
        },

    },
    computed:{
        
        computedDateStatDuration(){
            if(!this.datetimeclose)
            return '';

            let from1 =  new Date(this.datefiled);
            let to1 = new Date(this.datetimeclose);
            var ms1 = moment(to1,"YYYY-MM-DD HH:mm:ss").diff(moment(from1,"YYYY-MM-DD HH:mm:ss"));
            var d1 = moment.duration(ms1);

            var s1 = Math.floor(d1.asHours()) + moment.utc(ms1).format(":mm:ss");
            
            s1 = s1.slice(0, (s1.indexOf(':'))).length <= 1 ? '0'+s1 : s1;
            // slice the seconds
            s1 = s1.slice(0, 5);
            let duration1 =  s1 || '';
            return duration1;
        },
        isAcknowledge(){
            // show if attendees acknowledge
            // hide if not creator
            let findAttendee = this.attendeelist.find((a)=>a.empID == this.userinfo.empID && a.acknowledge == true);
            return !this.meetingID || this.showMeetingDetails || findAttendee || this.selected.empID_ == this.userinfo.empID;
        },
        attendeelistId(){
            return this.attendeelist.map(obj=>{
                return obj.empID;
            })
        },
        duration(){
            let from =  new Date(moment().format('YYYY-MM-DD')+' '+ this.starttime);
            let to = new Date(moment().format('YYYY-MM-DD')+' '+ this.endtime);
            var ms = moment(to,"YYYY-MM-DD HH:mm:ss").diff(moment(from,"YYYY-MM-DD HH:mm:ss"));
            var d = moment.duration(ms);
            
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
            
            s = s.slice(0, (s.indexOf(':'))).length <= 1 ? '0'+s: s;
            // slice the seconds
            s = s.slice(0, 5);
            return s || '';
        },
        disabledIfNoApprover(){
            return false;
            return this.$parent.$data.forapprover != 'approval' && this.$parent.approvers && this.$parent.approvers.length < 1;
        },
        confirm_witness(){
            // return this.witnesses.slice(2);
            return this.witnesses;
        },
        isFormValid(){
            return !Object.keys(this.fields).some((key )=>{
                if(key != 'timein' && key != 'timeout' && key != 'reason')
                {
                   return this.fields[key].invalid
                }
            });
        },
        computedActionStatus(){
            if(this.actionitems.length < 1)
            return 'Open';
            return this.actionitems.find(obj=>obj.actionstatus < 2) ? 'Open' : 'Closed'; 
        },
        hasEntries(){
            // return this.entries.length > 0;
        },
        submitBtn()
        {
            // return !this.meetingID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            // return this.meetingID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput
        },
        approveRejecBtn(){
            // return this.meetingID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            // return this.meetingID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
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
        // VeeValidate.Validator.extend('is_time', {
        //     getMessage: field => `The format must be HH:MM AM/PM`,
        //     validate: (value) => new Promise(resolve => {
        //         let regex = new RegExp("^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9])|(24:?00))");
        //         resolve({
        //             valid: value && regex.test(value)
        //         });
        //     })
        // });

    },
    beforeDestroy(){
        bus.$off('setupdate', this.setDataForEdit)
    },
    mounted(){
        axios.get('api/get-witness-list')
        .then(res=>{
            res.data.forEach(w=>{
                this.witnesses_list += w.fname+' '+w.lname+', ';
                witness_emails.push(w.email);
            })

        }).catch(err=>alert('Meeting Minutes Server Error'));
        this.MDBINPUT();

        if(this.$parent.disabledinput){
            $('.vdp-datepicker div.vdp-datepicker__calendar:nth-child(2)').addClass('disable-dates-approver');
        }

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