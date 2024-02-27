<template>
	<div>
		<v-dialog v-if="!isMobile" @click:outside="clickOutside" v-model="show" max-width="480px" :content-class="className">
			<v-card class="add-shipment-dialog-success">
				<v-card-title class="headline">
					<slot name="title"></slot>
				</v-card-title>
				<v-card-text class="pb-0 pt-4">
					<h2>
						{{ title }}
					</h2>
					<p>
						{{
							message
						}}
					</p>
				</v-card-text>
				<v-card-actions>
					<slot name="actions" v-bind:footer="{close: close }"></slot>
                </v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<style lang="scss">
@import "./scss/addShipmentModal.scss";
</style>
<script>
export default {
	name: 'BookingRequestSubmittedModal',
	props: ['isMobile', 'show', 'className', 'submitShipmentId', 'closeNoConnectedSupplierModalRole'],
	data: () => ({
	}),
    computed: {
        title() {
            return this.closeNoConnectedSupplierModalRole === 'shipper' ? 'No connected Supplier' : 'No connected Buyer'
        },
        message() {
            let staticMessage = `Please make sure that you are connected to a ${(this.closeNoConnectedSupplierModalRole === 'shipper') ? 'supplier' : 'buyer'}.`
            return staticMessage
        }
    },
	methods:{
        viewDetails() {
            this.$router.push(`/shipment/${this.submitShipmentId}`)
        },
		clickOutside() {
			this.$emit('close')
		},
		close() {
            this.$emit('update:bookingShipmentDialogView', true)
			this.$emit('close')
		}
	}
}
</script>