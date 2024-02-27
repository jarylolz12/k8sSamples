<template>
    <div class="flex border-b border-40">
        <div class="w-1/5 px-8 py-6">
            <label :for="field.attribute" class="inline-block text-80 pt-2 leading-tight">{{field.name}}</label>
        </div>
        <div class="py-6 px-8 w-4/5">
            <div class="flex w-full">
                <div class="mr-3">
                    <input
                        type="checkbox"
                        class="checkbox mt-2"
                        :id="field.attribute"
                        :name="field.name"
                        @click="onClickCheckbox"
                        :checked="trueValue"
                        ref="vcheckbox"
                    />
                    <shipment-cancel-mbl-removed-modal
                        v-if="showDeleteConfirmation"
                        @close="showDeleteConfirmation = false"
                    />
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import ShipmentCancelMblRemovedModal from './ShipmentCancelMblRemovedModal'

export default {
    mixins: [FormField, HandlesValidationErrors],
    props: ['resourceName', 'resourceId', 'field', 'mbl_num'],
    components: { ShipmentCancelMblRemovedModal },
    data() {
        return {
            trueValue: this.field.value || 0,
            showDeleteConfirmation: false,
        };
    },
    methods: {

        fill(formData) {
            formData.append(this.field.attribute, this.trueValue);
        },

        onClickCheckbox: function (e) {
            if(!this.field.readonly){
                this.trueValue = this.trueValue ? 0 : 1;
                //console.log(this.trueValue)
                if(this.trueValue == 1 && (this.field.mbl_num != null && this.field.mbl_num != undefined && this.field.mbl_num != '')){
                    this.showDeleteConfirmation = true;
                    this.$refs.vcheckbox.click()
                }
            } else {
                e.preventDefault()
                return false
            }
        },

    }
}
</script>
