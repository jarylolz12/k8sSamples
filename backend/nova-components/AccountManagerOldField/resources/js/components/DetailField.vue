<template>
    <div class="flex border-b border-40">
    	<div class="w-1/4 py-4">
	      <slot>
	        <h4 class="font-normal text-80">{{ label }}</h4>
	      </slot>
	    </div>
	    <div class="w-3/4 py-4 break-words">
	     {{ manager }}
	    </div>
  	</div>
</template>

<script>
import jQuery from 'jquery'
window.$ = jQuery
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data: function() {
        return {
            manager: 'Loading Account Manager...',
            base_url: this.field.baseUrl || '',
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
    	token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        },
        label() {
	      return this.fieldName || this.field.name
	    }
    },
    async mounted(){

        const requestOptions = {
            method: 'GET'
        }

		fetch(`${this.base_url}/custom/account-managers/find-by-id?id=${parseInt(this.field.value)}&_token=${this.token}`,requestOptions)
		.then(this.handleResponse)
		.then(response => {
			
			let { status, result } = response
			console.log(response)
            if (typeof status!=='undefined' && status=='ok') {
                this.manager = result.name
            } else {
                this.manager = 'No manager found.'
            }
		}).catch(e => {
			this.manager = 'No manager found.'
		})
    }
}
</script>
