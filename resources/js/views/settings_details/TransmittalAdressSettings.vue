<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title margin-15 bgc-white">
            <h4>MANAGE TRANSMITTAL ADDRESS GROUP</h4>
        </div>
        <div class="col-lg-12 margin-15 bgc-white">
            <div class="padding-tb-15 bgc-white">
                <div class="dflex">
                    <div class="policy-category">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal-address-trans" @click.prevent="getCategory">Manage Address</button>
                    </div>
                    <!-- <div class="policy-category">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal-category-transmittal" @click.prevent="getCategory">Manage Address Group</button>
                    </div> -->
                </div>

            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-lg-12 canvas-chart margin-15 bgc-white">
            <br><br>
            <table id="transmittal-addr-group-tbl" class="mdl-data-table" style="width:100%"></table>

             <!-- Modal -->
            <div id="myModal-address-trans" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Manage Address</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="dflex-normal">
                                    <div class="mdb-form-field" style="flex: 1">
                                        <div class="form-field__control">
                                            <input type="text" v-validate="{ required: true }" v-model="inputAddr" class="form-field__input" name="address">
                                            <label class="form-field__label">Address Name</label>
                                            <div class="form-field__bar"></div>
                                        </div>
                                        <span class="errors">{{ isDuplicateAddr? 'Address already exist':errors.first('address') }}</span>
                                    </div>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <div>
                                        <button style="margin-top: 20px;" type="button" class="btn btn-primary" :disabled="isDuplicateAddr || errors.first('address')" @click.prevent="updateAddress" v-show="forUpdate">Update</button>
                                        <button style="margin-top: 20px;" type="button" class="btn btn-primary" @click.prevent="forUpdate = false" v-show="forUpdate">Cancel</button>
                                        <button style="margin-top: 20px;" type="button" class="btn btn-primary" :disabled="isDuplicateAddr || errors.first('address')" @click.prevent="addNewAddress" v-show="!forUpdate">Save</button>
                                    </div>
                                </div>
                                <ul class="form-grouplist">
                                    <li v-for="list in address_list" :key="list.id">
                                        <i class="fas fa-backspace" style="color: #d43f3a; cursor: pointer" @click.prevent="delAddress(list.id)"></i> &nbsp;
                                        <a href="#" @click.prevent="selectAddrName(list)">{{list.addressName || ''}}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">

                        </div>
                        <div class="clearfix" style="padding-bottom:15px"></div>
                    </div>
                </div>
            </div>



            <!-- Modal -->
            <div id="myModal-category-transmittal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Manage Location Group</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="dflex-normal">
                                    <div class="mdb-form-field" style="flex: 1">
                                        <div class="form-field__control">
                                            <input type="text" v-validate="{ required: true, regex:/^[a-zA-Z][a-zA-Z0-9\ \-\&/]*$/ }" v-model="input_catname" class="form-field__input" name="catname">
                                            <label class="form-field__label">Location Group Name</label>
                                            <div class="form-field__bar"></div>
                                        </div>
                                        <span class="errors">{{ isDuplicate? 'Category Name already exist':errors.first('catname') }}</span>
                                    </div>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <div>
                                        <button style="margin-top: 20px;" type="button" class="btn btn-primary" :disabled="isDuplicate || errors.first('catname')" @click.prevent="updateCategory" v-show="forUpdate">Update</button>
                                        <button style="margin-top: 20px;" type="button" class="btn btn-primary" @click.prevent="forUpdate = false" v-show="forUpdate">Cancel</button>
                                        <button style="margin-top: 20px;" type="button" class="btn btn-primary" :disabled="isDuplicate || errors.first('catname')" @click.prevent="addNewCategory" v-show="!forUpdate">Save</button>
                                    </div>
                                </div>
                                <ul class="form-grouplist">
                                    <li v-for="list in category_list" :key="list.id">
                                        <i class="fas fa-backspace" style="color: #d43f3a; cursor: pointer" @click.prevent="delCategory(list.id)"></i> &nbsp;
                                        <a href="#" @click.prevent="selectGroupName(list)">{{list.groupname || ''}}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">

                        </div>
                        <div class="clearfix" style="padding-bottom:15px"></div>
                    </div>
                </div>
            </div>



            <!-- modal update details -->
            <div id="myModal-transmittal-category-details" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Set Form Group</h4>
                        </div>
                        <div class="modal-body">

                            <div class="mdb-form-field" style="flex: 1">
                                <div class="form-field__control">
                                    <input type="text" v-model="selectedRow.addressName" disabled class="form-field__input" name="catname">
                                    <label class="form-field__label">Address</label>
                                    <div class="form-field__bar"></div>
                                </div>
                                <!-- <span class="errors">{{ isDuplicate? 'Category Name already exist':errors.first('catname') }}</span> -->
                            </div>
                            <div class="mdb-form-field form-group-limitx form-field--is-filled">
                                <div class="form-field__control">
                                    <select id="posname" name="position" v-model="selectedRow.group_id" class="form-field__input" >
                                        <option :value="null" >SELECT GROUP NAME</option>
                                        <option  v-for="item in category_list" :value="item.branchID" :key="item.branchID">{{ item.branchname }}</option>
                                    </select>
                                    <label class="form-field__label"
                                    style="font-size: 10px;
                                            transform: translateY(-14px);
                                            top: -5px;
                                            color: #b11adc;"
                                    >Address Group Name</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('position') }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" @click.prevent="setFormGroup">Update</button>
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
            headers:  [],
            rows: [],
            address_list: [],
            addSelected: '',
            inputAddr: '',

            category_list: [],
            category_name: '',
            input_catname: '',
            catSelected: {
                catname: '',
            },
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
            let catname = this.input_catname.toLowerCase();
            let rows = this.category_list
            if(catname != ''){
                let duplicate = rows.find(data=>(data.groupname.toLowerCase())==catname);
                return duplicate ? true: false;
            }else{
                return false;
            }
        },
        isDuplicateAddr(){
            if(!this.inputAddr)
            return false;

            let catname = this.inputAddr.toLowerCase();
            let rows = this.address_list;
            
            if(catname != ''){
                let duplicate = rows.find(data=>(data.addressName.toLowerCase())==catname);
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
        // ADDRESS
        getAddress(){
            axios.get('api/get-transmittal-address')
            .then(res=>this.address_list = res.data)
            .catch(er=>alert('Server Error'));
        },
        selectAddrName(obj={}){
            // console.log(obj)
            this.catSelected = obj;
            this.forUpdate = true;
            this.inputAddr = obj.addressName;
            this.MDBINPUT();
        },
        addNewAddress(){
            if(!this.inputAddr)
            return;
           let params = {
               addressName: this.inputAddr,
           };
           axios.post('api/add-transmittal-address',params)
           .then(res=>{
               if(res.data){
                    this.address_list.push({id: res.data, addressName: this.inputAddr});
                    this.rows.push({id: res.data, addressName: this.inputAddr, group_id: null, groupname:''});
                    $('#myModal-address-trans').modal('hide');
               }
           })

       },
       updateAddress(){
           if(!this.inputAddr)
            return;
           let params = {
               id: this.catSelected.id,
               addressName: this.inputAddr
           };
           axios.post('api/update-transmittal-address',params)
           .then(res=>{
               if(res.data){
                    // update modal
                    let list = this.address_list.filter(add=> add.id != this.catSelected.id);
                    list.push({id: this.catSelected.id, addressName: this.inputAddr});
                    this.address_list = list;
                    
                    // udpate table
                    const rowId = this.rows.findIndex(data => data.id == this.catSelected.id);
                    const updatedAdd = this.rows[rowId];
                    updatedAdd.addressName = this.inputAddr;

                    let row = this.rows.filter(data=>data.id !== this.catSelected.id);
                    row.push(updatedAdd);
                    this.rows = row;
                    
                    
                    $('#myModal-address-trans').modal('hide');
               }
           })
       },
       delAddress( id = ''){

           let result = confirm("Are you sure you want to delete?");
            if (result) {
                axios.post('api/delete-transmittal-address', {id: id})
                .then(res=>{
                    let list = this.address_list.filter(data=>data.id != id);
                    this.address_list = list;


                    // table
                    const rowId = this.rows.findIndex(data => data.id == id);
                    const row = this.rows.filter(data=>data.id !== this.rows[rowId].id);
                    
                    this.rows = row;

                })
                .catch(err=>{
                    console.log(err)
                    alert('Server Error');
                })

            }
       },


        // CATEGORY
        getCategory(){
            axios.get('api/getbranch')
            .then(res=>this.category_list = res.data)
            .catch(er=>alert('Server Error'));
        },
        selectGroupName(obj={}){
            this.catSelected = obj;
            this.forUpdate = true;
            this.input_catname = obj.groupname;
            this.MDBINPUT();
        },
    //    selectedCategory(obj = {}){
    //        this.catSelected = obj;
    //        this.loader = true;
    //        axios.get('api/get-policy-category-details/'+obj.id)
    //        .then(res=>{
    //            this.loader = false;
    //            if(res.data.length>0){
    //                 this.rows = res.data;
    //            }else{
    //                 this.dtHandle.clear();
    //                 this.dtHandle.draw();
    //            }

    //        })
    //        .catch(err=>alert('Server Error..'));
    //    },
       addNewCategory(){
           if(!this.input_catname)
            return;
           let params = {
               groupname: this.input_catname
           };
           
           axios.post('api/add-transmittal-group',params)
           .then(res=>{
               if(res.data){
                //    console.log(res.data);
                    this.category_list.push({id: res.data, groupname: this.input_catname});
                    $('#myModal-category-transmittal').modal('hide');
               }
           })

       },
       updateCategory(){
           if(!this.input_catname)
            return;
           let params = {
               id: this.catSelected.id,
               groupname: this.input_catname
           };
           axios.post('api/update-transmittal-group',params)
           .then(res=>{
               if(res.data){
                    this.category_list.push({id: res.data, groupname: this.input_catname});
                    $('#myModal-category-transmittal').modal('hide');
               }
           })
       },
       delCategory( id = ''){

           let result = confirm("Are you sure you want to delete?");
            if (result) {
                axios.post('api/delete-transmittal-group', {id: id})
                .then(res=>{
                    let list = this.category_list.filter(data=>data.id != id);
                    this.category_list = list;
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

        // datatables
        async setFormGroup(){
            let params = {
                addrID_ : this.selectedRow.id,
                groupID_: this.selectedRow.group_id
            };
            let group = await this.category_list.filter(data=>this.selectedRow.group_id == data.branchID);
            
            axios.post('api/set-transmittal-group-details', params)
            .then(async res=>{
                let val = params;
                val['branchname'] = await group.length > 0 ?group[0].branchname : '';
                this.updateRow(val);
                $('#myModal-transmittal-category-details').modal('hide');
            });
        },

    //    addRow(val){
    //        this.rows.unshift(val);
    //    },
       updateRow(val){
        //    console.log(val);
            let row = this.rows;
            row.forEach(async (item, index)=>{
                // console.log(item, val);
                // return;
                if(item.id==val.addrID_)
                {
                    let record = await item;
                    record.group_id = await val.groupID_;
                    record.branchname = await val.branchname;
                    this.rows.splice(index, 1);
                    this.rows.unshift(record);
                }
            });
       },
       setUpdate(data){
            // bus.$emit('setupdate', data);
            this.selectedRow = data;
            $('#myModal-transmittal-category-details').modal('show');
            this.MDBINPUT();
        },
        closeModal(){
            this.input_catname = '';
            this.catSelected = {name: ''};
            this.forUpdate = false;

        },
        closeModal2(){
            this.input_catname = '';
            this.catSelected = {name: ''};
            this.forUpdate = false;
        },
        closeModalAddress(){
            this.inputAddr = '';
            this.catSelected = {name: ''};
            this.forUpdate = false;
        },
        // delRow(obj){
        //     let result = confirm("Are you sure you want to delete?");
        //     if (result) {
        //         axios.post('api/del-policy-per-detail',{
        //             detail_id: obj.detail_id,
        //             folder: obj.policy_id,
        //             filename: obj.policy_name,
        //         }).then((res)=>{
        //                 let row = this.$data.rows;
        //                 let data = row.filter((data)=>data.detail_id != obj.detail_id);
        //                 this.rows = data;
        //         })
        //         .catch(er=>console.log(er))

        //     }
        // }
    },
    created(){

    },
    mounted() {
        this.MDBINPUT();

        // address
        this.getAddress();
        
        // form group
        this.getCategory();

        // from settings
        axios.get('api/get-transmittal-group-details')
        .then(res=>this.rows = res.data)
        .catch(er=>alert('Server Error'));
        let columnDefs = [{
                title: "Address Name", data: 'addressName',
                className: 'td-ellipsis',
                // render: function ( data, type, row, meta ) {
                //         // return data.slice(0, 100);
                //         return data;

                // }
            },{
                title: "Branch Group", data: 'branchname', 
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
            this.dtHandle = $('#transmittal-addr-group-tbl').DataTable({
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

            $('#transmittal-addr-group-tbl tbody').on('click', 'tr',  function(){
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
        $('#myModal-category-transmittal').on("hidden.bs.modal", this.closeModal);
        $('#myModal-transmittal-category-details').on("hidden.bs.modal", this.closeModal2);
        $('#myModal-address-trans').on("hidden.bs.modal", this.closeModalAddress);
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
