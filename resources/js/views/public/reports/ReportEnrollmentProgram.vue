<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			{{formtitle}}
		</div>
        <div class="col-lg-12 margin-15">
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding">
                <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#reportEnrollmentProgramModal">ADD NEW</button> -->
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
            </div>
            <table id="approvalenrollmentprogramform" class="mdl-data-table" style="width:100%"></table>

            <!-- Modal -->
            <div id="reportEnrollmentProgramModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ReportManageEnrollmentProgram :userinfo="$root.userinfo" :selected="selected"></ReportManageEnrollmentProgram>
                        </div>
                    </div>
                </div>
            </div> <!---END MODAL-->


        </div>
    </div>
</template>
<script>
import ReportManageEnrollmentProgram from './details/ReportManageEnrollmentProgram';
// let check = ['<i class="fas fa-times"></i>', '<i class="fas fa-check"></i>'];
// let status = ['Pending', 'Approved', 'Rejected'];

export default {
    props: ['title'],
    components:{
        ReportManageEnrollmentProgram
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
            selected: {},
        }
    },
    watch:{
        rows(val, old){
            let row = val;
            this.dtHandle.clear();
            this.dtHandle.rows.add(row);
            this.dtHandle.draw();
        }
    },
    methods:{
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
                    $("#reportEnrollmentProgramModal").modal("hide");
                }

            });

        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.enrollmentID == val.enrollmentID)
                {
                    // let data = item;
                    // data['status'] = val.status;
                    // data['remarks'] = val.remarks;
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });

        },
        setUpdate(data){
            bus.$emit('setupdateEnrollmentProg', data);
        },

        closeModal(){
            this.disabledinput = false;
            this.selected = {};
        },
        setInit(data = null){
            this.rows = data;
        },
        getDuration(from, to){
            var ms = moment(to,"YYYY-MM-DD HH:mm:ss").diff(moment(from,"YYYY-MM-DD HH:mm:ss"));
            var d = moment.duration(ms);
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
            
            s = s.slice(0, (s.indexOf(':'))).length <= 1 ? '0'+s: s;

            return s || '';
        },
        downloadXLS(){
            const baseUrl = window.location.origin+'/storage/app';
            let wb = XLSX.utils.book_new();

            let rows = [];
            let header = [
                            'PROGRAM#', 'EMPLOYEE ID', 'EMPLOYEE NAME', 'DEPARTMENT NAME', 'POSITION', 'DATE & TIME FILED',
                            'PROGRAM CODE', 'PROGRAM NAME', 'TERITORY',  
                            'COMPANY','CUSTOMER NAME', 'BRANCH', 'DIVISION',
                            'ADDITIONAL INFORMATION', 'PROGRAM MECHANICS', 'PROGRAM AGREEMENT',
                            'APPROVER', 'DURATION', 'APPROVED DATE', 'REMARKS', 'STATUS'
                        ];
            rows.push(header);

            // const links1 = [];
            // const links2 = [];
            this.rows.forEach(obj => {
                let records = [
                            obj.enrollmentID, obj.empID_, obj.fullname, obj.deptname, obj.posname,
                            obj.dateenrolled, obj.program_code, obj.program_name, obj.teritory,
                            obj.company, obj.customer_name, obj.branch, obj.division,
                            obj.additional_info,
                            obj.progmechanic_attachment ? baseUrl+'/'+obj.progmechanic_attachment: '' , 
                            obj.progagree_attachment ? baseUrl +'/'+obj.progagree_attachment: '',
                            obj.approvedby,
                            obj.status == 0? '' : this.getDuration(obj.dateenrolled, obj.approveddatetime),
                            obj.approveddate,
                            obj.remarks, obj.status == 1 ?  'Approved': obj.status == 2 ? 'Rejected': 'Pending'
                        ];
                rows.push(records);
            });



            // /* SHEET 1 */
            // let ws = XLSX.utils.aoa_to_sheet([["a","b", "c"],[3,2,1],[1,2,3]]);
            let ws = XLSX.utils.aoa_to_sheet(rows);
            // ws[XLSX.utils.encode_cell({
            //     c: 0,
            //     r: 0
            // })] = {
            //     f: '=HYPERLINK("http://www.google.com","Google")'
            // };

            // jQuery.each(ws, function(cell, item) {
            //     console.log(cell, item);
            //     if (item.v != null && item.v.toString().indexOf('http') == 0)
            //         ws[cell] = {f: '=HYPERLINK("http://www.google.com","Google")'};
            // });
            

            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
            XLSX.writeFile(wb, this.title+".xls");
            /* uncomment writeFile to start saving excel */
        },

    },
    created(){

    },

    beforeDestroy(){
        bus.$off('initEnrollmentProgramReport', this.setInit);
    },
    mounted(){

        let columnDefs = [
           {
            title: "Program #", data: 'enrollmentID', visible: true,
        },
        {
            title: "Date & Time Created", data: 'dateenrolled', render: function(data){
                return moment(data).format('MM/DD/YYYY hh:mm a');
            }
        },
        
        {
            title: "Requestor", data: 'fullname'
        },
        {
            title: "Approver", data: 'approvedby'
        },
        {
            title: "Teritory", data: 'teritory'
        },
        {
            title: "Customer Name", data: 'customer_name'
        },
        {
            title: 'Program Code', data: 'program_code'
        },
        {
            title: 'Program Name', data: 'program_name'
        },
        {
            title: 'Duration', data: 'approveddatetime', render: function(data, type, row){
            if(row.status < 1)
            return '';
            
            var ms = moment(data,"YYYY-MM-DD HH:mm:ss").diff(moment(row.dateenrolled,"YYYY-MM-DD HH:mm:ss"));
            var d = moment.duration(ms);
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
            
            s = s.slice(0, (s.indexOf(':'))).length <= 1 ? '0'+s: s;
            // console.log('from: '+moment(row.dateenrolled, )+'to: '+data, s);
            
            return s;
            }
        },
        {
            title: "Status", data: 'status',
            render: function(data){
                return data == 0? 'Pending':
                       data == 1 ? 'Approved':
                       data == 2 ? 'Rejected': ''
            }
        }];
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[0];
        // this.formtitle = this.$route.name;
        // APPROVERS
        axios.get('api/getEnrollmentProgApprover').then((response)=>{
            this.approvers =  response.data;
        })
        .catch((err)=>{});
        // axios.get('api/approvalenrollmentprog').then((response)=>{
            this.loader = false;
            // this.rows=response.data;


            $.extend(jQuery.fn.dataTableExt.oSort, {
                "date-uk-pre": function (a) {
                    var x;
                    try {
                        var dateA = a.replace(/ /g, '').split("/");
                        var day = parseInt(dateA[1], 10);
                        var month = parseInt(dateA[0], 10);
                        var year = parseInt(dateA[2], 10);
                        var date = new Date(year, month - 1, day)
                        x = date.getTime();
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

            this.dtHandle=$('#approvalenrollmentprogramform').DataTable({
            aoColumnDefs: [{ "sType": "date-uk", "aTargets": [1] }],
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            "rowCallback": function(row, data, index) {
                var cellValue = data["status"];
                    if (cellValue == 0) {
                       $(row).addClass("tr-pending");
                    }
                    if (cellValue==1) {
                       $(row).addClass("tr-approved");
                    }
                    if (cellValue==2) {
                       $(row).addClass("tr-rejected");
                    }
                }
            });

            let table = this.dtHandle;
            // let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $("#approvalenrollmentprogramform tbody").on('click', 'tr', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                // CHECK IF STATUS IS APPROVED TO BE READY FOR CANCELLATION
                if(row.data().status >= 1){
                    self.isCancel = true;
                }
                let dataforedit = row.data();
                self.selected = row.data();
                // self.empID_ = row.data().empID_;
                self.setUpdate(dataforedit);

            });

        // }).catch((err)=>{
        //     console.log(err);

        // });

        // MODAL
        $('#reportEnrollmentProgramModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        bus.$on('initEnrollmentProgramReport', this.setInit);

    }

}
</script>