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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#salDiscreModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="salarydiscrepancy" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="salDiscreModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageSalaryDiscrepancy :userinfo="$root.userinfo" :selected="selected"></ReportManageSalaryDiscrepancy>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageSalaryDiscrepancy from './details/ReportManageSalaryDiscrepancy';
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props: ['title'],
    components:{
        ReportManageSalaryDiscrepancy
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
                if(item.saldiscID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#salDiscreModal").modal("hide");
                }
            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.saldiscID == val.saldiscID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });


        },
        setUpdate(data){
            bus.$emit('setupdateSalDis', data);
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
            let header = ['SALDISC ID','EMPLOYEE ID', 'EMPLOYEE NAME', 'DEPARTMENT NAME', 'POSITION', 'DATE FILED', 'DATE DISCREPANCY', 'DETAILS', 'APPROVER', 'APPROVED DATE', 'REMARKS', 'STATUS'];
            rows.push(header);
            this.rows.forEach(obj => {
                let records = [
                            obj.saldiscID,
                            obj.empID_, obj.fullname, obj.deptname, obj.posname,
                            obj.datefiled, obj.discrepancydate, obj.reason,
                            obj.approvedby, obj.approveddate,
                            obj.remarks, obj.status
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
        bus.$off('initSalDisReport', this.setInit);
    },
    mounted(){

        let columnDefs = [
        // {
        //     title: "SALDISC ID", data: 'saldiscID'
        // },
        {
            title: "Employee ID", data: 'empID_'
        },{
            title: 'Employee Name',  data: 'fullname'
        },{
            title: "Date Filed", data: 'datefiled'
        }, {
            title: "Discrepancy Date", data: 'discrepancydate'
        },{
            title: "Reason", data: 'reason', className: "row-limit"
        },{
            title: "Status", data: 'status'
        }];


        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();


        // axios.get('api/getsalarydiscrepancybyemployee').then((response)=>{
            this.loader = false;
            // this.rows=response.data;

            this.dtHandle=$('#salarydiscrepancy').DataTable({
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            });

            let table = this.dtHandle;
            // let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#salarydiscrepancy tbody").on('click', 'tr', function() {

                var tr = $(this).closest('tr');
                var row = table.row( tr );
                if(row.data().status.toLowerCase() == 'approved' ||
                   row.data().status.toLowerCase() == 'rejected')
                {
                    self.disabledinput = true;
                    // return;
                }
                let dataforedit = row.data();
                self.selected =  row.data();
                self.setUpdate(dataforedit);
                // console.log(row.data());


            });

        // }).catch((err)=>{
        //     console.log(err);

        // });

        // // APPROVERS
        // axios.get('api/getSalaryDiscrepancyApprover').then((response)=>{
        //     this.approvers =  response.data;
        // })
        // .catch((err)=>{});



        // MODAL
        $('#salDiscreModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initSalDisReport', this.setInit);
    }

}
</script>