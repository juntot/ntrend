<template>
    <div class="c">
        <form method="post">
					<div class="vforms">
						<div class="avatar-container text-center">
							<label for="file-emp-compo" id="avatarlbl">
							    <img id="avatar-lg" :src="avatar?'storage/app/'+avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar">
							</label>
							<input id="file-emp-compo" type="file" @change.prevent="newAvatar" style="display:none;">
						</div>
                        <div class="col-lg-12">
                            <h5 class="form-subtitle"><em>Employee Information</em></h5>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input :disabled="$parent.$data.forUpdate" type="text" v-validate="'required|numeric'" v-model="empID" class="form-field__input" name="empID">
                                    <label class="form-field__label">Employee ID</label>
                                    <div class="form-field__bar"></div>
                                </div>
                                <span class="errors">{{ errors.first('empID') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="fname"  v-validate="'required'" name="fname" class="form-field__input">
                                    <label class="form-field__label">First Name</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('fname') }}</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="lname"  v-validate="'required'" name="lname"  class="form-field__input">
                                    <label class="form-field__label">Last Name</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('lname') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="mname"  v-validate="" name="mname" class="form-field__input">
                                    <label class="form-field__label">Middle Name</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('mname') }}</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="email"  v-validate="'required|email'" name="email"  class="form-field__input">
                                    <label class="form-field__label">Email</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('email') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <Datepicker  :value="dhired" @selected="selectDHired" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd" name="datehired">
                                    <label slot="afterDateInput" class="form-field__label">Date hired</label>
                                    <div slot="afterDateInput" class="form-field__bar"></div>
                                    <span slot="afterDateInput" class="errors">{{ errors.first('datehired') }}</span>
                                </Datepicker>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="mobile"  v-validate="" name="mobile"  class="form-field__input">
                                    <label class="form-field__label">Mobile</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('mobile') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <Datepicker  :value="birthdate" @selected="selectBirthdate" wrapper-class="mdb-form-field" input-class="form-field__input datePicker" :typeable="false" format="yyyy-MM-dd" name="birthdate">
                                    <label slot="afterDateInput" class="form-field__label">Birth date</label>
                                    <div slot="afterDateInput" class="form-field__bar"></div>
                                    <span slot="afterDateInput" class="errors">{{ errors.first('birthdate') }}</span>
                                </Datepicker>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="SSS"  v-validate="" name="SSS"  class="form-field__input">
                                    <label class="form-field__label">SSS</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('SSS') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="TIN"  v-validate="" name="TIN"  class="form-field__input">
                                    <label class="form-field__label">TIN</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('TIN') }}</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="HDMF"  v-validate="" name="HDMF"  class="form-field__input">
                                    <label class="form-field__label">HDMF</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('HDMF') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="PhHealth"  v-validate="" name="PhHealth"  class="form-field__input">
                                    <label class="form-field__label">PHILHEALTH</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('PhHealth') }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="address"  v-validate="" name="address"  class="form-field__input">
                                    <label class="form-field__label">Address</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('address') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="emergency_contactperson"  v-validate="" name="emergency_contactperson"  class="form-field__input">
                                    <label class="form-field__label">Emergency Contact Person</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('emergency_contactperson') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="emergency_contactnum"  v-validate="" name="emergency_contactnum"  class="form-field__input">
                                    <label class="form-field__label">Emergency Contact Number</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('emergency_contactnum') }}</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <h5 class="form-subtitle"><em>Gender</em></h5>
                        </div>
                        <div class="col-lg-12">
                            <div class="col-lg-3">
                                <div>
                                    <label class="mdblblradio">
                                    <span class="checklbl">Male</span>
                                    <input type="radio" v-model="gender" value="M" name="radio" >
                                    <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div>
                                    <label class="mdblblradio">
                                    <span class="checklbl">Female</span>
                                    <input type="radio" v-model="gender" value="F" name="radio" >
                                    <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h5 class="form-subtitle"><em>Job Details</em></h5>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <select v-model="posID_" id="posname" name="position" v-validate="'required'" class="form-field__input" @change="getSelectedText">
                                        <option :value="item.posID" v-for="(item, key) in positions" :key="key">{{ item.posname }}</option>
                                    </select>
                                    <label class="form-field__label">Position</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('position') }}</span>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <select v-model="branchID_" id="branchname" name="branchname" v-validate="'required'" class="form-field__input" @change="getSelectedText">
                                        <option :value="item.branchID" v-for="(item,key) in branchnames" :key="key">{{ item.branchname }}</option>
                                    </select>
                                    <label class="form-field__label">Branch</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('branch') }}</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <select v-model="deptID_" id="deptname" name="department" v-validate="'required'" class="form-field__input" @change="getSelectedText">
                                        <option :value="item.deptID" v-for="(item, key) in deptnames" :key="key">{{ item.deptname }}</option>
                                    </select>
                                    <label class="form-field__label">Department</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('department') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <select v-model="compID_" id="compname" name="company" v-validate="'required'" class="form-field__input" @change="getSelectedText">
                                        <option :value="item.compID" v-for="(item, key) in compnames" :key="key">{{ item.compname }}</option>
                                    </select>
                                    <label class="form-field__label">Company</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('company') }}</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <!-- <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="employee_status"  v-validate="" name="employee_status"  class="form-field__input">
                                    <label class="form-field__label">Employee Status</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('employee_status') }}</span>
                            </div> -->
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <select v-model="employee_status" id="branchname" name="branchname" v-validate="'required'" class="form-field__input" @change="getSelectedText">
                                        <option value="Probationary" >Probationary</option>
                                        <option value="Regular" >Regular</option>
                                        <option value="Resigned" >Resigned</option>
                                        <option value="Contractual" >Contractual</option>
                                        <option value="Project Base" >Project Base</option>
                                        <option value="On Job Training" >On Job Training</option>
                                    </select>
                                    <label class="form-field__label">Employee Status</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('employee_status') }}</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12">
                            <h5 class="form-subtitle"><em>Leave Credits</em></h5>
                        </div>
						<div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="VL"  v-validate="" name="VL"  class="form-field__input">
                                    <label class="form-field__label">Vacation Leave Credits</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('VL') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="SL"  v-validate="" name="SL"  class="form-field__input">
                                    <label class="form-field__label">Sick Leave Credits</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('SL') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="BL"  v-validate="" name="BL"  class="form-field__input">
                                    <label class="form-field__label">Birthday Credits</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('BL') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-model="DL"  v-validate="" name="DL"  class="form-field__input">
                                    <label class="form-field__label">Discretionary Leave Credits</label>
                                    <div class="form-field__bar"></div>
                                </div>
                               <span class="errors">{{ errors.first('DL') }}</span>
                            </div>
                        </div>
					</div><!-- vfroms -->
                    <div class="col-lg-12 modal-footer">
                        <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addEmp" :disabled="isDisable || !isFormValid" v-if="!$parent.$data.forUpdate">
                        <input type="submit" class="btn btn-primary" value="Resign" @click.prevent="resignEmp" :disabled="isDisable || !isFormValid" v-if="$parent.$data.forUpdate">
                        <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateEmp" :disabled="isDisable || !isFormValid" v-if="$parent.$data.forUpdate">
                    </div>
                    <div class="clearfix"></div>


	    </form>
    </div>
