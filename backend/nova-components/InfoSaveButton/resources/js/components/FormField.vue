<!--
<template>
<default-field
  :field="field"
  :errors="errors"
>
	<template slot="field">
		<input
		  :id="field.name"
		  type="text"
		  class="w-full form-control form-input form-input-bordered"
		  :class="errorClasses"
		  :placeholder="field.name"
		  v-model="value"
		/>
	</template>
</default-field>
</template>
-->
<template>
<div
  v-show="resourceId != '' && resourceId != null"
  class="flex border-b border-40"
>
	<div class="w-1/5 px-8 py-6"></div>
	<div class="py-6 px-8 w-1/2">
		<button
		  v-on:click="saveTab"
		  class="btn-default"
		  style="
        border: blue 2px;
        background-color: var(--primary);
        color: var(--white);
        border: 0 solid var(--black);
        line-height: 2.25rem;
        text-shadow: 0 1px 2px
        rgba(0,0,0,.2);
        box-sizing: inherit;"
		  :disabled=isDisabled
		>
			<template v-if="isLoading">
				<span class=""> Loading... </span>
			</template>
			<template v-else>
				<span class=""> Save </span>
			</template>
		</button>
		<!-- <button v-show="!loading" @click="e => saveTab(e)" class="btn btn-default btn-primary inline-flex items-center relative" dusk="create-button">
                <span class="">
                    Save
                </span>
            </button> -->
	</div>
</div>
</template>

