<template>
    <!-- <panel-item :field="field"> -->
        <!-- <div slot="value"> -->
        <div class="flex border-b border-40 remove-bottom-border">
            <div class="w-1/4 py-4">
                <label for="time" class="inline-block text-80 pt-2 leading-tight">
                    Time
                </label>
            </div>
            <div class="w-3/4 py-4 break-words">
                {{ getTime() }}
            </div>
        </div>

        <!-- </div> -->
    <!-- </panel-item> -->
   
</template>

<script>
import moment from 'moment'
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    methods:{
        getTime: function(){
            let time = this.field.schedule.time
            if(this.shipmentTime !== ''){
                let getTime = moment.utc(time, 'hh:mm A')
                let localTime = getTime.local().format('hh:mm A')
                time = localTime;
            }
            return time
        }
    }
    
}
</script>
