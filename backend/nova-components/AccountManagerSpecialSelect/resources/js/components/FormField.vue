<template>
    <div>
        <div v-if="offices.length >0" v-for="office in offices" class="flex border-b border-40">
            <div class="w-1/5 px-8 py-6">
                <label class="inline-block text-80 pt-2 leading-tight">
                    Office: {{ office.name }}
                    <span class="text-danger text-sm">*</span>
                </label>
            </div>
            <div class="py-6 px-8 w-1/2">
                <select 
                    @change="(event) => {
                        setManager(office.id,event)
                    }"
                    :class="(typeof errors!=='undefined' && typeof errors.errors!=='undefined' && typeof errors.errors.offices_managers!=='undefined' && lodash.find(errors.errors.offices_managers, e => (e == office.id.toString()))) ? 'w-full form-control form-input form-input-bordered border-danger': 'w-full form-control form-input form-input-bordered'">
                    <option>Select Account Manager</option>
                    <option :selected="isSelected(office.id,manager.id)" v-if="typeof office.managers!=='undefined' && office.managers!=='' && office.managers.length > 0" v-for="manager in office.managers" :value="manager.id">
                        {{
                            manager.name
                        }} - {{
                            manager.email
                        }}
                    </option>
                </select>
                <div :class="(typeof errors!=='undefined' && typeof errors.errors!=='undefined' && typeof errors.errors.offices_managers!=='undefined' && lodash.find(errors.errors.offices_managers, e => (e == office.id.toString()))) ? 'help-text help-text mt-2 text-danger' : 'help-text help-text mt-2'">
                    {{
                        (typeof errors!=='undefined' && typeof errors.errors!=='undefined' && typeof errors.errors.offices_managers!=='undefined' && lodash.find(errors.errors.offices_managers, e => (e == office.id.toString()))) ? 'You need to assign an account manager to ' + office.name : ''
                    }}
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .parent-selection {
    }
</style>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import JQuery from 'jquery'
import _ from 'lodash'
window.$ = JQuery

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],
    data() {
        return {
            offices: [],
            token: null,
            placeholder: '----Select Here----',
            link: '',
            base_url: this.field.baseUrl || '',
            options: [],
            is_progress: false,
            matched_office: '',
            managers: 'test',
            lodash: (typeof _!=='undefined') ? _ : undefined,
            errorViews: {},
            //offices_managers: [],
            offices_managers: JSON.parse(this.field.offices_managers) || [],
            first_load: false

        };
    },
    updated() {
        console.log('updated')
    },
    mounted(){
        //load all office
        this.loadAllOffice()
    },
    methods: {
        isSelected(office_id, manager_id) {
            let f = _.findIndex(this.offices_managers, {
                office_id,
                manager_id
            })
            return (f==-1) ? '' : 'selected'
        },
        setManager(office_id, e) {
            
            let manager_id = e.target.value
            
            let f = _.findIndex(this.offices_managers, {
                office_id
            })

            if (f==-1) {
                this.offices_managers.push({
                    office_id,
                    manager_id: parseInt(manager_id)
                })
            } else {
                this.offices_managers[f].office_id = office_id
                this.offices_managers[f].manager_id = parseInt(manager_id)
            }

        },
        loadAllOffice() {
            
            fetch(`${this.base_url}/custom/shifl-offices`)
            .then(this.handleResponse)
            .then( response => {
                let { results } = response
                this.offices = results

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
            //let value = JSON.stringify(this.offices_managers)
            //formData.append('offices_managers', value)
            //formData.append('managers', 'not-need')
            formData.append('offices_managers', JSON.stringify(this.offices_managers))
            //let account_manager_val = $('#managers').val();
            
            //if(account_manager_val.length>0) {
               // formData.append('account_manager_ids',account_manager_val);
              //  formData.append('managers',account_manager_val);
            //}
            //formData.append('managers', this.custom_managers)
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            //console.log('handle change ' + value)
            //this.value = value;
        },
    },
}
</script>

<style>
.hide-account-manager-select {
    display: none;
}

</style>
