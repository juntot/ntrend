<template>
    <div>
       <form method="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">CALLING CARD REQUEST</span></h3>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="datefiled" name="name" readonly="true">
                        <label class="form-field__label">Date Filed</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedfname" name="middlename" readonly="true">
                        <label class="form-field__label">First Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedlname" name="lastname" readonly="true">
                        <label class="form-field__label">Last Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedposname" name="position" readonly="true">
                        <label class="form-field__label">Position</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedbranchname" name="branch" readonly="true">
                        <label class="form-field__label">Branch</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedMobile" name="mobile1" readonly="true">
                        <label class="form-field__label">Mobile 1</label>
                        <div class="form-field__bar"></div>
                        <span class="errors">{{ errors.first('mobile1') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="mobile2" name="mobile2" >
                        <label class="form-field__label">Mobile 2</label>
                        <div class="form-field__bar"></div>
                        <span class="errors">{{ errors.first('mobile2') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="computedEmail" name="email" readonly="true">
                        <label class="form-field__label">Email</label>
                        <div class="form-field__bar"></div>
                        <span class="errors">{{ errors.first('email') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="product" v-validate="'required'" name="product/brand">
                        <label class="form-field__label">Products Brand Handling</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('product/brand') }}</span>
                </div>
            </div>

            <div class="col-md-12">
                <h5 class="form-subtitle">
                    <em>PLease select type of card</em>
                </h5>
            </div>
            <div class="col-md-12">
                <div class="col-md-4 col-xs-6">
                    <div class="group">
                        <label class="mdblbl">
                        <span class="checklbl">NTMC</span>
                        <input :disabled="$parent.disabledinput" type="checkbox" v-model="ntmc" value="1">
                        <span class="mdbcheckmark"></span>
                        </label>
                    </div>
                    <div class="group">
                        <label class="mdblbl">
                        <span class="checklbl">APBW</span>
                        <input :disabled="$parent.disabledinput" type="checkbox" v-model="apbw" value="0">
                        <span class="mdbcheckmark"></span>
                        </label>
                    </div>

                </div>
                <div class="col-md-4 col-xs-6">
                    <div class="group">
                        <label class="mdblbl">
                        <span class="checklbl">PHILCREST</span>
                        <input :disabled="$parent.disabledinput" type="checkbox" v-model="philcrest" value="1">
                        <span class="mdbcheckmark"></span>
                        </label>
                    </div>
                    <div class="group">
                        <label class="mdblbl">
                        <span class="checklbl">TYREPLUS</span>
                        <input :disabled="$parent.disabledinput" type="checkbox" v-model="tyreplus" value="1">
                        <span class="mdbcheckmark"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="group">
                        <label class="mdblbl">
                        <span class="checklbl">SOLID</span>
                        <input :disabled="$parent.disabledinput" type="checkbox" v-model="solid" value="1">
                        <span class="mdbcheckmark"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h5 class="form-subtitle">
                    <em>FOR APPROVER</em>
                </h5>
            </div>
            <!-- status is rejected or approve -->
            <div v-if="$parent.disabledinput">
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="$parent.$data.forapprover != 'approval'" class="form-field__textarea" id="" cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
                                <label class="form-field__label">Remarks</label>
                                <div class="form-field__bar"></div>
                            </div>
                    </div>
                </div>
                <div class="col-lg-12" v-if="$parent.disabledinput">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="!$parent.$data.forapprover == 'approval'" type="text" class="form-field__input" :value="approvedby" name="branch" readonly="true">
                            <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Approved/Rejected By' }}</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12" v-if="!$parent.disabledinput">
                    <p class="text-info">
                        LIST OF APPROVERS:
                    </p>
                    <div class="clearfix"></div>
                    <span class="approverlist alert-info" v-for="(approver, key) in $parent.approvers" :key="key">{{approver.approvers}}</span>
                    <br><br>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addCard" :disabled="disabledIfNoApprover || isDisable || !isFormValid || !isBtnValid" v-if="submitBtn">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateCard" :disabled="isDisable || !isFormValid || !isBtnValid" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteCard" :disabled="isDisable" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionCard(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionCard(2)" v-if="approveRejecBtn">
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionCard(0)" v-if="cancelBtn"> -->
            </div>
        </form>

    </div>
</template>
<script>
let check = ['<i class="fas fa-times"></i>', '<i class="fas fa-check"></i>'];
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {

        empID_: '',
        datefiled: moment(new Date()).format('MM/DD/YYYY'),
        ntmc: 1,
        apbw: 0,
        philcrest: 0,
        tyreplus: 0,
        solid: 0,
        mobile: '',
        mobile2:'',
        product: '',
        isDisable: false,
        ccID:'',
        approvedby: '',
        remarks: '',
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    // filters:{
    //     customDateFormat: function (value) {
    //         if (!value) return ''
    //         value = new Date(value);
    //         return moment(value).format('MM/DD/YYYY');
    //     }
    // },
    methods:{
        addCard(){
            if(this.isFormValid){
                this.isDisable = true;
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addcallingcard', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                });
            }
        },
        updateCard(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updatecallingcard', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteCard(){
            this.isDisable = true;
            axios.get('api/deletecallingcard/'+this.ccID).then((response)=>{
                this.$parent.deleteRow(this.ccID);
            }).catch((err)=>{ console.log(err); });
        },
        // ACTIONS FOR CALLING CARD I.E APPROVE / REJECT / CANCEL
        requestActionCard(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$root.$data.userinfo.empID;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformCallingCard', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'ntmc' || i == 'apbw' || i == 'philcrest' ||
                   i == 'tyreplus' || i == 'solid'){
                        this.$data[i] = (check.indexOf(data[i]));
                   }


            }

            this.MDBINPUT();
            $("#myModal").modal("show");
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
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'datestart' && key != 'dateend'){
                    this.$data[key] =  '';
                }
                if(key == 'datefiled'){
                    this.$data[key] =  moment(new Date()).format('MM/DD/YYYY');
                }
                if(key == 'isDisable'){
                    this.$data[key] = false;
                }
                if(key == 'apbw' || key == 'philcrest' ||
                    key == 'tyreplus' || key == 'solid'){
                    this.$data[key] = 0;
                }
                if(key == 'ntmc')
                {
                    this.$data[key] = 1;
                }
            });
            $("#myModal").modal("hide");
        },
    },
    computed:{
        disabledIfNoApprover(){
            return this.$parent.$data.forapprover != 'approval' && this.$parent.approvers && this.$parent.approvers.length < 1;
        },
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        isBtnValid(){
            let data = this.$data;
            for(let key in data){
                if(key == 'ntmc' && data.ntmc ||
                    key == 'apbw' && data[key] ||
                    key == 'philcrest' && data[key] ||
                    key == 'tyreplus' && data[key] ||
                    key == 'solid' && data[key] ){

                    return true;
                }
            }
            return false;
        },
        submitBtn()
        {
            return !this.ccID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.ccID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput
        },
        approveRejecBtn(){
            return this.ccID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.ccID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedfname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fname  : this.userinfo.fname;
        },
        computedlname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.lname  : this.userinfo.lname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        },
        // computedmobile(){
        //     return this.selected.hasOwnProperty('fullname') ? this.selected.mobile1  : this.userinfo.mobile1;
        // },
        computedMobile(){
            this.mobile = this.selected.hasOwnProperty('fullname') ? this.selected.mobile  : this.userinfo.mobile;
            return this.selected.hasOwnProperty('fullname') ? this.selected.mobile  : this.userinfo.mobile;
        },
        computedEmail(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.email  : this.userinfo.branchname;
        },

    },
    beforeDestroy(){
        bus.$off('setupdate', this.test)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

        // EVENT BUS
        bus.$on('setupdate', this.setDataForEdit);

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