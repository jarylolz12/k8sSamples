<!-- @format -->

<template>
	<v-dialog
		v-model="dialogDelete"
		max-width="500px"
		content-class="item-delete-dialog"
		persistent
	>
		<v-card class="delete-dialog">
			<v-card-title class="headline">
				<div class="delete-icon mt-3 mb-1">
					<img src="../../assets/icons/icon-delete.svg" alt="" />
				</div>
			</v-card-title>

			<v-card-text style="padding-bottom: 15px;">
				<h2>Delete {{ componentName }}</h2>
				<p v-if="fromComponent !== 'card'">
					<span v-if="fromComponent !== 'purchase order'"
						>Do you want to delete the {{ fromComponent }} ‘‘{{
							editedItem !== null && editedItem.name !== null
								? editedItem.name
								: ""
						}}’’? Once deleted, this cannot be undone.</span
					>
					<span v-else
						>Do you want to delete the selected {{ fromComponent }}(s)? Once
						deleted, this cannot be undone.</span
					>
				</p>

				<p v-else>
					Do you want to delete the {{ fromComponent }} ‘‘{{
						editedItem !== null && editedItem.name !== null
							? editedItem.name
							: ""
					}}’’ from your payment methods?
				</p>
			</v-card-text>

			<v-card-actions class="delete-btn-wrapper">
				<v-btn
					class="btn-blue"
					text
					@click="deleteItemConfirm"
					:disabled="loadingDelete"
				>
					<span v-if="loadingDelete">Deleting...</span>
					<span v-if="!loadingDelete">Delete</span>
				</v-btn>

				<v-btn
					class="btn-white"
					text
					@click="closeDelete"
					:disabled="loadingDelete"
				>
					Cancel
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name: "DeleteACHDialog",
	props: [
		"editedItemData",
		"dialogData",
		"fromComponent",
		"loadingDelete",
		"componentName",
		"editedIndexWarehouse",
		"defaultItemWarehouse",
	],
	mounted() {},
	updated() {},
	methods: {
		deleteItemConfirm() {
			this.$emit("delete");
		},
		closeDelete() {
			this.$emit("close");
		},
	},
	computed: {
		dialogDelete: {
			get() {
				return this.dialogData;
			},
			set(value) {
				if (!value) {
					//this.$emit('update:editedIndexWarehouse', -1)
					this.$emit("update:dialogData", false);
				} else {
					this.$emit("update:dialogData", true);
				}
			},
		},
		editedItem: {
			get() {
				return this.editedItemData;
			},
			set(value) {
				console.log(value);
			},
		},
	},
};
</script>

<style lang="scss">
@import "../../assets/scss/buttons.scss";
@import "../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../assets/scss/pages_scss/dialog/deleteDialog.scss";
</style>
