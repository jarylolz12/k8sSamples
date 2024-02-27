<template>
	<v-dialog v-model="dialog" max-width="560px" content-class="track-shipment-dialog" :retain-focus="false" @click:outside="close">
		<v-card class="track-shipment-card">
			<v-form ref="form" v-model="valid" action="#" @submit.prevent="">
				<v-card-title>
					<span class="headline">Track Shipment</span>

					<button icon dark class="btn-close" @click="close">
						<v-icon>mdi-close</v-icon>
					</button>
				</v-card-title>

				<v-card-text>
					<v-row>
						<v-col cols="12" sm="12" md="12">
							<div class="track-shipment-information">
								<p>You can add shipment for tracking through Shifl. Simply share the MBL number to add shipment in the list.</p>
							</div>
						</v-col>
					</v-row>

					<div class="track-shipment-content">
						<v-row>
							<v-col cols="12" sm="12">
								<div class="contents mbl-number">
									<p class="content-title">MBL #</p>
									<v-text-field 
										v-model="editedItem.mbl_num"
										placeholder="Enter MBL number" 
										outlined 
										class="text-fields"
										:rules="rules"
										hide-details="auto">
									</v-text-field>
								</div>
							</v-col>

							<v-col cols="12" sm="12">
								<div class="contents po-number">
									<p class="content-title">PO # <span>(Optional)</span></p>
									<vue-tags-input
										hide-details="auto"
										:tags="options"
										:add-on-blur="true"
										:add-on-key="[13, ',']"
										v-model="po_numbers"
										placeholder="Enter Purchase Orders"
										@tags-changed="newTags => { 
											this.options = newTags 
											this.tagsInput.touched = true
											this.tagsInput.hasError = (this.options.length > 0) ? false : true
										}"
									/>
								</div>
							</v-col>
						</v-row>
					</div>
				</v-card-text>

				<v-card-actions>
					<v-btn class="btn-blue" text @click="trackShipment" 
						:disabled="loading || loadingAndAddAnother">
						<span>{{ loading ? 'Tracking Shipment...' : 'Track Shipment'}}</span>
					</v-btn>
					<v-btn class="btn-white" text @click="saveAndAddAnother" 
						:disabled="loading || loadingAndAddAnother">                        
						<span>{{ loadingAndAddAnother ? 'Saving...' : 'Save & Add Another'}}</span>
					</v-btn>
					<v-btn class="btn-white" text @click="close" v-if="!isMobile">
						Cancel
					</v-btn>
				</v-card-actions>
			</v-form>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions } from "vuex"
import VueTagsInput from '@johmun/vue-tags-input';
import jQuery from 'jquery'
import globalMethods from '../../../utils/globalMethods'
import iziToast from "izitoast";

