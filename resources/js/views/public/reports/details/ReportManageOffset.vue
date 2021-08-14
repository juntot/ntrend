<template>
    <div>
            <form method="post" >
                <h3 class="text-center form-title"><span class="dblUnderlined">WORK OFF-SET FORM</span></h3>
                <div class="col-md-12">
                    <div class="mdb-table-overflow">
                        
                            <table width="100%" class="table table-hover mdb-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="200px">DATE</th>
                                            <th class="text-center">TIME IN</th>
                                            <th class="text-center">TIME OUT</th>
                                            <th class="text-center">REASON</th>
                                        </tr>
                                        <!-- <tr v-if="!$parent.disabledinput">
                                            <td>
                                                <Datepicker :value="offsetdate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker inline-input" @selected="selectOffsetDate" :typeable="false" format="yyyy-MM-dd">
                                                    <div slot="afterDateInput" class="form-field__bar"></div>
                                                </Datepicker>
                                            </td>
                                            <td>
                                                <div class="mdb-form-field form-group-limit">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="time" name="timein" v-model="timein" v-validate="'is_time|required'" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('timein') }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mdb-form-field form-group-limit">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="time" name="timeout" v-model="timeout" v-validate="'is_time|required'" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('timeout') }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mdb-form-field">
                                                    <div class="form-field__control">
                                                        <input type="text" class="form-field__input inline-input" v-model="reason" v-validate="'required'" name="reason" @keydown.enter.prevent="appendTable">
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('reason') }}</span>
                                                </div>
                                               
                                            </td>
                                        </tr> -->
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in entries" :key="index">
                                            <td>
                                                <div>
                                                    <span style="padding: 0 12px;">
                                                        <a v-if="!$parent.disabledinput" @click="removeRow(index)"><i class="fas fa-trash text-danger"></i></a>
                                                    </span>
                                                    {{item.offsetdate | customDateFormat}}
                                                </div>
                                            </td>
                                            <td>
                                                {{item.timein}}
                                            </td>
                                            <td>
                                                {{item.timeout}}
                                            </td>
                                            <td>
                                                {{item.reason}}
                                            </td>
                                        </tr>
                                            
                                    </tbody>
                            </table>
                        
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Company</em></h5>
                </div>
                <div class="col-md-12">
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">NTMC</span>
                                    <input type="radio" v-model="brand" value="1" name="brand" checked="checked">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">APBW</span>
                                    <input type="radio" v-model="brand" value="2" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">PHILCREST</span>
                                    <input type="radio" v-model="brand" value="4" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">TYREPLUS</span>
                                    <input type="radio" v-model="brand" value="3" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Requested By</em></h5>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true"  type="text" class="form-field__input" :value="datefiled" name="datefiled">
                                <label class="form-field__label">Date filed</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                </div>
                <div class="col-md-8">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" :value="userinfo.empID" name="idno">
                                <label class="form-field__label">ID no</label>
                                <div class="form-field__bar"></div>
                                <span class="errors">{{ errors.first('idno') }}</span>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="userinfo.fullname" name="name" readonly="true">
                                <label class="form-field__label">Name</label>
                                <div class="form-field__bar"></div>
                                <span class="errors">{{ errors.first('name') }}</span>
                            </div>
                        </div>
                </div>
            
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="userinfo.posname" name="position" readonly="true">
                                <label class="form-field__label">Position</label>
                                <div class="form-field__bar"></div>
                                <span class="errors">{{ errors.first('position') }}</span>
                            </div>
                        </div>
                </div>
                <div class="col-md-4">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" class="form-field__input" :value="userinfo.branchname" name="branch" readonly="true">
                                <label class="form-field__label">Branch</label>
                                <div class="form-field__bar"></div>
                                <span class="errors">{{ errors.first('branch') }}</span>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <!-- status is rejected or approve -->
                <div v-if="$parent.disabledinput">
                    <div class="col-lg-12">
                        <h5 class="form-subtitle"></h5>
                        <div class="mdb-form-field">
                                <div class="form-field__control mdb-bgcolor">
                                    <textarea :disabled="true" class="form-field__textarea" cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
                                    <label class="form-field__label">Remarks</label>
                                    <div class="form-field__bar"></div>
                                </div>
                        </div>
                    </div>
                    <div class="col-lg-12" v-if="$parent.disabledinput">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" :value="approvedby" name="branch" readonly="true">
                                <label class="form-field__label">{{selected.status == 'Approved' ? 'Approved By': selected.status == 'Rejected'? 'Rejected By': 'Approved/Rejected By' }}</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-12" v-if="!$parent.disabledinput">
                    <p class="text-info">
                        LIST OF APPROVERS: 
                    </p>
                    <div class="clearfix"></div>
                    <span class="approverlist alert-info" v-for="approver in $parent.approvers">{{approver.approvers}}</span>
                    <br><br>
                </div> -->
                <div class="clearfix"></div>
                    <div class="modal-footer">
                        <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addOffset" :disabled="isDisable || !isFormValid || !hasEntries" v-if="submitBtn">
                        <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateOffset" :disabled="isDisable || !isFormValid || !hasEntries" v-if="updateDeleteBtn">
                        <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteOffset" :disabled="isDisable" v-if="updateDeleteBtn">
                        <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionOffset(1)" v-if="approveRejecBtn">
                        <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionOffset(2)" v-if="approveRejecBtn">
                        <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionOffset(0)" v-if="cancelBtn"> -->
                </div>
            </form>
    </div>
