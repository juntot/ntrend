<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css'; */
    /* @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title">
            <h4>MANAGE FORM APPROVER BY USERS</h4>
        </div>
        <div class="col-lg-12 canvas-chart margin-15">
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding" v-show="!loader">
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button> -->
            </div>
            <table id="FormApproverByUser" class="mdl-data-table" style="width:100%" ref="aws"></table>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body" style="display: block; position:relative;">
                            <FormApproverByUserForm></FormApproverByUserForm>
                            <div class="clearfix"></div>
                        </div>
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div> -->
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
import FormApproverByUserForm from '../components/FormApproverByUserForm';

export default {
    components: {
        FormApproverByUserForm
    },
    data() {
        return {
            headers:  [],
            rows: [],
            dtHandle: null,
            loader: true,
            forUpdate: false,
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
        addRow(...val){

           this.rows.unshift({
               empID: val[0], fname: val[1], lname: val[2], mname: val[3], dhired: val[4],
               gender: val[5], branchname: val[6], deptname: val[7], posname: val[8]
            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.empID == val.empID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });
        },
        updateAdmin(empid = null, type = null){
            axios.get('api/updateadmin/'+empid+'/'+type).then((response)=>{
                console.log(response.data);
            }).catch((err)=>{
                console.log(err);
            });
        },

        setUpdate(data){
            bus.$emit('setupdate', data);
            $('#myModal').modal("show");
        },
        closeModal(){
            this.forUpdate = false;
        }
    },
    created(){

    },
    mounted() {

        axios.get('api/getemployees').then((response)=>{
            this.loader = false;
            this.rows=response.data;

            this.dtHandle=$('#FormApproverByUser').DataTable({
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#FormApproverByUser tbody").on('click', 'tr', function() {
                if(rows > 0)
                {
                    var tr = $(this).closest('tr');
                    var row = table.row( tr );
                    let dataforedit = row.data();
                    self.forUpdate = true;
                    self.setUpdate(dataforedit);
                }
            });

        }).catch((err)=>{
            console.log(err);
        });


        let columnDefs = [{
            title: "Employee ID", data: 'empID'
        },{
            title: "First Name", data: 'fname'
        }, {
            title: "Last Name", data: 'lname'
        },{
            title: "Middle Name", data: 'mname'
        },{
            title: "Date Hired", data: 'dhired'
        },{
            title: "Gender", data: 'gender'
        },{
            title: "Branch", data: 'branchname'
        },{
            title: "Department", data: 'deptname'
        },{
            title: "Position", data: 'posname'
        }];
        // ,{
        //     title: "Admin",
        //     className:      'details-control',
        //     orderable:      false,
        //     data:           'admin'
        //     // defaultContent: `
        //     //     <label class="mdblbl inline-blocklbl">
        //     //     <input type="checkbox" class="canapprove">
        //     //     <span class="mdbcheckmark"></span>
        //     //     </label>
        //     // `
        // }];

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

    }
}
</script>
