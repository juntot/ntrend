<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div class="setting-container">
        <!-- <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div> -->
        <div class="col-lg-12 header-title">
            <h4>eFORM TERMS & CONDITIONS</h4>
        </div>
        <div class="col-lg-12 summernote">
            <div id="summernote"></div>
            <div class="text-left">
                <button class="btn btn-primary" @click.prevent="saveTerms">Save</button>
            </div>
        </div>




    </div>
</template>

<script>
export default {

    data(){
        return{
            // markup: '',
        }
    },
    methods:{
        saveTerms(){
            let markup = $('#summernote').summernote('code');
            axios.post('api/updateTerms', {
                markup: markup
            })
            .then(res=>{ })
            .catch(er=>{
                alert('An error occured while updating eforms terms & conditions. Please try again.');
            })
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
        // $('article').removeClass('bgc-white');
        $('#summernote').summernote('destroy');
    },
    mounted(){
        $(document).ready(async ()=>{
            await $('#summernote').summernote(
                {
                placeholder: 'start editing here..',
                tabsize: 2,
                height: 120,
                toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
                ]
            }
            );
            axios.get('api/getTermsCode')
            .then(res=>{
                if(res.data.length > 0){
                    let markup = res.data[0] ;
                    $('#summernote').summernote('code', markup.markup);
                }

            })
            .catch(er=>{});

        });
        $('article').css({"min-height": '90%'});
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
