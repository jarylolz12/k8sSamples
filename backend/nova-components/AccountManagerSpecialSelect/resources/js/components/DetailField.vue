<template>
	<div class="flex border-b border-40">
	    <div class="w-1/4 py-4">
	      <slot>
	        <h4 class="font-normal text-80">{{ label }}</h4>
	      </slot>
	    </div>
	    <div class="w-3/4 py-4 break-words">
	     {{ managers }}
	    </div>
  	</div>
</template>
<script>
import jQuery from 'jquery'
window.$ = jQuery
import _ from 'lodash'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data: function() {
        return {
            managers: 'Loading Account Managers...',
            offices_managers: JSON.parse(this.field.offices_managers) || [],
        };
    },
    computed: {
	    label() {
	      return this.fieldName || this.field.name
	    },
	    token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        }
	},
	methods: {
		handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
	},
    mounted(){

    	if (this.offices_managers.length > 0) {

    		let ids = []

    		this.offices_managers.map(o => {
    			ids.push(parseInt(o.manager_id))
    		})

    		let fd = new FormData()
    		fd.append('_token',this.token)
    		fd.append('ids', JSON.stringify(ids))

            const requestOptions = {
                method: 'POST',
                body: fd
            }

            console.log('here we go')

			fetch(`${this.field.baseUrl}/custom/account-managers/where-in`,requestOptions)
			.then(this.handleResponse)
			.then(response => {
				
				console.log('awts')
				console.log(response)
				let { results } = response
				let finalResults = []
				if (typeof results!=='undefined' && results.length > 0) {
					results.map ( result => {
						finalResults.push(result.name)
					})
					this.managers = finalResults.join(', ')
				} else {
					this.managers = 'No manager found.'
				}

			}).catch(e => {
				this.managers = 'No manager found.'
			})
		} else {
			this.managers = 'No manager found.'
		}


    	/* var h = document.getElementsByTagName('head')[0];
    	var script = 'https://code.jquery.com/jquery-3.4.1.min.js';
    	var s = document.createElement('script');
        s.setAttribute('src',script);
        s.setAttribute('integrity','sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=');
        s.setAttribute('crossorigin','anonymous');
        s.setAttribute('type','text/javascript');
        h.appendChild(s);
        s.addEventListener('load',()=> { */
        	/*
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
	        		this.managers = ` ${result.name} - ${result.email}`;

	        	}else {
	        		this.managers = 'No Account Manager was assigned.';
	        	}

	        });*/

        /* }); */


    }
}
</script>
