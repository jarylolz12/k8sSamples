import axios from "axios"
class SupplierModel {

    base_url = ''
    constructor(url) {
        this.base_url = url
    }

    getSuppliers(payload) {
        let passedData = {
            // url: `${this.base_url}/suppliers`,
            url: `${this.base_url}/v2/suppliers`,
            method: 'GET',
            params: {
                page: payload.pageNumber
            }
        }

        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData).then(response => {
                    if (response.status === 200 && typeof response.data !== 'undefined') {
                        response.data.data.map((r, key) => {
                            var { id, company_name, emails, phone, address, company_key } = r
                            response.data.data[key] = {
                                name: company_name,
                                id,
                                emails,
                                phone,
                                address,
                                company_key
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

    searchSuppliers(passedData) {
        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData).then(response => {
                    if (response.status === 200 && typeof response.data !== 'undefined') {
                        response.data.data.map((r, key) => {
                            var { id, company_name, emails, phone, address, company_key } = r
                            response.data.data[key] = {
                                name: company_name,
                                id,
                                emails,
                                phone,
                                address,
                                company_key
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

export default SupplierModel