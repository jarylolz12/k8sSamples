import axios from "axios"
class WarehouseCustomerModel {
	
	base_url = ''
	constructor(url) {
		this.base_url = url
	}

	getWarehouseCustomers(payload) {
		let passedData = {
			url: `${this.base_url}/customer/${payload.id}/warehouse-customers`,
			method: 'GET',
			params: {
				page: payload.page,
                per_page: 35
			}
		}

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(response => {
					if (response.status===200 && typeof response.data!=='undefined') {
						response.data.data.map((r, key) => {
							var { id, company_name, emails, phone, address, product_id, products, warehouse_id, is_active, customer_id, company_key, warehouses, warehouse_customer_company_id } = r
							response.data.data[key] = {
								name: company_name,
								id,
								emails: typeof emails === 'string' ? JSON.parse(emails) : [], 
								phone: phone !== null ? phone : '',
								address: address !== null ? address : '',
                                product_id,
                                products,
                                warehouse_id,
								is_active,
								customer_id,
								company_key,
								warehouses,
								warehouse_customer_company_id
							}
						})
                        
						resolve(response)
					} else {
						reject(response)
					}
				}).catch(err => {
					reject(err)
				})
			}
			proceed()
		})
	}
	searchWarehouseCustomers(passedData) {
		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(response => {
					if (response.status===200 && typeof response.data!=='undefined') {
						response.data.data.map((r, key) => {
							var { id, company_name, emails, phone, address, product_id, products, warehouse_id, is_active, customer_id, company_key, warehouses, warehouse_customer_company_id } = r
							response.data.data[key] = {
								name: company_name,
								id,
								emails: typeof emails === 'string' ? JSON.parse(emails) : [], 
								phone: phone !== null ? phone : '',
								address: address !== null ? address : '',
                                product_id,
                                products,
                                warehouse_id,
								is_active,
								customer_id,
								company_key,
								warehouses,
								warehouse_customer_company_id
							}
						})
						resolve(response)
					} else {
						reject(response)
					}
				}).catch(err => {
					reject(err)
				})
			}
			proceed()
		})
	}
	getWarehouseCustomersDropdown(payload) {
		let passedData = {
			url: `${this.base_url}/customer/${payload.id}/warehouse-customers`,
			method: 'GET',
			params: {
				page: payload.page,
			},
			cancelToken: payload.cancelToken
		}

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(response => {
					if (response.status===200 && typeof response.data!=='undefined') {
						response.data.data.map((r, key) => {
							var { id, company_name, emails, phone, address, product_id, products, warehouse_id, company_key, warehouses, warehouse_customer_company_id } = r
							response.data.data[key] = {
								name: company_name,
								id,
								emails: typeof emails === 'string' ? JSON.parse(emails) : [], 
								phone,
								address,
                                product_id,
                                products,
                                warehouse_id,
								company_key,
								warehouses,
								warehouse_customer_company_id
							}
						})
                        
						resolve(response)
					} else {
						reject(response)
					}
				}).catch(err => {
					reject(err)
				})
			}
			proceed()
		})
	}
}

export default WarehouseCustomerModel