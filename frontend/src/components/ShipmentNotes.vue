<!-- @format -->

<template>
	<div class="shipment-notes-wrapper" v-resize="onResize">
		<div class="notes-header-wrapper" v-if="getShipmentNotes.length !== 0">
			<h3 class="notes-title-wrapper">Notes</h3>
			<v-btn class="btn-blue" @click="addNoteDialog" text>
				+ Add Note
			</v-btn>
		</div>

		<div class="notes-info-wrapper pa-3" v-if="!isMobile">
			<v-expansion-panels
				class="pa-1"
				v-for="(note, index) in notes"
				:key="index"
			>
				<v-expansion-panel>
					<v-expansion-panel-header class="note-panel-header">
						<template v-slot:default="{ open }">
							<v-row no-gutters class="align-center">
								<v-col cols="4" class="note-title">
									<!-- <span
										class="private-label text--secondary"
									>
										<img
											src="../assets/images/lock.svg"
											alt=""
											class="private-lock-img"
										/>
										Private</span
									> -->
									{{ note.subject }}
								</v-col>
								<v-col cols="8" class="text--secondary">
									<v-fade-transition leave-relative>
										<ul v-if="open" key="0" class="action-icons">
											<li>
												<v-btn
													class="btn-white icons mr-1"
													min-width="30px"
													@click="editNote(note)"
												>
													<img src="../assets/icons/edit-blue.svg" alt="" />
												</v-btn>
											</li>
											<li>
												<v-btn
													class="btn-white icons"
													min-width="30px"
													@click="deleteNoteDialog(note.id)"
												>
													<img src="../assets/icons/delete-po.svg" alt="" />
												</v-btn>
											</li>
										</ul>
									</v-fade-transition>
								</v-col>
							</v-row>
						</template>
					</v-expansion-panel-header>
					<v-expansion-panel-content class="note-panel-content">
						<p class="note-text">
							{{ note.description }}
						</p>
						<div class="note-info mt-2">
							<a href="#" class="note-writer">{{ note.createdBy }}</a>
							<v-icon>mdi-circle-small</v-icon>
							<span class="note-time-date">{{
								noteDate(note.created_at)
							}}</span>
						</div>
					</v-expansion-panel-content>
				</v-expansion-panel>
			</v-expansion-panels>
		</div>
		<div class="notes-info-wrapper pa-2 " v-if="isMobile">
			<v-expansion-panels
				class="py-1"
				v-for="(note, index) in notes"
				:key="index"
			>
				<v-expansion-panel>
					<v-expansion-panel-header class="note-panel-header">
						<template v-slot:default="{ open }">
							<v-row no-gutters class="align-center">
								<v-col cols="8" class="note-title">
									<!-- <span
										class="private-label text--secondary"									>
										<img
											src="../assets/images/lock.svg"
											alt=""
											class="private-lock-img"
										/>
										Private</span
									> -->
									{{ note.subject }}
								</v-col>
								<v-col cols="4" class="text--secondary">
									<v-fade-transition leave-relative>
										<ul v-if="open" key="0" class="action-icons">
											<li class="mr-2">
												<v-menu
													bottom
													offset-y
													left
													content-class="outbound-lists-menu"
												>
													<template v-slot:activator="{ on, attrs }">
														<v-btn
															class="btn-white"
															icon
															v-bind="attrs"
															v-on="on"
														>
															<v-icon>mdi-dots-horizontal</v-icon>
														</v-btn>
													</template>

													<v-list class="outbound-lists">
														<v-list-item @click="editNote(note)">
															<v-list-item-title style="color: #0171A1;">
																Edit
															</v-list-item-title>
														</v-list-item>

														<v-list-item @click="deleteNoteDialog(note.id)">
															<v-list-item-title class="cancel">
																Delete
															</v-list-item-title>
														</v-list-item>
													</v-list>
												</v-menu>
											</li>
										</ul>
									</v-fade-transition>
								</v-col>
							</v-row>
						</template>
					</v-expansion-panel-header>
					<v-expansion-panel-content class="note-panel-content">
						<p class="note-text">
							{{ note.description }}
						</p>
						<div class="note-info mt-2">
							<a href="#" class="note-writer">{{ note.createdBy }}</a>
							<v-icon>mdi-circle-small</v-icon>
							<span class="note-time-date">{{
								noteDate(note.created_at)
							}}</span>
						</div>
					</v-expansion-panel-content>
				</v-expansion-panel>
			</v-expansion-panels>
		</div>

		<div
			class="loading-wrapper mt-4 text-center"
			v-if="getShipmentNotes.length == 0 && getShipmentNotesLoading"
		>
			<v-progress-circular :size="40" color="#0171a1" indeterminate>
			</v-progress-circular>
		</div>

		<div class="no-notes-wrapper" v-if="!getShipmentNotesLoading && getShipmentNotes.length == 0">
			<div class="no-notes-heading">
				<h3 class="text-center">No Notes</h3>
				<p class="text-center">This shipment doesn’t have any notes yet.</p>
			</div>

			<v-btn class="btn-white" @click="addNoteDialog" text>
				Add Note
			</v-btn>
		</div>
		
		<AddNoteDialog
			:dialogData.sync="dialog"
			@addnote="addnote"
			@close="close"
			:editedIndexData.sync="editedIndex"
			:editedItemData.sync="editedItem"
		/>
		<DeleteNoteDialog
			:dialogData.sync="deleteDialog"
			:shipmentNoteId="shipmentNoteId"
			@deleteNote="deleteNote"
			@close="closeDeleteDialog"
		/>
	</div>
