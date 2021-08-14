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
                <div><h4 class="margin-8" style="padding-top: 3.5px; padding-bottom: 3.5px;">
                    COMPANY PROFILE - {{formtitle}}
                    </h4>
                </div>
                <!-- <div><input id="customsearch" type="tex" class="form-control" v-model="search" placeholder="search"></div> -->
            </div>
        </div>
        <div class="col-lg-12 canvas-chart margin-15">
            <!-- {{src_img}} -->
            <div id="main-comp-profile-img-container">

            </div>
             <!-- <img id="img-comprofile" class="gallery-image" src=""  /> -->
            <!-- <div class="comp-profile-container">
                <div class="dflex justify-content-center lvl">
                    <div>
                        <div class="comp-profile-box">
                            <div class="dflex teal" style="padding: 0 15px 15px">
                                    <div style="align-self: flex-start; padding-top: 22px; z-index: 9; width: 88px;">
                                        <img id="avatar-md" :src="this.selectedCoo.avatar || 'public/images/priemer_jacket.jpg'" alt="Avatar" >
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
                <!-- lvl2 --
                <div class="dflex justify-content-center lvl">
                    <div class="comp-profile-details">
                        <router-link to="/company-profile-marketing">
                            <div class="comp-profile-box">
                                <div class="dflex neon" style="padding: 0 15px 15px">
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
                        </router-link>
                    </div>

                    <div class="comp-profile-details">
                        <router-link to="/company-profile-operation">
                        <div class="comp-profile-box">
                            <div class="dflex orange" style="padding: 0 15px 15px">
                                    <div style="align-self: flex-start; padding-top: 22px; z-index: 9; width: 88px;">
                                        <img id="avatar-md" :src="this.selectedVp2.avatar || 'public/images/priemer_jacket.jpg'" alt="Avatar" >
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
                        </router-link>
                    </div>
                </div>


                <div class="clearfix"></div>
            </div> -->

            <!-- <br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf<br>asdf -->
        </div>
         <div class="clearfix"></div>
        <br><br>
    </div>
</template>

<script>

export default {

    data() {
        return {
            // headers:  [],
            // rows: [],

            // dtHandle: null,
            loader: true,
            // forUpdate: false,
            formtitle: '',
        }
    },
    watch:{
        rows(val, old){
            // let row = val;
            // this.dtHandle.clear();
            // this.dtHandle.rows.add(row);
            // this.dtHandle.draw();
        },
        $route (to, from){
            this.pullComProfile(this.$route.params.comp_prof_id);
        },
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
            })
        },

    },
    methods:{
        async pullComProfile(params = ''){
                $("#main-comp-profile-img-container").empty();
                axios.get('api/compprofile-per-detail/'+params)
                .then(async (response) => {
                    this.loader = false;
                    if(response.data.length > 0){
                        this.formtitle = await response.data[0].catname;
                        (async (cb)=>{
                            let src = '../storage/app/'+response.data[0].pdf_loc;
                            await $("#main-comp-profile-img-container")
                            .append(`<img class="gallery-image" src="${src}"  />`);
                            // $('.gallery-image').src = await  '../storage/app/'+response.data[0].pdf_loc;
                            return cb();
                        })(()=>{
                            $('.gallery-image').imageZoom({zoom: 200});
                        });


                    }
                })
                .catch(err => console.log(err));
        }
    },
    created(){

    },
    mounted() {

        let path = this.$router.currentRoute.path;
        // this.formtitle = (path.slice(path.lastIndexOf("/")+1)).replace(/-/g, ' ').toUpperCase();

        this.$root.$data.baseURL = '../';

        this.pullComProfile(this.$route.params.comp_prof_id);

        // MODAL
        // $('#myModal').on("hidden.bs.modal", this.closeModal);

        // $('#content').css({"min-height": '100vh', "word-break": "break-all"});

    }
}
</script>
