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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#reportSupModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="supplementary" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="reportSupModal" class="modal fade" role="dialog" >
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageSupplementary :userinfo="$root.userinfo" :selected="selected"></ReportManageSupplementary>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->

        </div>
    </div>
</template>
<script>
// import ManageSupplementary from '../../../components/public/ManageSupplementary';
import ReportManageSupplementary from './details/ReportManageSupplementary';
let brand = ['NTMC', 'APBW', 'PHILCREST', 'TYREPLUS'];
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props:['title'],
    components:{
        ReportManageSupplementary
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
                if(!isNaN(item.brand) && !isNaN(item.status)){
                    row[index]['brand'] = brand[item.brand - 1];
                    // row[index]['status'] = status[item.status];
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
                if(item.supID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#reportSupModal").modal("hide");
                }
            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.supID == val.supID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });


        },
        setUpdate(data){
            bus.$emit('setupdateSup', data);
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
            let header = [
                'SUP-ID','EMPLOYEE ID', 'EMPLOYEE NAME', 'COMPANY', 'DEPARTMENT NAME', 'POSITION', 'DATE FILED', 'WITNESSES',  'APPROVER', 'APPROVED DATE', 'REMARKS', 'STATUS',
                'SUPPLEMENTARY DATE', 'SUP TIME-IN(AM)', 'SUP TIME-OUT(AM)', 'SUP TIME-IN(PM)', 'SUP TIME-OUT(PM)', 'REASON', 'DETAILED REASON',
            ];
            rows.push(header);
            this.rows.forEach(obj => {
                // let records = [];
                obj.entries.forEach((entry, i)=>{
                    // if(i < 1){
                        let records = [
                            obj.supID,
                            obj.empID_, obj.fullname, obj.compname, obj.deptname, obj.posname,
                            obj.datefiled, //obj.brand,
                            obj.witnesses, obj.approvedby,
                            obj.approveddate, obj.remarks, obj.status,
                            // entries
                            entry.supdate, entry.timein, entry.timeout,
                            entry.timein2, entry.timeout2, entry.reason, obj.detailedreason
                        ];
                    // }else{
                    //     records = [
                    //         '', '', '', '',
                    //         '', '', '', '',
                    //         '', '', '',
                    //         // intries
                    //         entry.supdate, entry.timein, entry.timeout,
                    //         entry.timein2, entry.timeout2, entry.reason
                    //     ];
                    // }
                    rows.push(records);
                });
                // records.push();

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
        bus.$off('initSupReport', this.setInit);
    },
    mounted(){

        let columnDefs = [
        //     {
        //     title: "SUP ID", data: 'supID'
        // },
        {
            title: "Employee ID", data: 'empID_'
        },{
            title: 'Employee Name',  data: 'fullname'
        },{
            title: "Date Filed", data: 'datefiled'
        },
        // {
        //     title: "Brand", data: 'brand'
        // },
        {
            title: "Status", data: 'status',
            render: function ( data, type, row, meta ) {
                return data == 0 ? 'Pending': data == 1? 'Verified': data== 2? 'Approved': 'Rejected';
            }
        },
        {
            title: "Approver", data: 'approvedby', clasName: 'approver'
        }];

        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        // axios.get('api/getSupplementarybyemployee').then((response)=>{
            this.loader = false;
            // this.rows=response.data;

            this.dtHandle=$('#supplementary').DataTable({
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
            $("#supplementary tbody").on('click', 'tr', function() {
                // var tr = $(this).closest('tr');
                // var row = table.row( tr );
                // if(row.data().status.toLowerCase() == 'approved' ||
                //    row.data().status.toLowerCase() == 'rejected')
                // {
                //     self.disabledinput = true;
                // }
                // let dataforedit = row.data();
                // self.selected = row.data();
                // self.setUpdate(dataforedit);
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                if(row.data().status == 1 ||
                  row.data().status == 2 ||
                   row.data().status == 3)
                {
                    self.disabledinput = true;
                }
                let dataforedit = row.data();
                self.selected = row.data();
                self.setUpdate(dataforedit);


            });

        // }).catch((err)=>{
        //     console.log(err);

        // });

        // APPROVERS
        // axios.get('api/getSupplementaryApprover').then((response)=>{
        //     this.approvers =  response.data;
        // })
        // .catch((err)=>{});



        // MODAL
        $('#reportSupModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initSupReport', this.setInit);

    }

}
</script>