</template>

<script>
import AddNoteDialog from "../components/ShipmentComponents/Notes/AddNotesDialog.vue";
import DeleteNoteDialog from "../components/ShipmentComponents/Notes/DeleteNoteDialog.vue";
import { mapActions, mapGetters } from "vuex";
import moment from "moment";
import globalMethods from "../utils/globalMethods";

export default {
	components: {
		AddNoteDialog,
		DeleteNoteDialog,
	},
	props: ["id"],
	mounted() {
		this.fetchShipmentNotes(this.id);
	},
	data: () => ({
		isMobile: false,
		dialog: false,
		buttonAction: false,
		editedIndex: -1,
		editedItem: {
			subject: "",
			description: "",
		},
		defaultItem: {
			subject: "",
			description: "",
		},
		deleteDialog: false,
		shipmentNoteId: "",
	}),
	computed: {
		...mapGetters({
			getShipmentNotes: "getShipmentNotes",
			getShipmentNotesLoading: "getShipmentNotesLoading",
		}),
		notes() {
			return this.getShipmentNotes;
		},
	},
	methods: {
		...mapActions({
			fetchShipmentNotes: "fetchShipmentNotes",
			createShipmentNotes: "createShipmentNotes",
			updateShipmentNotes: "updateShipmentNotes",
			deleteShipmentNotes: "deleteShipmentNotes",
		}),
		...globalMethods,
		noteDate(date) {
			return moment(date).format(" h:mmA, MMMM DD, YYYY");
		},
		addNoteDialog() {
			this.dialog = true;
		},
		async addnote(data) {
			let { note, subject, editedIndexData, noteId } = data;
			let createParms = {
				note,
				subject,
				shipmentId: this.id,
			};
			let updateParms = {
				note,
				subject,
				shipmentId: this.id,
				noteId,
			};

			if (editedIndexData == -1) {
				await this.createShipmentNotes(createParms);
				this.notificationMessage("Notes has been created.");
				await this.fetchShipmentNotes(this.id);
				this.close();

			} else {
				await this.updateShipmentNotes(updateParms);
				this.notificationMessage("Notes has been updated.");
				await this.fetchShipmentNotes(this.id);
				this.close();
				
			}
		},
		editNote(item) {
			this.editedIndex = this.notes.indexOf(item);
			this.editedItem = Object.assign({}, item);
			this.dialog = true;
		},
		deleteNoteDialog(id) {
			this.shipmentNoteId = id;
			this.deleteDialog = true;
		},
		async deleteNote(noteId) {
			await this.deleteShipmentNotes(noteId);
			this.notificationCustom("Notes has been deleted.");
			this.fetchShipmentNotes(this.id);
			this.closeDeleteDialog();
		},
		close() {
			this.dialog = false;
			this.$nextTick(() => {
				this.editedItem = Object.assign({}, this.defaultItem);
				this.editedIndex = -1;
			});
		},
		closeDeleteDialog() {
			this.deleteDialog = false;
		},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
	},
};
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/shipment/shipmentNotes.scss";
</style>
