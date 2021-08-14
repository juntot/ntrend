@extends('layout.main')
@section('main')

<!-- <div class="main-container" id="app"> 

<div class="grid-x grid-padding-x">
	<div class="large-2 medium-12 cell transparent-peach">
		<div class="content">
			<p class="h1">MENU</p>
			<ul class="menu">
				<li><a href="javascript:void(0)" class="active"><i class="fas fa-layer-group"></i> Appointment</a></li>
				<li><a href="javascript:void(0)"><i class="far fa-calendar-alt"></i> Weekly Sales Plan</a></li>
				<li><a href="javascript:void(0)"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
			</ul>
		</div>		
	</div> -->
	<div class="large-6 medium-12 cell solid-white">
		<div class="content tracker">
			<div class="grid-x grid-padding-x align-justify">
				<div class="column small-4 cell">
					<span class="date-header h2">@{{ dateHead }}</span>
				</div>

				<div class="column small-2 cell">
					<span><a href="#"><i class="fas fa-search"></i></a></span> 
					<span><a href="#"><i class="fas fa-th-large"></i></a></span>
				</div>
			</div>
			
			<!-- inner content track rec -->
			<hr class="divider">
			
			<div class="float data-time">
				<ul>
					
					<li v-for="(yyrec, index) in searchDate">

						<a href="#" data-toggle="modal" data-target="#myModal" @click="timeSched(index)" v-if="index!='06:00' && yyrec == null && !isBeforeDate && !isDisabledDate" >@{{ index }}</a>
						<a href="#" v-if="index=='06:00' || yyrec != null || isBeforeDate || isDisabledDate" :class="'disabledtime'" >@{{ index }}</a>
					</li>
				
				</ul>
			</div>
			<div class="float data-record">
				<div class="pastel">	
					
					<div v-for ="(yyrec, index) in searchDate" :class="yyrec !=null ? 'data data-prospect-container row'+yyrec.totalhrs:''" v-if="yyrec != 'disabled'">
						<a href="#" data-toggle="modal" data-target="#myModal2" @click="fetchRecEdit(index)" v-if="yyrec != null">
							<div v-if="yyrec != null">
								<div>	
									@{{ yyrec.name }}
								</div>
							</div>
						</a>

						<a href="#" data-toggle="modal" data-target="#myModal" @click="timeSched(index)" v-if="isBeforeDate == false && yyrec == null && !isDisabledDate">
							<hr>
						</a>

						<a href="#" v-if="isBeforeDate == true && yyrec == null || isDisabledDate" :class="'disabledtime'">
							<hr>
						</a>

					</div>
						
				</div>
			</div>
			
			
		</div>

		<div class="modal-footer dlrecord" v-show="isBeforeDate && hasRecords">
			 <input type="submit" class="button" value="Download" @click.prevent="createPDF">
		</div>
	</div>
	<div class="large-4 medium-12 cell transparent-purple">
		<div class="content calendar">
			<div id="appx">

			  <vuejs-datepicker :highlighted="highlightdates" :calendar-class="'vdp-datepicker__calendar_xl'" :inline="true" :disabled-dates="disabledDates" :value="date" @selected="selectDay" ></vuejs-datepicker>
			  <!-- <vuejs-datepicker :inline="true" :disabled-dates="disabledDates" :value="date" :highlighted="highlightdates"  @selected="selectDay" ></vuejs-datepicker> -->
			</div>
		</div>
	</div>
