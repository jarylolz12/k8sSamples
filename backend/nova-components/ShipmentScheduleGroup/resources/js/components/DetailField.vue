<template>
    <div class="flex border-b border-40">
	    <div class="w-1/4 py-4">
	      <slot>
	        <h4 class="font-normal text-80">{{ label }}</h4>
	      </slot>
	    </div>
	    <div class="w-3/4 py-4 break-words">
	    	<div style="border-bottom: 0px transparent;" v-show="(schedules.length==0 || schedules=='') && loading==false" class="shipment-schedule-group">
	    		<div>
	    			No Schedule added yet to this Shipment.
	    		</div>
	    	</div>
            <div style="border-bottom: 0px transparent;" v-show="loading==true">
                Checking Associated Schedules...
            </div>
	    	<div v-show="schedules.length>0" :id="`shipment-schedule-group-${item.id}`" v-for="(item, key) in schedules" class="shipment-schedule-group">
	    		<div>
	    			<label style="font-weight: bold;">Schedule</label>
	    		</div>
	    		<div v-show="key==0">
                    <label>Estimated Time of Departure</label>
                    <span>{{ item.etd }}</span>
                </div>
                <div>
                    <label>Estimated Time of Arrival</label>
                    <span>{{ item.eta }}</span>
                </div>
	    		<div v-show="key==0">
                    <label>Location From</label>
                    <span>{{ item.location_from_name}}</span>
                </div>
                <div>
                    <label>Location To</label>
                    <span>{{ item.location_to_name}}</span>
                </div>
                <div>
                    <label>Mode</label>
                    <span>{{ item.mode}}</span>
                </div>
	    	</div>
	    </div>
  	</div>
</template>
<script>
import _ from 'lodash';
import jQuery from 'jquery';

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data: function() {
    	return {
    		schedules: [],
            loading: false,
            terminal_regions: []
    	};
    },
    computed: {
    	label() {
	      //return this.fieldName || this.field.name
	    	return 'Schedules'
	    }
    },
    mounted() {
    	var token = jQuery('meta[name="csrf-token"]').attr('content');
        if(this.field.value!='') {
            var schedules = JSON.parse(this.field.value);
            console.log(schedules);
            if (schedules.length>0) {
                var limit = schedules.length;
                var counter = 0;
                let terminal_regions = [];

                jQuery.ajax(
                {
                	method: 'GET',
                	url: '/custom/terminal-regions'
                }).done(response => {

                	let { results } = response;
                	if (typeof results!='undefined'){
                		terminal_regions = results;
                		for(var x=0;x<schedules.length;x++) {

                			var location_from = _.find(terminal_regions,{
                				id: schedules[x].location_from
                			});
                			var location_to = _.find(terminal_regions,{
                				id: schedules[x].location_to
                			});

                			if(typeof location_from!='undefined') {
                				schedules[x].location_from_name = location_from.name;
                			}
                			if(typeof location_to!='undefined') {
                				schedules[x].location_to_name = location_to.name;
                			}

                		}
                	}
                	this.schedules = schedules;

                });

            }
            else
                counter = schedules.length;
            this.loading = false;

        }
        else
        {
            this.loading = false;
        }

    }
}
</script>
