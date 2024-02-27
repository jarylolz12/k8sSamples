<template>
	<div id="shipment-dropdown-booking-wrapper">
		<v-btn v-if="!isMobile" class="btn-white archive-shipment mr-4 shipment-header-button-btn d-none">
			<div>
				<custom-icon iconName="archive" />
			</div>
			<div class="pl-1">Archived</div>
		</v-btn>
		<v-menu
			offset-y
			bottom
			left
            close-on-content-click
            close-on-click
            @input="updateInput"
			attach="#shipment-dropdown-booking-wrapper"
			content-class="shipment-dropdown-button-menu"
		>
			<template v-slot:activator="{ on, attrs }">
				<button
					@click.stop="toggleMenu"
					v-bind="attrs"
					v-on="on"
					:class="`booking-shipment-btn ${getClass()}`"
				>
					<div>{{ "Add Shipment" }}</div>
					<div class="icon-wrapper" :class="getClass()">
						<generic-icon color="#0171A1" :iconName="`chevron-${getClass()}`">
						</generic-icon>
					</div>
				</button>
			</template>
			<v-list>
				<v-list-item v-bind:key="`bitem-${key}`" @click.stop="doAction(item)" v-for="(item,key) in items">
					<v-list-item-title class="d-flex">
						<div>
						{{ item.title }}
						</div>
						<div>
							<generic-icon color="#0171A1" iconName="chevron-right">
							</generic-icon>
						</div>
					</v-list-item-title>
					<v-list-item-content>
			            {{ item.content }}
			        </v-list-item-content>
				</v-list-item>
			</v-list>
		</v-menu>
	</div>
</template>
<style lang="scss">
@import "./scss/shipmentDropdown.scss";
</style>
<script>
import GenericIcon from '../Icons/GenericIcon'
import CustomIcon from '../../components/Icons/CustomIcon'
export default {
	name: 'ShipmentDropdown',
	components: {
		GenericIcon,
		CustomIcon
	},
	props: ['isMobile'],
	data: () => ({
		carretDirection: false,
        showMenu: false,
		items: [{
			title: 'Create Booking Request',
			content: 'Request for a booking and get a quote.'
		},
		{
			title: 'Add Shipment for Tracking',
			content: 'We will track the shipment for you and you can add information if needed.'
		}]
	}),
	methods: {
        updateInput() {
            //this.showMenu = i
            this.showMenu = false
        },
		getClass() {
			return this.showMenu ? 'up' : 'down'
		},
		toggleMenu() {
			this.carretDirection = !this.carretDirection
		},
        setMarkingBookedExternal(b) {
            this.$store.dispatch('page/setMarkingBookedExternal', b)
        },
        setEditingShipment(b) {
            this.$store.dispatch('page/setEditingShipment', b)
        },
        setAddingShipmentTracking(b) {
            this.$store.dispatch('page/setAddingShipmentTracking', b)
        },
        setCreatingBookingRequest(b) {
            this.$store.dispatch('page/setCreatingBookingRequest', b)
        },
		doAction(item) {
            this.$emit('update:shipmentDropDownClicked', true)
			if ( item.title === 'Add Shipment for Tracking' ){
                // this.showAddShipmentDialog()
				this.showSingleAddShipmentDialog()
            } else
				this.showBookingShipmentDialog()
		},
        showBookingShipmentDialog() {
            this.$emit('update:isEdit', false)
			this.$emit('showBookingShipmentDialog')

            this.setEditingShipment(false)
            this.setMarkingBookedExternal(false)
            this.setAddingShipmentTracking(false)
            this.setCreatingBookingRequest(true)

		},
		// showAddShipmentDialog() {
        //     this.$emit('update:isEdit', false)
		// 	this.$emit('showAddShipmentDialog')

        //     this.setEditingShipment(false)
        //     this.setMarkingBookedExternal(false)
        //     this.setAddingShipmentTracking(true)
        //     this.setCreatingBookingRequest(false)
		// },
		showSingleAddShipmentDialog() {
			this.$emit('update:isEdit', false)
			this.$emit('showSingleAddShipmentDialog')
			this.setEditingShipment(false)
			this.setMarkingBookedExternal(false)
			this.setAddingShipmentTracking(true)
			this.setCreatingBookingRequest(false)
		}
	}
}
</script>