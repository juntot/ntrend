<!DOCTYPE html>
<html class="login">
	<head>
		<meta charset="utf-8"/>
		<link rel="icon" href="./public/images/HRIS.ico" />
		<meta name="description" content="Free Web tutorials">
  		<meta name="keywords" content="HTML,CSS,XML,JavaScript">
  		<meta name="author" content="John Doe">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
  		<title>NORTH TRENDS</title>
  		<link rel="stylesheet" href="{{URL::asset('resources/assets/css/bootstrap.min.css')}}">
  		<link rel="stylesheet" href="{{URL::asset('resources/assets/css/mdb.css')}}">
		<link rel="stylesheet" href="./public/css/app.css">

		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<![endif]-->

	</head>
	<body id="login">
		<div id="root">

			<article class="container login-container">
				<div class="login-form">
					<div class="login-header">
						<!-- <img src="{{URL::asset('public/images/comp_logo.jpg')}}" alt="compay logo" style="width: 100%; height: auto;"/> -->
						<img src="{{asset('storage/app/public/images/comp_logo.png')}}" alt="compay logo" style="width: 100%; height: auto;"/>
					</div>
					<div class="login-body">
						<form method="post" action="authlogin">

							<div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="text" v-validate="'required'" value="{{ old('empid') }}" class="form-field__input" name="empid">
                                    <label class="form-field__label">Email / ID no.</label>
                                    <div class="form-field__bar"></div>
                                </div>
                                <span class="errors absolute">@{{ errors.first('empid') }}</span>
							</div>
							<div class="mdb-form-field form-group-limitx">
                                <div class="form-field__control">
                                    <input type="password" v-validate="'required'" value="{{ old('password') }}" class="form-field__input" name="password" @keydown="err_msg = false" autocomplete="false">
                                    <label class="form-field__label">Password</label>
                                    <div class="form-field__bar"></div>
                                </div>
                                <span class="errors absolute">@{{ errors.first('password') }}</span>
                            </div>
							{{ csrf_field() }}
							<div class="group-btn">
								<input type="submit" class="btn btn-primary fullwidth-mdb-btn" value="LOGIN" :disabled="!isFormValid">
								<!-- EERRORS -->
								@if($errors->any())
									<span v-show="err_msg" class="errors text-center">{{$errors->first()}}</span>
								@endif
							</div>
						</form>
					</div>
					<div class="login-footer">
						<!-- &copy;NorthTrends Capital INC. - @{{ yyyy }} -->
					</div>

				</div>
			</article>

			<footer>
			</footer>

		</div>

			<script src="{{URL::asset('resources/assets/js/jquery/jquery3.3.1.min.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/bootstrap/bootstrap.min.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/vue/vue.js')}}"></script>
			<script src="{{URL::asset('resources/assets/js/veevalidate/vee-validatev2.1.3.min.js')}}"></script>



<script>
	localStorage.clear();
	Vue.use(VeeValidate,{local: 'en'});

	 new Vue({
	 	el: '#root',
	 	data:{
	 		yyyy: new Date().getFullYear(),
	 		err_msg: true,
	 	},
	 	computed:{
			isFormValid(){
				return !Object.keys(this.fields).some(key => this.fields[key].invalid);
			}
		}
	});

	// MDB inputs
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
	)


</script>
	</body>
</html>