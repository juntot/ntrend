<template>
    <div>
            <h4 class="text-right" style="color: #3f51b5">{{transID ? 'Pouch# '+transID: ''}}</h4>
            <form method="post" >
                <h3 class="text-center form-title"><span class="dblUnderlined">TRANSMITTAL FORM</span></h3>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="computedfullname" class="form-field__input" name="From" :readonly="true">
                            <label class="form-field__label">From</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput" type="text" v-model="search_employee"  v-validate="'required'" name="To"  class="form-field__input"
                                @keyup.prevent="searchEmployee"
                                >
                                <label class="form-field__label">To:</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors" v-if="!search_employee">{{ errors.first('To') }}</span>
                            <div class="absolute-pos bg-white suggestion_filter" 
                                v-if="search_employee && !approvedby && employeeList.length > 0
                            ">
                                <!-- loader and err msg -->
                                <div v-if="loader">
                                    <i class="fas fa-spinner fa-spin"></i> 
                                    <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                </div>
                                <ul>
                                    <li v-for="emp in employeeList" 
                                    @click.prevent="selectedEmployee(emp)"
                                    :key="emp.empID">
                                        {{emp.fullname}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="receiver_pos" class="form-field__input" name="From" :readonly="true">
                            <label class="form-field__label">Receiver Position</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="computedbranchname" class="form-field__input" name="From" :readonly="true">
                            <label class="form-field__label">Sender Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="receiver_dept" class="form-field__input" name="From" :readonly="true">
                            <label class="form-field__label">Receiver Department</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="receiver_branch" class="form-field__input" name="From" :readonly="true">
                            <label class="form-field__label">Reciever Branch</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" v-model="contactnum" name="lastname" :readonly="true">
                            <label class="form-field__label">Contact Number</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" class="form-field__input" v-model="address" name="lastname" :readonly="true">
                            <label class="form-field__label">Address</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="computedStatus" class="form-field__input" name="status" :readonly="true">
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="datefiled | customDateFormat" class="form-field__input" name="datefiled" :readonly="true">
                            <label class="form-field__label">Date & Time Prepared</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" v-if="confirmdate">
                    <div class="mdb-form-field form-group-limit">
                        <div class="form-field__control">
                            <input type="text" :value="confirmdate | customDateFormat" class="form-field__input" name="datefiled" :readonly="true">
                            <label class="form-field__label">Date & Time Received</label>
                            <div class="form-field__bar"></div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>We are sending you:</em></h5>
                </div>
                <div class="col-lg-12">
                    <div class="col-md-4">
                        <div class="group">
                            <label class="mdblbl inline-blocklbl mdblblradio">Check(s)
                            <input :disabled="$parent.disabledinput" type="checkbox" value="check(s)" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl mdblblradio">Receipt(s)
                            <input :disabled="$parent.disabledinput" type="checkbox" value="receipt(s)" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group">
                            <label class="mdblbl inline-blocklbl mdblblradio">Invoice(s)
                            <input :disabled="$parent.disabledinput" type="checkbox" value="invoice(s)" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl mdblblradio">Document(s)
                            <input :disabled="$parent.disabledinput" type="checkbox" value="document(s)" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group">
                            <label class="mdblbl inline-blocklbl mdblblradio">Contract(s)
                            <input :disabled="$parent.disabledinput" type="checkbox" value="contract(s)" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                        <div class="group">
                            <label class="mdblbl inline-blocklbl mdblblradio">Item(s)
                            <input :disabled="$parent.disabledinput" type="checkbox" value="item(s)" v-model="request_type">
                            <span class="mdbcheckmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12">
                        <div class="mdb-form-field form-group-limit">
                            <div class="form-field__control">
                                <input type="text" :disabled="$parent.disabledinput" v-model="others" class="form-field__input" name="others">
                                <label class="form-field__label">Others (please specifiy)</label>
                                <div class="form-field__bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <h5 class="form-subtitle"><em>Item Details:</em></h5>
                </div>
                <div class="col-md-12">
                    <div class="mdb-table-overflow">
                            <table width="100%" class="table table-hover mdb-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="200px">COPIES</th>
                                            <th class="text-center" :colspan="$parent.disabledinput? 1: 2">DESCRIPTION</th>
                                            <th class="text-center" v-if="status != 0 || $parent.disabledinput">DOC STATUS</th>
                                            <th class="text-center" v-if="status != 0 || $parent.disabledinput">REMARKS</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in entries" :key="index">
                                            <td>
                                                <div>
                                                    <!-- <span style="padding: 0 12px;" v-if="!$parent.disabledinput">
                                                        <a @click="removeRow(index)"><i class="fas fa-trash text-danger"></i></a>
                                                    </span> -->
                                                    {{item.doctype}}
                                                </div>
                                            </td>
                                            <td
                                            :colspan="$parent.disabledinput? 1: 2"
                                            >
                                                {{item.refnum}}
                                            </td>
                                            <td v-if="status != 0 || $parent.disabledinput"> 
                                                <div class="mdb-form-field form-group-limit">
                                                    <select 
                                                    :disabled="$parent.$data.forapprover != 'approval' || status > 1"
                                                    v-model="item.docstatus" name="docstatus" class="form-field__input inline-input" >
                                                        <option :value="'For Approval'" :disabled="true">For Approval</option>
                                                        <option :value="'Partially Received'">Partially Received</option>
                                                        <option :value="'Received'">Received</option>
                                                    </select>
                                                    <!-- <div class="form-field__bar"></div> -->
                                                    <!-- <span class="errors">{{ errors.first('docstatus') }}</span> -->
                                                </div>
                                            </td>
                                            <td colspan="2" v-if="status != 0 || $parent.disabledinput">
                                                <!-- {{item.docremarks}} -->
                                                <div class="mdb-form-field">
                                                    <div class="form-field__control">
                                                        <input 
                                                        :disabled="$parent.$data.forapprover != 'approval' || status > 1"
                                                        type="text" name="docremarks" v-model="item.docremarks" class="form-field__input inline-input" >
                                                        <!-- <div class="form-field__bar"></div> -->
                                                    </div>
                                                    <!-- <span class="errors">{{ errors.first('docremarks') }}</span> -->
                                                </div>
                                            </td>
                                            
                                        </tr>

                                    </tbody>
                            </table>

                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="$parent.disabledinput" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="additionalInfo" name="additionalInfo"></textarea>
                            <label class="form-field__label">Additional Info</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('additionalInfo') }}</span></h6>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <h5 class="form-subtitle">
                        <em>FOR APPROVER</em>
                    </h5>
                </div>
                <!-- status is rejected or approve -->
                <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                    <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="$parent.$data.forapprover != 'approval' || status > 1" class="form-field__textarea"  cols="4" rows="4" v-validate="" v-model="remarks" name="remarks"></textarea>
                            <label class="form-field__label">Approver Remarks</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <h6><span class="errors">{{ errors.first('remarks') }}</span></h6>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" @click.prevent="printForm">Print</button>
                    <!-- <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addTransmittal" :disabled="isDisable || !isFormValid || !hasEntries || !approvedby" v-if="!transID && $parent.$data.forapprover != 'approval'">
                    <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateTransmittal" :disabled="isDisable || !isFormValid || !hasEntries" v-if="transID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteTransmittal" :disabled="isDisable" v-if="transID && $parent.$data.forapprover != 'approval' && !$parent.disabledinput ">
                    <input type="submit" class="btn btn-primary" value="Confirm" @click.prevent="requestActionTransmittal(1)" v-if="transID && $parent.$data.forapprover == 'approval' && status < 2">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionTransmittal(3)" v-if="transID && $parent.$data.forapprover == 'approval' && status < 2"> -->
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionTransmittal(4)" v-if="transID && $parent.$data.forapprover == 'approval' && status >= 2"> -->
                </div>
            </form>

