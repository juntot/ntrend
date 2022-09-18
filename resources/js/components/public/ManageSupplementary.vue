<template>
    <div>

            <form method="post" >
                <h3 class="text-center form-title"><span class="dblUnderlined">ATTENDANCE SUPPLEMENTARY</span></h3>
                <div class="col-md-12">
                    <div class="mdb-table-overflow">
                            <table width="100%" class="table table-hover mdb-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="200px">DATE</th>
                                            <th class="text-center">TIME IN (AM)</th>
                                            <th class="text-center">TIME OUT (AM)</th>
                                            <th class="text-center">TIME IN (PM)</th>
                                            <th class="text-center">TIME OUT (PM)</th>
                                            <th class="text-center" colspan="2">REASON</th>

                                        </tr>
                                        <tr v-if="!$parent.disabledinput">
                                            <td>
                                                <Datepicker :value="supdate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker inline-input" @selected="selectSupDate" :typeable="false" :format="'MM/dd/yyyy'">
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
                                            <!-- afternoon -->
                                            <td>
                                                <div class="mdb-form-field form-group-limit">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="time" name="timein" v-model="timein2" v-validate="'is_time|required'" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('timein2') }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mdb-form-field form-group-limit">
                                                    <div class="form-field__control form-field--is-filled">
                                                        <input type="time" name="timeout" v-model="timeout2" v-validate="'is_time|required'" class="form-field__input inline-input" >
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('timeout2') }}</span>
                                                </div>
                                            </td>
                                            <!-- END -->
                                            <td>
                                                <div class="mdb-form-field">
                                                    <div class="form-field__control">
                                                        <select v-model="reason" id="posname" name="position" class="form-field__input inline-input" >
                                                            <option :value="'Failure to log-in / out'">Failure to log-in / out</option>
                                                            <option :value="'Delivery team doing out base deliveries'">Delivery team doing out base deliveries</option>
                                                            <option :value="'Out of town for buiness travel'">Out of town for buiness travel</option>
                                                            <option :value="'Multiple logs'">Multiple logs</option>
                                                            <option :value="'Out of office on officials'">Out of office on officials</option>
                                                        </select>
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                <span class="errors">{{ errors.first('position') }}</span>
                                                </div>
                                                <!-- <div class="mdb-form-field">
                                                    <div class="form-field__control">
                                                        <input type="text" class="form-field__input inline-input" v-model="reason" v-validate="'required'" name="reason" @keydown.enter.prevent="appendTable">
                                                        <div class="form-field__bar"></div>
                                                    </div>
                                                    <span class="errors">{{ errors.first('reason') }}</span>
                                                </div> -->
                                            </td>
                                            <td>
                                                <button class="btn btn-primary" @click.prevent="appendTable">add</button>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in entries" :key="index">
                                            <td>
                                                <div>
                                                    <span style="padding: 0 12px;">
                                                        <a v-if="!$parent.disabledinput" @click="removeRow(index)"><i class="fas fa-trash text-danger"></i></a>
                                                    </span>
                                                    {{item.supdate | customDateFormat}}
                                                </div>
                                            </td>
                                            <td>
                                                {{item.timein}}
                                            </td>
                                            <td>
                                                {{item.timeout}}
                                            </td>
                                            <td>
                                                {{item.timein2}}
                                            </td>
                                            <td>
                                                {{item.timeout2}}
                                            </td>
                                            <td colspan="2">
                                                {{item.reason}}
                                            </td>
                                        </tr>

                                    </tbody>
                            </table>

                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Company</em></h5>
                </div>
                <div class="col-md-12">
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">NTMC</span>
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="brand" value="1" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">APBW</span>
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="brand" value="2" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">PHILCREST</span>
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="brand" value="3" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div>
                                <label class="mdblblradio">
                                    <span class="checklbl">TYREPLUS</span>
                                    <input :disabled="$parent.disabledinput" type="radio" v-model="brand" value="4" name="brand">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                </div>
                <div class="clearfix"></div> -->
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Requested By</em></h5>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="datefiled" class="form-field__input" name="datefiled" :readonly="true">
                            <label class="form-field__label">date-filed</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedID" name="idno" :readonly="true">
                            <label class="form-field__label">ID no.</label>
                            <div class="form-field__bar"></div>
                            <span class="errors">{{ errors.first('idno') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedfullname" name="name" :readonly="true">
                            <label class="form-field__label">Name</label>
                            <div class="form-field__bar"></div>
                            <span class="errors">{{ errors.first('name') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedposname" name="position" readonly="true">
                            <label class="form-field__label">Position</label>
                            <div class="form-field__bar"></div>
                            <span class="errors">{{ errors.first('position') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedbranchname" name="branch" readonly="true">
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                            <span class="errors">{{ errors.first('Branch') }}</span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Witnesses</em></h5>
                </div>
                <div class="col-md-12">
                    <div class="mdb-form-field ">
                        <div class="form-field__control form-field--is-filled">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="witnesses_list" name="witnesses" readonly>
                            <label class="form-field__label" style="top: -5px; transform: translateY(-14px);">Witnesses</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('witnesses') }}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12" v-if="witnesses">
                    <p class="text-info">
                        CONFIRMED WITNESS: <br>
                        <span style="display: inline-block; padding: 2px 12px; border-radius: 4px; color: white; background: orange;">
                            {{confirm_witness}}
                        </span>
                    </p>

                </div>
                <div class="clearfix"></div>
                <!-- status is rejected or approve -->
                <div v-if="!$parent.witness_approval && !$parent.forWitness">
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
                        <span class="approverlist alert-info" v-for="(approver, index) in $parent.approvers" :key="index">{{approver.approvers}}</span>
                        <br><br>
                    </div>
                </div>
                <div class="clearfix"></div>
                    <div class="modal-footer">
                        <em style="color: red;" v-if="!witnesses_list">No witness is set. please contact your I.T Administrator &nbsp;&nbsp;</em>
                        <input type="submit" class="btn btn-primary" value="Verify as witness" @click.prevent="confirmWitness" :disabled="isDisable || !isFormValid || !hasEntries" v-if="$parent.witness_approval">
                        <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addSupplementary" :disabled="disabledIfNoApprover || isDisable || !isFormValid || !hasEntries || !witnesses_list" v-if="!supID && $parent.$data.forapprover != 'approval'">
                        <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateSupplementary" :disabled="isDisable || !isFormValid || !hasEntries" v-if="supID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                        <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteSupplementary" :disabled="isDisable" v-if="supID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                        <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionSupplementary(2)" v-if="supID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel " :disabled="selected.status < 1">
                        <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionSupplementary(3)" v-if="supID && $parent.$data.forapprover == 'approval' && !$parent.$data.isCancel" :disabled="selected.status < 1">
                        <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionSupplementary(1)" v-if="supID && $parent.$data.forapprover == 'approval' && $parent.$data.isCancel" :disabled="selected.status < 1">
                    </div>
            </form>
    </div>
</template>
<script>
let brand = ['NTMC', 'APBW', 'PHILCREST', 'TYREPLUS'];
// let status = ['Pending', 'Approved', 'Rejected'];
let regex = new RegExp("^(((([0-1][0-9])|(2[0-3])):?[0-5][0-9])|(24:?00))");
let witness_emails = [];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {

			isDisable: false,
            datefiled: moment(new Date()).format('MM/DD/YYYY'),
            // supdate: moment(new Date()).format('YYYY/MM/DD'),
            // supdate: moment(new Date()).format('MM/DD/YYYY'),
            // timein: moment(new Date()).format('HH:mm'),
            // timeout: moment(new Date()).add(30, 'minutes').format('HH:mm'),
            // timein2: moment(new Date().setHours(13,0,0,0)).format('HH:mm'),
            // timeout2: moment(new Date().setHours(17,0,0,0)).format('HH:mm'),
            supdate: moment(new Date()).format('YYYY/MM/DD'),
            timein: moment(new Date().setHours(24,0,0,0)).format('HH:mm'),
            timeout: moment(new Date().setHours(24,0,0,0)).format('HH:mm'),
            timein2: moment(new Date().setHours(24,0,0,0)).format('HH:mm'),
            timeout2: moment(new Date().setHours(24,0,0,0)).format('HH:mm'),
            entries: [],
            brand: 1,
            reason: 'Failure to log-in / out',
            supID: '',
            witnesses: '',
            witnesses_list: '',
            approvedby: '',
            remarks: '',
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    filters: {
        customDateFormat: function (value) {
            if (!value) return ''
            value = new Date(value);
            return moment(value).format('MM/DD/YYYY');
        }
    },
    methods:{
        confirmWitness(){
            // console.log((this.witnesses).split(','));
            let data = {
                supID: this.supID,
            };
            // uncomment to enable multiple approver
            if(this.witnesses && (this.witnesses.slice(2)).split(',').length > 0){
                data['approver_count'] = 2;
            }

            // data['approver_count'] = 2;
            
            data['empID_'] = this.selected.empID_;
            data['reciever_emails'] = this.$parent.reciever_emails;
            axios.post('api/confirm-as-witness', data )
            .then(response=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            })
            .catch(er=>alert('Server Error'));

           /*
           axios.post('api/confirm-as-witness',{
                supID: this.supID
            })
            .then(response=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            })
            .catch(er=>alert('Server Error'));
            */
        },
        addSupplementary(){
            
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                // params['reciever_emails'] = this.$parent.reciever_emails;
                params['reciever_emails'] = witness_emails;
                axios.post('api/addSupplementary', params).then((response)=>{
                    // this.isDisable = false;
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{
                    // this.isDisable = false;
                    console.log(err);
                });
            }
        },
        updateSupplementary(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateSupplementary', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteSupplementary(){
            this.isDisable = true;
            axios.get('api/deleteSupplementary/'+this.supID).then((response)=>{
                this.$parent.deleteRow(this.supID);
            }).catch((err)=>{ console.log(err); });
        },
        appendTable(){
                this.$validator.validate('timein', this.timein);
                this.$validator.validate('timeout', this.timeout);
                // this.$validator.validate('reason', this.reason);

                if(regex.test(this.timein) && regex.test(this.timeout) && this.reason !='')
                {
                    this.entries.push({
                        supdate: this.supdate,
                        timein: moment(this.timein, ["HH:mm"]).format('hh:mm A'),
                        timeout: moment(this.timeout, ["HH:mm"]).format('hh:mm A'),
                        timein2: moment(this.timein2, ["HH:mm"]).format('hh:mm A'),
                        timeout2: moment(this.timeout2, ["HH:mm"]).format('hh:mm A'),
                        reason: this.reason
                    });
                    this.reason = '';
                    this.supdate = moment(new Date()).format('YYYY/MM/DD');
                    this.timein = moment(new Date().setHours(24,0,0,0)).format('HH:mm');
                    this.timeout = moment(new Date().setHours(24,0,0,0)).format('HH:mm');
                    this.timein2 = moment(new Date().setHours(24,0,0,0)).format('HH:mm');
                    this.timeout2 = moment(new Date().setHours(24,0,0,0)).format('HH:mm');

                }

        },
        removeRow(index)
        {
            this.entries.splice(index, 1);
        },
        // ACTIONS FOR Supplementary I.E APPROVE / REJECT / CANCEL
        requestActionSupplementary(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$root.$data.userinfo.empID;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformSupplementary', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable' && i != 'supdate' &&
                    i != 'timein' && i != 'timeout' &&
                    i != 'timein2' && i != 'timeout2' )
                    this.$data[i] = data[i];
                if(i == 'brand')
                    this.$data[i] = (brand.indexOf(data[i]) + 1);
            }

            this.MDBINPUT();
            $("#myModal").modal("show");
        },
        selectSupDate(val){
            this.supdate = moment(val).format('YYYY-MM-DD');
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key == 'witnesses' || key == 'supID'){
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
                if(key === 'datefiled'){
                    this.$data[key] = moment(new Date()).format('MM/DD/YYYY');
                }

            });

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
    computed:{
        disabledIfNoApprover(){
            return this.$parent.$data.forapprover != 'approval' && this.$parent.approvers && this.$parent.approvers.length < 1;
        },
        confirm_witness(){
            // return this.witnesses.slice(2);
            return this.witnesses;
        },
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
            return !this.supID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.supID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput
        },
        approveRejecBtn(){
            return this.supID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.supID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
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
        bus.$off('setupdate', this.setDataForEdit)
    },
    mounted(){
        axios.get('api/get-witness-list')
        .then(res=>{
            res.data.forEach(w=>{
                this.witnesses_list += w.fname+' '+w.lname+', ';
                witness_emails.push(w.email);
            })

        }).catch(err=>alert('Supplementary Server Error'));
        this.MDBINPUT();
        if(this.$parent.disabledinput){
            $('.vdp-datepicker div.vdp-datepicker__calendar:nth-child(2)').addClass('disable-dates-approver');
        }

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