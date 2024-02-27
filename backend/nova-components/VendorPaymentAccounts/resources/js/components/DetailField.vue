<template>
	<div>
    	<div class="flex border-b border-40">
    	    <div class="w-1/4 py-4">
    	        <h4 class="font-normal text-80">Payment Accounts</h4>
    	    </div>
    	    <div class="w-3/4 py-4 break-words">
    	    	<div v-if="items.length == 0" class="flex mb-2 flex-col">
    	    		{{
    	    			"No payment account added yet."
    	    		}}
    	    	</div>
    			<div v-if="items.length > 0" class="flex mb-2 flex-col" v-for="(v,k) in items">
		        <div class="flex flex-row mb-4">
		          <div class="w-1/4">
		            <label class="inline-block text-80 pt-2 leading-tight w-full">Type</label>
		          </div>
		          <div class="w-1/2 flex flex-row pt-2">
		          	{{
		          		v.details.type
		          	}}
		          </div>
		          <div class="w-1/4">
		          </div>
		        </div>
		        <div class="flex flex-row mb-4">
		          <div class="w-1/4">
		            <label class="inline-block text-80 pt-2 leading-tight w-full">Routing Number</label>
		          </div>
		          <div class="w-1/2 pt-2">
		          	{{
		          		v.details.routing_number
		          	}}
		          </div>
		        </div>
		        <div class="flex flex-row mb-4">
		          <div class="w-1/4">
		            <label class="inline-block text-80 pt-2 leading-tight w-full">Account Number</label>
		          </div>
		          <div class="w-1/2 pt-2">
		            {{
		            	v.details.account_number
		            }}
		          </div>
		        </div>
		        <div class="flex flex-row mb-4">
		          <div class="w-1/4">
		            <label class="inline-block text-80 pt-2 leading-tight w-full">Address</label>
		          </div>
		          <div class="w-1/2 flex flex-col pt-2">
		            <div class="w-full mb-4">
		           	  {{
		           	  	v.details.address.line1
		           	  }}
		            </div>
		            <div class="w-full mb-4">
		              {{
		           	  	v.details.address.line2
		           	  }}
		            </div>
		            <div class="w-full mb-4">
		              {{
		           	  	v.details.address.city
		           	  }}
		            </div>
		            <div class="w-full mb-4">
		              {{
		           	  	v.details.address.state
		           	  }}
		            </div>
		            <div class="w-full mb-4">
		              {{
		           	  	v.details.address.country
		           	  }}
		            </div>
		            <div class="w-full mb-4">
		              {{
		           	  	v.details.address.postal_code
		           	  }}
		            </div>
		            <div class="w-full mb-4">
		              {{
		           	  	v.details.address.number
		           	  }}
		            </div>
		          </div>
		        </div>
		      </div>
    		</div>
    	</div>
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
    return {
      items: [],
	    }
	},
	created() {
	    this.items = JSON.parse(this.field.value) || []
	},
}
</script>
