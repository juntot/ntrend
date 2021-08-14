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
            <h4>MANAGE FORM</h4>
        </div>
        <div class="col-lg-12 canvas-chart" v-show="true">
            <!-- <div class="col-lg-6 col-md-6  with-margin-bottom nopadding">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
            </div> -->
            <table id="manageform" class="mdl-data-table" style="width:100%"></table>

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
                        // temp[i] = column[i];
                        if(index == 'formtitle' || index == 'formID' || index == 'navname')
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
        updateFormByDept(navname = null, formid = null, deptid = null, status = null){
            axios.get('api/updateformbydept/'+navname+'/'+formid+'/'+deptid+'/'+status).then((response)=>{
                console.log(response);
            }).catch((err)=>{ console.log(err); });
        },

        addFormByDept(formid = null, deptid = null)
        {
            axios.get('api/addformbydept/'+formid+'/'+deptid).then((response)=>{
                console.log( response);        
            }).catch((err)=>{ console.log(err); });
        },
        
        unCheckFormByDept(formid = mull, deptid = null)
        {
            axios.get('api/uncheckformbydept/'+formid+'/'+deptid).then((response)=>{
                console.log( response);        
            }).catch((err)=>{ console.log(err); });
        }
    },
    created(){

    },
    mounted() {
        
        axios.get('api/getform').then((response)=>{
            this.loader = false;
            // let rows = response.data.delete
            this.rows = response.data;
            let column = response.data[0];
            let header = [];
            
            // SET HEADER
            for(let index in column)
            {
                if(index != 'status' && index != 'formID' && index != 'navname')
                {
                    if(index == 'formtitle')
                    {
                        header.push({ title: column[index], data: index });
                    }else{
                        header.push(
                            {
                                title: ((index.replace(/0/g, ' ')).replace(/_/g, '-')).replace(/8/g,'&'),
                                className:      'details-control',
                                orderable:      false,
                                data:           index
                                // defaultContent: `
                                //     <label class="mdblbl inline-blocklbl">
                                //     <input type="checkbox" value="${index}" class="canverify" ${column[index]>0 ? 'checked="checked"':''}> 
                                //     <span class="mdbcheckmark"></span>
                                //     </label>
                                // `
                            }
                        );
                    }
                    
                }
                
            }

            if(header.length <= 0){
                header.push({ title: "Records", data: 'records'});
            }
            this.dtHandle = $('#manageform').DataTable({
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
                $('#manageform tbody').on('change', 'input', function() {
                    if(rows > 0)
                    {
                        let tr = $(this).closest('tr');
                        let row = table.row( tr );
                        // self.updateFormByDept(row.data().formID, $(this).val());
                        if(this.checked) {
                            // console.log(row.data());
                            self.updateFormByDept(row.data().navname, row.data().formID, $(this).val(), 1); //1 for active
                        }else{
                            // self.unCheckFormByDept(row.data().formID, $(this).val()); // 0 to unset
                            self.updateFormByDept(row.data().navname, row.data().formID, $(this).val(), 0); // 0 to unset
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
