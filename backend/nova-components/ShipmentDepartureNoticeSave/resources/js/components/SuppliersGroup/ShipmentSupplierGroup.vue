<template>
<default-field :field="field" :errors="errors">
	<template slot="field">
		<delete-document-modal
          :item="current_document_delete_item"
          :delete_document_modal_show.sync="delete_document_modal_show"
          :url="baseUrl"
          :field.sync="field"
          :token="csrfToken"
          :resourceId="resourceId"
          :supplier_updated.sync="supplier_updated"
          @setSuppliers="setSuppliers"
        >
          <template v-slot:header="{ headerProps }">
            <div class="flex w-full justify-between">
              <h3>Delete Document</h3>
              <div>
                <button @click.prevent="headerProps.close">
                  <font-awesome-icon color="#0171a1" icon="fa-solid fa-xmark" />
                </button>
              </div>
            </div>
          </template>
          <template v-slot:body="{ item }">
            <div class=" delete-modal-body flex w-full justify-center items-center flex-col py-4">
              Are you sure you want to delete the <strong>{{ filename(item.path) }}</strong> document?
            </div>
          </template>
          <template v-slot:footer="{ footerProps }">
            <div class="flex w-full flex-row">
              <button @click.prevent="footerProps.deleteItem(footerProps.item)" class="btn-default btn btn-primary">
                {{ ( footerProps.item.is_deleting_loading ) ? 'Deleting...' : 'Delete' }}
              </button>
              <button @click.prevent="footerProps.close" class="ml-2 btn-default btn btn-primary">
                Cancel
              </button>
            </div>
          </template>
        </delete-document-modal>
        <document-modal
          :show.sync="document_modal_show"
          :item="current_supplier"
          :resourceId="resourceId"
          :url="baseUrl"
          :token="csrfToken"
          :field.sync="field"
          :formGroups="formGroups"
          :supplier_updated.sync="supplier_updated"
          @setSuppliers="setSuppliers"
          @updateSupplierGroup="updateSupplierGroupSpecial"
      		@setSupplierDocuments="setSupplierDocuments"
        >
          <template v-slot:header="{ headerProps }">
            <div class="flex w-full justify-between">
              <h3>Upload Documents</h3>
              <div>
                <button @click.prevent="headerProps.close">
                  <font-awesome-icon color="#0171a1" icon="fa-solid fa-xmark" />
                </button>
              </div>
            </div>
          </template>
          <template v-slot:body="{ item }">
            <div class="flex w-full justify-center items-center flex-col py-4">
              <div class="w-full text-center">Browse Files Here</div>
              <button @click.prevent="item.selectFile" class="flex flex-row text-center justify-center items-center mt-2">
                <font-awesome-icon color="#0171a1" icon="fa-solid fa-arrow-up" />
                <div class="ml-2">Upload</div>
              </button>
            </div>
          </template>
          <template v-slot:footer="{ footerProps }">
            <div class="flex w-full flex-row">
              <button @click.prevent="footerProps.upload(footerProps.item)" class="btn-default btn btn-primary">
                Set Document(s)
              </button>
              <button @click.prevent="footerProps.close" class="ml-2 btn-default btn btn-primary">
                Cancel
              </button>
            </div>
          </template>
        </document-modal>
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

		<div v-show="formGroups.length>0" :id="`shipment-supplier-group-${item.id}`" v-for="(item,key) in formGroups" class="shipment-supplier-group" style="border-bottom: 1px solid #eef1f4; padding-top: 4px;">
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
						<v-select :id="`shipment-supplier-select-${key}`" v-model="item.supplier_id" :reduce="company_name => company_name.id" label="company_name" :options="supplierOptions" @input="value => selectSupplier ( value, key )">
						</v-select>
                </div>
					<div v-show="(typeof errorMessages!='undefined' && typeof errorMessages.supplier_id!='undefined' && errorMessages.supplier_id.length>0 && errorMessages.supplier_id.indexOf(item.supplier_id)!=-1)"
						class="help-text error-text text-danger mt-2">
						Please make sure that it has no duplicate supplier selection.
					</div>
				</div>
			</div>
            <div>
                <label>Select Buyer</label>
                <div>
                    <div>
                        <v-select :id="`shipment-buyer-select-${key}`" v-model="item.buyer_id" :reduce="company_name => company_name.id" label="company_name" :options="BuyerOptions" @input="value => selectBuyer ( value, key )">
                        </v-select>
                    </div>
                    <div v-show="(typeof errorMessages!='undefined' && typeof errorMessages.buyer_id!='undefined' && errorMessages.buyer_id.length>0 && errorMessages.buyer_id.indexOf(item.buyer_id)!=-1)"
                         class="help-text error-text text-danger mt-2">
                        Please make sure that it has no duplicate buyer selection.
                    </div>
                </div>
            </div>
			<div>
		      	<label>Cargo Ready Date</label>
		      	<div>
		      		<date-time-picker
		                class="w-full form-control form-input form-input-bordered mb-1"
		                :value="item.cargo_ready_date"
		                :dateFormat="pickerFormat"
		                @change="(value) => {handleCargoReadyDate(value, item)}"
		                :placeholder="placeholder"
		                :enable-time="false"
		                :enable-seconds="false"
		                :first-day-of-week="firstDayOfWeek"
		                :disabled="isReadonly"
		            />
		      	</div>
		    </div>
			<div>
				<label>Select Order(s)</label>
				<div>
					<div>
						 <v-select :id="`purchase-order-select-${key}`" v-model="item.po_num" :reduce="po_number => po_number.po_number" label="po_number" :options="orders" @input="value => selectOrders ( value, key )"></v-select>
					</div>
					<div v-show="(typeof errorMessages!='undefined' && typeof errorMessages.po_id!='undefined' && errorMessages.po_id.length>0 && errorMessages.po_id.indexOf(item.po_id)!=-1)" class="help-text error-text text-danger mt-2">
						Please make sure that it has no duplicate purchase order selection.
					</div>
				</div>
			</div>

			<div>
				<label>Orders No. # (Old Way)</label>
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
				<label>AMS Number</label>
				<div>
					<input type="text" v-model="item.ams_num" class="w-full form-control form-input form-input-bordered" />
				</div>
			</div>
			<div>
				<label>HBL Number</label>
				<div>
					<input type="text" v-model="item.hbl_num" class="w-full form-control form-input form-input-bordered" />
				</div>
			</div>
			<div v-if="dateDifference < 0">
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
			<div v-if="dateDifference < 0">
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
			<div v-if="dateDifference < 0">
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
			<div v-if="dateDifference < 0">
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
				<div>
					<label>Marks</label>
					<div>
						<textarea v-model="item.marks" class="w-full form-control form-input form-input-bordered h-auto" />
					</div>
				</div>
                <div >
                    <label>Terms</label>
                    <div>
                        <select v-model="item.incoterm_id" class="w-full form-control form-select form-select-bordered" >
                            <option value="">Select Terms</option>
                            <option v-show="incoTermValues.length>0" v-for="option in incoTermValues" :value="option.id">{{option.name}}</option>
                        </select>
                    </div>
                </div>

				<!--Fields for BoL-->
				<div>
					<label>BoL Shipper Address</label>
					<div>
						<input v-model="item.bol_shipper_address"  type="text" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
				<div>
					<label>BoL Shipper Phone Number</label>
					<div>
						<input v-model="item.bol_shipper_phone_number"  type="text" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
				<div>
					<label>BoL Consignee Address</label>
					<div>
						<input v-model="item.bol_consignee_address"  type="text" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
				<div>
					<label>BoL Consignee Phone Number</label>
					<div>
						<input v-model="item.bol_consignee_phone_number" type="text" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
				<div>
					<label>BoL Notify Party</label>
					<div>
						<input v-model="item.bol_notify_party" type="text" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
				<div>
					<label>BoL Notify Address</label>
					<div>
						<input v-model="item.bol_notify_address" type="text" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>
				<div>
					<label>BoL Notify Phone Number</label>
					<div>
						<input v-model="item.bol_notify_phone_number" type="text" class="w-full form-control form-input form-input-bordered" />
					</div>
				</div>

                <div >
                    <label>HBL Telex Status</label>
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
                <div style="display: flex; flex-direction: column !important;" v-for="(itemSecond,keySecond) in item.containers">
					<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
						<label style="width: 40%; line-height: 36px;">Select Container</label>
						<div style="width: 60%;">
							<v-select :id="`shipment-supplier-container-select-${key}-${keySecond}`" v-model="itemSecond.container_id" :reduce="container_num => container_num.id" label="container_num" :options="containersOptions">
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
          <div v-if="typeof resourceId!=='undefined' && resourceId!==null" id="shipment-supplier-group-submit-group" style="width:100%;">
              <button style="width: 51%;" class="btn btn-default btn-primary inline-flex items-center relative mr-3 shipment-add-group" @click.prevent="addContainerGroup(key,item.id)" >Add Supplier Container</button>
          </div>
          <!--container group-->
          <div v-if="dateDifference >= 0" class="flex flex-row">
		      	<label>HBL Copy</label>
		      	<div>
		          <div class="flex flex-col" v-if="typeof item.temp_hbl_copies!=='undefined'">
		            <div v-tooltip="`${thc}`" class="font-bold supplier-document-display-name font-bold" v-for="thc in item.temp_hbl_copies">
		              {{
		                thc
		              }}
		            </div>
		          </div>
		          <button @click.prevent="addDocument(item, 'Hbl')" class="btn btn-default btn-primary">Add Document+</button>
		          <div class="flex flex-row mt-2" v-for="i in item.hbl_customer">
		            <div v-tooltip="`${i.name}`" class="supplier-document-path">
		              {{
		                i.name
		              }}
		            </div>
		            <div v-if="i.is_added_by_customer==0" class="ml-2">
		              <button @click.prevent="deleteSupplierDocument(i)">
		                  <font-awesome-icon color="#0171a1" icon="fa-solid fa-window-close" />
		              </button>
		            </div>
		          </div>
		        </div>
			    </div>
			    <div v-if="dateDifference >= 0" class="flex flex-row">
		        <label>Commercial Invoice</label>
		        <div>
		          <div class="flex flex-col" v-if="typeof item.temp_commercial_invoices!=='undefined'">
		            <div v-tooltip="`${tci}`" class="font-bold supplier-document-display-name font-bold" v-for="tci in item.temp_commercial_invoices">
		              {{
		                tci
		              }}
		            </div>
		          </div>
		          <button @click.prevent="addDocument(item, 'Commercial Invoice')" class="btn btn-default btn-primary">Add Document+</button>
		          <div class="flex flex-row mt-2" v-for="i in item.commercial_invoice_customer">
		            <div v-tooltip="`${i.name}`" class="supplier-document-path">
		              {{
		                i.name
		              }}
		            </div>
		            <div v-if="i.is_added_by_customer==0" class="ml-2">
		              <button @click.prevent="deleteSupplierDocument(i)">
		                  <font-awesome-icon color="#0171a1" icon="fa-solid fa-window-close" />
		              </button>
		            </div>
		          </div>
		        </div>
			    </div>
			    <div v-if="dateDifference >= 0" class="flex flex-row">
		        <label>Packing List</label>
		        <div>
		          <div class="flex flex-col" v-if="typeof item.temp_packing_lists!=='undefined'">
		            <div v-tooltip="`${tpl}`" class="font-bold supplier-document-display-name font-bold" v-for="tpl in item.temp_packing_lists">
		              {{
		                tpl
		              }}
		            </div>
		          </div>
		          <button @click.prevent="addDocument(item, 'Packing List')" class="btn btn-default btn-primary">Add Document+</button>
		          <div class="flex flex-row mt-2" v-for="i in item.packing_list_customer">
		            <div v-tooltip="`${i.name}`" class="supplier-document-path">
		              {{
		                i.name
		              }}
		            </div>
		            <div v-if="i.is_added_by_customer==0" class="ml-2">
		              <button @click="deleteSupplierDocument(i)">
		                  <font-awesome-icon color="#0171a1" icon="fa-solid fa-window-close" />
		              </button>
		            </div>
		          </div>
		        </div>
			    </div>
		      <div v-if="dateDifference >= 0" class="flex flex-row">
		        <label>Invoice & Packing List</label>
		        <div>
		          <div class="flex flex-col" v-if="typeof item.temp_invoice_packing_lists!=='undefined'">
		            <div v-tooltip="`${tipl}`" class="font-bold font-bold supplier-document-display-name" v-for="tipl in item.temp_invoice_packing_lists">
		              {{
		                tipl
		              }}
		            </div>
		          </div>
		          <button @click.prevent="addDocument(item, 'Invoice And Packing List')" class="btn btn-default btn-primary">Add Document+</button>
		          <div class="flex flex-row mt-2" v-for="i in item.invoice_packing_list_customer">
		            <div v-tooltip="`${i.name}`" class="supplier-document-path">
		              {{
		                i.name
		              }}
		            </div>
		            <div v-if="i.is_added_by_customer==0" class="ml-2">
		              <button @click="deleteSupplierDocument(i)">
		                  <font-awesome-icon color="#0171a1" icon="fa-solid fa-window-close" />
		              </button>
		            </div>
		          </div>
		        </div>
		      </div>
		      <div v-if="dateDifference >= 0" class="flex flex-row">
		        <label>Other Commercial Documents</label>
		        <div>
		          <div class="flex flex-col" v-if="typeof item.temp_other_commercial_docs!=='undefined'">
		            <div v-tooltip="`${tocd}`" class="font-bold supplier-document-display-name" v-for="tocd in item.temp_other_commercial_docs">
		              {{
		                tocd
		              }}
		            </div>
		          </div>
		          <button @click.prevent="addDocument(item, 'Other Commercial Docs')" class="btn btn-default btn-primary">Add Document+</button>
		          <div class="flex flex-row mt-2" v-for="i in item.commercial_documents_customer">
		            <div v-tooltip="`${i.name}`" class="supplier-document-path">
		              {{
		                i.name
		              }}
		            </div>
		            <div v-if="i.is_added_by_customer==0" class="ml-2">
		              <button @click.prevent="deleteSupplierDocument(i)">
		                  <font-awesome-icon color="#0171a1" icon="fa-solid fa-window-close" />
		              </button>
		            </div>
		          </div>
		        </div>
		      </div>
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
import './sass/field.scss';
//import DuplicateModal from '../../components/Modals/DuplicateModal';
import _ from 'lodash';
import jQuery from 'jquery';
import 'vue-select/dist/vue-select.css';
import DocumentModal from './Modals/DocumentModal.vue'
import DeleteDocumentModal from './Modals/DeleteDocumentModal.vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowUp, faWindowClose, faXmark } from '@fortawesome/free-solid-svg-icons'
import {
    mapGetters,
    mapActions
} from 'vuex'