<!-- print section -->
            <div id="printArea">
                <h4 class="text-right" style="color: #3f51b5">{{transID ? 'Pouch# '+transID: ''}}</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <h5 class="font-weight-bold">SENDER INFORMATION:</h5>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">From:</label>
                            <input type="text" class="form-control" id="usr" :value="computedfullname" :readonly="true">
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">Sender Branch:</label>
                            <input type="text" class="form-control" id="usr" :value="computedbranchname" :readonly="true">
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">Status:</label>
                            <input type="text" class="form-control" id="usr" :value="computedStatus" :readonly="true">
                        </div>
                    </div>
                    <!-- reciever -->
                    <div class="col-lg-12">
                        <h5 class="font-weight-bold">RECEIVER INFORMATION:</h5>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">To:</label>
                            <input type="text" class="form-control" id="usr" :value="search_employee" :readonly="true">
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">Receiver Position:</label>
                            <input type="text" class="form-control" id="usr" :value="receiver_pos" :readonly="true">
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">Receiver Department:</label>
                            <input type="text" class="form-control" id="usr" :value="receiver_dept" :readonly="true">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label for="usr" style="font-weight:bold;">Address: </label>
                            <!-- <input type="text" class="form-control" id="usr" :value="address" :readonly="true"> -->
                            {{address}}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">Receiver Branch:</label>
                            <input type="text" class="form-control" id="usr" :value="receiver_branch" :readonly="true">
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">Contact Number:</label>
                            <input type="text" class="form-control" id="usr" :value="contactnum" :readonly="true">
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="form-group">
                            <label for="usr">Date & Time Prepared:</label>
                            <input type="text" class="form-control" id="usr" :value="datefiled | customDateFormat" :readonly="true">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-4 col-md-4 col-lg-4" v-if="confirmdate">
                        <div class="form-group">
                            <label for="usr">Date & Time Received:</label>
                            <input type="text" class="form-control" id="usr" :value="confirmdate | customDateFormat" :readonly="true">
                        </div>
                    </div>
                    <!-- type -->
                    <div class="col-lg-12">
                        <h5 class="font-weight-bold">TRANSMITTAL TYPE:</h5>
                        <ul class="float-list">
                            <li v-for="(item, index) in request_type" :key="index">{{item}}</li>
                        </ul>
                    </div>
                    <div class="col-lg-12">
                        <p class="font-weight-bold">
                            others:
                        </p>
                        <p>
                            {{others}}
                        </p>
                    </div>
                    <div class="clearfix"></div>
                    <!-- item details -->
                    <div class="col-lg-12">
                        <h5 class="font-weight-bold">ITEM DETAILS:</h5>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="min-width: 50px; width: 50px;">COPIES</th>
                                <th class="text-center" :colspan="$parent.disabledinput? 1: 2">DESCRIPTION</th>
                                <th class="text-center" style="min-width: 150px; width: 150px;" v-if="status != 0 || $parent.disabledinput">DOC STATUS</th>
                                <th class="text-center" v-if="status != 0 || $parent.disabledinput">REMARKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in entries" :key="index">
                                <td>
                                    <div>
                                        {{item.doctype}}
                                    </div>
                                </td>
                                <td
                                :colspan="$parent.disabledinput? 1: 2"
                                >
                                    {{item.refnum}}
                                </td>
                                <td v-if="status != 0 || $parent.disabledinput"> 
                                    {{item.docstatus}}
                                </td>
                                <td colspan="2" v-if="status != 0 || $parent.disabledinput">
                                    {{item.docremarks}}
                                </td>
                                
                            </tr>

                        </tbody>
                    </table>
                    <div class="col-lg-12">
                        <h5 class="font-weight-bold">ADDITIIONAL INFORMATION:</h5>
                    </div>
                    <div class="col-lg-12">
                        <p>
                            {{additionalInfo}}
                        </p>
                    </div>
                    <div class="col-lg-12">
                        <h5 class="font-weight-bold">APPROVER REMARKS:</h5>
                    </div>
                    <div class="col-lg-12">
                        <p>
                            {{remarks}}
                        </p>
                    </div>
                    
                </div>
            </div>
            
    </div>
