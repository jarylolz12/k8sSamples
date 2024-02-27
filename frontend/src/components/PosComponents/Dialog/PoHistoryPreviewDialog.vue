<template>
    <v-dialog v-model="dialog" max-width="1260px" content-class="po-history-dialog" :retain-focus="false" scrollable>
        <v-card class="dialog-wrapper">
            <v-card-title v-if="typeof getPoHistoryPreview !== 'undefined' && !getPoHistoryPreviewLoading">
                <span class="headline-wrapper">
                    <span class="headline">Preview of Historical {{ from }}</span>

                    <span class="headline-title">
                        Updated At 
                        <span class="headline-data"> 
                            {{ 
                                typeof getPoHistoryPreview !== 'undefined' &&
                                typeof getPoHistoryPreview.updated_at !== 'undefined' ?
                                formatDateDefault(getPoHistoryPreview.updated_at) : '--'
                            }} 
                        </span>
                    </span>

                    <span class="headline-title">
                        Updated By 
                        <span class="headline-data text-capitalize">  
                            {{
                                typeof getPoHistoryPreview !== 'undefined' &&
                                typeof getPoHistoryPreview.updated_by !== 'undefined' ?
                                getPoHistoryPreview.updated_by : '--'
                            }}
                        </span>
                    </span>

                    <span class="headline-title">
                        Updated Type 
                        <span class="headline-data text-capitalize"> 
                            {{
                                typeof getPoHistoryPreview !== 'undefined' &&
                                typeof getPoHistoryPreview.update_type !== 'undefined' ?
                                replaceString(getPoHistoryPreview.update_type) : '--'
                            }}
                        </span>
                    </span>
                </span>

                <button icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </button>
            </v-card-title>

            <v-card-text>
                <div class="history-preview-body" v-if="typeof getPoHistoryPreview !== 'undefined' && !getPoHistoryPreviewLoading">
                    <v-row>
						<v-col cols="12" 
                            :sm="history_update_type === 'so_created' || history_update_type === 'po_created' || history_update_type === 'issued_PI' || history_update_type === 'accepted_PI' ? 4 : 6">
							<div class="history-info mb-3">
								<p class="history-title">Vendor
                                    <span class="is-user" 
                                        v-if="typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.order_type === 'SO'">
                                        Issuer
                                    </span>
                                </p>

								<p class="history-data">
                                    {{
										typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
										getPoHistoryPreview.purchase_order.buyer_id !== null
											? getUserId(getPoHistoryPreview.purchase_order.customer_id)
											: checkIfDataIsUndefined(getPoHistoryPreview.purchase_order, 'supplier')
									}}
								</p>
							</div>

                            <div class="history-info mb-3">
								<p class="history-title">Buyer
                                    <span class="is-user" 
                                        v-if="typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.order_type === 'PO'">
                                        Issuer
                                    </span>
                                </p>

								<p class="history-data">
                                    {{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
										getPoHistoryPreview.purchase_order.buyer_id !== null
											? getPoHistoryPreview.purchase_order.buyer_company_name
											: checkIfDataIsUndefined(getPoHistoryPreview.purchase_order, 'customer')
									}}
								</p>
							</div>
						</v-col>

                        <v-col cols="12" 
                            :sm="history_update_type === 'so_created' || history_update_type === 'po_created' || history_update_type === 'issued_PI' || history_update_type === 'accepted_PI' ? 4 : 6">
							<div class="history-info mb-3">
								<p class="history-title">Import Name</p>
								<p class="history-data">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.import_name !== null ?
                                        getPoHistoryPreview.purchase_order.import_name : 'N/A'
                                    }}
								</p>
							</div>

                            <div class="history-info mb-3">
								<p class="history-title">Ship To</p>
								<p class="history-data warehouse-name">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.ship_to !== null &&
                                        getPoHistoryPreview.purchase_order.ship_to !== '' ?
                                        getPoHistoryPreview.purchase_order.ship_to : 'N/A'
                                    }}
								</p>
							</div>
						</v-col>

						<v-col cols="12 history-details-header-last-col" sm="4" 
                            v-if="history_update_type === 'so_created' || history_update_type === 'po_created' || history_update_type === 'issued_PI' || history_update_type === 'accepted_PI'">
							<div class="history-info history-method">
								<p class="history-title">Ship Via</p>
								<p class="history-data">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.ship_via !== null &&
                                        getPoHistoryPreview.purchase_order.ship_via !== '' ?
                                        getPoHistoryPreview.purchase_order.ship_via : 'N/A'
                                    }}
								</p>
							</div>

							<div class="history-info history-method">
								<p class="history-title">Incoterm</p>
                                <p class="history-data">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.terms !== null &&
                                        getPoHistoryPreview.purchase_order.terms !== '' ?
                                        getPoHistoryPreview.purchase_order.terms : 'N/A'
                                    }}
								</p>
                            </div>

							<div class="history-info history-method">
								<p class="history-title">Payment Terms</p>
                                <p class="history-data">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.payment_term !== null &&
                                        getPoHistoryPreview.purchase_order.payment_term !== '' ?
                                        getPoHistoryPreview.purchase_order.payment_term : 'N/A'
                                    }}
								</p>
							</div>

							<div class="history-info history-method">
								<p class="history-title">Cargo Ready</p>
                                <p class="history-data">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.cargo_ready_date !== null &&
                                        getPoHistoryPreview.purchase_order.cargo_ready_date !== '' ?
                                        formatDate(getPoHistoryPreview.purchase_order.cargo_ready_date) : 'N/A'
                                    }}
								</p>
							</div>

							<div class="history-info history-method">
								<p class="history-title">Must Arrive By</p>
                                <p class="history-data">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.must_arrive_by !== null &&
                                        getPoHistoryPreview.purchase_order.must_arrive_by !== '' ?
                                        formatDate(getPoHistoryPreview.purchase_order.must_arrive_by) : 'N/A'
                                    }}
								</p>
							</div>

							<!-- <div class="history-info history-method">
								<p class="history-title">Committed CRD</p>
                                <p class="history-data">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.committed_cargo_ready_date !== null ?
                                        formatDate(getPoHistoryPreview.purchase_order.committed_cargo_ready_date) : '--'
                                    }}
								</p>
							</div>

							<div class="history-info history-method">
								<p class="history-title">Required Deposite</p>
                                <p class="history-data" style="font-family: 'Inter-SemiBold', sans-serif;">
									{{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.required_deposit_value !== null ?
                                        `$${getPoHistoryPreview.purchase_order.required_deposit_value}` : 0
                                    }}
								</p>
							</div> -->
						</v-col>
					</v-row>

                    <div class="history-table-wrapper type-created" 
                        v-if="history_update_type === 'so_created' || history_update_type === 'po_created' || history_update_type === 'issued_PI' || history_update_type === 'accepted_PI'">

                        <v-data-table
                            :headers="headers"
                            :items="poProductItems"
                            class="elevation-0 view-table"
                            hide-default-footer>

                            <template v-slot:[`item.description`]="{ item }">
                                <p class="mb-0"> #{{ item.product.category_sku }} </p>
                                <p class="mb-0" style="color: #6D858F;"> {{ item.product.name }} </p>
                            </template> 

                            <template v-slot:[`item.volume`]="{ item }">
                                <p class="mb-0">
                                    {{ item.volume !== null ? parseFloat(item.volume).toFixed(2) : 0 }} CBM
                                </p>
                            </template> 

                            <template v-slot:[`item.weight`]="{ item }">
                                <p class="mb-0">
                                    {{ item.weight !== null ? item.weight : 0 }} KG
                                </p>
                            </template>

                            <template v-slot:[`item.unit_price`]="{ item }">
                                <p class="mb-0">
                                    ${{ item.unit_price !== null ? parseFloat(item.unit_price).toFixed(2) : 0 }}
                                </p>
                            </template> 

                            <template v-slot:[`item.amount`]="{ item }">
                                <p class="mb-0">
                                    ${{ item.amount !== null ? parseFloat(item.amount).toFixed(2) : 0 }}
                                </p>
                            </template> 
                        </v-data-table>

                        <div class="total-and-notes-wrapper">
                            <div class="history-notes-wrapper">
                                <p class="history-title">Notes</p>
                                <p>
                                    {{
                                        typeof getPoHistoryPreview !== 'undefined' &&
                                        typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                        getPoHistoryPreview.purchase_order.notes !== null ?
                                        getPoHistoryPreview.purchase_order.notes : '--'
                                    }}
                                </p>
                            </div>

                            <div class="history-totals-wrapper">                               
                                <div class="history-totals">
                                    <div class="history-totals-title font-medium">Subtotal</div>
                                    <div class="history-totals-data font-medium">
                                        {{
                                            typeof getPoHistoryPreview !== 'undefined' &&
                                            typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                            getPoHistoryPreview.purchase_order.sub_total !== null ?
                                            `$${parseFloat(getPoHistoryPreview.purchase_order.sub_total).toFixed(2)}` : 0
                                        }}
                                    </div>
                                </div>

                                <div class="history-totals second-item">
                                    <div class="history-totals-title">Tax</div>
                                    <div class="history-totals-data">
                                        {{
                                            typeof getPoHistoryPreview !== 'undefined' &&
                                            typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                            getPoHistoryPreview.purchase_order.tax !== null ?
                                            `$${parseFloat(getPoHistoryPreview.purchase_order.tax).toFixed(2)}` : 0
                                        }}
                                    </div>
                                </div>

                                <div class="history-totals last-item">
                                    <div class="history-totals-title font-semi-bold">Total</div>
                                    <div class="history-totals-data font-semi-bold">
                                        {{
                                            typeof getPoHistoryPreview !== 'undefined' &&
                                            typeof getPoHistoryPreview.purchase_order !== 'undefined' &&
                                            getPoHistoryPreview.purchase_order.total !== null ?
                                            `$${parseFloat(getPoHistoryPreview.purchase_order.total).toFixed(2)}` : 0
                                        }}
                                    </div>
                                </div>  
                            </div>                          
                        </div>
                    </div>

                    <div class="history-table-wrapper history-logs"
                        v-if="history_update_type !== 'so_created' && history_update_type !== 'po_created' || history_update_type !== 'issued_PI' || history_update_type !== 'accepted_PI'">

                        <v-data-table
                            :headers="headersWithChangeLog"
                            :items="poHistoryChangeLogs"
                            class="elevation-0 view-table"
                            hide-default-footer
                            :item-class="itemRowClass"
                            v-if="history_update_type === 'change_request'">

                            <template v-slot:[`item.text`]="{ item }">
                                <div class="po-name pad-16" v-if="typeof item.is_product !== 'undefined' && item.is_product">
                                    <p class="mb-0">SKU #{{ item.category_sku }} - {{ item.text }}</p>
                                    <p style="color: #6D858F;">{{ item.description }}</p>
                                    
                                    <div class="pl-4">
                                        <p class="mb-0 h-30">Carton</p>
                                        <p class="mb-0 h-30">In Each</p>
                                        <p class="mb-0 h-30">Unit</p>
                                        <p class="mb-0 h-30">Volume</p>
                                        <p class="mb-0 h-30">Weight</p>
                                        <p class="mb-0 h-30">Unit Price</p>
                                        <p class="mb-0 h-30">Amount</p>
                                    </div>
                                </div>

                                <div v-else class="pad-16"> {{ item.text }} </div>
                            </template>

                            <template v-slot:[`item.previous_data`]="{ item }">
                                <div class="po-previous-data" v-if="typeof item.is_product !== 'undefined' && item.is_product">                                    
                                    <div class="pl-4">
                                        <p class="mb-0 pad-16 updated-data">{{ item.quantity.previous_data }}</p>
                                        <p class="mb-0 pad-16 updated-data">{{ item.units_per_carton.previous_data }}</p>
                                        <p class="mb-0 pad-16 updated-data">{{ item.units.previous_data }}</p>
                                        <p class="mb-0 pad-16 updated-data">{{ parseFloat(item.volume.previous_data).toFixed(2) }} CBM</p>
                                        <p class="mb-0 pad-16 updated-data">{{ item.weight.previous_data }} KG</p>
                                        <p class="mb-0 pad-16 updated-data">${{ item.unit_price.previous_data }}</p>
                                        <p class="mb-0 pad-16 updated-data">${{ item.amount.previous_data }}</p>
                                    </div>
                                </div>

                                <div v-else class="pad-16"> {{ item.previous_data }} </div>
                            </template>

                            <template v-slot:[`item.updated_data`]="{ item }">
                                <div class="po-previous-data" v-if="typeof item.is_product !== 'undefined' && item.is_product">                                    
                                    <div class="w-100">
                                        <p class="mb-0 pad-16 updated-data" :class="getClassUpdated(item.quantity)">
                                            {{ item.quantity.updated_data }}
                                        </p>
                                        <p class="mb-0 pad-16 updated-data" :class="getClassUpdated(item.units_per_carton)">
                                            {{ item.units_per_carton.updated_data }}
                                        </p>
                                        <p class="mb-0 pad-16 updated-data" :class="getClassUpdated(item.units)">
                                            {{ item.units.updated_data }}
                                        </p>
                                        <p class="mb-0 pad-16 updated-data" :class="getClassUpdated(item.volume)">
                                            {{ parseFloat(item.volume.updated_data).toFixed(2) }} CBM
                                        </p>
                                        <p class="mb-0 pad-16 updated-data" :class="getClassUpdated(item.weight)">
                                            {{ item.weight.updated_data }} KG
                                        </p>
                                        <p class="mb-0 pad-16 updated-data" :class="getClassUpdated(item.unit_price)">
                                            ${{ item.unit_price.updated_data }}
                                        </p>
                                        <p class="mb-0 pad-16 updated-data" :class="getClassUpdated(item.amount)">
                                            ${{ item.amount.updated_data }}
                                        </p>
                                    </div>
                                </div>

                                <div v-else class="pad-16 updated-to"> {{ item.updated_data }} </div>
                            </template>
                        </v-data-table>

                        <v-data-table
                            :headers="headersWithChangeLog"
                            :items="poHistoryChangeLogs"
                            class="elevation-0 view-table"
                            hide-default-footer
                            :item-class="itemRowClass"
                            v-if="history_update_type === 'update_status'">

                            <template v-slot:[`item.text`]="{ item }">
                                <div class="po-name" v-if="typeof item.is_product !== 'undefined' && item.is_product">
                                    <p class="mb-0">SKU #{{ item.category_sku }} - {{ item.text }}</p>
                                    <p style="color: #6D858F;">{{ item.description }}</p>                                    

                                    <div v-if="!item.isBothEmpty">
                                        <div v-if="item.previous.length > item.updated_to.length">
                                            <div class="mb-3" v-for="(v, i) in item.previous.length" :key="i">
                                                <p class="mb-0 pl-4" v-if="item.previous.length > 1">Product Batch {{ i+ 1 }}</p>
                                                <div class="pl-10">
                                                    <p class="mb-0 h-30">Carton</p>
                                                    <p class="mb-0 h-30">In Each</p>
                                                    <p class="mb-0 h-30">Unit</p>
                                                    <p class="mb-0 h-30">Volume</p>
                                                    <p class="mb-0 h-30">Weight</p>
                                                    <p class="mb-0 h-30">Production Status</p>
                                                    <p class="mb-0 h-30">Expected CRD</p>
                                                </div>
                                            </div>    
                                        </div>

                                        <div v-if="item.updated_to.length > item.previous.length || 
                                            item.updated_to.length === item.previous.length">

                                            <div class="mb-3" v-for="(v, i) in item.updated_to.length" :key="i">
                                                <p class="mb-0 pl-4" v-if="item.updated_to.length > 1">Product Batch {{ i+ 1 }}</p>
                                                <div class="pl-10">
                                                    <p class="mb-0 h-30">Carton</p>
                                                    <p class="mb-0 h-30">In Each</p>
                                                    <p class="mb-0 h-30">Unit</p>
                                                    <p class="mb-0 h-30">Volume</p>
                                                    <p class="mb-0 h-30">Weight</p>
                                                    <p class="mb-0 h-30">Production Status</p>
                                                    <p class="mb-0 h-30">Expected CRD</p>
                                                </div>
                                            </div>
                                        </div>                                                                                                                 

                                        <div class="unit-price-amount">
                                            <p class="mb-0 pad-other-item">Unit Price</p>
                                            <p class="mb-0 pad-other-item">Amount</p>
                                        </div>
                                    </div>

                                    <div v-if="item.isBothEmpty">
                                        <div class="pl-4">
                                            <p class="mb-0 h-30">Carton</p>
                                            <p class="mb-0 h-30">In Each</p>
                                            <p class="mb-0 h-30">Unit</p>
                                            <p class="mb-0 h-30">Volume</p>
                                            <p class="mb-0 h-30">Weight</p>
                                            <p class="mb-0 h-30">Unit Price</p>
                                            <p class="mb-0 h-30">Amount</p>
                                        </div>
                                    </div>
                                </div>

                                <div v-else class="pad-16"> {{ item.text }} </div>
                            </template>

                            <template v-slot:[`item.previous_data`]="{ item }">
                                <div class="po-previous-data update-status" 
                                    v-if="typeof item.is_product !== 'undefined' && item.is_product">
                                    <p class="mb-0 c-white">.</p>
                                    <p class="c-white">.</p>

                                    <div v-if="!item.isBothEmpty">
                                        <div class="pl-4 mb-3" v-for="(p_item, i) in item.previous" :key="i">
                                            <p class="mb-0 c-white" v-if="i > 0">.</p>
                                            
                                            <p class="mb-0 updated-data pad-16"> {{ p_item.carton }} </p>
                                            <p class="mb-0 updated-data pad-16"> {{ item.in_each }} </p>
                                            <p class="mb-0 updated-data pad-16"> {{ p_item.unit }} </p>
                                            <p class="mb-0 updated-data pad-16">
                                                {{ (item.volume !== null ? parseFloat(item.volume).toFixed(2) : 0) + ' CBM' }}
                                            </p>
                                            <p class="mb-0 updated-data pad-16">
                                                {{ (item.weight !== null ? item.weight : 0) + ' KG'  }}
                                            </p>
                                            <p class="mb-0 updated-data pad-16"> {{ p_item.production_status }} </p>
                                            <p class="mb-0 updated-data pad-16"> 
                                                {{ formatDate(p_item.expected_cargo_ready_date) }}
                                            </p>
                                        </div>

                                        <!-- show if previous is empty and updated to is not -->
                                        <div v-if="item.previous.length === 0 && item.updated_to.length > 0">
                                            <div v-for="(p_item, i) in item.updated_to" :key="i" class="mb-3">
                                                <p class="mb-0 c-white" v-if="i > 0">.</p>

                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                            </div>
                                        </div>

                                        <!-- show if a batch don't have a previous but has updated to value -->
                                        <div v-if="item.previous.length !== 0 && (item.previous.length < item.updated_to.length)">
                                            <div v-for="(p_item, i) in item.updated_to" :key="i" class="mb-3">
                                                <div v-if="item.previous[i] === undefined">
                                                    <p class="mb-0 c-white" v-if="i > 0">.</p>

                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="unit-price-amount w-100">
                                            <p class="mb-0 pad-16">
                                                ${{ item.unit_price !== null && item.unit_price !== '' ? parseFloat(item.unit_price).toFixed(2) : 0 }}
                                            </p>
                                            <p class="mb-0 pad-16">
                                                ${{ item.amount !== null && item.amount !== '' ? parseFloat(item.amount).toFixed(2) : 0 }}
                                            </p>
                                        </div>
                                    </div>

                                    <div v-if="item.isBothEmpty">
                                        <p class="mb-0 updated-data pad-16">{{ item.carton }}</p>
                                        <p class="mb-0 updated-data pad-16">{{ item.in_each }}</p>
                                        <p class="mb-0 updated-data pad-16">{{ item.units }}</p>
                                        <p class="mb-0 updated-data pad-16">
                                            {{ item.volume !== null ? parseFloat(item.volume).toFixed(2) : 0 }} CBM
                                        </p>
                                        <p class="mb-0 updated-data pad-16">
                                            {{ item.weight !== null ? item.weight : 0 }} KG
                                        </p>
                                        <p class="mb-0 updated-data pad-16">${{ parseFloat(item.unit_price).toFixed(2) }}</p>
                                        <p class="mb-0 updated-data pad-16">${{ parseFloat(item.amount).toFixed(2) }}</p>
                                    </div>
                                </div>

                                <div v-else class="pad-16"> 
                                    {{ item.previous_data !== null && item.previous_data !== '' ? item.previous_data : 'N/A' }} 
                                </div>
                            </template>

                            <template v-slot:[`item.updated_data`]="{ item }">
                                <div class="po-previous-data update-status" 
                                    v-if="typeof item.is_product !== 'undefined' && item.is_product">
                                    <p class="mb-0 c-white">.</p>
                                    <p class="c-white">.</p>

                                    <div class="w-100" v-if="!item.isBothEmpty">
                                        <div class="w-100 mb-3" v-for="(p_item, i) in item.updated_to" :key="i">
                                            <p class="mb-0 c-white" v-if="i > 0">.</p>
                                            <p class="mb-0 updated-data pad-16" :class="getUpdatedClassNew(item, 'carton', i)">
                                                {{ p_item.carton }}
                                            </p>
                                            <p class="mb-0 updated-data pad-16" :class="getUpdatedClassNew(item, '', i)">
                                                {{ item.in_each }}
                                            </p>
                                            <p class="mb-0 updated-data pad-16" :class="getUpdatedClassNew(item, 'unit', i)">
                                                {{ p_item.unit }}
                                            </p>
                                            <p class="mb-0 updated-data pad-16" :class="getUpdatedClassNew(item, '', i)">
                                                {{ item.volume !== null ? parseFloat(item.volume).toFixed(2) : 0 }} CBM
                                            </p>
                                            <p class="mb-0 updated-data pad-16" :class="getUpdatedClassNew(item, '', i)">
                                                {{ item.weight !== null ? item.weight : 0 }} KG
                                            </p>
                                            <p class="mb-0 updated-data pad-16" :class="getUpdatedClassNew(item, 'status', i)"> 
                                                {{ p_item.production_status }}
                                            </p>
                                            <p class="mb-0 updated-data pad-16" :class="getUpdatedClassNew(item, 'date',i)"> 
                                                {{ formatDate(p_item.expected_cargo_ready_date) }}
                                            </p>
                                        </div>

                                        <!-- show if updated to is empty and previous to is not -->
                                        <div v-if="item.updated_to.length === 0 && item.previous.length !== 0" class="w-100">
                                            <div v-for="(p_item, i) in item.previous" :key="i" class="w-100 mb-3">
                                                <p class="mb-0 c-white" v-if="i > 0">.</p>

                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                                <p class="mb-0 updated-data pad-16">-</p>
                                            </div>
                                        </div>

                                        <!-- show if a batch don't have a updated to but has previous value -->
                                        <div v-if="item.updated_to.length < item.previous.length" class="w-100">
                                            <div v-for="(p_item, i) in item.previous" :key="i" class="w-100 mb-3">
                                                <div v-if="item.updated_to[i] === undefined" class="w-100">
                                                    <p class="mb-0 c-white" v-if="i > 0">.</p>

                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                    <p class="mb-0 updated-data pad-16">-</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="unit-price-amount w-100">
                                            <p class="mb-0 pad-16">
                                                ${{ item.unit_price !== null && item.unit_price !== '' ? parseFloat(item.unit_price).toFixed(2) : 0 }}
                                            </p>
                                            <p class="mb-0 pad-16">
                                                ${{ item.amount !== null && item.amount !== '' ? parseFloat(item.amount).toFixed(2) : 0 }}
                                            </p>
                                        </div>
                                    </div>

                                    <div v-if="item.isBothEmpty" class="w-100">
                                        <p class="mb-0 updated-data pad-16">{{ item.carton }}</p>
                                        <p class="mb-0 updated-data pad-16">{{ item.in_each }}</p>
                                        <p class="mb-0 updated-data pad-16">{{ item.units }}</p>
                                        <p class="mb-0 updated-data pad-16">
                                            {{ item.volume !== null ? parseFloat(item.volume).toFixed(2) : 0 }} CBM
                                        </p>
                                        <p class="mb-0 updated-data pad-16">
                                            {{ item.weight !== null ? item.weight : 0 }} KG
                                        </p>
                                        <p class="mb-0 updated-data pad-16">${{ parseFloat(item.unit_price).toFixed(2) }}</p>
                                        <p class="mb-0 updated-data pad-16">${{ parseFloat(item.amount).toFixed(2) }}</p>
                                    </div>
                                </div>

                                <div v-else class="pad-16"> 
                                    {{ item.updated_data !== null && item.updated_data !== '' ? item.updated_data : 'N/A' }} 
                                </div>
                            </template>
                        </v-data-table>
                    </div>
                    <div v-if="getSignatureDetails ">
									<div class="d-flex" v-if="updateTypeSignature == 'issued_PI' || updateTypeSignature ==  'accepted_PI'">
										<div
                                            class="history-signature-wrapper mr-4"
											v-for="(item, index) in getSignatureDetails"
											:key="index"
										>
											<p class="history-title mb-0">
												{{ item.buyer_signature ? "Buyer" : "Vendor" }}
                                                Signature
											</p>
											<div v-if="item.vendor_signature && (updateTypeSignature == 'issued_PI' || updateTypeSignature ==  'accepted_PI')" class="">
												<p class="mb-0 sign-text">
													{{
														item.vendor_signature
															? item.vendor_signature
															: "N/A"
													}}
												</p>
												<p class="mb-0 sign-text">
													{{
														item.vendor_signature_date
															? item.vendor_signature_date
															: "N/A"
													}}
												</p>
											</div>
											<div v-if="item.buyer_signature && (updateTypeSignature == 'accepted_PI')">
												<p class="mb-0 sign-text">
													{{
														item.buyer_signature ? item.buyer_signature : "N/A"
													}}
												</p>
												<p class="mb-0 sign-text">
													{{
														item.buyer_signature_date
															? item.buyer_signature_date
															: "N/A"
													}}
												</p>
											</div>
										</div>
									</div>
								</div>
                </div>

                <div class="loading-wrapper" v-else>
                    <div class="loading-content" v-if="getPoHistoryPreviewLoading">
                        <v-progress-circular
                            :size="40"
                            color="#0171a1"
                            indeterminate>
                        </v-progress-circular>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import inventoryGlobalMethods from '../../../utils/inventoryMethods/inventoryGlobalMethods'
