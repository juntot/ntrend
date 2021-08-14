<template>
    <div>
        
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
								    <textarea id="textarea-post" class="autoExpand" v-model="posts.message" name="message" data-autoresize rows="1" cols="500" data-min-rows='1' :placeholder="placeholder" autofocus></textarea>
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
										<button class="btn btn-info round"
											id="btn-popupover-employee"
											role="button"
											
											title="TAG EMPLOYEE"
											data-toggle="employee-popup"
										>tag employee</button>
										
										<div id="popupover-employee-content" class="hidden">
											scrollTop
										</div>
										<!-- end employee tag -->

										<!-- dept tags -->
										<button class="btn btn-info round" 
											id="btn-popupover-dept"
											role="button" 
											
											title="TAG DEPARTMENT"
											data-toggle="department-popup"
										>tag department</button>
										
										<div id="popupover-dept-content" class="hidden">
											<b>Example popover</b> - title
										</div>
										<!-- end dept tag -->
									</div>
									<div class="tag-row">
										<input type="submit" value="post" class="btn btn-primary" @click.prevent="addPost" v-show="posts.message" style="width: 80px;">
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
                            <textarea name="" id="textarea-post"  rows="10" v-model="postmsg"></textarea>
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
			searchEmployee: '',
			announcement: [],
			postmsg: '',
			page: 1,
			attachmentNames: '',
			type: '',
        };
	},
	methods:{
		addPost(){
			let fullname = this.$root.userinfo.fullname;
			let file = this.newFile();
			let formData = new FormData();
			formData.append( 'attachment', file );
			formData.append( 'empID_',  this.$root.userinfo.empID);
			formData.append( 'deptID_',  this.$root.userinfo.deptID_);
			formData.append( 'message',  this.posts.message);
			formData.append( 'type',  this.type);

			axios.post('api/addAnnouncement', formData).then((response)=>{
				response.data['fullname'] = fullname;
				this.reset();
				
				this.announcement.unshift(response.data);
				this.textAreaResize();
				console.log(response.data)
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
				this.type = type;
			    const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'video/mp4'];
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
		reset(){
			this.type = '';
			this.attachmentNames = '';
			this.posts.message = '';
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



		// BS-POPUP OVER
		 $("[data-toggle='employee-popup']").popover({
			html: true,
			placement: "bottom",
			trigger: 'manual',
			content: function(){
				return $('#popupover-employee-content').html();
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

			/* if($(e.target).data('original-title') == 'TAG EMPLOYEE'){
				$("[data-toggle='employee-popup']").popover('show');
			}

			if($(e.target).data('original-title') == 'TAG DEPARTMENT'){
				$("[data-toggle='department-popup']").popover('show');
			} */


			if (typeof $(e.target).data('original-title') == 'undefined' &&
				!$(e.target).parents().is('.popover.in')) {
				$('[data-original-title]').popover('hide');
			}
			
		});


    }
}
</script>