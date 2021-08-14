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
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
		<!-- <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet"> -->
		<link rel="stylesheet" href="./public/css/calendar.app.min.css">
	</head>
<body>
<div id="app">
	<router-view></router-view>
</div>
<script src="{{URL::asset('resources/assets/js/moment/moment2.22.2.min.js')}}"></script>
	<!-- <script src="https://momentjs.com/downloads/moment.js"></script> -->
<script src="{{URL::asset('resources/assets/js/moment/moment-with-locales.js')}}"></script>
<script src="{{URL::asset('resources/assets/js/moment/moment-timezone-with-data-10-year-range.js')}}"></script>
<script src="{{URL::asset('resources/assets/js/vuejs-datepicker/vuejs-datepicker1.5.3.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="./public/js/calendar-app.js"></script>

<script>

</script>
	</body>
</html>