<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12">
            <h4>MANAGE COMPANY PROFILE</h4>
        </div>
        <div class="col-lg-12 canvas-chart margin-15">
            <div class="comp-profile-container">
                <div class="dflex justify-content-center lvl">
                    <div>
                        <div class="comp-profile-box">
                            <div class="dflex teal" style="padding: 0 15px 15px">
                                    <div style="align-self: flex-start; padding-top: 22px; z-index: 9; width: 88px;">
                                        <img id="avatar-md" :src="this.selectedCoo.avatar || 'public/images/priemer_jacket.jpg'" alt="Avatar" data-toggle="modal" data-target="#myModal" @click.prevent="type = 'coo'" >
                                    </div>
                                    <div style="padding-right: 7px; font-size: 12px">
                                        <div>
                                            <div class="comp-profile-title">{{this.selectedCoo.fname || 'Juan'}} {{this.selectedCoo.lname || 'Dela Cruz'}}</div>
                                            <div style="padding: 10px 0 0px 15px">
                                                    <table>
                                                        <tr>
                                                            <td style="padding-right: 8px" class="bold-weight">Title</td>
                                                            <td>{{this.selectedCoo.posname}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-right: 8px" class="bold-weight">ID</td>
                                                            <td>{{this.selectedCoo.empID}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-right: 8px" class="bold-weight">Tel</td>
                                                            <td>{{this.selectedCoo.mobile}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-right: 8px" class="bold-weight">Email</td>
                                                            <td>{{this.selectedCoo.email}}</td>
                                                        </tr>
                                                    </table>
                                            </div>

                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="comp-profile-divider">
                    <div class="dflex justify-space-between d-align-center">
                        <span class="dots neon"></span>
                        <span class="dots md teal"></span>
                        <span class="dots orange"></span>
                    </div>
                </div>
                <br>
                <!-- lvl2 -->
                <div class="dflex justify-content-center lvl">
                    <div class="comp-profile-details">
                        <!-- <router-link to="/company-profile-marketing"> -->
                            <div class="comp-profile-box">
                                <div class="dflex neon" style="padding: 0 15px 15px">
                                        <div style="align-self: flex-start; padding-top: 22px; z-index: 9; width: 88px;">
                                            <img id="avatar-md" :src="this.selectedVp1.avatar || 'public/images/priemer_jacket.jpg'" alt="Avatar" data-toggle="modal" data-target="#myModal" @click.prevent="type = 'vp1'" >
                                        </div>
                                        <div style="padding-right: 7px; font-size: 12px">
                                            <div>
                                                <div class="comp-profile-title">{{this.selectedVp1.fname || 'Juan'}} {{this.selectedVp1.lname || 'Dela Cruz'}}</div>
                                                <div style="padding: 10px 0 0px 15px">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 8px" class="bold-weight">Title</td>
                                                                <td>{{this.selectedVp1.posname}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-right: 8px" class="bold-weight">ID</td>
                                                                <td>{{this.selectedVp1.empID}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-right: 8px" class="bold-weight">Tel</td>
                                                                <td>{{this.selectedVp1.mobile}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-right: 8px" class="bold-weight">Email</td>
                                                                <td>{{this.selectedVp1.email}}</td>
                                                            </tr>
                                                        </table>
                                                </div>

                                            </div>
                                        </div>
                                </div>
                            </div>
                        <!-- </router-link> -->
                    </div>

                    <div class="comp-profile-details">
                        <!-- <router-link to="/company-profile-operation"> -->
                        <div class="comp-profile-box">
                            <div class="dflex orange" style="padding: 0 15px 15px">
                                    <div style="align-self: flex-start; padding-top: 22px; z-index: 9; width: 88px;">
                                            <img id="avatar-md" :src="this.selectedVp2.avatar || 'public/images/priemer_jacket.jpg'" alt="Avatar" data-toggle="modal" data-target="#myModal" @click.prevent="type = 'vp2'" >
                                        </div>
                                        <div style="padding-right: 7px; font-size: 12px">
                                            <div>
                                                <div class="comp-profile-title">{{this.selectedVp2.fname || 'Juan'}} {{this.selectedVp2.lname || 'Dela Cruz'}}</div>
                                                <div style="padding: 10px 0 0px 15px">
                                                        <table>
                                                            <tr>
                                                                <td style="padding-right: 8px" class="bold-weight">Title</td>
                                                                <td>{{this.selectedVp2.posname}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-right: 8px" class="bold-weight">ID</td>
                                                                <td>{{this.selectedVp2.empID}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-right: 8px" class="bold-weight">Tel</td>
                                                                <td>{{this.selectedVp2.mobile}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-right: 8px" class="bold-weight">Email</td>
                                                                <td>{{this.selectedVp2.email}}</td>
                                                            </tr>
                                                        </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <!-- </router-link> -->
                        </div>
                </div>


                <div class="clearfix"></div>



                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-comp-profile modal-body">

                            <ul class="nav nav-tabs">
                                <li id="home_li" class="active"><a data-toggle="tab" href="#home" @click.prevent="panelType = ''">Update Comp Profile</a></li>
                                <li><a data-toggle="tab" href="#menu1" v-if="type=='vp1' || type == 'vp2'" @click.prevent="panelType = 'addMem'">Add Dept Head</a></li>
                                <!-- <li><a data-toggle="tab" href="#menu2" v-if="type=='vp1' || type == 'vp2'" @click.prevent="panelType ='addSub'">Sub Member</a></li> -->
                            </ul>

                            <div class="tab-content">
                                <div id="home" class="relative-pos tab-pane fade in active">
                                        <div class="mdb-form-field form-group-limitx" style="margin-bottom: 2px;">
                                            <div class="form-field__control">
                                                <input type="text" name="empID" class="form-field__input" aria-required="true" aria-invalid="false" v-model="search" placeholder="Search Name/ID/etc.">
                                                <label class="form-field__label"></label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors"></span>
                                        </div>
                                        <div class="comp-profile-listing" style="position: absolute; width: 100%; background: white;
                                            -webkit-box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);
                                            -moz-box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);
                                            box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);">
                                            <ul>
                                                <li v-for="(data, key) in searchfilter" :key="key" @click.prevent="dataSelected(data)">
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.empID}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.fname}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.lname}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.deptname}}
                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                </div>
                                <div id="menu1" class="relative-pos tab-pane fade">
                                        <div class="mdb-form-field form-group-limitx" style="margin-bottom: 2px;">
                                            <div class="form-field__control">
                                                <input type="text" name="empID" class="form-field__input" aria-required="true" aria-invalid="false" v-model="search" placeholder="Add Department Head">
                                                <label class="form-field__label"></label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors"></span>
                                        </div>
                                        <div class="comp-profile-listing" style="position: absolute; width: 100%; background: white;
                                            -webkit-box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);
                                            -moz-box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);
                                            box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);">
                                            <ul>
                                                <li v-for="(data, key) in searchfilter" :key="key" @click.prevent="addHead(data)">
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.empID}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.fname}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.lname}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.deptname}}
                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                        <div class="clearfix"></div>
                                        <!-- table -->
                                        <div v-show="type=='vp1'">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Firstname</th>
                                                    <th>Lastname</th>
                                                    <th>Department</th>
                                                    <th>Operation</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(data, key) in vp1_heads" :key="key" @dblclick.prevent="showModal2(data.empID),
                                                    selected_vp1_head = data.empID, selected_head_ID = data.empID, selected_head_obj = data,
                                                    selected_head_info = (data.deptname+' - '+data.fname+' '+data.lname)">
                                                    <td>{{data.fname}}</td>
                                                    <td>{{data.lname}}</td>
                                                    <td>{{data.deptname}}</td>
                                                    <td><a href="#" @click.prevent="removeHead(data.empID)">remove</a></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- vp2 -->
                                        <div v-show="type=='vp2'">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Firstname</th>
                                                    <th>Lastname</th>
                                                    <th>Department</th>
                                                    <th>Operation</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(data, key) in vp2_heads" :key="key" @dblclick.prevent="showModal2(data.empID),
                                                    selected_vp2_head = data.empID, selected_head_ID = data.empID, selected_head_obj = data,
                                                    selected_head_info = (data.deptname+' - '+data.fname+' '+data.lname)">
                                                    <td>{{data.fname}}</td>
                                                    <td>{{data.lname}}</td>
                                                    <td>{{data.deptname}}</td>
                                                    <td><a href="#" @click.prevent="removeHead(data.empID)">remove</a></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    <div>
                                </div>
                                </div>
                                <!-- <div id="menu2" class="relative-pos tab-pane fade">
                                        <div class="mdb-form-field form-group-limitx">
                                            <div class="form-field__control">
                                                <!-- left side org --
                                                <select name="vp1" class="form-field__input" v-show="type=='vp1'" v-model="selected_vp1_head">
                                                    <option :value="data.empID" v-for="(data, key) in vp1_heads" :key="key">
                                                        {{data.fname}}
                                                    </option>
                                                </select>

                                                <!-- right side org --
                                                <select name="vp2" class="form-field__input" v-show="type=='vp2'" v-model="selected_vp2_head">
                                                    <option :value="data.empID" v-for="(data, key) in vp2_heads" :key="key">
                                                        {{data.fname}}
                                                    </option>
                                                </select>

                                                <label class="form-field__label">Select Head</label>
                                                <div class="form-field__bar"></div>
                                            </div> <span class="errors"></span>
                                        </div>
                                        <div class="mdb-form-field form-group-limitx" style="margin-bottom: 2px;">
                                            <div class="form-field__control">
                                                <input type="text" name="empID" class="form-field__input" aria-required="true" aria-invalid="false" v-model="search" placeholder="Search for Member">
                                                <label class="form-field__label"></label>
                                                <div class="form-field__bar"></div>
                                            </div>
                                            <span class="errors"></span>
                                        </div>
                                        <div class="comp-profile-listing" style="position: absolute; width: 100%; background: white;
                                            -webkit-box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);
                                            -moz-box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);
                                            box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);">
                                            <ul>
                                                <li v-for="(data, key) in searchfilter" :key="key" @click.prevent="addMember(data)">
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.empID}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.fname}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.lname}}
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        {{data.deptname}}
                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="modal-footer" style="padding: 15px;">
                            <!-- <button type="button" class="btn btn-primary" v-show="panelType == 'addMem'" @click.prevent="addHead">Save Head</button>
                            <button type="button" class="btn btn-primary" v-show="panelType == 'addSub'" @click.prevent="addMember">Save</button> -->
                        </div>
                    </div>

                    </div>
                </div>

                <!-- modal2 -->
                <div id="myModal2" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Team Members</h4>
                        </div>
                        <div class="modal-comp-profile modal-body">
                            <div>
                                <!-- <div class="mdb-form-field form-group-limitx">
                                    <div class="form-field__control">
                                        <select name="vp1" class="form-field__input"  @change.prevent="updateHead" :value="''">
                                            <option :value="data.empID" v-for="(data, key) in vp1_heads" :key="key">
                                                {{data.fname}}
                                            </option>
                                        </select>

                                        <label class="form-field__label">Update Head</label>
                                        <div class="form-field__bar"></div>
                                    </div> <span class="errors"></span>
                                </div> -->
                                <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{this.selected_head_info}}
                                <span class="caret2"></span></button>
                                <ul class="dropdown-menu">
                                    <li :value="data.empID" v-for="(data, key) in rows" :key="key" >
                                            <a href="#" @click.prevent="updateHead(data)">{{data.deptname}} - {{data.fname}} {{data.lname}}</a>
                                    </li>
                                </ul>
                                </div>

                                <!-- <div class="form-group">

                                    <select class="form-control" id="sel1" @change="updateHead">
                                        <option :value="selected_head_ID" :selected="true" >{{this.selected_head_info}}</option>
                                        <option :value="data.empID" v-for="(data, key) in rows" :key="key" >
                                            {{data.deptname}} - {{data.fname}} {{data.lname}}
                                        </option>
                                    </select>
                                </div> -->

                            </div>
                            <div class="relative-pos">
                                <div class="mdb-form-field form-group-limitx" style="margin-bottom: 2px;">
                                    <div class="form-field__control">
                                        <input type="text" name="empID" class="form-field__input" aria-required="true" aria-invalid="false" v-model="search2" placeholder="Add Member">
                                        <label class="form-field__label"></label>
                                        <div class="form-field__bar"></div>
                                    </div>
                                    <span class="errors"></span>
                                </div>
                                <div class="comp-profile-listing" style="position: absolute; width: 100%; background: white;
                                    -webkit-box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);
                                    -moz-box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);
                                    box-shadow: 0px 9px 11px 0px rgba(0,0,0,0.16);">
                                    <ul>
                                        <li v-for="(data, key) in searchfilter2ndModal" :key="key" @click.prevent="addMember(data)">
                                            <div class="col-md-3 col-sm-3">
                                                {{data.empID}}
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                {{data.fname}}
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                {{data.lname}}
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                {{data.deptname}}
                                            </div>

                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Firstname</th>
                                            <th>Lastname</th>
                                            <th>Department</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(data, key) in teamMember" :key="key" >
                                            <td>{{data.fname}}</td>
                                            <td>{{data.lname}}</td>
                                            <td>{{data.deptname}}</td>
                                            <td><a href="#" @click.prevent="removeMember(data)">remove</a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="modal-footer" style="padding-bottom: 15px">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div> -->
                        </div>

                    </div>
                </div>


            </div>

            <!-- <br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf -->
        </div>
    </div>
