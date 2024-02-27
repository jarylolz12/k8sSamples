<template>
    <div class="products-items-desktop-wrapper" v-resize="onResize">
        <div class="overlay" :class="handleLoadingShow ? 'show' : ''">  
            <div class="preloader" v-if="(handleLoadingShow)">
                <v-progress-circular
                    :size="40"
                    color="#0171a1"
                    indeterminate>
                </v-progress-circular>
            </div>       
        </div>

        <v-data-table
            :headers="headersComputed"
            :items="getProductItems"
            :page.sync="getCurrentPage"
            :items-per-page="getItemsPerPage"
            hide-default-footer
            mobile-breakpoint="1023"
            @page-count="pageCount = $event"
            class="product-table elevation-1"
            @click:row="viewProductItem"
            v-bind:class="{
                'no-data-table' : (typeof getProductItems !== 'undefined' && getProductItems.length === 0),
                'no-current-pagination' : (getTotalPage <= 1),
                'no-searched-data' : getSearchedDataClass,
            }"
            fixed-header
            hide-default-header
            show-select
            ref="my-table"
            v-model="selectedProducts"
            :expanded.sync="expanded"
            :single-expand="singleExpand">

            <template v-slot:header="{ props: { headers, ...props } }">
                <thead>
                    <tr>
                        <th v-for="(item, index) in headers" 
                            :key="index"
                            role="columnheader"
                            :aria-label="item.text"
                            scope="col"
                            v-bind:class="[
                                item.text == 'sku_with_category' ? 'd-none' : `${item.text}`,
                                `text-${item.align}`,
                                index === 0 ? 'left-fixed-column' : ''
                            ]"
                            v-bind:style="`width: ${item.width}; min-width: ${item.width};`">
                            
                            <v-simple-checkbox
                                :ripple="false"
                                v-model="props.everyItem"
                                :indeterminate="props.someItems && !props.everyItem"
                                v-if="item.value === 'data-table-select'"                                 
                                @input="selectAllProducts">
                            </v-simple-checkbox>

                            <span v-else>{{item.text}}</span>
                        </th>
                    </tr>  
                </thead>
            </template>

            <template v-slot:item="{ item, isSelected, select, expand, isExpanded }">
                <tr @click.stop="viewProductItem(item, 'parent')">
                    <th class="v-select-product-wrapper left-fixed-column">
                        <v-checkbox 
                            v-model="selectedProducts" 
                            multiple
                            :value="item"
                            style="margin:0px;padding:0px"
                            hide-details
                            @click.stop="select(!isSelected)" />
                    </th>

                    <th class="sku-and-category-wrapper left-fixed-column">
                        <div class="description-wrapper">
                            <div class="description-img">
                                <img :src="getImgUrl(item.image)" v-bind:alt="item.image" width="64px" height="64px">
                            </div>
                            <div class="info-wrapper">                        
                                <p class="mb-0 product-sku-text">
                                    <span v-if="item.category_id !== null">{{ item.category_id }}-</span>
                                    <span>{{ (item.sku !== null && item.sku !== '' ? item.sku : '--') }}</span>
                                </p>
                                <p class="desc-info">{{ item.name }}</p>
                                <p class="category">{{ item.category_name !== null ? item.category_name : '--' }}</p>
                            </div>
                        </div>
                    </th>

                    <td class="text-start">
                        <div v-if="item.child_products.length === 0">
                            <span class="mb-0" v-for="(i, index) in item.product_contact" :key="index">
                                <span v-if="index < 3">
                                    <span>{{ i.company_name }}</span>
                                    <span v-if="index+1 < item.product_contact.length">, </span>
                                </span>

                                <span v-if="index === 3">
                                    <v-tooltip bottom
                                        :open-on-focus="true" 
                                        :open-on-click="false" 
                                        :open-on-hover="true"
                                        content-class="has-selected top-arrow">                                       

                                        <template v-slot:activator="{ on }">                                              
                                            <span style="color: #0171A1;" v-on="on">+{{ item.product_contact.length - 3 }} Others</span>
                                        </template>                                            

                                        <span v-for="(i, index) in item.product_contact" :key="index">
                                            <span v-if="index >= 3">
                                                <span>{{ i.company_name }}</span>
                                                <span v-if="index+1 < item.product_contact.length">, </span><br>
                                            </span>
                                        </span>
                                    </v-tooltip>                             
                                </span>
                            </span>

                            <span v-if="item.product_contact.length === 0">--</span>
                        </div>
                        
                        <div v-if="item.child_products.length > 0">
                            <button @click.stop="expand(!isExpanded)" flat>
                                <v-icon v-if="isExpanded" color="#0171A1">mdi-chevron-up</v-icon>
                                <v-icon v-if="!isExpanded" color="#0171A1">mdi-chevron-down</v-icon>
                            </button> 
                            {{item.child_products.length}} Contact
                        </div>
                    </td>

                    <td class="text-start" v-if="getHas3PLProviderWarehouse">
                        <p class="mb-0">
                            {{ 
                                item.warehouse_customer !== null && 
                                item.warehouse_customer.company_name !== undefined ? 
                                item.warehouse_customer.company_name : '--' 
                            }}
                        </p>
                    </td>

                    <td class="text-end">
                        <p class="mb-0">{{ item.total_inventory }} Pcs</p>
                    </td>

                    <td class="text-end">
                        <p class="mb-0">{{ item.total_available }} Pcs</p>
                    </td>

                    <td class="text-end">
                        <p class="mb-0">{{ item.units_per_carton }} Pcs</p>
                    </td>

                    <td class="text-end">
                        <p class="mb-0">${{ getUnitPrice(item.unit_price) }}</p>
                    </td>

                    <td class="text-end">
                        <span>
                            {{  item.carton_dimensions.w !== undefined &&
                                item.carton_dimensions.w !== '' ?  
                                item.carton_dimensions.w : 0 
                            }}
                        </span> x
                        <span>
                            {{  item.carton_dimensions.h !== undefined &&
                                item.carton_dimensions.h !== '' ?  
                                item.carton_dimensions.h : 0 
                            }}
                        </span> x
                        <span>
                            {{  item.carton_dimensions.l !== undefined &&
                                item.carton_dimensions.l !== '' ?  
                                item.carton_dimensions.l : 0 
                            }}
                        </span>
                        <span>{{ item.carton_dimensions.uom }}</span> 
                    </td>

                    <td class="text-end">
                        <span>{{ getParsedAmount(item.duty_rate) }}%</span>
                        <p class="mb-0" style="color: #6D858F;">
                            {{ item.classification_code !== null && 
                                item.classification_code !== "null" &&
                                item.classification_code !== "" ? 
                                item.classification_code : 'N/A' 
                            }}
                        </p>
                    </td>

                    <th class="action-column-th">
                        <div class="actions">
                            <v-menu bottom left offset-y content-class="outbound-lists-menu">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn class="btn-edit" icon v-bind="attrs" v-on="on">
                                        <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                                    </v-btn>
                                </template>

                                <v-list class="outbound-lists">
                                    <v-list-item @click.stop="editProduct(item, 'edit')">
                                        <v-list-item-title> Edit </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item @click.stop="editProduct(item, 'copy')">
                                        <v-list-item-title> Duplicate </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item @click.stop="archiveProduct(item, false)">
                                        <v-list-item-title> Archive </v-list-item-title>
                                    </v-list-item>

                                    <v-list-item @click.stop="deleteProductItem(item, 'parent')">
                                        <v-list-item-title class="cancel"> Delete </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>
                    </th>
                </tr>
            </template>

            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" style="background-color: #f7f7f7;" v-if="item.child_products.length > 0">
                    <div class="product-contact-expanded" :style="`width:${currentWidth}px;`">
                        <div class="expanded-item-info">
                            <div class="expanded-header-wrapper">
                                <div class="expanded-header-content">
                                    <div class="header-title w-35">Contact</div>
                                    <div class="header-title w-15">In Each</div>
                                    <div class="header-title w-15">Unit Price</div>
                                    <div class="header-title w-20">Carton Dimension</div>
                                    <div class="header-title w-20">Shipping Unit dimension</div>
                                    <div class="header-title w-4"> </div>
                                </div>
                            </div>

                            <div class="expanded-body-wrapper">
                                <div class="expanded-body-content" 
                                    v-for="(v, index) in item.child_products" :key="index"
                                    @click.stop="viewProductItem(item, 'child', index)">

                                    <div class="header-data w-35">
                                        <div v-if="v.product_contact.length > 0">
                                            <span v-for="(vv, i) in v.product_contact" :key="i">
                                                <span>{{ vv.company_name }}</span>
                                                <span v-if="i+1 < v.product_contact.length">, </span>
                                            </span>
                                        </div>

                                        <span v-else>--</span>
                                    </div>

                                    <div class="header-data w-15">
                                        {{ v.units_per_carton }} Units
                                    </div>

                                    <div class="header-data w-15">
                                        ${{ parseFloat(v.unit_price).toFixed(2) }}
                                    </div>

                                    <div class="header-data w-20">
                                        <span>
                                            {{  JSON.parse(v.carton_dimensions) &&
                                                JSON.parse(v.carton_dimensions).w !== undefined &&
                                                JSON.parse(v.carton_dimensions).w !== '' ?  
                                                JSON.parse(v.carton_dimensions).w : 0 
                                            }}
                                        </span> x
                                        <span>
                                            {{  JSON.parse(v.carton_dimensions) &&
                                                JSON.parse(v.carton_dimensions).h !== undefined &&
                                                JSON.parse(v.carton_dimensions).h !== '' ?  
                                                JSON.parse(v.carton_dimensions).h : 0 
                                            }}
                                        </span> x
                                        <span>
                                            {{  JSON.parse(v.carton_dimensions) &&
                                                JSON.parse(v.carton_dimensions).l !== undefined &&
                                                JSON.parse(v.carton_dimensions).l !== '' ?  
                                                JSON.parse(v.carton_dimensions).l : 0 
                                            }}
                                        </span>
                                        <span>{{ JSON.parse(v.carton_dimensions).uom }}</span> 
                                    </div>

                                    <div class="header-data w-20">
                                        <span>
                                            {{  JSON.parse(v.unit_dimensions) &&
                                                JSON.parse(v.unit_dimensions).w !== undefined &&
                                                JSON.parse(v.unit_dimensions).w !== '' ?  
                                                JSON.parse(v.unit_dimensions).w : 0 
                                            }}
                                        </span> x
                                        <span>
                                            {{  JSON.parse(v.unit_dimensions) &&
                                                JSON.parse(v.unit_dimensions).h !== undefined &&
                                                JSON.parse(v.unit_dimensions).h !== '' ?  
                                                JSON.parse(v.unit_dimensions).h : 0 
                                            }}
                                        </span> x
                                        <span>
                                            {{  JSON.parse(v.unit_dimensions) &&
                                                JSON.parse(v.unit_dimensions).l !== undefined &&
                                                JSON.parse(v.unit_dimensions).l !== '' ?  
                                                JSON.parse(v.unit_dimensions).l : 0 
                                            }}
                                        </span>
                                        <span>{{ JSON.parse(v.unit_dimensions).uom }}</span> 
                                    </div>

                                    <div class="header-data w-4">
                                        <div class="actions">
                                            <v-menu bottom left offset-y content-class="outbound-lists-menu">
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-btn class="btn-white" icon v-bind="attrs" v-on="on" 
                                                        style="width:28px !important; height: 28px !important;">
                                                        <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                                                    </v-btn>
                                                </template>

                                                <v-list class="outbound-lists">
                                                    <v-list-item @click.stop="editProductContact(v, 'child', index, item)">
                                                        <v-list-item-title> Edit </v-list-item-title>
                                                    </v-list-item>

                                                    <v-list-item @click.stop="duplicateProductContact(v, 'child', index, item)">
                                                        <v-list-item-title> Duplicate </v-list-item-title>
                                                    </v-list-item>

                                                    <v-list-item @click.stop="deleteProductItem(v, 'child')">
                                                        <v-list-item-title class="cancel"> Delete </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </template>

            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Products</v-toolbar-title>                    
                    <v-spacer></v-spacer>

                    <!-- <v-btn @click="navigateArchivePage" class="btn-white black--text" color="primary">
                        <img src="@/assets/icons/archive-blue.svg" class="mr-1" width="16px" height="16px">
                        <span>Archived</span>
                    </v-btn>

                    <v-btn @click="$emit('import')" class="btn-white black--text ml-2" color="primary">Import</v-btn> -->
                    
                    <v-menu 
                        offset-y
                        v-if="getProductItems.length > 0"
                        bottom
                        nudge-bottom="6"
                        nudge-left="150"
                        :close-on-content-click="false"
                        :nudge-width="150">
                        
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                class="btn-white ml-2"
                                style="color: #4A4A4A !important;" 
                                v-bind="attrs"
                                v-on="on">
                                Export <v-icon right class="export-icon-arrow my-1">mdi-chevron-down</v-icon>
                            </v-btn>
                        </template>

                        <v-card>
                            <v-list>
                                <v-list-item style="min-height: 20px;" class="pt-2">
                                    <v-list-item-title style="color:#6D858F;font-size:12px !important;font-weight:500;" class="text-start">Product Data</v-list-item-title>
                                </v-list-item>

                                <v-list-item style="min-height: 35px;">
                                    <v-list-item-title style="cursor:pointer;color:#4A4A4A;font-size:14px !important;font-weight:400" class="text-start ml-3" @click="csvReportChangeFunction('product')">Export as .CSV</v-list-item-title>
                                </v-list-item>

                                <v-list-item style="min-height: 35px;">
                                    <v-list-item-title style="cursor:pointer;color:#4A4A4A;font-size:14px !important;font-weight:400" class="text-start ml-3" @click="pdfReportChangeFunction('product')">Export as .PDF</v-list-item-title>
                                </v-list-item>

                                <v-list-item style="min-height: 20px;" class="pt-2">
                                    <v-list-item-title style="color:#6D858F;font-size:12px !important;font-weight:500;" class="text-start">Inventory Prediction Report</v-list-item-title>
                                </v-list-item> 

                                <v-list-item style="min-height: 35px;"> 
                                    <v-list-item-title style="cursor:pointer;color:#4A4A4A;font-size:14px !important;font-weight:400" class="text-start ml-3" @click="csvReportChangeFunction('inventory')">Export as .CSV</v-list-item-title>
                                </v-list-item>

                                <v-list-item style="min-height: 35px;">
                                    <v-list-item-title style="cursor:pointer;color:#4A4A4A;font-size:14px !important;font-weight:400" class="text-start ml-3" @click="pdfReportChangeFunction('inventory')">Export as .PDF</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-card>
                    </v-menu>

                    <v-btn color="primary" class="mr-2 btn-white manage-category-button ml-2" @click="handleManageCategory">
                        Manage Categories
                    </v-btn>

                    <v-btn color="primary" class="btn-blue add-product-button" @click.stop="addProduct">
                        Add Product
                    </v-btn>

                    <v-menu bottom left offset-y content-class="outbound-lists-menu">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn class="btn-white ml-2 mr-0" icon v-bind="attrs" v-on="on" style="width: 40px !important;">
                                <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item @click.stop="$emit('import')">
                                <v-list-item-title> Import </v-list-item-title>
                            </v-list-item>

                            <v-list-item @click.stop="navigateArchivePage">
                                <v-list-item-title> Archived Products </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-toolbar>

                <div class="header-second-wrapper">
                    <v-spacer></v-spacer>
                    
                    <!-- <FilterComponent :menu.sync="filterMenu" :isMobile="isMobile">
                        <template v-slot:filter_body>
                            <form>
                                <div class="filter-component-body">
                                    <p class="filter-menu-title">Sort by</p>
                                    <v-radio-group v-model="filtersValue.selectedSort" hide-details="auto" class="pt-0 mt-0 mb-4">
                                        <div v-for="(n, index) in radioSortByOptions" :key="index" class="filter-radio-buttons">
                                            <v-radio
                                                style="margin: unset;"
                                                :key="index"
                                                :label="`${n.name}`"
                                                :value="n.value"
                                                @change="changeValueFilter(index)">
                                            </v-radio>

                                            <div class="filter-sorting-icons">                                               
                                                <v-btn icon 
                                                    :ripple="false"
                                                    @click="toggleAscDesc(true, n, index)" 
                                                    :class="(filtersValue.isAsc && filtersValue.index === index) ? 'active' : ''">
                                                    <v-icon aria-hidden="false">
                                                        mdi-sort-alphabetical-ascending
                                                    </v-icon>
                                                </v-btn>

                                                <v-btn icon 
                                                    :ripple="false"
                                                    @click="toggleAscDesc(false, n, index)"
                                                    :class="(!filtersValue.isAsc && filtersValue.index === index) ? 'active' : ''">
                                                    <v-icon aria-hidden="false">
                                                        mdi-sort-alphabetical-descending
                                                    </v-icon>
                                                </v-btn>
                                            </div>
                                        </div>
                                    </v-radio-group>
                                </div>

                                <div class="filter-component-sub-body">
                                    <v-row>
                                        <v-col cols="12" sm="12" class="pb-1">
                                            <p class="filter-menu-title">Category</p>

                                            <v-autocomplete
                                                class="text-fields select-items"
                                                :items="categoryLists"
                                                item-text="name"
                                                item-value="id"
                                                outlined
                                                hide-details="auto"
                                                placeholder="Select Category"
                                                v-model="filtersValue.category"
                                                clearable>
                                            </v-autocomplete>
                                        </v-col>

                                        <v-col cols="12" sm="12" class="pb-1">
                                            <p class="filter-menu-title">Warehouse Customer</p>

                                            <v-autocomplete
                                                class="text-fields select-items"
                                                :items="warehouseCustomerLists"
                                                item-text="name"
                                                item-value="id"
                                                outlined
                                                hide-details="auto"
                                                placeholder="Select Warehouse Customer"
                                                v-model="filtersValue.customers"
                                                clearable>
                                            </v-autocomplete>
                                        </v-col>

                                        <v-col cols="12" sm="12" class="pb-1">
                                            <p class="filter-menu-title">Classification Code</p>

                                            <v-text-field
                                                height="40px"
                                                color="#002F44"
                                                width="100%"
                                                type="text"
                                                dense
                                                class="text-fields custom"
                                                placeholder="Select Classification"
                                                outlined
                                                hide-details="auto"
                                                v-model="filtersValue.code"
                                                @wheel="$event.target.blur()">
                                            </v-text-field>
                                        </v-col>

                                        <v-col cols="12" sm="12" md="12" class="pb-1">
                                            <p class="filter-menu-title">Units In Each Carton</p>
                                            
                                            <v-row>
                                                <v-col cols="12" sm="12" md="6">
                                                    <v-text-field
                                                        height="40px"
                                                        color="#002F44"
                                                        width="100%"
                                                        type="number"
                                                        dense
                                                        class="text-fields custom"
                                                        placeholder="Enter Units"
                                                        outlined
                                                        hide-details="auto"
                                                        min="0"
                                                        v-model="filtersValue.in_each_carton_min"
                                                        @wheel="$event.target.blur()">
                                                    </v-text-field>
                                                    <span class="menu-subtitle">Minimum</span>
                                                </v-col>

                                                <v-col cols="12" sm="12" md="6">
                                                    <v-text-field
                                                        height="40px"
                                                        color="#002F44"
                                                        width="100%"
                                                        type="number"
                                                        dense
                                                        class="text-fields custom"
                                                        placeholder="Enter Units"
                                                        outlined
                                                        hide-details="auto"
                                                        min="0"
                                                        v-model="filtersValue.in_each_carton_max"
                                                        @wheel="$event.target.blur()">
                                                    </v-text-field>
                                                    <span class="menu-subtitle">Maximum</span>
                                                </v-col>
                                            </v-row>
                                        </v-col>

                                        <v-col cols="12" sm="12" md="12">
                                            <p class="filter-menu-title">Unit Price</p>
                                            
                                            <v-row>
                                                <v-col cols="12" sm="12" md="6">
                                                    <v-text-field
                                                        height="40px"
                                                        color="#002F44"
                                                        width="100%"
                                                        type="number"
                                                        dense
                                                        class="text-fields custom"
                                                        placeholder="Enter Price"
                                                        outlined
                                                        hide-details="auto"
                                                        min="0"
                                                        v-model="filtersValue.unit_price_min"
                                                        @wheel="$event.target.blur()">
                                                    </v-text-field>
                                                    <span class="menu-subtitle">Minimum</span>
                                                </v-col>

                                                <v-col cols="12" sm="12" md="6">
                                                    <v-text-field
                                                        height="40px"
                                                        color="#002F44"
                                                        width="100%"
                                                        type="number"
                                                        dense
                                                        class="text-fields custom"
                                                        placeholder="Enter Price"
                                                        outlined
                                                        hide-details="auto"
                                                        min="0"
                                                        v-model="filtersValue.unit_price_max"
                                                        @wheel="$event.target.blur()">
                                                    </v-text-field>
                                                    <span class="menu-subtitle">Maximum</span>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>
                                </div>
                            </form>
                        </template>

                        <template v-slot:filter_actions>
                            <v-btn class="btn-apply btn-blue" @click="applyFilters">
                                Apply
                            </v-btn>

                            <v-btn class="btn-cancel btn-white" @click="clearFilter">
                                Clear
                            </v-btn>
                        </template>
                    </FilterComponent> -->

                    <div class="search-component d-flex">
                        <SearchComponent
                            placeholder="Search Products"
                            :searchValue.sync="search"
                            :handleSearchComponent="handleSearchComponent"
                            @handleSearch="handleSearch"
                            @clearSearched="clearSearched" />
                            <!-- <v-btn
                                v-if="getProductItems.length > 0"
                                :disabled="getPdfReportLoading" 
                                class="btn-white ml-2 mr-0" 
                                @click="getpdfReport"
                                >
                                <span v-if="!getPdfReportLoading">Get Report</span>
                                <span v-else>Get Reporting...</span>
                            </v-btn> -->
                    </div>
                </div>
            </template>

            <template v-slot:no-data>
                <div class="no-data-preloader mt-4" v-if="getProductsLoading">
                    <v-progress-circular
                        :size="40"
                        color="#0171a1"
                        indeterminate>
                    </v-progress-circular>
                </div>
                                
                <div class="no-data-wrapper" v-if="!getProductsLoading && getProductItems.length == 0 && search === ''">
                    <div class="no-data-heading">
                        <img src="../../../assets/icons/empty-product-icon.svg" width="40px" height="42px" alt="">

                        <h3> Add Product </h3>
                        <p>
                            Let’s add the first product on Shifl. You can use this product list for creating Purchase Orders or for managing Inventory.
                        </p>

                        <div class="mt-4">
                            <v-btn color="primary" class="btn-blue add-product-button mx-auto" @click.stop="addProduct">
                                Add Product
                            </v-btn>                            
                        </div>
                    </div>
                </div>

                <div class="no-data-wrapper" v-if="!getProductsLoading && getProductItems.length == 0 && search !== ''">
                    <div class="no-data-heading">
                        <img src="../../../assets/icons/empty-product-icon.svg" width="40px" height="42px" alt="">

                        <div>
                            <h3> No matching result </h3>
                            <p> We couldn’t find any product that matches your search term.
                                <br/> Have you spelled it correctly? 
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </v-data-table>

        <PaginationComponent 
            :totalPage.sync="getTotalPage"
            :currentPage.sync="getCurrentPage"
            @handlePageChange="handlePageChange"
            :isMobile="isMobile" />

        <ConfirmDialog :dialogData.sync="pdfReportDialog">
            <template v-slot:dialog_icon>
				<div v-if="getPdfReportLoading && !pdfReportButtonShowCondition" class="header-wrapper-close">
                    <img src="@/assets/icons/progress.svg" style="transition-duration: 0.8s;transition-property: transform;" alt="alert">
                </div>
                <div v-else class="header-wrapper-close">
                    <img src="@/assets/icons/report-generate-file.svg" alt="alert">
                    <v-btn class="ma-0 pa-0" @click="pdfReportDialog =false" icon><v-icon color="#4A4A4A">mdi-close</v-icon></v-btn>
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2 v-if="getPdfReportLoading && !pdfReportButtonShowCondition">Preparing File...</h2>
                <h2 v-else>File is ready!</h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="getPdfReportLoading && !pdfReportButtonShowCondition"> 
					We are preparing your requested file. Please wait a few moment.
				</p>
                <p v-else>
                    We have prepared your requested file. Check below:
                </p>
			</template>

			<template v-slot:dialog_actions>
                <div v-if="getPdfReportLoading && !pdfReportButtonShowCondition">
                    <v-btn style="color: #4A4A4A !important;"  class="btn-white" text @click="closePdfReportDialog">
					Cancel
				</v-btn>
                </div>
                <div class="d-flex" v-else>
                    <v-btn class="btn-blue mr-1" @click="downloadPdfReportFile" text :disabled="getPdfReportLoading">
					<span>Download</span>
				</v-btn>
                <v-btn class="btn-white" style="color: #4A4A4A;" @click="previewPdfReportFile" text :disabled="getPdfReportLoading">
					<span style="color: #4A4A4A;">Preview</span>
				</v-btn>
                </div>	
			</template>
		</ConfirmDialog>

        <ConfirmDialog :dialogData.sync="csvReportDialog">
            <template v-slot:dialog_icon>
				<div v-if="getCsvReportLoading && !csvReportButtonShowCondition" class="header-wrapper-close">
                    <img src="@/assets/icons/progress.svg" style="transition-duration: 0.8s;transition-property: transform;" alt="alert">
                </div>
                <div v-else class="header-wrapper-close">
                    <img src="@/assets/icons/report-generate-file.svg" alt="alert">
                    <v-btn class="ma-0 pa-0" @click="csvReportDialog =false" icon><v-icon color="#4A4A4A">mdi-close</v-icon></v-btn>
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2 v-if="getCsvReportLoading && !csvReportButtonShowCondition">Preparing File...</h2>
                <h2 v-else>File is ready!</h2>
			</template>

			<template v-slot:dialog_content>
				<p v-if="getCsvReportLoading && !csvReportButtonShowCondition"> 
					We are preparing your requested file. Please wait a few moment.
				</p>
                <p v-else>
                    We have prepared your requested file. Download will be started automatically in a moment.
                </p>
			</template>

			<template v-slot:dialog_actions>
                <div v-if="getCsvReportLoading && !csvReportButtonShowCondition">
                    <v-btn style="color: #4A4A4A !important;"  class="btn-white" text @click="cancelCsvApi">
					Cancel
				</v-btn>
                </div>	
                <div v-else>
                    <v-btn style="color: #4A4A4A !important;"  class="btn-white" text @click="csvReportDialog = false">
					Close
				</v-btn>
                </div>
			</template>
		</ConfirmDialog>

        <ConfirmDialog :dialogData.sync="showArchiveDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/icon-delete.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2>Archive Product</h2>
			</template>

			<template v-slot:dialog_content>
				<p>Are you sure you want to archive this product? Once you confirm it will only be accessible through the archived page.</p>
			</template>

			<template v-slot:dialog_actions>
                <v-btn class="btn-blue" text @click="archiveProduct(currentSelectedProduct, true)" :disabled="getDoArchivedProductsLoading">
					{{ getDoArchivedProductsLoading ? 'Archiving...' : 'Archive Product' }}
				</v-btn>

				<v-btn class="btn-white" text @click="closeArchiveProduct" :disabled="getDoArchivedProductsLoading">
					Cancel
				</v-btn>
			</template>
		</ConfirmDialog>

        <ConfirmDialog :dialogData.sync="showUnableArchiveDialog">
			<template v-slot:dialog_icon>
				<div class="header-wrapper-close">
                    <img src="@/assets/icons/alert.svg" alt="alert">
                </div>
			</template>

			<template v-slot:dialog_title>
				<h2>Unable to Archive Product</h2>
			</template>

			<template v-slot:dialog_content>
				<p>You can not archive this product, because there is an inventory for this product within some Warehouse.</p>
			</template>

			<template v-slot:dialog_actions>
				<v-btn class="btn-blue" text @click="closeUnableArchive">
					Understood
				</v-btn>
			</template>
		</ConfirmDialog>     
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import _ from 'lodash'
import SearchComponent from '../../SearchComponent/SearchComponent.vue'
import PaginationComponent from '../../PaginationComponent/PaginationComponent.vue'
// import FilterComponent from '../../FilterComponent/FilterComponent.vue'
import ConfirmDialog from '../../Dialog/GlobalDialog/ConfirmDialog.vue'
import globalMethods from '../../../utils/globalMethods'
import jQuery from 'jquery'
import moment from 'moment'

