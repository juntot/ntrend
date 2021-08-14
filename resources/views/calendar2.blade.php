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

		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
		<!-- <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet"> -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
	</head>
<body>
<div id="app">
<v-app id="inspire">
            <v-row class="fill-height">
            <v-col>
                <v-sheet height="64">
                <v-toolbar
                    flat
                >
                    <v-btn
                    outlined
                    class="mr-4"
                    color="grey darken-2"
                    @click="setToday"
                    >
                    Today
                    </v-btn>
                    <v-btn
                    fab
                    text
                    small
                    color="grey darken-2"
                    @click="prev"
                    >
                    <v-icon small>
                        mdi-chevron-left
                    </v-icon>
                    </v-btn>
                    <v-btn
                    fab
                    text
                    small
                    color="grey darken-2"
                    @click="next"
                    >
                    <v-icon small>
                        mdi-chevron-right
                    </v-icon>
                    </v-btn>
                    <v-toolbar-title v-if="$refs.calendar">
                    @{{ $refs.calendar.title }}
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-menu
                    bottom
                    right
                    >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                        outlined
                        color="grey darken-2"
                        v-bind="attrs"
                        v-on="on"
                        >
                        <span>@{{ typeToLabel[type] }}</span>
                        <v-icon right>
                            mdi-menu-down
                        </v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="type = 'day'">
                        <v-list-item-title>Day</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = 'week'">
                        <v-list-item-title>Week</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = 'month'">
                        <v-list-item-title>Month</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = '4day'">
                        <v-list-item-title>4 days</v-list-item-title>
                        </v-list-item>
                    </v-list>
                    </v-menu>
                </v-toolbar>
                </v-sheet>
                <v-sheet height="600">
                <v-calendar
                    ref="calendar"
                    v-model="focus"
                    color="primary"
                    :events="events"
                    :event-color="getEventColor"
                    :type="type"
                    @click:event="showEvent"
                    @click:more="viewDay"
                    @click:date="viewDay"
                    @change="updateRange"
                ></v-calendar>
                <v-menu
                    v-model="selectedOpen"
                    :close-on-content-click="false"
                    :activator="selectedElement"
                    offset-x
                >
                    <v-card
                    color="grey lighten-4"
                    min-width="350px"
                    flat
                    >
                    <v-toolbar
                        :color="selectedEvent.color"
                        dark
                    >
                        <v-btn icon>
                        <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon>
                        <v-icon>mdi-heart</v-icon>
                        </v-btn>
                        <v-btn icon>
                        <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text>
                        <span v-html="selectedEvent.details"></span>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                        text
                        color="secondary"
                        @click="selectedOpen = false"
                        >
                        Cancel
                        </v-btn>
                    </v-card-actions>
                    </v-card>
                </v-menu>
                </v-sheet>
            </v-col>
            </v-row>
        </v-app>
</div>

  <!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script> -->
			<!-- <script src="{{URL::asset('resources/assets/js/bootstrap/bootstrap.min.js')}}"></script> -->
      <script src="{{URL::asset('resources/assets/js/moment/moment2.22.2.min.js')}}"></script>
      <!-- <script src="https://momentjs.com/downloads/moment.js"></script> -->
      <script src="{{URL::asset('resources/assets/js/moment/moment-with-locales.js')}}"></script>
      <script src="{{URL::asset('resources/assets/js/moment/moment-timezone-with-data-10-year-range.js')}}"></script>
            <script src="./public/js/calendar-app.js"></script>




<script>
// $(function(){
// 	$('#loader').hide();

// });

</script>
	</body>
</html>