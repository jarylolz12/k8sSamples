<template>
<default-field :field="field" :errors="errors">
	<template slot="field">

		<!-- <portal to="modals" transition="fade-transition" v-if="showBox">


			<modal @modal-close="closeModal">
				<div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width: 460px;">
					<div class="p-8 text-left">
						<h2 class="mb-6 text-90 font-normal text-xl">Delete File</h2>
						<p class="text-80 leading-normal">Are you Sure you want to delete this file?</p>
					</div>
					<div class="border-t border-50 px-6 py-3 ml-auto flex items-center" style="min-height: 70px; flex-direction: row-reverse;">
						<a @click="showBox=false" class="cursor-pointer btn text-80 font-normal px-3 mr-3 btn-link" style="order: 2;">
							Cancle
						</a>
						<span>
							<button dusk="confirm-upload-delete-button" data-testid="confirm-button" class="btn btn-default btn-danger">
								Delete
							</button>
						</span>
					</div>
				</div>
			</modal>
		</portal> -->




		<input :id="field.attribute" type="hidden" class="w-full form-control form-input form-input-bordered" :class="errorClasses" :placeholder="field.name" v-model="value" />
		<portal to="modals" transition="fade-transition" v-if="duplicateModal">
			<modal @modal-close="closeModal">
				<div class="p-8 bg-white rounded-lg shadow-lg overflow-hidden">
					<heading class="mb-6">Duplicate Supplier</heading>
					<p class="text-80 leading-normal">
						Please make sure that there are no duplicate supplier under Suppliers Section.
					</p>
				</div>
				<div class="bg-30 px-6 py-3 flex" style="margin-top: -15px;border-bottom-right-radius: .5rem;border-bottom-left-radius: .5rem;">
					<div class="ml-auto">
						<button type="button" dusk="close-modal-button" @click.prevent="closeModal" class="btn btn-primary font-normal h-9 px-3 mr-3 btn-link">
							Close
						</button>
					</div>
				</div>
			</modal>
		</portal>

		<div v-show="formGroups.length>0" :id="`shipment-supplier-group-${item.id}`" v-for="(item,key) in formGroups" class="shipment-supplier-group">

			<portal to="modals" v-show="resourceId!=null && (typeof item.hbl_copy!='object')">
				<confirm-upload-removal-modal v-if="hblRemoveModal[key]" @confirm="event =>{destroy(event, key, 'hbl_copy' ,item.hbl_copy);}" @close="closeHblRemoveModal(key)" />
			</portal>
			<portal to="modals" v-show="resourceId!=null && (typeof item.packing_list!='object')">
				<confirm-upload-removal-modal v-if="packingListRemoveModal[key]" @confirm="event =>{destroy(event, key, 'packing_list' ,item.packing_list);}" @close="closePackingListRemoveModal(key)" />
			</portal>
			<portal to="modals" v-show="resourceId!=null && (typeof item.commercial_documents!='object')">
				<confirm-upload-removal-modal v-if="commercialDocumentsRemoveModal[key]" @confirm="event =>{destroy(event, key, 'commercial_documents');}" @close="closeCommercialDocumentsRemoveModal(key)" />
			</portal>
			<portal to="modals" v-show="resourceId!=null && (typeof item.commercial_invoice!='object')">
				<confirm-upload-removal-modal v-if="commercialInvoiceRemoveModal[key]" @confirm="event =>{destroy(event, key, 'commercial_invoice');}" @close="closeCommercialInvoiceRemoveModal(key)" />
			</portal>
			<div>
				<label>Select Supplier</label>
				<div>
					<div>
						<v-select :id="`shipment-supplier-select-${key}`" v-model="item.supplier_id" :reduce="company_name => company_name.id" label="company_name" :options="supplier_options" @input="value => selectSupplier ( value, key )">
						</v-select>
					</div>
					<div v-show="(typeof errorMessages!='undefined' && typeof errorMessages.supplier_id!='undefined' && errorMessages.supplier_id.length>0 && errorMessages.supplier_id.indexOf(item.supplier_id)!=-1)"
						class="help-text error-text text-danger mt-2">
						Please make sure that it has no duplicate supplier selection.
					</div>
				</div>
			</div>


			<div>
				<label>Select Purchase Order</label>
				<div>
					<div>
						 <v-select :id="`purchase-order-select-${key}`" v-model="item.po_num" :reduce="po_num => po_num.po_num" label="po_num" :options="purchaseOrderOptions"></v-select>
					</div>
					<div v-show="(typeof errorMessages!='undefined' && typeof errorMessages.po_id!='undefined' && errorMessages.po_id.length>0 && errorMessages.po_id.indexOf(item.po_id)!=-1)" class="help-text error-text text-danger mt-2">
						Please make sure that it has no duplicate purchase order selection.
					</div>
				</div>
			</div>

			<div>
				<label>PO # (Old Way)</label>
				<div>
					<input type="text" v-model="item.po_num" class="w-full form-control form-input form-input-bordered" />
				</div>
			</div>
			<div>
				<label>Volume</label>
				<div>
					<input type="text" v-model="item.cbm" class="w-full form-control form-input form-input-bordered" />
				</div>
			</div>
			<div>
				<label>Weight</label>
				<div>
					<input type="text" v-model="item.kg" class="w-full form-control form-input form-input-bordered" />
				</div>
			</div>
			<div>
				<label>Total Cartons</label>
				<div>
					<input type="text" v-model="item.total_cartons" class="w-full form-control form-input form-input-bordered" />
				</div>
			</div>
			<div>
				<label>AMS #</label>
				<div>
					<input type="text" v-model="item.ams_num" class="w-full form-control form-input form-input-bordered" />
				</div>
			</div>
			<div>
				<label>HBL #</label>
				<div>
					<input type="text" v-model="item.hbl_num" class="w-full form-control form-input form-input-bordered" />
				</div>
			</div>
			<div>
				<label>HBL Copy</label>
				<div>


					<div :id="'hbl_copy_' + key" v-show=" resourceId!=null && (typeof item.hbl_copy!='object') " class="card flex item-center relative border border-lg border-50 overflow-hidden p-4">
						<span class="truncate mr-3">
							{{ (typeof item.hbl_copy!='object') ? filename(item.hbl_copy) : '' }}
						</span>
						<button @click="openHblRemoveModal(key)" type="button" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center ml-auto" dusk="mbl_copy-internal-delete-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
								<path fill-rule="nonzero"
									d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z">
								</path>
							</svg>
						</button>
					</div>



					<input id="file_hbl_copy" type="file" @change="event =>{fileChange(event, key, 'hbl_copy');}" />
				</div>
			</div>
			<div>
				<label>Packing List</label>
				<div>


					<div :id="'packing_list_' + key" v-show="resourceId!=null && (typeof item.packing_list!='object')" class="card flex item-center relative border border-lg border-50 overflow-hidden p-4">
						<span class="truncate mr-3">
							{{(typeof item.packing_list!='object') ? filename(item.packing_list) : ''}}
						</span>
						<button @click="openPackingListRemoveModal(key)" type="button" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center ml-auto" dusk="mbl_copy-internal-delete-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
								<path fill-rule="nonzero"
									d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z">
								</path>
							</svg>
						</button>
					</div>



					<input type="file" @change="event =>{fileChange(event, key, 'packing_list');}" />
				</div>
			</div>
			<div>
				<label>Commercial Documents</label>
				<div>

					<div :id="'commercial_documents_' + key" v-show="resourceId!=null && (typeof item.commercial_documents!='object')" class="card flex item-center relative border border-lg border-50 overflow-hidden p-4">
						<span class="truncate mr-3">
							{{(typeof item.commercial_documents!='object') ? filename(item.commercial_documents) : ''}}
						</span>
						<button @click="openCommercialDocumentsRemoveModal(key)" type="button" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center ml-auto" dusk="mbl_copy-internal-delete-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
								<path fill-rule="nonzero"
									d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z">
								</path>
							</svg>
						</button>
					</div>



					<input type="file" @change="event =>{fileChange(event, key, 'commercial_documents');}" />
				</div>
			</div>
			<div>
				<label>Commercial Invoice</label>

				<div>

					<div :id="'commercial_invoice_' + key" v-show="resourceId!=null && (typeof item.commercial_invoice!='object')" class="card flex item-center relative border border-lg border-50 overflow-hidden p-4">
						<span class="truncate mr-3">
							{{(typeof item.commercial_invoice!='object') ? filename(item.commercial_invoice) : ''}}
						</span>
						<button @click="openCommercialInvoiceRemoveModal(key)" type="button" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center ml-auto" dusk="mbl_copy-internal-delete-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
								<path fill-rule="nonzero"
									d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z">
								</path>
							</svg>
						</button>
					</div>


					<input type="file" @change="event =>{fileChange(event, key, 'commercial_invoice');}" />
				</div>
			</div>
			<div>
				<label>Product Description</label>
				<div>
					<textarea v-model="item.product_description" class="w-full form-control form-input form-input-bordered h-auto" />
					</div>
                </div>
                <div >
                    <label>Terms</label>
                    <div>
                        <select v-model="item.incoterm_id" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Terms</option>
                            <option v-show="inco_term_values.length>0" v-for="option in inco_term_values" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                </div>
                <div >
                    <label>BL Status</label>
                    <div>
                        <select v-model="item.bl_status" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Status</option>
                            <option v-for="option in bl_status_values" :value="option">{{option}}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="removeGroup(item,key)" >Remove Supplier</button>
                </div>
                <!--container group-->
            <div  style="display: flex; flex-direction: column !important;" v-for="(itemSecond,keySecond) in item.containers">
				<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
					<label style="width: 40%; line-height: 36px;">Select Container</label>
					<div style="width: 60%;">

						<v-select :id="`shipment-supplier-container-select-booking-${key}-${keySecond}`" v-model="itemSecond.container_id" :reduce="container_num => container_num.id" label="container_num" :options="containersOptions">
						</v-select>
					</div>
				</div>
            	<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
					<label style="width: 40%; line-height: 36px;">Cartons</label>
					<div style="width: 60%;">
						<input type="text" @keyup="(event) =>{cartonChange(event, itemSecond.cartons, itemSecond.container_id);}" v-model="itemSecond.cartons" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
				<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
					<label style="width: 40%; line-height: 36px;">Weight(Number of KG)</label>
					<div style="width: 60%;">
						<input type="text" v-model="itemSecond.weight" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
				<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
					<label style="width: 40%; line-height: 36px;">Volume(Number of CBM)</label>
					<div style="width: 60%;">
						<input type="text" v-model="itemSecond.size" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
	            <div id="shipment-supplier-group-submit-group">
	                <button class="btn btn-default btn-danger inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="removeContainerGroup(key, keySecond)" >Remove Supplier Container</button>
	            </div>
            </div>
            <div id="shipment-supplier-group-submit-group" style="width:100%;">
                <button style="width: 51%;" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addContainerGroup(key,item.id)" >Add Supplier Container</button>
            </div>
            <!--container group-->

            </div>
            <div id="shipment-supplier-group-submit-group">
                <button class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addGroup()" >Add Supplier</button>
            </div>
        </template>
    </default-field>
