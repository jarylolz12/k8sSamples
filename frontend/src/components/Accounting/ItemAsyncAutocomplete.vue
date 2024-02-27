<template>
	<v-autocomplete
		v-model="model"
		:loading="isDataLoading"
		:items="itemDataList"
		:search-input.sync="searchText"
		dense
		solo
		flat
		outlined
		item-value="id"
		item-text="name"
		return-object
		:rules="[(v) => !!v || 'Field Required']"
		@change="onSelect"
		hide-details="auto"
		placeholder="Select Item"
		:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
	>
	</v-autocomplete>
</template>

<script>
	import { debounce } from '../../utils/globalMethods';
	import { mapActions } from 'vuex';

	export default {
		name: 'ItemAsyncAutocomplete',
		props: {
			value: String,
			income: Boolean,
			isCategoryItem: Boolean
		},

		data() {
			return {
				isDataLoading: false,
				searchText: null,
				itemData: []
			};
		},

		computed: {
			model: {
				get() {
					return this.value;
				},
				set(val) {
					this.$emit('input', val);
				}
			},

			itemDataList: {
				get() {
					let valueInItems = true;
					const items = this.itemData.map((item) => {
						const data = {
							id: item.id,
							name: item.name,
							qbo_item_id: item.qbo_item_id,
							qbo_item_type: item.qbo_item_type
						};

						if (this.value && this.value.id && item.id !== this.value.id) {
							valueInItems = false;
						}

						if (this.income) {
							data.de_income_account_value = item.de_income_account_value;
						} else {
							data.de_expense_account_value = item.de_expense_account_value;
						}

						return data;
					});

					if (!valueInItems) {
						const item = {
							id: this.value.id,
							name: this.value.name,
							qbo_item_id: this.value.qbo_item_id,
							qbo_item_type: this.value.qbo_item_type
						};
						if (this.income) {
							item.de_income_account_value = this.value.de_income_account_value;
						} else {
							item.de_expense_account_value = this.value.de_expense_account_value;
						}
						items.push(item);
					}

					return items;
				}
			}
		},

		watch: {
			searchText: debounce(function() {
				this.searchItems();
			}, 300)
		},

		created() {
			this.fetchItemList();
		},

		methods: {
			...mapActions('accounting', ['getCategoryBasedItemData', 'getItemsData']),
			async fetchItemList(limit = 10, page = 1) {
				if (this.isDataLoading) {
					return;
				}
				this.isDataLoading = true;
				try {
					const params = { limit, page };
					const { data } = this.isCategoryItem
						? await this.getCategoryBasedItemData(params)
						: await this.getItemsData(params);
					this.isDataLoading = false;
					this.itemData = data;
				} catch (error) {
					this.isDataLoading = false;
				}
			},

			async searchItems() {
				const search = this.searchText;
				if (this.model) {
					const item = this.model;
					if (item && item.name === search) {
						return;
					}
				}
				if (this.isDataLoading || !search) {
					return;
				}
				this.isDataLoading = true;
				try {
					const params = { limit: 10, page: 1, search };
					const { data } = this.isCategoryItem
						? await this.getCategoryBasedItemData(params)
						: await this.getItemsData(params);
					this.itemData = data || [];
					this.isDataLoading = false;
				} catch (error) {
					this.isDataLoading = false;
				}
			},

			onSelect(data) {
				if (data) {
					this.$emit(
						'change',
						this.itemData.find((item) => item.id === data.id)
					);
				}
			}
		}
	};
</script>

<style></style>
