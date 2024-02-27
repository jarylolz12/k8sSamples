<!-- @format -->

<template>
	<div class="search-component-wrapper">
		<v-text-field
			v-if="currentRouteName == 'POs'"
			height="40px"
			color="#002F44"
			width="400px"
			dense
			class="search"
			id="search-local"
			:class="className"
			:placeholder="placeholder"
			outlined
			prepend-inner-icon="mdi-magnify"
			v-model="getPoLocalQuery"
			@input="updateInputData"
			autocomplete="off"
		>
			<!-- clearable
            @click:clear="clearInputPo" -->
		</v-text-field>

		<v-text-field
			v-else-if="currentRouteName == 'SalesOrders'"
			height="40px"
			color="#002F44"
			width="400px"
			dense
			class="search"
			id="search-local"
			:class="className"
			:placeholder="placeholder"
			outlined
			prepend-inner-icon="mdi-magnify"
			v-model="getSoLocalQuery"
			@input="updateInputData"
			autocomplete="off"
		>
			<!-- clearable
            @click:clear="clearInputPo" -->
		</v-text-field>

		<v-text-field
			v-else-if="currentRouteName == 'Billing'"
			height="40px"
			color="#002F44"
			width="400px"
			dense
			class="search"
			id="search-local"
			:class="className"
			:placeholder="placeholder"
			outlined
			prepend-inner-icon="mdi-magnify"
			v-model="getBillingLocalQuery"
			@input="updateInputData"
		>
		</v-text-field>

		<v-text-field
			v-else-if="currentRouteName == 'Settings'"
			height="40px"
			color="#002F44"
			width="400px"
			dense
			class="search"
			id="search-local"
			:class="className"
			:placeholder="placeholder"
			outlined
			prepend-inner-icon="mdi-magnify"
			v-model="getUsersLocalQuery"
			@input="updateInputData"
		>
		</v-text-field>

		<v-text-field
			v-else
			height="40px"
			color="#002F44"
			width="400px"
			dense
			class="search"
			id="search-local"
			:class="className"
			:placeholder="placeholder"
			outlined
			prepend-inner-icon="mdi-magnify"
			v-model="cData"
			@input="updateInputData"
		>
		</v-text-field>
	</div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
	props: ["inputData", "placeholder", "className"],
	data: () => ({
		cData: "",
	}),
	computed: {
		...mapGetters({
			poLocalQuery: "po/getPoLocalQuery",
			soLocalQuery: "salesOrders/getSoLocalQuery",
			billingLocalQuery: "getBillingLocalQuery",
			usersLocalQuery: "getUsersLocalQuery",
		}),
		currentRouteName() {
			return this.$route.name;
		},
		getPoLocalQuery: {
			get() {
				return this.poLocalQuery;
			},
			set(value) {
				this.$emit("update:poLocalQuery", value);
			},
		},
		getSoLocalQuery: {
			get() {
				return this.soLocalQuery;
			},
			set(value) {
				this.$emit("update:soLocalQuery", value);
			},
		},
		getBillingLocalQuery: {
			get() {
				return this.billingLocalQuery;
			},
			set(value) {
				this.$emit("update:billingLocalQuery", value);
			},
		},
		getUsersLocalQuery: {
			get() {
				return this.usersLocalQuery;
			},
			set(value) {
				this.$emit("update:usersLocalQuery", value);
			},
		},
	},
	methods: {
		...mapActions({
			setPoLocalQuery: "po/setPoLocalQuery",
			setSoLocalQuery: "salesOrders/setSoLocalQuery",
			setBillingLocalQuery: "setBillingLocalQuery",
			setUsersLocalQuery: "setUsersLocalQuery",
		}),
		updateInputData(val) {
			if (this.currentRouteName == "POs") {
				this.setPoLocalQuery(val);
				this.$emit("update:inputData", val);
				this.$emit("searchLocal");
			}
			if (this.currentRouteName == "SalesOrders") {
				this.setSoLocalQuery(val);
				this.$emit("update:inputData", val);
				this.$emit("searchLocal");
			}
			if (this.currentRouteName == "Billing") {
				this.setBillingLocalQuery(val);
				this.$emit("update:inputData", val);
			}
			if (this.currentRouteName == "Settings") {
				this.setUsersLocalQuery(val);
				this.$emit("update:inputData", val);
			}
		},
	},
	mounted() {
		if (this.currentRouteName == "POs") {
			if (this.getPoLocalQuery == "" || this.getPoLocalQuery == null)
				this.setPoLocalQuery(this.cData);
		}
		if (this.currentRouteName == "SalesOrders") {
			if (this.getSoLocalQuery == "" || this.getSoLocalQuery == null)
				this.setSoLocalQuery(this.cData);
		}
		if (this.currentRouteName == "Billing") {
			if (this.getBillingLocalQuery == "" || this.getBillingLocalQuery == null)
				this.setBillingLocalQuery(this.cData);
		}
		if (this.currentRouteName == "Settings") {
			if (this.getUsersLocalQuery == "" || this.getUsersLocalQuery == null)
				this.setUsersLocalQuery(this.cData);
		}

		this.cData = this.inputData;
	},
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
	width: 240px !important;
	background-color: #fff;
}

.search-component-wrapper .search .v-input__slot input {
	font-size: 14px;
}

.search-component-wrapper
	.search
	.v-input__slot
	.v-input__icon.v-input__icon--prepend-inner
	i {
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
	.search-component-wrapper
		.v-input__control
		.v-input__slot
		.v-text-field__slot
		input {
		width: 100%;
	}
}
</style>
