<template>
	<div :id="id">
		<slot name="content"></slot>
	</div>
</template>
<script>
export default {
	name: "CustomerSearchBoxWrapper",
	mounted() {

	},
	props: ['id']
}
</script>
<style lang="scss">
@import './scss/customer-search-box-wrapper.scss';
</style>