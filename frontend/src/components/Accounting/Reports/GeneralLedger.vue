<template>
	<div>
        <v-row>
		    <v-col cols="12" md="12" lg="12" class="pb-0" v-for="report,i in reports" :key="i">
                <v-simple-table class="mb-5">
                    <thead>
						<tr>
							<th>{{report.account_name}} ({{report.type_name}})</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
                        <tr>
                            <th>Date</th>
                            <th>Transaction</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           <td>Opening Balance</td>
						   <td></td>
						   <td></td>
						   <td></td>
						   <td>{{ report.account_balance_opening}}</td>
                        </tr>
                        <tr v-for="ledger, i2 in report.ledgers" :key="i2">
                            <td>{{ledger.issued_at}}</td>
                            <td>{{ledger.transaction}}</td>
                            <td>{{ledger.debit}}</td>
                            <td>{{ledger.credit}}</td>
                            <td>{{ledger.closing_balance}}</td>
                        </tr>
						<tr>
							<td>Totals and Closing Balance</td>
							<td></td>
							<td>{{report.debit_total}}</td>
							<td>{{report.credit_total}}</td>
							<td>{{report.closing_balance}}</td>
						</tr>
						<tr>
							<td>Balance Change</td>
							<td></td>
							<td></td>
							<td></td>
							<td>{{report.closing_balance}}</td>
						</tr>
                    </tbody>
                </v-simple-table>
            </v-col>
        </v-row>
		<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
			<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
		</v-snackbar> -->
	</div>
</template>

<script>
	// import { mapActions } from 'vuex';
	import moment from 'moment';
	// import { apiErrorMessage, debounce } from '../../../utils/globalMethods';

	export default {
		name: 'GeneralLedger',
		components: {

		},
        props: ['reportData'],
		data() {
			return {
                // 
			};
		},
		computed: {
			tableHeader() {
				return [
					{
						text: 'Date',
						value: 'paid_at',
						class: 'text-uppercase th--text font-weight-bold',
						width: '15%',
						align: 'start'
					},
					{
						text: 'Transaction',
						value: 'number',
						class: 'text-uppercase th--text font-weight-bold',
						width: '31%',
						align: 'start'
					},
					{
						text: 'Debit',
						value: 'amount_formatted',
						class: 'text-uppercase th--text font-weight-bold',
						width: '18%',
						align: 'start'
					},
					{
						text: 'Credit',
						value: 'contact.name',
						class: 'text-uppercase th--text font-weight-bold',
						width: '18%',
						align: 'start'
					},
					{
						text: 'Balance',
						value: 'category.name',
						class: 'text-uppercase th--text font-weight-bold',
						width: '18%',
						align: 'start'
					},
				];
			},

			reports(){
				return this.reportData;
			}
		},

		watch: {
			// searchText: debounce(function() {
			// 	this.fetchData();
			// }, 300)
		},

		mounted() {
            // 
		},
		methods: {
			formatDate(date) {
				return moment(date).format('DD MMM YYYY');
			},
		}
	};
</script>

<style lang="scss" scoped>
	@media screen and (max-width: 600px) {
		::v-deep .v-data-table > .v-data-table__wrapper {
			height: calc(100vh - 354px) !important;
			overflow-y: auto;
			overflow-x: hidden;
		}
	}
	::v-deep .v-data-table {
		.v-data-table__wrapper {
			height: calc(100vh - 342px);
			overflow-y: auto;
			overflow-x: hidden;
		}

		&.no-current__pagination,
		&.no-data__table {
			.v-data-table__wrapper {
				height: calc(100vh - 285px);
			}
		}
	}
	.v-list-item {
		min-height: 36px;
	}
	.border-bottom {
		border-bottom: thin solid #EBF2F5;
	}
</style>
