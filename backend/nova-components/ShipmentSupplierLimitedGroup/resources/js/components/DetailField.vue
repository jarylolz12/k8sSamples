<template>
<div class="">
	<div class="flex border-b border-40">
		<div class="w-1/4 py-4">
			<slot>
				<h4 class="font-normal text-80">{{ label }}</h4>
			</slot>
		</div>
		<div class="w-3/4 py-4 break-words">
			<div style="border-bottom: 0px transparent;" v-show="(suppliers.length==0 || suppliers=='') && loading==false" class="shipment-supplier-group">
				<div>
					No Supplier yet was added to this Shipment.
				</div>
			</div>
			<div style="border-bottom: 0px transparent;" v-show="loading==true">
				Checking Associated Suppliers...
			</div>
			<div v-show="suppliers.length>0" :id="`shipment-supplier-group-jQuery{item.id}`" v-for="item in suppliers" class="shipment-supplier-group">
				<div>
					<label style="font-weight: bold;">Supplier Item</label>
				</div>
				<div>
					<label>Supplier Name</label>
					<span>{{ item.company_name }}</span>
				</div>
				<div>
					<label>PO #</label>
					<span>{{ item.po_name}}</span>
				</div>
				<div>
					<label>PO # (Old Way)</label>
					<span>{{ item.po_num}}</span>
				</div>
				<div>
					<label>Volume</label>
					<span>{{ item.cbm }}</span>
				</div>
				<div>
					<label>Weight</label>
					<span>{{ item.kg }}</span>
				</div>
				<div>
					<label>Total Cartons</label>
					<span>{{ item.total_cartons }}</span>
				</div>
				<div>
					<label>Packing List</label>
					<div>
						<!---->
						<span class="break-words"> {{filename(item.packing_list)}}
							<p v-show="item.packing_list==null">—</p>
						</span>
						<!---->
						<p v-show="item.packing_list!=null && item.packing_list!=''" class="flex items-center text-sm mt-3"><a :href="`/custom/download?link=${encodeURIComponent(item.packing_list)}`" tabindex="0"
								class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
									class="fill-current mr-2">
									<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
								</svg>
								<span class="class mt-1">Download</span></a></p>
					</div>
				</div>
				<div>
					<label>Commercial Documents</label>
					<div>
						<!---->
						<span class="break-words"> {{ filename(item.commercial_documents)}}
							<p v-show="item.commercial_documents==null">—</p>
						</span>
						<!---->
						<p v-show="item.commercial_documents" class="flex items-center text-sm mt-3"><a :href="`/custom/download?link=${encodeURIComponent(item.commercial_documents)}`" tabindex="0"
								class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
									class="fill-current mr-2">
									<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
								</svg>
								<span class="class mt-1">Download</span></a></p>
					</div>
				</div>
				<div>
					<label>Commercial Invoice</label>
					<div>
						<!---->
						<span class="break-words"> {{ filename(item.commercial_invoice) }}
							<p v-show="item.commercial_invoice==null">—</p>
						</span>
						<!---->
						<p v-show="item.commercial_invoice" class="flex items-center text-sm mt-3"><a :href="`/custom/download?link=${encodeURIComponent(item.commercial_invoice)}`" tabindex="0"
								class="cursor-pointer dim btn btn-link text-primary inline-flex items-center"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" aria-labelledby="download" role="presentation"
									class="fill-current mr-2">
									<path d="M11 14.59V3a1 1 0 0 1 2 0v11.59l3.3-3.3a1 1 0 0 1 1.4 1.42l-5 5a1 1 0 0 1-1.4 0l-5-5a1 1 0 0 1 1.4-1.42l3.3 3.3zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
								</svg>
								<span class="class mt-1">Download</span></a></p>
					</div>
				</div>
				<div>
					<label>Product Description</label>
					<span>{{ item.product_description }}</span>
				</div>
				<div>
					<label>Terms</label>
					<span>{{ item.incoterm_name }}</span>
				</div>
			</div>
		</div>
	</div>
</div>
</template>
<script>
import _ from 'lodash';
import jQuery from 'jquery';

// import axios from 'axios';

export default {
	props: ['resource', 'resourceName', 'resourceId', 'field'],
	data: function() {
		return {
			suppliers: [],
			loading: false,
			incoterms: [],
			purchaseOrders: []
		};
	},
	computed: {
		label() {
			//return this.fieldName || this.field.name
			return 'Suppliers'
		}
	},
	mounted() {
		var counter = 0;
		var token = jQuery('meta[name="csrf-token"]')
			.attr('content');

		if (this.field.value != '') {

			var suppliers = null
			if (typeof this.field.shipment.suppliers_group_bookings!=='undefined' ) {
                suppliers = JSON.parse(this.field.shipment.suppliers_group_bookings);
            } else {
                suppliers = JSON.parse(this.field.value);
            }

			if (suppliers.length > 0) {
				var limit = suppliers.length;
				var counter = 0;
				let supplier_company_names = [];
				let incoterms = [];
				let purchaseOrders = [];
				let get_suppliers = [];
				jQuery.ajax({
						method: 'GET',
						url: '/custom/incoterms'
					})
					.done(rr => {
						counter++;
						var {
							results
						} = rr;
						if (typeof results != 'undefined') {
							incoterms = results;
							if (incoterms.length > 0) {
								for (var x = 0; x < suppliers.length; x++) {
									var ic = _.find(incoterms, {
										id: suppliers[x].incoterm_id
									});
									if (typeof ic != 'undefined') {
										suppliers[x].incoterm_name = ic.name;
									}
								}
							}
						}

					});

				jQuery.ajax({
						method: 'GET',
						url: '/custom/purchase-orders'
					})
					.done(res => {
						counter++;
						var {
							results
						} = res;
						// console.log('results=' + JSON.stringify(results));
						// console.log('suppliers=' + JSON.stringify(suppliers));
						if (typeof results != 'undefined') {
							purchaseOrders = results;
							if (purchaseOrders.length > 0) {
								for (var x = 0; x < suppliers.length; x++) {
									var po = _.find(purchaseOrders, {
										id: suppliers[x].po_id
									});
									if (typeof po != 'undefined') {
										suppliers[x].po_name = po.po_num;
									}
								}
							}
						}

					});

				var t = setInterval(() => {
					if (counter == 2) {
						jQuery.ajax({
								method: 'GET',
								url: '/custom/suppliers'
							})
							.done(rr => {
								var {
									results
								} = rr;
								get_suppliers = results;
								if (typeof results != 'undefined') {
									get_suppliers = results;

									if (get_suppliers.length > 0) {
										for (var x = 0; x < suppliers.length; x++) {
											var gs = _.find(get_suppliers, {
												id: suppliers[x].supplier_id
											});
											if (typeof gs != 'undefined') {

												suppliers[x].company_name = gs.company_name;
											}
										}

									}


								}
								this.suppliers = suppliers;
							});
						clearInterval(t);
					}
				}, 100);

			} else {
				counter = suppliers.length;
			}

		} else {
			this.loading = false;
		}

	},
	methods: {
		filename(file) {
			// console.log(this.path)
			// return file
			// console.log(file)
			// const path = require('path')
			// return path
			// 	.parse(file)
			// 	.base
			return (file == null) ? '' : file.split('\\')
				.pop()
				.split('/')
				.pop();

		}
	}

}
</script>
