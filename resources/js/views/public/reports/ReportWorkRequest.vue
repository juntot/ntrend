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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#workRequestModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="workrequest" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="workRequestModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageWorkRequest :userinfo="$root.userinfo" :selected="selected"></ReportManageWorkRequest>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageWorkRequest from './details/ReportManageWorkRequest';
// let worktype = ['System Access (SAP, HRIS etc.)',
//                 'Borrow item',
//                 'System Autorization',
//                 'RDP Access',
//                 'Password Reset',
//                 'Internet Access',
//                 'Email Setup',
//                 'Install Apps(Spark, Skype, etc.)',
//                 'Setup Workstation',
//                 'Setup Printer'
//                 ,'Setup Telephone',
//                 'Cleaning / Maintenance',
//                 'Repair',
//                 'Format',
//                 'System Report',
//                 'System Layout',
//                 'GPS Report',
//                 'Conversation History',
//                 'CCTV Report',
//                 'File & Data Recovery'];

// let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props: ['title'],
    components:{
        ReportManageWorkRequest
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
                // if(!isNaN(item.status)){
                //     row[index]['status'] = status[item.status];
                // }
                // if(!isNaN(item.worktype)){
                //     row[index]['worktype'] = worktype[(item.worktype - 1)];
                // }
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
                if(item.workID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#workRequestModal").modal("hide");
                }

            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.workID == val.workID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });

        },
        setUpdate(data){
            console.log(data);
            bus.$emit('setupdateWorkRequest', data);
        },

        closeModal(){
            this.disabledinput = false;
            this.selected = {};
        },
        setInit(data = null){
            this.rows = data;
        },
        getDuration(from, to) {
              
            var ms = moment(to,"YYYY-MM-DD HH:mm:ss").diff(moment(from,"YYYY-MM-DD HH:mm:ss"));
            var d = moment.duration(ms);
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
            
            s = s.slice(0, (s.indexOf(':'))).length <= 1 ? '0'+s: s;

            return s || '';
        },
        downloadXLS(){

            let wb = XLSX.utils.book_new();

            let rows = [];
            let header = [
                            'WRF #',
                            'EMPLOYEE ID', 'EMPLOYEE NAME', 'DEPARTMENT NAME', 'POSITION', 'BRANCH',
                            'DATE FILED', 'TIME CREATED', 'DATE NEEDED', 'URGENCY', 'TYPE OF WORK', 'PREFERRED IT', 'DATE FROM', 'DATE TO',
                            'REQUEST TYPE', 'CONTACT NUMBER', 'REQUEST DETAILS',
                            'APPROVER', 'EXECUTED BY', 'APPROVED DATE', 'EXECUTED DATE','EXECUTED TIME',
                            'CONFIRMED DATE', 'CONFIRMED TIME',
                            'REMARKS', 'STATUS',
                            'REQUEST TO EXECUTE DURATION',
                            'REQUEST TO APPROVED DURATION', 'APPROVED TO EXECUTION DURATION', 
                            'EXECUTED TO CONFIRMED DURATION', 'REQUEST TO CONFIRMED DURATION',
                            'RE GRADE', 'AE GRADE', 'RC GRADE',

                        ];
            rows.push(header);

            let worktype = '', date_from = '', date_to = '', status = '';

            this.rows.forEach(obj => {
                status =  obj.status == 1 ? 'Approved':
                          obj.status == 2 ? 'Rejected':
                          obj.status == 3? 'Executed':
                          obj.status == 4? 'Confirmed' : 'Pending';

                worktype = obj.worktype > 1? 'Permanent' : obj.worktype == 1? 'Temporary': '';
                date_from = obj.worktype == 1? obj.date_from : '';
                date_to = obj.worktype == 1? obj.date_to : '';


                let requestToExecution = this.getDuration(obj.datefiled_datetime, obj.exec_datetime);
                let approveToExecution = this.getDuration(obj.approveddate, obj.exec_datetime);
                let requestToConfirm   = this.getDuration(obj.datefiled_datetime, obj.confirm_datetime);

                let urgencyType = obj.urgency;
                if(urgencyType == 'Highest')
                    urgencyType = 2;
                
                if(urgencyType == 'High')
                    urgencyType = 48;
                
                if(urgencyType == 'Medium')
                    urgencyType = 144;

                if(urgencyType == 'Low')
                    urgencyType = 384;

                if(urgencyType == 'Lowest')
                    urgencyType = 768;
                
                let records = [
                            obj.workID,
                            obj.empID_, obj.fullname, obj.deptname, obj.posname,
                            obj.branchname, obj.datefiled, moment(obj.datefiled_datetime).format('HH:mm:ss'), obj.dateneed, obj.urgency,
                            worktype, obj.preferredIT,date_from, date_to, obj.request_type,
                            obj.mobile, obj.reason, obj.approvedby_, obj.executedby_, obj.approveddate,
                            moment(obj.exec_datetime).format('YYYY-MM-DD'), moment(obj.exec_datetime).format('HH:mm:ss'),
                            moment(obj.confirm_datetime).format('YYYY-MM-DD'), moment(obj.confirm_datetime).format('HH:mm:ss'),
                            obj.remarks, status,

                            // request to execution
                            requestToExecution, // this.getDuration(obj.datefiled_datetime, obj.exec_datetime),

                            this.getDuration(obj.datefiled_datetime, obj.approveddate),
                            // moment.utc(moment(obj.approveddate, "HH:mm:ss").diff(moment(obj.datefiled_datetime, "HH:mm:ss"))).format("HH:mm:ss"),
                            
                            // approve to execution
                            approveToExecution, // this.getDuration(obj.approveddate, obj.exec_datetime),
                            // moment.utc(moment(obj.exec_datetime, "HH:mm:ss").diff(moment(obj.approveddate, "HH:mm:ss"))).format("HH:mm:ss"),
                            
                            this.getDuration(obj.exec_datetime, obj.confirm_datetime),
                            // moment.utc(moment(obj.confirm_datetime, "HH:mm:ss").diff(moment(obj.exec_datetime, "HH:mm:ss"))).format("HH:mm:ss"),

                            // request to confirm
                            requestToConfirm, // this.getDuration(obj.datefiled_datetime, obj.confirm_datetime),
                            // moment.utc(moment(obj.confirm_datetime, "HH:mm:ss").diff(moment(obj.datefiled_datetime, "HH:mm:ss"))).format("HH:mm:ss"),

                            urgencyType >= parseInt(requestToExecution.slice(0, (requestToExecution.indexOf(':')))) ? 'PASSED': 'FAILED',
                            urgencyType >= parseInt(approveToExecution.slice(0, (approveToExecution.indexOf(':')))) ? 'PASSED': 'FAILED',
                            urgencyType >= parseInt(requestToConfirm.slice(0, (requestToConfirm.indexOf(':')))) ? 'PASSED': 'FAILED',


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
        bus.$off('initWorkRequestReport', this.setInit);
    },
    mounted(){
         let columnDefs = [
        // {
        //     title: "WORK ID", data: 'workID'
        // },
        {
            title: "Employee ID", data: 'empID_'
        },
        {
            title: "Employee Name", data: 'fullname'
        },{
            title: "Date Filed", data: 'datefiled'
        }, {
            title: "Date Needed", data: 'dateneed'
        },{
            title: "Urgency", data: 'urgency',
        },{
            title: "Type of Work", data: 'worktype',
            render: function(data){
                return data > 1? 'Permanent' : 'Temporary'
            }
        },{
            title: "Reason", data: 'reason', className: "row-limit"
        },{
            title: "Status", data: 'status',
            render: function(data){
                return data == 1 ? 'Approved':
                       data == 2 ? 'Rejected':
                       data == 3? 'Executed':
                       data == 4? 'Confirmed' : 'Pending';
            }
        }];

        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        // axios.get('api/getworkrequestbyemployee').then((response)=>{
            this.loader = false;
            // this.rows=response.data;

            this.dtHandle=$('#workrequest').DataTable({
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
            $("#workrequest tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                // if(row.data().status.toLowerCase() == 'approved' ||
                //    row.data().status.toLowerCase() == 'rejected')
                // {
                    self.disabledinput = true;
                    // return;
                // }
                let dataforedit = row.data();
                self.selected = row.data();
                self.setUpdate(dataforedit);

            });

        // }).catch((err)=>{
        //     console.log(err);

        // });

        // // APPROVERS
        // axios.get('api/getWorkRequestApprover').then((response)=>{
        //     this.approvers =  response.data;
        // })
        // .catch((err)=>{});



        // MODAL
        $('#workRequestModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initWorkRequestReport', this.setInit);
    }

}
</script>