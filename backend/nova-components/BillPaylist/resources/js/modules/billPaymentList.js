//import axios from 'axios'

const state = {
    items: [],
    accounts: [],
    accountsLoading: true,
    itemsLoading: true,
    currentPage: 1,
    prevPageUrl: null,
    nextPageUrl: null,
    from: 0,
    to: 0,
    total: 0,
    currentBillLines: {}
}

const getters = {
    getItems: state => state.items,
    getItemsLoading: state => state.itemsLoading,
    getCurrentPage: state => state.currentPage,
    getNextPageUrl: state => state.nextPageUrl,
    getPrevPageUrl: state => state.prevPageUrl,
    getTo: state => state.to,
    getFrom: state => state.from,
    getTotal: state => state.total,
    getAccounts: state => state.accounts,
    getAccountsLoading: state => state.accountsLoading,
    getCurrentBillLines: state => state.currentBillLines
}

const actions = {
    async getBillLines({commit}, payload) {
        return new Promise((resolve, reject) => {
            axios.post(`/custom/bill-get-bill-lines`, payload)
            .then(res => {
                if (res.status === 200) {
                    if (typeof res.data!=='undefined' && typeof res.data.result!=='undefined') {
                        if (typeof res.data.result.Line!=='undefined') {
                            commit('SET_CURRENT_BILL_LINES', res.data.result.Line)   
                        }
                        resolve(res.data.result)
                    } else {
                        reject({status: 'not ok'})
                    }
                }
            }).catch(e => {
                reject({status: 'not ok'})
            })
        })
    },
    async search({commit}, payload) {
        commit("SET_ITEMS_LOADING", true)
        commit("SET_ITEMS", [])

        
        return new Promise((resolve, reject) => {
            let field = ''
            if (typeof payload.params!=='undefined' ) {
                field = `&field=${payload.params.sort[0].field}&orderBy=${payload.params.sort[0].type}`
            }

            axios.get(`/custom/bill-payment-lists/search?q=${payload.qry}${field}`)
            .then(res => {
                commit("SET_ITEMS_LOADING", false)
                if (res.status === 200) {
                    if (typeof res.data!=='undefined' && typeof res.data.results!=='undefined') {
                        let { results } = res.data
                        if (res.data.results.data.length > 0) {
                            commit("SET_PREV_PAGE_URL", results.prev_page_url)
                            commit("SET_NEXT_PAGE_URL", results.next_page_url)
                            commit("SET_TO", results.to)
                            commit("SET_FROM", results.from)
                            commit("SET_TOTAL", results.total)
                        }

                        if (typeof results.data!=='undefined') {
                            let getData = results.data
                            if ( getData.length > 0) {

                                getData.map(async (gd, key) => {

                                    //getData[key].status_loading = true
                                    //getData[key].connected_qb = false
                                    //getData[key].payment_status = 1
                                    getData[key].status_loading = false
                                    getData[key].connected_qb = true
                                    /*
                                    try {
                                        let paymentStatus = await axios.get(`/custom/bill-payment-lists/qb/bill/${gd.qbo_bill_id}`)
                                        getData[key].status_loading = false
                                        if ( paymentStatus.data.status=='ok' ) {
                                            getData[key].balance_pay = paymentStatus.total_balance
                                            getData[key].payment_status = (paymentStatus.data.status=='ok') ? paymentStatus.data.payment_status : 1
                                            getData[key].is_integrated_brex = paymentStatus.data.is_integrated_brex
                                            getData[key].vendor_link = paymentStatus.data.vendor_link
                                            getData[key].connected_qb = true
                                        }
                                    } catch(e) {
                                        getData[key].status_loading =false
                                    }*/
                                    
                                })

                            }
                                
                        }
                        commit("SET_ITEMS_LOADING", false)
                        commit("SET_ITEMS", res.data.results.data)

                        resolve({status: 'ok'})
                    } else {
                        commit("SET_ITEMS", [])
                    }
                } else {
                    reject({status: 'not ok'})
                }
            }).catch(e => {
                reject({status: 'not ok'})
            })
        })
    },
    fetchPage({ commit}, payload) {
        commit("SET_ITEMS_LOADING", true)
        commit("SET_ITEMS", [])

        let _this = this;

        return new Promise((resolve, reject) => {
            let field  = ''

            if (typeof payload.params!=='undefined' ) {
                field = `&field=${payload.params.sort[0].field}&orderBy=${payload.params.sort[0].type}`
            }
            axios.get(payload.url + field )
            .then(res => {
                if (res.status === 200) {
                    if (typeof res.data!=='undefined' && typeof res.data.results!=='undefined') {
                        let { results } = res.data
                        if (res.data.results.data.length > 0) {
                            commit("SET_PREV_PAGE_URL", results.prev_page_url)
                            commit("SET_NEXT_PAGE_URL", results.next_page_url)
                            commit("SET_TO", results.to)
                            commit("SET_FROM", results.from)
                            commit("SET_TOTAL", results.total)
                        }
                        if (typeof results.data!=='undefined') {
                            let getData = results.data
                            if ( getData.length > 0) {

                                getData.map(async (gd, key) => {
                                    /*
                                    getData[key].payment_status = 1
                                    getData[key].connected_qb = false
                                    try {
                                        let paymentStatus = await axios.get(`/custom/bill-payment-lists/qb/bill/${gd.qbo_bill_id}`)

                                        if ( paymentStatus.data.status=='ok' ) {
                                            getData[key].connected_qb = true
                                            getData[key].balance_pay = paymentStatus.total_balance
                                            getData[key].payment_status = (paymentStatus.data.status=='ok') ? paymentStatus.data.payment_status : 1 
                                            getData[key].is_integrated_brex = paymentStatus.data.is_integrated_brex
                                            getData[key].vendor_link = paymentStatus.data.vendor_link
                                        }    
                                    } catch(e) {
                                        getData[key].status_loading = false
                                    }*/
                                    //getData[key].payment_status = 1
                                    getData[key].status_loading = false
                                    getData[key].connected_qb = true
                                    
                                })
                            }
                                
                        }
                        commit("SET_ITEMS_LOADING", false)
                        commit("SET_ITEMS", res.data.results.data)

                        resolve({status: 'ok'})
                    } else {
                        commit("SET_ITEMS", [])
                    }
                } else {
                    reject({status: 'not ok'})
                }
            }).catch(e => {
                reject({status: 'not ok'})
            })
        })
    },

    setItems({ commit }, payload) {
        commit("SET_ITEMS", payload)
    },
    setTo({ commit }, payload) {
        commit("SET_TO", payload)
    },
    setFrom({ commit }, payload) {
        commit("SET_FROM", payload)
    },
    setTotal({ commit }, payload) {
        commit("SET_TOTAL", payload)
    },
    setNextPageUrl({ commit }, payload) {
        commit("SET_NEXT_PAGE_URL", payload)
    },
    setPrevPageUrl({ commit }, payload) {
        commit("SET_PREV_PAGE_URL", payload)
    },
    setPage({ commit }, payload) {
        commit("SET_PAGE", payload)
    },
    setItemsLoading({ commit }, payload) {
        commit("SET_ITEMS_LOADING", payload)
    },
    removeBill: async({ commit }, payload) => {
        return new Promise((resolve, reject) => {

            axios.delete(`/custom/bill-payment-lists/remove-bill/${payload.bill_id}`)
            .then(res => {
                if (res.status === 200) {
                    if (typeof res.data!=='undefined' && typeof res.data.status!=='undefined') {
                        if (res.data.status=='ok') {
                            resolve({status: 'ok'})
                        } else {
                            reject({status: 'not ok'})
                        }
                    } else {
                        reject({status: 'not ok'})
                    }
                }


            }).catch(e => {
                reject({status: 'not ok'})
            })
        })
    },
    payBill: async({ commit }, payload) => {
        return new Promise((resolve, reject) => {

            axios.post(`/custom/bill/pay`, payload)
            .then(res => {

                if (res.status === 200) {
                    if (typeof res.data!=='undefined' && typeof res.data.status!=='undefined') {
                        if (res.data.status=='ok') {
                            resolve({status: 'ok'})
                        } else {
                            reject({status: 'not ok'})
                        }
                    } else {
                        reject({status: 'not ok'})
                    }
                }


            }).catch(e => {
                reject({status: 'not ok'})
            })
        })
    },
    markAsPaid: async ({ commit }, payload) =>{
        return new Promise((resolve, reject) => {
            axios.post(`/custom/bill-payment-lists/${payload.bill_id}`, payload)
            .then(res => {
                if (res.status === 200) {
                    if (typeof res.data!=='undefined' && typeof res.data.status!=='undefined') {
                        if (res.data.status=='ok') {
                            resolve({status: 'ok'})
                        } else {
                            reject({status: 'not ok'})
                        }
                    } else {
                        reject({status: 'not ok'})
                    }
                }
            }).catch(e => {
                reject({status: 'not ok'})
            })
        })
    },
    fetchItems: async ({
        commit
    }, payload) => {
        
        return new Promise((resolve, reject) => {
            let field = ''
            if (typeof payload.params!=='undefined' ) {
                field = `field=${payload.params.sort[0].field}&orderBy=${payload.params.sort[0].type}`
            }
            axios.get(`/custom/bill-payment-lists?${field}&page=${payload.params.page}`)
            .then(res => {
                //commit("SET_ITEMS_LOADING", false)
                if (res.status === 200) {
                    if (typeof res.data!=='undefined' && typeof res.data.results!=='undefined') {
                        let { results } = res.data

                        if (res.data.results.data.length > 0) {
                            commit("SET_PREV_PAGE_URL", results.prev_page_url)
                            commit("SET_NEXT_PAGE_URL", results.next_page_url)
                            commit("SET_TO", results.to)
                            commit("SET_FROM", results.from)
                            commit("SET_TOTAL", results.total)
                        }

                        if (typeof results.data!=='undefined') {
                            let getData = results.data
                            if ( getData.length > 0) {

                                getData.map(async (gd, key) => {

                                    /*
                                    getData[key].status_loading = true
                                    getData[key].payment_status = 1
                                    getData[key].connected_qb = false

                                    try {
                                        let paymentStatus = await axios.get(`/custom/bill-payment-lists/qb/bill/${gd.qbo_bill_id}`)
                                        getData[key].status_loading = false
                                        if ( paymentStatus.data.status=='ok' ) {
                                            getData[key].connected_qb = true
                                            getData[key].balance_pay = paymentStatus.data.total_balance
                                            getData[key].payment_status = (paymentStatus.data.status=='ok') ? paymentStatus.data.payment_status : 1
                                            getData[key].is_integrated_brex = paymentStatus.data.is_integrated_brex
                                            getData[key].vendor_link = paymentStatus.data.vendor_link
                                        }
                                    } catch(e) {
                                        getData[key].status_loading = false
                                    }*/
                                    //getData[key].payment_status = 1
                                    getData[key].status_loading = false
                                    getData[key].connected_qb = true
                                    

                                })

                            }
                                
                        }
                        commit("SET_ITEMS_LOADING", false);

                        let items_temp = res.data.results.data.map( (i,e) =>{
                            i.overdue_balance = 'Loading...';
                            i.last_payment = 'Loading...';

                            axios.get('/nova-vendor/bill-paylist/extra-data/customer/'+i.customer_id+'?row='+e)
                            .then( res =>{
                                let items = JSON.parse(JSON.stringify(state.items));

                                items[res.data.row].overdue_balance = res.data.overdue_balance;
                                items[res.data.row].last_payment = res.data.last_payment;

                                state.items = items;
                            });

                            return i;
                        });

                        commit("SET_ITEMS", items_temp);

                        resolve({status: 'ok'})
        
                    } else {
                        commit("SET_ITEMS_LOADING", [])
                    }
                } else {
                    reject({status: 'not ok'})
                }
            }).catch(e => {
                reject({status: 'not ok'})
            })
        })
        
    }
}

const mutations = {
    SET_PAGE: (state, payload) => {
        state.currentPage = payload
    },
    SET_ITEMS: (state, payload) => {
        state.items = payload
    },
    SET_ITEMS_LOADING: (state, payload) => {
        state.itemsLoading = payload
    },
    SET_NEXT_PAGE_URL: (state, payload) => {
        state.nextPageUrl = payload
    },
    SET_PREV_PAGE_URL: (state, payload) => {
        state.prevPageUrl = payload
    },
    SET_TO: (state, payload) => {
        state.to = payload
    },
    SET_TOTAL: (state, payload) => {
        state.total = payload
    },
    SET_FROM: (state, payload) => {
        state.from = payload
    },
    SET_CURRENT_BILL_LINES: (state, payload) => {
        state.currentBillLines = payload
    }
}
export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}