<template>
	<div class="text-center">
		<v-dialog v-model="showDialog" max-width="480" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card class="vendor-disable-modal-accounting">
				<v-card-text class="pa-8">
					<v-icon size="52" color="#EB9B26">mdi-alert-circle-outline</v-icon>
					<h1 class="header-1 my-4">{{ 'Disable Vendor' }}</h1>
					<p>{{ 'Do you want to disable the vendor' }} <span>"{{ formData ? formData.name : ''}}"</span>?</p>

					<div class="d-flex mt-6">
						<v-btn
							class="mr-2 white--text text-capitalize btn-blue"
							color="#0171a1"
							depressed
							large
							@click="onDelete"
							:disabled="isDeleting"
							:loading="isDeleting"
						>
							{{ 'Disable' }}
						</v-btn>
						<v-btn
							outlined
							large
							depressed
							class="teal--text text-capitalize btn-white"
							@click="onClose"
							:disabled="isDeleting"
						>
							{{ 'Cancel' }}
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
	props: ['open', 'formData'],

	data() {
		return {
			isDeleting: false,
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
			}
		}
	},

	methods: {
		...mapActions('accounting', ['disableVendor']),
		...globalMethods,
		async onDelete() {
			if (this.isDeleting) {
				return;
			}
			this.showSnackbar = false;
			this.isDeleting = true;
			try {
				const { message } = await this.disableVendor(this.formData.id);

				this.isDeleting = false;
				this.$emit('toggle', { disabled: true, message });
			} catch (error) {
				console.log(error);

				const { data } = error.response || { data: {} };

				// this.snackbarOption = {
				// 	icon: 'mdi-alert-circle',
				// 	color: 'error',
				// 	message: data.message || 'Could not disable the data.'
				// };

				const message = data.message || 'Could not disable the data.'
				this.notificationCustom(message)
				this.showSnackbar = true;
				this.isDeleting = false;
			}
		},

		onClose() {
			this.$emit('toggle');
		}
	}
};
</script>

<style lang="scss" scoped>
.vendor-disable-modal-accounting {
	.v-card__text {
		padding: 24px !important;

		h1 {
			color: #4a4a4a;
			font-size: 20px !important;
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
