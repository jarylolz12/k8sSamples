<template>
  <div>
    <div :class="'justmodal-shadow '+( open ? 'active' : '' )"></div>
    <div :class="'justmodal-wrapper '+( open ? 'active' : '' )" @click="open=false">
      <div class="justmodal-container" @click.stop>
        <svg xmlns="http://www.w3.org/2000/svg" style="height:24px" viewBox="0 0 320 512" class="justmodal-close" @click="open=false">
          <path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"/>
        </svg>
        <div class="justmodal-header-wrapper">
          <div class="justmodal-header"><h3 class="mb00">{{ title }}</h3></div>
        </div>
        <div class="justmodal-content">
          <table class="table" style="width:100%">
            <thead>
              <tr>
                <th>Updated From</th>
                <th>Updated To</th>
                <th>Update Date</th>
                <th>Origin</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in etas">
                <td>{{ item.old_eta }}</td>
                <td>{{ item.eta }}</td>
                <td>{{ item.updated_at }}</td>
                <td>{{ item.origin }}</td>
              </tr>
            </tbody>
          </table>
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
    width: 800px;
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
      min-height:30px;
      max-height: 87vh;
      overflow-y: auto;
      overflow-x:hidden;
    }
  }
  .justmodal-close {
    position: absolute;
    top:16px;
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
  export default{
    props: ['title','etalogs','terminal_etalogs'],
    data(){
      return {
        open : false,
        etas: []
      }
    },
    methods: {
      formatTimestamp(timestamp) {
        return new Date(timestamp).toLocaleString()
      },
      sortData(items){
        return items.sort((a,b)=> a.date_sort - b.date_sort );
      }
    },
    mounted(){
      let _this = this;

      let arr1 = this.etalogs.map( item=>{
        item.old_eta = item.old_eta ? _this.formatTimestamp(item.old_eta) : '';
        item.eta = item.old_eta ? _this.formatTimestamp(item.eta) : '';
        item.updated_at = item.created_at ? _this.formatTimestamp(item.created_at) : '';
        item.date_sort = item.created_at ? new Date(item.created_at).getTime() : '';
        item.origin = 'Status';
        return item;
      });

      let arr2 = this.terminal_etalogs.map( item=>{
        item.old_eta = item.eta ? _this.formatTimestamp(item.eta) : '';
        item.eta = item.eta ? _this.formatTimestamp(item.eta) : '';
        item.updated_at = item.updated_at ? _this.formatTimestamp(item.updated_at) : '';
        item.date_sort = item.updated_at ? new Date(item.updated_at).getTime() : '';
        item.origin = 'Terminal';
        return item;
      });
      
      this.etas = this.sortData(arr1.concat(arr2).sort( (a,b) => ( a.updated_at < b.updated_at ) ? 1 : -1 ),'asc');
    }
  }
</script>