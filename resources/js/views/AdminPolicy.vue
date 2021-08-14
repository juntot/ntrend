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
            <h4>MANAGE POLICY</h4>
        </div>
        <div class="col-lg-12 canvas-chart margin-15">
            <div class="padding-tb-15 bgc-white">
                <div class="dflex">
                    <div class="dropdown policy-category">
                        <button class="btn btn-primary" data-toggle="dropdown">{{catSelected.catname | categoryName_filter}}&nbsp;&nbsp;
                        <div class="caret"></div></button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">Category List</li>
                            <li v-for="cat in category_list" :key="cat.id"><a href="#" @click.prevent="selectedCategory(cat)">{{cat.catname}}</a></li>
                        </ul>
                    </div>
                    <div class="policy-category">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal-category">ADD NEW CATEGORY</button>
                        <button class="btn btn-primary" :disabled="!catSelected.catname" @click.prevent="delCategory">DELETE CATEGORY</button>
                    </div>

                </div>

            </div>
            <div class="clearfix"></div>
            <br><br>
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding" >
                <button class="btn  btn-primary" :disabled="loader || !catSelected.catname" data-toggle="modal" data-target="#modalPolicy">UPLOAD NEW POLICY</button>
            </div>
            <table id="managecomp" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="modalPolicy" class="modal fade" role="dialog" data-backdrop="static">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" >&times;</button>
                            <!-- <h4 class="modal-title">Add Category</h4> -->
                        </div>
                        <div class="modal-body">
                            <ManagePolicy :policy="catSelected"></ManagePolicy>
                        </div>
                        <div class="modal-footer" style="padding-bottom: 15px;">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" :disabled="disableCloseModal">Done</button>
                        </div>
                    </div>

                </div>
            </div>


            <!-- modal category -->
            <!-- Modal -->
            <div id="myModal-category" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add Category</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="mdb-form-field">
                                    <div class="form-field__control">
                                        <input type="text" v-validate="{ required: true, regex:/^[a-zA-Z][a-zA-Z0-9\ \-\&/]*$/ }" v-model="input_catname" class="form-field__input" name="catname">
                                        <label class="form-field__label">Category Name</label>
                                        <div class="form-field__bar"></div>
                                    </div>
                                    <span class="errors">{{ isDuplicate? 'Category Name already exist':errors.first('catname') }}</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" :disabled="isDuplicate || !isFormValid" @click.prevent="addNewCategory">Save</button>
                        </div>
                        <div class="clearfix" style="padding-bottom:15px"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import ManagePolicy from '../components/ManagePolicy';

export default {

    components: {
        ManagePolicy
    },
    data() {
        return {
            headers:  [],
            rows: [],
            category_list: [{id: 1, catname:'HTML'},{id: 2, catname:'CSS'}, {id: 3, catname:'JS'}],
            category_name: '',
            input_catname: '',
            catSelected: {
                catname: '',
            },

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
            let catname = this.input_catname.toLowerCase();
            let rows = this.category_list
            if(catname != ''){
                let duplicate = rows.find(data=>(data.catname.toLowerCase())==catname);
                return duplicate ? true: false;
            }else{
                return false;
            }
        },
        isFormValid(){
			return !Object.keys(this.fields).some(key => this.fields[key].invalid);
		},
    },
    filters:{
        categoryName_filter: (val) =>{
            return val || 'Select Category';
        }
    },
    methods:{
       selectedCategory(obj = {}){
           this.catSelected = obj;
           this.loader = true;
           axios.get('api/get-policy-category-details/'+obj.id)
           .then(res=>{
               this.loader = false;
               if(res.data.length>0){
                    this.rows = res.data;
               }else{
                    this.dtHandle.clear();
                    this.dtHandle.draw();
               }

           })
           .catch(err=>alert('Server Error..'));
       },
       addNewCategory(){
           let params = {
               catname: this.input_catname
           };
           axios.post('api/add-policy-category',params)
           .then(res=>{
               if(res.data){
                    this.category_list.push({id: res.data, catname: this.input_catname});
                    $('#myModal-category').modal('hide');
               }
           })

       },
       delCategory(){

           let result = confirm("Are you sure you want to delete?");
            if (result) {
                let id = this.catSelected.id
                axios.get('api/del-policy-category/'+id)
                .then(res=>{
                    let list = this.category_list.filter(data=>data.id != this.catSelected.id);
                    this.category_list = list;
                    this.catSelected={catname: ''};
                })
                .catch(err=>{
                    alert('Server Error');
                })

            }
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
       addRow(val){
           this.rows.unshift(val);
       },
       updateRow(val){
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.compID == val.compID)
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
            this.input_catname = '';
            this.catSelected = {name: ''};
            this.forUpdate = false;

        },
        delRow(obj){
            let result = confirm("Are you sure you want to delete?");
            if (result) {
                axios.post('api/del-policy-per-detail',{
                    detail_id: obj.detail_id,
                    folder: obj.policy_id,
                    filename: obj.policy_name,
                }).then((res)=>{
                        let row = this.$data.rows;
                        let data = row.filter((data)=>data.detail_id != obj.detail_id);
                        this.rows = data;
                })
                .catch(er=>console.log(er))

            }
        }
    },
    created(){

    },
    mounted() {
        this.MDBINPUT();
        axios.get('api/get-policy-category-all')
        .then(res=>this.category_list = res.data)
        .catch(er=>alert('Server Error'));

        let columnDefs = [{
                title: "POLICY NAME", data: 'policy_name',
            },{
                title: "FILE", data: 'pdf_loc'
            },
            {
                title: "Delete",
                className:      'details-control',
                orderable:      false,
                data:           'resetpass',
                defaultContent: `
                    <button type="button" class="btn btn-primary del-btn">Delete</button>
                `
            }
        ];

        // axios.get('api/getcompany').then((response)=>{
            // this.loader = false;
            // this.rows=response.data;
            this.dtHandle = $('#managecomp').DataTable({
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
            let remove_emp = false;
            // Add event listener for opening and closing details
            $("#managecomp tbody").on('click', 'button.del-btn', function() {
                let tr = $(this).closest('tr');
                let row = table.row( tr );
                let dataforedit = row.data();
                // console.log(dataforedit);
                self.delRow(dataforedit);
                remove_emp = true;
            });

            $('#managecomp tbody').on('click', 'tr',  function(){
                if(rows > 0 && !remove_emp)
                {
                    let tr = $(this).closest('tr');
                    let row = table.row( tr );
                    let dataforedit = row.data();
                    self.forUpdate = true;
                    self.setUpdate(dataforedit);
                }
            });


        // }).catch((err)=>{
        //     console.log(err);

        // });
        // MODAL
        $('#myModal-category').on("hidden.bs.modal", this.closeModal);
        // $('#modalPolicy').on("hidden.bs.modal", this.closeModal);

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
