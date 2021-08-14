import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';
import {store} from './Store';
import Vuetify from 'vuetify';

Vue.use(Vuetify);
Vue.use(VeeValidate,{local: 'en'});

Vue.component('Datepicker', vuejsDatepicker);
Vue.directive('mask', VueMask.VueMaskDirective);

window.bus = new Vue;
Vue.use(VueRouter);

const app = new Vue({
    vuetify : new Vuetify(),
    store: store,
    el: '#root',
    data:{
        // name: 'Juan Dela Cruz',
        userinfo: [

        ],
        forms: [],
        editprofile:{
            oldpass: '',
            password: '',
            password_confirm: ''
        },
    },
    computed:{
        fullname(){
            // if(this.userinfo.length)
            if(Object.keys(this.userinfo).length > 0)
            return this.userinfo.fname+' '+this.userinfo.lname;
        },
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
    },
    watch:{

        userinfo(val, old){

            this.MDBINPUT();
        },


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

        MDBINPUTRESET(){
            let el = document.querySelectorAll('.mdb-form-field');
                el.forEach(element=>{
                element.classList.remove('form-field--is-filled');
            })
        },
        changePassword(){
            // GET USER INFO
            axios.post('api/changepass', {
                oldpass: this.editprofile.oldpass,
                password: this.editprofile.password,
                empID: this.userinfo.empID
            }).then((response)=>{
                if(response.data.hasOwnProperty('err')){
                    console.log(response.data.err);
                }
                else{
                    this.closeModal();
                }
            }).catch((err)=>{ console.log(err); });
        },

        closeModal(){
            $("#profileModal").modal("hide");
        },
        hideMobileNav(){
            if($("body").hasClass("backdrop")){
                $('header#main-header').hide();
    		    $('body').removeClass('backdrop');
            }
        }

        // leave credits

    },
    created(){
        axios.get('api/getempinfo').then((response)=>{
            if(response.data.length>0){
                this.userinfo = response.data[0];
            }else{
                this.userinfo['avatar'] = null;
            }

        }).catch((err)=>{
            console.log(err);
        });





    },
    mounted(){
        // NOTIFS
        /* setInterval(()=>{
            // LEAVES
            axios.get('api/get-leave-credits').then(response=>{
                console.log(response.data);
            });

        }, 5000); */

    },
    router: new VueRouter(routes)

});


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