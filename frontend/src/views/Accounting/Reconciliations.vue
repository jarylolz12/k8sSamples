<template>
	<v-sheet color="transparent" class="accounting-reconciliations-wrapper">
		<h3 class="font-20 mb-3 ml-2 mt-3 ml-sm-0 mt-sm-0">Reconciliations</h3>
		<v-sheet>
			<v-data-table
				class="v-table-middle-align"
				v-bind:class="{
					'no-data__table' : (tableData.length === 0),
					'no-current__pagination' : (pagination.last_page <= 1),
				}"
				fixed-header
				show-select
				v-model="selected"
				hide-default-footer
				disable-pagination
				:single-select="false"
				:headers="tableHeaders"
				:items="tableData"
				:loading="isDataLoading"
			>
				<template v-slot:top>
					<div class="d-md-flex justify-end w-100 pt-3 px-4 pb-3 border-bottom">
						<div class="d-md-flex justify-md-end my-2 my-md-0 mx-md-0">
							<div class="d-flex">
								<div class="search-field-wrapper ml-2">
									<!-- <v-text-field :label="'bill_search_no'" prepend-inner-icon="mdi-magnify" clearable hide-details flat outlined solo dense v-model="searchText" color="primary"></v-text-field> -->
									<Search :inputData.sync="searchText" placeholder="Search No." />
								</div>
							</div>
							<div class="d-md-block d-none ml-2">
								<v-btn
									@click="onClickNewReconciliation"
									class="text-capitalize"
									color="btn-blue"
									height="38"
								>
									{{ 'New' }}
								</v-btn>
							</div>
							<div class="d-sm-block d-md-none mt-2">
								<v-btn
									@click="onClickNewReconciliation"
									class="text-capitalize d-block"
									color="btn-blue"
									height="38"
									width="100%"
								>
									{{ 'New' }}
								</v-btn>
							</div>
						</div>
					</div>
				</template>
				<template v-slot:[`item.created_at`]="{ item }">
					<span>{{ convertDate(item.created_at) }}</span>
				</template>
				<template v-slot:[`item.period`]="{ item }">
					<span>{{ convertDate(item.started_at) }} </span> - 
					<span>{{ convertDate(item.ended_at) }}</span>
				</template>
				<template v-slot:[`item.reconciled`]="{ item }">
					<div class="item-enabled-wrapper">
						<span v-if="item.reconciled" class="item-active-text">Yes</span>
						<span v-else class="item-inactive-text">No</span>
					</div>
				</template>
				<template v-slot:[`item.closing_balance`]="{ item }">
					<span>{{ item.closing_balance_formatted }}</span>
				</template>
				<template v-slot:[`item.actions`]="{ item }">
					<v-menu offset-y left content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn v-bind="attrs" primary v-on="on" color="primary" class="btn-more-items btn-white" text outlined small>
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>
						<v-list>
							<v-list-item link @click="onEditClick(item)">
								<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
							</v-list-item>
							<v-list-item
								link
							>
								<v-list-item-title>{{ 'View' }}</v-list-item-title>
							</v-list-item>
							<!-- <v-list-item
								link
								@click="$router.push(`/accounting/reconciliations/${item.id}`)"
							>
								<v-list-item-title>{{ 'View' }}</v-list-item-title>
							</v-list-item> -->
							<v-list-item
								link
								@click="onDeleteClick(item)"
							>
								<v-list-item-title
									class="text-red"
									>{{ 'Delete' }}</v-list-item-title
								>
							</v-list-item>
						</v-list>
					</v-menu>
				</template>
				<template v-slot:no-data>
					<div class="mt-5" v-if="isDataLoading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>

					<v-alert class="mt-2" v-if="!isDataLoading && tableData.length === 0 && searchText == ''">
						<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
						<p class="subtitle-2 no-data-title">Create New</p>
						<span class="subtitle-p-data">You don't have any reconciliations yet.</span>
						<br />
						<v-btn
							outlined
							text
							small
							@click="onClickNewReconciliation"
							color="#0889a0"
							class="py-4 text-capitalize btn-white"
						>
							<v-icon size="18">mdi-plus</v-icon>
							{{ 'Reconcile now' }}
						</v-btn>
					</v-alert>

					<v-alert class="mt-2" v-if="!isDataLoading && tableData.length === 0 && searchText !== ''">
						<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
						<p class="subtitle-2 no-data-title">No matching result</p>
						<span class="subtitle-p-data">Sorry! We could not find any reconciliations that matches your search term.</span>
						<br />
					</v-alert>
				</template>
				<template v-slot:loading>
					<div class="mt-5" v-if="isDataLoading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>
				</template>
			</v-data-table>
			<v-divider />
			<pagination
				:total="pagination.last_page || 1"
				:count="pagination.total || 0"
				:current-page="currentPage"
				:total-visible="10"
				:pageLimit.sync="pageLimit"
				@pageSelected="onPaginationClick"
				@changeLimit="onChangePageLimit"
				:from="pagination ? pagination.from : 1"
				:to="pagination ? pagination.to : 1"
			/>
		</v-sheet>
        <reconciliation-form
            :open="showFormDialog"
			:is-edit-mode="isFormEditMode"
			:form-values="selectedReconciliationData"
			@toggle="onToggleFormModal"
        ></reconciliation-form>
        <reconciliation-delete-modal
            :open="showDeleteDialog"
			:form-values="selectedReconciliationData"
			@toggle="onToggleDeleteModal"
        ></reconciliation-delete-modal>
	</v-sheet>
