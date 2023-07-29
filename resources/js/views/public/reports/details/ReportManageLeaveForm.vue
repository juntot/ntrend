<template>
    <div>

            <form method="post" >
                <h3 class="text-center form-title"><span class="dblUnderlined" >LEAVE APPLICATION FORM</span></h3>
                <div class="col-md-12">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="datefiled" name="datefiled" readonly="true">
                            <label class="form-field__label">Date Filed</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="computedfullname" name="fullname" readonly="true">
                            <label class="form-field__label">Full Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="computedposname" name="position" readonly="true">
                            <label class="form-field__label">Position</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="computedbranchname" name="branch" readonly="true">
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="col-md-12 col-lg-12">
                        <h5 class="form-subtitle"><em>Date/s of Leave</em></h5>
                </div>
                <div class="col-md-4">
                            <div class="form-group-limit">
                                    <Datepicker :value="datestart" @selected="selectDateStart" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                    <label slot="afterDateInput" class="form-field__label">Date Start</label>
                                    <div slot="afterDateInput" class="form-field__bar"></div>
                                    <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                    </Datepicker>
                            </div>
                </div>
                <div class="col-md-4">
                            <div class="form-group-limit">
                                    <Datepicker :value="dateend" @selected="selectDateEnd" wrapper-class="mdb-form-field" input-class="form-field__input datePicker"
                                    :typeable="false" :format="'MM/dd/yyyy'">
                                    <label slot="afterDateInput" class="form-field__label">Date End</label>
                                    <div slot="afterDateInput" class="form-field__bar"></div>
                                    <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                                    </Datepicker>
                            </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="getDiff" name="branch" readonly="true">
                                <label class="form-field__label">Total (days)</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div>
                        <label class="mdblblradio">
                        <span class="checklbl" >Whole-day</span>
                        <input :disabled="$parent.disabledinput" type="radio" @change="handleChangeHalfday" v-model="halfday" value="0" name="halfday" checked="checked" >
                        <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <label class="mdblblradio">
                        <span class="checklbl" >Half-day AM</span>
                        <input :disabled="$parent.disabledinput" type="radio" @change="handleChangeHalfday" v-model="halfday" value="1" name="halfday" checked="checked" >
                        <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <label class="mdblblradio">
                        <span class="checklbl" >Half-day PM</span>
                        <input :disabled="$parent.disabledinput" type="radio" @change="handleChangeHalfday" v-model="halfday" value="2" name="halfday"  >
                        <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Type of Leave: (Please check (/) appropriate leave to file)</em></h5>
                </div>
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div @click="hasCredits('SL')">
                            <label class="mdblblradio">
                            <span class="checklbl" >Sick Leave</span>
                            <input :disabled="$parent.disabledinput || disableSL" type="radio" @change="handleChange" v-model="leavetype" value="1" name="radio" checked="checked" >
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div @click="hasCredits('BL')">
                            <label class="mdblblradio">
                            <span class="checklbl">Birthday Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType || disableBL" type="radio" @change="handleChange" v-model="leavetype" value="2" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div >
                            <label class="mdblblradio">
                            <span class="checklbl">Leave w/out pay</span>
                            <input :disabled="$parent.disabledinput" type="radio" @change="handleChange" v-model="leavetype" value="3" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Bereavement Leave</span>
                            <input :disabled="$parent.disabledinput" type="radio" @change="handleChange" v-model="leavetype" value="4" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div @click="hasCredits('VL')">
                            <label class="mdblblradio">
                            <span class="checklbl">Vacation Leave</span>
                            <input :disabled="$parent.disabledinput || $parent.disableLeaveType || disableVL" type="radio" @change="handleChange" v-model="leavetype" value="5" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div @click="hasCredits('DL')">
                            <label class="mdblblradio">
                            <span class="checklbl">Discretionary Leave</span>
                            <input :disabled="$parent.disabledinput || disableDL" type="radio" @change="handleChange" v-model="leavetype" value="6" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Maternity Leave</span>
                            <input :disabled="$parent.disabledinput" type="radio" @change="handleChange" v-model="leavetype" value="7" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Paternity Leave</span>
                            <input :disabled="$parent.disabledinput" type="radio" @change="handleChange" v-model="leavetype" value="8" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Others (please specify)</span>
                            <input :disabled="$parent.disabledinput" type="radio" @change="handleChange" v-model="leavetype" value="9" name="radio">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="$parent.disabledinput" class="form-field__textarea" cols="4" rows="4" v-validate="'required'" v-model="reason" name="additional-info"></textarea>
                                <label class="form-field__label">Reasons to leave</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
                </div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle">
                        <em class="text-center relative-pos">
                            <div class="col-md-5 text-left nopadding">FOR APPROVER</div>
                            <div class="col-md-7 text-left nopadding" v-show="$parent.disabledinput">
                                <!-- LEAVE CALENDAR <i class="far fa-calendar-alt" style="cursor: pointer" ></i> -->
                            </div>
                            <div class="clearfix"></div>
                        </em>
                    </h5>
                </div>
                <!-- <div class="clearfix"></div> -->
                <!-- <div class="calendar-slot" v-show="showCalendar">
                    <slot></slot>
                </div> -->
                <div class="clearfix"></div>
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
                    <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addLeave" :disabled="isDisable || !isFormValid" v-if="submitBtn">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateLeave" :disabled="isDisable || !isFormValid" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteLeave" :disabled="isDisable" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionLeave(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionLeave(2)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionLeave(0)" v-if="cancelBtn"> -->
                </div>
            </form>


    </div>
