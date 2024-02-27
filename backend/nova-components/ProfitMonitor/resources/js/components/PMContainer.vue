<template>
<div>
    <card class="flex flex-col items-center">
        <tab />
        <!-- <profit-monitor-table/> -->
    </card>
</div>
</template>

<script>
import ProfitMonitorTable from './ProfitMonitorTable/ProfitMonitorTable.vue';
import Tab from './Tab.vue';
export default {
    name: "PMContainer",
    components: {
        ProfitMonitorTable,
        Tab
    },
    mounted() {

    },
}
</script>

<style>

</style>
