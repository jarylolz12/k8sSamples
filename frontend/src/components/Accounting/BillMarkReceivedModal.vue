<template>
	<div class="text-center">
		<v-dialog v-model="showDialog" max-width="480" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card :loading="isSaving" class="bill-mark-modal-accounting">
				<v-card-text class="pa-8">
					<v-icon size="52" color="#EB9B26">mdi-alert-circle-outline</v-icon>
					<h1 class="header-1 mt-4">Mark as Received</h1>
					<p>Mark as received the selected bill no <span>"{{ formData ? formData.document_number : '' }}"</span>?</p>

					<div class="d-flex mt-6">
						<v-btn
							class="mr-2 white--text text-capitalize btn-blue"
							color="#0171a1"
							depressed
							large
							@click="onMarkAsReceived"
							:loading="isSaving"
						>
							Mark as Received
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
			<v-snackbar timeout="2000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar>
		</v-dialog>
	</div>
</template>

<script>
	import { mapActions } from 'vuex';

	export default {
		name: 'BillMarkReceivedModal',
		props: ['open', 'formData'],

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
			...mapActions('accounting', ['billReceived']),

			async onMarkAsReceived() {
				if (this.isSaving) {
					return;
				}
				this.showSnackbar = false;
				this.isSaving = true;
				try {
					const { data } = await this.billReceived(this.formData.id);

					this.isSaving = false;
					this.$emit('toggle', null, { received: true, message: data.message });
				} catch (error) {
					const { data } = error.response || { data: {} };

					this.snackbarOption = {
						icon: 'mdi-alert-circle',
						color: 'green',
						message: data.message || 'Could not save the data.'
					};
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
.bill-mark-modal-accounting {
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
