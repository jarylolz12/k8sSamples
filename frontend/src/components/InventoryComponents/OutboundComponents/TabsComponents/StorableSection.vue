<template>
    <div :class="sortableData.length > 0 ? 'outbound-storable-section' :''">
        <div class="btn-headerMenus mt-n1" v-if="selected.length>0 && !isWarehouseConnected">         
            <button @click="ClearStorableSelection" :disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' ">
                <span  class="btn-clear"> Clear Selection </span>
            </button>

            <button :disabled="outboundProductsData.outbound_status=='cancelled' || outboundProductsData.outbound_status=='completed' || outboundProductsData.outbound_status == 'rejected' " @click="BatchLoadedSortable">
                <span class="btn-whitee"> Batch Loaded </span>
            </button>
        </div>
        <v-data-table
            :headers="productsHeader"
            :items="sortableData"
            :single-select="singleSelectval"
            item-key="id"
            v-model="selected"
            class="elevation-1 outbound-view-products"
            :class="(typeof sortableData !== 'undefined' && sortableData !== null && sortableData.length > 0) ? '' : 'no-data-table'"
            mobile-breakpoint="769"
            hide-default-footer
            show-select
            :expanded.sync="expanded"
            single-expand
            show-expand>

             <template v-slot:no-data>
                <div class="no-data-wrapper" v-if="sortableData.length == 0">
                    <div class="no-data-heading">
                        <div>
                            <h3> No Storable Units </h3>
                            <p> You will be able to add storable units once the product has been received. </p>
                        </div>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.label`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p>{{ item.label !== '' && item.label !== null ? item.label : '--'}}</p>
                </div>
                 <div class="mobile-content-wrapper" v-if="isMobile">
                    <div class="mobile-content">
                        <span>
                            {{ item.label !== null ? item.label : '--'}}                                                  

                            <span 
                                style="text-transform: capitalize; font-family: 'Inter-Medium', sans-serif; color: #6D858F;">
                                - {{ item.type !== null ? item.type : '--'}}
                            </span>
                        </span>
                    </div>
                     <div class="actions-wrapper" v-if="selected.length == 0 && !isWarehouseConnected">
                    <button 
                        v-if="item.status =='picked' || item.status == 'loaded' || item.status ==null"
                        :class="item.status=='loaded' && outboundProductsData.outbound_status !== 'cancelled' && outboundProductsData.outbound_status !== 'completed' && outboundProductsData.outbound_status !=='rejected' ? 'myclass':'btn-status btn-whitee'" 
                        :disabled="outboundProductsData.outbound_status=='cancelled' || outboundProductsData.outbound_status=='completed' "
                        @click.stop="pickProduct(item)">

                        <span class="btn-content">
                            <span v-if="item.status=='loaded'">                                
                                <div class="d-flex align-center" :class="outboundProductsData.outbound_status =='cancelled' ||  outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' ? '':'myclass' ">
                                    <img src="@/assets/icons/checkMark-green.svg" width="12px" height="12px"> 
                                    <span class="ml-1 greenColor" 
                                        style="text-transform: capitalize; font-size: 12px !important;"> 
                                        {{ item.status }}
                                    </span>
                                </div>
                            </span>

                            <span v-else style="text-transform: capitalize;">
                                 {{ GetStatus(item)}}
                                <!-- {{ item.status !==null && item.status !=='' ? item.status :'Loaded' }} -->
                            </span>
                        </span>
                    </button>

                    <v-menu v-if="item.status !=='loaded'" bottom left offset-y content-class="outbound-lists-menu">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn :disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' " class="btn-white" icon v-bind="attrs" v-on="on" >
                                <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item @click.stop="editStorableUnit(item)">
                                <v-list-item-title>
                                    Edit
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
                </div>
            </template>

            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">
                    <div class="expanded-item-info">
                        <div class="expanded-header-wrapper">
                            <div class="expanded-header-content">
                                <div class="header-title w-80">SKU</div>
                                <div class="header-title w-10">CARTON</div>
                                <div class="header-title w-10">UNIT</div>
                            </div>
                        </div>

                        <div class="expanded-body-wrapper">
                            <div class="expanded-body-content" 
                                v-for="(v, index) in item.outbound_storable_unit_products" :key="index + v.id">
                                <div class="header-data w-80">
                                    #{{ 
                                        v.outbound_product !== null && v.outbound_product.product !== null ? 
                                        v.outbound_product.product.category_sku : ''
                                    }}
                                    {{ 
                                        v.outbound_product !== null && v.outbound_product.product !== null ? 
                                        v.outbound_product.product.name : ''
                                    }}
                                </div>

                                <div class="header-data w-10">
                                    {{ v.carton_count }}
                                </div>

                                <div class="header-data w-10">
                                    {{ v.total_unit }}
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p style="text-transform: capitalize;">
                        {{ item.type !== '' && item.type !== null ? item.type : '--'}}
                    </p>
                </div>

                <div class="mobile-content-wrapper" v-if="isMobile">
                   <div class="mobile-content">                        
                        <p v-if="item.dimension && typeof item.dimension == 'string'">
                            <span>{{JSON.parse(item.dimension).l}}x</span>
                            <span>{{JSON.parse(item.dimension).w}}x</span>
                            <span>{{JSON.parse(item.dimension).h}}</span>
                            <span>{{JSON.parse(item.dimension).uom}}</span>
                        </p>

                        <p v-if="item.dimension && typeof item.dimension == 'object'">
                            <span>{{item.dimension.l}}x</span>
                            <span>{{item.dimension.w}}x </span>
                            <span>{{item.dimension.h}}</span>
                            <span>{{item.dimension.uom}}</span>
                        </p>

                        <p class="unit-weight-mobile" v-if="item.weight && typeof item.weight =='string'">
                            <span class="subtitle-1 name-wrapper">{{JSON.parse(item.weight).value}}</span> 
                            <span> {{JSON.parse(item.weight).unit}} </span>
                        </p>

                        <p class="unit-weight-mobile" v-if="item.weight && typeof item.weight =='object'">
                            <span class="subtitle-1 name-wrapper">{{item.weight.value}}</span> 
                            <span> {{item.weight.unit}} </span>
                        </p>  
                    </div> 
                </div>
            </template>

            <template v-slot:[`item.dimension`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p v-if="item.dimension && typeof item.dimension == 'string'">
                        <span>{{JSON.parse(item.dimension).l}}x</span>
                        <span>{{JSON.parse(item.dimension).w}}x</span>
                        <span>{{JSON.parse(item.dimension).h}}</span>
                        <span>{{JSON.parse(item.dimension).uom}}</span>
                    </p>

                    <p v-if="item.dimension && typeof item.dimension == 'object'">
                        <span>{{item.dimension.l}}x</span>
                        <span>{{item.dimension.w}}x</span>
                        <span>{{item.dimension.h}}</span>
                        <span>{{item.dimension.uom}}</span>
                    </p>

                    <p v-if="item.weight && typeof item.weight =='string'" class="unit-weight">
                        {{JSON.parse(item.weight).value}} {{JSON.parse(item.weight).unit}}
                    </p>

                    <p v-if="item.weight && typeof item.weight =='object'" class="unit-weight">
                        {{item.weight.value}} {{item.weight.unit}}
                    </p>                  
                </div>

                <div class="cartons-separator" v-if="isMobile">
                    <p>{{ item.outbound_storable_unit_products !== null && item.outbound_storable_unit_products.length > 0 ? item.outbound_storable_unit_products.length : 0 }} sku</p>
                    <span class="separator"></span>
                    <p>{{ getTotalCartonsAndUnits(item, 'carton') }} cartons</p>
                    <span class="separator"></span>
                    <p> {{ getTotalCartonsAndUnits(item, 'unit') }} units </p>
                </div>
            </template>

            <template v-slot:[`item.location`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                                                       
                    <p> {{getLocation(item.location)}}</p>
                </div>
            </template>

            <!-- <template v-slot:[`item.work_order`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p class="blueTxt">{{ item.work_order !== '' && item.work_order !== null ? item.work_order : '--'}}</p>
                </div>
            </template> -->

            <template v-slot:[`item.no_sku`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <p>{{ item.no_of_sku !== '' && item.no_of_sku !== null ? item.no_of_sku : '--'}}</p>
                </div>
            </template>

            <template v-slot:[`item.outbound_storable_unit_products.carton_count`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <!-- <p v-for="(ite,indexx) in item.outbound_storable_unit_products" :key="GenerateUniqueID_forCarton(ite.carton_count+indexx)">
                        {{ ite.carton_count !== '' && ite.carton_count !== null ? ite.carton_count : '--'}}
                    </p> -->
                    <span>{{ getTotalCartonsAndUnits(item, 'carton') }}</span>
                </div>
            </template>

            <template v-slot:[`item.outbound_storable_unit_products.total_unit`]="{ item }">
                <div class="name-wrapper" v-if="!isMobile">
                    <!-- <p v-for="(itemmm,inde) in item.outbound_storable_unit_products" :key="GenerateUniqueID_forUnits(itemmm.total_unit+inde)">
                        {{ itemmm.total_unit !== '' && itemmm.total_unit !== null ? itemmm.total_unit : '--'}}
                    </p> -->
                    <span>{{ getTotalCartonsAndUnits(item, 'unit') }} </span>
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions-wrapper" v-if="selected.length == 0 && !isWarehouseConnected">
                    <button 
                        v-if="item.status =='picked' || item.status == 'loaded'  || item.status ==null "
                        :class="item.status=='loaded' && outboundProductsData.outbound_status !== 'cancelled' && outboundProductsData.outbound_status !== 'completed' && outboundProductsData.outbound_status !== 'rejected' ? 'myclass':'btn-status btn-whitee'"
                        :disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' "
                        @click.stop="pickProduct(item)">

                        <span class="btn-content">
                            <span v-if="item.status=='loaded'">                                
                                <div class="d-flex align-center" :class="outboundProductsData.outbound_status =='cancelled' ||  outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' ? '':'myclass' ">
                                    <img src="@/assets/icons/checkMark-green.svg" width="12px" height="12px"> 
                                    <span class="ml-1 greenColor" 
                                        style="text-transform: capitalize; font-size: 12px !important;"> 
                                        {{ item.status }}
                                    </span>
                                </div>
                            </span>

                            <span v-else style="text-transform: capitalize;">
                               {{ GetStatus(item)}}
                                <!-- {{ item.status !==null && item.status !=='' ? item.status :'Loaded' }} -->
                            </span>
                        </span>
                    </button>

                    <v-menu v-if="item.status !=='loaded'" bottom left offset-y content-class="outbound-lists-menu">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn :disabled="outboundProductsData.outbound_status == 'cancelled' || outboundProductsData.outbound_status == 'completed' || outboundProductsData.outbound_status == 'rejected' "  class="btn-white" icon v-bind="attrs" v-on="on" >
                                <img src="@/assets/icons/dots.svg" alt="dots" width="16px" height="16px">
                            </v-btn>
                        </template>

                        <v-list class="outbound-lists">
                            <v-list-item @click.stop="editStorableUnit(item)">
                                <v-list-item-title>
                                    Edit
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </template>
        </v-data-table>

        <v-dialog v-model="dialogPick" width="500" content-class="outbound-dialog-pick">
            <v-card>
                <v-card-title class="headline">
                    <div class="question-icon mt-3 mb-1">
                        <img src="@/assets/icons/question.svg" alt="" width="48px" height="48px">
                    </div>
                </v-card-title>

                <v-card-text style="padding-bottom: 15px;">
                    <h2 v-if="currentSortableUnitValue && currentSortableUnitValue.status =='picked'">Confirm Build of Storable Unit</h2>
                    <h2 v-else>Confirm Loading of Storable Unit</h2>
                   
                    <p v-if="currentSortableUnitValue  && currentSortableUnitValue.status == 'picked'">
                        Have you built Storable unit from 
                        <span class="color-blue-default"> 
                            <span>{{totalCarton !==null && totalCarton!==''?totalCarton:'N/A'  }} Cartons </span>
                        </span> with
                        <span >{{totalUnits !==null && totalUnits !== '' ? totalUnits:'N/A'}}</span> Units of 
                        <span class="color-blue-default">SKU {{ currentSortableUnitValue.label }}</span> 
                        <!-- <span v-if="currentSortableUnitValue.pallet !== ''"> from Pallet {{ currentSortableUnitValue.pallet }}</span>? -->
                    </p>

                    <p v-else>Have you loaded the selected storable units into the truck?</p>
                </v-card-text>

                <v-card-actions>
                    <button 
                        v-if="currentSortableUnitValue  && currentSortableUnitValue.status == 'picked'"
                        :disabled="getLoadStorableUnitLoading" class="btn-blue confirm-pick" @click="ConfirmLoadStorableApi">         

                        <span>{{ getLoadStorableUnitLoading ? 'Confirming...' : 'Confirm Pick'}}</span>  
                    </button>

                    <button v-else 
                        :disabled="getLoadStorableUnitLoading" 
                        class="btn-blue confirm-pick" 
                        @click="ConfirmLoadStorableApi">

                        <span>{{ getLoadStorableUnitLoading ? 'Marking...' : 'Mark Loaded'}}</span>                   
                    </button>

                    <button class="btn-white cancel-pick" @click="closePickDialog">
                        Cancel
                    </button>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogPickBatch" width="500" content-class="outbound-dialog-pick">
            <v-card>
                <v-card-title class="headline">
                    <div class="question-icon mt-3 mb-1">
                        <img src="@/assets/icons/question.svg" alt="" width="48px" height="48px">
                    </div>
                </v-card-title>

                <v-card-text style="padding-bottom: 15px;">
                    <h2>Confirm Batch Loading</h2>
                    <p v-if="selected !== null">
                        Batch Loading of storable Units with 
                         <span class="color-blue-default"> 
                        <span>{{totalCartonBatchMult !==null && totalCartonBatchMult!==''?totalCartonBatchMult:'N/A'  }} Cartons
                             </span>
                             </span> and
                        <span >{{totalUnitsBatchMulti !==null && totalUnitsBatchMulti !== '' ? totalUnitsBatchMulti:'N/A'}}</span> Units of 
                        <span class="color-blue-default" v-for="(label,indexs) in BatchLoadedStorable" :key="indexs+label.id">SKU {{ label.label !==null && label.label !=='' ? label.label:'N/A'  }}
                            </span> 
                        <!-- <span v-if="currentSortableUnitValue.pallet !== ''"> from Pallet {{ currentSortableUnitValue.pallet }}</span>? -->
                    </p>
                </v-card-text>

                <v-card-actions>
                    <button :disabled="getsetBatchStorableLoading"  class="btn-blue confirm-pick" @click="ConfirmLoadStorableBatchApi">
                        Batch Loaded
                    </button>

                    <button :disabled="getsetBatchStorableLoading" class="btn-white cancel-pick" @click="closePickDialogBatch">
                        Cancel
                    </button>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <NewSortableUnitOutboundDialog
            :dialog.sync="BuildStorableUnitModel" 
            :editedItem.sync="storableItemsData"
            :productsData="outboundProductsData"
            :linkData="linkData"
            @close="closeStorableUnitModelFunction"
            :index="1"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import globalMethods from "../../../../utils/globalMethods"
import NewSortableUnitOutboundDialog from "../Dialogs/NewSortableUnitOutboundDialog.vue"
import _ from 'lodash'
export default {
    name: 'ProductSection',
    props: ["outboundProductsData", "isMobile","singleOutbound_status","isWarehouseConnected","isWarehouse6PL"],
    components: {
        NewSortableUnitOutboundDialog
    },
    data: () => ({
        productsHeader: [
            {
				text: 'Label',
				align: 'start',
				sortable: false,
				value: 'label',
				fixed: true,
				width: "75px"
			},
            { text: '', value: 'data-table-expand', width: "5%"},
			{ 
				text: 'Type',
				align: 'start',
				sortable: false,
				value: 'type',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'Spec',
				align: 'start',
				sortable: false,
				value: 'dimension',
				fixed: true,
				width: "15%"
			},
			{ 
				text: 'Location',
				align: 'start',
				sortable: false,
				value: 'location',
				fixed: true,
				width: "15%"
			},
            // { 
			// 	text: 'Work Order',
			// 	align: 'start',
			// 	sortable: false,
			// 	value: 'work_order',
			// 	fixed: true,
			// 	width: "11%"
			// },
            { 
				text: 'No of SKU',
				align: 'end',
				sortable: false,
				value: 'no_sku',
				fixed: true,
				width: "10%"
			},
            { 
				text: 'Carton',
				align: 'end',
				sortable: false,
				value: 'outbound_storable_unit_products.carton_count',
				fixed: true,
				width: "12%"
			},
            { 
				text: 'Unit',
				align: 'end',
				sortable: false,
				value: 'outbound_storable_unit_products.total_unit',
				fixed: true,
				width: "12%"
			},
			{ 
				text: '', 
				align: 'center',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "20%"
			},
        ],
        dialogPick: false,
        dialogPickBatch:false,
        currentSortableUnitValue: null,
        singleSelectval:false,
        selected:[],
        sendataStorable:[],
        linkData:{
            wid:"",
            oid:""
        },
        BuildStorableUnitModel:false,
        index: 1,
          storableItemsData: {
            action: "",
            type: "",
            copies:1,
            customer_id: null,
            dimension: {
                l: '',
                w: '',
                h: '',
                uom: 'cm'
            },
            weight: {
                value: '',
                unit: 'KG'
            },
            products: []
        },
        expanded: [],
        BatchLoadedStorable:[]
    }),
    computed: {
        ...mapGetters({getLoadStorableUnitLoading:"outbound/getLoadStorableUnitLoading",
            getsetBatchStorableLoading:"outbound/getsetBatchStorableLoading",
            getCurrentOutboundTab: "outbound/getCurrentOutboundTab"
        }),
        sortableData: {
            get() {
                let newStorableUnitData = []

                if (typeof this.outboundProductsData !== 'undefined' && this.outboundProductsData !== null) {
                    if (this.outboundProductsData.outbound_storable_units !== 'undefined' 
                        && this.outboundProductsData.outbound_storable_units.length > 0) {
                        
                        newStorableUnitData = this.outboundProductsData.outbound_storable_units
                    }
                }

                return newStorableUnitData
            },
            set(value) {
                this.$emit('update:outboundProductsData', value)
            }
        },
        totalCarton: function() {
            return this. currentSortableUnitValue.outbound_storable_unit_products.reduce(function(prev, item) {
                return prev + item.carton_count; 
            }, 0);
        },
        totalUnits: function(){
            return this. currentSortableUnitValue.outbound_storable_unit_products.reduce(function(prev, item){
                return prev + item.total_unit; 
            }, 0);
        },
        totalCartonBatch:function(){
            return this.BatchLoadedStorable.map(val => val.outbound_storable_unit_products.map(item=>item.carton_count).reduce((total,prev)=>{
                return total +prev
            }, 0))
        },
        totalCartonBatchMult(){
            return this.totalCartonBatch.reduce((total,item)=>{
                return total + item
            }, 0)
        },
        totalUnitBatch(){
            return this.BatchLoadedStorable.map(val => val.outbound_storable_unit_products.map(item=>item.total_unit).reduce((total,prev)=>{
                return total +prev
            }, 0))
        },
        totalUnitsBatchMulti(){
            return this.totalUnitBatch.reduce((total_val, values)=>{
                return total_val +values
            }, 0)
        }
    },
    methods: {
        ...mapActions({
            loadStorableUnit:"outbound/loadStorableUnit",
            getEnableButtonSortableUnit:"outbound/getEnableButtonSortableUnit",
            BatchStorableLoadedApi:"outbound/BatchStorableLoadedApi",
            fetchSingleOutbound: 'outbound/fetchSingleOutbound',
            fetchPendingOutbounds: "outbound/fetchPendingOutbounds",
	  	    fetchFloorOutbounds: "outbound/fetchFloorOutbounds",
            fetchPendingShippingServiceProvider:"outbound/fetchPendingShippingServiceProvider"
        }),
        ...globalMethods,
        pickProduct(item) {
            this.dialogPick = true
            this.currentSortableUnitValue = item
        },
        closePickDialog() {
            this.dialogPick = false
        },
        closePickDialogBatch(){
            this.dialogPickBatch = false;
            this.sendataStorable = []
            this.BatchLoadedStorable=[]
        },
        async ConfirmLoadStorableApi(){
            if (typeof this.currentSortableUnitValue !=="undefined"){
                try {
                    await this.loadStorableUnit(this.currentSortableUnitValue)
                    this.dialogPick = false
                    this.notificationMessage('Sortable Unit Loaded sucessfully')
                    let payload = { wid: this.linkData.wid, oid: this.linkData.oid }
                    await this.fetchSingleOutbound(payload)
                    if(this.getCurrentOutboundTab !=='undefined' && this.getCurrentOutboundTab !== undefined){
                        let storeOutboundTab = this.$store.state.outbound
					    let dataWithPage = { id: this.linkData.wid,page: 1}
                        if(!this.isWarehouse6PL){
                            if(this.getCurrentOutboundTab == 0){
                                dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						        await this.fetchPendingOutbounds(dataWithPage);   
                            }else {
                                if(this.getCurrentOutboundTab == 1){
                                    dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
						            await this.fetchFloorOutbounds(dataWithPage);
                                }
                            }
                        }else{
                            if(this.getCurrentOutboundTab == 0){
                                dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						        await this.fetchPendingOutbounds(dataWithPage);   
                            }else if(this.getCurrentOutboundTab == 1){
                                dataWithPage.page = storeOutboundTab.pendingShippingProviderOutboundPagination.current_page
								await this.fetchPendingShippingServiceProvider(dataWithPage)
                            } else {
                                if(this.getCurrentOutboundTab == 2){
                                    dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
						            await this.fetchFloorOutbounds(dataWithPage);
                                }
                            }
                        }
                        
                    }
                }
                catch(e) {
                    this.notificationError(e)
                }
            }        
        },
        ClearStorableSelection(){
            this.selected=[]
            this.sendataStorable=[]
            this.BatchLoadedStorable=[]
        },
        BatchLoadedSortable(){
            this.BatchLoadedStorable =this.selected.filter(check =>{
			if(check.status =='loaded'){
				return
			}else{
				return check
			}

		})
            this.BatchLoadedStorable.filter(val =>{
                if (this.sendataStorable.includes(val.id)){
                    return
                } else {
                    this.sendataStorable.push(val.id)
                }
            })

            if(this.BatchLoadedStorable.length){
                this.dialogPickBatch = true
            }
        },
       async ConfirmLoadStorableBatchApi(){
                try{
                    let payload = {
                    oid:this.linkData.oid,
                    idss:this.sendataStorable,
                    wid:this.linkData.wid
                }
                    await this.BatchStorableLoadedApi(payload)
                    this.dialogPickBatch=false
                    this.selected=[]
                    this.sendataStorable=[]
                    this.BatchLoadedStorable=[]
                    this.notificationMessage('Storable Units Loaded sucessfully')
                    let product_payload = { wid: this.linkData.wid, oid: this.linkData.oid }
                    await this.fetchSingleOutbound(product_payload)
                    if(this.getCurrentOutboundTab !=='undefined' && this.getCurrentOutboundTab !== undefined){
                        let storeOutboundTab = this.$store.state.outbound
					    let dataWithPage = { id: this.linkData.wid,page: 1}
                        if(!this.isWarehouse6PL){
                            if(this.getCurrentOutboundTab == 0){
                                dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						        await this.fetchPendingOutbounds(dataWithPage);   
                            }else {
                                if(this.getCurrentOutboundTab == 1){
                                    dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
						            await this.fetchFloorOutbounds(dataWithPage);
                                }
                            }
                        }else{
                            if(this.getCurrentOutboundTab == 0){
                                dataWithPage.page = storeOutboundTab.pendingOutboundPagination.current_page
						        await this.fetchPendingOutbounds(dataWithPage);   
                            }else if(this.getCurrentOutboundTab == 1){
                                dataWithPage.page = storeOutboundTab.pendingShippingProviderOutboundPagination.current_page
								await this.fetchPendingShippingServiceProvider(dataWithPage)
                            } else {
                                if(this.getCurrentOutboundTab == 2){
                                    dataWithPage.page = storeOutboundTab.floorOutboundPagination.current_page
						            await this.fetchFloorOutbounds(dataWithPage);
                                }
                            }
                        }
                    }
                }catch(e){
                    this.notificationError(e)
                }
        },
         editStorableUnit(item) {
            this.BuildStorableUnitModel = true
            this.index = _.findIndex(this.sortableData.outbound_storable_unit_products, (e) => e.id == item.id)

            let productLists = item.outbound_storable_unit_products

            let newProductLists = productLists.map(v => {
                let newItem = {}

                newItem = {
                    outbound_product_id: v.outbound_product_id,
                    carton_count: v.carton_count,
                    total_unit: v.total_unit,
                    shipping_unit: v.outbound_product!==null && v.outbound_product!=='undefined'  && v.outbound_product!==undefined ? v.outbound_product.shipping_unit :''
                }

                return newItem
            })

            if (item !== null) {
                this.storableItemsData = {
                    action: item.action !== null ? item.action : '',
                    type: item.type !== null ? item.type : '',
                    copies:item.copies !==null ? item.copies :1,
                    customer_id: item.customer_id !== null ? item.customer_id : '',
                    dimension: item.dimension,
                    weight: item.weight,
                    products: newProductLists,
                    storable_id: item.id,
                    label:item.label !==null ? item.label:''
                }
            }
        },
          closeStorableUnitModelFunction(){
            this.BuildStorableUnitModel=false,
             this.storableItemsData = {
                action: "",
                type: "",
                copies:1,
                customer_id: null,
                dimension: {
                    l: '',
                    w: '',
                    h: '',
                    uom: 'cm'
                },
                weight: {
                    value: '',
                    unit: 'KG'
                },
                products: [] 
            }
        },
          getTotalCartonsAndUnits(data, forItem) {
            let total = 0

            if (data !== 'undefined' && data !== null && data.outbound_storable_unit_products !== 'undefined' 
                && Array.isArray(data.outbound_storable_unit_products) && data.outbound_storable_unit_products.length > 0) {
                let arr = data.outbound_storable_unit_products

                if (forItem === 'carton') {
                    arr.forEach(function(value) {
                        total += value.carton_count                       
                    })
                } else if (forItem === 'unit') {
                    arr.forEach(function(value) {
                        total += value.total_unit
                    })
                }
            }

            total = total === 0 ? '--' : total

            return total
        },
        getLocation(item){
            let name =''
            if(item !==null && item !== 'undefined'){

            
            if (item.shelf !== null && item.row !==null && item.rack !==null){
				name = `${item.shelf}${item.row}${item.rack}`
			} else {
				name =  '--'
			}
            }
            name = name == '' ? '--' :name
            return name 
        },
        GetStatus(item){
               let name =''
            if(item !==null && item !== 'undefined'){

            if (item.status ==null){
				name = 'Load'
			} else if(item.status === 'picked') {
				name =  'Load'
			}
            else if(item.status === 'loaded'){
                name = 'Loaded'
            }
            }
            return name 
        },
        GenerateUniqueID_forCarton(item){
         return  _.uniqueId('carton_' + item)
        },
        GenerateUniqueID_forUnits(item){
            return _.uniqueId('units_' + item)
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "inventory");
        let wid = new URL(location.href).searchParams.get('wid')
        let oid = new URL(location.href).searchParams.get('id')
        this.linkData = { oid, wid }
    },
    updated() {}
}
</script>

<style lang="scss">
/* @import '../../../assets/scss/buttons.scss';
@import '../../../assets/scss/pages_scss/outbound/outboundView.scss'; */
@import './scss/productSection.scss';
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';

.btn-whitee {
    background-color: $white !important;
    color: $dark-blue !important;
    border: 1px solid $light-grey !important;
    padding: 10px 16px !important;
    font-size: 14px;
    height: 38px !important;
    text-transform: capitalize;
    letter-spacing: 0;
    box-shadow: none !important;
    border-radius: 4px;
    font-family: 'Inter-Regular', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Inter-Medium', sans-serif;

    &:disabled {
        color: $dark-blue !important;
    }

    &.delete {
        color: $red !important;
    }
}

.myclass {
    background-color: #EBFAEF !important;
    padding: 7px 0 !important;
    color: #16B442 !important;
    border: none !important;
    border-radius: 4px !important;
    pointer-events: none !important;
    margin-right: 5px !important;
}

.greenColor {
    color: #16B442 !important;
    font-weight: 500 !important;
    font-size: 12px !important
    
}
.cartons-separator {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin-bottom: 3px;

    p {
    text-align: left !important;
    font-size: 12px !important;
    color: #6D858F;
    margin-bottom: 0;
	}

    .separator {
        width: 6px;
        height: 6px;
        background-color: #ebf2f5;
        border-radius: 50%;
        margin: 0 6px;
    }
}
</style>