<template>
	<v-sheet class="accounting-reports-wrapper" :class="isLoading ? 'is-loading-wrapper' : ''">
		<h3 class="title mb-2 ml-2 mt-3 ml-sm-0 mt-sm-0">Accounting Report</h3>
		<p class="pb-4">Get a clear picture of how your business is doing</p>

		<v-divider></v-divider>

		<div class="loading-wrapper" v-if="isLoading">
            <v-progress-circular
                :size="40"
                color="#0171a1"
                indeterminate>
            </v-progress-circular>
        </div>

		<v-card flat color="transparent" class="mt-3" v-if="!isLoading">
			<v-row>
				<v-col cols="12" md="6" lg="4" v-for="(report, i) in allowedReports" :key="i">
					<v-card
						v-if="report.name"
						:disabled="!report.allowed"
						class="list-items"
						@click.prevent="$router.push(`/accounting/reports/${report.route}/${report.id}`)"
						flat>

						<v-card-text class="d-flex align-start pa-3 mt-2">
							<!-- <v-icon size="30" color="white" class="icon primary pa-2" style="border-radius: 10px; background-color: #0171A1 !important;">
								{{ report.icon }}
							</v-icon> -->

							<div class="flex-grow">
								<p class="font-semi-bold primary--text list-item-title d-flex justify-start align-center">
									{{ report.name }}
									<v-icon
										size="24"
										color="#0171A1"
										class="ml-1">
										mdi-chevron-right
									</v-icon>
								</p>
								<span class="list-item-description">{{ report.description }}</span>
							</div>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-card>
	</v-sheet>
</template>

<script>
import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
import { apiErrorMessage } from '../../utils/globalMethods';
import AkauntingService from '../../services/akaunting.service';

export default {
	name: 'AccountingReports',
	components: {
		// 
	},
	data() {
		return {
			reportList: [],
			allowedList: [
				{ name : 'General Ledger', route: 'general-ledger'},
				{ name : 'Profit & Loss (Chart of Account)', route: 'profit-and-loss'},
				{ name : 'Trial Balance', route: 'trial-balance'},
				{ name : 'Balance Sheet', route: 'balance-sheet'},
				{ name : 'Income Summary (Chart of Account)', route: 'income-summary'},
				{ name : 'Expense Summary (Chart of Account)', route: 'expense-summary'},
			],
			isLoading: false
		};
	},

	computed: {
		...mapState('accounting', ['isCheckingQBO', 'QBOInformation']),
		...mapGetters('accounting', ['isQBOEnabled']),

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},

		allowedReports(){
			let _dt = [];
			this.reportList.forEach(function(v){
				if(v.created_from === "shifl::seed"){
					let allowed = false;
					let route = null;
					this.allowedList.forEach(function(d){
						if(v.name === d.name){
							allowed = true;
							route = d.route;
						}
					});
					_dt.push({
						...v,
						allowed: allowed,
						route: route,
					});
				}
			},this)
			return _dt;
		}

	},

	created() {
		this.fetchReportList();
	},

	methods: {
		...mapActions('accounting', ['getQBOConnection', 'getQuickbooksLoginUrl', 'setLastUrlPath', 'signOutQBO']),

		...mapMutations('accounting', ['updateQBO']),

		async fetchReportList() {
			this.isLoading = true
			try {
				const { data, message } = await AkauntingService.getReportsList();
				if (!data) {
					apiErrorMessage(message);
					this.isLoading = false
				} else {
					const reports = data.data;
					this.reportList = reports.data;
					this.isLoading = false
				}
			} catch (error) {
				apiErrorMessage('qbo_fetch_error');
				this.isLoading = false
			}
		},
		
		onReportClick(data){
			console.log(data);
		},
			
		isDisabled(name){
			const itemName = name;
			this.disabledList.forEach(function(v){
				console.log(itemName == v)
				if(itemName === v) {
					return true;
				}
				
				return false;
			})
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
	.accounting-reports-wrapper {
		background-color: #fff;
		height: calc(100vh - 112px);
		padding: 24px;
		border-radius: 4px;

		&.is-loading-wrapper {
			width: 100%;

			.loading-wrapper {
				width: 100%;
				min-height: 200px;
				display: flex;
				justify-content: center;
				align-items: center;
			}
		}
	}

	.title {
		font-size: 24px !important;
		margin-bottom: 0;
		color: #4a4a4a;
		font-family: 'Inter-SemiBold', sans-serif !important;
		letter-spacing: 0 !important;
	}
	.list-items:hover {
		transition: 0.4s ease-in-out !important;
		background-color: var(--shifl-light-grey);
	}

	.list-item-title {
		color: #0171A1 !important;
		font-size: 14px;
		margin-bottom: 6px;
	}

	.list-item-description {
		color: #4a4a4a;
		font-size: 14px;
	}
</style>

<style lang="scss">
.reports-wrapper {
	.list-item-title {
		color: #0171a1 !important;
		margin-bottom: 5px;
	}

	.list-item-description {
		color: #4a4a4a !important;
		font-family: 'Inter-Regular', sans-serif;
		line-height: 18px;
	}
}
</style>