import _ from 'lodash'

export default {
    name: 'PoHistoryPreviewDialog',
    components: {},
    props: ['dialogData', 'from',"updatedType"],
    data: () => ({
        headers: [
			{
				text: 'SKU & Description',
				align: 'start',
				sortable: false,
				value: 'description',
				fixed: true,
				width: "32%"
			},
			{
				text: 'Carton',
				align: 'end',
				sortable: false,
				value: 'quantity',
				fixed: true,
				width: "8%"
			},
			{
				text: 'In Each',
				align: 'end',
				sortable: false,
				value: 'units_per_carton',
				fixed: true,
				width: "8%"
			},
			{
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'units',
				fixed: true,
				width: "8%"
			},
			{
				text: 'Volume',
				align: 'end',
				sortable: false,
				value: 'volume',
				fixed: true,
				width: "10%"
			},
            {
				text: 'Weight',
				align: 'end',
				sortable: false,
				value: 'weight',
				fixed: true,
				width: "10%"
			},
            // {
			// 	text: 'Product Status',
			// 	align: 'start',
			// 	sortable: false,
			// 	value: 'production_status',
			// 	fixed: true,
			// 	width: "15%"
			// },
            // {
			// 	text: 'Exp CRD',
			// 	align: 'end',
			// 	sortable: false,
			// 	value: 'exp_crd',
			// 	fixed: true,
			// 	width: "10%"
			// },
            {
				text: 'Unit Price',
				align: 'end',
				sortable: false,
				value: 'unit_price',
				fixed: true,
				width: "10%"
			},
            {
				text: 'Amount',
				align: 'end',
				sortable: false,
				value: 'amount',
				fixed: true,
				width: "15%"
			},
		],
        headersWithChangeLog: [
            {
				text: 'Fields',
				align: 'start',
				sortable: false,
				value: 'text',
				fixed: true,
				width: "20%"
			},
			{
				text: 'Previous',
				align: 'end',
				sortable: false,
				value: 'previous_data',
				fixed: true,
				width: "10%"
			},
			{
				text: 'Updated To',
				align: 'end',
				sortable: false,
				value: 'updated_data',
				fixed: true,
				width: "10%"
			},
        ]
    }),
    mounted() {},
    computed: {
        ...mapGetters({
            getPoHistoryPreview: 'poDetails/getPoHistoryPreview',
            getPoHistoryPreviewLoading: 'poDetails/getPoHistoryPreviewLoading',
            getUser: "getUser",
            getBuyerLists: "salesOrders/getBuyerLists",
        }),
        dialog: {
            get () {
                return this.dialogData
            },
            set (value) {
                this.$emit('update:dialogData', value)
            }
        },
        poProductItems() {
            let items = []

            if (typeof this.getPoHistoryPreview !== 'undefined' && this.getPoHistoryPreview !== null) {
                if (typeof this.getPoHistoryPreview.purchase_order !== 'undefined' && 
                    this.getPoHistoryPreview.purchase_order !== null) {
                    items = this.getPoHistoryPreview.purchase_order.purchase_order_products
                }
            }

            return items
        },
        poHistoryChangeLogs() {
            let poData = this.getPoHistoryPreview
            let products = []
            let productsField = []
            let finalFields = []

            if (typeof poData !== 'undefined' && poData !== null && 
                typeof poData.purchase_order !== 'undefined' && poData.purchase_order !== null) {

                if (typeof poData.purchase_order.purchase_order_products !== 'undefined') {
                    products = poData.purchase_order.purchase_order_products
                }

                if (this.history_update_type === 'change_request') {
                    productsField = products.map(v => {
                        let { product, quantity, units_per_carton, units, volume, weight, unit_price, amount,  history_change_logs } = v

                        return {
                            quantity: {
                                previous_data: this.getProductsHistoryValue(history_change_logs, 'quantity', 'old') === null ? 
                                    quantity : this.getProductsHistoryValue(history_change_logs, 'quantity', 'old'),
                                updated_data: this.getProductsHistoryValue(history_change_logs, 'quantity', 'updated') === null ? 
                                    quantity : this.getProductsHistoryValue(history_change_logs, 'quantity', 'updated'),
                            },
                            units_per_carton: {
                                previous_data: this.getProductsHistoryValue(history_change_logs, 'units_per_carton', 'old') === null ? 
                                    units_per_carton : this.getProductsHistoryValue(history_change_logs, 'units_per_carton', 'old'),
                                updated_data: this.getProductsHistoryValue(history_change_logs, 'units_per_carton', 'updated') === null ? 
                                    units_per_carton : this.getProductsHistoryValue(history_change_logs, 'units_per_carton', 'updated'),
                            },
                            units: {
                                previous_data: this.getProductsHistoryValue(history_change_logs, 'units', 'old') === null ? 
                                    units : this.getProductsHistoryValue(history_change_logs, 'units', 'old'),
                                updated_data: this.getProductsHistoryValue(history_change_logs, 'units', 'updated') === null ? 
                                    units : this.getProductsHistoryValue(history_change_logs, 'units', 'updated'),
                            },
                            volume: {
                                previous_data: this.getProductsHistoryValue(history_change_logs, 'volume', 'old') === null ? 
                                    volume : this.getProductsHistoryValue(history_change_logs, 'volume', 'old'),
                                updated_data: this.getProductsHistoryValue(history_change_logs, 'volume', 'updated') === null ? 
                                    volume : this.getProductsHistoryValue(history_change_logs, 'volume', 'updated'),
                            },
                            weight: {
                                previous_data: this.getProductsHistoryValue(history_change_logs, 'weight', 'old') === null ? 
                                    weight : this.getProductsHistoryValue(history_change_logs, 'weight', 'old'),
                                updated_data: this.getProductsHistoryValue(history_change_logs, 'weight', 'updated') === null ? 
                                    weight : this.getProductsHistoryValue(history_change_logs, 'weight', 'updated'),
                            },
                            unit_price: {
                                previous_data: this.getProductsHistoryValue(history_change_logs, 'unit_price', 'old') === null ? 
                                    unit_price : this.getProductsHistoryValue(history_change_logs, 'unit_price', 'old'),
                                updated_data: this.getProductsHistoryValue(history_change_logs, 'unit_price', 'updated') === null ? 
                                    unit_price : this.getProductsHistoryValue(history_change_logs, 'unit_price', 'updated'),
                            },
                            amount: {
                                previous_data: this.getProductsHistoryValue(history_change_logs, 'amount', 'old') === null ? 
                                    amount : this.getProductsHistoryValue(history_change_logs, 'amount', 'old'),
                                updated_data: this.getProductsHistoryValue(history_change_logs, 'amount', 'updated') === null ? 
                                    amount : this.getProductsHistoryValue(history_change_logs, 'amount', 'updated'),
                            },
                            history_change_logs,
                            text: product.name,
                            category_sku: product.category_sku,
                            description: product.description,
                            is_product: true,
                            class: 'products'
                        }
                    })
                } else {
                    productsField = products.map(v => {
                        let { product, quantity, units_per_carton, units, volume, weight, unit_price, amount, production_batch } = v                        
                        let isBothEmpty = production_batch !== null ? 
                            production_batch.previous.length === 0 && production_batch.updated_to.length === 0 : true

                        return {
                            carton: quantity,
                            in_each: units_per_carton,
                            units,
                            volume,
                            weight,
                            unit_price,
                            amount,
                            previous: production_batch !== null ? production_batch.previous : [],
                            updated_to: production_batch !== null ? production_batch.updated_to : [],
                            text: product.name,
                            category_sku: product.category_sku,
                            description: product.description,
                            is_product: true,
                            class: 'products',
                            isBothEmpty
                        }
                    })
                }

                finalFields = [
                    {
                        text: 'Ship Via',
                        previous_data: this.getHistoryValue('ship_via', 'old') === null ? 
                            poData.purchase_order.ship_via : this.getHistoryValue('ship_via', 'old'),
                        updated_data: this.getHistoryValue('ship_via', 'updated') === null ? 
                            poData.purchase_order.ship_via : this.getHistoryValue('ship_via', 'updated'),
                        class: 'ship_via'
                    },
                    {
                        text: 'Incoterm',
                        previous_data: this.getHistoryValue('terms', 'old') === null ? 
                            poData.purchase_order.terms : this.getHistoryValue('terms', 'old'),
                        updated_data: this.getHistoryValue('terms', 'updated') === null ? 
                            poData.purchase_order.terms : this.getHistoryValue('terms', 'updated'),
                        class: 'terms'
                    },
                    {
                        text: 'Payment Terms',
                        previous_data: this.getHistoryValue('payment_term', 'old') === null ? 
                            poData.purchase_order.payment_term : this.getHistoryValue('payment_term', 'old'),
                        updated_data: this.getHistoryValue('payment_term', 'updated') === null ? 
                            poData.purchase_order.payment_term : this.getHistoryValue('payment_term', 'updated'),
                        class: 'payment_term'
                    },
                    {
                        text: 'Cargo Ready',
                        previous_data: this.getHistoryValue('cargo_ready_date', 'old') === null ? 
                            this.formatDate(poData.purchase_order.cargo_ready_date) : 
                            this.getHistoryValue('cargo_ready_date', 'old'),
                        updated_data: this.getHistoryValue('cargo_ready_date', 'updated') === null ? 
                            this.formatDate(poData.purchase_order.cargo_ready_date) : 
                            this.getHistoryValue('cargo_ready_date', 'updated'),
                        class: 'cargo_ready_date'
                    },
                    {
                        text: 'Must Arrive By',
                        previous_data: this.getHistoryValue('must_arrive_by', 'old') === null ? 
                            this.formatDate(poData.purchase_order.must_arrive_by) : 
                            this.getHistoryValue('must_arrive_by', 'old'),
                        updated_data: this.getHistoryValue('must_arrive_by', 'updated') === null ? 
                            this.formatDate(poData.purchase_order.must_arrive_by) : 
                            this.getHistoryValue('must_arrive_by', 'updated'),
                        class: 'must_arrive_by'
                    },
                    {
                        text: 'Committed CRD',
                        previous_data: this.getHistoryValue('committed_cargo_ready_date', 'old') === null ? 
                            this.formatDate(poData.purchase_order.committed_cargo_ready_date) : 
                            this.getHistoryValue('committed_cargo_ready_date', 'old'),
                        updated_data: this.getHistoryValue('committed_cargo_ready_date', 'updated') === null ? 
                            this.formatDate(poData.purchase_order.committed_cargo_ready_date) : 
                            this.getHistoryValue('committed_cargo_ready_date', 'updated'),
                        class: 'committed_cargo_ready_date'
                    },
                    ...productsField,
                    {
                        text: 'Notes',
                        previous_data: this.getHistoryValue('production_notes', 'old') === null ? 
                            poData.purchase_order.production_notes : this.getHistoryValue('production_notes', 'old'),
                        updated_data: this.getHistoryValue('production_notes', 'updated') === null ? 
                            poData.purchase_order.production_notes : this.getHistoryValue('production_notes', 'updated'),
                        class: 'notes'
                    },
                ]                
            }            

            return finalFields
        },
        history_update_type() {
            let poData = this.getPoHistoryPreview
            let type = ''            

            if (typeof poData !== 'undefined' && poData !== null && poData.update_type !== null) {
                type = poData.update_type
            }

            return type
        },
        updateTypeSignature(){
            let type = ""
            if(typeof this.updatedType !== 'undefined' && this.updatedType !== null && this.updatedType.update_type !== 'undefined' && this.updatedType.update_type !== null){
                type = this.updatedType.update_type
            }
            else{
                type = ""
            }
            return type
            
        },
        getSignatureDetails() {
			return this.getPoHistoryPreview !== undefined && typeof this.getPoHistoryPreview.purchase_order !== 'undefined' &&
            this.getPoHistoryPreview.purchase_order.signature_details !== null &&
				this.getPoHistoryPreview.purchase_order.signature_details !== undefined
				? JSON.parse(this.getPoHistoryPreview.purchase_order.signature_details)
				: "";
		},
    },
    methods: {
        ...mapActions({}),
        ...inventoryGlobalMethods,
        formatDateDefault(date) {
            return this.formatTimeAndDateTogether(date)
        },
        formatDate(date) {
            return this.formatDateOnly(date)
        },
        replaceString(data) {
            if (data !== null) {
                if (data === 'so_created') {
                    data = 'SO_Created'
                } else if (data === 'po_created') {
                    data = 'PO_Created'
                }

                return data.replace(/_+/g, ' ');
            }
        },
        close() {
            this.$emit('close')
        },
        getHistoryValue(item, currentGetVal) {
            let poData = this.getPoHistoryPreview            

            if (typeof poData !== 'undefined' && poData !== null && typeof poData.purchase_order !== 'undefined' &&
                poData.purchase_order !== null && typeof poData.purchase_order.history_change_logs !== 'undefined') {
                let value = _.find(poData.purchase_order.history_change_logs, e => (item === e.field))

                if (value !== undefined) {
                    if (currentGetVal === 'updated') {
                        if (item === 'cargo_ready_date' || item === 'must_arrive_by' || item === 'committed_cargo_ready_date') {
                            return this.formatDate(value.new_value)
                        } else {
                            return value.new_value
                        }                    
                    } else {
                        if (item === 'cargo_ready_date' || item === 'must_arrive_by' || item === 'committed_cargo_ready_date') {
                            return this.formatDate(value.old_value)
                        } else {
                            return value.old_value
                        } 
                    }
                } else {
                    return null
                }
            } else {
                return null
            }            
        },
        getProductsHistoryValue(historyData, item, currentGetVal) {
            let value = _.find(historyData, e => (item === e.field))

            if (value !== undefined) {
                if (currentGetVal === 'updated') {
                    return value.new_value                 
                } else {
                    return value.old_value
                }
            } else {
                return null
            }
        },
        itemRowClass(item) {
            if (item !== null) return item.class
            else return ''
		},
        getClassUpdated(item) {
            if (item !== null) {
                if (item.previous_data !== item.updated_data) {
                    return 'not-equal'
                } else return ''
            }
        },
        // for update status type
        getUpdatedClassNew(item, isFor, i) {
            if (item.previous.length === 0 && item.updated_to.length > 0) {
                return 'not-equal'
            } else if (
                (item.previous.length !== 0 && (item.previous.length < item.updated_to.length)) ||
                item.previous.length === item.updated_to.length || 
                (item.previous.length > item.updated_to.length)) {

                let previous = item.previous
                let updated = item.updated_to                

                if (previous[i] !== undefined && updated[i] !== undefined) {
                    if (isFor !== '') {
                        if (isFor === 'carton') {
                            if (parseInt(previous[i].carton) === parseInt(updated[i].carton)) {
                                return ''
                            } else { return 'not-equal' }
                        } else if (isFor === 'unit') {
                            if (parseInt(previous[i].unit) === parseInt(updated[i].unit)) {
                                return ''
                            } else { return 'not-equal' }
                        } else if (isFor === 'status') {
                            if (previous[i].production_status === updated[i].production_status) {
                                return ''
                            } else { return 'not-equal'}
                        } else if (isFor === 'date') {
                            if (previous[i].expected_cargo_ready_date === updated[i].expected_cargo_ready_date) {
                                return ''
                            } else { return 'not-equal' }
                        }
                    } else { return '' }
                } else { return 'not-equal' }
            }
        },
        getUserId(id) {
			if (typeof this.getUser !== "undefined" &&
				typeof this.getUser == "string") {
				let parsedData = JSON.parse(this.getUser);

				if (parsedData.customers_api !== "undefined" &&
					Array.isArray(parsedData.customers_api) &&
					parsedData.customers_api.length > 0) {

					let findCustomer = _.find(
						parsedData.customers_api,
						(e) => e.id === id
					);
					if (typeof findCustomer !== "undefined") {
						return findCustomer.company_name;
					}
				}
			}

			return "--";
		},
        checkIfDataIsUndefined(data, isFor) {
            if (typeof data !== 'undefined' && data !== null) {
                if (isFor === 'supplier') {
                    return data.supplier_company_name
                } else if (isFor === 'customer') {
                    return data.customer_company_name
                }
            } else {
                return '-'
            }
        }
    },
    watch: {},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/tables.scss';
</style>
