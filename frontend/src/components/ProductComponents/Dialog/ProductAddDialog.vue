<template>
    <v-dialog v-model="dialogAdd" max-width="980px" content-class="product-dialog-wrapper" :retain-focus="false" persistent scrollable>
        <v-card class="product-dialog-card">
            <v-card-title class="add-product-header-title">
                <span class="headline">
                    {{ 
                        productItem.isDuplicate ? 'Duplicate Product' : 
                        productItem.isDuplicateEdit ? 'Edit Product - Contact Specific' : formTitle 
                    }}
                </span>

                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="add-wrapper">
                        <div class="product-info">
                            <div class="product-number mb-2">
                                <p class="product-title">SKU</p>
                                <v-radio-group v-model="isCustom" mandatory v-if="editedIndex === -1" hide-details="auto" class="pt-0">
                                    <v-radio label="System Generated" value="generated"></v-radio>
                                    <v-radio label="Custom Number" value="custom"></v-radio>

                                    <span class="custom-wrapper" v-if="isCustom == 'custom'">
                                        <img src="@/assets/icons/item-icon.svg" alt="" class="box-icon" />
                                        
                                        <v-text-field
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            type="text"
                                            dense
                                            class="text-fields custom"
                                            placeholder="Enter Product Number"
                                            outlined
                                            v-model="productItem.sku"
                                            :rules="customSkuRules"
                                            hide-details="auto"
                                            :prefix="getCategoryPrefix(productItem.category_id)"
                                            @keydown="inputRestrictSpecialChar($event)">
                                        </v-text-field>
                                    </span>
                                </v-radio-group>

                                <div class="custom-wrapper ml-0" v-if="editedIndex > -1">
                                    <img src="@/assets/icons/item-icon.svg" alt="" class="box-icon" />
                                    
                                    <v-text-field
                                        height="40px"
                                        color="#002F44"
                                        width="200px"
                                        type="text"
                                        dense
                                        class="text-fields custom"
                                        placeholder="Enter Product Number"
                                        outlined
                                        v-model="productItem.sku"
                                        :rules="customSkuRules"
                                        hide-details="auto"
                                        :prefix="getCategoryPrefix(productItem.category_id)"
                                        @keydown="inputRestrictSpecialChar($event)"
                                        :disabled="isProductDuplicate(productItem)">
                                    </v-text-field>
                                </div>
                            </div>
                            
                            <div class="product-name mb-2">
                                <p class="product-title">PRODUCT NAME</p>
                                <v-text-field
                                    height="40px"
                                    color="#002F44"
                                    width="200px"
                                    dense
                                    class="text-fields select-items"
                                    placeholder="Enter Product Name"
                                    outlined
                                    v-model="productItem.name"
                                    :rules="isSystemGeneratedValueCheck == true ? productNameRules : []"
                                    hide-details="auto"
                                    @keydown="inputRestrictSpecialChar($event)"
                                    :disabled="isProductDuplicate(productItem)">
                                </v-text-field>
                            </div>

                            <div class="product-category mb-2">
                                <p class="product-title">CATEGORY</p>
                                <v-autocomplete
                                    class="text-fields select-items"
                                    v-model="productItem.category_id"
                                    :items="categoryLists"
                                    item-text="nameWithId"
                                    item-value="id"
                                    outlined
                                    hide-details="auto"
                                    placeholder="Select the category"
                                    clearable
                                    :disabled="isProductDuplicate(productItem)">
                                </v-autocomplete>
                            </div>

                            <div class="product-customer mb-2" 
                                v-if="typeof getHas3PLProviderWarehouse !== 'undefined' && getHas3PLProviderWarehouse">
                                <p class="product-title">WAREHOUSE CUSTOMER</p>
                                <v-autocomplete
                                    class="text-fields select-items"
                                    v-model="productItem.warehouse_customer_id"
                                    :items="warehouseCustomerLists"
                                    item-text="name"
                                    item-value="id"
                                    outlined
                                    hide-details="auto"
                                    placeholder="Select Warehouse Customer"
                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true, closeOnContentClick: true }"
                                    clearable
                                    :disabled="isProductDuplicate(productItem)">

                                    <template v-slot:item="{ item }">
                                        <div class="option-items" style="padding: 14px 0;">
                                            <div class="name-address-item">
                                                <p class="name mb-1" style="color: #4a4a4a;"> 
                                                    {{ item.name }} 
                                                </p>

                                                <p class="address mb-0" style="color: #6D858F; font-size: 12px;"> 
                                                    {{ item.address }}
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                </v-autocomplete>
                            </div>

                            <div class="product-contact mb-2" v-if="checkIfHasChildProducts(productItem)">
                                <p class="product-title">CONTACT</p>
                                <v-select
                                    class="text-fields select-items contact-field"
                                    v-model="contactListsSelected"
                                    :items="allContactsCopy"
                                    item-text="company_name"
                                    item-value="company_name"
                                    outlined
                                    hide-details="auto"
                                    :placeholder="getProductContactsLoading ? 'Fetching contacts...' : 'Select Contact'"
                                    :menu-props="{ contentClass: 'product-lists-items contacts', bottom: true, offsetY: true, closeOnContentClick: false }"
                                    multiple
                                    small-chips
                                    deletable-chips
                                    return-object
                                    :disabled="getProductContactsLoading"
                                    v-bind:class="{
										'error-contact': (productItem.isDuplicate && contactListsSelected.length === 0) || isContactError || contactSelectedExist,
									}">
                                                                        
                                    <template v-slot:selection="{ item }">
                                        <v-chip class="product-item-chip font-medium" close @click:close="removeChip(item)"
                                        :class="typeof item.className !== 'undefined' ? item.className : ''">
                                            <span class="name">{{ item.company_name }}</span>
                                        </v-chip>
                                    </template>

                                    <template v-slot:prepend-item>
                                        <v-list-item class="search-input-field pa-0">
                                            <v-text-field 
                                                v-model="searchContactName" 
                                                placeholder="Search Contact"
                                                hide-details="auto"
                                                class="text-fields select-items product-search-contact-field"
                                                outlined
                                                prepend-inner-icon="mdi-magnify"
                                                @input="handleSearchContact">
                                            </v-text-field>
                                        </v-list-item>
                                        <v-divider style="border-color: #EBF2F5 !important;"></v-divider>
                                    </template>

                                    <template v-slot:item="{ item, attrs, on }">
                                        <v-list-item v-on="on" v-bind="attrs" #default="{ active }" @click.stop="ifIsContactSelected(item)"
                                            :class="`${item.class} ${typeof item.customClass === 'undefined' ? '' : item.customClass}`">
                                            <v-list-item-action class="mr-2 pa-0">
                                                <v-checkbox :input-value="active"></v-checkbox>
                                            </v-list-item-action>
                                            <v-list-item-content>
                                                <v-list-item-title>
                                                    <p class="contact-name mb-0">{{ item.company_name }}</p>
                                                    <span class="text-capitalize">{{ item.contact_type }}</span>
                                                </v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </template>

                                    <template v-slot:append-item>
                                        <v-list-item v-if="isAllOptionsHidden(allContactsCopy)">
                                            <v-list-item-content>
                                                <v-list-item-title>
                                                    <span style="font-size: 14px; color: #4a4a4a;">
                                                        No available data
                                                    </span>
                                                </v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </template>
                                </v-select>

                                <span v-if="(productItem.isDuplicate && contactListsSelected.length === 0) || isContactError" style="color: red; font-size: 12px;">Select a contact</span>

                                <p v-if="contactSelectedExist" class="mb-0 mt-1" 
                                style="color: red; font-size: 12px; line-height: 18px;">
                                    This sku has already been associated with this contact. Please select a different one.
                                </p>
                            </div>

                            <div class="price-and-units mt-2 mb-2" v-if="checkIfHasChildProducts(productItem)">
                                <div class="product-each">
                                    <p class="product-title">UNIT PRICE</p>
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        width="200px"
                                        dense
                                        prefix='$'
                                        class="text-fields"
                                        placeholder="0.00"
                                        outlined
                                        type="number"
                                        v-model="productItem.unit_price"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                    <!-- :rules="rules" -->
                                </div>

                                <div class="product-each">
                                    <p class="product-title">IN EACH CARTON</p>
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        width="200px"
                                        dense
                                        class="text-fields"
                                        placeholder="1 units"
                                        outlined
                                        type="number"
                                        v-model="productItem.units_per_carton"
                                        hide-details="auto"
                                        min="1"
                                        :rules="inEachCartonRules"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                </div>
                            </div>

                            <div class="carton-dimension mt-2 mb-2" v-if="checkIfHasChildProducts(productItem)">
                                <p class="product-title">CARTON DIMENSIONS</p>
                                <div class="input-container">
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        dense
                                        class="text-fields carton-length mr-1"
                                        outlined
                                        type="number"
                                        placeholder="0"
                                        prefix="L"
                                        v-model="cartonDimensions.l"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        dense
                                        class="text-fields carton-width mr-1"
                                        outlined
                                        type="number"
                                        placeholder="0"
                                        prefix="W"
                                        v-model="cartonDimensions.w"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        dense
                                        class="text-fields carton-height mr-1"
                                        outlined
                                        type="number"
                                        placeholder="0"
                                        prefix="H"
                                        v-model="cartonDimensions.h"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                    <v-select
                                        class="text-fields select-items carton-uom"
                                        :items="['cm', 'in']"
                                        item-text="name"
                                        item-value="id"
                                        height='40px'
                                        outlined
                                        v-model="cartonDimensions.uom"
                                        hide-details="auto"
                                        :menu-props="{ bottom: true, offsetY: true }">
                                    </v-select>
                                </div>
                            </div>  

                            <div class="product-upc-number mb-2" v-if="checkIfHasChildProducts(productItem)">
                                <p class="product-title">CARTON UPC (Optional)</p>
                                <v-text-field
                                    type="number"
                                    height="40px"
                                    color="#002F44"
                                    width="100px"
                                    dense
                                    class="text-fields select-items"
                                    placeholder="Enter Carton UPC"
                                    outlined
                                    v-model="productItem.carton_upc"
                                    hide-details="auto"
                                    @wheel="$event.target.blur()">
                                </v-text-field>
                            </div>

                            <div class="carton-dimension unit-shipping-dimension mt-2 mb-2" v-if="checkIfHasChildProducts(productItem)">
                                <p class="product-title">UNIT SHIPPING DIMS</p>
                                <div class="input-container">
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        dense
                                        class="text-fields carton-length mr-1"
                                        outlined
                                        type="number"
                                        placeholder="0"
                                        prefix="L"
                                        v-model="productItem.unit_dimensions.l"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        dense
                                        class="text-fields carton-width mr-1"
                                        outlined
                                        type="number"
                                        placeholder="0"
                                        prefix="W"
                                        v-model="productItem.unit_dimensions.w"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        dense
                                        class="text-fields carton-height mr-1"
                                        outlined
                                        type="number"
                                        placeholder="0"
                                        prefix="H"
                                        v-model="productItem.unit_dimensions.h"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()">
                                    </v-text-field>
                                    <v-select
                                        class="text-fields select-items carton-uom"
                                        :items="['cm', 'in']"
                                        item-text="name"
                                        item-value="id"
                                        height='40px'
                                        outlined
                                        v-model="productItem.unit_dimensions.uom"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()"
                                        :menu-props="{ bottom: true, offsetY: true }">
                                    </v-select>
                                </div>
                            </div> 

                            <div class="carton-dimension mt-2 mb-2">
                                <p class="product-title">UNIT WEIGHT</p>
                                <div class="input-container">
                                    <v-text-field
                                        background-color="white"
                                        height="40px"
                                        color="#002F44"
                                        dense
                                        class="text-fields carton-height mr-1"
                                        outlined
                                        type="number"
                                        placeholder="0"
                                        prefix="W"
                                        v-model="productItem.unit_weight.value"
                                        hide-details="auto"
                                        min="0"
                                        @keydown="restrictValues($event)"
                                        @wheel="$event.target.blur()"
                                        :disabled="isProductDuplicate(productItem)">
                                    </v-text-field>

                                    <v-select
                                        class="text-fields select-items carton-uom"
                                        :items="['kg', 'lb', 'g']"
                                        item-text="name"
                                        item-value="id"
                                        height='40px'
                                        outlined
                                        v-model="productItem.unit_weight.unit"
                                        hide-details="auto"
                                        :menu-props="{ bottom: true, offsetY: true }"
                                        :disabled="isProductDuplicate(productItem)">
                                    </v-select>
                                </div>
                            </div>                          
                            
                            <div class="product-upc-number mb-2">
                                <p class="product-title">UNIT UPC (Optional)</p>
                                <v-text-field
                                    type="number"
                                    height="40px"
                                    color="#002F44"
                                    width="100px"
                                    dense
                                    class="text-fields select-items"
                                    placeholder="Enter Unit UPC"
                                    outlined
                                    v-model="productItem.upc_number"
                                    hide-details="auto"
                                    @wheel="$event.target.blur()"
                                    :disabled="isProductDuplicate(productItem)">
                                </v-text-field>
                            </div>
                        </div>

                        <div class="product-description">
                            <div class="product-notes mb-2">
                                <p class="product-title">PRODUCT DESCRIPTION (Optional)</p>
                                <v-textarea
                                    height="100"
                                    class="text-fields description"
                                    outlined
                                    name="input-7-4"
                                    placeholder="Type description of the product..."
                                    v-model="productItem.description"
                                    :disabled="isProductDuplicate(productItem)">
                                </v-textarea>
                            </div>

                            <div class="product-img" v-if="!isProductDuplicate(productItem)">
                                <p class="product-title">PRODUCT IMAGE (Optional)</p>

                                <div id="product-img-select-box-id" 
                                    v-show="(product.create.image=='' && (productItem.image=='' || productItem.image==null && product.edit.image==''))" 
                                    class="product-img-select-box" @click="selectProductImage()">
                                        Browse Image
                                </div>

                                <div v-if="product.create.image !=='' || 
                                    (productItem.image !=='' && productItem.image!==null) || 
                                    product.edit.image!==''" 
                                    class="product-img-selected-image-container" 
                                    v-show="(product.create.image !=='' || productItem.image !=='')">

                                    <img v-if="product.edit.image !==''" class="product-img-selected-image" :src="product.edit.image" />

                                    <img v-if="productItem.image !=='' && productItem.image!==null && product.edit.image =='' && editedIndex > -1" class="product-img-selected-image" :src="productItem.image" />

                                    <img v-if="product.create.image !=='' && editedIndex === -1" class="product-img-selected-image" :src="product.create.image" />
                                </div>

                                <input 
                                    ref="product_image_reference" 
                                    type="file" 
                                    id="product_image" 
                                    @change="(e) => readFile(e)" 
                                    name="product_image"
                                    accept="image/png, image/jpg, image/jpeg" />

                                <div class="button-appear-success" 
                                    v-show="(product.create.image!=='' || (productItem.image!=='' && productItem.image!==null) || product.edit.image!=='')">
                                    <button class="btn-white mr-2" @click="selectProductImage()" v-if="!isProductDuplicate(productItem)">
                                        <img src="@/assets/icons/upload.svg" alt="" width="12px" height="12px">
                                        <span class="ml-1">Re-upload</span>
                                    </button>

                                    <button class="btn-white" @click="removeProductImage()" v-if="!isProductDuplicate(productItem)">
                                        <img src="@/assets/icons/deleteIcon.svg" alt="">
                                    </button>
                                </div>  
                            </div>

                            <div class="product-img" v-if="(isProductDuplicate(productItem) && productItem.image !== null && productItem.image !== '')">
                                <p class="product-title">PRODUCT IMAGE (Optional)</p>

                                <div id="product-img-select-box-id" 
                                    v-show="(product.create.image=='' && (productItem.image=='' || productItem.image==null && product.edit.image==''))" 
                                    class="product-img-select-box" @click="selectProductImage()">
                                        Browse Image
                                </div>

                                <div v-if="product.create.image !=='' || 
                                    (productItem.image !=='' && productItem.image!==null) || 
                                    product.edit.image!==''" 
                                    class="product-img-selected-image-container" 
                                    v-show="(product.create.image !=='' || productItem.image !=='')">

                                    <img v-if="product.edit.image !==''" class="product-img-selected-image" :src="product.edit.image" />

                                    <img v-if="productItem.image !=='' && productItem.image!==null && product.edit.image =='' && editedIndex > -1" class="product-img-selected-image" :src="productItem.image" />

                                    <img v-if="product.create.image !=='' && editedIndex === -1" class="product-img-selected-image" :src="product.create.image" />
                                </div>

                                <input 
                                    ref="product_image_reference" 
                                    type="file" 
                                    id="product_image" 
                                    @change="(e) => readFile(e)" 
                                    name="product_image"
                                    accept="image/png, image/jpg, image/jpeg" />

                                <div class="button-appear-success" 
                                    v-show="(product.create.image!=='' || (productItem.image!=='' && productItem.image!==null) || product.edit.image!=='')">
                                    <button class="btn-white mr-2" @click="selectProductImage()" v-if="!isProductDuplicate(productItem)">
                                        <img src="@/assets/icons/upload.svg" alt="" width="12px" height="12px">
                                        <span class="ml-1">Re-upload</span>
                                    </button>

                                    <button class="btn-white" @click="removeProductImage()" v-if="!isProductDuplicate(productItem)">
                                        <img src="@/assets/icons/deleteIcon.svg" alt="">
                                    </button>
                                </div>  
                            </div>
                        
                            <div class="product-customs-duty">
                                <p class="title-duty">Customs Duty Info</p>

                                <div class="inputs-container country-info mb-2">
                                    <div class="product-country-from">
                                        <p class="product-title">COUNTRY FROM</p>
                                        <v-combobox
                                            class="text-fields select-items combobox"
                                            v-model="productItem.country_from"
                                            :items="countries"
                                            item-text="name"
                                            item-value="id"
                                            :disabled="getCountriesLoading || isProductDuplicate(productItem)"
                                            :placeholder="getCountriesLoading ? 'Fetching countries...' : 'Select country from'"
                                            outlined
                                            hide-details="auto"
                                            single-line
                                            :class="(productItem.country_from === '' || productItem.country_from === null) ? '' : 'not-empty'"
                                            :menu-props="{ contentClass: 'country-from-lists' }">                                           

                                            <template v-slot:selection>
                                                <country-flag 
                                                    :country="getFlag(productItem.country_from)"
                                                    :v-if="getFlag(productItem.country_from) !== 'n/a'"/>

                                                <span style="color: #4a4a4a;" class="country-name"> {{ productItem.country_from }}</span>
                                            </template>

                                            <template v-slot:item="{ item }">
                                                <country-flag :country="getFlag(item)" />
                                                <span>{{ item }}</span>
                                            </template>
                                        </v-combobox>
                                    </div>

                                    <div class="product-country-to">
                                        <p class="product-title">COUNTRY TO</p>
                                        <v-combobox
                                            class="text-fields select-items combobox"
                                            v-model="productItem.country_to"
                                            :items="countries"
                                            item-text="name"
                                            item-value="id"
                                            :disabled="getCountriesLoading || isProductDuplicate(productItem)"
                                            :placeholder="getCountriesLoading ? 'Fetching countries...' : 'Select country to'"
                                            outlined
                                            hide-details="auto"
                                            single-line
                                            :class="(productItem.country_to === '' || productItem.country_to === null) ? '' : 'not-empty'"
                                            :menu-props="{ contentClass: 'country-from-lists' }">

                                            <template v-slot:selection>
                                                <country-flag 
                                                    :country="getFlag(productItem.country_to)"
                                                    :v-if="getFlag(productItem.country_to) !== 'n/a'"/>

                                                <span style="color: #4a4a4a;" class="country-name"> {{ productItem.country_to }}</span>
                                            </template>

                                            <template v-slot:item="{ item }">
                                                <country-flag :country="getFlag(item)" />
                                                <span>{{ item }}</span>
                                            </template>
                                        </v-combobox>
                                    </div>
                                </div>

                                <div class="product-notes mb-2">
                                    <p class="product-title">PRODUCT CLASSIFICATION DESCRIPTION</p>
                                    <v-textarea
                                        height="100"
                                        class="text-fields description"
                                        outlined
                                        name="input-7-4"
                                        placeholder="Add any links to the product or additional items that can help us better classify your item..."
                                        v-model="productItem.product_classification_description"
                                        :disabled="isProductDuplicate(productItem)">
                                    </v-textarea>
                                </div>
                                
                                <div class="inputs-container country-info">
                                    <div class="classification-code">
                                        <p class="product-title">PRIMARY CLASSIFICATION</p>
                                        <v-text-field
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            dense
                                            class="text-fields"
                                            placeholder="Enter classification"
                                            outlined
                                            type="text"
                                            v-model="productItem.classification_code"
                                            hide-details="auto"
                                            :disabled="(productItem.userClassification !== 1 && !productItem.userClassification) || isProductDuplicate(productItem)">
                                        </v-text-field>
                                    </div>

                                    <div class="classification-code">
                                        <p class="product-title">ADDITIONAL CLASSIFICATION <span>(Optional)</span></p>
                                        <v-text-field
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            dense
                                            class="text-fields"
                                            placeholder="Enter classification"
                                            outlined
                                            type="text"
                                            v-model="productItem.additional_classification_code"
                                            hide-details="auto"
                                            :disabled="(productItem.userClassification !== 1 && !productItem.userClassification) || isProductDuplicate(productItem)">
                                        </v-text-field>
                                    </div>                                                                  
                                    
                                    <div class="duty-rate">
                                        <p class="product-title">DUTY RATE</p>
                                        <vuetify-money
                                            :disabled="(productItem.userClassification !== 1 && !productItem.userClassification) || isProductDuplicate(productItem)"
                                            background-color="white"
                                            dense
                                            hide-details="auto"
                                            placeholder="0.00"
                                            outlined
                                            :options="{ 
                                                locale: 'en-US',
                                                length: 11,
                                                precision: 2,
                                                suffix: '%',
                                            }"
                                            type="number"
                                            v-model="productItem.duty_rate"
                                            :properties="{
                                                height:'40px',
                                                color:'#002F44',
                                                class:'text-fields'
                                            }"
                                        />                                    
                                    </div>
                                </div>  

                                <div class="classification-info-default" v-if="productItem.userClassification !== 1 &&  !productItem.userClassification">
                                    <p class="classification-info mt-1">
                                        Shifl will classify this product, If we have any questions we will reach out to you, once a classification is added you will receive an email.
                                    </p>
                                </div>

                                <v-checkbox
                                    class="classification-checkbox"
                                    color="#819FB2"
                                    :label="`Classify Myself`"
                                    hide-details="auto"
                                    v-model="productItem.userClassification"
                                    @change="userClassify(productItem.userClassification)"
                                    :disabled="isProductDuplicate(productItem)"
                                ></v-checkbox>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="product-actions">
                <button class="btn-blue" @click.stop="saveProduct" 
                    :disabled="loadingOnce || loadingAndAddAnother || dialogHasErrorChecking">

                    <span v-if="editedIndex === -1">
                        <span v-if="!loadingOnce">Add Product</span>
                        <span v-if="loadingOnce">Adding...</span>
                    </span>

                    <span v-if="editedIndex > -1 && !productItem.isDuplicate">
                        <span v-if="!loadingOnce">Save Edits</span>
                        <span v-if="loadingOnce">Saving...</span>
                    </span>                                      

                    <span v-if="editedIndex > -1 && productItem.isDuplicate">
                        <span v-if="!loadingOnce">Duplicate Product</span>
                        <span v-if="loadingOnce">Duplicating...</span>
                    </span>
                </button>

                <button class="btn-white" 
                    v-if="editedIndex === -1" @click.stop="saveAndAddProduct" 
                    :disabled="loadingOnce || loadingAndAddAnother">
                    <span>
                        <span v-if="!loadingAndAddAnother">Save & Add Another</span>
                        <span v-if="loadingAndAddAnother">Saving...</span>
                    </span>
                </button>

                <button class="btn-white" @click="close" v-if="!isMobile"
                    :disabled="loadingOnce || loadingAndAddAnother">
                    Cancel
                </button>
            </v-card-actions>            
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import globalMethods from '../../../utils/globalMethods';
import countriesDetails from '../../../helpers/flags.json'
import _ from 'lodash'

