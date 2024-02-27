<template>
    <v-dialog v-model="dialogAdd" max-width="800px" content-class="warehouse-dialog" scrollable :retain-focus="false" persistent>
        <v-card class="warehouse-dialog-card">
            <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                <v-card-title>
                    <span class="headline">
                        {{ (editedIndex > -1 && isWarehouseConnected) ? 'Edit Warehouse Information' : formTitle }}
                    </span>

                    <button icon dark class="btn-close" @click="close">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </v-card-title>

                <v-card-text>
                    <div class="add-wrapper">
                        <div class="warehouse-info">
                            <div class="warehouse-number mb-3">
                                <p class="warehouse-title">WAREHOUSE TYPE</p>

                                <v-radio-group mandatory v-model="warehouse.warehouse_type" hide-details="auto" v-if="editedIndex === -1">
                                    <v-radio label="Own Facility" value="Private Warehouse"> </v-radio>
                                    <v-radio label="3rd Party (3PL)" value="Third-Party Logistics (3pl)"> </v-radio>
                                    <v-radio label="3PL Service Provider" value="3PL Provider"> </v-radio>
                                </v-radio-group>

                                <p v-if="editedIndex > -1">
                                    {{ !isWarehouseConnected ? warehouse.warehouse_type : 'Connected 3PL' }}
                                </p>
                            </div>

                            <div class="warehouse-name mb-3">
                                <p class="warehouse-title">NAME</p>
                                <v-text-field
                                    color="#002F44"
                                    width="200px"
                                    dense
                                    class="text-fields select-items name"
                                    placeholder="Enter Name"
                                    outlined
                                    v-model="warehouse.name"
                                    :rules="warehouseNameRules"
                                    hide-details="auto"
                                    autocomplete="off">
                                </v-text-field>
                            </div>

                            <div class="warehouse-contact-number mb-4">
                                <p class="warehouse-title">WAREHOUSE CONTACT</p>
                                <!-- <vue-tel-input-vuetify
                                    class="text-fields select-items name"
                                    outlined
                                    dense
                                    single-line
                                    hide-details="auto"
                                    :valid-characters-only="true"
                                    :rules="numberRules"
                                    v-bind="telProps"
                                    v-model="warehouse.phone" /> -->

                                <vue-tel-input 
                                    v-model="warehouse.phone"
                                    mode="international"
                                    defaultCountry="us"
                                    validCharactersOnly
                                    :autoDefaultCountry="true"
                                    :dropdownOptions="vueTelDropdownOptions"
                                    :inputOptions="vueTelInputOptions"
                                    :styleClasses="isPhoneNumberEmpty && warehouse.phone === '' ? 'is-error' : ''"
                                    :disabled="(editedIndex > -1 && this.isWarehouseConnected)">

                                    <template v-slot:arrow-icon>
                                        <v-icon class="ml-1">mdi-chevron-down</v-icon>
                                    </template>
                                </vue-tel-input>

                                <span class="error-message" style="color: #ff5252; font-size: 12px;" v-if="isPhoneNumberEmpty">
                                    {{ warehouse.phone === '' ? 'Input is required.' : ''}}
                                </span>
                            </div>

                            <div class="warehouse-email mb-3">
                                <p class="warehouse-title">EMAIL</p>
                                <div class="mb-1">
                                    <vue-tags-input
                                        hide-details="auto"
                                        allow-edit-tags
                                        :rules="arrayNotEmptyRules"
                                        :tags="options"
                                        :add-on-blur=true
                                        :add-on-key="[13, ',']"
                                        :validation="tagsValidation"
                                        v-model="warehouseEmailAddress"
                                        placeholder="e.g. name@email.com"
                                        @tags-changed="newTags => {
                                            this.options = newTags
                                            this.tagsInput.touched = true
                                            this.tagsInput.hasError = (this.options.length > 0) ? false : true
                                            let el = this.documentProto.getElementsByClassName('ti-input')[0]
                                            if (typeof el!=='udnefined') {
                                                if (this.tagsInput.hasError)
                                                    el.classList.add('ti-new-tag-input-error')
                                                else
                                                    el.classList.remove('ti-new-tag-input-error')   
                                                
                                            }
                                        }"
                                    />
                                </div>
                                
                                <span style="color: #819FB2; font-size: 12px;">
                                    Separate multiple email addresses with comma
                                </span>

                                <div v-if="tagsInput.touched" class="v-text-field__details">
                                    <div class="v-messages theme--light error--text" role="alert">
                                        <div class="v-messages__wrapper">
                                            <div v-if="(options.length > 0) && warehouseEmailAddress!==''" class="v-messages__message">
                                                {{
                                                    tagsInput.errorMessage
                                                }}
                                            </div>

                                            <div v-if="options.length == 0 && warehouseEmailAddress!==''" class="v-messages__message">
                                                {{
                                                    tagsInput.errorMessage
                                                }}
                                            </div>
                                            <div v-if="options.length == 0 && warehouseEmailAddress==''" class="v-messages__message">
                                                Please provide at least 1 valid email address.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="warehouse-description">
                            <!-- <div class="warehouse-email mb-3">
                                <p class="warehouse-title">EMAIL</p>
                                <div class="mb-1">
                                    <vue-tags-input
                                      hide-details="auto"
                                      :rules="arrayNotEmptyRules"
                                      :tags="options"
                                      :add-on-blur=true
                                      :add-on-key="[13, ',']"
                                      :validation="tagsValidation"
                                      v-model="warehouseEmailAddress"
                                      placeholder="e.g. name@email.com"
                                      @tags-changed="newTags => {
                                        this.options = newTags
                                        this.tagsInput.touched = true
                                        this.tagsInput.hasError = (this.options.length > 0) ? false : true
                                        let el = this.documentProto.getElementsByClassName('ti-input')[0]
                                        if (typeof el!=='udnefined') {
                                            if (this.tagsInput.hasError)
                                                el.classList.add('ti-new-tag-input-error')
                                            else
                                                el.classList.remove('ti-new-tag-input-error')   
                                            
                                        }   

                                      }"
                                    />
                                </div>
                                <div v-if="tagsInput.touched" class="v-text-field__details">
                                    <div class="v-messages theme--light error--text" role="alert">
                                        <div class="v-messages__wrapper">
                                            <div v-if="(options.length > 0) && warehouseEmailAddress!==''" class="v-messages__message">
                                                {{
                                                    tagsInput.errorMessage
                                                }}
                                            </div>

                                            <div v-if="options.length == 0 && warehouseEmailAddress!==''" class="v-messages__message">
                                                {{
                                                    tagsInput.errorMessage
                                                }}
                                            </div>
                                            <div v-if="options.length == 0 && warehouseEmailAddress==''" class="v-messages__message">
                                                Please provide at least 1 valid email address.
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                <span style="color: #819FB2; font-size: 12px;">
                                    Press the "Enter" or "," key in your keyboard to confirm the email address
                                </span>
                            </div> -->

                            <div class="warehouse-notes mb-5">
                                <p class="warehouse-title">ADDRESS</p>
                                <v-textarea
                                    height="100"
                                    class="text-fields address"
                                    outlined
                                    name="input-7-4"
                                    placeholder="Type full address..."
                                    v-model="warehouse.address"
                                    :rules="rules"
                                    hide-details="auto"
                                    autocomplete="off"
                                    :disabled="(editedIndex > -1 && this.isWarehouseConnected)">
                                </v-textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-12 mb-1">
                                    <div class="row">
                                        <div class="warehouse-each col-sm-6 py-1">
                                            <p class="warehouse-title">COUNTRY</p>
                                            <v-autocomplete
                                                class="text-fields select-items"
                                                v-model="warehouse.country"
                                                :items="countries"
                                                :disabled="getCountriesLoading || (editedIndex > -1 && this.isWarehouseConnected)"
                                                :placeholder="getCountriesLoading ? 'Fetching countries...' : 'Type country name'"
                                                @input="setSelectedCountry"
                                                outlined
                                                :rules="rules"
                                                hide-details="auto"
                                                autocomplete="off">
                                            </v-autocomplete>
                                        </div>

                                        <div class="warehouse-each col-sm-6 py-1">
                                            <p class="warehouse-title">STATE</p>
                                            <v-combobox
                                                class="text-fields select-items"
                                                outlined
                                                :items="states"
                                                item-text="name"
                                                item-value="id"
                                                :placeholder="getStatesLoading ? 'Fetching states...' : 'Select state'"
                                                :disabled="getStatesLoading || (editedIndex > -1 && this.isWarehouseConnected)"
                                                v-model="warehouse.state"
                                                :rules="rules"
                                                hide-details="auto"
                                                autocomplete="off">
                                                <!-- @change="setSelectedState" -->
                                                <!-- @click="isStateClicked()" -->
                                            </v-combobox>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="row">
                                        <!-- <div class="warehouse-each col-sm-6 py-1">
                                            <p class="warehouse-title">CITY</p>
                                            <v-combobox
                                                class="text-fields select-items"
                                                v-model="warehouse.city"
                                                :items="cities"
                                                item-text="name"
                                                item-value="id"
                                                :disabled="getCitiesLoading"
                                                :placeholder="getCitiesLoading ? 'Fetching cities...' : 'Select city'"
                                                @click="isCitiesClicked()"
                                                outlined
                                                :rules="rules"
                                                hide-details="auto">

                                                <template v-slot:no-data>
                                                    <div tabindex="-1" class="v-list-item theme--light">
                                                        <div class="v-list-item__content">
                                                            <div class="v-list-item__title">No data available</div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </v-combobox>
                                        </div> -->

                                        <div class="warehouse-each col-sm-6 py-1">
                                            <p class="warehouse-title">ZIPCODE</p>
                                            <v-text-field
                                                color="#002F44"
                                                width="200px"
                                                dense
                                                class="text-fields"
                                                placeholder="Type zipcode"
                                                outlined
                                                type="number"
                                                v-model="warehouse.zipcode"
                                                :rules="rules"
                                                hide-details="auto"
                                                autocomplete="off"
                                                :disabled="(editedIndex > -1 && this.isWarehouseConnected)">
                                            </v-text-field>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="warehouse-notes" v-if="editedIndex > -1 && warehouse.is_own === 0">
                                <p class="warehouse-title">DESCRIPTION (OPTIONAL)</p>
                                <v-textarea
                                    height="100"
                                    class="text-fields address"
                                    outlined
                                    name="input-7-4"
                                    placeholder="Enter description"
                                    v-model="warehouse.description"
                                    hide-details="auto"
                                    autocomplete="off">
                                </v-textarea>
                            </div>
                        </div>
                    </div>
                </v-card-text>

                <v-card-actions>
                    <button class="btn-blue" 
                        @click.stop="saveWarehouse" 
                        :disabled="getWarehouseCreateLoading || getWarehouseSaveAddLoading || getWarehouseUpdate3PLDataLoading">
                        <span v-if="editedIndex === -1">
                            <span>
                                {{ 
                                    ( getWarehouseCreateLoading ) ? 'Adding...' : 'Add Warehouse'
                                }}
                            </span>
                        </span>

                        <span v-if="editedIndex > -1">
                            <span>
                                {{ 
                                    ( getWarehouseCreateLoading || getWarehouseUpdate3PLDataLoading) ? ' Saving...' : 'Save'
                                }}
                            </span>
                        </span>
                    </button>

                    <button class="btn-white" v-if="editedIndex === -1"
                        @click.stop="saveAndAddWarehouse" 
                        :disabled="getWarehouseCreateLoading || getWarehouseSaveAddLoading">
                        <span>
                            {{
                                ( getWarehouseSaveAddLoading ) ? 'Saving...' : 'Save & Add Another'
                            }}
                        </span>
                    </button>

                    <button class="btn-white" 
                        @click="close" 
                        v-if="!isMobile" 
                        :disabled="getWarehouseCreateLoading || getWarehouseSaveAddLoading">
                        Cancel
                    </button>
                
                    <v-spacer v-if="!isMobile"></v-spacer>
                    
                    <!-- <button class="btn-white" @click="deleteWarehouse(warehouse)" v-if="editedIndex > -1">
                        Delete
                    </button> -->
                </v-card-actions>
            </v-form>
        </v-card>

        <ConfirmDialog :dialogData.sync="dialogCreateWarehouseSuccess">
            <template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/check-icon-header.svg" alt="check">

                    <button class="btn-black" @click="closeWarehouseAlert">
                        <img src="@/assets/images/close.svg" alt="close">
                    </button>
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2> New Warehouse is Ready! </h2>
			</template>

			<template v-slot:dialog_content>
				<p> 
					Congratulations! You have added a new warehouse. 
                    Next step is to add inventory. 
                    You can start by adding your current inventory level or create inbound orders. 
				</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" @click="addCurrentInventory">
                    Add Current Inventory
				</v-btn>

				<v-btn class="btn-white" text @click="createInbound">
					Create Inbound
				</v-btn>
			</template>
		</ConfirmDialog>  
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import jQuery from 'jquery'
//import vSelect from 'vue-select'
import "vue-select/src/scss/vue-select.scss";
import globalMethods from '../../../utils/globalMethods'
// import VueTelInputVuetify from "vue-tel-input-vuetify/lib/vue-tel-input-vuetify.vue"
import { VueTelInput } from 'vue-tel-input'
import 'vue-tel-input/dist/vue-tel-input.css'
import VueTagsInput from '@johmun/vue-tags-input';
import _ from 'lodash'
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'

