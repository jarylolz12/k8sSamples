<template>
	<v-sheet color="transparent" class="accounting-bills-wrapper">
		<h3 class="font-20 mb-3 ml-2 mt-3 ml-sm-0 mt-sm-0">Journals</h3>
		<v-sheet>
			<v-data-table
				class="v-table-middle-align"
				v-bind:class="{
					'no-data__table' : (journals.length === 0),
					'no-current__pagination' : (pagination.last_page <= 1),
				}"
				fixed-header
				show-select
				v-model="selected"
				hide-default-footer
				disable-pagination
				:single-select="false"
				:headers="tableHeaders"
				:items="journals"
				:loading="isJournalEntryDataLoading"
			>
				<template v-slot:top>
					<div class="d-md-flex justify-end w-100 pt-3 px-4 pb-3 border-bottom">
						<div class="d-md-flex justify-md-end my-2 my-md-0 mx-md-0">
							<div class="d-flex">
								<div class="search-field-wrapper ml-2">
									<Search :inputData.sync="searchText" placeholder="Search No." />
								</div>
							</div>
							<div class="d-md-block d-none ml-2">
								<v-btn
									@click="onToggleJournalForm"
									class="text-capitalize"
									color="btn-blue"
									height="38"
								>
									{{ 'Add Journal' }}
								</v-btn>
							</div>
							<div class="d-sm-block d-md-none mt-2">
								<v-btn
									@click="onToggleJournalForm"
									class="text-capitalize d-block"
									color="btn-blue"
									height="38"
									width="100%"
								>
									{{ 'Add Journal' }}
								</v-btn>
							</div>
						</div>
					</div>
				</template>
				<template v-slot:[`item.journal_number`]="{ item }">
					<a
						style="text-decoration: none;"
						href="#"
						@click.prevent="
							$router.push(`#`)
						"
					>
						<span>{{ item.journal_number }}</span>
					</a>
				</template>
				<template v-slot:[`item.paid_at`]="{ item }">
					<span>{{ convertDate(item.paid_at) }}</span>
				</template>
				
				<template v-slot:[`item.actions`]="{ item }">
					<v-menu offset-y left content-class="outbound-lists-menu">
						<template v-slot:activator="{ on, attrs }">
							<v-btn v-bind="attrs" primary v-on="on" color="primary" class="btn-more-items btn-white" text outlined small>
								<v-icon>mdi-dots-horizontal</v-icon>
							</v-btn>
						</template>
						<v-list>
							<v-list-item link @click="onJournalSelected(item)">
								<v-list-item-title>{{ 'Edit' }}</v-list-item-title>
							</v-list-item>
							<!-- <v-list-item link @click="$router.push('#')" >
								<v-list-item-title>{{ 'View' }}</v-list-item-title>
							</v-list-item> -->
							<v-list-item link @click="onSelectDataToDelete(item)">
								<v-list-item-title
									class="text-red"
									>{{ 'Delete' }}</v-list-item-title
								>
							</v-list-item>
						</v-list>
					</v-menu>
				</template>
				<template v-slot:no-data>
					<div class="mt-5" v-if="isJournalEntryDataLoading">
						<v-progress-circular
							:size="40"
							color="#0171a1"
							indeterminate>
						</v-progress-circular>
					</div>

					<v-alert class="mt-2" v-if="!isJournalEntryDataLoading && transactionData.length === 0 && searchText == ''">
						<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
						<p class="subtitle-2 no-data-title">Add Journal</p>
						<span class="subtitle-p-data">You don't have any journal entry yet.</span>
						<br />
						<v-btn
							outlined
							text
							small
							@click="onToggleJournalForm"
							color="#0889a0"
							class="py-4 text-capitalize btn-white"
						>
							<v-icon size="18">mdi-plus</v-icon>
							{{ 'Add Journal Entry' }}
						</v-btn>
					</v-alert>

					<v-alert class="mt-2" v-if="!isJournalEntryDataLoading && transactionData.length === 0 && searchText !== ''">
						<img src="@/assets/icons/invoice-icon.svg" width="40px" height="42px" alt="" class="mb-3">
						<p class="subtitle-2 no-data-title">No matching result</p>
						<span class="subtitle-p-data">Sorry! We could not find any journal that matches your search term.</span>
						<br />
					</v-alert>
				</template>
				<template v-slot:loading>
					<div class="mt-5" v-if="isJournalEntryDataLoading">
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
				:total="pagination.last_page || 0"
				:count="pagination.total || 0"
				:current-page="currentPage"
				:total-visible="10"
				:pageLimit.sync="pageLimit"
				@pageSelected="onPaginationClick"
				@changeLimit="onChangePageLimit"
				:from="pagination ? pagination.from : 1"
				:to="pagination ? pagination.to : 1"
			/>
			<v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar>
            <journal-entry-form
                :open="showJournalFormDialog"
                :form-values="selectedJournalData"
                :is-edit-mode="isFormEditMode"
				@toggle="onToggleJournalForm"
            ></journal-entry-form>
			<journal-entry-delete-modal :open="showDeleteJournalModal" :form-data="formDataToDelete" @toggle="onToggleDeleteModal"></journal-entry-delete-modal>
		</v-sheet>
	</v-sheet>
