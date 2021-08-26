<template>
    <div>
        <div class="col-lg-12 bgc-white">
            <h2>FORMS SETTINGS</h2>
        </div>
        <div class="clearfix"></div>

        <!-- dates -->
        <br>
        <div class="bgc-white">
            <div class="col-md-3 col-sm-6" style="padding-top: 15px">
                <div class="form-group-limit">
                            <Datepicker :value="dateStart" @selected="selectDateStart" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                            <label slot="afterDateInput" class="form-field__label">Date Start</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                            </Datepicker>
                </div>
            </div>
            <div class="col-md-9 col-sm-6" style="padding-top: 15px">
                <div class="form-group-limit">
                            <Datepicker :value="dateEnd" @selected="selectDateEnd" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                            <label slot="afterDateInput" class="form-field__label">Date End</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                            </Datepicker>
                </div>
            </div>
            <div class="clearfix"></div>

        </div>
        <br>
        <!-- form types -->
        <div class="bgc-white">
            <br>
            <div class="dflex block-mobile" style="align-items: center">
                    <div class="dropdown padding-lr-15">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{selected_type.formtitle | formType_filters}}
                        &nbsp;&nbsp;<div class="caret"></div></button>
                        <ul class="dropdown-menu scroll-menu">
                            <li v-for="list in form_list" :key="list.formID"><a href="#" @click.prevent="selectFormType(list)">{{list.formtitle}}</a></li>
                        </ul>
                    </div>

                    <div class="dropdown padding-lr-15" style="margin-top: 15px; margin-bottom: 15px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{selected_status_name | status_filters}}
                        &nbsp;&nbsp;<div class="caret"></div></button>
                        <ul class="dropdown-menu scroll-menu">
                            <li v-for="stat in statusList" :key="stat.id">
                                <a href="#" @click.prevent="selected_status = stat.status, selected_status_name = stat.name" >{{stat.name}}</a>
                            </li>
                            <!-- <li><a href="#" @click.prevent="selected_status = 'Approved'" >Approved</a></li>
                            <li><a href="#" @click.prevent="selected_status = 'Rejected'">Rejected</a></li>
                            <li><a href="#" @click.prevent="selected_status = 'Pending'">Pending</a></li>
                            <li><a href="#" @click.prevent="selected_status = 'Disabled'">Disabled</a></li> -->
                        </ul>
                    </div>
                    <div class="padding-lr-15">
                        <button class="btn btn-primary dropdown-toggle" type="button" @click.prevent="pullRec" :disabled="disableBtn">Get Records</button>
                    </div>
            </div>
            <div class="clearfix"></div>
            <br>
        </div>
        <br>
        <!-- from table -->
        <div class="bgc-white col-lg-12">
            <br><br>
            <div class="col-md-6 nopadding">
                <button class="btn btn-primary" @click.prevent="enableRange" :disabled="this.selected_status != 99 || disableBtn">Enable</button>
                <button class="btn btn-danger" @click.prevent="disableRange" :disabled="this.selected_status == 99 || disableBtn">Disable</button>

            </div>
            <table id="manage-forms" class="mdl-data-table" style="width: 100%"></table>
        </div>
    </div>
</template>
<script>

