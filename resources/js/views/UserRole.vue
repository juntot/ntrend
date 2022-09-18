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
            let row = [];
            val.forEach((column)=>{
                let temp = {};
                for(let index in column){
                    if(index != 'status'){
                        // temp[i] = column[i];
                        if(index == 'empID' || index == 'fname' || index == 'lname' || index == 'mname' || index == 'posname' || index == 'deptname')
                        {
                            temp[index] = column[index];
                        }else{
                            temp[index] =`
                            <label class="mdblbl inline-blocklbl">
                            <input type="checkbox" value="${index}" ${column[index]==1? 'checked="checked"':''}> 
                            <span class="mdbcheckmark"></span>
                            </label>`;
                        }
                    }
                    
                    
                }
                row.push(temp);
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
        
        // isAdmin etc.
        updateUserRole(empid = null, type = null, val = null){
            axios.get('api/updateuserroles/'+empid+'/'+type+'/'+val).then((response)=>{
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
                            // alert($(this).val());
                            self.updateUserRole(row.data().empID, $(this).val(), 1); // 1 for checked
                        }
                        else{   
                            // self.updateUserRole(row.data().empID, 0); // 0 for unchecked
                            self.updateUserRole(row.data().empID, $(this).val(), 0); // 0 for unchecked
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
            data:           'isAdmin'
            // defaultContent: `
            //     <label class="mdblbl inline-blocklbl">
            //     <input type="checkbox" class="canapprove"> 
            //     <span class="mdbcheckmark"></span>
            //     </label>
            // `
        },
        {
            title: "Can Post", 
            className:      'details-control',
            orderable:      false,
            data:           'canPost'
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
        },{
            title: "Alter-Payslip",
            className:      'details-control',
            orderable:      false,
            data:           'addPayslip',
        //     defaultContent: `
        //         <label class="mdblbl inline-blocklbl">
        //         <input type="checkbox" class="canverify"> 
        //         <span class="mdbcheckmark"></span>
        //         </label>
        //     `
        },{
            title: "Upload DTR Log",
            className:      'details-control',
            orderable:      false,
            data:           'uploadDtr',
        //     defaultContent: `
        //         <label class="mdblbl inline-blocklbl">
        //         <input type="checkbox" class="canverify"> 
        //         <span class="mdbcheckmark"></span>
        //         </label>
        //     `
        },{
            title: "View DTR Report",
            className:      'details-control',
            orderable:      false,
            data:           'viewDTRReport',
        }
        // ,{
        //     title: "View Reports",
        //     className:      'details-control',
        //     orderable:      false,
        //     data:           'viewReports'
        // }
        
        ];

        
    } // end mounted
}
</script>



