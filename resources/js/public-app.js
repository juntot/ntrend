import Vue from 'vue';
import VueRouter from 'vue-router';
import { VTooltip, VPopover, VClosePopover } from 'v-tooltip'
import routes from './public-routes';
import {store} from './Store';
import Vuetify from 'vuetify'

Vue.use(Vuetify);
Vue.use(VeeValidate,{local: 'en'});
// Vue.use(VTooltip);
Vue.directive('tooltip', VTooltip);
Vue.component('v-popover', VPopover);
Vue.directive('close-popover', VClosePopover);
Vue.component('Datepicker', vuejsDatepicker);
Vue.directive('mask', VueMask.VueMaskDirective);
// Vue.component('v-popover', VTooltip);
window.bus = new Vue;
// const bus = new Vue;
Vue.use(VueRouter);
// moment().tz("Asia/Manila").format();
moment.tz.setDefault("Asia/Manila");

const app = new Vue({
    store: store,
    el: '#root',
    vuetify: new Vuetify(),
    data:{
        // name: 'Juan Dela Cruz',
        // local: window.location.origin,
        baseURL: '',
        userinfo: [],
        forms: [],
        policy_nav:[],
        comprofile_nav: [],

        activeform: '',
        formnavapproval: [],
        witnessnav: '',
        hasReports : false,
        approvertype: '',
        editprofile:{
            oldpass: '',
            password: '',
            password_confirm: ''
        },
        leaveCredits: '',
        formNotifs: [],
        postNotifs: [],
        witnessNotifs: 0,
    },
    computed:{
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        hasForApproval(){
            return Object.keys(this.formnavapproval).length > 0;
        },
        hasForWitness(){
            return this.witnessnav.length;
        },
    },
    watch:{

        // forms(val, old){
        //     let formactive = (this.$router.currentRoute.path).slice(1);
        //     val.forEach((item)=>{
        //         if(((item.navname).replace(/\s+/g, '-').toLowerCase()) == formactive)
        //         this.activeform = item.formtitle;
        //     });
        // }
        userinfo(val, old){
            // set type of approver
            // let arr=[
            //          'it', 'information', 'techonology',
            //          'hr', 'human',
            //         ];
            // let approver = '';
            // arr.forEach((keywords, index)=>{
            //     if(val.deptname.toLowerCase().includes(keywords) && index <= 2)
            //     {
            //         approver = 'it';
            //     }
            //     if(val.deptname.toLowerCase().includes(keywords) && (index > 2 && index <= 3 ))
            //     {
            //         approver = 'hr';
            //     }
            // });

            // set fullname
            //return
            val['fullname'] = val.fname+' '+val.lname;
            // val['approvertype'] = approver;
            this.MDBINPUT();
        },


    },
    methods:{
        // selectedForm(title = null){
        //     this.activeform = title;
        // }
        hardRefresh(){
            window.location.reload(true);
        },
        showMobileNav(){
            $('.left-Side-Bar').addClass('left-Side-Bar-show');
        },
        hideMobileNav(){
            window.scrollTo(0, 0);
            $('.left-Side-Bar').removeClass('left-Side-Bar-show');
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
        updateNotifCountForm(className=''){
            this.addClassBody(className);
            axios.post('api/update-form-notifs',{email: this.userinfo.email})
            .then(res=>{
                this.formNotifs.count_pending = 0;
            }).catch(err => console.log(err));
        },
        updateNotifCountPost(className=''){
            this.addClassBody(className);
            axios.post('api/update-post-notifs',{empID: this.userinfo.empID})
            .then(res=>{
                this.postNotifs.count_pending = 0;
            }).catch(err => console.log(err));
        },
        addClassBody(newClass = ''){
            $('body').addClass(newClass);

        },
        async updateAvatar(){
             //selects the query named img

            let file = await this.validateAvatar();
            if(file){
                const formData = new FormData();
                formData.append('image', file );
                axios.post('api/update_emp_avatar', formData)
                .then(res=>{
                    // this.userinfo.avatar = res.data.avatar;
                    let reader  = new FileReader();
				          reader.onloadend =  ()=>{
                            // this.userinfo.avatar = reader.result;
                            // $('avatar-md').src = reader.result;
                            let preview = document.querySelector('img#avatar-md'); //selects the query named img
                            let postavatar = document.querySelector('img.avatar-status');
                            preview.src = reader.result;
                            postavatar.src = reader.result;

				     }
				     reader.readAsDataURL(file);
                })
                .catch(er=>console.log(er));
            }
        },
        validateAvatar(){
			if(document.querySelector('input#file[type=file]').value != ''){
	        	let preview = document.querySelector('img#user-avatar-profile-md'); //selects the query named img
			    let file    = document.querySelector('input#file[type=file]').files[0]; //sames as here

			    let type = file['type'];
			    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid Image type');
				      return false;
				}
				if(file.size >= 2000000)
				{
                    alert('Filesize exceed 2MB');
                    document.querySelector('input#file[type=file]').value = '';
				    return false;
				}

				if (file) {
				    let reader  = new FileReader();
				          reader.onloadend = function () {
                          preview.src = reader.result;
				     }
				     reader.readAsDataURL(file);
                     return file;

				}
			}else{
                document.querySelector('input#file[type=file]').value != '';
				return null;
			}
		},
    },
    filters:{
        leaveCreditFilter: (value) => {
            if (!value) return '00'
            value = '0'+value;
            // return this.leaveCredits;
            // value = this.leaveCredits[value] || this.userinfo[value]
            // value = '0'+value;
            return value.slice(-2);
        },
        moment: function (date) {
    		return moment(date).fromNow();
  		}
    },
    async created(){
        
        let reqUserInfo = false;
        // GET USER INFO
        const callUser = async () => {
            await axios.get('api/getempinfo').then((response)=>{
                if(response.data){
                    this.userinfo = response.data[0];
                    reqUserInfo = true;
                }
                
                // form notif
                return axios.get('api/get-form-notifs')
            }).then(response => {
                this.formNotifs = response.data;
                // post notif
                return axios.get('api/get-post-notifs')
            }).then(response => {
                this.postNotifs = response.data;
                return true;
            })
            .catch((err)=>{ console.log(err); return false;});
        }

        await callUser();

        // GET FORMS BY USER
            // axios.get('api/getuserform').then((response)=>{
            //     this.forms = response.data;
            // }).catch((err)=>{ console.log(err); });
        await axios.get('api/get-form-nav')
        .then((response)=>{
            this.forms = response.data;
        }).catch((err)=>{ console.log(err); });


         // GET WITNESS NAV
         await axios.get('api/get-witness-nav')
         .then(res=>{
             this.witnessnav = res.data;
         })
         .catch(er=>alert('Server Error Witness Nav'));




        //GET FORMS NAV FOR APPROVAL
            // axios.get('api/getformnavapproval').then((response)=>{
            //     this.formnavapproval = response.data;
            // }).catch((err)=>{ console.log(err); alert('error occured make sure to reload the page');});
        await axios.get('api/get-form-nav-approver').then((response)=>{
            // this.formnavapproval = response.data;
            // console.log(response.data);

            let mynotif={
                fromnavapproval: response.data,
                witness: this.witnessnav.length > 0? true: false,
            };
            return axios.post('api/notif_center', mynotif)

        })
        .then(res=>{
            // console.log('taeeeeeee...', res.data.approval_notif)
            this.formnavapproval = res.data.approval_notif;
            this.leaveCredits = res.data.leave_cred[0];
            this.formNotifs = res.data.form;
            this.postNotifs = res.data.post;
            this.witnessNotifs = res.data.witness.length > 0 ? res.data.witness[0].count : 0;
        })
        .catch((err)=>{ console.log(err); alert('error occured make sure to reload the page');});

        //CHECK IF HAS REPORT
        await axios.get('api/hasreports').then((response)=>{
            if(response.data.length > 0){
                this.hasReports = true;
            }

        }).catch((err)=>{ console.log(err); });


        // GET POLICY
        await axios.get('api/get-policy-nav')
        .then(res=>{
            this.policy_nav = res.data;
        })
        .catch(er=>alert('Server Error'));

        // GET COMP PROFILE
        await axios.get('api/get-compprofile-nav')
        .then(res=>{
            this.comprofile_nav = res.data;
        })
        .catch(er=>alert('Server Error'));

        // hide loader
        $(()=>{
            if(reqUserInfo){
                $('#loader').hide();
                
            } else {
                let counter = 1;
                let interval = setInterval(async ()=>{
                    let result = await callUser();
                    if(result || counter == 3){
                        clearInterval(interval);
                    }
                    if(counter == 3 ) {
                        $('body').prepend(`
                            <div id="network-failure" class="text-center">
                                <article>
                                    <div>
                                        <h1>&rsquo;500 Error!</h1>
                                        <div>
                                            <p>Sorry for the inconvenience, A network error occured.</p>
                                            <p>&mdash; Please check your internet connection or contact your IT-Team</p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        `);
                        $('#network-failure').show();
                        $('#root').hide();
                    }
                    counter ++;
                }, 1000);
                
                
            }
        });


    },
    mounted(){

        // moment().tz("Asia/Manila").format();

        // console.log(moment().format('MM/DD/YYYY hh:mm A'));
        this.MDBINPUT();

        // NOTIFS
        setInterval(()=>{
            // LEAVES
           /* axios.get('api/get-leave-credits').then(response=>{
                if((response.data).length > 0){
                    this.leaveCredits = response.data[0];
                }
            });

            axios.get('api/get-form-notifs')
            .then(response => {
                this.formNotifs = response.data;
                return axios.get('api/get-post-notifs')
            }).then(response=>{
                this.postNotifs = response.data;
            })
            .catch( err => console.log(err));
            */
            let mynotif={
                fromnavapproval: this.formnavapproval,
                witness: this.witnessnav.length > 0? true: false,
            };

            axios.post('api/notif_center', mynotif)
            .then(res=>{
                this.leaveCredits = res.data.leave_cred[0];
                this.formNotifs = res.data.form;
                this.postNotifs = res.data.post;
                this.witnessNotifs = res.data.witness.length > 0 ? res.data.witness[0].count : 0;
            })
            .catch(er=>console.log('err notif center..'));

        }, 32000);



        $("#profileModal").on('show.bs.modal', function(){
            $('body').addClass('hide-backdrop');
        });
        $("#profileModal").on('hidden.bs.modal', function(){
            $('body').removeClass('hide-backdrop');
        });
    },
    router: new VueRouter(routes)

});

localStorage.setItem("base_url", window.location.origin);

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