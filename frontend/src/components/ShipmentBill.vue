<!-- @format -->

<template>
	<v-container
		fluid
		class="cont-fluid-wrapper pa-sm-5 pa-0"
		id="shipment-invoice"
		v-resize="onResize"
	>
		<div
			class="loading-wrapper mt-4 text-center"
			v-if="getShipmentBillsLoading"
		>
			<v-progress-circular :size="40" color="#0171a1" indeterminate>
			</v-progress-circular>
			<!-- <div class="pt-4" style="font-size: 14px;">Fetching bills...</div> -->
		</div>

		<v-card
			v-if="!getShipmentBillsLoading"
			:outlined="!isMobile"
			flat
			class="shipment-bills-card-wrapper"
			:class="isMobile ? 'mobile-bills-card' : ''"
		>
			<v-card-text class="mt-sm-0" :class="!isMobile ? 'mt-4' : ''">
				<v-row align="center" justify="space-between">
					<v-col>
						<h4 class="text-muted font-weight-bold fs-16">Total Due</h4>
						<h1 class="mt-4 pb-1 fs-24">
							{{ currencyNumberFormat(total_due) }}
						</h1>
					</v-col>

					<v-col
						align="right"
						v-if="getShipmentBills.length && total_due !== 0"
					>
						<button
							class="btn-blue mr-1"
							type="button"
							@click="onToggleMakePaymentModal(total_due)"
							v-if="userCanMakePayment"
						>
							Clear All Due
							<!-- :disabled="!hasSelectedBalance" -->
						</button>
					</v-col>
				</v-row>
			</v-card-text>
		</v-card>

		<v-sheet
			v-if="!getShipmentBillsLoading && getShipmentBills.length"
			outlined
			class="shipment-bills-card-wrapper table-data"
			:class="!isMobile ? 'mt-5' : ''"
		>
			<v-data-table
				v-if="!isMobile"
				v-model="selectedInvoices"
				show-select
				hide-default-footer
				:headers="tableHeader"
				:items="getShipmentBills"
				:loading="getInvoiceDetailLoadingStatus"
				:items-per-page="100"
			>
				<template v-slot:top v-if="selectedInvoices.length">
					<v-toolbar height="56">
						<div class="text-muted" style="font-size: 14px">
							{{ selectedInvoices.length }} Item selected.
							<a
								href="#"
								@click.prevent="onClearSelectedInvoices"
								class="text-primary-color text-decoration-none"
								>Clear Selection</a
							>
						</div>
						<v-spacer />
						<div class="d-flex justify-end mr-1">
							<button
								class="text-capitalize btn-blue mr-2"
								width="131"
								@click="onToggleMakePaymentModal(null)"
								:disabled="!hasSelectedBalance"
								v-if="userCanMakePayment"
							>
								Make Payment
							</button>
							<v-btn
								class="btn-white"
								outlined
								text
								height="40"
								@click="download(selectedInvoices)"
								>Download ({{ selectedInvoices.length }})</v-btn
							>
						</div>
					</v-toolbar>
					<v-divider />
				</template>

				<template v-slot:[`item.due_date`]="{ item }">
					{{ monthDayYearFormat(item.due_date) }}
				</template>

				<template v-slot:[`item.balance`]="{ item }">
					<span v-if="item.payment_status !== 'Paid'">
						{{ currencyNumberFormat(item.balance) }}
					</span>
					<span v-if="item.payment_status === 'Paid'">
						{{ currencyNumberFormat(item.total_amount) }}
					</span>

					<div>
						<span v-if="item.payment_status === 'Paid'" class="text-muted"
							>Paid on {{ item.paid_on }}</span
						>
					</div>
				</template>

				<template v-slot:[`item.actions`]="{ item }">
					<div class="d-flex justify-end">
						<v-btn
							v-if="Number(item.balance) > 0 && userCanMakePayment"
							outlined
							text
							class="btn-white"
							width="131"
							@click="onToggleMakePaymentModal(item)"
							height="40"
							min-height="40"
						>
							Make Payment
						</v-btn>

						<v-btn
							v-if="Number(item.balance) < 0"
							text
							outlined
							class="green--text paid-button"
						>
							<span class="text-capitalize paid-text-img">
								<img
									src="../assets/icons/checkMark.png"
									class="mr-1"
									width="15px"
									height="15px"
									alt=""
								/>
								<span>Paid</span>
							</span>
						</v-btn>
						<!-- <v-btn v-else color="success" text outlined width="131" class="text-capitalize" height="40" min-height="40"> <v-icon color="green">mdi-check</v-icon> Paid </v-btn> -->
						<v-menu offset-y left min-width="150">
							<template v-slot:activator="{ on, attrs }">
								<v-btn
									color="primary"
									v-bind="attrs"
									v-on="on"
									text
									outlined
									min-width="40"
									width="40"
									height="40"
									min-height="40"
									class="ml-2"
									style="border: 1px solid #b4cfe0 !important"
								>
									<v-icon style="font-size: 18px">mdi-dots-horizontal</v-icon>
								</v-btn>
							</template>

							<v-list>
								<v-list-item
									@click="viewPayment(item)"
									style="min-height: 42px"
								>
									<v-list-item-title class="d-flex">
										<div class="title-img">
											<img
												class="mr-2"
												src="@/assets/icons/visibility-po.svg"
												width="16px"
												height="16px"
											/>
										</div>
										<span>View</span>
									</v-list-item-title>
								</v-list-item>

								<v-list-item @click="download(item)" style="min-height: 40px">
									<v-list-item-title class="d-flex">
										<div class="title-img">
											<img
												class="mr-2"
												src="@/assets/icons/download-po.svg"
												width="16px"
												height="16px"
											/>
										</div>
										<span>Download</span>
									</v-list-item-title>
								</v-list-item>
							</v-list>
						</v-menu>
					</div>
				</template>
			</v-data-table>

			<v-data-table
				v-if="isMobile"
				hide-default-footer
				hide-default-header
				:items="getShipmentBills"
				:headers="tableMobileHeader"
				v-model="selectedInvoices"
				:loading="getShipmentBillsLoading"
				:items-per-page="100"
			>
				<template v-slot:top>
					<v-toolbar height="56" v-if="selectedInvoices.length === 0">
						<h3 class="fs-16">Invoices</h3>
					</v-toolbar>

					<v-toolbar height="56" v-else>
						<v-spacer></v-spacer>
						<button
							type="button"
							class="text-capitalize btn-blue mr-2"
							:disabled="!hasSelectedBalance"
							@click="onToggleMakePaymentModal(null)"
							v-if="userCanMakePayment"
						>
							Make Payment
						</button>

						<v-btn
							class="btn-white mr-2"
							outlined
							text
							height="40"
							@click="download(selectedInvoices)"
						>
							<img
								src="@/assets/icons/download.svg"
								width="16px"
								height="16px"
							/>
							({{ selectedInvoices.length }})
						</v-btn>

						<v-btn class="btn-white" @click="onClearSelectedInvoices">
							Cancel
						</v-btn>
					</v-toolbar>

					<v-divider style="border-color: #ebf2f5"></v-divider>
				</template>

				<template v-slot:item="{ item, isSelected }">
					<tr class="pa-4" :class="{ 'v-data-table__selected': isSelected }">
						<td>
							<div class="d-flex justify-space-between">
								<v-checkbox
									v-model="selectedInvoices"
									:value="item"
									style="margin: 0px; padding: 0px;"
									hide-details
									:label="item.invoice_number.toString()"
									class="text-primary-color mobile-checkbox"
								/>

								<span
									style="color: #4a4a4a"
									class="font-weight-bold"
									v-if="item.payment_status !== 'Paid'"
								>
									{{ currencyNumberFormat(item.balance) }}
								</span>

								<span
									style="color: #4a4a4a"
									class="font-weight-bold"
									v-if="item.payment_status === 'Paid'"
								>
									{{ currencyNumberFormat(item.total_amount) }}
								</span>
							</div>

							<div class="my-1">
								<span class="text-muted">Invoice Date</span>&nbsp;
								<span>{{ monthDayYearFormat(item.invoice_date) }}</span>
							</div>

							<div class="my-1">
								<span class="text-muted">Reference</span>&nbsp;
								<span class="text-primary-color"
									>#{{ item.shipment_reference_number }}</span
								>
							</div>

							<div class="my-1">
								<span class="text-muted">Due Date</span>&nbsp;
								<span>{{ monthDayYearFormat(item.due_date) }}</span>
							</div>

							<div class="my-1" v-if="item.payment_status === 'Paid'">
								<span class="text-muted">Paid On</span>&nbsp;
								<span>{{ item.paid_on }}</span>
							</div>

							<div class="d-flex mt-2">
								<v-btn
									color="success"
									text
									outlined
									class="mr-2 text-capitalize paid-button mobile"
									v-if="item.payment_status === 'Paid'"
									height="40"
								>
									<v-icon
										style="color: #16b442; font-size: 20px; padding-top: 2px"
										class="mr-1"
										>mdi-check
									</v-icon>
									<span style="letter-spacing: 0">Paid</span>
								</v-btn>

								<v-btn
									v-if="userCanMakePayment"
									class="btn-white mr-2"
									@click="onToggleMakePaymentModal(item)"
									min-width="131"
								>
									Make Payment
								</v-btn>

								<v-btn
									class="btn-white mr-2"
									min-width="40"
									height="40"
									@click="viewPayment(item)"
								>
									<img
										src="@/assets/icons/visibility.svg"
										width="16px"
										height="16px"
									/>
								</v-btn>

								<v-btn
									class="btn-white"
									min-width="40"
									height="40"
									@click="download(item)"
								>
									<img
										src="@/assets/icons/download.svg"
										width="16px"
										height="16px"
									/>
								</v-btn>
							</div>
						</td>
					</tr>
				</template>
			</v-data-table>
		</v-sheet>

		<MakePaymentDialog
			@paymentDialog="paymentDialog"
			:dialog.sync="dialog"
			:editedItemData.sync="invoiceToPay"
			:isMobile="isMobile"
			@close="closePaymentModal"
			:getcards="getcards"
			:default_customer_id="default_customer_id"
			:fromComponent="'shipmentBill'"
		/>

		<PaymentCompletedDialog
			:paymentsDialog="showPaymentsDialog"
			:editedItemData.sync="selectedInvoice"
			@close="showPaymentsDialog = false"
		/>

		<ViewPaymentDialog
			:viewitems="null"
			@makePayment="onToggleMakePaymentModal"
			:dialog.sync="showViewPaymentDialog"
			:editedIndex="-1"
			:editedItemData.sync="selectedInvoice"
			:isMobile="isMobile"
			@close="showViewPaymentDialog = false"
			:invoiceDetail="invoiceDetail"
			:getInvoiceDetailLoadingStatus="getInvoiceDetailLoadingStatus"
		/>

		<NoPaymentDialog
			:dialogShow.sync="NoPaymentDialogFlag"
			:fromShipmentBill="true"
			@close="closeNoPaymentDialog"
		/>
	</v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import globalMethods from "../utils/globalMethods";
