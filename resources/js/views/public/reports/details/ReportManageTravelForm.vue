<template>
    <div>
        <form method="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">TRAVEL FORM</span></h3>
            <div class="col-md-12">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" :value="datefiled" class="form-field__input" name="Date filed" readonly="true">
                        <label class="form-field__label">Date filed</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedfullname" name="Name" readonly="true">
                        <label class="form-field__label">Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedmobile" name="Mobile" readonly="true">
                        <label class="form-field__label">Mobile</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" v-model="totaldays" v-validate="'required'" name="No. of days">
                        <label class="form-field__label">No. of days</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('No. of days') }}</span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Purpose of trip</em></h5>
            </div>
            <div class="col-md-12">
                <div class="col-md-2 col-xs-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Training</span>
                            <input :disabled="true" type="radio" v-model="purpose" value="1" name="purpose" >
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-2 col-xs-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Audit</span>
                            <input :disabled="true" type="radio" v-model="purpose" value="2" name="purpose">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-2 col-xs-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Meeting</span>
                            <input :disabled="true" type="radio" v-model="purpose"  value="3" name="purpose">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-2 col-xs-6">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Events</span>
                            <input :disabled="true" type="radio" v-model="purpose" value="4" name="purpose">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div>
                        <label class="mdblblradio">
                            <span class="checklbl">Others</span>
                            <input :disabled="true" type="radio" v-model="purpose"  value="5" name="purpose">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="true" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="traveldetails" name="additional-info"></textarea>
                            <label class="form-field__label">Please indicate details of purpose below with date and time</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                </div>
            </div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Travel Details</em></h5>
            </div>
            <div class="col-lg-12">
                <div class="col-lg-2 col-xs-6">
                    <div>
                        <label class="mdblblradio">
                        <span class="checklbl">One Way</span>
                        <input :disabled="true" type="radio" v-model="tickettype" value="1" name="tickettype" >
                        <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-6">
                    <div>
                        <label class="mdblblradio">
                        <span class="checklbl">Round Trip</span>
                        <input :disabled="true" type="radio" v-model="tickettype" value="2" name="tickettype" >
                        <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="origin" v-validate="'required'" name="origin">
                        <label class="form-field__label">Origin</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('origin') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-limit">
                            <Datepicker :disabled="true" :value="departuredate" @selected="selectDeparture" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" name="departure date" :format="'MM/dd/yyyy'">
                            <label slot="afterDateInput" class="form-field__label">Departure Date</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('departure date') }}</span>
                            </Datepicker> 		
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="departsuggestime" name="Suggested Time">
                        <label class="form-field__label">Suggested Time</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="destination" v-validate="'required'" name="Destination">
                        <label class="form-field__label">Destination</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-limit">
                            <Datepicker :disabled="true" :value="returndate" @selected="selectReturnDate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" name="return date" :format="'MM/dd/yyyy'">
                            <label slot="afterDateInput" class="form-field__label">Return Date</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('return date') }}</span>
                            </Datepicker> 		
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="returnsuggestime" name="Suggested Time">
                        <label class="form-field__label">Suggested Time</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>With Baggage</em></h5>
            </div>
            <div class="col-lg-12">
                <div class="col-md-2 col-xs-6">
                    <div>
                        <label class="mdblblradio">
                        <span class="checklbl">YES</span>
                        <input :disabled="true" type="radio" v-model="withbaggage" value="1" name="withbaggage" >
                        <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-10 col-xs-6">
                    <div>
                        <label class="mdblblradio">
                        <span class="checklbl">NO</span>
                        <input :disabled="true" type="radio" v-model="withbaggage" value="2" name="withbaggage" @click="baggagekilos = ''">
                        <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12" v-show="withbaggage == 1">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="baggagekilos" v-validate="withbaggage != 1?'':'required'" name="no of kilos">
                        <label class="form-field__label">No of kilos</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('no of kilos') }}</span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Hotel Accommodation</em></h5>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="suggeshotel" v-validate="'required'" name="suggested hotel">
                        <label class="form-field__label">Suggested Hotel</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('suggested hotel') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="totalnight" name="no. of nights">
                        <label class="form-field__label">No. of nights</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('no. of nights') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group-limit">
                            <Datepicker :disabled="true" :value="checkindate" @selected="selectCheckInDate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" name="checkin date" :format="'MM/dd/yyyy'">
                            <label slot="afterDateInput" class="form-field__label">Check in Date</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('checkin date') }}</span>
                            </Datepicker> 		
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group-limit">
                            <Datepicker :disabled="true" :value="checkoutdate" @selected="selectCheckOutDate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" name="checkout date" :format="'MM/dd/yyyy'">
                            <label slot="afterDateInput" class="form-field__label">Check out Date</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('checkout date') }}</span>
                            </Datepicker> 		
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>Travel Allowance</em></h5>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="mealallowance" v-validate="'required'" name="meal allowance">
                        <label class="form-field__label">Meal Allowance</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('meal allowance') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="transpo" v-validate="'required'" name="transportation">
                        <label class="form-field__label">Transportation</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('transportation') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="pocketmoney" v-validate="'required'" name="pocket money">
                        <label class="form-field__label">Pocket Money</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('pocket money') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="true" type="text" class="form-field__input" v-model="others" v-validate="'required'" name="others">
                        <label class="form-field__label">Others</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('others') }}</span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"><em>FOR APPROVER</em></h5>
            </div>
            <!-- status is rejected or approve -->
            <div v-if="true">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="true" class="form-field__textarea"  cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
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
            <!-- <div class="clearfix"></div>
            <div class="col-lg-12" v-if="!true">
                    <p class="text-info">
                        LIST OF APPROVERS: 
                    </p>
                    <div class="clearfix"></div>
                    <span class="approverlist alert-info" v-for="(approver, key) in $parent.approvers" :key="key">{{approver.approvers}}</span>
                    <br><br>
            </div> -->
            <div class="clearfix"></div>
            <div class="modal-footer">
                <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addTravel" :disabled="isDisable || !isFormValid" v-if="submitBtn">
                <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateTravel" :disabled="isDisable || !isFormValid" v-if="updateDeleteBtn">
                <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteTravel" :disabled="isDisable" v-if="updateDeleteBtn">
                <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionTravel(1)" v-if="approveRejecBtn">
                <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionTravel(2)" v-if="approveRejecBtn">
                <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionTravel(0)" v-if="cancelBtn"> -->
            </div>
        </form>
    </div>
