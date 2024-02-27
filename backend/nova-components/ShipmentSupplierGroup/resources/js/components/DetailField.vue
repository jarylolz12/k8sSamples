<template>
<div class="">
	<div class="flex border-b border-40">
		<vue-final-modal v-model="isOpenPurchaseOrdersProductsModal">
			<purchase-orders-products-detail
				:isOpenPurchaseOrdersProductsModal="isOpenPurchaseOrdersProductsModal"
				:purchaseOrder="purchaseOrder"
				@closePOProductsTableModal="closePOProductsTableModal"
			/>
		</vue-final-modal>
		<div class="w-1/4 py-4">
            <h4 class="font-normal text-80">{{ label }}</h4>
		</div>
		<div class="w-3/4 py-4 break-words" style="padding-top: 11px;">
			<div style="border-bottom: 0px transparent !important; padding-top: 5px;" v-show="(suppliers.length==0 || suppliers=='')" class="shipment-supplier-group">
				<div>
					No Supplier added yet to this Shipment.
				</div>
			</div>
			<div
                v-if="loading && suppliers.length > 0"
				class="pt-1"
                style="min-height: 30px"
                >
                <loader style="margin-left: 0px !important;margin-right: 0px !important;" class="text-60" />
            </div>
			<div data-aos="fade-left" data-aos-duration="500" data-aos-once="true"  v-show="(suppliersDisplay && suppliersDisplay.length>0) && loading==false" :id="`shipment-supplier-group-${item.id}`" v-for="(item,key) in suppliersDisplay" class="shipment-supplier-group" style="padding-top: 0px;">
				<!-- <div v-if="item.isOpenPurchaseOrdersProductsModal" class="absolute w-full h-screen purchase-order-container">
					<vue-final-modal v-model="item.isOpenPurchaseOrdersProductsModal">
						<purchase-orders-products-detail
							:isOpenPurchaseOrdersProductsModal="item.isOpenPurchaseOrdersProductsModal"
							:purchaseOrder="item.purchaseOrder"
							:supplierKey="key"
							@closePOProductsTableModal="closePOProductsTableModal"
						/>
					</vue-final-modal>
				</div> -->
				<div style="border-top: 1px solid #eef1f4; border-bottom: 1px solid #eef1f4; line-height: 26px; padding-bottom: 0px;">
					<label style="padding-top: 0px !important;" class="font-bold">HBL Item</label>
				</div>
                <div v-if="item.buyer_id == null || item.buyer_id == '' ">
					<label>Supplier Name</label>
					<span style="padding-top: 5px;">{{ item.company_name }}</span>
				</div>
                <div v-if="item.supplier_id == '' || item.supplier_id == null">
                    <label>Buyer Name</label>
                    <span style="padding-top: 5px;">{{ item.company_name }}</span>
                </div>
				<div>
					<label>Cargo Ready Date</label>
					<span style="padding-top: 5px;">{{ item.cargo_ready_date }}</span>
				</div>
                <!-- <div>
					<label>PO #</label>
					<span style="padding-top: 5px;">{{ item.po_name}}</span>
				</div> -->
				<div>
					<label>Orders No. # (Old Way)</label>
					<span style="padding-top: 5px;">{{ item.po_num}}</span>
				</div>
				<div>
					<label>Select Order(s)</label>
					<div class="mt-3">
						<tbody>
							<tr v-for="(purchaseOrder, index) in item.purchaseOrders" :key="`selected-purchase-order-${index}`">
								<td class="p-2">{{purchaseOrder.po_number}}</td>
								<td class="p-2"><button type="button" class="mr-1 bg-primary p-1 text-white" @click="onViewPurchaseOrderProducts(purchaseOrder, key)">View</button></td>
							</tr>
						</tbody>
					</div>
				</div>
				<div>
					<label>Volume</label>
					<span style="padding-top: 5px;">{{ item.cbm }}</span>
				</div>
				<div>
					<label>Weight</label>
					<span style="padding-top: 5px;">{{ item.kg }}</span>
				</div>
				<div>
					<label>Total Cartons</label>
					<span style="padding-top: 5px;">{{ item.total_cartons }}</span>
				</div>
				<div>
					<label>AMS Number</label>
					<span style="padding-top: 5px;">{{ item.ams_num }}</span>
				</div>
				<div>
					<label>HBL Number</label>
					<span style="padding-top: 5px;">{{ item.hbl_num }}</span>
				</div>
				<div>
					<label>Product Description</label>
					<span style="padding-top: 5px;">{{ item.product_description }}</span>
				</div>
				<div>
					<label>Marks</label>
					<span style="padding-top: 5px;">{{ item.marks }}</span>
				</div>
				<div>
					<label>Terms</label>
					<span style="padding-top: 5px;">{{ item.incoterm_name }}</span>
				</div>
				<div>
					<label>HBL Telex Status</label>
					<span style="padding-top: 5px;">{{ item.bl_status }}</span>
				</div>
				<div>
					<label>BoL Shipper Address</label>
					<span style="padding-top: 5px;">{{ item.bol_shipper_address }}</span>
				</div>
				<div>
					<label>BoL Shipper Address</label>
					<span style="padding-top: 5px;">{{ item.bol_shipper_phone_number }}</span>
				</div>
				<div>
					<label>BoL Consignee Address</label>
					<span style="padding-top: 5px;">{{ item.bol_consignee_address }}</span>
				</div>
				<div>
					<label>BoL Consignee Phone Number</label>
					<span style="padding-top: 5px;">{{ item.bol_consignee_phone_number }}</span>
				</div>
				<div>
					<label>BoL Notify Party</label>
					<span style="padding-top: 5px;">{{ item.bol_notify_party }}</span>
				</div>
				<div>
					<label>BoL Notify Address</label>
					<span style="padding-top: 5px;">{{ item.bol_notify_address }}</span>
				</div>
				<div>
					<label>BoL Notify Phone Number</label>
					<span style="padding-top: 5px;">{{ item.bol_notify_phone_number }}</span>
				</div>

				<div style="display: flex; flex-direction: column !important; margin-bottom: 15px; padding-bottom: 0px;" v-for="(itemSecond,keySecond) in item.containers">
					<div style="display: flex;flex-direction: row; width: 60%; padding-bottom: 0px; border-bottom: 1px solid #eef1f4;border-top: 1px solid #eef1f4; margin-bottom: 15px;">
						<label style="width: 40%; line-height: 26px; font-weight: bold;">Supplier Container {{
								parseInt(keySecond + 1)
							}}</label>
						<div style="width: 60%; ">
						</div>
					</div>
					<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
						<label style="width: 40%;">Container #</label>
						<div style="width: 60%; ">
							{{
								itemSecond.container_num
							}}
						</div>
					</div>
                	<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
						<label style="width: 40%; ">Cartons</label>
						<div style="width: 60%; ">
							{{
								itemSecond.cartons
							}}
						</div>
					</div>
					<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
						<label style="width: 40%; ">Weight(Number of KG)</label>
						<div style="width: 60%; ">
							{{
								itemSecond.weight
							}}
						</div>
					</div>
					<div style="display: flex;flex-direction: row; width: 100%; padding-bottom: 15px;">
						<label style="width: 40%; ">Volume(Number of CBM)</label>
						<div style="width: 60%; ">
							{{
								itemSecond.size
							}}
						</div>
					</div>
                </div>
				<div style="margin-top: -10px;">
					<label>HBL System Generated</label>
					<span>
						<p class="flex items-center text-sm mt-5">
							<a :href="`/bol/download/${resourceId}/${item.id}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center">
								<icon class="mr-2" type="download" view-box="0 0 24 24" width="16" height="16" />
								<span class="class mt-1">{{ __('Download') }}</span>
							</a>
						</p>
					</span>
				</div>
				<div class="flex flex-col">
					<label class="detail-supplier-label flex items-center font-bold">Hbl Copy</label>
					<div v-if="dateDifference >=0" :class="`w-full flex flex-col`">
						<div v-if="!item.hbl_customer">
							—
						</div>
						<div v-for="document_hbl_customer in item.hbl_customer" class="w-full flex flex-row py-2 ml-2 pb-2">
							<div class="mr-2">
								<div v-show="document_hbl_customer.path" class="flex items-center text-sm">
									<a :href="`/custom/download?link=${encodeURIComponent(document_hbl_customer.path)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary flex items-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
										class="fill-current mr-2">
											<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z">
											</path>
										</svg>
										<span class="class flex items-center">Download</span>
									</a>
								</div>
							</div>
							<div v-tooltip="`${document_hbl_customer.name}`" class="mr-2 supplier-document-path-detail">
								{{
									document_hbl_customer.name
								}}
							</div>
							<div class="flex items-center">
								Added by {{ ( document_hbl_customer.is_added_by_customer === 0) ? 'Admin' : 'Customer' }}
							</div>
						</div>
					</div>
					<div v-if="dateDifference < 0">
						<!---->
						<span class="break-words"> {{ filename(item.hbl_copy) }}
							<p v-show="item.hbl_copy==null">—</p>
						</span>
						<!---->
						<p v-show="item.hbl_copy" class="flex items-center text-sm"><a :href="`/custom/download?link=${encodeURIComponent(item.hbl_copy)}`" tabindex="0"
								class="cursor-pointer dim btn btn-link text-primary inline-flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
								class="fill-current mr-2">
								<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
							</svg>
							<span class="class mt-1">Download</span></a>
						</p>
					</div>
				</div>
				<div class="flex flex-col">
					<label class="detail-supplier-label flex items-center font-bold">Commercial Invoice</label>
					<div v-if="dateDifference >= 0" :class="`w-full flex flex-col`">
						<div v-if="!item.commercial_invoice_customer">
							—
						</div>
						<div v-for="document_commercial_invoice_item in item.commercial_invoice_customer" class="w-full flex flex-row items-center pt-2 ml-2">
							<div class="mr-2">
								<div v-show="document_commercial_invoice_item.path" class="flex items-center text-sm">
									<a :href="`/custom/download?link=${encodeURIComponent(document_commercial_invoice_item.path)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary flex items-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
										class="fill-current mr-2">
											<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z">
											</path>
										</svg>
										<span class="class flex items-center">Download</span>
									</a>
								</div>
							</div>
							<div v-tooltip="`${document_commercial_invoice_item.name}`" class="mr-2 supplier-document-path-detail">
								{{
									document_commercial_invoice_item.name
								}}
							</div>
							<div class="flex items-center">
								Added by {{ ( document_commercial_invoice_item.is_added_by_customer === 0) ? 'Admin' : 'Customer' }}
							</div>
						</div>
					</div>
					<div v-if="dateDifference < 0">
						<!---->
						<span class="break-words"> {{ filename(item.commercial_invoice) }}
							<p v-show="item.commercial_invoice==null">—</p>
						</span>
						<!---->
						<p v-show="item.commercial_invoice" class="flex items-center text-sm"><a :href="`/custom/download?link=${encodeURIComponent(item.commercial_invoice)}`" tabindex="0"
								class="cursor-pointer dim btn btn-link text-primary inline-flex items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
									class="fill-current mr-2">
								<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
							</svg>
							<span class="class mt-1">Download</span></a></p>
					</div>
				</div>
				<div class="flex flex-col">
					<label class="detail-supplier-label flex items-center font-bold">Packing List</label>
					<div v-if="dateDifference >= 0"  :class="`w-full flex flex-col pt-2`">
						<div v-if="!item.packing_list_customer">
							—
						</div>
						<div v-tooltip="`${document_packing_list.name}`" v-for="document_packing_list in item.packing_list_customer" class="w-full flex flex-row items-center pl-2 pb-2">
							<div class="mr-2">
								<div v-show="document_packing_list.path" class="flex items-center text-sm">
									<a :href="`/custom/download?link=${encodeURIComponent(document_packing_list.path)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary flex items-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
										class="fill-current mr-2">
											<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z">
											</path>
										</svg>
										<span class="class flex items-center">Download</span>
									</a>
								</div>
							</div>
							<div class="mr-2 supplier-document-path-detail">
								{{
									document_packing_list.name
								}}
							</div>
							<div class="flex items-center">
								Added by {{ ( document_packing_list.is_added_by_customer === 0) ? 'Admin' : 'Customer' }}
							</div>
						</div>
					</div>
					<div v-if="dateDifference < 0">
						<!---->
						<span class="break-words"> {{filename(item.packing_list)}}
							<p v-show="item.packing_list==null">—</p>
						</span>
						<!---->
						<p v-show="item.packing_list!=null && item.packing_list!=''" class="flex items-center text-sm">
							<a :href="`/custom/download?link=${encodeURIComponent(item.packing_list)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation" class="fill-current mr-2">
									<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
								</svg>
								<span class="class mt-1">Download</span>
							</a>
						</p>
					</div>
				</div>
				<div v-if="dateDifference >=0" class="flex flex-col">
					<label class="detail-supplier-label flex items-center font-bold">Invoice & Packing List</label>
					<div :class="`w-full flex flex-col pl-2 pt-2`">
						<div v-if="!item.invoice_packing_list">
							—
						</div>
						<div v-for="document_invoice_packing_list_customer_item in item.invoice_packing_list" class="w-full flex flex-row items-center pb-2">
							<div class="mr-2">
								<div v-show="document_invoice_packing_list_customer_item.path" class="flex items-center text-sm">
									<a :href="`/custom/download?link=${encodeURIComponent(document_invoice_packing_list_customer_item.path)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary flex items-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
										class="fill-current mr-2">
											<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z">
											</path>
										</svg>
										<span class="class flex items-center">Download</span>
									</a>
								</div>
							</div>
							<div v-tooltip="`${document_invoice_packing_list_customer_item.name}`" class="mr-2 supplier-document-path-detail">
								{{
									document_invoice_packing_list_customer_item.name
								}}
							</div>

							<div class="flex items-center">
								Added by {{ ( document_invoice_packing_list_customer_item.is_added_by_customer === 0) ? 'Admin' : 'Customer' }}
							</div>
						</div>
					</div>
					<div v-if="1==2">
						<!---->
						<span class="break-words"> {{ filename(item.commercial_invoice) }}
							<p v-show="item.commercial_invoice==null">—</p>
						</span>
						<!---->
						<p v-show="item.commercial_invoice" class="flex items-center text-sm"><a :href="`/custom/download?link=${encodeURIComponent(item.commercial_invoice)}`" tabindex="0"
								class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
									class="fill-current mr-2">
									<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
								</svg>
								<span class="class mt-1">Download</span></a></p>
					</div>
				</div>
				<div v-if="dateDifference < 0" class="flex flex-col">
					<label class="detail-supplier-label flex items-center font-bold">Commercial Documents</label>
					<div>
						<!---->
						<span class="break-words"> {{filename(item.commercial_documents)}}
							<p v-show="item.commercial_documents==null">—</p>
						</span>
						<!---->
						<p v-show="item.commercial_documents!=null && item.commercial_documents!=''" class="flex items-center text-sm">
							<a :href="`/custom/download?link=${encodeURIComponent(item.commercial_documents)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary inline-flex items-center">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation" class="fill-current mr-2">
									<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
								</svg>
								<span class="class mt-1">Download</span>
							</a>
						</p>
					</div>
				</div>
				<div v-if="dateDifference >=0" class="flex flex-col">
					<label class="detail-supplier-label flex items-center font-bold">Other Commercial Documents</label>
					<div :class="`w-full flex flex-col pl-2 pt-2`">
						<div v-if="!item.others_commercial_documents">
							—
						</div>
						<div v-for="document_others_commercial_documents in item.others_commercial_documents" class="w-full flex flex-row items-center pb-2">
							<div class="mr-2">
								<div v-show="document_others_commercial_documents.path" class="flex items-center text-sm">
									<a :href="`/custom/download?link=${encodeURIComponent(document_others_commercial_documents.path)}`" tabindex="0" class="cursor-pointer dim btn btn-link text-primary flex items-center">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
										class="fill-current mr-2">
											<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z">
											</path>
										</svg>
										<span class="class flex items-center">Download</span>
									</a>
								</div>
							</div>
							<div v-tooltip="`${document_others_commercial_documents.name}`" class="mr-2 supplier-document-path-detail">
								{{
									document_others_commercial_documents.name
								}}
							</div>
							<div class="flex items-center">
								Added by {{ ( document_others_commercial_documents.is_added_by_customer === 0) ? 'Admin' : 'Customer' }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
import _ from 'lodash';
// import axios from 'axios';
import jQuery from 'jquery'
import AOS from 'aos'
import 'aos/dist/aos.css'
import moment from 'moment'

export default {
	props: ['resource', 'resourceName', 'resourceId', 'field'],
	data: function() {
		return {
			isOpenPurchaseOrdersProductsModal: false,
			purchaseOrder: {},
			multiPurchaseOrders: [],
			suppliersOptions: [],
			suppliersDisplay: [],
			suppliers: [],
			containers: (typeof this.field.shipment.containers_group!=='undefined') ? this.field.shipment.containers_group : null,
			loading: true,
			incoterms: [],
			purchaseOrders: [],
			loadContent: 0
		};
	},
	computed: {
		token() {
            return jQuery('meta[name="csrf-token"]').attr('content')
        },
        dateDifference() {
        	return parseInt(moment(this.field.shipment.created_at).diff(moment('2022-07-31'), 'days').toString())
      	},
		label() {
			// return this.fieldName || this.field.name
			return 'HBL Section'
		}
	},
	created() {
		AOS.init()
        this.suppliers = (typeof this.field.shipment.suppliers_group !== 'undefined') ? JSON.parse(this.field.shipment.suppliers_group) : [];
        if (this.field.shipment.was_created_in_portal === 1) {
            let dates = [];
            this.suppliers.map(item => {
                item.cargo_ready_date != '' && item.cargo_ready_date != null ? dates.push(item.cargo_ready_date) : '';
            })
            let totalVolume = 0;
            let totalWeight = 0;
            let totalCartons = 0;
            const poNums = [];
            this.suppliers.map((value, index, self) => {
                totalVolume += parseFloat(value.cbm)
                totalWeight += parseInt(value.kg)
                totalCartons += parseInt(value.total_cartons)
                value.po_num != '' && value.po_num !== null  ? poNums.push(value.po_num) : '';
                value.supplier_id = value.supplier_id !== null ? parseInt(value.supplier_id) : null;
                value.buyer_id = value.buyer_id !== null ? parseInt(value.buyer_id) : null;
            });
            let maxDate = ''
            if (dates && dates.length > 0) {
                maxDate = dates.reduce((acc, date) => {
                    return acc && new Date(acc) > new Date(date) ? acc : date
                }, '');
                this.suppliers = this.suppliers.filter(value => maxDate == value.cargo_ready_date);
            } else {
                this.suppliers = this.suppliers.filter((value, index, self) =>
                    index === self.findIndex((t) => (
                        (value.supplier_id != null && t.supplier_id === value.supplier_id) || (value.buyer_id != null && t.buyer_id === value.buyer_id)
                    ))
                );
            }

            if(this.field.shipment.add_manual_po_so_data !== '[]' && this.field.shipment.add_manual_po_so_data != null && this.field.shipment.add_manual_po_so_data.length > 0){
                this.suppliers = this.suppliers.map(({po_num, cbm, kg, total_cartons, cargo_ready_date, ...rest}) => {
                    return {
                        'po_num': poNums.length > 0 ? poNums.join(', ') : '',
                        'cbm': totalVolume,
                        'kg': totalWeight,
                        'total_cartons': totalCartons,
                        'cargo_ready_date': maxDate,
                        ...rest
                    }
                })
            }
        }
	},
	mounted() {
		Nova.$on('update-documents-customer', value => {
			console.log('update doc')
          this.getSuppliers()
        })

        //get all suppliers
        this.getSuppliers()
    },
    methods: {
		getSuppliers() {
			var formData = new FormData()
	        let supplierIds = []
            let buyerIds = []
	        let incoTermIds = []
	        let purchaseOrderIds = []
	        let containerIds = []

        if (this.suppliers.length > 0) {
        	for (var x =0; x< this.suppliers.length; x++) {
                this.suppliers[x].buyer_id !== null ? buyerIds.push(this.suppliers[x].buyer_id) : buyerIds.push(0);
                this.suppliers[x].supplier_id !== null ? supplierIds.push(this.suppliers[x].supplier_id) : supplierIds.push(0);
                incoTermIds.push(this.suppliers[x].incoterm_id)
        		purchaseOrderIds.push(this.suppliers[x].po_id)
        		if (typeof this.suppliers[x].containers!=='undefined' && this.suppliers[x].containers!=='') {

	        			let tempContainers = []
	        			tempContainers = this.suppliers[x].containers

	        			if (tempContainers.length > 0) {
	    					tempContainers.map( tc => {
	    						containerIds.push(tc.container_id)
	    					})
	    				}

	        		}
	        	}
	        }

        formData.append('ids', JSON.stringify(buyerIds))
        formData.append('_token', this.token)

        fetch(`${this.field.baseUrl}/custom/buyers/where-in`,{
            token: this.token,
            method: 'POST',
            body: formData
        })
            .then(this.handleResponse)
            .then( response => {

                var { results } = response

                if (typeof results!=='undefined') {
                    let fetchBuyers = results

                    if ( this.suppliers.length > 0 && fetchBuyers.length > 0) {
                        for (var y=0;y<this.suppliers.length;y++) {
                            if(this.suppliers[y].buyer_id !== null && !this.suppliers[y].company_name) {
                                let buyer_details = _.find(results, {
                                    id: this.suppliers[y].buyer_id
                                });

                                this.suppliers[y].company_name = !!buyer_details ? buyer_details.company_name : '';
                            }
                        }
                        //this.suppliersDisplay = this.suppliers
                    }
                }
            });

        formData.append('ids', JSON.stringify(supplierIds))
        formData.append('_token', this.token)
		formData.append('shipment_id', this.resourceId)
        fetch(`${this.field.baseUrl}/custom/suppliers/where-in-custom`,{
            token: this.token,
            method: 'POST',
            body: formData
        })
        .then(this.handleResponse)
        .then( response => {

            let { results } = response

	            if (typeof results!=='undefined') {
	                let fetchSuppliers = results
	                if ( this.suppliers.length > 0 && fetchSuppliers.length > 0) {
	                	for (var y=0;y<this.suppliers.length;y++) {
                            if(this.suppliers[y].supplier_id !== null && !this.suppliers[y].company_name) {

                                let supplier_documents = []
                                results.map(r => {
                                    if ( r.id === parseInt(this.suppliers[y].supplier_id)) {
                                        supplier_documents.new_documents = r.new_documents
                                        this.suppliers[y].company_name = r.company_name
                                    }
                                })

								supplier_documents = typeof supplier_documents!=='undefined' ? supplier_documents.new_documents : []

								//this.suppliers[y].packing_list = null
								this.suppliers[y].commercial_invoice_customer = []
								this.suppliers[y].invoice_packing_list = []
								this.suppliers[y].others_commercial_documents = []
								this.suppliers[y].packing_list_customer = []
								this.suppliers[y].hbl_customer =  []


								if ( supplier_documents.length > 0 ) {

									supplier_documents.map( ({ type, shipment_id }, key) => {
										if ( type === 'Commercial Invoice' ) {
											this.suppliers[y].commercial_invoice_customer.push(supplier_documents[key])
										} else if ( type === 'Other Commercial Docs') {
											this.suppliers[y].others_commercial_documents.push(supplier_documents[key])
										} else if ( type === 'Hbl' ) {
											this.suppliers[y].hbl_customer.push(supplier_documents[key])
										} else if ( type === 'Invoice And Packing List' ) {
											this.suppliers[y].invoice_packing_list.push(supplier_documents[key])
										} else if ( type === 'Packing List' ) {
											this.suppliers[y].packing_list_customer.push(supplier_documents[key])
										}
									})

									//commercial invoice
									/*
									let temp_commercial_invoice = _.find(supplier_documents,{
										shipment_id: parseInt(this.resourceId),
										type: 'Commercial Invoice'
									})

									console.log('temp commercial invoice', temp_commercial_invoice)



									//invoice and packing list
									let temp_invoice_packing_list = _.find(supplier_documents,{
										shipment_id: parseInt(this.resourceId),
										type: 'Invoice And Packing List'
									})

									//others commercial documents
									let temp_others_commercial_documents = _.find(supplier_documents,{
										shipment_id: parseInt(this.resourceId),
										type: 'Other Commercial Docs'
									})

									//packing list
									let temp_packing_list = _.find(supplier_documents,{
										shipment_id: parseInt(this.resourceId),
										type: 'Packing List'
									})

									//hbl customer
									let temp_hbl_customer = _.find(supplier_documents,{
										shipment_id: parseInt(this.resourceId),
										type: 'Hbl'
									})*/

									//this.suppliers[y].invoice_packing_list = ( typeof temp_invoice_packing_list!=='undefined' ) ? temp_invoice_packing_list.path : []

									/*
									this.suppliers[y].packing_list = ( typeof temp_packing_list!=='undefined' ) ? temp_packing_list.path : null
									*/

									//this.suppliers[y].commercial_invoice_customer = ( typeof temp_commercial_invoice!=='undefined' ) ? temp_commercial_invoice.path : []

									//this.suppliers[y].hbl_customer = ( typeof temp_hbl_customer!=='undefined' ) ? temp_hbl_customer.path : []

									//this.suppliers[y].others_commercial_documents = ( typeof temp_others_commercial_documents!=='undefined') ? temp_others_commercial_documents : []


								}
                            }

	                	}

	                	this.suppliersDisplay = this.suppliers
                    }
	            }


	            formData = new FormData()
		        formData.append('_token', this.token)
		        formData.append('ids', JSON.stringify(incoTermIds))

		        fetch(`${this.field.baseUrl}/custom/incoterms/where-in`,{
		            token: this.token,
		            method: 'POST',
		            body: formData
		        })
		        .then(this.handleResponse)
		        .then( response => {

		            var { results } = response

		            if (typeof results!=='undefined') {

		                let fetchIncoTerms = results
		                if ( this.suppliers.length > 0 && fetchIncoTerms.length > 0) {
		                	for (var y=0;y<this.suppliers.length;y++) {
			                	this.suppliers[y].incoterm_name = (typeof this.suppliers[y].incoterm_id!=='undefined' && this.suppliers[y].incoterm_id!=='') ? _.find(fetchIncoTerms, {
			                		id: this.suppliers[y].incoterm_id
			                	}).name : ''

		                	}
		                	//this.suppliersDisplay = this.suppliers
		                }
		            }


		            formData = new FormData()
			        formData.append('_token', this.token)
			        formData.append('ids', JSON.stringify(containerIds))

			        fetch(`${this.field.baseUrl}/custom/containers/where-in`,{
			            token: this.token,
			            method: 'POST',
			            body: formData
			        })
			        .then(this.handleResponse)
			        .then( async response => {

			            let { results } = response

			            if (typeof results!=='undefined') {

			                let fetchContainers = results

			                if ( this.suppliers.length > 0) {
			                	for (var y=0;y<this.suppliers.length;y++) {

				                	if (typeof this.suppliers[y].containers!=='undefined' && this.suppliers[y].containers!=='') {

					        			let tempContainers = []
					        			tempContainers = this.suppliers[y].containers

				        				if (tempContainers.length > 0 && fetchContainers.length > 0) {

				        					tempContainers.map( (tc, key) => {
				        						tempContainers[key].container_num = _.find(fetchContainers, {
				        							unique_id: parseInt(tc.container_id)
				        						}).container_num
				        					})

				        					this.suppliers[y].containers = tempContainers

				        				}

			        				}
			                	}
			                }
			            }

			            formData = new FormData()
				        formData.append('_token', this.token)
				        formData.append('ids', JSON.stringify(purchaseOrderIds))

                        await fetch(`${this.field.baseUrl}/custom/purchase-orders/where-in`,{
				            token: this.token,
				            method: 'POST',
				            body: formData
				        })
				        .then(this.handleResponse)
				        .then( response => {

				            let { results } = response

				            if (typeof results!=='undefined') {

				                let fetchPurchaseOrders = results

				                if (this.suppliers.length > 0 && fetchPurchaseOrders.length > 0) {
				                	this.suppliers.map ((supplier, key) => {
				                		this.suppliers[key].po_name = _.find(fetchPurchaseOrders, {
				                			id: supplier.po_id
				                		})
				                	}).po_num
				                }

					        }

					        this.loading = false
					        this.suppliersDisplay = this.suppliers
							if(this.field.shipment && this.field.shipment.id){

								try {
                                    this.fetchShipmentPurchaseOrders(this.field.shipment, this.suppliersDisplay)
								} catch(e) {
                                    this.loading = false
									this.suppliersDisplay = this.suppliers
                                }

							}
				        }).catch(e => {
                            this.loading = false
					        this.suppliersDisplay = this.suppliers
                            })
                        localStorage.setItem(`shipment_suppliers${this.resourceId}`, JSON.stringify(this.suppliersDisplay));
				    })
		        })
	        })
        },
		fetchShipmentPurchaseOrders: async function(shipment, suppliers){
            const tempSelectedPurchaseOrders = []
			let formGroupCount = 0;
			const tempFormGroups = [...suppliers]

			for(const supplier of suppliers) {
				const { data } = await Nova.request().get(`/custom/po/shipments/${shipment.id}/purchase-orders`, {
					params: {
						supplierId: supplier.supplier_id
					}
				})

				const tempSelectedPurchaseOrders = []
				for(const purchaseOrder of data){
					let tempData = purchaseOrder.purchase_order_products.map(temp => {
						const tempShipmentDistribution = temp.shipment_distribution.find(ship => ship.shipment_id === shipment.id)

						if(tempShipmentDistribution){
							return {
								...temp,
								is_shipment: tempShipmentDistribution.is_shipment ? true : false,
								ship_cartons: tempShipmentDistribution.ship_cartons,
								name: temp.product.name
							}
						}
						return {
							...temp,
							is_shipment: false,
							ship_cartons: 0,
							name: temp.product.name
						}
					})

					// tempData = tempData.filter(data => data.unship_cartons !== 0 && data.ship_cartons !== 0)
					purchaseOrder.isFetched = true
					tempSelectedPurchaseOrders.push({
						...purchaseOrder,
						products: tempData
					})
				}

				tempFormGroups[formGroupCount].purchaseOrders = tempSelectedPurchaseOrders
				formGroupCount++

			}

			this.suppliersDisplay = tempFormGroups
        },
		onViewPurchaseOrderProducts: function(po,key){
			// const tempFormGroups = [...this.suppliersDisplay]
			// tempFormGroups[key].isOpenPurchaseOrdersProductsModal = true
			// tempFormGroups[key].purchaseOrder = po
			this.isOpenPurchaseOrdersProductsModal = true
			this.purchaseOrder = po
			console.log("What is po", po)
			// this.suppliersDisplay = tempFormGroups
		},
		closePOProductsTableModal: function(){
			// const tempFormGroups = [...this.suppliersDisplay]
			// tempFormGroups[key].isOpenPurchaseOrdersProductsModal = false
			// this.suppliersDisplay = tempFormGroups
			this.isOpenPurchaseOrdersProductsModal = false
		},
		handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
        pathSplit( path ) {
        	let get_paths = path.split('/')
        	return (get_paths.length > 0 ) ? get_paths[get_paths.length - 1] : path
        },
		filename(file) {
			// console.log(this.path)
			// return file
			// console.log(file)
			// const path = require('path')
			// return path
			// 	.parse(file)
			// 	.base
			return (file == null || {}) ? '' : file.split('\\')
				.pop()
				.split('/')
				.pop();

		}
	}

}
</script>


<style scoped>
.customer-section {
	border-top: 1px solid rgb(238, 241, 244);
    border-bottom: 1px solid rgb(238, 241, 244);
    padding-bottom: 4px;
    padding-top: 4px;
}
</style>
