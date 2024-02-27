<template>
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
            </div>
            <div class="justmodal-content">
               <div class="mb-6 pl-3 pr-3 text-center">
                  <div class="flex items-center justify-center mb-6">
                     <div style="width:80px;height:80px;">
                        <svg xmlns="http://www.w3.org/2000/svg" v-if="type == 'error'" class="icon icon-tabler icon-tabler-alert-circle" viewBox="0 0 24 24" stroke-width="2" stroke="#ffdb55" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                          <circle cx="12" cy="12" r="9" />
                          <line x1="12" y1="8" x2="12" y2="12" />
                          <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" v-else-if="type == 'success'" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                           <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                           <path d="M9 12l2 2l4 -4"></path>
                        </svg>
                     </div>
                  </div>
                  <h3>{{ text }}</h3>
               </div>
               <div class="text-center">
                  <a href="#" @click.prevent="$emit('confirm')" v-if="!disable_confirm_button" class="btn btn-default btn-primary inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem;">
                     {{ confirm_text }}
                  </a>
                  <a href="#" @click.prevent="open = false" v-if="!disable_cancel_button" class="btn btn-default btn-outlined inline-flex items-center m-5" dusk="create-button" style="height: 2.12rem; border: 1px solid #ccc; color: #242424; text-shadow: none;">
                     {{ cancel_text }}
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<style lang="scss" scoped>
   .justmodal-shadow {
      position: fixed;
      top: 0px;
      left: 0px;
      width: 100%;
      height: 100vh;
      background: rgba(0, 0, 0, 0.5);
      z-index: 200;
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
      z-index: 201 !important;
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
      width: 600px;
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
         overflow-y: hidden;
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
   export default {
      data() {
          return {
            open: false,
            text: '',
            type: '',
            confirm_text: 'Confirm',
            cancel_text: 'Cancel',
            disable_confirm_button: false,
            disable_cancel_button: false
         };
      },
      watch: {
         open(isOpen){
            if( !isOpen ){
               this.type = '';
               this.disable_confirm_button = false;
               this.disable_cancel_button = false;
               this.$emit('close',(new Date).getTime());
            }
         }
      }
   };
</script>
