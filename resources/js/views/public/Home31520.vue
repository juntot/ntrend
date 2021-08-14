<template>
	
    <div>
        <!-- <div style="margin: 20px;">
		 
		</div> -->
            <div class="main-post" v-show="$root.userinfo.canPost">
			    <div class="post-container b-radius">
				    <div class="post-header">
					    <div class="post-bar">
						    <span class="post-title">POST AN ANNOUNCEMENT</span>
					</div>
				    </div>
				    <div class="post-content">
					    <!-- <form enctype="multipart/form-data"> -->
						    <div class="dflex">
							    <div class="post-avatar avatar align-self-start">
								    <img id="post-avatar" :src="$root.userinfo.avatar? 'storage/app/'+$root.userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px">
							    </div>
							    <div class="post-textarea flex-grow-8">
								    <textarea ref="search" id="textarea-post" class="autoExpand" v-model="posts.message" name="post-message" data-autoresize rows="1" cols="500" data-min-rows='1' :placeholder="placeholder" v-validate="'max:65520'" ></textarea>
									
									<div id="attachment-container">
										<!-- videos preview-->
										<div id="video-preview" v-if="type == 'mp4'">
											<video width="100%" controls="controls">
												<source src="" type="video/mp4">
											</video>
										</div>

										<!-- images preview-->
										<div id="image-preview" v-if="('jpg jpeg png gif').includes(type) && type != ''">
											<img src="" style="width: 100%; height: auto;" />
										</div>
									</div>
							    </div>
						    </div>
							<!-- error -->
							<span class="errors" v-show="errors.has('post-message')">{{ errors.first('post-message') }} Total characters: {{countPostString}}/65520</span>
						    <hr  id="narrow-margin">
						    <div class="post-tags">
								<div class="dflex justify-space-between">
									<div class="tag-row">
										<!-- <button class="btn btn-info round" @click.prevent="">photos/vidoes</button> -->
										<label for="file" class="btn btn-info round">
											photos/videos
										</label>
										<input id="file" type="file" style="display: none;" @change.prevent="newFile">
										<!-- employee tags -->
										<!-- <button class="btn btn-info round"
											id="btn-popupover-employee"
										>tag employee</button> -->
										<v-popover offset="3" style="display: inline;">
												<span class="btn btn-info round" @click="removeClassBody">tag employee</span>
												<template slot="popover">
													<div>
															<div class="popover-title">
																<div class="input-group">
																	<span class="input-group-addon"><i class="fas fa-search"></i></span>
																	<input id="email" type="text" class="form-control" v-model="searchEmp" placeholder="Search employees" @keyup.prevent="searchEmployee">
																</div>
															</div>
															<div class="popover-content">
																<ul class="nopadding nomargin nostyle-list emplist ">
																	<li v-for="(obj, key) in empList" :key="key">
																		<label class="mdblbl inline-blocklbl normal-weight">
																			<input type="checkbox" :value="obj.empID" v-model="checkedNames" @click="appendTagWithName(obj, $event)"> 
																			<span class="mdbcheckmark"></span>
																			{{obj.fullname}}
																		</label>
																		
																	</li>
																</ul>
															</div>
													</div>
												</template>
										</v-popover>
										<!-- end employee tag -->

										<!-- dept tags -->
										<v-popover offset="3" style="display: inline;">
												<span class="btn btn-info round" @click="removeClassBody">tag department</span>
												<template slot="popover">
													<div>
															<div class="popover-title">
																<div class="input-group">
																	<span class="input-group-addon"><i class="fas fa-search"></i></span>
																	<input id="email" type="text" class="form-control" v-model="searchDept" placeholder="Search departments" @keyup.prevent="searchDepartment">
																</div>
															</div>
															<div class="popover-content">
																<ul class="nopadding nomargin nostyle-list emplist">
																	<li v-for="(obj, key) in deptList" :key="key">
																		<label class="mdblbl inline-blocklbl normal-weight">
																			<input type="checkbox" :value="obj.deptID" v-model="checkedDepts" @click="appendTagWithDept(obj, $event)"> 
																			<span class="mdbcheckmark"></span>
																			{{obj.deptname}}
																		</label>
																		
																	</li>
																</ul>	
															</div>
															
													</div>
												</template>
										</v-popover>
										<!-- end dept tag -->
									</div>
									<div class="tag-row">
										<input type="submit" value="post" class="btn btn-primary" @click.prevent="addPost" v-show="posts.message" style="width: 80px;" :disabled="disableBtn || !isFormValid">
									</div>
								</div>
								<div class="tag-row attachments" v-if="false">
									{{attachmentNames? 'Attachment: '+attachmentNames: ''}}
								</div>
						    </div>
					    <!-- </form> -->
				    </div>
					
			    </div>
		    </div>
			<div class="main-post" v-for="(val, index) in announcement" :key="index">
			    <div class="post-container b-radius">
				    <div class="post-header post-header-notitle">
					    <!-- <div class="post-bar">
						    <span class="post-title">POST AN ANNOUNCEMENT</span>
						</div> -->
				    </div>
				    <div class="post-content">
						
							<button type="button" data-dismiss="modal" @click="delPost(index, val.postID)" v-if="val.postedby_ == $root.userinfo.empID" class="close post-close-btn">×</button>
						    <div class="dflex d-align-center">
							    <div class="post-avatar avatar align-self-start">
								    <img id="post-avatar" :src="val.avatar? 'storage/app/'+val.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px">
							    </div>
							    <div class="post-textarea flex-grow-8">
								    <h5>{{val.fullname}}</h5>
									
							    </div>
						    </div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div  class="relative-pos">
									<div data-toggle="modal" data-target="#myModal" @click="editMsg(index)" :class="val.type == 'mp4'?'trriger-modal-overlay-video':'trriger-modal-overlay'">
									</div>
										<div v-for="(line, keys) in val.message.split('\n')" :key="keys">
											<template>
												{{line}}<br>
											</template>
										</div>
										<!-- videos -->
										<div v-if="val.type == 'mp4'">
											<video width="100%" controls="controls" >
												<source :src="'storage/app/'+val.attachment" type="video/mp4">
											</video>
										</div>

										<!-- images -->
										<div v-if="('jpg jpeg png gif').includes(val.type) && val.type != ''">
											<img :src="'storage/app/'+val.attachment" style="width: 100%; height: auto;" />
										</div>
									</div>
								
								<hr id="narrow-margin-post">
								<div class="post-date-diff" style="font-size: 12px; margin: 10px 0px;">
									<div class="dflex justify-space-between">
										<div class="tag-icons">
											{{ val.dateposted | moment }}
										</div>
										<div class="tag-icons">
											<a><i class="fas fa-users tag-icons custom-tooltip">
													<span class="custom-tooltiptext">Tooltip text</span>
												</i></a>
											<a><i class="fas fa-braille tag-icons"></i></a>
										</div>
									</div>
								</div>
							</div>
							
				    </div>
			    </div>
		    </div>
			 <!-- Modal -->
            <div id="myModal" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog modal-lgx">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
							<div class="dflex justify-space-between">
								<div>
									<div class="dflex d-align-center">
										<div class="post-avatarxx avatar align-self-start" style="margin-right: 10px;">
											<img id="post-avatar" :src="$root.userinfo.avatar? 'storage/app/'+$root.userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px">
										</div> 
										<div class="post-textareaxx flex-grow-8">
											<h5>standard user<br><i class="post-date-diff" style="padding-top: 5px; display: inline-block;">{{ formUpdate.dateposted | moment }}</i></h5>
										</div>
									</div>
									<!-- <img id="post-avatar" :src="$root.userinfo.avatar? 'storage/app/'+$root.userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px"> -->
									
								</div>
								
								<button type="button" class="close modal-announcement-close" data-dismiss="modal">&times;</button>
							</div>
                            
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
							<!-- error -->
							<span class="errors" v-show="errors.has('message-update')">{{ errors.first('message-update') }} Total characters: {{countPostUpdateString}}/65520</span>
                            <textarea name="message-update" id="textarea-postxxxx" data-autoresizetextarea class="noborder width-100" rows="1" v-model="formUpdate.message" v-validate="'max:65520'" ></textarea>

							<!-- videos -->
							<div v-if="formUpdate.type == 'mp4'">
								<video width="100%" controls="controls" class="video-modal">
									<source :src="'storage/app/'+formUpdate.attachment" type="video/mp4">
								</video>
							</div>

							<!-- images -->
							<div v-if="('jpg jpeg png gif').includes(formUpdate.type) && formUpdate.type != ''">
								<img :src="'storage/app/'+formUpdate.attachment" style="width: 100%; height: auto;" />
							</div>
                        </div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" @click.prevent="updatePost" :disabled="!isFormValid">Update</button>
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
            posts:{
				message: '',
				dateposted: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
			},
			formUpdate:{
				attachment:'',
				avatar:'',
				dateposted:'',
				fullname:'',
				message:'',
				postID:'',
				postedby_:'',
				status:'',	
				taggedepts:'',
				taggednames:'',
				type:'',

			},
			announcement: [],
			// postmsg: '',
			page: 1,
			attachmentNames: '',
			type: '',
				// search for tags
			searchEmp: '',
			searchDept: '',
				// array result for search
			empList: [],
			deptList: [],
				// tags
			checkedNames: [],
			checkedDepts: [],

			tagwithEmp: [],
			tagwithDept: [],
			disableBtn:  false,
        };
	},
	methods:{
		addPost(){
			this.disableBtn = true;
			let fullname = this.$root.userinfo.fullname;
			let file = this.newFile();

			// include users empID and deptID b4 posting
			this.checkedNames.push(this.$root.userinfo.empID);
			this.checkedDepts.push(this.$root.userinfo.deptID_);
			

			let formData = new FormData();
			
			formData.append('postedby_',  this.$root.userinfo.empID);
			// formData.append('deptID_',  this.$root.userinfo.deptID_);
			formData.append('message',  this.posts.message);
			formData.append('dateposted',  this.posts.dateposted);
			formData.append('file', file );
			formData.append('type',  this.type);

			formData.append('taggednames', this.checkedNames);
			let arrDept = [];
			this.checkedDepts.forEach(obj => {
				arrDept.push('dept'+obj);
			});
			// formData.append('taggedepts', this.checkedDepts);
			formData.append('taggedepts', arrDept);

			// TAG WITH EMP
			let arrTagEmp = [];
			this.tagwithEmp.forEach(obj => {
				arrTagEmp.push(obj.fullname);
			});
			formData.append('tagwith_emp', arrTagEmp);

			// TAG WITH DEPT
			let arrTagDept = [];
			this.checkedDepts.forEach(obj => {
				arrTagDept.push(obj.deptname);
			});
			formData.append('tagwith_dept', arrTagDept);

			axios.post('api/addAnnouncement', formData).then((response)=>{
				response.data['fullname'] = fullname;
				response.data['avatar'] = this.$root.userinfo.avatar;
				this.reset();
				
				this.announcement.unshift(response.data);
				this.textAreaResize();
				this.disableBtn = false;
				
			})
			.catch((err)=>{ console.log(err); });

			
		},
		updatePost(){
			// alert('aws');
			this.formUpdate['dateposted'] = moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
			axios.post('api/updateAnnouncement', this.formUpdate).then((response)=>{
				this.announcement.splice(this.announcement.findIndex(obj => obj.postID == response.data.postID), 1);
				response.data['avatar'] = this.$root.userinfo.avatar;
				this.announcement.unshift(response.data);
				$("#myModal").modal('hide');
			})
			.catch((err)=>{ console.log(err); });
		},
		delPost(index, postID){
			if(confirm("Are you sure you want to delete this post?"))
			{
				
				axios.get('api/delAnnouncementLogical/'+postID)
				.then((response)=>{
					return this.announcement.splice(index, 1);
				})
				.catch(err=>{console.log(err);});
				
			}
			
		},
		editMsg(postIndex){
			let obj = this.announcement[postIndex];
			for(let index in obj)
			{
				this.formUpdate[index] = obj[index];
			}
		},
		appendTagWithName(objname= {} , event){
			if(event.target.checked){
				this.tagwithEmp.push(objname);
			}else{
				this.tagwithEmp = this.tagwithEmp.filter(obj=>{
					return obj.empID != objname.empID;
				})
			}
			// this.tagwithEmp.push(fullname);
		},
		appendTagWithDept(objdept={}, event){
			if(event.target.checked){
				this.tagwithDept.push(objdept);
			}else{
				this.tagwithDept = this.tagwithDept.filter(obj=>{
					return obj.deptID != objdept.deptID;
				})
			}
			
		},
		textAreaResize(){
			let offset = this.offsetHeight - this.clientHeight;
            $('textarea#textarea-post').css('height', 'auto').css({'height': $('textarea#textarea-post').scrollHeight + offset, "max-height": '200px'});
		},
		handleScroll () {
			if($(window).scrollTop() + $(window).height() >= $(document).height()) {
				this.page++;
				axios.get('api/getAnnouncement?page='+this.page).then((response)=>{
					let arraymerge = this.announcement.concat(response.data.data);
					this.announcement = arraymerge;
				})
				.catch((err)=>{ console.log(err); });
  			}

		},
		newFile(){
			
			if(document.querySelector('input#file[type=file]').value != ''){
	        	// let preview = document.querySelector('img#avatar-lg'); //selects the query named img
			    let file = document.querySelector('input#file[type=file]').files[0]; //sames as here
				let type = file['type'];
				this.type = ((type).split('/'))[(type).split('/').length - 1];
				const validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];
				const imageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];
				console.log(type);
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid File type');
				      return false;
				}
				if(file.size >= 50000000)
				{
					alert('Filesize exceed 50MB');
				    return false;
				}
				this.attachmentNames = file.name;
				if (file) {
					
					if(imageTypes.includes(type)){
						let reader  = new FileReader();
						reader.onloadend = function () {
							  let preview = document.querySelector('#image-preview img');
							  preview.src = reader.result;
						}
						reader.readAsDataURL(file); 
					}else{
						let reader  = new FileReader();
						reader.onloadend = function () {
							 let preview = document.querySelector('#video-preview video');
							 let blobURL = window.URL.createObjectURL(file);
							 preview.src = blobURL;
						}
						reader.readAsDataURL(file);
						
						
					}
				     
				     return file;
				}
			}else{

				return null;
			}
		},
		searchEmployee(){
			let search = (this.searchEmp).replace(/[^a-zA-Z0-9-. ]/g, '');
			axios.get('api/search-emp/'+search).then(res=>{
				if(res.data.length > 0)
				this.empList = res.data;
			})
			.catch(err => console.log(err));
		},
		searchDepartment(){
			let search = (this.searchDept).replace(/[^a-zA-Z0-9-. ]/g, '');
			axios.get('api/search-dept/'+search).then(res=>{
				if(res.data.length > 0)
				this.deptList = res.data;
			})
			.catch(err => console.log(err));

			
		},
		reset(){
			this.type = '';
			this.attachmentNames = '';
			this.posts.message = '';
			document.querySelector('input#file[type=file]').value = '';
		},
		removeClassBody(){
			$('body').removeClass('push-notification');
		},
		closeModal(e){
			this.formUpdate = {
				attachment:'',
				avatar:'',
				dateposted:'',
				fullname:'',
				message:'',
				postID:'',
				postedby_:'',
				status:'',	
				taggedepts:'',
				taggednames:'',
				tagwith_emp: '',
				tagwith_dept: '',
				type:'',
			};
			$('#textarea-postxxxx').attr('data-autoresizetextarea');
			$('#textarea-postxxxx').removeAttr("style");
            $('video').trigger('pause');
        },
		openModal(){
			$('video').trigger('pause');
			jQuery.each(jQuery('textarea#textarea-postxxxx'), function() {
			
				let offset = this.offsetHeight - this.clientHeight;
							
				let resizeTextarea2 = function(el) {
					// jQuery(el).css('height', 'auto').css({'height': el.scrollHeight + offset, "max-height": '200px'});
					jQuery(el).css('height', 'auto').css({'height': el.scrollHeight + offset, "max-height": '200px'});
				};
				
					resizeTextarea2(this);
			});
		}

	},
	filters:{
		moment: function (date) {
    		return moment(date).fromNow();  
  		}
	},
	computed:{
		placeholder(){
			return 'What\'s up for today '+this.$root.userinfo.fname+'?';
		},
		countPostString(){
			return this.posts.message.length;
		},
		countPostUpdateString(){
			return this.formUpdate.message.length;
		},
		isFormValid(){
                return !Object.keys(this.fields).some(key => this.fields[key].invalid);
        },
	},
	created () {
 		window.addEventListener('scroll', this.handleScroll);
	},
	beforeDestroy(){
		document.getElementById('content').className += " content";
	},
	destroyed () {
		window.removeEventListener('scroll', this.handleScroll);
	},
    mounted(){
		// remove white background for content
		document.getElementById('content').classList.remove("content");
		// resize text area post
        jQuery.each(jQuery('textarea[data-autoresize]'), function() {
			
            let offset = this.offsetHeight - this.clientHeight;
                        
            let resizeTextarea = function(el) {
                jQuery(el).css('height', 'auto').css({'height': el.scrollHeight + offset, "max-height": '200px'});
            };
			jQuery(this).on('keyup input', function() { 
				resizeTextarea(this);
				$('#attachment-container').css({"padding-top": "20px"});
			}).removeAttr('data-autoresize');
			
		});
		

		jQuery.each(jQuery('textarea[data-autoresizetextarea]'), function() {
			
            let offset = this.offsetHeight - this.clientHeight;
                        
            let resizeTextarea2 = function(el) {
                jQuery(el).css('height', 'auto').css({'height': el.scrollHeight + offset, "max-height": '200px'});
            };
			jQuery(this).on('keyup input', function() { 
				resizeTextarea2(this);
			}).removeAttr('data-autoresizetextarea');
			
		});

		axios.get('api/getAnnouncement').then((response)=>{
			this.announcement = response.data.data;
		})
		.catch((err)=>{ console.log(err); });
		

		
		$('#myModal').on("hidden.bs.modal", this.closeModal);
		$(document).on('shown.bs.modal','#myModal', this.openModal);
		
    }
}
</script>