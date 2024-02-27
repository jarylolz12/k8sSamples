<template>
	<div>
		<v-sheet color="white">
			<v-card flat>
				<v-tabs v-model="tab" height="56">
					<v-tab v-if="isQBOEnabled">Tax Codes</v-tab>
					<v-tab>Tax Rates</v-tab>
				</v-tabs>
				<v-divider></v-divider>
				<v-tabs-items v-model="tab">
					<v-tab-item v-if="isQBOEnabled">
						<tax-codes />
					</v-tab-item>
					<v-tab-item>
						<tax-rates />
					</v-tab-item>
				</v-tabs-items>
			</v-card>
		</v-sheet>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
	import TaxCodes from './TaxCodes.vue';
	import TaxRates from './TaxRates.vue';
	export default {
		name: 'Taxes',
		components: {
			TaxCodes,
			TaxRates
		},
		data() {
			return {
				tab: null
			};
		},
		computed: {
			...mapGetters('accounting', ['isQBOEnabled'])
		}
	};
</script>
<style lang="scss" scoped>
	.v-tabs {
		border-bottom: 1px solid #ddd;

		.v-tab {		
			letter-spacing: 0;
			text-transform: capitalize;
			font-family: 'Inter-SemiBold', sans-serif;
			color: #4a4a4a;
			font-size: 14px;

			&.v-tab--active {
				color: #0171A1;
			}
		}
	}
</style>
