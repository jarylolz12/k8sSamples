<template>
	<div class="flex border-b border-40">
	    <div class="w-1/4 py-4">
	      <slot>
	        <h4 class="font-normal text-80">{{ label }}</h4>
	      </slot>
	    </div>
	    <div class="w-3/4 py-4 break-words">
	     {{ sale_persons }}
	    </div>
  	</div>
</template>
<script>
import JQuery from 'jquery'
window.$ = JQuery
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data: function() {
        return {
            sale_persons: 'Loading Sales Representative...'
        };
    },
    computed: {
	    label() {
	      //return this.fieldName || this.field.name
	    	return 'Sales Representative'
	    }
	},
	mounted(){

    	/* var h = document.getElementsByTagName('head')[0];
    	var script = 'https://code.jquery.com/jquery-3.4.1.min.js';
    	var s = document.createElement('script');
        s.setAttribute('src',script);
        s.setAttribute('integrity','sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=');
        s.setAttribute('crossorigin','anonymous');
        s.setAttribute('type','text/javascript');
        h.appendChild(s);
        s.addEventListener('load',()=> { */
        	var id = this.field.value;
	    	$.ajax({
	            method: 'GET',
	            url: '/custom/sale-agents/find-by-id',
	            data: {
	                _token: $('meta[name="csrf-token"]').attr('content'),
	                id
	            }

	        }).done( response => {
	        	let { status } = response;
	        	if(status=='ok') {
	        		let { result } = response;
	        		this.sale_persons = ` ${result.name} - ${result.email}`;

	        	}else {
	        		this.sale_persons = 'No Sales Representative was assigned.';
	        	}


	        });

        /* }); */


    }

}
</script>
