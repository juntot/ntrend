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
                    <div :class="openFilter?'dropdown open':'dropdown'">
                    <button class="btn btn-primary dropdown-toggle" type="button" @click.prevent="openFilter = !openFilter">
                        Filter Status
                    <span class="caret"></span></button>
                    
                    <ul class="dropdown-menu" style="padding: 10px">
                        <button type="button" class="close" 
                        
                        @click="openFilter = !openFilter">x</button>
                        <li style="padding: 4px 15px">
                            <label>
                                <input type="checkbox" value="0" v-model="status" name="status" >
                                <span class="mdbcheckmark"></span>
                                Pending
                            </label>
                        </li>
                        <li style="padding: 4px 15px">
                            <label>
                                <input type="checkbox" value="1.0" v-model="status" name="status" >
                                <span class="mdbcheckmark"></span>
                                1st Endorsement
                            </label>
                        </li>
                        <li style="padding: 4px 15px">
                            <label>
                                <input type="checkbox" value="1.1" v-model="status" name="status" >
                                <span class="mdbcheckmark"></span>
                                2nd Endorsement
                            </label>
                        </li>
                        <li style="padding: 4px 15px">
                            <label>
                                <input type="checkbox" value="2" v-model="status" name="status" >
                                <span class="mdbcheckmark"></span>
                                Approved
                            </label>
                        </li>
                        <li style="padding: 4px 15px">
                            <label>
                                <input type="checkbox" value="3" v-model="status" name="status" >
                                <span class="mdbcheckmark"></span>
                                Rejected
                            </label>
                        </li>
                    </ul>
                    </div>
            </div>
            <!--  -->
            <table id="overrideform" class="mdl-data-table" style="width:100%"></table>

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
                            <ManageOverrideForm :userinfo="$root.userinfo" :selected="selected" ></ManageOverrideForm>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ManageOverrideForm from '../../components/public/ManageOverrideForm';
// let status = ['Pending', 'Approved', 'Rejected'];
let defaultRows = [];
export default {
    components:{
        ManageOverrideForm
    },
    data(){
        return{
            openFilter: false,
            forapprover: '',
            formtitle: '',
            rows: [],
            disabledinput: false,
            dtHandle: null,
            loader: true,
            approvers: [],
            reciever_emails: [],
            selected: {},
            status: ['0', '1.0', '1.1', '2', '3'],
        }
    },
    watch:{
        status(val, old){
            let rows = [];
            // if(val.includes('1.1')){
                // rows = 
            // }
            rows = defaultRows.filter(data=>{
                return val.includes(data.status+'') || data.status == 1;
            }).reduce((acc, cur)=>{
                if(!val.includes('1.0')){
                    
                    if(!cur.endorseddate){
                        console.log('tae', cur.endorseddate, cur.endorseddate != '');
                        acc.push(cur);
                    }
                }
                if(!val.includes('1.1')){
                    if(!cur.endorseddate2){
                        acc.push(cur);
                    }
                }
                if(val.includes('1.0') && val.includes('1.1')){
                    acc.push(cur);
                }
                
                return acc;
            }, []);

            

            this.dtHandle.clear();
            this.dtHandle.rows.add(rows);
            this.dtHandle.draw();
        },
        rows(val, old){
            let row = val;
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
                if(item.overrideID == val)
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
                if(item.overrideID == val.overrideID)
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


        this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[0];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();
        this.formtitle = this.$route.name;
        
        axios.get('api/approvalOverrideForm').then((response)=>{
            this.loader = false;
            this.rows = response.data;
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
            this.dtHandle=$('#overrideform').DataTable({
            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [2] }],
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "rowCallback": function(row, data, index) {
                
                var cellValue = data.status;
                    if (cellValue==0) {
                       $(row).addClass("tr-pending");
                    }
                    if (cellValue==1) {
                       $(row).addClass("tr-verified");
                    }
                    if (cellValue==2) {
                       $(row).addClass("tr-approved");
                    }
                    if (cellValue==3) {
                       $(row).addClass("tr-rejected");
                    }

                }
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#overrideform tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                // if(row.data().status == 2 ||
                //    row.data().status == 3)
                // {
                    self.disabledinput = true;
                    // return;
                // }
                let dataforedit = row.data();
                self.selected = row.data();
                
                self.setUpdate(dataforedit);

            });

        }).catch((err)=>{
            console.log(err);

        });

        // APPROVERS
        axios.get('api/getOverrideApprover').then((response)=>{
            this.approvers =  response.data;
        })
        .catch((err)=>{});

        let columnDefs = [
            {
            title: "Override #", data: 'overrideID', visible: true,
        },{
            title: "Division", data: 'division'
        },
        {
            title: "Date Override", data: 'dateoverride',
        },
        {
            title: "Customer Name", data: 'customer_name'
        },
        {
            title: "Creator", data: 'fullname'
        },
        {
            title: 'Amount of order', data: 'mode'
        },
        {
            title: "Status", data: 'status',
            render: function(data){
                return data == 0? 'Pending':
                       data == 1 ? 'Endorsed':
                       data == 2 ? 'Approved': 'Rejected'
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