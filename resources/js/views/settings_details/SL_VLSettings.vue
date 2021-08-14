<template>
    <div>
        <!-- <div class="alert alert-success" id="alert-message">
          <strong>successfully sync</strong>
        </div> -->
        <!-- <h2>SL / VL Settings</h2> -->
        <div class="col-lg-12 header-title bgc-white">            
            <h2>SL / VL Settings</h2>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="col-lg-12 bgc-white">
            <br><br>
            <!-- id="settings_SL_VL" -->
            <div class="col-lg-6 nopadding">
                <button class="mdl-button mdl-button--raised mdl-button--colored" @click.prevent="resetLeaveCredit">Sync Now</button>
            </div>
            <table id="settings_pos_table" class="mdl-data-table" style="width:100%"></table>
        </div>
        <div class="clearfix"></div>
        <!-- Modal -->
        <div class="modal fade" id="settings_pos_table_employee_modal" role="dialog">
            <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Modal Methods</h4>
                </div>
                <div class="modal-body">
                    <table id="settings_pos_table_employee" class="mdl-data-table" style="width:100%"></table>
                </div>
            </div>

            </div>
        </div>
    </div>
</template>
<script>

export default {
    data(){
        return{
            rows: [],
            empRows: [],
            dtHandle: null,
            dtHandle2: null,
            loader: true,
            forUpdate: false,
        }
    },
    watch:{
        rows(val, old){

            // this.dtHandle.draw();
            return val.map(data=>{
                data['SL'] = `<span>
                                <input type="number" name="SL" value=${data.SL || 0} />
                            </span>`;
                data['VL'] = `<span>
                                <input type="number" name="VL" value=${data.VL || 0} />
                            </span>`;
                data['BL'] = `<span>
                                <input type="number" name="BL" value=${data.BL || 0} />
                            </span>`;
                data['DL'] = `<span>
                                <input type="number" name="DL" value=${data.DL || 0} />
                            </span>`;
                return data;
            });
        },
        empRows(val, old){
            // this.dtHandle.draw();
            let rows = val.map(data=>{
                data['SL'] = `<span>
                                <input type="number" name="SL" value=${data.SL || 0} />
                            </span>`;
                data['VL'] = `<span>
                                <input type="number" name="VL" value=${data.VL || 0} />
                            </span>`;
                data['BL'] = `<span>
                                <input type="number" name="BL" value=${data.BL || 0} />
                            </span>`;
                data['DL'] = `<span>
                                <input type="number" name="DL" value=${data.DL || 0} />
                            </span>`;
                return data;
            });
            this.dtHandle2.clear();
            this.dtHandle2.rows.add(rows);
            this.dtHandle2.columns.adjust().draw();
            return rows;
        }
    },
    methods:{
        resetLeaveCredit(){
            axios.post('api/resetNow')
            .then(res=>{
                alert('successfully snyc..');
            })
            .catch(er=>alert('Server ERROR'));
        },
        async initTable(){
            let row = [];
            this.dtHandle.clear();
            await this.dtHandle.rows.add(this.rows);
            this.dtHandle.columns.adjust().draw();
        },
        async initUserTable(){
            this.dtHandle2.columns.adjust().draw();
        },
        setUpdate(data){
            axios.get('api/get_users_bypos/'+data.posID)
            .then(res=>{
                this.empRows = res.data;
                $("#settings_pos_table_employee_modal").modal("show");
            });

        },
        setDefaultLeaves(posID, val, type){
            let postData = {
                posID_: posID,
                [type]: parseInt(val) || 0,
            };

            axios.post('api/set_default_leaves_bypos', postData)
            .then(res=>{
                // console.log(res.data);
            })
            .catch(er=>console.log(er, 'error saving default leave'));
        },

        // override per individual users
        setDefaultLeaves2(empID, val, type){
            let postData = {
                empID_: empID,
                [type]: parseInt(val) || 0,
            };

            axios.post('api/set_default_leaves_by_emp', postData)
            .then(res=>{
                // console.log(res.data);
            })
            .catch(er=>console.log(er, 'error saving default leave'));
        }
    },
    mounted(){

            $('#settings_pos_table_employee_modal').on('shown.bs.modal', this.initUserTable);



            $.fn.DataTable.ext.pager.numbers_length = 5;
            this.dtHandle=$('#settings_pos_table').DataTable({

            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [0] }],
            "sPaginationType": "simple_numbers",
            data: [],
            columns: [
                {
                    title: "Position Name", data: 'posname',
                },{
                    title: 'Sick Leave', data: 'SL', className: 'disabled-col'
                },{
                    title: 'Vacation Leave', data: 'VL', className: 'disabled-col'
                },{
                    title: 'Birthday Leave', data: 'BL', className: 'disabled-col'
                },{
                    title: 'Discretionary Leave', data: 'DL', className: 'disabled-col'
                }
            ],
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

            let disabledCol = false;
            // Add event listener for opening and closing details
            $("#settings_pos_table tbody").on('focus', 'input', function(e) {
                disabledCol = true;
                e.preventDefault();
            });

            $("#settings_pos_table tbody").on('blur', 'input', function(e) {
                disabledCol = true;
                e.preventDefault();
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                self.setDefaultLeaves(row.data().posID , $(this).val(), $(this).attr("name"));
            });



            $("#settings_pos_table tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                if(row.data() && !disabledCol)
                {
                    self.disabledinput = true;
                    // return;
                    let dataforedit = row.data();
                    // self.selected = row.data();
                    self.setUpdate(dataforedit);
                }else{
                    disabledCol = false;

                }


            });

        // get position
        axios.get('api/get_default_leaves_bypos').then((response)=>{
            this.rows = response.data;
        });


        // table per user
        this.dtHandle2=$('#settings_pos_table_employee').DataTable({

            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [0] }],
            "sPaginationType": "simple_numbers",
            data: [],
            columns: [
                {
                    title: "ID", data: 'empID',
                },{
                    title: "First Name", data: 'fname',
                },{
                    title: "Last Name", data: 'lname',
                },{
                    title: 'Sick Leave', data: 'SL', className: 'disabled-col'
                },{
                    title: 'Vacation Leave', data: 'VL', className: 'disabled-col'
                },{
                    title: 'Birthday Leave', data: 'BL', className: 'disabled-col'
                },{
                    title: 'Discretionary Leave', data: 'DL', className: 'disabled-col'
                }
            ],
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


            let table2 = this.dtHandle2;
            let self2 = this;

            let disabledCol2 = false;
            // Add event listener for opening and closing details
            $("#settings_pos_table_employee tbody").on('focus', 'input', function(e) {
                disabledCol2 = true;
                e.preventDefault();
            });

            $("#settings_pos_table_employee tbody").on('blur', 'input', function(e) {
                disabledCol2 = true;
                e.preventDefault();
                var tr = $(this).closest('tr');
                var row = table2.row( tr );
                self2.setDefaultLeaves2(row.data().empID , $(this).val(), $(this).attr("name"));
            });



    }
}
</script>