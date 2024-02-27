<!-- @format -->

<template>
	<div>
		<v-dialog
			persistent
			v-model="openFeedback"
			max-width="640px"
			max-height="600px"
			content-class="share-feedback-dialog"
			:retain-focus="false"
		>
			<v-card class="share-feedback-card">
				<v-form
					id="shareFeedback"
					ref="formFeedback"
					v-model="valid"
					action="#"
					@submit.prevent=""
					enctype="multipart/form-data"
				>
					<v-card-title>
						<span class="headline">Share Feedback</span>

						<button
							:disabled="isFetching"
							icon
							dark
							class="btn-close"
							@click="close"
						>
							<v-icon>mdi-close</v-icon>
						</button>
					</v-card-title>

					<v-card-text>
						<div class="report-type mb-2">
							<label class="text-item-label">Type</label>
							<div class="radio-input">
								<v-radio-group
									class="mt-0"
									v-model="form.type"
									row
									hide-details="auto"
									outlined
								>
									<v-radio
										class="report-type-radio"
										v-for="(type, i) in types"
										:key="i"
										:label="type.value"
										:value="type.value"
									>
									</v-radio>
								</v-radio-group>
							</div>
							<small class="red--text" v-if="errorKeys.includes('type')"
								>This field is required</small
							>
						</div>
						<div class="report-subject mb-2">
							<label class="text-item-label">Subject</label>
							<v-text-field
								v-model="form.subject"
								type="text"
								placeholder="Subject"
								outlined
								class="text-fields"
							/>
							<small class="red--text" v-if="errorKeys.includes('subject')"
								>This field is required</small
							>
						</div>
						<div class="report-reason mb-2">
							<label class="text-item-label mb-0">Comment</label>
							<textarea v-model="form.comment" rows="5" class="textarea-field">
							</textarea>
							<small class="red--text" v-if="errorKeys.includes('comment')"
								>This field is required</small
							>
						</div>
						<div class="report-page mb-2">
							<label class="text-item-label">Related page</label>
							<v-text-field
								v-model="form.page_related"
								type="text"
								placeholder="Related Page"
								outlined
								class="rp-bg text-fields"
								disabled
							/>
							<small class="red--text" v-if="errorKeys.includes('page_related')"
								>This field is required</small
							>
						</div>
						<div class="report-screenshot mb-2">
							<label class="text-item-label">Screenshots</label>
							<div class="screenshot">
								<div
									style="display: block"
									class="h-screen items-left justify-left text-left"
									id="app"
								>
									<div
										style="width: 100%"
										@dragover="dragover"
										@dragleave="dragleave"
										@drop="dropFile"
									>
										<input
											style="display: none"
											type="file"
											name="fields[]"
											id="screenshots"
											class="w-px h-px opacity-0 overflow-hidden absolute"
											@change="onChange()"
											ref="file"
											accept="image/*"
										/>
										<label
											for="screenshots"
											class="block cursor-pointer trigger-field"
										>
											<div class="flex">
												<div class="flex flex-column drop-field">
													<div class="browser-text text-center">
														<span>Browse or Drop Image</span>
													</div>
													<div class="btn-white2 browser-btn">
														<div class="upload-btn mt-2">
															<span class="iconUploadfile"
																><i class="ic-upload"></i
															></span>
															<span class="classUpload">Upload</span>
														</div>
													</div>
												</div>
											</div>
										</label>
									</div>
								</div>
								<div class="default-image">
									<img :src="base64URL" class="img-ratio" />
								</div>
								<div v-for="(file, i) in filelistInURL" :key="i">
									<img :src="file" class="img-ratio" />
									<button @click="removeFile(i)" class="remove-ss-btn">
										Remove
									</button>
								</div>
							</div>
						</div>
					</v-card-text>

					<v-card-actions>
						<v-btn
							:disabled="isFetching"
							@click="handleSubmit"
							class="btn-blue mr-2"
							text
						>
							<span>
								<span>
									<v-progress-circular
										:size="15"
										color="#fff"
										:width="2"
										indeterminate
										style="margin-right:3px"
										v-if="isFetching"
									>
									</v-progress-circular>
									Submit
								</span>
							</span>
						</v-btn>

						<v-btn :disabled="isFetching" class="btn-white" @click="close">
							Cancel
						</v-btn>
					</v-card-actions>
				</v-form>
			</v-card>
		</v-dialog>
		<ConfirmDialog :dialogData.sync="feedbackSubmittedDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="../assets/icons/CircleCheckOutline.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Feedback Submitted</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					We have received your feedback. Thanks for informing us. We will get
					back to you with update.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="closeSubmittedDialog">
					<span>Understood</span>
				</v-btn>
				<v-btn class="btn-white" text @click="shareMoreFeedback">
					Share More Feedback
				</v-btn>
			</template>
		</ConfirmDialog>
	</div>
</template>

<script>
import jquery from "jquery";
import axios from "axios";
import globalMethods from "@/utils/globalMethods";
import html2canvas from "html2canvas";
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";

