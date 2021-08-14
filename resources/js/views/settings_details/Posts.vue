<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div class="admin-component">
		<div class="col-lg-12 header-title bgc-white">
            <h2>POST ANNOUNCEMENTS</h2>
        </div>
		<div class="clearfix"></div>
		<br>
		<div class="">
			<div class="bgc-white">
                <br>
				<div class="col-md-3 col-sm-6" style="padding-top: 15px">
					<div class="form-group-limit">
								<Datepicker :value="dateStart"  @selected="selectDateStart" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
								<label slot="afterDateInput" class="form-field__label">Date Start</label>
								<div slot="afterDateInput" class="form-field__bar"></div>
								<span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
								</Datepicker>
					</div>
				</div>
				<div class="col-md-9 col-sm-6" style="padding-top: 15px">
					<div class="form-group-limit">
								<Datepicker :value="dateEnd" @selected="selectDateEnd" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
								<label slot="afterDateInput" class="form-field__label">Date End</label>
								<div slot="afterDateInput" class="form-field__bar"></div>
								<span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span>
								</Datepicker>
					</div>
				</div>
				<div class="clearfix"></div>

			</div>
			<br>
			<div class="bgc-white">
				<br>
				<div class="dflex block-mobile" style="align-items: center">
						<div class="dropdown padding-lr-15">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{selected_type.deptname | formType_filters}}
							&nbsp;&nbsp;<div class="caret"></div></button>
							<ul class="dropdown-menu scroll-menu">
								<li v-for="dept in dept_list" :key="dept.deptID"><a href="#" @click.prevent="selectFormType(dept)">{{dept.deptname}}</a></li>

							</ul>
						</div>

						<div class="dropdown padding-lr-15" style="margin-top: 15px; margin-bottom: 15px;">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{selected_status | status_filters}}
							&nbsp;&nbsp;<div class="caret"></div></button>
							<ul class="dropdown-menu scroll-menu">
								<li><a href="#" @click.prevent="selected_status = 'Active'">Active</a></li>
								<li><a href="#" @click.prevent="selected_status = 'In-active'">In-active</a></li>

							</ul>
						</div>
						<div class="padding-lr-15">
							<button class="btn btn-primary dropdown-toggle" type="button"  @click.prevent="pullRec" :disabled="disableBtn">Get Records</button>
						</div>
				</div>
				<div class="clearfix"></div>
				<br>
			</div>
			<br>
			<div class="bgc-white col-lg-12">
				<br><br>
				<div class="col-md-6 nopadding">
					<button class="btn btn-danger" @click.prevent="delRange" :disabled="disableBtn">Permanently Delete</button>
                    <button class="btn btn-warning" @click.prevent="disableRange" :disabled="selected_status != 'Active'">Disable</button>
					<button class="btn btn-primary" @click.prevent="enableRange" :disabled="selected_status != 'In-active'">Enable</button>
				</div>
				<table id="manage-post" class="mdl-data-table" style="width: 100%"></table>
			</div>
		</div>
        <div id="postModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Post Preview</h4>
                </div>
                <div class="modal-body">
                        <div class="dflex-normal d-align-center">
                            <div class="post-avatarxx avatar align-self-start" style="margin-right: 10px;">
                                <img id="post-avatar" :src="selected_row.avatar? 'storage/app/'+selected_row.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px; height: 50px; object-fit: cover;">
                            </div>
                            <div class="post-textareaxx flex-grow-8">
                                <h5>{{selected_row.fullname || ''}}<br><i class="post-date-diff"
                                style="padding-top: 5px; display: inline-block; font-size: 11px; color: darkgray;">
                                    {{ selected_row.dateposted | moment }}</i>
                                </h5>
                            </div>
                        </div>
                        <hr>
                        <textarea name="message-update" id="textarea-postxxxx"
                            data-autoresizetextarea class="noborder width-100"
                            rows="1" v-model="selected_row.message" :disabled="true"></textarea>

                        <!-- videos -->
                        <div v-if="selected_row.type == 'mp4'">
                            <video width="100%" controls="controls" class="video-modal">
                                <source :src="'storage/app/'+selected_row.attachment" type="video/mp4">
                            </video>
                        </div>

                        <!-- images -->
                        <div v-if="('jpg jpeg png gif').includes(selected_row.type) && selected_row.type != ''">
                            <img :src="'storage/app/'+selected_row.attachment" style="width: 100%; height: auto;" />
                        </div>
                </div>
                <div class="modal-footer" style="padding-bottom: 15px;">
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                        <!-- <button type="button" class="btn btn-warning" >Disable</button>
                        <button type="button" class="btn btn-danger" >Delete Permanently</button> -->
                </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            dateStart: moment().format('YYYY-MM-DD'),
            dateEnd: moment().format('YYYY-MM-DD'),
            dept_list: [],
            rows: [],
            selected_type: {
                deptname:'',
            },
            selected_status: '',
            // selected_form_obj:{},
            selected_row: {},
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
        },
    },
    filters:{
        formType_filters(val){
            return val || 'Select Department';
        },
        status_filters(val){
            return val || 'Select Status';
        },
        moment: function (date) {
    		return moment(date).fromNow();
  		}
    },
    computed:{
        disableBtn(){
            return !this.selected_status.length > 0 || !this.selected_type.deptname.length>0;
        }
    },
    methods:{
        delRange(){
            let dept_id = this.selected_type.deptID;
            let type= status.indexOf(this.selected_status);
            let params = {
                datefrom: this.dateStart+' 00:00:00',
                dateto: this.dateEnd+' 23:59:59',
                deptID: dept_id,
            }
            axios.post('api/delAnnouncement', params)
            .then(res=>{
                this.rows = [];
            })
            .catch(er=>alert('Server Error'));
        },
        disableRange(){
                let dept_id = this.selected_type.deptID;
                let type= status.indexOf(this.selected_status);
                let params = {
                    datefrom: this.dateStart+' 00:00:00',
                    dateto: this.dateEnd+' 23:59:59',
                    deptID: dept_id,
                    status: 1,
                }
                axios.post('api/disable-post-records',params)
                .then(res=>{
                    this.rows = [];
                }).catch(()=>alert('Server Error please reload'));

        },
        enableRange(){
                let dept_id = this.selected_type.deptID;
                let type= status.indexOf(this.selected_status);
                let params = {
                    datefrom: this.dateStart+' 00:00:00',
                    dateto: this.dateEnd+' 23:59:59',
                    deptID: dept_id,
                    status: 0,
                }
                axios.post('api/enable-post-records',params)
                .then(res=>{
                    this.rows = [];
                }).catch(()=>alert('Server Error please reload'));
        },
        selectFormType(obj){
            this.selected_type=obj
        },
        selectDateStart(val){
            this.dateStart = moment(val).format('YYYY-MM-DD');

        },
        selectDateEnd(val){
            this.dateEnd = moment(val).format('YYYY-MM-DD');
        },
        pullRec(){
            let type = '';
            let params = {};


            let dept_id = this.selected_type.deptID;
            type= status.indexOf(this.selected_status);
            params = {
                datefrom: this.dateStart+' 00:00:00',
                dateto: this.dateEnd+' 23:59:59',
                deptID: dept_id,
                status: type,
            }

            axios.post('api/get-post-records', params)
            .then(res=>{
                this.rows = res.data;
            })
            .catch(err=>alert('Server Error'));
        },
        setUpdate(data){
            this.selected_row = data;
            $('#postModal').modal("show");

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
    mounted(){
		this.MDBINPUT();
		axios.get('api/getdept')
		.then(res=>{
			this.dept_list = res.data;
		})
		.catch(()=>alert('Server Error'));


        $(document).on('shown.bs.modal','#postModal', ()=>{
            jQuery.each(jQuery('textarea#textarea-postxxxx'), function() {

				let offset = this.offsetHeight - this.clientHeight;

				let resizeTextarea2 = function(el) {
					// jQuery(el).css('height', 'auto').css({'height': el.scrollHeight + offset, "max-height": '200px'});
					jQuery(el).css('height', 'auto').css({'height': el.scrollHeight + offset, "max-height": '200px'});
				};

					resizeTextarea2(this);
			});
        });

		 this.dtHandle=$('#manage-post').DataTable({
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
            // $("#manage-post tbody").on('click', 'button.reset-pass', function() {
            //     var tr = $(this).closest('tr');
            //     var row = table.row( tr );
            //     self.resetPassword(row.data());
            //     reset = true;
            // });

            // $("#manage-post tbody").on('click', 'button.remove-emp', function() {
            //     let tr = $(this).closest('tr');
            //     let row = table.row( tr );
            //     let dataforedit = row.data();

            //     self.delRow(dataforedit.empID);
            //     remove_emp = true;
            // });

            // modal update
            $("#manage-post tbody").on('click', 'tr', function() {

                // if((rows > 0 && !reset) && (rows > 0 && !remove_emp))
                // {
                    var tr = $(this).closest('tr');
                    var row = table.row( tr );
                    let dataforedit = row.data();
                    // self.forUpdate = true;
                    console.log(dataforedit);
                    self.setUpdate(dataforedit);

                // }else{
                //     reset =  false;
                //     remove_emp = false;
                // }
            });

    }
}

let status = ['Active', 'In-active'];

let columnDefs = [{
            title: "Date Posted", data: 'dateposted'
        },{
            title: "Full Name", data: 'fullname'
        }, {
            title: "Message", data: 'message'
        },{
            title: "Attachment", data: 'attachment'
        }
];



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
