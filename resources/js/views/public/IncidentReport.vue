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
			{{formtitle}}
		</div>
        <div class="col-lg-12 margin-15">
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
            </div>
            <table id="incidentReport" class="mdl-data-table" style="width:100%"></table>

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
                            <ManageIncidentReport :userinfo="$root.userinfo" :selected="selected"></ManageIncidentReport>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ManageIncidentReport from '../../components/public/ManageIncidentReport';
// let incidenttype = [
//         'Inventory Discrepancy', 'Habitual Tardiness', 'Habitual Absences', 'TYREPLUSAbsence w/o official leave', 'Insubordination',
//         'Non-compliance to policies/procedures', 'Delivery Discrepancy', 'Theft', 'Falsification/Tampering of Documents', 'Loss/Damage of Company Property',
//         'Non remittance/short of collections', 'Others'
//     ];
// let status = ['Pending', 'Approved', 'Rejected'];

export default {
    components:{
        ManageIncidentReport
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
            selected: {}
        }
    },
    watch:{
        rows(val, old){
            let row = val;

            // row.forEach((item, index)=>{
            //     if(!isNaN(item.incidenttype) && !isNaN(item.status)){
            //         row[index]['incidenttype'] = incidenttype[item.incidenttype - 1];
            //         row[index]['status'] = status[item.status];
            //     }
            // });
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
                if(item.incidentID == val)
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
                if(item.incidentID == val.incidentID)
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
        }
    },
    created(){

    },

    mounted(){

        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();
        this.formtitle = this.$route.name;

        axios.get('api/getIncidentReportbyemployee').then((response)=>{
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
            this.dtHandle=$('#incidentReport').DataTable({
            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [1] }],
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
                    // if (cellValue==2) { // rejected
                    //    $(row).addClass("td-endorse");
                    // }
                    if (cellValue==3) { // executed
                       $(row).addClass("td-close");
                    }
                    if (cellValue==4) { // executed
                       $(row).addClass("td-reject");
                    }

                }
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
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

        }).catch((err)=>{
            console.log(err);

        });

        // APPROVERS
        axios.get('api/getIncidentReportApprover').then((response)=>{
            this.approvers =  response.data;
        })
        .catch((err)=>{});


        let columnDefs = [
        {
            title: "Incident #", data: 'incidentID', visible: true,
        },
        {
            title: "Date Filed", data: 'datefiled'
        },
        {
            title: "Person Involved", data: 'search_employee'
        },
        {
            title: "Nature of incident", data: 'incidenttype'
        },
        // {
        //     title: "Details of incident", data: 'details', className: "row-limit"
        // },
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
                       data == 1 || data == 2 ? 'Further Investigation':
                    //    data == 2 ? '2nd Endorsed': 
                       data == 3 ? 'Closed': 'Rejected';
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