<template>
	<div class="flex border-b border-40">
	    <div class="w-1/4 py-4">
	      <slot>
	        <h4 class="font-normal text-80">{{ field.name }}</h4>
	      </slot>
	    </div>
	    <div class="w-3/4 py-4 break-words">
	    	<div v-if="emails.length > 0">
	    		<div v-bind:key="`k-${k}`" class="mb-4" v-for="(v,k) in emails">
	    			{{
	    				v
	    			}}
	    		</div>
	    	</div>
	    	<div v-if="emails.length==0">
	    		<strong>{{ field.emptyFieldsMessage }}</strong>
	    	</div>
	    </div>
   	</div>
</template>

<script>
import {
  mapActions,
  mapGetters
} from 'vuex'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
    	return {
            value: '',
    		//value: (typeof this.field.value!=='undefined' && this.field.value!==null && this.field.value!=='') ? JSON.parse(this.field.value) : [],
            emailsValue: []
    	}
    },
    computed: {
        entity() {
            return this.field.entity
        },
        emails() {

            //emails container
            let setEmails = []
            
            //first get the value
            let {
                value,
                emailsValue
            } = this

            if ( Array.isArray(value) ) {
                value.map(sv => {
                    let setValue = ''
                    if ( typeof sv.email!=='undefined')
                        setValue = sv.email
                    else
                        setValue = sv

                    if ( setEmails.indexOf(setValue)==-1)
                        setEmails.push(sv)
                })
            }
            return setEmails
        }
    },
    methods: {
        ...mapActions({
            fetchCustomerItem: 'customer/fetchItem',
            fetchOfficeItem: 'office/fetchItem'
        })
    },
    async mounted() {
        if ( this.field.entity == 'customer' || this.field.entity == 'office') {
            if ( this.field.attribute == 'booking_and_updated_emails' ) {
                try {

                    let output = (this.field.entity=='customer') ? await this.fetchCustomerItem({
                        id: this.field.id
                    }) : await this.fetchOfficeItem({
                        id: this.field.id
                    })

                    let {
                        data
                    } = output

                    if ( typeof data!=='undefined' )
                        this.emailsValue = data.item.emails
                } catch(e) {
                    console.log('err', e)
                }
            }
        }

        let value = (typeof this.field.value!=='undefined' && this.field.value!==null && this.field.value!=='') ? this.field.value : []
        try {
            this.value = JSON.parse(value)
        }catch(e) {
            this.value = value 
        }

    },
    updated() {
    }
}
</script>
