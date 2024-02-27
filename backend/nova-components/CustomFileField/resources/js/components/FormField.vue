<!-- <template>
<default-field :field="field" :errors="errors">
	<template slot="field">
		<input :id="field.name" type="text" class="w-full form-control form-input form-input-bordered" :class="errorClasses" :placeholder="field.name" v-model="value" />
	</template>
</default-field>
</template>

<script>
import {
	FormField,
	HandlesValidationErrors
} from 'laravel-nova'

export default {
	mixins: [FormField, HandlesValidationErrors],

	props: ['resourceName', 'resourceId', 'field'],

	methods: {
		/*
		 * Set the initial, internal value for the field.
		 */
		setInitialValue() {
			this.value = this.field.value || ''
		},

		/**
		 * Fill the given FormData object with the field's internal value.
		 */
		fill(formData) {
			formData.append(this.field.attribute, this.value || '')
		},

		/**
		 * Update the field's internal value.
		 */
		handleChange(value) {
			this.value = value
		},
	},
}
</script> -->

<template>
<default-field :field="field" :errors="errors">
	<template slot="field">
		<div v-if="hasValue" class="mb-6">

			<template v-if="field.value">
				<card class="flex item-center relative border border-lg border-50 overflow-hidden p-4">
					<span class="truncate mr-3"> {{ filename(field.value) }} </span>

					<button @click="confirmRemoval" type="button" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center ml-auto" dusk="mbl_copy-internal-delete-link">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
							<path fill-rule="nonzero"
								d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z">
							</path>
						</svg>
					</button>
				</card>
			</template>



			<portal to="modals">
				<confirm-upload-removal-modal v-if="removeModalOpen" @confirm="removeFile" @close="closeRemoveModal" />
			</portal>
		</div>

		<span class="form-file mr-4" :class="{ 'opacity-75': isReadonly }">
			<input ref="fileField" :dusk="field.attribute" class="form-file-input select-none" type="file" :id="idAttr" name="name" @change="fileChange" :disabled="isReadonly" :accept="field.acceptedTypes" />
			<label :for="labelFor" class="form-file-btn btn btn-default btn-primary select-none">
				{{ __('Choose File') }}
			</label>
		</span>

		<span class="text-gray-50 select-none"> {{ currentLabel }} </span>

	</template>
</default-field>
</template>

<script>
import {
	FormField,
	HandlesValidationErrors,
	Errors
} from 'laravel-nova'

export default {

	props: ['resourceName', 'resourceId', 'field'],


	mixins: [HandlesValidationErrors, FormField],

	components: {
		// DeleteButton,
	},

	data: () => ({
		file: null,
		fileName: '',
		removeModalOpen: false,
		missing: false,
		deleted: false,
		uploadErrors: new Errors(),
	}),

	mounted() {
		this.field.fill = formData => {
			if (this.file) {
				formData.append(this.field.attribute, this.file, this.fileName)
			}
		}
	},

	methods: {
		/**
		 * Respond to the file change
		 */
		fileChange(event) {
			let path = event.target.value
			let fileName = path.match(/[^\\/]*$/)[0]
			this.fileName = fileName
			this.file = this.$refs.fileField.files[0]
		},

		/**
		 * Confirm removal of the linked file
		 */
		confirmRemoval() {
			this.removeModalOpen = true
		},

		/**
		 * Close the upload removal modal
		 */
		closeRemoveModal() {
			this.removeModalOpen = false
		},

		/**
		 * Remove the linked file from storage
		 */
		async removeFile() {
			this.uploadErrors = new Errors()

			const {
				resourceName,
				resourceId,
				relatedResourceName,
				relatedResourceId,
				viaRelationship,
			} = this

			const attribute = this.field.attribute


			const uri = `/custom-api/${resourceName}/${resourceId}/field/${attribute}`


			try {
				await Nova.request()
					.delete(uri)
				this.closeRemoveModal()


				this.deleted = true
				this.$emit('file-deleted')

				Nova.success(
					this.__('The :resource file was deleted!', {
						resource: attribute
					}))
			} catch (error) {
				this.closeRemoveModal()

				if (error.response.status == 422) {
					this.uploadErrors = new Errors(error.response.data.errors)
				}
				Nova.error(
					this.__('There was a problem deleting the file.')
				)
			}


		},

		/*
		 * Set the initial, internal value for the field.
		 */

		setInitialValue() {
			this.value = this.field.value || ''
		},

		/**
		 * Fill the given FormData object with the field's internal value.
		 */

		fill(formData) {
			formData.append(this.field.attribute, this.value || '')
		},

		/**
		 * Update the field's internal value.
		 */
		handleChange(value) {
			this.value = value
		},
		filename(file) {
			// console.log(this.path)
			// return file
			// const path = require('path')
			// return path
			// 	.parse(file)
			// 	.base
			return file.split('\\')
				.pop()
				.split('/')
				.pop();

		}
	},

	computed: {
		/**
		 * Determine if the field has an upload error.
		 */
		hasError() {
			return this.uploadErrors.has(this.fieldAttribute)
		},

		/**
		 * Return the first error for the field.
		 */
		firstError() {
			if (this.hasError) {
				return this.uploadErrors.first(this.fieldAttribute)
			}
		},

		/**
		 * The current label of the file field.
		 */
		currentLabel() {
			return this.fileName || this.__('no file selected')
		},

		/**
		 * The ID attribute to use for the file field.
		 */
		idAttr() {
			return this.labelFor
		},

		/**
		 * The label attribute to use for the file field.
		 */
		labelFor() {
			return `file-${this.field.attribute}`
		},

		/**
		 * Determine whether the field has a value.
		 */
		hasValue() {
			return (
				Boolean(this.field.value || this.imageUrl) &&
				!Boolean(this.deleted) &&
				!Boolean(this.missing)
			)
		},

		/**
		 * Return the preview or thumbnail URL for the field.
		 */


		/**
		 * Determine the maximum width of the field.
		 */
		maxWidth() {
			return this.field.maxWidth || 320
		},
	},
}
</script>
