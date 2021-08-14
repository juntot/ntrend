<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- <link rel="icon" href="./public/images/HRIS.ico" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <title>NORTH TRENDS</title> -->
	<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="{{URL::asset('resources/assets/css/loader.css')}}"> -->
	<!-- <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css"> -->
  	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css"> -->


	<link rel="stylesheet" href="{{URL::asset('resources/assets/css/fa-all.css')}}">
    <link rel="stylesheet" href="{{URL::asset('resources/assets/css/loader.css')}}">
	<!-- <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css"> -->
  	<link rel="stylesheet" href="{{URL::asset('resources/assets/css/material.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('resources/assets/css/dataTables.material.min.css')}}">


	<link rel="stylesheet" href="{{URL::asset('resources/assets/css/bootstrap.min.css')}}">

  	<link rel="stylesheet" href="{{URL::asset('resources/assets/css/mdbadmin.css')}}">
    <link rel="stylesheet" href="./public/css/app.css">
	<!--[if lt IE 9]>
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.6/xlsx.full.min.js')}}"></script>
	<![endif]-->
</head>
<body>
    <div id="loader">
		<div class="loader">
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
		</div>
	</div>
	<div id="root" v-cloak>
		<header class="nt-main-header">
			<div class="container">
				<div class="dflex d-align-center">
					<div class="comp-logo col-md-3 col-lg-3 col-sm-3">
						<!-- <a href="{{URL::to('/')}}"><img src="./public/images/comp_logo.jpg" alt="compay logo"/></a> -->
						<!-- <a href="{{URL::to('/')}}"><img src="{{asset('public/storage/images/comp_logo.png')}}" alt="compay logo"/></a> -->
							<a href="{{URL::to('/')}}"><img src="{{asset('public/storage/images/WB-LOGO.png')}}" alt="compay logo"/></a>
					</div>
					<div class="col-md-9 col-lg-9 col-sm-9 text-right notifs">
							<!-- post notification -->
							<v-popover offset="3" style="display: inline;">
								<span class="notif-icon-container" @click="updateNotifCountPost('push-notification')">
									<span class="notif-counter" v-if="postNotifs.count_pending>0">@{{postNotifs.count_pending}}</span>
									<i class="fas fa-bell"></i>
								</span>
									<template slot="popover">
										<div class="notification-slot">
												<div class="popover-title text-center">
													Post Notification
												</div>
												<div class="popover-content">
													<ul class="sidebarpanel notif-list">
														<li v-for="(notif, key) in postNotifs" :key="key" v-if="!isNaN(key)">
															<blockquote >@{{notif.notif_msg}}<span v-if="notif.notif_msg.length == 134">...<span/></blockquote>
															<br>
															<div class="text-right">
																<span style="color:#F97000">@{{notif.notifBy}}</span>
																<br>
																<i>---- @{{notif.dateadded | moment}}</i>
															</div>
														</li>
													</ul>

												</div>
										</div>
									</template>
							</v-popover>

							<!-- form notification -->
							<v-popover offset="3" style="display: inline;">
								<span class="notif-icon-container" @click="updateNotifCountForm('push-notification')">
									<span class="notif-counter" v-if="formNotifs.count_pending>0">@{{formNotifs.count_pending}}</span>
									<i class="far fa-file-alt"></i>
								</span>
									<template slot="popover">

										<div class="notification-slot" >
												<div class="popover-title text-center">
													Form Notification
												</div>
												<div class="popover-content">
												<!-- <div style="overflow-y: scroll; max-height: 200px;">			 -->
														<ul class="sidebarpanel notif-list">
															<li v-for="(notif, key) in formNotifs" :key="key" v-if="!isNaN(key)">
																<!-- @{{notif.notif_msg}} -->
																<span v-html="notif.notif_msg"> </span><br>
																<i>---- @{{notif.dateadded | moment}}</i>
															</li>
														</ul>
													<!-- </div> -->
												</div>
										</div>
									</template>
							</v-popover>

					</div>
				</div>
				<!-- <div class="comp-logo col-md-3 col-lg-3 col-sm-3"> -->
					<!-- <a href="{{URL::to('/')}}"><img src="./public/images/comp_logo.jpg" alt="compay logo"/></a> -->
					<!-- <a href=""><img src="logo.png" alt="compay logo"/></a> -->
				<!-- </div> -->
				<!-- <div class="col-md-9 col-lg-9 col-sm-9 text-right"> -->

					<!-- <a href=""><img src="logo.png" alt="compay logo"/></a> -->
				<!-- </div> -->
			</div>
		</header>

		<article class="container">
			<section class="col-md-3 col-sm-4">
				<div class="content overflow-auto relative-pos">
					<div class="text-center">
						<div class="profile-header">
									<div class="half-banner">
									</div>
									<div class="avatar-container" v-show="true">
										<!-- <img id="emp-avatar" src="https://www.w3schools.com/howto/img_avatar.png" alt="Avatar">	 -->
										<a data-toggle="modal" data-target="#profileModal">
											<img id="avatar-md" :src="userinfo.avatar? 'storage/app/'+userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar">
										</a>
									</div>
						</div>
						<div class="col-lg-12" style="padding-top: 15px;">
							@{{userinfo.fullname}}
						</div>
						<div><a href="{{ route('logout') }}">Logout</a></div>
					</div>
					<div class="col-md-12 even-padding">
						<hr>
						<div class="col-md-6 text-center">
							<div class="numeric-leaves">
								<i class="fas fa-plane-departure"></i>
								@{{(leaveCredits.VL || userinfo.VL) | leaveCreditFilter}}
							</div>
							<div>
									VL CREDITS
							</div>
						</div>
						<div class="col-md-6 text-center">
							<div class="numeric-leaves">
								<i class="fas fa-sad-tear"></i>
								@{{(leaveCredits.SL || userinfo.SL) | leaveCreditFilter}}
							</div>
							<div>
								SL CREDITS
							</div>
						</div>
						<div class="clearfix"></div>
						<hr>
						<div class="col-md-6 text-center">
							<div class="numeric-leaves">
								<i class="fas fa-birthday-cake"></i>
								@{{(leaveCredits.BL || userinfo.BL) | leaveCreditFilter}}
							</div>
							<div>
									Birthday Leaves
							</div>
						</div>
						<div class="col-md-6 text-center">
							<div class="numeric-leaves">
								<i class="fas fa-info-circle"></i>
								@{{(leaveCredits.DL || userinfo.DL) | leaveCreditFilter}}
							</div>
							<div>
								Discretionary Leaves
							</div>
						</div>
						<div class="clearfix"></div>
						<hr>
						<div>
							<p>ID No: @{{userinfo.empID}}</p>
							<p>Date Hired: @{{userinfo.dhired}}</p>
							<p>Employee Status: </p>
							<p>Job Title: @{{userinfo.posname}}</p>
							<p>Department: @{{userinfo.deptname}}</p>
							<p>Email: <a :href="'mailto:'+userinfo.email" v-if="userinfo.email">@{{userinfo.email}}</a></p>
						</div>
						<hr>
						<div>
							<p>SSS: @{{userinfo.SSS}}</p>
							<p>TIN: @{{userinfo.TIN}}</p>
							<p>HDMF: @{{userinfo.HDMF}}</p>
						</div>
						<hr>
						<div>
								<div class="panel-group" id="accordion">
									<!-- EMPLOYEE -->
									<div class="panel panel-default" v-if="userinfo.addEmp">
										<div class="panel-heading">
											<h4 class="panel-title">
											<!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapseemp">MANAGE EMPLOYEE</a> -->
												<router-link to="/manage-users" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">Manage Employee</router-link>
											</h4>
										</div>
									</div>
									<!-- DEPARTMENT -->
									<div class="panel panel-default" v-if="userinfo.addDept">
										<div class="panel-heading">
											<h4 class="panel-title">
											<!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapseemp">MANAGE EMPLOYEE</a> -->
												<router-link to="/manage-department" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">Manage Department</router-link>
											</h4>
										</div>
									</div>
									<!-- POSITION -->
									<div class="panel panel-default" v-if="userinfo.addPos">
										<div class="panel-heading">
											<h4 class="panel-title">
											<!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapseemp">MANAGE EMPLOYEE</a> -->
												<router-link to="/manage-position" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">Manage Position</router-link>
											</h4>
										</div>
									</div>
									<!-- BRANCH -->
									<div class="panel panel-default" v-if="userinfo.addBranch">
										<div class="panel-heading">
											<h4 class="panel-title">
											<!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapseemp">MANAGE EMPLOYEE</a> -->
												<router-link to="/manage-branch" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">Manage Branch</router-link>
											</h4>
										</div>
									</div>
									<!-- FORMS -->
									<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">FORMS</a>
										</h4>
									</div>
									<div id="collapse1" class="panel-collapse collapse inx">
										<div class="panel-body">
											<ul class="sidebarpanel">
												<li v-for="item in forms">
													<router-link :to="(item.navname).replace(/\s+/g, '-').toLowerCase()" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">
														@{{item.navname}}
													</router-link>
												</li>
											</ul>
										</div>
									</div>
									</div>
									<!-- FORM APPROVAL -->
									<div class="panel panel-default" v-if="$root.formnavapproval.length > 0">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapse2">MANAGE FORM REQUEST</a>
										</h4>
									</div>
									<div id="collapse2" class="panel-collapse collapse">
										<div class="panel-body">
											<ul class="sidebarpanel">
												<li v-for="item in formnavapproval">
													<router-link :to="'approval-'+(item.navname).replace(/\s+/g, '-').toLowerCase()" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">
														@{{item.navname}}
													</router-link>
												</li>
											</ul>
										</div>
									</div>
									</div>
									<!-- REPORTS -->
									<div class="panel panel-default" v-if="hasReports">
									<div class="panel-heading">
										<h4 class="panel-title">
										<!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">REPORTS</a> -->
										<router-link to="/reports" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">REPORTS</router-link>
										</h4>
									</div>
									<div id="collapse3" class="panel-collapse collapse">
										<div class="panel-body">
											<!-- <ul class="sidebarpanel">
												<li v-for="item in formnavapproval">
													<router-link :to="'approval-'+(item.navname).replace(/\s+/g, '-').toLowerCase()" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">
														@{{item.navname}}
													</router-link>
												</li>
											</ul> -->
										</div>
									</div>
									</div>
									<!-- POLICY -->
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapse4">POLICY</a>
											</h4>
										</div>
										<div id="collapse4" class="panel-collapse collapse">
											<div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
											sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
											quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
											<!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">VIDEOS</a> -->
											<router-link to="/videos" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">VIDEOS</router-link>
											</h4>
										</div>
										<div id="collapse5" class="panel-collapse collapse">
											<div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
											sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
											quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
											<!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">PAYSLIP</a> -->
											<router-link to="/payslip" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">PAYSLIP</router-link>
											</h4>
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
											 <!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapse7">DIRECTORY</a> -->
											 <router-link to="/directory" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">DIRECTORY</router-link>
											</h4>
										</div>
										<!-- <div id="collapse7" class="panel-collapse collapse">
											<div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
											sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
											quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
										</div> -->
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<!-- <a data-toggle="collapse" data-parent="#accordion" href="#collapse8">COMPANY PROFILE</a> -->
												<router-link to="/company-profile" onClick="window.scrollTo(0, 0)" class="accordion-menu collapseall">COMPANY PROFILE</router-link>
											</h4>
										</div>
										<!-- <div id="collapse8" class="panel-collapse collapse">
											<div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
											sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
											quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
										</div> -->
									</div>
								</div>
						</div>
					</div>
				</div>

				<!-- Modal -->
					<div id="profileModal" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg">

						<!-- Modal content-->
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Edit Proflie</h4>
						</div>
						<div class="modal-body">
							<!-- <p>Some text in the modal.</p> -->
							<div>
								<div class="dflex d-align-center">
									<div>
										<img id="user-avatar-profile-md" :src="userinfo.avatar? 'storage/app/'+userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar">
									</div>
									<div class="post-textarea width-100">
										<div class="col-md-4">
											<div class="mdb-form-field">
												<div class="form-field__control">
													<input type="text" class="form-field__input" v-model="userinfo.empID" name="empID" readonly="true" >
													<label class="form-field__label">ID No.</label>
													<div class="form-field__bar"></div>
												</div>
												<span class="errors"></span>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-4">
											<div class="mdb-form-field">
												<div class="form-field__control">
													<input type="text" class="form-field__input" v-model="userinfo.fname" name="fname" readonly="true">
													<label class="form-field__label">First Name</label>
													<div class="form-field__bar"></div>
												</div>
												<span class="errors"></span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="mdb-form-field">
												<div class="form-field__control">
													<input type="text" class="form-field__input" v-model="userinfo.mname" name="mname" readonly="true">
													<label class="form-field__label">Middle Name</label>
													<div class="form-field__bar"></div>
												</div>
												<span class="errors"></span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="mdb-form-field">
												<div class="form-field__control">
													<input type="text" class="form-field__input" v-model="userinfo.lname" name="lname" readonly="true">
													<label class="form-field__label">Last Name</label>
													<div class="form-field__bar"></div>
												</div>
												<span class="errors"></span>
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>

							</div>
							<hr/>
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#basic-info">Basic-Info</a></li>
								<li><a data-toggle="tab" href="#gov-id">Gov-ID</a></li>
								<li><a data-toggle="tab" href="#comp-profile">Comp-Profile</a></li>
								<li><a data-toggle="tab" href="#security">Security</a></li>
							</ul>

							<div class="tab-content">
								<div id="basic-info" class="tab-pane fade in active">
									<h3 class="form-title"><span class="dblUnderlined"></span></h3>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.gender" name="gender" readonly="true">
												<label class="form-field__label">Gender</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.mobile" name="contactnum" readonly="true">
												<label class="form-field__label">Contact number</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.email" name="email" readonly="true">
												<label class="form-field__label">Email Address</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
								</div>
								<div id="gov-id" class="tab-pane fade">
									<h3 class="form-title"><span class="dblUnderlined"></span></h3>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.SSS" name="SSS" readonly="true">
												<label class="form-field__label">SSS</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.TIN" name="TIN" readonly="true">
												<label class="form-field__label">TIN</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.HDMF" name="HDMF" readonly="true">
												<label class="form-field__label">HDMF</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
								</div>
								<div id="comp-profile" class="tab-pane fade">
									<h3 class="form-title"><span class="dblUnderlined"></span></h3>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.deptname" name="deptname" readonly="true">
												<label class="form-field__label">Department Name</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.posname" name="position" readonly="true">
												<label class="form-field__label">Position</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.branchname" name="branch" readonly="true">
												<label class="form-field__label">Branch Name</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="userinfo.dhired" name="branch" readonly="true">
												<label class="form-field__label">Date Hired</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors"></span>
										</div>
									</div>
								</div>
								<div id="security" class="tab-pane fade">
									<h3 class="form-title"><span class="dblUnderlined">Change Password</span></h3>
									<div>
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="editprofile.oldpass" v-validate="'required'" name="oldpass" >
												<label class="form-field__label">Old Password</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors">@{{ errors.first('oldpass') }}</span>
										</div>
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="editprofile.password" v-validate="'required'" name="password" ref="password" >
												<label class="form-field__label">New Pass</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors">@{{ errors.first('password') }}</span>
										</div>
										<div class="mdb-form-field">
											<div class="form-field__control">
												<input type="text" class="form-field__input" v-model="editprofile.password_confirm" v-validate="'required|confirmed:password'" name="password_confirmation" data-vv-as="password">
												<label class="form-field__label">New Pass</label>
												<div class="form-field__bar"></div>
											</div>
											<span class="errors">@{{ errors.first('password_confirmation') }}</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="modal-footer">
							<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Save</button> -->
							<input type="submit" value="Save" :disabled="!isFormValid" class="btn btn-primary" @click.prevent="changePassword">
						</div>
						</div>
					</div>
					<!-- end modal -->

			</section>
			<section id="content" class="col-md-9 col-lg-9 col-sm-8 content nopadding relative-pos margin-top-20">
				<router-view></router-view>
			</section>

		</article>
    </div>


	<script src="{{URL::asset('resources/assets/js/jquery/jquery3.3.1.min.js')}}"></script>
	<!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script> -->
	<script src="{{URL::asset('resources/assets/js/datatable/jquery.dataTables.js')}}"></script>

	<!-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.material.min.js"></script> -->
	<script src="{{URL::asset('resources/assets/js/datatable/dataTables.material.min.js')}}"></script>


	<script src="{{URL::asset('resources/assets/js/vuejs-datepicker/vuejs-datepicker1.5.3.min.js')}}"></script>
	<!-- <script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script> -->
	<script src="{{URL::asset('resources/assets/js/vmask/v-mask.min.js')}}"></script>

	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.1/xlsx.full.min.js"></script> -->
	<script src="{{URL::asset('resources/assets/js/xlsx/xlsx.full.min.js')}}"></script>

	<!-- <script src="{{URL::asset('resources/assets/js/xlsx/xlsx.full.min.js')}}"></script> -->

	<script src="{{URL::asset('resources/assets/js/veevalidate/vee-validatev2.1.3.min.js')}}"></script>
	<script src="{{URL::asset('resources/assets/js/moment/moment2.22.2.min.js')}}"></script>
	<script src="{{URL::asset('resources/assets/js/axios/axios.min.js')}}"></script>


	<script src="{{URL::asset('resources/assets/js/bootstrap/bootstrap.min.js')}}"></script>
	<script src="{{URL::asset('resources/assets/js/BSmultiSelect/bootstrap-multiselect.min.js')}}"></script>
	<!-- <script src="https://unpkg.com/popper.js"></script>
	<script src="https://unpkg.com/v-tooltip@2.0.2"></script> -->
	<script src="./public/js/public-app.js"></script>

<script>

    $(function() {
        $('#loader').hide();
    });

</script>
</body>
</html>