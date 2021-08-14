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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#overtimeModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="overtimeform" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="overtimeModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageOvertimeRequest :userinfo="$root.userinfo" :selected="selected"></ReportManageOvertimeRequest>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageOvertimeRequest from './details/ReportManageOvertimeRequest';
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props: ['title'],
    components:{
        ReportManageOvertimeRequest
    },
    data(){
        return{
            forapprover: '',
            formtitle: '',
            rows: [],
            disabledinput: false,
            dtHandle: null,
            loader: true,
            approvers: [],
            selected: {},
        }
    },
    watch:{
        rows(val, old){
            let row = val;

            row.forEach((item, index)=>{
                if(!isNaN(item.status)){
                    row[index]['status'] = status[item.status];
                }
                row[index]['starttime'] = moment((row[index]['starttime']), ["h:mm A"]).format('hh:mm A');
                row[index]['endtime'] = moment((row[index]['endtime']), ["h:mm A"]).format('hh:mm A');
            });
            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();
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
                if(item.undertimeID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#overtimeModal").modal("hide");
                }

            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.undertimeID == val.undertimeID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });

        },
        setUpdate(data){
            bus.$emit('setupdateOvertime', data);
        },

        closeModal(){
            this.disabledinput = false;
            this.selected = {};
        },
        setInit(data = null, stat = null){
            if(stat > 0){
                $('.approver').text((status[stat]+' BY').toUpperCase());
            }else{
                $('.approver').text('APPROVER');
            }
            this.rows = data;
        },
        downloadXLS(){

            let wb = XLSX.utils.book_new();

            let rows = [];
            let header = ['EMPLOYEE ID', 'EMPLOYEE NAME', 'COMPANY', 'DEPARTMENT NAME', 'POSITION', 'DATE FILED', 'DATE OVERTIME', 'START TIME', 'END TIME', 'TOTAL HOURS', 'REASON', 'APPROVER', 'APPROVED DATE', 'REMARKS', 'STATUS'];
            rows.push(header);
            this.rows.forEach(obj => {
                let records = [
                            obj.empID_, obj.fullname, obj.compname, obj.deptname, obj.posname,
                            obj.datefiled, obj.date_overtime, obj.starttime, obj.endtime, obj.totalhrs,
                            obj.reason, obj.approvedby, obj.approveddate, obj.remarks,
                            obj.status
                        ];
                // records.push();
                rows.push(records);
            });



            // /* SHEET 1 */
            // let ws = XLSX.utils.aoa_to_sheet([["a","b", "c"],[3,2,1],[1,2,3]]);
            let ws = XLSX.utils.aoa_to_sheet(rows);
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");



            XLSX.writeFile(wb, this.title+".xls");
            /* uncomment writeFile to start saving excel */
        },
    },
    beforeDestroy(){
        bus.$off('initOvertimeReport', this.setInit);
    },
    mounted(){
        let columnDefs = [
        // {
        //     title: "Undertime ID", data: 'undertimeID'
        // },
        {
            title: "Employee ID", data: 'empID_'
        },{
            title: 'Employee Name',  data: 'fullname'
        },{
            title: "Date Filed", data: 'datefiled'
        },{
            title: "Date Overtime", data: 'date_overtime'
        },{
            title: "Start Time", data: 'starttime'
        },{
            title: "End Time", data: 'endtime'
        },{
            title: "Total(hrs)", data: 'totalhrs'
        },{
            title: "Reason", data: 'reason', className: "row-limit"
        },
        {
            title: "Status", data: 'status'
        },
        {
            title: "Approver", data: 'approvedby', className: "approver"
        }];

        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        // axios.get('api/getundertimebyemployee').then((response)=>{
            this.loader = false;
            // this.rows=response.data;

            this.dtHandle=$('#overtimeform').DataTable({
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[0, "desc"]]
            });

            let table = this.dtHandle;
            // let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#overtimeform tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                if(row.data().status.toLowerCase() == 'approved' ||
                   row.data().status.toLowerCase() == 'rejected')
                {
                    self.disabledinput = true;
                    // return;
                }

                let dataforedit = row.data();
                self.selected = row.data();
                dataforedit['endtime'] = moment(dataforedit.endtime, ["h:mm A"]).format("HH:mm")
                dataforedit['starttime'] = moment(dataforedit.starttime, ["h:mm A"]).format("HH:mm")

                self.setUpdate(dataforedit);

            });

        // }).catch((err)=>{
        //     console.log(err);

        // });

        // // APPROVERS
        // axios.get('api/getUndertimeApprover').then((response)=>{
        //     this.approvers =  response.data;
        // })
        // .catch((err)=>{});



        // MODAL
        $('#overtimeModal').on("hidden.bs.modal", this.closeModal);

        bus.$on('initOvertimeReport', this.setInit);
    }

}
</script>