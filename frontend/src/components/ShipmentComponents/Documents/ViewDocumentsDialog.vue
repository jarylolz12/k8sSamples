<!-- @format -->

<template>
	<div>
		<v-dialog
			v-model="dialog"
			width="700px"
			@click:outside="close"
			content-class="view-document-dialog"
			persistent
			scrollable
		>
			<v-card class="view-document-card">
				<v-card-title class="view-document-title">
					<div class="headline">
						<p class="file-name mb-0">
							<span>{{ documentData.name }}</span>
							<img
								@click="openUpdateDialog(documentData)"
								src="@/assets/icons/edit-blue.svg"
								width="16px"
								height="16px"
								class="ml-2"
							/>
						</p>
						<p class="file-details mb-0" v-if="documentData.updated_by_name">
							{{
								documentData.updated_by_name
									? documentData.updated_by_name.company_name
									: "N/A"
							}}, {{ formatDate(documentData.updated_at) }}
						</p>
						<p class="file-details mb-0">
							{{ documentData.type }},
							<span
								v-if="
									documentData.supplier_ids &&
										documentData.supplier_ids.length === 1
								"
							>
								<span
									v-for="(supplier, index) in documentData.supplier_ids"
									:key="index"
								>
									{{ supplier.company_name }}
								</span>
							</span>
							<span v-else>Multiple</span>
						</p>
					</div>
					<div class="file-details"></div>

					<div class="header-actions">
						<button
							class="btn-blue btn-edit"
							@click="download(documentData.url)"
						>
							<img
								src="@/assets/images/download-white.svg"
								width="16px"
								height="16px"
							/>
						</button>

						<!-- <button
							class="btn-white btn-edit ml-2"
							@click="sendDocument(documentData)"
						>
							Send
						</button> -->

						<button icon dark class="btn-close" @click="close">
							<v-icon>mdi-close</v-icon>
						</button>
					</div>
				</v-card-title>
				<v-card-text class="view-document-content">
					<pdf :src="pdf"></pdf>
				</v-card-text>
			</v-card>
		</v-dialog>

		<UpdateDocumentsDialog
			:shipment_id="shipment_id"
			:editItem.sync="editItem"
			:dialogData.sync="dialogUpdateDocs"
			@close="dialogUpdateDocs = false"
			@fetchDocuments="fetchShipmentDocumentsAfter"
		/>
	</div>
</template>

<script>
import pdf from "vue-pdf";
import UpdateDocumentsDialog from "../Documents/UpdateDocumentsDialog.vue";
import moment from "moment";
export default {
	name: "ViewDocumentsDialog",
	props: ["dialogData", "documentData"],
	components: { pdf, UpdateDocumentsDialog },
	data: () => ({
		sendDocumentDialog: false,
		selectedDocument: [],
		editItem: {
			id: 0,
			name: "",
			type: "",
			supplier_ids: [],
		},
		dialogUpdateDocs: false,
		shipment_id: "",
	}),
	methods: {
		close() {
			this.$emit("close");
		},
		formatDate(date) {
			return moment(date).format(" h:mm A DD/MM/YY");
		},
		download(url) {
			window.location.href = `${
				this.getBetaUrl
			}download?link=${encodeURIComponent(url)}`;
		},
		sendDocument(item) {
			this.sendDocumentDialog = true;
			this.selectedDocument.push(item);
		},
		openUpdateDialog(item) {
			this.shipment_id = item.shipment_id;
			this.$store.dispatch("documents/setEditId", item.id);
			this.editItem = {
				id: item.id,
				name: item.name,
				supplier_ids: item.supplier_ids[0],
				shipment_id: item.shipment_id,
				type: item.type,
			};
			this.$store.dispatch("documents/setEditDocument", item);
			this.dialogUpdateDocs = true;
		},
		async fetchShipmentDocumentsAfter(payload) {
			this.$store.dispatch("fetchShipmentDocuments", payload);
			this.close();
		},
	},
	computed: {
		dialog: {
			get() {
				return this.dialogData;
			},
			set(value) {
				this.$emit("update:dialogData", value);
			},
		},
		pdf() {
			let url = process.env.VUE_APP_BASE_URL.slice(0, -3);
			let imageUrl = `${url}storage/`;

			return `${imageUrl}${this.documentData.url}`;
		},
		getBetaUrl() {
			let betaUrl = this.$store.getters["page/getBetaUrl"];
			betaUrl = betaUrl.replace("api", "");
			return betaUrl;
		},
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/shipment/viewDocumentDialog.scss";
</style>
