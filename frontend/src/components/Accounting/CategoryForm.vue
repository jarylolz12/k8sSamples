<template>
	<v-form ref="categoryForm">
		<v-dialog
			v-model="showDialog"
			max-width="600"
			origin="top center"
			content-class="accounting-module-dialog pa-0"
			persistent
			scrollable
			:fullscreen="isMobile"
		>
			<v-card :loading="isSaving">
				<v-card-title class="pa-0 z-index-front">
					<span class="headline">{{ isEditMode ? 'Edit Category' : 'Add Category' }}</span>
					<v-spacer></v-spacer>
					<v-btn icon @click="onClose">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text class="pt-3 item-form-column">
					<label class="form-label text-uppercase" for="formdata-name">{{ 'Name' }}</label>
					<v-text-field
						v-model="formData.name"
						:label="'Enter Name'"
						solo
						class="app-theme-input-border"
						flat
						required
						dense
						outlined
						id="formdata-name"
						:rules="[(v) => !!v || 'Field required']"
						hide-details="auto"
					></v-text-field>

					<label class="form-label text-uppercase" for="formdata-type">{{ 'Type' }}</label>
					<v-select
						v-model="formData.type"
						:items="categoryTypeList"
						solo
						flat
						class="app-theme-input-border"
						dense
						outlined
						:rules="[(v) => !!v || 'Field required']"
						hide-details="auto"
						placeholder="Select Type"
						:menu-props="{ bottom: true, offsetY: true, contentClass: 'accounting-lists-items' }"
					>
					</v-select>

					<label class="form-label text-uppercase">{{ 'Color' }}</label>
					<v-color-picker v-model="formData.color" mode="hexa" width="580"></v-color-picker>

					<div>
						<label class="form-label text-uppercase">{{ 'Enabled' }}</label>
						<div>
							<v-btn
								small
								rounded
								outlined
								:color="formData.enabled ? 'success' : 'error'"
								class="pa-4"
								@click="onToggleEnable"
							>
								<div
									v-if="formData.enabled"
									class="d-flex align-center justify-space-around"
									style="min-width: 60px"
								>
									<v-icon>mdi-check-circle</v-icon>
									{{ 'Yes' }}
								</div>
								<div v-else class="d-flex align-center justify-space-around" style="min-width: 60px">
									<v-icon>mdi-minus-circle</v-icon>
									{{ 'No' }}
								</div>
							</v-btn>
						</div>
					</div>
				</v-card-text>
				<v-divider></v-divider>
				<v-card-actions class="justify-start">
					<v-btn
						@click="onSaveForm"
						class="text-capitalize btn-blue"
						color="primary"
						v-if="!isEditMode"
						:disabled="isSaving"
						:loading="isSaving"
						>{{ 'Save' }}</v-btn
					>
					<v-btn
						@click="onSaveForm"
						class="text-capitalize btn-blue"
						color="primary"
						v-if="isEditMode"
						:loading="isSaving"
						:disabled="isSaving"
						>{{ 'Update' }}</v-btn
					>
					<v-btn text outlined class="text-capitalize primary--text btn-white" @click="onClose" :disabled="isSaving">{{
						'Cancel'
					}}</v-btn>
					<v-spacer></v-spacer>
				</v-card-actions>
			</v-card>
			<!-- <v-snackbar timeout="5000" vertical :color="snackbarOption.color" v-model="showSnackbar" bottom>
				<v-icon v-if="snackbarOption.icon">{{ snackbarOption.icon }}</v-icon> {{ snackbarOption.message }}
			</v-snackbar> -->
		</v-dialog>
	</v-form>
</template>

<script>
import AkauntingService from '../../services/akaunting.service';
import globalMethods from '../../utils/globalMethods';

export default {
	name: 'CategoryForm',
	props: ['open', 'isEditMode', 'formValues'],
	data: () => ({
		formData: {
			name: '',
			type: '',
			color: '#FFFFFF',
			enabled: 1
		},
		defaultFields: null,
		isSaving: false,
		snackbarOption: {},
		showSnackbar: false
	}),
	created() {
		this.defaultFields = JSON.parse(JSON.stringify(this.formData));
	},
	watch: {
		formValues(value) {
			if (this.open && this.isEditMode) {
				this.formData = {
					id: value.id,
					name: value.name,
					type: value.type,
					color: (value.color || '').toUpperCase(),
					enabled: value.enabled ? 1 : 0
				};
			}
		},
		open(isTrue) {
			if (!isTrue) {
				this.showSnackbar = false;
			}
		}
	},
	computed: {
		showDialog: {
			get() {
				return this.open;
			}
		},
		categoryTypeList() {
			return [
				{ text: 'Expense', value: 'expense' },
				{ text: 'Income', value: 'income' },
				{ text: 'Item', value: 'item' },
				{ text: 'Other', value: 'other' }
			];
		},
		isMobile() {
			return this.$vuetify.breakpoint.mobile;
		}
	},
	methods: {
		...globalMethods,
		onClose() {
			this.$refs.categoryForm.reset();
			this.formData = {
				...this.defaultFields
			};
			this.showSnackbar = false;
			this.$emit('toggle');
		},

		onToggleEnable() {
			this.formData = {
				...this.formData,
				enabled: this.formData.enabled === 1 ? 0 : 1
			};
		},

		async onSaveForm() {
			if (this.isSaving) {
				return;
			}

			const validated = this.$refs.categoryForm.validate();
			if (validated) {
				try {
					this.isSaving = true;
					const isEdit = this.isEditMode;
					const formData = { ...this.formData };
					const data = isEdit
						? await AkauntingService.updateCategory(formData)
						: await AkauntingService.createCategory(formData);

					const message = data.message || 'Data was successfully saved.';

					// this.snackbarOption = {
					// 	icon: 'mdi-check',
					// 	color: 'success',
					// 	message
					// };
					this.$emit('toggle', { created: true, message });
				} catch (error) {
					console.log(error);
					const { data } = error.response || { data: {} };

					// this.snackbarOption = {
					// 	icon: 'mdi-alert-circle',
					// 	color: 'error',
					// 	message: data.message || 'Could not save the data.'
					// };

					const message = data.message || 'Could not save the data.'
					this.notificationError(message)
				} finally {
					this.showSnackbar = true;
					this.isSaving = false;
				}
			}
		}
	}
};
</script>

<style lang="scss" scoped>
	$button-bg-color: #0171a1;
	$form-label: #819fb2;
	.form-label {
		color: $form-label;
	}

	.border-dashed {
		border: 1px dashed $form-label !important;
	}

	::v-deep {
		.v-dialog {
			.v-input__control {
				background: transparent !important;
			}

			.v-text-field--enclosed .v-input__prepend-inner {
				margin-top: 0 !important;
			}

			.v-chip {
				font-size: 12px;
				height: 30px;
				text-transform: capitalize !important;

				.v-chip__content {					
					font-family: 'Inter-Medium', sans-serif;
				}
			}

			.v-input {
				&.v-input--is-disabled {
					fieldset {
						background-color: #ebf2f5 !important;
						border: 1px solid #ebf2f5 !important;
					}
				}
			}
		}

		.v-select--is-menu-active {
			background: transparent !important;
		}

		fieldset {
			border: 1px solid #b3cfe0;
			font-size: 14px;
		}

		fieldset:focus {
			border: 1px solid #b3cfe0 !important;
			outline: 0;
		}

		.v-text-field__slot input::placeholder {
			color: #b4cfe0 !important;
		}
	}
</style>