</template>
<script>
import {
	FormField,
	HandlesValidationErrors,
	Errors,
	InteractsWithDates
} from 'laravel-nova';
import '../../sass/field.scss';
//import DuplicateModal from '../../components/Modals/DuplicateModal';
import _ from 'lodash';
import jQuery from 'jquery';
import 'vue-select/dist/vue-select.css';
import axios from 'axios';


export default {
	mixins: [FormField, HandlesValidationErrors, InteractsWithDates],
	props: ['resourceName', 'resourceId', 'field'],
	mounted() {
		//set default selected suppliers
		let getSuppliers = (this.field.value == '' || this.field.value == null) ? [] : JSON.parse(this.field.value);

		if (getSuppliers.length > 0) {
			this.selected_suppliers = _.map(getSuppliers, (supplier, key) => {
				return {
					key,
					value: supplier.supplier_id
				};
			})
		}

		//get all suppliers
        fetch(`${this.field.baseUrl}/custom/suppliers`,{
            token: this.token
        }).then(this.handleResponse)
        .then( response => {
            let { results } = response 
            if (typeof results!=='undefined') {
            	this.supplier_options = results
            }
        })

		//var token = jQuery('meta[name="csrf-token"]').attr('content');

		//set default selected purchase orders

		//get all purchase orders
        fetch(`${this.field.baseUrl}/custom/purchase-orders`,{
            token: this.token
        }).then(this.handleResponse)
        .then( response => {
            let { results } = response 
            if (typeof results!=='undefined') {
               // this.purchase_order_options = results
            	this.purchaseOrderOptions = results
            }
        })

        /*

		let getPurchaseOrders = (this.field.value == '' || this.field.value == null) ? [] : JSON.parse(this.field.value);

		if (getPurchaseOrders.length > 0) {
			this.selected_purchase_orders = _.map(getPurchaseOrders, (po, key) => {
				return {
					key,
					value: po.po_id
				};
			})
		}*/
		/*

		var token = jQuery('meta[name="csrf-token"]')
			.attr('content');
		jQuery.ajax({
			method: 'GET',
			url: '/custom/purchase-orders',
			data: {
				_token: token
			}
		})
		.done(response => {
			let {
				results
			} = response;

			if (results.length > 0) {
				this.purchase_order_options = results;
			}
		});*/


		//get all incoterms
        fetch(`${this.field.baseUrl}/custom/incoterms`,{
            token: this.token
        }).then(this.handleResponse)
        .then( response => {
            let { results } = response 
            if (typeof results!=='undefined') {
            	this.inco_term_values = results
            }
        })
        /*
		jQuery.ajax({
				method: 'GET',
				url: '/custom/incoterms',
				data: {
					_token: token
				}
		})
		.done(response => {
			let {
				results
			} = response;
			if (results.length > 0) {
				this.inco_term_values = results;
			}
		});*/

	},

	computed: {
		token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        },
		errorMessages() {
			return (typeof this.firstError != 'undefined') ? JSON.parse(this.firstError) : [];
		},
		firstDayOfWeek() {
			return 0
		},

		placeholder() {
			return moment()
				.format(this.format)
		},

		format() {
			return 'YYYY-MM-DD'
		},

		pickerFormat() {
			return 'Y-m-d'
		},
	},
	data: function() {
		return {
			duplicateModal: false,
			selected_suppliers: [],
			selected_purchase_orders: [],
			errors: {},
			has_submitted: false,
			current_id: parseInt(new Date()
				.getTime()),
			bl_status_values: [
				'Pending',
				'Telex Released',
				'Original Received'
			],
			hbl_copy: null,
			packing_list: null,
			commercial_documents: null,
			commercial_invoice: null,
			inco_term_values: [],
			supplier_options: [],
			purchaseOrderOptions: [],
			//purchase_order_options: [],
			custom_supplier_options: [],
			custom_purchase_order_options: [],
			formGroups: (this.field.value == '' || this.field.value == null) ? [] : JSON.parse(this.field.value),
			hblRemoveModal: [],
			packingListRemoveModal: [],
			commercialInvoiceRemoveModal: [],
			commercialDocumentsRemoveModal: [],
			// path: require('path')

		};
	},
	methods: {
		removeContainerGroup(key, keySecond) {
			this.formGroups[key].containers.splice(keySecond, 1)
		},
		addContainerGroup(key, id) {

			let setId = new Date().getTime()
			
			this.formGroups[key].containers.push({
				id: setId,
				shipment_suppliers_id: id,
				container_num: '',
				cartons: '',
				size: '',
				weight: '',
				container_id: 0
			})
			
		},
		handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
		setSelected(value, key) {
			console.log(value);
		},
		/**
		 * Respond to the file change
		 */
		fileChange(event, key, fieldName) {
			var {
				target
			} = event;
			var path = target.value;
			switch (fieldName) {
				case 'hbl_copy':
					this.formGroups[key].hbl_copy = target.files[0];
					break;
				case 'packing_list':
					this.formGroups[key].packing_list = target.files[0];
					break;
				case 'commercial_documents':
					this.formGroups[key].commercial_documents = target.files[0];
					break;
				case 'commercial_invoice':
					this.formGroups[key].commercial_invoice = target.files[0];
					break;
				default:

			}

		},
		/**
		 * Close the modal.
		 */
		closeModal() {
			this.duplicateModal = false;
		},
		openModal() {
			this.duplicateModal = true;
		},
		removeGroup(item, key) {
			jQuery(`#shipment-supplier-group-${key}`)
				.remove();
			var filterFormGroups = _.filter(this.formGroups, o => (o.id != item.id));
			var filterSelectedSuppliers = _.filter(this.selected_suppliers, o => (o.key != key));
			this.errors[key] = false;
			this.selected_suppliers = filterSelectedSuppliers;
			this.formGroups = filterFormGroups;
			this.value = JSON.stringify(filterFormGroups);
		},

		selectPurchaseOrder(value, key) {
			this.selected_purchase_orders.push({
				key,
				value
			});
		},

		selectSupplier(id, key) {

			//var findExisting = _.findIndex( this.selected_suppliers, o => (o.key!=key && o.value==id));
			this.selected_suppliers.push({
				key,
				value: id
			});

		},
		unSelectSupplier(id) {

		},
		addGroup() {

			let setId = new Date().getTime();

			this.formGroups.push({
				id: setId,
				hbl_copy: null,
				packing_list: null,
				commercial_documents: null,
                commercial_invoice: null,
                po_id: '',
				po_num: '',
				volume: '',
				supplier_id: '',
				kg: '',
				cbm: '',
				incoterm_id: '',
				hbl_num: '',
				product_description: '',
				//total_weight: '',
				total_cartons: '',
				bl_status: 'Pending',
				ams_num: '',
				containers: []
			});

			this.value = JSON.stringify(this.formGroups);

			// this.hblRemoveModal.push(false);
			// this.packingListRemoveModal.push(false);
			// this.commercialDocumentsRemoveModal.push(false);
			// this.commercialInvoiceRemoveModal.push(false);
		},
		/*
		 * Set the initial, internal value for the field.
		 */
		setInitialValue() {
			this.value = this.field.value || ''
		},
		/**
		 * Fill the given FormData object with the field's internal value.
		 */
		fill(formData) {
			if (this.formGroups.length > 0) {

				var fileContainer = [];
				for (var x = 0; x < this.formGroups.length; x++) {
					if (typeof this.formGroups[x].hbl_copy != 'undefined')
						formData.append(`hbl_copy_${(x + 1)}`, this.formGroups[x].hbl_copy);
					if (typeof this.formGroups[x].packing_list != 'undefined')
						formData.append(`packing_list_${(x + 1)}`, this.formGroups[x].packing_list);
					if (typeof this.formGroups[x].commercial_documents != 'undefined')
						formData.append(`commercial_documents_${(x + 1)}`, this.formGroups[x].commercial_documents);
					if (typeof this.formGroups[x].commercial_invoice != 'undefined')
						formData.append(`commercial_invoice_${(x + 1)}`, this.formGroups[x].commercial_invoice);
				}

				formData.append(this.field.attribute, JSON.stringify(this.formGroups));

			} else
				formData.append(this.field.attribute, '[]');

			//return false;

		},

		/**
		 * Update the field's internal value.
		 */
		handleChange(value) {
			this.value = value
		},

		selectSupplier(id, key) {

			this.selected_purchase_orders.push({
				key,
				value: id
			});
		},
		openHblRemoveModal(key) {
			this.$set(this.hblRemoveModal, key, true)
		},
		openPackingListRemoveModal(key) {
			this.$set(this.packingListRemoveModal, key, true)
		},
		openCommercialDocumentsRemoveModal(key) {
			this.$set(this.commercialDocumentsRemoveModal, key, true)
		},
		openCommercialInvoiceRemoveModal(key) {
			this.$set(this.commercialInvoiceRemoveModal, key, true)
		},
		closeHblRemoveModal(key) {
			this.$set(this.hblRemoveModal, key, false)
		},
		closePackingListRemoveModal(key) {
			this.$set(this.packingListRemoveModal, key, false)
		},
		closeCommercialDocumentsRemoveModal(key) {
			this.$set(this.commercialDocumentsRemoveModal, key, false)
		},
		closeCommercialInvoiceRemoveModal(key) {
			this.$set(this.commercialInvoiceRemoveModal, key, false)
		},
		async destroy(event, key, fieldName, file) {

			this.formGroups[key][fieldName] = null
			this.$emit('file-deleted')
			// console.log(this.$emit('file-deleted'))
			// console.log(this._retrieved_at)

			axios.post('/custom-api/shipment/supplier-section/file/delete', {
					headers: {
						'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content')
							.value,
						'Accept': 'application/json'
					},

					shipment_id: this.resourceId,
					file: fieldName,
					key: key,
				})
				.then(res => {
					// console.log(res)

					Nova.success(
						this.__('The :resource file was deleted!', {
							resource: fieldName
						}))

				})
				.catch(err => {
					// console.log(err)
					Nova.error(
						this.__('There was a problem deleting the file.')
					)

				})

			this.closeHblRemoveModal(key)
			this.closePackingListRemoveModal(key)
			this.closeCommercialDocumentsRemoveModal(key)
			this.closeCommercialInvoiceRemoveModal(key)
		},
		filename(file) {
			// console.log(this.path)
			// return file
			// const path = require('path')
			// return path
			// 	.parse(file)
			// 	.base
			return file.split('\\')
				.pop()
				.split('/')
				.pop();

		}
	},
}
</script>
