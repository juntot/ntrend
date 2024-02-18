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

            <form method="post" >
                <h3 class="text-center form-title"><span class="dblUnderlined">MINUTES OF MEETING</span></h3>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="meetingname" name="meeting name" v-validate="'required'">
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
                            <Datepicker :disabled="$parent.disabledinput" :value="meetingdate" @selected="selectMeetingDate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                            <label slot="afterDateInput" class="form-field__label">Meeting Date</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                            </Datepicker>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="location" name="location" v-validate="'required'" >
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
                            <input :disabled="$parent.disabledinput" type="time" name="starttime" v-model="starttime" class="form-field__input" >
                            <label class="form-field__label">Start Time</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('starttime') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control form-field--is-filled">
                            <input :disabled="$parent.disabledinput" type="time" name="endtime" v-model="endtime" class="form-field__input" >
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
                            <input type="text" class="form-field__input" :value="meetingtype" name="name" readonly="true">
                            <label class="form-field__label">Meeting Type</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
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
                  <div class="attendee-list bg-info" v-for="(alist, i) in attendeelist" :key="i">
                    {{ alist.fullname }}
                  </div>
                </div>
                <div class="clearfix"></div>
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
                          <td>{{ a.agendaremarks }}</td>
                          <td>{{ a.agendaaddedby }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
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
                          <th>Item Description</th>
                          <th>Responsible</th>
                          <th>Deadline</th>
                          <th>Remarks</th>
                          <th>Status</th>
                          <th>Added By</th>
                        </tr>
                      </thead>
                      <tbody>  
                        <tr v-for="(a, i) in actionitems" :key="i">
                            <td>{{ a.actionitem }}</td>
                            <td>{{ a.actionresponsible }}</td>
                            <td>{{ a.actionduedate }}</td>
                            <td>{{ a.actionremarks }}</td>
                            <td>
                                <select :disabled="true" name="status" class="meeting-input" v-model="a.actionstatus" placeholder="status" >
                                    <option value="0">Pending</option>
                                    <option value="1">On Going</option>
                                    <option value="2">Completed</option>
                                </select>
                            </td>
                            <td>{{ a.actionaddedby }}
                            </td>
                        </tr>
                      </tbody>
                      </table>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <h5 class="form-subtitle">
                        <em>OTHER NOTES</em>
                    </h5>
                </div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                </div>
                <div class="col-md-12">
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea class="form-field__textarea" id="" cols="4" rows="4" v-model="remarks" name="additional-info" readonly></textarea>
                                <label class="form-field__label">Remarks</label>
                                <div class="form-field__bar"></div>
                            </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                    <div class="modal-footer">
                        <!-- <em style="color: red;" v-if="!witnesses_list">No witness is set. please contact HR Department &nbsp;&nbsp;</em> -->
                        <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addMeetingMinute" :disabled="disabledIfNoApprover || isDisable || !isFormValid " v-if="!meetingID">
                        <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateMeetingMinute" :disabled="isDisable || !isFormValid" v-if="meetingID && $parent.$data.forapprover != 'approval'">
                        <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteMeetingMinute" :disabled="isDisable" v-if="!$parent.disabledinput && userinfo.empID == selected.empID_"> -->
                        <!-- <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionSupplementary(2)" v-if="meetingID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel " :disabled="selected.status < 1">
                        <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionSupplementary(3)" v-if="meetingID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel" :disabled="selected.status < 1">
                        <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionSupplementary(1)" v-if="meetingID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel" :disabled="selected.status < 1"> -->
                    </div>
            </form>
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
        ];
// let status = ['Pending', 'Approved', 'Rejected'];
let regex = new RegExp("^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9])|(24:?00))");
let witness_emails = [];
export default {
    props: ['userinfo', 'selected'],
    data() {
        return {
          formtitle: '',
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

          actionitems: [],
          actionitem:'',
          actionresponsible:'',
          actionduedate: '',
          actionindex: '',
          actionremarks: '',

          employeeList: [],
          attendeelist:[],
          search_employee: '',

          remarks: '',
          meetingID: '',
          actionstatus: 0,
          status: 0,
          showMeetingDetails: false,
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
        }
    },
    methods:{
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
                this.attendeelist.push(val);
                
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

          let items = {
            actionitem: this.actionitem,
            actionresponsible: this.actionresponsible,
            actionduedate: this.actionduedate,
            actionremarks: this.actionremarks,
            actionstatus: this.actionstatus,
            actionaddedby: this.userinfo.fullname,
            actionaddedbyID: this.userinfo.empID,
          };
          
          this.actionitems.push(items);
          this.actionitem = '';
          this.actionresponsible = '';
          this.actionduedate = '';
          this.actionremarks = '';
          this.actionstatus = 0;
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
        deleteMeetingMinute(){
            this.isDisable = true;
            axios.get('api/del-minute-meeting/'+this.meetingID).then((response)=>{
                this.$parent.deleteRow(this.meetingID);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },
        appendTable(){
                // this.$validator.validate('timein', this.timein);
                // this.$validator.validate('timeout', this.timeout);

                // this.$validator.validate('reason', this.reason);

                // if(regex.test(this.timein) && regex.test(this.timeout) && this.reason !='')
                // {
                    // this.entries.push({
                    //     supdate: this.supdate,
                    //     timein: this.timein? moment(this.timein, ["HH:mm"]).format('hh:mm A') : '',
                    //     timeout: this.timeout? moment(this.timeout, ["HH:mm"]).format('hh:mm A') : '',
                    //     timein2: this.timein2? moment(this.timein2, ["HH:mm"]).format('hh:mm A') : '',
                    //     timeout2: this.timeout2? moment(this.timeout2, ["HH:mm"]).format('hh:mm A') : '',
                    //     reason: this.reason
                    // });
                    // this.reason = '';
                    // this.supdate = moment(new Date()).format('YYYY/MM/DD');
                    // this.timein =  '';
                    // this.timeout = '';
                    // this.timein2 = '';
                    // this.timeout2 = '';

                // }

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
            $("#meetingModal").modal("show");
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key == 'witnesses' || key == 'meetingID' || key == 'meetingname' || 
                   key == 'location' || key == 'remarks' ){
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
            $(".modal-backdrop").remove();
            // $("#meetingModal").modal("hide");
            

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
        this.formtitle = this.$route.name?  this.$route.name.toUpperCase() : '';

        this.MDBINPUT();
        // MODAL
        $('#meetingModal').on("hidden.bs.modal", this.closeModal);
        
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