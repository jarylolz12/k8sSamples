<template>
	<v-sheet color="transparent">
		<h3 class="font-20 mb-3 ml-2 mt-3 ml-sm-0 mt-sm-0">Transactions</h3>
		<!-- <div class="transaction-summary">
			<div class="due-info">
				<h3>Total Due</h3>
				<h2>555</h2>
			</div>
			<div
				class="due-button"
			>
				<v-btn class="btn-white">
					<img
						src="../../assets/icons/checkMark.png"
						class="mr-1"
						width="15px"
						height="15px"
						alt=""
					/>
					<span class="green--text">All Paid</span>
				</v-btn>
			</div>
		</div> -->
		<v-sheet color="white">
			<v-card flat outlined color="#EBF2F5" style="border-radius: 4px;">
				<v-tabs v-model="tab" height="56">
					<v-tab class="text-capitalize">Incomes</v-tab>
					<v-tab class="text-capitalize">Expenses</v-tab>
				</v-tabs>
				<v-divider></v-divider>
				<v-tabs-items v-model="tab">
					<v-tab-item :transition="false" :reverse-transition="false">
						<accounting-income-transactions></accounting-income-transactions>
					</v-tab-item>
					<v-tab-item :transition="false" :reverse-transition="false">
						<accounting-expense-transactions></accounting-expense-transactions>
					</v-tab-item>
				</v-tabs-items>
			</v-card>
		</v-sheet>
	</v-sheet>
</template>

<script>
import AccountingIncomeTransactions from '../../components/Accounting/IncomeTransactions.vue';
import AccountingExpenseTransactions from '../../components/Accounting/ExpenseTransactions.vue';
import { mapGetters } from 'vuex';

export default {
	name: 'AccountingTransactions',
	components: {
		AccountingIncomeTransactions,
		AccountingExpenseTransactions,
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
<style lang="scss" scoped>
// @media screen and (max-width: 600px) {
// 	::v-deep .v-data-table > .v-data-table__wrapper {
// 		height: calc(100vh - 296px) !important;
// 		overflow-y: auto;
// 		overflow-x: hidden;
// 	}
// }

h3 {
	font-size: 24px !important;
	margin-bottom: 0;
	color: #4a4a4a;
}

.v-tabs {
	.v-tab {
		letter-spacing: 0;
		text-transform: capitalize !important;
		font-family: 'Inter-SemiBold', sans-serif;
		color: #4a4a4a !important;
		font-size: 14px;
		padding: 6px 24px;

		&.v-tab--active {
			color: #0171A1;
		}
	}
}
.transaction-summary {
		margin-bottom: 24px;
        min-height: 114px;
        background-color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px;

        .due-info {
            h3 {
                color: #6D858F;
                font-size: 16px !important;
                font-family: 'Inter-Regular', sans-serif;
            }

            h2 {
                color: #4A4A4A;
                font-size: 24px !important;
            }
        }
    }
</style>
