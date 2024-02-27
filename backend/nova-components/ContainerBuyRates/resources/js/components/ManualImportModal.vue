<template>
  <div>
    <div :class="'justmodal-shadow '+( open ? 'active' : '' )"></div>
    <div :class="'justmodal-wrapper '+( open ? 'active' : '' )" @click="$store.commit('modal_open',false)">
      <div class="justmodal-container" @click.stop>
        <svg xmlns="http://www.w3.org/2000/svg" class="justmodal-close" style="width:16px" viewBox="0 0 320 512" @click="open=false">
          <path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"/>
        </svg>
        <div class="justmodal-header-wrapper">
          <div class="justmodal-header"><h4 class="mb00">Manual Import</h4></div>
        </div>
        <div class="justmodal-content">
          <form action="" @submit.prevent="save()">
            <div class="mb-2">
              <label class="mb-1 block">Container</label>
              <v-select :options="containers" :loading="loading" @search:blur="searchBlur1" :value="container" placeholder="Select container" v-model="container">
                <template #search="{attributes, events}">
                  <input 
                    name="container" 
                    class="vs__search"
                    v-bind="attributes"
                    v-on="events" 
                  />
                </template>
              </v-select>
            </div>
            <div class="mb-2">
              <label class="mb-1 block">Date</label>
              <input type="date" class="w-full form-control form-input form-input-bordered" v-model="data_date">
            </div>
            <div class="mb-2">
              <label class="mb-1 block">Location From</label>
              <v-select :options="offices" :loading="loading2" @search:blur="searchBlur2" :value="location_from" placeholder="Select location from" v-model="location_from">
                <template #search="{attributes, events}">
                  <input 
                    name="container" 
                    class="vs__search"
                    v-bind="attributes"
                    v-on="events" 
                  />
                </template>
              </v-select>
            </div>
            <div class="mb-2">
              <label class="mb-1 block">Location To</label>
              <v-select :options="offices" :loading="loading3" @search:blur="searchBlur2" :value="location_to" placeholder="Select location to" v-model="location_to">
                <template #search="{attributes, events}">
                  <input 
                    name="container" 
                    class="vs__search"
                    v-bind="attributes"
                    v-on="events" 
                  />
                </template>
              </v-select>
            </div>
            <div class="mb-2">
              <label class="mb-1 block">Terminal</label>
              <v-select :options="terminals" :loading="loading4" @search:blur="searchBlur3" :value="terminal" placeholder="Select terminal" v-model="terminal">
                <template #search="{attributes, events}">
                  <input 
                    name="container" 
                    class="vs__search"
                    v-bind="attributes"
                    v-on="events" 
                  />
                </template>
              </v-select>
            </div>
            <div class="mb-4">
              <label class="mb-1 block">Amount</label>
              <input type="number" class="w-full form-control form-input form-input-bordered" v-model="amount">
            </div>
            <div class="mb-2 flex">
              <button class="float-right btn bg-success flex text-white w-40 p-3 rounded justify-center mr-2" :disabled="saving">{{ saving ? 'Saving...' : 'Save' }}</button>
            </div>
          </form>
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
    background: rgba(0,0,0,0.5);
    z-index: 9999;
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
    z-index: 99999 !important;
    opacity: 1 !important;
    transform: translate3d(0, 0px, 0) !important;
    -webkit-transform: translate3d(0, 0px, 0) !important;
    -moz-transform: translate3d(0, 0px, 0) !important;
    visibility: visible !important;
  }
  .justmodal-container {
    min-height: 60px;
    max-height: 100%;
    background: #FFF;
    width: 600px;
    padding: 24px 0px;
    position: relative;
    -webkit-box-shadow: 0 10px 40px -6px rgba(0,0,0,.1);
    -moz-box-shadow: 0 10px 40px -6px rgba(0,0,0,.1);
    -o-box-shadow: 0 10px 40px -6px rgba(0,0,0,.1);
    box-shadow: 0 10px 40px -6px rgba(0,0,0,.1);
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
      max-height: 87vh;
    }
  }
  .justmodal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    font-size: 16px;
    z-index: 10;
    opacity: .5;
    color: #555;
    cursor: pointer;
    &:hover {
      opacity: 1 !important;
    }
  }
  @media (max-width: 767px ) {
    .justmodal-container {
      width: 90% !important;
    }
    #app-loader-content {
      width: 95% !important;
    }
  }
  @media (min-width: 768px ) and (max-width: 1024px ) {
    .justmodal-container {
      width: 500px !important;
    }
  }

