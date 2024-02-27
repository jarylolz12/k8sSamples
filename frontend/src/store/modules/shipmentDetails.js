import axios from "axios"
//import { filter } from "vue/types/umd"
// import { has } from "core-js/fn/reflect"
// import router from '../../router/index'
import Shipment from './../../custom/ShipmentResource'
// import MilestoneDetails from './../../custom/MilestoneResource'
//import _ from 'lodash'
import moment from 'moment'
import store from '../'
//import terminalRegions from './terminalRegions'

const state = {
	shipments_details: {},
	shipment_details_loading: true,
	shipment_terms: [],
	shipment_containers_sizes: [],
	shipment_milestones: [],
	terminal_regions: [],
	carriers: [],
	terminals: []
}

const getters = {
	getShipmentDetails: state => state.shipments_details,
	getShipmentDetailsLoading: state => state.shipment_details_loading,
	getShipmentTerms: state => state.shipment_terms,
	getShipmentContainerSizes: state => state.shipment_containers_sizes,
	getMilestonesDetails: state => state.shipment_milestones,
	getTerminalRegions: state => state.terminal_regions,
	getTerminals: state => state.terminals,
	getCarriers: state => state.carriers
}

const actions = {	
	fetchShipmentDetails: async({
		commit
	}, id) => {
		commit("SET_SHIPMENT_DETAILS_LOADING", true)
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`/shipment/${id}`)
				// await axios.get(`/shipmentt/${id}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							let d = res.data.data
							/*
							let val = res.data.data	
												
							if ((val.booking_confirmed === 0 && val.cancelled === 0)) {
								if (typeof val.schedules_group_bookings !== 'undefined' && val.schedules_group_bookings !== '' && val.schedules_group_bookings !== null) {
									try {
										let set_schedules = (Array.isArray(val.schedules_group_bookings)) ? val.schedules_group_bookings : JSON.parse(val.schedules_group_bookings)

										if (set_schedules.length > 0) {
											set_schedules.map((s, kk) => {
												let set_to = moment.utc(moment()).format('YYYY-MM-DD HH:mm:ss')
												let set_from = moment.utc(moment(s.etd)).format('YYYY-MM-DD HH:mm:ss')

												// check if the etd has remaining 4 days and not confirmed
												let diff_days = moment.duration(moment(set_to).diff(set_from))

												diff_days = (diff_days._isValid) ? diff_days.asDays() : 1000
												if (diff_days >= 4) {
													set_schedules.splice(kk, 1)
												}
											})

											if (set_schedules.length == 0) {
												val.shipment_status = 'Expired'
											}
										}
									} catch (e) {
										console.log(e);
									}
								}
							}
							
							var shipmentDetailsUpdate = new Shipment(val)
							commit("SET_SHIPMENTS_DETAILS", shipmentDetailsUpdate.all())

							let mergeSchedules = JSON.parse(d.schedules_group)
							let mergeToSchedulesBookings = JSON.parse(d.schedules_group_bookings)
										
							let newSchedules = []

							if (mergeSchedules !== null && mergeToSchedulesBookings !== null) {
								if (mergeSchedules.length > 0) {
									mergeSchedules.map(ms => {
										newSchedules.push(ms.id)
									})
								}
					
								if (mergeToSchedulesBookings.length > 0) {
									mergeToSchedulesBookings.map(ms => {
										if (newSchedules.indexOf(ms.id)==-1)
											newSchedules.push(ms.id)
									})
								}
								
								let newFinalSchedules = [];
					
								if (newSchedules.length > 0) {
									newSchedules.map (ns => {
										let setId = null
										if (mergeSchedules.length > 0) {
											mergeSchedules.map(ms => {
												if (ns==ms.id) {
													newFinalSchedules.push(ms)
													setId = ms.id
												}
											})
										}
					
										if (mergeToSchedulesBookings.length > 0) {
											mergeToSchedulesBookings.map(ms => {
												if (ns==ms.id && setId!=ms.id) {
													newFinalSchedules.push(ms)
												}
											})
										}
									})
								}
					
								if (newFinalSchedules.length > 0) {
									let has_confirmed = false;							
									let selectedSchedule = []
		
									newFinalSchedules.map(newFinalSchedule => {
										if (typeof newFinalSchedule.is_confirmed !== 'undefined' && newFinalSchedule.is_confirmed == 1) {
											if(!has_confirmed) {
												has_confirmed = true
												selectedSchedule = newFinalSchedule
											}
										}
									})
					
									if (!has_confirmed) {
										selectedSchedule = newFinalSchedules[0]
									}
									
									axios.get(`/terminal/${selectedSchedule.location_from}`).then( response => {
										if (response.status == 200) {
											if (typeof response.data!=='undefined') {
												// d.location_from_name = response.data.data.name
												d.location_from_name_bookings = response.data.data.name
											}
					
											axios.get(`/terminal/${selectedSchedule.location_to}`).then( responseSecond => {
												if (responseSecond.status==200) {
													if (typeof responseSecond.data!=='undefined') {
														// d.location_to_name = responseSecond.data.data.name
														d.location_to_name_bookings = responseSecond.data.data.name
													}
													
													if (typeof d.location_from_name!=='undefined') {
														let etd = moment.utc(d.etd).format('ddd MMM DD, YYYY')
														// d.location_from_with_etd = `${d.location_from_name}, ${etd}`
														d.location_from_with_etd = `${etd}`
													}
													
													if (typeof d.location_to_name!=='undefined') {
														let eta = moment.utc(d.eta).format('ddd MMM DD, YYYY')
														let etaDetails = moment.utc(d.eta).format('MMM DD, YYYY')
														// d.location_to_with_eta = `${d.location_to_name}, ${eta}`
														d.location_to_with_eta = `${eta}`
														d.location_to_with_eta_details = `${d.location_to_name}, ETA ${etaDetails}`
													}
													
													// shipmentDetailsUpdate = new Shipment(d)
													// commit("SET_SHIPMENTS_DETAILS", shipmentDetailsUpdate.all())	
													// return Promise.resolve('ok')
												}	
											})
										}	
									})
								}
							}*/
							
							let shipmentDetailsUpdate = new Shipment(d)
							//commit("SET_SHIPMENTS_DETAILS", d)
							commit("SET_SHIPMENTS_DETAILS", shipmentDetailsUpdate.all())
							
						}
						//let shipmentDetailsUpdate = new Shipment(d)
						//commit("SET_SHIPMENTS_DETAILS", d)
						//commit("SET_SHIPMENTS_DETAILS", shipmentDetailsUpdate.all())
						// var milestoneDetails = new MilestoneDetails(d)
						// // commit("SET_MILESTONES_DETAILS", milestoneDetails.all())

						// var getKeys = Object.keys(milestoneDetails.all())
						// var setData = []

						// getKeys.map(getKey => {
						// 	setData.push({
						// 		...milestoneDetails.all()[getKey]
						// 	})
						// })

						// // console.log(setData);
						// commit("SET_MILESTONES_DETAILS", setData)
					}
					commit("SET_SHIPMENT_DETAILS_LOADING", false)
					resolve()
				})
				.catch(err => {

					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing ) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							commit('SET_SHIPMENT_DETAILS_LOADING', false)
							reject('Token has been expired. Please reload the page.')
						}
					}

					if (typeof err.error !=='undefined') {
						commit('SET_SHIPMENT_DETAILS_LOADING', false)
						//reject(err.error)
						//commit("SET_PO_DETAIL_LOADING", false)
						reject(err.error)
					}

					/*
					commit("SET_SHIPMENT_DETAILS_LOADING", false)
					return Promise.reject(err)*/
				})
			}
			proceed()
		})
	},
	fetchCarriers: async({ commit }) => {
		return new Promise((resolve, reject) => {
			axios.get('/carriers').then( response => {
				if ( typeof response.data!=='undefined' && typeof response.data.data!=='undefined') {
					commit('SET_CARRIERS', response.data.data)
					resolve(response.data.data)
				}
			}).catch(e => {
				reject(e)
			})
		})
	},
	fetchTerminals: async({ commit }, payload) => {
		let {
			is_paginate
		} = payload
		let set_paginate = (typeof is_paginate=='undefined') ? 1 : is_paginate;
		return new Promise((resolve, reject) => {
			axios.get(`/terminals?is_paginate=${set_paginate}`).then( response => {
				if ( typeof response.data!=='undefined' && typeof response.data.results!=='undefined') {
					if ( is_paginate === 1 ) {
						commit('SET_TERMINALS', response.data.results.data)
						resolve(response.data.results.data)
					} else {
						commit('SET_TERMINALS', response.data.results)
						resolve(response.data.results)
					}		
				}
			}).catch(e => {
				reject(e)
			})
		})
	},
	fetchTerminalRegions: async({ commit }, payload) => {
		let {
			is_paginate
		} = payload
		
		let set_paginate = (typeof is_paginate=='undefined') ? 1 : is_paginate;

		return new Promise((resolve, reject) => {
			axios.get(`/terminal-regions?is_paginate=${set_paginate}`).then( response => {
				if ( typeof response.data!=='undefined' && typeof response.data.results!=='undefined') {
					if ( is_paginate === 1 ) {
						commit('SET_TERMINAL_REGIONS', response.data.results.data)
						resolve(response.data.results.data)
					} else {
						commit('SET_TERMINAL_REGIONS', response.data.results)
						resolve(response.data.results)
					}
					
				}
			}).catch(e => {
				reject(e)
			})
			//commit('SET_TERMINAL_REGIONS', terminalRegions.results)
			//resolve(terminalRegions.results)
		})
	},
	fetchTerminal: async(o, id ) => {
		return new Promise((resolve, reject) => {
			axios.get(`/terminal/${id}}`).then( response => {
				if ( typeof response!=='undefined' && typeof response.data!=='undefined' && typeof response.data.data!=='undefined') {
					resolve(response.data.data)
				} else {
                    reject('not ok')
                }
			}).catch(e => {
				reject(e)
			})
		})
	},
	fetchTerms: async({
		commit
	}) => {
		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`/incoterms`)
				.then(res => {
					if(res.status === 200) {
						if (res.data) {
							let value = res.data.data
							commit("SET_SHIPMENT_TERMS", value)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing ) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							reject('Token has been expired. Please reload the page.')
						}
					} else {
						reject(err)
					}
					reject(err)
				})	
			}
			proceed()
		})
		
	},
	fetchContainers: async({
		commit
	}) => {
		let attempt = false
		return new Promise((resolve, reject) => {

			function proceed() {
				axios.get(`/container-sizes`)
				.then(res => {
					if(res.status === 200) {
						if (res.data) {
							let value = res.data.data
							commit("SET_SHIPMENT_CONTAINERS_SIZES", value)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing ) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							reject('Token has been expired. Please reload the page.')
						}
					} else {
						reject(err)
					}
					//return Promise.reject(err)
				})
			}
			proceed()	
		})
		
	},

	fetchShipmentDetailsBack: async ({
		commit
	}, id) => {

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_SHIPMENT_DETAILS_LOADING", true)
				axios.get(`/shipment/${id}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							let d = res.data.data

							//s
							if (typeof d.schedules_group!=='undefined' && d.schedules_group !== null) {
								let mergeSchedules = JSON.parse(d.schedules_group)
								let mergeToSchedulesBookings = JSON.parse(d.schedules_group_bookings)
								
								let newSchedules = []
					
								if (mergeSchedules.length > 0) {
									mergeSchedules.map(ms => {
										newSchedules.push(ms.id)
									})
								}
					
								if (mergeToSchedulesBookings.length > 0) {
									mergeToSchedulesBookings.map(ms => {
										if (newSchedules.indexOf(ms.id)==-1)
											newSchedules.push(ms.id)
									})
								}
								
								let newFinalSchedules = [];
					
								if (newSchedules.length > 0) {
									newSchedules.map (ns => {
										let setId = null
										if (mergeSchedules.length > 0) {
											mergeSchedules.map(ms => {
												if (ns==ms.id) {
													newFinalSchedules.push(ms)
													setId = ms.id
												}
											})
										}
					
										if (mergeToSchedulesBookings.length > 0) {
											mergeToSchedulesBookings.map(ms => {
												if (ns==ms.id && setId!=ms.id) {
													newFinalSchedules.push(ms)
												}
											})
										}
									})
								}
					
								if (newFinalSchedules.length > 0) {
									let has_confirmed = false
									let selectedSchedule = {}

									newFinalSchedules.map(newFinalSchedule => {
										if (typeof newFinalSchedule.is_confirmed!=='undefined' &&newFinalSchedule.is_confirmed==1) {
											if(!has_confirmed) {
												has_confirmed = true
												selectedSchedule = newFinalSchedule
											}
										}
									})
					
									if (!has_confirmed) {
										selectedSchedule = newFinalSchedules[0]
									}
									
									axios.get(`/terminal/${selectedSchedule.location_from}`).then( response => {
										if (response.status==200) {
											if (typeof response.data!=='undefined') {
												d.location_from_name = response.data.data.name
											}
					
											axios.get(`/terminal/${selectedSchedule.location_to}`).then( responseSecond => {
												if (responseSecond.status==200) {
													if (typeof responseSecond.data!=='undefined') {
														d.location_to_name = responseSecond.data.data.name
													}
													
													if (typeof d.location_from_name!=='undefined') {
														let etd = moment.utc(d.etd).format('ddd MMM DD, YYYY')
														// d.location_from_with_etd = `${d.location_from_name}, ${etd}`
														d.location_from_with_etd = `${etd}`
													}
													
													if (typeof d.location_to_name!=='undefined') {
														let eta = moment.utc(d.eta).format('ddd MMM DD, YYYY')
														let etaDetails = moment.utc(d.eta).format('MMM DD, YYYY')
														// d.location_to_with_eta = `${d.location_to_name}, ${eta}`
														d.location_to_with_eta = `${eta}`
														d.location_to_with_eta_details = `${d.location_to_name}, ETA ${etaDetails}`
													}
													
													
													let shipmentDetailsUpdate = new Shipment(d)
													commit("SET_SHIPMENTS_DETAILS", shipmentDetailsUpdate.all())	

													console.log(d)
												}	
											})
										}	
									})
								}
							}
							//e
							// let shipmentDetails = new Shipment(res.data.data)
							// commit("SET_SHIPMENTS_DETAILS", shipmentDetails.all())	
						}
					}

					commit("SET_SHIPMENT_DETAILS_LOADING", false)
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if ( !store.getters.getIsRefreshing ) {
									proceed()
									clearInterval(t)
								}
							},300)
						} else {
							reject('Token has been expired. Please reload the page.')
						}
					} else {
						reject(err)
					}
					console.log(err)
				})	
			}
			proceed()	
		})
		
		
	}
}

const methods = {
	testing: () => {
		alert('testing')
	}
}
const mutations = {
	SET_CARRIERS: (state, payload) => {
		state.carriers = payload
	},
	SET_SHIPMENTS_DETAILS: (state, payload) => {
		state.shipments_details = payload
	},
	SET_TERMINALS: (state, payload) => {
		state.terminals = payload
	},
	SET_TERMINAL_REGIONS: (state, payload) => {
		state.terminal_regions = payload
	},
	SET_SHIPMENT_DETAILS_LOADING: (state, payload) => {
		state.shipment_details_loading = payload
	},
	SET_SHIPMENT_TERMS: (state, payload) => {
		state.shipment_terms = payload
	},
	SET_SHIPMENT_CONTAINERS_SIZES: (state, payload) => {
		state.shipment_containers_sizes = payload
	},
	SET_MILESTONES_DETAILS: (state, payload) => {
		state.shipment_milestones = payload
	}
}

export default {
	state,
	getters,
	actions,
	mutations,
	methods
}