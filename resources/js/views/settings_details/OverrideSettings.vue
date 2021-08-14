<template>
    <div>
        <div class="col-lg-12 bgc-white">
            <h2>OVERRIDE SETTINGS</h2>
        </div>
        <div class="clearfix"></div>
        <!-- dates -->
        <div class="col-md-6 nopadding-left">
            <br>
            <div class="col-md-12 bgc-white">
                <p class="orange-text">Company</p>
                <div class="col-md-6 nopadding with-margin-bottom">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal" 
                    @click.prevent="type = 'company'">Add New</button>
                </div>
                <table id="override-company-tbl" class="mdl-data-table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(val, index) in compRows" :key="index">
                            <td :id="val.id" data-type="company">{{val.name}}</td>
                            <td><button type="button" class="btn btn-danger remove-override">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
        <div class="col-md-6 nopadding-right">
            <br>
            <div class="col-md-12 bgc-white">
                <p class="orange-text">Division</p>
                <div class="col-md-6 nopadding with-margin-bottom">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"
                    @click.prevent="type = 'division'">Add New</button>
                </div>
                <table id="override-division-tbl" class="mdl-data-table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Division Name</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(val, index) in divRows" :key="index">
                            <td :id="val.id" data-type="division">{{val.name}}</td>
                            <td><button type="button" class="btn btn-danger remove-override">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
                <br>
            </div>
        </div>

        <div class="clearfix"></div>
        <br>
        <div class="col-md-12 bgc-white">
            <div class="bgc-white">
                <p class="orange-text">Branch</p>
                <div class="col-lg-6 col-md-6  with-margin-bottom nopadding" >
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"
                    @click.prevent="type = 'branch'">Add New</button>
                </div>
                <table id="override-branch-tbl" class="mdl-data-table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Branch Name</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(val, index) in branchRows" :key="index">
                            <td :id="val.id" data-type="branch">{{val.name}}</td>
                            <td><button type="button" class="btn btn-danger remove-override">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
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
    </div>
</template>
<script>

export default {
    data(){
        return{
            compRows: [
                {id: 1, name: 'aw'},
                {id: 123, name: 'aw2'}
            ],
            divRows: [],

            branchRows: [],
            overrideFields: {
                name: '',
            },
            type: 'company',
            isUpdate: false,
        }
    },
    watch:{
        rows(val, old){
            // let row = val;

            // row.forEach((item, index)=>{
            //     // if(!isNaN(item.leavetype) && !isNaN(item.status)){
            //         row[index]['formtype'] = this.selected_type.formtitle;
            //     // }
            // });

            // this.dtHandle.clear();
            // this.dtHandle.rows.add(row);
            // this.dtHandle.draw();
        },
    },
    filters:{
        capitalize(value){
            return value.charAt(0).toUpperCase() + value.slice(1)
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
        add(){
            if(!this.overrideFields.name) return true;
            const data = {...this.overrideFields, type: this.type};
            axios.post('api/add-override-company', data)
            .then( async ({data})=>{
                const newRec = await {...this.overrideFields, id: data.id};
                if(this.type == 'company') {
                    await this.compRows.push(newRec);
                }
                if(this.type == 'division') {
                    await this.divRows.push(newRec);
                }
                if(this.type == 'branch') {
                    await this.branchRows.push(newRec);
                }
                $('.modal').modal('hide');
            });
        },
        update(){
            if(!this.overrideFields.name) return true;
            const data = {...this.overrideFields, type: this.type};

            // mange update
            const inputData = this.overrideFields;
            const updateRow = (row) =>{
                for (const iterator of this[row]) {
                    if(iterator.id == inputData.id) {
                        iterator.name = inputData.name;
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
                if(this.type == 'branch') {
                    updateRow('branchRows');
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
                    if(obj.type == 'branch') {
                        const rows = this.branchRows.filter(data=>data.id != obj.id);
                        this.branchRows = rows;
                    }
                });
            }
        },
        setUpdate(data, type){
            this.overrideFields = data;
            this.type = type;
            $('#myModal').modal('show');
            this.MDBINPUT();
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
                id: '',
            }
            this.isUpdate = false;
        },
    },
    beforeDestory(){

    },
    mounted(){
        this.MDBINPUT();
        $('.modal').on("hidden.bs.modal", this.closeModal);
        
        axios.get('api/get-override-company')
        .then(({data})=>{
            data.forEach( val => {
                if(val.type == 'company') {
                    this.compRows = JSON.parse(val.json);
                }
                else if(val.type == 'division') {
                    this.divRows = JSON.parse(val.json)
                }
                else {
                    this.branchRows = JSON.parse(val.json);
                }
            });
        });

        const self = this;
        let isremove = false;
        $("tbody").on('click', 'button.remove-override', function() {
            const data = $(this).closest("tr")   // Finds the closest row <tr> 
                    .find("td") 
                    .siblings(":last");
            
            // self.setUpdate({id: data.attr('id'), name: data.text()}, data.data('type'));
            self.delete({type: data.data('type'), id: data.attr('id')})
            isremove = true;
        });

        $("tbody").on('click', 'tr', function() {
            if(!isremove) {
                self.isUpdate = true;
                const data = $(this).closest("tr")   // Finds the closest row <tr> 
                       .find("td") 
                       .siblings(":last");
                
                self.setUpdate({id: data.attr('id'), name: data.text()}, data.data('type'));
            }
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