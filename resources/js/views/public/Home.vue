<template>
		
    <div>
				<!-- fireworks & confetti -->
			<div style="position: relative" v-show="showGreeting">
					
					<canvas id="my-canvas" 
					style="top:0; position: fixed; left:0; width: 100%; z-index: 9
					"></canvas>
					<div class="fireworks-container" 
					@click="hideGreetings"
					style="
					left: 0;
					top: 0;
					width: 100%;
					height: 100%;
					position: fixed;
					z-index: 9;
					background: rgba(0,0,0,.4);
					"
					></div>
					<div class="birthday-list" style="
					position: fixed; 
					z-index: 9;
					left: 50%;
					top: 50%;
    			transform: translate(-50%, -50%);
					box-shadow: rgba(0, 0, 0, 0.15) 0px 2px 8px;
					border-radius: 2px;
					padding: 15px;
					min-width: 350px;
					background: white;
					border: 10px solid;
					border-image-slice: 1;
					border-width: 5px;
					border-image-source: linear-gradient(to right, red, orange);
					max-height: 500px;
					overflow: auto;
					">
							<h4
							style="
							font-size: 3rem;
							background: -webkit-linear-gradient(#e73827, #f85032);
							font-weight: bold;
							-webkit-background-clip: text;
							-webkit-text-fill-color: transparent;
							text-align: center;
							line-height: 150%;
							position: static;
							top: 0;
							"
							>HAPPY BIRTHDAY</h4>
							<ul style="list-style: none">
								<li v-for="(birthday, idx) in birthdays" :key="idx">
									<div class="dflex d-align-center">
										<div class="post-avatar avatar align-self-start">
											<img id="avatar-sm" :src="birthday.avatar? 'storage/app/'+birthday.avatar :'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px; height: 50px; object-fit: cover;">
										</div>
										<div class="flex-grow-1" style="width: 100%;">
											<h5 class="avatar-name">{{birthday.fullName}} <br>
												<small>{{birthday.birthdate | filterBirthDate}}</small><br>
												<small>{{birthday.posname}}</small><br>
												<small>{{birthday.deptname}}</small><br>
												<small>{{birthday.branchname}}</small><br>
											</h5>
										</div>
									</div>
								</li>
							</ul>
							<div class="text-center">
									<i
							style="
							font-size: 12px;
							background: -webkit-linear-gradient(#e73827, #f85032);
							-webkit-background-clip: text;
							-webkit-text-fill-color: transparent;
							"
							>Greetings from: Exceltrend Family</i>
							</div>
					</div>
			</div>
			<!-- end -->
		<div id="loader-announcement">
			<div class="loader-fixed">
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
			</div>
		</div>
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
								    <img id="post-avatar" class="avatar-status" :src="$root.userinfo.avatar? 'storage/app/'+$root.userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px; height: 50px; object-fit: cover;">
							    </div>
							    <div class="post-textarea flex-grow-8">
								    <textarea ref="search" id="textarea-post" class="autoExpand" v-model="posts.message" name="post-message" data-autoresize rows="1" cols="500" data-min-rows='1' :placeholder="placeholder" v-validate="'max:777215'" ></textarea>

									<div id="attachment-container">
										<!-- videos preview-->
										<div id="video-preview" v-if="type == 'mp4'">
											<video width="100%" controls="controls" controlsList="nodownload">
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
										<label for="file-attachment" class="btn btn-info round">
											photos/videos
										</label>
										<input id="file-attachment" type="file" style="display: none;" @change.prevent="newFile">
									
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
										<!-- company tags -->
										<v-popover offset="3" style="display: inline;">
												<span class="btn btn-info round" @click="removeClassBody">tag company</span>
												<template slot="popover">
													<div>
															<div class="popover-title">
																<div class="input-group">
																	<span class="input-group-addon"><i class="fas fa-search"></i></span>
																	<input id="company" type="text" class="form-control" v-model="searchComp" placeholder="Search companies" @keyup.prevent="searchCompany">
																</div>
															</div>
															<div class="popover-content">
																<ul class="nopadding nomargin nostyle-list emplist">
																	<li v-for="(obj, key) in compList" :key="key">
																		<label class="mdblbl inline-blocklbl normal-weight">
																			<input type="checkbox" :value="obj.compID" v-model="checkedComps" @click="appendTagWithComp(obj, $event)">
																			<span class="mdbcheckmark"></span>
																			{{obj.compname}}
																		</label>

																	</li>
																</ul>
															</div>

													</div>
												</template>
										</v-popover>
										<!-- end company tag -->
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
								    <img id="post-avatar" :src="val.avatar? 'storage/app/'+val.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px; height: 50px; object-fit: cover;">
							    </div>
							    <div class="post-textareax flex-grow-8">
								    <h5 class="avatar-name">{{val.fullname}}</h5>
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
											<video width="100%" controls="controls" controlsList="nodownload">
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
										<div>
											<!-- <i class="fas fa-comment-dots tag-icons"></i> -->
										</div>
										<div class="tag-icons">
											<a data-toggle="modal" data-target="#myModalSmall" @click.prevent="showTags(val.tagwith_emp)"><i class="fas fa-users tag-icons" title="tag by users"></i></a>
											<a data-toggle="modal" data-target="#myModalSmall" @click.prevent="showTags(val.tagwith_dept)"><i class="fas fa-compress-arrows-alt tag-icons" title="tag by department"></i></a>
											<a data-toggle="modal" data-target="#myModalSmall" @click.prevent="showTags(val.tagwith_comp)"><i class="fas fa-braille tag-icons" title="tag by company"></i></a>
										</div>
									</div>
								</div>
								<hr id="narrow-margin-post">
								<!-- comments section -->
								<div>
									<p>
										<span class="more-comments"  v-if="val.comments.length > 1 " data-toggle="modal" data-target="#myModal" @click="editMsg(index)">
												View more comments
										</span>
									</p>
									<div class="comment-wrap" v-for="comment in (val.comments).slice(-1)" :key="comment.comment_ID">
										<div class="dflex justify-space-between" >
											<div>
												<img :src="comment.avatar? 'storage/app/public/avatar/'+comment.commentBy_ : 'public/images/priemer_jacket.jpg'" alt="Avatar" 
												style="width:35px; height: 35px; object-fit: cover; border-radius: 50%">
											</div>
											<div style="margin-left: 8px; flex: 1">
												
												<div class="comment-list">
													<!-- commentator name -->
													<span class="avatar-name">{{comment.fullname || ''}}</span><br/>
													{{comment.text_comment || ''}}

													<div class="comment-actionx" v-if="comment.commentBy_ == $root.userinfo.empID">
														<span @click="deleteComment(comment)">
															<i class="fas fa-trash color-redorange" title="tag by users"></i>
														</span>
													</div>
												</div>
											</div>
										</div>
										
									</div>
									
								</div>
									<!-- comment textarea -->
								<div style="padding-bottom: 15px; text-align: right;">
									<div class="dflex justify-space-between">
										<div>
											<img :src="$root.userinfo.avatar? 'storage/app/'+$root.userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" 
											style="width:35px; height: 35px; object-fit: cover; border-radius: 50%">
										</div>
										<div style="margin-left: 8px; flex: 1">
											<textarea name="comment" @keyup="showBtnComment" :data-postid="val.postID" placeholder="Write a comment.." id="textarea-comment" data-autoresizecomment class="noborder txtarea-comment width-100" rows="1" v-validate="'max:777215'">
											</textarea>
										</div>
									</div>
									
									<button @click.prevent="addComment" class="btn btn-info round" v-if="showCommentBtn.display && val.postID == showCommentBtn.id">
										Add Comment
									</button>
								</div>
									<!-- end textarea -->
								<!-- end comemnts section -->
							</div>

				    </div>
			    </div>
		    </div>
			<!-- no post availble -->
			<div class="" v-if="isNoPost">
				<div class="pagenotfound dflex d-align-center justify-content-center nopost">
					<div class="code">
						<i class="fas fa-tasks"></i>
					</div>
					<div class="message" style="padding: 10px;">
						No availble post
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
											<img id="post-avatar" :src="formUpdate.avatar? 'storage/app/'+formUpdate.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px; height: 50px; object-fit: cover;">
										</div>
										<div class="post-textareaxx flex-grow-8">
											<h5>{{formUpdate.fullname || ''}}<br><i class="post-date-diff" style="padding-top: 5px; display: inline-block;">{{ formUpdate.dateposted | moment }}</i></h5>
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
                            <textarea name="message-update" id="textarea-postxxxx" data-autoresizetextarea class="noborder width-100" rows="1" v-model="formUpdate.message" v-validate="'max:777215'"
								:disabled="formUpdate.postedby_ != $root.userinfo.empID || daysDif > 1"></textarea>

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



							<!-- comments -->
							<hr id="narrow-margin-post">
							<!-- comments section -->
								<div class="comment-wrap" v-for="comment in formUpdate.comments" :key="comment.comment_ID">
									<div class="dflex justify-space-between" >
										<div>
											<img  :src="comment.avatar? 'storage/app/public/avatar/'+comment.commentBy_ : 'public/images/priemer_jacket.jpg'" alt="Avatar" 
											style="width:35px; height: 35px; object-fit: cover; border-radius: 50%">
										</div>
										<div style="margin-left: 8px; flex: 1">
											<div class="comment-list">
												<span class="avatar-name">{{comment.fullname || ''}}</span><br/>
												{{comment.text_comment || ''}}
												
												
												<div class="comment-actionx" v-if="comment.commentBy_ == $root.userinfo.empID">
													<span @click="deleteComment(comment)">
														<i class="fas fa-trash color-redorange" title="tag by users"></i>
													</span>
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="comment-action" v-if="comment.commentBy_ == $root.userinfo.empID">
										<span @click.prevent="deleteComment(comment)">delete</span>
									</div> -->
								</div>
									<!-- comment textarea -->
								<div style="padding-bottom: 15px; text-align: right;">
									<div class="dflex justify-space-between">
										<div>
											<img :src="$root.userinfo.avatar? 'storage/app/'+$root.userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" 
											style="width:35px; height: 35px; object-fit: cover; border-radius: 50%">
										</div>
										<div style="margin-left: 8px; flex: 1">
											<textarea name="comment" @keyup="showBtnCommentModal" :data-postid="formUpdate.postID" placeholder="Write a comment.." id="textarea-comment" data-autoresizecomment class="noborder txtarea-comment width-100" rows="1" v-validate="'max:777215'">
											</textarea>
										</div>
									</div>
									
									<button @click.prevent="addCommentModal" class="btn btn-info round" v-if="showCommentBtnModal.display && formUpdate.postID == showCommentBtnModal.id">
										Add Comment
									</button>
									<button type="button" class="btn btn-primary" @click.prevent="updatePost" :disabled="!isFormValid"
									v-show="formUpdate.postedby_ == $root.userinfo.empID || daysDif < 1"
									>
										Update POST
									</button>
								</div>
									<!-- end textarea -->
								<!-- end comemnts section -->
                        </div>
						<!-- <div class="modal-footer" v-show="formUpdate.postedby_ == $root.userinfo.empID && daysDif < 1" >
							
						</div> -->
                    </div>
                </div>
            </div> <!---END MODAL-->


			<!-- TAG MODALS SMALL -->
			<!-- Modal -->
				<div class="modal fade" id="myModalSmall" role="dialog">
					<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						 Post - Tags
						</div>
						<div class="modal-body">
							<ul style="padding: 15px;">
								<li v-for="(tags, key) in tagLists" :key="key">
									{{tags}}
								</li>
							</ul>
						</div>
					</div>
					</div>
				</div>
			<!-- END -->

    </div>
</template>

<script>
let defaultCompList = [];
let fireworks;
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
					showCommentBtn: {
						display: false,
						id:''
					},
					showCommentBtnModal: {
						display: false,
						id:''
					},
					comment: [],
					announcement: [],
					// postmsg: '',
					page: 1,
					attachmentNames: '',
					type: '',
						// search for tags
					searchEmp: '',
					searchDept: '',
					searchComp: '',
						// array result for search
					empList: [],
					deptList: [],
					compList: [],
						// tags
					checkedNames: [],
					checkedDepts: [],
					checkedComps: [],

					tagwithEmp: [],
					tagwithDept: [],
					tagwithComp: [],

					disableBtn:  false,
					// for modal show small
					tagLists: [],
					isNoPost: false,

					// greetings
					showGreeting: false,
					birthdays: [],
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

			let arrComp = [];
			if(this.checkedComps.length){
				this.checkedComps.forEach(obj => {
					arrComp.push('comp'+obj);
				});
			}else{
				// this will be use to tag all post to all company by default
				// check the tag with comp below similar code
				defaultCompList.forEach(obj => {
					arrComp.push('comp'+obj.compID);
				});
			}
			
			// formData.append('taggedepts', this.checkedDepts);
			formData.append('taggedcomps', arrComp);



			// TAG WITH EMP
			let arrTagEmp = [];
			this.tagwithEmp.forEach(obj => {
				arrTagEmp.push(obj.fullname);
			});
			formData.append('tagwith_emp', arrTagEmp);

			// TAG WITH DEPT
			let arrTagDept = [];
			this.tagwithDept.forEach(obj => {
				arrTagDept.push(obj.deptname);
			});
			formData.append('tagwith_dept', arrTagDept);

			// TAG WITH COMP
			let arrTagComp = [];
			if(this.checkedComps.length) {
				this.tagwithComp.forEach(obj => {
					arrTagComp.push(obj.compname);
				});
			}else{
				defaultCompList.forEach(obj => {
					arrTagComp.push('comp'+obj.compname);
				});
			}
			formData.append('tagwith_comp', arrTagComp);

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

		appendTagWithComp(objcomp={}, event){
			if(event.target.checked){
				this.tagwithComp.push(objcomp);
			}else{
				this.tagwithComp = this.tagwithComp.filter(obj=>{
					return obj.compID != objcomp.compID;
				});
			}
		},


		textAreaResize(){
			let offset = this.offsetHeight - this.clientHeight;
            $('textarea#textarea-post').css('height', 'auto').css({'height': $('textarea#textarea-post').scrollHeight + offset, "max-height": '200px'});
		},
		handleScroll (e) {
			// console.log(Math.abs($(window).scrollTop() + $(window).height()), ($(document).height()-10));
			if(Math.abs(window.innerHeight + window.scrollY) >= Math.abs(document.body.offsetHeight-10)){
			// if($(window).scrollTop() + $(window).height() == $(document).height()) {
			//  if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
				 	$('#loader-announcement').show();
					this.page++;
					axios.get('api/getAnnouncement?page='+this.page).then((response)=>{
						setTimeout(function(){
							$('#loader-announcement').hide();
						}, 500);

						if(response.data.data.length>0){
							let arraymerge = this.announcement.concat(response.data.data);
							this.announcement = arraymerge;

						}else{
							this.page--;
						}

					})
					.catch((err)=>{ console.log(err);
						$('#loader-announcement').hide();
					});
					// console.log(this.page);


			}
			// if($(window).scrollTop() + $(window).height() >= $(document).height()-5) {
			// 	$('#loader-announcement').show();
			// 	this.page++;

			// 	axios.get('api/getAnnouncement?page='+this.page).then((response)=>{
			// 		setTimeout(function(){
			// 			$('#loader-announcement').hide();
			// 		}, 500);
			// 		let arraymerge = this.announcement.concat(response.data.data);
			// 		this.announcement = arraymerge;

			// 		// $div2.show();
			// 		// setTimeout(function() {
			// 		// 	$div2.hide();
			// 		// }, 10000);
			// 	})
			// 	.catch((err)=>{ console.log(err);
			// 		$('#loader-announcement').hide();
			// 	});
  			// }

		},
		newFile(){

			if(document.querySelector('input#file-attachment[type=file]').value != ''){
	        	// let preview = document.querySelector('img#avatar-lg'); //selects the query named img
			    let file = document.querySelector('input#file-attachment[type=file]').files[0]; //sames as here
				let type = file['type'];
				this.type = ((type).split('/'))[(type).split('/').length - 1];
				const validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];
				const imageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];
				// console.log(type);
			    if (!validImageTypes.includes(type)) {
				      alert('Invalid File type');
				      return false;
				}
				if(file.size >= 7000000000)
				{
					alert('Filesize exceed 7GB');
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
			let validSearch = /^[a-zA-Z0-9 Ññ)\._-]+$/g
			let regex = RegExp(validSearch);
			if(regex.test((this.searchEmp))){
				// let search = (this.searchEmp).replace(/[^a-zA-Z0-9-. ]/g, '');
				let search = this.searchEmp;
				axios.post('api/search-emp', {keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.empList = res.data;
					}
				})
				.catch(err => console.log(err));
			}

		},
		searchDepartment(){
			let validSearch = /^[a-zA-Z0-9 Ññ&)\._-]+$/g
			let regex = RegExp(validSearch);
			if(regex.test((this.searchDept))){
				// let search = (this.searchDept).replace(/[^a-zA-Z0-9-. ]/g, '');
				let search = this.searchDept;
				axios.post('api/search-dept',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.deptList = res.data;
					}
				})
				.catch(err => console.log(err));
			}

		},
		searchCompany(){
			let validSearch = /^[a-zA-Z0-9 Ññ&)\._-]+$/g
			let regex = RegExp(validSearch);
			if(regex.test((this.searchComp))){

				// let search = (this.searchComp).replace(/[^a-zA-Z0-9-. ]/g, '');
				let search = this.searchComp;
				axios.post('api/search-comp',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						this.compList = res.data;
					}
				})
				.catch(err => console.log(err));
			}
		},
		loadCompany(val){
			let validSearch = /^[a-zA-Z0-9 Ññ&)\._-]+$/g
			let regex = RegExp(validSearch);
			if(regex.test((val))){

				// let search = (val).replace(/[^a-zA-Z0-9-. ]/g, '');
				let search = val;
				axios.post('api/search-comp',{keyword: search}).then(res=>{
					if(res.data.length > 0 && res.status == 200){
						defaultCompList = res.data;
					}
				})
				.catch(err => console.log(err));
			}
		},

		showTags(string = ''){
			if(string != null){
				if(string.length > 0){
					let arrStringTags = string.split(',')
					this.tagLists = arrStringTags;
				}
			}

		},
		reset(){
			this.type = '';
			this.attachmentNames = '';
			this.posts.message = '';
			this.checkedNames = [];
			this.checkedDepts = [];
			this.tagwithEmp = [];
			this.tagwithDept = [];
			document.querySelector('input#file-attachment[type=file]').value = '';
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
					jQuery(el).css('height', 'auto').css({'height': el.scrollHeight + offset, "max-height": '100%'});
				};

					resizeTextarea2(this);
			});
		},
		clearTags(){
			this.tagLists = [];
		},

		showBtnComment(e){
			if(e.target.value.length >= 1){
				$('.txtarea-comment').not($(e.target)).val('');
				// .val('asd');
			}
			this.showCommentBtn = {
				display: e.target.value,
				id: e.target.dataset.postid
			};
		},
		addComment(){
			axios.post('api/addComment', {
				postID_: this.showCommentBtn.id,
				text_comment: this.showCommentBtn.display
			}).then((response)=>{
				if(response.data > 0 && response.status == 200){
					let ann = this.announcement;
					let index = this.announcement.findIndex(obj => obj.postID == this.showCommentBtn.id);

					(ann[index]['comments']).push({
						comment_ID: response.data,
						postID_: this.showCommentBtn.id,
						commentBy_: this.$root.userinfo.empID,
						text_comment: this.showCommentBtn.display,
						fullname: this.$root.userinfo.fullname,
						avatar: this.$root.userinfo.avatar,
					});
					this.announcement = ann;
					$('.txtarea-comment').val('');
				}
			})
			.catch((err)=>{ console.log(err); });
		},
		showBtnCommentModal(e){
			if(e.target.value.length >= 1){
				$('.txtarea-comment').not($(e.target)).val('');
				// .val('asd');
			}
			this.showCommentBtnModal = {
				display: e.target.value,
				id: e.target.dataset.postid
			};
		},
		addCommentModal(){
			axios.post('api/addComment', {
				postID_: this.showCommentBtnModal.id,
				text_comment: this.showCommentBtnModal.display
			}).then((response)=>{
				if(response.data > 0 && response.status == 200){
					let ann = this.announcement;
					let index = this.announcement.findIndex(obj => obj.postID == this.showCommentBtnModal.id);

					(ann[index]['comments']).push({
						comment_ID: response.data,
						postID_: this.showCommentBtnModal.id,
						commentBy_: this.$root.userinfo.empID,
						text_comment: this.showCommentBtnModal.display,
						fullname: this.$root.userinfo.fullname,
						avatar: this.$root.userinfo.avatar,
					});
					this.announcement = ann;
					$('.txtarea-comment').val('');
				}
			})
			.catch((err)=>{ console.log(err); });
		},

		editComment(){
			axios.post('api/editComment').then((response)=>{
				// if(response.data <= 0 && response.status == 200){
				// 	this.isNoPost = true;
				// }
				// this.announcement = response.data.data;
			})
			.catch((err)=>{ console.log(err); });
		},

		deleteComment(comment = {}){
			if(confirm("Are you sure you want to delete this comment?"))
			{

				axios.get('api/deleteComment/'+comment.comment_ID).then((response)=>{
					if(response.status == 200){
						// let post_index = this.announcement.findIndex(obj => obj.postID == comment.postID_);
						let ann = this.announcement;
						ann = ann.map(data=>{
							if(data.postID == comment.postID_){
								data.comments = data.comments.filter(comm=>comm.comment_ID != comment.comment_ID);
							}
							return data;
						});
					}
				})
				.catch((err)=>{ console.log(err); });

			}
			
		},

		// birthday greetings
		getBirthdays(){
				axios.get('api/birthdays').then((response)=>{
					if(response.status == 200 && response.data.length > 0){
						this.birthdays = response.data;
						if(!localStorage.getItem('greetings')){
							this.showGreeting = true;
							this.greetingConfetti();
						}else{
							const cacheDate = localStorage.getItem('greetings');
							const currentDate = moment().format('YYYY-MM-DD');
							if(cacheDate != currentDate){
								this.showGreeting = true;
								localStorage.setItem('greetings', currentDate);
								this.greetingConfetti();
							}
						}
					}
				})
				.catch((err)=>{ console.log(err); });
		},
		hideGreetings(){
			this.showGreeting = false;
			localStorage.setItem('greetings', moment().format('YYYY-MM-DD'));
			
		},
		// greetings confeeit
		greetingConfetti(){
			// if(!localStorage.getItem('greetings') || this.showGreeting) {	
					var confettiElement = document.getElementById('my-canvas');
					var confettiSettings = { target: confettiElement, rotate: true, size: 2 };
					var confetti = new ConfettiGenerator(confettiSettings);
					confetti.render();

			// }
		}

	},
	filters:{
		moment: function (date) {
    		return moment(date).fromNow();
		},
		filterBirthDate: function(date) {
			return moment(date).format('MMMM D');
		}
	},
	computed:{
		daysDif(){
			if(this.formUpdate.dateposted){
				let now = moment(new Date()); //todays date
				let end = moment(this.formUpdate.dateposted); // another date
				let duration = moment.duration(now.diff(end));
				let days = duration.asDays();
				// console.log(days);
				return days;
			}
			return 30;
		},
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
	
	beforeDestroy(){
		document.getElementById('content').className += " content";
	},
	destroyed () {
		window.removeEventListener('scroll', this.handleScroll);
	},

	updated(){
			jQuery.each(jQuery('textarea[data-autoresizecomment]'), function() {

				let offset = this.offsetHeight - this.clientHeight;

				let resizeTextarea3 = function(el) {
					jQuery(el).css('height', 'auto').css({'height': el.scrollHeight + offset, "max-height": '200px'});
				};
				jQuery(this).on('keyup input', function() {
					resizeTextarea3(this);
				}).removeAttr('data-autoresizecomment');

			});
		},
		created () {
 		// window.addEventListener('scroll', this.handleScroll);
		 this.getBirthdays();
		 this.loadCompany(' ');
		},
    mounted(){	
		// scroll
		// window.addEventListener('scroll', this.handleScroll);
		window.addEventListener('scroll', this.handleScroll);
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
			if(response.data <= 0 && response.status == 200){
				this.isNoPost = true;
			}
			this.announcement = response.data.data;
		})
		.catch((err)=>{ console.log(err); });



		$('#myModal').on("hidden.bs.modal", this.closeModal);
		$(document).on('shown.bs.modal','#myModal', this.openModal);

		// tags
		$('#myModalSmall').on("hidden.bs.modal", this.clearTags);

    }
}
</script>