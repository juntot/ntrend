<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			{{formtitle}}
		</div>
        <div class="col-lg-12 margin-15">
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
            </div>
            <table id="workrequest" class="mdl-data-table" style="width:100%"></table>

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
                            <ManageWorkRequest :userinfo="$root.userinfo" :selected="selected"></ManageWorkRequest>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ManageWorkRequest from '../../components/public/ManageWorkRequest';
let worktype = ['System Access (SAP, HRIS etc.)',
                'Borrow item',
                'System Autorization',
                'RDP Access',
                'Password Reset',
                'Internet Access',
                'Email Setup',
                'Install Apps(Spark, Skype, etc.)',
                'Setup Workstation',
                'Setup Printer'
                ,'Setup Telephone',
                'Cleaning / Maintenance',
                'Repair',
                'Format',
                'System Report',
                'System Layout',
                'GPS Report',
                'Conversation History',
                'CCTV Report',
                'File & Data Recovery'];

let status = ['Pending', 'Approved', 'Rejected'];

export default {
    components:{
        ManageWorkRequest
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
            reciever_emails: [],
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
                if(!isNaN(item.worktype)){
                    row[index]['worktype'] = worktype[(item.worktype - 1)];
                }
            });
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
                    $("#myModal").modal("hide");
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
            bus.$emit('setupdate', data);
        },

        closeModal(){
            this.disabledinput = false;
            this.selected = {};
        },

    },
    created(){

    },

    mounted(){
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        axios.get('api/getworkrequestbyemployee').then((response)=>{
            this.loader = false;
            this.rows=response.data;


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
            this.dtHandle=$('#workrequest').DataTable({
            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [0] }],
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "rowCallback": function(row, data, index) {
                var cellValue = data["status"];
                    if (cellValue=="Pending") {
                       $(row).addClass("bg-light-orange");
                    }

                }
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#workrequest tbody").on('click', 'tr', function() {
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

        }).catch((err)=>{
            console.log(err);

        });

        // APPROVERS
        axios.get('api/getWorkRequestApprover').then((response)=>{
            this.approvers =  response.data;
        })
        .catch((err)=>{});

        let columnDefs = [
        //     {
        //     title: "WORK ID", data: 'workID'
        // },{
        //     title: "Employee ID", data: 'empID_'
        // },
        {
            title: "Date Filed", data: 'datefiled'
        }, {
            title: "Date Needed", data: 'dateneed'
        },{
            title: "Type of Work", data: 'worktype'
        },{
            title: "Reason", data: 'reason', className: "row-limit"
        },{
            title: "Status", data: 'status'
        }];

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

    }

}
</script>