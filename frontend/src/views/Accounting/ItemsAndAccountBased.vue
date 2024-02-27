<template>
	<v-sheet color="transparent" class="items-and-account-based-wrapper">
		<h3 class="font-20 mb-3 ml-2 mt-3 ml-sm-0 mt-sm-0">Items</h3>
		<v-sheet color="white">
			<v-card flat outlined>
				<v-tabs v-model="tab" height="56" no-animation>
					<v-tab class="text-capitalize">{{ 'Products & Services' }}</v-tab>
					<v-tab v-if="isQBOEnabled" class="text-capitalize">{{ 'Account-Based Items' }}</v-tab>
				</v-tabs>
				<v-divider></v-divider>
				<v-tabs-items v-model="tab">
					<v-tab-item :transition="false" :reverse-transition="false">
						<items></items>
					</v-tab-item>
					<v-tab-item :transition="false" :reverse-transition="false">
						<account-based></account-based>
					</v-tab-item>
				</v-tabs-items>
			</v-card>
		</v-sheet>
	</v-sheet>
</template>

<script>
	import Items from '../../components/Accounting/Items.vue';
	import AccountBased from '../../components/Accounting/AccountBasedItems.vue';
	import { mapGetters } from 'vuex';

	export default {
		components: {
			Items,
			AccountBased
		},
		created() {
			//
		},
		data() {
			return {
				tab: false
			};
		},
		computed: {
			...mapGetters('accounting', ['isQBOEnabled'])
		},
		methods: {
			//
		}
	};
</script>

<style lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';
@import './scss/itemsAndAccountBased.scss';
</style>