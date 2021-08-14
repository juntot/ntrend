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
            <h4>MANAGE FORM APPROVERS BY USER</h4>
        </div>
        <div class="col-lg-12 canvas-chart" v-show="true">
            <!-- <div class="col-lg-6 col-md-6  with-margin-bottom nopadding">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
            </div> -->
            <table id="manageformapprover" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <!-- <ManagePosition></ManagePosition> -->
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
// import ManagePosition from '../components/ManagePosition';
export default {
    components: {
        // ManagePosition
    },
    data() {
        return {
            headers: [],
            rows: [],
            dtHandle: null,
            showModal: false,
            loader: true
        }
    },
    watch:{
        rows(val, old){
            let row  =  []; //this.rows;
            val.forEach((column)=>{
                let temp = {};
                for(let index in column){
                    if(index != 'status' && index != 'navlink'){
                        // temp[i] = column[i];')
                        if(index == 'empID_' || index == 'fname' || index == 'lname')
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
        updateFormApprover(empid = null, formname = null, status = null){
            axios.get('api/updateformapprover/'+empid+'/'+formname+'/'+status).then((response)=>{
            }).catch((err)=>{ console.log(err); });
        },
    },
    created(){

    },
    mounted() {
         
        axios.get('api/getformapprovers').then((response)=>{
            this.loader = false;
            // let rows = response.data.delete
            this.rows = response.data;
            let column = response.data[0];
            let sorted = Object.entries(column)
            .sort()
            .reduce((r, [k, v]) => ({ ...r, [k]: v }), {});
            let header = [];
            
            // SET HEADER
            for(let index in sorted)
            {
                if(index != 'status')
                {
                    if(index == 'empID_')
                    {
                        header.unshift({ title: 'Employee ID', data: index });
                    }else if(index == 'fname')
                    {
                        header.unshift({ title: 'First Name', data: index });
                    }else if(index == 'lname')
                    {
                        header.unshift({ title: 'Last Name', data: index });
                    }
                    else{
                        header.push(
                            {
                                title: ((index.replace(/0/g, ' ')).replace(/_/g, '-')).replace(/8/g,'&'),
                                className:      'details-control',
                                orderable:      false,
                                data:           index
                            }
                        );
                    }
                    
                }
                
            }

            if(header.length <= 0){
                header.push({ title: "Records", data: 'records'});
            }
            this.dtHandle = $('#manageformapprover').DataTable({
                "sPaginationType": "simple_numbers",
                data: [],
                columns: header,
                "sPaginationType": "simple_numbers",
                "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
                "scrollX": true,
            });

            
            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
                $('#manageformapprover tbody').on('change', 'input', function() {
                    if(rows > 0)
                    {
                        let tr = $(this).closest('tr');
                        let row = table.row( tr );
                        
                        if(this.checked) {    
                            self.updateFormApprover(row.data().empID_, $(this).val(), 1); //1 for active
                        }else{
                            self.updateFormApprover(row.data().empID_, $(this).val(), 0); //1 for active
                        }
                    }
                });

        }).catch((err)=>{ console.log(err); });


        let columnDefs = [{
            title: "FORM NAME", data: 'formtitle'
        },{
            title: "Can Verify",
            className:      'details-control',
            orderable:      false,
            data:           'canverify',
            defaultContent: `
                <label class="mdblbl inline-blocklbl">
                <input type="checkbox" class="canverify"> 
                <span class="mdbcheckmark"></span>
                </label>
            `
        }];


    }
}
</script>
