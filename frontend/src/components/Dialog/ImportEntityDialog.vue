<!-- @format -->

<template>
	<v-dialog
		persistent
		v-model="isOpen"
		max-width="700"
		:fullscreen="$vuetify.breakpoint.mdAndDown"
		scrollable
	>
		<v-card>
			<v-card-title class="text-h5 lighten-2">
				<span class="headline">
					{{ $attrs.title ? $attrs.title : `Upload Products` }}
				</span>
				<button icon dark class="btn-close" @click="closeDialog">
					<v-icon>mdi-close</v-icon>
				</button>
			</v-card-title>

			<template v-slot:progress>
				<v-progress-linear active indeterminate></v-progress-linear>
			</template>

			<v-card-text>
				<div
					v-if="!dialogType"
					class="mt-4 pa-3 d-md-flex justify-space-between align-center download-template"
				>
					<span class="text-caption">
						{{
							$attrs.templateText
								? $attrs.templateText
								: "You can import Products by uploading Excel or CSV file. To ensure accurate import, download and use this template:"
						}}
					</span>
					<v-btn
						:disabled="isDownloading"
						@click="downloadTemplate"
						small
						class="btn-white download-btn"
						color="primary"
					>
						{{ `${isDownloading ? "Downloading..." : "Download Template"}` }}
					</v-btn>
				</div>

				<div
					v-if="!file"
					class="mt-4 browse-or-drop d-flex justify-center align-center"
					@dragenter.prevent="dragPrompt"
					@dragleave.prevent="dragPrompt"
					@dragover.prevent="() => {}"
					@drop.prevent="selectFile"
				>
					<div>
						<div class="mb-2">
							{{
								!dialogType
									? "Brows or Drop one file here"
									: "Browse Or Drop file here"
							}}
						</div>
						<v-btn
							@click="triggerSelectFile"
							small
							class="btn-white mx-auto"
							color="primary"
						>
							<v-icon>mdi-upload</v-icon>
							Browse
						</v-btn>
					</div>
				</div>

				<div v-else>
					<div
						v-if="!errors"
						class="d-flex align-center files-div pa-3 overflow-y-auto"
						:class="dialogType ? 'file-selected-for-marked-as-payment' : ''"
					>
						<v-icon
							small
							dark
							class="file-icon rounded-circle pa-2 shifl white--text"
						>
							mdi-file-document
						</v-icon>
						<div class="d-flex align-center">
							<span class="ml-2 file-name">{{ file.name }}</span>
							<span class="ml-2 file-size">[{{ fileSize }}]</span>
							<v-btn @click="removeFile" icon color="red" :disabled="loading">
								<!-- <v-icon small>mdi-trash-can</v-icon> -->
								<img
									src="@/assets/icons/delete-po.svg"
									alt="delete"
									width="18px"
									height="18px"
								/>
							</v-btn>
						</div>
					</div>

					<div v-else class="re-upload-error-div pa-3">
						<!--file-->
						<div class="d-flex align-center mb-1">
							<v-icon
								dark
								class="file-icon rounded-circle pa-2 white red--text"
							>
								mdi-alert-circle-outline
							</v-icon>
							<div class="d-flex align-center">
								<span class="ml-2 file-name">{{ file.name }}</span>
								<span class="ml-2 file-size">[{{ fileSize }}]</span>
								<v-btn @click="removeFile" icon color="red">
									<!-- <v-icon small>mdi-trash-can</v-icon> -->
									<img
										src="@/assets/icons/delete-po.svg"
										alt="delete"
										width="18px"
										height="18px"
									/>
								</v-btn>
							</div>
						</div>

						<!--error-->
						<div class="pa-1 main-error-message mb-1">{{ formatedError }}</div>

						<!--reupload-->
						<div class="pa-1 d-flex justify-space-between align-center">
							<v-btn
								color="primary"
								class="btn-white mr-2"
								small
								@click="triggerSelectFile"
							>
								Re-upload
							</v-btn>
							<span class="error-submessage text-caption">
								You can use the template provided at the top of this modal to
								make sure you are uploading the file in proper format/structure.
							</span>
						</div>
					</div>
				</div>
				<div
					v-if="dialogType && dialogType == 'upload_payment_proof'"
					class="mark-as-paid-notes-div"
				>
					<p class="note-title">
						note for vendor
						<span style="text-transform: capitalize !important;"
							>(Optional)</span
						>
					</p>
					<v-textarea
						class="note note-textarea-field"
						outlined
						hide-details="auto"
						v-model="notes"
					>
					</v-textarea>
				</div>
			</v-card-text>

			<v-card-actions>
				<v-btn
					v-if="dialogType"
					@click="paymentProofUpload"
					:disabled="!file || loading"
					color="primary"
					class="btn-blue"
				>
					{{ loading ? "Uploading..." : "Confirm Payment" }}
				</v-btn>
				<v-btn
					v-else
					@click="doImport"
					:disabled="!file || loading"
					color="primary"
					class="btn-blue"
				>
					{{ loading ? "Confirming..." : "Confirm" }}
				</v-btn>

				<v-btn
					color="primary"
					class="btn-white"
					@click="closeDialog"
					:disabled="loading"
				>
					Cancel
				</v-btn>
			</v-card-actions>
		</v-card>

		<input
			@change="selectFile"
			class="d-none"
			type="file"
			accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
			ref="fileInput"
		/>

		<input
			@change="selectFile"
			class="d-none"
			type="file"
			accept=".pdf"
			ref="paymentProofInput"
		/>
	</v-dialog>
