import axios from "axios"
class ShipmentModel {
	
	base_url = ''
	constructor(url) {
		this.base_url = url
	}
    fetchBuyerSupplier(params) {
        let {
            id,
            role
        } = params
        
        return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.get(`${base_url}/v2/buyer-seller-details?id=${id}&role=${role}`).then(response => {
					resolve(response)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed(this.base_url)
		})
    }

    checkMblNumber(mbl_num) {
        return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.get(`${base_url}/terminal49/shipment/${mbl_num}`).then(response => {
					resolve(response)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed(this.base_url)
		})
    }
    fetchShipmentDetails(id) {
        return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.get(`${base_url}/shipment/${id}?include_orders=1`).then(response => {
					resolve(response.data.data)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed(this.base_url)
		})
    }
	fetchShipmentCustomerDocuments(id) {
		return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.get(`${base_url}/shipment-customer-documents?shipment_id=${id}&page=1&order=desc&order_by=id`).then(response => {
					resolve(response.data.data)
				})
					.catch( err => {
						reject(err)
					})
			}
			proceed(this.base_url)
		})
	}
	deleteShipmentCustomerDocuments(id) {
		return new Promise((resolve, reject) => {
			function proceed(base_url) {
				let fd = new FormData()
				fd.append('ids[0]', id)
				let passedData = {
					url: `${base_url}/v2/shipment/delete-multiple-documents`,
					method: 'POST',
					data: fd
				}
				axios(passedData).then(response => {
					resolve(response.data.data)
				})
					.catch( err => {
						reject(err)
					})
			}
			proceed(this.base_url)
		})
	}

	markBookExternal(payload) {
		return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.post(`${base_url}/v2/shipment/mark-book-external`,payload ).then(response => {
					resolve(response)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed(this.base_url)
		})
	}
	editShipment(payload) {
		/*
		let fd = new FormData()
		let keys = Object.keys(payload)
		for ( var x =0; x< keys.length; x++ ) {
			fd.append(keys[x], payload[keys[x]])
		}

		let passedData = {
			url: `${this.base_url}/v2/shipment/edit`,
			method: 'POST',
			data: fd
		}*/

		return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.post(`${base_url}/v2/shipment/edit`,payload ).then(response => {
					resolve(response)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed(this.base_url)
		})
		/*

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(response => {
					resolve(response)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed()
		})*/
	}
    createBookingShipment(payload) {


        return new Promise((resolve, reject) => {

            //let's split the payload
            let fd = new FormData()

            //get object keys
            let getKeys = Object.keys(payload)

            //build now the form data
            getKeys.map(gk => {
                
                if ( gk === 'purchase_orders' ) {
                    let get_purchase_orders = payload[gk]
                    get_purchase_orders.map((gpo,k) => {
                        let getPoKeys = Object.keys(gpo)
                        getPoKeys.map(gpok => {
                            if ( gpok === 'documents' ) {
                                let get_documents = gpo[gpok]
                                get_documents.map((gd,kk) => {
                                    let getDoKeys = Object.keys(gd)
                                    getDoKeys.map(gdk => {
                                        fd.append(`purchase_orders[${k}][documents][${kk}][${gdk}]`,gd[gdk])
                                    })
                                })
                            } else if ( gpok === 'products' ) {
                                let get_products = gpo[gpok]
                                get_products.map((gd,kk) => {
                                    let getPoKeys = Object.keys(gd)
                                    getPoKeys.map(gdk => {
                                        fd.append(`purchase_orders[${k}][products][${kk}][${gdk}]`,gd[gdk])
                                    })
                                })
                            } else {
                                fd.append(`purchase_orders[${k}][${gpok}]`,gpo[gpok])    
                            }
                            
                        })
                        
                    })
                } else if (gk === 'containers') {
                    let get_containers = payload[gk]
                    get_containers.map((gd,kk) => {
                        let getContainerKeys = Object.keys(gd)
                        getContainerKeys.map(gdk => {
                            fd.append(`containers[${kk}][${gdk}]`,gd[gdk])
                        })
                    })
                } else {
                    fd.append(gk, payload[gk])
                }

            })

			let createBookingURL = ''
			if(payload.shipment_id)
				createBookingURL = `${this.base_url}/v2/shipment/update-booking`
			else
				createBookingURL = `${this.base_url}/v2/shipment/create-booking`
            let passedData = {
                url: createBookingURL,
                method: 'POST',
                data: fd
            }

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
	createShipment(payload) {
		/*
		let fd = new FormData()
		let keys = Object.keys(payload)
		for ( var x =0; x< keys.length; x++ ) {
			if ( keys[x] === 'purchase_orders') {
				let ppo = 0

				payload.purchase_orders.map(po => {
					fd.append(`purchase_orders[${ppo}]`)
					ppo++
				})

			}
			fd.append(keys[x], payload[keys[x]])
		}*/
		/*

		let passedData = {
			url: `${this.base_url}/v2/shipment/create`,
			method: 'POST',
			data: payload
		}*/
		

		return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.post(`${base_url}/v2/shipment/create`,payload ).then(response => {
					resolve(response)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed(this.base_url)
		})

	}
	delete(shipment_id) {
		let passedData = {
			url: `${this.base_url}/tracking-shipments/${shipment_id}/delete`,
			method: 'POST'
		}
		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(response => {
					if (typeof response.data!=='undefined') {
						resolve(response)
					} else {
						reject(response)
					}
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed()
		})
	}
	createBulkShipment(payload) {
		return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.post(`${base_url}/v2/shipment/bulk-create`,payload).then(response => {
					resolve(response)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed(this.base_url)
		})

	}
	updateShipmentInfo(payload) {
		return new Promise((resolve, reject) => {
			function proceed(base_url) {
				axios.post(`${base_url}/v2/shipment/update-shipment-info`,payload ).then(response => {
					resolve(response)
				})
				.catch( err => {
					reject(err)
				})	
			}
			proceed(this.base_url)
		})
	}
}
export default ShipmentModel