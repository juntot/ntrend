<style lang="css">
.text-white{
    color: white !important;
}

a.text-white{
    text-decoration: underline;
    color: white !important;
}
a.text-white:hover{
    color: white !important;
}
a.text-white:active{
    color: white !important;
}

.mb-0{
    margin-bottom: 0;
}

.delivery-log-records{
    border: 1px solid #3f51b5;
    clear: both;
    display: none;
    margin: 0 15px;
}

.record-log-items{
    background: aliceblue;
    padding: 10px;
    margin-bottom: 5px;
}

.record-log-items strong{
    font-weight: 600 !important;
}
ul .record-log-items{
    list-style-type: none;
}

</style>
<template>
    <div>
       <form method="post">
            <h3 class="text-center form-title"><span class="dblUnderlined">{{'Delivery System'}}</span></h3>
            <!-- <div class="col-md-12">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="datefiled" name="name" readonly="true">
                        <label class="form-field__label">Date Filed</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div> -->

            <!--  -->
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="selected.CardCode" name="middlename" readonly="true">
                        <label class="form-field__label">Customer Code</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="selected.DocDate | filterPosDate" name="mobile2" readonly="true" >
                        <label class="form-field__label">Delivery Date</label>
                        <div class="form-field__bar"></div>
                        <span class="errors">{{ errors.first('mobile2') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="selected.DocumentStatus | filterStatus" name="mobile1" readonly="true">
                        <label class="form-field__label">Status</label>
                        <div class="form-field__bar"></div>
                        <span class="errors">{{ errors.first('mobile1') }}</span>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-md-8">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="selected.CardName" name="lastname" readonly="true">
                        <label class="form-field__label">Customer Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input type="text" class="form-field__input" :value="selected.DocNum" name="branch" readonly="true">
                        <label class="form-field__label">Invoice Number</label>
                        <div class="form-field__bar"></div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <h5 class="tr-approved">Delivery Type</h5>
                <div class="col-md-2">    
                    <label class="mdblblradio">
                        <span class="checklbl" >Delivery</span>
                        <input :disabled="$parent.disabledinput" type="radio" v-model="deliverytype" value="delivery" name="radio">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="col-md-10">
                    <label class="mdblblradio">
                        <span class="checklbl">Pickup</span>
                        <input :disabled="$parent.disabledinput" type="radio" v-model="deliverytype" value="pickup" name="radio">
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <div class="clearfix"></div>
            <div class="col-md-12">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="selected.custCoordinatesAddrName" name="customer address" readonly="true">
                        <label class="form-field__label">Customer Address</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('customer address') }}</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="selected.site_execution" name="site execution" readonly="true">
                        <label class="form-field__label">Site Execution</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('site execution') }}</span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="selected.U_COMMITMENT | dateLogs" v-validate="'required'" name="commitment date">
                        <label class="form-field__label">Commitment Date</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('commitment date') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <Datepicker :value="deliverydate" v-validate="'required'" @selected="selectDeliveryDate" wrapper-class="mdb-form-field form-group-limitx" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                    <label slot="afterDateInput" class="form-field__label">Delivered/Pickup (Date)</label>
                    <div slot="afterDateInput" class="form-field__bar"></div>
                    <span slot="afterDateInput" class="errors">{{ errActualDateDelivered }}</span>
                </Datepicker>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" v-model="receivedby" v-validate="'required'" name="receivedby">
                        <label class="form-field__label">Received/Pickup By</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('receivedby') }}</span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4">
                     <div class="mdb-form-field form-group-limitx">
                        <div class="relative-pos">
                            <div class="form-field__control">
                                <input :disabled="$parent.disabledinput || deliverytype == 'pickup'" type="text" v-model="search_driver" name="driver"  class="form-field__input"
                                @keyup.prevent="searchEmployee"
                                >
                                <label class="form-field__label">Driver</label>
                                <div class="form-field__bar"></div>
                            </div>
                            <span class="errors" v-if="!search_driver">{{ error_driver }}</span>
                            <div class="absolute-pos bg-white suggestion_filter" 
                                v-if="search_driver && !driver && employeeList.length > 0
                            ">
                                <!-- loader and err msg -->
                                <div v-if="loader">
                                    <i class="fas fa-spinner fa-spin"></i> 
                                    <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                                </div>
                                <ul>
                                    <li 
                                    @click.prevent="selectedEmployee('Walk-In')">
                                        Walk-In
                                    </li>
                                    <li v-for="emp in employeeList" 
                                    @click.prevent="selectedEmployee((emp.fullname).replace(',', ''))"
                                    :key="emp.empID">
                                        {{ (emp.fullname).replace(',', '') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || deliverytype == 'pickup'" type="text" class="form-field__input" v-model="vehiclenum" name="vehicle_no">
                        <label class="form-field__label">Vehicle No</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ error_vehiclenum }}</span>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-12">
                <h5 class="form-subtitle">
                    <em>Invoice Details</em>
                </h5>
            </div>
            <div class="col-md-12">
                <div class="mdb-table-overflow">
                            <table width="100%" class="table table-hover mdb-table">
                                    <thead>
                                        <tr>
                                            <th class="text-left" style="min-width:50px; width: 74px;">#</th>
                                            <th class="text-left" style="min-width:100px; width: 100px;">ITEM NO</th>
                                            <th class="text-left" style="min-width:100px;">ITEM DESCRIPTION</th>
                                            <th class="text-left" style="min-width:50px;">UOM</th>
                                            <th class="text-left" style="min-width:50px;">QTY</th>
                                            <th class="text-left" style="">WAREHOUSE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in selected.DocumentLines" :key="index">
                                            <td>{{ item.LineNum + 1 }}</td>
                                            <td>{{ item.ItemCode }}</td>
                                            <td>{{ item.ItemDescription }}</td>
                                            <td>{{ item.MeasureUnit }}</td>
                                            <td>{{ item.Quantity }}</td>
                                            <td>{{ item.WarehouseName }}</td>
                                        </tr>
                                    </tbody>
                            </table>
                </div>
            </div>
            <div class="col-md-12">
                <h5 class="form-subtitle">
                    <em></em>
                </h5>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="selected.SalesPersonName">
                        <label class="form-field__label">Sales Employee</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="selected.DocumentsOwnerName">
                        <label class="form-field__label">Prepared By</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors"></span>
                </div>
            </div>
            <div class="col-lg-12">
                <h5 class="form-subtitle"></h5>
                <div class="mdb-form-field">
                        <div class="form-field__control mdb-bgcolor">
                            <textarea :disabled="$parent.disabledinput" class="form-field__textarea" id="" cols="4" rows="4" v-model="remarks" v-validate="'max:250'" name="remarks" ></textarea>
                            <label class="form-field__label">Remarks</label>
                            <div class="form-field__bar"></div>
                        </div>
                        <span class="errors">{{ errors.first('remarks') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mdb-form-field form-group-limitx">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" class="form-field__input" :value="userinfo.fullname" name="updatedby">
                        <label class="form-field__label">Updated By</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('updatedby') }}</span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12" v-show="historylogs.length > 0">
                <h5 class="form-subtitle mb-0">
                    <em><a href="#" class="text-white" @click.prevent="showHistory">change logs</a></em>
                </h5>
            </div>
            <div class="delivery-log-records" v-show="historylogs.length > 0">
                <ul>
                    <li class="record-log-items" v-for="(val, index) in historylogs" :key="index">
                        <strong>Updated By:</strong> {{ val.updatebyFullName }} &nbsp;&nbsp;&nbsp;&nbsp;
                        <strong>Updated On:</strong> {{ val.updateddate | dateTimeLogs }}
                        <br>
                        <strong>Received/Pickup By:</strong> {{ val.receivedby }}
                        <br>
                        <strong>Date Delivered:</strong> {{ val.deliverydate | dateLogs }}
                        <br>
                        <strong>Driver:</strong> {{ val.driver }}
                        <br>
                        <strong>Vehicle Num:</strong> {{ val.vehiclenum }}
                        <br>
                        <strong>Site Execution:</strong> {{ val.siteExecutionAddrName || '' }}
                    </li>
                </ul>
            </div>
            
            <!-- status is rejected or approve -->
           
            <!-- <div class="clearfix"></div>
            <div class="col-lg-12" v-if="!$parent.disabledinput">
                    <p class="text-info">
                        LIST OF APPROVERS:
                    </p>
                    <div class="clearfix"></div>
                    <span class="approverlist alert-info" v-for="(approver, key) in $parent.approvers" :key="key">{{approver.approvers}}</span>
                    <br><br>
            </div> -->
            <div class="clearfix"></div>
            <br>
            <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Save" @click.prevent="updateCard" :disabled="disabledIfNoApprover || isDisable || !isFormValid || !isBtnValid" v-if="submitBtn">
                    <!-- <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateCard" :disabled="isDisable || !isFormValid || !isBtnValid" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Delete" @click.prevent="deleteCard" :disabled="isDisable" v-if="updateDeleteBtn">
                    <input type="submit" class="btn btn-primary" value="Approve" @click.prevent="requestActionCard(1)" v-if="approveRejecBtn">
                    <input type="submit" class="btn btn-primary" value="Reject" @click.prevent="requestActionCard(2)" v-if="approveRejecBtn"> -->
                    <!-- <input type="submit" class="btn btn-primary" value="Cancel" @click.prevent="requestActionCard(0)" v-if="cancelBtn"> -->
            </div>
        </form>

    </div>
