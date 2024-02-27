<!-- @format -->

<template>
	<v-dialog
		v-model="dialog"
		max-width="1376px"
		width="60%"
		content-class="payment-review-dialog"
		:retain-focus="false"
		persistent
		scrollable
	>
		<v-card class="review-payment-card">
			<v-card-title>
				<span class="headline">Review Payment</span>

				<button icon dark class="btn-close" @click="close">
					<v-icon>mdi-close</v-icon>
				</button>
			</v-card-title>

			<v-card-text>
				<pdf :src="pdf" style="display: inline-block; width: 100%;"></pdf>
			</v-card-text>

			<v-card-actions
				class="review-payment-button-actions"
				v-if="dialogType != 'view_proof'"
			>
				<button class="btn-blue" @click="acceptPayment()">
					Accept
				</button>

				<button class="btn-white" @click="rejectDocuments()">
					<span class="red--text">
						Reject Documents
					</span>
				</button>
				<button class="btn-white download" @click="download()">
					Download
				</button>
			</v-card-actions>
		</v-card>

		<ConfirmDialog :dialogData.sync="paymentAcceptance">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/question-blue.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Payment Acceptance</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Please confirm that you have reviewed and accepted the proof of
					payment from the buyer. It will send request to forwarder for
					releasing telex and inform buyer.
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn
					class="btn-blue"
					text
					@click="acceptPaymentReview(getSoDetail, '3')"
					:disabled="getReviewPaymentLoading"
				>
					<span>Confirm & Request</span>
				</v-btn>
				<v-btn
					class="btn-white"
					text
					@click="closeAcceptPaymentDialog()"
					:disabled="getReviewPaymentLoading"
				>
					Close
				</v-btn>
			</template>
		</ConfirmDialog>

		<ConfirmDialog :dialogData.sync="paymentRejection">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
					<img src="@/assets/icons/question-blue.svg" alt="alert" />
				</div>
			</template>

			<template v-slot:dialog_title>
				<h2>Payment Rejection</h2>
			</template>

			<template v-slot:dialog_content>
				<p>
					Please confirm that you have reviewed and rejected the proof of
					payment from the buyer.
				</p>
				<div class="reason-notes">
					<p class="reason-title mb-1">
						Rejection Reason
						<span style="text-transform: capitalize !important;"
							>(Optional)</span
						>
					</p>
					<v-textarea
						class="note"
						outlined
						v-model="reasonNotes"
						hide-details="auto"
						rows="2"
					>
					</v-textarea>
				</div>
			</template>

			<template v-slot:dialog_actions>
				<v-btn
					class="btn-white"
					text
					@click="rejectPaymentReview(getSoDetail, '4')"
					:disabled="getReviewPaymentLoading"
				>
					<span class="red--text">Reject Payment</span>
				</v-btn>
				<v-btn
					class="btn-white"
					text
					@click="closeRejectDocuments()"
					:disabled="getReviewPaymentLoading"
				>
					Close
				</v-btn>
			</template>
		</ConfirmDialog>
	</v-dialog>
</template>

<script>
import ConfirmDialog from "@/components/Dialog/GlobalDialog/ConfirmDialog.vue";
import pdf from "vue-pdf";
import { mapActions, mapGetters } from "vuex";

export default {
	name: "PaymentReviewDialog",
	props: [
		"dialog",
		"isMobile",
		"dialogType",
		"orderList",
		"requestForm",
		"showProof",
	],
	components: { ConfirmDialog, pdf },
	data: () => ({
		paymentAcceptance: false,
		paymentRejection: false,
		reasonNotes: "",
	}),
	computed: {
		...mapGetters({
			getSoDetail: "salesOrders/getPoDetail",
			getPoDetail: "po/getPoDetail",
			getReviewPaymentLoading: "salesOrders/getReviewPaymentLoading",
			getAllPoPage: "salesOrders/getAllPoPage",
		}),
		pdf() {
			if (this.requestForm != "list") {
				if (this.showProof == "show_proof") {
					return this.getSoDetail?.mark_as_paid?.document_path;
				}
				return this.dialogType != "view_proof"
					? this.getSoDetail?.mark_as_paid?.document_path
					: this.getPoDetail?.mark_as_paid?.document_path;
			} else {
				return this.orderList?.mark_as_paid?.document_path;
			}
		},
	},
	methods: {
		...mapActions({
			reviewPayment: "salesOrders/reviewPayment",
			getPo: "salesOrders/getPo",
			downloadPaymentDocument: "salesOrders/downloadPaymentDocument",
			fetchPoAllNew: "salesOrders/fetchPoAllNew",
		}),
		acceptPayment() {
			this.paymentAcceptance = true;
		},
		async acceptPaymentReview(item, status) {
			let parms = {
				order_id: item.id,
				status: status,
			};
			await this.reviewPayment(parms);
			this.closeAcceptPaymentDialog();
			this.close();
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
				this.getPo(item.id);
			}
		},
		async rejectPaymentReview(item, status) {
			let parms = {
				order_id: item.id,
				status: status,
				reject_note: this.reasonNotes,
			};
			await this.reviewPayment(parms);
			this.closeAcceptPaymentDialog();
			this.close();
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
				this.getPo(item.id);
			}
		},
		closeAcceptPaymentDialog() {
			this.paymentAcceptance = false;
		},
		rejectDocuments() {
			this.paymentRejection = true;
		},
		closeRejectDocuments() {
			this.paymentRejection = false;
		},
		close() {
			this.$emit("close");
		},
		download() {
			let orderId =
				this.requestForm != "list" ? this.getSoDetail.id : this.orderList.id;
			this.downloadPaymentDocument(orderId);
		},
	},
};
</script>

<style lang="scss">
@import "@/assets/scss/pages_scss/paymentReviewDialog.scss";
</style>