</div>





  <!-- BOOTSTRAP MODAL -->
  <div class="modal fade" id="myModal" role="dialog" ref="vuemodal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      	<!-- FORM -->
      	<form method="post" action="" @submit.prevent="saveSched">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h6 class="h5 cell modal-title">Set Prospect Schedule</h6>
          <p>@{{dateHead}}</p>
        </div>
        <div class="modal-body">

			<div class="grid-container">
			    <div class="grid-x grid-padding-x">

			      <div class="medium-12 cell">
			      		
 					<!-- search -->
				 	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-user-secret"></i>
				      </span>
				      <input id="searchp" type="text" class="input-group-field" placeholder="Search Prospect Name...." v-model="prospectname"  @keyup="showSearchResult" name="prosectname">
				      <!-- <input class="input-group-field" type="text" placeholder="contact_email"> -->
				    </div>
			      </div>

				<!-- search result -->
			      <div class="medium-12 cell" id="searchresult" >
			      		<div style="position: relative; ">
			      			<div style="position: absolute;" class="absearch">
				      			<div v-for="(prospect, index) in search" v-show="prospectname!=''">
				      				<a href="javascript:void(0)" @click="selectProspect(prospect, index)">@{{ prospect.name }}</a>
				      			</div>
				      		</div>
			      		</div>
			      </div>

				<!-- weeks -->
			      <div class="medium-6 cell">
			      	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="far fa-calendar-check"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="weekday" disabled>
				    </div> 
			      </div>
				<!-- time -->
			      <div class="medium-6 cell">
					<div class="input-group">
				      <span class="input-group-label">
			      		<i class="far fa-clock"></i>
				      </span>
				      <select class="input-group-field" name="" id="" v-model="totalhrs">
			          	<option  value="" v-for="(timesearch, timeindex) in timeS"  :value="timeindex+1" >@{{time}} - @{{timesearch}}</option>
			          </select>
				    </div>
				  </div>
				
				<!--  contact person-->
				  <div class="medium-6 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-user"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="contactp" name="" disabled>
				    </div>
				  </div>

				<!-- designation -->
				  <div class="medium-6 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-briefcase"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="designation" disabled>
				    </div>
				  </div>

				<!--  telnum-->
				  <div class="medium-6 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-phone-square"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="telno" disabled>
				    </div>
				  </div>

				<!-- cell -->
				  <div class="medium-6 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-mobile-alt"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="mobile" disabled>
				    </div>
				  </div>
				 <!--  contact_email-->
				  <div class="medium-12 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-envelope"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="contact_email" disabled>
				    </div>
				  </div>
				<!-- address -->
				  <div class="medium-12 cell">
					<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-home"></i>
				      </span>
				      <input type="text" class="input-group-field" v-model="address" disabled>
				    </div>
				  </div>
				</div>
			 </div>
			{{csrf_field()}}
        </div>
        <div class="modal-footer">
	           
			   <input type="submit" class="button" value="Save" :disabled="disableBtn">
			  
        </div>
        </form>
      </div>
      
    </div>
  </div>



