<template>
	<div>
		<div v-if="item.show_tooltip" :class="`custom-tooltip custom-tooltip-${currentItemIndex} fade-in`">
			<div @click.stop="handleView(item)" class="custom-tooltip-view">View</div>
			<div v-if="shipment_type=='pendingQuote'" @click.stop="handleSnooze(item)" class="custom-tooltip-snooze">
				{{
					(item.snoozing) ? 'Snoozing...' : 'Snooze'
				}}
			</div>
			<div @click.stop="handleCancel(item)" class="custom-tooltip-cancel">Cancel</div>
		</div>
		<v-dialog v-if="shipment_type=='pendingQuote'" v-model="show_snooze_date" max-width="600px">
			<v-card class="delete-dialog">
				<v-card-title class="headline">
					<div class="delete-icon mt-3 mb-1">
						Shipment Reference #{{
							item.ReferenceID
						}}
					</div>
				</v-card-title>
				<v-card-text class="date-picker-container">
					<v-date-picker @change="changeDate" full-width class="date-picker" v-if="show_snooze_date" v-model="item.snooze_date_at"></v-date-picker>
				</v-card-text>
				<v-card-actions>
					<v-btn @click.stop="handleSnoozeShipment(item, show_snooze_date)" class="btn-blue" text >
						<span>{{ (item.snoozing) ? 'Snoozing Shipment...' : 'Snooze Shipment'}}</span>
					</v-btn>
					<v-btn @click.stop="handleClose" class="btn-white" text >Cancel</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<style scoped>
	@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
	@-webkit-keyframes fade-in {
		0% {
			opacity: 0;
		}
		100% {
			opacity: 1;
		}
	}
	@keyframes fade-in {
		0% {
			opacity: 0;
		}
		100% {
			opacity: 1;
		}
	}
	
	.date-picker-container {
		display: flex;
		justify-content: center;
		align-items: center;
		padding-top: 12px;
		padding-bottom: 12px;
	}

	.fade-in {
		-webkit-animation: fade-in 0.5s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
		animation: fade-in 0.5s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
	}

	.custom-tooltip {
		display: flex;
		flex-direction: column;
		position: absolute; 
		min-width: 150px; 
		background: #FFFFFF; 
		filter: drop-shadow(0 4px 3px rgb(0 0 0 / 0.07)) drop-shadow(0 2px 2px rgb(0 0 0 / 0.06));
		z-index: 1000000;
		border: 1px solid #EBF2F5;
		border-radius: 12px;
		text-align: left;
		color: black;
		top:  0px;
		left:  86.5%;

	}

	.custom-tooltip > div:hover {
		background: #eeeeee;
	}

	.custom-tooltip > div {
		cursor: pointer;
		font-family: 'Inter', sans-serif !important;
		font-size: 12px !important;
		color: #4A4A4A ;
		font-weight: 500 !important;
		padding-top: 8px;
		padding-left: 16px;
		padding-bottom: 8px;
		line-height: 18px;
	}
	.custom-tooltip > div:first-child:hover {
		border-top-left-radius: 12px;
		border-top-right-radius: 12px;
	}

	.custom-tooltip > .custom-tooltip-cancel {
		color: #F93131;
	}
