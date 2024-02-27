<template>
	<v-menu
		ref="dateMenu"
		v-model="dateMenu"
		transition="scale-transition"
		max-width="290px"
		min-width="auto"
		:close-on-content-click="false"
	>
		<template v-slot:activator="{ on, attrs }">
			<v-text-field
				:value="dateFormat(dateValue)"
				:label="label || 'Date'"
				persistent-hint
				append-icon="mdi-calendar"
				v-bind="attrs"
				v-on="on"
				:id="id || 'date-input'"
				solo
				dense
				class="app-theme-input-border"
				flat
				outlined
				:rules="rules"
				hide-details="auto"
			></v-text-field>
		</template>
		<v-date-picker v-model="dateValue" no-title @input="dateMenu = false" :min="min"></v-date-picker>
	</v-menu>
</template>

<script>
	import moment from 'moment';

	export default {
		name: 'DatePicker',
		props: {
			value: {
				type: String,
				default: null
			},
			min: {
				type: String,
				default: null
			},
			id: {
				type: String,
				default: null
			},
			label: {
				type: String,
				default: null,
				required: false
			},
			rules: {
				type: [String, Array, Function],
				default: ''
			}
		},
		data() {
			return {
				dateMenu: false
			};
		},

		computed: {
			dateValue: {
				get() {
					return this.value;
				},
				set(val) {
					this.$emit('input', val);
				}
			}
		},

		methods: {
			dateFormat(dateStr) {
				return dateStr ? moment(dateStr, 'YYYY-MM-DD').format('DD MMM YYYY') : '';
			}
		}
	};
</script>

<style></style>
