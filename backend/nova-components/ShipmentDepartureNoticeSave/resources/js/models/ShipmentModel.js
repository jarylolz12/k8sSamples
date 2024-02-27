import axios from "axios"
import jQuery from 'jquery'
class ShipmentModel {

	constructor(url) {
		this.base_url = url
	}
	csrfToken() {
		return jQuery('meta[name="csrf-token"]').attr("content")
	}
	async fetchSuppliers({
		get_suppliers_group,
		shipment_id
	}) {
		let supplierIds = []
        let formData = new FormData()
		shipment_id = typeof shipment_id ==='undefined' ? 0 : shipment_id
		shipment_id = shipment_id == null ? 0 : shipment_id
        if ( get_suppliers_group.length > 0 ) {
            get_suppliers_group.map( gsg => {
                supplierIds.push(parseInt(gsg.supplier_id))
            })
			formData.append('shipment_id', shipment_id)
            formData.append('ids', JSON.stringify(supplierIds))
            formData.append('_token', this.csrfToken())

			shipment_id = typeof shipment_id ==='undefined' ? 0 : shipment_id
			shipment_id = shipment_id == null ? 0 : shipment_id
			formData.append('shipment_id', shipment_id)

            let passedData = {
	            url: `${this.base_url}/custom/suppliers/where-in-custom`,
	            method: 'POST',
	            data: formData
	        }
	        return new Promise((resolve, reject) => {
	            function proceed() {
	                axios(passedData).then(response => {
	                    resolve(response)
	                }).catch(err => {
	                    reject(err)
	                })
	            }
	            proceed()
	        })
        }
	}
}
export default ShipmentModel