const APIBaseUrl = process.env.VUE_APP_BASE_URL;
export default {
	name: "FeedbackDialog",
	props: ["openFeedback"],
	components: {
		ConfirmDialog,
	},
	data: () => ({
		form: {
			type: "",
			subject: "",
			comment: "",
			page_related: "",
			files: [],
			base64File: "",
		},
		valid: false,
		isFetching: false,
		errorFile: false,
		errorKeys: [],
		filelist: [],
		filelistInURL: [],
		base64URL: "",
		allFieldsValid: false,
		types: [
			{
				value: "Issue",
				label: "Issue",
			},
			{
				value: "Bug",
				label: "Bug",
			},
			{
				value: "Feature Request",
				label: "Feature Request",
			},
			{
				value: "General Feedback",
				label: "General Feedback",
			},
		],
		feedbackSubmittedDialog: false,
	}),
	watch: {
		filelist(nd) {
			this.filelistInURL = [];
			let table = nd;
			let errorCount = 0;

			Object.keys(table).forEach((key) => {
				if (
					table[key].type.split("/")[0] !== "application" ||
					table[key].type.split("/")[0] !== "video"
				) {
					console.log(table[key]);
					this.filelistInURL.push(this.createURL(table[key]));
					errorCount++;
				}
			});
			if (errorCount > 0) {
				this.errorFile = 1;
				this.allFieldsValid = false;
			} else {
				this.errorFile = 0;
				this.allFieldsValid = true;
			}
			this.form.files = nd;
		},
		openFeedback(nd) {
			if (nd) {
				this.form.page_related = window.location.href;

				const screenshotTarget = document.body;
				html2canvas(screenshotTarget).then((canvas) => {
					const base64image = canvas.toDataURL("image/png");

					this.base64URL = base64image;
					this.form.base64File = base64image.replace(
						/^data:image\/(png|jpg);base64,/,
						""
					);
				});
			}
		},
	},
	methods: {
		...globalMethods,
		close() {
			this.$emit("closeDialog");

			this.form = {
				type: "",
				subject: "",
				comment: "",
				page_related: "",
				files: [],
				base64File: "",
			};
			this.valid = false;
			this.errorFile = false;
			this.errorKeys = [];
			this.filelist = [];
			this.filelistInURL = [];
			this.allFieldsValid = false;
		},
		uploadAgain() {
			jquery(document)
				.find(".trigger-field")
				.trigger("click");
		},
		dragover(event) {
			event.preventDefault();
			if (!event.currentTarget.classList.contains("bg-green-300")) {
				event.currentTarget.classList.remove("bg-gray-100");
				event.currentTarget.classList.add("bg-green-300");
			}
		},
		dragleave(event) {
			event.currentTarget.classList.add("bg-gray-100");
			event.currentTarget.classList.remove("bg-green-300");
		},
		dropFile(event) {
			event.preventDefault();
			this.$refs.file.files = event.dataTransfer.files;
			this.onChange();
		},
		onChange() {
			this.filelist.push(...this.$refs.file.files);
			let table = this.filelist;
			let errorCount = 0;
			Object.keys(table).forEach((key) => {
				if (
					table[key].type.split("/")[0] !== "application" ||
					table[key].type.split("/")[0] !== "video"
				) {
					this.filelistInURL.push(this.createURL(table[key]));
					errorCount++;
				}
			});
			if (errorCount > 0) {
				this.errorFile = 1;
				this.allFieldsValid = false;
			} else {
				this.errorFile = 0;
				this.allFieldsValid = true;
			}
		},
		createURL(input) {
			let file = input;
			return URL.createObjectURL(file);
		},
		readUrl(input) {
			let file = input;
			return new Promise((resolve, reject) => {
				const reader = new FileReader();
				reader.readAsDataURL(file);
				reader.onload = () => {
					let encoded = reader.result.toString().replace(/^data:(.*,)?/, "");
					if (encoded.length % 4 > 0) {
						encoded += "=".repeat(4 - (encoded.length % 4));
					}
					resolve(encoded);
				};
				reader.onerror = (error) => reject(error);
			});
		},
		removeFile(i) {
			this.filelist.splice(i, 1);
			this.filelistInURL.splice(i, 1);
		},
		handleSubmit() {
			this.errorKeys = [];
			Object.entries(this.form).forEach(([key, value]) => {
				if (key !== "files") {
					if (value === null || value === "" || value.length < 1) {
						this.errorKeys.push(key);
						this.$refs.formFeedback.validate();
					}
				}
			});
			if (this.errorKeys.length < 1) {
				this.isFetching = true;
				let formdata = new FormData();
				formdata.append("type", this.form.type);
				formdata.append("subject", this.form.subject);
				formdata.append("comment", this.form.comment);
				formdata.append("pagerelated", this.form.page_related);
				this.form.files.map((file, key) => {
					formdata.append(`files[${key}]`, file);
				});
				formdata.append("base64", this.form.base64File);

				axios
					.post(`${APIBaseUrl}/v2/share-feedback`, formdata)
					.then(() => {
						this.valid = false;
						this.isFetching = false;
						this.errorFile = false;
						this.errorKeys = [];
						this.filelist = [];
						this.filelistInURL = [];
						this.allFieldsValid = false;
						this.feedbackSubmittedDialog = true;
					})
					.catch((e) => {
						this.notificationError(e.message);
						this.isFetching = false;
					})
					.finally(() => {
						this.isFetching = false;
					});
			}
		},
		closeSubmittedDialog() {
			this.feedbackSubmittedDialog = false;
			this.close();
		},
		shareMoreFeedback() {
			this.form.type = "";
			this.form.subject = "";
			this.form.comment = "";
			this.feedbackSubmittedDialog = false;
		},
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/dialog/feedbackDialog.scss";
</style>
