<!-- @format -->

<template>
	<v-dialog
		v-model="dialog"
		max-width="824px"
		content-class="add-note-dialog"
		:retain-focus="false"
		@click:outside="close"
	>
		<v-card>
			<v-form ref="form" action="#" @submit.prevent="">
				<v-card-title>
					<span class="headline">{{ formTitle }}</span>

					<button icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text>
					<div class="add-note-wrapper">
						<div class="add-note-info">
							<v-row>
								<v-col cols="12">
									<div class="card-name mb-3">
										<p class="card-title">Title</p>
										<v-text-field
											v-model="editedItem.subject"
											color="#002F44"
											dense
											class="text-fields select-items"
											placeholder="Enter note title"
											outlined
											type="text"
											hide-details="auto"
										>
										</v-text-field>
									</div>
								</v-col>

								<v-col cols="12">
									<div class="card-name mb-3">
										<p class="card-title">Note</p>
										<v-textarea
											v-model="editedItem.description"
											rows="3"
											row-height="25"
											auto-grow
											color="#002F44"
											dense
											class="text-fields select-items"
											placeholder="Write your note here"
											outlined
											type="number"
											hide-details="auto"
											:maxlength="2000"
										>
										</v-textarea>
									</div>
								</v-col>

								<!-- <v-col cols="12">
									<div class="card-name">
										<v-radio-group row>
											<label class="text-item-label mb-0 mr-3">Privacy</label>
											<v-radio
												class="text-label"
												label="Public"
												value="Public"
											></v-radio>
											<v-radio
												class="text-label"
												label="Private"
												value="Private"
											></v-radio>
										</v-radio-group>
									</div>
								</v-col> -->
							</v-row>
						</div>
					</div>
				</v-card-text>

				<v-card-actions>
					<button
						class="btn-blue"
						@click="save"
						:disabled="
							(this.editedItem.description == '' || this.editedItem.subject == '') || 
							getCreateShipmentNotesLoading || getUpdateShipmentNotesLoading 
						"
					>
						<span v-if="editedIndexData === -1">
							<!-- Add Note  -->
							{{ getCreateShipmentNotesLoading ? 'Adding...' : 'Add Note'}}
						</span>
						<span v-else>
							<!-- Save Changes -->
							{{ getUpdateShipmentNotesLoading ? 'Saving...' : 'Save Changes'}}
						</span>
					</button>

					<button class="btn-white" @click="close" 
						:disabled="getCreateShipmentNotesLoading || getUpdateShipmentNotesLoading">
						Cancel
					</button>
				</v-card-actions>
			</v-form>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
	name: "AddNotesDialog",
	props: ["dialogData", "editedIndexData", "editedItemData"],
	data: () => ({
		addNoteButton: false,
	}),
	methods: {
		save() {
			let data = {
				noteId: this.editedItem.id,
				subject: this.editedItem.subject,
				note: this.editedItem.description,
				editedIndexData: this.editedIndexData,
			};
			this.$emit("addnote", data);
		},
		close() {
			this.$emit("close");
		},
	},
	computed: {
		...mapGetters({
			getCreateShipmentNotesLoading:"getCreateShipmentNotesLoading",
			getUpdateShipmentNotesLoading:"getUpdateShipmentNotesLoading"
		}),
		dialog: {
			get() {
				return this.dialogData;
			},
			set(value) {
				this.$emit("update:dialogData", value);
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
		formTitle() {
			return this.editedIndexData === -1 ? "Add Note" : "Edit Note";
		},
	},
};
</script>
<style lang="scss">
@import "../../../assets/scss/pages_scss/shipment/addNotesDialog.scss";
</style>