</template>

<script>
import axios from "axios";
import fileDownload from "js-file-download";
import globalMethods, {
	getFilenameFromAxiosResponse,
} from "@/utils/globalMethods";
import { mapActions, mapGetters } from "vuex";

export default {
	name: "ImportEntityDialog",
	props: {
		isOpen: {
			type: Boolean,
			required: true,
		},
		importFn: {
			type: Function,
			required: true,
		},
		templateUrl: {
			type: String,
			required: true,
		},
		dialogType: {
			type: String,
		},
		importErrors: {
			type: Object,
		},
		editedItems: {
			type: Object,
		},
		orderId: {
			type: String,
		},
		paymentDocumentStatus: {
			type: String,
		},
		requestForm: {
			type: String,
		},
	},
	data() {
		return {
			loading: false,
			isDownloading: false,
			file: null,
			errors: "erro line 1",
			notes: "",
		};
	},
	methods: {
		...mapActions({
			fetchProducts: "products/fetchProducts",
			getPo: "po/getPo",
			fetchPoAllNew: "po/fetchPoAllNew",
		}),
		...globalMethods,
		async doImport() {
			try {
				this.loading = true;
				let { data } = await this.importFn(this.file);
				this.loading = false;
				this.notificationMessage(data.message ?? data);
				this.closeDialog();
				// call products API
				let storePagination = this.$store.state.products.products;
				let page =
					typeof storePagination.current_page !== "undefined"
						? storePagination.current_page
						: 1;
				await this.fetchProducts(page);
			} catch (e) {
				this.errors = e;
			} finally {
				this.loading = false;
			}
		},
		async paymentProofUpload() {
			try {
				this.loading = true;
				const payload = {
					notes: this.notes,
					file: this.file,
					order_id: this.orderId,
					status: this.paymentDocumentStatus,
				};
				let { data } = await this.importFn(payload);
				if (this.requestForm == "list") {
					let pathData =
						typeof this.$router.history.current.query.tab !== "undefined"
							? this.$router.history.current.query.tab
							: null;

					this.fetchPoAllNew({
						page: this.getAllPoPage,
						state: pathData,
					});
				} else {
					this.getPo(this.orderId);
				}

				this.loading = false;
				this.notificationMessage(data.message ?? data);
				this.closeDialog();
			} catch (e) {
				this.errors = e;
			} finally {
				this.loading = false;
			}
		},
		removeFile() {
			this.file = null;
			this.$refs.fileInput.value = null;
			this.$refs.paymentProofInput.value = null;
			this.errors = null;
		},
		closeDialog() {
			if (this.loading) return false;
			this.removeFile();
			this.$emit("update:isOpen", false);
			this.$emit("close");
		},
		dragPrompt(e) {
			if (e.type === "dragleave") {
				e.currentTarget.classList.remove("active");
				return;
			}
			e.currentTarget.classList.add("active");
		},
		selectFile(evt) {
			if (evt.type === "drop") {
				if (evt.dataTransfer.files.length === 0) return false;
				this.file = evt.dataTransfer.files[0];
			} else {
				if (this.dialogType) {
					const paymentProofInput = this.$refs.paymentProofInput;
					if (paymentProofInput.files.length === 0) return false;
					this.file = paymentProofInput.files[0];
				} else {
					const fileInput = this.$refs.fileInput;
					if (fileInput.files.length === 0) return false;
					this.file = fileInput.files[0];
				}
			}
			this.errors = null;
		},
		triggerSelectFile() {
			if (this.dialogType) {
				this.$refs.paymentProofInput.click();
			} else {
				this.$refs.fileInput.click();
			}
		},
		downloadTemplate() {
			this.isDownloading = true;
			axios
				.get(this.templateUrl, {
					responseType: "blob",
				})
				.then((res) =>
					fileDownload(res.data, getFilenameFromAxiosResponse(res))
				)
				.catch((err) => console.log(err))
				.finally(() => {
					this.isDownloading = false;
				});
		},
	},
	watch: {
		isOpen() {
			if (this.importErrors) {
				this.file = this.importErrors.file;
				this.errors = this.importErrors.errors;
			}
		},
	},
	computed: {
		...mapGetters({
			getAllPoPage: "po/getAllPoPage",
		}),
		formatedError() {
			let errors = this.errors;
			console.log(errors);
			if (Array.isArray(errors)) {
				let message = errors.slice(0, 5).join(". ");
				return errors.length > 5
					? `${message}...\n ${errors.length - 5} more errors.`
					: message;
			}
			return errors;
		},
		fileSize() {
			if (!this.file) return false;
			return this.file.size > 15900
				? `${(Math.round(+this.file.size / 1024) / 1000).toFixed(2)} mb`
				: `0.01 mb`;
		},
	},
};
</script>

