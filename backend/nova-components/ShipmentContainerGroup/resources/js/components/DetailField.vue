<template>
    <div class="flex border-b border-40">
	    <div class="w-1/4 py-4">
	      <slot>
	        <h4 class="font-normal text-80">{{ label }}</h4>
	      </slot>
	    </div>
	    <div class="w-3/4 py-4 break-words">
	    	<div style="border-bottom: 0px transparent; padding-top: 0px !important;" v-show="(containers.length==0 || containers=='') && loading==false" class="shipment-container-group">
	    		<div>
	    			No Container added yet to this Shipment.
	    		</div>
	    	</div>
            <div
                v-if="loading"
                style="min-height: 30px"
            >
                <loader style="margin-left: 0px !important;margin-right: 0px !important;" class="text-60" />
            </div>
	    	<div data-aos="fade-left" data-aos-once="true" data-aos-duration="500" v-show="containers.length>0" :id="`shipment-container-group-${item.id}`" v-for="(item, key) in containers" class="shipment-container-group" style="border-bottom:1px solid #eef1f4; padding-top: 4px;">
	    		<div>
	    			<label style="font-weight: bold;">Container</label>
	    		</div>
	    		<div>
                    <label>Container #</label>
                    <span>{{ item.container_num }}</span>
                </div>
                <div>
                    <label>Seal #</label>
                    <span>{{ item.seal_num }}</span>
                </div>
                <div>
                    <label>Size</label>
                    <span>{{ item.size_name }}</span>
                </div>
	    		<div>
                    <label>Volume(Number of CBM)</label>
                    <span>{{ item.cbm}}</span>
                </div>
                <div>
                    <label>Weight(Number of KG)</label>
                    <span>{{ item.kg}}</span>
                </div>
                <div>
                    <label>Carton Count</label>
                    <span>{{ item.cartons}}</span>
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
    created() {
        AOS.init()
    },
    data: function() {
    	return {
    		containers: (typeof this.resourceId!==null) ? JSON.parse(this.field.shipment.containers_group) : JSON.parse(this.field.value),
            sizes: [],
            loading: true,
    	};
    },
    methods: {
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        }
    },
    computed: {
    	label() {
	      //return this.fieldName || this.field.name
	    	return 'Containers'
	    },
        token() {
            return jQuery('meta[name="csrf-token"]').attr('content');
        }
    },
    mounted() {

        let containerSizeIds = []

        if (this.containers.length > 0) {
            this.containers.map((c, k) => {
                if (typeof c.size!=='undefined' && c.size!==null && c.size!=='') {
                    if (containerSizeIds.indexOf(c.size)==-1) {
                        containerSizeIds.push(c.size)
                    }
                }
            })
        }

        let fd = new FormData()
        fd.append('_token', this.token)
        fd.append('ids', JSON.stringify(containerSizeIds))

        //get all sizes
        fetch(`${this.field.baseUrl}/custom/container-sizes/where-in`,{
            body: fd,
            method: 'POST'
        }).then(this.handleResponse)
        .then( response => {

            let { results } = response
            if (typeof results!=='undefined' && results.length > 0) {
                this.sizes = results
                if (this.containers.length > 0) {
                    this.containers.map((c, k) => {
                        if (typeof c.size!=='undefined' && c.size!==null && c.size!=='') {
                            this.containers[k].size_name = _.find(results, {
                                id: parseInt(c.size)
                            }).name
                        }

                    })
                }
            }
            this.loading = false
        })
    }
}
</script>
