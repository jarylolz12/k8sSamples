<template>
	<div class="text-center">
		<v-dialog v-model="showDialog" max-width="480" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card class="invoice-mark-modal-accounting" :loading="isSaving">
				<v-card-text class="pa-8">
					<img src="@/assets/icons/question-blue.svg" width="48px" height="48px">
					<h1 class="header-1 my-4">Mark as Sent</h1>
					<p>This will mark the invoice as sent.</p>
					<div class="d-flex mt-6">
						<v-btn
							class="mr-2 white--text text-capitalize btn-blue"
							color="#0171a1"
							depressed
							large
							@click="onSave"
							:loading="isSaving"
						>
							Mark as Sent
						</v-btn>
						<v-btn
							outlined
							large
							depressed
							class="teal--text text-capitalize btn-white"
							@click="onClose"
							:disabled="isSaving"
						>
							Cancel
						</v-btn>
					</div>
				</v-card-text>
			</v-card>
			<!-- <v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar> -->
		</v-dialog>
	</div>
</template>

<script>
import { mapActions } from 'vuex';
import globalMethods from '../../utils/globalMethods'

export default {
	name: 'InvoiceMarkSent',
	props: ['open', 'invoiceId'],

	data() {
		return {
			isSaving: false,
			errorMessage: '',
			showSnackbar: false,
			snackbarOption: {
				icon: '',
				message: '',
				color: ''
			}
		};
	},

	computed: {
		showDialog: {
			get() {
				return this.open;
			},
			set(value) {
				this.$emit('toggle', value);
			}
		}
	},

	methods: {
		...mapActions('accounting', ['markInvoiceAsSent']),
		...globalMethods,
		async onSave() {
			if (this.isSaving) {
				return;
			}
			this.showSnackbar = false;
			this.isSaving = true;
			try {
				const { data } = await this.markInvoiceAsSent(this.invoiceId);

				this.isSaving = false;
				this.$emit('toggle', null, { success: true, message: data.message });
			} catch (error) {
				const { data } = error.response || { data: {} };

				// this.snackbarOption = {
				// 	icon: 'mdi-alert-circle',
				// 	color: 'error',
				// 	message: data.message || 'Could not save the data.'
				// };

				const message = data.message || 'Could not save the data.'
				this.notificationCustom(message)
				this.showSnackbar = true;
				this.isSaving = false;
			}
		},

		onClose() {
			this.$emit('toggle');
		}
	}
};
</script>

<style lang="scss" scoped>
.invoice-mark-modal-accounting {
	.v-card__text {
		padding: 24px !important;

		h1 {
			color: #4a4a4a;
			font-size: 20px;
			margin-bottom: 10px !important;
		}

		p {
			margin-bottom: 0;
			font-size: 14px;
			color: #4a4a4a;
			line-height: 20px;
			
			span {
				font-family: 'Inter-SemiBold', sans-serif;
			}
		}
	}
	
}
</style>
