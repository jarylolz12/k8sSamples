<template>
    <v-dialog v-model="dialog" max-width="95vw" content-class="add-supplier-dialog" v-resize="onResize" :retain-focus="false">
        <v-card class="add-supplier-card">
            <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                <v-card-title>
                    <div class="headline">Create Invoice
                        <!-- <v-chip
                        class="ml-2"
                            color="#2dc48e"
                            outlined
                        >
                            Partially Paid
                        </v-chip> -->
                        <!-- <v-chip
                        class="ml-2"
                            color="red"
                            outlined
                        >
                            Overdue
                        </v-chip> -->
                        <!-- <v-chip
                        class="ml-2"
                            color="primary"
                            outlined
                        >
                            Open
                        </v-chip> -->
                        <v-chip
                        class="ml-2"
                            color="success"
                            outlined
                        >
                            Paid
                        </v-chip>
                    </div>
                    <button icon dark class="btn-close" @click="close">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </v-card-title>

                <template>
                    <v-container-fluid>
                        <v-row>
                            <v-col
                            cols="3"
                            class="grey lighten-5 pa-5"
                            >
                                <v-row>
                                    <v-col cols="12" sm="12" md="12" class="pb-0">
                                        <label class="text-item-label">Invoice No.</label>
                                        <v-text-field
                                            flat solo
                                            value="712119-129"
                                            outlined 
                                            class="text-fields cvalue"
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-text-field>
                                    </v-col>                    

                                    <v-col cols="12" sm="12" md="12" class="pb-0">
                                        <label class="text-item-label">Customer</label>
                                        <v-select
                                            flat solo
                                            v-model="select"
                                            class="text-fields"
                                            :items="items"
                                            outlined
                                            :error-messages="errors"
                                            placeholder="Select Customer"
                                            data-vv-name="select"
                                            required
                                        ></v-select>
                                    </v-col>

                                    <v-col cols="12" sm="12" md="12">
                                        <label class="text-item-label">Customer Email</label>
                                        <v-text-field
                                            flat solo
                                            type="email"
                                            placeholder="e.g. name@email.com"
                                            outlined 
                                            class="text-fields"
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="12" md="12" class="pb-0">
                                        <label class="text-item-label">Invoice Date</label>
                                        <template>
                                        <v-row>
                                            <v-col
                                            cols="12"
                                            >
                                            <v-menu
                                                ref="menu"
                                                v-model="menu"
                                                :close-on-content-click="false"
                                                :return-value.sync="date"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="auto"
                                            >
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-text-field
                                                        flat solo
                                                    v-model="date"
                                                    class="text-fields"
                                                    outlined
                                                    label="Select Date"
                                                    append-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"
                                                        
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                v-model="date"
                                                no-title
                                                scrollable
                                                >
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                    text
                                                    color="primary"
                                                    @click="menu = false"
                                                >
                                                    Cancel
                                                </v-btn>
                                                <v-btn
                                                    text
                                                    color="primary"
                                                    @click="$refs.menu.save(date)"
                                                >
                                                    OK
                                                </v-btn>
                                                </v-date-picker>
                                            </v-menu>
                                            </v-col>
                                        </v-row>
                                        </template>
                                    </v-col> 

                                    <v-col cols="12" sm="12" md="12" class="pb-0">
                                        <label class="text-item-label">Due Date</label>
                                        <v-menu
                                            ref="menu1"
                                            v-model="menu1"
                                            :close-on-content-click="false"
                                            :return-value.sync="date1"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="auto"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                    flat solo
                                                    v-model="date1"
                                                    class="text-fields"
                                                    outlined
                                                    label="Select Date"
                                                    append-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                            v-model="date1"
                                            no-title
                                            scrollable
                                            >
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="menu1 = false"
                                            >
                                                Cancel
                                            </v-btn>
                                            <v-btn
                                                text
                                                color="primary"
                                                @click="$refs.menu1.save(date1)"
                                            >
                                                OK
                                            </v-btn>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-col> 

                                    <v-col cols="12" sm="12" md="12" class="pb-0">
                                        <label class="text-item-label">Billing Address</label>
                                        <v-textarea
                                            flat solo
                                            placeholder="Type your Billing Address here..." 
                                            value=""
                                            outlined 
                                            class="text-fields"
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-textarea>
                                    </v-col> 
                                    <v-col cols="12" sm="12" md="12" class="">
                                        <label class="text-item-label">Notes</label>
                                        <v-textarea
                                            flat solo
                                            placeholder="Type your Notes here..." 
                                            value=""
                                            outlined 
                                            class="text-fields"
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-textarea>
                                    </v-col> 
                                </v-row>
                            </v-col>
                            <v-col
                            cols="9"
                            class="pa-5"
                            >
                                <template>
                                    <v-simple-table>
                                        <template v-slot:default>
                                        <tbody>
                                            <tr style="border: none" class="grey lighten-5 grey--text">
                                            <th class="text-left" style="border-bottom: none !important;">
                                                Product
                                            </th>
                                            <th class="text-left" style="border-bottom: none !important;">
                                                HSN/SAC
                                            </th>
                                            <th class="text-left" style="border-bottom: none !important;">
                                                Description
                                            </th>
                                            <th class="text-left" style="border-bottom: none !important;">
                                                QTY
                                            </th>
                                            <th class="text-left" style="border-bottom: none !important;">
                                                Rate
                                            </th>
                                            <th class="text-left" style="border-bottom: none !important;">
                                                Amount
                                            </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <v-select
                                                        style="width:15vw"
                                                        v-model="select"
                                                        class="text-fields mt-2"
                                                        :items="product_items"
                                                        outlined
                                                        :error-messages="errors"
                                                        placeholder="Select Product"
                                                        data-vv-name="select"
                                                        required
                                                    ></v-select>
                                                </td>
                                                <td>
                                                    <v-text-field 
                                                        placeholder="HSN/SAC" 
                                                        value=""
                                                        outlined 
                                                        class="text-fields"
                                                        :rules="rules"
                                                        hide-details="auto">
                                                    </v-text-field>
                                                </td>
                                                <td>
                                                    <v-text-field 
                                                        placeholder="" 
                                                        value=""
                                                        outlined 
                                                        class="text-fields"
                                                        :rules="rules"
                                                        hide-details="auto">
                                                    </v-text-field>
                                                </td>
                                                <td>
                                                    <v-text-field 
                                                        placeholder="0" 
                                                        value=""
                                                        outlined 
                                                        class="text-fields"
                                                        :rules="rules"
                                                        hide-details="auto">
                                                    </v-text-field>
                                                </td>
                                                <td>
                                                    <v-text-field 
                                                        placeholder="0" 
                                                        value=""
                                                        outlined 
                                                        class="text-fields"
                                                        :rules="rules"
                                                        hide-details="auto">
                                                    </v-text-field>
                                                </td>
                                                <td>
                                                    <v-text-field
                                                        flat solo
                                                        placeholder="" 
                                                        
                                                        value="$1500.00" 
                                                        class="text-fields close-amount"
                                                        :rules="rules"
                                                        hide-details="auto"
                                                        clearable
                                                    >
                                                    <!-- <template v-slot:append>        
                                                        <v-icon color="red" click:clear> mdi-close</v-icon> 
                                                    </template> -->
                                                    </v-text-field>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td>
                                                    <v-btn
                                                        class="text-capitalize elevation-0"
                                                    style="font-weight:600 !important; background-color:white; border: 1px solid #B4CFE0;color: #0171A1;"
                                                        >
                                                        <v-icon left>
                                                            mdi-plus
                                                        </v-icon>
                                                        Add Product
                                                    </v-btn>
                                                </td>
                                            </tr>
                                            <!-- <tr>
                                            v-for="item in desserts"
                                            :key="item.name"
                                            >
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.calories }}</td>
                                            </tr> -->
                                        </tbody>
                                        </template>
                                    </v-simple-table>
                                    <v-divider></v-divider>
                                    <!-- <hr class="color-light"/> -->
                                </template>
                                <v-row class="mt-2" justify="space-between">
                                    <v-col cols="4" sm="12" md="4" class="pb-0">
                                        <label class="text-item-label">ATTACHMENTS
                                            <v-row class="d-flex flex-column text-capitalize py-10 mt-1" dense align="center" justify="center" style="border: 2px dashed #eee; border-radius: 5px;">
                                                <p>
                                                    Browse or Drop Image
                                                </p>
                                                <v-btn
                                                    
                                                    class="text-capitalize elevation-0"
                                                    style="font-weight:600 !important; background-color:white; border: 1px solid #B4CFE0;color: #0171A1;"
                                                    @click="onButtonClick"
                                                    >
                                                    <img src="../../assets/icons/upload.svg" alt="" width="20px" height="20px">
                                                    Upload
                                                </v-btn>
                                                <input
                                                    ref="uploader"
                                                    class="d-none"
                                                    type="file"
                                                    accept="image/*"
                                                    @change="onFileChanged"
                                                >
                                            </v-row>
                                        </label>
                                    </v-col>
                                    <v-col cols="4" sm="12" md="4" class="pb-0">
                                        <template>
                                            <v-simple-table dense>
                                                <template v-slot:default>
                                                <tbody>
                                                    <tr>
                                                    <td>Subtotal</td>
                                                    <td>$1500.00</td>
                                                    </tr>
                                                    <tr>
                                                    <td>Tax</td>
                                                    <td>$0</td>
                                                    </tr>
                                                    <tr>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold">Total</td>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold">$1500.00</td>
                                                    </tr>
                                                    <!-- <tr>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold">Amount Recieved</td>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold">$1500.00</td>
                                                    </tr> -->
                                                    <tr>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold">Balance Due</td>
                                                    <td style="border-bottom: none !important;" class="font-weight-bold">$0.00</td>
                                                    </tr>
                                                </tbody>
                                                </template>
                                            </v-simple-table>
                                        </template>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container-fluid>
                </template>

                <v-card-actions>
                    <v-btn class="btn-blue" text @click="save">
                        <span v-if="editedIndexData === -1">
                            <span>
                                {{ 
                                    ( getCreateSuppliersLoading ) ? 'Adding Invoice...' : 'Add Invoice'
                                }}
                            </span>
                        </span>

                        <span v-if="editedIndexData > -1">
                            <span>
                                {{ 
                                    ( getCreateSuppliersLoading ) ? ' Saving Invoice...' : 'Save Invoice'
                                }}
                            </span>
                        </span>
                    </v-btn>

                    <v-btn class="btn-white" text @click="saveAndAddSupplier" v-if="editedIndexData === -1">
                        {{
                            ( getSupplierSaveAddLoading ) ? 'Saving...' : 'Save & Add Another'
                        }}
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
import { mapActions, mapGetters } from 'vuex'
import jQuery from 'jquery'
// import globalMethods from '../../utils/globalMethods'

