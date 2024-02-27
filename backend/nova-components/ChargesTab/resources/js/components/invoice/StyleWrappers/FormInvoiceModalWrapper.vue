<template>
	<div :id="id">
		<slot name="content" v-bind:dnamic="dynamic"></slot>
	</div>
</template>
<script>
export default {
	name: "FormInvoiceModalWrapper",
	props: ['id', 'initServiceSectionValue','dynamic']
}
</script>
<style lang="scss">
@import './scss/form-invoice-modal-wrapper.scss';
</style>