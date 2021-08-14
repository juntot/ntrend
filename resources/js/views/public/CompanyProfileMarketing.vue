<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css'; */
    /* @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title">
            <div class="dflex d-align-center justify-space-between">
                <div><h4 class="margin-8">
                    <router-link to="/company-profile" style="color: white">
                        <i class="btn btn-lg far fa-arrow-alt-circle-left back-btn"></i>
                    </router-link>
                    COMPANY PROFILE</h4>
                </div>
                <!-- <div><input id="customsearch" type="tex" class="form-control" v-model="search" placeholder="search"></div> -->
            </div>
        </div>
        <div class="col-lg-12 canvas-chart margin-15">
            <div class="comp-profile-container">
                <div class="dflex justify-content-center lvl">
                    <div>
                        <div class="comp-profile-box">
                            <div class="dflex teal" style="padding: 0 15px 15px">
                                    <div style="align-self: flex-start; padding-top: 22px; z-index: 9; width: 88px;">
                                        <img id="avatar-md" :src="this.selectedVp1.avatar || 'public/images/priemer_jacket.jpg'" alt="Avatar"  >
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
                <!-- ========================================== separator ========================================= -->
                <br>
                <div class="col-md-4" v-for="(data, key) in heads" :key="key">
                    <div class="comp-profile-img-overlay dflex" data-toggle="modal" data-target="#myModal" @click.prevent="getMembers(data)">
                        <img :src="data.avatar || 'public/images/priemer_jacket.jpg'" class="" style="width:100%; object-fit: cover; height: 100%" alt="Avatar">
                        <div style="position: absolute; top: 50%; z-index: 1; transform: translateY(-50%)" class="col-sm-12">
                            <table>
                                <tr style="vertical-align: top;">
                                    <td style="padding-right: 8px; width: 50px;" class="bold-weight">Title</td>
                                    <td>{{data.posname}}</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td style="padding-right: 8px" class="bold-weight">ID</td>
                                    <td>{{data.empID}}</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td style="padding-right: 8px" class="bold-weight">Tel</td>
                                    <td>{{data.mobile}}</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td style="padding-right: 8px" class="bold-weight">Email</td>
                                    <td>{{data.email}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>


                <div class="clearfix"></div>
            </div>

            <!-- <br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf -->
        </div>



        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title" style="color: #3f51b5">Member Lists</h5>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr  v-for="(data, key)  in members" :key="key">
                            <td>{{data.fname}}</td>
                            <td>{{ data.lname }}</td>
                            <td>{{data.email}}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div>

            </div>
        </div>

    </div>
</template>

<script>

export default {

    data() {
        return {
            // headers:  [],
            // rows: [],

            // deptNames: [],
            // posNames: [],
            // branchNames: [],

            // dtHandle: null,
            loader: true,
            // forUpdate: false,
            compProfile: [],
            selectedVp1:{},
            heads:[],
            members:[],
        }
    },
    watch:{
        // rows(val, old){
        //     let row = val;
        //     this.dtHandle.clear();
        //     this.dtHandle.rows.add(row);
        //     this.dtHandle.draw();
        // }

        compProfile(val, old){
            val.forEach(data=>{

                if(data.referer === '1'){
                    this.selectedVp1 = data
                }else{
                    this.heads.push(data)
                }
            })
        },

    },
    methods:{
        // setUpdate(data){
        //     bus.$emit('setupdate', data);
        // },
        // closeModal(){
        //     this.forUpdate = false;
        // }
        getMembers(data){
            axios.get('api/get-comp-profile-getmembers/'+data.empID)
            .then(res=>this.members = res.data)
            .catch(er=>console.log(er))
        }
    },
    created(){

    },
    mounted() {
        axios.get('api/get-comp-profile-secondary/s1')
        .then(res=>{
            this.compProfile = res.data;
            this.loader = false;
        })
        .catch(er=>console.log(er));
        // this.loader = false;
        // MODAL
        // $('#myModal').on("hidden.bs.modal", this.closeModal);

        $('#content').css({"height": '100vh', "word-break": "break-all"});

    }
}
</script>
