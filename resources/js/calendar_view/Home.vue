<template>
    <div>
        <v-app id="inspire">
            <div class="fill-height">
                <v-col>
                    <v-sheet height="64">
                    <v-toolbar
                        flat
                    >

                    <v-dialog
                    v-model="dialog"
                    width="500"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                    outlined
                                    class="mr-4"
                                    color="grey darken-2"
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                Add New
                                </v-btn>
                        </template>

                        <v-card>
                            <v-card-title class="headline blue darken-3 white--text">
                                Add New Schedule
                            </v-card-title>

                            <v-card-text>
                                <br><br>
                            <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="usr">Event Name:</label>
                                        <input type="text" class="form-control" id="usr" v-model="event_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="pwd">Event Start Date:</label>
                                        <Datepicker :value="event_start_date" @selected="dateStart" wrapper-class="mdb-form-field" input-class="form-control form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                        <!-- <label slot="afterDateInput" class="form-field__label">Date Undertime</label> -->
                                        <!-- <div slot="afterDateInput" class="form-field__bar"></div> -->
                                        <!-- <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span> -->
                                        </Datepicker>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="pwd">Event End Date:</label>
                                        <Datepicker :value="event_end_date" @selected="dateEnd" wrapper-class="mdb-form-field" input-class="form-control form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
                                        <!-- <label slot="afterDateInput" class="form-field__label">Date Undertime</label> -->
                                        <!-- <div slot="afterDateInput" class="form-field__bar"></div> -->
                                        <!-- <span slot="afterDateInput" class="errors">{{ errors.first('mname') }}</span> -->
                                        </Datepicker>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="pwd">Start Time:</label>
                                        <input type="time" v-model="event_timeStart" class="form-control" id="pwd">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="pwd">End Time:</label>
                                        <input type="time" v-model="event_timeEnd" class="form-control" id="pwd">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="comment">Comment:</label>
                                        <textarea v-model="details" class="form-control" rows="5" id="comment"></textarea>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </v-card-text>


                            <v-divider></v-divider>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                color="primary"
                                text
                                @click="setCalendar"
                                >
                                Submit
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!-- end modal -->
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
                    {{ $refs.calendar.title }}
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
                        <span>{{ typeToLabel[type] }}</span>
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
                        <v-list-item @click="type = '3day'">
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
                    <!-- <v-calendar
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
                    ></v-calendar> -->
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
            </div>
        </v-app>
    </div>
</template>

<script>
export default {

    data(){
        return{
            dialog: false,
            event_name: '',
            event_start_date:  moment(new Date()).format('YYYY-MM-DD'),
            event_end_date:  moment(new Date()).format('YYYY-MM-DD'),
            event_timeStart: '',
            event_timeEnd: '',
            details: '',

            focus: '',
            type: 'month',
            typeToLabel: {
                month: 'Month',
                week: 'Week',
                day: 'Day',
                '3day': '3 Days',
            },
            selectedEvent: {},
            selectedElement: null,
            selectedOpen: false,
            events: [],
            colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
            names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
        };
	},
	methods:{
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
            this.dialog = false;
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
            this.$refs.calendar.prev()
        },
        next () {
            this.$refs.calendar.next()
        },
        showEvent ({ nativeEvent, event }) {
            const open = () => {
            this.selectedEvent = event
            this.selectedElement = nativeEvent.target
            setTimeout(() => {
                this.selectedOpen = true
            }, 10)
            }

            if (this.selectedOpen) {
            this.selectedOpen = false
            setTimeout(open, 10)
            } else {
            open()
            }

            nativeEvent.stopPropagation()
        },
        updateRange ({ start, end }) {
            const events = []

            // const min = new Date(`${start.date}T00:00:00`)
            // const max = new Date(`${end.date}T23:59:59`)
            // const days = (max.getTime() - min.getTime()) / 86400000
            // const eventCount = this.rnd(days, days + 20)

            // for (let i = 0; i < eventCount; i++) {
            //     const allDay = this.rnd(0, 3) === 0
            //     const firstTimestamp = this.rnd(min.getTime(), max.getTime())
            //     const first = new Date(firstTimestamp - (firstTimestamp % 900000))
            //     const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
            //     const second = new Date(first.getTime() + secondTimestamp)

            //     events.push({
            //         name: this.names[this.rnd(0, this.names.length - 1)],
            //         start: first,
            //         end: second,
            //         color: this.colors[this.rnd(0, this.colors.length - 1)],
            //         timed: !allDay,
            //     });
            // }

            // const allDay = this.rnd(0, 3) === 0;
            // const firstTimestamp = this.rnd(min.getTime(), max.getTime())
            // const first = new Date(firstTimestamp - (firstTimestamp % 900000));
            // const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000;
            // const second = new Date(first.getTime() + secondTimestamp)
            // events.push({
            //     name: 'awsssxxxxxxxxxxx',
            //     start: first,
            //     end: second,
            //     color: 'indigo',
            //     // timed: !allDay,
            // });
            // events.push({
            //     name: 'awsssxxxxxxxxxxx',
            //     start: first,
            //     end: second,
            //     color: 'indigo',
            //     timed: true,
            // });
            // events.push({
            //     name: 'awsssxxxxxxxxxxx',
            //     start: first,
            //     end: second,
            //     color: 'indigo',
            //     timed: true,
            // });
            // events.push({
            //     name: 'awsssxxxxxxxxxxx',
            //     start: first,
            //     end: second,
            //     color: 'indigo',
            //     timed: true,
            // });
            this.events = events
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

	},
	destroyed () {

	},
    mounted(){


    }
}
</script>