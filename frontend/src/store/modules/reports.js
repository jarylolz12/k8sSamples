/** @format */
import axios from "axios";
import qs from "qs";
import store from '../'
import moment from 'moment'

const state = {
	emailReports: [],
	emailReportsLoading: false,
	emailReportDownloadLoading: false,
	defaultValue: [],
	defaultValueLoading: false,
	updateEmailReports: [],
	updateEmailReportsLoading: false,
	createEmailReports: [],
	createEmailReportsLoading: false,
	// 
	reportsSchedule: null,
	reportsScheduleLoading: false,
	reportColumns: null,
	reportColumnsLoading: false,
	deleteReportLoading: false,
	sendReportLoading: false,
	updateStatusLoading: false,
	emailReportDownloadData: null
};

const getters = {
	getEmailReports: (state) => state.emailReports,
	getEmailReportsLoading: (state) => state.emailReportsLoading,
	getEmailReportDownloadLoading: (state) => state.emailReportDownloadLoading,
	getDefaultValue: (state) => state.defaultValue,
	getDefaultValueLoading: (state) => state.defaultValueLoading,
	getUpdateEmailReports: (state) => state.updateEmailReports,
	getUpdateEmailReportsLoading: (state) => state.updateEmailReportsLoading,
	getCreateEmailReports: (state) => state.createEmailReports,
	getCreateEmailReportsLoading: (state) => state.createEmailReportsLoading,
	// 
	getReportsSchedule: state => state.reportsSchedule,
	getReportsScheduleLoading: state => state.reportsScheduleLoading,
	getReportColumns: state => state.reportColumns,
	getReportColumnsLoading: state => state.reportColumnsLoading,
	getDeleteReportScheduleLoading: state => state.deleteReportLoading,
	getSendReportScheduleLoading: state => state.sendReportLoading,
	getUpdateReportStatusLoading: state => state.updateStatusLoading,
	getEmailReportDownloadData: state => state.emailReportDownloadData
};