import MakePaymentDialog from "../components/BillingComponents/Dialog/MakePaymentDialog.vue";
import PaymentCompletedDialog from "../components/BillingComponents/Dialog/PaymentCompletedDialog.vue";
import ViewPaymentDialog from "../components/BillingComponents/Dialog/ViewPaymentDialog.vue";
import moment from "moment";
import NoPaymentDialog from "../components/BillingComponents/Dialog/NoPaymentDialog.vue";
import { CUSTOMER_ADMIN } from "../constants/UserRoles";

export default {
	name: "ShipmentBill",
	components: {
		MakePaymentDialog,
		ViewPaymentDialog,
		PaymentCompletedDialog,
		NoPaymentDialog,
	},
	props: [],
	data() {
		return {
			selectedInvoices: [],
			tableHeader: [
				{
					text: "Invoice #",
					align: "middle",
					sortable: false,
					value: "invoice_number",
					class: "text-uppercase text-muted",
					width: "30%",
					fixed: true,
				},
				{
					text: "Due Date",
					align: "middle",
					sortable: false,
					value: "due_date",
					class: "text-uppercase text-muted",
					width: "20%",
					fixed: true,
				},
				{
					text: "Amount",
					align: "right",
					sortable: false,
					value: "balance",
					class: "text-uppercase text-muted",
					width: "25%",
					fixed: true,
				},
				{
					text: "",
					sortable: false,
					value: "actions",
					width: "25%",
					class: "text-uppercase text-muted",
					fixed: true,
				},
			],
			tableMobileHeader: [
				{
					text: "Invoice Date",
					sortable: false,
					value: "invoice_date",
					class: "text-uppercase text-muted",
				},
				{
					text: "Reference",
					sortable: false,
					value: "shipment_reference_number",
					class: "text-uppercase text-muted",
				},
				{
					text: "Due Date",
					sortable: false,
					value: "due_date",
					class: "text-uppercase text-muted",
				},
			],
			dialog: false,
			invoiceToPay: {
				invoice_no: "",
				invoice_date: "",
				shipment_reference: "",
				due_date: "",
				amuont: "",
			},
			showInvoiceView: false,
			selectedInvoice: {},
			showPaymentsDialog: false,
			showViewPaymentDialog: false,
			isMobile: false,
			NoPaymentDialogFlag: false,
			default_customer_id: 0,
		};
	},
	mounted() {
		this.fetchShipmentBills(this.$route.params.id);
		this.$store.dispatch("page/setPage", "shipments");
	},
	computed: {
		...mapGetters([
			"getUser",
			"getcards",
			"getcardsLoading",
			"getShipmentBillsLoading",
			"getShipmentBills",
			"getInvoice",
			"getInvoiceDetailLoadingStatus",
		]),
		total_due() {
			const totalAmount = this.getShipmentBills
				.filter((record) => Number(record.balance) > 0)
				.reduce((c, n) => c + Number(n.balance), 0);
			return totalAmount;
		},
		hasSelectedBalance() {
			const find = this.selectedInvoices.find(
				(record) => Number(record.balance) > 0
			);
			return !!find;
		},
		invoiceDetail() {
			return this.getInvoice;
		},
		userCanMakePayment() {
			let user = this.getUser;
			if (typeof user !== "object") {
				user = JSON.parse(this.getUser);
			}

			let roles = [];
			if (user.roles && user.roles.length > 0) {
				roles = user.roles.map((role) => role.name);
			}
			return roles.includes(CUSTOMER_ADMIN);
		},
	},
	methods: {
		...globalMethods,
		...mapActions([
			"fetchAllPaymentMethods",
			"fetchShipmentBills",
			"fetchInvoice",
			"downloadInvoice",
		]),
		onViewInvoice(data) {
			this.selectedInvoice = data;
			this.showInvoiceView = true;
		},
		closePaymentModal() {
			this.dialog = false;
		},
		onClearSelectedInvoices() {
			this.selectedInvoices = [];
		},
		async onToggleMakePaymentModal(item = null) {
			this.default_customer_id = this.defaultCustomerId();
			this.invoiceToPay =
				item === null
					? this.selectedInvoices.filter(
							(invoice) => Number(invoice.balance) > 0
					  )
					: item;

			const response = await this.fetchAllPaymentMethods(
				this.defaultCustomerId()
			);
			if (response.data.code !== 200) {
				this.NoPaymentDialogFlag = true;
				this.dialog = false;
			} else {
				this.NoPaymentDialogFlag = false;
				this.dialog = true;
			}
		},
		viewPayment(item) {
			this.fetchInvoice(item.id);
			this.selectedInvoice = Object.assign({}, this.invoiceDetail);
			this.showViewPaymentDialog = true;
		},
		monthDayYearFormat(date) {
			return moment.utc(date).format("MMM DD, YYYY");
		},
		paymentDialog(item) {
			if (item.paid == true) {
				this.selectedInvoice = Object.assign({}, item);
				this.showPaymentsDialog = true;
				this.onClearSelectedInvoices();
				this.fetchShipmentBills(this.$route.params.id);
			} else {
				this.showPaymentsDialog = false;
			}
		},
		closeView() {
			this.showInvoiceView = false;
			this.$nextTick(() => {
				this.selectedInvoice = {};
			});
		},
		async download(item) {
			if (item?.id) {
				await this.downloadInvoice(item);
			} else if (Array.isArray(item)) {
				item.forEach(async (id) => {
					await this.downloadInvoice(id);
				});
			}
		},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		closeNoPaymentDialog() {
			this.NoPaymentDialogFlag = false;
		},
	},
};
</script>

