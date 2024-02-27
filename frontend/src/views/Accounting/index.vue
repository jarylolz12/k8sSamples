<template>
	<v-container fluid class="pa-sm-6 pa-0 accounting-item-container__wrapper">
		<v-sheet v-if="isPreparingAccountingInfo" color="transparent">
			<v-card class="text-center accounting-index-card" flat :loading="isPreparingAccountingInfo">
				<v-card-text>
					<v-row align="center" class="h-100">
						<v-col>
							<h3 v-if="isCreatingCompany" class="header-4 mb-4">
								Setting up your accounting information, Please wait...
							</h3>
							<h3 v-else-if="isCheckingQBO" class="header-4 mb-4">
								Loading Quickbooks Information, Please wait...
							</h3>
							<h3 v-else class="header-4 mb-4">
								Loading Accounting Information, Please wait...
							</h3>
						</v-col>
					</v-row>
				</v-card-text>
			</v-card>
		</v-sheet>
		<v-sheet v-else-if="isQBOSignInRequired" color="transparent">
			<v-card class="text-center accounting-index-card" flat :loading="isCheckingQBO">
				<v-card-text>
					<v-row align="center" class="h-100">
						<v-col>
							<h4 class="header-4 mb-4">
								You are not connected to QuickBooks. Please click the button below to connect.
							</h4>
							<v-btn color="primary" class="text-capitalize btn-blue mx-auto" @click="onQuickbooksSignIn" :loading="isCheckingQBO">
								<v-icon class="mr-2">mdi-login-variant</v-icon>
								Connect
							</v-btn>
						</v-col>
					</v-row>
				</v-card-text>
			</v-card>
		</v-sheet>
		<div
			v-else-if="(companyInformation && !isQBOSignInRequired) || inSettingsPage"
			class="widgetContainer widgetContainer--new full-width dashboard-router-view accounting-module"
		>
			<router-view />
		</div>
	</v-container>
</template>

<script>
	import { mapActions, mapState, mapMutations, mapGetters } from 'vuex';
	import { apiErrorMessage } from '../../utils/globalMethods';

	export default {
		name: 'Accounting',
		data: () => {
			return {
				isCreatingCompany: false,
				errorMessage: '',
				isFetchingSignInURL: false,
				isCheckingQBO: false,
				isPreparingAccountingInfo: true,
				isQBOSignInRequired: false,
				isSignedIn:false,
			};
		},
		created() {
			if(this.getUser){
				this.isSignedIn = true;
				this.getAccountingInfo();

				if (this.companyInformation && this.companyInformation?.accounting_company?.qbo_realm_id && !this.QBOInformation) {
					this.isQBOSignInRequired = true;
				}
				if(this.$route.path === "/accounting/quickbooks/token"){
					this.isQBOSignInRequired = false;
				}
			}else{
				// this.loginAccounting();
			}			
		},
		mounted() {
			this.$store.dispatch('page/setPage', 'accounting');
		},
		computed: {
			...mapState('accounting', ['companyInformation', 'isCheckingAccounting', 'QBOInformation']),
			...mapGetters({ getUserToken: "getUserToken", getUser: "getUser", }),
			showConnectQBO: {
				get() {
					return (
						this.companyInformation &&
						this.companyInformation?.accounting_company?.qbo_realm_id &&
						!this.QBOInformation
					);
				},
				set(value) {
					return value;
				}
			},
			inSettingsPage() {
				return this.$route.path === '/accounting/settings';
			},
			inQuickbooksTokenPage() {
				return this.$route.path === '/accounting/quickbooks/token';
			},
		},
		methods: {
			...mapActions('accounting', [
				'getAccounting',
				'createAccountingCompany',
				'getQuickbooksLoginUrl',
				'setLastUrlPath',
				'getQBOConnection',
			]),
			...mapMutations('accounting', ['updateAccountingCompany', 'updateQBO']),

			async getAccountingInfo() {
				try {
					const { data } = await this.getAccounting();
					if (!data) {
						this.isCreatingCompany = true;
						const { data } = await this.createAccountingCompany();
						if(data.data){
							this.isCreatingCompany = false;
							this.isPreparingAccountingInfo = false;
						}
					} else {
						this.updateAccountingCompany(data);

						// Fetch the QBO if this user has QBO
						if (this.companyInformation.accounting_company.qbo_realm_id) {
							if(this.$route.path !== "/accounting/quickbooks/token"){
								this.fetchQBO();
							}
						}
						this.isPreparingAccountingInfo = false;
					}
				} catch (error) {
					apiErrorMessage(error);
				}
			},

			async checkAccounting() {
				try {
					const { data } = await this.getAccounting();
					if (!data) {
						this.isCreatingCompany = true;
						await this.createAccountingCompany();
						this.isCreatingCompany = false;
						this.isCheckingQBO = false;
					} else {
						// Save the returned company if exists
						this.updateAccountingCompany(data);

						// Fetch the QBO if this user has QBO
						if (this.companyInformation.accounting_company.qbo_realm_id) {
							this.isCheckingQBO = true;
							await this.fetchQBO();
						}
					}
				} catch (error) {
					apiErrorMessage(error);
				} finally {
					this.isCheckingQBO = false;
				}
			},

			async fetchQBO() {
				this.isCheckingQBO = true;
				try {
					const { data, message } = await this.getQBOConnection();
					if (!data) {
						this.isQBOSignInRequired = true;
						if(this.$route.path !== "/accounting/quickbooks/token"){
							this.setLastUrlPath(this.$route.path);
							// this.onQuickbooksSignIn();
						}
						apiErrorMessage(message);
					} else {
						this.updateQBO(data);
						this.isCheckingQBO = false;
					}
				} catch (error) {
					this.isQBOSignInRequired = true;
					if(this.$route.path !== "/accounting/quickbooks/token"){
						this.setLastUrlPath(this.$route.path);
						// this.onQuickbooksSignIn();
					}
					if(this.$route.path === "/accounting/settings"){
						this.setLastUrlPath(this.$route.path);
						this.onQuickbooksSignIn();
					}
				} finally {
					this.isCheckingQBO = false;
				}
			},

			async onQuickbooksSignIn() {
				if (this.isFetchingSignInURL) {
					return;
				}

				this.isFetchingSignInURL = true;
				try {
					if(this.$route.path !== "/accounting/quickbooks/token"){
						this.setLastUrlPath(this.$route.path);
					}
					const url = await this.getQuickbooksLoginUrl();

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

		},

		watch:{
			getUser(){
				if(this.user){
					this.isSignedIn = true;
					this.getAccountingInfo();

					if (this.companyInformation && this.companyInformation?.accounting_company?.qbo_realm_id && !this.QBOInformation) {
						this.isQBOSignInRequired = true;
					}
					if(this.$route.path === "/accounting/quickbooks/token"){
						this.isQBOSignInRequired = false;
					}
				}
			}
		}
	};
</script>

<style lang="scss">
@import './scss/globalAccounting.scss';
@import '../../assets/scss/tables.scss';
</style>

<style lang="scss" scoped>
	.accounting-item-container__wrapper,
	.accounting-module {
		height: 100%;
	}

	.accounting-index-card {
		height: calc(100vh - 118px);
		.v-card__text {
			height: 100%;
		}
		.h-100 {
			height: 100%;
		}
	}
	::v-deep .v-dialog .v-card .v-card__text .v-input .v-input__control {
		background-color: transparent !important;
	}
</style>
