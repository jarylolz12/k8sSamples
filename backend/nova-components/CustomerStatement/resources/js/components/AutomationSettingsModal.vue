`<template>
   <div>
      <div :class="'justmodal-shadow '+( open ? 'active' : '' )"></div>
      <div :class="'justmodal-wrapper '+( open ? 'active' : '' )" @click="open=false">
         <div class="justmodal-container" @click.stop>
            <svg xmlns="http://www.w3.org/2000/svg" style="height: 24px;" viewBox="0 0 320 512" class="justmodal-close" @click="open=false">
               <path
                  d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"
               />
            </svg>
            <div class="justmodal-header-wrapper">
               <div class="justmodal-header"><h3 class="mb00">{{ title }}</h3></div>
            </div>
            <div class="justmodal-content">
               <form action="/nova-vendor/customer-statement/save" method="post" @submit.prevent="save(true)" ref="statementForm">
                  <div class="flex justify-start">
                     <div class="flex-initial" style="width: 190%; padding: 5px;">
                        <p class="font900 mb-2">Enabled?</p>
                        <input type="checkbox" v-model="sched_enabled">
                     </div>
                  </div>
                  <div class="flex justify-start">
                     <div class="flex-initial" style="width: 45%; padding: 5px;">
                        <p class="font900 mb-2">Every</p>
                        <select class="block w-full form-control-sm form-select" v-model="sched_type">
                           <option value="" selected>Select type</option>
                           <option :value="item" v-for="item in sched_types">{{ item }}</option>
                       </select>
                     </div>
                     <div class="flex-initial" style="width: 45%; padding: 5px;">
                        <p class="font900 mb-2">Day</p>
                        <select class="block w-full form-control-sm form-select" v-model="sched_day">
                           <option value="" selected>Select day</option>
                           <option :value="item" v-for="item in sched_days">{{ item }}</option>
                       </select>
                     </div>
                  </div>
                  <div class="flex justify-start">
                      <div class="flex-initial" style="width: 45%; padding: 5px;">
                        <p class="font900 mb-2">Time</p>
                        <vue-timepicker drop-direction="up" input-class="block w-full form-control-sm form-select" v-model="sched_time" format="hh:mm A"></vue-timepicker>
                     </div>
                  </div>
                  <div class="flex mt-6">
                     <div class="flex-initial">
                        <button @click.prevent="save()" class="btn btn-default btn-primary inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem;" :disabled="isLoading || isSaving">
                           Save
                        </button>
                        <button @click.prevent="open = false" class="btn btn-default btn-outlined inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem;border: 1px solid #ccc;color: #242424;text-shadow: none;">
                           Cancel
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</template>

<style lang="scss" scoped>
   .font900{ 
      font-weight:900!important;
   }
   .justmodal-shadow {
      position: fixed;
      top: 0px;
      left: 0px;
      width: 100%;
      height: 100vh;
      background: rgba(0, 0, 0, 0.5);
      z-index: 99;
      display: none;
   }
   .justmodal-shadow.active {
      display: block !important;
   }
   .justmodal-wrapper {
      position: fixed;
      width: 100%;
      height: 100vh;
      z-index: -999;
      top: 0px;
      left: 0px;
      align-items: center;
      justify-content: center;
      display: flex !important;
      opacity: 0;
      transition: all 0.3s ease;
      -webkit-transition: all 0.3s ease;
      -moz-transition: all 0.3s ease;
      transform: translate3d(0, 10px, 0);
      -webkit-transform: translate3d(0, 10px, 0);
      -moz-transform: translate3d(0, 10px, 0);
      visibility: hidden;
   }
   .justmodal-wrapper.active {
      z-index: 100 !important;
      opacity: 1 !important;
      transform: translate3d(0, 0px, 0) !important;
      -webkit-transform: translate3d(0, 0px, 0) !important;
      -moz-transform: translate3d(0, 0px, 0) !important;
      visibility: visible !important;
   }
   .justmodal-container {
      min-height: 60px;
      max-height: 100%;
      background: #fff;
      width: 800px;
      padding: 24px 0px;
      position: relative;
      -webkit-box-shadow: 0 10px 40px -6px rgba(0, 0, 0, 0.1);
      -moz-box-shadow: 0 10px 40px -6px rgba(0, 0, 0, 0.1);
      -o-box-shadow: 0 10px 40px -6px rgba(0, 0, 0, 0.1);
      box-shadow: 0 10px 40px -6px rgba(0, 0, 0, 0.1);
      .justmodal-header {
         padding: 0px 24px 8px 24px;
         border-bottom: 1px solid #ededed;
         margin-bottom: 24px;
         h5 {
            margin-top: 0px !important;
            margin-bottom: 0px !important;
         }
      }
      .justmodal-content {
         padding: 0px 24px;
         min-height: 30px;
         max-height: 87vh;
         overflow-y: auto;
         overflow-x: hidden;
      }
   }
   .justmodal-close {
      position: absolute;
      top: 16px;
      right: 16px;
      font-size: 16px;
      z-index: 10;
      opacity: 0.5;
      color: #555;
      cursor: pointer;
      &:hover {
         opacity: 1 !important;
      }
   }
   @media (max-width: 767px) {
      .justmodal-container {
         width: 90% !important;
      }
      #app-loader-content {
         width: 95% !important;
      }
   }
   @media (min-width: 768px) and (max-width: 1024px) {
      .justmodal-container {
         width: 500px !important;
      }
   }
</style>

<script>
   import VueTimepicker from 'vue2-timepicker'
   import 'vue2-timepicker/dist/VueTimepicker.css'
   import moment from "moment";

   export default {
      components: { VueTimepicker },
      data() {
         return {
            title: "Automation Settings",
            open: false,
            id: false,
            sched_enabled: false,
            sched_type: '',
            sched_types: ['Daily','Weekly','Monthy','Yearly'],
            sched_day: '',
            sched_days: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
            sched_time: '',
            isSaving: false,
            isLoading: true
         };
      },
      watch: {
         open(v){
            if( v ){
               this.fetchAutoSend();
            }
         }
      },
      computed: {},
      methods: {
         async save(is_send) {
            if (this.isSaving) {
               return;
            }

            this.isSaving = true;

            try {
         
               const { data } = await axios.post('/nova-vendor/customer-statement/auto-send/status/save',{
                  id: this.id,
                  sched_enabled: this.sched_enabled ? 1 : 0,
                  sched_type: this.sched_type,
                  sched_day: this.sched_day,
                  sched_time: moment(this.sched_time, "h:mm:ss A").format("HH:mm:ss")
               });

               this.$toasted.show( (data.message || 'Auto send settings has been saved'), { type: 'success' });

               this.isSaving = false;
               this.open = false;

            } catch (error) {
               console.log(error);
               const { data } = error.response || { data: {} };

               this.$toasted.show( (data.message || 'Could not save auto send data.'), { type: 'error' });
               this.isSaving = false;
            }
            
         },
         fetchAutoSend(){
            this.isLoading = true;
            
            let _this = this;

            axios.get('/nova-vendor/customer-statement/auto-send/status/get')
            .then(res => {
               _this.isLoading = false;

               if( !res.data.success ) return;

               _this.id = res.data.message.id;
               _this.sched_enabled = res.data.message.sched_enabled == 1 ? true : false;
               _this.sched_type = res.data.message.sched_type;
               _this.sched_day = res.data.message.sched_day;
               _this.sched_time = res.data.message.sched_time ? moment(res.data.message.sched_time,'HH:mm').format('hh:mm a') : ''; 

            })
            .catch(error => {
                console.log(error);
                _this.$toasted.show( 'An error occured upon downloading automation data.', { type: 'error' });
                _this.isLoading = false;

            });
        },
      }
   };
</script>
