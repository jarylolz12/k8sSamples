<template>
<div>
    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="trucker" class="inline-block text-80 pt-2 leading-tight">
                Trucker
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            <div :class="`${typeof errors.trucker!='undefined' ? 'border-custom-danger' : ''}`">
                <v-select v-model="trucker" :reduce="trucker => trucker.value" :options="options" label="label" @search="fetchOptions"></v-select>
            </div>
            <!---->
            <input type="hidden" id="trucker" dusk="trucker" name="trucker" :value="trucker" />
            <div v-show="typeof errors.trucker!='undefined'" class="help-text help-text mt-2">
                <div style="color: red;" v-for="value in errors.trucker">
                    {{ value }}
                </div>
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
                <div style="color: red;" v-for="value in errors.trucker_custom_note">
                    {{ value }}
                </div>
            </div>
        </div>
    </div>
    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label for="copy_customer" class="inline-block text-80 pt-2 leading-tight">
                Copy Customer
                <!---->
            </label>
        </div>
        <div class="py-6 px-8 w-1/2">
            <input v-model="copy_customer" type="checkbox" :class="`checkbox mt-2`" true-value="1" false-value="0">
            <input dusk="copy_customer" id="copy_customer" type="hidden" name="copy_customer" :value="copy_customer" />
            <!---->
            <div v-show="typeof errors.copy_customer!='undefined'" class="help-text help-text mt-2">
                <div style="color: red;" v-for="value in errors.copy_customer">
                    {{ value }}
                </div>
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
<style type="text/css">
.border-custom-danger {
    border: 1px solid var(--danger) !important;
    border-radius: .5rem;
}
</style>
<script>
import {
    FormField,
    HandlesValidationErrors
} from 'laravel-nova'
import jQuery from 'jquery'
import _ from 'lodash'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field', 'panels'],
    data() {
        return {
            trucker: null,
            resourceUrlName: null,
            truckerModel: {
                value: this.field.trucker_id
            },
            loading: false,
            loadingText: 'Saving',
            buttonText: 'Save',
            trucker_custom_note: null,
            copy_customer: null,
            id: null,
            options: [],
            errors: {
                trucker_custom_note: '',
                trucker: '',
                copy_customer: ''
            }
        }
    },
    updated() {

    },
    async mounted() {
        let arrPath = window.location.pathname.split("/");
        this.resourceUrlName = arrPath[3];

        //alert(JSON.stringify(this.field.shipment.copy_customer))
        this.trucker = this.field.trucker_id
        this.id = this.field.id
        this.trucker_custom_note = this.field.trucker_custom_note,
            this.options = this.field.truckers,
            this.copy_customer = this.field.copy_customer
        console.log(this.field.copy_customer)
    },
    methods: {
        saveDeliveryOrder(e) {

            e.preventDefault()

            if (!this.loading) {
                this.errors = {}
                this.loading = true
                this.buttonText = 'Saving...'
                const token = jQuery('meta[name="csrf-token"]').attr('content')
                const requestOptions = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        trucker: this.trucker,
                        trucker_custom_note: this.trucker_custom_note,
                        copy_customer: this.copy_customer != true ? 0 : 1,
                        _token: token,
                        id: this.id
                    })
                }

                fetch(`${this.field.baseUrl}/custom/truckers/save`, requestOptions)
                    .then(this.handleResponse)
                    .then(response => {
                        let {
                            status,
                            messages
                        } = response

                        if (status == 'ok') {
                            Nova.success(
                                'Delivery Order fields successfully updated.'
                            )
                            // go to detail view delivery order tab after successfully updating.
                            this.$router.push(`/resources/${this.resourceUrlName}/${this.resourceId}?tab=delivery-order`)

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
                headers: {
                    'Content-Type': 'application/json'
                }
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

            let truckerValue = (this.trucker == null) ? 0 : this.trucker
            formData.append('trucker_custom_note', this.trucker_custom_note || '')
            formData.append('trucker', truckerValue)
            formData.append('copy_customer', this.copy_customer || '')
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
