<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <!-- <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div> -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header-title page-title padding-43">
            <div class="dflex d-align-center justify-space-between">
                <div><h4 class="margin-8">MASTER DIRECTORY</h4></div>
                <div><input id="customsearch" type="tex" v-model="searchname" class="form-control" placeholder="search"></div>
            </div>

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-top-58 margin-top-83">
            <div class="dflex flex-wrap directory-wrap block-mobile">
                <div class="gallery animate fadeInUp" v-for="(val, index) in filterName" :key="index">
                    <div class="content padding-15 b-radius">
                        <table>
                            <tr>
                                <td>
                                    <img id="avatar-xs" :src="val.img?'storage/app/'+val.img : 'public/images/img_avatar.png'" alt="Avatar" >
                                </td>
                                <td class="gallery-profile-name">

                                        <div class="font-weight-bold">
                                            {{val.fullname}}
                                        </div>
                                        <div>
                                            {{val.posname}}
                                        </div>

                                </td>
                            </tr>
                        </table>
                        <hr id="narrow-margin">
                        <div class="directory-quote">
                            <!-- <p><i class="fas fa-mobile-alt"></i> 091812812</p>
                            <p><i class="far fa-envelope"></i>juandelacruz@trend.com</p>
                            <p><i class="fas fa-map-marker-alt"></i>mabolomablolo</p>
                            <p><i class="fas fa-project-diagram"></i>IT-Department</p> -->
                            <p>{{val.deptname}}</p>
                            <p>{{val.email}}</p>
                            <p>{{val.mobile}}</p>
                            <p>{{val.branchname}}</p>
                            <p>{{val.dhired}}</p>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

export default {
   data(){
       return{
           searchname: '',
           rows:[
            //    {img: 'public/images/img_avatar.png', fullname: 'Juan Dela Paz', posname: 'IT-Personnel', mobile: '09123456789', email: 'juandelacruz@gmail.com', add: 'Agustinian Augostos Mabolo Cebu City 6000', deptname: 'IT-Department'},
            //    {img: 'public/images/img_avatar.png', fullname: 'Juan Dela Cruz', posname: 'IT-Personnel', mobile: '09123456789', email: 'juandelacruz@gmail.com', add: 'Agustinian Augostos Guada Cebu City 6000', deptname: 'IT-Department'},
            //    {img: 'public/images/img_avatar.png', fullname: 'Jean Grey', posname: 'HR-Personnel', mobile: '09123456789', email: 'juandelacruz@gmail.com', add: 'Agustinian Augostos Mingla Cebu CHRy 6000', deptname: 'HR-Department'},
            //    {img: 'public/images/img_avatar.png', fullname: 'Jean Gray', posname: 'HR-Personnel', mobile: '09123456789', email: 'juandelacruz@gmail.com', add: 'Agustinian Augostos Talisay Cebu City 6000', deptname: 'HR-Department'},
            //    {img: 'public/images/img_avatar.png', fullname: 'John Doe', posname: 'Accounting-Personnel', mobile: '09123456789', email: 'juandelacruz@gmail.com', add: 'Agustinian Colon Cebu City 6000', deptname: 'Accounting-Department'},
            //    {img: 'public/images/img_avatar.png', fullname: 'Steven Borough', posname: 'Sales-Personnel', mobile: '09123456789', email: 'juandelacruz@gmail.com', add: 'Agustinian Sanciangko Cebu CSalesy 6000', deptname: 'Sales-Department'},
            //    {img: 'public/images/img_avatar.png', fullname: 'Steven Stringer', posname: 'Sales-Personnel', mobile: '09123456789', email: 'juandelacruz@gmail.com', add: 'Agustinian Banawa Cebu City 6000', deptname: 'Sales-Department'},
           ]
       }
   },
   computed:{
       filterName(){
	    	let self=this;
            let counter =1;

            return this.rows.filter(function (item) {
                return Object.values(item).map(function (value) {
                    return String(value).toLowerCase();
                }).find(function (value) {
                    return value.includes(self.searchname.toLowerCase());
                });
            });


		},
   },
   methods:{

   },
   beforeDestroy(){

        if(document.getElementById("content") !=  null)
        {
            document.querySelector("#content").classList += " content";
        }


	},
   mounted(){

        if(document.getElementById("content") !=  null)
        {
            document.querySelector("#content").classList.remove("content");
            document.querySelector(".margin-top-58").classList += ' nopadding';
        }
        axios.get('api/getdirectory').then((response)=>{
            this.rows = response.data;
        }).catch(err=>console.log(err));

   }

}

</script>
