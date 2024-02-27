/** @format */

import axios from "axios"

class ProductModel {
    base_url = ""
    constructor(url) {
        this.base_url = url
    }

    getProducts(page) {
        let passedData = {
            url: `${this.base_url}/v2/products`,
            method: "GET",
            params: {
                page,
            },
        }

        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData)
                    .then((response) => {
                        if (typeof response.data !== "undefined") {
                            resolve(response)
                        } else {
                            reject(response)
                        }
                    })
                    .catch((err) => {
                        reject(err)
                    })
            }
            proceed()
        })
    }
    findById(id) {
        let passedData = {
            url: `${this.base_url}/products/${id}`,
            method: "GET",
        }

        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData)
                    .then((response) => {
                        if (typeof response.data !== "undefined") {
                            resolve(response)
                        } else {
                            reject(response)
                        }
                    })
                    .catch((err) => {
                        reject(err)
                    })
            }
            proceed()
        })
    }
    search(otherProps) {
        let passedData = {
            url: `${this.base_url}/v2/products`,
            method: "GET",
            ...otherProps,
        }

        return new Promise((resolve, reject) => {
            axios(passedData)
                .then((response) => {
                    if (typeof response.data !== "undefined") {
                        let data = response.data.results
                        resolve(data)
                    }
                })
                .catch((err) => {
                    reject(err)
                })
        })
    }
    getProductsDropdown(page) {
        let passedData = {
            url: `${this.base_url}/products`,
            method: "GET",
            params: {
                page,
                per_page: 500
            },
        }

        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData)
                    .then((response) => {
                        if (typeof response.data !== "undefined") {
                            resolve(response)
                        } else {
                            reject(response)
                        }
                    })
                    .catch((err) => {
                        reject(err)
                    })
            }
            proceed()
        })
    }
    getProductsDropdown3PLProvider(payload) {
        let passedData = {
            url: `${this.base_url}/customer/${payload.customer_id}/warehouse-customer/${payload.warehouse_customer_id}/products`,
            method: "GET",
            params: {
                page: payload.page,
                per_page: 500
            },
        }

        return new Promise((resolve, reject) => {
            function proceed() {
                axios(passedData)
                    .then((response) => {
                        if (typeof response.data !== "undefined") {
                            resolve(response)
                        } else {
                            reject(response)
                        }
                    })
                    .catch((err) => {
                        reject(err)
                    })
            }
            proceed()
        })
    }
}

export default ProductModel