<template>
	<div class="search-component-wrapper">
		<v-text-field
			color="#002F44"
			dense
			hide-details
			class="search"
			:class="{ 'custom-search': !isMobile }"
			id="search-local"
			:placeholder="placeholder"
			outlined
			prepend-inner-icon="mdi-magnify"
			v-model="cData"
			@input="updateInputData"
			autocomplete="off"
		>
		</v-text-field>
	</div>
</template>

<script>
	export default {
		name: 'SearchInput',
		props: ['inputData', 'placeholder'],
		data: () => ({
			cData: ''
		}),
		computed: {
			isMobile() {
				return this.$vuetify.breakpoint.mobile;
			}
		},
		methods: {
			updateInputData(val) {
				this.$emit('update:inputData', val);
			}
		}
	};
</script>

<style>
	.search-component-wrapper {
		height: 40px;
	}

	.search-component-wrapper .search .v-input__slot {
		height: 40px !important;
		width: 400px;
	}

	.search-component-wrapper .search .v-input__slot .v-input__prepend-inner {
		margin-top: 8px !important;
	}

	.search-component-wrapper .search.custom-search .v-input__slot {
		width: 280px !important;
		background-color: #fff;
	}

	.search-component-wrapper .search .v-input__slot input {
		font-size: 14px;
	}

	.search-component-wrapper .search .v-input__slot .v-input__icon.v-input__icon--prepend-inner i {
		color: #4a4a4a !important;
		font-size: 21px;
	}

	.search-component-wrapper .search fieldset {
		border: 1px solid #b3cfe0;
		font-size: 14px;
	}

	.search-component-wrapper .search fieldset:focus {
		border: 1px solid #b3cfe0 !important;
		outline: 0;
	}

	.search-component-wrapper .v-text-field__slot input::placeholder {
		color: #b4cfe0 !important;
	}

	@media screen and (max-width: 768px) {
		.search-component-wrapper {
			height: 40px;
			padding: 0;
			border-bottom: 2px solid #f1f6fa;
			width: 100%;
			background-color: #fff;
		}

		.search-component-wrapper .v-input__control,
		.search-component-wrapper .v-input__control .v-input__slot,
		.search-component-wrapper .v-input__control .v-input__slot fieldset,
		.search-component-wrapper .v-input__control .v-input__slot .v-text-field__slot input {
			width: 100%;
		}
	}
</style>