import axios from 'axios';


export default {
	mixins: [FormField, HandlesValidationErrors, InteractsWithDates],
	props: ['resourceName', 'resourceId', 'field', 'containers', 'supplierOptions', 'incoTermValues', 'purchaseOrderOptions', 'containers', 'baseUrl', 'shipment', 'BuyerOptions'],
	updated() {
		this.$emit('updateSupplierGroup', this.formGroups)
        //this.updateSupplierGroup(this.formGroups)
    },
    components: {
        DocumentModal,
        FontAwesomeIcon,
        DeleteDocumentModal
    },
    created() {
    	library.add(faArrowUp)
      library.add(faWindowClose)
      library.add(faXmark)
    	if (typeof this.formGroups[0]!=='undefined' && this.formGroups.length > 0) {
    		/*
				for (var x=0;x<this.formGroups.length;x++) {
					if (typeof this.formGroups[x].containers=='undefined') {
						this.formGroups[x].containers = []
					}
				}*/
			}
    },
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

		/*
		var token = jQuery('meta[name="csrf-token"]').attr('content');
		jQuery.ajax({
			method: 'GET',
			url: '/custom/suppliers',
			data: {
				_token: token
			}
		})
		.done(response => {

			let {
				results
			} = response;

			if (results.length > 0) {
				this.supplier_options = results;
			}

		});*/

		//this.supplier_options = this.supplierOptions
		//this.inco_term_values = this.incoTermValues
		//this.purchase_order_options = this.purchaseOrderOptions


		//set default selected purchase orders
		/*
		let getPurchaseOrders = (this.field.value == '' || this.field.value == null) ? [] : JSON.parse(this.field.value);

		if (getPurchaseOrders.length > 0) {
			this.selected_purchase_orders = _.map(getPurchaseOrders, (po, key) => {
				return {
					key,
					value: po.po_id
				};
			})
		}

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
		});

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
		}); */

		/*
		if (this.formGroups.length > 0) {
			for (var x=0;x<this.formGroups.length;x++) {
				if (typeof this.formGroups[x].containers=='undefined') {
					this.formGroups[x].containers = []
				}
			}
		}*/

	},

	computed: {
		...mapGetters({
    	getShipmentSupplierGroupField: 'shipmentDeparture/getShipmentSupplierGroupField'
    }),
		dateDifference() {
    	return parseInt(moment(this.shipment.created_at).diff(moment('2022-07-31'), 'days').toString())
  	},
		csrfToken() {
	        return jQuery('meta[name="csrf-token"]').attr('content');
	    },
		document_modal_show: {
	        set(value) {
	          this.document_departure_modal_show_data = value
	        },
	        get() {
	          return this.document_departure_modal_show_data
	        }
      	},
      	supplier_updated: {
            get() {
              return this.supplier_updated_data
            },
            set(value) {
              this.supplier_updated_data = value
            }
        },
      	delete_document_modal_show: {
	        set(value) {
	          this.delete_document_departure_modal_show_data = value
	        },
	        get() {
	          return this.delete_document_departure_modal_show_data
	        }
	    },
		containersOptions() {
			let options = (typeof JSON.parse(this.containers)!=='undefined') ? JSON.parse(this.containers) : []
			return options
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
        token() {
            return jQuery('meta[name="csrf-token"]').attr("content");
        }
	},
	data: function() {
		return {
			current_supplier: {},
			supplier_updated_data: false,
			document_departure_modal_show_data: false,
            delete_document_departure_modal_show_data: false,
			duplicateModal: false,
			current_document_delete_item: {},
			selected_suppliers: [],
            selected_buyers:[],
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
			purchase_order_options: [],
			custom_supplier_options: [],
			custom_purchase_order_options: [],
			formGroups: (this.field.value == '' || this.field.value == null) ? [] : JSON.parse(this.field.value),
			hblRemoveModal: [],
			packingListRemoveModal: [],
			commercialInvoiceRemoveModal: [],
			commercialDocumentsRemoveModal: [],
            orders: [],
            selectedOrders: '',
			// path: require('path')

		};
	},
	methods: {
		updateSupplierGroupSpecial(suppliers) {
      this.$emit('updateSupplierGroup', this.formGroups)
    },
    setSupplierDocuments(payload) {
      this.$emit('setSupplierDocuments', payload)
    },
		findSupplierWithinShipment(item) {
      let find_supplier = _.find(JSON.parse(this.shipment.suppliers_group_bookings), {
        id: item.id
      })
      return find_supplier
    },
		deleteSupplierDocument(item) {
      this.current_document_delete_item = item
      this.delete_document_modal_show = true
    },
    setSuppliers() {
      this.$emit('setSuppliers')
    },
		addDocument(item, type){
      item.special_type = type
      this.document_modal_show = true
      this.current_supplier = item
    },
		handleCargoReadyDate(value, item) {
       // this.field.value = value
        item.cargo_ready_date = value
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
					this.$emit('updateSupplierGroup',this.formGroups);
					break;
				case 'packing_list':
					this.formGroups[key].packing_list = target.files[0];
					this.$emit('updateSupplierGroup',this.formGroups);
					break;
				case 'commercial_documents':
					this.formGroups[key].commercial_documents = target.files[0];
					this.$emit('updateSupplierGroup',this.formGroups);
					break;
				case 'commercial_invoice':
					this.formGroups[key].commercial_invoice = target.files[0];
					this.$emit('updateSupplierGroup',this.formGroups);
					break;
				default:
					this.$emit('updateSupplierGroup',this.formGroups);
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
			//jQuery(`#shipment-supplier-group-${key}`)
				//.remove();
			//var filterFormGroups = _.filter(this.formGroups, o => (o.id != item.id));
			this.formGroups.splice(key,1)
			var filterSelectedSuppliers = _.filter(this.selected_suppliers, o => (o.key != key));
			this.errors[key] = false;
			this.selected_suppliers = filterSelectedSuppliers;
			//this.formGroups = filterFormGroups;
			//this.value = JSON.stringify(filterFormGroups);

		},
		selectPurchaseOrder(value, key) {
			this.selected_purchase_orders.push({
				key,
				value
			});
		},

        async selectSupplier(id, key) {
            this.formGroups[key].po_num = null;

            this.formGroups[key].buyer_id = null;
			//var findExisting = _.findIndex( this.selected_suppliers, o => (o.key!=key && o.value==id));
			this.selected_suppliers.push({
				key,
				value: id
			});
            this.selectedOrders = '';
            this.orders = [];

            const order_type = "PO";
            const result = await Nova.request().get(
                `/custom/customer-orders/${id}/${order_type}`,

            );
            let data = result.data.results

            if (typeof data !== "undefined") {
                this.orders = data;
            }

        },
        async selectBuyer(id, key) {
            this.formGroups[key].po_num = null;

		    this.formGroups[key].supplier_id = null;
            //var findExisting = _.findIndex( this.selected_buyers, o => (o.key!=key && o.value==id));
            this.selected_buyers.push({
                key,
                value: id
            });
            this.selectedOrders = '';
            this.orders = [];

            const order_type = "SO";

            const result = await Nova.request().get(
                `/custom/customer-orders/${id}/${order_type}`,

            );
            let data = result.data.results

            if (typeof data !== "undefined") {
                this.orders = data;
            }

        },

        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text);
                return data;
            });
        },

        selectOrders(value) {
            this.selectedOrders = value;
        },
		unSelectSupplier(id) {

		},
		removeContainerGroup(key, keySecond) {
			this.formGroups[key].containers.splice(keySecond, 1)
		},
		cartonChange(e, currentValue, compareValue) {

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

		addGroup() {

			let setId = new Date().getTime();

			this.formGroups.push({
				id: setId,
				hbl_copy: null,
				packing_list: null,
				commercial_documents: null,
                commercial_invoice: null,
                po_id: '',
                cargo_ready_date: '',
				po_num: '',
				volume: '',
				supplier_id: '',
				kg: '',
				cbm: '',
				incoterm_id: '',
				hbl_num: '',
				product_description: '',

				marks: '',

				bol_shipper_address: '',
				bol_shipper_phone_number: '',

				bol_consignee_address: '',
				bol_consignee_phone_number: '',

				bol_notify_party: '',
				bol_notify_address: '',
				bol_notify_phone_number: '',

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
					if (typeof this.formGroups[x].packing_list != 'undefined')
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
			return (typeof file!=='undefined') ? file.split('\\')
				.pop()
				.split('/')
				.pop() : ''

		}
	},
}
</script>
