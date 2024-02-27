<!-- @format -->

<template>
	<div
		:class="
			`${isMobile ? 'edit-shipment-mobile-wrapper' : ''} ${ isBookingInviteForm != true ? 'slide-in-fwd-center' : ''}`
		"
		:id="`${isMobile ? 'booking-shipment-dialog-mobile-wrapper' : ''}`"
		v-if="show"
		:style="
			`${
				isMobile
					? 'position:relative; z-index: 1000000; background-color: white;'
					: ''
			}`
		"
	>
		<div :style="`${isMobile ? 'width: 100%;' : ''}`">
			<div class="slide-in-fwd-center" v-if="isMobile">
				<div class="d-flex header-title-wrapper">
					<h2 class="w-full header-dialog-mobile">{{ "Submit Request" }}</h2>
					<button icon dark class="btn-close" @click.stop="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</div>
				<div
					style="overflow-y: auto;max-height: 400px;"
					class="forwarder-container-mobile"
				>
					<div class="forwarder-wrapper-mobile">
						<div class="d-flex text-content">
							<div>
								<label
									:class="`${is_review ? 'checked' : ''}`"
									style="position: relative;"
								>
									<generic-icon
										:marginLeft="0"
										:iconName="`${is_review ? 'checked' : 'not-checked'}`"
									></generic-icon>
									<input
										@click.stop="toggleReview"
										id="is_review"
										style="position: absolute; opacity: 0;"
										type="checkbox"
										:checked="is_review"
										class=""
									/>
								</label>
							</div>
							<div>
								<h3>
									{{ "Select Shifl as forwarder" }}
								</h3>
								<p>
									{{
										"Shifl will review your booking request and send a quote"
									}}
								</p>
							</div>
						</div>
					</div>
					<div
						v-if="!is_review"
						is_reviewclass="d-flex flex-column main-content"
					>
						<div
							v-bind:key="`forwarder-${key}`"
							v-for="(fi, key) in forwarders"
							class="forwarder-item"
						>
							<p class="forwarder-header">{{ "Forwarder " + (key + 1) }}</p>
							<div>
								<input-text
									label="Forwarder’s name"
									:field.sync="fi.name"
									labelColor="#819FB2"
									placeholderText="Enter forwarder’s name"
									:inputFontWeight="600"
									:inputFontSize="10"
									contentClassLabel="forwarder-label"
								>
									<template v-slot:input="{ mainContent }">
										<v-text-field
											:height="40"
											:color="mainContent.textColor"
											width="200px"
											type="text"
											:value="mainContent.field"
											dense
											:class="
												`custom-font-${mainContent.inputFontSize} custom-font-weight-${mainContent.inputFontWeight}`
											"
											@change="mainContent.updateValue"
											:placeholder="mainContent.placeholderText"
											outlined
											hide-details="auto"
											:rules="[(v) => !!v || 'Field required']"
										>
										</v-text-field>
									</template>
								</input-text>
							</div>
							<div style="margin-top: 16px; margin-bottom: 16px;">
								<input-text
									label="Forwarder’s emaila"
									:field.sync="fi.email"
									contentClassLabel="forwarder-label"
									labelColor="#819FB2"
									placeholderText="Enter forwarder’s email"
									:inputFontWeight="600"
									:inputFontSize="10"
								>
									<template v-slot:input="{ mainContent }">
										<v-text-field
											:height="40"
											:color="mainContent.textColor"
											width="200px"
											type="text"
											:value="mainContent.field"
											dense
											:class="
												`custom-font-${mainContent.inputFontSize} custom-font-weight-${mainContent.inputFontWeight}`
											"
											@change="mainContent.updateValue"
											:placeholder="mainContent.placeholderText"
											outlined
											hide-details="auto"
											:rules="emailRules"
										>
										</v-text-field>
									</template>
								</input-text>
							</div>
						</div>
					</div>
					<div
						v-if="!is_review"
						class="d-flex add-forwarder-content add-forwarder-content-mobile"
					>
						<a @click.stop="addForwarder">
							<span>
								<generic-icon iconName="plus"></generic-icon>
							</span>
							<span>
								{{ "Add Forwarder" }}
							</span>
						</a>
					</div>
				</div>
				<div class="forwarder-mobile-footer">
					<div class="d-flex footer">
						<v-btn
							style="margin-right: 8px;"
							class="save-btn btn-blue"
							text
							@click.stop="submit"
						>
							<span>{{ "Submit" }}</span>
						</v-btn>
						<v-btn
							class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue"
							text
							@click.stop="close"
						>
							<span>{{ "Cancel" }}</span>
						</v-btn>
					</div>
				</div>
			</div>
			<div style="min-height: 400px;" id="submit-request-modal-wrapper">
				<booking-request-submitted-modal
					:show.sync="showBookingRequestSubmittedModal"
					:submitShipmentId="submitShipmentId"
					@close="closeBookingRequestSubmittedModal"
					@reloadShipments="reloadShipments"
				>
					<template v-slot:title>
						<generic-icon iconName="circle-check"></generic-icon>
					</template>
					<template v-slot:actions="{ footer }">
						<div class="d-flex footer">
							<v-btn
								@click.stop="() => viewDetails(footer)"
								style="margin-right: 8px;"
								class="save-btn btn-blue"
								text
							>
								<span>{{ "View Details" }}</span>
							</v-btn>
							<v-btn
								class="delete-cancel btn-white edit-shipment-cancel-btn btn-blue"
								text
								@click.stop="() => closeFinish(footer)"
							>
								<span>{{ "Okay" }}</span>
							</v-btn>
						</div>
					</template>
				</booking-request-submitted-modal>
				<v-dialog
					v-if="!isMobile && !showBookingRequestSubmittedModal && !finished"
					attach="#submit-request-modal-wrapper"
					@click:outside="clickOutside"
					v-model="showDialog"
					max-width="560px"
          z-inxex="999999999"
					:content-class="className"
				>
					<v-card class="add-shipment-dialog-success">
						<v-card-title class="headline">
							<slot name="title"></slot>
						</v-card-title>
						<v-card-text class="pb-0">
							<div class="d-flex text-content">
								<div>
									<label
										:class="`${is_review ? 'checked' : ''}`"
										style="position: relative;"
									>
										<generic-icon
											:marginLeft="0"
											:iconName="`${is_review ? 'checked' : 'not-checked'}`"
										></generic-icon>
										<input
											@click.stop="toggleReview"
											id="is_review"
											style="position: absolute; opacity: 0;"
											type="checkbox"
											:checked="is_review"
											class=""
										/>
									</label>
								</div>
								<div>
									<h3>
										{{ "Select Shifl as forwarder" }}
									</h3>
									<p>
										{{
											"Shifl will review your booking request and send a quote"
										}}
									</p>
								</div>
							</div>
							<div v-if="!is_review" class="d-flex flex-column main-content">
								<div
									v-bind:key="`forwarder-${key}`"
									v-for="(fi, key) in forwarders"
									class="forwarder-item"
								>
									<p>{{ "Forwarder " + (key + 1) }}</p>
									<div>
										<input-text
											label="Forwarder’s name"
											contentClassLabel="forwarder-label"
											:field.sync="fi.name"
											labelColor="#819FB2"
											placeholderText="Enter forwarder’s name"
											:inputFontWeight="400"
											:inputFontSize="14"
										>
											<template v-slot:input="{ mainContent }">
												<v-text-field
													:height="40"
													:color="mainContent.textColor"
													width="200px"
													type="text"
													dense
													:class="
														`custom-font-${mainContent.inputFontSize} custom-font-weight-${mainContent.inputFontWeight}`
													"
													@change="mainContent.updateValue"
													:placeholder="mainContent.placeholderText"
													outlined
													hide-details="auto"
													:rules="[(v) => !!v || 'Field required']"
												>
												</v-text-field>
											</template>
										</input-text>
									</div>
									<div style="margin-top: 16px; margin-bottom: 16px;">
										<input-text
											label="Forwarder’s email"
											:field.sync="fi.email"
											contentClassLabel="forwarder-label"
											labelColor="#819FB2"
											placeholderText="Enter forwarder’s email"
											:inputFontWeight="400"
											:inputFontSize="14"
										>
											<template v-slot:input="{ mainContent }">
												<v-text-field
													:height="40"
													:color="mainContent.textColor"
													width="200px"
													type="text"
													dense
													:class="
														`custom-font-${mainContent.inputFontSize} custom-font-weight-${mainContent.inputFontWeight}`
													"
													@change="mainContent.updateValue"
													:placeholder="mainContent.placeholderText"
													outlined
													hide-details="auto"
													:rules="emailRules"
												>
												</v-text-field>
											</template>
										</input-text>
									</div>
								</div>
							</div>
							<div v-if="!is_review" class="d-flex add-forwarder-content">
								<a @click.stop="addForwarder">
									<span>
										<generic-icon iconName="plus"></generic-icon>
									</span>
									<span>
										{{ "Add Forwarder" }}
									</span>
								</a>
							</div>
						</v-card-text>
						<v-card-actions>
							<slot
								name="actions"
								v-bind:footer="{
									close: close,
									submit: submit,
									submitLoading: submitRequestModalLoading,
									disableSubmit: disableSubmit,
									submitRequestLoading: submitRequestLoading,
								}"
							></slot>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</div>
		</div>
	</div>
