<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <br>
        <div class="col-lg-12">
            <h4>Upload DTR</h4>
        </div>
        
        <iframe 
        style="display: flex; height:100vh; width:100%;" 
        src="https://ams.northtrend.com/upload" height="auto"  title="W3Schools Free Online Web Tutorials"
        frameBorder="0">
        </iframe>
    </div>
</template>

<script>
// import ManagePayslip from '../components/ManagePayslip';


export default {
    components: {
        // ManagePayslip
    },
    data() {
        return {
            headers:  [],
            rows: [],
            dtHandle: null,
            loader: true,
            forUpdate: false,
            disableCloseModal: false,
            highlight: {
                    dates: []
                    },
            date: moment(new Date()).format('MM/DD/YYYY'),

        }
    },
    watch:{
        rows(val, old){
            let row = [];
            this.dtHandle.clear();
            this.dtHandle.rows.add(this.rows);
            this.dtHandle.draw();
        }
    },
    methods:{
        selectDate(val){

            this.date = moment(val).format('MM/DD/YYYY');
            axios.get('api/getAllPaySlip/'+moment(val).format('YYYY-MM-DD'))
            .then((res)=>{

                this.rows = res.data;

                // this.dtHandle.clear();
                // this.dtHandle.rows.add(res.data);
                // this.dtHandle.draw();

            })
            .catch((er)=>{
                alert('Server Error')
            });

        },
        addRow(val){
           this.rows.unshift({deptname: val});
        },
        deletePayslip(){
            axios.post('api/delete-payslip',{cutoffenddate: moment(this.date).format('YYYY-MM-DD')})
            .then((res)=>{

                this.rows = res.data;
                this.dtHandle.clear();
                this.dtHandle.draw();

            })
            .catch((er)=>{
                alert('Server Error...')
            });
        },
        // payslipTemplate(){
        //     bus.$emit('downloadTemplate');
        // },
        updateRow(val)
        {
            let row = this.$data.rows;
            row.forEach((item, index)=>{
                if(item.deptID == val.deptID)
                {
                    this.$data.rows.splice(index, 1);
                    this.$data.rows.unshift(val);
                }
            });
        },
        setUpdate(data){
            bus.$emit('setupdate', data);
        },
        closeModal(){
            this.forUpdate = false;
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
    },
    created(){

    },
    mounted() {
        axios.get('api/get-cutoff-dates').then((res)=>{
            if(res.data.length>0){
                this.date = moment(res.data[0].cutoffenddate).format('MM/DD/YYYY'),
                res.data.forEach(element => {
                    // console.log(new Date(element.cutoffenddate));
                    this.highlight.dates.push(new Date(element.cutoffenddate));
                });
            }

        }).catch((e)=>alert('Server Error'));
        this.MDBINPUT();

        let columnDefs = [
            {
                title: "Date Period", data: 'cutoffenddate',
                render: function(data, type, row){
                    return moment(data).format("MMMM Do YYYY");

                }
            },
            {
                title: "ID NO", data: 'empID'
            },
            {
                title: "NAME", data: 'fname'
            },
            {
                title: "POSITION", data: 'posname'
            },
            {
                title: "DEPARTMENT", data: 'deptname'
            }];


        axios.get('api/getAllPaySlip').then((response)=>{
            this.loader = false;
            this.rows = response.data;

            $.fn.DataTable.ext.pager.numbers_length = 5;
            this.dtHandle = $('#managedept').DataTable({
                "sPaginationType": "simple_numbers",
                data: [],
                columns: columnDefs,
                "sPaginationType": "simple_numbers",
                "dom": '<"top with-margin-bottom"f>rt<"mdl-grid"<"mdl-cell mdl-cell--4-col"i><"mdl-cell mdl-cell--8-col"p>><"clear">',
                "scrollX": true,
                "order": [[ 0, "asc" ]],
            });

            let table = this.dtHandle;
            let rows = this.rows.length;
            let self = this;
            // Add event listener for opening and closing details
            $('#managedept tbody').on('click', 'tr',  function(){
                if(rows > 0)
                {
                    let tr = $(this).closest('tr');
                    let row = table.row( tr );
                    let dataforedit = row.data();
                    self.forUpdate = true;
                    self.setUpdate(dataforedit);
                }
            });

        }).catch((err)=>{
            alert('Server Error');

        });

        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);


        $('.modal').on('show.bs.modal', function (e) {
			$('html').addClass('hidden-overflow');
		})
		$(".modal").on('hide.bs.modal', function(){
			$('html').removeClass('hidden-overflow');
        });

        // End Axios


        // var dataSet = [
        //     {name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},{name: 'JOhn DOe', position: 'IT', salary: '1000', aw: 'JOhn sdfsfsf', aws: 'ITsdfsfsfsfooofjfsf', awss: '1000'},
        // ];




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
