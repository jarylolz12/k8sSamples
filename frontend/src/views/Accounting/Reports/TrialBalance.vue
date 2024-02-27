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
							<h2 class="report-title">Trial Balance</h2>
						</span>
					</div>
				</div>
			</div>

			<v-card flat :loading="isLoading" class="mt-4 pa-4">
				<v-row>
					<v-col cols="12" md="12" lg="12" class="pb-0" v-if="reportsData">
						<v-simple-table class="mb-12">
							<thead>
								<tr>
									<th>Description</th>
									<th></th>
									<th class="text-end">Debit</th>
									<th class="text-end">Credit</th>
								</tr>
							</thead>
							<tbody v-for="item,i in reportsData.tableBody" :key="i" class="table-body-common">
								<tr>
									<td class="font-semi-bold">{{ item.header }}</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr v-for="rowItem, i2 in item.body" :key="i2">
									<td>{{ rowItem.name }}</td>
									<td></td>
									<td v-for="colItem, i3 in rowItem.values" :key="'a'+i3" class="text-end">{{ colItem }}</td>
								</tr>
								<tr v-for="footerItem, fI in item.footer" :key="'b'+fI">
									<td>Total</td>
									<td></td>
									<td v-for="colItem, fI2 in footerItem.value" :key="'c'+fI2" class="text-end"> {{ colItem }}</td>
								</tr>
							</tbody>
							<tbody>
								<tr>
									<td></td>
									<td class="text-end font-semi-bold">Total</td>
									<td class="text-end">{{reportsData.contentFooter ? reportsData.contentFooter.debit : ''}}</td>
									<td class="text-end">{{reportsData.contentFooter ? reportsData.contentFooter.credit : ''}}</td>
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
		name: 'AccountingTrialBalanceReports',
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
                    if(report && report.class === "Modules\\Shifl\\Reports\\ShiflTrialBalance"){
                        const data = report.data;

						let tableData = [];
						Object.keys(data).forEach(function(key){
							if(key === 'tables'){
								Object.keys(data[key]).forEach(function(tableKey){
									let header = data[key][tableKey];
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
										}										
									})
									tableData.push({
										header: header,
										body: body,
										footer: footer,
									});
								});
							}
						});
						reportsData = {
							tableBody : tableData,
							contentFooter: data.content_footer_total,
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