<script>
import {
	mapGetters,
	mapState
}
from "vuex";
import {
	FormField,
	HandlesValidationErrors
}
from "laravel-nova";
import _ from "lodash";
import jQuery from 'jquery'
export default {
	mixins: [FormField, HandlesValidationErrors],
	props: ["resourceName", "resourceId", "field"],
	data() {
		return {
			name: "headerInfo",
			isLoaded: false,
			isLoading: true,
			resourceUrlName: null,
		};
	},
	created: function() {
		// `this` points to the vm instance
		// this.$store.registerModule()
		let arrPath = window.location.pathname.split("/");
		console.log(arrPath);
		this.resourceUrlName = arrPath[3];
		if((arrPath[3] == "shipments" || arrPath[3] == 'tracking-shipments') && arrPath[5] == "edit") {
			const btnHide = document.querySelector('[dusk="update-and-continue-editing-button"]');
			const btnHide2 = document.querySelector('[dusk="update-button"]');
			btnHide.style.visibility = "hidden";
			btnHide2.style.visibility = "hidden";
		}
	},
	mounted() {
		Nova.$on("on-customer-shipment-select-loaded", value => {
			this.enableButton(value);
		});
		/* Nova.$on('on-customer-shipment-select-loaded', function (loaded) {
		    if (loaded){
		        console.log(this.isLoaded)
		        //this.isLoaded = true
		        console.log('loaded')
		        console.log(this.isLoaded)
		        this.enableButton(loaded);
		    }
		}) */
	},
	computed: {
		isDisabled: function() {
			console.log('isDisabled')
			return !this.isLoaded;
		}
	},
	methods: {
		enableButton: function(loaded) {
			if(loaded) {
				console.log(this.isLoaded)
				this.isLoaded = true
				console.log('loaded')
				console.log(this.isLoaded)
				this.isLoading = false
			}
		},
		/* enableButton(loaded) {
		    console.log('enabled')
		    this.isLoaded = true
		}, */
		/* getElementValue(root, elemName){
		    let value = null
		    root.$children.forEach(component => {
		        console.log(component)
		        if (component._props.field !== undefined && component._props.field.$refs.input.dataTestid == elemName) {
		            value = component._props.field.value
		        }
		    })
		    return value
		}, */
		printForm(e) {
			const form = e.target;
			const formData = new FormData(form); // get all named inputs in form
			for(const [inputName, value] of formData) {
				console.log("printForm:" + {
					inputName,
					value
				});
			}
		},
		getElementValueUpdated(root, elemName) {
			//let objForm = {}
			let objForm = new FormData();
			let arrCommon = [
                "shifl_ref",
                "etd",
                "eta",
                "custom_notes",
                "origin_country",
                "shifl_office_origin_from_id",
                "shifl_office_origin_to_id",
                "custom_customer",
				"import_name_id"
            ];
			let arrRelations = ["customer_id"];
			//formData2.append('booking_confirmed', document.getElementById('booking_confirmed').checked === true ? 1 : 0);
			//formData2.append('arrival_notice_confirmed', document.getElementById('arrival_notice_confirmed').checked === true ? 1 : 0);
			//formData2.append('freight_released_processed', document.getElementById('freight_released_processed').checked === true ? 1 : 0);
			//formData2.append('customs_processed', document.getElementById('customs_processed').checked === true ? 1 : 0);
			//formData2.append('DO_confirmed', document.getElementById('DO_confirmed').checked === true ? 1 : 0);
			//formData2.append('freight_confirmed', document.getElementById('freight_confirmed').checked === true ? 1 : 0);
			//formData2.append('customs_released', document.getElementById('customs_released').checked === true ? 1 : 0);
			//formData2.append('pending_refund', document.getElementById('pending_refund').checked === true ? 1 : 0);
			//formData2.append('delivered', document.getElementById('delivered').checked === true ? 1 : 0);
			//formData2.append('billed', document.getElementById('billed').checked === true ? 1 : 0);
			//formData2.append('cancelled', document.getElementById('cancelled').checked === true ? 1 : 0);
			let arrMilestones = [
                "booking_confirmed",
                "rate_confirmed",
                "so_released",
                "arrival_notice_confirmed",
                "freight_released_processed",
                "customs_processed",
                "DO_confirmed",
                "freight_confirmed",
                "customs_released",
                "pending_refund",
                "delivered",
                "billed",
                "cancelled",
                "isf_done",
                "ams_done",
                "is_containers_loaded"
            ];
			root.$children.forEach(component => {
				// For etd, shifl_ref, notes, status, and similar fields fruits.includes("Banana")
				if(component._props.field !== undefined) {
					if(arrCommon.includes(component._props.field.attribute)) {
						// (component._props.value){
						//console.log(component._props.value)
						// console.log(component);
						//objForm[component._props.field.attribute] = component.value;
						objForm.append(component._props.field.attribute, component.value);
					}
					// For customer or similar belongsTo fields
					if(arrRelations.includes(component._props.field.attribute)) {
						// (typeof component._props.selectedResource != "undefined"){
						//console.log(component._props.selectedResource.value)
						//console.log(component)
						//objForm[component._props.field.attribute] = component.selectedResource.value;
						objForm.append(component._props.field.attribute, component.selectedResource.value);
					}
					// For checkboxes like the milestones type of fields
					if(arrMilestones.includes(component._props.field.attribute)) {
						// (component._props.trueValue){
						console.log(component);
						//objForm[component._props.field.attribute] = component.trueValue;
						objForm.append(component._props.field.attribute, component.trueValue);
					}
				}
				/* if (component._props.field !== undefined && component._props.field.attribute == elemName) {
				    value = component._props.field.value
				} */
			});
			return objForm;
		},
		getElementValue(root, elemName) {
			let objForm = {};
			let arrCommon = [
                "shifl_ref",
                "etd",
                "eta",
                "custom_notes",
                "origin_country",
                "shifl_office_origin_from_id",
                "shifl_office_origin_to_id",
                "custom_customer",
				"import_name_id"
            ];
			let arrRelations = ["customer_id"];
			//formData2.append('booking_confirmed', document.getElementById('booking_confirmed').checked === true ? 1 : 0);
			//formData2.append('arrival_notice_confirmed', document.getElementById('arrival_notice_confirmed').checked === true ? 1 : 0);
			//formData2.append('freight_released_processed', document.getElementById('freight_released_processed').checked === true ? 1 : 0);
			//formData2.append('customs_processed', document.getElementById('customs_processed').checked === true ? 1 : 0);
			//formData2.append('DO_confirmed', document.getElementById('DO_confirmed').checked === true ? 1 : 0);
			//formData2.append('freight_confirmed', document.getElementById('freight_confirmed').checked === true ? 1 : 0);
			//formData2.append('customs_released', document.getElementById('customs_released').checked === true ? 1 : 0);
			//formData2.append('pending_refund', document.getElementById('pending_refund').checked === true ? 1 : 0);
			//formData2.append('delivered', document.getElementById('delivered').checked === true ? 1 : 0);
			//formData2.append('billed', document.getElementById('billed').checked === true ? 1 : 0);
			//formData2.append('cancelled', document.getElementById('cancelled').checked === true ? 1 : 0);
			let arrMilestones = [
                "booking_confirmed",
                "rate_confirmed",
                "so_released",
                "arrival_notice_confirmed",
                "freight_released_processed",
                "customs_processed",
                "DO_confirmed",
                "freight_confirmed",
                "customs_released",
                "pending_refund",
                "delivered",
                "billed",
                "cancelled",
                "is_containers_loaded"
            ];
			root.$children.forEach(component => {
				// For etd, shifl_ref, notes, status, and similar fields fruits.includes("Banana")
				if(component._props.field !== undefined) {
					if(arrCommon.includes(component._props.field.attribute)) {
						// (component._props.value){
						//console.log(component._props.value)
						console.log(component);
						objForm[component._props.field.attribute] = component.value;
					}
					// For customer or similar belongsTo fields
					if(arrRelations.includes(component._props.field.attribute)) {
						// (typeof component._props.selectedResource != "undefined"){
						//console.log(component._props.selectedResource.value)
						//console.log(component)
						objForm[component._props.field.attribute] = component.selectedResource.value;
					}
					// For checkboxes like the milestones type of fields
					if(arrMilestones.includes(component._props.field.attribute)) {
						// (component._props.trueValue){
						console.log(component);
						objForm[component._props.field.attribute] = component.trueValue;
					}
				}
				/* if (component._props.field !== undefined && component._props.field.attribute == elemName) {
				    value = component._props.field.value
				} */
			});
			return objForm;
		},
		async saveTab(e) {
			if(e) {
				e.preventDefault();
				//var formData2 = new FormData();
				/* const getFormData = () => {
				    const form = document.getElementsByTagName("form");
				    return new FormData(form);
				} */
				//console.log(this.$store);
				//var oForm = document.forms[0];
				//var formData = new FormData(oForm);
				/* let customer = this.getElementValue(this.$root.Update, 'customers-search-input'); */
				//formData2 = this.getElementValue(this.$parent, 'shifl_ref')
				var formData2 = null;
				formData2 = this.getElementValueUpdated(this.$parent, "shifl_ref");
				//services_section
				if(document.getElementById("services_section")) {
					formData2.append("services_section", document.getElementById("services_section").value);
				}
				//this.printForm(this.$parent);
				/* let object = {};
				formData.forEach((value, key) => {
				    // Reflect.has in favor of: object.hasOwnProperty(key)
				    if(!Reflect.has(object, key)){
				        object[key] = value;
				        return;
				    }
				    if(!Array.isArray(object[key])){
				        object[key] = [object[key]];
				    }
				    object[key].push(value);
				    console.log(key + ':' + value)
				});
				var json = JSON.stringify(object); */
				/*formData2.append(formData); */
				// `event` is the native DOM event
				//console.log(this.$root.update.fields.tabs.fields[4].belongsToId)
				//console.log(document.getElementsByName('etd').placeholder)
				/* for(var pair of formData2.entries()) {
				    console.log(pair[0]+ ', '+ pair[1]);
				} */
				/*  let customerId = document.getElementByName('customer_id').value !== null ? document.getElementByName('customer_id').value : "26";  */
				//formData2.append('shifl_ref', document.getElementById('shifl_ref').value);
				//formData2.append('shipment_status', document.getElementById('shipment_status').value);
				/* formData2.append('etd', document.getElementsByName('etd').placeholder);
				formData2.append('eta', document.getElementsByName('eta').placeholder);
				formData2.append('custom_notes', document.getElementById('custom_notes').value); */
				/* shifl_ref: document.getElementById('shifl_ref').value, */
				if(jQuery('#remove_mbl_copy_surrendered').val() == "0") {
					if(jQuery('#mbl_copy_surrendered_name').val() !== '') {
						formData2.append('mbl_copy_surrendered_file', document.getElementById('mbl_copy_surrendered').files[0])
						formData2.append('mbl_copy_surrendered_name', jQuery('#mbl_copy_surrendered_name').val())
						formData2.append('mbl_copy_surrendered', jQuery('#mbl_copy_surrendered_name').val())
					}
				}
				else if(jQuery('#remove_mbl_copy_surrendered').val() == "1") {
					formData2.append('mbl_copy_surrendered_remove', 1)
				}

                if ( jQuery('#po_num').val()!=='') {
                    let po_num = jQuery.trim(jQuery('#po_num').val())
                    formData2.append('po_num', po_num)
                }
                //make sure to check if the dom is null or not
                let so_received = document.getElementById('so_received')!==null ? document.getElementById('so_received').checked === true ? 1 : 0 : 0

                //formData2.append('so_received', document.getElementById('so_received').checked === true ? 1 : 0);
                formData2.append('so_received', so_received)

                let changeCustomerID = formData2.get("custom_customer");
                const { data }  = await Nova.request().get(`/custom/po/shipments/${this.resourceId}/purchase-orders`);

                if(changeCustomerID != this.field.defaultFields.customer.id && data.length > 0){
                    Nova.request().delete(`/custom/shipment/${this.resourceId}/orders`);
                }

				Nova.request({
					url: "/custom/shipment-header-info/" + this.resourceId,
					method: "post",
					data: formData2
				}).then(response => {
					console.log(response);
					if(response.data.status === "update successful") {
						Nova.success(this.__("Shipment Header Information updated!", {
							resource: this.resourceId
						}));
						//location.reload(true);
						// location.replace('/administrator/resources/shipments/'+this.resourceId);
						this.$router.push(`/resources/${this.resourceUrlName}/${this.resourceId}`);
					}
					else {
						Nova.error(this.__(response.data.status, {
							resource: this.resourceId
						}));
					}
				});
				/* Nova.request().post('/custom/shipment/' + this.resourceId).then(response => {
                    Nova.success(
						this.__('Shipment Header Information updated!', {
							resource: this.resourceId
						})
                    )
                }) */
			}
		}
	}
};
</script>
