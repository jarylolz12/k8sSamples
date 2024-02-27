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
                    <shipment-so-received-confirmation-modal
                        v-if="showDeleteConfirmation"
                        @close="showDeleteConfirmation = false"
                        :office_from="field.office_from"
                    />
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import { FormField, HandlesValidationErrors } from 'laravel-nova'
    import ShipmentSoReceivedConfirmationModal from "./ShipmentSoReceivedConfirmationModal";

    export default {
        mixins: [FormField, HandlesValidationErrors],
        props: ['resourceName', 'resourceId', 'field', 'so_received', 'is_agent_booking_confirm', 'shipment', 'office_from'],
        components: { ShipmentSoReceivedConfirmationModal },
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
                let filteredSchedule = this.field.shipment.schedules_group.filter(schedule => schedule.is_confirmed == 1);

                if(!this.field.readonly){
                    this.trueValue = this.trueValue ? 0 : 1;
                    if(this.trueValue == 1){
                        if (
                            typeof filteredSchedule === 'object' &&
                            Array.isArray(filteredSchedule) &&
                            filteredSchedule !== null &&
                            filteredSchedule.length > 0 &&
                            (filteredSchedule[0].is_agent_booking_confirm == '0' || filteredSchedule[0].is_agent_booking_confirm === undefined )
                        ) {
                            this.showDeleteConfirmation = true;
                            if(this.field.is_agent_booking_confirm == '0')
                                this.$refs.vcheckbox.click()
                        }
                    }
                } else {
                    e.preventDefault()
                    return false
                }
            },

        }
    }
</script>
