// BRANCH ENTRY
<template>
    <div class="c">
        <form method="post">

				    <div class="vforms">
                        <div class="col-md-12">
                            <div class="mdb-form-field">
                                <div class="form-field__control">
                                    <input type="text" v-validate="'required'" v-model="branchname" class="form-field__input" name="branch name">
                                    <label class="form-field__label">Branch Name</label>
                                    <div class="form-field__bar"></div>
                                </div>
                                <span class="errors">{{ isDuplicate? 'Branch already exist' : errors.first('branch name') }}</span>
                            </div>
                        </div>
					</div>

			<div class="col-lg-12 modal-footer">
			    <input type="submit" class="btn btn-primary" value="Submit" @click.prevent="addBranch" :disabled="isDisable || !isFormValid || isDuplicate" v-if="!$parent.$data.forUpdate">
                <input type="submit" class="btn btn-primary" value="Update" @click.prevent="updateBranch" :disabled="isDisable || !isFormValid || isDuplicate" v-if="$parent.$data.forUpdate">
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</template>

<script>
export default {
    data(){
        return{
            branchname: '',
            branchID:'',
            datecreated: moment(new Date()).format('YYYY-MM-DD'),
            isDisable: false,
        };
    },
	methods:{
		addBranch(){
			if(this.isFormValid){
				this.isDisable = true;
				let params = this.$data;
				axios.post('api/addbranch',params)
				.then((response)=>{
                        this.$parent.addRow(response.data);
						this.closeModal();

				});
			}

        },
        updateBranch(){
			if(this.isFormValid){
				this.isDisable = true;
				let params = this.$data;
				axios.post('api/updatebranch',params)
				.then((response)=>{
                        this.$parent.updateRow(response.data);
						this.closeModal();

				});
			}

        },
        setDataForEdit(data = null){
            for(let i in this.$data)
            {
                if(i != 'isDisable')
                this.$data[i] = data[i];
            }

            this.MDBINPUT();
            $("#myModal").modal("show");
        },
		closeModal(){
            let obj = this.$data;
            // this.MDBINPUTRESET();
			Object.keys(obj).forEach((key)=>{
				if(key=='isDisable'){
					this.$data[key] = false;
				}else{
					this.$data[key] =  '';
				}
			});
			$("#myModal").modal("hide");
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
        },
        isDuplicate(){
            let branchname = this.branchname.toLowerCase();
            let rows = this.$parent.$data.rows;
            if(branchname != ''){
                let duplicate = rows.find(data=>(data.branchname.toLowerCase())==branchname);
                return duplicate ? true: false;
            }else{
                return false;
            }
        }
	},
    mounted(){
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
