<template>
<v-dialog @click:outside="clickOutside" v-model="show" max-width="600px">
	<v-card class="delete-dialog snooze-shipment-dialog">
		<v-card-title class="headline">
			<slot name="reference" v-bind:item="item"></slot>
		</v-card-title>
		<v-card-text class="body-text">
			<slot name="main-text"></slot>
			<div class="font-weight-bold">
				{{
					"Snooze Until"
				}}
			</div>
			<div>
			<v-menu
				ref="menu"
				v-model="menu"
				:close-on-content-click="false"
				transition="scale-transition"
				offset-y
				min-width="auto">
				<!-- :return-value.sync="date_save"  -->
					<template v-slot:activator="{ on, attrs }">
						<!-- <v-text-field
							v-model="date_save"
							height="40px"
							type="date"
							class="text-fields"
							placeholder="Select a date" 
							append-icon="mdi-calendar"
							v-bind="attrs"
							outlined
							v-on="on"
						>
						</v-text-field> -->
						<v-text-field
							class="text-fields" 
							placeholder="Select a date"
							outlined
							v-on="on"
							height="40px"
							v-bind="attrs"
							v-model="textFieldDate"
							@keydown="onDateKeyDown"
							append-icon="mdi-calendar"
						/>
							<!-- v-model="textFieldDate" -->
							<!-- v-model="date_save" -->
					</template>
					<v-date-picker
						v-model="date_save"
						no-title
						:min="minDate"
					>
						<!-- @input="menu = false" -->
						<!-- @input="$refs.menu.save(date_save)" -->
					</v-date-picker>
				</v-menu>
			</div>
		</v-card-text>
		<v-card-actions :class="`snooze-shipment-actions-wrapper ${(isMobile) ? 'snooze-shipment-action-wrapper-mobile' : ''}`">
			<slot name="actions" v-bind:actionItems="{item, handleSnoozeShipment, handleClose, date_save }"></slot>
		</v-card-actions>
	</v-card>
</v-dialog>
</template>
<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
@import '../../assets/scss/colors.scss';
@import './scss/snoozeShipmentDialog.scss';
</style>
<script>
import iziToast from 'izitoast'
import { mapActions } from 'vuex'
import globalMethods from '../../utils/globalMethods'
import moment from 'moment'

export default {
	name: 'SnoozeShipmentDialog',
	props: ['show', 'item', 'isMobile'],
	mounted() {},
	data() {
		return {
			date_save: null,
			menu: false,
			activePicker: null,
			date: null,
			textFieldDate: null,
		}
	},
	watch: {
		date_save(value) {
			if(value) {
				this.menu = false
				this.textFieldDate = value
			}
		},

		textFieldDate(value) {
			if( moment(value).isValid() && value.length === 10 && value >= this.minDate ) {
				this.date_save = value
			} else {
				this.date_save = null
			}
		},

		show(isTrue) {
			if(isTrue) {
				this.textFieldDate = null
			}
		}
	},
	methods: {
		...mapActions(['snoozeShipment']),
		...globalMethods,
		notificationError(message) {
			iziToast.error({
				title: "Error",
				message,
				displayMode: 1,
				position: 'bottomCenter',
				timeout: 3000
			})
		},
		async handleSnoozeShipment(item, date_save) {
			item.snoozing = true
			try {
				await this.snoozeShipment({shipment_id: item.id, snooze_date: date_save})
				item.snoozing = false
				this.$emit('reloadAllShipments')
				this.notificationMessage('Shipment was successfully snoozed.')
				this.textFieldDate = null
				this.handleClose()
			} catch(e) {
				this.notificationError(e)
				console.log(e)
			}
		},
		changeDate(date) {
			this.date_save = date
		},
		clickOutside() {
			this.$emit('close')
			this.textFieldDate = null
			this.date_save = this.minDate
		},
		handleClose() {
			this.$emit('close')
			this.textFieldDate = null
			this.date_save = this.minDate
		},
		onDateKeyDown(e) {
			let allow = false
			const kc = e.keyCode
			if(kc === 8 || kc === 189 || ((kc > 47 && kc < 58) || (kc > 95 && kc < 106) || (kc >= 37 && kc <= 40 )))  {
				allow = true
			}
			if(!allow) {
				e.preventDefault()
			}
		}
	},
	computed: {
		minDate() {
			return moment().format('YYYY-MM-DD')
		}
	}
}
</script>