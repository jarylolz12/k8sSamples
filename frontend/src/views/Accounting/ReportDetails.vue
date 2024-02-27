<template>
	<v-sheet outlined class="report-details-wrapper">
		<div class="report-main-header">
			<v-breadcrumbs :items="breadCrumb" class="pl-0 px-3 pb-2 px-sm-0">
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
						<h2 class="report-title">{{data.name}}</h2>
					</span>
				</div>
			</div>
		</div>

		<v-card flat :loading="isLoading" class="mt-4">
			<v-sheet v-if="data && data.name === 'General Ledger'">
				<general-ledger :reportData="data.reports"></general-ledger>
			</v-sheet>
			<v-sheet v-else-if="isLoaded">
				<h3>Work in progress, this feature will be available soon.</h3>
			</v-sheet>
			<v-sheet v-else>
				<h2>Loading...</h2>
			</v-sheet>
		</v-card>
	</v-sheet>
</template>

<script>
import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
import AkauntingService from '../../services/akaunting.service';
import { apiErrorMessage } from '../../utils/globalMethods';
import GeneralLedger from '../../components/Accounting/Reports/GeneralLedger.vue';

export default {
	name: 'AccountingReportDetails',
	components: {
		GeneralLedger,
	},
	data() {
		return {
			data : {},
			isLoading: false,
			isLoaded: false,
		};
	},

	computed: {
		...mapState('accounting', ['isCheckingQBO', 'QBOInformation']),
		...mapGetters('accounting', ['isQBOEnabled']),

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
		...mapActions('accounting', ['getQBOConnection', 'getQuickbooksLoginUrl', 'setLastUrlPath', 'signOutQBO']),

		...mapMutations('accounting', ['updateQBO']),

		async loadReport() {
			if (this.isLoading) {
				return;
			}
			this.isLoading = true;
			try {
				const records = await AkauntingService.getReport(this.reportId);
				this.data = records.data.data	 || {};
				this.isLoaded = true;
			} catch (error) {
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

<style lang="scss">
		
.report-details-wrapper {    
    height: 100%;
    background-color: #fff !important;
    border: none !important;
    border-radius: 4px;

    .report-main-header {
        background-color: #F1F6FA !important;
        padding-bottom: 15px;

        .v-breadcrumbs {
            li {
                &.v-breadcrumbs__divider {
                    padding: 0 6px;
                }
    
                a {
                    color: #4a4a4a;
                    font-size: 12px;
                    text-decoration: none;
                    font-family: "Inter-Medium", sans-serif;
    
                    &.v-breadcrumbs__item--disabled {
                        color: #6d858f!important;
                    }
                }
            }
        }

        .report-details-header {
            .report-title {
                margin-right: 8px;
                color: #4a4a4a;
                line-height: 0;
                letter-spacing: 0;
                font-family: 'Inter-Medium', sans-serif !important;
                display: flex;
                justify-content: flex-start;
                align-items: center;
    
                .report-title {
                    font-family: 'Inter-Medium', sans-serif !important;
                    font-size: 24px !important;
                }
    
                .v-chip {
                    font-size: 12px;
                    height: 28px;
                    text-transform: capitalize !important;
    
                    .v-chip__content {					
                        font-family: 'Inter-Regular', sans-serif;
                    }
    
                    &.draft {
                        background-color: #fff !important;
                        border: 1px solid #EBF2F5;
                        color: #819FB2 !important;
                    }
                }
            }
        }
    }

    .v-card {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;

        .v-card__text {
            padding-bottom: 24px;
            color: #4a4a4a;

            .form-label {
                font-size: 12px !important;
                color: #819fb2 !important;
                font-family: "Inter-SemiBold", sans-serif !important;
                text-transform: uppercase;
                margin-bottom: 5px;
                letter-spacing: 0 !important;
                line-height: 18px;
            }

            .subtitle-1 {
                font-family: "Inter-Regular", sans-serif !important;
                color: #4a4a4a;
                font-size: 14px !important;
            }
        }

        .v-toolbar {
            border: 1px solid #EBF2F5;
            border-left: none;
            border-right: none;
            font-family: 'Inter-Regular', sans-serif;
            height: 56px !important;

            .v-toolbar__content {
                height: 56px !important;

                .v-tabs {
                    .v-tab {
                        letter-spacing: 0;
                        text-transform: capitalize;
                        font-family: "Inter-SemiBold" ,sans-serif;
                        color: #4a4a4a;
                        font-size: 14px;
    
                        &.v-tab--active {
                            color: #0171a1;
                        }
                    }
                }
            }            
        }
    }
}
</style>
