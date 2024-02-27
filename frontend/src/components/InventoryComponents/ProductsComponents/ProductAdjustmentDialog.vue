<template>
    <div class="">
        <v-dialog v-model="dialog" max-width="960px" :retain-focus="false" persistent v-resize="onResize" scrollable>
            <v-card>
                <v-card-title>
				    <span class="headline">
				    	Adjustment
				    </span>
				    <v-spacer></v-spacer>
				    <v-btn icon dark class="btn-close" @click="close">
				    	<v-icon>mdi-close</v-icon>
				    </v-btn>
			    </v-card-title>
                
                <v-card-text class="py-6 px-6 my-0">
                    <v-form ref="form" v-model="valid" lazy-validation action="#" @submit.prevent="">
                        <span class="font-semi-bold" style="color: #6D858F;letter-spacing: 0;">Adjustment Type</span>
                        <v-radio-group
                            v-model="selectAdjustmentOrTransfer"
                            class="py-0 my-0 mt-2"
                            row>
                            <v-radio
                                style="border-radius: 4px;padding:8px 12px" class="mr-2"
                                :style="selectAdjustmentOrTransfer == 'InventoryAdjustment' ? 'border: 1px solid #0171A1':'border: 1px solid #B4CFE0;'"
                                value="InventoryAdjustment">
                                <span slot="label" style="color: #4A4A4A; font-size: 14px;">Inventory Adjustment</span>
                            </v-radio>
                            <v-radio
                                style="border-radius: 4px;padding:8px 12px"
                                :style="selectAdjustmentOrTransfer == 'WarehouseTransfer' ? 'border: 1px solid #0171A1':'border: 1px solid #B4CFE0;'"
                                value="WarehouseTransfer">
                                <span slot="label" style="color: #4A4A4A; font-size: 14px;">Warehouse Transfer</span>
                            </v-radio>
                        </v-radio-group>

                        <v-row v-if="selectAdjustmentOrTransfer == 'WarehouseTransfer'" no-gutters class="mb-3 mt-0 pt-0">
                            <v-col cols="6" class="pr-2">
                                <p class="mb-0 font-semi-bold" style="color: #6D858F;font-size:14px;">From</p>
                                <v-select
                                    :items="getAllWarehouseFrom"
                                    class="text-fields"
                                    v-model="currentWarehouse.name"
                                    item-text="name"
                                    item-value="name"
                                    outlined
                                    disabled
                                    background-color="#EBF2F5"
                                    hide-details
                                    append-icon="mdi-chevron-down"
                                    dense>
                                </v-select>
                            </v-col>
                            <v-col cols="6" class="pl-2">
                                <p class="mb-0 font-semi-bold" style="color: #6D858F;font-size:14px;">To</p>
                                <v-select
                                    placeholder="Select Warehouse"
                                    :items="getAllWarehouseTo"
                                    v-model="selectedWareHouse"
                                    :rules="[selectAdjustmentOrTransfer == 'WarehouseTransfer' ? ((v) => v !== '' || 'Input is required') :'']"
                                    append-icon="mdi-chevron-down"
                                    class="text-fields"
                                    :menu-props="{ contentClass: 'product-lists-items', bottom: true, offsetY: true }"
                                    item-text="name"
                                    item-value="id"
                                    outlined
                                    hide-details
                                    dense>
                                </v-select>
                            </v-col>
                        </v-row>
                        <div class="product-adjustment">
                            <v-data-table
                                :headers="_header"
                                :items="inventoryProducts"
                                class="elevation-0 product-adjustment-table"
                                mobile-breakpoint="769"
                                hide-default-footer
                                disable-pagination>
                                <template v-slot:item="{item}">
                                    <tr>
                                        <td class="pr-2 pl-0">
                                            <v-select
                                                v-model="item.product_sku"
                                                :items="inventroyDefaultProducts"
                                                class="text-fields"
                                                outlined
                                                disabled
                                                background-color="#EBF2F5"
                                                hide-details
                                                item-text="name"
                                                item-value="product_sku"
                                                append-icon="mdi-chevron-down"
                                                dense
                                            >
                                            </v-select>
                                        </td>
                                        <td class="pr-2">
                                            <v-select
                                                v-model="item.shipping_unit"
                                                :items="shippingUnit"
                                                class="text-fields"
                                                outlined
                                                placeholder="Select UOM"
                                                hide-details
                                                item-text="name"
                                                item-value="shipping_unit"
                                                append-icon="mdi-chevron-down"
                                                :menu-props="{ bottom: true, offsetY: true }"
                                                dense
                                                :rules="[transferedError.required]"
                                                validate-on-blur
                                            >
                                            </v-select>
                                        </td>
                                        <td class="pr-2">
                                            <v-text-field
                                                placeholder="0"
                                                v-model="item.current_quantity"
                                                hide-details
                                                dense
                                                class="text-fields"
                                                outlined
                                                disabled
                                                background-color="#EBF2F5"
                                            >
                                            </v-text-field>
                                        </td>
                                        <td class="pr-2" v-if="selectAdjustmentOrTransfer == 'InventoryAdjustment'">
                                            <v-text-field
                                                v-on:input="fillQuantity(item, $event,'adjusted_quantity')"
                                                placeholder="0"
                                                v-model.number="item.adjusted_quantity"
                                                :rules="[transferedError.required]"
                                                type="number"
                                                hide-details
                                                dense
                                                class="text-fields"
                                                outlined
                                                validate-on-blur
                                            >
                                            </v-text-field>
                                        </td>
                                        <td class="pr-2" v-if="selectAdjustmentOrTransfer == 'WarehouseTransfer'">
                                            <v-tooltip right :content-class="maximumValueCheck(item) == false ? 'left-arrow' :'has-none'">
                                                <template v-slot:activator="{ on }"> 
                                                    <v-text-field
                                                        v-on:input="fillQuantity(item, $event,'transfer')"
                                                        placeholder="0"
                                                        v-on="on"
                                                        v-model.number="item.transferred"
                                                        type="number"
                                                        :rules="[transferedError.required,maximumValueCheck(item)]"
                                                        hide-details
                                                        dense
                                                        class="text-fields"
                                                        outlined
                                                        validate-on-blur
                                                    >
                                                    </v-text-field>
                                                </template>
                                                <span>Can't exceed current quantity</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="pr-0" v-if="selectAdjustmentOrTransfer == 'InventoryAdjustment'">
                                            <div class="d-flex align-center">
                                                <v-text-field
                                                    v-on:input="fillQuantity(item, $event,'difference')"
                                                    placeholder="0"
                                                    v-model.number="item.difference"
                                                    :rules="[transferedError.required]"
                                                    type="number"
                                                    hide-details
                                                    dense
                                                    class="text-fields"
                                                    outlined
                                                    validate-on-blur
                                                >
                                                </v-text-field>
                                                <v-btn v-if="inventoryProducts.length > 1" @click="removeItem(item)" icon>
                                                    <v-icon color="red">mdi-close</v-icon>
                                                </v-btn>
                                            </div>
                                        </td>
                                        <td class="pr-0" v-if="selectAdjustmentOrTransfer == 'WarehouseTransfer'">
                                            <div class="d-flex align-center">
                                                <v-text-field
                                                    placeholder="0"
                                                    v-model.number="item.available_after"
                                                    type="number"
                                                    hide-details
                                                    disabled
                                                    background-color="#EBF2F5"
                                                    dense
                                                    class="text-fields"
                                                    outlined
                                                >
                                                </v-text-field>
                                                <v-btn v-if="inventoryProducts.length > 1" @click="removeItem(item)" icon>
                                                    <v-icon color="red">mdi-close</v-icon>
                                                </v-btn>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </div>
                        <v-row>
                            <v-col cols="12" class="mt-4">
                                <p class="py-0 my-0 mb-1 font-semi-bold" style="color: #6D858F;font-size:14px; letter-spacing:0;">Reason</p>
                                <v-textarea
                                    class="deliver-address"
                                    outlined
                                    dense
                                    :rules="[(v) => v !== '' || 'Input is required']"
                                    rows="3"
                                    name="input-7-4"
                                    placeholder="Type reason for adjustment"
                                    v-model="reasonForAdjustment"
                                    hide-details="auto"
                                >
                                </v-textarea>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
               
                <v-card-actions>
                    <v-btn @click="adjustInventoryFunction" :disabled="adjustInventoryLoading || !valid" class="btn-blue">
                       {{selectAdjustmentOrTransfer == 'InventoryAdjustment' ? 'Adjust' :'Transfer'}}
                    </v-btn>
                    <v-btn @click="close" :disabled="adjustInventoryLoading" class="btn-white">Cancel</v-btn>
                </v-card-actions>
            </v-card>

        </v-dialog>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import _ from "lodash"
