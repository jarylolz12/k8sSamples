<template>
	<div :id="id">
		<v-dialog v-if="!isMobile" @click:outside="clickOutside" v-model="show" max-width="800px" :content-class="className">
			<v-card class="add-manual-po-modal">
				<v-card-title style="padding: 10px 16px !important;" class="headline">
					<slot name="title"></slot>
                    <button icon dark class="btn-close" @click.stop="close">
                        <v-icon>mdi-close</v-icon>
                    </button>
				</v-card-title>
				<v-card-text style="padding-left: 16px !important; padding-right: 16px !important;" class="pb-0 d-flex flex-column">
					<div class="d-flex flex-row add-manual-po-text-wrapper">
                        <div>
                            <div class="form-label">
                                <span>{{ "PO NUMBER" }}</span>
                            </div>
                            <div class="text-field-wrapper">
                                <v-text-field
                                height="40px"
                                color="#002F44"
                                width="200px"
                                type="number"
                                dense
                                v-model="poManual.po_number"
                                placeholder=""
                                outlined
                                hide-details="auto">
                                </v-text-field>
                            </div>
                            <div class="form-label">
                                <span>{{ "CARGO READY DATE" }}</span>
                            </div>
                            <div class="text-field-wrapper dates-wrapper">
                                <v-menu
                                ref="menuCargoReadyDate"
                                v-model="menuCargoReadyDate"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                    class="text-fields cargo-ready-date-field date-fields" 
                                    placeholder="MM-DD-YYYY"
                                    outlined
                                    v-bind="attrs"
                                    v-on="on"
                                    type="text"
                                    clear-icon
                                    :height="40"
                                    hide-details="auto"
                                    @input="updateCargoReadyDateInput"
                                    v-model="poManual.cargoReadyDate"
                                    append-icon="mdi-calendar"
                                    />
                                </template>
                                <v-date-picker @input="updateCargoReadyDate" v-model="poManual.cargoReadyDate"></v-date-picker>
                                </v-menu>
                            </div>
                            <div class="form-label">
                                <span>{{ "TOTAL VOLUME" }}</span>
                            </div>
                            <div class="text-field-wrapper">
                                <v-text-field
                                height="40px"
                                color="#002F44"
                                width="200px"
                                type="number"
                                dense
                                v-model="poManual.volume"
                                placeholder=""
                                outlined
                                hide-details="auto">
                                </v-text-field>
                            </div>
                        </div>
                        <div style="padding-left: 16px;">
                            <div class="form-label">
                                <span>{{ "SUPPLIER" }}</span>
                            </div>
                            <div id="supplier-dropdown-wrapper-1" class="text-field-wrapper dropdown-wrapper">
                                <v-select
                                    attach="#supplier-dropdown-wrapper-1"
                                    class="text-fields select-items carton-uom"
                                    :items="poManual.suppliersOptions"
                                    height='40px'
                                    outlined
                                    item-text="name"
                                    item-value="id"
                                    placeholder="Select supplier"
                                    v-model="poManual.supplier_id"
                                    hide-details="auto"
                                    :menu-props="{ bottom: true, offsetY: true }">
                                </v-select>
                            </div>
                            <div class="form-label">
                                <span>{{ "TOTAL CARTON" }}</span>
                            </div>
                            <div class="text-field-wrapper">
                                <v-text-field
                                height="40px"
                                color="#002F44"
                                width="200px"
                                type="number"
                                dense
                                v-model="poManual.cartons"
                                placeholder=""
                                outlined
                                hide-details="auto">
                                </v-text-field>
                            </div>
                            <div class="form-label">
                                <span>{{ "TOTAL WEIGHT" }}</span>
                            </div>
                            <div class="text-field-wrapper">
                                <v-text-field
                                height="40px"
                                color="#002F44"
                                width="200px"
                                type="number"
                                dense
                                v-model="poManual.weights"
                                placeholder=""
                                outlined
                                hide-details="auto">
                                </v-text-field>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column po-document-wrapper">
                        <div class="po-document-title-wrapper">
                            <span class="po-document-title">{{ "Please upload Commercial Invoice & Packing List" }}</span>
                        </div>
                        <div class="dashed">
                            <div class="d-flex flex-column">
                                <p class="browse-drop-text">
                                    {{
                                        "Browse Or Drop files here"
                                    }}
                                </p>
                                <div style="justify-content: center;" class="d-flex">
                                <v-btn style="width: 73px !important;" class="btn-white btn-blue btn-upload">
                                    <generic-icon iconName="upload"></generic-icon>
                                    <span style="color: #0171A1;">{{ "Upload" }}</span>
                                </v-btn>
                                </div>
                            </div>
                        </div>
                    </div>
				</v-card-text>
				<v-card-actions>
					<slot name="actions" v-bind:footer="{close: close,addPoLoading: addPoLoading, addPo: addPo }"></slot>
                </v-card-actions>
			</v-card>
		</v-dialog>
	</div>
</template>
<style lang="scss">
@import "./scss/addManualPoModal.scss";
</style>
<script>
import moment from 'moment'
import GenericIcon from '../../../Icons/GenericIcon'

export default {
	name: 'AddManualPoModal',
	props: ['isMobile', 'show', 'className', 'id', 'item'],
    components: {
        GenericIcon
    },
	data: () => ({
        addPoLoading: false,
        menuCargoReadyDate: false,
        poManual: {}
	}),
    mounted() {
        this.poManual = this.item.poManual
    },
	methods:{
		clickOutside() {
			this.$emit('close')
		},
        updateCargoReadyDateInput(value) {
            if ( moment(value).isValid() )
                this.poManual.cargoReadyDate = moment(value).format('YYYY-MM-DD')
            else
                this.poManual.cargoReadyDate = ''
            this.menuCargoReadyDate = false
        },
        updateCargoReadyDate(value) {
            this.poManual.cargoReadyDate = moment(value).format('MM-DD-YYYY')
            this.menuCargoReadyDate = false
        },
        addPo() {
            this.addPoLoading = true
            setTimeout(() => {
                this.addPoLoading = false
            },5000)
        },
		close() {
			this.$emit('close')
		}
	}
}
</script>