</style>
<script>
	import iziToast from "izitoast";
	import jQuery from 'jquery'
	import { mapActions, mapGetters } from 'vuex'
	export default {
		props: {
			item: {
				type: Object
			},
			position: {
				type: Object
			},
			currentItemIndex: {
				type: Number,
			},
			snoozeShipment: {
				type: Function
			},
			shipment_type: {
				type: String,
				default: 'pendingQuote'
			},
			pagination: {
				type: Object
			}
		},
		data() {
			return {
				hasSet: false,
				show_snooze_date: false,
				date_save: null,
				currentScrollTop: 0,
				scrollTop: 0
			}
		},
		computed: {
			...mapGetters([
				'getAllPendingQuoteShipments',
				'getAllExpiredShipments',
				'getAllPendingShipments',
				'getAllSnoozeShipments'
				])
		},
		methods: {
			...mapActions([
				'fetchPendingQuoteShipments',
				'fetchPendingShipments',
				'fetchExpired',
				'fetchExpiredShipments',
				'fetchSnoozeShipments',
				'clearTooltips',
				'clearShipmentsData'
				]),
			changeDate(date) {
				this.date_save = date
			},
			notificationSuccess() {
				iziToast.success({
					theme: 'dark',
					message: `<h4 style="font-weight: 500 !important; color: #fff !important;">Successfully snooze the shipment.</h4>`,
					backgroundColor: '#16B442',
					messageColor: '#ffffff',
					iconColor: '#ffffff',
					progressBarColor: '#ADEEBD',
					displayMode: 1,
					position: 'bottomCenter',
					timeout: 3000,
				});
			},
			notificationError(message) {
				iziToast.error({
					title: "Error",
					message,
					displayMode: 1,
					position: 'bottomCenter',
					timeout: 3000
				});
			},
			handleClose() {
				this.show_snooze_date = false
			},
			reloadAllShipments() {

				this.clearShipmentsData({
					pending_quote: this.getAllPendingQuoteShipments,
					pending: this.getAllPendingShipments,
					expired: this.getAllExpiredShipments,
					snooze: this.getAllExpiredShipments
				})
				
				this.fetchPendingQuoteShipments(1)
				this.fetchPendingShipments(1)
				this.fetchExpiredShipments(1)
				this.fetchSnoozeShipments(1)
			},
			async handleSnoozeShipment(item) {

				item.snoozing = true
				try {
					await this.snoozeShipment({shipment_id: item.id, snooze_date: this.date_save})
					//let page = this.pagination.pendingQuoteTab.current_page
					this.$emit('handleCancel', item)
					this.reloadAllShipments()
					//this.$emit('handlePageChangePendingQuote',page)
					this.notificationSuccess()
					this.show_snooze_date = false
				} catch(e) {
					this.notificationError(e)
					console.log(e)
				}
			},
			handleSnooze(item) {
				this.show_snooze_date = true
				this.$emit('handleCancel', item)
				//this.$emit('handleSnooze', item)
			},
			handleView(item) {
				let {
					shipment_type
				} = this
				let pendingQuoteShipments = this.getAllPendingQuoteShipments
				let expiredShipments = this.getAllExpiredShipments
				let snoozeShipments = this.getAllSnoozeShipments
				let pendingShipments = this.getAllPendingShipments

				if (shipment_type=='pendingQuote') {
					pendingQuoteShipments.shipment_type = shipment_type
					this.clearTooltips(pendingQuoteShipments)	
				} else if(shipment_type=='expired') {
					expiredShipments.shipment_type = shipment_type
					this.clearTooltips(expiredShipments)
				} else if (shipment_type=='snooze') {
					snoozeShipments.shipment_type = 'snooze'
					this.clearTooltips(snoozeShipments)
				} else if (shipment_type=='pending') {
					pendingShipments.shipment_type = 'pending'
					this.clearTooltips(snoozeShipments)
				}
				this.$emit('handleView', item)
			},
			handleCancel(item) {
				this.$emit('handleCancel', item)
			}
		},
		updated() {
			if (jQuery(`.three-dots-wrapper-${this.currentItemIndex}`).length > 0) {
				let offset = jQuery(`.three-dots-wrapper-${this.currentItemIndex}`).offset()

				//let minusOffset = (this.scrollTop==0) ? 0 : (2 * this.currentItemIndex) - (this.scrollTop * 0.88)

				//console.log(minusOffset)
				jQuery(`.custom-tooltip-${this.currentItemIndex}`).css('top', (((offset.top * 0.34) + ((this.currentItemIndex * 52.9)*0.76)) + this.currentItemIndex) - (2.5 * this.currentItemIndex) - (this.scrollTop * 0.66))
				//jQuery(`.custom-tooltip-${this.currentItemIndex}`).css('top', (((offset * 0.33) + ((this.currentItemIndex * 53)*0.75)) + this.currentItemIndex) - this.scrollTop)
			}
		},
		created() {
			this.date_save = this.item.snooze_date_at
		},
		mounted() {

			const tableWrapper = document.getElementsByClassName('v-data-table__wrapper')[0]
			tableWrapper.addEventListener('scroll', (e) => {
				this.scrollTop = e.target.scrollTop
				this.$emit('handleCancel', this.item)
			})

			if (jQuery(`.three-dots-wrapper-${this.currentItemIndex}`).length > 0) {
				let offset = jQuery(`.three-dots-wrapper-${this.currentItemIndex}`).offset()
				//let minusOffset = (this.scrollTop==0) ? 0 : (2 * this.currentItemIndex) - (this.scrollTop * 0.88)
				jQuery(`.custom-tooltip-${this.currentItemIndex}`).css('top', (((offset.top * 0.34) + ((this.currentItemIndex * 52.9)*0.76)) + this.currentItemIndex) - (2.5 * this.currentItemIndex) - (this.scrollTop * 0.66))
				//jQuery(`.custom-tooltip-${this.currentItemIndex}`).css('top', (((offset * 0.33) + ((this.currentItemIndex * 53)*0.75)) + this.currentItemIndex) - this.scrollTop)
			}
		}
	}
</script>