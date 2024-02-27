<template>
	<v-dialog @click:outside="clickOutside" v-model="show" max-width="500px" content-class="item-delete-dialog">
		<v-card class="delete-dialog">
			<v-card-title class="headline">
				<div class="delete-icon mt-3 mb-1">
					<img src="../../assets/icons/icon-delete.svg" alt="">
				</div>
			</v-card-title>
			<v-card-text style="padding-bottom: 15px;">
				<h2>
					{{
						title
					}}
				</h2>
				<slot name="message" v-bind:ref_num="ref_num"></slot>
			</v-card-text>
			<v-card-actions class="delete-btn-wrapper">
				<v-btn @click.stop="deleteShipment" class="delete-btn" text >
					<span >{{ (deleting) ? 'Deleting...' : 'Delete' }}</span>
				</v-btn>
				<v-btn class="delete-cancel btn-white" text @click="close">
					<span>Cancel</span>
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name: 'DeleteShipmentDialog',
	props: ['show', 'message', 'title', 'ref_num', 'id', 'deleting'],
	mounted() {
		
	},
	methods: {
		clickOutside() {
			this.$emit('close')
		},
		deleteShipment() {
			this.$emit('deleteShipment', this.id)
		},
		close() {
			this.$emit('close')
		},
	},
}
</script>

<style lang="scss">
@import '../../assets/scss/buttons.scss';
@import '../../assets/css/dialog_styles/deleteDialog.css';
</style>