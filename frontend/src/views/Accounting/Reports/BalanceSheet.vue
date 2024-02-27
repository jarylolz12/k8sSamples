<template>
	<v-sheet outlined class="report-details-wrapper balance-sheet" :class="isLoading ? 'is-loading-wrapper' : ''">
		<div class="loading-wrapper" v-if="isLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

		<div v-if="!isLoading">		
			<div class="report-main-header">
				<v-breadcrumbs :items="breadCrumb" class="pl-0 px-3 pb-4 px-sm-0 pt-0">
					<template v-slot:item="{ item }">
						<v-breadcrumbs-item
							link
							:href="item.href"
							@click.prevent="$router.push(item.href)"
							:disabled="item.disabled">
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
							<h2 class="report-title">Balance Sheet</h2>
						</span>
					</div>
				</div>
			</div>

			<v-card flat :loading="isLoading">
				<v-row>
					<v-col cols="12" md="12" lg="12" class="pb-0" v-if="reportsData">
						<!-- <v-simple-table class="mb-12">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody v-for="item,i in reportsData.tableBody" :key="i" class="table-body-common">
								<tr>
									<td>{{ item.header }}</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<template v-for="rowItem, i2 in item.body" >
									<tr :key="'a'+i2">
										<td>{{ rowItem.name }}</td>
										<td></td>
										<td>{{ rowItem.trans_total }}</td>
										<td></td>
									</tr>
									<tr v-for="subRow,i3 in rowItem.sub_rows" :key="'b_'+i2+'_'+i3">
										<td></td>
										<td>{{ subRow.account_name }}</td>
										<td></td>
										<td>{{ subRow.trans_de_balance ? subRow.trans_de_balance : subRow.balance }}</td>
									</tr>
								</template>
							</tbody>
						</v-simple-table> -->


						<v-data-table
							:headers="headers"
							:items="reportsData.tableBody"
							:single-expand="singleExpand"
							:expanded.sync="expanded"
							hide-default-footer
							:items-per-page="300"
							item-key="id"
							show-expand
							class="balance-sheet-table-wrapper elevation-0">

							<template v-slot:[`item.header`]="{ item }">
								<span class="header-name font-semi-bold">{{ item.header }}</span>                                        
							</template> 

							<template v-slot:[`item.totalAmount`]="{ item }">
								<span class="font-semi-bold">{{ item.totalAmount }}</span>                                        
							</template>

							<template v-slot:expanded-item="{ headers, item }">
								<td :colspan="headers.length">									
									<v-data-table 
										:headers="headersSub"
										mobile-breakpoint="769"
										:items="item.body"
										class="balance-sheet-table-content elevation-0"
										hide-default-footer
										:items-per-page="300"
										fixed-header
										show-expand
										:single-expand="false"
										item-key="id">

										<template v-slot:expanded-item="{ headers, item }">
											<td :colspan="headers.length">
												<v-data-table 
													:headers="headersSub2"
													mobile-breakpoint="769"
													:items="item.sub_rows"
													class="balance-sheet-table-inner-content elevation-0"
													hide-default-footer
													fixed-header>

													<template v-slot:[`item.account_name`]="{ item }">
														<span style="color: #6D858F;">{{ item.account_name }}</span>                                        
													</template> 

													<template v-slot:[`item.balance`]="{ item }">
														<span>
															{{ item.trans_de_balance ? item.trans_de_balance : item.balance }}
														</span>                                        
													</template> 
												</v-data-table>
											</td>
										</template>
									</v-data-table>
								</td>
							</template>
						</v-data-table> 
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
		name: 'AccountingBalanceSheetReports',
		components: {
            // 
		},
		data() {
			return {
				data : {},
                reportsData: [],
				isLoading: false,
				isLoaded: false,
				expanded: [],
        		singleExpand: false,
				headers: [
					{
						text: '',
						align: "start",
						sortable: false,
						value: "header",
						width: "50%", 
						fixed: false
					},
					{
						text: '',
						align: "start",
						sortable: false,
						value: "",
						width: "", 
						fixed: false
					},
					{
						text: '',
						align: "start",
						sortable: false,
						value: "",
						width: "", 
						fixed: false
					},
					{
						text: '',
						align: "end",
						sortable: false,
						value: "totalAmount",
						width: "25%", 
						fixed: false
					},
					{
						text: '',
						align: "end",
						sortable: false,
						value: "",
						width: "8%", 
						fixed: false
					}
				],
				headersSub: [
					{ text: '', value: 'data-table-expand', width: "13%", align: 'end pr-1'},
					{
						text: '',
						align: "start",
						sortable: false,
						value: "name",
						width: "65%", 
						fixed: false
					},
					{
						text: '',
						align: "end",
						sortable: false,
						value: "trans_total",
						width: "", 
						fixed: false
					},
					{
						text: '',
						align: "start",
						sortable: false,
						value: "",
						width: "25%", 
						fixed: false
					},
				],
				headersSub2: [
					{
						text: '',
						align: "start",
						sortable: false,
						value: "",
						width: "18%", 
						fixed: false
					},
					{
						text: '',
						align: "start has-value pl-4",
						sortable: false,
						value: "account_name",
						width: "60%", 
						fixed: false
					},
					{
						text: '',
						align: "end has-value",
						sortable: false,
						value: "balance",
						width: "", 
						fixed: false
					},
					{
						text: '',
						align: "start has-value",
						sortable: false,
						value: "",
						width: "30%", 
						fixed: false
					},
				]
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
                    if(report && report.class === "Modules\\Shifl\\Reports\\ShiflBalanceSheet"){
                        const data = report.data;

						let tableData = [];
						Object.keys(data).forEach(function(key){
							if(key === 'de_classes'){
								Object.keys(data[key]).forEach(function(tableKey, i){
									let header = data[key][tableKey]['trans_name'];
									let body = [];
									let footer = [];
									let index = i + 1
									let totalAmount = data[key][tableKey]['total_trans'];
                                    
                                    Object.keys(data[key][tableKey]['types']).forEach(function(typeKey, i){
                                        let sub_rows = [];
										let index = i + 1
                                        if(data[key][tableKey]['types'][typeKey]['total'] != 0){

                                            Object.keys(data['de_accounts']).forEach(function(accountKey){
                                                if(data[key][tableKey]['types'][typeKey]['id'] == accountKey){
                                                    Object.keys(data['de_accounts'][accountKey]).forEach(function(k){
                                                        sub_rows.push({
                                                            account_name: data['de_accounts'][accountKey][k]['trans_name'],
                                                            balance: data['de_accounts'][accountKey][k]['de_balance'],
                                                            trans_de_balance: data['de_accounts'][accountKey][k]['trans_de_balance'],
                                                        });
                                                    });
                                                }
                                            });

                                            body.push({
                                                name: data[key][tableKey]['types'][typeKey]['trans_name'],
                                                total: data[key][tableKey]['types'][typeKey]['total'],
                                                trans_total: data[key][tableKey]['types'][typeKey]['trans_total'],
                                                sub_rows: sub_rows,
												id: index
                                            });

                                        }
                                    });
									tableData.push({
										header: header,
										body: body,
										footer: footer,
										id: index,
										totalAmount
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

