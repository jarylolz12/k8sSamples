<template>
	<div>
		<v-dialog v-if="!isMobile" @click:outside="clickOutside" v-model="show" max-width="700px" :content-class="className">
			<v-card class="add-shipment-dialog-success">
				<v-card-title class="headline">
					<slot name="title"></slot>
				</v-card-title>
				<v-card-text class="pb-0">
					<h2>
						Shipments Added for Tracking
					</h2>
					<p class="pb-0 mb-2">
						You have successfully added {{ shipmentCounter.length }} shipments for tracking. You can add further information for each shipment separately from Shipment Details if needed.
					</p>
					<div class="shipments-ref-listing">
						<p class="pb-0 mb-2"><b>Added shipments are: </b></p>
						<v-row>
							<div
								class="shipment-refs mb-2"
								v-for="(item, i) in shipmentCounter" :key="i"
								style="padding: 10px;"
							>
								<a :href="'/shipment/'+item.substring(1)" target="__blank" class="shifl-ref-link">#{{ item }} <v-icon>mdi-open-in-new </v-icon></a>
							</div>
						</v-row>
					</div>
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
	name: 'BulkShipmentAddedModal',
	props: ['isMobile', 'show', 'className', 'shipmentCounter'],
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