</template>
<script>
// let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
let leavetype = [
    'Sick Leave', 'Birthday Leave', 'Leave w/out Pay', 'Bereavement Leave', 'Vacation Leave',
    'Descritionary Leave', 'Maternity Leave', 'Paternity Leave', 'Others'
    ];
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
			isDisable: false,
			datefiled: moment(new Date()).format('MM/DD/YYYY'),
			datestart: moment(new Date()).format('MM/DD/YYYY'),
			dateend:   moment(moment(new Date()).add(1, 'days')).format('MM/DD/YYYY'),
			totaldays: 1,
			leavetype: 3, //1 sickleave, 2birth leave, 3leave without pay, 4breavementleave, 5vacationleave, 6others
            reason: '',
            leaveID: '',
            approvedby: '',
            remarks: '',
            halfday: 0,

			// reqstat: 0, //0 pending, 1//approve //2 declined
			// status: 0, //0 open, 1//close

		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        },

    },
    methods:{
        // toggleCalendar(){
        //     this.$parent.Aws();
        // },
        handleChangeHalfday(){
            // this.totaldays -=.5;
        },
        async handleChange(e){

            if(this.isLessThan7Days && this.leavetype == 2 || this.isLessThan7Days && this.leavetype == 5){
                if(confirm(`You filed your leave less than 7 Days from Date Filed. Due to that, it will be considered as leave without pay. Do you want to proceed?`)){

                    this.leavetype = await 3;
                    return;

                }else{
                    $("#leaveModal").modal("hide");
                }
            }
            // if(this.leavetype !=3 && this.leavetype != 1 && this.leavetype != 4){
                if(this.greater30Days && this.leavetype != 3 && this.greater30Days && this.leavetype != 1 && this.greater30Days() && this.leavetype != 4){
                    alert('3 days leave and up should be filed 30 days before the date filed');
                    this.leavetype = await 3;
                    return;
                }
            // }

        },

        addLeave(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                this.totaldays = this.getDiff;
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addleave', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                    location.href = "/terms-and-conditions";
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
            params['old_status'] = this.selected.status;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
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
                if(i == 'leavetype')
                    this.$data[i] = (leavetype.indexOf(data[i]) + 1);


            }

            this.MDBINPUT();
            $("#leaveModal").modal("show");
        },

        async selectDateStart(val){
            this.datestart = await moment(val).format('MM/DD/YYYY');

            if(this.isLessThan7Days && this.leavetype == 2 || this.isLessThan7Days && this.leavetype == 5){
                if(confirm(`You filed your leave less than 7 Days from Date Filed. Due to that, it will be considered as leave without pay. Do you want to proceed?`)){

                    this.leavetype = await 3;
                    return;

                }else{
                    $("#leaveModal").modal("hide");
                }
            }
            if(this.greater30Days && this.leavetype !=3 && this.greater30Days && this.leavetype != 1 && this.greater30Days && this.leavetype != 4){
                alert('3 days leave and up should be filed 30 days before the date filed');
                this.leavetype = await 3;
                return;
            }

        },
        async changeDateEnd(val){

        },
        async selectDateEnd(val){
            // if(!this.$parent.disabledinput)
            this.dateend = await moment(val).format('MM/DD/YYYY');

            // validation
            // get total date days from start start and date filed
            let datestart =  new Date(this.datestart);
            let dateend = new Date(this.datefiled);
            let startDate = moment(datestart, 'YYYY-MM-DD');
            let endDate = moment(dateend, 'YYYY-MM-DD');
            // let totaldays = endDate.diff(startDate, 'days');
            let totaldays_from_start_and_filed = await startDate.diff(endDate, 'days');
            totaldays_from_start_and_filed += 1;

            // get total days from date start and date end
            datestart =  new Date(this.datestart);
            dateend = new Date(this.dateend);
            startDate = moment(datestart, 'YYYY-MM-DD');
            endDate = moment(dateend, 'YYYY-MM-DD');
            let totaldays = await endDate.diff(startDate, 'days');
            totaldays += 1;



            if(totaldays >= 3 && totaldays_from_start_and_filed < 30 ){
                if(this.leavetype !=3 && this.leavetype != 1){
                    alert('3 days leave and up should be filed 30 days before the date filed');
                    this.leavetype = await 3;
                    return;
                }
            }




            // this.validate30Days();
            // this.validateLess7Days();
            // if(this.greater30Days){
            //     alert('30days');
            //     this.leavetype = 3;
            // }
        },

        validateLess7Days(){

            if(this.isLessThan7Days){
                if(confirm(`You filed your leave less than 7 Days from Date Filed. Due to that, it will be considered as leave without pay. Do you want to proceed?`)){
                    // console.log('confirm..');
                    this.leavetype = 3;
                    this.$parent.disableLeaveType = true;
                    // return true;
                }else{
                    // console.log('not confirm');
                    $("#leaveModal").modal("hide");

                }
            }else{

                this.$parent.disableLeaveType = false;
                // this.validate30Days();
                // return false;
            }
        },
        validate30Days(){

            if(this.greater30Days){
                alert('30 days');
                this.leavetype = 3;
                this.$parent.disableLeaveType = true;
            }
        },
        closeModal(){
            this.$parent.disableLeaveType = false;
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'datestart' && key != 'dateend'){
                    this.$data[key] =  '';
                }
                if(key == 'halfday')
                {
                    this.$data[key] = 0;
                }
                if(key == 'totaldays')
                {
                    this.$data[key] = 1;
                }
                if(key == 'leavetype'){
                    this.$data[key] = 3;
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                if(key == 'datefiled' || key == 'datestart'){
                    this.$data[key] = moment(new Date()).format('MM/DD/YYYY');
                }
                if(key == 'dateend'){
                    this.$data[key] = moment(moment(new Date()).add(1, 'days')).format('MM/DD/YYYY');
                }

            });

            $("#leaveModal").modal("hide");

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
        customFormatter(date) {
           return moment(date).format('MM/dd/yyyy');
        },
        hasCredits(type = ''){
            switch(type){
                case 'VL':
                    return this.disableVL? alert('no available credits'): '';
                    break;
                case 'SL':
                    return this.disableSL? alert('no available credits'): '';
                    break;
                case 'BL':
                    return this.disableBL? alert('no available credits'): '';
                    break;
                case 'DL':
                    return this.disableDL? alert('no available credits'): '';
                    break;
                default:
                    break;
            }
            alert(type);
        },


    },
    computed:{
        isValidType(){
            const validTypes = [1, 3, 4, 6, 7, 8, 9];
            return validTypes.includes(parseInt(this.leavetype));
        },
        isLessThan7Days(){

            let datestart =  new Date(this.datestart);
            let dateend = new Date(this.datefiled);
            let startDate = moment(datestart, 'YYYY-MM-DD');
            let endDate = moment(dateend, 'YYYY-MM-DD');
            // let totaldays = endDate.diff(startDate, 'days');
            let totaldays = startDate.diff(endDate, 'days');
            totaldays += 1;
            // this.totaldays = totaldays;
            // console.log(totaldays);
            // return totaldays < 7 && totaldays > 0? true: false;
            // console.log(totaldays);
            return totaldays < 7;

        },
        greater30Days(){
            let datestart =  new Date(this.datestart);
            let dateend = new Date(this.datefiled);
            let startDate = moment(datestart, 'YYYY-MM-DD');
            let endDate = moment(dateend, 'YYYY-MM-DD');
            // let totaldays = endDate.diff(startDate, 'days');
            let totaldays = startDate.diff(endDate, 'days');
            totaldays += 1;

            if(this.totaldays >= 3 && totaldays < 30 ){

                // alert('3 days leave and up should be filed 30 days before the date filed');
                // this.leavetype = 3;
                // this.$parent.disableLeaveType = true;
                return true;
            }else{
                return false;
                // this.$parent.disableLeaveType = false;
            }
        },

        getDiff(){
            // this.datestart =  moment(this.datestart).format('YYYY-MM-DD');
            // this.dateend = moment(this.dateend).format('YYYY-MM-DD');
            // let startDate = moment(this.datestart, 'YYYY-MM-DD');
            // let endDate = moment(this.dateend, 'YYYY-MM-DD');
            // let totaldays = endDate.diff(startDate, 'days');
            // this.totaldays = totaldays;
            // return totaldays;


            let datestart =  new Date(this.datestart);
            let dateend = new Date(this.dateend);
            let startDate = moment(datestart, 'YYYY-MM-DD');
            let endDate = moment(dateend, 'YYYY-MM-DD');
            let totaldays = endDate.diff(startDate, 'days');
            totaldays += 1;


            if(parseInt(this.halfday) > 0){
                totaldays -= .5;
            }

            this.totaldays = totaldays;
            return totaldays;
            // :format="'MM/dd/yyyy'">
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
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        },
        disableVL(){
            if(this.$root.leaveCredits.hasOwnProperty('VL')){
                return this.$root.$data.leaveCredits.VL <= 0;
            }
            return this.userinfo.VL <= 0;
        },
        disableBL(){
            if(this.$root.leaveCredits.hasOwnProperty('BL')){
                return this.$root.$data.leaveCredits.BL <= 0;
            }
            return this.userinfo.BL <= 0;
        },
        disableDL(){
            if(this.$root.leaveCredits.hasOwnProperty('DL')){
                return this.$root.$data.leaveCredits.DL <= 0;
            }
            return this.userinfo.DL <= 0;

        },
        disableSL(){
            if(this.$root.leaveCredits.hasOwnProperty('SL')){
                return this.$root.$data.leaveCredits.SL <= 0;
            }
            return this.userinfo.SL <= 0;

        },
    },
    beforeDestroy(){
        bus.$off('setupdateLeave', this.test)
    },
    mounted(){

        this.MDBINPUT();
        if(this.$parent.disabledinput){
            $('.vdp-datepicker div.vdp-datepicker__calendar:nth-child(2)').addClass('disable-dates-approver');
        }

        // MODAL
        $('#leaveModal').on("hidden.bs.modal", this.closeModal);
        // $('#leaveModal').on("shown.bs.modal", this.onOpenModal);

        // EVENT BUS
        bus.$on('setupdateLeave', this.setDataForEdit);

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