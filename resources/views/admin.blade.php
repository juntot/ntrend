<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<!-- <link rel="icon" href="./public/images/HRIS.ico" /> -->
		<meta name="description" content="Free Web tutorials">
  		<meta name="keywords" content="HTML,CSS,XML,JavaScript">
  		<meta name="author" content="John Doe">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
        <title>NORTH TRENDS</title>
		<link rel="icon" href="./public/images/HRIS.ico" />

		<!-- include libraries(jQuery, bootstrap) -->
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

<!-- include summernote css/js -->


		<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="{{URL::asset('resources/assets/css/loader.css')}}"> -->
	    <!-- <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css"> -->
  		<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css"> -->

		<!-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet"> -->
		<link href="{{URL::asset('resources/assets/css/materialdesignicons.min.css')}}" rel="stylesheet">
		<!-- <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet"> -->
		<link rel="stylesheet" href="{{URL::to('/public/css/vuetify.css')}}">


		<link rel="stylesheet" href="{{URL::asset('resources/assets/css/fa-all.css')}}">
		<link rel="stylesheet" href="{{URL::asset('resources/assets/css/loader.css')}}">
		<!-- <link rel="stylesheet" href="http://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css"> -->
		<link rel="stylesheet" href="{{URL::asset('resources/assets/css/material.min.css')}}">
		<link rel="stylesheet" href="{{URL::asset('resources/assets/css/dataTables.material.min.css')}}">


        <link rel="stylesheet" href="{{URL::asset('resources/assets/css/bootstrap.min.css')}}">

  		<link rel="stylesheet" href="{{URL::asset('resources/assets/css/mdbadmin.css')}}">

		<link rel="stylesheet" href="{{URL::asset('resources/assets/css/image-zoom.css')}}">

        <link rel="stylesheet" href="./public/css/admin.css">
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')}}"></script>
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

		<div id="root" class="container-fluid nopadding" v-cloak>
			<div class="dflex flexroot block-mobile">
						<div class="show-mobile mobile-nav-container">
							<div class="admin-mobile-nav col-md-12 dflex-normal">
								<div class="company-logo">
									<a href="{{URL::to('/')}}">
										<img src="{{asset('storage/app/public/images/comp_logo.png')}}" alt="compay logo" />
									</a>
								</div>
								<button type="button" class="navbar-toggle burger-mobile">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
						</div>
						<div class="clearfix"></div>
						<header id="main-header" class="hide-mobile">
								<div class="light-gray show-mobile" style="height: 0;
								position: absolute;
								right: 10px;
								top: 10px;">
									<span class="close-mobile-nav">&times;</span>
								</div>
								<div class="avatar-container light-gray dflex d-align-center">
									<div>
										<a data-toggle="modal" data-target="#profileModal">
											<img id="avatar-sm" :src="userinfo.avatar? 'storage/app/'+userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar">
										</a>
									</div>
									<div >
										<div>Welcome, </div>
										<div>@{{fullname}}</div>
									</div>
								</div>
								<!-- ACCORDION -->
								<div class="panel-group" id="accordion">
										<div class="panel panel-default">
											<div class="panel-heading active">
												<h4 class="panel-title">
													<div class="dflex d-align-center">
															<router-link to="/" @click.native="hideMobileNav" class="accordion-menu collapseall">Dashboard</router-link>
													    <span class="caret"></span>
													</div>
												</h4>
											</div>
											<div id="dashboard" class="panel-collapse collapse">
												<div class="panel-body" v-show="false"></div>
											</div>
										</div>
										<!-- <div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
                                                    <div class="dflex d-align-center">
														<!-- <a href="#posts" data-toggle="collapse" data-parent="#accordion" class="accordion-menu" >Posts</a> --
														<router-link @click.native="hideMobileNav" to="/post" class="accordion-menu collapseall">Posts</router-link>
                                                        <span></span>
                                                    </div>
												</h4>
											</div>
											<div id="posts" class="panel-collapse collapse">
												<div class="panel-body" v-show="false"></div>
											</div>
										</div> -->
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
														<div class="dflex d-align-center">
																<a  data-toggle="collapse" data-parent="#accordion" href="#users" class="accordion-menu">Manage Users</a>
																<span></span>
														</div>
												</h4>
											</div>
											<div id="users" class="panel-collapse collapse">
												<div class="panel-body">
														<ul class="submenu">
																<li><router-link @click.native="hideMobileNav" to="/manage-users">Users</router-link></li>
																<li><router-link @click.native="hideMobileNav" to="/job-position">Job Position</router-link></li>
																<li><router-link @click.native="hideMobileNav" to="/dept">Department</router-link></li>
																<li><router-link @click.native="hideMobileNav" to="/branch">Branch</router-link></li>
																<li><router-link @click.native="hideMobileNav" to="/company">Company</router-link></li>
																<li><router-link @click.native="hideMobileNav" to="/form-approver-byuser">Approver per users</router-link></li>
																<li><router-link @click.native="hideMobileNav" to="/resigned-users">Resigned Employees</router-link></li>
																<!-- <li><router-link @click.native="hideMobileNav" to="/user-role">User roles</router-link></li> -->
														</ul>
												</div>
											</div>
										</div>
										<!-- <div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
                                                    <div class="dflex d-align-center">
														<router-link @click.native="hideMobileNav" to="/job-position" class="accordion-menu collapseall">Job Position</router-link>
													    <span></span>
                                                    </div>
												</h4>
											</div>
											<div id="jobposition" class="panel-collapse collapse">
												<div class="panel-body" v-show="false"></div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
                                                    <div class="dflex d-align-center">
														<router-link @click.native="hideMobileNav" to="/dept" class="accordion-menu collapseall">Department</router-link>
                                                        <span></span>
                                                    </div>
												</h4>
											</div>
											<div id="department" class="panel-collapse collapse">
												<div class="panel-body" v-show="false"></div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
                                                    <div class="dflex d-align-center">
														!-- <a data-toggle="collapse" data-parent="#accordion" href="#branch" class="accordion-menu">Branch</a> --
														<router-link @click.native="hideMobileNav" to="/branch" class="accordion-menu collapseall">Branch</router-link>
                                                        <span></span>
                                                    </div>
												</h4>
											</div>
											<div class="panel-heading">
												<h4 class="panel-title">
                                                    <div class="dflex d-align-center">
														!-- <a data-toggle="collapse" data-parent="#accordion" href="#branch" class="accordion-menu">Branch</a> --
														<router-link @click.native="hideMobileNav" to="/company" class="accordion-menu collapseall">Company</router-link>
                                                        <span></span>
                                                    </div>
												</h4>
											</div>
											<div id="branch" class="panel-collapse collapse">
												<div class="panel-body" v-show="false"></div>
											</div>
										</div> -->
										<div class="panel panel-default">
										<div class="panel-heading">
												<h4 class="panel-title">
														<div class="dflex d-align-center">
																<a data-toggle="collapse" data-parent="#accordion" href="#collapse3" class="accordion-menu">Authorizations</a>
																<span></span>
														</div>
												</h4>
											</div>
											<div id="collapse3" class="panel-collapse collapse">
												<div class="panel-body">
														<ul class="submenu">
															<li><router-link @click.native="hideMobileNav" to="/user-role">User roles</router-link></li>
															<li><router-link @click.native="hideMobileNav" to="/manage-reports">Reports</router-link></li>
															<!-- <li><router-link @click.native="hideMobileNav" to="/form-group">Form Groups</router-link></li> -->
															<!-- <li><router-link @click.native="hideMobileNav" to="/witness-supplementary">Witness-Supplementary</router-link></li> -->
															<li><a>e-Forms</a>
																<ul class="submenu">
																	<li><router-link @click.native="hideMobileNav" to="/form-group">Form Groups</router-link></li>
																	<li><router-link @click.native="hideMobileNav" to="/manage-form">Department Access</router-link></li>
																	<li><router-link @click.native="hideMobileNav" to="/manage-user-form">User Access</router-link></li>
																	<li><router-link @click.native="hideMobileNav" to="/form-approver">Form Approver Accesss</router-link></li>
																	<li><router-link @click.native="hideMobileNav" to="/form-approver-bydept">Approver Per Department</router-link></li>
																	<li><router-link @click.native="hideMobileNav" to="/form-approver-byuser">Approver Per Users</router-link></li>
																	
																	<li><router-link @click.native="hideMobileNav" to="/terms-conditions">Terms and Conditions</router-link></li>
																</ul>
															</li>
															<li><router-link @click.native="hideMobileNav" to="/manage-policy">Policy</router-link></a>
																<!-- <ul>
																	<li><a>Manage Policy By department</a></li>
																	<li><a>Manage Policy By Users</a></li>
																</ul> -->
															</li>
															<li><router-link @click.native="hideMobileNav" to="/videos">Videos</router-link></li>
															<li><router-link @click.native="hideMobileNav" to="/payslip">Payslip</router-link></li>
															<li><router-link @click.native="hideMobileNav" to="/directory">Directory</router-link></li>
															<!-- <li><router-link @click.native="hideMobileNav" to="/manage-comp-profile">Company Profile</router-link></li> -->
														</ul>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
                                                    <div class="dflex d-align-center">
                                                        <!-- <a data-toggle="collapse" data-parent="#accordion" href="#settings" class="accordion-menu">Settings</a> -->
														<router-link @click.native="hideMobileNav" to="/admin-calendar" class="accordion-menu collapseall">Calendar</router-link>
                                                        <span></span>
                                                    </div>
												</h4>
											</div>
											<!-- <div id="settings" class="panel-collapse collapse">
												<div class="panel-body" v-show="false"></div>
											</div> -->
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
                                                    <div class="dflex d-align-center">
                                                        <!-- <a data-toggle="collapse" data-parent="#accordion" href="#settings" class="accordion-menu">Settings</a> -->
														<router-link @click.native="hideMobileNav" to="/settings" class="accordion-menu collapseall">Maintenance</router-link>
                                                        <span></span>
                                                    </div>
												</h4>
											</div>
											<div id="settings" class="panel-collapse collapse">
												<div class="panel-body" v-show="false"></div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
                                                    <div class="dflex d-align-center">
                                                        <!-- <a data-toggle="collapse" data-parent="#accordion" href="#settings" class="accordion-menu">Settings</a> -->
														<!-- <router-link @click.native="hideMobileNav" to="/settings" class="accordion-menu collapseall">Maintenance</router-link> -->
														<router-link @click.native="hideMobileNav" to="/manage-comp-profile">Organizational Chart</router-link>
                                                        <span></span>
                                                    </div>
												</h4>
											</div>
											<div id="settings" class="panel-collapse collapse">
												<div class="panel-body" v-show="false"></div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-heading">
												<h4 class="panel-title">
													<a href="{{ route('logout') }}" class="accordion-menu">Logout</a>
												</h4>
											</div>
										</div>
									</div>
								<!-- END ACCORDION -->
						</header>
						<article>
                                <router-view></router-view>

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
													<!-- <img id="user-avatar-profile-md" src="public/images/priemer_jacket.jpg" alt="Avatar"> -->
													<img id="avatar-lg" :src="userinfo.avatar? 'storage/app/'+userinfo.avatar : 'public/images/priemer_jacket.jpg'" alt="Avatar">
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
															<input type="text" class="form-field__input" v-model="userinfo.compname" name="compname" readonly="true">
															<label class="form-field__label">Company</label>
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
									<div class="modal-footer modal-reset-footer">
										<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Save</button> -->
										<input type="submit" value="Save" :disabled="!isFormValid" class="btn btn-primary" @click.prevent="changePassword">
									</div>
									</div>
								</div>
								<!-- end modal -->
						</article>
			</div>
			<footer>
			</footer>


		</div> <!--ROOT-->
            <script src="{{URL::asset('resources/assets/js/jquery/jquery3.3.1.min.js')}}"></script>

			<link href="{{ URL::asset('resources/assets/js/summernote/summernote.min.css') }}" rel="stylesheet">
			<script src="{{ URL::asset('resources/assets/js/summernote/summernote.min.js') }}"></script>


			<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.js"></script> -->
			<script src="{{URL::asset('resources/assets/js/chart/chart.js')}}"></script>

			<!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script> -->
			<script src="{{URL::asset('resources/assets/js/datatable/jquery.dataTables.js')}}"></script>

			<!-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.material.min.js"></script> -->
			<script src="{{URL::asset('resources/assets/js/datatable/dataTables.material.min.js')}}"></script>

			<script src="{{URL::asset('resources/assets/js/vuejs-datepicker/vuejs-datepicker1.5.3.min.js')}}"></script>
			<!-- <script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script> -->
			<script src="{{URL::asset('resources/assets/js/vmask/v-mask.min.js')}}"></script>

			<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.1/xlsx.full.min.js"></script> -->
			<script src="{{URL::asset('resources/assets/js/xlsx/xlsx.full.min.js')}}"></script>



			<script src="{{URL::asset('resources/assets/js/veevalidate/vee-validatev2.1.3.min.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/moment/moment2.22.2.min.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/axios/axios.min.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/zoom/image-zoom.min.js')}}"></script>

            <script src="./public/js/app.js"></script>



			<script src="{{URL::asset('resources/assets/js/bootstrap/bootstrap.min.js')}}"></script>
			<!-- <script src="{{URL::asset('resources/assets/js/axios/axios.min.js')}}"></script> -->
			<!-- <script src="{{URL::asset('resources/assets/js/vue/vue.js')}}"></script> -->
			<!-- <script src="{{URL::asset('resources/assets/js/v-components/vc-datatable.js')}}"></script> -->


				<!-- hr -->
			<!-- <script src="{{URL::asset('resources/assets/js/v-components/vc-datatable.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-leaveform.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-undertimeform.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-saldisc.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-saldisc.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-ccard.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-urgentcheck.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-attendsup.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-attendoffset.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-incident.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-cloan.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-CLX.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-travel.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-supaccredit.js')}}"></script> -->
				<!--  IT -->
			<!-- <script src="{{URL::asset('resources/assets/js/v-components/vc-workrequest.js')}}"></script> -->
				<!--  accounting -->
			<!-- <script src="{{URL::asset('resources/assets/js/v-components/vc-fadvance.js')}}"></script> -->
				<!--  purchasing -->
			<!-- <script src="{{URL::asset('resources/assets/js/v-components/vc-canvas.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-miis.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-prf.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/v-components/vc-prs.js')}}"></script> -->

			<!--
			<script src="{{URL::asset('resources/assets/js/v-components/vc-datatable.js')}}"></script>-->

			<!-- <script src="{{URL::asset('resources/assets/js/vuejs-datepicker/vuejs-datepicker1.5.3.min.js')}}"></script>
			<script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script>
			<script src="{{URL::asset('resources/assets/js/veevalidate/vee-validatev2.1.3.min.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/moment/moment2.22.2.min.js')}}"></script> -->


			<!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
      		<script src="https://cdn.datatables.net/1.10.19/js/dataTables.material.min.js"></script> -->




