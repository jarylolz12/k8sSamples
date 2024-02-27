import axios from "axios"
//import _ from 'lodash'
import store from '../'

const state = {
	scheduleOptions: [],
	scheduleOptions_loading: true,
	selectedSchedule: null,
	requestNewSchedules: [],
	requestNewSchedulesLoading: false
}

const getters = {
	getScheduleOptions: state => state.scheduleOptions,
	getScheduleOptionsLoading: state => state.scheduleOptions_loading,
	getSelectedSchedule: state => state.selectedSchedule,
	getNewSchedules: state => state.requestNewSchedules,
	getNewSchedulesLoading: state => state.requestNewSchedulesLoading
}

const actions = {
	fetchScheduleOptions: async ({
		commit
	}, id) => {
		let attempt = false
		commit("SET_SCHEDULE_OPTIONS", [])
		commit("SET_SCHEDULE_OPTIONS_LOADING", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				
				axios.get(`/schedule-options?id=${id}`)
				.then(res => {
					if(res.status === 200) {
						if (res.data) {
							commit("SET_SCHEDULE_OPTIONS_LOADING", false)

							let value = res.data
							commit("SET_SCHEDULE_OPTIONS", value)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							},300)
							
						} else {
							commit("SET_SCHEDULE_OPTIONS_LOADING", false)
							// reject('Token has been expired. Please reload the page.')
							reject(err.message)
						}
					} else {
						commit("SET_SCHEDULE_OPTIONS_LOADING", false)
						reject(err.error)
					}

					//return Promise.reject(err)
				})	
			}
			proceed()	
		})
	},
	sendConfirmSchedule: async ({
		commit
	}, passedData) => {

		let attempt = false
		return new Promise((resolve, reject) => {
			function proceed() {
				axios.get(`/select-schedule?id=${passedData.id}&schedule_id=${passedData.sid}`)
				.then(res => {
					if(res.status === 200) {
						if (res.data) {
							let value = res.data
							commit("SET_SELECTED_SCHEDULE", value)
						}
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if ( !attempt ){
							attempt = true
							let t =setInterval(() => {
								if (!store.getters.getIsRefreshing) {
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
	requestNewSchedule: async ({
		commit
	}, id) => {
		commit("SET_NEW_SCHEDULE_OPTIONS_LOADING", true)

		await axios.get(`/request-new-schedule/${id}`)
		.then(res => {
			if(res.status === 200) {
				if (res.data) {
					commit("SET_NEW_SCHEDULE_OPTIONS_LOADING", false)

					let value = res.data
					commit("SET_NEW_SCHEDULE_OPTIONS", value)
				}
			}
		})
		.catch(err => {
			return Promise.reject(err)
		})
	},
}

const methods = {
	testing: () => {
		alert('testing')
	}
}
const mutations = {
	SET_SCHEDULE_OPTIONS: (state, payload) => {
		state.scheduleOptions = payload
	},
	SET_SCHEDULE_OPTIONS_LOADING: (state, payload) => {
		state.scheduleOptions_loading = payload
	},
	SET_SELECTED_SCHEDULE: (state, payload) => {
		state.selectedSchedule = payload
	},
	SET_NEW_SCHEDULE_OPTIONS: (state, payload) => {
		state.requestNewSchedules = payload
	},
	SET_NEW_SCHEDULE_OPTIONS_LOADING: (state, payload) => {
		state.requestNewSchedulesLoading = payload
	},
}

export default {
	state,
	getters,
	actions,
	mutations,
	methods
}