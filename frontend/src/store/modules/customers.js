/** @format */

import axios from "axios";
import store from "..";
import qs from "qs";

const state = {
	customer_data: false,
	customers: [],
	customersLoading: true,
	createCustomer: null,
	createCustomerLoading: false,
	updateCustomer: null,
	updateCustomerProfileInfo: null,
	updateCustomerLoading: false,
	deleteCustomerLoading: false,
	sendInvitationCustomer: null,
	sendInvitationCustomerLoading: false,
	customersSearched: [],
	customersSearchedLoading: false,
	updateCustomerProfileInfoLoading: false,
	sendUserInvitationForCompanyLoading: false,
	sendUserInvitationForCompany: {},
	reSendUserInvitationForCompanyLoading: false,
	reSendUserInvitationForCompany: {},
	userInviteConfirm: {},
	userInviteConfirmLoading: false,
	requestLoginCredentialsLoading: false,
};

const getters = {
	getCustomerData: (state) => state.customer_data,
	getCustomers: (state) => state.customers,
	getCustomersLoading: (state) => state.customersLoading,
	getCreateCustomers: (state) => state.createCustomer,
	getCreateCustomersLoading: (state) => state.createCustomerLoading,
	getUpdateCustomers: (state) => state.updateCustomer,
	getUpdateCustomerProfileInfo: (state) => state.updateCustomerProfileInfo,
	getUpdateCustomersLoading: (state) => state.updateCustomerLoading,
	getDeleteCustomersLoading: (state) => state.deleteCustomerLoading,
	getSendInvitationCustomerLoading: (state) =>
		state.sendInvitationCustomerLoading,
	getSearchedCustomer: (state) => state.customersSearched,
	getSearchedCustomerLoading: (state) => state.customersSearchedLoading,
	getUpdateCustomerProfileInfoLoading: (state) =>
		state.updateCustomerProfileInfoLoading,
	getUserInvitationForCompany: (state) => state.sendUserInvitationForCompany,
	getSendUserInvitationForCompanyLoading: (state) =>
		state.sendUserInvitationForCompanyLoading,
	getReSendUserInvitationForCompany: (state) =>
		state.reSendUserInvitationForCompany,
	getReSendUserInvitationForCompanyLoading: (state) =>
		state.reSendUserInvitationForCompanyLoading,
	getUserInviteConfirm: (state) => state.userInviteConfirm,
	getUserInviteConfirmLoading: (state) => state.userInviteConfirmLoading,
	getRequestLoginCredentialsLoading: (state) =>
		state.requestLoginCredentialsLoading,
};

