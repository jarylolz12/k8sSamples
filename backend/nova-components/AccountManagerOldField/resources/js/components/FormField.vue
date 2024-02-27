<template>
    <default-field :field="field" :errors="errors" id="hide-account-manager-select-id" >
        <template slot="field">
            <select class="w-full form-control form-input form-input-bordered" v-model="value">
                <option>{{ placeholder }}</option>
                <option :value="o.id" v-if="options.length > 0" v-for="o in options">
                    {{
                        o.text
                    }}
                </option>
            </select>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field'],
    data: function() {
        return {
            token: null,
            placeholder: '----Select Here----',
            base_url: this.field.baseUrl || '',
            options: []
        };
    },
    mounted(){

        fetch(`${this.base_url}/custom/account-managers-old`)
        .then(this.handleResponse)
        .then( response => {
            
            console.log(response)
            
            let { 
                results
            } = response

            if (typeof results!=='undefined') {
                this.options = results
            }

        }).catch(e => {
            console.log(e)
        })


        ///account-managers
       
    },
    methods: {

        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {

            formData.append('managers', this.value)
            
            /*
            let account_manager_val = $('#managers').val();
            if(account_manager_val.length>0) {
               // formData.append('account_manager_ids',account_manager_val);
                formData.append('managers',account_manager_val);
            }*/
        },
        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value;
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
    },
}
</script>

<style>
.hide-account-manager-select {
    display: none;
}
</style>