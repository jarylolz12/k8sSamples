<template>
	<v-dialog v-model="show" max-width="500px" content-class="item-delete-dialog">
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
				<p>{{ message }}</p>
			</v-card-text>
			<v-card-actions class="delete-btn-wrapper">
				<v-btn class="delete-btn" text @click="close">
					<span>Understood</span>
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name: 'SpecialDialog',
	props: ['show', 'message', 'title'],
	mounted() {
		
	},
	methods: {
		close() {
			this.$emit('close')
		},
	},
}
</script>

<style>
@import '../../assets/css/dialog_styles/deleteDialog.css';
</style>