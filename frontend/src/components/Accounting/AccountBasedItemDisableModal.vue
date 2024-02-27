<template>
	<div class="text-center">
		<v-dialog v-model="showDialog" max-width="480" origin="top center" content-class="accounting-module-dialog pa-0" persistent scrollable>
			<v-card>
				<v-card-text class="pa-8">
					<v-icon size="58" color="orange">mdi-alert-circle-outline</v-icon>
					<h2 class="my-4">{{ 'Disable Account Based Item' }}</h2>
					{{ 'Do you want to disable the Account Based Item' }} "{{ formData.name }}"?

					<div class="mt-8">
						<v-btn
							class="mr-2 white--text text-capitalize"
							color="#0171a1"
							depressed
							large
							@click="onDelete"
							:disabled="isDeleting"
							:loading="isDeleting"
						>
							{{ 'disable' }}
						</v-btn>
						<v-btn
							outlined
							large
							depressed
							class="teal--text text-capitalize"
							@click="onClose"
							:disabled="isDeleting"
						>
							{{ 'Cancel' }}
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
			...mapActions('accounting', ['disableAccountBasedItem']),

			async onDelete() {
				if (this.isDeleting) {
					return;
				}
				this.showSnackbar = false;
				this.isDeleting = true;
				try {
					const { message } = await this.disableAccountBasedItem(this.formData.id);

					this.isDeleting = false;
					this.$emit('toggle', { disabled: true, message });
				} catch (error) {
					console.log(error);

					const { data } = error.response || { data: {} };

					this.snackbarOption = {
						icon: 'mdi-alert-circle',
						color: 'error',
						message: data.message || 'Could not disable the data.'
					};
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

<style lang="scss" scoped></style>