<script>



$(function() {

// ACCORDION
    $('.collapseall').click(function(){
        $('.panel-collapse.in').collapse('hide');
	});

	// Clicked accordion
	$('.accordion-menu').click(function(){
			$('.panel-heading').removeClass('active');
			$('.panel-heading span').removeClass('caret');
			$(this).closest('.panel-heading').addClass('active');
			$(this).next().addClass('caret');
	});

	$('.panel-heading').removeClass('active');
	$('.panel-heading span').removeClass('caret');

	if($('.router-link-exact-active') && $('.router-link-exact-active').has('span'))
	{
		$('.router-link-exact-active').parentsUntil("div.panel-heading").parent().addClass('active');
		$('.router-link-exact-active').next().addClass('caret');
	}

	if($('.router-link-exact-active') && $('.router-link-exact-active').parent().parent().is('ul.submenu') ||
	   $('.router-link-exact-active') && $('.router-link-exact-active').parent().parent().is('ul.submenux')){

		$('.router-link-exact-active').parentsUntil("div.panel-collapse").parent().prev().addClass('active');
		$('.router-link-exact-active').parentsUntil("div.panel-collapse").parent().prev().find('span').addClass('caret');
		$('.router-link-exact-active').parentsUntil("div.panel-collapse").parent().collapse('show');
	}


	$('#loader').hide();
		$('.burger-mobile').click(function(){
			$('header#main-header').show();
			$('body').addClass('backdrop');
		});
	$('.close-mobile-nav').click(function(){
		$('header#main-header').hide();
		$('body').removeClass('backdrop');
	});
// let vheight = $( window ).height();
// $('#root .flexroot').css('min-height', vheight+'px');
});


</script>
	</body>
</html>