</template>
<script>
let modtype = ['RF ONLINE', 'CHECK', 'CASH', 'PETTY CASH']; 
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo' , 'selected'],
    data() {
		return {

        empID_: '',
        datefiled: moment(new Date()).format('YYYY-MM-DD'),
        departuredate: moment(new Date()).format('YYYY-MM-DD'),
        returndate: moment(new Date()).format('YYYY-MM-DD'),
        checkindate: moment(new Date()).format('YYYY-MM-DD'),
        checkoutdate: moment(new Date()).format('YYYY-MM-DD'),
        totaldays: 1,
        purpose: 1,
        origin: '',
        departsuggestime: '', 
        returnsuggestime: '',
        withbaggage: 1,
        baggagekilos: 1,
        transpo: '',
        suggeshotel: '',
        totalnight: 1,
        mealallowance: '',
        destination: '',
        pocketmoney: '',
		tickettype: 1, 
        traveldetails: '',
        others: '',
        isDisable: false,
        travelID: '',
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
        addTravel(){
            if(this.isFormValid){
                this.isDisable = true;
                let params = this.$data;
                axios.post('api/addTravel', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateTravel(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateTravel', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteTravel(){
            this.isDisable = true;
            axios.get('api/deleteTravel/'+this.faID) .then((response)=>{
                this.$parent.deleteRow(this.faID) ;
            }).catch((err)=>{
                console.log(err);
            });
            
        },
        // ACTIONS FOR Travel I.E APPROVE / REJECT / CANCEL
        requestActionTravel(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformTravel', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },
        selectDateNeed(val){
            this.dateneed = moment(val).format('YYYY-MM-DD');
        },
        selectDeparture(val){
            this.departuredate = moment(val).format('YYYY-MM-DD');
        },
        selectReturnDate(val){
            this.returndate = moment(val).format('YYYY-MM-DD');
        },
        selectCheckInDate(val){
            this.checkindate = moment(val).format('YYYY-MM-DD');
        },
        selectCheckOutDate(val){
            this.checkoutdate = moment(val).format('YYYY-MM-DD');
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
            $("#travelModal").modal("hide");
        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'modtype')
                this.$data[i] = (modtype.indexOf(data[i]) + 1);
                
            }
            
            this.MDBINPUT();
            $("#travelModal").modal("show");
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
            // return this.errors.items.length <= 0;
        },
        submitBtn()
        {
            return !this.travelID && this.true;
        },
        updateDeleteBtn(){
            return this.travelID && this.true && !this.true 
        },
        approveRejecBtn(){
            return this.travelID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.travelID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedmobile(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.mobile  : this.userinfo.mobile;
        }
        // computedposname(){
        //     return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        // },
        // computedbranchname(){
        //     return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        // }
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
        bus.$off('setupdateTravel', this.test)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#travelModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateTravel', this.setDataForEdit);

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