import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify'

import 'vuetify/dist/vuetify.min.css'

import routes from './calendar-routes'
Vue.component('Datepicker', vuejsDatepicker);

moment.tz.setDefault("Asia/Manila");
Vue.use(Vuetify);
Vue.use(VueRouter);
new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    data: {
      aaw:'sdf'
    },
    mounted () {
    },
    router: new VueRouter(routes)

  })