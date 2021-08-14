<template>
    <div>
        
            <form method="get">
                <h3 class="text-center form-title"><span class="dblUnderlined">SUPPLIER ACCREDIATION</span></h3>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>SUPPLIERS' INFORMATION </em></h5>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="supname" name="Supplier's Name">
                            <label class="form-field__label">Supplier's Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="supheadoffice" name="Head Office Address">
                            <label class="form-field__label">Head Office Address</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="supbranchadd" name="Branch Address">
                            <label class="form-field__label">Branch Address</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="product" name="Product/Service">
                            <label class="form-field__label">Product/Service</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="website" name="Website">
                            <label class="form-field__label">Website</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="contactperson" name="Contact Person">
                            <label class="form-field__label">Contact Person</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="phone" name="Phone num">
                            <label class="form-field__label">Phone num</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="mobile" name="Mobile num">
                            <label class="form-field__label">Mobile num</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="fax" name="Fax num">
                            <label class="form-field__label">Fax num</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="TIN" name="TIN">
                            <label class="form-field__label">TIN</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="SECDTIregno" name="SEC/DTI Reg No.">
                            <label class="form-field__label">SEC/DTI Reg No.</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group-limit">
                            <Datepicker :disabled="true" :value="datereg" @selected="selectDateReg" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                            <label slot="afterDateInput" class="form-field__label">Date Registered</label>
                            <div slot="afterDateInput" class="form-field__bar"></div>
                            <span slot="afterDateInput" class="errors">{{ errors.first('dateregistered') }}</span>
                            </Datepicker> 		
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="philhealth" name="Philhealth">
                            <label class="form-field__label">Philhealth</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="sss" name="SSS Number">
                            <label class="form-field__label">SSS Number</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>SUPPLIERS PROFILE</em></h5>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-validate="'required|numeric'" v-model="yearinbusiness" name="Year in Business">
                            <label class="form-field__label">Year in Business</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('Year in Business') }}</span></h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="capital" name="Total Capitalization">
                            <label class="form-field__label">Total Capitalization</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="asset" name="Total Asset">
                            <label class="form-field__label">Total Asset</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="liabilities" name="Total Liabilities">
                            <label class="form-field__label">Total Liabilities</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" v-model="industry" name="Industry">
                                <label class="form-field__label">Industry</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" v-validate="'required|numeric'" v-model="totalemployees" name="No. of Employees">
                                <label class="form-field__label">No. of Employees</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('No. of Employees') }}</span></h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" v-validate="'required|numeric'" v-model="regemployee" name="No. of Regular Employees">
                                <label class="form-field__label">No. of Regular Employees</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('No. of Regular Employees') }}</span></h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" v-validate="'required|numeric'" v-model="activeclient" name="No.of active clients">
                                <label class="form-field__label">No.of active clients</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('No.of active clients') }}</span></h6>
                        </div>
                    </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>DOCUMENTS PROVIDED</em></h5>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">DTI/SEC Resgistration*</span>
                            <input :disabled="true" type="checkbox" v-model="docu1" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">Latest Business Permit*</span>
                            <input :disabled="true" type="checkbox" v-model="docu2" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">BIR Registration*</span>
                            <input :disabled="true" type="checkbox" v-model="docu3" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">Latest SEC GIS received by SEC</span>
                            <input :disabled="true" type="checkbox" v-model="docu4" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">Latest Audited Financial Statements*</span>
                            <input :disabled="true" type="checkbox" v-model="docu5" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">A copy of the blank OR or Invoice*</span>
                            <input :disabled="true" type="checkbox" v-model="docu6" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">Company Profile/Annual Report</span>
                            <input :disabled="true" type="checkbox" v-model="docu7" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">List of Clients*</span>
                            <input :disabled="true" type="checkbox" v-model="docu8" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">List of Suppliers</span>
                            <input :disabled="true" type="checkbox" v-model="docu9" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">Pictures of the Establishment*</span>
                            <input :disabled="true" type="checkbox" v-model="docu10" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group"> 
                            <label class="mdblbl">
                            <span class="checklbl">Pictures of products/work, if any</span>
                            <input :disabled="true" type="checkbox" v-model="docu11" value="1">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>BANK DETAILS and PAYMENT REQUIREMENT</em></h5>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="bankname" name="Bank/Branch">
                            <label class="form-field__label">Bank/Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="accountno" name="Account No">
                            <label class="form-field__label">Account No</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="accountname" name="Account Name">
                            <label class="form-field__label">Account Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>PAYMENT TERMS</em></h5>
                </div>
                <!-- <div class="col-md-12">
                    <div class="col-md-2">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">30 days</span>
                            <input :disabled="true" type="radio" v-model="payterms" value="1" name="radio" checked="checked">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">60 days</span>
                            <input :disabled="true" type="radio" v-model="payterms" value="1" name="radio" checked="checked">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">90 days</span>
                            <input :disabled="true" type="radio" v-model="payterms" value="1" name="radio" checked="checked">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-12">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" v-model="payterms" class="form-field__input" name="Others">
                            <label class="form-field__label">Terms (30 days, 60 days, etc.)</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>PAYMENT MODE</em></h5>
                </div>
                <div class="col-md-12">
                    <div class="col-md-2">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Cash</span>
                            <input :disabled="true" type="radio" v-model="modepayment" value="1" name="radio" checked="checked">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Check</span>
                            <input :disabled="true" type="radio" v-model="modepayment" value="2" name="radio" checked="checked">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div>
                            <label class="mdblblradio">
                            <span class="checklbl">Online Deposit</span>
                            <input :disabled="true" type="radio" v-model="modepayment" value="3" name="radio" checked="checked">
                            <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>QUALITY ASSURANCE</em></h5>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <p>Are we assured that the products leaving your facility are properly scrutinized to be free of defects and are the right quantity and quality?</p>
                </div>
                <div class="col-md-12">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="productcommitment" name="Specify here">
                            <label class="form-field__label">Specify here</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <p>Do you take responsibilty for all items delivered that do not meet the  aggreed specification and quantities?</p>
                </div>
                <div class="col-md-12">
                    <div class="mdb-form-field">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="productquality" name="Specify here">
                            <label class="form-field__label">Specify here</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                            <div class="form-field__control mdb-bgcolor">
                                <textarea :disabled="true" class="form-field__textarea"  cols="4" rows="4" v-validate="'required'" v-model="reason" name="additional-info"></textarea>
                                <label class="form-field__label">Please described the after sales services you can extend to NTM:</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <h6><span class="errors">{{ errors.first('additional-info') }}</span></h6>
                    </div>
                    <p class="form-note">
                            The undersigned hereby confirms that the above information is true and correct, and that  are  duly authorized to enter into this accreditation process.    Moreover,  all the supporting documents attached here into are true and authentic .    Furthermore, 
                            I hereby authorize NTMC to obtain pertinent information from clients, banks and any other source necessary for the objective of evaluation for this application. 
                            
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input :disabled="true" type="text" class="form-field__input" v-model="repname" name="Owner/Representative">
                            <label class="form-field__label">Owner/Representative's Name</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" :value="datefiled" name="Date filed" readonly="true">
                            <label class="form-field__label">Date</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>FOR APPROVER</em></h5>
                </div>
                <!-- status is rejected or approve -->
                <div v-if="true">
                    <div class="col-lg-12">
                        <h5 class="form-subtitle"></h5>
                        <div class="mdb-form-field">
                                <div class="form-field__control mdb-bgcolor">
                                    <textarea :disabled="true" class="form-field__textarea"  cols="4" rows="4" v-model="remarks" name="additional-info"></textarea>
                                    <label class="form-field__label">Remarks</label>
                                    <div class="form-field__bar"></div>
                                </div>
                        </div>
                    </div>
                    <div class="col-lg-12" v-if="true">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input :disabled="true" type="text" class="form-field__input" :value="approvedby" name="branch" readonly="true">
                                <label class="form-field__label">Approved/Rejected By</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="clearfix"></div>
                <div class="col-lg-12" v-if="!true">
                    <p class="text-info">
                        LIST OF APPROVERS: 
                    </p>
                    <div class="clearfix"></div>
                    <span class="approverlist alert-info" v-for="approver in $parent.approvers">{{approver.approvers}}</span>
                    <br><br>
                </div> -->
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addSupplierAccreditation" :disabled="isDisable || !isFormValid" v-if="submitBtn">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateSupplierAccreditation" :disabled="isDisable || !isFormValid" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteSupplierAccreditation" :disabled="isDisable" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionSupplierAccreditation(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionSupplierAccreditation(2)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionSupplierAccreditation(0)" v-if="cancelBtn"> -->
                </div>
            </form>
        
        
    </div>
