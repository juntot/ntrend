<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			{{title}}
		</div>
        <div class="col-lg-12 margin-15">
            <!-- <div class="col-lg-12 col-md-12  with-margin-bottom nopadding">
                <button class="btn btn-primary" data-toggle="modal" data-target="#laptopModal">ADD NEW</button>
            </div> -->
            <table id="laptopform" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="laptopModal" class="modal fade" role="dialog" ref="vuemodal">

                <div class="moLaptopg modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageLaptopAgreement :userinfo="$root.userinfo"></ReportManageLaptopAgreement>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->

        </div>
    </div>

</template>
<script>
import ReportManageLaptopAgreement from './details/ReportManageLaptopAgreement';
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others'];
let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props:['title'],
    components:{
        ReportManageLaptopAgreement
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
            isApprove: false,
        }
    },
    watch:{
        rows(val, old){
            let row = val;

            row.forEach((item, index)=>{
                if(!isNaN(item.status)){
                    row[index]['status'] = status[item.status];
                }

            });
            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();
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
                if(item.laptopID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#laptopModal").modal("hide");
                }
            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.laptopID == val.laptopID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });


        },
        setUpdate(data){
            bus.$emit('setupdateLaptop', data);
        },

        closeModal(){
            this.disabledinput = false;
            this.isApprove = false;
        },
        setInit(data = null){
            this.rows = data;
        }
    },
    beforeDestroy(){
        bus.$off('initLaptop', this.setInit);
    },
    mounted(){

        let columnDefs = [
        //     {
        //     title: "LAPTOP ID", data: 'laptopID'
        // },
        {
            title: "Employee ID", data: 'empID_'
        },{
            title: "Employee Name", data: 'fullname'
        },{
            title: "Date Filed", data: 'datefiled'
        }, {
            title: "Suggested Brand", data: 'suggestedbrand'
        },{
            title: "Suggested Model", data: 'suggestedmodel'
        },{
            title: "Status", data: 'status'
        }];

        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        // axios.get('api/getLaptopRequestbyemployee').then((response)=>{
            this.loader = false;
            // this.rows=response.data;

            this.dtHandle=$('#laptopform').DataTable({
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            });

            let table = this.dtHandle;
            // let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#laptopform tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                if(row.data().status.toLowerCase() == 'approved' ||
                   row.data().status.toLowerCase() == 'rejected')
                {
                    self.disabledinput = true;
                    self.isApprove = true;
                    // return;
                }
                let dataforedit = row.data();
                self.setUpdate(dataforedit);
                // console.log(row.data());

            });

        // }).catch((err)=>{
        //     console.log(err);

        // });
        // APPROVERS
        // axios.get('api/getLaptopRequestApprover').then((response)=>{
        //     this.approvers =  response.data;
        // })
        // .catch((err)=>{});




        // MODAL
        $('#laptopModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initLaptop', this.setInit);

    }

}
</script>