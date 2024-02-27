<template>
	<div class="account-settings-taxcodes-wrapper">
		<v-data-table
			disable-pagination
			:headers="tableHeader"
			:items="tableData"
			:loading="isTaxcodesDataLoading"
			:hide-default-footer="true"
			:search="searchText"
		>
			<template v-slot:top>
				<div class="d-block d-sm-flex justify-end d-md-flex d-lg-flex pa-4">
					<div class="d-sm-flex d-inline">
						<Search :inputData.sync="searchText" placeholder="Search" />
					</div>
				</div>
				<v-divider />
			</template>
			<template v-slot:[`item.taxable`]="{ item }">
				{{ Boolean(item.taxable) ? 'Yes' : 'No' }}
			</template>
			<template v-slot:[`item.actions`]="{ item }">
				<v-btn
					class="primary--text text-capitalize"
					text
					outlined
					small
					@click="onToggleTaxcodesInformationModal(item)"
					>View</v-btn
				>
			</template>
		</v-data-table>
		<v-divider />
		<pagination
			:total="pagination.last_page || 1"
			:count="pagination.total || 0"
			:current-page="currentPage"
			:total-visible="10"
			:pageLimit.sync="pageLimit"
			:from="pagination ? pagination.from : 1"
			:to="pagination ? pagination.to : 1"
		/>

		<v-dialog v-model="showInfoModal" max-width="500" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card>
				<v-card-title class="pa-0">
					<span>{{ selectedTaxcodesInformation.name }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onToggleTaxcodesInformationModal">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="py-4 pb-8">
					<v-row class="row-stripe py-2 px-1" no-gutters>
						<v-col cols="6" class="text-left ">
							<h4 class="labelcolor--text">Name</h4>
							<span>{{ selectedTaxcodesInformation.name }}</span>
						</v-col>
						<v-col cols="6" class="text-left">
							<h4 class="labelcolor--text">Description</h4>
							<span>{{ selectedTaxcodesInformation.description }}</span>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="6">
							<h4 class="labelcolor--text">Taxable</h4>
							<span>{{ Boolean(selectedTaxcodesInformation.taxable) ? 'Yes' : 'No' }}</span>
						</v-col>
					</v-row>
					<h4 class="labelcolor--text mt-4">Tax Rates</h4>
					<table class="w-100 table" cellspacing="0">
						<thead>
							<tr>
								<th class="text-left">Name</th>
								<th class="text-left">Rate</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="rate in taxRateData" :key="rate.id">
								<td>{{ rate.name }}</td>
								<td>{{ rate.rate }}</td>
							</tr>
						</tbody>
					</table>
				</v-card-text>
			</v-card>
		</v-dialog>
	</div>
</template>
<script>
	import { apiErrorMessage } from '../../utils/globalMethods';
	import Pagination from './Pagination.vue';
	import Search from './SearchInput.vue';
	import AkauntingService from '../../services/akaunting.service';
	// import TaxcodesForm from './TaxcodesForm.vue';
	// import TaxcodesDelete from './TaxcodesDeleteModal.vue';
	export default {
		name: 'TaxCodes',
		components: {
			Pagination,
			Search
			// TaxcodesForm,
			// TaxcodesDelete
		},
		data() {
			return {
				isTaxcodesDataLoading: false,
				searchText: '',
				showInfoModal: false,
				selectedTaxcodesInformation: {},
				page: 1,
				currentPage: 1,
				// pageLimit: 25,
				pageLimit: 35,
				taxCodeLists: []
			};
		},
		created() {
			this.fetchTaxcodesData();
		},
		computed: {
			tableData: {
				get() {
					return this.taxCodeLists?.data || [];
				}
			},

			pagination: {
				get() {
					return this.taxCodeLists.data ? this.taxCodeLists.meta : {};
				}
			},

			tableHeader() {
				return [
					{
						text: 'Name',
						value: 'name',
						class: 'text-uppercase th--text font-weight-bold',
						align: 'left'
					},
					{
						text: 'Description',
						value: 'description',
						class: 'text-uppercase th--text font-weight-bold',
						align: 'center'
					},
					{
						text: 'Taxable',
						value: 'taxable',
						class: 'text-uppercase th--text font-weight-bold',
						align: 'center'
					},
					{
						text: 'Actions',
						value: 'actions',
						class: 'text-uppercase th--text font-weight-bold',
						align: 'left'
					}
				];
			},

			taxRateData() {
				return this.selectedTaxcodesInformation?.tax_rates?.data || [];
			}
		},
		methods: {
			onToggleTaxcodesInformationModal(data = {}) {
				this.selectedTaxcodesInformation = data;
				this.showInfoModal = !this.showInfoModal;
			},

			async fetchTaxcodesData() {
				if (this.isTaxcodesDataLoading) {
					return;
				}
				this.isTaxcodesDataLoading = true;
				try {
					const { data } = await AkauntingService.getTaxCodes();
					this.taxCodeLists = data?.data || [];
					this.isTaxcodesDataLoading = false;
				} catch (error) {
					this.isTaxcodesDataLoading = false;
					apiErrorMessage(error);
				}
			}
		}
	};
</script>
<style lang="scss" scoped>
	@media screen and (max-width: 767px) {
		::v-deep {
			.v-data-table > .v-data-table__wrapper {
				height: calc(100vh - 244px) !important;
				overflow-y: auto;
				overflow-x: hidden;
			}
		}
	}
	::v-deep .v-data-table > .v-data-table__wrapper {
		height: calc(100vh - 320px);
		overflow-y: auto;
		overflow-x: hidden;
	}

	.w-100 {
		width: 100%;
	}

	.table {
		th,
		td {
			border: 1px solid #eee;
			padding: 8px;
		}
	}
</style>

<style lang="scss">
.account-settings-taxcodes-wrapper {
	.search-component-wrapper {
		.v-input {
			.v-input__control {
				.v-input__slot{
					min-height: 40px !important;
				}
			}
		}
	}

	.v-data-table {
		.v-data-table__wrapper {
			table {
				thead {
					tr {
						th {
							box-shadow: none;
							border-bottom: 2px solid #EBF2F5 !important;
							padding: 8px 12px;

							&:first-child {
								padding-left: 24px;
							}
						}
					}
				}

				tbody {
					tr {
						td {
							padding: 12px;
                        	border-bottom: 1px solid #EBF2F5 !important;

							&:first-child {
								padding-left: 24px;
							}

							.btn-more-items {
								min-width: 36px;
								padding: 6px !important;
								height: 35px !important;
								margin: 0 10px 0 auto;

								i {
									font-size: 18px;
								}
							}
						}
					}
				}
			}
		}
	}
}
</style>
