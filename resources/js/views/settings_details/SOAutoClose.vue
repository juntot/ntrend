<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
.p-15{
  padding: 15px;
}
.dflex.baseline{
      align-items: baseline;
}
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<template>
    <div>
        <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div>
        <div class="col-lg-12 header-title margin-15 p-15 bgc-white">
            <h4>Auto Close SAP Documents</h4>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 margin-15 bgc-white">
            <!-- <div class="padding-tb-15 bgc-white">
                <div class="dflex">
                    <div class="policy-category"> -->
                      <div class="p-15">
                        <button :class="isIstart? 'btn btn-danger': 'btn btn-success' " @click="activateCron" >{{ isIstart | startfilter }} Auto Close</button>
                      </div>
                    <!-- </div>
                </div>
            </div> -->
            <div class="col-md-6">
              <div class="checkbox" v-for="(val, i) in company" :key="i">
                <label><input type="checkbox" :value="val.name" v-model="selectedcomp" @change="updateComp">{{val.name}}</label>
              </div>
              <!-- <div class="checkbox">
                <label><input type="checkbox" value="">Option 2</label>
              </div>
              <div class="checkbox disabled">
                <label><input type="checkbox" value="" disabled>Option 3</label>
              </div> -->
                
            </div>
            <div class="clearfix"></div>
            <div class="dflex baseline">
              <div class="col-md-6">
                Sales Order
              </div>
              <div class="col-md-6">
                <div class="mdb-form-field form-group-limitx">
                      <div class="form-field__control">
                          <input type="text" class="form-field__input" v-model="dayslimit" name="comp_name" @change="updateDaysLimit">
                          <label class="form-field__label">Auto close days limit</label>
                          <div class="form-field__bar"></div>
                      </div>
                      <span class="errors">{{ errors.first('comp_name') }}</span>
                  </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="dflex baseline">
              <div class="col-md-6">
                Inventory Transfer Request
              </div>
              <div class="col-md-6">
                <div class="mdb-form-field form-group-limitx">
                      <div class="form-field__control">
                          <input type="text" class="form-field__input" v-model="dayslimit_invtrans" name="comp_name" @change="updateDaysLimitInvTrans">
                          <label class="form-field__label">Auto close days limit</label>
                          <div class="form-field__bar"></div>
                      </div>
                      <span class="errors">{{ errors.first('comp_name') }}</span>
                  </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="dflex baseline">
              <div class="col-md-6">
                Return Request
              </div>
              <div class="col-md-6">
                <div class="mdb-form-field form-group-limitx">
                      <div class="form-field__control">
                          <input type="text" class="form-field__input" v-model="dayslimit_returnrequest" name="comp_name" @change="updateDaysLimitReturnRequest">
                          <label class="form-field__label">Auto close days limit</label>
                          <div class="form-field__bar"></div>
                      </div>
                      <span class="errors">{{ errors.first('comp_name') }}</span>
                  </div>
              </div>
            </div>
            <small style="color:#3d4b96; font-size: 14px;">
              <i>Note: Setting zero value for days limit will disable the autoclose per request</i>
            </small>
            <br><br>
            <div class="clearfix"></div>
        </div> <br>

        <div class="col-lg-12 header-title margin-15 p-15 bgc-white">
            <h4>Notification</h4>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 margin-15 bgc-white">
          <div class="col-md-12">
                <label class="switch">
                  <input type="checkbox" v-model="smsnotif" @change.prevent="handleChangeCheckboxSms">
                  <span class="slider round"></span>
                </label>
           </div>
           <div class="col-md-12">
              <div class="mdb-form-field form-group-limit">
                  <div class="form-field__control">
                      <input type="text" class="form-field__input" v-model="mobilenum" name="mobile num" @change="addMobile">
                      <label class="form-field__label">Mobile Num:</label>
                      <div class="form-field__bar"></div>
                  </div>
                  <span class="errors">{{ errors.first('mobile num') }}</span>
              </div>
           </div>
           <div class="clearfix"></div>
        </div>
    </div>
</template>

<script>
// import ManagePolicy from '../components/ManagePolicy';

export default {

    components: {
        // ManagePolicy
    },
    data() {
        return {
            headers:  [],
            company: [],
            loader: false,
            selectedcomp: [],
            dayslimit: 0,
            dayslimit_invtrans: 0,
            dayslimit_returnrequest: 0,
            isIstart: false,
            mobilenum: '',
            smsnotif: false,
        }
    },
    watch:{
        rows(val, old){
            let row = [];
            this.dtHandle.clear();
            this.dtHandle.rows.add(val);
            this.dtHandle.draw();
        }
    },
    computed:{
        isDuplicate(){
            // let catname = this.input_catname.toLowerCase();
            // let rows = this.rows
            // if(catname != ''){
            //     let duplicate = rows.find(data=>(data.groupname.toLowerCase())==catname);
            //     return duplicate ? true: false;
            // }else{
            //     return false;
            // }
        },
        isFormValid(){
			    return !Object.keys(this.fields).some(key => this.fields[key].invalid);
		    },
    },
    filters:{
        startfilter: function(value){
          return !value? 'Enable': 'Disable';
        }
    },
    methods:{
       handleChangeCheckboxSms(){
          axios.post('api/cron-sms-notif',{
            smsnotif: this.smsnotif
          });
       },
       addMobile(){
          axios.post('api/cron-sms-notif-num',{
            mobilenum: this.mobilenum
          });
       },
       updateDaysLimit(){
          axios.post('api/cron-so-dayslimit',{
            dayslimit: this.dayslimit
          });
       },
       updateDaysLimitInvTrans(){
          axios.post('api/cron-invtrans-dayslimit',{
            dayslimit_invtrans: this.dayslimit_invtrans
          });
       },
       updateDaysLimitReturnRequest(){
          axios.post('api/cron-returnrequest-dayslimit',{
            dayslimit_returnrequest: this.dayslimit_returnrequest
          });
       },
       updateComp(){
          axios.post('api/cron-so-updatecomp',{
            selectedcomp: this.selectedcomp.toString()
          });
       },
       activateCron(){
          axios.post('api/cron-so-action',{
            isStart: !this.isIstart
          })
          .then(()=>{
            this.isIstart = !this.isIstart;
          });
          
       },
       MDBINPUT(){
            this.$nextTick(() => {
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
                                if(!el.parentNode.classList.contains('form-field__control')){
                                    el.parentNode.classList.add('form-field__control');
                                }
                                el.parentNode.parentNode.classList.add('form-field--is-filled');
                            }
                        }
                    );
             });
        },
    },
    created(){

    },
    mounted() {
        
        // SO Settings
        axios.get('api/cron-so-settings')
        .then(({data})=>{
          this.mobilenum = data.mobilenum;
          this.selectedcomp = data.selectedcomp.length > 0? data.selectedcomp.split(","): [];
          this.dayslimit = data.dayslimit;
          this.dayslimit_invtrans = data.dayslimit_invtrans;
          this.dayslimit_returnrequest = data.dayslimit_returnrequest;
          this.isIstart =  data.isStart;
          this.smsnotif = data.smsnotif;
          this.MDBINPUT();
        });

        // api settings
        axios.get('api/get-override-setting-company')
        .then(({data})=>{
          data.forEach( val => {
              if(val.type == 'company') {
                  this.company = JSON.parse(val.json);
              }
          });
        });
        // MODAL
        // $('#ardelivery-sett-modal').on("hidden.bs.modal", this.closeModal);

        
    }
}

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
</script>