<!-- BOOTSTRAP MODAL EDIT-->
  <div class="modal fade" id="myModal2" role="dialog" ref="vuemodalEdt">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      	<!-- FORM -->
      	<form method="post" action="">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h6 class="h5 cell modal-title">Edit Prospect Schedule</h6>
          <p>@{{dateHead}}</p>
        </div>
        <div class="modal-body">

			<div class="grid-container">
			    <div class="grid-x grid-padding-x">

			      <div class="medium-12 cell">
			      		
 					<!-- search -->
				 	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-user-secret"></i>
				      </span>
				      <input id="searchp" type="text" class="input-group-field" placeholder="Search Prospect Name...." v-model="prospectname"  @keyup="showSearchResult" name="prosectname" :disabled="isBeforeDate">
				      <!-- <input class="input-group-field" type="text" placeholder="contact_email"> -->
				    </div>
			      </div>

				<!-- search result -->
			      <div class="medium-12 cell" id="searchresultEdt" >
			      		<div style="position: relative; ">
			      			<div style="position: absolute;" class="absearch">
				      			<div v-for="(prospect, index) in search" v-show="prospectname!=''">
				      				<a href="javascript:void(0)" @click="selectProspect(prospect, index)">@{{ prospect.name }}</a>
				      			</div>
				      		</div>
			      		</div>
			      </div>

				<!-- weeks -->
			      <div class="medium-6 cell">
			      	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="far fa-calendar-check"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="weekday" disabled>
				    </div> 
			      </div>
				<!-- time -->
			      <div class="medium-6 cell">
					<div class="input-group">
				      <span class="input-group-label">
			      		<i class="far fa-clock"></i>
				      </span>
				      <select class="input-group-field" name="" id="" v-model="edittotalhrs" :disabled="isBeforeDate">
			          	<option v-for="(timesearch, timeindex) in timeSEdit"  :value="timeindex+1" >@{{time}} - @{{timesearch}}</option>
			          </select>

				    </div>
				  </div>
				
				<!--  contact person-->
				  <div class="medium-6 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-user"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="contactp" name="" disabled>
				    </div>
				  </div>

				<!-- designation -->
				  <div class="medium-6 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-briefcase"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="designation" disabled>
				    </div>
				  </div>

				<!--  telnum-->
				  <div class="medium-6 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-phone-square"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="telno" disabled>
				    </div>
				  </div>

				<!-- cell -->
				  <div class="medium-6 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-mobile-alt"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="mobile" disabled>
				    </div>
				  </div>
				 <!--  contact_email-->
				  <div class="medium-12 cell">
				  	<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-envelope"></i>
				      </span>
				      <input type="text" class="input-group-field" :value="contact_email" disabled>
				    </div>
				  </div>
				<!-- address -->
				  <div class="medium-12 cell">
					<div class="input-group">
				      <span class="input-group-label">
			      		<i class="fas fa-home"></i>
				      </span>
				      <input type="text" class="input-group-field" v-model="address" disabled>
				    </div>
				  </div>
				</div>
			 </div>
			{{csrf_field()}}
        </div>
        <div class="modal-footer">
	           <div v-show="!isBeforeDate">
				   <input type="submit" class="button" value="Update" @click.prevent="updateSched">
				   <input type="submit" class="button" value="Delete" @click.prevent="deleteSched">
			   </div>
        </div>
        </form>
      </div>
      
    </div>
  </div>


  <!-- END APP -->
</div>
@push('modal')
	 
@endpush




@endsection

