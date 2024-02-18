<template>
    <div>
        
            <form method="post" >
                <h3 class="text-center form-title"><span class="dblUnderlined">MARKETING ITEM INFORMATION SHEET (MIIS)</span></h3>
                <div class="col-md-12">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="datefiled" name="Date filed" readonly="true">
                            <label class="form-field__label">Date filed</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedfullname" name="Name" readonly="true">
                            <label class="form-field__label">Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computeddeptname" name="Department" readonly="true">
                            <label class="form-field__label">Department</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="computedbranchname" name="Branch" readonly="true">
                            <label class="form-field__label">Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Item Classification</em></h5>
                </div>
                <div class="col-md-12">
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">NTMC</span>
                                <input :disabled="$parent.disabledinput" type="radio" value="1" v-model="brand" name="brand">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">APBW</span>
                                <input :disabled="$parent.disabledinput" type="radio" value="2" v-model="brand" name="brand">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">PHILCREST</span>
                                <input :disabled="$parent.disabledinput" type="radio" value="3" v-model="brand" name="brand">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-6">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">TYREPLUS</span>
                                <input :disabled="$parent.disabledinput" type="radio" value="4" v-model="brand" name="radio">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="itemtype" name="item type">
                            <label class="form-field__label">Item Type</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="inventorytype" name="Inventory type">
                            <label class="form-field__label">Inventory type</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="category" name="Category">
                            <label class="form-field__label">Category</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Item Details</em></h5>
                </div>
                <div class="col-lg-12">
                    <div class="col-lg-2">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">New Item</span>
                                <input :disabled="$parent.disabledinput" type="radio" value="1" v-model="newitem" name="new item">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div>
                            <label class="mdblblradio">
                                <span class="checklbl">Existing Item</span>
                                <input :disabled="$parent.disabledinput" type="radio" value="2" v-model="newitem" name="new item">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="itemdesc" name="Item Description">
                            <label class="form-field__label">Item Description</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="sapcode" name="SAP Code">
                            <label class="form-field__label">SAP Code</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="itemstatus" name="Status">
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="detailtype" name="Type">
                            <label class="form-field__label">Type</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="itemorigin" name="Origin">
                            <label class="form-field__label">Origin</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="warehouse" name="Warehouse">
                            <label class="form-field__label">Warehouse</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="qty" name="Quantity">
                            <label class="form-field__label">Quantity</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="unitmeasure" name="Unit of meassure">
                            <label class="form-field__label">Unit of meassure</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="itemcost" name="Item cost">
                            <label class="form-field__label">Item cost</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="totalcost" name="Total">
                            <label class="form-field__label">Total</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="moreinfo" name="additional-info"></textarea>
                                <label class="form-field__label">Additional Info</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
                </div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>FOR APPROVER</em></h5>
                </div>
                <!-- status is rejected or approve -->
                <div v-if="$parent.disabledinput">
                    <div class="col-lg-12">
                        <h5 class="form-subtitle"></h5>
                        <div class="mdb-form-field">
                                <div class="form-field__control mdb-bgcolor">
                                    <textarea :disabled="$parent.$data.forapprover != 'approval'" class="form-field__textarea"  cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
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
                    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addMIIS" :disabled="disabledIfNoApprover || isDisable || !isFormValid" v-if="submitBtn">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateMIIS" :disabled="isDisable || !isFormValid" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteMIIS" :disabled="isDisable" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionMIIS(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionMIIS(2)" v-if="approveRejecBtn">
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionMIIS(0)" v-if="cancelBtn"> -->
                </div>
            </form>
        
        
    </div>
</template>
<script>
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others']; 
let status = ['Pending', 'Approved', 'Rejected'];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
			isDisable: false,
			datefiled: moment(new Date()).format('MM/DD/YYYY'),
            brand: 1,
            itemtype: '',
            inventorytype: '',
            category: '',
            newitem: 1,
            itemdesc: '',
            sapcode: '',
            itemstatus: '',
            detailtype: '',
            itemorigin: '',
            warehouse: '',
            qty: '',
            unitmeasure: '',
            itemcost: '',
            totalcost: '',
            moreinfo: '',
            miisID: '',
            approvedby: '',
            remarks: ''
            
			
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        addMIIS(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                params['reciever_emails'] = this.$parent.reciever_emails;
                axios.post('api/addMIIS', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateMIIS(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateMIIS', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteMIIS(){
            this.isDisable = true;
            axios.get('api/deleteMIIS/'+this.miisID).then((response)=>{
                this.$parent.deleteRow(this.miisID);
            }).catch((err)=>{ console.log(err); });
        },
        // ACTIONS FOR MIIS I.E APPROVE / REJECT / CANCEL
        requestActionMIIS(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            params['email'] = this.$parent.$data.selected.email;
            delete params.isDisable;
            axios.post('api/actionformMIIS', params).then((response)=>{
                this.$parent.updateRow(response.data);
                this.closeModal();
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
                if(i == 'leavetype')
                    this.$data[i] = (leavetype.indexOf(data[i]) + 1);
            
            }
            
            this.MDBINPUT();
            $("#myModal").modal("show");
        },

        selectDateStart(val){
            this.datestart = val;
        },
        selectDateEnd(val){
            this.dateend = val;
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'newitem' && key != 'brand'){
                    this.$data[key] =  '';
                }
                
                if(key == 'datefiled'){
                    this.$data[key] = moment(new Date()).format('MM/DD/YYYY');
                }

                if(key=='isDisable'){
                    this.$data[key] = false;
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

        isFormValid(){
                return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        submitBtn()
        {
            return !this.miisID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.miisID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput 
        },
        approveRejecBtn(){
            return this.miisID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.miisID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        },
        computedfullname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.fullname  : this.userinfo.fullname;
        },
        computedbranchname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.branchname  : this.userinfo.branchname;
        },
        computeddeptname(){
            return this.selected.hasOwnProperty('fullname') ? this.selected.deptname  : this.userinfo.deptname;
        }
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