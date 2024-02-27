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
				<v-breadcrumbs :items="breadCrumb" class="pl-0 px-3 pb-4 pt-0 px-sm-0">
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
							<h2 class="report-title">Expense Summary</h2>
						</span>
					</div>
				</div>
			</div>

			<v-card flat :loading="isLoading" class="mt-4 pa-4">
				<v-row  v-if="reportsData">
					<v-col cols="6" md="6" lg="6" class="pb-0">
						<v-simple-table class="mb-12">
							<thead>
								<tr>
									<th>Description</th>
									<th class="text-end">Amount</th>
								</tr>
							</thead>
							<tbody v-for="item,i in reportsData.tableBody" :key="i">
								<tr>
									<td class="font-semi-bold text-uppercase font-12">{{ reportsData.tableHeader ? reportsData.tableHeader.title : '' }}</td>
									<td class="text-end">{{ reportsData.tableHeader ? reportsData.tableHeader.total : '' }}</td>
								</tr>
								<tr v-for="rowItem, i2 in item.body" :key="i2">
									<td>{{ rowItem.name }}</td>
									<td class="text-end">{{ rowItem.total }}</td>
								</tr>
							</tbody>
						</v-simple-table>
					</v-col>
					<v-col cols="6" md="6" lg="6" class="pb-0" v-if="reportsData"></v-col>
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
	name: 'AccountingExpenseSummaryReports',
	components: {
		// 
	},
	data() {
		return {
			data : {},
			reportsData: [],
			isLoading: false,
			isLoaded: false,
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
				if(report && report.class === "App\\Reports\\ExpenseSummary"){

					const data = report.data;
					let mainHeader = [];
					let tableData = [];
					let total = 0;

					Object.keys(data).forEach(function(key){
						if(key === 'tables'){
							Object.keys(data[key]).forEach(function(tableKey){
								let body = [];
								let footer = [];
								Object.keys(data['row_values'][tableKey]).forEach(function(rowKey){
									let total = 0;
									Object.keys(data['row_values'][tableKey][rowKey]).forEach(function(k3){
										total = total + data['row_values'][tableKey][rowKey][k3];
									});
									if(total > 0){
										body.push({
											name : data['row_names'][tableKey][rowKey],
											values : data['row_values'][tableKey][rowKey],
											total: total,
										});
									}
								});
								Object.keys(data['footer_totals']).forEach(function(footerKey){
									if(footerKey === tableKey){
										let footerTotal = 0;
										Object.keys(data['footer_totals'][footerKey]).forEach(function(k4){
											footerTotal = footerTotal + data['footer_totals'][footerKey][k4];
										});
										footer.push({
											value : data['footer_totals'][footerKey],
											total: footerTotal,
										});
										total = total + footerTotal;
									}										
								})
								tableData.push({
									body: body,
									footer: footer,
								});
								
								mainHeader = {
									title : data[key][tableKey],
									total: total
								};
							});
						}
					});
					reportsData = {
						tableHeader : mainHeader,
						tableBody : tableData,
					};
				}
				this.data = report;
				this.reportsData = reportsData;
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