<style scoped lang="scss">
.download-template {
	background: #f7f7f7;
	border-radius: 4px;

	.download-btn {
		// height: 28px !important;
		min-width: 50px !important;
		padding: 0 12.4444444444px !important;
		// font-size: 0.75rem;
	}

	.text-caption {
		color: #4a4a4a;
		font-family: "Inter-Regular", sans-serif !important;
		font-size: 14px !important;
		letter-spacing: 0 !important;
	}
}
.re-upload-error-div {
	border: 1px #f93131 dashed;
	background: #fff2f2;
	border-radius: 4px;

	.file-name {
		color: #4a4a4a;
		font-family: "Inter-Medium", sans-serif;
	}

	.file-size {
		color: #6d858f;
	}

	i {
		&.file-icon {
			padding: 8px !important;
			font-size: 22px !important;
		}
	}

	.main-error-message {
		color: #4a4a4a;
		font-family: "Inter-Regular", sans-serif !important;
	}

	.error-submessage {
		color: #6d858f;
		font-family: "Inter-Regular", sans-serif !important;
		font-size: 12px !important;
		letter-spacing: 0 !important;
		line-height: 18px !important;
	}
}

.files-div {
	border: 1px #b4cfe0 dashed;
	border-radius: 4px;

	.file-name {
		color: #4a4a4a;
		font-family: "Inter-Medium", sans-serif;
	}

	.file-size {
		color: #6d858f;
	}

	i {
		&.file-icon {
			padding: 10px !important;
			font-size: 18px !important;
		}
	}
}

.browse-or-drop {
	color: #b4cfe0;
	border: 1px #b4cfe0 dashed;
	height: 160px;
	left: 0px;
	top: 96px;
	border-radius: 4px;
	padding: 24px;
	&.active {
		border-color: black;
		background: #f7f7f7;
	}
}
</style>

<style lang="scss">
@import "@/assets/scss/pages_scss/po/markAsPaidDialog.scss";
</style>