</style>

<script>
  import vSelect from 'vue-select';
  import 'vue-select/dist/vue-select.css';

  export default{
    components: { vSelect },
    data(){
      return {
        open: false,
        id: false,
        data_date: '',
        location_from: '',
        location_to: '',
        terminal: '',
        amount: '',
        container: '',
        containers: [],
        terminals: [],
        offices: [],
        loading: false,
        loading2: false,
        loading3: false,
        saving: false
      }
    },
    watch: {
      open(v){
        if( v ){
          this.loadContainers();
        }else{
          this.reset();
        }
      }
    },
    methods: {
      searchBlur1(){
        if( this.containers.findIndex( i => i == this.value ) == -1 ){
          this.value = '';
        }
      },
      searchBlur2(){
        if( this.offices.findIndex( i => i == this.value ) == -1 ){
          this.value = '';
        }
      },
      searchBlur3(){
        if( this.terminals.findIndex( i => i == this.value ) == -1 ){
          this.value = '';
        }
      },
      save(){
        let _this = this;

        this.saving = true;

        Nova.request().post('/nova-vendor/container-buy-rates/index-rates/save',{
          id: this.id,
          data_date: this.data_date,
          location_from: this.location_from,
          location_to: this.location_to,
          terminal: this.terminal,
          amount: this.amount,
          container: this.container
        }).then(res => {
          if( res.data.success ){
            Nova.$emit('index_rates_reload',1);
          }
          _this.$toasted.show(res.data.message, { type: ( res.data.success ? 'success' : 'error' ) });

          _this.saving = false;

          if( !_this.id ){
            _this.reset();
          }else{
            _this.$emit('manual_import_update',{
              id: _this.id,
              data_date: _this.data_date,
              location_from: _this.location_from,
              location_to: _this.location_to,
              terminal: _this.terminal,
              amount: _this.amount,
              container: _this.container
            });
          }
        }).catch(err=>{
          let message = '';
          let err_arr = [];

          if( err.response.data.errors.data_date){
            err_arr.push(err.response.data.errors.data_date[0]);
          }

          if( err.response.data.errors.location_from){
            err_arr.push(err.response.data.errors.location_from[0]);
          }

          if( err.response.data.errors.location_to){
            err_arr.push(err.response.data.errors.location_to[0]);
          }

          if( err.response.data.errors.terminal){
            err_arr.push(err.response.data.errors.terminal[0]);
          }

          if( err.response.data.errors.amount){
            err_arr.push(err.response.data.errors.amount[0]);
          }

          if( err.response.data.errors.container){
            err_arr.push(err.response.data.errors.container[0]);
          }

          message = err_arr.join(', ');

          _this.$toasted.show(message, { type: 'error' });

          _this.saving = false;
        });
      },
      reset(){
        this.id = false;
        this.data_date = '';
        this.location_from = '';
        this.location_to = '';
        this.terminal = '';
        this.amount = '';
        this.container = '';
        this.saving = false;
        this.loading = false;
        this.loading2 = false;
        this.loading3 = false;
        this.loading4 = false;
      },
      loadContainers(){
        let _this = this;

        this.laoding = true;
        this.loading2 = true;
        this.loading3 = true;
        this.loading4 = true;

        Nova.request().get('/nova-vendor/container-buy-rates/containers/all').then(res => {
          _this.containers = res.data;
          _this.loading = false;
        }).catch(err=>{
          _this.$toasted.show(message, { type: 'error' });
          _this.loading = false;
        });

        Nova.request().get('/nova-vendor/container-buy-rates/offices/all').then(res => {
          _this.offices = res.data;
          _this.loading2 = false;
          _this.loading3 = false;
          _this.loading4 = false;
        }).catch(err=>{
          _this.$toasted.show(message, { type: 'error' });
          _this.loading2 = false;
          _this.loading3 = false;
          _this.loading4 = false;

        });
        Nova.request().get('/nova-vendor/container-buy-rates/terminals/all').then(res => {
          _this.terminals = res.data;
          _this.loading2 = false;
          _this.loading3 = false;
          _this.loading4 = false;
        }).catch(err=>{
          _this.$toasted.show(message, { type: 'error' });
          _this.loading2 = false;
          _this.loading3 = false;
          _this.loading4 = false;
        });
      }
    }
  }
</script>