</template>
<style lang="scss">
@import "./scss/submitRequestModal.scss";
</style>
<script>
import GenericIcon from "../../../Icons/GenericIcon";
import InputText from "../InputTexts/InputText";
import BookingRequestSubmittedModal from "./BookingRequestSubmittedModal.vue";
import _ from "lodash";
export default {
	name: "SubmitRequestModal",
	props: [
		"isMobile",
		"show",
		"className",
		"submitRequestModalLoading",
		"showBookingRequestSubmittedModal",
		"reference",
		"showDialog",
		"submitShipmentId",
		"submitRequestLoading",
      "isBookingInviteForm"
	],
	components: {
		GenericIcon,
		InputText,
		BookingRequestSubmittedModal,
	},
	computed: {
		disableSubmit() {
			if (this.is_review) {
				return false;
			} else {
				//perform validation on forwarders
				if (this.forwarders.length == 0) {
					return true;
				} else {
					//find invalid forwarder
					let invalidForwarder = _.find(
						this.forwarders,
						(e) => e.email === "" || e.name === ""
					);
					if (typeof invalidForwarder === "undefined") return false;
					else return true;
				}
			}
		},
	},
	data: () => ({
		is_review: false,
		forwarders: [],
		submitLoading: false,
		finished: false,
		emailRules: [
			(v) => !!v || "Email is required",
			(v) =>
				!v ||
				/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
				"E-mail must be valid",
		],
	}),
	methods: {
		closeFinish(f) {
			this.finished = true;
			f.closeSubmitRequest();
		},
		viewDetails(f) {
			f.viewDetails();
		},
		closeBookingRequestSubmittedModal() {
			this.$emit("update:bookingShipmentDialogView", false);
			this.close();
		},
		reloadShipments() {
			this.$emit("reloadShipments");
		},
		toggleReview() {
			this.is_review = !this.is_review;
			if (!this.is_review) {
				this.forwarders = [];
			}
		},
		submit() {
			//this.$emit('update:showBookingRequestSubmittedModal',true)
			this.$emit("update:submitRequestLoading", true);
			//validate forwarders
			if (!this.is_review && this.forwarders.length == 0) {
				this.$emit(
					"notificationError",
					"Please make sure you input at least 1 forwarder."
				);
			} else {
				this.$emit("update:submitRequestModalLoading", true);
				this.$emit("submit", {
					forwarders: this.forwarders,
					is_review: this.is_review,
				});
			}
		},
		addForwarder() {
			this.forwarders.push({
				name: "",
				email: "",
			});
		},
		clickOutside() {
			this.$emit("close");
		},
		close() {
			this.$emit("close");
		},
	},
};
</script>
