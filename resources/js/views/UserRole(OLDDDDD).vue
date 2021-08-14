<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css'; */
    /* @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12">
            <h4>USER ROLES (FORMS)</h4>
        </div>
        <div class="col-lg-12 canvas-chart">
            <table id="userrole" class="mdl-data-table" style="width:100%" ref="table"></table>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Modal Header</h4>
                        </div>
                        <div class="modal-body">
                            <!-- <AddEmployee ></AddEmployee> -->
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
import AddEmployee from '../components/ManageEmployee';

export default {
    components: {
        AddEmployee
    },
    data() {
        return {
            headers:  [],
            rows: [],
            dtHandle: null,
            selecteRow:'',
            name:'',
            form:{
                date: new Date(),
            },
            showModal: false,
            loader: true
        }
    },
    watch:{
        rows(val, old){
            let row = val;
             val.forEach((item, index) => {

                 row[index]['admin'] =`
                    <label class="mdblbl inline-blocklbl">
                    <input type="checkbox" class="isadmin" ${item.isAdmin ? 'checked="checked"':''}> 
                    <span class="mdbcheckmark"></span>
                    </label>`;

                // row[index]['canrequest'] =`
                //     <label class="mdblbl inline-blocklbl">
                //     <input id="canrequest" type="checkbox" class="formrole" ${item.canrequest?'checked="checked"':''}> 
                //     <span class="mdbcheckmark"></span>
                //     </label>`;
                // row[index]['canapprove'] = `
                //     <label class="mdblbl inline-blocklbl">
                //     <input id="canapprove" type="checkbox" class="formrole" ${item.canapprove?'checked="checked"':''}> 
                //     <span class="mdbcheckmark"></span>
                //     </label>
                // `;
                // row[index]['canverify'] =`
                //     <label class="mdblbl inline-blocklbl">
                //     <input id="canverify" type="checkbox" class="formrole" ${item.canverify?'checked="checked"':''}> 
                //     <span class="mdbcheckmark"></span>
                //     </label>
                // `;

           });
            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();
        }
    },
    methods:{
        updateRoles(empid = null, type = null, role = null){
            axios.get('api/updateuserformroles/'+empid+'/'+type+'/'+role).then((response)=>{
                console.log(response.data);
            }).catch((err)=>{
                console.log(err);
            });
        },
        updateAdmin(empid = null, type = null){
            axios.get('api/updateadmin/'+empid+'/'+type).then((response)=>{
                console.log(response.data);
            }).catch((err)=>{
                console.log(err);
            });
        }
    },
    created(){

    },
    mounted() {

        this.$nextTick(function () {
            axios.get('api/getemployees').then((response)=>{
                this.loader = false;
                this.rows = response.data;
                this.dtHandle = $('#userrole').DataTable({
                    "sPaginationType": "simple_numbers",
                    data: [],
                    columns: columnDefs,
                    "sPaginationType": "simple_numbers",
                    "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
                    "scrollX": true,    
                    });
                
                let table = this.dtHandle;
                let rows = this.rows.length;
                let self = this;
                // Add event listener for opening and closing details
                $('#userrole tbody').on('change', 'input', function() {
                    if(rows > 0){
                        let tr = $(this).closest('tr');
                        let reqtype = $(this).attr('id');
                        let row = table.row( tr );
                        if(this.checked) {
                            self.updateAdmin(row.data().empID, 1); // 1 for checked
                        }
                        else{   
                            self.updateAdmin(row.data().empID, 0); // 0 for unchecked
                        }
                    }
                });
        });
                
    }); //end vue next tick
        
        let columnDefs = [{
            title: "Employee ID", data: 'empID'
        },{
            title: "First Name", data: 'fname'
        },{
            title: "Last Name", data: 'lname'
        },{
            title: "Middle Name", data: 'mname'
        },{
            title: "Position", data: 'posname'
        },{
            title: "Department", data: 'deptname'
        },
        {
            title: "Admin", 
            className:      'details-control',
            orderable:      false,
            data:           'admin'
            // defaultContent: `
            //     <label class="mdblbl inline-blocklbl">
            //     <input type="checkbox" class="canapprove"> 
            //     <span class="mdbcheckmark"></span>
            //     </label>
            // `
        }
        ,{
            title: "Alter-Employee",
            className:      'details-control',
            orderable:      false,
            data:           'addEmp',
        //     defaultContent: `
        //         <label class="mdblbl inline-blocklbl">
        //         <input type="checkbox" class="canrequest"> 
        //         <span class="mdbcheckmark"></span>
        //         </label>
        //     `
        },{
            title: "Alter-Department", 
            className:      'details-control',
            orderable:      false,
            data:           'addDept',
        //     defaultContent: `
        //         <label class="mdblbl inline-blocklbl">
        //         <input type="checkbox" class="canapprove"> 
        //         <span class="mdbcheckmark"></span>
        //         </label>
        //     `
        },{
            title: "Alter-Position",
            className:      'details-control',
            orderable:      false,
            data:           'addPos',
        //     defaultContent: `
        //         <label class="mdblbl inline-blocklbl">
        //         <input type="checkbox" class="canverify"> 
        //         <span class="mdbcheckmark"></span>
        //         </label>
        //     `
        },{
            title: "Alter-Branch",
            className:      'details-control',
            orderable:      false,
            data:           'addBranch',
        //     defaultContent: `
        //         <label class="mdblbl inline-blocklbl">
        //         <input type="checkbox" class="canverify"> 
        //         <span class="mdbcheckmark"></span>
        //         </label>
        //     `
        }];

        
    } // end mounted
}
</script>



