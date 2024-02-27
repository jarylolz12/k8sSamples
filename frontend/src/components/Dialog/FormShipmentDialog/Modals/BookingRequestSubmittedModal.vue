<template>
	<div>
		<v-dialog v-if="!isMobile" @click:outside="clickOutside" v-model="show" max-width="480px" :content-class="className">
			<v-card class="add-shipment-dialog-success">
				<v-card-title class="headline">
					<slot name="title"></slot>
				</v-card-title>
				<v-card-text class="pb-0">
					<h2>
						{{ "Booking Request Submitted" }}
					</h2>
					<p>
						{{
							"We have received your booking request. Our team will review your request and get back with a quote."
						}}
					</p>
				</v-card-text>
				<v-card-actions>
					<slot name="actions" v-bind:footer="{close: close, addAnotherShipment: addAnotherShipment, closeSubmitRequest: closeSubmitRequest, viewDetails: viewDetails }"></slot>
                </v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<style lang="scss">
@import "./scss/bookingRequestSubmittedModal.scss";
</style>
<script>
export default {
	name: 'BookingRequestSubmittedModal',
	props: ['isMobile', 'show', 'className', 'submitShipmentId'],
	data: () => ({
	}),
	methods:{
        viewDetails() {
            window.location.href = `/shipment/${this.submitShipmentId}`
            //this.$router.push(`/shipment/${this.submitShipmentId}`)
        },
		clickOutside() {
			this.$emit('close')
		},
        closeSubmitRequest() {
            this.$emit('update:show', false)
            //this.$emit('reloadShipments')
            window.location.href = '/shipments'
        },
		addAnotherShipment() {
			this.$emit('addAnotherShipment')
		},
		close() {
			this.$emit('close')
		}
	}
}
</script>