</template>

<script>
	import { apiErrorMessage, debounce } from '../../utils/globalMethods';
	import { mapActions } from 'vuex';
	import Pagination from '../../components/Accounting/Pagination.vue';
	import Search from '../../components/Accounting/SearchInput.vue';
	import moment from 'moment';
    import JournalEntryForm from '../../components/Accounting/JournalEntryForm.vue';
    import JournalEntryDeleteModal from '../../components/Accounting/JournalEntryDeleteModal.vue';

	export default {
		name: 'ManualJournalEntry',
		components: {
			Search,
			Pagination,
            JournalEntryForm,
			JournalEntryDeleteModal,
		},
		data: () => ({
			showJournalFormDialog: false,
			showDeleteJournalModal: false,
			isFormEditMode: false,
			formDataSelected: {},
			formDataToDelete: {},
			searchText: '',
			selected: [],
			currentPage: 1,
			pageLimit: 35,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			},
			showSnackbar: false,
			isDataLoading: false,
			selectedJournalData: null,
			journalEntriesData: [],
			isJournalEntryDataLoading: false,
		}),

		created() {
			this.fetchJournalEntries();
		},

		watch: {
			searchText: debounce(function() {
				this.fetchJournalEntries();
			}, 300)
		},

		computed: {
			tableHeaders() {
				return [
					{
						text: 'Number',
						value: 'journal_number',
						class: 'text-uppercase th--text',
						width: '10%',
						align: 'start'
					},
					{
						text: 'Date',
						sortable: false,
						value: 'paid_at',
						class: 'text-uppercase th--text',
						width: '20%',
						align: 'start'
					},
					{
						text: 'Amount',
						value: 'amount',
						class: 'text-uppercase th--text',
						width: '15%',
						align: 'start'
					},
					{
						text: 'Description',
						value: 'description',
						class: 'text-uppercase th--text',
						width: '15%',
						align: 'start'
					},
					{
						text: 'Reference',
						value: 'reference',
						class: 'text-uppercase th--text',
						width: '12%',
						align: 'end'
					},
					{
                        text: '',
                        value: 'actions',
                        sortable: false
                    }
				];
			},

			journals: {
				get() {
					return this.journalEntriesData?.data || [];
				}
			},

			pagination: {
				get() {
					return this.journalEntriesData.data ? this.journalEntriesData.meta : {};
				}
			}
		},

		methods: {
			...mapActions('accounting', ['getJournalEntriesData']),
			moment,
			convertDate(val) {
				return moment(val).format('DD MMM YYYY');
			},

			async fetchJournalEntries(filters = {}) {
				if (this.isJournalEntryDataLoading) {
					return;
				}
				this.isJournalEntryDataLoading = true;
				try {
					const data = await this.getJournalEntriesData({
						...filters,
						page: this.currentPage,
						limit: this.pageLimit,
						search: this.searchText || '',
					});

					this.journalEntriesData = data;
					this.isJournalEntryDataLoading = false;
				} catch (error) {
					this.isJournalEntryDataLoading = false;
					apiErrorMessage(error);
				}
			},

			onPaginationClick(pageNumber) {
				this.currentPage = pageNumber;
				this.fetchJournalEntries();
			},

			onChangePageLimit() {
				this.currentPage = 1;
				this.fetchJournalEntries();
			},

			currencyDollarFormat(number = 0) {
				return new Intl.NumberFormat('en-US').format(number);
			},

			onSelectDataToDelete(data = {}) {
				this.showDeleteJournalModal = true;
				this.formDataToDelete = { ...data };
				if(!this.showDeleteJournalModal){
					this.formDataToDelete = null;
				}
			},

			onToggleJournalForm(options = {}) {
				this.showJournalFormDialog = !this.showJournalFormDialog;
				if (options.created || options.updated) {
					this.snackbarOption = {
						icon: 'mdi-check',
						color: 'success',
						message: options.message
					};
					this.showSnackbar = true;
					this.isFormEditMode = false;
					this.fetchJournalEntries();
					this.selectedJournalData = null;
				}
				if (!this.showJournalFormDialog) {
					this.isFormEditMode = false;
					this.selectedJournalData = null;
				}
			},

			onJournalSelected(data = null) {
				this.selectedJournalData = null;

				this.isFormEditMode = data !== null;

				this.selectedJournalData = JSON.parse(JSON.stringify(data));
				this.showJournalFormDialog = !this.showJournalFormDialog;
			},

			onToggleDeleteModal(options = {}) {
				this.showDeleteJournalModal = !this.showDeleteJournalModal;
				if (options.deleted) {
					this.snackbarOption = {
						icon: 'mdi-delete',
						color: 'red',
						message: options.message || 'Deleted'
					};
					this.fetchJournalEntries();
					this.showSnackbar = true;
				}
				this.formDataToDelete = {};
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
</style>

<style lang="scss">
@import './scss/bills.scss';
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/buttons.scss';
</style>