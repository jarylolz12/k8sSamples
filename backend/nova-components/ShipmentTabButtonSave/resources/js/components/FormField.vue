<template>
    <div>
    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="trucker" class="inline-block text-80 pt-2 leading-tight">
                Trucker
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            <v-select v-model="trucker" :reduce="trucker => trucker.value" :value="trucker" :options="options" label="label" @search="fetchOptions"></v-select>
            <!---->
            <input type="hidden" id="trucker" dusk="trucker" name="trucker" :value="trucker" />
            <div class="help-text help-text mt-2">
            </div>
        </div>
    </div>
    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="trucker" class="inline-block text-80 pt-2 leading-tight">
                Delivery Notes
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            <textarea v-model="trucker_custom_note" rows="5" placeholder="Notes" :class="`w-full form-control form-input form-input-bordered py-3 h-auto ${typeof errors.trucker_custom_note!='undefined' ? 'border-danger' : ''}`"></textarea>
            <input dusk="trucker_custom_note" id="trucker_custom_note" type="hidden" name="trucker_custom_note" :value="trucker_custom_note" />
            <!---->
            <div v-show="typeof errors.trucker_custom_note!='undefined'" class="help-text help-text mt-2">
                
            </div>
        </div>
    </div>
    <div v-show="resourceId!='' && resourceId!=null" class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
        </div>
        <div class="py-6 px-8 w-1/2">
            <button v-show="loading" disabled class="btn btn-default btn-primary inline-flex items-center relative" dusk="create-button">
                <span class="">
                    Saving...
                </span>
            </button>
           <button v-show="!loading" @click="e => saveDeliveryOrder(e)" class="btn btn-default btn-primary inline-flex items-center relative" dusk="create-button">
                <span class="">
                    Save
                </span>
            </button>
        </div>
    </div>
    </div>
</template>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import jQuery from 'jQuery'
import _ from 'lodash'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field', 'panels'],
    data() {
        return {
            trucker: null,
            loading: false,
            loadingText: 'Saving',
            buttonText: 'Save',
            trucker_custom_note: null,
            id: null,
            options: [],
            errors: {}
        }
    },
    updated() {
    },
    async mounted() {
        this.trucker = this.field.trucker_id
        this.id = this.field.id
        this.trucker_custom_note = this.field.trucker_custom_note,
        this.options = this.field.truckers
    },
    methods: {
        saveDeliveryOrder(e) {
            e.preventDefault()
            if (!this.loading) {
                this.loading = true
                this.buttonText = 'Saving...'
                const token = jQuery('meta[name="csrf-token"]').attr('content')
                const requestOptions = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    trucker: this.trucker,
                    trucker_custom_note: this.trucker_custom_note,
                    _token: token,
                    id: this.id
                })
                }

                fetch(`${this.field.baseUrl}/custom/truckers/save`, requestOptions)
                .then(this.handleResponse)
                .then(response => {
                    console.log(response)
                    let { status, messages } = response

                    if ( status == 'ok' ) {
                        Nova.success(
                            'Delivery Order successfully updated.'
                        )
                    } else {
                        this.errors = messages
                    }
                    this.loading = false
                    this.buttonText = 'Save'
                })
            }
            
            
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
        fetchOptions(search, loading) {
            loading(true)
            const requestOptions = {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
            }
            const token = jQuery('meta[name="csrf-token"]').attr('content')
            fetch(`${this.field.baseUrl}/custom/truckers/search?search=${search}&token=${token}`, requestOptions).then(this.handleResponse).then(response => {
                loading(false)
                this.options = response.results
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
            formData.append('trucker_custom_note', this.trucker_custom_note || '')
            formData.append('trucker', this.trucker)
           // formData.append(this.field.attribute, this.value || '')
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