export default {
    data(){
        return{
            
            dateStart: moment().format('YYYY-MM-DD'),
            dateEnd: moment().format('YYYY-MM-DD'),
            form_list: [],
            rows: [],
            selected_type: {
                formtitle:'',
            },
            selected_status: '',
            selected_status_name: '',
            // selected_form_obj:{},

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

            row.forEach((item, index)=>{
                // if(!isNaN(item.leavetype) && !isNaN(item.status)){
                    row[index]['formtype'] = this.selected_type.formtitle;
                // }
            });

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
            return this.selected_status < 0 || this.selected_status.length == 0 || !this.selected_type.formtitle.length>0;
        },
        statusList(){
            
            if(this.selected_type.td == 'formoverride'){
                this.selected_status_name = '';
                return [
                    {status: 0, name: 'Pending'},
                    {status: 2, name: 'Approved'},
                    {status: 3, name: 'Rejected'},
                    {status: 99, name: 'Disabled'},
                ];    
            }
            // else if(this.selected_type.td == "formworkrequest"){
            //     this.selected_status_name = '';
            //     this.selected_status_name = '';
            //     return [
            //         {status: 0, name: 'Pending'},
            //         {status: 1, name: 'Approved'},
            //         {status: 2, name: 'Rejected'},
            //         {status: 99, name: 'Disabled'},
            //     ];
            // }
            else if(this.selected_type.td == "formsupplementary"){
                this.selected_status_name = '';
                this.selected_status_name = '';
                return [
                    {status: 0, name: 'Pending'},
                    {status: 2, name: 'Approved'},
                    {status: 3, name: 'Rejected'},
                    {status: 99, name: 'Disabled'},
                ];
            }
            else{
            return [
                    {status: 0, name: 'Pending'},
                    {status: 1, name: 'Approved'},
                    {status: 2, name: 'Rejected'},
                    {status: 99, name: 'Disabled'},
                ];
            }
        }
    },
    methods:{
        disableRange(){
            if(this.selected_status != 99){
                let td = this.selected_type.td;
                let type= this.selected_status;
                axios.post('api/disable-form-records',{
                    datefrom: this.dateStart,
                    dateto: this.dateEnd,
                    td: td, status: type,
                })
                .then(res=>{
                    this.rows = [];
                }).catch(()=>alert('Server Error please reload'));
            }

        },
        enableRange(){
            if(this.selected_status == 99){
                let td = this.selected_type.td;
                let type= this.selected_status;
                axios.post('api/enable-form-records',{
                    datefrom: this.dateStart,
                    dateto: this.dateEnd,
                    td: td,
                    // status: type,
                })
                .then(res=>{
                    this.rows = [];
                }).catch(()=>alert('Server Error please reload'));
            }
        },
        selectFormType(obj){
            this.selected_type=obj
        },
        selectDateStart(val){
            this.dateStart = moment(val).format('YYYY-MM-DD');

        },
        selectDateEnd(val){
            this.dateEnd = moment(val).format('YYYY-MM-DD');
        },
        pullRec(){
            let type = '';
            let params = {};
            // let status = ['Pending', 'Approved', 'Rejected'];
            let td = this.selected_type.td;
            if(this.selected_status != 99){
                type= this.selected_status;
                params = {
                    datefrom: this.dateStart, dateto: this.dateEnd,
                    td: td, status: type,
                }
            }else{
                params = {
                    datefrom: this.dateStart, dateto: this.dateEnd,
                    td: td, recstat: 1,
                }
            }



            axios.post('api/get-form-records', params)
            .then(res=>{
                this.rows = res.data;
            })
            .catch(err=>alert('Server Error'));
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
    },
    beforeDestory(){

    },
    mounted(){
        this.MDBINPUT();
        axios.get('api/get-form-settings')
        .then(res=>{
            this.form_list = res.data;
        })
        .catch(er=>alert('Server Error'));

        // TABLE
        this.dtHandle=$('#manage-forms').DataTable({
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
            $("#manage-forms tbody").on('click', 'button.reset-pass', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                self.resetPassword(row.data());
                reset = true;
            });

            $("#manage-forms tbody").on('click', 'button.remove-emp', function() {
                let tr = $(this).closest('tr');
                let row = table.row( tr );
                let dataforedit = row.data();

                self.delRow(dataforedit.empID);
                remove_emp = true;
            });

            // modal update
            $("#manage-forms tbody").on('click', 'tr', function() {

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

let status = ['Pending', 'Approved', 'Rejected'];


let columnDefs = [{
            title: "Form Type", data: 'formtype'
        },{
            title: "Status", data: 'status',
             render: function(data, type, full, meta) {
                //  return data == 1? 'Approved': data == 2? 'Rejected': 'Pending'
                return data;
             }
        }, {
            title: "Date Filed", data: 'datefiled'
        },{
            title: "Requested By", data: 'fullname'
        },{
            title: "Department", data: 'deptname'
        },{
            title: "Company", data: 'compname'
        },{
            title: "Branch", data: 'branchname'
        },{
            title: "Position", data: 'posname'
        }
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