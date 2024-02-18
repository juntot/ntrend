<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css'; */
    /* @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div id="alertProgress" :class="isProgress? 'alert alert-warning alert-notification': 'hide'">
            <strong>PROCESSING!</strong> Please wait while processing your request...
        </div>
        <div id="alertError" :class="isFailed? 'alert alert-danger alert-notification': 'hide'">
            <strong>ERROR!</strong> Error occurred please try again...
        </div>
        <div id="alertSuccess" :class="isSuccess? 'alert alert-success alert-notification': 'hide'">
            <strong>SUCCESS!</strong> File Successfully Uploaded.
        </div>
        <div class="col-lg-12 header-title">
            <h4>MANAGE USERS</h4>
        </div>
        <div class="col-lg-12 canvas-chart margin-15">
            <div class="col-lg-6 col-md-6  with-margin-bottom nopadding" v-show="!loader">
                <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">ADD NEW</button>
                <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">DOWNLOAD</button>
                <button class="btn btn-primary" @click.prevent="downloadXLSTemplate">USER TEMPLATE</button>
                <!-- <button class="btn btn-primary" @click.prevent="downloadXLS" v-if="rows.length>0">UPLOAD</button> -->
                <label for="fileXls" class="btn btn-primary">UPLOAD
                    <input type="file" id="fileXls" @change="uploadXLS" class="hidden">
                </label>
            </div>
            <table id="manageuser" class="mdl-data-table" style="width:100%" ref="aws"></table>

            <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <ManageEmployee></ManageEmployee>
                        </div>
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div> -->
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
import { isError } from 'util';
import ManageEmployee from '../components/ManageEmployee';

export default {
    components: {
        ManageEmployee
    },
    data() {
        return {
            headers:  [],
            rows: [],

            deptNames: [],
            posNames: [],
            branchNames: [],
            compNames: [],

            dtHandle: null,
            loader: true,
            forUpdate: false,

            isProgress: false,
            isSuccess: false,
            isFailed:  false,
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
        resetPassword(data){
            axios.post('api/resetpass',{empID: data.empID})
            .then(res=>{
                alert('password reset..');
            })
            .catch(err=>console.log(err));
        },
        addRow(val){

        //    this.rows.unshift({
        //        empID: val[0], fname: val[1], lname: val[2], mname: val[3], dhired: val[4],
        //        gender: val[5], branchname: val[6], deptname: val[7], posname: val[8]
        //     });
        this.rows.unshift(val);


        },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.empID == val.empID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });
        },
        removeRowTable(empID = ''){
            let data = this.rows.filter((obj)=>obj.empID != empID);
            this.rows = data;
        },
        delRow(empID){
            let result = confirm("Are you sure you want to delete?");
            if (result) {
                axios.post('api/del-emp',{
                    empID: empID
                }).then((res)=>{
                    let row = this.$data.rows;
                    let data = this.rows.filter((obj)=>obj.empID != empID);
                    this.rows = data;
                })
                .catch(er=>console.log(er))

            }

        },
        updateAdmin(empid = null, type = null){
            axios.get('api/updateadmin/'+empid+'/'+type).then((response)=>{
                // console.log(response.data);
            }).catch((err)=>{
                console.log(err);
            });
        },
         validateFile(){
			if(document.querySelector('input#file[type=file]').value != ''){
	        	// let preview = document.querySelector('img#avatar-lg'); //selects the query named img
			    let file    = document.querySelector('input#file[type=file]').files[0]; //sames as here

                let type = file['type'];
                // const validImageTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                const validImageTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                // console.log(type);
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid type');
				      return false;
                }
                return file;
			}else{

				return null;
			}
        },
        validateFileXls(){
            if(
                this.deptNames.length <= 0 &&
                this.posNames.length <= 0 &&
                this.branchNames.length <= 0 &&
                this.compNames.length <= 0
            ){
                alert('please make sure to add first the ff: (departments, positions, branches and company)');
                return false;
            }
			if(document.querySelector('input#fileXls[type=file]').value != ''){
	        	// let preview = document.querySelector('img#avatar-lg'); //selects the query named img
			    let file    = document.querySelector('input#fileXls[type=file]').files[0]; //sames as here

                let type = file['type'];
                const validImageTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                // const validImageTypes = ['image/png', 'image/jpeg', 'image/jpg'];
			    if (!validImageTypes.includes(type)) {
                    document.querySelector('input#fileXls[type=file]').value = '';
				      alert('Invalid excel file');
				      return false;
                }
                return file;
			}else{

				return null;
			}
		},
        uploadXLS(e){
            this.isProgress = true;
            if(!this.validateFileXls()){
                this.isProgress = false;
                return;
            }

            if(!this.branchNames.length>0 || !this.posNames.length>0 || !this.deptNames.length>0 || !this.compNames.length >0){
                document.querySelector('input#fileXls[type=file]').value = '';
                alert('An Error occurred please reload the page to fix this issue..');
                this.isProgress = false;
                return;
            }

            var files = e.target.files,file;
            if (!files || files.length == 0) return;
                file = files[0];
                var fileReader = new FileReader();
            fileReader.onload = async (e) =>{
                var filename = file.name;
                // call 'xlsx' to read the file
                var binary = "";
                var bytes = new Uint8Array(e.target.result);
                var length = bytes.byteLength;
                for (var i = 0; i < length; i++) {
                    binary += String.fromCharCode(bytes[i]);
                }
                var workbook = XLSX.read(binary, {type: 'binary', cellDates:true, cellStyles:true});

                //console.log(oFile);
                var first_sheet_name = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[first_sheet_name];
                let xlsData = XLSX.utils.sheet_to_json(worksheet);
                if(xlsData.length > 50){
                    document.querySelector('input#fileXls[type=file]').value = '';
                    alert('max records to be uploaded must not be greater than 50...');
                    this.isProgress = false;
                    return;
                }
                if(xlsData.length <= 0){
                    document.querySelector('input#fileXls[type=file]').value = '';
                    alert('no records found...');
                    this.isProgress = false;
                    return;
                }

                let checkHeader = ['empID',
                                    'mname',
                                    'fname',
                                    'lname',
                                    // 'gender',
                                    'email',
                                    // 'dhired',
                                    // 'birthdate',
                                    // 'mobile',
                                    'posname',
                                    'branchname',
                                    'deptname',
                                    'compname'
                                ];
                let XlsHeaders = await Object.keys(xlsData[0]);
                for(var validHeaders in checkHeader){
                    if(!XlsHeaders.includes(checkHeader[validHeaders])){
                        this.isProgress = false;
                        alert(`Missing header/required data: ${checkHeader[validHeaders]}..`);
                        document.querySelector('input#fileXls[type=file]').value = '';
                        return;
                    }
                }

                // prepare data for API
                let isPass = true;
                xlsData = await xlsData.reduce((acc, cur, i, arr) => {

                    cur['birthdate'] = moment(cur.birthdate).isValid()? moment(cur.birthdate).format('YYYY-MM-DD') : moment().format('YYYY-MM-DD');
                    cur['dhired'] = moment(cur.dhired).isValid()? moment(cur.dhired).format('YYYY-MM-DD') : moment().format('YYYY-MM-DD');
                    if( !cur.hasOwnProperty('branchname') ||
                        !cur.hasOwnProperty('deptname') ||
                        !cur.hasOwnProperty('posname') ||
                        !cur.hasOwnProperty('compname')
                    ){
                        this.isProgress = false;
                        this.isFailed = true;
                        $("#alertError").show().delay(1900).queue(function(n) {
                            $(this).hide(); n();
                        });
                        alert(`Missing header/required data..`);
                        document.querySelector('input#fileXls[type=file]').value = '';
                        arr.splice(0);
                        isPass = false;

                    }else{

                            this.branchNames.some(data=>{
                                if(((cur.branchname).toLowerCase()).includes(data.branchname.toLowerCase())){
                                    cur['branchID_'] = data.branchID;
                                    return true;
                                }else{
                                    cur['branchID_'] = this.branchNames[0].branchID;
                                }
                            });

                            this.deptNames.some(data=>{
                                if(((cur.deptname).toLowerCase()).includes(data.deptname.toLowerCase())){
                                    cur['deptID_'] = data.deptID;
                                    return true;
                                }else{
                                    cur['deptID_'] = this.deptNames[0].deptID;
                                }
                            });

                            this.posNames.some(data=>{
                                if(((cur.posname).toLowerCase()).includes(data.posname.toLowerCase())){
                                    cur['posID_'] = data.posID;
                                    return true;
                                }else{
                                    cur['posID_'] = this.posNames[0].posID;
                                }
                            });

                            this.compNames.some(data=>{
                                if(((cur.compname).toLowerCase()).includes(data.compname.toLowerCase())){
                                    cur['compID_'] = data.compID;
                                    return true;
                                }else{
                                    cur['compID_'] = this.compNames[0].compID;
                                }
                            });


                    }
                    delete cur.compname;
                    delete cur.branchname;
                    delete cur.deptname;
                    delete cur.posname;


                    if(isPass){
                        acc.push(cur);
                    }else{
                        acc = [];
                    }
                    return acc;
                }, []);



                // send to route
                if(xlsData.length > 0){
                    axios.post('api/upload_employee',{xlsData: xlsData})
                        .then(res=>{
                            if(res.status == 200){
                                this.isProgress = false;
                                this.isSuccess =  true,

                                $("#alertSuccess").show().delay(1800).queue(function(n) {
                                    $(this).hide(); n();
                                });
                            }
                        })
                        .catch(err=>{
                            // error.response.status
                            this.isProgress = false;
                            this.isFailed = true;
                            $("#alertError").show().delay(1800).queue(function(n) {
                                $(this).hide(); n();
                            });
                        });
                }

                // console.log(xlsData)


            };
            fileReader.readAsArrayBuffer(file);
        },
        downloadXLSTemplate(){
            axios.get('api/get_columns')
            .then(res=>{
                if(res.data.length > 0){
                    let records =  {};
                    (res.data).forEach(element => {
                        switch(element.COLUMN_NAME){
                            case "branchID_":
                                records['branchname'] = "";
                                break;
                            case "deptID_":
                                records['deptname'] = "";
                                break;
                            case "posID_":
                                records['posname'] = "";
                                break;
                            case "compID_":
                                records['compname'] = "";
                                break;
                            case "status": break;
                            default:
                                records[element.COLUMN_NAME] = "";

                        }

                    });
                    var workSheet = XLSX.utils.json_to_sheet([records]);
                    var wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, workSheet, 'Employee Template');

                    /* uncomment below to generate excel from json boject */
                    XLSX.writeFile(wb, 'Employee Template.xlsx')
                }
            })
            .catch(er=>console.log(er));
        },
        downloadXLS(){
            let params = this.rows;
            let departments = {};
            params.forEach((obj)=>{
              departments[obj.deptname] = [];
              params.forEach((obj2)=>{
                  if(obj.deptname  == obj2.deptname){
                      departments[obj.deptname].push(obj2);
                  }
              });

            });



            let wb = XLSX.utils.book_new();
            let ws = '';

            let header = [];
            let rows = [];
            for(let deptname in departments)
            {
                header = [];
                rows = [];
                (departments[deptname]).forEach((obj)=>{
                    let temprow = [];
                    for(let val in obj)
                    {
                        if(!header.includes(val)){
                            header.push(val);
                        }
                        temprow.push(obj[val]);

                    }

                    rows.push(temprow);

                });
                rows.unshift(header);
                /* SHEET 2 */
                ws = XLSX.utils.aoa_to_sheet(rows);
                XLSX.utils.book_append_sheet(wb, ws, deptname);

            }
            // /* SHEET 2 */
            // ws = XLSX.utils.aoa_to_sheet([["a","b", "c"],[3,2,1]]);
            // XLSX.utils.book_append_sheet(wb, ws, "Sheet2");

            XLSX.writeFile(wb, "Employee Lists.xls");
            /* uncomment writeFile to start saving excel */
        },
        setUpdate(data){
            bus.$emit('setupdate', data);
        },
        closeModal(){
            this.forUpdate = false;
        }
    },
    created(){

    },
    mounted() {

        axios.get('api/getemployees').then((response)=>{
            this.loader = false;
            this.rows=response.data;

            this.dtHandle=$('#manageuser').DataTable({
            "sPaginationType": "simple_numbers",
            data: [],
            columns: columnDefs,
            "sPaginationType": "simple_numbers",
            "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
            "scrollX": true,
            "order": [[ 0, "desc" ]],
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            let reset = false;
            let remove_emp = false;
            $("#manageuser tbody").on('click', 'button.reset-pass', function() {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                self.resetPassword(row.data());
                reset = true;
            });

            $("#manageuser tbody").on('click', 'button.remove-emp', function() {
                let tr = $(this).closest('tr');
                let row = table.row( tr );
                let dataforedit = row.data();

                self.delRow(dataforedit.empID);
                remove_emp = true;
            });

            // modal update
            $("#manageuser tbody").on('click', 'tr', function() {

                if((rows > 0 && !reset) && (rows > 0 && !remove_emp))
                {
                    var tr = $(this).closest('tr');
                    var row = table.row( tr );
                    let dataforedit = row.data();
                    self.forUpdate = true;
                    self.setUpdate(dataforedit);

                }else{
                    reset =  false;
                    remove_emp = false;
                }
            });

        }).catch((err)=>{
            console.log(err);
        });



        // ,{
        //     title: "Admin",
        //     className:      'details-control',
        //     orderable:      false,
        //     data:           'admin'
        //     // defaultContent: `
        //     //     <label class="mdblbl inline-blocklbl">
        //     //     <input type="checkbox" class="canapprove">
        //     //     <span class="mdbcheckmark"></span>
        //     //     </label>
        //     // `
        // }];

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);

        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

    }
}

let columnDefs = [{
            title: "Employee ID", data: 'empID'
        },{
            title: "First Name", data: 'fname'
        }, {
            title: "Last Name", data: 'lname'
        },{
            title: "Middle Name", data: 'mname'
        },{
            title: "Date Hired", data: 'dhired'
        },{
            title: "Gender", data: 'gender'
        },{
            title: "Branch", data: 'branchname'
        },{
            title: "Department", data: 'deptname'
        },{
            title: "Position", data: 'posname'
        },{
            title: "Status",
            data: 'employee_status'
        },
        {
            title: "Reset Pass",
            className:      'details-control',
            orderable:      false,
            data:           'resetpass',
            defaultContent: `
                <button type="button" class="btn btn-primary reset-pass">Reset</button>
            `
        },
        {
            title: "Remove Emp",
            className:      'details-control',
            orderable:      false,
            data:           'deluser',
            defaultContent: `
                <button type="button" class="btn btn-primary remove-emp">Delete</button>
            `
        }
];

</script>
