<style>
/* hide overlapping labels for multi select dropdowns */
button.multiselect span.multiselect-selected-text{
    display: inline-block;
    width: 100%;
    overflow: hidden;
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
        <div class="col-lg-12 col-sm-12 col-md-12 margin-15 margin-bottom-15" v-show="docReady">
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

            <div class="col-lg-4 col-md-4">
                <div class="mdb-form-field form-field--is-filled">
                    <div class="form-field__control">
                        <select id="posname" v-model="selectedRepType" name="position" class="form-field__input" @change.prevent="getSelectedReportType">
                            <option value="select">No option selected</option>
                            <option v-for="(data, key) in reportType" :key="key" :value="((data.formtitle).replace(/ /g, '')).toLowerCase()">{{data.formtitle}}</option>
                        </select>
                        <label class="form-field__label">Form Type</label>
                        <div class="form-field__bar"></div>
                    </div>
                    <span class="errors"></span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-4 col-md-4">
                <div class="vdp-datepicker mdb-form-field form-field--is-filled">
                    <div class="multiple">
                            <select name="company" class="multiselect3 form-field__input" multiple="multiple">
                                <option :value="data.compID" v-for="(data) in company" :key="data.compID">{{data.compname}}</option>
                            </select>
                            <label class="form-field__label">Company</label>
                            <div class="form-field__bar"></div>
                    </div>

                    <span class="errors"></span>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="vdp-datepicker mdb-form-field form-field--is-filled">
                    <div class="multiple">
                            <select name="position" class="multiselect1 form-field__input" multiple="multiple">
                                <option :value="data.branchID" v-for="(data) in branches" :key="data.branchID">{{data.branchname}}</option>
                            </select>
                            <label class="form-field__label">Branches</label>
                            <div class="form-field__bar"></div>
                    </div>

                    <span class="errors"></span>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="vdp-datepicker mdb-form-field form-field--is-filled">
                    <div class="multiple">
                            <select name="stat" class="multiselect2 form-field__input" multiple="multiple">
                                <!-- <option v-for="stat in status_list" :value="stat.id" :key="stat.id">{{stat.name}}</option> -->
                                <!-- <option :value="1">Approved</option> -->
                                <!-- <option :value="2">Rejected</option>
                                <option :value="0">Pending</option> -->
                            </select>
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                    </div>

                    <span class="errors"></span>
                </div>
            </div>
            <!-- <div class="col-lg-4 col-md-4">
                <div class="mdb-form-field form-field--is-filled">
                     <div class="multiple">
                            <select name="stat" class="multiselect2 form-field__input" multiple="multiple">
                                <option :value="1">Approved</option>
                                <option :value="2">Rejected</option>
                                <option :value="0">Pending</option>
                            </select>
                            <label class="form-field__label">Status</label>
                            <div class="form-field__bar"></div>
                    </div>
                </div>
            </div> -->
            <div class="clearfix"></div>
            <div class="col-lg-12 col-md-12">
                <input type="submit" class="btn btn-primary" @click.prevent="getReport" value="Generate Report" :disabled="isDisabled || disabledBtn">
                <input type="submit" class="btn btn-primary" v-if="showGenerateChartBtn" @click.prevent="generateChart" value="Generate Chart" :disabled="isDisabled">
            </div>
            
        </div>
        <div class="clearfix"></div>
        <div>
                <!-- dynamic call components -->
                <div v-show="showGenerateChartBtn" >
                    <!-- <canvas id="myChart"></canvas> -->
                    <IRChart v-show="showChart"></IRChart>
                </div>
                <div class="clearfix"></div>
                <div v-for="(compo, index) in $options.components" :key="index" v-show="reportComponent[index] == selectedRepType && !ishiddenComponent">
                    <!-- <{{index}}></{{index}}> -->
                    <component :is="index" :title="title" v-if="index != 'IRChart'" />
                </div>
                
        </div>

    </div>
</template>
<script>
import ReportSupplementary              from './reports/ReportSupplementary';
import ReportCallingCardRequest         from './reports/ReportCallingCardRequest';
import ReportCompanyLoan                from './reports/ReportCompanyLoan';
import ReportFinancialAdvantage         from './reports/ReportFinancialAdvantage';
import ReportIncidentReport             from './reports/ReportIncidentReport';
import ReportLaptopAgreement            from './reports/ReportLaptopAgreement';
import ReportLeaveForm                  from './reports/ReportLeaveForm';
import ReportMIIS                       from './reports/ReportMIIS';
import ReportPRS                        from './reports/ReportPRS';
import ReportPRF                        from './reports/ReportPRF';
import ReportCanvas                     from './reports/ReportCanvas';
import ReportSalaryDiscrepancy          from './reports/ReportSalaryDiscrepancy';
import ReportSupplierAccreditation      from './reports/ReportSupplierAccreditation';
import ReportTravelForm                 from './reports/ReportTravelForm';
import ReportUndertimeRequest           from './reports/ReportUndertimeRequest';
import ReportUrgentCheck                from './reports/ReportUrgentCheck';
import ReportOffset                     from './reports/ReportOffset';
import ReportWorkRequest                from './reports/ReportWorkRequest';
import ReportOvertimeRequest            from './reports/ReportOvertimeRequest';
import ReportOverrideForm               from './reports/ReportOverrideForm';
import ReportTransmittal                from './reports/ReportTransmittal';
import ReportEnrollmentProgram          from './reports/ReportEnrollmentProgram';
import IRChart                          from './reports/IRChart';
import ReportMeetingMinute              from './reports/ReportMeetingMinute';

// let brand = ['NTMC', 'APBW', 'PHILCREST', 'TYREPLUS'];
// let status = ['Pending', 'Approved', 'Rejected'];

/*
    BUS report for request list
    add prefix - Report for bus event in component
*/

let typeofReportBus = {
                supplementary:          'initSup',              callingcardrequest:     'initCallCard',         clearanceform:      'initClearance',
                companyloan:            'initLoan',             financialadvance:       'initFA',               incidentreport:     'initIR',
                laptopform:             'initLaptop',           leaveform:              'initLeave',            miis:               'initMIIS',
                prs:                    'initPRS',              prf:                    'initPRF',              canvas:             'initCanvas',
                salarydiscrepancy:      'initSalDis',           supplieraccreditation:  'initSupAccre',         travelform:         'initTravel',
                undertimerequest:       'initUndertime',        urgentcheck:            'initUrgentCheck',      offset:             'initOffset',
                workrequest:            'initWorkRequest',      overtimerequest:        'initOvertime',         overrideform:       'initOverride',
                transmittal:            'initTransmittal',      
                enrollmentprogram:      'initEnrollmentProgram', minutesofmeeting:      'initMeetingMinute',
            };

export default {
    components:{
        ReportSupplementary,
        ReportCallingCardRequest,
        ReportCompanyLoan,
        ReportFinancialAdvantage,
        ReportIncidentReport,
        ReportLaptopAgreement,
        ReportLeaveForm,
        ReportMIIS,
        ReportPRS,
        ReportPRF,
        ReportCanvas,
        ReportSalaryDiscrepancy,
        ReportSupplierAccreditation,
        ReportTravelForm,
        ReportUndertimeRequest,
        ReportUrgentCheck,
        ReportOffset,
        ReportWorkRequest,
        ReportOvertimeRequest,
        ReportOverrideForm,
        ReportTransmittal,
        ReportEnrollmentProgram,
        IRChart,
        ReportMeetingMinute
        
    },
    data(){
        return{
            rows: [],
            formtitle: '',
            title: '',
            // disabledinput: false,
            disabledBtn: false,
            showChart: false,
            dtHandle: null,
            loader: false,
            datefrom: moment(new Date()).format('YYYY-MM-DD'),
            dateto: (moment(new Date()).add(1, 'days')).format('YYYY-MM-DD'),
            status: 1, // old single selectedStatus

            // getSelectedRepType: 0 , // helper
            ishiddenComponent: true,

            reportType: [],
            branches: [],
            company: [],
            status_list:[
                {id: 1, name: 'Approved'},
                {id: 2, name: 'Rejected'},
                {id: 0, name: 'Pending'}
            ],

            selectedBranch:[],
            selectedRepType: 'select',
            selectedStatus: [],
            selectedCompany:[],

            selectedBranchText:[],
            selectedStatusText:[],

            // setSelectedRepTypeText: '',
            docReady: false,

            reportComponent: {
                // supplementary:          'ReportSupplementary',          callingcardrequest:     'ReportCallingCardRequest',         clearanceform:      'ReportClearance',
                // companyloan:            'ReportCompanyLoan',            financialadvantage:     'ReportFinancialAdvantage',         incidentreport:     'ReportIncidentReport',
                // laptopform:             'ReportLaptopAgreement',        leaveform:              'ReportLeaveForm',                  miis:               'ReportMIIS',
                // prs:                    'ReportPRS',                    prf:                    'ReportPRF',                        canvas:             'ReportCanvas',
                // salarydiscrepancy:      'ReportSalaryDiscrepancy',      supplieraccreditation:  'ReportSupplierAccreditation',      travelform:         'ReportTravelForm',
                // undertimerequest:       'ReportUndertimeRequest',       urgentcheck:            'ReportUrgentCheck',                offset:             'ReportOffset',
                // workrequest:            'ReportWorkRequest'
                
                /*
                    //DEFINITION ===================================================================================
                    index = componentname
                    value =  selectype value
                */
                ReportSupplementary:        'supplementary',          ReportCallingCardRequest:     'callingcardrequest',         ReportClearance:          'clearanceform',
                ReportCompanyLoan:          'companyloan',            ReportFinancialAdvantage:     'financialadvance',           ReportIncidentReport:     'incidentreport',
                ReportLaptopAgreement:      'laptopform',             ReportLeaveForm:              'leaveform',                  ReportMIIS:               'miis',
                ReportPRS:                  'prs',                    ReportPRF:                    'prf',                        ReportCanvas:             'canvas',
                ReportSalaryDiscrepancy:    'salarydiscrepancy',      ReportSupplierAccreditation:  'supplieraccreditation',      ReportTravelForm:         'travelform',
                ReportUndertimeRequest:     'undertimerequest',       ReportUrgentCheck:            'urgentcheck',                ReportOffset:             'offset',
                ReportWorkRequest:          'workrequest',            ReportOvertimeRequest:        'overtimerequest',            ReportOverrideForm:       'overrideform',
                ReportTransmittal:          'transmittal',           
                ReportEnrollmentProgram:    'enrollmentprogram',      ReportMeetingMinute:          'minutesofmeeting'
            },
        }
    },
    watch:{

    },
    computed:{
        isDisabled(){
            return this.selectedBranch.length < 1 || this.selectedStatus.length < 1 || this.selectedRepType == 'select';
        },

        showGenerateChartBtn(){
            // show dashboard chart if incident report is selected from form type
            return this.selectedRepType == 'incidentreport' ||  false;
        }

    },
    methods:{
        selectDateFrom(val){
            this.datefrom = moment(val).format('YYYY-MM-DD');
        },
        selectDateTo(val){
            this.dateto = moment(val).format('YYYY-MM-DD');
        },
        getSelectedText(event){
            let options = event.target.options;
            let name = event.target.id;
            let selectedOptiontext = options[options.selectedIndex].textContent;
            this.selected[name] = selectedOptiontext;
        },
        getSelectedReportType(el){
            this.title = 'SUMMARY REPORT OF '+ (el.target.options[el.target.options.selectedIndex].textContent).toUpperCase();
            this.disabledBtn = false;
            this.ishiddenComponent = true;
            $('#posname option[value="select"]').remove();
            // console.log(el.target.options[el.target.options.selectedIndex].textContent);
            // console.log(el.target.value);

            bus.$emit(`${typeofReportBus[el.target.value]}Report`, []);



            let options = [];
            if(el.target.value == "supplementary"){
                options =  [
                    {label: 'Approved', title: 'Approved', value: 2},
                    {label: 'Rejected', title: 'Rejected', value: 3},
                    {label: 'Verified', title: 'Verified', value: 1},
                    {label: 'Pending', title: 'Pending', value: 0}
                ];
            }else if(el.target.value == "workrequest"){
                options =  [
                    {label: 'Approved', title: 'Approved', value: 1},
                    {label: 'Rejected', title: 'Rejected', value: 2},
                    {label: 'Executed', title: 'Executed', value:3},
                    {label: 'Confirmed', title: 'Confirmed', value:4},
                    {label: 'Pending', title: 'Pending', value: 0},

                ];
            }else if(el.target.value == "overrideform"){

                options =  [
                    {label: 'Endorsed', title: 'Endored', value: 1},
                    {label: 'Approved', title: 'Approved', value: 2},
                    {label: 'Rejected', title: 'Rejected', value:3},
                    {label: 'Pending', title: 'Pending', value: 0},

                ];

            }else if(el.target.value == "transmittal"){

                options =  [
                    {label: 'Partially Received', title: 'Partially Received', value: 1},
                    {label: 'Received', title: 'Received', value: 2},
                    {label: 'Rejected', title: 'Rejected', value:3},
                    {label: 'Pending', title: 'Pending', value: 0},

                ];

            }else if(el.target.value == 'incidentreport'){
                /**
                 * 0 pending
                 * 1 endorse 1
                 * 2 endorse 2
                 * 3 close
                 * 4 rejected
                 * 9 approved by the immidedate approver but not as a whole request
                 */
                options =  [
                    // {label: '1st Endorsement', title: '1st Endorsement', value: 1},
                    // {label: '2nd Endorsement', title: '2nd Endorsement', value: 2},
                    {label: 'Pending', title: 'Pending', value: 0},
                    {label: 'Approved', title: 'Approved', value: 9},
                    {label: 'Further Investigation', title: 'Further Investigation', value: 8},
                    {label: 'Closed', title: 'Closed', value: 3},
                    {label: 'Rejected', title: 'Rejected', value: 4},
                ];
            }else if(el.target.value == 'minutesofmeeting'){
                /**
                 * 0 pending
                 * 1 endorse 1
                 * 2 endorse 2
                 * 3 close
                 * 4 rejected
                 * 9 approved by the immidedate approver but not as a whole request
                 */
                options =  [
                    // {label: '1st Endorsement', title: '1st Endorsement', value: 1},
                    // {label: '2nd Endorsement', title: '2nd Endorsement', value: 2},
                    {label: 'Open', title: 'Pending', value: 0},
                    {label: 'Closed', title: 'Closed', value: 1},
                ];
            }
            else{
                options =  [
                    {label: 'Approved', title: 'Approved', value: 1},
                    {label: 'Rejected', title: 'Rejected', value: 2},
                    {label: 'Pending', title: 'Pending', value: 0}
                ];
            }

           
            if(el.target.value != 'incidentreport')
            this.showChart = false;

            this.selectedStatus = [];
            let self = this;
            // $('.multiselect2').multiselect('destroy');
            // $('.multiselect2').multiselect('refresh')

            $('.multiselect2').multiselect('dataprovider', options);
            $('.multiselect2').multiselect('setOptions', {
                includeSelectAllOption : true,
                selectAllValue: 'multiselect-all',
                nonSelectedText: 'No option selected',
                // enableCaseInsensitiveFiltering: true,
                // enableFiltering: true,
                maxHeight: '300',
                // buttonWidth: '235',
                onSelectAll: function(element, checked){
                    if(element){
                        let brands = $('.multiselect2 option:selected');
                        let selected = [];
                        let selectedText = [];

                        $(brands).each(function(index, brand){
                            selected.push([$(this).val()]);
                            selectedText.push($(this).text().toLowerCase());
                        });
                        self.selectedStatus = selected;
                        self.selectedStatusText = selectedText;
                    }else{
                        let brands = $('.multiselect2 option:selected');
                        let selected = [];
                        let selectedText = [];

                        $(brands).each(function(index, brand){
                            selected.push([$(this).val()]);
                            selectedText.push($(this).text().toLowerCase());
                        });
                        self.selectedStatus = selected;
                        self.selectedStatusText = selectedText;
                    }
                },
                onChange: function(element, checked) {
                    let brands = $('.multiselect2 option:selected');
                    let selected = [];
                    let selectedText = [];

                    $(brands).each(function(index, brand){
                        selected.push([$(this).val()]);
                        selectedText.push($(this).text().toLowerCase());
                    });

                    self.selectedStatus = selected;
                    self.selectedStatusText = selectedText;
                }
            });
            // $('.multiselect2').multiselect('rebuild');


        },
        getReport(){
            this.disabledBtn = true;
            this.ishiddenComponent = false;
            // console.log(`api/getreport/${this.datefrom}/${this.dateto}/${this.selectedRepType}/${this.selectedBranch}/${this.selectedStatus}`);
           
           axios.get(`api/getreport/${this.datefrom}/${this.dateto}/${this.selectedRepType}/${this.selectedBranch}/${this.selectedStatus}/${this.selectedCompany}`)
            .then(res=>{
                bus.$emit(`${typeofReportBus[this.selectedRepType]}Report`, res.data, this.status || '');
                // this.disabledBtn = false;
            })
            .catch(err => console.log(err));
        },

        generateChart(){

// ${this.datefrom}/${this.dateto}/${this.selectedRepType}/${this.selectedBranch}/${this.selectedStatus}/${this.selectedCompany}
            axios.get(`api/get-ir-chart/${this.datefrom}/${this.dateto}/${this.selectedRepType}/${this.selectedBranch}/${this.selectedStatus}/${this.selectedCompany}`,
            {
                // params: {
                //     branch: this.selectedBranchText,
                // }
            })
            .then(res=>{
                this.showChart = true;
                bus.$emit(`${typeofReportBus[this.selectedRepType]}ReportChart`, res.data, { 
                    pie: this.selectedStatusText,
                    bar: this.selectedBranchText
                });
                
            })
            .catch(err => console.log(err));
            
        },

        closeModal(){
            $('.multiple').removeClass('form-field__control');
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
    },

    mounted(){

// const labels = [
//     'January',
//     'February',
//     'March',
//     'April',
//     'May',
//     'June',
//   ];

//   const data = {
//     labels: labels,
//     datasets: [{
//       label: 'My First dataset',
//       backgroundColor: 'rgb(255, 99, 132)',
//       borderColor: 'rgb(255, 99, 132)',
//       data: [0, 10, 5, 2, 20, 30, 45],
//     }]
//   };

//   const config = {
//     type: 'line',
//     data: data,
//     options: {}
//   };
//   const myChart = new Chart(
//     document.getElementById('myChart'),
//     config
//   );
        // const ctx = document.getElementById('reportChart');
        // const myChart = new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //         labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        //         datasets: [{
        //             label: '# of Votes',
        //             data: [12, 19, 3, 5, 2, 3],
        //             backgroundColor: [
        //                 'rgba(255, 99, 132, 0.2)',
        //                 'rgba(54, 162, 235, 0.2)',
        //                 'rgba(255, 206, 86, 0.2)',
        //                 'rgba(75, 192, 192, 0.2)',
        //                 'rgba(153, 102, 255, 0.2)',
        //                 'rgba(255, 159, 64, 0.2)'
        //             ],
        //             borderColor: [
        //                 'rgba(255, 99, 132, 1)',
        //                 'rgba(54, 162, 235, 1)',
        //                 'rgba(255, 206, 86, 1)',
        //                 'rgba(75, 192, 192, 1)',
        //                 'rgba(153, 102, 255, 1)',
        //                 'rgba(255, 159, 64, 1)'
        //             ],
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         scales: {
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        // });

        this.MDBINPUT();
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        axios.get('api/getreport-type').then(response=>{
            if(response.data.length > 0)
            {
                // this.selectedRepType = ((response.data[0].formtitle).replace(/ /g, '')).toLowerCase();
                this.reportType = (response.data.sort((a, b)=>{
                    var nameA = a.formtitle.toLowerCase();
                    var nameB = b.formtitle.toLowerCase(); 
                    if(nameA === nameB) return 0; 
                    return nameA > nameB ? 1 : -1;
                }));
                // this.setSelectedRepTypeText = response.data[0].formtitle;
                this.title = 'SUMMARY REPORT OF '+ (response.data[0].formtitle).toUpperCase();

            }
            return axios.get('api/getbranch')
        })
        .then(response=>{
            this.branches = response.data;
             this.$nextTick(() => {
                 let self = this;
                $('.multiselect1').multiselect({
                    includeSelectAllOption : true,
                    selectAllValue: 'multiselect-all',
                    nonSelectedText: 'No option selected',
                    // enableCaseInsensitiveFiltering: true,
                    // enableFiltering: true,
                    maxHeight: '300',
                    // buttonWidth: '235',
                    onSelectAll: function(element, checked){
                        if(element){
                            let brands = $('.multiselect1 option:selected');
                            let selected = [];
                            let selectedText = [];
                            $(brands).each(function(index, brand){
                                selectedText.push($(this).text().toLowerCase());
                                selected.push([$(this).val()]);
                            });
                            self.selectedBranch = selected;
                            self.selectedBranchText = selectedText;
                        }else{
                            let brands = $('.multiselect1 option:selected');
                            let selected = [];
                            let selectedText = [];
                            $(brands).each(function(index, brand){
                                selectedText.push($(this).text().toLowerCase());
                                selected.push([$(this).val()]);
                            });
                            self.selectedBranch = selected;
                            self.selectedBranchText = selectedText;
                        }
                    },
                    onChange: function(element, checked) {
                        let brands = $('.multiselect1 option:selected');
                        let selected = [];
                        let selectedText = [];
                        $(brands).each(function(index, brand){
                            selectedText.push($(this).text().toLowerCase());
                            selected.push([$(this).val()]);
                        });

                        self.selectedBranch = selected;
                        self.selectedBranchText = selectedText;
                    }
                });

            });

            this.docReady = true;
            return axios.get('api/getcompany')
        })
        .then(response=>{
            this.company = response.data;
             this.$nextTick(() => {
                 let self = this;
                $('.multiselect3').multiselect({
                    includeSelectAllOption : true,
                    selectAllValue: 'multiselect-all',
                    nonSelectedText: 'No option selected',
                    // enableCaseInsensitiveFiltering: true,
                    // enableFiltering: true,
                    maxHeight: '300',
                    // buttonWidth: '235',
                    onSelectAll: function(element, checked){
                        if(element){
                            let brands = $('.multiselect3 option:selected');
                            let selected = [];
                            $(brands).each(function(index, brand){
                                selected.push([$(this).val()]);
                            });
                            self.selectedCompany = selected;
                        }else{
                            let brands = $('.multiselect3 option:selected');
                            let selected = [];
                            $(brands).each(function(index, brand){
                                selected.push([$(this).val()]);
                            });
                            self.selectedCompany = selected;
                        }
                    },
                    onChange: function(element, checked) {
                        let brands = $('.multiselect3 option:selected');
                        let selected = [];
                        $(brands).each(function(index, brand){
                            selected.push([$(this).val()]);
                        });

                        self.selectedCompany = selected;
                    }
                });

            });
        })
        .catch(err=>console.log(err))

        // MODAL
        $('.modal').on("hidden.bs.modal", this.closeModal);


        // multi select status
        let self = this;
        let options =  [
                {label: 'Approved', title: 'Approved', value: 1},
                {label: 'Rejected', title: 'Rejected', value: 2},
                {label: 'Pending', title: 'Pending', value: 0}
            ];
        $('.multiselect2').multiselect('dataprovider', options);
        $('.multiselect2').multiselect('setOptions', {
            includeSelectAllOption : true,
            selectAllValue: 'multiselect-all',
            nonSelectedText: 'No option selected',
            // enableCaseInsensitiveFiltering: true,
            // enableFiltering: true,
            maxHeight: '300',
            // buttonWidth: '235',
            onSelectAll: function(element, checked){
                if(element){
                    let brands = $('.multiselect2 option:selected');
                    let selected = [];
                    $(brands).each(function(index, brand){
                        selected.push([$(this).val()]);
                        
                    });
                    self.selectedStatus = selected;
                }else{
                    let brands = $('.multiselect2 option:selected');
                    let selected = [];
                    $(brands).each(function(index, brand){
                        selected.push([$(this).val()]);
                    });
                    self.selectedStatus = selected;
                }
            },
            onChange: function(element, checked) {
                let brands = $('.multiselect2 option:selected');
                let selected = [];
                $(brands).each(function(index, brand){
                    selected.push([$(this).val()]);
                });

                self.selectedStatus = selected;
            }
        });
        $('.multiselect2').multiselect('rebuild');

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