@push('script')
	<script src="{{URL::asset('resources/app/jquery/jquery3.3.1.min.js')}}"></script>
	<script src="{{URL::asset('resources/app/foundation 6/foundation6.5.0.min.js')}}"></script>
	<script src="{{URL::asset('resources/app/bootstrap/js/bootstrap.min.js')}}"></script>
	<!-- <script src="{{URL::asset('resources/app/jsPDF/jsPDF1.4.1.js')}}"></script> -->
	<script src="{{URL::asset('resources/app/jsPDF/jsPDF1.4.1.min.js')}}"></script>
	<script src="{{URL::asset('resources/app/jsPDF/jspdf.autotable.v2.3.5.min.js')}}"></script>
	
	<script src="{{URL::asset('resources/app/axios/axios.min.js')}}"></script>
	<script src="{{URL::asset('resources/app/vue/vue.js')}}"></script>
	<script src="{{URL::asset('resources/app/vuejs-datepicker/vuejs-datepicker@1.5.3.min.js')}}"></script>
	<script>
	
		 Vue.component('test',{
		 	template: `
			<div>
				<div>
				format	
				</div>
				
			</div>
		 	`,

		 });

		new Vue({

		  el: '#app',
		  data:
		  {
			  	date: new Date(),
			  	disabledDates: { days: [0, 6] },
			  	dynamicdatehead: new Date(),
			  	highlightdates: {
			  		dates:[]
			  	},
			  	yearlyrecords: '',
			  	totalhrs: 1,
			  	edittotalhrs: 1,
			  	searchProspect: [],
			  	prospectname: '',
			  	time: '',
			  	contactp: '',
			  	address: '',
			  	telno: '',
			  	mobile: '',
			  	contact_email: '',
			  	searchDate: '',
			  	prospectId:'',
			  	designation: '',

			  	disableBtn: false,
			  	

		  },

		  components: 
		  {
		  		vuejsDatepicker
		  },

		  methods:
		  {
			  	

			  	selectDay(value){

			  		var $rows = $(".float.data-time, .float.data-record").addClass("data-animate");

				    setTimeout(function() {
				       $rows.removeClass("data-animate");
				    }, 1000);

				    

			  		if(this.dynamicdatehead.getMonth() != value.getMonth())
			  		{
			  			//console.log(value.getMonth());	

				    	axios.get('date/'+moment(value).format('YYYY-MM-DD'))
						  .then((response) => {
						    // handle success
						    this.yearlyrecords = response.data;

						    var self=this;
							const filtered = Object.keys(self.yearlyrecords)
							  .filter(key => moment(value).format('YYYY-MM-DD').includes(key))
							  .reduce((obj, key) => {
							    obj = self.yearlyrecords[key];
							    return obj;
							  }, {});
							this.searchDate = filtered;
						    

						  })
						  .catch(function (error) {
						    // handle error
						   
						  });
					}
					
					this.dynamicdatehead = value;

					
					//filter array

					var self=this;
					const filtered = Object.keys(self.yearlyrecords)
					  .filter(key => moment(this.dynamicdatehead).format('YYYY-MM-DD').includes(key))
					  .reduce((obj, key) => {
					    obj = self.yearlyrecords[key];
					    return obj;
					  }, {});
					this.searchDate = filtered;
			    },

			    
			    // DATE FORMATTER
			    customFormatter(date) {
			      return moment(date).format('MM/DD/YYYY');
			    },


			    //  SET SELECTED PROSPECT 
			   selectProspect(prospect, index){

			   		this.address = prospect.address;
			   		this.designation = prospect.designation;
			   		this.prospectname = prospect.name;
			   		this.contactp = prospect.contact_person;
					this.telno = prospect.contact_telno;
					this.mobile = prospect.contact_celno;
					this.contact_email = prospect.contact_email;
					this.prospectId = prospect.id_prosp;

			   		$('#searchresult').hide();
			   		$('#searchresultEdt').hide();
			   		
			   },


			   // SHOW SEARCH RESULT
			   showSearchResult(){
			   		$('#searchresult').show();
			   		$('#searchresultEdt').show();
			   		//$('#searchresultedt').hide();
			   		this.clearFields();
			   },


			   // ADD APPOINTMENT SCHEDULE
			   saveSched(){
			   	//saveAppt
					this.disableBtn = true;
					//var self = this;
					if(this.prospectId == '')
					{
						this.prospectname = '';
						$('#searchp').attr({'placeholder': 'No prospect selected', 'class': 'input-group-field shake'});
						this.disableBtn = false;
						return false; 
					}

					axios.post('saveAppt', {

						appointment: moment(this.dynamicdatehead).format('YYYY-MM-DD')+' '+this.time, 
						id_prost: this.prospectId,
						totalhrs: this.totalhrs,

					})
					  .then((response)=> { 

					  	for(var time1 in this.yearlyrecords)
					  	{
					  		if(time1 == moment(this.dynamicdatehead).format('YYYY-MM-DD'))
					  		{
					  			
					  			this.yearlyrecords[time1][this.time] = {
					  				name: this.prospectname, 
					  				totalhrs: this.totalhrs,
			   						address: this.address,
					  				contact_person: this.contactp,
									contact_telno: this.telno,
									mobile: this.mobile,
									contact_email: this.contact_email ,
									prospectID: this.prospectId, 
					  			};
					  			
					  			if(this.totalhrs > 1)
					  			{
					  				
					  				for( var totalhours = this.totalhrs-2; totalhours > -1; totalhours--)
					  				{
					  					var disableTime = this.timeS[totalhours];
					  					this.yearlyrecords[time1][disableTime] = 'disabled';
					  				}
					  			}	

					  			$("#myModal").modal("hide");
					  			break;
					  			
					  		}
					  		
					  	}

					  	// remove prospect form the prospect list
					  	var index = this.searchProspect.findIndex(x => x.id_prosp == this.prospectId);
					  	this.searchProspect.splice(index, 1);


					  })
					  .catch(function (error) {
					    console.log(error);
					  });


			   },

			   // DELETE APPOINTMENT SCHEDULE
			   deleteSched(){

			   		axios.get('delAppt/'+moment(this.dynamicdatehead).format('YYYY-MM-DD')+' '+this.time+':00')
					  .then((response)=> { 

				

					  	for(var time1 in this.yearlyrecords)
						 {
						  		if(time1 == moment(this.dynamicdatehead).format('YYYY-MM-DD'))
						  		{
						  			
						  			
						  			
						  			if(this.edittotalhrs > 1)
						  			{
						  				
						  				for( var totalhours = this.edittotalhrs-2; totalhours > -1; totalhours--)
						  				{
						  					var disableTime = this.timeSEdit[totalhours];
						  					this.yearlyrecords[time1][disableTime] = null;
						  					
						  				}
						  			}

						  			//push pospect to prospectrecords
					  				//this.searchProspect.push(this.yearlyrecords[moment(this.dynamicdatehead).format('YYYY-MM-DD')][this.time]);
					  				var findprospect = this.searchProspect.find(obj => obj.id_prosp == this.prospectId);
					  				if(!findprospect){
					  					this.searchProspect.push(this.yearlyrecords[time1][this.time]);
					  				}
					  				
						  			this.yearlyrecords[time1][this.time] = null;

						  			$("#myModal2").modal("hide");
						  			break;
						  			
						  		}
						 }
					  })
					  .catch(function (error) {
					    console.log(error);
					  });


			   },

			   // UPDATE APPOINTMENT SCHEDULE
			   updateSched(){

			   		// check if there's a selected prospect id
			   		if(this.prospectId == '')
					{
						this.prospectname = '';
						$('[id=searchp]:eq(1)').attr({'placeholder': 'No prospect selected', 'class': 'input-group-field shake'});
						return false; 
					}

					//check if all data is unchange
					if(this.prospectId == this.searchDate[this.time].prospectID && this.edittotalhrs == this.searchDate[this.time].totalhrs)
					{
						$("#myModal2").modal("hide");
						return;
					}

			   		axios.post('updateAppt',{
			   			appointment: moment(this.dynamicdatehead).format('YYYY-MM-DD')+' '+this.time, 
			   			id_prost: this.prospectId,
			   			totalhrs: this.edittotalhrs,
			   		})
			   		.then((response) => {

			   			for(var time1 in this.yearlyrecords)
					  	{
					  		if(time1 == moment(this.dynamicdatehead).format('YYYY-MM-DD'))
					  		{

					  			
					  			// set disabled time within timerange
					  			if(this.edittotalhrs > this.yearlyrecords[time1][this.time].totalhrs)
					  			{
					  				for( var totalhours = this.edittotalhrs-2; totalhours > -1; totalhours--)
					  				{
					  					var disableTime = this.timeSEdit[totalhours];
					  					this.yearlyrecords[time1][disableTime] = 'disabled';
					  				}
					  			}

					  			// set null previous time range until edited time
					  			if(this.edittotalhrs < this.yearlyrecords[time1][this.time].totalhrs)
					  			{
					  				
					  				for( var totalhours = this.yearlyrecords[time1][this.time].totalhrs-1; totalhours >= this.edittotalhrs; totalhours--)
					  				{
					  					
					  					var disableTime = this.timeSEdit[totalhours-1];
					  					this.yearlyrecords[time1][disableTime] = null;
					  				}

					  			}

					  			// update prospects if new prospect selected
					  			if(this.prospectId != this.yearlyrecords[time1][this.time].prospectID)
					  			{	
					  				//push previous record prospect to prospect lists
					  				this.searchProspect.push(this.yearlyrecords[time1][this.time]);

					  				// remove eelected prospect from porpsect list
					  				var index = this.searchProspect.findIndex(x => x.id_prosp == this.prospectId);
					  				this.searchProspect.splice(index, 1);
					  			}

					  			this.yearlyrecords[time1][this.time] = {
					  				name: this.prospectname, 
					  				totalhrs: this.edittotalhrs,
			   						address: this.address,
					  				contact_person: this.contactp,
									contact_telno: this.telno,
									mobile: this.mobile,
									contact_email: this.contact_email, 
									prospectID: this.prospectId
					  			};


					  			$("#myModal2").modal("hide");
					  			break;
					  			
					  		}
					 	 }
			   		})
			   		.catch(function(error){
			   			console.log(erorr+' updating');
			   		});

			   },

			    // FETCH APPOINTMENT DATES FROM DATABASE
			    getDates(){

			    	const CancelToken = axios.CancelToken;
					let cancel;

			    	axios.get('getdates')
			    	 .then((response)=>{

			    	 	for(var dates in response.data)
			    	 	{
			    	 		this.highlightdates.dates.push(new Date(response.data[dates].dates));
			    	 	}
			    	 })

			    	 .catch(function(error){

			    	 });

			    },

			   // FETCH PROSPECT LIST FROM DATABASE
			    prospectList(){

			    	const CancelToken = axios.CancelToken;
					let cancel;

			    	axios.get('searchProspect', { cancelToken: new CancelToken(function executor(c) {
	  					  cancel = c;
	  				}) })
			    	 .then((response)=>{
			    	 	this.searchProspect = response.data;
			    	 })

			    	 .catch(function(error){

			    	 });

			    },

			   	// FETCH APPOINTMENT RECORDS FROM THE DATABASE
			    recordList(date){

			    		
				    	axios.get('date/'+moment(date).format('YYYY-MM-DD'))
						  .then((response) => {
						    // handle success
						    this.yearlyrecords = response.data;
						    
						    
						    //filtered array
						    var self=this;
							const filtered = Object.keys(self.yearlyrecords)
							  .filter(key => moment(date).format('YYYY-MM-DD').includes(key))
							  .reduce((obj, key) => {
							    obj = self.yearlyrecords[key];

							    return obj;
							  }, {});
							this.searchDate = filtered;

						  })
						  .catch(function (error) {
						    // handle error
						   
						  });

					
					


			    },

			    // SET STARTTIME FUNCTION
			    timeSched(starttime){
			    
			    		this.time = starttime;
			    },

			    // FETCH TIME RANGE BASE FROM PARAMETER
			    timeRange(param1, param2 = null)
			    {
			    	var y = 0;
			  		var timearray = [];
				    	for(var x in this.searchDate){
				    		
				    		let time2 = parseInt(x.slice(-5, 4));
				    		let time1 = parseInt(this.time.slice(-5, 4));
				    		
				    		if(time2 < 8)
				    		{
				    			time2 = time2+12;
				    		}
				    		if(time1 < 8)
				    		{
				    			time1 = time1+12;
				    		}
				    		
				    		if(time1 < time2)
				    		{
				    			timearray[y] = x;
				    			y++;
				    			
				    			if( param1 != this.searchDate[x] && param2 != this.searchDate[x])
				    			{
				    				break;
				    			}
				    		}

				    		
				    		if(y == 3)
				    		break;

				    	}
	
			    	
			    	return timearray;
			    },

			    //EDIT
			    fetchRecEdit(index){

			    	$('#searchresultEdt').hide();
			    	//SET STARTTIME
			    	this.timeSched(index);

			    	// DISPLAY RECORD SELECTED DATE
			    	for(let time in this.searchDate)
			    	{
			    		if(index == time)
			    		{
			    			
			    			this.edittotalhrs = this.searchDate[time].totalhrs;
			    			
			    			//this.totalhrs = this.searchDate[time].totalhrs;
			    			this.prospectId = this.searchDate[time].id_prost;
			    			this.prospectname = this.searchDate[time].name;
			    	 	
			    	 		

			    			this.designation = this.searchDate[time].designation;
						  	this.address = this.searchDate[time].address;
					   		
					   		this.contactp = this.searchDate[time].contact_person;
							this.telno = this.searchDate[time].contact_telno;
							this.mobile = this.searchDate[time].contact_celno;
							this.contact_email = this.searchDate[time].contact_email;
							
			    			break;
			    		}
			    	}

			    },

			    // CREATE PDF REPORT
			    createPDF() {

			    		var rows =[];
			 			var columns = [
						    {title: "TIME", dataKey: "time"},
						    {title: "PROSPECT NAME", dataKey: "name"}, 
						    {title: "CONTACT NUMBER", dataKey: "contactnum"}, 
						    {title: "ADDRESS", dataKey: "address"}, 
						    
						];
						
						
						for(let prop in this.searchDate)
						{


							if(this.searchDate[prop] != null && this.searchDate[prop] != 'disabled')
							{

								this.timeSched(moment(this.searchDate[prop].starttime).format('HH:mm'));
								//alert();
								rows.push({ 

									time: moment(this.searchDate[prop].starttime).format('HH:mm') +' - '+ this.timeSEdit[this.searchDate[prop].totalhrs - 1], 
									name: this.searchDate[prop].prospectname,
									contactnum: '',
									address: this.searchDate[prop].address

								});
							}
						}
						
						
						// Only pt supported (not mm or in)
						var doc = new jsPDF('p', 'pt');
						doc.setProperties({
						    title: "SPA report"
						});
						doc.autoTable(columns, rows, {
						   startY: 90,
						    margin: { horizontal: 25},
						    styles: { overflow: 'linebreak', fontSize: 8 },
						    bodyStyles: { valign: 'top' },
						    columnStyles: {// addres: { columnWidth: 'wrap' } 

							},
						    theme: "striped",
						    addPageContent: (data)=> {
						    	doc.setFontSize(10);
						    	doc.setTextColor(46,128,186);
						    	doc.text(this.dateHead, 25, 85);
						   
						    }
						});	

						var colfoooter = [{ title: '', dataKey: 'signatury' }, {title: '', dataKey: 'footeroffset' }, { title: '', dataKey: 'requestor' }];
						var rowfooter = [

							{signatury: 'Rhealdwin Tumakay', footeroffset: '', requestor: 'Justine Juarez'}, 
							{signatury: 'Branch Officer', footeroffset: '', requestor: 'Account Officer'}, 

						]; 

						doc.autoTable(colfoooter, rowfooter, {
						   startY: 720,
						    margin: { horizontal: 45 },
						    styles: { overflow: 'linebreak', fontSize: 8 , cellPadding: 3, halign: 'left'},
						    bodyStyles: { valign: 'bottom' },
						    columnStyles: { 

						    	signatury: { columnWidth: 'wrap' },
						    	requestor: { columnWidth: 'wrap' },  

							},
							createdCell: function(cell, data) {
							    if (data.row.index === 0 || data.row.index === 3) {
							      cell.styles.fontStyle = 'bold';
							    }
							  },
							//alternateRowStyles: {},
						    theme: "plain",
						    addPageContent: (data)=> {
						    	/*doc.setFontSize(10);
						    	doc.setTextColor(46,128,186);
						    	doc.text(this.dateHead, 25, 35);*/
						    }
						});


				        var string = doc.output('datauristring',);
						var objectEl = '<body id="pdfviewer" style="overflow:hidden;"> <object id="obj" type="application/pdf" width="100%" height="100%" data= "'+string+'" ></object> </body>';
						var x = window.open();
						x.document.open();
						x.document.write(objectEl);
						x.document.close();

			
				
				},


			    // CLEAR VARIABLES 
			    clearFields(){
			    	
			    	//this.totalhrs = 1;
				  	this.contactp = '';
				  	this.address = '';
				  	this.telno = '';
				  	this.mobile = '';
				  	this.contact_email = '';
				  	this.prospectId = '';
				  	this.designation = '';
			    },

			    // CALL WHEN MODAL TRIGGERED FOR CLOSE
			    closeModal(){

			    	 this.prospectname = '';
			    	 this.totalhrs = 1;
			    	 this.disableBtn = false;
			    	 $('#searchresultEdt').hide();
				     this.clearFields();
				     $('#searchp').attr({'placeholder': 'Search Prospect Name....', 'class': 'input-group-field'});
				     $('[id=searchp]:eq(1)').attr({'placeholder': 'Search Prospect Name....', 'class': 'input-group-field'});
				},
		    

		  
		  },

		  computed:
		  {
		  		// RETURN SELECTED DATE
			  	dateHead(){
			  		return  moment(this.dynamicdatehead).format('Do MMMM YYYY');
			  	},

			  	// RETURN WEEKDAY OF SELECTED DATE
			  	weekday()
			  	{
			  		return moment(this.dynamicdatehead).format('dddd');
			  	},

			  	// SEARCH PROSPECT
			  	search(){

			  		
			    	var self=this;
				     var counter =1;
				     
				     return this.searchProspect.filter(function(prospect){
				    	 // return prospect.prospectname.toLowerCase().indexOf(self.prospectname.toLowerCase())>=0 && counter++ <=3;
				    	 // return prospect.prospectname.toLowerCase().indexOf(self.prospectname.toLowerCase()) == 0 && prospect.status != 0 && counter++ <=3;
				    	 return prospect.name.toLowerCase().indexOf(self.prospectname.toLowerCase()) == 0 && counter++ <=3;
				    	});
			    },

			    // CHECK SELECTED DATE IF BEFORE CURRENT DATE
			    isBeforeDate()
			    {
			    	return moment(moment(this.dynamicdatehead).format('YYYY-MM-DD')).isBefore(moment(this.date).format('YYYY-MM-DD'));	
			    },

			    // check if calendar date is disabled
				isDisabledDate()
				{

					return this.disabledDates.days.includes(moment(this.dynamicdatehead).day()); 
				},

			   // TIME RANGE FOR ADD
			   timeS(){

			   		return this.timeRange(null);
			    	
			    },

			    // timeRange for Edit
			    timeSEdit()
			    {
			    	return this.timeRange(null, 'disabled');
			    },


			 	hasRecords(){
			  		for(let prop in this.searchDate){
			  			if(this.searchDate[prop] != null)
			  			return true;
			  		}
			  		return false;		
			  },
			    	
		  },

		  created(){

		  		//call records from prospects and appointment when page loads 
		  		this.recordList(this.date);
		  		this.prospectList();
		  		this.getDates();
		  		
		  		
		  },

		  mounted(){

		    // bind bootstrap  modal to vue
		    $(this.$refs.vuemodal).on("hidden.bs.modal", this.closeModal);
		    $(this.$refs.vuemodalEdt).on("hidden.bs.modal", this.closeModal);
		  }


		})


		$('.menu li a').click(function(){
			$('.menu li a').attr("class","");
			$(this).attr("class","active");
		});



		$('span.cell.day').click(function(){

			/*var $rows = $(".float.data-time, .float.data-record").addClass("data-animate");

		    setTimeout(function() {
		       $rows.removeClass("data-animate");
		    }, 3000);*/
			
			
		});

	


		
	</script>
@endpush