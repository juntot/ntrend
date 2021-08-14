import Vue from 'vue';
import Vuex from 'vuex';

let employee = '';

// $.ajax({
//     async: false,
//     url: 'api/employees',
//     type: 'GET',
//     success: function(response) {
//         employee = response;
//     }
// })



Vue.use(Vuex);

export const store = new Vuex.Store({
        state: {
        //    product: employee
        },
        getters:{
            allEmployee: (state)=>{
                return state.product;
            }
        },
        mutations:{
            intializeEmp:(state, val)=>{
                state.product = val;
            }
        },
        actions:{
            intializeEmpAct: (state)=>{
                state.commit('intializeEmp', val);
            }
        }  
});
