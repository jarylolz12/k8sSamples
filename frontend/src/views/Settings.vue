<template>
	<div class="settings-wrapper" v-resize="onResize">
		<div class="settings-wrapper-contents">
			<v-tabs
				class="method-tabs"
				@change="onTabChange"
				v-model="$store.state.page.currentSettingsTab"
			>
				<v-tab v-for="(n, i) in tabs" :key="i" @click="getCurrentTab(i)">
					{{ n }}
				</v-tab>
			</v-tabs>

			<div class="settings-components">
				<Users v-if="$store.state.page.currentSettingsTab === 0" />
				<!-- <Notifications v-if="$store.state.page.currentSettingsTab === 1" /> -->
				<ManagePayment v-if="$store.state.page.currentSettingsTab === 1" />
				<!-- <Integrations v-if="$store.state.page.currentSettingsTab === 3" /> -->
			</div>
		</div>
	</div>
</template>

<script>
import { mapActions } from "vuex";
import globalMethods from "../utils/globalMethods";
import Users from "../components/SettingsComponents/Users/Users.vue";
// import Notifications from "../components/SettingsComponents/Notifications/Notifications.vue";
import ManagePayment from "../components/SettingsComponents/ManagePaymentMethod/ManagePayment.vue";
// import Integrations from "../components/SettingsComponents/Integrations/Integrations.vue";

export default {
	name: "Settings",
	components: {
		Users,
		// Notifications,
		ManagePayment,
		// Integrations,
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "settings");
		this.fetchCountries();
	},
	data: () => ({
		isMobile: false,
		tabs: ["Company Profile", "Payment Methods"],
		activeTab: 0,
	}),
	methods: {
		...mapActions({
			fetchCountries: "warehouse/fetchCountries",
		}),
		...globalMethods,
		getCurrentTab(id) {
			const poyntScriptSrc =
				"https://poynt.net/snippet/poynt-collect/bundle.js";
			if (this.isScriptLoaded(poyntScriptSrc)) {
				var scripts = document.getElementsByTagName("script");
				for (var i = 0; i < scripts.length; i++) {
					if (scripts[i].src === poyntScriptSrc) {
						let theScript = scripts[i];
						theScript.parentNode.removeChild( theScript);
						break;
					}
				}
			}

			this.$store.dispatch("page/setCurrentSettingsTab", id);

			let pathData =
				typeof this.$router.history.current.query.tab !== "undefined"
					? this.$router.history.current.query.tab
					: null;

			if (id == 0 && pathData !== null && pathData !== "users") {
				this.$router.push("?tab=users");
			}
			// if (id == 1 && pathData !== null && pathData !== "notifications") {
			// 	this.$router.push("?tab=notifications");
			// }
			if (
				id == 1 &&
				pathData !== null &&
				pathData !== "manage-payment-methods"
			) {
				this.$router.push("?tab=manage-payment-methods");
			}
			// if (
			// 	id == 3 &&
			// 	pathData !== null &&
			// 	pathData !== "integrations"
			// ) {
			// 	this.$router.push("?tab=integrations");
			// }
		},
		onTabChange() {
			this.page = 1;
		},
		onResize() {
			if (window.innerWidth < 769) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
	},
};
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/settings/settings.scss";
</style>