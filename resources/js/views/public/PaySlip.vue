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
                            <!-- https://raw.githubusercontent.com/mozilla/pdf.js/ba2edeae/web/compressed.tracemonkey-pldi-09.pdf -->
                            <button type="button" class="btn btn-warning slipdates"
                                @click.prevent="renderPDF('storage/app/'+record.pdf_loc)" data-toggle="modal" data-target="#pdfModal"
                                v-for="(record, key) in data" :key="key" >
                                {{record.cutoffenddate | formatMonth}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div id="pdfModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <div id="printThis">
                                <canvas id="pdfCanvas"></canvas>
                            </div>
                            <div class="clearfix"></div>
                            <!-- <div class="col-lg-12 col-md-12 col-sm-12 colxs">
                                <br><br><br>

                            </div>
                            <div class="clearfix"></div> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary hidden-print" style="float:right;"  @click="printPDF">
                                <i class="fas fa-print"></i> <span style="padding: 0 4px;">Print</span>
                            </button>
                            <button type="button" class="btn btn-primary hidden-print" style="float:right; margin-right: 15px;"  @click="downloadPDF">
                                <i class="fas fa-cloud-download-alt"></i> <span style="padding: 0 4px;">Download</span>
                            </button>
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
                            <div id="printThisx">
                                <div>
                                    <div class="text-center">
                                        <!-- <img id="slip-header-logo" src="public/images/comp_logo.jpg" alt="compay logo"/>     -->
                                        <h3 style="color: #3f51b5">
                                            {{selectedSlip.company}}
                                            <div style="font-size: 13px; color: #333; margin-top: 10px;">
                                                Pay Period: {{ selectedSlip.cutoffstartdate | formatMonthPrint }}
                                                &nbsp; - &nbsp;{{selectedSlip.cutoffenddate | formatMonthPrint}}
                                            </div>
                                        </h3>
                                    </div>

                                    <!-- <h4 class="text-center" style="text-align:center">NORTH TREND MARKETING CORPORATION</h4> -->
                                    <br>
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <div>Employee: {{selectedSlip.fname +' '+selectedSlip.fname}}</div>
                                        <div>Dept/Section: {{selectedSlip.deptname}}</div>
                                        <div>Excempt Code: {{selectedSlip.exempcode}}</div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div>SSS: {{selectedSlip.SSS}}</div>
                                        <div>TIN: {{selectedSlip.TIN}}</div>
                                        <div>HDMF: {{selectedSlip.HDMF}}</div>
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
                                                <td>{{selectedSlip.leave_w_or_out_pay}}</td>
                                            </tr>
                                            <tr>
                                                <td>Late / Tardy</td>
                                                <td></td>
                                                <td>{{selectedSlip.late_tardy}}</td>
                                            </tr>
                                            <tr>
                                                <td>Vacation Leave</td>
                                                <td></td>
                                                <td>{{selectedSlip.VL}}</td>
                                            </tr>
                                            <tr>
                                                <td>Total Earnings</td>
                                                <td></td>
                                                <td>{{selectedSlip.total_earning}}</td>
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
                                <div class="clearfix"></div>
                                <br>
                                <div class="col-sm-12">
                                    <table class="table-payslip table-bordered">
                                        <thead>
                                            <tr>
                                                <th>YTD-INFO</th>
                                                <th>AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Taxable Gross</td>
                                                <td>{{selectedSlip.sss_contribution}}</td>
                                            </tr>
                                            <tr>
                                                <td>Withholding Tax</td>
                                                <td>{{selectedSlip.philh_contribution}}</td>
                                            </tr>
                                            <tr>
                                                <td>SSS Contribution</td>
                                                <td>{{selectedSlip.total_deduction}}</td>
                                            </tr>
                                            <tr>
                                                <td>PhilHealth Contribution</td>
                                                <td>{{selectedSlip.total_deduction}}</td>
                                            </tr>
                                            <tr>
                                                <td>HDMF Contribution</td>
                                                <td>{{selectedSlip.total_deduction}}</td>
                                            </tr>
                                            <!-- loan balance -->
                                            <tr>
                                                <td style="border-right: 0;"><b>LOAN BALANCE</b></td>
                                                <td style="border-left: 0;"><b>AMOUNT</b></td>
                                            </tr>
                                            <tr>
                                                <td>Company Loan</td>
                                                <td>{{selectedSlip.total_deduction}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <br>
                                    <h5>NET PAY</h5>
                                    <div class="relative-pos x-scroll">
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
                                </div>

                                <div class="clearfix"></div>
                                <div class="col-lg-12 col-md-12 col-sm-12 colxs">
                                    <br><br><br>
                                    <!-- <button type="button" class="btn btn-primary hidden-print" style="float:right;"  @click="print">
                                        <i class="fas fa-print"></i> <span style="padding: 0 4px;">Print</span>
                                    </button> -->
                                </div>
                                <div class="clearfix"></div>
                            </div> <!-- END PRINT THIS -->
                        </div>
                    </div>
                </div>
            </div>
        <!---END MODAL-->

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
            dataURI: '',
        }
    },
    watch:{

    },
    filters:{
        formatMonth(value)
        {
            return moment(value).format('MMM Do');
        },
        formatMonthPrint(value)
        {
            return moment(value).format('MMM Do YYYY');
        },
    },
    methods:{
       selectDate(val){
           this.date =  moment(val).format('YYYY-MM-DD')
       },
       displaySlip(val){
           this.selectedSlip = val;

       },
       async downloadPDF(){
           var dataUrl = await document.getElementById('pdfCanvas').toDataURL();
           var link = await document.createElement("a");
            link.download = 'payslip';
            link.href = await this.dataURI;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
       },
       async printPDF(){
           let elem = document.getElementById("printThis");
           let domClone = elem.cloneNode(true);
            let $printSection = document.getElementById("printSection");

            if (!$printSection) {
                let $printSection = document.createElement("div");
                 var dataUrl = await document.getElementById('pdfCanvas').toDataURL('image/jpeg', 0.5); //attempt to save base64 string to server using this var

                $printSection.innerHTML = '<div><img src="' + dataUrl + '" /></div>';
                $printSection.id = "printSection";
                await document.body.appendChild($printSection);

            }else{
                await $printSection.appendChild(domClone);
            }

            window.print();
       },
       renderPDF(pdf_URL){
           this.dataURI = pdf_URL;
            // If absolute URL from the remote server is provided, configure the CORS
            // header on that server.
            let url = pdf_URL;

            // Loaded via <script> tag, create shortcut to access PDF.js exports.
            let pdfjsLib = window['pdfjs-dist/build/pdf'];

            // The workerSrc property shall be specified.
            // pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorker;

            // Asynchronous download of PDF
            let loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(function(pdf) {
                // console.log('PDF loaded');
                renderPage(pdf);
            }, function (reason) {
            // PDF loading error
                console.error(reason);
            });

            function renderPage(pdf){
                // Fetch the first page
                let pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    // console.log('Page loaded');

                    let scale = 1.5;
                    let viewport = page.getViewport({scale: scale});

                    // Prepare canvas using PDF page dimensions
                    let canvas = document.getElementById('pdfCanvas');
                    let context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    let renderContext = {
                    canvasContext: context,
                    viewport: viewport
                    };
                    let renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                        // console.log('Page rendered');
                    });
                });
            }
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
            this.loader = false;
            this.rows = response.data;
        })
        .catch(err => console.log(err));


    }

}
</script>