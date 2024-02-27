<template>
    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label :for="field.attribute" class="inline-block text-80 pt-2 leading-tight">{{field.name}}</label>
        </div>
        <div class="py-6 px-8 w-4/5">
            <div class="flex w-full">
                <div class="mr-3">
                    <input
                        :disabled="isBoxDisabled"
                        type="checkbox"
                        class="checkbox mt-2"
                        :id="field.attribute"
                        :name="field.name"
                        @click="onClickCheckbox"
                        :checked="trueValue"
                        ref="containerloadedcheckbox"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],
    data() {
        return {
            trueValue: this.field.is_containers_loaded_auto ? this.field.is_containers_loaded_auto : this.field.value || 0
        };
    },
    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
          this.value = this.field.is_containers_loaded_auto ? this.field.is_containers_loaded_auto : this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        onClickCheckbox: function (e) {
            if(!this.field.readonly){
                this.trueValue = this.trueValue ? 0 : 1;
            } else {
                e.preventDefault()
                return false
            }
        },
    },
    computed:{
        isBoxDisabled() {
            return this.field.is_containers_loaded_auto == 1;
        },
    }
}
</script>
