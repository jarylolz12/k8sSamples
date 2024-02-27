<template>
    <v-dialog v-model="dialogView" max-width="800px" content-class="product-dialog-view" :retain-focus="false" scrollable>
        <v-card class="dialog-wrapper">
            <v-card-title>
                <span class="headline">
                    <span class="po-num">
                        SKU #<span class="po-num" v-if="editedItem.category_id !== null">{{editedItem.category_id}}-</span>
                        <span class="po-num">{{ editedItem.sku }}</span>
                    </span>

                    <button icon dark class="btn-close" @click="closeView" v-if="isMobile">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </span>

                <div class="header-actions">
                    <button @click.stop="editItem(editedItem, 'edit')" class="btn-blue btn-edit">
                        Edit
                    </button>

                    <button @click.stop="editItem(editedItem, 'copy')" class="btn-white btn-edit ml-2">
                        Duplicate
                    </button>

                    <button @click.stop="deleteItem(editedItem)" class="btn-white btn-delete" 
                        style="color: #4a4a4a !important;">
                        <span v-if="!isMobile">Delete</span>
                        <span v-if="isMobile">
                            <img src="@/assets/icons/deleteIcon.svg" alt="">
                        </span>
                    </button>

                    <button icon dark class="btn-close" @click="closeView" v-if="!isMobile">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </div>
            </v-card-title>

            <v-card-text class="mb-0">
                <div class="view-wrapper">
                    <div class="view-box-info">
                        <div class="mb-3">
                            <p class="item-name mb-1">{{ editedItem.name }}</p>
                            <!-- <p class="item-price">
                                ${{ editedItem.unit_price !== null ? parseFloat(editedItem.unit_price).toFixed(2) : 0 }}
                            </p> -->
                            <p class="product-description mb-1" style="color: #4a4a4a;"> 
                                {{ editedItem.description !== null && editedItem.description !== 'null' && 
                                editedItem.description !== '' ? editedItem.description : '--'}} 
                            </p>
                        </div>

                        <div class="view-box-other-data">
                            <div class="item-info category">
                                <p class="item-title">Category</p>
                                <p class="item-data">
                                    {{ getCategoryName(editedItem.category_id) }}
                                    <span v-if="editedItem.category_id !== null">({{ editedItem.category_id }})</span>
                                </p>
                            </div>

                            <div class="item-info carton">
                                <p class="item-title">Classification Code</p>
                                <p class="item-data">{{ editedItem.classification_code !== null ? editedItem.classification_code : 'N/A' }}</p>
                            </div>

                            <div class="item-info carton">
                                <p class="item-title">Duty Rate</p>
                                <p class="item-data">{{ editedItem.duty_rate !== null ? parseFloat(editedItem.duty_rate).toFixed(2) : 0 }}%</p>
                            </div>

                            <!-- <div class="item-info carton">
                                <p class="item-title">In each carton</p>
                                <p class="item-data">{{ editedItem.units_per_carton }} Units</p>
                            </div> 

                            <div class="item-info dimensions">
                                <p class="item-title">Carton Dimension</p>
                                <div class="item-data-dimensions">
                                    <p>
                                        <span class="item-subtitle">L </span>
                                        <span class="item-subtitle-data">
                                            {{ editedItem.carton_dimensions.l }}{{editedItem.carton_dimensions.uom}}
                                        </span>
                                    </p>

                                    <p>
                                        <span class="item-subtitle">W </span>
                                        <span class="item-subtitle-data">
                                            {{ editedItem.carton_dimensions.w }}{{editedItem.carton_dimensions.uom}}
                                        </span>
                                    </p>

                                    <p>
                                        <span class="item-subtitle">H </span>
                                        <span class="item-subtitle-data">
                                            {{ editedItem.carton_dimensions.h }}{{editedItem.carton_dimensions.uom}}
                                        </span>
                                    </p>
                                </div>
                            </div> -->

                            <div class="item-info weight">
                                <p class="item-title">Unit Weight</p>
                                <p class="item-data">
                                    {{
                                        editedItem.unit_weight !== null && editedItem.unit_weight.value !== '' ?
                                        editedItem.unit_weight.value : 0
                                    }}{{ editedItem.unit_weight.unit }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="view-box-img" v-if="!editedItem.isForChildProduct">
                        <img :src="getImgUrl(editedItem.image)" v-bind:alt="editedItem.image">
                    </div>

                    <div class="view-box-img" v-if="editedItem.isForChildProduct">
                        <img :src="getImgUrlForChild(editedItem.child_image)" v-bind:alt="editedItem.child_image">
                    </div>
                </div>

                <div class="view-wrapper contact-info" 
                    v-if="typeof editedItem.product_contact !== 'undefined' && editedItem.product_contact.length !== 0">
                    <div class="view-box-info">
                        <div class="view-box-other-data">
                            <div class="item-info category mb-1 align-start">
                                <p class="item-title mb-1">Contact(s)</p>
                                <div class="mb-1" style="width: calc(100% - 180px);">
                                    <span class="item-data mr-0" v-for="(contact, i) in editedItem.product_contact" :key="i">
                                        {{ contact.company_name }}<span class="mr-0" v-if="i+1 < editedItem.product_contact.length">, </span>
                                    </span>
                                </div>
                            </div>

                            <div class="item-info carton mb-1">
                                <div class="d-flex align-center" style="width:50%;">
                                    <p class="item-title mb-1">Unit Price</p>
                                    <p class="item-data mb-1">
                                        ${{ editedItem.unit_price !== null ? parseFloat(editedItem.unit_price).toFixed(2) : 0 }}
                                    </p>
                                </div>

                                <div class="d-flex align-center" style="width:50%;">
                                    <p class="item-title mb-1">In each carton</p>
                                    <p class="item-data">{{ editedItem.units_per_carton }} Units</p>
                                </div>
                            </div>

                            <div class="item-info carton">
                                <div class="d-flex align-center" style="width:50%;">
                                    <p class="item-title mb-1">Carton Dimension</p>
                                    <div class="item-data d-flex justify-start align-center mb-1">
                                        <p class="mb-0">
                                            <span class="item-subtitle mr-0">L </span>
                                            <span class="item-subtitle-data" style="color: #4a4a4a;">
                                                {{ editedItem.carton_dimensions.l !== '' ? editedItem.carton_dimensions.l : 0 }}{{editedItem.carton_dimensions.uom}}
                                            </span>
                                        </p>

                                        <p class="mb-0">
                                            <span class="item-subtitle mr-0">W </span>
                                            <span class="item-subtitle-data" style="color: #4a4a4a;">
                                                {{ editedItem.carton_dimensions.w !== '' ? editedItem.carton_dimensions.w : 0 }}{{editedItem.carton_dimensions.uom}}
                                            </span>
                                        </p>

                                        <p class="mb-0">
                                            <span class="item-subtitle mr-0">H </span>
                                            <span class="item-subtitle-data" style="color: #4a4a4a;">
                                                {{ editedItem.carton_dimensions.h !== '' ? editedItem.carton_dimensions.h : 0 }}{{editedItem.carton_dimensions.uom}}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="d-flex align-center" style="width:50%;">
                                    <p class="item-title mb-1">Unit Shipping Dims</p>
                                    <div class="item-data d-flex justify-start align-center mb-1">
                                        <p class="mb-0">
                                            <span class="item-subtitle mr-0">L </span>
                                            <span class="item-subtitle-data" style="color: #4a4a4a;">
                                                {{ editedItem.unit_dimensions.l !== '' ? editedItem.unit_dimensions.l : 0 }}{{editedItem.unit_dimensions.uom}}
                                            </span>
                                        </p>

                                        <p class="mb-0">
                                            <span class="item-subtitle mr-0">W </span>
                                            <span class="item-subtitle-data" style="color: #4a4a4a;">
                                                {{ editedItem.unit_dimensions.w !== '' ? editedItem.unit_dimensions.w : 0 }}{{editedItem.unit_dimensions.uom}}
                                            </span>
                                        </p>

                                        <p class="mb-0">
                                            <span class="item-subtitle mr-0">H </span>
                                            <span class="item-subtitle-data" style="color: #4a4a4a;">
                                                {{ editedItem.unit_dimensions.h !== '' ? editedItem.unit_dimensions.h : 0 }}{{editedItem.unit_dimensions.uom}}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="product-description-wrapper">
                    <span>Product Description</span>
                    <p class="product-description"> 
                        {{ editedItem.description !== null && editedItem.description !== 'null' && 
                        editedItem.description !== '' ? editedItem.description : '--'}} 
                    </p>

                    <div class="view-box-other-data">
                        <div class="item-info classification">
                            <p class="item-title">Primary Classification</p>
                            <p class="item-data">
                                {{ (editedItem.classification_code !== null && editedItem.classification_code !== 'null') ? editedItem.classification_code : '--' }}
                            </p>
                        </div>

                        <div class="item-info classification">
                            <p class="item-title">Additional Classification</p>

                            <p class="item-data">
                                {{ (editedItem.additional_classification_code !== null && editedItem.additional_classification_code !== 'null') ? editedItem.additional_classification_code : '--' }}
                            </p>
                        </div>

                        <div class="item-info rate">
                            <p class="item-title">Duty Rate</p>
                            <p class="item-data">{{ editedItem.duty_rate !== null ? parseAmount(editedItem.duty_rate) : '--'  }}</p>
                        </div>

                        <div class="item-info dimensions">
                            <p class="item-title">Unit Shipping Dims</p>
                            <div class="item-data-dimensions">
                                <p>
                                    <span class="item-subtitle">L </span>
                                    <span class="item-subtitle-data">
                                        {{
                                            editedItem.unit_dimensions !== null ?
                                            editedItem.unit_dimensions.l + editedItem.unit_dimensions.uom
                                            : 0
                                        }}
                                    </span>
                                </p>

                                <p>
                                    <span class="item-subtitle">W </span>
                                    <span class="item-subtitle-data">
                                        {{
                                            editedItem.unit_dimensions !== null ?
                                            editedItem.unit_dimensions.w + editedItem.unit_dimensions.uom
                                            : 0
                                        }}
                                    </span>
                                </p>

                                <p>
                                    <span class="item-subtitle">H </span>
                                    <span class="item-subtitle-data">
                                        {{
                                            editedItem.unit_dimensions !== null ?
                                            editedItem.unit_dimensions.h + editedItem.unit_dimensions.uom
                                            : 0
                                        }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="product-view-content-wrapper mt-5">
                    <v-tabs class="products-breakdown-tabs" v-model="currentTab">
                        <v-tab v-for="(item, index) in headersComputed" 
                            :key="index"
                            :class="bindClassName(editedItem, index)">

                        <span class="active-breakdown">{{ item }}</span>
                        </v-tab>
                    </v-tabs>

                    <div v-if="currentTab == 0 && childProducts.length !== 0" class="product-contact-wrapper">
                        <v-data-table
                            :headers="contactsHeader"
                            :items="childProducts"
                            :items-per-page="5000"
                            @page-count="pageCount = $event"
                            mobile-breakpoint="769"
                            item-key="id"
                            class="product-breakdown-table elevation-0"
                            v-bind:class="{
                                'no-data-table show-header' : childProducts.length === 0
                            }"
                            hide-default-footer
                            fixed-header
                            :item-class="itemRowBackground">                            

                            <template v-slot:[`item.company_name`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                       <span class="mr-0" style="color: #4a4a4a !important;" 
                                            v-for="(contact, i) in item.product_contact" :key="contact.id">
                                            {{ contact.company_name }}<span class="mr-0" v-if="i+1 < item.product_contact.length">, </span>
                                       </span>

                                       <span style="color: #4a4a4a !important;" v-if="item.product_contact.length === 0">
                                            --
                                       </span>
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.unit_price`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        ${{ item.unit_price !== null ? item.unit_price : 0 }}
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.units_per_carton`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        {{ item.units_per_carton !== null ? item.units_per_carton : 0 }} Units
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.carton_dimensions`]="{ item }">
                                <div class="item-data d-flex justify-start align-center">
                                    <p class="mb-0">
                                        <span class="item-subtitle-data mr-1" style="color: #4a4a4a;">
                                            {{  JSON.parse(item.carton_dimensions) &&
                                                JSON.parse(item.carton_dimensions).l !== undefined &&
                                                JSON.parse(item.carton_dimensions).l !== '' ?  
                                                JSON.parse(item.carton_dimensions).l : 0 
                                            }} x
                                        </span>
                                    </p>

                                    <p class="mb-0">
                                        <span class="item-subtitle-data mr-1" style="color: #4a4a4a;">
                                            {{  JSON.parse(item.carton_dimensions) &&
                                                JSON.parse(item.carton_dimensions).w !== undefined &&
                                                JSON.parse(item.carton_dimensions).w !== '' ?  
                                                JSON.parse(item.carton_dimensions).w : 0 
                                            }} x
                                        </span>
                                    </p>

                                    <p class="mb-0">
                                        <span class="item-subtitle-data mr-1" style="color: #4a4a4a;">
                                            {{  JSON.parse(item.carton_dimensions) &&
                                                JSON.parse(item.carton_dimensions).h !== undefined &&
                                                JSON.parse(item.carton_dimensions).h !== '' ?  
                                                JSON.parse(item.carton_dimensions).h : 0 
                                            }} 
                                        </span>
                                    </p>

                                    <p class="mb-0">{{ JSON.parse(item.carton_dimensions).uom }}</p> 
                                </div>
                            </template>

                            <template v-slot:[`item.unit_dimensions`]="{ item }">
                                <div class="item-data d-flex justify-start align-center">
                                    <p class="mb-0">
                                        <span class="item-subtitle-data mr-1" style="color: #4a4a4a;">
                                            {{  JSON.parse(item.unit_dimensions) &&
                                                JSON.parse(item.unit_dimensions).l !== undefined &&
                                                JSON.parse(item.unit_dimensions).l !== '' ?  
                                                JSON.parse(item.unit_dimensions).l : 0 
                                            }} x
                                        </span>
                                    </p>

                                    <p class="mb-0">
                                        <span class="item-subtitle-data mr-1" style="color: #4a4a4a;">
                                            {{  JSON.parse(item.unit_dimensions) &&
                                                JSON.parse(item.unit_dimensions).w !== undefined &&
                                                JSON.parse(item.unit_dimensions).w !== '' ?  
                                                JSON.parse(item.unit_dimensions).w : 0 
                                            }} x
                                        </span>
                                    </p>

                                    <p class="mb-0">
                                        <span class="item-subtitle-data mr-1" style="color: #4a4a4a;">
                                            {{  JSON.parse(item.unit_dimensions) &&
                                                JSON.parse(item.unit_dimensions).h !== undefined &&
                                                JSON.parse(item.unit_dimensions).h !== '' ?  
                                                JSON.parse(item.unit_dimensions).h : 0 
                                            }} 
                                        </span>
                                    </p>

                                    <p class="mb-0">{{ JSON.parse(item.unit_dimensions).uom }}</p> 
                                </div>
                            </template>                            

                            <template v-slot:no-data>
                                <div class="no-data-wrapper" v-if="childProducts.length === 0">
                                    <div class="no-data-heading">
                                        <p class="mb-0"> This product has no contacts attached yet. </p>
                                    </div>
                                </div>
                            </template>
                        </v-data-table>
                    </div>

                    <div v-if="(currentTab == 1 && childProducts.length > 0) || (currentTab == 0 && childProducts.length == 0)" class="warehouse-breakdown-wrapper">
                        <v-data-table
                            :headers="warehouseBreakdownHeader"
                            :items="warehouseBreakdownItems"
                            :items-per-page="5000"
                            @page-count="pageCount = $event"
                            mobile-breakpoint="769"
                            item-key="id"
                            class="product-breakdown-table elevation-0"
                            v-bind:class="{
                                'no-data-table show-header' : warehouseBreakdownItems !== null && warehouseBreakdownItems.length === 0,
                                'table-loading' : getProductWarehouseBreakdownLoading
                            }"
                            hide-default-footer
                            fixed-header>
                            
                            <template v-slot:[`item.warehouse_name`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        {{ item.warehouse_name !== null ? item.warehouse_name : '--' }}
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.total_unit`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        {{ item.total_unit !== null ? item.total_unit : '--' }}
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.products_allocated`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        {{ item.products_allocated !== null ? item.products_allocated : '--' }}
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.available`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        {{ item.available !== null ? item.available : '--' }}
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.inbound`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        {{ item.inbound !== null ?  item.inbound : '--' }}
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.preferred`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        {{ item.preferred !== null ?  item.preferred : '--' }}
                                    </div>
                                </div>
                            </template>

                            <template v-slot:[`item.delta`]="{ item }">
                                <div class="warehouse-breakdown-content">
                                    <div class="products-label-unit">
                                        {{ item.delta !== null ?  item.delta : '--' }}
                                    </div>
                                </div>
                            </template>

                            <template v-slot:no-data>
                                <div class="loading-wrapper pt-5" v-if="getProductWarehouseBreakdownLoading">
                                    <v-progress-circular
                                        :size="40"
                                        color="#0171a1"
                                        indeterminate>
                                    </v-progress-circular>
                                </div>

                                <div class="no-data-wrapper" 
                                    v-if="!getProductWarehouseBreakdownLoading && warehouseBreakdownItems.length === 0">
                                    <div class="no-data-heading">
                                        <p class="mb-0"> This product has no warehouse breakdown yet. </p>
                                    </div>
                                </div>
                            </template>
                        </v-data-table>
                    </div>

                    <div v-if="(currentTab == 2 && childProducts.length > 0) || (currentTab == 1 && childProducts.length == 0)" class="projection-report-wrapper">
                        <div class="projection-report-content-wh">
                            <v-data-table
                                :headers="projectionHeaders"
                                :items="projectionReportItemsWH"
                                :items-per-page="5000"
                                @page-count="pageCount = $event"
                                mobile-breakpoint="769"
                                item-key="id"
                                class="product-projection-table elevation-0"
                                v-bind:class="{
                                    'no-data-table show-header' : projectionReportItemsWH !== null && projectionReportItemsWH.length === 0,
                                    'table-loading' : getProductProjectionReportLoading
                                }"
                                hide-default-footer
                                fixed-header>
                                
                                <template v-slot:[`item.warehouse_name`]="{ item }">
                                    <div class="warehouse-breakdown-content">
                                        <div class="products-label-unit">
                                            {{ item.warehouse_name !== null ? item.warehouse_name : '--' }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:[`item.total_stock`]="{ item }">
                                    <div class="warehouse-breakdown-content">
                                        <div class="products-label-unit">
                                            {{ item.total_stock !== null ? item.total_stock : '--' }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:[`item.available`]="{ item }">
                                    <div class="warehouse-breakdown-content">
                                        <div class="products-label-unit">
                                            {{ item.available !== null ? item.available : '--' }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:no-data>
                                    <div class="loading-wrapper pt-5" v-if="getProductProjectionReportLoading">
                                        <v-progress-circular
                                            :size="40"
                                            color="#0171a1"
                                            indeterminate>
                                        </v-progress-circular>
                                    </div>
                                    <div class="no-data-wrapper" 
                                        v-if="!getProductProjectionReportLoading && projectionReportItemsWH.length === 0">
                                        <div class="no-data-heading">
                                            <p class="mb-0"> No data available </p>
                                        </div>
                                    </div>
                                </template>
                            </v-data-table>

                            <div class="report-total-wrapper" 
                                v-if="!getProductProjectionReportLoading && projectionReportItemsWH.length !== 0">
                                <p>Subtotal</p>
                                <p class="text-right">
                                    {{ 
                                        projectionTotalValues !== null && projectionTotalValues.sub_total_warehouse !== 'undefined' ?
                                        projectionTotalValues.sub_total_warehouse : ''
                                    }}
                                </p>
                                <p class="text-right">
                                    {{ 
                                        projectionTotalValues !== null && projectionTotalValues.sub_available_warehouse !== 'undefined' ?
                                        projectionTotalValues.sub_available_warehouse : ''
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="projection-report-content-po pt-3">
                            <v-data-table
                                :headers="projectionHeadersPo"
                                :items="projectionReportItemsPO"
                                :items-per-page="5000"
                                @page-count="pageCount = $event"
                                mobile-breakpoint="769"
                                item-key="id"
                                class="product-projection-table elevation-0"
                                v-bind:class="{
                                    'no-data-table show-header' : projectionReportItemsPO !== null && projectionReportItemsPO.length === 0,
                                    'table-loading' : getProductProjectionReportLoading
                                }"
                                hide-default-footer
                                fixed-header>
                                
                                <template v-slot:[`item.po_number`]="{ item }">
                                    <div class="warehouse-breakdown-content">
                                        <div class="products-label-unit">
                                            {{ item.po_number !== null ? item.po_number : '--' }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:[`item.date`]="{ item }">
                                    <div class="warehouse-breakdown-content">
                                        <div class="products-label-unit">
                                            {{ item.date !== null ? item.date : '--' }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:[`item.quantity`]="{ item }">
                                    <div class="warehouse-breakdown-content">
                                        <div class="products-label-unit">
                                            {{ item.quantity !== null ? item.quantity : '--' }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:[`item.available`]="{ item }">
                                    <div class="warehouse-breakdown-content">
                                        <div class="products-label-unit">
                                            {{ item.available !== null ? item.available : '--' }}
                                        </div>
                                    </div>
                                </template>

                                <template v-slot:no-data>
                                    <div class="loading-wrapper pt-5" v-if="getProductProjectionReportLoading">
                                        <v-progress-circular
                                            :size="40"
                                            color="#0171a1"
                                            indeterminate>
                                        </v-progress-circular>
                                    </div>
                                    <div class="no-data-wrapper" 
                                        v-if="!getProductProjectionReportLoading && projectionReportItemsPO.length === 0">
                                        <div class="no-data-heading">
                                            <p class="mb-0"> No data available </p>
                                        </div>
                                    </div>
                                </template>
                            </v-data-table>

                            <div class="report-total-wrapper-po" 
                                v-if="!getProductProjectionReportLoading && projectionReportItemsPO.length !== 0">
                                <p>Subtotal</p>
                                <p class="text-right"></p>
                                <p class="text-right">
                                    {{ 
                                        projectionTotalValues !== null && projectionTotalValues.sub_total_po !== 'undefined' ?
                                        projectionTotalValues.sub_total_po : ''
                                    }}
                                </p>
                                <p class="text-right">-</p>
                            </div>

                            <div class="report-total-wrapper-po" v-if="!getProductProjectionReportLoading">
                                <p>Total</p>
                                <p class="text-right"></p>
                                <p class="text-right">
                                    {{ 
                                        projectionTotalValues !== null && projectionTotalValues.total_stock !== 'undefined' ?
                                        projectionTotalValues.total_stock : ''
                                    }}
                                </p>
                                <p class="text-right">
                                    {{ 
                                        projectionTotalValues !== null && projectionTotalValues.total_available !== 'undefined' ?
                                        projectionTotalValues.total_available : ''
                                    }}
                                </p>
                            </div>
                        </div>

                        <!-- Load More Example code -->
                        <!-- <div class="button-actions-logs-wrapper">
                            <div class="button-load-more-wrapper" 
                                v-if="isShowLoadMore && !getProductInventoryLogsLoading">
                                <button class="btn-black" @click.stop="loadMoreLogs">
                                    Load More
                                </button>
                            </div>

                            <div class="button-load-more-wrapper" 
                                style="height: 41px;" v-if="isShowLoadMore && getProductInventoryLogsLoading">
                                <div class="loading-wrapper" v-if="getProductInventoryLogsLoading">
                                    <v-progress-circular
                                        :size="25"
                                        color="#0171a1"
                                        indeterminate>
                                    </v-progress-circular>
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <!-- <div v-if="currentTab == 0 && typeof editedItem.product_contact !== 'undefined' && editedItem.product_contact.length !== 0" class="product-contact-wrapper">
                        <v-data-table
                            :headers="contactsHeader"
                            :items="editedItem.product_contact"
                            :items-per-page="5000"
                            @page-count="pageCount = $event"
                            mobile-breakpoint="769"
                            item-key="id"
                            class="product-breakdown-table elevation-0"
                            v-bind:class="{
                                'no-data-table show-header' : typeof editedItem.product_contact !== 'undefined' && editedItem.product_contact.length === 0
                            }"
                            hide-default-footer
                            fixed-header>                            

                            <template v-slot:no-data>
                                <div class="no-data-wrapper" 
                                    v-if="typeof editedItem.product_contact !== 'undefined' && editedItem.product_contact.length === 0">
                                    <div class="no-data-heading">
                                        <p class="mb-0"> This product has no contacts attached yet. </p>
                                    </div>
                                </div>
                            </template>
                        </v-data-table>
                    </div> -->
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import _ from 'lodash'
import { mapActions, mapGetters } from 'vuex'

export default {
    name: 'ProductViewDialog',
    props: ['editedItemData', 'dialogViewData', 'isMobile', 'categoryLists'],
    data: () => ({
        warehouseBreakdownHeader: [
            {
                text: 'WAREHOUSE',
                align: 'start',
				sortable: false,
				value: 'warehouse_name',
				fixed: true,
				width: "25%" 
            },
            {
                text: 'UNIT',
                align: 'end',
				sortable: false,
				value: 'total_unit',
				fixed: true,
				width: "12%" 
            },
            {
                text: 'ALLOCATED',
                align: 'end',
				sortable: false,
				value: 'products_allocated',
				fixed: true,
				width: "12%" 
            },
            {
                text: 'AVAILABLE',
                align: 'end',
				sortable: false,
				value: 'available',
				fixed: true,
				width: "12%" 
            },
            {
                text: 'INBOUND',
                align: 'end',
				sortable: false,
				value: 'inbound',
				fixed: true,
				width: "12%" 
            },
            {
                text: 'PREFERRED',
                align: 'end',
				sortable: false,
				value: 'preferred',
				fixed: true,
				width: "12%" 
            },
            {
                text: 'DELTA',
                align: 'end',
				sortable: false,
				value: 'delta',
				fixed: true,
				width: "12%" 
            },
        ],
        projectionHeaders: [
            {
                text: 'WAREHOUSE',
                align: 'start',
				sortable: false,
				value: 'warehouse_name',
				fixed: true,
				width: "50%" 
            },
            {
                text: 'TOTAL STOCK',
                align: 'end',
				sortable: false,
				value: 'total_stock',
				fixed: true,
				width: "25%" 
            },
            {
                text: 'AVAILABLE',
                align: 'end',
				sortable: false,
				value: 'available',
				fixed: true,
				width: "25%" 
            },
        ],
        projectionHeadersPo: [
            {
                text: 'PO',
                align: 'start',
				sortable: false,
				value: 'po_number',
				fixed: true,
				width: "30%" 
            },
            {
                text: 'DATE',
                align: 'center',
				sortable: false,
				value: 'date',
				fixed: true,
				width: "20%" 
            },
            {
                text: 'QUANTITY',
                align: 'end',
				sortable: false,
				value: 'quantity',
				fixed: true,
				width: "25%" 
            },
            {
                text: 'AVAILABLE',
                align: 'end',
				sortable: false,
				value: 'available',
				fixed: true,
				width: "25%" 
            },
        ],
        contactsHeader: [
            {
                text: 'CONTACT',
                align: 'start',
				sortable: false,
				value: 'company_name',
				fixed: true,
				width: "32%" 
            },
            {
                text: 'UNIT PRICE',
                align: 'end',
				sortable: false,
				value: 'unit_price',
				fixed: true,
				width: "12%" 
            },
            {
                text: 'IN EACH',
                align: 'end',
				sortable: false,
				value: 'units_per_carton',
				fixed: true,
				width: "12%" 
            },
            {
                text: 'CARTON DIMENSIONS',
                align: 'start',
				sortable: false,
				value: 'carton_dimensions',
				fixed: true,
				width: "22%" 
            },
            {
                text: 'UNIT SHIPPING DIMS',
                align: 'start',
				sortable: false,
				value: 'unit_dimensions',
				fixed: true,
				width: "22%" 
            },
        ],
        productsViewTab: ['Contact', 'Warehouse Breakdown', 'Projection Report'],
        currentTab: 0,
    }),
    mounted() {},
    methods: {
        ...mapActions({
            setProduct: 'products/setProduct',
        }),
        closeView() {
            this.$emit('close')
        },
        getImgUrl(pic) {
			if (pic !== 'undefined' && pic !== null) {
				return pic
			} else {
				return require('../../../assets/icons/default-product-icon.svg')
			}
		},
        getImgUrlForChild(pic) {
            // displaying child image in view dialog with default product icon if null
            let imageUrl = 'https://po.shifl.com/storage/'

			if (pic !== 'undefined' && pic !== null) {
                if (pic.includes(imageUrl) !== 'undefined' && !pic.includes(imageUrl)) {
                    let newImage = imageUrl + pic
                    return newImage
                } else {
                    return pic
                }
            } else {
				return require('../../../assets/icons/default-product-icon.svg')
			}
		},
        getCategoryName(id) {
			if(this.categoryLists.length !== 0 && id) {
                let findSizeValue = _.find(this.categoryLists, (e) => (e.id == id))

                if (typeof findSizeValue !== 'undefined') {
                    if (findSizeValue.name !== 'undefined') {
                        return findSizeValue.name
                    }
                } else {
                    return '--'
                }
            } else {
                return '--'
            }
		},
        getChildImageUrl(pic) {
            let imageUrl = 'https://po.shifl.com/storage/'

            if (pic !== 'undefined' && pic !== null) {
                if (pic.includes(imageUrl) !== 'undefined' && !pic.includes(imageUrl)) {
                    let newImage = imageUrl + pic
                    return newImage
                } else {
                    return pic
                }
            }
		},
        editItem(product, isViewFor) {
            // if product selected is from child products
            let index = this.editedItem.currentProductChildIndex
            let isFor = ''

            if (typeof product.isForChildProduct !== 'undefined' && product.isForChildProduct) {
                // let imageForDuplicate = product.image
                let parentCopy = product
                let productForChild = product.child_products[index]
                
                productForChild.duplicate_contact = productForChild.product_contact
                productForChild.parentItem = parentCopy
                productForChild.image = productForChild.image !== null ? this.getChildImageUrl(productForChild.image) : null

                if (isViewFor === 'edit') {
                    isFor = 'child'
                    productForChild.isDuplicateEdit = true
                    productForChild.isDuplicate = false
                } else {                    
                    isFor = 'copy'
                    productForChild.isDuplicateEdit = false
                    productForChild.isDuplicate = true
                }
                
                product = productForChild
            } else {
                isFor = isViewFor
            }

            // let data = { product, isFor: product.isFor, index: this.editedItem.currentProductChildIndex }
            let data = { product, isFor, index: this.editedItem.currentProductChildIndex }
            this.$emit('update:dialogViewData', false)
            setTimeout(() => {
                this.$emit('editItem', data)
            }, 100)
        },
        deleteItem(product) {
            let index = product.currentProductChildIndex
            let isFor = ''

            if (typeof product.isForChildProduct !== 'undefined' && product.isForChildProduct) {
                isFor = 'child'
                let productForChild = product.child_products[index]                
                product = productForChild
            } else {
                isFor = 'parent'
            }

            let data = { product, isFor }
            this.$emit('update:dialogViewData', false)
            setTimeout(() => {
                this.$emit('deleteItem', data)
            }, 100)
        },
        parseAmount(amount) {
            if (amount !== 'undefined' && amount !== null && amount !== '') {
                return parseFloat(amount).toFixed(2) + '%'
            }
        },
        parseCodes(item) {
            if (item !== '' && item !== null) {
                let parsedCodes = JSON.parse(item)

                return parsedCodes
            } else {
                return '--'
            }
        },
        bindClassName(item, index) {
            // if ((typeof item.product_contact !== 'undefined' && item.product_contact.length !== 0) ||
            //     (typeof item.child_products !== 'undefined' && item.child_products.length !== 0)) {
            //     if (index === 2) {
            //         return 'products-breakdown-inner-tab-last'
            //     }
            // } else {
            //     if (index === 1) {
            //         return 'products-breakdown-inner-tab-last'
            //     }
            // } 
            
            if (typeof item.child_products !== 'undefined' && item.child_products.length !== 0) {
                if (index === 2) {
                    return 'products-breakdown-inner-tab-last'
                }
            } else {
                if (index === 1) {
                    return 'products-breakdown-inner-tab-last'
                }
            } 
        },
        itemRowBackground(item) {
            let currentItem = item.index

            if (currentItem === this.editedItem.currentProductChildIndex) {
                return "background-light-blue"
            } else {
                return ""
            }
        }
    },
    watch: {
        dialogView(val) {
            if (val) {
                // if product clicked for viewing is from child products, set current tab to contacts
                // if (this.editedItem.isForChildProduct) {
                //     this.currentTab = 0
                // } else {
                //     this.currentTab = 1
                // }

                this.currentTab = 0
            }
        }
    },
    computed: {
        ...mapGetters({
            getProducts: 'products/getProducts',
            getProductWarehouseBreakdown: 'products/getProductWarehouseBreakdown',
            getProductWarehouseBreakdownLoading: 'products/getProductWarehouseBreakdownLoading',
            getProductProjectionReport: 'products/getProductProjectionReport',
            getProductProjectionReportLoading: 'products/getProductProjectionReportLoading'
        }),
        dialogView: {
            get () {
                return this.dialogViewData
            },
            set (value) {
                if (!value) {
                    this.$emit('update:dialogViewData', false)
                } else {
                    this.$emit('update:dialogViewData', true)
                }
            }
        },
        editedItem: {
            get () {
                return this.editedItemData
            },
            set (value) {
                console.log(value)
            }
        },
        childProducts() {
            let items = []

            if (typeof this.editedItem.child_products !== 'undefined' && this.editedItem.child_products.length > 0) {
                items = this.editedItem.child_products.map((v, i) => {
                    return {
                        ...v,
                        index: i
                    }
                })
            }

            return items
        },
        warehouseBreakdownItems() {
            let items = []

            if (this.getProductWarehouseBreakdown !== null && typeof this.getProductWarehouseBreakdown !== 'undefined') {
                if (this.getProductWarehouseBreakdown.data !== null && typeof this.getProductWarehouseBreakdown.data !== 'undefined') {
                    items = this.getProductWarehouseBreakdown.data
                }else{
                   items = [] 
                }
            }

            return items
        },
        projectionReportItemsWH() {
            let items = []

            if (this.getProductProjectionReport !== null && typeof this.getProductProjectionReport !== 'undefined') {
                if (this.getProductProjectionReport.warehouses !== null && typeof this.getProductProjectionReport.warehouses !== 'undefined') {
                    items = this.getProductProjectionReport.warehouses
                }else{
                    items = []
                }
            }

            return items
        },
        projectionReportItemsPO() {
            let items = []

            if (this.getProductProjectionReport !== null && typeof this.getProductProjectionReport !== 'undefined') {
                if (this.getProductProjectionReport.pos !== null && typeof this.getProductProjectionReport.pos !== 'undefined') {
                    items = this.getProductProjectionReport.pos
                }else{
                   items = [] 
                }
            }

            return items
        },
        projectionTotalValues() {
            let items = null

            if (this.getProductProjectionReport !== null) {
                items = this.getProductProjectionReport
            }

            return items
        },
        headersComputed() {
            let headers = this.productsViewTab

            // if ((typeof this.editedItem.product_contact !== 'undefined' && this.editedItem.product_contact.length === 0) &&
            //     (typeof this.editedItem.child_products !== 'undefined' && this.editedItem.child_products.length === 0)) {
            //     headers = headers.filter(v => {
            //         return v !== 'Contact'
            //     })
            // }

            if (typeof this.editedItem.child_products !== 'undefined' && this.editedItem.child_products.length === 0) {
                headers = headers.filter(v => {
                    return v !== 'Contact'
                })
            }

            return headers
        }
    },
    updated() {}
}
</script>

<style lang="scss">
@import '../../../assets/scss/pages_scss/product/productViewDialog.scss';
@import '../../../assets/scss/tables.scss';
</style>
