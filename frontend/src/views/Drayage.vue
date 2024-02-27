<template>
	<div class="drayagewrapper" v-resize="onResize">
		<DrayageComponent
		:itemdata="getdata"
		:isfetching="isfetching"
		:centralcustomer="itemkey"
		:centraldata="centralkeydata"
		v-if="refresh"
		/>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import DrayageComponent from "../components/Tables/Drayage/DrayageDesktopTable.vue";
import globalMethods from "../utils/globalMethods";
//import _ from 'lodash'

export default {
	name: "Products",
	components: {
		DrayageComponent,
	},
	data: () => ({
        test: 'test',
		refresh:1,
		isfetching:0,
		itemkey:'',
		centralkeydata:''
	}),
	computed: {
		...mapGetters({
			getUser: "getUser",
			getDrayage: "drayage/getDrayage",
			drayageLoading: "drayage/getDrayageLoading",
		}),
		getdata() {
			var data = [];
			if (this.isUndefinedNull(this.getDrayage)) {
				console.log(this.getDrayage);
				return this.getDrayage.data;
			}

			return data;
		},
	},
	watch: {},
	created() {},
	methods: {
		...mapActions({
			fetchDrayage: "drayage/fetchDrayage",
			fetchUser: "fetchUser",
		}),
		...globalMethods,
		onResize() {
			if (window.innerWidth < 1023) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
		},
		isUndefinedNull(object) {
			if (typeof object !== "undefined" && object !== null) {
				return true;
			}
			return false;
		},
	},
	async mounted() {
		this.$store.dispatch("page/setPage", "drayage");

		this.isfetching = 1;
		await this.fetchUser();
		let user = JSON.parse(this.getUser);

		let valuecustomer = user.customers_api.find(
			(c) => c.id == user.default_customer_id
		).company_key;
		this.itemkey = valuecustomer;

		try {
			await this.fetchDrayage(valuecustomer);
			this.refresh = 0;
			this.$nextTick(() => {
				this.isfetching = 0;
				this.refresh = 1;
			});
		} catch (e) {
			console.log(e);
			this.isfetching = 0;
			this.refresh = 1;
		}
	},
};
</script>

<style lang="scss">
@import "../assets/scss/pages_scss/drayage/drayage.scss";
//@import '../assets/scss/pages_scss/product/product.scss';
@import "../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../assets/scss/buttons.scss";
</style>
