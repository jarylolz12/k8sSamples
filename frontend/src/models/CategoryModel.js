import axios from "axios"
class CategoryModel {
	
	base_url = ''
	constructor(url) {
		this.base_url = url
	}
	getCategories(page) {

		let passedData = {
			url: `${this.base_url}/categories`,
			method: 'GET',
			params: {
				page
			}
		}

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(response => {
					if (typeof response.data!=='undefined') {
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
	findById(id) {
		let passedData = {
			url: `${this.base_url}/products/${id}`,
			method: 'GET',
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
	getCategoriesDropdown(page) {

		let passedData = {
			url: `${this.base_url}/categories`,
			method: 'GET',
			params: {
				page,
				per_page: 500
			}
		}

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(passedData).then(response => {
					if (typeof response.data!=='undefined') {
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
export default CategoryModel