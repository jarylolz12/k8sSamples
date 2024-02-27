import axios from "axios"
import store from '../'
import CategoryModel from '../../models/CategoryModel'


const state = {
	categories: [],
	categoriesLoading: false,
	createCat: null,
	createCatLoading: false,
	updateCat: null,
	updateCatLoading: false,
	deleteCat: null,
	deleteCatLoading: false,
	currentCat: null,
	currentCatLoading: false,
	error: '',
	categoryBaseUrlState: process.env.VUE_APP_PO_URL,
	searchedCategories: [],
	searchedCategoriesLoading: false,
	categoriesDropdown: [],
	categoriesDropdownLoading: false,
	allCategoriesDropdown: []
}

const getters = {
	getCategories: state => state.categories,
	getCategoriesLoading: state => state.categoriesLoading,
	getCreateCat: state => state.createCat,
	getCreateCatLoading: state => state.createCatLoading,
	getUpdateCat: state => state.updateCat,
	getUpdateCatLoading: state => state.updateCatLoading,
	getDeleteCat: state => state.deleteCat,
	gettDeleteCatLoading: state => state.deleteCatLoading,
	getCurrentCategory: state => state.currentCat,
	getCurrentCategoryLoading: state => state.currentCatLoading,
	getError: state => state.error,
	getSearchedCategories: state => state.searchedCategories,
	getSearchedCategoriesLoading: state => state.searchedCategoriesLoading,
	getCategoriesDropdown: state => state.categoriesDropdown,
	getCategoriesDropdownLoading: state => state.categoriesDropdownLoading,
	getAllCategoriesDropdown: state => state.allCategoriesDropdown
}

