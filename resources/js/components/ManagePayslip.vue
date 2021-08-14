// MANAGE PAYSLIP
<template>
    <div class="c">
        <form method="post" enctype="multipart/form-data">
            <div class="col-md-6">
                <div class="mdb-form-field form-group-limitx">
                    <Datepicker  :value="cutoffenddate" @selected="selectCutOffDate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd" name="payroll date">
                        <label slot="afterDateInput" class="form-field__label">Payroll Date</label>
                        <div slot="afterDateInput" class="form-field__bar"></div>
                        <span slot="afterDateInput" class="errors">{{ errors.first('payroll date') }}</span>
                    </Datepicker>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-12">
                <div class="dflex">
                    <div class="file-upload mb-3">
                        <input type="file" class="custom-file-input" id="file-payslip" @change="uploadXLS" name="images[]" style="display: none;" multiple>
                        <label class="btn btn-primary" for="file-payslip">Choose file</label>
                    </div>
                    <div class="progress flexgrow-1" v-show="showProgress">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%">
                            10%
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
			<!-- <div class="col-lg-12 modal-footer"> -->
			    <!-- <input type="submit" class="btn btn-primary"> -->
            <!-- </div> -->
            <div class="clearfix"></div>
        </form>
    </div>
</template>

<script>
export default {
    data(){
        return{
            cutoffenddate: moment(new Date()).format('YYYY-MM-DD'),
            showProgress: false,
            xlsBuffer: false,
            interVal: '',

        }

    },
    methods:{
        selectCutOffDate(val){
            this.cutoffenddate = moment(val).format('YYYY-MM-DD');
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
        moveProgressBar(){
            let width = 10;
            let self = this;
            clearInterval(self.interVal);
            this.interVal = setInterval(frame, 80);

            function frame() {
                if (width > 100) {
                    document.querySelector('.progress-bar').innerHTML = 'Successfully Completed';
                    clearInterval(self.interVal);
                    self.$parent.$data.disableCloseModal= false;
                } else {
                    document.querySelector('.progress-bar').style.width = width+'%';
                    document.querySelector('.progress-bar').innerHTML = width+'%';
                    if(self.xlsBuffer && width >= 90)
                    {
                       width = width;
                    }else{
                        width++;
                    }

                }
            }
        },
        async validate(e){

            if(document.querySelector('input#file-payslip[type=file]').value != ''){
                const validImageTypes = ["application/pdf"];
                let count = 0;

                // let files    = document.querySelector('input#file-payslip[type=file]').files[0];
                let files    = document.querySelector('input#file-payslip[type=file]').files;
                let formData = new FormData();
                let remaining_formData = new FormData();
                let targetFiles = e.target.files;

                // if(files.length > 20){
                //     this.$parent.$data.disableCloseModal= false;
                //     alert('maximum number of files allowed is 20');
                //     clearInterval(this.interVal);
                //     return false;
                // }
                let totalFiles = targetFiles.length;
                let remainingFiles = (totalFiles % 20);
                let iterators = totalFiles - remainingFiles;

                if(totalFiles>0){
                    let cleared = await this.clearTable();
                    if(!cleared){
                        alert('Server Error');
                        return;
                    }
                }

                for(var key in targetFiles){


                    if(targetFiles.length > count){
                        let type = targetFiles[count]['type'];
                        if (!validImageTypes.includes(type)) {
                            this.$parent.$data.disableCloseModal= false;
                            clearInterval(this.interVal);

                            document.querySelector('input#file-payslip[type=file]').value = '';
                            alert('Invalid pdf type');
                            return false;
                        }
                    }
                    await formData.append('attachment[]', targetFiles[key]);
                    await formData.append('cutoffenddate', this.cutoffenddate);

                    if((count + 1) % 20 == 0){
                        this.postPayslip(formData, false);
                        formData = new FormData();
                    }
                    if(iterators < count+1){
                        await remaining_formData.append('attachment[]', targetFiles[key]);
                        await remaining_formData.append('cutoffenddate', this.cutoffenddate);
                    }
                    // console.log(totalFiles, count, iterators);

                    count++;
                }

                this.postPayslip(remaining_formData, true);
                return false;
                // return await formData;
            }
        },
        async clearTable(){
            let cutoffenddate = this.cutoffenddate;
            try{
                await axios.post('api/addPaySlip', {clear: true, cutoffenddate: cutoffenddate});
                return true;
            }
            catch(err){
                return false;
            }
        },
        postPayslip(files, isDone = false){
            axios.post('api/addPaySlip', files)
            .then(res=>{
            // this.userinfo.avatar = res.data.avatar;
                if(isDone){
                    this.xlsBuffer = false;
                    document.querySelector('input#file-payslip[type=file]').value = '';
                }

            })
            .catch(er=>{
                document.querySelector('input#file-payslip[type=file]').value = '';
                alert('Server Error Occured...');
            });
        },
        async uploadXLS(e){
                this.$parent.$data.disableCloseModal = true;
                let self = this;
                this.showProgress = true;
                this.xlsBuffer = true;
                this.moveProgressBar();
                let file = await this.validate(e);

                if(file){

                    // axios.post('api/addPaySlip', file)
                    // .then(res=>{
                    // // this.userinfo.avatar = res.data.avatar;
                    //     this.xlsBuffer = false;
                    //     document.querySelector('input#file-payslip[type=file]').value = '';
                    // })
                    // .catch(er=>{
                    //     document.querySelector('input#file-payslip[type=file]').value = '';
                    //     alert('Server Error Occured...');
                    // });
                }




            // let file    = document.querySelector('input#file-payslip[type=file]').files;

            // let form_Data = new FormData();
            // // for(var key in file){
            // //         // this.postFormData.append('images[]', event.target.files[key]);
            // //     form_Data.append('attachment[]', file[key]);
            // // }
            // for(var key in event.target.files){
            //         form_Data.append('images[]', event.target.files[key]);
            // }
            // console.log(form_Data);
            // let form_Data = await this.validate();
            // console.log(form_Data);
            // axios.post('api/addPaySlip', form_Data)
            // .then((result)=>{
            //         console.log(result.data);
            // }).catch((err)=>{ console.log(err); });













            // this.$parent.$data.disableCloseModal = true;
            // let self = this;
            // this.showProgress = true;
            // this.xlsBuffer = true;
            // this.moveProgressBar();


            // let files = e.target.files,file;
            // if (!files || files.length == 0) return;
            // file = files[0];
            // let fileReader = new FileReader();

            // fileReader.onload = function (e) {
            //     let filename = file.name;
            //     // call 'xlsx' to read the file
            //     let binary = "";
            //     let bytes = new Uint8Array(e.target.result);
            //     let length = bytes.byteLength;
            //     for (let i = 0; i < length; i++) {
            //         binary += String.fromCharCode(bytes[i]);
            //     }
            //     let workbook = XLSX.read(binary, {type: 'binary', cellDates:true, cellStyles:true});
            //     //console.log(oFile);
            //     let first_sheet_name = workbook.SheetNames[0];
            //     let worksheet = workbook.Sheets[first_sheet_name];
            //     let jsonobj = XLSX.utils.sheet_to_json(worksheet);
            //     // console.log(jsonobj);
            //     // REPLICATE TB COLS WITH XLS COLS

            //     let serializedata = [];
            //     jsonobj.forEach(items => {
            //         let data = {};
            //         for(let props in items){
            //             let dbprop = dbcols[props.toLowerCase()];
            //             (dbprop != 'cutoffstartdate' && dbprop != 'cutoffenddate')
            //             ? data[dbprop]=items[props]
            //             : data[dbprop] = moment(items[props]).format('YYYY-MM-DD');

            //         }
            //         serializedata.push(data);
            //     });


            //     axios.post('api/addPaySlip', serializedata)
            //     .then((result)=>{
            //             console.log(result.data);
            //     }).catch((err)=>{ console.log(err); });
            //     self.xlsBuffer = false;
            // };
            // fileReader.readAsArrayBuffer(file);
        },
        resetProgressBar(){
            let width = 10;
            document.querySelector('.progress-bar').style.width = width+'%';
            document.querySelector('.progress-bar').innerHTML = width+'% ';
        },
        closeModal(){
            this.showProgress =  false;
            clearInterval(this.interVal);
            this.resetProgressBar();
        },
        downloadTemplate(){
            let records = {};
            for (const key in dbcols) {
                records[key] = "";
            }
            var workSheet = XLSX.utils.json_to_sheet([records]);
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, workSheet, 'Payslip Template');

            /* uncomment below to generate excel from json boject */
            XLSX.writeFile(wb, 'Payslip Template.xlsx')
        }
    },
    mounted(){

        $('#myModal').on("hidden.bs.modal", this.closeModal);
        bus.$on('downloadTemplate', this.downloadTemplate);
        this.MDBINPUT();
    }
}