</template>
<script>
// let excludeBody = [
//             'search_driver',
//             'employeeList'
// ];

export default {
    props: ['userinfo', 'selected'],
    data() {
		return {
        addressName: '',
        empID_: '',
        datefiled: moment(new Date()).format('MM/DD/YYYY'),
        deliverydate: moment(new Date()).format('YYYY-MM-DD'),
        
        isDisable: false,
        remarks: '',


        receivedby: '',
        updatedby_: '',
        updatebyFullName: '',
        driver: '',
        vehiclenum: '',
        deliverytype: 'delivery',
        historylogs: [],
        errActualDateDelivered: '',


        search_driver: '',
        employeeList: [],
        // driver: '',
        errMsg: '',

        error_driver: '',
        error_vehiclenum: '',

		}
    },
    watch:{
        userinfo(val, old){
            this.MDBINPUT();
        },
        deliverydate(val, old){
            // this.errActualDateDelivered = val == moment(new Date()).subtract(1, 'years').format('YYYY-MM-DD')? 'Invalid Date': '';
        },
        deliverytype(val, old){
            if(val == 'delivery' && (this.vehiclenum == '' || this.driver == '' )) {
                this.error_vehiclenum = 'This field is required.';
                this.error_driver = 'This field is required.';
                this.isDisable = true;
            }else{
                this.error_vehiclenum = '';
                this.error_driver = '';
                this.isDisable = false;
            }
            
        }
    },
    filters:{
    
            dateLogs: function(val){
                return moment(val).format('MM/DD/YYYY');
            },
            dateTimeLogs: function(val){
                return moment(val).format('MM/DD/YYYY hh:mm a');
            },
            filterStatus: function(val){
                return val? val.replace('bost_', ''): '';
            },
            filterPosDate: function(val){
                return moment(val).format('MM/DD/YYYY');
            }

    },
    methods:{
        selectDeliveryDate(val){
            this.deliverydate = moment(val).format('YYYY-MM-DD');
        },
         searchEmployee(){
            this.loader = false;
			let validSearch = /^[a-zA-Z0-9, Ññ)\._-]+$/g
			let regex = RegExp(validSearch);
            this.driver = '';
            this.errMsg = '';
            
			if(regex.test((this.search_driver))){
				
                // FOR UPDATE GET ONLY THE LAST NAME
                let search = '';
                // if(this.selected.overrideID) {
                //     search = (this.search_driver).split(", ");
                //     search = search[0];
                //     // return;
                // }else{
                    search = this.search_driver;
                // }
                
                
				axios.post('api/search-override-emp',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.employeeList = res.data;
					}
                    this.loader = false;
				})
				.catch(err => this.errMsg = 'Network problem please contact your IT-Department');
			}

		},
        selectedEmployee(val, tag = 'employee') {
            if(tag == 'manager') {
                this.search_manager = val;
                this.sales_manager = val;
            } else {
                this.search_driver = val;
                this.driver = val;
            }
            
        },
        async updateCard(){
            await this.getLocation(this.processUpdate);
            return;
            // if(this.deliverydate == moment(new Date()).subtract(1, 'years').format('YYYY-MM-DD'))
            // return this.errActualDateDelivered = 'Invalid Date';
            if(this.deliverytype == 'delivery' && this.vehiclenum == ''){
                this.error_vehiclenum = 'This field is required.';
                return;
            } 

            if(this.deliverytype == 'delivery' && this.driver == ''){
                this.error_driver = 'This field is required.';
                return;
            }
            
            if(this.isFormValid)
            {
                
                this.isDisable = true;
               
                const dataRec = {
                    deliverydate: this.deliverydate,
                    receivedby: this.receivedby,
                    updatedby_: this.userinfo.empID,
                    updatebyFullName: this.computedfullname,
                    driver: this.driver,
                    vehiclenum: this.vehiclenum,
                    updateddate: moment().format('YYYY-MM-DD HH:mm:ss'),
                    company: this.selected.company,
                    remarks: this.remarks,
                    }   
                this.historylogs.push(dataRec)
                let params = {
                    path: `Invoices(${this.selected.DocEntry})`,
                    method: 'PATCH',
                    DocNum_: this.selected.DocNum,
                    updatebyFullName: this.computedfullname,
                    CardCode: this.selected.CardCode,
                    DocTotal: this.selected.DocTotal,
                    SalesPersonName: this.selected.SalesPersonName,
                    DocumentsOwnerName: this.selected.DocumentsOwnerName,
                    historylogs: JSON.stringify(this.historylogs),
                    branchcode: this.selected.branchcode,
                    deliverytype: this.deliverytype,
                    ...dataRec
                };
                // return;
                
                axios.post('api/update-delivery', params).then((response)=>{
                    // console.log(response.data);
                    // this.$parent.updateRow(response.data);
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{
                    // console.log(response.data);
                    this.$parent.updateRow(err.response.data);
                    this.closeModal();
                });
            }
        },
        showHistory(){
            $('.delivery-log-records').fadeToggle(500);
        },
        async setDataForEdit(data = null){
            
            
            for(let i in this.$data)
            {
                
                if(i != 'isDisable' && i != 'historylogs' && i != 'deliverydate' && i != 'search_driver' && i!= 'employeeList') 
                this.$data[i] = data[i];

                if(i == 'historylogs')
                this.$data[i] = data[i]? JSON.parse(data[i]) : [];

                if(i == 'deliverydate' && (data[i] != '' && data[i] != null ))
                this.$data[i] = data[i];

                if(i == 'remarks')
                this.$data[i] = data['U_RCVREMARKS'];

                if(i == 'driver'){
                    this.search_driver = data[i];
                    // console.log(i, data[i]);
                }   // parse json data here for customerList

            }
            if(!this.deliverytype)
            this.deliverytype = 'delivery';

            this.MDBINPUT();
            $("#deliverysystemModal").modal("show");
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
                if(key != 'datefiled' && key != 'historylogs' && key != 'employeeList' && key != 'deliverytype'){
                    this.$data[key] =  '';
                }
                if(key == 'datefiled'){
                    this.$data[key] =  moment(new Date()).format('MM/DD/YYYY');
                }
                if(key == 'deliverydate'){
                    this.$data[key] =  moment(new Date()).format('YYYY-MM-DD')
                }
                if(key == 'isDisable'){
                    this.$data[key] = false;
                }
                if(key == 'historylogs'){
                    this.$data[key] = [];
                }
                if(key == 'deliverytype'){
                    this.$data[key] = 'delivery'
                }
            });
            $("#deliverysystemModal").modal("hide");
        },
        getDistanceBetweenPoints(lat1, lng1, lat2, lng2){
            let R = 6378137;
            let dLat = this.degreesToRadians(lat2 - lat1);
            let dLong = this.degreesToRadians(lng2 - lng1);
            let a = Math.sin(dLat/2)
                    *
                    Math.sin(dLat /2)
                    +
                    Math.cos(this.degreesToRadians(lat1))
                    *
                    Math.cos(this.degreesToRadians(lat1))
                    *
                    Math.sin(dLong / 2)
                    *
                    Math.sin(dLong / 2);
            
            let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            let distance = R * c;
            return distance;

        },
        
        degreesToRadians(degrees){
            return degrees * Math.PI / 180;
        },

        async getGEOAddress(lat, lng){
            if(!lat || !lng)
            return;

            // return $.get({ url: `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&sensor=false&key=AIzaSyAvJeiIwxKvgUd5cpvMK6qQCZUzvE67ArE`, success(data) {
            //     return data.results[0].formatted_address;

            // }});
            const addr = await axios.get(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&sensor=false&key=AIzaSyAvJeiIwxKvgUd5cpvMK6qQCZUzvE67ArE`);
            return addr.data.results[0].formatted_address;
        },
        getLocation(callback){
                if (navigator.geolocation) {
                    
                     navigator.geolocation.getCurrentPosition(async (position)=>{
                        let distanceInMeters = this.getDistanceBetweenPoints(
                            position.coords.latitude, position.coords.longitude,
                            this.selected.custCoordinates[0], this.selected.custCoordinates[1]) * 0.001; //Calc Distance in KMS
                        
                        let siteExecution = await this.getGEOAddress(position.coords.latitude, position.coords.longitude);
                        return callback({lat: position.coords.latitude, lng: position.coords.longitude, distance: distanceInMeters, 
                                         siteExecutionAddrName: siteExecution || '' });
                    
                    });
                } else { 
                    alert("Geolocation is not supported by this browser.");
                    return false;
                }
        },
        processUpdate(data){
            // console.log('process', data);
            // if(data.distance > 1 ) //greater than 1km
            // return alert("You are too far from customer location. Maximum distance is 1km");            
            
            // historylogs

            // if(this.deliverydate == moment(new Date()).subtract(1, 'years').format('YYYY-MM-DD'))
            // return this.errActualDateDelivered = 'Invalid Date';
            if(this.deliverytype == 'delivery' && this.vehiclenum == ''){
                this.error_vehiclenum = 'This field is required.';
                return;
            } 

            if(this.deliverytype == 'delivery' && this.driver == ''){
                this.error_driver = 'This field is required.';
                return;
            }
            
            if(this.isFormValid)
            {
                
                this.isDisable = true;
               
                const dataRec = {
                    deliverydate: this.deliverydate,
                    receivedby: this.receivedby,
                    updatedby_: this.userinfo.empID,
                    updatebyFullName: this.computedfullname,
                    driver: this.driver,
                    vehiclenum: this.vehiclenum,
                    updateddate: moment().format('YYYY-MM-DD HH:mm:ss'),
                    company: this.selected.company,
                    remarks: this.remarks,
                    }   
                this.historylogs.push({
                    ...dataRec, 
                    siteExecutionAddrName: data.siteExecutionAddrName,
                    siteExecCoordinates: data.lat+', '+data.lng,
                    distance: data.distance 
                });
                    
                let params = {
                    path: `Invoices(${this.selected.DocEntry})`,
                    method: 'PATCH',
                    DocNum_: this.selected.DocNum,
                    updatebyFullName: this.computedfullname,
                    CardCode: this.selected.CardCode,
                    DocTotal: this.selected.DocTotal,
                    SalesPersonName: this.selected.SalesPersonName,
                    DocumentsOwnerName: this.selected.DocumentsOwnerName,
                    historylogs: JSON.stringify(this.historylogs),
                    branchcode: this.selected.branchcode,
                    deliverytype: this.deliverytype,
                    ...dataRec
                };
                // return;
                
                axios.post('api/update-delivery', params).then((response)=>{
                    // console.log(response.data);
                    // this.$parent.updateRow(response.data);
                    this.$parent.updateRow(response.data);
                    this.closeModal();
                }).catch((err)=>{
                    // console.log(response.data);
                    this.$parent.updateRow(err.response.data);
                    this.closeModal();
                });
            }
        }

    },
    computed:{
        disabledIfNoApprover(){
            return false;
        },
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
        isBtnValid(){
            return true;
            // let data = this.$data;
            // for(let key in data){
            //     if(key == 'ntmc' && data.ntmc ||
            //         key == 'apbw' && data[key] ||
            //         key == 'philcrest' && data[key] ||
            //         key == 'tyreplus' && data[key] ||
            //         key == 'solid' && data[key] ){

            //         return true;
            //     }
            // }
            // return false;
        },
        submitBtn()
        {
            return !this.ccID && this.$parent.$data.forapprover != 'approval';
        },
        updateDeleteBtn(){
            return this.ccID && this.$parent.$data.forapprover != 'approval' && !this.$parent.disabledinput
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
        $('#deliverysystemModal').on("hidden.bs.modal", this.closeModal);

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