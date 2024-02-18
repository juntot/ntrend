<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			{{formtitle}}
		</div>
        <div class="col-lg-12 margin-15">
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding">

            </div>
            <table id="leaveform" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">

                            <ManageLeaveForm :userinfo="$root.userinfo" :selected="selected" :showCalendar="showCalendar"  :invokeParent="initCalendar">
                                <div class="col-lg-12">
                                    <Calendar :getEventDetails="showEventDetails"/>
                                </div>
                            </ManageLeaveForm>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->




            <div class="modal fade" id="myModal3">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Modal 3</h4>
                        </div>
                        <div class="container"></div>
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label for="usr">Event Name:</label>
                                <input type="text" class="form-control" v-model="event_name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pwd">Event Start Date:</label>
                                <Datepicker :value="event_start_date" @selected="dateStart" wrapper-class="mdb-form-field" input-class="form-control form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                </Datepicker>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pwd">Event End Date:</label>
                                <Datepicker :value="event_end_date" @selected="dateEnd" wrapper-class="mdb-form-field" input-class="form-control form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                </Datepicker>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="pwd">Start Time:</label>
                                <input type="time" v-model="event_timeStart" class="form-control" id="pwd">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pwd">End Time:</label>
                                <input type="time" v-model="event_timeEnd" class="form-control" id="pwd">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="comment">Comment:</label>
                                <textarea v-model="details" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-primary" @click="addEvent">Save changes</a>
                        </div>
                    </div>
                </div>
            </div>



            <div id="overlay-popup" class="hide" style="z-index: 1100; position: fixed;top: 0;right: 0;bottom: 0;left: 0;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" @click="hidePopOver">&times;</button>
                            <h4 class="modal-title">Event Details</h4>
                        </div>
                        <div class="container"></div>
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label for="usr">Event Name:</label>
                                <input type="text" class="form-control" v-model="selectedEventDetails.name" :readonly="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pwd">Event Start Date:</label>
                                <input type="text" class="form-control" v-model="selectedEventDetails.start" :disabled="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pwd">Event End Date:</label>
                                <input type="text" class="form-control" v-model="selectedEventDetails.end" :disabled="true">
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <label for="pwd">Event End Date:</label>
                                <Datepicker :value="event_end_date" @selected="dateEnd" wrapper-class="mdb-form-field" input-class="form-control form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                </Datepicker>
                            </div> -->
                            <div class="form-group col-md-12">
                                <label for="comment">Details:</label>
                                <textarea v-model="selectedEventDetails.details" class="form-control" rows="5" :disabled="true"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-default" data-dismiss="modal" @click.prevent="hidePopOver">Close</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="myModal4" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Event Details</h4>
                        </div>
                        <div class="container"></div>
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label for="usr">Event Name:</label>
                                <input type="text" class="form-control" v-model="selectedEventDetails.name" :readonly="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pwd">Event Start Date:</label>
                                <input type="text" class="form-control" v-model="selectedEventDetails.start" :disabled="true">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pwd">Event End Date:</label>
                                <input type="text" class="form-control" v-model="selectedEventDetails.end" :disabled="true">
                            </div>
                            <!-- <div class="form-group col-md-6">
                                <label for="pwd">Event End Date:</label>
                                <Datepicker :value="event_end_date" @selected="dateEnd" wrapper-class="mdb-form-field" input-class="form-control form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                </Datepicker>
                            </div> -->
                            <div class="form-group col-md-12">
                                <label for="comment">Details:</label>
                                <textarea v-model="selectedEventDetails.details" class="form-control" rows="5" :disabled="true"></textarea>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</template>
