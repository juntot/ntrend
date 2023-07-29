<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			{{title}}
		</div>
        <div class="col-lg-12 margin-15">
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding">
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#meetingModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="meetingform" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="meetingModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageMeetingMinute :userinfo="$root.userinfo" :selected="selected"></ReportManageMeetingMinute>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageMeetingMinute from './details/ReportManageMeetingMinute';
export default {
    props: ['title'],
    components:{
        ReportManageMeetingMinute
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
        rows(val, old){
            this.dtHandle.clear();
            this.dtHandle.rows.add(val);
            this.dtHandle.draw();
        },
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
                    $("#myModalMeetingReport").modal("hide");
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
        },
        setInit(data = null){
            this.rows = data;
        },
        downloadXLS(){

            let wb = XLSX.utils.book_new();

            let rows = [];
            let header = [
                'MEETING ID','MEETING NAME','NOTE TAKER ID', 'NOTE TAKER NAME', 'DEPARTMENT NAME', 'POSITION', 'DATE FILED', 'MEETING DATE',
                'DURATION',  'LOCATION', 'START TIME', 'END TIME','ATTENDEES' , 'REMARKS', 'STATUS',
            ];
            rows.push(header);
            this.rows.forEach(obj => {
                // let records = [];
                obj.entries.forEach((entry, i)=>{
                    // if(i < 1){

                        
                        let from =  new Date(moment().format('YYYY-MM-DD')+' '+ obj.starttime);
                        let to = new Date(moment().format('YYYY-MM-DD')+' '+ obj.endtime);
                        var ms = moment(to,"YYYY-MM-DD HH:mm:ss").diff(moment(from,"YYYY-MM-DD HH:mm:ss"));
                        var duration = moment.duration(ms);

                        let records = [
                            obj.meetingID,
                            obj.meetingname, obj.empID_, obj.fullname,obj.deptname, obj.posname, obj.datefiled,
                            obj.meetingdate, duration, obj.location, moment(new Date(obj.starttime)).format('HH:mm a'),
                            obj.moment(new Date(obj.endtime)).format('HH:mm a'), (JSON.parse(obj.attendeelist)).map(obj=>obj.fullname).toString(),
                            obj.remarks, obj.status,
                        ];
                    rows.push(records);
                });

            });



            // /* SHEET 1 */
            // let ws = XLSX.utils.aoa_to_sheet([["a","b", "c"],[3,2,1],[1,2,3]]);
            let ws = XLSX.utils.aoa_to_sheet(rows);
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");



            XLSX.writeFile(wb, this.title+".xls");
            /* uncomment writeFile to start saving excel */
        },
    },
    created(){

    },
    beforeDestroy(){
        bus.$off('initMeetingMinuteReport', this.setInit);
    },

    mounted(){
        
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
        {
            title: "Location", data: 'location'
        },
        {
            title: "Attendees", data: 'attendeelist', className:"td-ellipses row-limit" , render: function(data){
                return (JSON.parse(data)).map(obj=>obj.fullname).toString();
            }
        },
        {
            title: "Status", data: 'status',
            render: function ( data, type, row, meta ) {
                return data == 0 ? 'Open': 'Closed'; //data == 1? 'Verified': data== 2? 'Approved': 'Rejected';
            }
        }];

        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        

        // axios.get('api/getCanvasbyemployee').then((response)=>{
            this.loader = false;
            // this.rows=response.data;

            this.dtHandle=$('#meetingform').DataTable({
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
            // let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#meetingform tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                self.disabledinput = true;
                let dataforedit = row.data();
                self.selected = row.data();
                self.setUpdate(dataforedit);



            });

        // MODAL
        $('#meetingModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		});
        

        $('.modal').on('shown.bs.modal', function (e) {
            
            let countBackdrop = document.querySelectorAll('.modal-backdrop');
            if(countBackdrop.length > 1)
            countBackdrop.forEach((el,i) =>{if(i==0)el.remove()});
		});

		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initMeetingMinuteReport', this.setInit);

    }

}
</script>