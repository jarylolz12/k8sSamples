<template>
  <v-dialog
		v-model="dialog"
		max-width="800"
		content-class="view-invoice-dialog"
		:retain-focus="false"
	>
		<v-card>
			<v-card-title>
				<span class="headline">Invoice #</span>
				<v-btn icon dark class="btn-close" type="button" @click="close">
					<v-icon>mdi-close</v-icon>
				</v-btn>
      </v-card-title>
      <v-card-text>
			</v-card-text>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name: 'ShipmentInvoiceView',
	props: {
		open: {
			type: Boolean,
		},
		invoice: {
			type: Object,
			default: null,
		}
	},

	computed: {
		dialog: {
			get() {
				return this.open;
			},
			set() { 
				this.$emit('close');
			}
		}
	},

	methods: {
		close() {
			this.$emit('close');
		}
	}
}
</script>

<style>

</style>