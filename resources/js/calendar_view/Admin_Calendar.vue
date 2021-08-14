<template>
    <div>
        <v-app id="inspire">
            <div class="fill-heightx">
                <v-col>
                    <div style="position: relative; z-index: 999; display: block;">
                    <v-sheet height="64">

                    <v-toolbar
                        flat
                    >
                    <!-- <button data-backdrop="false" data-toggle="modal" data-target="#myModal3" class="btn btn-primary" style="background: transparent; color: black; border-color: gray;"> -->
                        <button data-toggle="modal" data-target="#myModal" class="btn btn-default">
                        Add New
                    </button>

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
                    {{ $refs.calendar.title }}
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
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Dropdown Example
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
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
                        </ul>
                    </div>
                </v-toolbar>
                </v-sheet>
                </div>
                <v-sheet :height="type=='month'? 600: 'auto'">
                    <v-calendar
                    ref="calendar"
                    interval-count="0"

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
                            <!-- <v-btn icon>
                            <v-icon>mdi-pencil</v-icon>
                            </v-btn> -->
                            <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                            <!-- <v-spacer></v-spacer>
                            <v-btn icon>
                            <v-icon>mdi-heart</v-icon>
                            </v-btn>
                            <v-btn icon>
                            <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn> -->
                        </v-toolbar>
                        <v-card-text>
                            <span v-html="selectedEvent.details"></span>
                        </v-card-text>
                        <!-- <v-card-actions>
                            <v-btn
                            text
                            color="secondary"
                            @click="selectedOpen = false"
                            >
                            Cancel
                            </v-btn>
                        </v-card-actions> -->
                        </v-card>
                    </v-menu>
                    </v-sheet>
                </v-col>
            </div>
        </v-app>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <div class="form-group col-md-6">
            <label >Event Name:</label>
            <input type="text" class="form-control"  v-model="event_name">
        </div>
        <div class="form-group col-md-6">
            <label for="pwd">Event Date:</label>
            <Datepicker :value="event_start_date" @selected="dateStart" wrapper-class="mdb-form-field" input-class="form-control form-field__inputx datePicker" :typeable="false" :format="'MM/dd/yyyy'">
            </Datepicker>
        </div>
        <!-- <div class="form-group col-md-6">
            <label for="pwd">Event End Date:</label>
            <Datepicker :value="event_end_date" @selected="dateEnd" wrapper-class="mdb-form-field" input-class="form-control form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
            </Datepicker>
        </div> -->

        <!-- <div class="form-group col-md-6">
            <label for="pwd">Start Time:</label>
            <input type="time" v-model="event_timeStart" class="form-control" id="pwd">
        </div>
        <div class="form-group col-md-6">
            <label for="pwd">End Time:</label>
            <input type="time" v-model="event_timeEnd" class="form-control" id="pwd">
        </div> -->
        <div class="form-group col-md-12">
            <label for="comment">Comment:</label>
            <textarea v-model="details" class="form-control" rows="5" id="comment"></textarea>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer with-padding">
        <button type="button" class="btn btn btn-primary" @click.prevent="setCalendar">Save</button>
      </div>
    </div>

  </div>
</div>


<!-- details -->
<!-- Modal -->
<div id="modalCalendarDetails" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <div class="form-group col-md-6">
            <label>Event Name:</label>
            <input type="text" class="form-control" v-model="event_name">
        </div>
        <div class="form-group col-md-6">
            <label for="pwd">Event Date:</label>
            <Datepicker :value="event_start_date" @selected="dateStart" wrapper-class="mdb-form-field" input-class="form-control form-field__inputx datePicker" :typeable="false" :format="'MM/dd/yyyy'">
            </Datepicker>
        </div>
        <!-- <div class="form-group col-md-6">
            <label for="pwd">Event End Date:</label>
            <Datepicker :value="event_end_date" @selected="dateEnd" wrapper-class="mdb-form-field" input-class="form-control form-field__input datePicker" :typeable="false" :format="'MM/dd/yyyy'">
            </Datepicker>
        </div> -->

        <!-- <div class="form-group col-md-6">
            <label for="pwd">Start Time:</label>
            <input type="time" v-model="event_timeStart" class="form-control" id="pwd">
        </div>
        <div class="form-group col-md-6">
            <label for="pwd">End Time:</label>
            <input type="time" v-model="event_timeEnd" class="form-control" id="pwd">
        </div> -->
        <div class="form-group col-md-12">
            <label for="comment">Comment:</label>
            <textarea v-model="details" class="form-control" rows="5" id="comment"></textarea>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-footer with-padding">
          <button type="button" class="btn btn btn-danger" @click.prevent="delEvent">Delete</button>
        <button type="button" class="btn btn btn-primary" @click.prevent="updateEvent">Update</button>
      </div>
    </div>

  </div>