</template>
<script>
let brand = ['NTMC', 'APBW', 'PHILCREST', 'TYREPLUS'];
let status = ['Pending', 'Approved', 'Rejected'];
let regex = new RegExp("^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9])|(24:?00))");
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
            
			isDisable: false,
            datefiled: moment(new Date()).format('YYYY-MM-DD'),
            offsetdate: moment(new Date()).format('YYYY-MM-DD'),
            timein: moment(new Date()).format('HH:mm'),
            timeout: moment(new Date()).add(30, 'minutes').format('HH:mm'),
            entries: [],
            brand: 1,
            reason: '',
            offsetID: '',
            approvedby: '',
            remarks: '',
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    filters:{
        customDateFormat: function (value) {
            if (!value) return ''
            value = new Date(value);
            return moment(value).format('MM/DD/YYYY');
        }
    },
    methods:{
        addOffset(){
            if(this.isFormValid)
            {
                
                let params = this.$data;
                axios.post('api/addOffset', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateOffset(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateOffset', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteOffset(){
            this.isDisable = true;
            axios.get('api/deleteOffset/'+this.offsetID).then((response)=>{
                this.$parent.deleteRow(this.offsetID);
            }).catch((err)=>{ console.log(err); });
        },
        appendTable(){
                this.$validator.validate('timein', this.timein);
                this.$validator.validate('timeout', this.timeout);
                this.$validator.validate('reason', this.reason);

                if(regex.test(this.timein) && regex.test(this.timeout) && this.reason !='')
                {
                    this.entries.push({ 
                        offsetdate: this.offsetdate, timein: moment(this.timein, ["HH:mm"]).format('hh:mm A'), 
                        timeout: moment(this.timeout, ["HH:mm"]).format('hh:mm A'), reason: this.reason
                    });
                                         
                }

        },
        removeRow(index)
        {
            this.entries.splice(index, 1);
        },
        // ACTIONS FOR Offset I.E APPROVE / REJECT / CANCEL
        requestActionOffset(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$root.$data.userinfo.empID;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformOffset', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable' && i != 'offsetdate')
                    this.$data[i] = data[i];
                if(i == 'brand')
                    this.$data[i] = (brand.indexOf(data[i]) + 1);
            }
            
            this.MDBINPUT();
            $("#offsetModal").modal("show");
        },
        selectOffsetDate(val){
            this.offsetdate = moment(val).format('YYYY-MM-DD');
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key == 'witnesses' || key == 'offsetID'){
                    this.$data[key] = '';
                }
                if(key == 'brand')
                {
                    this.$data[key] = 1;
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                if(key === 'entries')
                {
                    this.$data[key] = [];
                }
                
            });
            
            $("#offsetModal").modal("hide");
            
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
    computed:{
        isFormValid(){
            return !Object.keys(this.fields).some((key )=>{ 
                if(key != 'timein' && key != 'timeout' && key != 'reason')
                {
                   return this.fields[key].invalid
                }
            });
        },
        hasEntries(){
            return this.entries.length > 0;
        },
        submitBtn()
        {
            return !this.offsetID && this.true;
        },
        updateDeleteBtn(){
            return this.offsetID && this.true && !this.$parent.disabledinput 
        },
        approveRejecBtn(){
            return this.offsetID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.offsetID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        },
        computedID(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.empID_  : this.userinfo.empID;
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedposname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.posname  : this.userinfo.posname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        }
    },
    created(){
        VeeValidate.Validator.extend('is_time', {
            getMessage: field => `The format must be HH:MM AM/PM`,
            validate: (value) => new Promise(resolve => {
                // let regex = new RegExp("([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])");
                // let regex = new RegExp("^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$");
                
                resolve({
                    valid: value && regex.test(value)
                });
            })
        });
        
    },
    beforeDestroy(){
        bus.$off('setupdateOffset', this.setDataForEdit)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#offsetModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateOffset', this.setDataForEdit);

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