import axios from 'axios'
var cancel
var CancelToken = axios.CancelToken

export default {
    name: "ProductDesktopTable",
    props: ['items', 'categoryLists', 'isMobile', 'productsNextPageLoading', 'searchVal'],
	components: {
        SearchComponent,
        PaginationComponent,
        ConfirmDialog
        // FilterComponent
    },
    data: () => ({
        page: 1,
        pageCount: 0,
        itemsPerPage: 35,
        headersDefault: [
			{
				text: "SKU & Category",
                align: "start left-fixed-column",
                sortable: false,
                value: "sku",
                width: "285px",
                // fixed: true
			},
			// {
			// 	text: "Name & Category",
            //     align: "start",
            //     sortable: false,
            //     value: "name",
            //     width: "20%", 
            //     fixed: true
			// },
            {
				text: "Contact",
                align: "start",
                sortable: false,
                value: "contact",
                width: "190px", 
                // width: "18%", 
                // fixed: true
			},
            {
				text: "Warehouse Customer",
                align: "start",
                sortable: false,
                value: "warehouse_customer.company_name",
                width: "190px",
                // fixed: true
			},
            {
				text: "Total Inventory",
                align: "end",
                sortable: false,
                value: "total_inventory",
                width: "130px", 
                // fixed: true
			},
            {
				text: "Available",
                align: "end",
                sortable: false,
                value: "available",
                width: "120px", 
                // fixed: true
			},
			{
				text: "In Each",
                align: "end",
                sortable: false,
                value: "units_per_carton",
                width: "100px", 
                // fixed: true
			},
            {
				text: "Unit Price",
                align: "end",
                sortable: false,
                value: "unit_price",
                width: "115px", 
                // fixed: true
			},
            {
				text: "Carton Dimension",
                align: "end",
                sortable: false,
                value: "carton_dimensions",
                width: "160px", 
                // fixed: true
			},
            {
				text: "Custom Duty",
                align: "end",
                sortable: false,
                value: "duty_rate",
                width: "140px", 
                // fixed: true
			},            
            {
				text: "sku_with_category",
                align: " d-none",
                sortable: false,
                value: "sku_with_category",
                width: "1px", 
                fixed: true
			},
			{
				text: "",
                align: "end action-column-th",
                sortable: false,
                value: "actions",
                width: "60px", 
                fixed: true
			},
		],
        // filters
        radioSortBy: 'name',
        radioSortByOptions: [
            {
                value: 'name',
                name: 'Name'
            },
            {
                value: 'category',
                name: 'Category'
            },
            {
                value: 'in_each_carton',
                name: 'Unit in each carton'
            },
            {
                value: 'unit_price',
                name: 'Unit Price'
            },
        ],
        filterMenu: false,
        filtersValue: {
            isAsc: true,
            selectedSort: '',
            category: '',
            customers: [],
            code: '',
            in_each_carton_min: '',
            in_each_carton_max: '',
            unit_price_min: '',
            unit_price_max: '',
            index: -1
        },
        // csv report
        csvReportButtonShowCondition: false,
        csvReportDialog: false,
        // pdf report
        pdfReportButtonShowCondition: false,
        cancelerrorIs: false,
        pdfReportDialog: false,
        exportDataSelected: '',
        exportDataPdfOrCsv: [
            { header: 'Product Data' },
            { name: 'Export as .CSV', group: 'Group 1' },
            { name: 'Export as .PDF', group: 'Group 1'},
            { divider: true },
            { header: 'Inventory Prediction Report' },
            { name: 'Export as .CSV', group: 'Group 2'},
            { name: 'Export as .PDF', group: 'Group 2'}
        ],
        currentSelectedProduct: null,
        showArchiveDialog: false,
        showUnableArchiveDialog: false,
        selectedProducts: [],
        isTableScrollable: false,
        expanded: [],
        singleExpand: true,
        currentWidth: '',
        pdfDataType: ''
	}),
	computed: {
		...mapGetters({
            getCategories: 'category/getCategories',
			getProducts: 'products/getProducts',
			getProductsLoading: 'products/getProductsLoading',
			getUser: 'getUser',
			getDeleteProductLoading: 'products/getDeleteProductLoading',
            getSearchedProductsLoading: 'products/getSearchedProductsLoading',
            getSearchedProducts: 'products/getSearchedProducts',            
			getHas3PLProviderWarehouse: 'warehouseCustomers/getHas3PLProviderWarehouse',
            getWarehouseCustomersDropdown: 'warehouseCustomers/getWarehouseCustomersDropdown',
            // pdf report
            getPdfReportLoading:'products/getPdfReportLoading',
            getSetPdfReportData:'products/getSetPdfReportData',
            getSetCsvReportData:'products/getSetCsvReportData',
            getCsvReportLoading:'products/getCsvReportLoading',
            getDoArchivedProductsLoading: 'products/getDoArchivedProductsLoading'
        }),
        search: {
            get() {
                return this.searchVal
            },
            set(value) {
                this.$emit('update:searchVal', value)
            }
        },
        getProductItems() {
            // return this.items
            let value = this.items

            value.map(v => {
                Object.keys(v).map((key) => {
                    if (v[key] === 'null') {
                        v[key] = ""
                    }
                })
            })

            return value
        },
        getTotalPage: {
            get() {
				let totalPage = 1

                if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
                    if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
                        if (typeof this.getSearchedProducts.last_page !== 'undefined') {
                            totalPage = this.getSearchedProducts.last_page
                        }
                    } else {
                        if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                            if (typeof this.getProducts.last_page !== 'undefined') {
                                totalPage = this.getProducts.last_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                        if (typeof this.getProducts.last_page !== 'undefined') {
                            totalPage = this.getProducts.last_page
                        }
                    }
                }

				return totalPage
            }
        },
        getCurrentPage: {
            get() {
                let currentPage = 1

                if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
                    if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
                        if (typeof this.getSearchedProducts.current_page !== 'undefined') {
                            currentPage = this.getSearchedProducts.current_page
                        }
                    } else {
                        if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                            if (typeof this.getProducts.current_page !== 'undefined') {
                                currentPage = this.getProducts.current_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                        if (typeof this.getProducts.current_page !== 'undefined') {
                            currentPage = this.getProducts.current_page
                        }
                    }
                }

				return currentPage
            },
            set() {
                return {}
            }
        }, 
		getItemsPerPage: {
            get() {
				let itemsPerPage = 1

                if (typeof this.getSearchedProducts !== 'undefined' && this.getSearchedProducts !== null) {
                    if (this.search !== '' && this.getSearchedProducts.tab === 'products') {
                        if (typeof this.getSearchedProducts.per_page !== 'undefined') {
                            itemsPerPage = this.getSearchedProducts.per_page
                        }
                    } else {
                        if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                            if (typeof this.getProducts.per_page !== 'undefined') {
                                itemsPerPage = this.getProducts.per_page
                            }
                        }
                    }
                } else {
                    if (typeof this.getProducts !== 'undefined' && this.getProducts !== null) {
                        if (typeof this.getProducts.per_page !== 'undefined') {
                            itemsPerPage = this.getProducts.per_page
                        }
                    }
                }

				return itemsPerPage
            }
        },
        handleLoadingShow() {
            if (this.search === '') {
                return this.productsNextPageLoading
            } else {
                return this.getSearchedProductsLoading
            }
        },
        handleSearchComponent() {
            let isShow = true

            if (this.search == '' && this.getProductItems.length === 0) {
                isShow = false
            } else if (this.search !== '' && this.getProductItems.length === 0) {
                isShow = true
            }

            return isShow
        },
        headersComputed() {
            let defaultHeaders = this.headersDefault
            if (!this.getHas3PLProviderWarehouse) {
                defaultHeaders = defaultHeaders.filter(v => {
                    return v.text !== 'Warehouse Customer'
                })
            }
            return defaultHeaders
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
        getSearchedDataClass() {
            if (this.getProductItems.length == 0 && this.search !== '') {
                return true
            } else {
                return false
            }
        }
	},
	created () {
	},
	methods: {
		...mapActions({
            fetchCategories: 'category/fetchCategories',
			fetchProducts: 'products/fetchProducts',
			createProducts: 'products/createProducts',
			getCurrentCategory: 'category/getCurrentCategory',
			deleteProduct: 'products/deleteProduct',
			updateProducts: 'products/updateProducts',            
			fetchSearchedProducts: 'products/fetchSearchedProducts',
			setSearchedProductsLoading: 'products/setSearchedProductsLoading',
			setSearchedProductsVal: 'products/setSearchedProductsVal',
			poBaseUrlState: 'products/poBaseUrlState',
            // pdf report
            fetchPdfReport:'products/fetchPdfReport',
            fetchCsvReport:'products/fetchCsvReport',
            fetchProductWarehouseBreakdown: 'products/fetchProductWarehouseBreakdown',
            fetchProductProjectionReport: 'products/fetchProductProjectionReport',            
            setArchiveProduct: 'products/setArchiveProduct',
            fetchPdfReportProduct: 'products/fetchPdfReportProduct',
            fetchCsvReportProduct: 'products/fetchCsvReportProduct'
        }),
        ...globalMethods,
        onResize() {
            const div = document.getElementsByClassName('v-data-table__wrapper')
            this.currentWidth = div[0].clientWidth - 60
            this.checkElementScroll()
            // var hasHorizontal = div[0].scrollWidth > div[0].clientWidth
            // if (hasHorizontal) {
            //     this.currentWidth = div[0].clientWidth
            // } else {
            //     this.currentWidth = div[0].clientWidth
            // }
        },
        handleUpliftScroll(e) {
            let targetScrollLeft = e.target.scrollLeft;
            var maxScrollLeftValue = e.target.scrollWidth - e.target.clientWidth;

            if (targetScrollLeft > 0) {
                jQuery('.left-fixed-column').addClass('show-box-shadow')
            } else {
                jQuery('.left-fixed-column').removeClass('show-box-shadow')
            }

            if (targetScrollLeft < maxScrollLeftValue) {
                jQuery('.action-column-th').addClass('show-box-shadow')
            } else {
                if (targetScrollLeft === maxScrollLeftValue) {
                    jQuery('.action-column-th').removeClass('show-box-shadow')
                }
            }
        },
		getImgUrl(pic) {
			if (pic !== 'undefined' && pic !== null) {
				return pic				
			} else {
				return require('../../../assets/icons/default-product-icon.svg')
			}
		},
		handleManageCategory() {
			this.$router.push(`products/manage-categories`)
		},
		getCategoryName(id) {
			if (this.categoryLists.length !== 0 && id) {
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
        async addProduct() {
            this.$emit('addProduct')
        },
        async editProduct(product, isFor) {
            let data = { product, isFor }
            this.$emit('editProduct', data)
        },
        deleteProductItem(product, isFor) {
            let data = { product, isFor }
            this.$emit('deleteProductItem', data)
        },
        async viewProductItem(item, isDataFor, index) {
            let isFor = null
            if (isDataFor === 'parent') {
                isFor = 'edit'
            } else {
                isFor = 'child'
                item.isForChildProduct = true

                let child_image = item.child_products[index].image
                item.child_image = child_image
            }

            let data = { item, isFor, index }
            this.$emit('viewProductItem', data)
            this.fetchProductWarehouseBreakdown(item.id)
            this.fetchProductProjectionReport(item.id)
        },
        getParsedAmount(amount) {
            return amount !== null && amount !== '' && amount !== 'null' ? parseFloat(amount).toFixed(2) : '0.00'
        },
        async handlePageChange(page) {
            if (this.search == '') {
                this.$emit('handlePageChange', page)
            } else {
                let data = { search: this.search, page }
                this.handlePageSearched(data)
            }

            this.handleScrollToTop()
        },
        clearSearched() {
            this.search = ''
            this.setSearchedProductsVal([])
            // document.getElementById("search-input").focus()
        },
		handleSearch() {
            if (cancel !== undefined) {
                cancel()
            }
            // this.setSearchedProductsLoading(false)
            clearTimeout(this.typingTimeout)
            this.typingTimeout = setTimeout(() => {
                let data = { 
                    search: this.search
                }  

                this.setSearchedProductsLoading(true)
                this.apiCall(data)
            }, 500)
        },
        apiCall(data) {
            if (data !== null && this.search !== '') {
                let passedData = {
                    method: "get",
                    url: `${process.env.VUE_APP_PO_URL}/v2/products`,
                    cancelToken: new CancelToken(function executor(c) {
                        cancel = c
                    }),
                    params: {
                        search: this.search,
                        page: 1
                    }
                }

                try {
                    passedData.tab = 'products'
                    this.fetchSearchedProducts(passedData)
                } catch(e) {
                    this.notificationError(e)
                    this.setSearchedProductsLoading(false)
                    console.log(e, 'Search error')
                }
            } else {
                this.setSearchedProductsVal([])
            }
        },
        async handlePageSearched(data) {
            let storePagination = this.$store.state.products.searchedProducts

            if (data !== null && this.search !== '') {
                if (storePagination.old_page !== data.page) {
                    storePagination.old_page = data.page

                    let passedData = {
                        method: "get",
                        url: 'https://po.shifl.com/api/v2/products',
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                        params: {
                            search: this.search,
                            page: data.page
                        }
                    }

                    try {
                        passedData.tab = 'products'
                        this.fetchSearchedProducts(passedData)
                    } catch(e) {
                        this.notificationError(e)
                        this.setSearchedProductsLoading(false)
                        console.log(e, 'Search error')
                    }
                }                
            } else {
                this.setSearchedProductsVal([])
            }

            this.handleScrollToTop()
        },
        handleScrollToTop() {
            var table = this.$refs['my-table']
            var wrapper = table.$el.querySelector('div.v-data-table__wrapper')
            
            this.$vuetify.goTo(table) // to table
            this.$vuetify.goTo(table, {container: wrapper}) // to header
        },
        changeValueFilter(val) {
            this.filtersValue.isAsc = true

            if (val !== -1) {
                this.filtersValue.index = val
            }
        },
        toggleAscDesc(val, n, index) {
            this.filtersValue.isAsc = val
            this.filtersValue.selectedSort = n.value
            this.filtersValue.index = index
        },
        applyFilters() {
            console.log(this.filtersValue)
        },
        clearFilter() {
            this.filtersValue = {
                isAsc: true,
                selectedSort: '',
                category: '',
                customers: [],
                code: '',
                in_each_carton_min: '',
                in_each_carton_max: '',
                unit_price_min: '',
                unit_price_max: '',
                index: -1
            }
        },
        async csvReportChangeFunction(value) {
            if (value !== '') {
                try {
                    this.csvReportButtonShowCondition = false
                    this.csvReportDialog = true
                    let dataWithPage = {
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                    }

                    if (value === 'inventory') {
                        await this.fetchCsvReport(dataWithPage)
                    } else if (value === 'product') {
                        await this.fetchCsvReportProduct(dataWithPage)
                    }

                    if (typeof this.getSetCsvReportData !== 'undefined' && this.getSetCsvReportData !== undefined && 
                        this.getSetCsvReportData !== null) {
                        var url = window.URL.createObjectURL(new Blob([this.getSetCsvReportData]));
                        let name = value === 'product' ? 'Product_Data_Report' : 'Inventory_Prediction_Report'
                        this.downloadCsvReportFile(`${name}.csv`, url)
                    }

                    this.csvReportButtonShowCondition = true
                } catch(e) {
                    this.csvReportButtonShowCondition = false
                    if (this.cancelerrorIs) return this.cancelerrorIs = false
                    this.notificationError(e)
                }                
            }
        },
        downloadCsvReportFile(fileName,urlData) {
            if (typeof this.getSetCsvReportData !=='undefined' && this.getSetCsvReportData !== undefined && 
                this.getSetCsvReportData !== null) {
                var aLink = document.createElement('a');
                aLink.download = fileName;
                aLink.href = urlData;
                document.body.appendChild(aLink);
                aLink.click();
                aLink.remove()
            }
        },
        async pdfReportChangeFunction(value) {
            if (value !== '') {
                try {
                    let currentLocalUserDate = moment().format('hh:mm A MMM DD, YYYY')
                    this.pdfReportButtonShowCondition = false
                    this.pdfReportDialog = true
                    this.pdfDataType = value
                    let dataWithPage = {
                        date: currentLocalUserDate,
                        cancelToken: new CancelToken(function executor(c) {
                            cancel = c
                        }),
                    }
                    if (value === 'inventory') {
                        await this.fetchPdfReport(dataWithPage)
                    } else if (value === 'product') {
                        await this.fetchPdfReportProduct(dataWithPage)
                    }

                    this.pdfReportButtonShowCondition = true
                    this.notificationMessage('Document has been downloaded.')
                } catch(e) {
                    this.pdfReportButtonShowCondition = false
                    if (this.cancelerrorIs) return this.cancelerrorIs = false
                    this.notificationError(e)
                }                
            }
        },
        closePdfReportDialog() {
            this.pdfReportDialog = false
            this.pdfReportButtonShowCondition = false
            if (cancel !== undefined && this.getPdfReportLoading) {
                this.cancelerrorIs = true
			    cancel()
		    }
        },
        cancelCsvApi(){
            this.csvReportDialog =false
            this.csvReportButtonShowCondition =false
            if (cancel !== undefined && this.getCsvReportLoading) {
                this.cancelerrorIs = true
			    cancel()
		    }
        },
        downloadPdfReportFile() {
            let name = ''

            if (this.pdfDataType !== '') {
                name = this.pdfDataType === 'inventory' ? 'Inventory_Prediction_Report' : 'Product_Data_Report'
            } else {
                name = 'productReport'
            }

            if (typeof this.getSetPdfReportData !=='undefined' && this.getSetPdfReportData !== undefined && 
                this.getSetPdfReportData !== null) {
                var url = window.URL.createObjectURL(new Blob([this.getSetPdfReportData]));
                var a = document.createElement("a");
                a.href = url;
                a.download = `${name}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
            }
        },
        previewPdfReportFile(){
            var fileURL = window.URL.createObjectURL(this.getSetPdfReportData);
             window.open(fileURL);
        },
        navigateArchivePage() {
            this.$router.push(`products/archived-products`)
        },
        async archiveProduct(item, isConfirm) {
            this.showArchiveDialog = true

            if (!isConfirm) {
                this.currentSelectedProduct = item
            } else {
                try {
                    await this.setArchiveProduct(item.id)
                    this.notificationCustom('Product has been marked archived.')
                    this.currentSelectedProduct = null
                    this.closeArchiveProduct()
                    this.fetchProducts(this.getCurrentPage)
                } catch(e) {
                    if (e === 'Bad Request') {
                        this.currentSelectedProduct = null
                        this.closeArchiveProduct()
                        this.showUnableArchiveDialog = true
                    } else {
                        this.notificationError(e)
                    }
                }
            }
        },
        closeArchiveProduct() {
            this.showArchiveDialog = false
        },
        closeUnableArchive() {
            this.currentSelectedProduct = null
            this.showUnableArchiveDialog = false
        },
        selectAllProducts(val) {
            this.$refs['my-table'].toggleSelectAll(val)
        },
        getUnitPrice(item) {
            if (typeof item !== 'undefined' && item !== null) {
                let total = (item).toFixed(2)
                return this.addCommaToNum(total)
            } else {
                return 0
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
        async editProductContact(product, isFor, index, item) {
            product.isDuplicate = false
            product.isDuplicateEdit = true
            product.duplicate_contact = product.product_contact
            product.parentItem = item
            product.image = product.image !== null ? this.getChildImageUrl(product.image) : null

            let data = { product, isFor, index }
            this.$emit('editProduct', data)   
        },
        duplicateProductContact(product, isFor, index, item) {
            product.isDuplicate = true
            product.isDuplicateEdit = false
            isFor = 'copy'
            product.duplicate_contact = product.product_contact
            product.parentItem = item
            product.image = product.image !== null ? this.getChildImageUrl(product.image) : null
            
            let data = { product, isFor, index }
            this.$emit('editProduct', data)
        },
        checkElementScroll() {
            const div = document.getElementsByClassName('v-data-table__wrapper')
            if (div[0].scrollWidth > div[0].clientWidth) {
                jQuery('.action-column-th').addClass('show-box-shadow')
            } else {
                if (div[0].scrollWidth === div[0].clientWidth) {
                    jQuery('.action-column-th').removeClass('show-box-shadow')
                }
            }
        }
	},
    mounted() {
        const tableWrapper = document.querySelector('.v-data-table__wrapper')
        tableWrapper.addEventListener('scroll', this.handleUpliftScroll)
    },
	updated() {
        this.checkElementScroll()
    }
}
</script>

<style lang="scss">
.products-items-desktop-wrapper {
    position: relative;
    height: 100%;

    .overlay {
        &.show {
            position: absolute;
            // top: 64px;
            top: 129px;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #fff !important;
            z-index: 100;
            cursor: auto;
            border: 1px solid #EBF2F5 !important;

            .preloader {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 3%;
                padding-top: 20px;
            }
        }
    }
}

.v-tooltip__content {
    &.menuable__content__active {
        background-color: #4a4a4a !important;
        line-height: 20px;
        padding: 8px 12px;
    
        &.top-arrow {
            overflow: inherit !important;
            z-index: 20;
            opacity: 1 !important;
    
            &::before {
                content: " ";
                position: absolute;
                top: 0;
                left: 45%;
                margin-top: -20px;
                border-width: 10px;
                border-style: solid;
                border-top: solid 10px transparent;
                border-left: solid 10px transparent;
                border-right: solid 10px transparent;
                border-bottom-color: #4a4a4a !important;
            }
        }
    }
}
</style>