</template>
<script>
const statCode = ['In Transit', 'Partially Received', 'Received', 'Rejected'];
const excludeBody = [
    'isDisable', 
    'employeeList',
    'search_employee',
    'doctype',
    'refnum',
    'docstatus',
    'docremarks',
    'computedStatus',
    'receiver_pos',
    'receiver_branch',
    'receiver_dept',
    'confirmdate',
    'addressList',
];
export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
            transID: '',
            isDisable: false,
            datefiled: moment(new Date()).format('MM/DD/YYYY HH:mm:ss'),
            search_employee: '',
            employeeList: [],
            entries: [],
            doctype: '',
            request_type: [],
            addressList: [],
            contactnum: '',
            address: ' ',
            others: '',
            refnum: '',
            // docstatus: 'For Approval',
            // docremarks: '',
            additionalInfo: '',
            confirmdate: '',
            approvedby: '',
            receiver_pos: '',
            receiver_branch: '',
            receiver_dept: '',
            remarks: '',
            status: 0,
            computedStatus: 'In Transit',
		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        },
        entries: {
            async handler(val){
                 if(val.length == 0)
                    return this.computedStatus = statCode[0];

                if(this.status == 3) 
                    return this.computedStatus = statCode[3];

                const entries = val;

                let tempStatus = [];
                for (const el of entries) {
                    await tempStatus.push(el.docstatus.toLowerCase());
                }
                if(tempStatus.includes("partially received"))
                return this.computedStatus = statCode[1];

                if(
                    !tempStatus.includes('for approval') && 
                    !tempStatus.includes('') && 
                    !tempStatus.includes('partially received')
                )
                return this.computedStatus = statCode[2];


                if(
                    (tempStatus.includes('received') && (
                    tempStatus.includes('for approval') || tempStatus.includes('')))
                )
                return this.computedStatus = statCode[1];
            },
            deep: true
           
        },
    },
    filters: {
        customDateFormat: function (value) {
            if (!value) return ''
            value = new Date(value);
            return moment(value).format('MM/DD/YYYY hh:mm A');
        }
    },
    methods:{
        async printForm(){
            let elem = document.getElementById("printArea");
            let domClone = elem.cloneNode(true);
            
            let $printSection = document.createElement("div");
            $printSection.id = "printSection";
            
            // let $printSection = document.getElementById("printSection");

            await $printSection.appendChild(domClone);
            document.body.appendChild($printSection);

            window.print();
        },
        searchEmployee(){
            this.loader = false;
			let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
			let regex = RegExp(validSearch);
            this.approvedby = '';
            this.errMsg = '';
            
			if(regex.test((this.search_employee))){
				
                // FOR UPDATE GET ONLY THE LAST NAME
                let search = '';
                if(this.selected.overrideID) {
                    search = (this.search_employee).split(", ");
                    search = search[0];
                    // return;
                }else{
                    search = this.search_employee;
                }
				axios.post('api/search-override-emp',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.employeeList = res.data;
					}
                    this.loader = false;
				})
				.catch(err => this.errMsg = 'Network problem please contact your IT-Department');
			}

		},
        selectedEmployee(val) {
            
            this.search_employee = val.fullname;
            this.approvedby = val.empID;
            
        },
        addTransmittal(){
            if(this.isFormValid)
            {
                this.isDisable = true;
                let params = {};
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        if(key == 'entries') {
                            params[key] = JSON.stringify(this.$data[key]);
                        }else{
                            params[key] = this.$data[key];   
                        }
                    }
                }
                axios.post('api/addtransmittal', params).then(({data})=>{
                    this.$parent.addRow({...data, search_employee: this.search_employee});
                    $("#reportTransmittalModal").modal("hide");
                }).catch((err)=>{console.log(err);});
            }
        },
        updateTransmittal(){
            if(this.isFormValid)
            {

                this.isDisable = true;
                let params = {};
                for (const key in this.$data) {
                    if (!excludeBody.includes(key)) {
                        if(key == 'entries') {
                            params[key] = JSON.stringify(this.$data[key]);
                        }else{
                            params[key] = this.$data[key];   
                        }
                    }
                }
                axios.post('api/updatetransmittal', params).then(({data})=>{
                    this.$parent.updateRow({...data, search_employee: this.search_employee});
                    $("#reportTransmittalModal").modal("hide");
                }).catch((err)=>{console.log(err);});
            }
        },
        deleteTransmittal(){
            this.isDisable = true;
            axios.post('api/deletetransmittal/'+this.transID).then((response)=>{
                this.$parent.deleteRow(this.transID);
                $("#reportTransmittalModal").modal("hide");
            }).catch((err)=>{ console.log(err); }); 
        },
        appendTable(){
            if(this.doctype != '' && this.refnum != '')
            {
                this.entries.push({
                    doctype: this.doctype,
                    refnum: this.refnum,
                    docstatus: 'For Approval',
                    docremarks: '',
                });
            }
        },
        removeRow(index)
        {
            this.entries.splice(index, 1);
        },
        // ACTIONS FOR Supplementary I.E APPROVE / REJECT / CANCEL
        requestActionTransmittal(status = null){
            let params =  {}; 
            params['status'] = statCode.indexOf(this.computedStatus);
            if(status == 3) {
                params['status'] = status;
            }
            if(status == 4) {
                params['status'] = 1;
            }
            for (const key in this.$data) {
                if (!excludeBody.includes(key)) {
                    if(key == 'entries') {
                        params[key] = JSON.stringify(this.$data[key]);
                    }

                    if(key == 'remarks') {
                        params[key] = this.$data[key];   
                    }
                }
            }
            params['transID'] = this.transID;
            axios.post('api/actiontransmittal', params).then(({data})=>{
                this.$parent.updateRow({
                    ...data,
                    transID: this.transID,
                    datefiled: this.datefiled,
                    search_employee: this.search_employee,
                    additionalInfo: this.additionalInfo,
                    approvedby: this.approvedby,
                    fullname: this.selected.fullname
                });
                $("#reportTransmittalModal").modal("hide");
            }).catch((err)=>{ console.log(err); });
        },

        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'entries' && i != 'employeeList' && i != 'computedStatus' && i != 'addressList')
                    this.$data[i] = data[i];
                if(i == 'entries' )
                    this.$data[i] = JSON.parse(data[i]);
                if(i == 'request_type')
                    this.$data[i] = data[i]? (data[i]).split(',') : [];
            }

            this.MDBINPUT();
            $("#reportTransmittalModal").modal("show");
        },
        selectSupDate(val){
            this.supdate = moment(val).format('YYYY-MM-DD');
        },

        closeModal(){
            let obj = this.$data;
            $('#printSection').remove();
            Object.keys(obj).forEach((key)=>{
                if(
                    key != 'employeeList'
                )
                    this.$data[key] = '';
                if(key=='isDisable'){
                    this.$data[key] = false;
                }
                if(key === 'entries' || key == 'request_type')
                {
                    this.$data[key] = [];
                }
                if(key == 'datefiled'){
                    this.$data[key] = moment(new Date()).format('MM/DD/YYYY HH:mm:ss');
                }
                if(key == 'computedStatus')
                {
                    this.$data[key] = 'In Transit';
                }
                if(key == 'status')
                {
                    this.$data[key] = 0;
                }

            });

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
                   return this.fields[key].invalid
            });
        },
        hasEntries(){
            return this.entries.length > 0;
        },
        submitBtn()
        {
            return !this.transID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.transID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput
        },
        approveRejecBtn(){
            return this.transID && this.$parent.$data.forapprover == 'approval' && !this.$parent.$data.isCancel;
        },
        cancelBtn(){
            return this.transID && this.$parent.$data.forapprover == 'approval' && this.$parent.$data.isCancel;
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
        bus.$off('setupdateTransmittalRep', this.setDataForEdit)
    },
    mounted(){
        
        this.MDBINPUT();
        

        // MODAL
        $('#reportTransmittalModal').on("hidden.bs.modal", this.closeModal);
        // EVENT BUS
        bus.$on('setupdateTransmittalRep', this.setDataForEdit);

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