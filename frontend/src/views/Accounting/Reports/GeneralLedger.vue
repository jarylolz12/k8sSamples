<template>
	<v-sheet outlined class="report-details-wrapper general-ledger" :class="isLoading ? 'is-loading-wrapper' : ''">
		<div class="loading-wrapper" v-if="isLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

		<div v-if="!isLoading">
			<div class="report-main-header">
				<v-breadcrumbs :items="breadCrumb" class="pl-0 pt-0 px-3 pb-4 px-sm-0">
					<template v-slot:item="{ item }">
						<v-breadcrumbs-item
							link
							:href="item.href"
							@click.prevent="$router.push(item.href)"
							:disabled="item.disabled"
						>
							{{ item.text }}
						</v-breadcrumbs-item>
					</template>
					<template v-slot:divider>
						<v-icon>mdi-chevron-right</v-icon>
					</template>
				</v-breadcrumbs>
				
				<div class="d-md-flex d-sm-flex justify-space-between align-center report-details-header">
					<div class="px-3 px-sm-0">
						<span class="report-title text-h5">
							<h2 class="report-title">{{ 'General Ledger' }}</h2>
						</span>
					</div>
				</div>
			</div>

			<v-card flat :loading="isLoading" class="mt-4 pa-4">
				<v-row>
					<v-col cols="12" md="12" lg="12" class="pb-0" v-for="report,i in reportsData" :key="i">
						<v-simple-table class="mb-12">
							<thead>
								<tr>
									<th style="width: 35%; min-width: 35%;">Date</th>
									<th style="width: 15%; min-width: 15%;">Transaction</th>
									<th style="width: 15%; min-width: 15%;" class="text-end">Debit</th>
									<th style="width: 15%; min-width: 15%;" class="text-end">Credit</th>
									<th style="width: 15%; min-width: 15%;" class="text-end">Balance</th>
								</tr>
							</thead>
							<tbody>
								<tr class="body-header">
									<td style="color: #4a4a4a;" class="font-semi-bold">{{ report.header }}</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="opening-balance-header font-semi-bold">Opening Balance</td>
									<td></td>
									<td></td>
									<td></td>
									<td class="text-end">{{ report.opening_balance }}</td>
								</tr>
								<tr v-for="ledger, i2 in report.data" :key="i2">
									<td>{{ ledger.issued_at }}</td>
									<td>{{ ledger.transaction }}</td>
									<td class="text-end">{{ ledger.debit || '--' }}</td>
									<td class="text-end">{{ ledger.credit || '--' }}</td>
									<td class="text-end">{{ ledger.balance || '--' }}</td>
								</tr>
								<tr>
									<td class="font-12 font-semi-bold text-uppercase">Totals and Closing Balance</td>
									<td></td>
									<td class="text-end">{{report.footer.debit}}</td>
									<td class="text-end">{{report.footer.credit}}</td>
									<td class="text-end">{{report.footer.balance}}</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td class="font-12 font-semi-bold text-uppercase text-end">Balance Change</td>
									<td class="text-end">{{report.footer.balance_change}}</td>
								</tr>
							</tbody>
						</v-simple-table>
					</v-col>

					<v-divider class="mx-4" vertical></v-divider>
				</v-row>
			</v-card>
		</div>
	</v-sheet>
</template>

<script>
import AkauntingService from '../../../services/akaunting.service';
import { apiErrorMessage } from '../../../utils/globalMethods';

export default {
	name: 'AccountingGeneralLedgerReports',
	components: {
		// 
	},
	data() {
		return {
			data : {},
			reportsData: [],
			isLoading: false,
			isLoaded: false,
			headers: [
				{
					text: 'DATE',
					align: "start",
					sortable: false,
					value: "",
					width: "", 
					fixed: false
				},
				{
					text: 'TRANSACTION',
					align: "start",
					sortable: false,
					value: "",
					width: "", 
					fixed: false
				},
				{
					text: 'DEBIT',
					align: "end",
					sortable: false,
					value: "",
					width: "", 
					fixed: false
				},
				{
					text: 'CREDIT',
					align: "end",
					sortable: false,
					value: "",
					width: "", 
					fixed: false
				},
				{
					text: 'BALANCE',
					align: "end",
					sortable: false,
					value: "",
					width: "", 
					fixed: false
				}
			],
		};
	},

	computed: {

		reportId() {
			return this.$route.params.id;
		},

		breadCrumb() {
			return [
				{
					text: 'Accounting',
					disabled: true,
					href: '#'
				},
				{
					text: 'Reports',
					disabled: false,
					href: '/accounting/reports'
				},
				{
					text: this.data.name || '',
					disabled: true,
					href: '#'
				}
			];
		},

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},
	},

	created() {
		this.loadReport();
	},

	methods: {
		async loadReport() {
			if (this.isLoading) {
				return;
			}
			this.isLoading = true;
			try {
				const records = await AkauntingService.getReport(this.reportId);
				const report = records.data.data || {};
				let reportsData = [];
				if(report && report.class === "Modules\\Shifl\\Reports\\ShiflGeneralLedger"){
					const data = report.data;
					Object.keys(data).forEach(function(key){
						if(key === 'tables'){
							Object.keys(data[key]).forEach(function(tableKey, i){
								let index = i + 1
								Object.keys(data['row_values']).forEach(function(rowKey){
									if(rowKey === tableKey){
										reportsData.push({
											opening_balance: data['opening_balances'][rowKey],
											header: data[key][tableKey],
											data: data['row_values'][rowKey],
											footer: data['footer_totals'][rowKey],
											id: index
										});
									}
								});
							});
						}
					});
					this.data = report;
					this.reportsData = reportsData;
				}
				this.isLoaded = true;
			} catch (error) {
				console.log(error);
				apiErrorMessage(error);
				this.isLoaded = true;
			} finally {
				this.isLoading = false;
				this.isLoaded = true;
			}
		},
	}
};
</script>

<style lang="scss" scoped>
	@media screen and (max-width: 767px) {
		::v-deep {
			.v-dialog .v-card .v-card__text {
				margin-bottom: 0 !important;
			}
		}
	}
</style>

<style lang="scss" scoped>
	$button-bg-color: #0171a1;

	.btn-primary {
		background-color: $button-bg-color !important;
		color: #fff !important;
	}

	.activity-color {
		color: #0b6084;
	}

	.activity-color.in-active-activity {
		color: #b3cfe0;
	}
</style>

<style lang="scss">
    @import '../scss/reportsDetails.scss';
</style>

