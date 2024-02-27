<!-- @format -->

<template>
	<div>
		<v-data-table
			:headers="headers"
			:items="items"
			sort-by="calories"
			class="methods-table elevation-1"
			hide-default-footer
			:page.sync="page"
			:items-per-page="itemsPerPage"
			:search="search"
			@page-count="pageCount = $event"
			mobile-breakpoint="769"
			v-if="activeTab == 2"
			:class="items !== null && items.length > 0 ? '' : 'no-data-table'"
		>
			<template v-slot:[`item.routing`]="{ item }">
				<div>
					<p class="mb-0">{{ achRouteFormat(item.routing, "*****") }}</p>
				</div>
			</template>
			<template v-slot:[`item.account`]="{ item }">
				<div>
					<p class="mb-0">{{ achAccountFormat(item.account, "*****") }}</p>
				</div>
			</template>
			<template v-slot:[`item.default`]="{ item }">
				<div>
					<p class="mb-0">{{ item.default === 1 ? "Default" : "-" }}</p>
				</div>
			</template>

			<template v-slot:[`item.actions`]="{ item }">
				<div class="manage-action-buttons">
					<v-btn class="btn-white icons" @click="editPaymentMethod(item)">
						<img src="../../../../../assets/icons/edit-blue.svg" alt="" />
					</v-btn>

					<v-btn class="btn-white icons" @click="deletePaymentMethod(item)">
						<img src="../../../../../assets/icons/delete-blue.svg" alt="" />
					</v-btn>
				</div>
			</template>

			<template v-slot:no-data>
				<div class="loading-wrapper mt-4" v-if="getACHPaymentMethodLoading">
					<v-progress-circular :size="40" color="#0171a1" indeterminate>
					</v-progress-circular>
				</div>

				<div
					class="no-data-wrapper pa-6"
					v-if="getACHPayments.length == 0 && !getACHPaymentMethodLoading"
				>
					<div class="no-data-heading">
						<img
							src="../../../../../assets/icons/payment-icon.svg"
							width="40px"
							height="42px"
							alt=""
						/>
						<h3>No Payment Method</h3>
						<p>
							You haven’t added any payment method.
							<br />Please add one to pay for the invoices.
						</p>
						<v-btn
							color="primary"
							dark
							class="btn-white payment-btn"
							@click="openACHPaymentMethodDialog"
						>
							Add ACH Payment Method
						</v-btn>
					</div>
				</div>
			</template>
		</v-data-table>
	</div>
</template>

<script>
import { mapGetters } from "vuex";
import globalMethods from "../../../../../utils/globalMethods";

export default {
	name: "ACHPaymentMethodDesktopTable",
	props: ["isMobile"],
	components: {},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "settings/manage-payment-methods");
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		search: "",
		headers: [
			{
				text: "Name",
				align: "start",
				sortable: false,
				value: "name",
				width: "20%",
				fixed: true,
			},
			{
				text: "Routing",
				align: "start",
				sortable: false,
				value: "routing",
				width: "30%",
				fixed: true,
			},
			{
				text: "Account",
				align: "start",
				sortable: false,
				value: "account",
				width: "30%",
				fixed: true,
			},
			{
				text: "",
				align: "end",
				sortable: false,
				value: "actions",
				width: "20%",
				fixed: true,
			},
		],
		activeTab: 2,
		cardDetail: "",
	}),
	computed: {
		...mapGetters({
			getACHPaymentMethodLoading: "getACHPaymentMethodLoading",
			getACHPayments: "getACHPayments",
		}),
		paymentMethods() {
			let data = this.getACHPayments.filter(
				(payment) => payment.tab === this.tabs[this.activeTab]
			);
			return data;
		},
		items() {
			if (
				!(Array.isArray(this.getACHPayments) && this.getACHPayments.length >= 1)
			) {
				return [];
			}

			return this.getACHPayments.map((item) => {
				return {
					record_id: item.id,
					name: item.name,
					routing: item.routing,
					account: item.account,
					is_default: item.is_default,
					tab: "Payment Methods",
				};
			});
		},
	},
	watch: {},
	methods: {
		...globalMethods,
		getCurrentTab(id) {
			console.log(id);
		},
		onTabChange() {
			this.page = 1;
		},
		openACHPaymentMethodDialog() {
			this.$emit("openACHPaymentMethodDialog");
		},
		editPaymentMethod(payment) {
			this.$emit("editPaymentMethod", payment);
		},
		close() {
			this.$emit("close");
		},
		deletePaymentMethod(item) {
			this.$emit("deletePaymentMethod", item);
		},
		closeDelete() {
			this.$emit("closeDelete");
		},
		save() {
			if (this.editedIndex > -1) {
				Object.assign(this.desserts[this.editedIndex], this.editedItem);
			} else {
				this.desserts.push(this.editedItem);
			}
			this.close();
		},
		handleManagePayment() {
			this.$router.push(`billing/manage-payment-methods`);
		},
		managePayment(item) {
			console.log(item);
		},
		getImgUrl(pic) {
			if (pic !== "undefined" && pic !== null) {
				return require(`../../../../../assets/icons/${pic}.svg`);
			} else {
				return require("../../../../../assets/icons/default-product-icon.svg");
			}
		},
	},
};
</script>

<style lang="scss">
@import "../../../../../assets/scss/pages_scss/settings/managePayment.scss";
@import "../../../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../../../assets/scss/buttons.scss";
</style>
