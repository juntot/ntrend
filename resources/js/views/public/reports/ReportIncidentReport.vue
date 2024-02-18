<style>
.td-approve {
  color: #2979ff !important;
}
.td-reject {
  color: #ab003c !important;
}
.td-close {
  color: #00a732 !important;
}
.td-endorse {
  color: #651fff !important;
}
</style>
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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#reportIRModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="incidentReport" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="reportIRModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageIncidentReport :userinfo="$root.userinfo" :selected="selected"></ReportManageIncidentReport>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageIncidentReport from './details/ReportManageIncidentReport';
// let incidenttype = [
//         'Inventory Discrepancy', 'Habitual Tardiness', 'Habitual Absences', 'TYREPLUSAbsence w/o official leave', 'Insubordination',
//         'Non-compliance to policies/procedures', 'Delivery Discrepancy', 'Theft', 'Falsification/Tampering of Documents', 'Loss/Damage of Company Property',
//         'Non remittance/short of collections', 'Others'
//     ];
// let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props: ['title'],
    components:{
        ReportManageIncidentReport
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
            selected: {}
        }
    },
    watch:{
        rows(val, old){
            let row = val;

            row.forEach((item, index)=>{
                if(!isNaN(item.incidenttype) && !isNaN(item.status)){
                    row[index]['incidenttype'] = incidenttype[item.incidenttype - 1];
                    row[index]['status'] = status[item.status];
                }
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
                if(item.incidentID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#reportIRModal").modal("hide");
                }
            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.incidentID == val.incidentID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });


        },
        setUpdate(data){
            bus.$emit('setupdateIR', data);
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
                        'EMPLOYEE ID', 'EMPLOYEE NAME', 'DEPARTMENT NAME', 'POSITION', 'DATE FILED',
                        'DATE OCCURED', 'INCIDENT TIME', 'PERSON/S INVOLVED', 'DESIGNATION', 'BRANCH',
                        'NATURE OF INCIDENT', 'DETAILS OF INCIDENT', 'INITIAL ACTION TAKEN', 'REASON',
                        'EXPLANATION', 'APPROVER', 'APPROVED DATE', 'REMARKS', 'REFS',
                        '1st ENDORSEMENT', '1st ENDORSEMENT EMAIL', '1st ENDORSMENT ACTION TAKEN', 'DEDUCTION AMT', '1st ENDORSEMENT REMARKS',
                        '2nd ENDORSEMENT', '2nd ENDORSEMENT EMAIL', '2nd ENDORSMENT ACTION TAKEN', 'DEDUCTION AMT', '2nd ENDORSEMENT REMARKS',
                        'APPROVED DATE', 'CLOSE DATE',
                        'STATUS'
                        ];
            rows.push(header);
            this.rows.forEach(obj => {
                let records = [
                            obj.empID_, obj.fullname, obj.deptname, obj.posname, obj.datefiled,
                            obj.dateccured, obj.incidenttime, obj.personsinvolve, obj.designation, obj.branch, 
                            obj.incidenttype, obj.details, obj.actiontaken, obj.reason, 
                            obj.explanation, obj.approvedby, obj.approveddate, obj.remarks, obj.refs,
                            obj.endorse1, obj.endorse1_email, obj.actionTaken1, obj.deduction_amt1,  obj.endorse1_remarks, 
                            obj.endorse2, obj.endorse2_email, obj.actionTaken2, obj.deduction_amt2,  obj.endorse2_remarks, 
                            obj.approveddate2, obj.closeDate,
                            obj.status == 1 ? '1st Endorsement' : 
                            obj.status == 2 ? '2nd Endorsement' : 
                            obj.status == 3 ? 'Closed' : 
                            obj.status == 4 ? 'Rejected' : 'Pending'
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
        bus.$off('initIRReport', this.setInit);
    },
    mounted(){

        let columnDefs = [
        // {
        //     title: "INCIDENT ID", data: 'incidentID'
        // },
        // {
        //     title: "Employee ID", data: 'empID_'
        // },
        // {
        //     title: 'Employee Name',  data: 'fullname'
        // },{
        //     title: "Date Filed", data: 'datefiled'
        // },{
        //     title: "Nature of incident", data: 'incidenttype'
        // },{
        //     title: "Details of incident", data: 'details', className: "row-limit"
        // },
         {
            title: "INCIDENT ID", data: 'incidentID', visible: true,
        },{
            title: "Reported By", data: 'fullname'
        },{
            title: "Nature of incident", data: 'incidenttype'
        },{
            title: "Person Involved", data: 'search_employee'
        },
        {
            title: "Status", data: 'status',
            render: function(data){
                /**
                 * 0 pending
                 * 1 endorse 1
                 * 2 endorse 2
                 * 3 close
                 * 4 rejected
                 */
                return data == 0 ? 'Pending':
                       data == 1 || data == 2? 'Further Investigation':
                    //    data == 2 ? '2nd Endorsed': 
                       data == 3 ? 'Closed': 'Rejected';
            }
        }];
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        // axios.get('api/getIncidentReportbyemployee').then((response)=>{
            this.loader = false;
            // this.rows=response.data;

            this.dtHandle=$('#incidentReport').DataTable({
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "rowCallback": function(row, data, index) {
                var cellValue = data["status"];
                    if (cellValue==1 && !data['endorse2']) { // approved
                       $(row).addClass("td-approve");
                    }
                    if (cellValue==2 || (cellValue == 1 && data['endorse1'])) { // rejected
                       $(row).addClass("td-endorse");
                    }
                    if (cellValue==3) { // executed
                       $(row).addClass("td-close");
                    }
                    if (cellValue==4) { // executed
                       $(row).addClass("td-reject");
                    }

                }
            });

            let table = this.dtHandle;
            // let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#incidentReport tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                if(row.data().status >= 1 )
                {
                    self.disabledinput = true;
                    // return;
                }
                let dataforedit = row.data();
                self.selected = row.data();
                self.setUpdate(dataforedit);
                // console.log(row.data());


            });

        // }).catch((err)=>{
        //     console.log(err);

        // });

        // APPROVERS
        // axios.get('api/getIncidentReportApprover').then((response)=>{
        //     this.approvers =  response.data;
        // })
        // .catch((err)=>{});



        // MODAL
        $('#reportIRModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initIRReport', this.setInit);
    }

}
</script>