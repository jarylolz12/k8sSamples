<template>
	<div>
		<v-sheet color="white">
			<v-card flat v-if="isQBOEnabled">
				<v-tabs v-model="tab" height="56">
					<v-tab>Import</v-tab>
					<v-tab>Entities</v-tab>
					<v-tab>Settings</v-tab>
					<v-tab>Audit</v-tab>
				</v-tabs>
				<v-divider></v-divider>
				<v-tabs-items v-model="tab">
					<v-tab-item>
						<quickbooks-import></quickbooks-import>
					</v-tab-item>
					<v-tab-item>
						Work in progress, this feature will be available soon.
					</v-tab-item>
					<v-tab-item>
						Work in progress, this feature will be available soon.
					</v-tab-item>
					<v-tab-item>
						Work in progress, this feature will be available soon.
					</v-tab-item>
				</v-tabs-items>
			</v-card>
		</v-sheet>
	</div>
</template>
<script>
	import { mapGetters } from 'vuex';
    import QuickbooksImport from '../Accounting/QuickbooksImport.vue';
	export default {
		name: 'QuickbooksSettings',
		components: {
            QuickbooksImport,
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
