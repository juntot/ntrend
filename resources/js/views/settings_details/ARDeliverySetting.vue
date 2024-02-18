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
            <h4>Manage Delivery Branch</h4>
        </div>
        <div class="col-lg-12 margin-15 bgc-white">
            <div class="padding-tb-15 bgc-white">
                <div class="dflex">
                    <div class="policy-category">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#ardelivery-sett-modal" >Manage Branch</button>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-lg-12 canvas-chart margin-15 bgc-white">
            <br><br>
            <table id="ARdelivery-tbl" class="mdl-data-table" style="width:100%"></table>

             <!-- Modal -->
            <div id="ardelivery-sett-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Manage Delivery Branch</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-6">
                              <div class="mdb-form-field form-group-limitx">
                                  <div class="form-field__control">
                                      <input type="text" class="form-field__input" v-model="compname" name="comp_name">
                                      <label class="form-field__label">Company Name</label>
                                      <div class="form-field__bar"></div>
                                  </div>
                                  <span class="errors">{{ errors.first('comp_name') }}</span>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mdb-form-field form-group-limitx">
                                  <div class="form-field__control">
                                      <input type="text" class="form-field__input" v-model="branchname" v-validate="'required'" name="branch_name">
                                      <label class="form-field__label">Branch Name</label>
                                      <div class="form-field__bar"></div>
                                  </div>
                                  <span class="errors">{{ errors.first('branch_name') }}</span>
                              </div>
                          </div>
                          <div class="col-md-3">
                              <div class="mdb-form-field form-group-limitx">
                                  <div class="form-field__control">
                                      <input type="text" class="form-field__input" v-model="branchcode" v-validate="'required'" name="branch_code">
                                      <label class="form-field__label">Branch Code</label>
                                      <div class="form-field__bar"></div>
                                  </div>
                                  <span class="errors">{{ errors.first('branch_code') }}</span>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="mdb-form-field form-group-limitx">
                                  <div class="form-field__control">
                                      <input type="text" class="form-field__input" v-model="branchnum" v-validate="'required'" name="branch_num">
                                      <label class="form-field__label">Branch Num#</label>
                                      <div class="form-field__bar"></div>
                                  </div>
                                  <span class="errors">{{ errors.first('branch_num') }}</span>
                              </div>
                          </div>
                          <div class="col-md-3">
                              <div class="mdb-form-field form-group-limitx">
                                  <div class="form-field__control">
                                      <input type="text" class="form-field__input" v-model="smsport" v-validate="'required|numeric'" name="smsport">
                                      <label class="form-field__label">SMS Port</label>
                                      <div class="form-field__bar"></div>
                                  </div>
                                  <span class="errors">{{ errors.first('smsport') }}</span>
                              </div>
                          </div>
                        <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                          <input type="submit" class="btn btn-primary" value="Save" @click.prevent="addUpdateBranch" :disabled="!isFormValid ">
                          <input type="submit" class="btn btn-primary" value="Remove" @click.prevent="delBranch" v-if="isUpdate">
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
            dtHandle: null,
            loader: false,
            isUpdate: false,
            smsport: 1,
            compname: '',
            branchname: '',
            branchcode: '',
            branchnum: '',
            selectedRow: {},
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
            // let catname = this.input_catname.toLowerCase();
            // let rows = this.rows
            // if(catname != ''){
            //     let duplicate = rows.find(data=>(data.groupname.toLowerCase())==catname);
            //     return duplicate ? true: false;
            // }else{
            //     return false;
            // }
        },
        isFormValid(){
			    return !Object.keys(this.fields).some(key => this.fields[key].invalid);
		    },
    },
    filters:{
        
    },
    methods:{
       addUpdateBranch(){
           
          //  if(!this.input_catname)
          //   return;
          let params = {
               compname: this.compname,
               branchcode: this.branchcode,
               branchname: this.branchname,
               branchnum: this.branchnum,
               smsport: this.smsport
           };
          
          if(this.isUpdate)
          params['deliveryId'] = this.selectedRow['deliveryId'];

           axios.post('api/addupdate-delivery-branch', params)
           .then(res=>{
               if(res.data){
                //  console.log(res.data);
                    if(!this.isUpdate)
                    this.rows.push(res.data);

                    if(this.isUpdate)
                    this.updateRow(res.data);

                    $('#ardelivery-sett-modal').modal('hide');
               }
           })

       },
       
       delBranch(){
           const id =  this.selectedRow['deliveryId'];
           let result = confirm("Are you sure you want to delete?");
            if (result) {
                axios.post('api/del-delivery-branch', {id: id})
                .then(res=>{
                    let list = this.rows.filter(data=>data.deliveryId != id);
                    this.rows = list;
                    $('#ardelivery-sett-modal').modal('hide');
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

       
       updateRow(val){
        //    console.log(val);
            let row = this.rows;
            row.forEach(async (item, index)=>{
                // console.log(item, val);
                // return;
                if(item.deliveryId == val.deliveryId)
                {
                    let record = await item;
                    record.compname = await val.compname;
                    record.branchcode  = await val.branchcode;
                    record.branchname = await val.branchname;
                    record.branchnum = await val.branchnum;
                    record.smsport = await val.smsport;
                    this.rows.splice(index, 1);
                    this.rows.unshift(record);
                }
            });
       },
       setUpdate(data){
            // bus.$emit('setupdate', data);
            this.selectedRow = data;
            this.compname = data.compname;
            this.branchcode = data.branchcode;
            this.branchname = data.branchname;
            this.branchnum = data.branchnum;
            this.smsport =  data.smsport;

            $('#ardelivery-sett-modal').modal('show');
            this.MDBINPUT();
        },
        closeModal(){
            this.selectedRow = {};
            this.compname = '';
            this.branchcode = '';
            this.branchname = '';
            this.branchnum = '';
            this.smsport = 1;
            this.isUpdate = false;

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

      

        // from settings
        axios.get(`api/get-delivery-branch`)
        .then(res=>this.rows = res.data)
        .catch(er=>alert('Server Error'));

        let columnDefs = [
            {
                title: "Company Name", data: 'compname',
                className: 'td-ellipsis',
            },
            {
                title: "Branch Code", data: 'branchcode',
                className: 'td-ellipsis',
            },
            {
                title: "Branch Name", data: 'branchname', 
                className: 'td-ellipsis',
            },
            {
                title: "Branch Num#", data: 'branchnum',
            },
            {
                title: "SMS Port", data: 'smsport', 
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

        
            this.dtHandle = $('#ARdelivery-tbl').DataTable({
                "sPaginationType": "simple_numbers",
                data: [],
                columns: columnDefs,
                "sPaginationType": "simple_numbers",
                "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
                "scrollX": true,
                "order": [[ 1, "asc" ]],
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            

            $('#ARdelivery-tbl tbody').on('click', 'tr',  function(){
                // if(rows > 0 && !remove_emp)
                // {
                    
                    let tr = $(this).closest('tr');
                    let row = table.row( tr );
                    let dataforedit = row.data();
                    self.isUpdate = true;
                    self.setUpdate(dataforedit);
                // }
            });


        

        // MODAL
        $('#ardelivery-sett-modal').on("hidden.bs.modal", this.closeModal);
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