const actions = {
	fetchCategories: async ({
		commit,
		state
	}, page) => {
		let attempt = false
		commit("SET_CATEGORIES_LOADING", true)
		return new Promise((resolve, reject) => {
			let limit = 1
			let start = 0

			function proceed() {
				let category = new CategoryModel(state.categoryBaseUrlState)
				category.getCategories(page).then(res => {
					commit("SET_CATEGORIES_LOADING", false)
					if (res.status === 200) {
						let finalData = {
							categories: [],
							current_page: 1,
							total: 0,
							last_page: 0,
							old_page: 1,
							per_page: 35
						}

						if (typeof res.data!=='undefined' && 
							typeof res.data.results!=='undefined' && 
							typeof res.data.results.data!=='undefined') {

							finalData = {
								categories: res.data.results.data,
								current_page: res.data.results.current_page,
								total: res.data.results.total,
								old_page: res.data.results.current_page,
								last_page: res.data.results.last_page,
								per_page: res.data.results.per_page
							}

							commit("SET_CATEGORIES", finalData)
						} else {
							commit("SET_CATEGORIES", finalData)
						}
					}
					resolve()
				}).catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									if ( start == limit) {
										store.dispatch('refreshToken').then(() => {
											attempt = false
											start = 0
											proceed()
										}).catch(e => {
											console.log(e)
											commit("SET_CATEGORIES_LOADING", false)
											reject(err)
										})
									} else {
										start++
										proceed()
										attempt = false
									}
									clearInterval(t)
								}
							},300)
							
						} else {
							commit("SET_CATEGORIES_LOADING", false)
							reject(err)
						}
					}
				})
				/*
				axios.get(`${process.env.VUE_APP_PO_URL}/categories`)
				.then(res => {
					commit("SET_CATEGORIES_LOADING", false)
					if (res.status === 200) {
						if (typeof res.data!=='undefined' && typeof res.data.results!=='undefined' && typeof res.data.results.data!=='undefined') {
							// console.log(res.data.results);
							//commit("SET_CATEGORIES", res)
							commit("SET_CATEGORIES", res.data.results.data)
						} else {
							commit("SET_CATEGORIES", [])
						}
					}
					resolve()
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_CATEGORIES_LOADING', false)
							reject('Token has been expired. Please reload the page.')
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_CATEGORIES_LOADING', false)
						//reject(err.error)
						//commit("SET_PO_DETAIL_LOADING", false)
						reject(err.error)
					}
					/*
					commit("SET_CATEGORIES_LOADING", false)
					if (typeof err.error !== 'undefined') {
						return Promise.reject(err.error)
					} else {
						if (typeof err.message !== 'undefined') {
							return Promise.reject('Token has been expired. Please reload the page.')
						}
					}*/
				//})	
			}
			proceed()
		})		
	},
	createCategories: async ({
		commit
	}, payload ) => {
		
		let attempt = false
		commit("SET_CREATE_CATEGORIES_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.post(`${process.env.VUE_APP_PO_URL}/categories/create`, payload)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							// console.log(res.data);
							commit("SET_CREATE_CATEGORIES_LOADING", false)
							// commit("SET_CREATE_CATEGORIES", res.data)
						}
					}
					resolve()
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_CREATE_CATEGORIES_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_CREATE_CATEGORIES_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	updateCategories: async ({
		commit
	}, payload, ) => {

		let attempt = false
		commit("SET_UPDATE_CATEGORIES_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.put(`${process.env.VUE_APP_PO_URL}/categories/update/${payload.cat_id}`, payload.passedData)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							// console.log(res.data);
							commit("SET_UPDATE_CATEGORIES_LOADING", false)
							// commit("SET_UPDATE_CATEGORIES", res.data)
						}
					}
					resolve()
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_UPDATE_CATEGORIES_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_UPDATE_CATEGORIES_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})	
	},
	deleteCategories: async({
		commit
	}, id) => {

		commit("SET_DELETE_CATEGORIES_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.delete(`${process.env.VUE_APP_PO_URL}/categories/delete/${id}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DELETE_CATEGORIES_LOADING", false)
							// commit("SET_DELETE_CATEGORIES", res.data)
						}
					}
					resolve()
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit("SET_ERROR", err)
							commit('SET_DELETE_CATEGORIES_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit("SET_ERROR", err)
						commit('SET_DELETE_CATEGORIES_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()	
		})
		
	},
	getCurrentCategory: async({
		commit
	}, id) => {
		
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_CURRENT_CATEGORY_LOADING", true)
				axios.get(`${process.env.VUE_APP_PO_URL}/categories/${id}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							console.log(res.data);
							commit("SET_CURRENT_CATEGORY_LOADING", false)
							// commit("SET_CURRENT_CATEGORY", res.data.results)
						}
					}
					resolve()
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_CURRENT_CATEGORY_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_CURRENT_CATEGORY_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	fetchSearchedCategories: async ({
		commit
	}, payload) => {
		let attempt = false
		commit("SET_SEARCHED_CATEGORIES_LOADING", true)
		return new Promise((resolve, reject) => {
			// let limit = 1
			// let start = 0

			function proceed() {
				axios(payload)
				.then(res => {
					commit("SET_SEARCHED_CATEGORIES_LOADING", false)
					if (res.status === 200) {
						let finalData = {
							categories: [],
							current_page: 1,
							total: 0,
							last_page: 0,
							old_page: 1,
							per_page: 35
						}

						if (typeof res.data!=='undefined' && 
							typeof res.data.results!=='undefined' && 
							typeof res.data.results.data!=='undefined') {

							finalData = {
								categories: res.data.results.data,
								current_page: res.data.results.current_page,
								total: res.data.results.total,
								old_page: res.data.results.current_page,
								last_page: res.data.results.last_page,
								per_page: res.data.results.per_page,
								tab: payload.tab
							}

							commit("SET_SEARCHED_CATEGORIES", finalData)
						} else {
							commit("SET_SEARCHED_CATEGORIES", finalData)
						}
					}
					resolve()
				}).catch(err => {
					// if (typeof err.message!=='undefined') {
					// 	if ( !attempt ){
					// 		attempt = true
					// 		let t = setInterval(() => {
					// 			if (!store.getters.getIsRefreshing) {
					// 				if ( start == limit) {
					// 					store.dispatch('refreshToken').then(() => {
					// 						attempt = false
					// 						start = 0
					// 						proceed()
					// 					}).catch(e => {
					// 						console.log(e)
					// 						commit("SET_SEARCHED_CATEGORIES_LOADING", false)
					// 						reject('Token has been expired. Please reload the page.')
					// 					})
					// 				} else {
					// 					start++
					// 					proceed()
					// 					attempt = false
					// 				}
					// 				clearInterval(t)
					// 			}
					// 		},300)
							
					// 	} else {
					// 		commit("SET_SEARCHED_CATEGORIES_LOADING", false)
					// 		reject('Token has been expired. Please reload the page.')
					// 	}
					// }

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							commit('SET_SEARCHED_CATEGORIES_LOADING', false)
							reject(err)
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_SEARCHED_CATEGORIES_LOADING', false)
						reject(err.error)
					}
				})
			}
			proceed()
		})		
	},
	fetchCategoriesDropdown: async ({
		commit,
		state
	}, page) => {
		let attempt = false
		commit("SET_CATEGORIES_DROPDOWN_LOADING", true)
		return new Promise((resolve, reject) => {
			let limit = 1
			let start = 0

			function proceed() {
				let category = new CategoryModel(state.categoryBaseUrlState)
				category.getCategoriesDropdown(page).then(res => {
					commit("SET_CATEGORIES_DROPDOWN_LOADING", false)
					if (res.status === 200) {
						let finalData = {
							categories: [],
							current_page: 1,
							total: 0,
							last_page: 0,
							old_page: 1,
							per_page: 35
						}

						if (typeof res.data!=='undefined' && 
							typeof res.data.results!=='undefined' && 
							typeof res.data.results.data!=='undefined') {

							finalData = {
								categories: res.data.results.data,
								current_page: res.data.results.current_page,
								total: res.data.results.total,
								old_page: res.data.results.current_page,
								last_page: res.data.results.last_page,
								per_page: res.data.results.per_page
							}

							commit("SET_CATEGORIES_DROPDOWN", finalData)
						} else {
							commit("SET_CATEGORIES_DROPDOWN", finalData)
						}
					}
					resolve()
				}).catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									if ( start == limit) {
										store.dispatch('refreshToken').then(() => {
											attempt = false
											start = 0
											proceed()
										}).catch(e => {
											console.log(e)
											commit("SET_CATEGORIES_DROPDOWN_LOADING", false)
											reject(err)
										})
									} else {
										start++
										proceed()
										attempt = false
									}
									clearInterval(t)
								}
							},300)
							
						} else {
							commit("SET_CATEGORIES_DROPDOWN_LOADING", false)
							reject(err)
						}
					}
				})
			}
			proceed()
		})		
	},
	setSearchedCategoriesLoading({ commit } , payload) {
		commit("SET_SEARCHED_CATEGORIES_LOADING", payload)
	},
    setSearchedCategoriesVal({ commit }, payload) {
		commit("setSearchedCategoriesVal", payload)
	},
	setAllCategoriesDropdown({ commit }, payload ) {
		commit("setAllCategoriesDropdown", payload)
	}
}

const methods = {
	testing: () => {
		alert('testing')
	}
}

const mutations = {
	SET_CATEGORIES: (state, payload) => {
		state.categories = payload
	},
	SET_CATEGORIES_LOADING: (state, payload) => {
		state.categoriesLoading = payload
	},
	SET_CREATE_CATEGORIES: (state, payload) => {
		state.createCat = payload
	},
	SET_CREATE_CATEGORIES_LOADING: (state, payload) => {
		state.createCatLoading = payload
	},
	SET_UPDATE_CATEGORIES: (state, payload) => {
		state.updateCat = payload
	},
	SET_UPDATE_CATEGORIES_LOADING: (state, payload) => {
		state.updateCatLoading = payload
	},
	SET_DELETE_CATEGORIES: (state, payload) => {
		state.deleteCat = payload
	},
	SET_DELETE_CATEGORIES_LOADING: (state, payload) => {
		state.deleteCatLoading = payload
	},
	SET_CURRENT_CATEGORY: (state, payload) => {
		state.currentCat = payload
	},
	SET_CURRENT_CATEGORY_LOADING: (state, payload) => {
		state.currentCatLoading = payload
	},
	SET_ERROR: (state, payload) => {
		state.error = payload
	},
	SET_SEARCHED_CATEGORIES: (state, payload) => {
		state.searchedCategories = payload
	},
	SET_SEARCHED_CATEGORIES_LOADING: (state, payload) => {
		state.searchedCategoriesLoading = payload
	},
	setSearchedCategoriesVal: (state, payload) => {
        let locationDefaultData = {
            currentTab: 1,
            current_page: 1,
            old_page: 1,
            total: 0,
            per_page: 0,
            categories: payload
        }

        state.searchedCategories = locationDefaultData
    },
	SET_CATEGORIES_DROPDOWN: (state, payload) => {
		state.categoriesDropdown = payload
	},
	SET_CATEGORIES_DROPDOWN_LOADING: (state, payload) => {
		state.categoriesDropdownLoading = payload
	},
	setAllCategoriesDropdown: (state, payload) => {
		state.allCategoriesDropdown = payload
	}
}

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations,
	methods
}