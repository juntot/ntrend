<template>
    <div>
        <div class="col-lg-12 bgc-white">
            <h2>EMPLOYEE SETTINGS</h2>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="bgc-white col-lg-12">
            <br><br>
            <table id="manage-employee-settings" class="mdl-data-table" style="width: 100%"></table>
        </div>

        <!-- Modal -->
        <div id="myModalUserSetting" class="modal fade" role="dialog" ref="vuemodal">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <!-- <h4 class="modal-title">Modal Header</h4> -->
                    </div>
                    <div class="modal-body">
                        <!-- <ManageEmployee></ManageEmployee> -->
                    </div>
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// import ManageEmployee from '../../components/ManageEmployee';
export default {
    //  components: {
    //     ManageEmployee
    // },
    data(){
        return{

            rows: [],
            selected_type: {
                formtitle:'',
            },

            dtHandle: null,
            loader: true,
            forUpdate: false,

            isProgress: false,
            isSuccess: false,
            isFailed:  false,
        }
    },
    watch:{
        rows(val, old){
            let row = val;

            // row.forEach((item, index)=>{
            //     // if(!isNaN(item.leavetype) && !isNaN(item.status)){
            //         row[index]['formtype'] = this.selected_type.formtitle;
            //     // }
            // });

            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();
        },
    },
    filters:{
        formType_filters(val){
            return val || 'Select Form Type';
        },
        status_filters(val){
            return val || 'Select Status';
        }
    },
    computed:{
        disableBtn(){
            return !this.selected_status.length > 0 || !this.selected_type.formtitle.length>0;
        }
    },
    methods:{
        pullRec(){
            axios.get('api/getemployees-inactive')
            .then(res=>{
                this.rows = res.data;
            })
            .catch(err=>console.log(err));
        },
        MDBINPUT(){
            this.$nextTick(() => {
                [].forEach.call(
                        document.querySelectorAll('.form-field__input, .form-field__textarea'),
                        (el) => {
                            el.onblur = () => {
                                setActive(el, false)
                            }
                            el.onfocus = () => {
                                setActive(el, true)
                            }
                            if(el.value !='')
                            {
                                // console.log(el);
                                if(!el.parentNode.classList.contains('form-field__control')){
                                    el.parentNode.classList.add('form-field__control');
                                }
                                el.parentNode.parentNode.classList.add('form-field--is-filled');
                            }
                        }
                    );
             });
        },
        delRow(empID){

                let row = this.$data.rows;
                let data = this.rows.filter((obj)=>obj.empID != empID);
                this.rows = data;


        },
        closeModal(){
            this.forUpdate = false;
        }
    },
    mounted(){
        this.MDBINPUT();
        this.pullRec();

        $('#myModal').on("hidden.bs.modal", this.closeModal);

        // TABLE
        this.dtHandle=$('#manage-employee-settings').DataTable({
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
            let reset = false;
            let remove_emp = false;

            $("#manage-employee-settings tbody").on('click', 'button.activate-emp', function() {
                let tr = $(this).closest('tr');
                let row = table.row( tr );
                let dataforedit = row.data();

                // self.delRow(dataforedit.empID);
                // console.log(dataforedit);
                axios.post('api/activate-emp',{
                    empID: dataforedit.empID
                })
                .then(res=>{
                    self.delRow(dataforedit.empID);
                }).catch(er=>alert('Server Error'))
                remove_emp = true;
            });

            // modal update
            $("#manage-employee-settings tbody").on('click', 'tr', function() {

                if((rows > 0 && !reset) && (rows > 0 && !remove_emp))
                {
                    var tr = $(this).closest('tr');
                    var row = table.row( tr );
                    let dataforedit = row.data();
                    self.forUpdate = true;
                    self.setUpdate(dataforedit);

                }else{
                    reset =  false;
                    remove_emp = false;
                }
            });
    }
}

let columnDefs = [{
            title: "Activate Employee",
            className:      'details-control',
            orderable:      false,
            data:           'deluser',
            defaultContent: `
                <button type="button" class="btn btn-primary activate-emp">Activate</button>
            `
        },{
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
        },{
            title: "Status",
            data: 'employee_status'
        },

];

const setActive = (el, active) => {
        const formField = el.parentNode.parentNode
        if (active) {
            formField.classList.add('form-field--is-active')
        } else {
            formField.classList.remove('form-field--is-active')
            el.value === '' ?
            formField.classList.remove('form-field--is-filled') :
            formField.classList.add('form-field--is-filled')
        }
    }
</script>