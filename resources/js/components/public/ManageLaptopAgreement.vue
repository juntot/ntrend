<template>
    <div>
        
        <form method="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">LAPTOP REQUEST FORM</span></h3>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="datefiled" name="date request" :readonly="true">
                                <label class="form-field__label">Date request</label>
                                <div class="form-field__bar"></div>
                        </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-limit">
                          <Datepicker :disabled="$parent.disabledinput" :value="dateneed" @selected="selectDateNeed" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                          <label slot="afterDateInput" class="form-field__label">Date Needed</label>
                          <div slot="afterDateInput" class="form-field__bar"></div>
                          <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                          </Datepicker> 		
              </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input type="text" class="form-field__input"  :value="userinfo.fullname" name="name" :readonly="true">
                                <label class="form-field__label">Name</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="userinfo.posname" name="position" :readonly="true">
                                <label class="form-field__label">Position</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="userinfo.deptname" name="department" :readonly="true">
                                <label class="form-field__label">Department</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="userinfo.branchname" name="branch" :readonly="true">
                                <label class="form-field__label">Branch</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-12">
                <h5 class="form-subtitle"><em>LAPTOP DETAILS</em></h5>
            </div>
            <div class="col-md-4 col-lg-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="suggestedbrand" name="suggested brand">
                                <label class="form-field__label">Suggested Brand</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-8 col-lg-8">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="suggestedmodel" name="suggested model">
                                <label class="form-field__label">Suggested Model</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-12">
                <h5 class="form-subtitle"><em>IT SECTION (Recommendation)</em></h5>
            </div>
            <div class="col-md-12">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input :disabled="submitBtn || cancelBtn || !isITapprover" type="text" class="form-field__input" v-model="brand" name="brand">
                                <label class="form-field__label">Brand</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input :disabled="submitBtn || cancelBtn || !isITapprover" type="text" class="form-field__input" v-model="model" name="model">
                                <label class="form-field__label">Model</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input :disabled="submitBtn || cancelBtn || !isITapprover" type="text" class="form-field__input" v-model="supplier" name="supplier">
                                <label class="form-field__label">Supplier</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                          <Datepicker :disabled="submitBtn || cancelBtn || !isITapprover" :value="datereleased" @selected="selectDateReleased" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                          <label slot="afterDateInput" class="form-field__label">Date Released</label>
                          <div slot="afterDateInput" class="form-field__bar"></div>
                          <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                          </Datepicker> 
                    </div>
            </div>
            <div class="col-md-12">
                <h5 class="form-subtitle"><em>HR SECTION</em></h5>
            </div>
            <div class="col-md-12">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input :disabled="submitBtn || cancelBtn || !isHRapprover" type="text" class="form-field__input" v-model="amount" name="amount">
                                <label class="form-field__label">Amount</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                                <input :disabled="submitBtn || cancelBtn || !isHRapprover" type="text" value="Deduction Per PayDay" class="form-field__input" v-model="computation" name="computation">
                                <label class="form-field__label">Computation</label>
                                <div class="form-field__bar"></div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                          <Datepicker :disabled="submitBtn || cancelBtn || !isHRapprover" :value="dateagreement" @selected="selectDateAgreement" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                          <label slot="afterDateInput" class="form-field__label">Date finalized agreement</label>
                          <div slot="afterDateInput" class="form-field__bar"></div>
                          <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                          </Datepicker> 
                    </div>
            </div>
            <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                          <Datepicker :disabled="submitBtn || cancelBtn || !isHRapprover" :value="loanencoded" @selected="selectDateLoanEncoded" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                          <label slot="afterDateInput" class="form-field__label">Date Loan Encoded</label>
                          <div slot="afterDateInput" class="form-field__bar"></div>
                          <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                          </Datepicker> 
                    </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12" v-if="!$parent.disabledinput">
               <p class="text-info">
                    LIST OF APPROVERS: 
               </p>
               <div class="clearfix"></div>
               <span class="approverlist alert-info" v-for="(approver, index) in $parent.approvers" :key="index">{{approver.approvers}}</span>
               <br><br>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addLaptopRequest" :disabled="disabledIfNoApprover || isDisable || !isFormValid" v-if="submitBtn">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateLaptopRequest" :disabled="isDisable || !isFormValid" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteLaptopRequest" :disabled="isDisable" v-if="updateDeleteBtn && !this.$parent.$data.forapprover">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionLaptopRequest(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionLaptopRequest(2)" v-if="approveRejecBtn">
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionLaptopRequest(0)" v-if="cancelBtn"> -->
            </div>
        </form>

    </div>
