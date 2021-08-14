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
                <Datepicker :value="datefrom" @selected="selectDateFrom" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                    <label slot="afterDateInput" class="form-field__label">Date From</label>
                    <div slot="afterDateInput" class="form-field__bar"></div>
                    <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                </Datepicker>
            </div>
            <div class="col-lg-4 col-md-4">
                <Datepicker :value="dateto" @selected="selectDateTo" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd">
                    <label slot="afterDateInput" class="form-field__label">Date To</label>
                    <div slot="afterDateInput" class="form-field__bar"></div>
                    <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
                </Datepicker>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-4 col-md-4">
                <div class="mdb-form-field form-field--is-filled">
                    <div class="form-field__control">
                        <select id="posname" v-model="selectedRepType" name="position" class="form-field__input" @change.prevent="getSelectedReportType">
                            <option v-for="(data, key) in reportType" :key="key" :value="key">{{data.formtitle}}</option>
                        </select> 
                        <label class="form-field__label">Report Type</label> 
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
                                <!-- <option value="tomatoes">Mingla</option> -->
                                <!-- <option value="tomatoes">Mandaue</option> -->
                            </select>
                            <label class="form-field__label">Branches</label>
                            <div class="form-field__bar"></div>
                    </div>
                    
                    <span class="errors"></span>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="mdb-form-field form-field--is-filled">
                    <div class="form-field__control">
                        <select id="posname" v-model="status" name="position" class="form-field__input">
                            <!-- 1 approve 2rejected 0pending -->
                            <option :value="1">Approved</option>
                            <option :value="2">Rejected</option>
                            <option :value="0">Pending</option>
                        </select> 
                        <label class="form-field__label">Status</label> 
                        <div class="form-field__bar"></div>
                    </div> 
                    <span class="errors"></span>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-4 col-md-4">
                <input type="submit" class="btn btn-primary" @click.prevent="getReport" value="Generate Report" :disabled="isDisabled || disabledBtn">
            </div>
        </div>
        <div class="clearfix"></div>
        <div>
                <!-- dynamic call components -->
                <div v-for="(compo, index) in $options.components" :key="index" v-show="reportComponent.indexOf(index) == selectedRepType && !ishiddenComponent">
                    <!-- <{{index}}></{{index}}> -->
                    <component :is="index" :title="title" />
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

// let brand = ['NTMC', 'APBW', 'PHILCREST', 'TYREPLUS'];
// let status = ['Pending', 'Approved', 'Rejected'];
let typeofReportBus = [
                'initSup',          'initCallCard',         'initClearance', 
                'initLoan',         'initFA',               'initIR',
                'initLaptop',       'initLeave',            'initMIIS',
                'initPRS',          'initPRF',              'initCanvas',
                'initSalDis',       'initSupAccre',         'initTravel',
                'initUndertime',    'initUrgentCheck',      'initOffset',
                'initWorkRequest'
            ];
            
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
    },
    data(){
        return{
            rows: [],
            formtitle: '',
            title: '',
            // disabledinput: false,
            disabledBtn: false,
            dtHandle: null,
            loader: false,
            datefrom: moment(new Date()).format('YYYY-MM-DD'),
            dateto: (moment(new Date()).add(1, 'days')).format('YYYY-MM-DD'),
            status: 1,
            
            // getSelectedRepType: 0 , // helper 
            ishiddenComponent: true,

            reportType: [],
            branches: [],
            selectedBranch:[],
            selectedRepType: 0,

            // setSelectedRepTypeText: '',
            docReady: false,

            reportComponent: [
                'ReportSupplementary',          'ReportCallingCardRequest',         'ReportClearance', 
                'ReportCompanyLoan',            'ReportFinancialAdvantage',         'ReportIncidentReport',
                'ReportLaptopAgreement',        'ReportLeaveForm',                  'ReportMIIS',
                'ReportPRS',                    'ReportPRF',                        'ReportCanvas',
                'ReportSalaryDiscrepancy',      'ReportSupplierAccreditation',      'ReportTravelForm',
                'ReportUndertimeRequest',       'ReportUrgentCheck',                'ReportOffset',
                'ReportWorkRequest'
            ],
        }
    },
    watch:{
        // rows(val, old){
        //     let row = val;
            
        //     row.forEach((item, index)=>{
        //         if(!isNaN(item.brand) && !isNaN(item.status)){
        //             row[index]['brand'] = brand[item.brand - 1];
        //             row[index]['status'] = status[item.status];
        //         }
        //     });
        //     this.dtHandle.clear();
        //     this.dtHandle.rows.add(row);
        //     this.dtHandle.draw();
        // },
      
    },
    computed:{
        isDisabled(){
            return this.selectedBranch.length < 1;
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
            // console.log(el.target.options[el.target.options.selectedIndex].textContent);
            // console.log(el.target.value);
            
            bus.$emit(`${typeofReportBus[el.target.value]}Report`, []);
        },
        getReport(){
            this.disabledBtn = true;
            this.ishiddenComponent = false;
            axios.get(`api/getreport/${this.datefrom}/${this.dateto}/${this.selectedRepType}/${this.selectedBranch}/${this.status}`)
            .then(res=>{
                // this.rows = res.data;
                bus.$emit(`${typeofReportBus[this.selectedRepType]}Report`, res.data);
                this.disabledBtn = false;
            })
            .catch(err => console.log(err));
        },
        closeModal(){
            // this.disabledinput = false;
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
        
        this.MDBINPUT();
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();

        axios.get('api/getreport-type').then(response=>{
            if(response.data.length > 0)
            {
                //this.selectedRepType = response.data[0].formtitle;
                this.reportType = response.data;
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
                            $(brands).each(function(index, brand){
                                selected.push([$(this).val()]);
                            });
                            self.selectedBranch = selected;
                        }else{
                            let brands = $('.multiselect1 option:selected');
                            let selected = [];
                            $(brands).each(function(index, brand){
                                selected.push([$(this).val()]);
                            });
                            self.selectedBranch = selected;
                        }
                    },
                    onChange: function(element, checked) {
                        let brands = $('.multiselect1 option:selected');
                        let selected = [];
                        $(brands).each(function(index, brand){
                            selected.push([$(this).val()]);
                        });

                        self.selectedBranch = selected;
                    }
                });

                
            });

            this.docReady = true;
        })
        .catch(err=>console.log(err))

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

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