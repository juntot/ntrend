<style>
/* .v-calendar-weekly__week {
    display: flex;
    flex: 1;
    height: unset;
    min-height: 129px;
} */
/* commented above and use check the v-sheet value to extend the height month*/
</style>

<template>
    <div>
        <v-app id="inspire">
            <div class="fill-heightx calendar-container">
                <v-col>
                    <div style="position: relative; z-index: 2; display: block;">
                    <v-sheet height="64">

                    <v-toolbar flat>
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

                        <v-toolbar-title v-if="$refs.calendar">
                        &nbsp;&nbsp;
                        {{ $refs.calendar.title }}
                        &nbsp;&nbsp;
                        </v-toolbar-title>

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

                        <v-spacer></v-spacer>
                        <div class="dropdown calendar-dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">View
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <!-- <v-list-item @click="type = 'day'">
                                    <v-list-item-title>Day</v-list-item-title>
                                </v-list-item> -->
                                <v-list-item @click="type = 'week'">
                                    <v-list-item-title>Week</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'month'">
                                    <v-list-item-title>Month</v-list-item-title>
                                </v-list-item>
                                <!-- <v-list-item @click="type = '4day'">
                                    <v-list-item-title>4 days</v-list-item-title>
                                </v-list-item> -->
                            </ul>
                        </div>
                    </v-toolbar>
                    </v-sheet>
                    </div>
                    <v-sheet :height="type=='month'? calendarHeight || 600: 'auto'">
                        <v-calendar
                        ref="calendar"
                        interval-count="0"
                        :now="setFocus"
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
                        <!-- event-more-text="{0} events hidden" -->
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
                        <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <span v-html="selectedEvent.details"></span>
                        </v-card-text>
                        </v-card>
                        </v-menu>
                    </v-sheet>
                </v-col>
            </div>
        </v-app>
    </div>
</template>
<style scoped>
    @import "https://fonts.googleapis.com/css?family=Roboto:100,300,400";
</style>
<script>
export default {
    props: ['getEventDetails', 'getNext', 'getPrev', 'calendarHeight'],
    data(){
        return{
            // dialog: false,
            
            event_name: '',
            event_start_date:  moment(new Date()).format('YYYY-MM-DD'),
            event_end_date:  moment(new Date()).format('YYYY-MM-DD'),
            event_timeStart: '',
            event_timeEnd: '',
            details: '',

            setFocus: '2020-01-01',
            focus: '',
            type: 'month',
            typeToLabel: {
                month: 'Month',
                week: 'Week',
                day: 'Day',
                '4day': '4 Days',
            },
            selectedEvent: {},
            selectedElement: null,
            selectedOpen: false,
            events: [],
        };
	},
	methods:{
        closeCalendarModal(){
            $('#myModal3').modal('hide');
        },
        dateStart(val){
            this.event_start_date = moment(val).format('YYYY-MM-DD');
        },
        dateEnd(val){
            this.event_end_date = moment(val).format('YYYY-MM-DD');
        },
        setCalendar(){
            let events = {
                    name : this.event_name,
                    details : this.details,
                    start : new Date(`${this.event_start_date} ${this.event_timeStart}`), //;'Thu Jan 14 2021 17:45:00 GMT+0800 (China Standard Time)',
                    end :  new Date(`${this.event_end_date} ${this.event_timeEnd}`), //'Sat Jan 16 2021 08:15:00 GMT+0800 (China Standard Time)',
                    color : 'indigo',
                    timed: true,
                };
            this.events.push(events);
            // this.dialog = false;
        },
        initEvent(data){
            // this.setFocus = moment(new Date(data.start)).format('YYYY-MM-DD');
            // this.focus = moment(new Date(data.start)).format('YYYY-MM-DD');
            this.events = data;

        },
        addEvent(data){
            // console.log(data);
            this.events.push(data);
            // this.dialog = false;
        },
        viewDay ({ date }) {
            this.focus = date
            this.type = 'day'
        },
        getEventColor (event) {
            return event.color
        },
        setToday () {
            this.focus = ''
        },
        prev () {
            this.$refs.calendar.prev();
            const startOfMonth = moment(this.focus).format('YYYY-MM-01');
            const endOfMonth   = moment(this.focus).format('YYYY-MM-DD');
            this.getPrev({start: startOfMonth, end: endOfMonth});
        },
        next () {
            this.$refs.calendar.next();
            const startOfMonth = moment(this.focus).format('YYYY-MM-01');
            const endOfMonth   = moment(this.focus).endOf('month').format('YYYY-MM-DD');
            this.getNext({start: startOfMonth, end: endOfMonth});
        },
        showEvent ({ nativeEvent, event }) {
            this.getEventDetails(event);
            nativeEvent.stopPropagation()
        },
        updateRange ({ start, end }) {
            const events = []
            this.events = this.events;
        },
        rnd (a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
	},
	filters:{
		moment: function (date) {
    		return moment(date).fromNow();
  		}
	},
	computed:{

	},
	created () {

	},
	beforeDestroy(){
        bus.$off('plotEvent', this.initEvent)
	},
	destroyed () {

	},
    mounted(){
        // EVENT BUS
        bus.$on('plotEvent', this.initEvent);

    }
}
</script>