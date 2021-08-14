<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			{{formtitle}}
		</div>
        <div class="col-lg-12 margin-15">
            
            <div class="panel-group" id="payslip-accordion">
                <div class="panel panel-default" v-for="(data, index) in formatSlip" :key="index">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#payslip-accordion" :href="'#'+index.replace(/ /g,'_')">{{index}}</a>
                        </h4>
                    </div>
                    <div :id="index.replace(/ /g,'_')" :class="index==classIn ? 'panel-collapse collapse in' : 'panel-collapse collapse'">
                        <div class="panel-body">
                            <button type="button" class="btn btn-warning slipdates" @click.prevent="displaySlip(record)" data-toggle="modal" data-target="#myModal" v-for="(record, key) in data" :key="key" >
                                {{record.cutoffstartdate | formatMonth}}
                            </button>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 

        <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <div id="printThis">
                                <div>
                                    <div class="text-center">
                                        <!-- <img id="slip-header-logo" src="public/images/comp_logo.jpg" alt="compay logo"/>     -->
                                    </div>
                                    
                                    <!-- <h4 class="text-center" style="text-align:center">NORTH TREND MARKETING CORPORATION</h4> -->
                                    <br><br>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <p>Employee: {{selectedSlip.fname +' '+selectedSlip.fname}}</p>
                                        <p>Dept/Section: {{selectedSlip.deptname}}</p>
                                        <p>Excempt Code: {{selectedSlip.exempcode}}</p>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <p>SSS: {{selectedSlip.SSS}}</p>
                                        <p>TIN: {{selectedSlip.TIN}}</p>
                                        <p>HDMF: {{selectedSlip.HDMF}}</p>
                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 cols-2">
                                    <br>
                                    <table class="table-payslip table-bordered">
                                        <thead>
                                            <tr>
                                                <th>EARNINGS</th>
                                                <th>HOURS</th>
                                                <th>AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Basic Pay</td>
                                                <td></td>
                                                <td>{{selectedSlip.basic_pay}}</td>
                                            </tr>
                                            <tr>
                                                <td>Legal Holiday Pay</td>
                                                <td></td>
                                                <td>{{selectedSlip.legal_holiday_pay}}</td>
                                            </tr>
                                            <tr>
                                                <td>Leave w/o Pay</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Late / Tardy</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Vacation Leave</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Total Earnings</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 cols-2">
                                    <br>
                                    <table class="table-payslip table-bordered">
                                        <thead>
                                            <tr>
                                                <th>DEDUCTIONS</th>
                                                <th>AMOUNT</th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>SSS Contribution</td>
                                                <td>{{selectedSlip.sss_contribution}}</td>
                                            </tr>
                                            <tr>
                                                <td>PhilHealth Contribution</td>
                                                <td>{{selectedSlip.philh_contribution}}</td>
                                            </tr>
                                            <tr>
                                                <td>Total Deduction</td>
                                                <td>{{selectedSlip.total_deduction}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <br>
                                    <h4>NET PAY</h4>
                                    <table class="table-payslip table-bordered breakword">
                                        <thead>
                                            <tr>
                                                <th>Leaves</th>
                                                <th>Entitlement This Year</th>
                                                <th>Last Year Balance</th>
                                                <th>For Cash Conversion</th>
                                                <th>Earned To Date</th>
                                                <th>Leave Credit</th>
                                                <th>Used To-Date</th>
                                                <th>Balance</th>
                                            </tr>   
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Vacation Leave</td>
                                                <td>{{selectedSlip.VL_entitle_thisyear}}</td>
                                                <td>{{selectedSlip.VL_entitle_lastyear}}</td>
                                                <td>{{selectedSlip.VL_forcash_conversion}}</td>
                                                <td>{{selectedSlip.VL_earn_todate}}</td>
                                                <td></td>
                                                <td>{{selectedSlip.VL_use_date}}</td>
                                                <td>{{selectedSlip.VL_balance}}</td>
                                            </tr>
                                            <tr>
                                                <td>Sick Leave</td>
                                                <td>{{selectedSlip.SL_entitle_thisyear}}</td>
                                                <td>{{selectedSlip.SL_entitle_lastyear}}</td>
                                                <td>{{selectedSlip.SL_forcash_conversion}}</td>
                                                <td>{{selectedSlip.SL_earn_todate}}</td>
                                                <td></td>
                                                <td>{{selectedSlip.SL_use_date}}</td>
                                                <td>{{selectedSlip.SL_balance}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="clearfix"></div>
                                <div class="col-lg-12 col-md-12 col-sm-12 colxs">
                                    <br><br><br>
                                    <button type="button" class="btn btn-primary hidden-print" style="float:right;"  @click="print">
                                        <i class="fas fa-print"></i> <span style="padding: 0 4px;">Print</span>
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div> <!-- END PRINT THIS -->
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->       
    </div>
</template>
<script>

export default {
    
    data(){
        return{
            date: moment(new Date()).format('YYYY-MM-DD'),
            formtitle: '',
            loader: true,
            classIn: '',
            rows: [],
            selectedSlip: {},
        }
    },
    watch:{
        
    },
    filters:{
        formatMonth(value)
        {
            return moment(value).format('MMM Do');
        },
    },
    methods:{
       selectDate(val){
           this.date =  moment(val).format('YYYY-MM-DD')
       },
       displaySlip(val){
           this.selectedSlip = val;
           console.log(val);
       },
       print(){

           let elem = document.getElementById("printThis");
           var domClone = elem.cloneNode(true);
    
            var $printSection = document.getElementById("printSection");
            
            if (!$printSection) {
                var $printSection = document.createElement("div");
                $printSection.id = "printSection";
                document.body.appendChild($printSection);
            }
            
            $printSection.innerHTML = "";
            $printSection.appendChild(domClone);
            window.print();
       }
    },
    created(){

    },
    computed:{
        formatSlip(){
            let YYlabel = 'Year ';
            let rows  =  this.rows;
            let year = 0;
            let result = {};
            this.rows.forEach((data)=>{
                if(year != moment(data.cutoffenddate).format('YYYY'))
                {
                    year = moment(data.cutoffenddate).format('YYYY');
                    result[YYlabel+year] = [];
                    if(!this.classIn){
                        this.classIn = YYlabel+year;
                    }
                }
                result[YYlabel+year].push(data);
            });
            
            return result;

            let reversesort = {};
            Object.keys(result).sort((a, b)=>{return b-a;}).forEach(function(key) {
                reversesort[key] = result[key];
            });
            return reversesort;
            
        }
    },
    mounted(){
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        this.formtitle = ((this.$router.currentRoute.path).slice(1)).replace(/-/g, ' ').toUpperCase();
        

        axios.get('api/getPaySlipByEmployee')
        .then((response) => {
            this.rows = response.data;
        })
        .catch(err => console.log(err));

    }

}
</script>