const actions = {
	fetchCustomers: async ({ commit }, payload) => {
		commit("SET_CUSTOMERS_LOADING", true);
		let attempt = false;

		return new Promise((resolve, reject) => {
			function proceed() {
				axios
					.get(
						// `/buyers?page=${payload.pageNumber}&per_page=${payload.itemsPerPage}`
						`v2/buyers?page=${payload.pageNumber}`
					)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_CUSTOMERS_LOADING", false);
								commit("SET_CUSTOMERS", res.data);
								resolve();
							}
						}
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_CUSTOMERS_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	createCustomers: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_CREATE_CUSTOMERS_LOADING", true);

				axios
					.post(`/buyers`, payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_CREATE_CUSTOMERS_LOADING", false);
								commit("SET_CREATE_CUSTOMERS", res.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_CREATE_CUSTOMERS_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_CREATE_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	updateCustomers: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_UPDATE_CUSTOMERS_LOADING", true);

				axios
					.put(`/buyers/${payload.id}`, payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_UPDATE_CUSTOMERS_LOADING", false);
								commit("SET_UPDATE_CUSTOMERS", res.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_UPDATE_CUSTOMERS_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_UPDATE_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	updateCustomerInfo: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_UPDATE_CUSTOMER_PROFILE_INFO_LOADING", true);

				axios
					.put(`/customers/profile/${payload.id}`, payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_UPDATE_CUSTOMER_PROFILE_INFO_LOADING", false);
								commit("SET_UPDATE_CUSTOMER_PROFILE_INFO", res.data.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_UPDATE_CUSTOMER_PROFILE_INFO_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_UPDATE_CUSTOMER_PROFILE_INFO_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	deleteCustomers: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_DELETE_CUSTOMERS_LOADING", true);

				axios
					.delete(`/buyers/${payload}`)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_DELETE_CUSTOMERS_LOADING", false);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_DELETE_CUSTOMERS_LOADING", false);
								reject(err.message);
								// reject("Token has been expired. Please reload the page.");
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_DELETE_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	sendInvitationCustomer: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_SEND_INVITATION_CUSTOMERS_LOADING", true);

				axios
					.post(`/send-buyer-invitation`, qs.stringify(payload), {
						headers: { "Content-Type": "application/x-www-form-urlencoded" },
					})
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_SEND_INVITATION_CUSTOMERS_LOADING", false);
								commit("SET_SEND_INVITATION_CUSTOMERS", res.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_SEND_INVITATION_CUSTOMERS_LOADING", false);
								// reject("Token has been expired. Please reload the page.");
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_SEND_INVITATION_CUSTOMERS_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	fetchSearchedCustomers: async ({ commit }, payload) => {
		commit("SET_CUSTOMERS_SEARCHED_LOADING", true);
		let attempt = false;

		return new Promise((resolve, reject) => {
			function proceed() {
				axios(payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								let finalData = {
									data: [],
									current_page: 1,
									total: 0,
									last_page: 0,
									old_page: 1,
									per_page: 35,
								};

								finalData = {
									data: res.data.data,
									current_page: res.data.current_page,
									total: res.data.total,
									old_page: res.data.current_page,
									last_page: res.data.last_page,
									per_page: res.data.per_page,
									tab: payload.tab,
								};

								commit("SET_CUSTOMERS_SEARCHED_LOADING", false);
								commit("SET_CUSTOMERS_SEARCHED", finalData);
								resolve();
							}
						}
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_CUSTOMERS_SEARCHED_LOADING", false);
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_CUSTOMERS_SEARCHED_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	setSearchedCustomerLoading({ commit }, payload) {
		commit("SET_CUSTOMERS_SEARCHED_LOADING", payload);
	},
	setSearchedCustomerVal({ commit }, payload) {
		commit("setSearchedCustomerVal", payload);
	},
	sendUserInvitationForCompany: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_USER_INVITATION_COMPANY_LOADING", true);

				axios
					.post(`/customer/inviteUser`, payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_USER_INVITATION_COMPANY_LOADING", false);
								commit("SET_USER_INVITATION_COMPANY", res.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_USER_INVITATION_COMPANY_LOADING", false);
								commit("SET_USER_INVITATION_COMPANY", {
									success: false,
									message: err.message,
								});
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_USER_INVITATION_COMPANY_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	sendUserInviteConfirm: async ({ commit }, payload) => {
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_USER_INVITE_CONFIRM_LOADING", true);

				axios
					.post(`/customer/invite-user-confirm`, payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_USER_INVITE_CONFIRM_LOADING", false);
								commit("SET_USER_INVITE_CONFIRM", res.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						commit("SET_USER_INVITE_CONFIRM_LOADING", false);
						reject(err.message);
						if (typeof err.error !== "undefined") {
							commit("SET_USER_INVITE_CONFIRM_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	reSendUserInvitationForCompany: async ({ commit }, payload) => {
		let attempt = false;
		return new Promise((resolve, reject) => {
			function proceed() {
				commit("SET_RESEND_USER_INVITATION_COMPANY_LOADING", true);

				axios
					.post(`/customer/reSendInviteUser`, payload)
					.then((res) => {
						if (res.status === 200) {
							if (res.data) {
								commit("SET_RESEND_USER_INVITATION_COMPANY_LOADING", false);
								commit("SET_RESEND_USER_INVITATION_COMPANY", res.data);
							}
						}
						resolve();
					})
					.catch((err) => {
						if (typeof err.message !== "undefined") {
							if (!attempt) {
								attempt = true;
								let t = setInterval(() => {
									if (!store.getters.getIsRefreshing) {
										proceed();
										clearInterval(t);
									}
								}, 300);
							} else {
								commit("SET_RESEND_USER_INVITATION_COMPANY_LOADING", false);
								commit("SET_RESEND_USER_INVITATION_COMPANY", {
									success: false,
									message: err.message,
								});
								reject(err.message);
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_RESEND_USER_INVITATION_COMPANY_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	requestLoginCredentials: async ({ commit }, payload) => {
		commit("SET_REQUEST_LOGIN_CREDENTIALS_LOADING", true);
		return await axios
			.post("/customer/request-login-credentials", payload)
			.then((res) => res)
			.catch((err) => {
				if (typeof err.error !== "undefined") {
					return Promise.reject(err.error);
				} else {
					return Promise.reject(err.message);
				}
			})
			.finally(() => {
				commit("SET_REQUEST_LOGIN_CREDENTIALS_LOADING", false);
			});
	},
};

const mutations = {
	SET_CUSTOMER_DATA: (state, payload) => {
		state.customer_data = payload;
	},
	SET_CUSTOMERS: (state, payload) => {
		state.customers = payload;
	},
	SET_CUSTOMERS_LOADING: (state, payload) => {
		state.customersLoading = payload;
	},
	SET_CREATE_CUSTOMERS: (state, payload) => {
		state.createCustomer = payload;
	},
	SET_CREATE_CUSTOMERS_LOADING: (state, payload) => {
		state.createCustomerLoading = payload;
	},
	SET_UPDATE_CUSTOMERS: (state, payload) => {
		state.updateCustomer = payload;
	},
	SET_UPDATE_CUSTOMER_PROFILE_INFO: (state, payload) => {
		state.updateCustomerProfileInfo = payload;
	},
	SET_UPDATE_CUSTOMERS_LOADING: (state, payload) => {
		state.updateCustomerLoading = payload;
	},
	SET_UPDATE_CUSTOMER_PROFILE_INFO_LOADING: (state, payload) => {
		state.updateCustomerProfileInfoLoading = payload;
	},
	SET_DELETE_CUSTOMERS_LOADING: (state, payload) => {
		state.deleteCustomerLoading = payload;
	},
	SET_SEND_INVITATION_CUSTOMERS: (state, payload) => {
		state.sendInvitationCustomer = payload;
	},
	SET_SEND_INVITATION_CUSTOMERS_LOADING: (state, payload) => {
		state.sendInvitationCustomerLoading = payload;
	},
	SET_CUSTOMERS_SEARCHED: (state, payload) => {
		state.customersSearched = payload;
	},
	SET_CUSTOMERS_SEARCHED_LOADING: (state, payload) => {
		state.customersSearchedLoading = payload;
	},
	setSearchedCustomerVal: (state, payload) => {
		let locationDefaultData = {
			currentTab: 1,
			current_page: 1,
			old_page: 1,
			total: 0,
			per_page: 0,
			data: payload,
		};

		state.customersSearched = locationDefaultData;
	},
	SET_USER_INVITATION_COMPANY_LOADING: (state, payload) => {
		state.sendUserInvitationForCompanyLoading = payload;
	},
	SET_USER_INVITATION_COMPANY: (state, payload) => {
		state.sendUserInvitationForCompany = payload;
	},
	SET_RESEND_USER_INVITATION_COMPANY_LOADING: (state, payload) => {
		state.reSendUserInvitationForCompanyLoading = payload;
	},
	SET_RESEND_USER_INVITATION_COMPANY: (state, payload) => {
		state.reSendUserInvitationForCompany = payload;
	},
	SET_USER_INVITE_CONFIRM_LOADING: (state, payload) => {
		state.userInviteConfirmLoading = payload;
	},
	SET_USER_INVITE_CONFIRM: (state, payload) => {
		state.userInviteConfirm = payload;
	},
	SET_REQUEST_LOGIN_CREDENTIALS_LOADING: (state, payload) => {
		state.requestLoginCredentialsLoading = payload;
	},
};

export default {
	namespaced: true,
	state,
	mutations,
	actions,
	getters,
};
