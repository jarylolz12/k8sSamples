<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <select
                :dusk="field.attribute"
                :resource-id="resourceId"
                :id="field.attribute"
                type="text"
                @change="(event) => { selectAgent(event) }"
                class="w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="placeholder"
                v-model="field.value"
            >
                <option>Select Sales Representative</option>
                <option :selected="sale_agents==o.id" v-if="options.length > 0"  v-for="o in options" :value="o.id">
                {{  o.text}}
                </option>
            </select>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import JQuery from 'jquery'
window.$ = JQuery

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],
    data: function() {
        return {
            token: $('meta[name="csrf-token"]').attr('content'),
            placeholder: '----Select Here----',
            base_url: this.field.baseUrl || '',
            options: [],
            sale_agents: this.field.value || '',
        };
    },
    mounted() {

            var id = this.field.value;
            var resourceId = this.resourceId;

            this.loadSaleAgents()


            //first load all account managers and put them in a variable container
            /*
            $.ajax({
                method: 'GET',
                url: '/custom/sale-agents/index',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    customer_id: (isNaN(resourceId)) ? null : resourceId
                }

            }).done( response => {

                let { results,related_sale_agent_id } = response;

                if(results.length>0) {

                    let saleAgents = results;
                    let saleAgentsOriginal = saleAgents;

                    var createOption = '';
                    var hasSaleAgent = false;
                    for(var x=0;x<results.length;x++) {

                        if (related_sale_agent_id!=null) {

                            if(related_sale_agent_id==results[x].id) {
                                hasSaleAgent = true;
                                createOption +=`<option selected="selected" value="${results[x].id}">${results[x].text}</option>`;
                            } else {
                                createOption +=`<option value="${results[x].id}">${results[x].text}</option>`;
                            }
                        } else {
                            createOption +=`<option value="${results[x].id}">${results[x].text}</option>`;
                        }
                    }

                    $('#sale_persons').html((!hasSaleAgent) ? `<option value="0" selected="selected">None</option>${createOption}` : `<option value="0">None</option>${createOption}`);
                }
            });*/
    },
    methods: {
        selectAgent(event) {
            this.sale_agents = event.target.value
            
        },
        loadSaleAgents() {
            
            let customer_id = (isNaN(this.resourceId)) ? null : this.resourceId
            fetch(`${this.base_url}/custom/sale-agents/index?_token=${this.token}&customer_id=${customer_id}`,{
                method: 'GET'
            })
            .then(this.handleResponse)
            .then( response => {
                let { results,related_sale_agent_id } = response
                this.options = results


            }).catch(e => {
                console.log(e)
            })

        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
        
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
            /*
            let sale_persons_val = $('#sale_persons').val();
            if(sale_persons_val.length>0 && sale_persons_val!='') {

               // formData.append('account_manager_ids',account_manager_val);
                //formData.append('sale_persons',sale_persons_val);
                //formData.append(this.field.attribute, this.value || '')
                
            }*/
            formData.append('sale_persons', this.sale_agents)
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
    },
}
</script>