<style lang="scss" scoped>
$text-color: #6d858f;
$th-color: #819fb2;
$primary-color: #0171a1;

#shipment-invoice {
	min-height: calc(100vh - 305px);

	.shipment-bills-card-wrapper {
		border: 1px solid #ebf2f5 !important;
		border-radius: 4px;

		&.table-data {
			border-bottom: none !important;
		}

		&.mobile-bills-card {
			.v-card__text {
				padding: 20px 16px;

				h1 {
					margin-top: 10px !important;
					font-size: 20px !important;
				}
			}
		}
	}
}

.paid-button {
	height: 40px !important;
	display: flex;
	justify-content: center;
	align-items: center;
	border: 1px solid #b4cfe0 !important;
	padding: 10px 16px;
	min-width: 131px !important;

	.paid-text-img {
		display: flex;
		justify-content: center;
		align-items: center;
		letter-spacing: 0;
		color: #16b442 !important;
		font-weight: 500;
	}

	&.mobile {
		min-width: 82px !important;
	}
}

.paid {
	color: #16b442 !important;
}

.btn-disabled {
	background-color: #0171a1 !important;
}

::v-deep .v-data-table > .v-data-table__wrapper > table {
	.mdi-checkbox-blank-outline::before {
		content: "\F14FC";
		color: #b4cfe0;
	}

	thead {
		th {
			color: $th-color !important;
			border-bottom: 1px solid #ebf2f5 !important;
			box-shadow: none;
			height: 42px;
			padding: 8px 14px;

			.theme--light.v-icon {
				color: $primary-color;
			}

			&:nth-child(2) {
				padding-left: 0;
			}
		}
	}

	tbody {
		tr td {
			border-bottom: 1px solid #ebf2f5 !important;
			padding: 12px 12px !important;

			.v-simple-checkbox {
				padding-left: 2px;
			}

			.theme--light.v-icon {
				color: $primary-color;
			}

			label {
				color: #4a4a4a;
			}

			&:nth-child(2) {
				padding-left: 0 !important;
			}

			.mobile-checkbox {
				label {
					font-size: 14px !important;
					font-weight: 600;
				}
			}
		}

		tr.v-data-table__selected {
			background-color: #f0fbff !important;
		}
	}
}

.text-muted {
	color: $text-color !important;
}

.text-primary-color {
	color: $primary-color;
}

.fs-16 {
	font-size: 16px;
}

.fs-24 {
	font-size: 24px;
}

@media screen and (min-width: 320px) and (max-width: 768px) {
	#shipment-invoice  {
		min-height: calc(100vh - 378px) !important;
	}
}
</style>