import globalMethods from "../../../utils/globalMethods"
    export default {
        props:["dialogAdjustment","selectedData","defaultProducts","currentWarehouseSelected","isWarehouse3PL","getCurrentPage"],
        data(){
            return {
                valid:true,
                isMobile: false,
                selectAdjustmentOrTransfer:'InventoryAdjustment',
                reasonForAdjustment:'',
                header: [
	  		        {
			        	text: "SKU",
			        	align: "start",
			        	sortable: false,
			        	value: "product_sku",
			        	fixed: true,
			        	width: "20%",
	  		        },
                    {
			        	text: "UOM",
			        	align: "start",
			        	sortable: false,
			        	value: "shipping_unit",
			        	fixed: true,
			        	width: "18%",
	  		        },
                    {
			        	text: "Current Quantity",
			        	align: "start",
			        	sortable: false,
			        	value: "current_quantity",
			        	fixed: true,
			        	width: "18%",
	  		        },
	  		        {
			        	text: "Adjusted Quantity",
			        	align: "start",
			        	sortable: false,
			        	value: "adjusted_quantity",
			        	fixed: true,
			        	width: "18%",
	  		        },
                    {
			        	text: "Transferred",
			        	align: "start",
			        	sortable: false,
			        	value: "transferred",
			        	fixed: true,
			        	width: "16%",
	  		        },
	  		        {
			        	text: "Differences",
			        	align: "start",
			        	sortable: false,
			        	value: "difference",
			        	fixed: true,
			        	width: "18%",
	  		        },
                    {
			        	text: "Available After",
			        	align: "start",
			        	sortable: false,
			        	value: "available_after",
			        	fixed: true,
			        	width: "18%",
	  		        },
		        ],
                selectedWareHouse:'',
                inventoryProducts:[{
                    quantity:'',
                    available:'',
                    product_sku:'',
                    name:'',
                    id:''
                }],
                shippingUnit: [
	  		        {
	  		          name: "Carton",
	  		          shipping_unit: "carton",
	  		        },
	  		        {
			        	name: "Unit",
			        	shipping_unit: "unit",
	  		        }
		        ],
                transferedError:{
		  	        required:(v) => !!v || "Input is required."
		        },
            }
        },
        computed:{
            ...mapGetters({
                getWarehouse:'warehouse/getWarehouse',
                getUser: "getUser",
                getAdjustInventoryLoading:'productInventories/getAdjustInventoryLoading',
                getAdjustInventoryMultipleLoading:'productInventories/getAdjustInventoryMultipleLoading',
                getTransferInventoryProductLoading:'productInventories/getTransferInventoryProductLoading'
            }),
            adjustInventoryLoading(){
                let loading = false
                if(this.selectAdjustmentOrTransfer == 'WarehouseTransfer'){
                   loading = this.getTransferInventoryProductLoading
                }
                else{
                    if(this.inventoryProducts.length == 1){
                        loading = this.getAdjustInventoryLoading
                    }else{
                        loading = this.getAdjustInventoryMultipleLoading
                    }
                }
                return loading
            },
            _header(){
                let final_data = this.header
                    final_data = this.header.filter(val =>{
                        if(this.selectAdjustmentOrTransfer == 'InventoryAdjustment'){
                            return val.text !== 'Available After' && val.text !== 'Transferred'
                        }else{
                            return  val.text !== 'Differences' && val.text !== 'Adjusted Quantity'
                        }
                    })
                return final_data
            },
            dialog: {
	  		    get() {
			    	return this.dialogAdjustment;
	  		    },
	  		    set(value) {
			    	this.$emit("update:dialogAdjustment", value);
	  		    },
		    },
            inventroyDefaultProducts(){
                let data = []
                if(typeof this.defaultProducts !== undefined && this.defaultProducts !== "undefined" && this.defaultProducts !== null){
                    data = this.defaultProducts
                }else{
                     data = []
                }
                return data
            },
            // inventroyAdjustmentItems(){
            //     let data = []
            //     if(typeof this.selectedData !== undefined && this.selectedData !== "undefined" && this.selectedData !== null){
            //         data = this.selectedData
            //     }else{
            //          data = []
            //     }
            //     return data
            // },
            editedItemStorableUnits: {
                get() {                            
                    let values = [...this.selectedData ]
                    return values
                },
                set(value) {
                    this.$emit('update:selectedData', value)
                }
            },
            getAllWarehouseFrom(){
                let data = []
                if(typeof this.getWarehouse !== undefined && this.getWarehouse !== "undefined" && this.getWarehouse !== null && typeof this.getWarehouse.results !== undefined){
                    data = this.getWarehouse.results
                }else{
                     data = []
                }
                return data
            },
            getAllWarehouseTo(){
                let data = []
                if(typeof this.getWarehouse !== undefined && this.getWarehouse !== "undefined" && this.getWarehouse !== null && typeof this.getWarehouse.results !== undefined){
                    data = this.getWarehouse.results.filter(val => val.id !== this.currentWarehouse.id)
                }else{
                     data = []
                }
                return data
            },
            currentWarehouse(){
                let data = ''
                if(typeof this.currentWarehouseSelected !== undefined && this.currentWarehouseSelected !== "undefined" && this.currentWarehouseSelected !== null){
                    data = this.currentWarehouseSelected
                }else{
                     data = ''
                }
                return data
            },
            getCompnayName() {
			let getUser;
			if (typeof this.getUser === "string") {
				getUser = JSON.parse(this.getUser);
			} else {
				getUser = this.getUser;
			}
			return getUser.name ?? ''
		},
            
        },
        updated() {
            if (typeof this.editedItemStorableUnits !== 'undefined') {
                if (this.inventoryProducts !== this.editedItemStorableUnits) {
                    this.inventoryProducts = this.editedItemStorableUnits
                }
            }
        },
        methods:{
        ...mapActions({
            transferInventoryProductApi:'productInventories/transferInventoryProductApi',
            adjustInventoryApi:'productInventories/adjustInventoryApi',
            adjustMultipleInventoryApi:'productInventories/adjustMultipleInventoryApi',
            fetchProductInventories3pl: 'productInventories/fetchProductInventories3pl',
            fetchProductInventories: 'productInventories/fetchProductInventories',
        }),    
        ...globalMethods,
            onResize() {
	  		    if (window.innerWidth < 1024) {
				    this.isMobile = true;
	  		    } else {
				    this.isMobile = false;
	  		    }
		    },
            close(){
                this.$refs.form.resetValidation()
                this.$emit('closeAdjustment')
                this.selectedWareHouse=''
                this.selectAdjustmentOrTransfer='InventoryAdjustment'
                this.reasonForAdjustment = ''
            },
            removeItem(item){
                let getIndex = this.inventoryProducts.indexOf(item);
	  		    this.inventoryProducts.splice(getIndex, 1);
            },
            fillQuantity(item,value,from){
                let findItem = _.find(this.inventoryProducts, (e) => e.id === item.id)
                if (findItem !== undefined) {
                    if(from == 'adjusted_quantity'){
                        if(value) return item.difference = parseInt(value) - parseInt(findItem.current_quantity)
                        item.difference = parseInt(findItem.current_quantity) + parseInt(value)
                    }else if(from == 'transfer'){
                        item.available_after =  findItem.current_quantity - value
                    }
                    else{
                        if(value) return item.adjusted_quantity = parseInt(findItem.current_quantity) + parseInt(value)
                        item.adjusted_quantity = parseInt(findItem.current_quantity) - parseInt(value)
                    }
                }
            },
            // rules
            maximumValueCheck(item){
                let findItem = _.find(this.inventoryProducts, (e) => e.id === item.id )
		   			if(findItem !=='undefined' && findItem !== undefined){
							if(findItem.transferred > findItem.current_quantity) return false
							return true
		   			}
					return true
            },
            async adjustInventoryFunction(){
                if(!this.$refs.form.validate()) return
                try{
                    let dataWithPage = { 
                            page: this.getCurrentPage,
                            id: this.currentWarehouse.id
                        }
                    if(this.selectAdjustmentOrTransfer == 'WarehouseTransfer'){
                        let payload_transfered = {
                            products:this.inventoryProducts.map(({shipping_unit,transferred,id})=>{
                                return {
                                    inventory_product_id:id,
                                    shipping_unit,
                                    transferred,
                                }
                            }),
                            warehouse_to : this.selectedWareHouse,
                            reason:this.reasonForAdjustment,
                            updated_by:this.getCompnayName
                        }
                        await this.transferInventoryProductApi(payload_transfered)
                    }else{
                        if(this.inventoryProducts.length == 1){
                            let payload_Single = {
                                inventory_product_id:this.inventoryProducts[0].id,
                                item:this.inventoryProducts.map(({shipping_unit,difference})=>{
                                    return {
                                        // balance:current_quantity,
                                        shipping_unit,
                                        difference,
                                        reason:this.reasonForAdjustment,
                                        updated_by:this.getCompnayName
                                    }
                                })
                            }
                            let {item,...others}  = payload_Single
                            item = item[0]
                            let final_data = {item,...others}
                            await this.adjustInventoryApi(final_data)
                        }else{
                            let payload_Multiple = {
                                products:this.inventoryProducts.map(({adjusted_quantity,shipping_unit,difference,id})=>{
                                    return {
                                        inventory_product_id:id,
                                        balance:adjusted_quantity,
                                        shipping_unit,
                                        difference,
                                        reason:this.reasonForAdjustment
                                    }
                                }),
                                updated_by:this.getCompnayName
                            }
                            await this.adjustMultipleInventoryApi(payload_Multiple)
                        }
                    }
                    if (!this.isWarehouse3PL) {
                        await this.fetchProductInventories(dataWithPage)
                    } else {                  
                        await this.fetchProductInventories3pl(dataWithPage)
                    }
                    this.notificationMessage("The inventory has been adjusted");
                    this.close()

                }catch(e){
                    this.notificationError(e)
                }   
            }
        },
    }