</template>
<script>
let leavetype = ['Sick Leave', 'Birthday Leave', 'Leave w/o Pay', 'Bereavement Leave', 'Vacation Leave', 'Others']; 
let status = ['Pending', 'Approved', 'Rejected'];
// <!-- NOT SUBJECT FOR SELECTED BUT ADDED FOR PREPARATION -->
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
			isDisable: false,
            datefiled: moment(new Date()).format('YYYY-MM-DD'),
            supname: '',
            supheadoffice: '',
            supbranchadd: '',
            product: '',
            website: '',
            contactperson: '',
            phone: '',
            mobile: '',
            fax: '',
            TIN: '',
            SECDTIregno: '',
            datereg: moment(new Date()).format('YYYY-MM-DD'),
            philhealth: '',
            sss: '',
            yearinbusiness: '',
            capital: '',
            asset: '',
            liabilities: '',
            industry: '',
            totalemployees: '',
            regemployee: '',
            activeclient: '',
            docu1: '', docu2: '', docu3: '', docu4: '', docu5: '', docu6: '', docu7: '', docu8: '', docu9: '', docu10: '', docu11: '', 
            bankname: '',
            accountno: '',
            accountname: '',
            payterms:'',
            modepayment: 1,
            productcommitment: '',
            productquality: '',
            reason: '',
            repname: '',
            accredID: '',
            approvedby: '',
            remarks: ''
            
			// reqstat: 0, //0 pending, 1//approve //2 declined
			// status: 0, //0 open, 1//close
			
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        }
    },
    methods:{
        addSupplierAccreditation(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = this.$data;
                axios.post('api/addSupplierAccreditation', params).then((response)=>{
                    this.$parent.addRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        updateSupplierAccreditation(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = this.$data;
                axios.post('api/updateSupplierAccreditation', params).then((response)=>{
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteSupplierAccreditation(){
            this.isDisable = true;
            axios.get('api/deleteSupplierAccreditation/'+this.accredID).then((response)=>{
                this.$parent.deleteRow(this.accredID);
            }).catch((err)=>{ console.log(err); });
        },
        
        // ACTIONS FOR SupplierAccreditation I.E APPROVE / REJECT / CANCEL
        requestActionSupplierAccreditation(status = null){
            let params =  this.$data;
            params['approvedby'] = this.$root.$data.userinfo.empID;
            params['empID_'] = this.$parent.$data.empID_;
            params['status'] = status;
            delete params.isDisable;
            axios.post('api/actionformSupplierAccreditation', params).then((response)=>{
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
            $("#supAccreModal").modal("show");
        },

        selectDateReg(val){
            this.datereg = moment(val).format('YYYY-MM-DD');
        },
        selectDateEnd(val){
            this.dateend = val;
        },
        closeModal(){
            let obj = this.$data;
            Object.keys(obj).forEach((key)=>{
                if(key != 'datefiled' && key != 'modepayment' && key != 'datereg'){
                    this.$data[key] =  '';
                }
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                
            });
            
            $("#supAccreModal").modal("hide");
            
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

        getDiff(){
            this.datestart =  moment(this.datestart).format('YYYY-MM-DD');
            this.dateend = moment(this.dateend).format('YYYY-MM-DD');
            let startDate = moment(this.datestart, 'YYYY-MM-DD');
            let endDate = moment(this.dateend, 'YYYY-MM-DD');
            let totaldays = endDate.diff(startDate, 'days');
            this.totaldays = totaldays;
            return totaldays;
        },
        isFormValid(){
                return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        submitBtn()
        {
            return !this.accredID && this.true;
        },
        updateDeleteBtn(){
            return this.accredID && this.true && !this.true 
        },
        approveRejecBtn(){
            return this.accredID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.accredID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
        }
    },
    beforeDestroy(){
        bus.$off('setupdateSupAccre', this.test)
    },
    mounted(){

        this.MDBINPUT();
        // MODAL
        $('#supAccreModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateSupAccre', this.setDataForEdit);

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