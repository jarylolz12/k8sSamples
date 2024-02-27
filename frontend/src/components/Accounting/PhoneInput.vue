<template>
	<v-text-field v-model="phoneValue" solo flat required dense outlined hide-details="auto" placeholder="Enter phone number"></v-text-field>
</template>

<script>
	export default {
		name: 'PhoneInput',
		components: {},
		props: ['value'],
		computed: {
			phoneValue: {
				get() {
					return this.value;
				},
				set(val) {
					this.$emit('input', val);
				}
			}
		}
	};
</script>

<style></style>