</template>

<script>

import { apiErrorMessage, debounce } from '../../utils/globalMethods';
import { mapActions } from 'vuex';
import moment from 'moment';
import globalMethods from '../../utils/globalMethods'
import Pagination from '../../components/Accounting/Pagination.vue';
import Search from '../../components/Accounting/SearchInput.vue';
import ReconciliationForm from '../../components/Accounting/ReconciliationForm.vue';
import ReconciliationDeleteModal from '../../components/Accounting/ReconciliationDeleteModal.vue';

export default {
	name: 'AccountingReconciliations',
	components: {
		Search,
        Pagination,
        ReconciliationForm,
		ReconciliationDeleteModal,
	},
	data: () => ({
		showFormDialog: false,
		showDeleteDialog: false,
		isFormEditMode: false,
		selectedReconciliationData: {},
		searchText: '',
		selected: [],
		filterBy: '',
		selectedStatus: '',
		currentPage: 1,
		pageLimit: 25,
		showSnackbar: false,
		reconciliationsData: [],
		isDataLoading: false,
	}),

	created() {
		this.fetchReconciliations();
	},

	watch: {
		searchText: debounce(function() {
			this.fetchReconciliations();
		}, 300)
	},

	computed: {
		tableHeaders() {
			return [
				{
					text: 'Created',
					value: 'created_at',
					class: 'text-uppercase th--text',
					width: '20%',
					align: 'start'
				},
				{
					text: 'Account',
					sortable: false,
					value: 'account.name',
					class: 'text-uppercase th--text',
					width: '20%',
					align: 'start'
				},
				{
					text: 'Period',
					value: 'period',
					class: 'text-uppercase th--text',
					width: '20%',
					align: 'start'
				},
				{
					text: 'Reconciled',
					value: 'reconciled',
					class: 'text-uppercase th--text',
					width: '18%',
					align: 'start'
				},
				{
					text: 'Closing Balance',
					value: 'closing_balance',
					class: 'text-uppercase th--text',
					width: '18%',
					align: 'start'
				},
				{ text: '', value: 'actions', sortable: false }
			];
		},

		tableData: {
			get() {
				return this.reconciliationsData?.data || [];
			}
		},

		pagination: {
			get() {
				return this.reconciliationsData.data ? this.reconciliationsData.meta : {};
			}
		}
	},

	methods: {
		...mapActions('accounting', ['getReconciliations']),
		moment,
		...globalMethods,
        
		convertDate(val) {
			return moment(val).format('DD MMM YYYY');
		},

		async fetchReconciliations(filters = {}) {
			if (this.isDataLoading) {
				return;
			}
			this.isDataLoading = true;
			try {
				const data = await this.getReconciliations({
					...filters,
					page: this.currentPage,
					limit: this.pageLimit,
					search: this.searchText || '',
					status: this.selectedStatus
				});

				this.reconciliationsData = data.data;
				this.isDataLoading = false;
			} catch (error) {
				this.isDataLoading = false;
				apiErrorMessage(error);
			}
		},

		onClickNewReconciliation(){
			this.showFormDialog = !this.showFormDialog;
			this.isFormEditMode = false;
		},

        onToggleFormModal(options = {}) {
			this.showFormDialog = !this.showFormDialog;
			if (options.created || options.updated) {
				this.notificationMessage(options.message)
				this.showSnackbar = true;
				this.isFormEditMode = false;
				this.fetchReconciliations();
			}
			this.selectedReconciliationData = {};
		},

		onPaginationClick(pageNumber) {
			this.currentPage = pageNumber;
			this.fetchReconciliations();
		},

		onChangePageLimit() {
			this.currentPage = 1;
			this.fetchReconciliations();
		},

		onEditClick(data){
			this.isFormEditMode = true;
			this.selectedReconciliationData = data;
			this.showFormDialog = !this.showFormDialog;
		},

		onDeleteClick(data){
			this.isFormEditMode = true;
			this.selectedReconciliationData = data;
			this.showDeleteDialog = !this.showDeleteDialog;
		},

		onToggleDeleteModal(options = {}) {
			this.selectedReconciliationData = {};
			this.showDeleteDialog = !this.showDeleteDialog;
			
			if (options.deleted) {
				const message = options.message || 'Reconciliation has been deleted.'
				this.notificationCustom(message)
				this.fetchReconciliations();
				this.showSnackbar = true;
			}
		},

	}
};
</script>

