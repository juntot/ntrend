// MANAGE COMP PROFILE
<template>
    <div class="c">
        <form method="post" enctype="multipart/form-data">
            <div class="col-lg-12">
                <div class="dflex">
                    <div class="file-upload mb-3">
                        <input type="file" class="custom-file-input" id="file-profile" @change="uploadXLS" name="images[]" style="display: none;" multiple>
                        <label class="btn btn-primary" for="file-profile">Choose file</label>
                    </div>
                    <div class="progress flexgrow-1" v-show="showProgress">
                        <div class="progress-bar progress-bar-striped active custom-progress-bar" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                            10%
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</template>

<script>
export default {
    props: ['policy'],
    data(){
        return{

            showProgress: false,
            xlsBuffer: false,
            interVal: '',

        }

    },
    methods:{

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
        async validate(e){

            if(document.querySelector('input#file-profile[type=file]').value != ''){
                let profile_id = this.policy.id;
                const validImageTypes = ['image/jpeg', 'image/png'];
                let count = 0;

                // let files    = document.querySelector('input#file-profile[type=file]').files[0];
                let files    = document.querySelector('input#file-profile[type=file]').files;
                let formData = new FormData();
                let remaining_formData = new FormData();
                let targetFiles = e.target.files;

                // if(files.length > 20){
                //     this.$parent.$data.disableCloseModal= false;
                //     alert('maximum number of files allowed is 20');
                //     clearInterval(this.interVal);
                //     return false;
                // }
                let totalFiles = targetFiles.length;
                let remainingFiles = (totalFiles % 10);
                let iterators = totalFiles - remainingFiles;

                // reset table and directory
                // if(totalFiles>0){
                //     let cleared = await this.clearProfileTable();
                //     if(!cleared){
                //         alert('Server Error');
                //         return;
                //     }
                // }

                for(var key in targetFiles){

                    if(targetFiles.length > count){
                        let type = targetFiles[count]['type'];
                        if (!validImageTypes.includes(type)) {
                            this.$parent.$data.disableCloseModal= false;
                            clearInterval(this.interVal);

                            document.querySelector('input#file-profile[type=file]').value = '';
                            alert('Invalid pdf type');
                            return false;
                        }
                    }
                    await formData.append('attachment[]', targetFiles[key]);
                    await formData.append('profile_id', profile_id);

                    if((count + 1) % 10 == 0){
                        this.postCompProfile(formData, false);
                        formData = new FormData();

                    }
                    if(iterators < count+1){
                        await remaining_formData.append('attachment[]', targetFiles[key]);
                        await remaining_formData.append('profile_id', profile_id);
                    }
                    // console.log(totalFiles, count, iterators);

                    count++;
                }

                this.postCompProfile(remaining_formData, true);
                return false;
                // return await formData;
            }
        },
        async clearProfileTable(){
            let profile_id = this.policy.id;
            try{
                await axios.post('api/add-compprofile', {clear: true, profile_id: profile_id});
                return true;
            }
            catch(err){
                return false;
            }


        },
        postCompProfile(files, isDone = false){


            axios.post('api/add-compprofile', files)
            .then(res=>{
                if(isDone){
                    this.xlsBuffer = false;
                    this.$parent.$data.isDisableModal = false;
                    // this.xlsBuffer = false;
                    document.querySelector('input#file-profile[type=file]').value = '';
                }

            })
            .catch(er=>{
                document.querySelector('input#file-profile[type=file]').value = '';
                alert('Server Error Occured...');
            });
        },
        async uploadXLS(e){
                this.$parent.$data.disableCloseModal = true;
                let self = this;
                this.showProgress = true;
                this.xlsBuffer = true;
                this.moveProgressBar();
                let file = await this.validate(e);

                // if(file){

                    // axios.post('api/addPaySlip', file)
                    // .then(res=>{
                    // // this.userinfo.avatar = res.data.avatar;
                    //     this.xlsBuffer = false;
                    //     document.querySelector('input#file-profile[type=file]').value = '';
                    // })
                    // .catch(er=>{
                    //     document.querySelector('input#file-profile[type=file]').value = '';
                    //     alert('Server Error Occured...');
                    // });
                // }

        },
        resetProgressBar(){
            let width = 10;
            document.querySelector('.progress-bar').style.width = width+'%';
            document.querySelector('.progress-bar').innerHTML = width+'% ';
            document.querySelector('input#file-profile[type=file]').value = '';
        },
        closeModal(){
            this.showProgress =  false;
            this.$parent.$data.disableCloseModal = false;
            clearInterval(this.interVal);
            this.resetProgressBar();
        },

    },
    mounted(){

        $('#modalPolicy').on("hidden.bs.modal", this.closeModal);
        this.MDBINPUT();
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
