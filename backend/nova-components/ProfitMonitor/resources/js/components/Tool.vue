<template>
    <div>
        <heading class="mb-6">Profit Monitor</heading>
        <p-m-container/>
        <!-- <card
         class="flex flex-col items-center"
        >
            <profit-monitor-table/>
        </card> -->
    </div>
</template>

<script>
import ProfitMonitorTable from './ProfitMonitorTable/ProfitMonitorTable.vue';
import PMContainer from './PMContainer.vue';
import Tab from './Tab.vue';
export default {
    name:"Tool",
    components:{
        PMContainer,
        ProfitMonitorTable,
        Tab
    },
    metaInfo() {
        return {
          title: 'ProfitMonitor',
        }
    },
    created() {
      if( !document.querySelector('#xls-export-script') ){
        let el = document.createElement('script');
        el.setAttribute('id','xls-export-script');
        el.src = "https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.1/xlsx.full.min.js";
        document.querySelector('head').appendChild(el);
        console.log('XLS script has been loaded');
      }  
    },
}
</script>

<style>
</style>
