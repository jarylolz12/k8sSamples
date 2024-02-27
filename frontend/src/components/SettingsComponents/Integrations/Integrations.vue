<!-- @format -->

<template>
	<div class="integrations-wrapper" v-resize="onResize">
		<IntegrationsDesktop
			v-if="!isMobile"
		/>	
	
		<IntegrationsMobile
			v-if="isMobile"
		/>

	</div>
</template>


<script>
import { mapActions, mapGetters } from "vuex";
import IntegrationsDesktop from "../../Tables/Settings/IntegrationsMethod/IntegrationsDesktop.vue";
import IntegrationsMobile from "../../Tables/Settings/IntegrationsMethod/IntegrationsMobile.vue";
import globalMethods from "../../../utils/globalMethods";

export default {
	name: "ManagePayment",
	components: {
		IntegrationsDesktop,
		IntegrationsMobile,
	},
	mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "settings/integrations");
	},
	data: () => ({
		page: 1,
		pageCount: 0,
		itemsPerPage: 35,
		isMobile: false,
	}),
	computed: {
		...mapGetters({}),
	},
	methods: {
		...mapActions({}),
		...globalMethods,
		getCurrentTab(id) {
			console.log(id); 
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
@import "../../../assets/scss/pages_scss/settings/integrations.scss";
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/buttons.scss";
</style>