export default {
    name: 'ProductAddDialog',
    props: ['dialog', 'editedIndex', 'categoryLists', 'isMobile',
            'editedItem', 'defaultItem', 'isInventoryPage', 'isWarehouse3PL', 
            'isWarehouse3PLProvider', 'searchVal', 'searchFromInventory', 'productsData', 'inboundItems', 'isBookingShipmentDialog', 'shipmentData'],
    components: {},
    data: () => ({
        categoryListData:[],
        current_page_is:null,
        lastDataCheck:[],
        lastDataCheckDisable:false,
        valid: true,
        isCustom: 'generated',
        product: {
            create: {
                image: '',
                imageFile: null
            },
            edit: {
                image: '',
                imageFile: null
            }
        },
        fileReader: null,
        fileReaderUpdate: null,
        isFileUploadSuccess: false,
        showRemoveButton: false,
        loadingOnce: false,
        loadingAndAddAnother: false,
        rules: [
            (v) => !!v || "Input is required."
        ],
        productNameRules: [
            (v) => !!v || "Input is required.",
            (v) => /^.{1,100}$/.test(v) || 'Only 100 maximum characters allowed.'
        ],
        inEachCartonRules: [
            (v) => !!v || "Input is required.",
            v => (parseFloat(v) > 0) || 'Value should not be 0.'
        ],
        customSkuRules: [
            (v) => !!v || "Input is required.",
            v => (v || '' ).length <= 50 || 'Only 50 maximum characters allowed.'
        ],
        contactListsSelected: [],
        searchContactName: '',
        contactItemsCopy: [],
        isContactError: false,
        contactSelectedExist: false,
        cartonDimensions: {
            l: '',
            w: '',
            h: '',
            uom: 'cm'
        },
        // for contacts field
        allContacts: [],
        allContactsCopy: []
    }),
    computed: {
        ...mapGetters({
			getUpdatedProduct: 'products/getUpdatedProduct',
            getCurrentSelectedProduct: 'products/getCurrentSelectedProduct',
            getUser: 'getUser',
            getCountries: 'warehouse/getCountries',
			getCountriesLoading: 'warehouse/getCountriesLoading',
            getCategories: 'category/getCategories',
            getCategoriesLoading:'category/getCategoriesLoading',
            getFlags: 'products/getFlags',
            getSearchedProducts: 'products/getSearchedProducts',
            poBaseUrlState: 'products/poBaseUrlState',
            getProductInventorySearched: 'productInventories/getProductInventorySearched',
            getProductInventory: 'productInventories/getProductInventory',
            getProductInventory3pl: 'productInventories/getProductInventory3pl',            
			getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            getHas3PLProviderWarehouse: 'warehouseCustomers/getHas3PLProviderWarehouse',
            getProductContacts: 'products/getProductContacts',
            getProductContactsLoading: 'products/getProductContactsLoading'
        }),
        formTitle () {
            return this.editedIndex === -1 ? 'Add Product' : 'Edit Product'
        },
        dialogAdd: {
            get() {
                return this.dialog
            },
            set(value) {
                this.$emit('update:dialog', value)
            }
        },
        productItem: {
            get() {
                return this.editedItem
            },
            set(value) {
                this.$emit('update:editedItem', value)
            }
        },
        countries() {
            let data = []

            if (countriesDetails !== 'undefined' && countriesDetails.length > 0) {
                data = countriesDetails.map(v => {
                    return v.englishShortName
                })
            }

            return data
        },
        flags() {
            return typeof this.getFlags !== 'undefined' && this.getFlags !== null ? this.getFlags : {}
        },
        warehouseCustomerLists() {
            let data = []

            if (typeof this.getWarehouseCustomersDropdown !== "undefined" && this.getWarehouseCustomersDropdown !== null) {
                if (typeof this.getWarehouseCustomersDropdown.data !== "undefined" &&
                    this.getWarehouseCustomersDropdown.data.length !== "undefined") {
                    data = this.getWarehouseCustomersDropdown.data
                }
            }

            return data
        },
        productCreateTemplate() {
            let { carton_dimensions, unit_dimensions, unit_weight, is_for_classification_code, warehouse_customer_id, preferred_unit_quantity, category_id, userClassification, classification_code, duty_rate, contacts, ...otherItems } = this.productItem

            carton_dimensions = JSON.stringify(this.cartonDimensions)
            unit_dimensions = JSON.stringify(unit_dimensions)
            unit_weight = JSON.stringify(unit_weight)
            is_for_classification_code = userClassification ? 0 : 1
            duty_rate = !duty_rate ? 0 : parseFloat(duty_rate)

            if (is_for_classification_code === 1) {
                classification_code = ''
            }

            preferred_unit_quantity = (typeof preferred_unit_quantity !== 'undefined' && preferred_unit_quantity !== null && preferred_unit_quantity !== '') ? preferred_unit_quantity : 0

            warehouse_customer_id = warehouse_customer_id !== null && warehouse_customer_id !== undefined ? warehouse_customer_id : ''
            category_id = category_id !== null && category_id !== undefined ? category_id : ''
            contacts = this.contactListsSelected.length > 0 ? this.contactListsSelected.map(v => {
                if (typeof v.contact_id !== 'undefined') {
                    return { id: v.contact_id, type: v.contact_type }
                } else {
                    return { id: v.id, type: v.contact_type }
                }
            }) : []
            
            let passedData = { 
                customer_id: (typeof this.getUser=='string') ? JSON.parse(this.getUser).default_customer_id : '',
                category_id,
                warehouse_customer_id,
                carton_dimensions,
                unit_dimensions,
                unit_weight,
                is_for_classification_code,
                preferred_unit_quantity,
                userClassification,
                classification_code,
                duty_rate,
                contacts: JSON.stringify(contacts),
                ...otherItems
            }

            return passedData
        },
        isSystemGeneratedValueCheck() {
            let value = false
            if (this.editedIndex === -1) {
                if (this.isCustom == 'generated') {
                    value = true
                } else {
                    value = false
                }
            } else {
                if (this.productItem.is_system_generated == 1) {
                    value = true
                } else {
                    value = false
                }
            }
            return value
        },
        contactItems() {
            let data = []

            if (typeof this.getProductContacts !== "undefined" && this.getProductContacts !== null) {
                if (typeof this.getProductContacts.results !== "undefined" &&
                    this.getProductContacts.results.length !== "undefined") {
                    data = this.getProductContacts.results
                }
            }

            return data
        },
        dialogHasErrorChecking() {
            let isError = false

            if (this.productItem.isDuplicate) {
                if (this.contactListsSelected.length === 0) {
                    isError = true
                } else {
                    if (this.isContactError) {
                        isError = true
                    } else if (this.contactSelectedExist) {
                        isError = true
                    } else {
                        isError = false
                    }
                }
            }

            return isError
        }
    },
    methods: {
        ...mapActions({
			fetchProducts: 'products/fetchProducts',
			createProducts: 'products/createProducts',
            updateProducts: 'products/updateProducts',
            fetchProductInventories: 'productInventories/fetchProductInventories',
            fetchCategories: 'category/fetchCategories',
            fetchProductInventories3pl: 'productInventories/fetchProductInventories3pl',
            setAllInboundProductsLists: 'inbound/setAllInboundProductsLists',
            fetchSearchedProducts: 'products/fetchSearchedProducts',
			setSearchedProductsLoading: 'products/setSearchedProductsLoading',
            setSearchedInventoryProductsLoading: 'productInventories/setSearchedInventoryProductsLoading',
            fetchProductInventoriesSearched: 'productInventories/fetchProductInventoriesSearched',
            duplicateProducts: 'products/duplicateProducts'
        }),
        ...globalMethods,
        selectProductImage() {
            this.$refs.product_image_reference.click()
        },
        readFile() {
            let file = this.$refs.product_image_reference.files[0]
            this.fileReader.readAsDataURL(file)
            
            this.fileReader.onload = () => {
                if (this.editedIndex > -1) {
                    this.product.edit.imageFile = file
                    this.product.edit.image = this.fileReader.result
                } else {
                    this.product.create.imageFile = file
                    this.product.create.image = this.fileReader.result   
                }
            }

            this.fileReader.onerror = () => {
                console.log('An error occured.')
                console.log(this.fileReader.error);
            }
        },        
        removeProductImage() {
            this.$refs.product_image_reference.value = ''
            this.product.create.image = ''
            this.product.create.imageFile = null
            this.productItem.imageCreate = null
            this.productItem.image = null
            this.product.edit.image = ''
            this.product.edit.imageFile = null
        },
        checkImageFileType(image) {
            let isValid = true

            if (image.type == 'image/gif' || image.type == 'image/svg+xml') {
                isValid = false
                this.notificationError('The image must be a file of type: jpg, png, jpeg.')
            }

            return isValid
        },
        async saveProduct() {
            let { image, units_per_carton, ...otherItems } = this.productCreateTemplate
           
            this.$refs.form.validate()

            if (this.$refs.form.validate()) {
                if (this.editedIndex > -1) { // product edit
                    if (this.product.edit.image !== '') {
                        this.checkImageFileType(this.product.edit.imageFile)
                        
                        if (this.checkImageFileType(this.product.edit.imageFile)) {
                            image = this.product.edit.imageFile
                        }
                    } else {
                        image = null
                    }

                    let passedData = { image, units_per_carton, ...otherItems }

                    if ((this.productItem.image !== null && this.productItem.image !== '') || 
                        (image !== null && image !== '')) {
                        Object.keys(passedData).map((key) => {
                            if (key !== 'image') {
                                if (passedData[key] === null || passedData[key] === 'null') {
                                    passedData[key] = ""
                                }
                            }
                        })
                    } else {
                        image = null
                        units_per_carton = units_per_carton === null || units_per_carton === 'null' ? '' : units_per_carton
                    }

                    if (!this.productItem.isDuplicate) {
                        if (!this.contactSelectedExist) {
                            this.saveProductApiUpdate(passedData)
                        }  
                    } else {
                        if (this.contactListsSelected.length === 0) {
                            this.isContactError = true
                        } else {
                            this.isContactError = false

                            if (!this.contactSelectedExist) {
                                this.saveProductApiDuplicate(passedData)
                            }                            
                        }
                    }

                } else { // product create
                    if (this.product.create.image !== '') {
                        this.checkImageFileType(this.product.create.imageFile)
                        
                        if (this.checkImageFileType(this.product.create.imageFile)) {
                            image = this.product.create.imageFile
                        }
                    } else {
                        image = null
                    }

                    let passedData = { image, units_per_carton, ...otherItems }

                    if (image !== null && image !== '') {
                        Object.keys(passedData).map((key) => {
                            if (passedData[key] === null || passedData[key] === 'null') {
                                passedData[key] = ""
                            }
                        })
                    } else {
                        image = null
                        units_per_carton = units_per_carton === null || units_per_carton === 'null' ? '' : units_per_carton
                    }

                    this.saveProductApiCreate(passedData)
                } 
            }   
        },
        saveAndAddProduct() {
            let { image, units_per_carton, ...otherItems } = this.productCreateTemplate

            this.$refs.form.validate()
            
            if (this.$refs.form.validate()) {
                if (this.editedIndex > -1) { //check product edit
                    if (this.product.edit.image !== '') {
                        this.checkImageFileType(this.product.edit.imageFile)
                        
                        if (this.checkImageFileType(this.product.edit.imageFile)) {
                            image = this.product.edit.imageFile
                        }
                    } else {
                        image = null
                    }

                    let passedData = { image, units_per_carton, ...otherItems }

                    if ((this.productItem.image !== null && this.productItem.image !== '') || 
                        (image !== null && image !== '')) {
                        Object.keys(passedData).map((key) => {
                            if (key !== 'image') {
                                if (passedData[key] === null || passedData[key] === 'null') {
                                    passedData[key] = ""
                                }
                            }
                        })
                    } else {
                        image = null
                        units_per_carton = units_per_carton === null || units_per_carton === 'null' ? '' : units_per_carton
                    }

                    this.saveUpdateAndAdd(passedData)

                } else { //check product create
                    if (this.product.create.image !== '') {
                        this.checkImageFileType(this.product.create.imageFile)
                        
                        if (this.checkImageFileType(this.product.create.imageFile)) {
                            image = this.product.create.imageFile
                        }
                    } else {
                        image = null
                    }

                    let passedData = { image, units_per_carton, ...otherItems }

                    if (image !== null && image !== '') {
                        Object.keys(passedData).map((key) => {
                            if (passedData[key] === null || passedData[key] === 'null') {
                                passedData[key] = ""
                            }
                        })
                    } else {
                        image = null
                        units_per_carton = units_per_carton === null || units_per_carton === 'null' ? '' : units_per_carton
                    }

                    this.saveCreateAndAdd(passedData)
                }
            }
        },
        close() {
            this.$emit('close')
            this.userClassification = 0
            this.isCustom = 'generated'
            this.$refs.form.resetValidation()
            this.product = {
                create: {
                    image: '',
                    imageFile: null
                },
                edit: {
                    image: '',
                    imageFile: null
                }
            }
            this.contactItems.forEach((item) => { return item.class = '' , item.className = '' })
            this.allContacts = this.contactItems
            this.allContactsCopy = this.contactItems
            this.searchContactName = ''
            this.contactListsSelected = []
        },
        async fetchProductSearchedAPI(page) {
            let passedData = {
                method: "get",
                url: 'https://po.shifl.com/api/products',
                params: {
                    search: this.searchVal,
                    page
                }
            }

            try {
                passedData.tab = 'products'
                await this.fetchSearchedProducts(passedData)

                if (typeof this.productsData !== 'undefined' && this.productsData.length === 0 && page !== 1) {
					passedData.params.page = page - 1
					await this.fetchSearchedProducts(passedData)
				}
                await this.fetchProducts(1)
            } catch(e) {
                this.notificationError(e)
                this.setSearchedProductsLoading(false)
                console.log(e, 'Search error')
            }
        },
        async fetchProductSearchedAPIFromInventory(page, warehouse_id) {
            let passedData = {
                method: "GET",
                url: '',
                params: {
                    search: this.searchFromInventory,
                    page
                }
            }

            if (!this.isWarehouse3PL) {
                passedData.url = `${this.poBaseUrlState}/warehouse/${warehouse_id}/products`
            } else {
                passedData.url = `${this.poBaseUrlState}/warehouse-3pl/${warehouse_id}/products`
            }

            passedData.tab = 'all'

            try {
                if (passedData.url !== '') {
                    await this.fetchProductInventoriesSearched(passedData)
                    
                    if (typeof this.productsData !== 'undefined' && this.productsData.length === 0 && page !== 1) {
                        passedData.params.page = page - 1
                        await this.fetchProductInventoriesSearched(passedData)
                    }
                }
            } catch(e) {
                this.setSearchedInventoryProductsLoading(false)
                console.log(e, 'Search error');
            }
        },
        async saveProductApiDuplicate(passedData) {
            this.loadingOnce = true
            passedData.sys_gen = (this.isCustom !== 'generated') ? '' : true    
            
            let passedDataKeys = Object.keys(passedData)
            let newPassedData = {}

            if (passedDataKeys.length > 0) {
                passedDataKeys.map(pdk => {
                    if (pdk !== 'parentItem') 
                    newPassedData[pdk] = passedData[pdk]       
                })
            }

            passedData = newPassedData

            try {
                await this.duplicateProducts(passedData)
                this.loadingOnce = false
                this.notificationMessage('Product has been duplicated!')
                this.close()

                let storePagination = this.$store.state.products.products
                let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                await this.fetchProducts(page)

                this.$store.dispatch("products/setReset", true)
            } catch(e) {
                this.loadingOnce = false
                console.log(e);
                this.notificationError(e)
            }
        },
        async saveProductApiCreate(passedData) {
            this.loadingOnce = true

            passedData.sys_gen = (this.isCustom !== 'generated') ? '' : true                   

            if (this.isCustom === 'generated') {
                let passedDataKeys = Object.keys(passedData)
                let newPassedData = {}

                if (passedDataKeys.length > 0) {
                    passedDataKeys.map(pdk => {
                        if (pdk !== 'sku') 
                        newPassedData[pdk] = passedData[pdk]       
                    })
                }

                passedData = newPassedData
            }

            try {
                await this.createProducts(passedData)
                this.loadingOnce = false
                this.notificationMessage('Product has been created!')

                if(!this.isBookingShipmentDialog){
                    if (!this.isInventoryPage) {
                        let storePagination = this.$store.state.products.products
                        this.close()

                        if (typeof this.searchVal !== 'undefined' && this.searchVal !== '') {
                            if (typeof this.getSearchedProducts !== 'undefined') {
                                let searchedPage = typeof this.getSearchedProducts.current_page !== 'undefined' ?
                                    this.getSearchedProducts.current_page : 1
                                                    
                                await this.fetchProductSearchedAPI(searchedPage)
                            }
                        } else {
                            let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                            await this.fetchProducts(page)
                        }
                    } else {                    
                        this.close()

                        if (!this.isWarehouse3PLProvider) {
                            await this.$emit('callInboundProductsFor3PL')
                        } else {
                            if (this.inboundItems.warehouse_customer_id !== '' && this.inboundItems.warehouse_customer_id !== null) {
                                await this.$emit('callWarehouseCustomerProducts', this.inboundItems)
                            } else {
                                await this.$emit('callInboundProductsFor3PL')
                            }
                        }
                    }
                }else{
                    if (this.shipmentData.role === "shipper") {
                        this.$store.dispatch("salesOrders/fetchSuppliersSku",this.shipmentData.consignee);
                    } else {
                        this.$store.dispatch("po/fetchVendorSku", this.shipmentData.shipper);
                    }
                    this.close()
                }

                this.$store.dispatch("products/setReset", true)
            } catch(e) {
                this.loadingOnce = false
                this.notificationError(e)
            }
        },
        async saveProductApiUpdate(passedData) {
            this.loadingOnce = true
          
            if (passedData.image == null) {
                let newPassedData = {}
                let getPassedDataKeys = Object.keys(passedData)
                if (getPassedDataKeys.length > 0) {
                    getPassedDataKeys.map(gdk => {
                        if (gdk !== 'image' && gdk !== 'imageCreate' && gdk !== 'parentItem')
                            newPassedData[gdk] = passedData[gdk]
                        else {
                            if (gdk === 'imageCreate') {
                                if (passedData[gdk] == null) {
                                    newPassedData['image'] = null
                                }
                            }
                        }
                    })

                    passedData = newPassedData
                }
            } else {
                let newPassedData = {}
                let getPassedDataKeys = Object.keys(passedData)
                if (getPassedDataKeys.length > 0) {
                    getPassedDataKeys.map(gdk => {
                        if (gdk !== 'parentItem' && gdk !== 'child_products')
                            newPassedData[gdk] = passedData[gdk]
                    })

                    passedData = newPassedData
                }
            }

            passedData.prod_id = passedData.id
            passedData._method = 'PUT'

            try {
                await this.updateProducts(passedData)
                this.loadingOnce = false
                this.notificationMessage('Product has been updated!')

                if (!this.isInventoryPage) {
                    let storePagination = this.$store.state.products.products
                    this.close()

                    if (typeof this.searchVal !== 'undefined' && this.searchVal !== '') {
                        if (typeof this.getSearchedProducts !== 'undefined') {
                            let searchedPage = typeof this.getSearchedProducts.current_page !== 'undefined' ?
                                this.getSearchedProducts.current_page : 1
                                                
                            await this.fetchProductSearchedAPI(searchedPage)
                        }
                    } else {
                        let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                        await this.fetchProducts(page)
                    }
                } else {
                    // fetch products inventories
                    let data = { id: this.productItem.warehouse_id, page: 1 }
                    this.close()

                    // dynamically check if current page is 1 or not
                    if (!this.isWarehouse3PL) {
                        data.page = typeof this.getProductInventory !== 'undefined' && 
                            this.getProductInventory.current_page !== 'undefined' ? 
                            this.getProductInventory.current_page : 1
                    } else {
                        data.page = typeof this.getProductInventory3pl !== 'undefined' && 
                            this.getProductInventory3pl.current_page !== 'undefined' ? 
                            this.getProductInventory3pl.current_page : 1
                    }

                    if (typeof this.searchFromInventory !== 'undefined' && this.searchFromInventory !== '') {
                        if (typeof this.getProductInventorySearched !== 'undefined') {
                            let searchedPage = typeof this.getProductInventorySearched.current_page !== 'undefined' ?
                                this.getProductInventorySearched.current_page : 1

                            await this.fetchProductSearchedAPIFromInventory(searchedPage, data.id)
                            // setting page back to 1 to show default products on page 1
                            data.page = 1
                        }
                    }
                    
                    if (!this.isWarehouse3PL) {
                        await this.fetchProductInventories(data)
                    } else {
                        await this.fetchProductInventories3pl(data)
                    }
                }
                
                this.$store.dispatch("products/setReset", true)
            } catch(e) {
                this.loadingOnce = false
                this.notificationError(e)
            }
        },
        async saveCreateAndAdd(passedData) {
            this.loadingAndAddAnother = true
            passedData.sys_gen = (this.isCustom !== 'generated') ? '' : true

            if (this.isCustom === 'generated') {
                let passedDataKeys = Object.keys(passedData)
                let newPassedData = {}

                if (passedDataKeys.length > 0) {
                    passedDataKeys.map(pdk => {
                        if ( pdk!=='sku')
                            newPassedData[pdk] = passedData[pdk]
                    })
                }

                passedData = newPassedData
            }
            
            passedData.customer_id = (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : ''
            passedData.preferred_unit_quantity = (typeof passedData.preferred_unit_quantity !== 'undefined' && passedData.preferred_unit_quantity !== null && passedData.preferred_unit_quantity !== '') ? passedData.preferred_unit_quantity : 0
            passedData.warehouse_customer_id = passedData.warehouse_customer_id !== null && passedData.warehouse_customer_id !== undefined ? passedData.warehouse_customer_id : ''
            passedData.category_id = passedData.category_id !== null && passedData.category_id !== undefined ? passedData.category_id : ''
            
            try {
                await this.createProducts(passedData)
                this.loadingAndAddAnother = false
                this.notificationMessage('Product has been created!')

                if (!this.isInventoryPage) {
                    let storePagination = this.$store.state.products.products

                    if (typeof this.searchVal !== 'undefined' && this.searchVal !== '') {
                        if (typeof this.getSearchedProducts !== 'undefined') {
                            let searchedPage = typeof this.getSearchedProducts.current_page !== 'undefined' ?
                                this.getSearchedProducts.current_page : 1
                                                
                            await this.fetchProductSearchedAPI(searchedPage)
                        }
                    } else {
                        let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                        await this.fetchProducts(page)
                    }
                    this.setToDefault()
                } else {
                    // await this.$emit('callInboundProductsFor3PL')
                    // this.setToDefault()
                    this.setToDefault()
                    if (!this.isWarehouse3PLProvider) {
                        await this.$emit('callInboundProductsFor3PL')
                    } else {
                        if (this.inboundItems.warehouse_customer_id !== '' && this.inboundItems.warehouse_customer_id !== null) {
                            await this.$emit('callWarehouseCustomerProducts', this.inboundItems)
                        } else {
                            await this.$emit('callInboundProductsFor3PL')
                        }
                    }
                }

                this.$store.dispatch("products/setReset", true)
            } catch(e) {
                this.loadingAndAddAnother = false
                this.notificationError(e)
            }
        },
        async saveUpdateAndAdd(passedData) {
            this.loadingAndAddAnother = true
    
            if (passedData.image==null) {
                let newPassedData = {}
                let getPassedDataKeys = Object.keys(passedData)
                if (getPassedDataKeys.length > 0) {
                    getPassedDataKeys.map(gdk => {
                        if (gdk!=='image')
                            newPassedData[gdk] = passedData[gdk]
                    })

                    passedData = newPassedData
                }
            }

            passedData.preferred_unit_quantity = passedData.preferred_unit_quantity !== null ? passedData.preferred_unit_quantity : 0
            passedData.prod_id = passedData.id
            passedData._method = 'PUT'
            passedData.customer_id = (typeof this.getUser=='string') ? JSON.parse(this.getUser).customer.id : ''

            try {					
                await this.updateProducts(passedData)
                this.loadingAndAddAnother = false

                let storePagination = this.$store.state.products.products
                let page = typeof storePagination.current_page !== 'undefined' ? storePagination.current_page : 1
                this.fetchProducts(page)

                this.$store.dispatch("products/setReset", true)
                this.notificationMessage('Product has been updated!')
                this.setToDefault()
            } catch(e) {
                this.loadingAndAddAnother = false
                this.notificationError(e)
            }
        },
        setToDefault() {
            this.$emit('setToDefault')
            this.userClassification = 0
            this.isCustom = 'generated'
            this.product = {
                create: {
                    image: '',
                    imageFile: null
                },
                edit: {
                    image: '',
                    imageFile: null
                }
            }
            this.contactItems.forEach((item) => { return item.class = '' , item.className = '' })
            this.allContacts = this.contactItems
            this.allContactsCopy = this.contactItems
            this.searchContactName = ''
            this.contactListsSelected = []
        },
        handlePrice(price) {
            if (price !== '') {                
                let getPrice = parseInt(price)
                
                if (getPrice == 0) {
                    this.notificationErrorCustom('Please enter price higher than 0.')
                }
            }
        },
        inputRestrictSpecialChar(e) {
            if (/^\W$/.test(e.key)) {
                if (e.key !== '-' && e.key !== ' ') {
                    e.preventDefault();
                }
            }
        },
        userClassify(val) {
            if (!val) {
                this.productItem.classification_code = null
                this.productItem.additional_classification_code = null
                this.productItem.duty_rate = 0
                this.productItem.is_for_classification_code = 1
            } else {
                this.productItem.is_for_classification_code = 0
                this.productItem.classification_code = ''
                this.productItem.additional_classification_code = ''
            }
        },
        getFlag(selectedCountry) {
            let code = ''

            if (selectedCountry !== 'undefined' && selectedCountry !== null && selectedCountry !== '') {
                let countryFlag = _.find(countriesDetails, (e) => e.englishShortName === selectedCountry )
                
                if (typeof countryFlag !== 'undefined') {
                    code = countryFlag.alpha2Code
                } else {
                    code = 'no-code'
                }                
            }

            return code
        },
        getCategoryPrefix(item) {
            if (typeof item !== 'undefined' && item !== null && item !== '') {
                return item.toString() + '-'
            } else {
                return ' '
            }
        },
        restrictValues(e) {
            if(e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187) {
                e.preventDefault()
            }
        },
        checkIfHasChildProducts(product) {
            if (product.parent_id === null) {
                if (typeof product.child_products !== 'undefined' && product.child_products.length !== 'undefined') {
                    if (product.child_products.length === 0) 
                        return true
                    else {
                        if (product.isDuplicate) 
                            return true
                        else 
                            return false                                                               
                    }
                } else
                    return false 
            } else {
                if (this.editedIndex > -1) {
                    product.isDuplicateEdit = true
                }

                return true
            }
        },
        isProductDuplicate(product) {
            if (product.isDuplicate || product.isDuplicateEdit) {
                return true
            } else {
                return false
            }
        },
        // 
        handleSearchContact() {
            if (this.searchContactName !== '') {
                this.allContacts = this.allContactsCopy.filter((c) => {
                    return c.company_name.toLowerCase().indexOf(this.searchContactName.toLowerCase()) > -1
                })

                if (this.allContacts !== this.allContactsCopy) {
                    let hideItems = this.allContactsCopy.filter(e => !this.allContacts.some(o => o.company_name === e.company_name))
                    let showItems = this.allContactsCopy.filter(e => this.allContacts.some(o => o.company_name === e.company_name))
                    
                    this.allContactsCopy.map(v => {
                        hideItems.map(a => { if (v.id === a.id) { v.class = 'hide-product-contact-list' } })
                        showItems.map(a => { if (v.id === a.id) { v.class = '' } })
                    })
                }
            } else {
                this.allContacts = this.contactItems
                this.allContactsCopy = this.contactItems
                this.allContactsCopy.map(a => { a.class = '' })
            }
        },
        removeChip(item) {
            let indexLodash = -1

            if (this.editedIndex === -1) {
                indexLodash = _.findIndex(this.contactListsSelected, e => (e.id == item.id))
            } else if (this.editedIndex > -1) {
                indexLodash = _.findIndex(this.contactListsSelected, e => {
                    if (typeof e.contact_id !== 'undefined') {
                        return e.contact_id == item.contact_id
                    } else {
                        return e.id == item.id
                    }
                })
            }
            
            if (indexLodash > -1) {
				this.contactListsSelected.splice(indexLodash, 1)
            }
        },
        ifIsContactSelected(item) {
            item.className = ""

            // for parent duplicate
            if (this.editedIndex > -1) {
                if (this.productItem.isDuplicate || this.productItem.isDuplicateEdit) {
                    if (typeof this.productItem.parentItem === 'undefined' || this.productItem.parentItem === 'undefined') {
                        this.checkProductItemContactsFunctionParent(item)
                    } else {
                        this.checkProductItemContactsFunctionChild(item)
                    }
                }
            } else {
                item.className = ""
                this.contactSelectedExist = false
            }
        },
        checkProductItemContactsFunctionParent(item) {
            if (typeof this.productItem.child_products !== 'undefined' && 
                this.productItem.child_products !== 'undefined' &&
                this.productItem.child_products.length > 0) {
                let child_products = this.productItem.child_products

                let findParent = _.find(child_products, product => product.product_contact.some(prod => prod.contact_id === item.id))

                if (typeof findParent !== 'undefined' && findParent !== undefined) {
                    this.contactSelectedExist = true
                    let parentsContact = findParent.product_contact

                    let findChildData = _.find(parentsContact, product => (product.contact_id === item.id))
                    
                    if (typeof findChildData !== 'undefined' && findChildData !== undefined) {
                        item.className = "error-child"
                    } else {
                        item.className = ""
                    }
                } else {
                    this.contactSelectedExist = false
                }
            } else if (typeof this.productItem.duplicate_contact !== 'undefined' && 
                this.productItem.duplicate_contact.length > 0 && 
                typeof this.productItem.child_products !== 'undefined' && 
                this.productItem.child_products !== 'undefined' &&
                this.productItem.child_products.length === 0) {

                let contacts = this.productItem.duplicate_contact

                let findIndexData = _.find(contacts, e => (e.contact_id === item.id))
                if (typeof findIndexData !== 'undefined' && findIndexData !== undefined) {
                    item.className = "error-child"
                } else {
                    item.className = ""
                }
            } else {
                item.className = ''
                this.contactSelectedExist = false
            }
        },
        checkProductItemContactsFunctionChild(item) {
            let productItemParent = this.productItem.parentItem

            if (typeof productItemParent !== 'undefined') {
                let productItemChildProducts = productItemParent.child_products

                if (typeof productItemChildProducts !== 'undefined' && productItemChildProducts !== 'undefined' &&
                    productItemChildProducts.length > 0) {

                    let findParent = _.find(productItemChildProducts, product => product.product_contact.some(prod => prod.contact_id === item.id))

                    if (typeof findParent !== 'undefined' && findParent !== undefined) {
                        this.contactSelectedExist = true
                        let parentsContact = findParent.product_contact

                        let findChildData = _.find(parentsContact, product => (product.contact_id === item.id))
                        
                        if (typeof findChildData !== 'undefined' && findChildData !== undefined) {
                            item.className = "error-child"
                        } else {
                            item.className = ""
                        }
                    } else {
                        this.contactSelectedExist = false
                    }
                } else {
                    item.className = ''
                    this.contactSelectedExist = false
                }
            }   
        },
        isAllOptionsHidden(items) { // contacts show no available data if has class value
            if (items.length !== 0) {
                let findEmployeeNotHidden = _.find(items, e => e.class === '')
                if (findEmployeeNotHidden !== undefined) {
                    return false
                } else {
                    return true
                }     
            } else {
                return false
            }
        },        
        syncContactLists() {
            if (this.contactItems.length > 0) {
                if (this.allContacts !== this.contactItems) {
                    this.contactItems.forEach((item) => { return item.class = '' , item.className = '' })
                    this.allContacts = _.cloneDeep(this.contactItems)
                    this.allContactsCopy = _.cloneDeep(this.contactItems)
                    this.allContacts = this.allContacts.map((rest)=> ({class: '', ...rest}))
                    this.allContactsCopy = this.allContactsCopy.map((rest)=> ({class: '', ...rest}))
                }
            }
        }
    },
    watch: {
        dialogAdd(value, oldValue) {
            this.syncContactLists()

			if (!value) {
				this.contactListsSelected = []
                this.cartonDimensions = {
                    l: '',
                    w: '',
                    h: '',
                    uom: 'cm'
                }
			} else if (value && !oldValue) {
				if (this.editedIndex == -1) {
					this.contactListsSelected = []
                    this.cartonDimensions = {
                        l: '',
                        w: '',
                        h: '',
                        uom: 'cm'
                    }
				} else {
					let validCompanyNames = []
					if (this.productItem.product_contact !== null &&
						this.productItem.product_contact.length > 0) {
						this.productItem.product_contact.map((v) => {
							validCompanyNames.push({ ...v })
						})
					}

                    this.contactListsSelected = validCompanyNames

                    if (this.productItem.carton_dimensions !== null) {
                        this.cartonDimensions = {
                            l: this.productItem.carton_dimensions.l,
                            w: this.productItem.carton_dimensions.w,
                            h: this.productItem.carton_dimensions.h,
                            uom: this.productItem.carton_dimensions.uom
                        }
					}
				}
			}
        },
        contactItems(value) {
            if (value.length > 0)
                this.syncContactLists()
        },
        contactListsSelected(val) {
            let findIndexData = _.findIndex(val, e => typeof e.className !== 'undefined' && e.className === 'error-child')
            if (findIndexData === -1) {
                this.contactSelectedExist = false
            } else {
                this.contactSelectedExist = true
            }
        },
    },
    async mounted() {
        this.fileReader = new FileReader()
        this.fileReaderUpdate = new FileReader()
        this.contactListsSelected = []
    },
    updated() {
        if (this.editedIndex === -1) {
            if (typeof this.$refs.form !== 'undefined') {
                if (typeof this.$refs.form.resetValidation() !== 'undefined') {
                    this.$refs.form.resetValidation()
                }
            }
        }
    },
}
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/product/productAddDialog.scss';

.country-from-lists {
    .v-list {
        .v-list-item {
            span {
                font-size: 14px !important;

                &.flag {
                    margin-right: 0 !important;
                    margin-bottom: 0 !important
                }
            }
        }
    }
}

.hide-product-contact-list {
    display: none;
}
</style>