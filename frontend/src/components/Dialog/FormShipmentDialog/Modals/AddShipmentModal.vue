<template>
	<div>
		<v-dialog v-if="!isMobile" @click:outside="clickOutside" v-model="show" max-width="480px" :content-class="className">
			<v-card class="add-shipment-dialog-success">
				<v-card-title class="headline">
					<slot name="title"></slot>
				</v-card-title>
				<v-card-text class="pb-0">
					<h2>
						{{ "Shipment Added" }} #{{ refNumber }}
					</h2>
					<p>
						{{
							"Your shipment has successfully been added for tracking. You will find it on the shipment list."
						}}
					</p>
				</v-card-text>
				<v-card-actions>
					<slot name="actions" v-bind:footer="{close: close, addAnotherShipment: addAnotherShipment, closeRefresh: closeRefresh }"></slot>
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
	name: 'AddShipmentDialogModal',
	props: ['isMobile', 'show', 'className', 'refNumber'],
	methods:{
        closeRefresh() {
            window.location.reload()
            this.close()
        },
		clickOutside() {
			this.$emit('close')
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