let dbcols = {
            'employee id': 'empID_',
            'company': 'company',
            'cut-off start date': 'cutoffstartdate',
            'cut-off end date': 'cutoffenddate',
            'exemp code': 'exempcode',
            'basic-pay': 'basic_pay',
            'legal-holiday-pay': 'legal_holiday_pay',
            'leave w/out pay': 'leave_w_or_out_pay',
            'late/tardy': 'late_tardy',
            'vacation leave': 'VL',
            'Total Earning': 'total_earning',
            'sss contribution': 'sss_contribution',
            'philhealth': 'philh_contribution',
            'total deduction': 'total_deduction',
            'ytd taxable gross': 'ytd_taxable_gross',
            'ytd witholding tax': 'ytd_w_holdingtax',
            'ytd sss contribution': 'ytd_sss_contribution',
            'ytd hdmf contribution': 'ytd_hdmf_contribution',
            'vl-entitlement this year': 'VL_entitle_thisyear',
            'vl-lastyear balance': 'VL_entitle_lastyear',
            'vl-forcashconversion': 'VL_forcash_conversion',
            'vl-earned to-date': 'VL_earn_todate',
            'vl-used-date': 'VL_use_date',
            'vl-balance': 'VL_balance',
            'sl-entitlement this year': 'SL_entitle_thisyear',
            'sl-lastyear balance': 'SL_entitle_lastyear',
            'sl-forcashconversion': 'SL_forcash_conversion',
            'sl-earned to-date': 'SL_earn_todate',
            'sl-used-date': 'SL_use_date',
            'sl-balance': 'SL_balance',
};

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