<style lang="scss" scoped>
	$form-color: #0889a0;
	$btn-active-color: #0171a1;
	$text-muted-color: #6d858f;
	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 298px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table {
		.v-data-table__wrapper {
			height: calc(100vh - 286px);
			overflow-y: auto;
			overflow-x: hidden;
		}

		&.no-current__pagination,
		&.no-data__table {
			.v-data-table__wrapper {
				height: calc(100vh - 230px);
			}
		}
	}
	.text-green {
		color: #07a107be;
	}

	.text-red {
		color: red;
	}

	.form-label {
		color: $form-color;
	}

	.form-border {
		border: 1px solid;
		border-color: $form-color !important;
	}

	.btn-active-color:active {
		background-color: $btn-active-color;
		color: #fff;
	}

	.text-muted {
		color: ext-muted-color;
	}

	.v-btn.v-item--active.v-btn--active {
		color: $btn-active-color;
	}

	.search-field-wrapper {
		max-width: 400px;
	}

	.text-white {
		color: #fff;
	}

	.text-primary {
		color: var(--shifl-pressed-blue);
	}

	.v-list-item {
		min-height: 36px;
	}

	.btn-primary {
		background-color: $btn-active-color !important;
		color: #ffffff !important;
	}

	.border-bottom {
		border-bottom: thin solid #EBF2F5;
	}

	.font-20 {
		font-size: 20px;
	}

	.draft {
		background-color: #F1F6FA !important;
		color: #4a4a4a !important;
		font-family: 'Inter-Medium', sans-serif;
		border-radius: 99px;
		padding: 6px 10px;
		font-size: 12px;
	}

	.received {
		background-color: #F5F9FC !important;
		border-radius: 99px;
		padding: 6px 10px;
		font-size: 12px;
		color: #16B442 !important;
		font-family: 'Inter-Medium', sans-serif;
	}
</style>

<style lang="scss">
@import './scss/reconciliations.scss';
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';
</style>