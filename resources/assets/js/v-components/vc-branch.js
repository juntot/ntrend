// BRANCH ENTRY
Vue.component('branch-entry', {
	data: function () {
		return {
			branchname: '',
			isDisable: false,
		}
	},
	methods:{
		addBranch(){
			if(this.isFormValid){
				this.isDisable = true;
				let self = this;
				let params = this.$data;
				axios.post('addbranch', params).then(function (response) {
					self.$root.getBranch();
					self.closeModal();
					
				});
			}
		},
		closeModal(){ 		
			let obj = this.$data;
			Object.keys(obj).forEach((key)=>{
				if(key=='isDisable'){
				 this.$data[key] = false;
				}else{	
				this.$data[key] =  '';
				}
			});
			$("#myModal").modal("hide");
		},
		

	},
	computed:{
			isFormValid(){
				return !Object.keys(this.fields).some(key => this.fields[key].invalid);
			}
	},

	template: `
	<div>
	<form method="post" @submit.prevent="addBranch">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Modal Header</h4>
			</div>
				<div class="modal-body">
				    <div class="vforms">
						<div class="group">      
							<input type="text" v-validate="'required'" v-model="branchname" name="branchname" required>
							<span class="highlight"></span>
							<span class="bar"></span>
							<label class="mdb">Branch name</label>
							<span class="errors">{{ errors.first('branchname') }}</span>
						</div>
					</div>		  
				</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-primary" value="Submit" :disabled="isDisable || !isFormValid">
		</div>
	</form>
	</div>`
});