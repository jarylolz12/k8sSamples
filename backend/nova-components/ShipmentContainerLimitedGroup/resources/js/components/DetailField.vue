<template>
    <div class="flex border-b border-40">
	    <div class="w-1/4 py-4">
	        <h4 class="font-normal text-80">Containers</h4>
	    </div>
	    <div class="w-3/4 py-4 break-words">
	    	<div style="border-bottom: 0px transparent; padding-top: 0px;" v-show="(containersDisplay.length==0 || containersDisplay=='')" class="shipment-container-group">
	    		<div>
	    			No Container added yet to this Shipment.
	    		</div>
	    	</div>
	    	<div data-aos="fade-left" data-aos-once="true" data-aos-duration="500" v-if="containersDisplay.length>0" :id="`shipment-container-group-${item.id}`" v-for="(item, key) in containersDisplay" class="shipment-container-group" style="padding-top: 0px; margin-top: -5px; border-bottom: 1px solid #eef1f4;">
	    		<div>
	    			<label style="font-weight: bold;">Container {{ (key + 1)}}</label>
	    		</div>
                <div>
                    <label>Container #</label>
                    <span style="padding-top: 5px;">{{ item.container_num }}</span>
                </div>
                <div>
                    <label>Size</label>
                    <span style="padding-top: 5px;">{{ item.size_name }}</span>
                </div>
	    	</div>
	    </div>
  	</div>
</template>
<script>
import _ from 'lodash';
import jQuery from 'jquery';
import AOS from 'aos'
import 'aos/dist/aos.css'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data: function() {
    	return {
    		containersDisplay: (typeof this.field.shipment.containers_group_bookings!=='undefined') ? JSON.parse(this.field.shipment.containers_group_bookings) : [],
            loading: false,
            sizes: []
    	};
    },
    computed: {
    	label() {
	      //return this.fieldName || this.field.name
	    	return 'Containers'
	    }
    },
    updated() {

        console.log(this.field.shipment.containers_group_bookings.length)
    },
    created() {
        AOS.init()
    },
    mounted() {
        localStorage.setItem(`shipment_containers${this.resourceId}`, (typeof this.field.shipment.containers_group_bookings!=='undefined') ? this.field.shipment.containers_group_bookings : []);
    }
}
</script>
