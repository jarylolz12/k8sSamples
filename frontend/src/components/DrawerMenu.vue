<!-- @format -->

<template>
	<div class="sidebar">
		<div class="logo">
			<router-link to="/shipments" class="shipment-logo">
				<img src="@/assets/images/logo.png" alt="" />
			</router-link>
		</div>
		<ul class="links" v-if="customersApi.length === 0">
			<li v-if="isMobile">
				<v-btn
					style="width: 100%"
					@click="userLogout"
					class="btn-logout-mobile justify-start"
				>
					<img
						style="margin-right: 10px"
						src="@/assets/images/sign-out.png"
						alt=""
					/>
					Sign Out
				</v-btn>
			</li>
		</ul>
		<ul v-if="customersApi.length > 0" class="links">
			<!-- <li>
				<router-link to="/" v-bind:class="{'active-link': ($store.state.page.currentPage=='dashboard')}">
					<img src="@/assets/images/dashboard.svg" alt="" />
					Dashboard
				</router-link>
			</li> -->

			<li>
				<router-link
					to="/shipments"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'shipments',
					}"
				>
					<img src="@/assets/images/shipments.svg" alt="" />
					Shipments
				</router-link>
			</li>
			<li v-if="checkCustomerkey">
				<router-link
					to="/drayage"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'drayage',
					}"
				>
					<img src="@/assets/icons/trucking-customer.svg" alt="" />
					Drayage
				</router-link>
			</li>
			<li>
				<router-link
					to="/pos?tab=active"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'pos',
					}"
				>
					<img src="@/assets/images/POs.svg" alt="" />
					POs
				</router-link>
			</li>

			<li>
				<router-link
					to="/sales-orders?tab=active"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'sales-orders',
					}"
				>
					<img src="@/assets/images/salesOrders.svg" alt="" />
					Sales Orders
				</router-link>
			</li>

			<li>
				<router-link
					to="/products"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'products',
					}"
				>
					<img src="@/assets/images/Items.svg" alt="" />
					Products
				</router-link>
			</li>

			<li>
				<router-link
					to="/inventory?tab=Products"
					@click.native="setTabToProducts"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'inventory',
					}"
				>
					<img src="@/assets/images/Inventory.svg" alt="" />
					Inventory
				</router-link>
			</li>

			<li v-if="isMobile && isCustomerAdminEmployee === 1">
				<router-link
					to="/mwms"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'mwms',
					}"
				>
					<img src="@/assets/images/Mwms.svg" alt="" />
					MWM System
				</router-link>
			</li>

			<li>
				<router-link
					to="/contact"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'contact',
					}"
				>
					<img src="@/assets/images/Suppliers.svg" alt="" />
					Contact
				</router-link>
			</li>

			<li>
				<router-link
					to="/ach-statements"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'ach-statements',
					}"
				>
					<img src="@/assets/images/statement-white.svg" alt="" />
					ACH Statements
				</router-link>
			</li>

			<!-- ****************** adding new features ********************* -->
			<!-- <v-list dark>
				<v-list-group color="white" :value="true">
				<template v-slot:activator>
					<img class="pe-3" src="@/assets/images/Suppliers.svg" alt="" />
					<v-list-item-title>Accounting</v-list-item-title>
				</template>
				<li>
					<router-link
					class="pl-15"
					to="/invoices"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'invoices',
					}"
					>
					Invoices
					</router-link>
				</li>
				<li>
					<router-link
					class="pl-15"
					to="/payments"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'payments',
					}"
					>
					Payments
					</router-link>
				</li>
				</v-list-group>
			</v-list> -->
			<!-- ******************  ********************* -->

			<li>
				<router-link
					to="/reports"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'reports',
					}"
				>
					<img src="@/assets/images/Reports.svg" alt="" />
					Reports
				</router-link>
			</li>

			<li>
				<router-link
					to="/billing"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'billing',
					}"
				>
					<img src="@/assets/images/billing.svg" alt="" />
					Billing
				</router-link>
			</li>

			<v-expansion-panels v-if="accountingModuleEnabled" accordion flat v-model="accountingMenuShow">
				<v-expansion-panel>
					<v-expansion-panel-header>
						<div class="settings-menu-group">
							<img src="@/assets/images/transfer.svg" alt="" />
							Accounting
						</div>
					</v-expansion-panel-header>

					<v-expansion-panel-content>
						<v-list-item-group two-line class="pa-0">
							<template v-for="(item, i) in accountingMenuItems">
								<v-list-item
									v-if="item.nonQBO ? isQBOEnabled === 0 : true"
									:key="`accounting-menu-${i}`"
									:class="{
										'active-link':
											getCurrentPageIndex === i + 1 &&
											containsActive(item.path),
									}"
									@click.native="onClickAccounting(i + 1)"
								>
									<!-- $route.path === item.path -->
									<v-list-item-title>
										<router-link :to="item.path">
											<div class="icon-separator"></div>
											<span>{{ item.title }}</span>
										</router-link>
									</v-list-item-title>
								</v-list-item>
							</template>
						</v-list-item-group>
					</v-expansion-panel-content>
				</v-expansion-panel>
			</v-expansion-panels>

			<!-- Settings Pop Over -->
			<!-- <div class="settings-popup-menu"
			v-bind:class="{
				'active-link': $store.state.page.currentPage.includes('settings'),
			}">
				<v-menu
					v-model="menu"
					:close-on-content-click="false"
					:nudge-width="200"
					offset-x
					content-class="drawer-menu-popover">
					<template v-slot:activator="{ on, attrs }">
						<v-btn
							class="settings-btn"
							:ripple="false"
							dark
							v-bind="attrs"
							v-on="on">
							<router-link
							to="/settings"
							v-bind:class="{
								'active-link': $store.state.page.currentPage == 'settings',
							}">
								<div class="settings-menu-group">
									<img src="@/assets/images/settings.svg" alt="" />
									Settings
								</div>
								<img src="@/assets/images/chevron-right.svg" alt="" />
							</router-link>
						</v-btn>
					</template>
					<v-card>
						<v-list>
							<v-list-item>
								<v-list-item-title>
									<router-link
									to="/settings/?tab=users"
									v-bind:class="{
										'active-link': $store.state.page.currentSettingsTab == 0,
									}"
									@click.native="onClickSettings(0)">
										<img src="@/assets/images/users.svg" width="22px" height="22px"/>
										Users
									</router-link>
								</v-list-item-title>
							</v-list-item>
							<v-list-item>
								<v-list-item-title>
									<router-link
									to="/settings/?tab=notifications"
									v-bind:class="{
										'active-link': $store.state.page.currentSettingsTab == 1,
									}"
									@click.native="onClickSettings(1)">
										<img src="@/assets/images/notification.svg" width="22px" height="22px"/>
										Notifications
									</router-link>
								</v-list-item-title>
							</v-list-item>
							<v-list-item>
								<v-list-item-title>
									<router-link
									to="/settings/?tab=manage-payment-methods"
									v-bind:class="{
										'active-link': $store.state.page.currentSettingsTab == 1,
									}"
									@click.native="onClickSettings(1)">
										<img src="@/assets/images/payment.svg" width="22px" height="22px"/>
										Payment Methods
									</router-link>
								</v-list-item-title>
							</v-list-item>
							<v-list-item>
								<v-list-item-title>
									<router-link
									to="/settings/?tab=integrations"
									v-bind:class="{
										'active-link': $store.state.page.currentSettingsTab == 3,
									}"
									@click.native="onClickSettings(3)">
										<img src="@/assets/images/integrations.svg" width="22px" height="22px"/>
										Integrations
									</router-link>
								</v-list-item-title>
							</v-list-item>
						</v-list>
					</v-card>
				</v-menu>
			</div> -->

			<v-expansion-panels accordion flat v-model="settingsMenuShow">
				<v-expansion-panel v-model="settingsMenuShow">
					<v-expansion-panel-header>
						<div class="settings-menu-group">
							<img src="@/assets/images/settings.svg" alt="" />
							Settings
						</div>
					</v-expansion-panel-header>

					<v-expansion-panel-content>
						<v-list-item-group two-line class="pa-0">
							<v-list-item
								@click.native="onClickSettings(0)"
								:class="
									($router.currentRoute.name === 'Settings' &&
									$store.state.page.currentSettingsTab == 0) || 
									($router.currentRoute.name === 'AddUserGroup' &&
									$store.state.page.currentSettingsTab == 0)
										? 'active-link'
										: ''
								"
							>
								<v-list-item-title>
									<router-link to="/settings?tab=users">
										<div class="icon-separator"></div>
										<span>Company Profile</span>
									</router-link>
								</v-list-item-title>
							</v-list-item>

							<!-- <v-list-item>
								<v-list-item-title>
									<router-link
									to="/settings/?tab=notifications"
									v-bind:class="{
										'active-link': $store.state.page.currentSettingsTab == 1,
									}"
									@click.native="onClickSettings(1)">
										Notifications
									</router-link>
								</v-list-item-title>
							</v-list-item> -->

							<v-list-item
								@click="onClickSettings(1)"
								:class="
									$router.currentRoute.name === 'Settings' &&
									$store.state.page.currentSettingsTab == 1
										? 'active-link'
										: ''
								"
							>
								<v-list-item-title>
									<router-link to="/settings?tab=manage-payment-methods">
										<div class="icon-separator"></div>
										Payment Methods
									</router-link>
								</v-list-item-title>
							</v-list-item>

							<!-- <v-list-item>
								<v-list-item-title>
									<router-link
									to="/settings/?tab=integrations"
									v-bind:class="{ 'active-link': $store.state.page.currentSettingsTab == 3, }"
									@click.native="onClickSettings(3)">
										Integrations
									</router-link>
								</v-list-item-title>
							</v-list-item> -->
						</v-list-item-group>
					</v-expansion-panel-content>
				</v-expansion-panel>
			</v-expansion-panels>

			<div v-if="isMobile" class="mobile-line-separator"></div>

			<!-- REDIRECT TO PROFILE PAGE -->
			<li v-if="isMobile && 1 == 2">
				<router-link
					to="/profile"
					v-bind:class="{
						'active-link': $store.state.page.currentPage == 'profile',
					}"
				>
					<v-avatar size="35" style="margin-right: 10px">
						<v-img src="https://cdn.vuetifyjs.com/images/john.png"></v-img>
						<v-icon small class="mr-2 account">
							mdi-account
						</v-icon>
					</v-avatar>
					{{ getUser ? JSON.parse(getUser).name : "" }}
				</router-link>
			</li>

			<li style="line-height: 28px; padding-top: 16px" v-if="isMobile">
				<div class="customer-lists-header">Accounts</div>
			</li>

			<!-- TEMPORARY NO REDIRECTION -->
			<div v-if="isMobile">
				<li
					@click="selectCustomer(customer)"
					:key="`customer-${key}`"
					v-for="(customer, key) in customers"
					style="
						padding: 0px 8px 0px 8px;
						color: #e1ecf0 !important;
						cursor: pointer;
					"
				>
					<div
						:class="
							`default-customer-wrapper ${
								customer.is_selected == 1 ? 'customer-selected' : ''
							}`
						"
					>
						<v-avatar v-if="1 == 2" size="35" style="margin-right: 10px">
							<v-icon small class="mr-2 account">
								mdi-account
							</v-icon>
						</v-avatar>
						<div class="customer-avatar-group-wrapper">
							<div class="customer-avatar-wrapper">
								<img src="../assets/images/account-menus/avatar.png" />
							</div>
							<div
								style="
									display: flex;
									flex-direction: column;
									width: 90%;
								"
							>
								<div class="customer-entity-name">
									{{
										typeof customer !== "undefined" &&
										typeof customer.name !== "undefined"
											? customer.name
											: ""
									}}
								</div>
								<div class="customer-entity-company-name">
									{{
										typeof customer !== "undefined" &&
										typeof customer.company_name !== "undefined"
											? customer.company_name
											: ""
									}}
								</div>
								<div class="customer-entity-company-name">
									{{
										typeof customer !== "undefined" &&
										typeof customer.company_key !== "undefined"
											? "Key: " + customer.company_key
											: ""
									}}
								</div>
							</div>
						</div>
						<div
							v-if="customer.is_selected == 1"
							class="customer-arrow-wrapper"
						>
							<svg
								width="16"
								height="14"
								viewBox="0 0 16 14"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M5.68817 10.526L2.27614 6.9434C1.75544 6.39666 0.911221 6.39666 0.390523 6.9434C-0.130174 7.49013 -0.130174 8.37656 0.390523 8.92329L4.83496 13.5899C5.38936 14.1721 6.30013 14.1287 6.80206 13.4962L15.6909 2.29629C16.1623 1.7023 16.0859 0.819512 15.5202 0.324524C14.9545 -0.170464 14.1138 -0.0902105 13.6423 0.503775L5.68817 10.526Z"
									fill="white"
								/>
							</svg>
						</div>
					</div>
					{{ /*getUser ? JSON.parse(getUser).name : ""*/ }}
				</li>
			</div>

			<li v-if="isMobile">
				<v-btn @click="userLogout" class="btn-logout-mobile">
					<img
						style="margin-right: 10px"
						src="@/assets/images/sign-out.png"
						alt=""
					/>
					Sign Out
				</v-btn>
			</li>
		</ul>
		<p class="footer">© {{ currentYear }} Shifl. All rights reserved.</p>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import _ from "lodash";
