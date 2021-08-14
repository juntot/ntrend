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
            <h4>MANAGE WITNESS SUPPLEMENTARY</h4>
        </div>
        <div class="col-lg-12 canvas-chart margin-15">
            <table id="form-group-tbl" class="mdl-data-table" style="width:100%"></table>

            <!-- modal update details -->
            <div id="myModal-category-details" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Set Employee Witness For Supplementary</h4>
                        </div>
                        <div class="modal-body">

                            <div class="mdb-form-field" style="flex: 1">
                                <div class="form-field__control">
                                    <input type="text" v-model="search_emp" class="form-field__input" name="catname">
                                    <label class="form-field__label">Search Employee</label>
                                    <div class="form-field__bar"></div>
                                </div>
                                <div class="search-container">
                                        <div class="search-res" style="max-height: 100px; overflow: auto;">
                                            <ul style="list-style:none;  padding: 0" v-show="search_emp">
                                                <li v-for="emp in filterEmployee" :key="emp.empID" @click.prevent="appendWitness(emp)" >{{`${emp.empID} - ${emp.fname} ${emp.lname} ${emp.posname} ${emp.deptname}`}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                <!-- <span class="errors">{{ isDuplicate? 'Category Name already exist':errors.first('catname') }}</span> -->
                            </div>
                            <div class="clearfix"></div>
                            <span style="border: 1px solid orange; background: orange; width: 100%; text-align:center; color: white; display: inline-block; padding: 10px 10px; border-radius: 8px 8px 0 0;">WITNESSES</span>
                            <div style="padding: 15px 0px; border: 1px solid orange">
                                <ul style="list-style: none; margin: 0; padding: 0;">
                                    <li v-for="witness in witness_list" :key="witness.empID" style="padding: 4px 15px;"  class="hover-gray">
                                        <i class="fas fa-backspace" @click.prevent="delWitness(witness.empID)" style="color: rgb(212, 63, 58); cursor: pointer;"></i> &nbsp;
                                        {{`${witness.empID} - ${witness.fname} ${witness.lname} ${witness.posname} ${witness.deptname}`}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal">Done</button>
                        </div>
                        <div class="clearfix" style="padding-bottom:15px"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
// import ManagePolicy from '../components/ManagePolicy';

export default {

    components: {
        // ManagePolicy
    },
    data() {
        return {
            search_emp: '',
            headers:  [],
            rows: [],
            witness_list: [],

            selectedRow:{},

            disableCloseModal: true,
            dtHandle: null,
            loader: false,
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
    computed:{
        isDuplicate(){
            return true;
        },
        isFormValid(){
			return !Object.keys(this.fields).some(key => this.fields[key].invalid);
		},
        filterEmployee(){
            let self=this;
            let counter =1;

            if(this.search_emp){
                let rows = this.rows.filter(res=>{
                    return !this.witness_list.reduce((acc, data)=>{
                        acc.push(data.empID);
                        return acc;
                    },[this.selectedRow.empID]).includes(res.empID);
                });

                return rows.filter(function (item) {
                    return Object.values(item).map(function (value) {
                        return String(value).toLowerCase();
                    }).find(function (value) {
                        return value.includes(self.search_emp.toLowerCase());
                    }) && counter++ < 20;
                });
            }
        },
    },
    filters:{
        categoryName_filter: (val) =>{
            return val || 'Select Category';
        }
    },
    methods:{
        appendWitness(val){
            axios.post('api/add-witness',{
                approverID_: val.empID,
                empID_: this.selectedRow.empID
            })
            .then(res=>{
                this.search_emp = '';
                this.witness_list.unshift(val);
            });

        },
        delWitness(empID = ''){
            axios.post('api/del-witness',{
                approverID_: empID,
            })
            .then(res=>{
                let list = this.witness_list.filter(res=>res.empID != empID);
                this.witness_list = list;
            });
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
                                if(!el.parentNode.classList.contains('form-field__control')){
                                    el.parentNode.classList.add('form-field__control');
                                }
                                el.parentNode.parentNode.classList.add('form-field--is-filled');
                            }
                        }
                    );
             });
        },

       setUpdate(data){
            // bus.$emit('setupdate', data);
            this.selectedRow = data;
            axios.get('api/get-witness-list/'+data.empID)
            .then(async res=>{
                this.witness_list = await res.data;
                $('#myModal-category-details').modal('show');
                this.MDBINPUT();
            });

        },
        closeModal(){
            this.input_catname = '';
            this.catSelected = {name: ''};
            this.forUpdate = false;

        },

    },
    created(){

    },
    mounted() {
        this.MDBINPUT();

        // from settings
        axios.get('api/getemployees')
        .then(res=>this.rows = res.data)
        .catch(er=>alert('Server Error'));


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
            // {
            //     title: "Delete",
            //     className:      'details-control',
            //     orderable:      false,
            //     data:           'resetpass',
            //     defaultContent: `
            //         <button type="button" class="btn btn-primary del-btn">Delete</button>
            //     `
            // }
        ];

        // axios.get('api/getcompany').then((response)=>{
            // this.loader = false;
            // this.rows=response.data;
            this.dtHandle = $('#form-group-tbl').DataTable({
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
            // Add event listener for opening and closing details
            // let remove_emp = false;
            // Add event listener for opening and closing details
            // $("#form-group-tbl tbody").on('click', 'button.del-btn', function() {
            //     let tr = $(this).closest('tr');
            //     let row = table.row( tr );
            //     let dataforedit = row.data();
            //     // console.log(dataforedit);
            //     self.delRow(dataforedit);
            //     remove_emp = true;
            // });

            $('#form-group-tbl tbody').on('click', 'tr',  function(){
                // if(rows > 0 && !remove_emp)
                // {
                    let tr = $(this).closest('tr');
                    let row = table.row( tr );
                    let dataforedit = row.data();
                    self.forUpdate = true;
                    self.setUpdate(dataforedit);
                // }
            });


        // }).catch((err)=>{
        //     console.log(err);

        // });
        // MODAL
        $('#myModal-category-details').on("hidden.bs.modal", this.closeModal);

    }
}

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
