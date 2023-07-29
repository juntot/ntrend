<style lang="css">
#deliverysystemtbl_wrapper{
  margin-top: 25px;
}
#deliverysystemtbl_wrapper div.dt-button-background{
    background: transparent;
}
#deliverysystemtbl_wrapper div.dt-button-collection{
    overflow: initial;
    border:0;
    box-shadow: none;
    padding: 0;
    margin-top: 0;
}
#deliverysystemtbl_wrapper .dropdown-menu{
    display: block;
    padding: 5px;
    box-shadow: 3px 4px 10px 1px rgb(0 0 0 / 30%);
}

#deliverysystemtbl_wrapper .dropdown-menu a{
    border: 0;
}

#deliverysystemtbl_wrapper button.btn-secondary,  #deliverysystemtbl_wrapper button.btn-secondary:hover{
    background: #7978E9;
    color: white;
}

#deliverysystemtbl_wrapper div.dt-button-collection a.dt-button.active:not(.disabled) {
    color: #fff;
    background-color: #dadada;
    background: #007bff;
    filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr="#f0f0f0", EndColorStr="#dadada");
    box-shadow: none;
}
#deliverysystemtbl_wrapper div.dt-buttons.btn-group.flex-wrap{
  margin: auto;
  width: auto;
}
#deliverysystemtbl_wrapper div.dt-buttons.btn-group.flex-wrap div.btn-group{
  width: auto;
  margin-top: auto;
}
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			  {{formtitle}}
        </div>
        <div class="col-lg-12 margin-15">
            <span style="font-size: 12px; color: #434242;">Note: For a optimal performance, we advice to generate records per day </span>
        </div>
        <div class="col-md-4">
            <div class="mdb-form-field form-group-limitx">
                <div class="relative-pos">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput" type="text" :value="company" name="company"  class="form-field__input"
                        @focus="compFocus = true"
                        @blur="handleBlur"
                        >
                        <label class="form-field__label">Company</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors">{{ errors.first('company') }}</span>
                    <!-- loader and err msg -->
                    <div v-if="loader2">
                        <i class="fas fa-spinner fa-spin"></i> 
                        <span class="errors" style="padding-left: 8px">{{errMsg}}</span>
                    </div>
                    <div class="absolute-pos bg-white suggestion_filter" 
                        v-if="compFocus">
                        <ul>
                            <li v-for="comp in compRows" 
                            @click.prevent="selectCompany(comp)"
                            :key="comp.id">
                                {{comp.name}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- <div class="mdb-form-field form-group-limitx">
                <div class="relative-pos">
                    <div class="form-field__control">
                        <input :disabled="$parent.disabledinput || isDisable || !company" type="text" v-model="search_customer" 
                            @keyup.prevent="getCustomer"
                            v-validate="''" name="customer name"  class="form-field__input">
                        <label class="form-field__label">Customer Name</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span v-if="!search_customer" class="errors">
                        {{ search_customer && !customerList.length ? 
                            'customer not found':''||  errors.first('customer name') }}
                    </span>
                    <div class="absolute-pos bg-white suggestion_filter" 
                        v-if="search_customer && !customer_name && customerList.length > 0 || custLoader  && !customer_name
                    ">
                        <-- custLoader and err msg -- >
                        <div v-if="custLoader" style="padding-left: 8px">
                            <i class="fas fa-spinner fa-spin"></i> 
                            <span class="errors" style="padding-left: 8px">{{custErrMsg}}</span>
                        </div>
                        <ul>
                            <li v-for="customer in customerList" 
                            @click.prevent="selectedCustomer(customer)"
                            :key="customer.CardCode">
                                {{customer.CardName}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div> -->
            <div class="mdb-form-field form-group-limitx">
                <div class="form-field__control">
                    <select v-model="branchID_" id="branchname" name="branchname" @change="selectedBranch" v-validate="'required'" class="form-field__input">
                        <option :value="item.branchcode" v-for="(item,key) in branchRows"  :key="key">{{ item.branchname }}</option>
                    </select>
                    <label class="form-field__label">Branch</label>
                    <div class="form-field__bar"></div>
                </div>
                <span class="errors">{{ errors.first('branch') }}</span>
                <div v-if="loader3">
                    <i class="fas fa-spinner fa-spin"></i> 
                    <span class="errors" style="padding-left: 8px">{{errMsg3}}</span>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-4 col-md-4">
            <Datepicker :value="datefrom" @selected="selectDateFrom" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                <label slot="afterDateInput" class="form-field__label">Date From</label>
                <div slot="afterDateInput" class="form-field__bar"></div>
                <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
            </Datepicker>
        </div>
        <div class="col-lg-4 col-md-4">
            <Datepicker :value="dateto" @selected="selectDateTo" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                <label slot="afterDateInput" class="form-field__label">Date To</label>
                <div slot="afterDateInput" class="form-field__bar"></div>
                <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
            </Datepicker>
        </div>
        <div class="col-lg-4 col-md-4  with-margin-bottom">
            <br>
            <button class="btn btn-primary" @click.prevent="getInvoiceReport" :disabled="isDisable || !isFormValid">Generate</button>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 margin">
            <div class="clearfix"></div>
            <table id="deliverysystemtbl" class="mdl-data-table" style="width:100%"></table>


            <!-- Loader Modal -->
            <div id="deliverysystemLoaderModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body text-center">
                            <div v-if="loaderNestedSapRequest">
                                <i class="fas fa-spinner fa-spin" style="font-size: 2em;"></i> 
                                <br><br>
                                <p class="success">Please wait while requesting records from your SAP System</p>
                            </div>
                            <div v-if="loaderNestedSapRequestError">
                                <p><span class="errors">An error occured Please contact your Network Administrator</span></p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
             <!---END MODAL-->
             
            <!-- Modal -->
            <div id="deliverysystemModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <div v-if="loaderNestedSapRequest">
                                    <i class="fas fa-spinner fa-spin" style="font-size: 2em;"></i> 
                                    <br><br>
                                    <p class="success">Please wait while requesting records from your SAP System</p>
                                </div>
                                <div v-if="loaderNestedSapRequestError">
                                    <p><span class="errors">An error occured Please contact your Network Administrator</span></p>
                                </div>
                            </div>
                            <ManageDeliverySystem v-show="!loaderNestedSapRequest && !loaderNestedSapRequestError" :userinfo="$root.userinfo" :selected="selected"></ManageDeliverySystem>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


            

        </div>

        
    </div>
</template>
<script>
import ManageDeliverySystem from '../../components/public/ManageDeliverySystem';
let excludedBranch = [
    'EMPLOYEE', 'REVOLVING FUND', 'EMPLOYEE SAVINGS', 'NONTRADE', 'CUSTOMER W/ REBATES',
    'MARINE', 'TRADE', 'CUSTOMER W/ REBATES'
];
const branchCodes = {
    BAC: 'BAC',   // BACOLOD
    BOH: 'BHL',   // BOHOL
    CAG: 'CDO',   // CAGAYAN
    CEB: 'CBU',   // CEBU
    DAV: 'DVO',   // DAVAO
    DUM: 'DGT',   // DUMAGUETE
    ILO: 'ILO',   // ILOILO
    MAN: 'MLA',   // MANILA
    TAC: 'TAC',   // TACLOBAN
    // MAR: ,   // MARINE
    // TRA: ,   // TRADE
    // CUS: ,   // CUSTOMER W/ REBATES
};













export default {
    components:{
        ManageDeliverySystem
    },
    data(){
        return{
            forapprover: '',
            formtitle: '',
            rows: [],
            disabledinput: false,
            dtHandle: null,
            loader: true,
            approvers: [],
            reciever_emails: [],

            skip: 0,
            isDisable: true,
            selected: {},
            // trIndex:'',

            datefrom: moment(new Date()).format('YYYY-MM-DD'),
            dateto: (moment(new Date()).add(1, 'days')).format('YYYY-MM-DD'),
            compFocus: false,
            loader2: false,
            loader3: false,
            loaderNestedSapRequest: false,
            loaderNestedSapRequestError: false,
            company:'',
            compRows: [],
            branchRows: [],
            branchID_: '',
            branchcode: '',

            // customer
            custLoader: false,
            search_customer: '',
            customer_name: '',
            customerList: [],
            selectedCust: '',
        }
    },
    watch:{
        rows(val, old){
            let row = val;
            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();
        },
        approvers(val, old){
            val.forEach(item=>{
                this.reciever_emails.push(item.email);
            })
        },
        
    },
    methods:{
        selectDateFrom(val){
            this.datefrom = moment(val).format('YYYY-MM-DD');
        },
        selectDateTo(val){
            this.dateto = moment(val).format('YYYY-MM-DD');
        },
        addRow(val)
        {
            this.rows.unshift(val);
        },
        deleteRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.enrollmentID == val)
                {
                    this.$data.rows.splice(index, 1);
                    $("#deliverysystemModal").modal("hide");
                }

            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.DocNum == val.DocNum_)
                {
                    let data =  {...this.selected, ...val};
                    
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(data);
                    
                }
            });

        },

        handleBlur(e){
            e.preventDefault();
        },
        selectCompany(val){
            this.dtHandle.clear();
            this.dtHandle.draw();
            this.closeModal(false);
            this.selectedComp =  val.name;
            this.company = val.name;
            this.compFocus = false;
            this.disableCustomerField = true;
            this.customerList = [];
            this.MDBINPUT();
            
            
            this.loader2 = true;
            this.errMsg = '';
            
            axios.post('api/override-login', {
                id: val.id,
                type: 'company',
            })
            .then(({status})=>{
                if(status == 200) {
                    this.isDisable = false;
                    this.loader2 = false;
                    this.disableCustomerField = false;
                }else{
                    this.errMsg = 'An error occured when connecting please contact your IT-Department';    
                }
                


            })
            .catch((e)=>{
                console.log(e);
                this.errMsg = 'Unable to connect to SAP-DB please contact your IT-Department';
                this.isDisable = true;
            });


            
        },
        getCustomer(){
            this.customer_name = '';
            this.selectedCust = '';
            this.custErrMsg = '';
            this.custLoader = true;
            const searchStr = ((this.search_customer).trim()).toUpperCase();
            axios.post(`api/consume-api`, {
                query:'BusinessPartners?$select=CardCode,GroupCode,CardName',
                params: `&$filter=(startswith(CardName,\'${searchStr}\')) or startswith(CardCode, \'${searchStr}\')`,
                order: '&$orderby=CardName&$top=1000',
                method: 'GET'
            })
            .then(({data})=>{
                if(data.status == 200) {
                    this.custLoader = false;
                    this.customerList = data.data.value;     
                }else{
                    this.custErrMsg = 'Invalid data please try again';
                }
                
            }).catch(()=>{
                this.custErrMsg = 'Network problem please contact your IT-Department';
            });
        },
        selectedCustomer(val) {
            this.search_customer = val.CardName;
            this.customer_name = val.CardName;
            this.selectedCust = val;
        },
        selectedBranch(e){

            // return this.cardCode = branchCodes[e.target.value];
            return this.branchcode = e.target.value;
            
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
    getInvoiceReport(){
        this.rows = [];
        this.loader = true;
        this.isDisable = true;
        // let path = `$select=DocEntry,DocTotal,DocNum,DocDueDate,U_COMMITMENT,CardCode,CardName,DocumentStatus,DocumentDelivery,DocumentsOwner,SalesPersonCode,Comments,DocumentLines&$filter=U_COMMITMENT ge '${this.datefrom}' and U_COMMITMENT le '${this.dateto}'`;
        // path = this.selectedCust? path + ` and contains(CardCode, '${this.selectedCust.CardCode }')` : path ;

        // let path = `$crossjoin(Invoices,BusinessPartners,BusinessPartnerGroups)?$expand=Invoices($select=DocNum,U_RCVREMARKS),BusinessPartnerGroups($select=Name)&$filter=Invoices/CardCode eq BusinessPartners/CardCode and BusinessPartners/GroupCode eq BusinessPartnerGroups/Code and 
        // BusinessPartnerGroups/Name eq`;
        
        let path = `$select=DocEntry,DocTotal,DocNum,DocDueDate,DocDate,U_COMMITMENT,U_RCVREMARKS,CardCode,CardName,DocumentStatus,DocumentDelivery,DocumentsOwner,SalesPersonCode,Comments,DocumentLines&$filter=DocDate ge '${this.datefrom}' and DocDate le '${this.dateto}'`;
        path = this.branchcode? path + ` and U_BRANCH eq '${this.branchcode}'` : path ;


         axios.post(`api/get-sap-invoice`, {
            query:'Invoices?',
            params: path,
            method: 'GET'
        })
        .then((res)=>{
            // return;
            if(res.status == 200) {
                this.rows = res.data.data;
                this.skip = res.data.next || '';
                this.isDisable = false;

            }else{
                this.errMsg = 'Invalid data please try again';
            }
            this.loader = false;
            
        }).catch(()=>{
            this.loader = false;
            this.errMsg = 'Network problem please contact your IT-Department';
        });
    },
    async getNextInvoiceReport(){
        
        await $('.mdl-button.next').attr('disabled', 'disabled');

        axios.post(`api/get-sap-invoice`, {
            query: this.skip,
            method: 'GET'
        })
        .then((res)=>{
            if(res.status == 200) {
                this.rows = [...this.rows, ...res.data.data];
                this.skip = res.data.next || '';
                this.isDisable = false;
            }else{
                this.errMsg = 'Invalid data please try again';
            }
            this.loader = false;
            
        }).catch(()=>{
            this.loader = false;
            this.errMsg = 'Network problem please contact your IT-Department';
        });  
    },
    async setUpdate(data){

        this.loaderNestedSapRequest = true;
        bus.$emit('setupdate', '');
        
        try {

            const resDocOwner = await axios.post(`api/get-sap-details`, {
                                query:'EmployeesInfo?',
                                params: `$select=FirstName,LastName&$filter=EmployeeID eq ${data.DocumentsOwner}`,
                                method: 'GET'
                            });

            data['DocumentsOwnerName'] = await resDocOwner.data.data.value[0]['FirstName']+' '+resDocOwner.data.data.value[0]['LastName'];  
            // console.log(resDocOwner.data.data.value[0]['FirstName']+' '+resDocOwner.data.data.value[0]['LastName']);
                        
            const resSalesPerson = await axios.post(`api/get-sap-details`, {
                                query:'SalesPersons?',
                                params: `$filter=SalesEmployeeCode eq ${data.SalesPersonCode}`,
                                method: 'GET'
                            });
            
            data['SalesPersonName'] = await resSalesPerson.data.data.value[0]['SalesEmployeeName'];  

            let resWareHouse = '';
            for (const [index, item] of data.DocumentLines.entries()) {
                 resWareHouse = await axios.post(`api/get-sap-details`, {
                                query:'Warehouses?',
                                params: `$select=WarehouseCode,WarehouseName&$filter=WarehouseCode eq '${item.WarehouseCode}'`,
                                method: 'GET'
                            });

                  data.DocumentLines[index]['WarehouseName'] = await resWareHouse.data.data.value[0]['WarehouseName'];
            }

        } catch (error) {
            this.loaderNestedSapRequest = false;
            this.loaderNestedSapRequestError = true;
            return;
        }
        this.loaderNestedSapRequest = false;
        this.loaderNestedSapRequestError = false;
        this.selected = await data;
        bus.$emit('setupdate',  data);
         
        
        
    },

    closeModal(){
        this.disabledinput = false;
        this.selected = {};
        this.loaderNestedSapRequest = false;
        this.loaderNestedSapRequestError = false;
    },

    },
    computed:{
        isFormValid(){
            return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
    },
    created(){
         // SAP API
        // axios.get(SAP+'/login')
        // .then(({data})=>{
        //     console.log(data);
        //     // ?$select=CardCode,CardName,CardType&$filter=startswith(CardCode, 'C') &$orderby=CardCode&$top=10&$skip=1,
        //     // this.customer_list = data.value;
        // });
        axios.get('api/get-override-company')
        .then(({data})=>{
            data.forEach( val => {
                if(val.type == 'company') {
                    this.compRows = (val.json);
                }
                // else if(val.type == 'division') {
                //     this.divRows = JSON.parse(val.json)
                // }
                // else {
                    // this.branchRows = JSON.parse(val.json);
                // }
            });
        });
    },
    beforeDestroy(){
        // $('body').removeClass('visible');
    },
    mounted(){
        
        this.formtitle = this.$route.name;

        // to fix print butotn datatable
        // $('body').addClass('visible');
        

        // axios.get('api/getenrollmentprog').then((response)=>{
        //     this.loader = false;
        //     // this.rows=response.data;

        // }).catch((err)=>{
        //     console.log(err);

        // });

        axios.get(`api/get-delivery-branch`).then(({data})=>{
            this.loader = false;
            this.branchRows = data;

        })
        .catch((err)=>{
            console.log(err);
            // this.errMsg = 'Unable to connect to SAP-DB please contact your IT-Department';
            this.isDisable = true;
        });

        $.extend(jQuery.fn.dataTableExt.oSort, {
                "date-uk-pre": function (a) {
                    var x;
                    try {
                        var dateA = a.replace(/ /g, '').split("/");
                        var day = parseInt(dateA[1], 10);
                        var month = parseInt(dateA[0], 10);
                        var year = parseInt(dateA[2], 10);
                        var date = new Date(year, month - 1, day)
                        // x = date.getTime();
                        x = moment(a).valueOf();
                    }
                    catch (err) {
                        x = new Date().getTime();
                    }

                    return x;
                },

                "date-uk-asc": function (a, b) {
                    return a - b;
                },

                "date-uk-desc": function (a, b) {
                    return b - a;
                }
            });

        let columnDefs = [
        {
            title: "Invoice#", data: 'DocNum', visible: true,
        },
        {
            title: "Delivery Date", data: 'DocDate', render: function(data){
                return moment(new Date(data)).format('MM/DD/YYYY');
            }
        },
        {
            title: "Delivered/Pickup Date", data: 'deliverydate', render: function(data){
                return data ? moment(data).format('MM/DD/YYYY') : '';
            }, 
            visible: false,
        },
        {
            title: 'Received/Pickup By', render: function(data, type, row){
                return row.receivedby || '';
            },
            visible: false
        },
        {
            title: 'Customer Code', data: 'CardCode',
            visible: false
        },
        {
            title: 'Customer Name', data: 'CardName',
        },
        {
            title: 'Driver', render: function(data, type, row){
                return row.driver || '';
            },
            visible: false
        },
        {
            title: 'Vehicle #', render: function(data, type, row){
                return row.vehiclenum || '';
            },
            visible: false
        },
        {
            title: 'Sales Employee', // sales employee ad new col
            render: function(data, type, row){
                return row.SalesPersonName || '';
            },
            visible: false
        },
        {
            title: 'Prepared By', // add new col
            render: function(data, type, row){
                return row.DocumentsOwnerName || '';
            },
            visible: false
        },
        {
            title: 'Updated By', render: function(data, type, row){
                return row.updatebyFullName || '';
            }
        },
        {
            title: "Status", data: 'DocumentStatus', render: function(data){
                return data.replace('bost_', '');
            },
            visble: false,
        },
        ];
        let self = this;
        this.dtHandle=$('#deliverysystemtbl').DataTable({
        pageLength: 100,
        aoColumnDefs: [{ "sType": "date-uk", "aTargets": [1] }],
        data: [],
        columns: columnDefs,
        dom: 'Bfrtip',
        // dom: '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
        scrollX: true,
        order: [[ 1, "desc" ]],
          buttons: [
            {
              extend: 'csv',          
              title: '',         
              exportOptions: {
                columns: ':not(.noShow):visible'
              }
            },
            {
                  extend: 'print',
                  autoPrint: true,
                  title: '',
                  exportOptions: {
                      columns: ':visible'
                  },
                  customize: function ( win ) {
                    // $(win.document.body)
                    //     .css( 'visibility', 'visible !important' );
                    $(win.document.body).addClass( 'visible' );
                        // .addClass( 'compact' )
                    //     .css( 'font-size', 'inherit' );
                }
            },
            'colvis'
          ],
          rowCallback: function(row, data, index) {
            var cellValue = data["isSuccess"];
                // if (cellValue=="200") {
                //     $(row).addClass("tr-pending");
                // }
                if (cellValue==200) {
                    $(row).addClass("tr-verified");
                }
                if (cellValue==400) {
                    $(row).addClass("tr-rejected");
                }

          },
          drawCallback: function(){
            if(self.skip)
            $('.mdl-button.next.disabled', this.api().table().container()).removeAttr('disabled');

            $('.mdl-button.next.disabled', this.api().table().container())          
             .on('click', function(){
                self.getNextInvoiceReport();
             });  
          },
          
        });
        

        let table = this.dtHandle;
        // table.buttons().container().appendTo( '#order-listing_wrapper .col-md-6:eq(0)' );
        // let rows = this.rows.length ;
        
        // Add event listener for opening and closing details
        $("#deliverysystemtbl tbody").on('click', 'tr', function() {
            // this.trIndex = $(this);
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            
            let dataforedit = row.data();
            dataforedit['company'] = self.company;
            dataforedit['branchcode'] = self.branchcode;
            // dataforedit.entries = JSON.parse(dataforedit.entries);
            // self.selected = dataforedit;
            self.setUpdate(dataforedit);
        });
        
         // MODAL
        $('#deliverysystemModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
          $('html').addClass('hidden-overflow');
        })
        $(".modal").on('hide.bs.modal', ()=>{
            
          $('html').removeClass('hidden-overflow');
        });
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