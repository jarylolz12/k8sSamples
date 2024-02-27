<template>
    <span>{{ managers }}</span>
</template>

<script>
import JQuery from 'jquery'
window.$ = JQuery
export default {
    props: ['resourceName', 'field'],
    data: function() {
        return {
            managers: null
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
	            url: '/custom/account-managers/find-by-id',
	            data: {
	                _token: $('meta[name="csrf-token"]').attr('content'),
	                id
	            }
	        }).done( response => {
	        	let { status } = response;
	        	if(status=='ok') {
	        		let { result } = response;
	        		this.managers = ` ${result.name}`;

	        	}else {
	        		this.managers = 'No Account Manager was assigned.';
	        	}
	        });
	    /* }); */

    }
}
</script>
