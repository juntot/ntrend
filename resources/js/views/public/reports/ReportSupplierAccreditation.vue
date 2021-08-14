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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#supAccreModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="supaccred" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="supAccreModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageSupplierAccreditation :userinfo="$root.userinfo" :selected="selected"></ReportManageSupplierAccreditation>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageSupplierAccreditation from './details/ReportManageSupplierAccreditation';
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
let status = ['Pending', 'Approved', 'Rejected'];
let modepay = ['','CASH', 'CHECK', 'ONLINE DEPOSIT'];
// <!-- NOT SUBJECT FOR SELECTED BUT ADDED FOR PREPARATION -->
export default {
    props: ['title'],
    components:{
        ReportManageSupplierAccreditation
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
                if(item.accredID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#supAccreModal").modal("hide");
                }
            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.accredID == val.accredID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });


        },
        setUpdate(data){
            bus.$emit('setupdateSupAccre', data);
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
                'SUPPLIER NAME', 'HEAD OFFICE ADDRESS', 'BRANCH ADDRESS', 'PRODUCT/SERVICES',
                'WEBSIET', 'CONTACT PERSON', 'PHONE NUMBER', 'MOBILE', 'FAX NUM', 'TIN',
                'SEC/DTI REG NO', 'DATE REGISTERED', 'PHILHEALTH', 'SSS NUM',
                'YEAR IN BUSINESS', 'TOTAL CAPITAL', 'TOTAL ASSET', 'TOTAL LIABILITIES',
                'INDUSTRY', 'NO OF EMPLOYEES', 'NO OF REGULAR EMPLOYEES', 'NO OF ACTIVE CLIENTS',
                'DOCUMENTS PROVIDED', 'BANK/BRANCH', 'ACCOUNT NO', 'ACCOUNT NAME',
                'TERMS', 'PAYMENT MODE', 'ASSURANCE', 'AGREED TO TAKE RESPONSIBILTY',
                'REASON', 'APPROVER', 'APPROVED DATE', 'REMARKS', 'STATUS'
            ];
            rows.push(header);
            this.rows.forEach(obj => {
                let docuprovided = '';
                docuprovided += docu1 > 0? 'DTI/SEC Resgistration* ' : '';
                docuprovided += docu2 > 0? 'Latest Business Permit* ' : '';
                docuprovided += docu3 > 0? 'BIR Registration* ' : '';
                docuprovided += docu4 > 0? 'Latest SEC GIS received by SEC ' : '';
                docuprovided += docu5 > 0? 'Latest Audited Financial Statements* ' : '';

                docuprovided += docu6 > 0? 'A copy of the blank OR or Invoice* ' : '';
                docuprovided += docu7 > 0? 'Company Profile/Annual Report ' : '';
                docuprovided += docu8 > 0? 'List of Clients* ' : '';
                docuprovided += docu9 > 0? 'List of Suppliers ' : '';
                docuprovided += docu10 > 0? 'Pictures of the Establishment* ' : '';

                docuprovided += docu11 > 0? 'Pictures of products/work, if any ' : '';

                let records = [
                            obj.empID_, obj.fullname, obj.deptname, obj.posname, obj.datefiled,
                            obj.supname, obj.supheadoffice, obj.supbranchadd, obj.product,
                            obj.website, obj.contactperson, obj.phone, obj.mobile, obj.fax, obj.TIN,
                            obj.SECDTIregno, obj.datereg, obj.philhealth, obj.SSS,
                            obj.yearinbusiness, obj.capital, obj.asset, obj.liabilities,
                            obj.industry, obj.totalemployees, obj.regemployee, obj.activeclient,
                            docuprovided, obj.bankname, obj.accountno, obj.accountname,
                            obj.payterms, modepay[obj.modepayment], obj.productquality, obj.productcommitment,
                            obj.leavetype, obj.reason, obj.approvedby, obj.approveddate,
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
        bus.$off('initSupAccreReport', this.setInit);
    },
    mounted(){
        let columnDefs = [
        //     {
        //     title: "Acrred ID", data: 'accredID'
        // },
        {
            title: "Employee ID", data: 'empID_'
        },{
            title: "Employee Name", data: 'fullname'
        },{
            title: "Date Filed", data: 'datefiled'
        }, {
            title: "Supplier", data: 'supname'
        },{
            title: "Head office", data: 'supheadoffice', className: "row-limit"
        },{
            title: "website", data: 'website', className: "row-limit"
        },{
            title: "Status", data: 'status'
        }];
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        // axios.get('api/getSupplierAccreditationbyemployee').then((response)=>{
            this.loader = false;
            // this.rows=response.data;

            this.dtHandle=$('#supaccred').DataTable({
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
            $("#supaccred tbody").on('click', 'tr', function() {
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
                // console.log(row.data());


            });

        // }).catch((err)=>{
        //     console.log(err);

        // });

        // APPROVERS
        // axios.get('api/getSupplierAccreditationApprover').then((response)=>{
        //     this.approvers =  response.data;
        // })
        // .catch((err)=>{});




        // MODAL
        $('#supAccreModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initSupAccreReport', this.setInit);

    }

}
</script>