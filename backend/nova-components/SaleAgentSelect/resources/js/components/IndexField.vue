<template>
    <span>{{ sale_persons }}</span>
</template>

<style type="text/css">
	div[dusk="customers-index-component"] .table td:first-child,
	div[dusk="customers-index-component"] .table th:first-child {
		display:  table-cell !important;
	}
</style>
<script>
import JQuery from 'jquery'
window.$ = JQuery
export default {
    props: ['resourceName', 'field'],
    data: function() {
        return {
            sale_persons: 'Loading Sales Representative...'
        };
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
	        		this.sale_persons = ` ${result.name}`;
	        	}else {
	        		this.sale_persons = 'No Sales Representative was assigned.';
	        	}
	        });

        /* }); */


    }
}
</script>