<script>
import ManageLeaveForm from '../../components/public/ManageLeaveForm';
import Calendar from '../../calendar_view/Calendar';
let leavetype = [
    'Sick Leave', 'Birthday Leave', 'Leave w/out Pay', 'Bereavement Leave', 'Vacation Leave',
    'Descritionary Leave', 'Solo Parent Leave', 'Paternity Leave', 'Others'
    ];
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    components:{
        ManageLeaveForm,
        Calendar
    },
    data(){
        return{
            formtitle: '',
            forapprover: '',
            isCancel: false,
            rows: [],
            disabledinput: true,
            dtHandle: null,
            loader: true,
            empID_: '', //requestedby
            status: '', //selected form status
            selected: {},

            // events
            event_name: '',
            event_start_date:  moment(new Date()).format('YYYY-MM-DD'),
            event_end_date:  moment(new Date()).format('YYYY-MM-DD'),
            event_timeStart: '',
            event_timeEnd: '',
            details: '',
            showCalendar: false,

            selectedEventDetails:{},
        }
    },
    watch:{
        rows(val, old){
            let row = val;

            row.forEach((item, index)=>{
                if(!isNaN(item.leavetype) && !isNaN(item.status)){
                    row[index]['leavetype'] = leavetype[item.leavetype - 1];
                    row[index]['status'] = status[item.status];
                }
            });
            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();

        }
    },
    methods:{
        initCalendar(){

            this.showCalendar = !this.showCalendar;
            if(this.showCalendar)
            bus.$emit('plotEvent', {
                start: this.selected.datestart,
                empID: this.selected.empID_,
            });
        },
        showEventDetails(data={}){
            this.selectedEventDetails = data;
            // $('#myModal4').modal('show');
            $('#overlay-popup').toggleClass('hide');
        },
        dateStart(val){
            this.event_start_date = moment(val).format('YYYY-MM-DD');
        },
        dateEnd(val){
            this.event_end_date = moment(val).format('YYYY-MM-DD');
        },
        addEvent(){

            let events = {
                    name : this.event_name,
                    details : this.details,
                    start : new Date(`${this.event_start_date} ${this.event_timeStart}`), //;'Thu Jan 14 2021 17:45:00 GMT+0800 (China Standard Time)',
                    end :  new Date(`${this.event_end_date} ${this.event_timeEnd}`), //'Sat Jan 16 2021 08:15:00 GMT+0800 (China Standard Time)',
                    color : 'indigo',
                    // timed: true,
                };
            // bus.$emit('addEvent', events);
            $('#myModal3').modal('hide');
        },
        // end calendar
        addRow(val)
        {
            this.rows.unshift(val);
        },
        updateRow(val)
        {
            let row = this.$data.rows;

            row.forEach((item, index)=>{
                if(item.leaveID == val.leaveID)
                {
                    let data = item;
                    data['status'] = status[val.status];
                    data['remarks'] = val.remarks;
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(data);

                }
            });



        },
        setUpdate(data){
            bus.$emit('setupdate', data);

        },
        closeModal(){
            this.isCancel = false;
            this.status = '';
            this.selected = {};

            this.showCalendar = false;
        },
        hidePopOver(){
            $('#overlay-popup').toggleClass('hide');
        }

    },
    created(){

    },

    mounted(){
        this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[0];
        this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();




        // $(document).on('show.bs.modal', '.modal', function (event) {
        //     var zIndex = 1040 + (10 * $('.modal:visible').length);
        //     $(this).css('z-index', zIndex);
        //     setTimeout(function() {
        //         $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        //     }, 0);
        // });



        axios.get('api/approvalleaverequest').then((response)=>{
            this.loader = false;
            this.rows=response.data;


             $.extend(jQuery.fn.dataTableExt.oSort, {
                "date-uk-pre": function (a) {
                    var x;
                    try {
                        var dateA = a.replace(/ /g, '').split("/");
                        var day = parseInt(dateA[1], 10);
                        var month = parseInt(dateA[0], 10);
                        var year = parseInt(dateA[2], 10);
                        var date = new Date(year, month - 1, day)
                        x = date.getTime();
                    }
                    catch (err) {
                        x = new Date().getTime();
                    }

                    return x;
                },

                "date-uk-asc": function (a, b) {
                    return a - b;
                },

                "date-uk-desc": function (a, b) {
                    return b - a;
                }
            });

            this.dtHandle=$('#leaveform').DataTable({
            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [2] }],
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "rowCallback": function(row, data, index) {
                var cellValue = data["status"];
                    if (cellValue=="Pending") {
                       $(row).addClass("tr-pending");
                    }
                    if (cellValue=="Approved") {
                       $(row).addClass("tr-approved");
                    }
                    if (cellValue=="Rejected") {
                       $(row).addClass("tr-rejected");
                    }

                }
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#leaveform tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                // CHECK IF STATUS IS APPROVED TO BE READY FOR CANCELLATION
                if(status.indexOf(row.data().status) >= 1){
                    self.isCancel = true;
                    // self.status = (row.data().status).toLowerCase();
                }
                let dataforedit = row.data();
                self.selected = row.data();
                self.empID_ = row.data().empID_;
                self.setUpdate(dataforedit);

            });

        }).catch((err)=>{
            console.log(err);

        });

        let columnDefs = [
        {
            title: "LEAVE ID", data: 'leaveID', visible: false,
        },
        // ,{
        //     title: "Employee ID", data: 'empID_'
        // },
        // {
        //     title: "Employee ID", data: 'empID_'
        // },
        
        {
            title: "Employee Name", data: 'fullname'
        },
        {
            title: "Date Filed", data: 'datefiled'
        },
        //  {
        //     title: "Date Start", data: 'datestart'
        // },{
        //     title: "Date End", data: 'dateend'
        // },
        {
            title: "Type", data: 'leavetype'
        },
        {
            title: "Total(days)", data: 'totaldays',
            render: function(data){
                return Number(data).valueOf();
            }
        },
        {
            title: "Reason", data: 'reason', className: "row-limit"
        },{
            title: "Status", data: 'status'
        }];

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);
        // $('#myModal').on("show.bs.modal", ()=>this.showCalendar=true);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });
    }

}
</script>