</template>

<script>

export default {
    // props: ['deptnames', 'branchnames', 'positions'],
    data: function () {
		return {
            avatar: '',
			isDisable: false,
			empID: '',
			fname: '',
			lname: '',
			mname: '',
			email: '',
            dhired: moment(new Date()).format('YYYY-MM-DD'),
            birthdate: moment(new Date()).subtract(21, 'years').format('YYYY-MM-DD'),
			gender: 'M',
			branchID_: '',
			deptID_: '',
            posID_: '',
            compID_: '',
            positions: '',
            deptnames: '',
            branchnames: '',
            compnames: '',
            mobile: '',
            SSS: '',
            TIN: '',
            PhHealth: '',
            HDMF: '',
            address: '',
            emergency_contactperson: '',
            emergency_contactnum: '',
            employee_status: '',
            DL: 0,
            VL: 0,
            BL: 0,
            DL: 0,
            SL: 0,

            selected:{
                compname: '',
                branchname: '',
                deptname: '',
                posname: ''
            }
		}
    },
    watch:{
        userinfo(val, old){
            let arr=[
                     'it', 'information', 'techonology',
                     'hr', 'human',
                    ];
            let approver = '';
            arr.forEach((keywords, index)=>{
                if(val.deptname.toLowerCase().includes(keywords) && index <= 2)
                {
                    approver = 'it';
                }
                if(val.deptname.toLowerCase().includes(keywords) && (index > 2 && index <= 3 ))
                {
                    approver = 'hr';
                }
            });

            val['approvertype'] = approver;
            this.MDBINPUT();
        },
    },
    methods:{
        addEmp(){
			if(this.isFormValid){
				this.isDisable = true;
				// let self = this;
				this.dhired = moment(this.dhired).format('YYYY-MM-DD');
				let params = this.$data;

				let file = this.newAvatar();
				const formData = new FormData();
				formData.append( 'image', file );

				Object.keys(params).forEach((keys)=>{
                    if(keys != 'positions' && keys != 'deptnames' && keys != 'branchnames'
                        && keys != 'compnames' && keys != 'selected')
					formData.append( keys, params[keys]);
                });

				//axios
				axios.post('api/addemp', formData)
				.then((response)=>{
                    // console.log(response.data);
                    response.data['posname']= this.selected.posname;
                    response.data['branchname']= this.selected.branchname;
                    response.data['deptname']= this.selected.deptname;
                    response.data['compname']= this.selected.compname;

                    this.$parent.addRow(
                        response.data
                        // this.empID, this.fname, this.lname, this.mname, this.dhired, this.gender,
                        // this.selected.branchname, this.selected.deptname, this.selected.posname
                    );

                    // this.resetInput();
                    this.closeModal();
                    // this.$validator.reset();
				}).catch((err)=>{
					// self.clear();
				});
			}

        },
        updateEmp(){
			if(this.isFormValid){
				this.isDisable = true;
				// let self = this;

				let params = this.$data;

				let file = this.newAvatar();
				const formData = new FormData();
				formData.append( 'image', file );

				Object.keys(params).forEach((keys)=>{
                    if(keys != 'positions' && keys != 'deptnames' && keys != 'branchnames'
                        && keys != 'selected' && keys != 'compnames')
					formData.append( keys, params[keys]);
                });

				//axios
				axios.post('api/updateemp', formData)
				.then((response)=>{

                    response.data['branchname'] = this.selected.branchname;
                    response.data['deptname'] = this.selected.deptname;
                    response.data['posname'] = this.selected.posname;
                    response.data['compname'] = this.selected.compname;
                    this.$parent.updateRow(response.data);

                    // this.resetInput();
                    this.closeModal();
                    // this.$validator.reset();
				}).catch((err)=>{
					// self.clear();
				});
			}

        },
        resignEmp(){
			if(this.isFormValid){
                let confirmation = confirm('Are you sure this employee is no longer connected to the company?');
                if(confirmation){
                    this.isDisable = true;
                    // let self = this;
                    let params = {
                        empID: this.empID
                    };
                    //axios
                    axios.post('api/del-emp', params)
                    .then((response)=>{
                        this.$parent.removeRowTable(this.empID);
                        // this.resetInput();
                        this.closeModal();
                        // this.$validator.reset();
                    }).catch((err)=>{
                    	// self.clear();
                    });
                }

			}

        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'branchnames' && i != 'deptnames' && i != 'positions' && i != 'compnames' && i != 'selected' && i != 'isDisable')
                this.$data[i] = data[i];

                if(i == 'dhired')
                this.$data[i] = moment(data[i]).format('YYYY-MM-DD');

                if(i == 'birthdate'){
                    this.$data[i] = moment(data[i]).format('YYYY-MM-DD');
                    // console.log(data);
                }

                if(i == 'SL' || i == 'VL' || i == 'BL' || i == 'DL'){
                    this.$data[i] = data[i] || 0;
                }


                this.selected.branchname = data.branchname;
                this.selected.deptname = data.deptname;
                this.selected.posname = data.posname;
                this.selected.compname = data.compname;
                // this.selected.compname = data.compname;

            }
            // console.log(data);

            this.MDBINPUT();
            $("#myModal").modal("show");
        },
        closeModal(){
            document.querySelector('input#file-emp-compo[type=file]').value = '';
            let obj = this.$data;
            // this.MDBINPUTRESET();

            Object.keys(obj).forEach((key)=>{
                if( key != 'dhired' && key != 'positions' && key != 'deptnames' && key != 'birthdate'
                    && key != 'branchnames' && key != 'compnames' && key != 'selected' && key != 'gender'){
                    this.$data[key] =  '';
                }
                if(key == 'SL' || key == 'VL' || key == 'BL' || key == 'DL'){
                    this.$data[key] = 0;
                }
            });
            $("#myModal").modal("hide");

        },
        getSelectedText(event){
            let options = event.target.options;
            let name = event.target.id;
            let selectedOptiontext = options[options.selectedIndex].textContent;
            this.selected[name] = selectedOptiontext;
        },
        selectDHired(val){
            this.dhired = moment(val).format('YYYY-MM-DD');
        },
        selectBirthdate(val){
            this.birthdate = moment(val).format('YYYY-MM-DD');
        },
        newAvatar(){
			if(document.querySelector('input#file-emp-compo[type=file]').value != ''){
	        	let preview = document.querySelector('img#avatar-lg'); //selects the query named img
			    let file    = document.querySelector('input#file-emp-compo[type=file]').files[0]; //sames as here

			    let type = file['type'];
			    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid Image type');
				      return false;
				}
				if(file.size >= 2000000)
				{
					alert('Filesize exceed 2MB');
				    return false;
				}

				if (file) {
				    let reader  = new FileReader();
				          reader.onloadend = function () {
				          preview.src = reader.result;
				     }
				     reader.readAsDataURL(file);
				     return file;
				}
			}else{

				return null;
			}
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
        }

    },
    computed:{
		isFormValid(){
			return !Object.keys(this.fields).some(key => this.fields[key].invalid);
		}
	},
    beforeDestroy(){
        bus.$off('setupdate', this.setDataForEdit)
    },
    mounted(){
        // get Position
        axios.get('api/getposition')
        .then((response)=>{
            this.positions =  response.data;
            this.$parent.posNames = response.data;
        })
        .catch((err)=>{

        });
         // get Department
        axios.get('api/getdept')
        .then((response)=>{
            this.deptnames =  response.data;
            this.$parent.deptNames = response.data;
        })
        .catch((err)=>{

        });
         // get Branch
        axios.get('api/getbranch')
        .then((response)=>{
            this.branchnames =  response.data;
            this.$parent.branchNames = response.data;
        })
        .catch((err)=>{

        });

        // get Company
        axios.get('api/getcompany')
        .then((response)=>{
            this.compnames =  response.data;
            this.$parent.compNames = response.data;
        })
        .catch((err)=>{

        });


        this.MDBINPUT();
        // MODAL
        $('#myModal').on("hidden.bs.modal", this.closeModal);
        // $('#myModal').on("show.bs.modal", this.MDBINPUT);
        // EVENT BUS
        bus.$on('setupdate', this.setDataForEdit);

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



/*
function previewFile(){
       var preview = document.querySelector('img#aw'); //selects the query named img
       var file    = document.querySelector('input#aws[type=file]').files[0]; //sames as here
       var type = file['type'];
       const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];

       if (!validImageTypes.includes(type)) {
         alert('asdfasdf');
         return false;
      	}

       if (file) {
        var reader  = new FileReader();
          reader.onloadend = function () {
             preview.src = reader.result;
         }
           reader.readAsDataURL(file); //reads the data as a URL
       }
  } */