export default {
    name: 'SupplierDialog',
    props: ['editedItemData','editedIndexData', 'dialogData', 'defaultItemData','alert'],
    components: {
    },
    data: () => ({
        date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        date1: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        select: null,
        items: [
            'Customer 1',
            'Customer 2',
            'Customer 3',
            'Customer 4',
        ],
        product_items: [
            'Product 1',
            'Product 2',
            'Product 3',
            'Product 4',
        ],
        valid: true,
        telInputOptions: {
            showDialCodeInSelection: true,
            showDialCodeInList: true,
            showFlags: true,
        },
        options: [],
        value: [],
        isMobile: false,
        rules: [
            (v) => !!v || "Input is required."
        ],
        telProps: {
            mode: "international",
            defaultCountry: "US",
            placeholder: "Type phone number",
            label: 'Type phone number',
            autocomplete: "off",
            maxLen: 25,
            preferredCountries: ["US", "CN"],
            enabledCountryCode: true,
            dropdownOptions: {
                showDialCodeInSelection: true,
                showFlags: true
            },
            validCharactersOnly: true
        },
        numberRules: [
            (v) => !!v || "Input is required.",
            (v) => /^(?=.*[0-9])[- +()0-9]+$/.test(v) || "Letters are not allowed."
        ],
        tagsValidation: [
            {
            classes: 't-new-tag-input-text-error',
            rule: (/^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/),
            disableAdd: true
            }
        ],
        documentProto: document,
        tagsInput: {
            touched: false,
            hasError: false,
            errorMessage: 'Please confirm the entered email address by pressing the "Enter" or "," key in your keyboard.'
        },
        warehouseEmailAddress: '',
        arrayNotEmptyRules: [
            (v) => !!v || "Email is required",
            () => this.optionsFiltered.length > 0 || "Make sure to supply at least 1 email." 
        ],
    }),
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getCreateSuppliersLoading: 'suppliers/getCreateSuppliersLoading',
            getSupplierSaveAddLoading: 'suppliers/getSupplierSaveAddLoading'
        }),
        formTitle () {
            return this.editedIndexData === -1 ? 'Add Supplier' : 'Edit Supplier'
        },
        dialog: {
            get () {
                return this.dialogData
            },
            set (value) {
                if (!value) {
                    this.$emit('update:dialogData', false)
                } else {
                    this.$emit('update:dialogData', true)
                }
            }
        },
        editedItem: {
            get () {
                return this.editedItemData
            },
            set (value) {
                console.log(value);
            }
        },
        defaultItem: {
            get () {
                return this.defaultItemData
            },
            set (value) {
                console.log(value);
            }
        },
        addSupplierTemplate() {
            let { company_name, address, phone, emails } = this.editedItem

            return {
                custom_customers_id: [(typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : ''],
                company_name, 
                address, 
                phone, 
                emails
            }
        },
        optionsFiltered: {
            get() {
                let validEmailAddress = []

                if (this.editedItem.emails !== null && this.editedItem.emails.length > 0) {                    
                    this.editedItem.emails.map(wea => {
                        if (wea!==null) {
                            validEmailAddress.push({text: wea,tiClasses:["ti-valid"]})
                        }
                    })
                }

                return validEmailAddress
            },
            set(value) {
                this.options = value
            }            
        },
    },
    methods: {
        onButtonClick() {
            this.isSelecting = true
            window.addEventListener('focus', () => {
                this.isSelecting = false
            }, { once: true })

            this.$refs.uploader.click()
        },
        onFileChanged(e) {
            this.selectedFile = e.target.files[0]
            
            // do something
        },
        ...mapActions({
            createSuppliers: 'suppliers/createSuppliers',
            fetchSuppliers: 'suppliers/fetchSuppliers',
            updateSuppliers: 'suppliers/updateSuppliers'
        }),
        // ...globalMethods,
        generateErrorMessage() {
            this.tagsInput.hasError = (this.options.length > 0) ? false : true
            if (this.tagsInput.hasError)
                jQuery('.ti-input').addClass('ti-new-tag-input-error')
            else
                jQuery('.ti-input').removeClass('ti-new-tag-input-error')
        },
        onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true
            } else {
                this.isMobile = false
            }
        },
        close() {
            this.$refs.form.resetValidation()
            this.$emit('update:dialogData', false)
        },
        async save() {
            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.$refs.form.validate()
            this.tagsInput.hasError = (this.options.length > 0) ? false : true
    
            this.generateErrorMessage()

            setTimeout(async () => {
                if (this.$refs.form.validate()) {
                    if (!this.tagsInput.hasError) {
                        try {
                            jQuery('.ti-new-tag-input').trigger(
                                jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                            )

                            if (this.editedIndexData === -1) {
                                let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                    return o.text
                                }) : []

                                let addSupplierTemplate = this.addSupplierTemplate
                                addSupplierTemplate.emails = finalEmailAddress

                                await this.createSuppliers(this.addSupplierTemplate)
                                
                                this.notificationMessage('Supplier has been created.')
                                await this.fetchSuppliers()

                                //close dialog
                                this.close()
                                this.editedIndexData = -1
                            } else {
                                let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                    return o.text
                                }) : []

                                const { custom_customers_id, ...newSupplierTemplate } = this.addSupplierTemplate
                                newSupplierTemplate.id = this.editedItem.id
                                newSupplierTemplate.emails = finalEmailAddress

                                await this.updateSuppliers(newSupplierTemplate)
                                console.log(custom_customers_id)
                                this.notificationMessage('Supplier has been updated.')
                                await this.fetchSuppliers()

                                // close dialog
                                this.close()
                            }
                        } catch (e) {
                            this.notificationError(e)
                            console.log(e)
                        }
                    }
                    
                } 
            }, 200)               
        },
        async saveAndAddSupplier() {
            if (!this.tagsInput.touched)
                this.tagsInput.touched = true

            this.$refs.form.validate()
            this.tagsInput.hasError = (this.options.length > 0) ? false : true            

            this.generateErrorMessage()

             setTimeout(async () => {
                 if (this.$refs.form.validate()) {
                    if (!this.tagsInput.hasError) {
                        try {
                            jQuery('.ti-new-tag-input').trigger(
                                jQuery.Event( 'keyup', { keyCode: 13, which: 13 } )
                            )

                            if (this.editedIndexData === -1) {
                                let finalEmailAddress = (this.options.length > 0) ? this.options.map(o => {
                                    return o.text
                                }) : []

                                this.addSupplierTemplate.customerMethod = 'save-add'
                                this.addSupplierTemplate.emails = finalEmailAddress
                                await this.createSuppliers(this.addSupplierTemplate)
                                

                                await this.fetchSuppliers()                    
                                this.setToDefault()

                                this.notificationMessage('Supplier has been created.')
                            }
                        } catch(e) {
                            this.notificationError(e)
                            console.log(e)
                        }
                    }
                    
                }
             }, 200)

            
        },
        setToDefault() {
            this.$emit('setToDefault')
            this.tagsInput.touched = false
            this.options = []
        }
    },
    watch: {
        tagsInput(value) {
            if ( value.hasError) {
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
            
            if ( !value ) {
                this.options = []
                this.warehouseEmailAddress = ''                

            } else if(value && !oldValue) {
                if (this.editedIndex==-1) {
                    this.options = []
                } else {
                    let validEmailAddress = []
                    if (this.editedItem.emails !== null && this.editedItem.emails.length > 0) {
                        
                        this.editedItem.emails.map(wea => {
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
    updated() {
        if (this.editedIndexData === -1) {
            if (typeof this.$refs.form !== 'undefined') {
                if (typeof this.$refs.form.resetValidation() !== 'undefined') {
                    this.$refs.form.resetValidation()
                }
            }
        }
    }
}
</script>

<style scoped lang="scss">
@import '../../assets/scss/pages_scss/dialog/globalDialog.scss';
@import '../../assets/scss/pages_scss/invoice/invoiceAddDialog.scss';

.v-autocomplete__content.v-menu__content {
    border-radius: 4px !important;
}
.color-light{
    color: rgb(224, 224, 224);
}
  tbody {
     tr:hover {
        background-color: transparent !important;
     }
  }
// .close-amount button{
//     color: red !important;
//     background: blue !important;
// }


// .v-text-field{
//       width: 40px;
// }
</style>
