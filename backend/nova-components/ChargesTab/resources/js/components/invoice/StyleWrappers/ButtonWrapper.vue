<template>
	<div :id="id">
		<slot name="content"></slot>
	</div>
</template>
<script>
export default {
	name: "ButtonWrapper",
	mounted() {

	},
	props: ['id']
}
</script>
<style lang="scss">
@import './scss/button-wrapper.scss';
</style>