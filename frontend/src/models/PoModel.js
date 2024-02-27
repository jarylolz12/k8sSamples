import axios from "axios"
class PoModel {
	
	base_url = ''
	constructor(url) {
		this.base_url = url
	}
	fetchPurchaseOrderProducts(purchase_order_id) {
		
		return new Promise((resolve, reject) => {
			axios({
				url: `${this.base_url}/po/purchase-orders/${purchase_order_id}/products`,
				method: 'GET'
			}).then(res => {
				resolve(res)
			}).catch(e => {
				reject(e)
			})
		})
	}
	fetchPurchaseOrderOptions({ qry, type, entity_id, other_id }) {

		qry = typeof qry=='undefined' ? '' : qry
        let finalUrl = ''
        //if type is being passed
        if ( typeof type!=='undefined') {
            finalUrl = `${this.base_url}/po/purchase-orders-options?type=${type}&entity_id=${entity_id}`

            if ( typeof other_id!=='undefined' ) {
                finalUrl +=`&other_id=${other_id}`
            }

        } else {
            finalUrl = `${this.base_url}/po/purchase-orders-options?qry=${qry}`
        }

		let passedData = {
			url: finalUrl,
			method: 'GET'
		}
		return new Promise((resolve, reject) => {
			axios(passedData).then( res => {
				resolve(res)
			}).catch(e => {
				reject(e)
			})
		})

	}
	fetchPosByShipmentId(shipment_id) {

		let passedData = {
			url: `${this.base_url}/po/purchase-orders/purchase-orders-shipments/${shipment_id}`,
			method: 'GET',
		}

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(res => {
					if (typeof res!=='undefined' && res.status === 200) {
						resolve(res)
					} else
						reject('not ok')
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed()	
		})
	}
	findById(id) {

		let passedData = {
			url: `${this.base_url}/orders/${id}`,
			method: 'GET',
		}

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(res => {
					
					if (typeof res!=='undefined' && res.status === 200) {
						resolve(res)
					} else {
						reject(
						{
							'message': 'The token has been expired, Please reload the page',
							'error': 'UnAuthenticated',
							'status': 401
						}
						)
					}
				})
				.catch( err => {
					console.log('rejected', err)
					reject(err)
				})	
			}
			proceed()
			
		})

		
	}
	fetchShipmentPoDetails(params) {
		let {
			po_id,
			po_number,
			customer_id,
			supplier_id
		} = params

		return new Promise((resolve, reject) => {
			axios.get(`${this.base_url}/shipment-po-details/${po_id}?supplier_id=${supplier_id}&customer_id=${customer_id}&po_number=${po_number}`)
			.then(res => {
				if (res.status === 200) {
					if (res.data) {
						resolve(res)
					}
				}
			}).catch(err => {
				reject(err)
			})
		})
		
	}

	search(otherProps) {
		let passedData = {
			url: `${this.base_url}/v3/orders/search`,
			method: 'GET',
			...otherProps
		}

		return new Promise((resolve, reject) => {
			axios(passedData).then(response => {
				if (typeof response.data!=='undefined') {
					if (typeof response.data.data!=='undefined') {
						if ( response.data.data.length > 0) {
							response.data.data.map( (val, k) => {
								response.data.data[k].status = (val.status==='partial_shipped') ? 'partially shipped' : val.status
							})
						}	
					}
					resolve(response.data.data)
				}
			})
			.catch( err => {
				reject(err)
			})
		})
		
	}
}
export default PoModel