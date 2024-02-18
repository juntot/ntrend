<template>
    <div>
        <div class="col-lg-12 bgc-white">
            <h2>API SETTINGS</h2>
        </div>
        <div class="clearfix"></div>
        <!-- dates -->
        <div class="col-md-12 nopadding">
            <br>
            <div class="col-md-12 bgc-white">
                <br>
                <p class="orange-text nomargin">API URL</p>
                <div class="col-md-12 nopadding">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input type="text" 
                            v-validate="'required'" 
                            @change.prevent="updateAPIpoint"
                            v-model="endpoint" 
                            class="form-field__input" name="api url" 
                            placeholder="https://example.com">
                            <label class="form-field__label"></label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('api url') }}</span>
                    </div>
                </div>
                <p class="orange-text">Company</p>
                <div class="col-md-6 nopadding with-margin-bottom">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal-SAP-Setting" 
                    @click.prevent="type = 'company'">Add New</button>
                </div>
                <table id="override-company-tbl" class="mdl-data-table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>User</th>
                            <th>Password</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody class="tbl_body_sap_setting">
                        <tr v-for="(val, index) in compRows" :key="index" @click="selected = val">
                            <td>{{val.name}}</td>
                            <td>{{val.user}}</td>
                            <td  :id="val.id" :data-type="'company'">{{'12345' | filterPass}}</td>
                            <td>
                                <button type="button" class="btn btn-primary retest-connection">Test Connection</button>
                                <button type="button" class="btn btn-danger remove-override">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
        

        <div class="clearfix"></div>
        <br>
         <!-- Modal -->
            <div id="myModal-SAP-Setting" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="mdb-form-field">
                                    <div class="form-field__control">
                                        <input type="text" v-validate="'required'" v-model="overrideFields.name" class="form-field__input" name="branch name">
                                        <label class="form-field__label">{{type | capitalize}} Name</label>
                                        <div class="form-field__bar"></div>
                                    </div>
                                    <span class="errors">{{ errors.first('branch name') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12" v-show="type == 'company'">
                                <div class="mdb-form-field">
                                    <div class="form-field__control">
                                        <input type="text" v-validate="'required'" v-model="overrideFields.user" class="form-field__input" name="user">
                                        <label class="form-field__label">User Name</label>
                                        <div class="form-field__bar"></div>
                                    </div>
                                    <span class="errors">{{ errors.first('user') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12" v-show="type == 'company'">
                                <div class="mdb-form-field">
                                    <div class="form-field__control">
                                        <input type="text" v-validate="'required'" v-model="overrideFields.pwd" class="form-field__input" name="password">
                                        <label class="form-field__label">Password</label>
                                        <div class="form-field__bar"></div>
                                    </div>
                                    <span class="errors">{{ errors.first('password') }}</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 modal-footer">
                                <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="add" v-if="!isUpdate" :disabled="!isFormValid">
                                <input type="submit" class="btn btn-primary" value="Update" @click.prevent="update" v-if="isUpdate" :disabled="!isFormValid" >
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div> -->
                    </div>

                </div>
            </div>

        <!-- Modal2 -->
            <div id="myModal-SAP-Setting2" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body text-center">
                            <p class="text-center" v-if="loader" style="color: orange; padding-bottom: 25px;">
                                <i class="fas fa-spinner fa-spin" style="font-size: 4em;"></i> 
                            </p>
                            <p class="text-center" v-if="!loader" style="color: orange; padding-bottom: 25px;">
                                <i class="fas fa-check" style="font-size: 4em;"></i>
                            </p>
                            <h5>
                                {{errMsg}}
                            </h5>
                            
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer" style="padding-bottom: 15px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
    </div>
</template>
<script>
const exclude = [
    'selected'
];
export default {
    data(){
        return{
            loader: false,
            endpoint: '',
            errMsg: `Please wait while checking your credentials`,
            selected: {
                name: '',
                user: 'test',
                pwd: 'test',
            },
            compRows: [],
            divRows: [],

            branchRows: [],
            overrideFields: {
                name: '',
                user: '',
                pwd: '',
            },
            type: 'company',
            isUpdate: false,
        }
    },
    watch:{
        rows(val, old){
        },
    },
    filters:{
        capitalize(value){
            if(value)
            return value.charAt(0).toUpperCase() + value.slice(1)
        },
        filterPass(val){
            return (val).split("").reduce((acc, val) => acc+='*', '');
        }
    },
    computed:{
        isFormValid(){
			return !Object.keys(this.fields).some(key => this.fields[key].invalid);
		},
        isDuplicate(){
            return false;
            // let branchname = this.branchname.toLowerCase();
            // let rows = this.$parent.$data.rows;
            // if(branchname != ''){
            //     let duplicate = rows.find(data=>(data.branchname.toLowerCase())==branchname);
            //     return duplicate ? true: false;
            // }else{
            //     return false;
            // }
        }  
    },
    methods:{
        updateAPIpoint(){
            axios.post('api/update-seop', {
                endpoint: this.endpoint
            })
            .then(()=>{

            });
        },
        add(){
            if(!this.overrideFields.name) return true;

            const data = {...this.overrideFields, type: this.type};
            if(this.type == 'division') {
                delete data.user;
                delete data.pwd;
            }
            
            axios.post('api/add-override-company', data)
            .then( async ({data})=>{
                const newRec = await {...data, id: data.id};
                if(this.type == 'company') {
                    await this.compRows.push(newRec);
                }
                if(this.type == 'division') {
                    await this.divRows.push(newRec);
                }
                $('.modal').modal('hide');
            });
        },
        update(){
            if(!this.overrideFields.name) return true;
            const data = {...this.overrideFields, type: this.type};
            
            // const data = {...this.overrideFields, type: this.type};
            if(this.type == 'division') {
                delete data.user;
                delete data.pwd;
            }
            // mange update
            const inputData = this.overrideFields;
            const updateRow = (row) =>{
                for (const iterator of this[row]) {
                    if(iterator.id == inputData.id) {
                        iterator.name = inputData.name;
                        iterator.user = inputData.user;
                        iterator.pwd = inputData.pwd;
                    }
                }
            }


            // request update
            axios.post('api/update-override-company', data)
            .then( async ({data})=>{
                
                if(this.type == 'company') {
                    updateRow('compRows');
                }

                if(this.type == 'division') {
                    updateRow('divRows');
                }
                $('.modal').modal('hide');
            });
            



            
        },
        delete(obj){
            const result = confirm("Are you sure you want to delete?");
            if (result) {
                const data = {type: obj.type, id: obj.id};
                axios.post('api/del-override-company', data)
                .then(res=>{
                    if(obj.type == 'company'){
                        const rows = this.compRows.filter(data=>data.id != obj.id);
                        this.compRows = rows;
                    }
                    if(obj.type == 'division') {
                        const rows = this.divRows.filter(data=>data.id != obj.id);
                        this.divRows = rows;
                    }
                });
            }
        },
        async setUpdate(data, type){
            
            this.overrideFields['name'] = this.selected.name;            
            this.type = type;
            
            this.overrideFields['id'] = this.selected.id;
            this.overrideFields['user'] = type=='company'? this.selected.user : 'test';
            this.overrideFields['pwd'] =  type=='company'? this.selected.pwd  : 'test';
            
            $('#myModal-SAP-Setting').modal('show');
            this.MDBINPUT();
            
        },
        testConnection(){
            this.loader = true;
            $('#myModal-SAP-Setting2').modal('show');
            
            // return;
            axios.post('api/override-login', {
                id: this.selected.id || 0,
                type: this.type || '',
                // user: this.selected.user || '',
                // pwd: this.selected.pwd || ''
            }).then(()=>{
                this.loader = false;
                this.errMsg = `Successfuly connected`;
            }).catch(er=>{

                console.log('status', er.response.status);
                if(er.response.status == 401){
                    this.errMsg = `Invalid user credentials`
                }
                else{
                    this.errMsg = `Network connection error. Please check SAP API Server if you are connected`;
                }
                
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
        closeModal(){
            this.overrideFields = {
                name: '',
                user: '',
                pwd: ''
            };
            this.loader = false;
            this.errMsg = `Please wait while checking your credentials`;
            this.isUpdate = false;
            // $('.modal').modal('hide');
        },
    },
    beforeDestory(){

    },
    mounted(){
        this.MDBINPUT();
        $('.modal').on("hidden.bs.modal", this.closeModal);
        axios.post('api/get-seop')
        .then(({data})=>{
            this.endpoint = data;
        });

        axios.get('api/get-override-setting-company')
        .then(({data})=>{
            data.forEach( val => {
                if(val.type == 'company') {
                    this.compRows = JSON.parse(val.json);
                }
                else if(val.type == 'division'){
                    this.divRows = JSON.parse(val.json)
                }
            });
        });

        const self = this;
        let isremove = false;
        $("tbody.tbl_body_sap_setting").on('click', 'button.remove-override', function() {
            const data = $(this).closest("tr")   // Finds the closest row <tr> 
                    .find("td") 
                    .siblings(":last");
            
            // self.setUpdate({id: data.attr('id'), name: data.text()}, data.data('type'));
            self.delete({type: data.data('type'), id: data.attr('id')})
            isremove = true;
        });

        $("tbody.tbl_body_sap_setting").on('click', 'button.retest-connection', function() {
            const data = $(this).closest("tr")   // Finds the closest row <tr> 
                    .find("td") 
                    .siblings(":last");
            self.testConnection();
            isremove = true;
        });

        $("tbody.tbl_body_sap_setting").on('click', 'tr', function() {
            if(!isremove) {
                self.isUpdate = true;
                const data = $(this).closest("tr")   // Finds the closest row <tr> 
                       .find("td") 
                       .siblings(":last");
                // console.log(data.attr('id'), data.data('type'));
                self.setUpdate({id: data.attr('id'), name: data.text()}, data.data('type'));
            }
            isremove = false;    
        });
        
        
    }
}

let status = ['Pending', 'Approved', 'Rejected'];


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