<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title text-center">
			{{formtitle}}
		</div>
    <div class="col-lg-12 margin-15 padding-15">
        
        <VuetifyCalendar :getEventDetails="showEventDetails" calendarHeight="890" :getNext="getNextRec" :getPrev="getPrevRec"/>
        <span class="text-primary" style="white-space: pre-line">
          <br>
          Notes: {{ my_calendar_notes }}
        </span>
        <!-- Modal -->
            <div id="user-dtr-records" class="modal fade" role="dialog" ref="vuemodal">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                          <table class="table table-hover">
                            <thead>
                              <tr class="text-primary">
                                <th>DATE</th>
                                <th>TIME</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(log, index) in record.logs" :key="index">
                                <td>{{log.datelog | formatMonth }}</td>
                                <td>{{log.datelog | formatTime }}</td>
                              </tr>
                              <tr class="text-primary">
                                <TH>Total Hours Rendered: </TH>
                                <TH>{{ record._totalhrs | formatTotalTime }}</TH>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                </div>
            </div> 
          <!---END MODAL-->
    </div>
    <div class="clearfix"></div>
    </div>
</template>
<script>
import VuetifyCalendar from '../../calendar_view/VuetifyCalendar.vue';
export default {
    props:['userId'],
    components:{
        VuetifyCalendar
    },
    data(){
        return{
            formtitle: 'My Calendar',
            record: {
              logs: [],
              _totalhrs: 0,
            },
            loader: false,
            holidayEvents: [],
            my_calendar_notes: '',
        }
    },
    watch: { 
      	userId: function(newVal, oldVal) { // watch it
          this.pullRecords(
            moment(new Date()).format('YYYY-MM-01'), 
            moment(new Date()).endOf('month').format('YYYY-MM-DD'),
            newVal
          );
        }
    },
    filters:{
        formatMonth(value)
        {
            return moment(value).format('MMM-DD-YYYY');
        },
        formatTime(value)
        {
            return moment(value).format('hh:mm a');
        },
        formatTotalTime(value){
            return (value+'').slice(0, -3);
        }
    },
    methods:{
       showEventDetails(detail){
        return;

        if(typeof detail.logs == 'string')
        detail.logs = JSON.parse(detail.logs) || [];
        
        this.record = detail;
        $("#user-dtr-records").modal("show");
       },

       getPrevRec(date){
        this.pullRecords(date.start, date.end);
       },

       getNextRec(date){
        this.pullRecords(date.start, date.end);
       },

      pullRecords(from, to, userid_){
        if(!userid_)
        userid_ = this.userId;

         axios.post('api/myapproveleave', {
          start: from,
          end: to,
         }).
          then((res)=>{
              //   let events_calendar = res.data.map(event=>{
              //     event['start'] = event.start;
              //     return event;
              //   });
              // this.holidayEvents = events_calendar;
              this.holidayEvents = res.data;
          });
                
        axios.post('https://ams.northtrend.com/dtr-user-report',{
                start: from,
                end: to,
                user: userid_
        })
        .then(res=>{
          // console.log(res.data);
          if(res.data){
            let events = res.data.reduce((acc, curr) => {
              
              // in
              if(curr._in && moment(curr._in).isValid() && curr._in != '0000-00-00 00:00:00')
              acc.push({
                name: moment(curr._in).format('hh:mm a'),
                color: curr.color,
                start: curr.start,
                end: curr.end,
                logs: curr.logs
              });

              

              // in2
              if(curr._in2 && moment(curr._in2).isValid() && curr._in2 != '0000-00-00 00:00:00')
              acc.push({
                name: moment(curr._in2).format('hh:mm a'),
                color: curr.color,
                start: curr.start,
                end: curr.end,
                logs: curr.logs
              });

              // out
              if(curr._out && moment(curr._out).isValid() && curr._out != '0000-00-00 00:00:00')
              acc.push({
                name: moment(curr._out).format('hh:mm a'),
                color: curr.color,
                start: curr.start,
                end: curr.end,
                logs: curr.logs
              });
              
              // out2
              if(curr._out2 && moment(curr._out2).isValid() && curr._out2 != '0000-00-00 00:00:00')
              acc.push({
                name: moment(curr._out2).format('hh:mm a'),
                color: curr.color,
                start: curr.start,
                end: curr.end,
                logs: curr.logs
              });
              return acc;
            }, []);
            events = [...this.holidayEvents, ...events];
            bus.$emit('plotEvent', events);
          }
          
        })
        .catch(er=>console.log(er))
      },
      closeModal(){

      }


    },
    created(){

    },
    computed:{
        formatSlip(){
           
        }
    },
    mounted(){
        // this.forapprover = ((this.$route.path).slice(1)).toLowerCase().split('-')[];
        // this.formtitle = ((this.$router.name)).toUpperCase();
        
     
        axios.get('api/getcalendar-notes')
            .then(res=>{
                this.my_calendar_notes = res.data;
        })



        $('#myModal').on("hidden.bs.modal", this.closeModal);
        this.pullRecords(
          moment(new Date()).format('YYYY-MM-01'), 
          moment(new Date()).endOf('month').format('YYYY-MM-DD'),
        );
        

        
    }

}
</script>