</template>
<script>
export default {
    props: ['userinfo', 'approvertype'],
    data() {
		return {
			isDisable: false,
            datefiled: moment(new Date()).format('YYYY-MM-DD'),
            dateneed: moment(new Date()).format('YYYY-MM-DD'),
            datereleased: moment(new Date()).format('YYYY-MM-DD'),
            dateagreement: moment(new Date()).format('YYYY-MM-DD'),
            loanencoded: moment(new Date()).format('YYYY-MM-DD'),
            suggestedbrand: '',
            suggestedmodel: '',
            brand: '',
            model: '',
            supplier: '',
            amount: '',
            computation: '',
            laptopID: '',
            hrapprover: '',
			// reqstat: 0, //0 pending, 1//approve //2 declined
			// status: 0, //0 open, 1//close
			
		}
    },
    watch:{
        userinfo(val, old){
            let arr=[
                     'it', 'information', 'techonology', 
                     'hr', 'human',
                    ];
            let approver = '';
            arr.forEach((keywords, index)=>{
                if(val.deptname.toLowerCase().includes(keywords) && index <= 2)
                {
                    approver = 'it';
                }
                if(val.deptname.toLowerCase().includes(keywords) && (index > 2 && index <= 3 ))
                {
                    approver = 'hr';
                }
            });

            val['approvertype'] = approver;
            this.MDBINPUT();
        },
    },
    methods:{
        addLaptopRequest(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                axios.post('api/addLaptopRequest', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateLaptopRequest(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateLaptopRequest', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteLaptopRequest(){
            this.isDisable = true;
            axios.get('api/deleteLaptopRequest/'+this.laptopID).then((response)=>{
                this.$parent.deleteRow(this.laptopID);
            }).catch((err)=>{ console.log(err); });
        },
        // ACTIONS FOR LEAVE I.E APPROVE / REJECT / CANCEL
        requestActionLaptopRequest(status = null){
            let params =  this.$data;
            params['hrapprover'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$root.$data.userinfo.empID;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformLaptopRequest', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
            }
            
            this.MDBINPUT();
            $("#myModal").modal("show");
        },

        selectDateNeed(val){
            this.dateneed = moment(val).format('YYYY-MM-DD');
        },
        selectDateReleased(val){
            this.datereleased = moment(val).format('YYYY-MM-DD');
        },
        selectDateAgreement(val){
            this.agreement = moment(val).format('YYYY-MM-DD');
        },
        selectDateLoanEncoded(val){
            this.loanencoded = moment(val).format('YYYY-MM-DD');
        },
        closeModal(){
            let obj = this.$data;
            // this.MDBINPUTRESET();
            
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'dateneed' && key != 'datereleased' && key != 'dateagreement' && key != 'loanencoded'){
                    this.$data[key] =  '';
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                
            });
            // this.form.clear();
            // this.form.reset();
            // this.$validator.reset();
            // this.$validator.errors.clear();
            // this.$validator.resume();
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
            // [].forEach.call(
            //     document.querySelectorAll('.form-field__input, .form-field__textarea'),
            //     (el) => {           
            //         if(el.value !='')
            //         {
            //             // el.parentNode.parentNode.classList.remove('form-field--is-filled');
            //             // console.log(el.parentNode.parentNode.classList.remove('form-field--is-filled'));
            //         }
            //     }
            // );
        }

    },
    computed:{
        disabledIfNoApprover(){
            return this.$parent.$data.forapprover != 'approval' && this.$parent.approvers && this.$parent.approvers.length < 1;
        },
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        isITapprover(){
            return this.userinfo.approvertype=='it' && this.$parent.$data.forapprover == 'approval';
        },
        isHRapprover(){
            return this.userinfo.approvertype=='hr' && this.$parent.$data.forapprover == 'approval';
        },
        submitBtn()
        {
            return !this.laptopID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){

            // if(this.userinfo.approvertype == 'hr' && this.$parent.$data.forapprover == 'approval'){
            //     return false;
            // }
            if(this.userinfo.approvertype == 'it' && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel)
            {
                return true;
            }
            if(this.laptopID && this.$parent.$data.forapprover != 'approval' && !this.$parent.$data.isApprove){
                return true;
            }
            // if(!this.submitBtn && !this.cancelBtn && && !this.$parent.$data.isCancel){
            //     return true;
            // }            
        },
        approveRejecBtn(){
            return this.laptopID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel && this.userinfo.approvertype == 'hr';
        },
        cancelBtn(){
            return this.laptopID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel && this.userinfo.approvertype == 'hr';
        }
    },
    beforeDestroy(){
        bus.$off('setupdate', this.setDataForEdit)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);
        // $('#myModal').on("show.bs.modal", this.MDBINPUT);
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