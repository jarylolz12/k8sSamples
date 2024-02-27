<template>
    <div class="flex border-b border-40">
	    <div class="w-1/4 py-4">
	      <slot>
	        <h4 class="font-normal text-80">Shifl Office</h4>
	      </slot>
	    </div>
	    <div class="w-3/4 py-4 break-words">
	    	<p class="text-90">
        	{{
        		name
        	}}
      		</p>
	    </div>
	</div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
    	return {
    		office: this.field.office || ''
    	}
    },
    computed: {
        name() {
            return (typeof this.office.name!=='undefined' && this.office.name!==null && this.office.name!=='') ? this.office.name : ''
        }
    }
}
</script>
