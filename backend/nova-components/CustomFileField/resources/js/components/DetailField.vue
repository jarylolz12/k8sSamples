<!-- <template>
    <panel-item :field="field" />
</template>

<script>
export default {
	props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script> -->

<template>
<panel-item :field="field">
	<div slot="value">
		<!-- <template v-if="shouldShowLoader">
			<ImageLoader :src="imageUrl" :maxWidth="maxWidth" :rounded="rounded" @missing="value => (missing = value)" />
		</template> -->

		<template v-if="field.value">
			<span class="break-words"> {{ filename(field.value) }} </span>
		</template>

		<span v-if="!field.value">&mdash;</span>

		<p v-if="field.value" class="flex items-center text-sm mt-3">
			<a :dusk="field.attribute + '-download-link'" @keydown.enter.prevent="download" @click.prevent="download" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center">
				<icon class="mr-2" type="download" view-box="0 0 24 24" width="16" height="16" />
				<span class="class mt-1">{{ __('Download') }}</span>
			</a>
		</p>
	</div>
</panel-item>
</template>

<script>
// import ImageLoader from '@/components/ImageLoader'
import axios from 'axios'

export default {
	props: ['resource', 'resourceName', 'resourceId', 'field'],

	components: {
		// ImageLoader
	},

	data: () => ({
		missing: false
	}),

	methods: {
		/**
		 * Download the linked file
		 */
		download() {
			const {
				resourceName,
				resourceId
			} = this
			const attribute = this.field.attribute

			// axios.post('/custom-api/file/download', {
			// 		headers: {
			// 			'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content')
			// 				.value,
			// 			'Accept': 'application/json'
			// 		},
			// 		file: encodeURIComponent(this.field.value)
			// 	})
			// 	.then(res => {
			// 		console.log(res)
			// 	})
			// 	.then(err => {
			// 		console.log(err)
			// 	})

			// .then(res => {
			// 	Nova.success(
			// 		this.__('The resource file was deleted!'))
			// })
			// .catch(err => {
			// 	// console.log(err)
			// 	Nova.error(
			// 		this.__('There was a problem deleting the file.')
			// 	)
			//
			// })

			//

			if (this.field.attribute==='mbl_copy_surrendered') {
				window.location.href = `/custom/download-mbl-copy-surrendered/${resourceId}`
			} else {
				const link = document.createElement('a');
				link.href = this.field.attribute === 'wnine_doc' || this.field.attribute === 'ssfour_doc' ?
				  `/custom-api/${resourceName}/${resourceId}/download-custom/${attribute}` :
				  `/custom-api/${resourceName}/${resourceId}/download/${attribute}`;
				link.download = `${attribute}`;
				document.body.appendChild(link);
				link.click();
				link.href = '';
			}
			
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
		hasValue() {
			return (
				Boolean(this.field.value || this.imageUrl) && !Boolean(this.missing)
			)
		},

		// shouldShowLoader() {
		// 	return Boolean(this.imageUrl)
		// },

		// shouldShowToolbar() {
		// 	return Boolean(this.field.downloadable && this.hasValue)
		// },

		imageUrl() {
			return this.field.previewUrl || this.field.thumbnailUrl
		},

		rounded() {
			return this.field.rounded
		},

		maxWidth() {
			return this.field.maxWidth || 320
		},
	},
}
</script>
