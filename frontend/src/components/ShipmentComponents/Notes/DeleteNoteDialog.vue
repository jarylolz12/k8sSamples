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
					<img src="../../../assets/icons/icon-delete.svg" alt="" />
				</div>
			</v-card-title>

			<v-card-text style="padding-bottom: 15px;">
				<h2>Delete Note</h2>
				<p>
					<span
						>Do you want to delete the selected note? Once deleted, a note
						cannot be retrieved.</span
					>
				</p>
			</v-card-text>

			<v-card-actions class="delete-btn-wrapper">
				<v-btn class="btn-blue" @click="deleteNote" text :disabled="getDeleteShipmentNotesLoading">
					<span>
						<!-- Delete -->
						{{ getDeleteShipmentNotesLoading ? 'Deleting...' : 'Delete' }}
					</span>
				</v-btn>

				<v-btn class="btn-white" text @click="closeDelete" :disabled="getDeleteShipmentNotesLoading">
					Cancel
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
	name: "DeleteNoteDialog",
	props: ["dialogData", "shipmentNoteId"],
	methods: {
		deleteNote() {
			this.$emit("deleteNote", this.shipmentNoteId);
		},
		closeDelete() {
			this.$emit("close");
		},
	},
	computed: {
		...mapGetters({
			getDeleteShipmentNotesLoading:"getDeleteShipmentNotesLoading"
		}),
		dialogDelete: {
			get() {
				return this.dialogData;
			},
			set(value) {
				this.$emit("update:dialogData", value);
			},
		},
	},
};
</script>

<style lang="scss">
@import "../../../assets/scss/buttons.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/pages_scss/dialog/deleteDialog.scss";
</style>
