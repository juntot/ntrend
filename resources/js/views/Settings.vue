<style lang="css">
    /* @import 'https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.1.0/material.min.css';
    @import 'https://cdn.datatables.net/1.10.19/css/dataTables.material.min.css'; */
</style>
<template>
    <div class="setting-container">
        <!-- <div id="loader2" v-if="loader">
            <div class="loader-bar"></div>
        </div> -->
        <div class="col-lg-12 header-title">
            <h4>Maintenance</h4>
        </div>

       <div class="col-lg-12 canvas-chart margin-15 settings">
            <div class="row">
                <div class="col-xs-12">
                    <!-- tabs -->
                    <div class="tabbablex tabs-leftx">
                        <div class="dflex">
                            <div class="tabbable tabs-left">
                                    <ul class="nav nav-tabs nav-tabs-settings" style="margin-right: 3px;">
                                        <li><a href="#services" data-toggle="tab" @click.prevent="removeClass">E-Forms Maintenance</a></li>
                                        <li class="active"><a href="#home" data-toggle="tab" @click.prevent="addClass" >Company Logo</a></li>
                                        <li><a href="#post" data-toggle="tab" @click.prevent="removeClass">Post</a></li>
                                        <li><a href="#about" data-toggle="tab" @click.prevent="removeClass(), initTablePos">Leave Balance</a></li>
                                        
                                        <li><a href="#supplementaryForm" data-toggle="tab" @click.prevent="removeClass">Supplementary Form</a></li>
                                        <li><a href="#sap_setting" data-toggle="tab" @click.prevent="removeClass">API Settings</a></li>
                                        <li><a href="#override" data-toggle="tab" @click.prevent="removeClass">Override Settings</a></li>
                                        <li><a href="#transmittalSett" data-toggle="tab" @click.prevent="removeClass">Transmittal Address</a></li>
                                        <li><a href="#ARdelivery" data-toggle="tab" @click.prevent="removeClass">A/R Delivery</a></li>
                                        <li><a href="#SOautoClose" data-toggle="tab" @click.prevent="removeClass">Auto Close SAP Documents</a></li>
                                        <!-- <li><a href="#contact" data-toggle="tab" @click.prevent="removeClass">Employees</a></li> -->
                                        
                                    </ul>
                            </div>
                            <div id="settings-content" class="settings-content">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="home">
                                            <div class="">
                                                <GeneralSettings />
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="post">
                                            <div class="">
                                                <Posts />
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="about">
                                            <div class="">
                                                <SL_VLSettings ref="initTable"/>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="services">
                                            <div class="">
                                                <FormSettings />
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="contact">
                                            <div class="">
                                                <!-- <EmployeeSettings/> -->
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="supplementaryForm">
                                            <div class="">
                                                <AdminWitnessSup/>
                                                <!-- <EmployeeSettings/> -->
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="sap_setting">
                                            <div class="">
                                                <SAPSettings/>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="override">
                                            <div class="">
                                                <OverrideSettings/>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="transmittalSett">
                                            <div class="">
                                                <transmittalSett/>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ARdelivery">
                                            <div>
                                                <ARDeliverySetting/>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="SOautoClose">
                                            <div>
                                                <SOAutoClose/>
                                            </div>
                                        </div>
                                    </div>
                            </div>

                        </div>
                    </div>
                    <!-- /tabs -->
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import GeneralSettings from './settings_details/GeneralSettings'
import SL_VLSettings from './settings_details/SL_VLSettings'
import FormSettings from './settings_details/FormSettings'
// import EmployeeSettings from './settings_details/EmployeeSettings'
import Posts from './settings_details/Posts'
import SAPSettings from './settings_details/SAPSettings'
import OverrideSettings from './settings_details/OverrideSettings'
import AdminWitnessSup from './AdminWitnessSup'
import transmittalSett from './settings_details/TransmittalAdressSettings.vue'
import ARDeliverySetting from './settings_details/ARDeliverySetting.vue'
import SOAutoClose from './settings_details/SOAutoClose'
export default {
    components:{
        GeneralSettings,
        SL_VLSettings,
        FormSettings,
        // EmployeeSettings,
        SAPSettings,
        AdminWitnessSup,
        Posts,
        OverrideSettings,
        transmittalSett,
        ARDeliverySetting,
        SOAutoClose
    },
    data(){
        return{

        }
    },
    methods:{
        addClass(){
            $('#settings-content').addClass('settings-content');
        },
        removeClass(){
            $('#settings-content').removeClass('settings-content');
        },
         initTablePos(){
            //  this.addClass();
             this.$refs.initTable.initTable();
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
                                // console.log(el);
                                if(!el.parentNode.classList.contains('form-field__control')){
                                    el.parentNode.classList.add('form-field__control');
                                }
                                el.parentNode.parentNode.classList.add('form-field--is-filled');
                            }
                        }
                    );
             });
        }
    },
    beforeDestroy(){
        // $('article').removeClass('bgc-white');
    },
    mounted(){
        // $('article').addClass('bgc-white');

        $('.nav-tabs a').on('shown.bs.tab', this.initTablePos);
        this.MDBINPUT();
        $('article').css({"min-height": '90%'});
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
