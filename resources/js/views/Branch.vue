<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title">
            <h4>MANAGE BRANCH</h4>
        </div>
        <div class="col-lg-12 canvas-chart margin-15">
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding" v-show="!loader">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
            </div>
            <table id="managebranch" class="mdl-data-table" style="width:100%"></table>

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
                            <ManageBranch></ManageBranch>
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
import ManageBranch from '../components/ManageBranch';

export default {
    components: {
        ManageBranch
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
            let row = [];
            this.dtHandle.clear();
            this.dtHandle.rows.add(val);
            this.dtHandle.draw();
        }
    },
    methods:{
        addRow(val){
           this.rows.unshift(val);
       },
       updateRow(val){
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.branchID == val.branchID)
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
            this.forUpdate = false;
        },
        delRow(id=''){
            let result = confirm("Are you sure you want to delete?");
            if (result) {
                axios.post('api/delbranch',{
                    branchID: id
                }).then((res)=>{
                    if(res.data.hasOwnProperty('error')){
                        alert('Cannot delete this branch. \nThere are '+res.data.error+' users from this branch.\nmake sure to assign them first to another branch.');
                    }else{
                        let row = this.$data.rows;
                        let data = row.filter((obj)=>obj.branchID != id);
                        this.rows = data;
                    }

                })
                .catch(er=>console.log(er))

            }
        }
    },
    created(){

    },
    mounted() {
        let dataSet = [
            {name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},
        ];

        let columnDefs = [{
            title: "BRANCH NAME", data: 'branchname'
        },{
            title: "Delete",
            className:      'details-control',
            orderable:      false,
            data:           'resetpass',
            defaultContent: `
                <button type="button" class="btn btn-primary del-btn">Delete</button>
            `
        },];

        axios.get('api/getbranch').then((response)=>{
            this.loader = false;
            this.rows=response.data;
            this.dtHandle = $('#managebranch').DataTable({
                "sPaginationType": "simple_numbers",
                data: [],
                columns: columnDefs,
                "sPaginationType": "simple_numbers",
                "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
                "scrollX": true,
                "order": [[ 0, "asc" ]],
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            let remove_emp = false;
            // Add event listener for opening and closing details
            $("#managebranch tbody").on('click', 'button.del-btn', function() {
                let tr = $(this).closest('tr');
                let row = table.row( tr );
                let dataforedit = row.data();
                // console.log(dataforedit.compID);
                self.delRow(dataforedit.branchID);
                remove_emp = true;
            });

            $('#managebranch tbody').on('click', 'tr',  function(){
                if(rows > 0 && !remove_emp)
                {
                    let tr = $(this).closest('tr');
                    let row = table.row( tr );
                    let dataforedit = row.data();
                    self.forUpdate = true;
                    self.setUpdate(dataforedit);
                }
            });

        }).catch((err)=>{
            console.log(err);

        });
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