export default {
	name: 'TrackShipment',
	props: ['dialogData', 'editedItemData', 'isMobile'],
	components: {
		VueTagsInput
	},
	data: () => ({
		valid: true,
		rules: [
			(v) => !!v || "Input is required.",
			(v) => (v || '' ).length >= 4 || 'Please input at least 4 characters.',
			(v) => /^[a-zA-Z]{4}/.test(v) || 'First 4 characters should be letters.'
		],
		options: [],
		po_numbers: "",
		tagsInput: {
			touched: false,
			hasError: false,
			errorMessage: 'Please confirm the entered email address by pressing the "Enter" or "," key in your keyboard.'
		},
		loading: false,
		loadingAndAddAnother: false
	}),
	computed: {
		dialog: {
			get() {
				return this.dialogData
			},
			set(value) {
				this.$emit('update:dialogData', value)
			}
		},
		editedItem: {
			get() {
				return this.editedItemData
			},
			set(value) {
				this.$emit('update:editedItemData', value)
			}
		},
		addTrackingShipmentTemplate() {
			let { mbl_num, po_num } = this.editedItem

			return {
				mbl_num, 
				po_num
			}
		},
	},
	mounted() {},
	methods: {
		...mapActions([
			"addTrackShipment",
			"fetchShipments",
			"setShipments"
		]),
		...globalMethods,
		generateErrorMessage() {
			this.tagsInput.hasError = (this.options.length > 0) ? false : true
		},
		successMessage(name) {
			iziToast.success({
				theme: 'dark',
				message: `<h4 style="font-weight: 500 !important; color: #fff !important;">Shipment with MBL#${name} has been added for tracking.</h4>`,
				backgroundColor: '#16B442',
				messageColor: '#ffffff',
				iconColor: '#ffffff',
				progressBarColor: '#ADEEBD',
				displayMode: 1,
				position: 'bottomCenter',
				timeout: 3000,
			});
		},
		async trackShipment() {
			if (!this.tagsInput.touched)
				this.tagsInput.touched = true

			this.$refs.form.validate()
			this.tagsInput.hasError = (this.options.length > 0) ? false : true
	
			this.generateErrorMessage()

			setTimeout(async () => {

				if (this.$refs.form.validate()) {                    
					try {
						this.loading = true
						if (!this.tagsInput.hasError) {
							jQuery('.ti-new-tag-input').trigger(
								jQuery.Event('keyup', { keyCode: 13, which: 13 })
							)
							let finalPoNumbers = (this.options.length > 0) ? this.options.map(o => { return o.text }) : []
							let trackingTemplate = this.addTrackingShipmentTemplate
							trackingTemplate.po_num = JSON.stringify(finalPoNumbers)
							this.processAddTrackShipment(trackingTemplate, false)
						} else {
							let trackingTemplate = this.addTrackingShipmentTemplate
							this.processAddTrackShipment(trackingTemplate, false)
						} 
					} catch(e) {
						this.loading = false
						this.notificationError(e)
					}   
				}
			}, 300)
		},
		async processAddTrackShipment(trackingTemplate, set_default) {
			try {
				await this.addTrackShipment(trackingTemplate)
				this.successMessage(trackingTemplate.mbl_num)
				this.loading = false
				this.loadingAndAddAnother = false
				this.close()
				if ( set_default ) {
					this.setToDefault()
				}
				let payloadShipments = {
					page: 1,
					order: 'desc'
				}
				this.fetchShipments(payloadShipments)
			} catch(e) {
				this.loading = false
				this.loadingAndAddAnother = false
				
				if (typeof e.is_scac_validation_failed!=='undefined') {
					this.notificationError('SCAC validation failed.')
				} else {
					this.notificationError('An error occured while processing your request.')
				}
			}
		},
		async saveAndAddAnother() {
			if (!this.tagsInput.touched)
				this.tagsInput.touched = true

			this.$refs.form.validate()
			this.tagsInput.hasError = (this.options.length > 0) ? false : true
			this.generateErrorMessage()
			setTimeout(async () => {
				if (this.$refs.form.validate()) {
					try {
						this.loadingAndAddAnother = true
						if (!this.tagsInput.hasError) {
							jQuery('.ti-new-tag-input').trigger(
								jQuery.Event('keyup', { keyCode: 13, which: 13 })
							)

							let finalPoNumbers = (this.options.length > 0) ? this.options.map(o => { return o.text }) : []

							let trackingTemplate = this.addTrackingShipmentTemplate
							trackingTemplate.po_num = JSON.stringify(finalPoNumbers)

							this.processAddTrackShipment(trackingTemplate, true)
						} else {
							let trackingTemplate = this.addTrackingShipmentTemplate
							this.processAddTrackShipment(trackingTemplate, true)
						}
					} catch(e) {
						this.loadingAndAddAnother = false
						this.notificationError(e)
					}   
				}
			}, 300)            
		},
		close() {
			this.options = []
			this.$refs.form.resetValidation()
			this.$emit('close')
		},
		setToDefault() {
			this.close()
			this.$emit('update:dialogData', true)
		}
	},
	updated() {}
}
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/shipment/trackShipment.scss';
</style>
