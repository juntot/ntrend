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
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button> -->
            </div>
            <table id="approvalenrollmentprogramform" class="mdl-data-table" style="width:100%"></table>

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
                            <ManageEnrollmentProgramForm :userinfo="$root.userinfo" :selected="selected"></ManageEnrollmentProgramForm>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ManageEnrollmentProgramForm from '../../components/public/ManageEnrollmentProgramForm';
let check = ['<i class="fas fa-times"></i>', '<i class="fas fa-check"></i>'];
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    components:{
        ManageEnrollmentProgramForm
    },
    data(){
        return{
            formtitle: '',
            forapprover: '',
            isCancel: false,
            rows: [],
            disabledinput: true,
            dtHandle: null,
            loader: true,
            empID_: '', //requestedby
            selected: {},
        }
    },
    watch:{
        rows(val, old){
            let row = val;
            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();
        }
    },
    methods:{
        addRow(val)
        {
            console.log(val);
             this.rows.unshift(val);
        },
        deleteRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.enrollmentID == val)
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
                if(item.enrollmentID == val.enrollmentID)
                {
                    // let data = item;
                    // data['status'] = val.status;
                    // data['remarks'] = val.remarks;
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
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
        this.formtitle = this.$route.name;

        axios.get('api/approvalenrollmentprog').then((response)=>{
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

            this.dtHandle=$('#approvalenrollmentprogramform').DataTable({
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
                    if (cellValue == 0) {
                       $(row).addClass("tr-pending");
                    }
                    if (cellValue==1) {
                       $(row).addClass("tr-approved");
                    }
                    if (cellValue==2) {
                       $(row).addClass("tr-rejected");
                    }
                }
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#approvalenrollmentprogramform tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                // CHECK IF STATUS IS APPROVED TO BE READY FOR CANCELLATION
                if(row.data().status >= 1){
                    self.isCancel = true;
                }
                let dataforedit = row.data();
                self.selected = row.data();
                // self.empID_ = row.data().empID_;
                self.setUpdate(dataforedit);

            });

        }).catch((err)=>{
            console.log(err);

        });

        let columnDefs = [
            {
            title: "Program #", data: 'enrollmentID', visible: true,
        },
        {
            title: "Date & Time", data: 'dateenrolled',
        },
        {
            title: "Customer Name", data: 'customer_name'
        },
        {
            title: 'Program Code', data: 'program_code'
        },
        {
            title: 'Program Name', data: 'program_name'
        },
        {
            title: "Status", data: 'status',
            render: function(data){
                return data == 0? 'Pending':
                       data == 1 ? 'Approved':
                       data == 2 ? 'Rejected': ''
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