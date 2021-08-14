<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css'; */
    /* @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 header-title page-title">
            <!-- <h4>COMPANY VIDEOS</h4> -->
            <div class="dflex d-align-center justify-space-between">
                <div><h4 class="margin-8">COMPANY VIDEOS</h4></div>
                <div><input id="customsearch" type="tex" class="form-control" v-model="search" placeholder="search"></div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12x  margin-top-58 margin-top-83">
            <div class="with-margin-bottom" v-show="!loader && showAddBtn.isAdmin">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
            </div>

            <!-- GALLERY -->
            <div class="dflex flex-wrap block-mobile">
                <div class="gallery vid-thumbnail animate fadeInUp"  v-for="(val, index) in filterVid" :key="index">
                    <div class="content padding-15 b-radius vid-gallery">
                        <div class="close vid-close" @click="deleteVid(val.vidpath, index)" v-if="showAddBtn.isAdmin" >&times;</div>
                       <video width="100%" controls controlsList="nodownload">
                            <source :src="'storage/app/'+val.vidpath" type="video/mp4">
                            <!-- <source src="movie.ogg" type="video/ogg"> -->
                            Your browser does not support the video tag.
                        </video>
                        <hr id="narrow-margin">
                        <!-- {{val.title}} -->
                         <div class="mdb-form-field" style="margin: 0;">
                            <div class="form-field__control">
                                <input :id="val.vidID" type="text" class="form-field__input" :value="val.title || ''" name="position" style="margin: 0; border:0;" :disabled="!showAddBtn.isAdmin" @blur="handleBlur">
                                <!-- <labelss class="form-field__label">Position</label> -->
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                        <!-- <input type="text" :value="val.title || ''" /> -->
                    </div>

                </div>

            </div>
            <!-- Modal -->
            <div id="myVideo" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">

                        </div>
                    </div>
                </div>
            </div>
            <!-- END MODAL -->
            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <form method="post" >
                                <div class="col-lg-12">
                                    <div class="mdb-form-field form-group-limitx">
                                        <div class="form-field__control">
                                            <input type="text" v-model="title" class="form-field__input" name="title" v-validate="'required'">
                                            <label class="form-field__label">Video Title</label>
                                            <div class="form-field__bar"></div>
                                        </div>
                                        <span class="errors">{{ errors.first('title') }}</span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-lg-12">
                                    <div class="dflex">
                                        <div class="file-upload mb-3">
                                            <input type="file" class="custom-file-input" id="file" name="filename" @change="validateVid" style="display: none;">
                                            <label class="btn btn-primary" for="file">Choose file</label>
                                        </div>
                                        <div class="progress flexgrow-1">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                                                0%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-lg-12 modal-footer">
                                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addVid" :disabled="!isFormValid || disabled">
                                </div>
                                <div class="clearfix"></div>
                            </form>
                            <!-- <ManageEmployee></ManageEmployee> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MODAL -->

        </div>

    </div>
</template>

<script>
export default {
    data(){
        return{
            rows: [],
            loader: true,
            title: '',
            search: '',
            xlsBuffer: false,

            disabled: false,
        }
    },
    computed:{
        filterVid(){
	    	let self=this;
            let counter =1;

            return this.rows.filter(function (item) {

                return item.title.toLowerCase().indexOf(self.search.toLowerCase()) >= 0; // && counter++ <=3;
                // return Object.values(item).map(function (value) {
                //     return String(value).toLowerCase();
                // }).find(function (value) {
                //     return value.includes(self.searchname.toLowerCase());
                // });
            });
        },
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        showAddBtn(){
            return this.$root.userinfo;
        }
    },
    methods:{
        addVid(){

            this.xlsBuffer = true;
            this.disabled = true;

            let file = this.validateVid();
            if(!file)
            return alert('no file selected');
            this.moveProgressBar();

            const formData = new FormData();
            formData.append('video', file );
            formData.append('title', this.title);
            axios.post('api/addVid', formData)
            .then((response)=>{

                    this.rows.push({title: this.title, vidpath: response.data});
                    this.xlsBuffer = false;
                    this.disabled = false;
                console.log(response.data);
                console.log(this.rows);
                this.closeModal();
            })
            .catch((err)=>{
                console.log(err);
            });

        },
        handleBlur(e){
            // console.log(e.target.id, e.target.value);
            let formData = {vidID: e.target.id, title: e.target.value};
            axios.post('api/updateVid', formData)
            .then((response)=>{
            })
            .catch((err)=>{
                console.log(err);
            });
        },
        deleteVid(hex, index){
            let x = confirm("Are you sure you want to delete?");
            if(x)
            {
                let path =  hex.split("/")[2];
                axios.get('api/delVid/'+path)
                .then((response)=>{
                    this.rows.splice(index, 1);
                })
                .catch((err)=>{
                    console.log(err);
                });
            }
        },
        validateVid(){
			if(document.querySelector('input#file[type=file]').value != ''){
	        	// let preview = document.querySelector('img#avatar-lg'); //selects the query named img
			    let file    = document.querySelector('input#file[type=file]').files[0]; //sames as here

                let type = file['type'];

			    const validImageTypes = ['video/mp4'];
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid type');
				      return false;
                }
                return file;
			}else{

				return null;
			}
		},
        moveProgressBar(){
            let width = 10;
            let self = this;
            clearInterval(self.interVal);
            this.interVal = setInterval(frame, 80);

            function frame() {
                if (width > 100) {
                    document.querySelector('.progress-bar').innerHTML = 'Successfully Completed';
                    clearInterval(self.interVal);
                    self.$parent.$data.disableCloseModal= false;


                } else {
                    document.querySelector('.progress-bar').style.width = width+'%';
                    document.querySelector('.progress-bar').innerHTML = width+'%';
                    if(self.xlsBuffer && width >= 90)
                    {
                       width = width;
                    }else{
                        width++;
                    }

                }
            }

        },
        closeModal(){
            clearInterval(this.interVal);
            this.title = '';
            document.querySelector('.progress-bar').style.width = 10+'%';
            document.querySelector('.progress-bar').innerHTML = 0+'%';
            $("#myModal").modal("hide");
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
        }
    },
    beforeDestroy(){
        if(document.getElementById("content") !=  null)
        {
            document.querySelector("#content").classList += " content";
        }
        clearInterval(this.interVal);

	},
    mounted(){
        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

        this.MDBINPUT();
        // let width = 10;
        //     let self = this;
        //     clearInterval(self.interVal);
        //     this.interVal = setInterval(frame, 40);

        //     function frame() {
        //         if (width > 100) {
        //             document.querySelector('.progress-bar').innerHTML = 'Successfully Completed';
        //             clearInterval(self.interVal);
        //             self.$parent.$data.disableCloseModal= false;
        //         } else {
        //             document.querySelector('.progress-bar').style.width = width+'%';
        //             document.querySelector('.progress-bar').innerHTML = width+'%';
        //             if(self.xlsBuffer && width >= 90)
        //             {
        //                width = width;
        //             }else{
        //                 width++;
        //             }

        //         }
        //     }



        axios.get('api/getVid')
        .then((response)=>{
            this.loader = false;
            this.rows = response.data;
        })
        .catch((err)=>{console.log(err)});


        if(document.getElementById("content") !=  null)
        {
            document.querySelector("#content").classList.remove("content");
            // document.querySelector(".margin-top-48").classList += ' nopadding';
        }
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