export default {
    name: 'WarehouseAddDialog',
    components: {
        VueTelInput,
        // VueTelInputVuetify,
        VueTagsInput,
        ConfirmDialog
    },
    props: ['dialog', 'editedIndex', 'addWarehouseItems', 'isMobile', 'warehousesLists', 'isWarehouseConnected'],
    data: () => ({
        tagsValidation: [
            {
            classes: 't-new-tag-input-text-error',
            rule: (/^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/),
            disableAdd: true
            }
        ],
        documentProto: document,
        valid: true,
        tagsInput: {
            touched: false,
            hasError: false,
            errorMessage: 'Please confirm the entered email address by pressing the "Enter" or "," key in your keyboard.'
            //errorMessage: ''
        },
        warehouseEmailAddress: '',
        emailTagActive: false,
        options: [],
        selectedCountry: '',
        selectedState: '',
        telProps: {
            mode: "international",
            defaultCountry: "US",
            placeholder: "Type phone number",
            label: 'Type phone number',
            autocomplete: "off",
            maxLen: 25,
            preferredCountries: ["US", "CN"]
        },
        vueTelDropdownOptions: {
			showDialCodeInSelection: true,
			showDialCodeInList: true,
			showFlags: true,
			showSearchBox: true,
		},
		vueTelInputOptions: {
			autocomplete: false,
			placeholder: "Type phone number",
			styleClasses: "tel-input-class",
			required: true,
		},
        rules: [
            (v) => !!v || "Input is required."
        ],
        arrayNotEmptyRules: [
            (v) => !!v || "Email is required",
            () => this.optionsFiltered.length > 0 || "Make sure to supply at least 1 email." 
        ],
        numberRules: [
            (v) => !!v || "Input is required.",
            (v) => /^(?=.*[0-9])[- +()0-9]+$/.test(v) || "Letters are not allowed."
        ],
        warehouseNameRules: [
            (v) => !!v || "Input is required.",
            (v) => /^.{1,250}$/.test(v) || 'Only 250 maximum characters allowed.'
        ],
        isPhoneNumberEmpty: false,
        // 3PL
        dialogCreateWarehouseSuccess: false
    }),
    watch: {
        tagsInput(value) {
            if (value.hasError) {
                jQuery('.ti-input').addClass('ti-new-tag-input-error')
            } else {
                jQuery('.ti-input').removeClass('ti-new-tag-input-error')
            }
        },
        dialog(value, oldValue) {
            this.tagsInput.hasError = false
            this.tagsInput.touched = false
            jQuery('.ti-input').removeClass('ti-new-tag-input-error')
            if (typeof el!=='undefined') {
                let el = document.getElementsByClassName('ti-input')[0]
                el.classList.remove('ti-new-tag-input-error')
            }           
            
            if (!value) {
                this.options = []
                this.warehouseEmailAddress = ''              
            } else if (value && !oldValue) {
                if (this.editedIndex==-1) {
                    this.options = []
                } else {
                    let validEmailAddress = []
                    if (this.warehouse.email_address.length > 0) {                        
                        this.warehouse.email_address.map(wea => {
                            if (wea!==null) {
                                validEmailAddress.push({text: wea,tiClasses:["ti-valid"]})
                            }
                        })
                    }
                    this.options = validEmailAddress    
                }                
            }
        }
    },
    computed: {
        ...mapGetters({
            getCountries: 'warehouse/getCountries',
			getCountriesLoading: 'warehouse/getCountriesLoading',
            getStates: 'warehouse/getStates',
            getStatesLoading: 'warehouse/getStatesLoading',
            getCities: 'warehouse/getCities',
            getCitiesLoading: 'warehouse/getCitiesLoading',
            getWarehouseDeleteLoading: 'warehouse/getWarehouseDeleteLoading',
            getUser: 'getUser',
            getWarehouseCreateLoading: 'warehouse/getWarehouseCreateLoading',
            getWarehouseLoading: 'warehouse/getWarehouseLoading',
            getWarehouseSaveAddLoading: 'warehouse/getWarehouseSaveAddLoading',
            getWarehouseUpdate3PLDataLoading: 'warehouse/getWarehouseUpdate3PLDataLoading'
        }),
        formTitle() {
            return this.editedIndex === -1 ? 'Add Warehouse' : 'Edit Warehouse'
        },
        dialogAdd: {
            get() {
                return this.dialog
            },
            set(value) {
                this.$refs.form.resetValidation()
                this.$emit('update:dialog', value)
            }
        },
        optionsFiltered: {
            get() {
                let validEmailAddress = []
                if (this.warehouse.email_address.length > 0) {                    
                    this.warehouse.email_address.map(wea => {
                        if (wea !== null) {
                            validEmailAddress.push({text: wea, tiClasses:["ti-valid"]})
                        }
                    })
                }
                return validEmailAddress
            },
            set(value) {
                this.options = value
            }            
        },
        dialogIndex: {
            get() {
                return this.editedIndex
            },
            set(value) {
                this.$emit('update:editedIndex', value)
            }
        },
        countries() {
            return typeof this.getCountries !== 'undefined' && this.getCountries !== null && this.getCountries.length !== 0 ? this.getCountries : []
        },
        states() {
            return typeof this.getStates !== 'undefined' && this.getStates !== null && this.getStates.length !== 0 ? this.getStates : []
        },
        cities() {
            return typeof this.getCities !== 'undefined' && this.getCities !== null && this.getCities.length !== 0 ? this.getCities : []
        },
        warehouse: {
            get() {
                return this.addWarehouseItems
            },
            set(value) {               
                this.$emit('update:addWarehouseItems', value)
            }
        },
        addWarehouseTemplate() {
            let {
                name,
                email_address,
                phone,
                address,
                country,
                city,
                state,
                zipcode,
                warehouse_type,
                description,
                edit_id_connected
            } = this.warehouse

            let payload = {
                name,
                email_address,
                phone,
                address,
                country,
                state,
                city,
                zipcode,
                warehouse_type_id: warehouse_type,
                customer_id: (typeof this.getUser == 'string') ? JSON.parse(this.getUser).customer.id : ''
            }

            if (this.editedIndex === -1) {
                return payload
            } else {
                if (this.isWarehouseConnected) {
                    return {
                        warehouse_name: name,
                        emails: email_address,
                        description,
                        warehouse_customer_id: edit_id_connected
                    }
                } else {
                    return payload
                }
            }
        },
    },
    methods: {
        ...mapActions({
            fetchCountries: 'warehouse/fetchCountries',
            fetchStates: 'warehouse/fetchStates',
            fetchCities: 'warehouse/fetchCities',
            createWarehouse: 'warehouse/createWarehouse',
            updateWarehouse: 'warehouse/updateWarehouse',
            fetchWarehouse: 'warehouse/fetchWarehouse',
            fetchSingleWarehouse: 'warehouse/fetchSingleWarehouse',
            fetchInventories: 'inventory/fetchInventories',
            setActiveId: 'warehouse/setActiveId',
            // 3PL
            fetchProductInventories:'productInventories/fetchProductInventories',
            fetchProductInventories3pl: 'productInventories/fetchProductInventories3pl',
            setProductEmptyData: 'productInventories/setProductEmptyData',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
            setIsCreateInboundShow: 'inbound/setIsCreateInboundShow',
            setIsAddInventoryShow: 'productInventories/setIsAddInventoryShow',
            checkWarehouse3PLProvider: 'warehouseCustomers/checkWarehouse3PLProvider',
			setWarehouseTypeHas3PL: 'warehouseCustomers/setWarehouseTypeHas3PL',
            updateConnected3PLWarehouse: 'warehouse/updateConnected3PLWarehouse',
            setConnected3PLWarehouseReset: 'warehouse/setConnected3PLWarehouseReset'
        }),
        ...globalMethods,
        async setSelectedCountry(value) {
            if (value !== '' && value !== null) {
                try {
                    await this.fetchStates(value)
                } catch (e) {
                    this.notificationError(e)
                    console.log(e);
                }
            }
        },
        async setSelectedState(value) {
            if (value !== '' && value !== null) {
                let payload = {
                    country: this.warehouse.country,
                    states: value
                }

                try {
                    await this.fetchCities(payload)
                } catch (e) {
                    this.notificationError(e)
                    console.log(e);
                }
            }
        },
        generateErrorMessage() {
            this.tagsInput.hasError = (this.options.length > 0) ? false : true
            if (this.tagsInput.hasError)
                jQuery('.ti-input').addClass('ti-new-tag-input-error')
            else
                jQuery('.ti-input').removeClass('ti-new-tag-input-error')

            this.isPhoneNumberEmpty = this.addWarehouseTemplate.phone === '' ? true : false
        },
        async checkWarehouse3PLProviderAction() {
            let warehouseCustomerId = (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : this.getUser.default_customer_id
            await this.checkWarehouse3PLProvider(warehouseCustomerId).then(async (res) => {
                if (typeof res !== 'undefined' && res.exist) {
                    this.setWarehouseTypeHas3PL(true)
                } else {
                    this.setWarehouseTypeHas3PL(false)
                }
            })  
        },
        async saveWarehouse() {
            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.$refs.form.validate()
            this.tagsInput.hasError = (this.options.length > 0) ? false : true            
    
            this.generateErrorMessage()
           
            setTimeout(async () => {
                if (this.$refs.form.validate()) {
                    if (!this.tagsInput.hasError && !this.isPhoneNumberEmpty) {
                        try {                        
                            jQuery('.ti-new-tag-input').trigger(
                                jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                            )
                            
                            // save then close
                            if (this.editedIndex === -1) {
                                let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                    return o.text
                                }) : []

                                let addWarehouseTemplate = this.addWarehouseTemplate
                                addWarehouseTemplate.email_address = finalEmailAddress

                                await this.createWarehouse(addWarehouseTemplate).then(async (createRes) => {
                                    this.$store.state.warehouse.currentWarehouse = null
                                    this.$store.dispatch("page/setCurrentInventoryTab", 0);    
                                    
                                    await this.fetchWarehouse().then(async () => {
                                        if (typeof createRes.data !== 'undefined') {
                                            await this.setProductEmptyData([])
                                            let newWarehouse = createRes.data
                                            let findWarehouse = _.find(this.warehousesLists, e => newWarehouse.id === e.id)
                                            this.$store.state.warehouse.currentWarehouse = findWarehouse
                                            this.setActiveId(findWarehouse.id)
                                            
                                            this.notificationMessage('Warehouse has been created.')
                                            this.close()

                                            let dataWithPage = { page: 1, id: findWarehouse.id }

                                            if (findWarehouse.warehouse_type === 'Third-Party Logistics (3pl)') {
                                                await this.setProductEmptyData([])
                                                this.dialogCreateWarehouseSuccess = true
                                                await this.fetchProductInventories3pl(dataWithPage)

                                            } else {
                                                await this.setProductEmptyData([])
                                                await this.fetchProductInventories(dataWithPage)
                                            }
                                        } else {
                                            this.getFirstWarehouse()
                                        }
                                    })
                                })

                                if (this.isMobile) {
                                    this.callSingleWarehouse()
                                }

                                await this.checkWarehouse3PLProviderAction()
                            } else {
                                let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                    return o.text
                                }) : []

                                let editWarehouseTemplate = this.addWarehouseTemplate
                                editWarehouseTemplate.id = this.warehouse.id

                                if (!this.isWarehouseConnected) {
                                    editWarehouseTemplate.email_address = finalEmailAddress
                                    editWarehouseTemplate._method = 'PUT'
                                    await this.updateWarehouse(editWarehouseTemplate)
                                } else {
                                    editWarehouseTemplate.emails = JSON.stringify(finalEmailAddress)
                                    await this.updateConnected3PLWarehouse(editWarehouseTemplate)
                                    await this.setConnected3PLWarehouseReset(null)
                                }
                                
                                this.notificationMessage('Warehouse has been updated.')

                                await this.fetchWarehouse().then(() => {
                                    if (this.$store.state.warehouse.currentWarehouse !== null) {
                                        if (this.warehouse.id === this.$store.state.warehouse.currentWarehouse.id) {
                                            let findWarehouse = _.find(this.warehousesLists, e => this.warehouse.id === e.id)
                                            this.$store.state.warehouse.currentWarehouse = findWarehouse

                                            if (findWarehouse.warehouse_type === 'Third-Party Logistics (3pl)') {
                                                // call the products for Inventory
                                                let newData = { 
                                                    id: this.warehouse.id, 
                                                    page: 1, 
                                                    warehouse_type: this.$store.state.warehouse.currentWarehouse.warehouse_type_id 
                                                }

                                                this.$emit('callWarehouseProducts', newData)
                                            }
                                        }
                                    } else {
                                        this.getFirstWarehouse()
                                    }
                                })                                
                                
                                this.close()

                                if (this.isMobile) {
                                    this.callSingleWarehouse()
                                }
                            }
                        } catch(e) {
                            this.notificationError(e)
                            console.log(e)
                        }    
                    }                    
                }
            }, 200)            
        },
        async saveAndAddWarehouse() {
            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.$refs.form.validate()
            this.tagsInput.hasError = (this.options.length > 0) ? false : true
            
            this.generateErrorMessage()
            setTimeout(async () => {
                if (this.$refs.form.validate()) {
                    if (!this.tagsInput.hasError && !this.isPhoneNumberEmpty) {
                        try {                        
                            jQuery('.ti-new-tag-input').trigger(
                                jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                            )

                            if (this.editedIndex === -1) {
                                let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                    return o.text
                                }) : []

                                let passedTemplate = this.addWarehouseTemplate
                                passedTemplate.another = true
                                passedTemplate.email_address = finalEmailAddress

                                await this.createWarehouse(passedTemplate).then(createRes => {
                                    this.$store.state.warehouse.currentWarehouse = null
                                    this.$store.dispatch("page/setCurrentInventoryTab", 0);    
                                    this.setProductEmptyData([])

                                    this.fetchWarehouse().then(async () => {
                                        if (typeof createRes.data !== 'undefined') {
                                            let newWarehouse = createRes.data
                                            let findWarehouse = _.find(this.warehousesLists, e => newWarehouse.id === e.id)
                                            this.$store.state.warehouse.currentWarehouse = findWarehouse
                                            this.setActiveId(findWarehouse.id)

                                            let dataWithPage = { page: 1, id: findWarehouse.id }

                                            if (findWarehouse.warehouse_type === 'Third-Party Logistics (3pl)') {
                                                await this.setProductEmptyData([])
                                                this.dialogCreateWarehouseSuccess = true
                                                await this.fetchProductInventories3pl(dataWithPage)

                                            } else {
                                                await this.setProductEmptyData([])
                                                await this.fetchProductInventories(dataWithPage)
                                            }
                                        } else {
                                            this.getFirstWarehouse()
                                        }
                                    })
                                })

                                this.notificationMessage('Warehouse has been created.')                   
                                this.close()
                                this.dialogIndex = -1
                                this.dialogAdd = true

                                if (this.isMobile) {
                                    this.callSingleWarehouse()
                                }

                                await this.checkWarehouse3PLProviderAction() 
                            } else {
                                let editWarehouseTemplate = this.addWarehouseTemplate
                                let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                    return o.text
                                }) : []
                                editWarehouseTemplate.email_address = finalEmailAddress
                                editWarehouseTemplate.id = this.warehouse.id
                                editWarehouseTemplate._method = 'PUT'
                                editWarehouseTemplate.another = true
                                await this.updateWarehouse(editWarehouseTemplate)
                                this.notificationMessage('Warehouse has been updated.')
                                
                                // await this.fetchWarehouse()
                                await this.fetchWarehouse().then(() => {
                                    if (this.$store.state.warehouse.currentWarehouse !== null) {
                                        if (this.warehouse.id === this.$store.state.warehouse.currentWarehouse.id) {
                                            let findWarehouse = _.find(this.warehousesLists, e => this.warehouse.id === e.id)
                                            this.$store.state.warehouse.currentWarehouse = findWarehouse
                                        }
                                    }
                                })

                                if (this.isMobile) {
                                    this.callSingleWarehouse()
                                }
                            }                            
                            // save then close
                            this.setToDefault()
                        } catch(e) {
                            this.notificationError(e)
                            console.log(e)
                        }
                    }                    
                }
            }, 200)                        
        },
        async callSingleWarehouse() {
            let pageId = new URL(location.href).searchParams.get('id')

            if (pageId !== 'undefined' && pageId !== null) {
                this.fetchSingleWarehouse(pageId)

                try {
                    await this.fetchInventories(pageId)
                } catch(e) {
                    this.notificationError(e)
                }
            }
        },
        close() {
            this.$store.dispatch("warehouse/setStatesReset", [])
            this.$store.dispatch("warehouse/setCitiesReset", [])
            this.$refs.form.resetValidation()
            this.$emit('close')
            this.isPhoneNumberEmpty = false
        },
        setToDefault() {
            this.$store.dispatch("warehouse/setStatesReset", [])
            this.$store.dispatch("warehouse/setCitiesReset", [])
            this.$refs.form.resetValidation()
            this.$emit('setWarehouseToDefault')
            this.isPhoneNumberEmpty = false
            this.options = []
            this.warehouseEmailAddress = ''

            this.tagsInput = {
                touched: false,
                hasError: false
            }
        },
        deleteWarehouse(warehouse) {
            this.$emit('deleteWarehouse', warehouse)
        },
        getFirstWarehouse() {
            this.$emit('getFirstWarehouse')
        },
        isStateClicked() {
            if (this.warehouse.country == '') {
                this.notificationErrorCustom('Please select a country first.')
            }
        },
        isCitiesClicked() {
            if (this.warehouse.country == '') {
                this.notificationErrorCustom('Please select a country first.')
            } else {                
                if (this.warehouse.state == '') {
                    this.notificationErrorCustom('Please select a state first.')
                }
            }
        },
        handlers: (map, vm) => ({
            ...map,
            50: (e) => {
                e.preventDefault()
                if (e.key === '@' && vm.search.length > 0) {
                    // autocomplete email if @ key was clicked
                    vm.search = `${vm.search}@gmail.com`
                }
            },
        }),
        closeWarehouseAlert() {
            this.dialogCreateWarehouseSuccess = false
        },
        addCurrentInventory() {
            this.closeWarehouseAlert()
            this.setIsAddInventoryShow(true)
        },
        createInbound() {
            if (this.$router.history.current.query.tab !== 'undefined' && 
                this.$router.history.current.query.tab !== 'Inbound') {
                    
                this.$router.push(`?tab=Inbound`)
                this.$router.history.current.query.tab = 'Inbound'
                this.$store.state.page.currentInventoryTab = 3
                this.setCurrentInboundTab(0)
                this.closeWarehouseAlert()
                this.setIsCreateInboundShow(true)
            }
        },
    },
    async mounted() {
        if (this.editedIndex!==-1) {
            let validEmailAddress = []
            if (this.warehouse.email_address.length > 0) {                
                this.warehouse.email_address.map(wea => {
                    if (wea !== null) {
                        validEmailAddress.push({text: wea,tiClasses:["ti-valid"]})
                    }
                })
            }
            this.options = validEmailAddress
        }
    },
    async updated() {
        if (this.editedIndex === -1) {
            if (typeof this.$refs.form !== 'undefined') {
                if (typeof this.$refs.form.resetValidation() !== 'undefined') {
                    this.$refs.form.resetValidation()
                }
            }
        }
    }
}
</script>

