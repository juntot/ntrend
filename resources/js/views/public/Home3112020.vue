<template>
	
    <div>
        <div style="margin: 20px;">
		  
	</div>
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
								    <textarea ref="search" id="textarea-post" class="autoExpand" v-model="posts.message" name="message" data-autoresize rows="1" cols="500" data-min-rows='1' :placeholder="placeholder" ></textarea>
							    </div>
						    </div>
						    <hr  id="narrow-margin">
						    <div class="post-tags">
								<div class="dflex justify-space-between">
									<div class="tag-row">
										<!-- <button class="btn btn-info round" @click.prevent="">photos/vidoes</button> -->
										<label for="file" class="btn btn-info round">
											photos/vidoes
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
																			<input type="checkbox" :value="obj.empID" v-model="checkedNames"> 
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
																			<input type="checkbox" :value="obj.deptID" v-model="checkedDepts"> 
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
										<input type="submit" value="post" class="btn btn-primary" @click.prevent="addPost" v-show="posts.message" style="width: 80px;" :disabled="disableBtn">
									</div>
								</div>
								<div class="tag-row attachments">
									{{attachmentNames? 'Attachement: '+attachmentNames: ''}}
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
						
							<button type="button" data-dismiss="modal" @click="delPost(index, val.postID)" v-if="val.empID_ == $root.userinfo.empID" class="close post-close-btn">×</button>
						    <div class="dflex d-align-center">
							    <div class="post-avatar avatar align-self-start">
								    <img id="post-avatar" :src="val.avatar? 'storage/app/'+val.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar" style="width:50px">
							    </div>
							    <div class="post-textarea flex-grow-8">
								    <h5>{{val.fullname}}</h5>
							    </div>
						    </div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div  data-toggle="modal" data-target="#myModal" @click="editMsg(val.message)">
										<div v-for="(line, keys) in val.message.split('\n')" :key="keys">
											<template>
												{{line}}<br>
											</template>
										</div>
										<!-- videos -->
										<div v-if="val.type == 'mp4'">
											<video width="100%" controls="controls"><source :src="'storage/app/'+val.attachment" type="video/mp4"></video>
										</div>

										<!-- images -->
										<div v-if="('jpg jpeg png gif').includes(val.type)">
											<img :src="'storage/app/'+val.attachment" style="width: 100%; height: auto;" />
										</div>
								</div>
								<hr id="narrow-margin-post">
								<p>
									{{ val.dateposted | moment }}
								</p>
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
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <!-- <h4 class="modal-title">Modal Header</h4> -->
                        </div>
                        <div class="modal-body">
                            <!-- <textarea name="" id="textarea-postxxxx"  rows="10" v-model="postmsg"></textarea> -->
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
			announcement: [],
			postmsg: '',
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
			disableBtn:  false,
        };
	},
	methods:{
		addPost(){
			this.disableBtn = true;
			let fullname = this.$root.userinfo.fullname;
			let file = this.newFile();
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

			axios.post('api/addAnnouncement', formData).then((response)=>{
				response.data['fullname'] = fullname;
				this.reset();
				
				this.announcement.unshift(response.data);
				this.textAreaResize();
				this.disableBtn = false;
				
			})
			.catch((err)=>{ console.log(err); });

			// ======================= OLD =============================
			
			// this.posts.dateposted = moment(new Date()).format('YYYY-MM-DD HH:mm:ss');
			// let params =  this.posts;
			// params['empID_'] = this.$root.userinfo.empID;
			// params['deptID_'] = this.$root.userinfo.deptID_;
			
			// /* this.announcement.unshift(params); */
			// axios.post('api/addAnnouncement', params).then((response)=>{
			// 	response.data['fullname'] = fullname;
			// 	this.posts.message = '';
			// 	this.announcement.unshift(response.data);
			// 	this.textAreaResize();
				
			// })
			// .catch((err)=>{ console.log(err); });
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
		editMsg(msg){
			this.postmsg = msg;
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
				     let reader  = new FileReader();
					 reader.onloadend = function () {
				        //   preview.src = reader.result;
				     }
				     reader.readAsDataURL(file); 
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
		togglePopUp(val){
			// var popup = document.getElementById(val);
  			// popup.classList.toggle("show");
		},
		removeClassBody(){
			$('body').removeClass('push-notification');
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
		}
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
			jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
			
		});
		
		axios.get('api/getAnnouncement').then((response)=>{
			this.announcement = response.data.data;
		})
		.catch((err)=>{ console.log(err); });
		


		
		/* this.$refs.search.focus();
		$('#textarea-post').focus(); */
		 
		// let offset = $('#btn-popupover-employee').offset();
		// // $('.popover.fadex')
		// $('#btn-popupover-employee').bind('click', function(e){
		// 	console.log(e.pageX, e.pageY);
		// 	$('.popover.fadex').css('left', 0);      // <<< use pageX and pageY
		// 	$('.popover.fadex').css('top',	e.pageY);
		// 	$('.popover.fadex').css('display','inline');     
		// 	$('.popover.fadex').css("position", "absolute");  
		// });
	
	
	

		/* // BS-POPUP OVER
		 $("[data-toggle='employee-popup']").popover({
			html: true,
			placement: "bottom",
			trigger: 'manual',
			content: function(){
				return `<div><input type="text" v-model="searchEmployee"></div>`;
			}
		});
	
		$("[data-toggle='department-popup']").popover({
			html: true,
			placement: "bottom",
			trigger: 'manual',
			content: function(){
				return $('#popupover-dept-content').html();
			}
		});
	
		$("#btn-popupover-employee").on('click', function(){
			$("[data-toggle='department-popup']").popover('hide');
			$("[data-toggle='employee-popup']").popover('show');
		});
		$("#btn-popupover-dept").on('click', function(){
			$("[data-toggle='employee-popup']").popover('hide');
			$("[data-toggle='department-popup']").popover('show');
		});


		$('html').on('click', (e) =>{

		


			if (typeof $(e.target).data('original-title') == 'undefined' &&
				!$(e.target).parents().is('.popover.in')) {
				$('[data-original-title]').popover('hide');
			}
			
		}); */


    }
}
</script>