<style scoped>
.dropdown-menu.worktypefilter {
    min-width: 298px !important;
    max-height: 400px;
    overflow: auto;
}
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			{{formtitle}}
		</div>
        <div class="col-lg-12 margin-15">
            <div class="col-md-6">
                <div class="dflex">
                        <div :class="openFilter?'dropdown open z-1':'dropdown z-1'" style="margin-right: 15px;" >
                            <button class="btn btn-primary dropdown-toggle" type="button" @click.prevent="openFilter = !openFilter">
                                Filter Status
                            <!-- <span class="caret"></span> -->
                            </button>
                            
                            <ul class="dropdown-menu" style="padding: 10px">
                                <button type="button" class="close" 
                                
                                @click="openFilter = !openFilter">×</button>
                                <li style="padding: 4px 15px">
                                    <label>
                                        <input type="checkbox" value="0" v-model="status" name="status" >
                                        <span class="mdbcheckmark"></span>
                                        Pending
                                    </label>
                                </li>
                                <li style="padding: 4px 15px">
                                    <label>
                                        <input type="checkbox" value="3" v-model="status" name="status" >
                                        <span class="mdbcheckmark"></span>
                                        Executed
                                    </label>
                                </li>
                                <li style="padding: 4px 15px">
                                    <label>
                                        <input type="checkbox" value="1" v-model="status" name="status" >
                                        <span class="mdbcheckmark"></span>
                                        Approved
                                    </label>
                                </li>
                                <li style="padding: 4px 15px">
                                    <label>
                                        <input type="checkbox" value="2" v-model="status" name="status" >
                                        <span class="mdbcheckmark"></span>
                                        Rejected
                                    </label>
                                </li>
                                <li style="padding: 4px 15px">
                                    <label>
                                        <input type="checkbox" value="4" v-model="status" name="status" >
                                        <span class="mdbcheckmark"></span>
                                        Confirmed
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <!-- filter req type -->
                        <div :class="openFilterReqType?'dropdown open z-1':'dropdown z-1'">
                            <button class="btn btn-primary dropdown-toggle" type="button" @click.prevent="openFilterReqType = !openFilterReqType">
                                Filter Request Type
                            <!-- <span class="caret"></span> -->
                            </button>
                            
                            <ul class="dropdown-menu worktypefilter" style="padding: 10px">
                                <button type="button" class="close" 
                                
                                @click="openFilterReqType = !openFilterReqType">×</button>
                                <li v-for="(val, i) in worktypeFilter" style="padding: 4px 15px" :key="i">
                                    <label>
                                        <input type="checkbox" v-model="worktype" :value="val" name="worktype" >
                                        <span class="mdbcheckmark"></span>
                                        {{val}}
                                    </label>
                                </li>
                            </ul>
                        </div>
                </div>
                    
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
let worktype = ['system access',
                'borrow item',
                'system autorization',
                'rdp access',
                'password reset',
                'internet access',
                'email setup',
                'install apps (spark, skype etc.)',
                'setup workstation',
                'setup printer',
                'layout design',
                'cleaning / maintenance',
                'repair',
                'format',
                'system report & layout',
                'system development',
                'gps report',
                'conversation history',
                'cctv report',
                'file & data recovery',
                'assistance',
                ].sort();

// let status = ['Pending', 'Approved', 'Rejected'];
let defaultRows = [];
export default {
    components:{
        ManageWorkRequest
    },
    data(){
        return{
            openFilter: false,
            openFilterReqType: false,
            status: ['0', '1', '2', '3', '4'],
            formtitle: '',
            forapprover: '',
            isCancel: false,
            rows: [],
            disabledinput: true,
            dtHandle: null,
            loader: true,
            empID_: '', //requestedby
            selected: {},
            worktype: worktype,
            worktypeFilter: worktype
        }
    },
    watch:{
        status(val, old){
            let rows = [];
            // if(val.includes('1.1')){
                // rows = 
            // }
            let tempReqType = [];
            rows = defaultRows.filter(data=>{
                tempReqType = (data.request_type.toLowerCase()).split(',');
                return val.includes(data.status+'') && this.worktype.some((type)=>tempReqType.indexOf(type)>=0);
            });

            this.dtHandle.clear();
            this.dtHandle.rows.add(rows);
            this.dtHandle.draw();
        },
        rows(val, old){
            let rows = [];
            // let row = val;
            let tempReqType = [];
            rows = defaultRows.filter(data=>{
                tempReqType = (data.request_type.toLowerCase()).split(',');
                return this.status.includes(data.status+'') && this.worktype.some((type)=>tempReqType.indexOf(type)>=0);
            });
            
            this.dtHandle.clear();
            this.dtHandle.rows.add(rows);
            this.dtHandle.draw();
        },
        worktype(val, old){
            let rows = [];
            let tempReqType = [];
            rows = defaultRows.filter(data=>{
                tempReqType = (data.request_type.toLowerCase()).split(',');
                return this.status.includes(data.status+'') && (tempReqType.some((type) => val.indexOf(type)>=0))
            });
            
            this.dtHandle.clear();
            this.dtHandle.rows.add(rows);
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
                    let data = item;
                    data['status'] = val.status;
                    data['remarks'] = val.remarks;
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(data);
                }
            });

        },
        setUpdate(data){
            bus.$emit('setupdate', data);
        },

        closeModal(){
            this.isCancel = false;
            this.selected = {};
        },

    },
    created(){

    },

    mounted(){
        this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[0];
        this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        axios.get('api/approvalWorkRequest').then((response)=>{
            this.loader = false;
            this.rows=response.data;
            defaultRows = response.data;            

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
            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [3] }],
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "rowCallback": function(row, data, index) {
                var cellValue = data["status"];
                    if (cellValue==1) { // approved
                       $(row).addClass("tr-approved");
                    }
                    if (cellValue==2) { // rejected
                       $(row).addClass("tr-rejected");
                    }
                    if (cellValue==3) { // executed
                       $(row).addClass("tr-executed");
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
            $("#workrequest tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                // CHECK IF STATUS IS APPROVED TO BE READY FOR CANCELLATION
                if(row.data().status == 1){
                    self.isCancel = true;
                }
                if(row.data().status == 3){
                    self.isCancel = true;
                }
                let dataforedit = row.data();
                self.selected = row.data();
                self.empID_ = row.data().empID_;
                self.setUpdate(dataforedit);

            });

        }).catch((err)=>{
            console.log(err);

        });

        let columnDefs = [
        {
            title: "Ticket #", data: 'workID'
        },
        // {
        //     title: "Employee ID", data: 'empID_',
        //     visible: false
        // },
        {
            title: "Employee Name", data: 'fullname'
        },{
            title: "Date & Time Filed", data: 'datefiled_datetime',
            render: function(data) {
                return moment(data).format('MM/DD/YYYY HH:mm:ss');
            }
        },
        // {
        //     title: "Date Needed", data: 'dateneed'
        // },
        {
            title: "Reason", data: 'reason', className: "row-limit"
        },{
            title: "Urgency", data: 'urgency',
        },
        // {
        //     title: "Type of Work", data: 'worktype',
        //     render: function(data){
        //         return data > 1? 'Permanent' : 'Temporary'
        //     }
        // },
        {
            title: "Status", data: 'status',
            render: function(data){
                return data == 1 ? 'Approved':
                       data == 2 ? 'Rejected':
                       data == 3? 'Executed':
                       data == 4? 'Confirmed' : 'Pending';
            }
        }];

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });
    }

}
</script>