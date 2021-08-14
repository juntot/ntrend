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
            <table id="undertimeform" class="mdl-data-table" style="width:100%"></table>

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
                            <ManageOverrideForm :userinfo="$root.userinfo" :selected="selected"></ManageOverrideForm>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ManageOverrideForm from '../../components/public/ManageOverrideForm';
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    components:{
        ManageOverrideForm
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
                row[index]['starttime'] = moment((row[index]['starttime']), ["h:mm A"]).format('hh:mm A');
                row[index]['endtime'] = moment((row[index]['endtime']), ["h:mm A"]).format('hh:mm A');
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
                if(item.undertimeID == val)
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
                if(item.undertimeID == val.undertimeID)
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
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();
        this.formtitle = this.$route.name;
        
        axios.get('api/getoverride').then((response)=>{
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
            this.dtHandle=$('#undertimeform').DataTable({
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
                       $(row).addClass("tr-pending");
                    }
                    if (cellValue=="Approved") {
                       $(row).addClass("tr-approved");
                    }
                    if (cellValue=="Rejected") {
                       $(row).addClass("tr-rejected");
                    }

                }
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#undertimeform tbody").on('click', 'tr', function() {
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
                dataforedit['endtime'] = moment(dataforedit.endtime, ["h:mm A"]).format("HH:mm")
                dataforedit['starttime'] = moment(dataforedit.starttime, ["h:mm A"]).format("HH:mm")

                self.setUpdate(dataforedit);

            });

        }).catch((err)=>{
            console.log(err);

        });

        // APPROVERS
        axios.get('api/getUndertimeApprover').then((response)=>{
            this.approvers =  response.data;
        })
        .catch((err)=>{});

        let columnDefs = [
            {
            title: "Undertime ID", data: 'undertimeID', visible: false,
        },
        // {
        //     title: "Employee ID", data: 'empID_'
        // },
        {
            title: "Date Override", data: 'datefiled'
        }, {
            title: "Date Undertime", data: 'date_undertime'
        }, {
            title: "Start Time", data: 'starttime'
        },{
            title: "End Time", data: 'endtime'
        },{
            title: "Total(hrs)", data: 'totalhrs'
        },{
            title: "Reason", data: 'reason', className: "row-limit"
        },{
            title: "Status", data: 'status'
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