const actions = {
	fetchDropdownValue: async ({ commit }) => {
		commit("SET_DROPDOWN_DEFAULT_VALUE_LOADING", true);
		await axios
			.get(`email-report-schedule-default-values`)
			.then((res) => {
				if (res.status === 200) {
					if (typeof res.data !== "undefined") {
						commit("SET_DROPDOWN_DEFAULT_VALUE", res.data.data);
					}
				}
				commit("SET_DROPDOWN_DEFAULT_VALUE_LOADING", false);
			})
			.catch((err) => {
				commit("SET_DROPDOWN_DEFAULT_VALUE_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			});
	},
	fetchEmailReportSchedule: async ({ commit }, payload) => {
		commit("SET_EMAIL_REPORTS_SCHEDULE_LOADING", true);
		await axios
			.get(`email-report-schedule/${payload}`)
			.then((res) => {
				if (res.status === 200) {
					if (typeof res.data !== "undefined") {
						let reportData = res.data.data;
						commit("SET_EMAIL_REPORTS_SCHEDULE", reportData);
					}
				}
				commit("SET_EMAIL_REPORTS_SCHEDULE_LOADING", false);
			})
			.catch((err) => {
				commit("SET_EMAIL_REPORTS_SCHEDULE_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			});
	},
	updateEmailReportSchedule: async ({ commit }, payload) => {
		commit("SET_UPDATE_EMAIL_REPORTS_SCHEDULE_LOADING", true);
		await axios
			.post(`email-report-schedule/update`, qs.stringify(payload), {
				headers: { "Content-Type": "application/x-www-form-urlencoded" },
			})
			.then((res) => {
				if (res.status === 200) {
					if (typeof res.data !== "undefined") {
						commit("SET_UPDATE_EMAIL_REPORTS_SCHEDULE", res.data);
					}
				}
				commit("SET_UPDATE_EMAIL_REPORTS_SCHEDULE_LOADING", false);
			})
			.catch((err) => {
				commit("SET_UPDATE_EMAIL_REPORTS_SCHEDULE_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			});
	},
	downloadReport: async ({ commit }, payload) => {
		commit("SET_REPORT_DOWNLOAD_LOADING", true);
		await axios
			.get(`/download-report/${payload}`, { responseType: "blob" })
			.then((res) => {
				var url = window.URL.createObjectURL(
					new Blob([res.data], {
						type: "application/vnd.ms-excel",
					})
				);
				var a = document.createElement("a");
				a.href = url;
				a.setAttribute("download", "report.xls");
				document.body.appendChild(a);
				a.click();
				a.remove();
			})
			.catch((err) => {
				commit("SET_REPORT_DOWNLOAD_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			})
			.finally(() => {
				commit("SET_REPORT_DOWNLOAD_LOADING", false);
			});
	},
	createEmailReportSchedule: async ({ commit }, payload) => {
		commit("SET_CREATE_EMAIL_REPORTS_SCHEDULE_LOADING", true);
		await axios
			.post(`email-report-schedule/new`, qs.stringify(payload), {
				headers: { "Content-Type": "application/x-www-form-urlencoded" },
			})
			.then((res) => {
				if (res.status === 200) {
					if (typeof res.data !== "undefined") {
						commit("SET_CREATE_EMAIL_REPORTS_SCHEDULE", res.data);
					}
				}
				commit("SET_CREATE_EMAIL_REPORTS_SCHEDULE_LOADING", false);
			})
			.catch((err) => {
				commit("SET_CREATE_EMAIL_REPORTS_SCHEDULE_LOADING", false);
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			});
	},

	// new reports
	fetchReportColumns: async ({ commit }) => {
		let attempt = false
		commit("SET_REPORTS_COLUMNS_LOADING", true);

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
				.get(`shipment-fields`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							let reportColumns = res.data;
							commit("SET_REPORTS_COLUMNS", reportColumns);
						}
						commit("SET_REPORTS_COLUMNS_LOADING", false)
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							commit("SET_REPORTS_COLUMNS_LOADING", false)
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_REPORTS_COLUMNS_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	fetchReportsSchedule: async ({ commit }, payload) => {
		let attempt = false
		commit("SET_REPORTS_SCHEDULE_LOADING", true);

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
				.get(`shipment-report/${payload}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							let reportData = res.data;
							let { data } = reportData

							let companySchedules = []
							let personalizedSchedules = []

							if (typeof data !== 'undefined' && data !== null && data.length > 0) {
								data.map(v => {
									v.isSending = false
									v.utcConvertedTime = v.time

									// convert time to local time of user
									var getTime = moment.utc(v.time, "HH:mm:ss")
									var finalTimeConverted = getTime.local().format('HH:mm:ss')
									v.time = finalTimeConverted
									v.currentTime = finalTimeConverted
									
									if (v.report === 1) {
										companySchedules.push(v)
									} else {
										personalizedSchedules.push(v)
									}
								})
							}

							let finalValues = {
								company_schedules: companySchedules,
								personalized_schedules: personalizedSchedules
							}

							commit("SET_REPORTS_SCHEDULE", finalValues);
							// commit("SET_REPORTS_SCHEDULE", reportData);
						}
						commit("SET_REPORTS_SCHEDULE_LOADING", false)
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							commit("SET_REPORTS_SCHEDULE_LOADING", false)
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_REPORTS_SCHEDULE_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	downloadReportSchedule: async ({ commit }, payload) => {
		// let attempt = false
		commit("SET_REPORT_DOWNLOAD_LOADING", true)

		// let current_report_type = payload.report_type === 'BYCONTAINER' ? 'By_Container' : 'By_Reference'

		// await axios
		// .get(`download/email-report/${payload.id}`, { 
		// 	responseType: "blob",
		// 	cancelToken: payload.cancelToken
		// })
		// .then((res) => {
		// 	commit("SET_REPORT_DOWNLOAD_LOADING", false);
		// 	var url = window.URL.createObjectURL(
		// 		new Blob([res.data], {
		// 			type: "application/vnd.ms-excel",
		// 		})
		// 	)
		// 	var a = document.createElement("a")
		// 	a.href = url
		// 	a.setAttribute("download", `Shifl Shipment Report_${current_report_type}_${payload.date}.xlsx`)
		// 	document.body.appendChild(a)
		// 	a.click()
		// 	a.remove()
		// })
		// .catch((err) => {
		// 	commit("SET_REPORT_DOWNLOAD_LOADING", false);
		// 	if (typeof err.error !== "undefined") {
		// 		return Promise.reject(err.error)
		// 	} else {
		// 		return Promise.reject(err.message)
		// 	}
		// })
		// .finally(() => {
		// 	commit("SET_REPORT_DOWNLOAD_LOADING", false)
		// });

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
					.get(`download/email-report/${payload.id}`, {
							responseType: "blob",
							cancelToken: payload.cancelToken
						}
					)
					.then(res => {
						if (res.status === 200) {
							commit("SET_REPORT_DOWNLOAD_LOADING", false)
							commit("SET_REPORT_DATA", res.data)
							resolve("ok")
						}
					})
					.catch(err => {
						if (typeof err.message !== 'undefined') {
							commit('SET_REPORT_DOWNLOAD_LOADING', false)
							reject(err.message)

							// if (!attempt) {
							// 	attempt = true
							// 	let t = setInterval(() => {
							// 		if (!store.getters.getIsRefreshing) {
							// 			proceed()
							// 			clearInterval(t)
							// 		}
							// 	}, 300)
							// } else {
							// 	commit('SET_REPORT_DOWNLOAD_LOADING', false)
							// 	reject(err)
							// }
						}

						if (typeof err.error !== 'undefined') {
							commit('SET_REPORT_DOWNLOAD_LOADING', false)
							reject(err.error)
						}
					})
			}
			proceed()
		})
	},
	createReportSchedule: async ({ commit }, payload) => {
		let attempt = false
		commit("SET_CREATE_EMAIL_REPORTS_SCHEDULE_LOADING", true);

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
				.post(`shipment-report/new`, payload)
				.then(res => {
					if (res.status === 200) {
						commit("SET_CREATE_EMAIL_REPORTS_SCHEDULE_LOADING", false)
					}
					resolve()
				})
				.catch(err => {
					commit("SET_CREATE_EMAIL_REPORTS_SCHEDULE_LOADING", false)

					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {							
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	editReportSchedule: async ({ commit }, payload) => {
		let attempt = false
		commit("SET_UPDATE_EMAIL_REPORTS_SCHEDULE_LOADING", true);

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
				.post(`shipment-report/edit`, payload)
				.then(res => {
					if (res.status === 200) {
						commit("SET_UPDATE_EMAIL_REPORTS_SCHEDULE_LOADING", false)
					}
					resolve()
				})
				.catch(err => {
					commit("SET_UPDATE_EMAIL_REPORTS_SCHEDULE_LOADING", false)

					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	deleteReportScehdule: async({
		commit
	}, reportId) => {
		let attempt = false
		commit("SET_DELETE_REPORT_SCHEDULE", true)
		return new Promise((resolve, reject) => {
			function proceed() {
				axios
				.post(`shipment-report/delete/${reportId}`)
				.then(res => {
					if (res.status === 200) {
						if (res.data) {
							commit("SET_DELETE_REPORT_SCHEDULE", false)
						}
					}
					resolve()
				})
				.catch(err => {
					commit("SET_DELETE_REPORT_SCHEDULE", false)

					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {							
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
	sendReportSchedule: async ({ commit }, payload) => {
		// let attempt = false
		commit("SET_SEND_EMAIL_REPORTS_SCHEDULE_LOADING", true);

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
				.post(`shipment-report/send-report`, payload)
				.then(res => {
					if (res.status === 200) {
						commit("SET_SEND_EMAIL_REPORTS_SCHEDULE_LOADING", false)
					}
					resolve()
				})
				.catch(err => {
					commit("SET_SEND_EMAIL_REPORTS_SCHEDULE_LOADING", false)

					if (typeof err.message!=='undefined') {						
						reject(err.message)
						// if (!attempt) {
						// 	attempt = true
						// 	let t = setInterval(() => {
						// 		if (!store.getters.getIsRefreshing) {
						// 			proceed()
						// 			clearInterval(t)
						// 		}
						// 	}, 300)
						// } else {
						// 	commit("SET_SEND_EMAIL_REPORTS_SCHEDULE_LOADING", false)
						// 	reject(err.message)
						// }
					}

					if (typeof err.error!=='undefined') {
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},	
	updateReportStatus: async ({ commit }, payload) => {
		let attempt = false
		commit("SET_UPDATE_REPORT_STATUS_LOADING", true);

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
				.post(`shipment-report/update/${payload.id}`, payload)
				.then(res => {
					if (res.status === 200) {
						commit("SET_UPDATE_REPORT_STATUS_LOADING", false)
					}
					resolve()
				})
				.catch(err => {
					if (typeof err.message!=='undefined') {
						if (!attempt) {
							attempt = true
							let t = setInterval(() => {
								if (!store.getters.getIsRefreshing) {
									proceed()
									clearInterval(t)
								}
							}, 300)
						} else {
							commit("SET_UPDATE_REPORT_STATUS_LOADING", false)
							reject(err.message)
						}
					}

					if (typeof err.error!=='undefined') {
						commit("SET_UPDATE_REPORT_STATUS_LOADING", false)
						reject(err.error)
					}
				})
			}
			proceed()
		})
	},
};

const mutations = {
	SET_EMAIL_REPORTS_SCHEDULE: (state, payload) => {
		state.emailReports = payload;
	},
	SET_EMAIL_REPORTS_SCHEDULE_LOADING: (state, payload) => {
		state.emailReportsLoading = payload;
	},
	SET_REPORT_DATA: (state, payload) => {
		state.emailReportDownloadData = payload;
	},
	SET_REPORT_DOWNLOAD_LOADING: (state, payload) => {
		state.emailReportDownloadLoading = payload;
	},
	SET_DROPDOWN_DEFAULT_VALUE: (state, payload) => {
		state.defaultValue = payload;
	},
	SET_DROPDOWN_DEFAULT_VALUE_LOADING: (state, payload) => {
		state.defaultValueLoading = payload;
	},
	SET_UPDATE_EMAIL_REPORTS_SCHEDULE: (state, payload) => {
		state.updateEmailReports = payload;
	},
	SET_UPDATE_EMAIL_REPORTS_SCHEDULE_LOADING: (state, payload) => {
		state.updateEmailReportsLoading = payload;
	},
	SET_CREATE_EMAIL_REPORTS_SCHEDULE: (state, payload) => {
		state.createEmailReports = payload;
	},
	SET_CREATE_EMAIL_REPORTS_SCHEDULE_LOADING: (state, payload) => {
		state.createEmailReportsLoading = payload;
	},
	// 
	SET_REPORTS_SCHEDULE: (state, payload) => {
		state.reportsSchedule = payload;
	},
	SET_REPORTS_SCHEDULE_LOADING: (state, payload) => {
		state.reportsScheduleLoading = payload;
	},	
	SET_REPORTS_COLUMNS: (state, payload) => {
		state.reportColumns = payload;
	},
	SET_REPORTS_COLUMNS_LOADING: (state, payload) => {
		state.reportColumnsLoading = payload;
	},
	SET_DELETE_REPORT_SCHEDULE: (state, payload) => {
		state.deleteReportLoading = payload;
	},
	SET_SEND_EMAIL_REPORTS_SCHEDULE_LOADING: (state, payload) => {
		state.sendReportLoading = payload;
	},
	SET_UPDATE_REPORT_STATUS_LOADING: (state, payload) => {
		state.updateStatusLoading = payload;
	},
};

export default {
	state,
	getters,
	actions,
	mutations,
};