</script>

<style lang="scss">
.product-adjustment {
    .v-data-table {
        &.product-adjustment-table {
            box-shadow: none !important;
            .v-data-table__wrapper {
                overflow-x: hidden;
                table {
                    thead {
                        background-color: #F7F7F7;
                        tr {
                            th {
                                border: none;
                                color: #6D858F;
                                font-size: 12px;
                                padding: 10px 4px;

                                &:first-child {
                                    padding-left: 16px;
                                }
                            }
                        }
                    }
                    tbody {
                        tr {
                            td {
                                padding: 8px 8px 0 0;
                                // border-bottom: 1px solid #EBF2F5;
                                border-bottom: none !important;

                                .v-input {
                                    .v-input__control {
                                        .v-input__slot {
                                            .v-select__slot {
                                                .v-select__selections {
                                                    .v-select__selection.v-select__selection--comma {
                                                        margin-top: 3px;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            &:hover {
                                background-color: white !important;
                            }
                        }
                    }
                }
            }
        }
    }
}
.v-tooltip__content.menuable__content__active {
    background-color: #4A4A4A !important;
    line-height: 30px;

    &.has-none {
        display: none !important;
    }

    &.left-arrow {
        overflow: inherit !important;
        z-index: 20;
        opacity: 1 !important;
        background: #4A4A4A !important;

        &::before {
            transform: rotate(180deg);
            content: " ";
            position: absolute;
            top: 50%;
            right: 100%;
            margin-top: -10px;
            border-width: 10px;
            border-style: solid;
            border-top: solid 10px transparent;
            border-bottom: solid 10px transparent;
            border-left: solid 10px transparent;
            border-left-color: #4A4A4A !important;
        }
    }
}

</style>