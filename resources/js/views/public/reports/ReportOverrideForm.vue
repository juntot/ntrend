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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="overrideform" class="mdl-data-table" style="width:100%"></table>

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
                            <ReportManageOverrideForm :userinfo="$root.userinfo" :selected="selected"></ReportManageOverrideForm>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageOverrideForm from './details/ReportManageOverrideForm';
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props: ['title'],
    components:{
        ReportManageOverrideForm
    },
    data(){
        return{
            forapprover: '',
            
            rows: [],
            disabledinput: false,
            dtHandle: null,
            loader: true,
            approvers: [],
            reciever_emails: [],
            selected: {},
        }
    },
    watch:{
        rows(val, old){
            let row = val;
            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();
        },
        approvers(val, old){
            val.forEach(item=>{
                this.reciever_emails.push(item.email);
            })
        }
    },
    methods:{
       
        setUpdate(data){
            bus.$emit('setupdate', data);
        },

        closeModal(){
            this.disabledinput = false;
            this.selected = {};
        },
        downloadXLS(){

            let wb = XLSX.utils.book_new();

            let rows = [];
            let header = [
                'OVERRIDE ID', 'CREATOR NAME', 'DATABASE', 'BRANCH', 'DIVISION', 'DATE OVERRIDE & TIME', 
                'CUSTOMER NAME', 'MODE', 'SALES EMPLOYEE', 'SALES MANAGER', 'COMMITMENT DATE', 
                'COMMITMENT TIME', 'AMOUNT ORDER', 'PO/SO', 
                'CL', 'PDC', 'A/R', 'ORDER', 'TOTAL', 'EXCESS', '%',
                
                'ADDITIONAL INFO', 'REASONS',
                '1ST ENDORSER', '1ST ENDORSER DATE & TIME', '2ND ENDORSER', '2ND ENDORSER DATE & TIME', 
                'APPROVED BY', 'APPROVED DATE & TIME', 'APPROVER REMARKS', 'STATUS',
                'REQUEST TO 1ST ENDORSEMENT DURATION', 
                'REQUEST TO 2ND ENDORSEMENT DURATION', 
                '1ST ENDORSEMENT TO APPROVED DURATION', 
                '2ND ENDORSEMENT TO APPROVED DURATION',
                'REQUEST TO APPROVED DURATION', 
            ];

            rows.push(header);
            this.rows.forEach(obj => {                                
                let records = [
                            obj.overrideID, obj.fullname, obj.company, obj.branch, obj.division, obj.dateoverride, 
                            obj.customer_name, obj.mode, obj.sales_employee, obj.sales_manager, obj.commited_date, 
                            obj.commited_time, obj.amount_order, obj.po_so, 
                            obj.cl, obj.pdc, obj.ar, obj.order, obj.total, obj.excess2, obj.percent2,

                            obj.additional_info, obj.reasons, 
                            obj.endorsedby_, obj.endorseddate, obj.endorsedby2_, obj.endorseddate2, 
                            obj.approvedby, obj.approveddate, obj.remarks, obj.status == 1? 'Endorsed': obj.status == 2 ? 'Approved': obj.status == 3?
                            'Rejected' : 'Pending',
                            
                            
                            obj.endorseddate && obj.dateoverride ?
                            moment.utc(moment(obj.endorseddate, "HH:mm:ss").diff(moment(obj.dateoverride, "HH:mm:ss"))).format("HH:mm:ss") : '',

                            obj.endorseddate2 && obj.dateoverride ?
                            moment.utc(moment(obj.endorseddate2, "HH:mm:ss").diff(moment(obj.dateoverride, "HH:mm:ss"))).format("HH:mm:ss") : '',

                            
                            
                            obj.endorseddate && obj.approveddate ?
                            moment.utc(moment(obj.approveddate, "HH:mm:ss").diff(moment(obj.endorseddate, "HH:mm:ss"))).format("HH:mm:ss") : '',

                            obj.endorseddate2 && obj.approveddate ?
                            moment.utc(moment(obj.approveddate, "HH:mm:ss").diff(moment(obj.endorseddate, "HH:mm:ss"))).format("HH:mm:ss") : '',
                            
                            
                            
                            obj.approveddate && obj.dateoverride ?
                            moment.utc(moment(obj.approveddate, "HH:mm:ss").diff(moment(obj.dateoverride, "HH:mm:ss"))).format("HH:mm:ss") : '',

                            
                        ];
                // records.push();
                rows.push(records);
            });
            
            // console.log(rows);
            // /* SHEET 1 */
            // let ws = XLSX.utils.aoa_to_sheet([["a","b", "c"],[3,2,1],[1,2,3]]);
            
            let ws = XLSX.utils.aoa_to_sheet(rows);
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

            XLSX.writeFile(wb, this.title+".xls");
            /* uncomment writeFile to start saving excel */
        },
        setInit(data = null){
            this.rows = data;
        },

    },
    created(){
        
    },
    beforeDestroy(){
        bus.$off('initOverrideReport', this.setInit);
    },
    mounted(){        
        
            this.loader = false;

             let columnDefs = [
           
            {
                title: "Override #", data: 'overrideID', visible: true,
            },
            {
                title: "Division", data: 'division'
            },
            {
                title: "Date Override", data: 'dateoverride',
            },
            {
                title: "Customer Name", data: 'customer_name'
            },
            {
                title: "Creator", data: 'fullname'
            },
            {
                title: 'Amount of order', data: 'mode'
            },
            {
                title: "Status", data: 'status',
                render: function(data){
                    return data == 0? 'Pending':
                        data == 1 ? 'Endorsed':
                        data == 2 ? 'Approved': 'Rejected'
                }
            }];
            
            $.extend(jQuery.fn.dataTableExt.oSort, {
                "date-uk-pre": function (a) {
                    var x;
                    try {
                        var dateA = a.replace(/ /g, '').split("/");
                        var day = parseInt(dateA[1], 10);
                        var month = parseInt(dateA[0], 10);
                        var year = parseInt(dateA[2], 10);
                        var date = new Date(year, month - 1, day)
                        // x = date.getTime();
                        x = moment(a).valueOf();
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
            this.dtHandle=$('#overrideform').DataTable({
            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [2] }],
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "rowCallback": function(row, data, index) {
                var cellValue = data.status;
                    if (cellValue==0) {
                       $(row).addClass("tr-pending");
                    }
                    if (cellValue==1) {
                       $(row).addClass("tr-verified");
                    }
                    if (cellValue==2) {
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
            $("#overrideform tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                self.disabledinput = true;
                let dataforedit = row.data();
                self.selected = row.data();

                self.setUpdate(dataforedit);

            });


       

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initOverrideReport', this.setInit);

    }

}
</script>