</div>
    </div>
</template>
<style scoped>
    @import "https://fonts.googleapis.com/css?family=Roboto:100,300,400";
    /* @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap'); */
</style>
<script>
export default {

    data(){
        return{
            dialog: false,
            id: 0,
            event_name: '',
            event_start_date:  moment(new Date()).format('YYYY-MM-DD'),
            // event_end_date:  moment(new Date()).format('YYYY-MM-DD'),
            event_timeStart: '',
            event_timeEnd: '',
            details: '',


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
            // colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
            // names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
        };
	},
	methods:{
        // closeCalendarModal(){
        //     $('#myModal').modal('hide');
        // },
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
                    start : moment(this.event_start_date).format('YYYY-MM-DD'), //;'Thu Jan 14 2021 17:45:00 GMT+0800 (China Standard Time)',
                    // end :  new Date(`${this.event_start_date} 23:59:59`), //'Sat Jan 16 2021 08:15:00 GMT+0800 (China Standard Time)',
                    color : 'red',
                    // timed: true,
                };
            axios.post('api/add_event', events)
            .then(res=>{
                events['start'] = new Date(`${events.start}`);
                this.events.push(events);
            })
            .catch(er=>
                alert('Error occurred. Please try again....')
            );

            $('#myModal').modal('hide');
        },
        // addEvent(data){
        //     // console.log(data);
        //     this.events.push(data);
        //     // this.dialog = false;
        // },
        updateEvent(){
            // console.log(this.events.findIndex(el => el.id == this.id));

            let arr_events = this.events;
            arr_events.splice(arr_events.findIndex(el => el.id == this.id), 1);

            // this.events.splice(0, 1);
            let params = {
                id: this.id,
                name : this.event_name,
                details : this.details,
                start : moment(this.event_start_date).format('YYYY-MM-DD'),
                // start: new Date(),
                color : 'red',
            }



            /*let arr_events =  this.events.filter(res=>res.id != this.id);

            let params = {
                id: this.id,
                name : 'this.event_name',
                details : this.details,
                // start : moment(this.event_start_date).format('YYYY-MM-DD'),
                start: new Date(),
                color : 'primary',
            }
            arr_events.push(params);
            // this.events = arr_events;

            */

            // arr_events[0] = params;
            // this.events = arr_events;
            // this.events = params;
            axios.post('api/update_event', params)
            .then(res=>{
                params['start'] = new Date(`${params.start}`);
                arr_events.push(params);
                this.events = arr_events;
            })
            .catch(err=>alert('Error updating.. please try again'));

            $('#modalCalendarDetails').modal('hide');
        },
        delEvent(){
            let new_events = this.events.filter(res=>res.id != this.id);
            axios.post('api/del_event', {id: this.id})
            .then(res=>{
                this.events = new_events;
                $('#modalCalendarDetails').modal('hide');
            })
            .catch(er=>console.log(er));
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
            // const open = () => {
            // this.selectedEvent = event
            // this.selectedElement = nativeEvent.target
            // setTimeout(() => {
            //     this.selectedOpen = true
            // }, 10)
            // }

            // if (this.selectedOpen) {
            // this.selectedOpen = false
            // setTimeout(open, 10)
            // } else {
            // open()
            // }
            this.event_name = event.name;
            this.event_start_date = event.start;
            this.details = event.details;
            this.id = event.id;

            $('#modalCalendarDetails').modal('show');

            nativeEvent.stopPropagation()
        },
        updateRange ({ start, end }) {

            // const events = []

            // const min = new Date(`${start.date}T00:00:00`)
            // const max = new Date(`${end.date}T23:59:59`)
            // const days = (max.getTime() - min.getTime()) / 86400000
            // const eventCount = this.rnd(days, days + 20)

            // for (let i = 0; i < this.events; i++) {
                // const allDay = this.rnd(0, 3) === 0
                // const firstTimestamp = this.rnd(min.getTime(), max.getTime())
                // const first = new Date(firstTimestamp - (firstTimestamp % 900000))
                // const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
                // const second = new Date(first.getTime() + secondTimestamp)

                // events.push(this.events[i]);
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

        axios.get('api/get_events').
        then((res)=>{
            let events_calendar = res.data.map(event=>{
                event['start'] = new Date(event.start);
                return event;
            });
            this.events = events_calendar;
            // this.events = events_calendar;
            // this.updateRange();

        });
	},
	beforeDestroy(){
        // bus.$off('addEvent', this.test)
	},
	destroyed () {

	},
    mounted(){

        // EVENT BUS
        // bus.$on('addEvent', this.addEvent);

    }
}
</script>