<style lang="scss">
/* @import '../../../assets/css/warehouse_styles/addWarehouseDialog.css'; */
@import '../../../assets/scss/pages_scss/warehouse/addWarehouseDialog.scss';
@import '../../../assets/scss/inputs.scss';

.v-autocomplete__content.v-menu__content {
    border-radius: 4px !important;
}

.ti-tag {
    border-radius: 4px !important;
    font-size: 14px;
    font-family: 'Inter-Medium', sans-serif;
    padding: 3px 10px !important;
    line-height: 1;
    height: 30px;
    background-color: #F7F7F7 !important;
    border: 1px solid #E1ECF0;
    color: #6D858F !important;
    margin: 6px 2px 2px 2px;
    min-width: 75px;
    justify-content: space-between;
    align-items: center;
}

.ti-new-tag-input {
    flex: 1 1 auto;
    line-height: 20px;
    padding: 8px 0 8px;
    max-width: 100%;
    min-width: 0px;
    width: 100%;
}

.ti-input {
    border: 1px solid #B4CFE0 !important;
    border-radius: 4px;
    min-height: 45px;
    font-size: 14px;
}

.ti-input .ti-tags span {
    color: #4a4a4a !important;
}

.ti-new-tag-input::placeholder,
.ti-input::placeholder{
    color: #B4CFE0 !important;
}

.ti-new-tag-input-error {
    border: 1px solid #fc8686 !important;
}

.t-new-tag-input-text-error {
    color: #ff5252 !important;
}

.ti-new-tag-input-wrapper input {
    font-size: 14px !important;
}
</style>