</template>

<script>
// import ManagePayslip from '../components/ManagePayslip';

export default {
    components: {
        // ManagePayslip
    },
    data() {
        return {
            search: '',
            search2: '',
            headers:  [],
            rows: [],
            selectedCoo: {},
            selectedVp1: {},
            selectedVp2: {},

            // vp1_list:[],
            // vp2_list:[],

            vp1_heads: [],
            vp2_heads: [],
            selected_head_obj: {},
            selected_head_info: '',
            selected_head_ID: '',
            selected_vp1_head:'',
            selected_vp2_head:'',

            vp1_heads_member: [],
            vp2_heads_member: [],
            teamMember:[],

            compProfile:[],
            type:'',
            panelType: '',
            dtHandle: null,
            loader: true,
            forUpdate: false,
            disableCloseModal: false,


        }
    },
    watch:{
        compProfile(val, old){
            val.forEach(data=>{
                if(data.role === 1){
                    this.selectedCoo = data
                }
                if(data.referer === '1'){
                    this.selectedVp1 = data
                }
                if(data.referer === '2'){
                    this.selectedVp2 = data
                }
                if(data.referer === '3'){
                    this.vp1_heads.push(data);
                }
                if(data.referer === '4'){
                    this.vp2_heads.push(data);
                }
            })
        },
        rows(val, old){
            // let row = [];
            // this.dtHandle.clear();
            // this.dtHandle.rows.add(this.rows);
            // this.dtHandle.draw();
        }
    },
    computed:{
        searchfilter(){
	    	let self=this;
            let counter =0;
            if(self.search != ''){
                return this.rows.filter(function (item) {
                    return Object.values(item).map(function (value) {
                        return String(value).toLowerCase();
                    }).find(function (value) {
                        return value.includes(self.search.toLowerCase());
                    }) && counter++ < 4;
                });
            }


        },
        searchfilter2ndModal(){
	    	let self=this;
            let counter =0;
            if(self.search2 != ''){
                return this.rows.filter(function (item) {
                    return Object.values(item).map(function (value) {
                        return String(value).toLowerCase();
                    }).find(function (value) {
                        return value.includes(self.search2.toLowerCase());
                    }) && counter++ < 4;
                });
            }


        },
        searchfilterVp1(){
	    	let self=this;
            let counter =0;
            if(self.search != ''){
                return this.vp1_heads.filter(function (item) {
                    return Object.values(item).map(function (value) {
                        return String(value).toLowerCase();
                    }).find(function (value) {
                        return value.includes(self.search.toLowerCase());
                    }) && counter++ < 4;
                });
            }
        },
        searchfilterVp2(){
	    	let self=this;
            let counter =0;
            if(self.search != ''){
                return this.vp2_heads.filter(function (item) {
                    return Object.values(item).map(function (value) {
                        return String(value).toLowerCase();
                    }).find(function (value) {
                        return value.includes(self.search.toLowerCase());
                    }) && counter++ < 4;
                });
            }
		},
    },
    methods:{
        addRow(val){
        //    this.rows.unshift({deptname: val});
        },
        showModal2(empID = null){
            axios.get('api/get-comp-profile-getmembers/'+empID)
            .then(res=>{
                this.teamMember = res.data;
                $('#myModal2').modal('show');
            })
            .catch(er=>console.log(er))

        },
        dataSelected(data){

            if(this.type === 'coo'){
                let tempCoo = this.selectedCoo;
                let newCoo = this.rows.filter(coo=>{
                    return data.empID !== coo.empID
                });
                newCoo.push(tempCoo);

                axios.post('api/set-comp-profile',{
                    _empID: data.empID,
                    role: 1,
                    referer: 0,
                }).then(res=>{
                    this.rows = newCoo;
                    this.selectedCoo = data;
                })
                .catch(er=>console.log(er));

            }
            if(this.type === 'vp1'){
                let tempCoo = this.selectedVp1;
                let newCoo = this.rows.filter(coo=>{
                    return data.empID !== coo.empID
                });
                newCoo.push(tempCoo);
                axios.post('api/set-comp-profile',{
                    _empID: data.empID,
                    role: 2,
                    referer: 1,
                }).then(res=>{
                    this.rows = newCoo;
                    this.selectedVp1 = data;
                })
                .catch(er=>console.log(er));
            }

            if(this.type === 'vp2'){
                let tempCoo = this.selectedVp2;
                let newCoo = this.rows.filter(coo=>{
                    return data.empID !== coo.empID
                });
                newCoo.push(tempCoo);

                axios.post('api/set-comp-profile',{
                    _empID: data.empID,
                    role: 2,
                    referer: 2,
                }).then(res=>{
                    this.rows = newCoo;
                    this.selectedVp2 = data;
                })
                .catch(er=>console.log(er));

            }
        },
        addHead(obj){
            if(this.type == 'vp1'){
                let newCoo = this.rows.filter(head=>{
                    return obj.empID !== head.empID
                });

                axios.post('api/add-vp1-head',{
                    _empID: obj.empID,
                    role: 3,
                    referer: 3,
                })
                .then(res=>{
                    this.rows = newCoo;
                    this.vp1_heads.push(obj);

                })
                .catch(er=>console.log(er))
            }
            if(this.type == 'vp2'){
                let newCoo = this.rows.filter(head=>{
                    return obj.empID !== head.empID
                });
                axios.post('api/add-vp2-head',{
                    _empID: obj.empID,
                    role: 3,
                    referer: 4
                })
                .then(res=>{
                    this.rows = newCoo;
                    this.vp2_heads.push(obj);
                })
                .catch(er=>console.log(er))
            }
        },
        updateHead(obj){
            // alert(e.target.value);
            let empID = obj.empID;
            this.selected_head_info= (obj.deptname+' - '+obj.fname+' '+obj.lname);
            this.rows.push(this.selected_head_obj);
            this.selected_head_obj = obj;


            let temp = this.rows.filter(data=>data.empID != obj.empID);
            this.rows = temp;
            // this.selected_head_ID = obj.empID;
            if(this.type == 'vp1'){
                this.selected_vp1_head = obj.empID;
                this.vp1_heads.unshift(obj);
                this.vp1_heads = this.vp1_heads.filter(data=>data.empID != this.selected_head_ID);
                // this.selected_head_ID = empID;

                axios.post('api/modify-head',{
                    empID: this.selected_vp1_head
                })
                .then(res=>{
                    return axios.post('api/update-head',{
                            old_referer: this.selected_head_ID,
                            reference: empID,
                            _empID: empID,
                            role: 3,
                            referer: 3,
                    });
                })
                .then(res=>{
                    // this.rows = newCoo;
                    // this.vp2_heads.push(this.selected_head_obj);
                    // let heads = this.vp2_heads.filter(data=>data.empID != this.selected_vp2_head);
                    // this.vp2_heads = heads;


                     this.selected_head_ID = empID;
                })
                .catch(er=>console.log(er))


            }else{
                this.selected_vp2_head = obj.empID;
                this.vp2_heads.unshift(obj);
                this.vp2_heads = this.vp2_heads.filter(data=>data.empID != this.selected_head_ID);
                 // this.selected_head_ID = empID;

                axios.post('api/modify-head',{
                    empID: this.selected_vp1_head
                })
                .then(res=>{
                    return axios.post('api/update-head',{
                            old_referer: this.selected_head_ID,
                            reference: empID,
                            _empID: empID,
                            role: 3,
                            referer: 3,
                    });
                })
                .then(res=>{
                    // this.rows = newCoo;
                    // this.vp2_heads.push(this.selected_head_obj);
                    // let heads = this.vp2_heads.filter(data=>data.empID != this.selected_vp2_head);
                    // this.vp2_heads = heads;


                     this.selected_head_ID = empID;
                })
                .catch(er=>console.log(er))
            }

            // if(this.type == 'vp2'){
            //     this.rows.push(this.selected_head_obj);

            //     let newCoo = this.rows.filter(head=>{
            //         return empID !== head.empID;
            //     });
            //     // process
            //     axios.post('api/modify-head',{
            //         empID: this.selected_vp2_head
            //     })
            //     .then(res=>{
            //         return axios.post('api/update-head',{
            //                 old_referer: this.selected_vp2_head,
            //                 reference: empID,
            //                 _empID: empID,
            //                 role: 3,
            //                 referer: 4,
            //         });
            //     })
            //     .then(res=>{
            //         this.rows = newCoo;
            //         this.vp2_heads.push(this.selected_head_obj);
            //         let heads = this.vp2_heads.filter(data=>data.empID != this.selected_vp2_head);
            //         this.vp2_heads = heads;
            //     })
            //     .catch(er=>console.log(er))
            // }

        },
        removeHead(id){
            let newCoo = [];
            if(this.type == 'vp1'){
                 newCoo = this.vp1_heads.filter(head=>{
                    return id !== head.empID
                });
            }else{
                newCoo = this.vp2_heads.filter(head=>{
                    return id !== head.empID
                });
            }
            axios.post('api/remove-head',{
                empID: id
            })
            .then(res=>{
                if(this.type == 'vp1'){
                    this.vp1_heads = newCoo
                }else{
                    this.vp2_heads = newCoo
                }

                return axios.get('api/get-comp-profile-notlisted')
            })
            .then(res=>{
                this.rows = res.data;
            })
            .catch(res=>console.log(res));
        },
        addMember(obj){
        let slectedHeadID = ''
            if(this.type == 'vp1'){
                slectedHeadID = this.selected_vp1_head;
            }
            else{
                slectedHeadID = this.selected_vp2_head;
            }

                let newCoo = this.rows.filter(member=>{
                    return obj.empID !== member.empID
                });

                axios.post('api/add-vp1-head-member',{
                    _empID: obj.empID,
                    role: 4,
                    referer: slectedHeadID,
                })
                .then(res=>{
                    this.rows = newCoo;
                    this.teamMember.push(obj);
                })
                .catch(er=>console.log(er))
            // }
            // if(this.type == 'vp2'){
            //     let slectedHeadID = this.selected_vp2_head;
            //     let newCoo = this.rows.filter(member=>{
            //         return obj.empID !== member.empID
            //     });
            //     axios.post('api/add-vp2-head-member',{
            //         _empID: obj.empID,
            //         role: 4,
            //         referer: slectedHeadID
            //     })
            //     .then(res=>{
            //         this.rows = newCoo;
            //         this.vp2_heads_member.push(obj);
            //     })
            //     .catch(er=>console.log(er))
            // }
        },
        removeMember(obj){
             let newList = this.teamMember.filter(data=>data.empID != obj.empID);
             axios.post('api/remove-head-member',{
                empID: obj.empID
            })
            .then(res=>{
                this.teamMember = newList;
                this.rows.push(obj);
            })
            .catch(er=>console.log(er))
        },
        updateRow(val)
        {
            // let row = this.$data.rows;
            // row.forEach((item, index)=>{
            //     if(item.deptID == val.deptID)
            //     {
            //         this.$data.rows.splice(index, 1);
            //         this.$data.rows.unshift(val);
            //     }
            // });
        },
        setUpdate(data){
            bus.$emit('setupdate', data);
        },


        closeModal(){
            // this.forUpdate = false;
            $('.nav-tabs li').removeClass('active');
            $('.nav-tabs li#home_li').addClass('active');

            $('#home').addClass('in active');
            $('#menu1').removeClass('in active');
            $('#menu2').removeClass('in active');
        }
    },
    created(){

    },
    mounted() {

        axios.get('api/get-comp-profile-notlisted')
        .then(res=>{
            this.rows = res.data;
            this.loader = false;
        })
        .catch(er=>console.log(er));

        axios.get('api/get-comp-profile')
        .then(res=>{
            this.compProfile = res.data;
        })
        .catch(er=>console.log(er));
        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

        $('#myModal2').on("show.bs.modal", ()=>{
            // $('#myModal').css("display", "none");
        });

        $('#myModal2').on("hidden.bs.modal", ()=>{
            // $('#myModal').css("display", "block");
        });
        // End Axios


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
        // $(document).on('show.bs.modal', '.modal', function () {
        //     var zIndex = 1040 + (10 * $('.modal:visible').length);
        //     $(this).css('z-index', zIndex);
        //     setTimeout(function() {
        //         $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        //     }, 0);
        // });


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
