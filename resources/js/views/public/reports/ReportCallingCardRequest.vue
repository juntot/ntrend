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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#reportCardModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="callingcardform" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="reportCardModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageCallingCardRequest :userinfo="$root.userinfo" :selected="selected"></ReportManageCallingCardRequest>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageCallingCardRequest from './details/ReportManageCallingCardRequest';
let check = ['<i class="fas fa-times"></i>', '<i class="fas fa-check"></i>'];
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props: ['title'],
    components:{
        ReportManageCallingCardRequest
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

                if(item.ntmc == true){
                    row[index]['ntmc'] = check[1];
                }
                if(item.ntmc == false){
                    row[index]['ntmc'] = check[0];
                }
                if(item.apbw == true)
                {
                    row[index]['apbw'] = check[1];
                }
                if(item.apbw == false){
                    row[index]['apbw'] = check[0];
                }
                if(item.philcrest == true)
                {
                    row[index]['philcrest'] = check[1];
                }
                if(item.philcrest == false){
                    row[index]['philcrest'] = check[0];
                }
                if(item.tyreplus == true)
                {
                    row[index]['tyreplus'] = check[1];
                }
                 if(item.tyreplus == false){
                    row[index]['tyreplus'] = check[0];
                }
                if(item.solid == true)
                {
                    row[index]['solid'] = check[1];
                }if(item.solid == false){
                    row[index]['solid'] = check[0];
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
                if(item.ccID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#reportCardModal").modal("hide");
                }

            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.ccID == val.ccID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });

        },
        setUpdate(data){
            bus.$emit('setupdateCardReq', data);
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
                            'MOBILE 1', 'MOBILE 2', 'EMAIL', 'PRODUCT BRAND HANDLING',
                            'APPROVER', 'APPROVED DATE', 'REMARKS', 'STATUS'
                        ];
            rows.push(header);
            this.rows.forEach(obj => {
                let products = '';
                products += check.indexOf(obj.ntmc)>      0? 'NTMC ': ''
                products += check.indexOf(obj.philcrest)> 0? 'PHILCREST ': ''
                products += check.indexOf(obj.solid)>     0 ? 'SOLID ': ''
                products += check.indexOf(obj.apbw)>      0 ? 'APBW ': ''
                products += check.indexOf(obj.tyreplus)>  0 ? 'TYREPLUS ': ''
                let records = [
                            obj.empID_, obj.fullname, obj.deptname, obj.posname,
                            obj.datefiled, obj.mobile, obj.mobile2, obj.email,
                            products, obj.approvedby, obj.approveddate,
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
    created(){

    },
    beforeDestroy(){
        bus.$off('initCallCardReport', this.setInit);
    },
    mounted(){

        let columnDefs = [
        //     {
        //     title: "CC ID", data: 'ccID'
        // },
        {
            title: "Employee ID", data: 'empID_'
        },{
            title: "Employee Name", data: 'fullname'
        },{
            title: "Date Filed", data: 'datefiled'
        }, {
            title: "Mobile 1", data: 'mobile'
        },{
            title: "Mobile 2", data: 'mobile2'
        },{
            title: "Product", data: 'product'
        },{
            title: "NTMC", data: 'ntmc', className: "row-limit"
        },{
            title: "APBW", data: 'apbw', className: "row-limit"
        },{
            title: "PHILCREST", data: 'philcrest', className: "row-limit"
        },{
            title: "SOLID", data: 'solid', className: "row-limit"
        },{
            title: "TYREPLUS", data: 'tyreplus', className: "row-limit"
        },{
            title: "Status", data: 'status'
        }];

        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        // axios.get('api/getcallingcardbyemployee').then((response)=>{
            this.loader = false;
        //     this.rows=response.data;

            this.dtHandle=$('#callingcardform').DataTable({
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
            $("#callingcardform tbody").on('click', 'tr', function() {
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
                self.setUpdate(dataforedit);


            });

        // }).catch((err)=>{
        //     console.log(err);

        // });

        // APPROVERS
        // axios.get('api/getCallingCardApprover').then((response)=>{
        //     this.approvers =  response.data;
        // })
        // .catch((err)=>{});



        // MODAL
        $('#reportCardModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initCallCardReport', this.setInit);
    }

}
</script>