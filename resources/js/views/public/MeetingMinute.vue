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
                <div class="dflex">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="margin-right: 15px;">ADD NEW</button>
                    <div :class="openFilterStatus?'dropdown open z-1':'dropdown z-1'">
                        <button class="btn btn-primary dropdown-toggle" type="button" @click.prevent="openFilterStatus = !openFilterStatus">
                            Filter Status
                        <!-- <span class="caret"></span> -->
                        </button>
                        
                        <ul class="dropdown-menu statusfilter" style="padding: 10px">
                            <button type="button" class="close" 
                            
                            @click="openFilterStatus = !openFilterStatus">×</button>
                            <li style="padding: 4px 15px">
                                <label>
                                    <input type="checkbox" v-model="status" :value="0" name="status" >
                                    <span class="mdbcheckmark"></span>
                                    Open
                                </label>
                            </li>
                            <li style="padding: 4px 15px">
                                <label>
                                    <input type="checkbox" v-model="status" :value="1" name="status" >
                                    <span class="mdbcheckmark"></span>
                                    Close
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                
            </div>
            <table id="meetingminutes" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg meetingminutes-modal">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ManageMeetingMinute :userinfo="$root.userinfo" :selected="selected"></ManageMeetingMinute>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ManageMeetingMinute from '../../components/public/ManageMeetingMinute';
let brand = ['NTMC', 'APBW', 'PHILCREST', 'TYREPLUS'];
// let status = ['Pending', 'Approved', 'Rejected'];
let defaultRows = [];
export default {
    components:{
        ManageMeetingMinute
    },
    data(){
        return{
            openFilterStatus: false,
            forapprover: '',
            formtitle: '',
            rows: [],
            disabledinput: false,
            dtHandle: null,
            loader: true,
            approvers: [],
            reciever_emails: [],
            selected: {},
            status: [0, 1],
        }
    },
    watch:{
        status(val, old){
            let rows = [];
            rows = defaultRows.filter(data=>{
                return val.includes(data.status);
            });

            this.dtHandle.clear();
            this.dtHandle.rows.add(rows);
            this.dtHandle.draw();
        },
        rows(val, old){
            let rows = [];
            rows = defaultRows.filter(data=>{
                return this.status.includes(data.status);
            });

            this.dtHandle.clear();
            this.dtHandle.rows.add(rows);
            this.dtHandle.draw();
        },
        approvers(val, old){
            val.forEach(item=>{
                this.reciever_emails.push(item.email);
            })
        }
    },
    methods:{
        addRow(val)
        {
            this.rows.unshift(val);
        },
        deleteRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.meetingID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#myModal").modal("hide");
                }
            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.meetingID == val.meetingID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });


        },
        setUpdate(data){
            bus.$emit('setupdate', data);
        },

        closeModal(){
            this.disabledinput = false;
            this.selected = {};
        }
    },
    created(){

    },

    mounted(){

        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();
        this.formtitle = this.$route.name.toUpperCase();

        axios.get('api/get-minute-meeting').then((response)=>{
            this.loader = false;
            this.rows=response.data;
            defaultRows = response.data;

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
            this.dtHandle=$('#meetingminutes').DataTable({
            // aoColumnDefs: [{ "sType": "date-uk", "aTargets": [1] }],
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "rowCallback": function(row, data, index) {
                var cellValue = data["status"];
                    if (cellValue== 0 ) {
                       $(row).addClass("tr-pending");
                    }if (cellValue==1) {
                       $(row).addClass("tr-approved");
                    }
                    if (cellValue==3) {
                       $(row).addClass("tr-rejected");
                    }

                }
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#meetingminutes tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                if(row.data().status == 1 ||
                  row.data().status == 2 ||
                   row.data().status == 3)
                {
                    self.disabledinput = true;
                }
                
                

                let dataforedit = row.data();
                self.selected = dataforedit;
                self.setUpdate(dataforedit);

            });

        }).catch((err)=>{
            console.log(err);

        });

        // APPROVERS
        axios.get('api/get-minute-meeting').then((response)=>{
            this.approvers =  response.data;
        })
        .catch((err)=>{});

        let columnDefs = [
        {
            title: "Meeting ID", data: 'meetingID'
        },
        {
            title: "Meeting Name", data: 'meetingname'
        },
        {
            title: "Note Taker", data: 'fullname'
        },
        // {
        //     title: "Note Taken", data: 'datefiled', render: function(data){
        //         return moment(data).format('MM/DD/YYYY');
        //     }
        // },
        {
            title: "Meeting Date", data: 'meetingdate', render: function(data){
                return moment(data).format('MM/DD/YYYY');
            }
        },
        {
            title: "Duration", render: function(data, type, row){
            let from =  new Date(moment().format('YYYY-MM-DD')+' '+ row.starttime);
            let to = new Date(moment().format('YYYY-MM-DD')+' '+ row.endtime);
            var ms = moment(to,"YYYY-MM-DD HH:mm:ss").diff(moment(from,"YYYY-MM-DD HH:mm:ss"));
            var d = moment.duration(ms);
            
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
            
            s = s.slice(0, (s.indexOf(':'))).length <= 1 ? '0'+s: s;
            // slice the seconds
            s = s.slice(0, 5);
            return s || '';
            // return moment(data).format('MM/DD/YYYY');
            }
        },
        // {
        //     title: "Start Time", data: 'starttime', render: function(data){
        //         return moment(data, 'HH:mm:ss').format('HH:mm a');
        //     }
        // },
        // {
        //     title: "End Time", data: 'endtime', render: function(data){
        //         return moment(data, 'HH:mm:ss').format('HH:mm a');
        //     }
        // },
        {
            title: "Location", data: 'location'
        },
        {
            title: "Attendees", data: 'attendeelist', className:"td-ellipses row-limit" , render: function(data){
                return (JSON.parse(data)).map(obj=>obj.fullname).toString();
            }
        },
        // {
        //     title: "Brand", data: 'brand'
        // },
        {
            title: "Status", data: 'status',
            render: function ( data, type, row, meta ) {
                return data == 0 ? 'Open': 'Closed'; //data == 1? 'Verified': data== 2? 'Approved': 'Rejected';
            }
        }];

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

    }

}
</script>