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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#reportTransmittalModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="reportTransmittalTbl" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="reportTransmittalModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageTransmittal :userinfo="$root.userinfo" :selected="selected"></ReportManageTransmittal>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageTransmittal from './details/ReportManageTransmittal';
// let check = ['<i class="fas fa-times"></i>', '<i class="fas fa-check"></i>'];
// let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props: ['title'],
    components:{
        ReportManageTransmittal
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

            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
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
                if(item.transID == val)
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
                if(item.transID == val.transID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });

        },
        setUpdate(data){
            bus.$emit('setupdateTransmittalRep', data);
        },

        closeModal(){
            this.disabledinput = false;
            this.selected = {};
        },
        setInit(data = null){
            console.log('data === ', data)
            this.rows = data;
        },
        downloadXLS(){

            let wb = XLSX.utils.book_new();

            let rows = [];
            let header = [
                            'POUCH#',
                            'FROM', 
                            'SENDER BRANCH',
                            'TO',
                            'RECEIVER POSITION', 
                            'RECEIVER DEPARTMENT', 
                            'RECEIVER BRANCH', 
                            'CONTACT NUMBER',
                            'ADDRESS',
                            'TYPE',
                            'DATE & TIME FILED', 
                            'RECEIVED DATE & TIME',
                            'ADDITIONAL INFO', 
                            'REMARKS', 
                            'STATUS'
                        ];
            rows.push(header);
            this.rows.forEach(obj => {
                
                let records = [
                            obj.transID,
                            obj.fullname, obj.branchname,
                            obj.search_employee, obj.receiver_pos,
                            obj.receiver_dept, obj.receiver_branch,
                            obj.contactnum, obj.address,
                            obj.request_type,
                            obj.datefiled, moment(obj.confirmdate).format('DD/MM/YYYY HH:mm:ss A'),
                            obj.additionalInfo, obj.REMARKS, 
                            obj.status == 0? 'In Transit':
                            obj.status == 1 ? 'Partially Received':
                            obj.status == 2 ? 'Received': 'Rejected'
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
        bus.$off('initTransmittalReport', this.setInit);
    },
    mounted(){

         let columnDefs = [
        {
            title: "Pouch #", data: 'transID'
        },
        {
            title: "FROM", data: 'fullname'
        },
        {
            title: "TO", data: 'search_employee'
        },
        {
            title: "Date & Time Filed", data: 'datefiled',
            render: function(data) {
                return moment(data).format('MM/DD/YYYY HH:mm:ss A');
            }
        },
        {
            title: "Status", data: 'status',
            render: function(data){
                return data == 0? 'In Transit':
                       data == 1 ? 'Partially Received':
                       data == 2 ? 'Received': 'Rejected';
            }
        }];

        this.loader = false;
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
        this.dtHandle=$('#reportTransmittalTbl').DataTable({
        aoColumnDefs: [{ "sType": "date-uk", "aTargets": [4] }],
        "sPaginationType": "simple_numbers",
        data: [],
        columns: columnDefs,
        "sPaginationType": "simple_numbers",
        "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
        "scrollX": true,
        "order": [[ 0, "desc" ]],
        "rowCallback": function(row, data, index) {
            var cellValue = data["status"];
                    if (cellValue==1) { // partially received
                       $(row).addClass("tr-executed");
                    }
                    if (cellValue==2) { // confirmed
                       $(row).addClass("tr-approved");
                    }
                    if (cellValue==3) { // rejected
                       $(row).addClass("tr-rejected");
                    }
                    if (cellValue==4) { //confirmed
                       $(row).addClass("tr-verified");
                    }
                }
        });

        let table = this.dtHandle;
        let rows = this.rows.length;
        let self = this;
        // Add event listener for opening and closing details
        $("#reportTransmittalTbl tbody").on('click', 'tr', function() {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            if(row.data().status == 1 || // Approved
                row.data().status == 2 || //'rejected'
                row.data().status == 3 || // executed
                row.data().status == 4  //  confirmed
            )
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
        $('#reportTransmittalModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
          $('html').addClass('hidden-overflow');
        })
        $(".modal").on('hide.bs.modal', function(){
          $('html').removeClass('hidden-overflow');
        });

        bus.$on('initTransmittalReport', this.setInit);
    }

}
</script>