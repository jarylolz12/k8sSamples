<template>
	<v-sheet class="vue-app account-settings-wrapper">
		<v-card flat>
			<v-card-text>
				<v-row>
					<v-col cols="12" md="6" lg="8" class="pb-0">
						<v-card flat>
							<v-card-text class="d-flex align-center">
								<v-btn
									text
									class="primary"
									rounded
									color="white"
									style="background-color: #0171A1 !important; border-radius: 4px"
									width="48"
									height="48"
									fab
									:disabled="!QBOInformation"
									@click="componentWasClicked('quickbookssettings')">
									<v-icon size="30" color="white" class="icon">mdi-domain</v-icon>
								</v-btn>

								<div class="d-flex justify-start align-center flex-grow ml-3">
									<h4 class="font-weight-bold primary--text mr-2 settings-account-title mb-0">
										Quickbooks 
										<span v-if="QBOInformation" class="ml-2 mr-1"> - </span>
									</h4>

									<span class="font-weight-bold" style="color: #4a4a4a;" v-if="QBOInformation">
										{{ qboCompanyName }}
									</span>

									<v-btn
										v-if="QBOInformation"
										outlined
										class="d-flex mt-1 btn-white ml-4"
										small
										color="red white--text"
										@click="onQBOSignOut"
										:loading="isQBOSigningOut"
										style="color: red !important;">
										<v-icon class="mr-2">mdi-power</v-icon>
										<h5 class="text-capitalize" style="color: red !important;">Disconnect</h5>
									</v-btn>

									<v-btn
										v-else
										outlined
										class="d-flex btn-white"
										color="primary"
										small
										@click="onSignIn"
										:loading="isFetchingSignInURL || isCheckingQBO">
										<v-icon class="mr-2">mdi-login-variant</v-icon>
										<h5>Connect</h5>
									</v-btn>
								</div>
							</v-card-text>
						</v-card>
					</v-col>
				</v-row>

				<v-row>
					<v-col cols="12" md="6" lg="4" v-for="link in linkSettings" :key="link.title">
						<v-card
							v-if="!link.hidden"
							class="setting-list-component"
							@click="componentWasClicked(link.component)"
							flat
							:disabled="!link.component">

							<v-card-text class="d-flex align-start pb-1">
								<v-icon size="30" color="white" class="icon primary pa-2" style="border-radius: 4px; background-color: #0171A1 !important;">
									{{ link.icon }}
								</v-icon>
								<div class="flex-grow ml-4">
									<h4 class="font-weight-bold primary--text settings-account-title">{{ link.title }}</h4>
									<span class="settings-account-description">{{ link.description }}</span>
								</div>
							</v-card-text>
						</v-card>
					</v-col>
				</v-row>
			</v-card-text>
		</v-card>

		<v-dialog v-model="showDialog" max-width="1366" scrollable content-class="accounting-module-dialog" persistent :fullscreen="isMobile">
			<v-card flat>
				<v-card-title class="pa-0 z-index-front">
					<span class="headline">{{ settingTitle }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="closeDialog">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>

				<v-card-text class="pa-0">
					<currencies v-if="componentActive('currencies')"></currencies>
					<quickbooks-settings v-if="componentActive('quickbookssettings')"></quickbooks-settings>
					<category-settings v-if="componentActive('categorysettings')"></category-settings>
					<tax-settings v-if="componentActive('tax')"></tax-settings>
				</v-card-text>
			</v-card>
		</v-dialog>
	</v-sheet>
</template>

<script>
import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
import { apiErrorMessage } from '../../utils/globalMethods';
import Currencies from '../../components/Accounting/Currencies.vue';
import QuickbooksSettings from '../../components/Accounting/QuickbooksSettings.vue';
import CategorySettings from '../../components/Accounting/Category.vue';
import TaxSettings from '../../components/Accounting/Taxes.vue';
export default {
	name: 'AccountingSettings',
	components: {
		Currencies,
		QuickbooksSettings,
		CategorySettings,
		TaxSettings
	},
	data: () => ({
		isFetchingSignInURL: false,
		isQBOSigningOut: false,
		selectedComponent: '',
		showDialog: false,
		linkSettings: [
			{
				title: 'Company',
				description: 'Change company name, email address, tax number etc',
				icon: 'mdi-office-building',
				component: ''
			},
			{
				title: 'Localisation',
				description: 'Set fiscal year, time zone, date format and more locals',
				icon: 'mdi-map-marker',	
				component: ''
			},
			{
				title: 'Invoice',
				description: 'Customize invoice prefix, number, terms, footer etc',
				icon: 'mdi-file-document',
				component: ''
			},
			{
				title: 'Default',
				description: 'Default account, currency, language of your company',
				icon: 'mdi-tune',
				component: ''
			},
			{
				title: 'Categories',
				description: 'Unlimited categories for income, expense, and item',
				icon: 'mdi-folder',
				component: 'categorysettings',
				active: true
			},
			{
				title: 'Currencies',
				description: 'Create and manage currencies and set their rates',
				icon: 'mdi-currency-usd',
				component: 'currencies',
				active: true
			},
			{
				title: 'Taxes',
				description: 'Fixed, normal, inclusive, and compound tax rates',
				icon: 'mdi-percent',
				component: 'tax',
				active: true
			},
			{ 
				title: 'Quickbooks Settings',
				icon: '',
				component: 'quickbookssettings',
				hidden: true
			}
			// { title: ('taxes_title'), description: ('taxes_description'), icon: 'mdi-percent', component: '', },
			// { title: ('paypal_standard_title'), description: ('paypal_standard_description'), icon: 'mdi-alpha-p', component: '' },
			// { title: ('offline_payments_title'), description: ('offline_payments_description'), icon: 'mdi-credit-card-outline', component: '' },
		]
	}),
	computed: {
		...mapState('accounting', ['isCheckingQBO', 'QBOInformation']),
		...mapGetters('accounting', ['isQBOEnabled']),
		settingTitle() {
			if (this.selectedComponent) {
				const component = this.linkSettings.find((setting) => setting.component === this.selectedComponent);
				return component.title;
			}
			return 'Settings';
		},

		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		},

		qboCompanyName() {
			return this.QBOInformation?.CompanyName;
		}
	},

	created() {
		if (this.QBOInformation === null) {
			this.fetchQBO();
		}
	},

	methods: {
		...mapActions('accounting', ['getQBOConnection', 'getQuickbooksLoginUrl', 'setLastUrlPath', 'signOutQBO']),

		...mapMutations('accounting', ['updateQBO']),

		closeDialog() {
			this.showDialog = false;
			this.selectedComponent = '';
		},
		componentWasClicked(component) {
			if (component) {
				this.selectedComponent = component;
				this.showDialog = true;
			}
		},
		componentActive(str) {
			return this.selectedComponent === str ? true : false;
		},
		async fetchQBO() {
			try {
				const { data, message } = await this.getQBOConnection();
				if (!data) {
					apiErrorMessage(message);
				} else {
					this.updateQBO(data);
				}
			} catch (error) {
				apiErrorMessage('qbo_fetch_error');
			}
		},

		async onSignIn() {
			if (this.isFetchingSignInURL) {
				return;
			}

			this.isFetchingSignInURL = true;
			try {
				this.setLastUrlPath(this.$route.path);
				const url = await this.getQuickbooksLoginUrl();

				// Already signed-in
				if (url) {
					window.open(url, '_self');
				}

				// Already connected
				if (url === null) {
					this.fetchQBO();
				}
			} catch (error) {
				apiErrorMessage(error);
				this.isFetchingSignInURL = false;
			}
		},

		async onQBOSignOut() {
			if (this.isQBOSigningOut) {
				return;
			}

			this.isQBOSigningOut = true;

			try {
				const { message } = await this.signOutQBO();
				this.updateQBO(null);
				this.messageBox(message);
				this.isQBOSigningOut = false;
			} catch (error) {
				this.isQBOSigningOut = false;
				apiErrorMessage(error);
			}
		},

		messageBox() {
			/* Notification.closeAll();
	Notification({
	message,
	type: 'info',
	}); */
		}
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
	.settings {
		.v-application--wrap {
			min-height: auto !important;
		}
	}
	.setting-list-component:hover {
		transition: 0.4s ease-in-out;
		background-color: var(--shifl-light-grey);
	}
</style>

<style lang="scss">
.account-settings-wrapper {
	height: 100%;
	border: 1px solid #EBF2F5;
	border-radius: 4px;

	.settings-account-title {
		color: #0171a1 !important;
		margin-bottom: 5px;
	}

	.settings-account-description {
		color: #4a4a4a !important;
		font-family: 'Inter-Regular', sans-serif;
		line-height: 18px;
	}
}
</style>