import { accountingMenuItems } from "../router/accounting";

export default {
	name: "DrawerMenu",
	props: ["isMobile"],
	data: () => ({
		menu: false,
		settingsMenuShow: -1,
		accountingMenuShow: -1,
	}),
	methods: {
		...mapActions(["logout", "updateCustomerPreference"]),
		selectCustomer({ id: customer_id }) {
			let userDetails = JSON.parse(localStorage.getItem("shifl.user_details"));
			if (userDetails.default_customer_id !== customer_id) {
				this.updateCustomerPreference(customer_id)
					.then((response) => {
						if (typeof response.status !== "undefined") {
							if (response.status == "ok") {
								// let currentUrl = window.location.pathname
								// window.location.href = currentUrl
								let currentUrl = window.location.pathname;
								if (this.$route.name === "PO Details") {
									window.location.href = "/pos";
								} else if (this.$route.name === "Shipment Details") {
									window.location.href = "/shipments";
								} else {
									window.location.href = currentUrl;
								}
							}
						}
					})
					.catch((e) => {
						console.log(e);
					});
			}
		},
		userLogout() {
			var messageToPost = { logout: true };
			if (window.webkit != undefined) {
				window.webkit.messageHandlers.buttonClicked.postMessage(messageToPost);
			}
			this.$store.dispatch("page/setTab", 1);
			this.$store.dispatch("page/setCurrentSettingsTab", 0);
			this.$store.dispatch("page/setCurrentInventoryTab", 0);
			this.logout();
		},
		onClickSettings(index) {
			this.$store.dispatch("page/setCurrentSettingsTab", index);
		},
		setTabToProducts() {
			this.$store.dispatch("page/setCurrentInventoryTab", 0);
		},
		isSettingsDefaultOpen() {
			if (
				this.$router.currentRoute.name === "Settings" &&
				this.settingsMenuShow === -1
			) {
				this.settingsMenuShow = 0;
			}
		},
		onClickAccounting(index) {
			if(this.$store.hasModule('accounting')){
				this.$store.dispatch("accounting/setAccountingPageIndex", index);
			}
		},
		containsActive(itemPath) {
			if (itemPath !== null) {
				let currentRoutePath = this.$route.path;

				if (
					currentRoutePath === itemPath ||
					currentRoutePath.includes(itemPath)
				) {
					return true;
				} else return false;
			} else {
				return false;
			}
		},
		isAccountingDefaultOpen() {
			let currentRoutePath = this.$route.path;
			if (
				currentRoutePath.includes("/accounting/") &&
				this.accountingMenuShow === -1
			) {
				this.accountingMenuShow = 0;
			}
		},
	},
	computed: {
		...mapGetters(["getUser", "getLoadingUserDetails"]),
		// ...mapGetters("accounting", ["isQBOEnabled", "getCurrentPageIndex"]),
		accountingMenuItems() {
			return accountingMenuItems;
		},
		
		accountingModuleEnabled(){
			if(this.$store.hasModule('accounting')){
				return true;
			}
			return false;
		},

		isQBOEnabled(){
			if(this.$store.hasModule('accounting')){
				return this.$store.getters['accounting/isQBOEnabled'];
			}
			return 0;
		},

		getCurrentPageIndex(){
			if(this.$store.hasModule('accounting')){
				return this.$store.getters['accounting/getCurrentPageIndex'];
			}
			return 1;
		},

		currentYear() {
			return new Date().getFullYear();
		},
		customersApi() {
			let customers_api =
				typeof this.getUser.customers_api == "undefined"
					? JSON.parse(this.getUser).customers_api
					: this.getUser.customers_api;
			return customers_api;
		},
		checkCustomerkey() {
			try {
				let user = JSON.parse(this.getUser);
				let key = user.customers_api.find(
					(c) => c.id == user.default_customer_id
				).company_key;
				if (key.length) {
					return true;
				}
			} catch (e) {
				return false;
			}
			return false;
		},
		defaultCustomerEntity() {
			if (this.customers.length > 0) {
				return _.find(this.customers, {
					is_selected: 1,
				});
			} else {
				return {
					name: "",
					company_name: "",
				};
			}
		},
		defaultCustomer() {
			let getUser = this.getUser;
			return typeof getUser.default_customer_id !== "undefined"
				? getUser.default_customer_id
				: JSON.parse(getUser).default_customer_id;
		},
		customers() {
			let getUser = this.getUser;
			let getCustomers =
				typeof getUser.customers_api !== "undefined"
					? getUser.customers_api
					: JSON.parse(getUser).customers_api;
			if (typeof getCustomers !== "undefined") {
				getCustomers.map((customer, key) => {
					(getCustomers[key].is_selected =
						this.defaultCustomer !== null && this.defaultCustomer == customer.id
							? 1
							: 0),
						(getCustomers[key].name = customer.company_name),
						(getCustomers[key].company_name =
							customer.address !== null && customer.address !== ""
								? customer.address
								: "No address found.");
				});
				let findSelected = _.find(getCustomers, { is_selected: 1 });
				if (typeof findSelected == "undefined") getCustomers[0].is_selected = 1;
				getCustomers = _.orderBy(getCustomers, ["is_selected"], ["desc"]);
				return getCustomers;
			}
			return [];
		},
		isCustomerAdminEmployee() {
			let isWarehouseEmployee =
				typeof this.getUser.is_warehouse_employee == "undefined"
					? JSON.parse(this.getUser).is_warehouse_employee
					: this.getUser.is_warehouse_employee;
			return isWarehouseEmployee;
		},
	},
	mounted() {
		this.isSettingsDefaultOpen();
		this.isAccountingDefaultOpen();
	},
	updated() {
		this.isSettingsDefaultOpen();
		this.isAccountingDefaultOpen();
	},
};
</script>

<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap");
@import "../assets/scss/components/drawer.scss";
@import "../assets/scss/components/drawer-1.scss";
</style>
