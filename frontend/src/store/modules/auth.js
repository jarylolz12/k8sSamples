/** @format */

// import axios from 'axios'

import axios from "axios";
import router from "../../router/index";
import store from "../";
import _ from "lodash";

const state = {
	specialDialog: false,
	user_token: localStorage.getItem("user_token") || "",
	refresh_token: localStorage.getItem("shifl.refresh_token") || "",
	expiresAt: localStorage.getItem("expiresAt") || "",
	user:
		localStorage.getItem("shifl.user_details") !== null
			? JSON.parse(localStorage.getItem("shifl.user_details"))
			: null,
	errors: "",
	forgetPasswordLoading: false,
	loadingUserDetails: true,
	resetPasswordLoading: false,
	notificationTokenLoading: false,
	resetPasswordGlobalLoading: false,
	isRefreshing: false,
	customerPreferenceLoading: false,
};

const getters = {
	/*// <<<<<<< HEAD
    //  getUser: (state) => state.user,
    //  getUserToken: state => state.user_token,
    //  getExpiresAt: state => state.expiresAt
    // }
    //
    // const actions = {
    //  async login({
    //      commit,
    //      dispatch
    //  }, payload) {
    //      // console.log("hello")
    //      await axios.post('/login', payload, {
    //              withCredentials: true,
    //              credentials: 'include',
    //          })
    //          .then(res => {
    //              // console.log(res.status)
    //              if (res.status === 200) {
    //                  // console.log("here")
    //                  const expiresAt = new Date(new Date()
    //                      .getTime() + (res.data.expiresIn - 2) * 1000)
    //                  localStorage.setItem("user_token", res.data.token)
    //
    //                  // optional
    //                  localStorage.setItem("expiresAt", expiresAt)
    //                  commit("SET_TOKEN", {
    //                      token: res.data.token,
    //                      expiresAt: expiresAt
    //                  })
    //
    //                  // fetch user
    //                  dispatch("fetchUser")
    //                      .then(res => {
    //                          if (res.status === 200) {
    //                              router.push('/')
    //                          }
    //                      })
    //
    //
    //
    //              }
    //          })
    //          .catch(err => console.log(err))
    //  },
    //  async logout({
    //      commit
    //  }) {
    //      localStorage.removeItem("user_token")
    //      localStorage.removeItem("expiresAt")
    //
    //      commit("SET_TOKEN", {
    //          token: '',
    //          expiresAt: ''
    //      })
    //      commit("SET_USER", null)
    //
    //      //
    //      router.push({
    //              name: "Login"
    //          })
    //          .catch(err => {
    //              // Ignore the vuex err regarding  navigating to the page they are already on.
    //              if (
    //                  err.name !== 'NavigationDuplicated' &&
    //                  !err.message.includes('Avoided redundant navigation to current location')
    //              ) {
    //                  // But print any other errors to the console
    //                  console.log(err);
    //              }
    //          });
    //
    //  },
    //  fetchUser: ({
    //      commit
    //  }) => {
    //
    //      return new Promise((resolve, reject) => {
    //          axios.post('/details')
    //              .then(res => {
    //                  if (res) {
    //
    //                      if (res.status === 200) {
    //                          commit("SET_USER", res.data)
    //                          resolve(res)
    //                      }
    //                  }
    //              })
    //              .catch(err => {
    //                  // console.log(err)
    //                  reject(err)
    //              })
    //      })
    //  },
    //
    //
    //
    //  refreshToken: async ({
    //      commit,
    //      dispatch
    //  }) => {
    //      return new Promise((resolve, reject) => {
    //
    //          axios.defaults.withCredentials = true
    //
    //          axios.post(`/refresh-token`, {}, {
    //                  withCredentials: true,
    //                  credentials: 'include',
    //              })
    //              .then(res => {
    //                  if (res.status === 200) {
    //                      // console.log("here")
    //                      const expiresAt = new Date(new Date()
    //                          .getTime() + (res.data.expiresIn - 2) * 1000)
    //
    //                      localStorage.setItem("user_token", res.data.token)
    //                      localStorage.setItem("expiresAt", expiresAt) // optional
    //
    //
    //                      commit("SET_TOKEN", {
    //                          token: res.data.token,
    //                          expiresAt: expiresAt
    //                      })
    //
    //                      // fetch user
    //                      dispatch("fetchUser")
    //
    //                  }
    //                  resolve(res);
    //              })
    //              .catch(error => {
    //                  reject(error);
    //              });
    //      })
    //  }
    // }
    // const mutations = {
    //  SET_TOKEN: (state, payload) => {
    //      state.user_token = payload.token
    //      state.expiresAt = payload.expiresAt
    //  },
    //  SET_USER: (state, payload) => {
    //      state.user = payload
    //  }
    // =======*/
	getSpecialDialog: (state) => state.specialDialog,
	getUser: (state) => state.user,
	getUserToken: (state) => state.user_token,
	getRefreshToken: (state) => state.refresh_token,
	getIsRefreshing: (state) => state.isRefreshing,
	getExpiresAt: (state) => state.expiresAt,
	getErrorMessage: (state) => state.errors,
	getforgetPasswordLoading: (state) => state.forgetPasswordLoading,
	getResetPasswordLoading: (state) => state.resetPasswordLoading,
	getResetPasswordGlobalLoading: (state) => state.resetPasswordGlobalLoading,
	getCustomerPreferenceLoading: (state) => state.customerPreferenceLoading,
	getNotificationTokenLoading: (state) => state.notificationTokenLoading,
	getLoadingUserDetails: (state) => state.loadingUserDetails,
};

const actions = {
	closeSpecialDialog({ commit }) {
		commit("SET_SPECIAL_DIALOG", false);
	},
	openSpecialDialog({ commit }) {
		commit("SET_SPECIAL_DIALOG", true);
	},
	async updateNotificationToken({ commit }, payload) {
		commit("SET_NOTIFICATION_TOKEN_LOADING", true);

		return new Promise((resolve, reject) => {
			axios
				.put("/v2/update-notification-token", {
					notification_token: payload,
				})
				.then((res) => {
					commit("SET_NOTIFICATION_TOKEN_LOADING", false);
					resolve(res);
				})
				.catch((err) => {
					commit("SET_NOTIFICATION_TOKEN_LOADING", false);
					reject(err);
				});
		});
	},
	async updateCustomerPreference({ commit }, payload) {
		let attempt = false;
		commit("SET_CUSTOMER_PREFERENCE_LOADING", true);
		return new Promise((resolve, reject) => {
			function proceed() {
				axios
					.post("/v2/update-customer-preference", {
						customer_id: payload,
					})
					.then((res) => {
						commit("SET_CUSTOMER_PREFERENCE_LOADING", false);
						if (res.status === 200) {
							resolve(res.data);
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
								commit("SET_CUSTOMER_PREFERENCE_LOADING", false);
								reject("Token has been expired. Please reload the page.");
							}
						}

						if (typeof err.error !== "undefined") {
							commit("SET_CUSTOMER_PREFERENCE_LOADING", false);
							reject(err.error);
						}
					});
			}
			proceed();
		});
	},
	async checkForgotPasswordCode({ commit }, payload) {
		return new Promise((resolve, reject) => {
			commit("SET_RESET_PASSWORD_GLOBAL_LOADING", true);
			axios
				.get(`/check-forgot-password-code?code=${payload}`)
				.then((res) => {
					commit("SET_RESET_PASSWORD_GLOBAL_LOADING", false);
					if (res.status === 200) {
						if (typeof res.data !== "undefined") resolve(res.data);
						else reject("not ok");
					} else {
						reject("not ok");
					}
				})
				.catch((err) => {
					console.log(err);
					commit("SET_RESET_PASSWORD_GLOBAL_LOADING", false);
					reject(err);
				});
		});
	},
	async forgetPassword({ commit }, payload) {
		return new Promise((resolve, reject) => {
			commit("SET_FORGET_PASSWORD_LOADING", true);
			axios
				.post("/forgot-password", payload)
				.then((res) => {
					commit("SET_FORGET_PASSWORD_LOADING", false);
					if (res.status === 200) {
						if (typeof res.data !== "undefined") resolve(res.data);
						else reject("not ok");
					} else {
						reject("not ok");
					}
				})
				.catch((err) => {
					console.log(err);
					commit("SET_FORGET_PASSWORD_LOADING", false);
					reject(err);
				});
		});
	},
	async resetPassword({ commit }, payload) {
		return new Promise((resolve, reject) => {
			commit("SET_RESET_PASSWORD_LOADING", true);
			axios
				.post("/change-password", payload)
				.then((res) => {
					commit("SET_RESET_PASSWORD_LOADING", false);
					if (res.status === 200) {
						if (typeof res.data !== "undefined") {
							resolve(res.data);
						} else reject("not ok");
					} else {
						reject("not ok");
					}
				})
				.catch((err) => {
					console.log(err);
					commit("SET_RESET_PASSWORD_LOADING", false);
					reject(err);
				});
		});
	},
	async login({ commit, dispatch }, payload) {
		commit("SET_ERROR_MESSAGE", "");
		await axios
			.post("/login", payload, {
				withCredentials: true,
				credentials: "include",
			})
			.then((res) => {
				if (res.status === 200) {
					const expiresAt = new Date(
						new Date().getTime() + (res.data.expiresIn - 2) * 1000
					);
					localStorage.setItem("user_token", res.data.token);
					if (typeof res.data.refresh_token !== "undefined")
						localStorage.setItem("shifl.refresh_token", res.data.refresh_token);

					// optional
					localStorage.setItem("expiresAt", expiresAt);
					commit("SET_TOKEN", {
						token: res.data.token,
						expiresAt: expiresAt,
					});
					// check if the remember me is ticked
					if (typeof payload.checkbox !== "undefined" && payload.checkbox) {
						localStorage.setItem("shifl.user_rememberme_email", payload.email);
					} else {
						localStorage.removeItem("shifl.user_rememberme_email");
					}
					// fetch user
					dispatch("fetchUser").then((res) => {
						if (res.status === 200) {
							if (payload.redirectTo !== "") {
								window.location.href =
									window.location.origin + payload.redirectTo;
							} else {
								const redirectPath = localStorage.getItem("redirectPath") || "";
								if (redirectPath) {
									router.push(`${redirectPath}`);
								} else {
									router.push("/");
								}
							}
						}
					});
				}
			})
			.catch((err) => {
				let errorMessage =
					typeof err.response.data !== "undefined" &&
					typeof err.response.data.message !== "undefined"
						? err.response.data.message
						: "Wrong username or password.";
				errorMessage = "Wrong username or pssword.";
				commit("SET_ERROR_MESSAGE", errorMessage);
			});
	},
	async logout({ commit }) {
		localStorage.removeItem("user_token");
		localStorage.removeItem("expiresAt");
		localStorage.removeItem("shifl.user_details");
		localStorage.removeItem("shifl.refresh_token");
		localStorage.removeItem("redirectPath");

		commit("SET_TOKEN", {
			token: "",
			expiresAt: "",
			refresh_token: "",
		});

		commit("SET_USER", null);
		//
		router
			.push({
				name: "Login",
			})
			.catch((err) => {
				// Ignore the vuex err regarding  navigating to the page they are already on.
				if (
					err.name !== "NavigationDuplicated" &&
					!err.message.includes(
						"Avoided redundant navigation to current location"
					)
				) {
					// But print any other errors to the console
					console.log(err);
				}
			});
	},
	checkCurrentAccount: ({ commit }) => {
		return new Promise((resolve, reject) => {
			axios
				.post("/details")
				.then((res) => {
					if (res) {
						if (res.status === 200) {
							if (res.data) {
								localStorage.setItem(
									"shifl.user_details",
									JSON.stringify(res.data.success)
								);

								let userDetails = JSON.parse(
									localStorage.getItem("shifl.user_details")
								);
								let getCustomers =
									typeof userDetails.customers_api !== "undefined"
										? userDetails.customers_api
										: [];
								if (
									typeof getCustomers !== "undefined" &&
									getCustomers.length > 0
								) {
									if (
										typeof userDetails.default_customer_id !== "undefined" &&
										userDetails.default_customer_id !== null
									) {
										let findCustomer = _.find(getCustomers, {
											id: userDetails.default_customer_id,
										});

										if (typeof findCustomer == "undefined") {
											axios
												.post("/v2/update-customer-preference", {
													customer_id: getCustomers[0].id,
												})
												.then(() => {
													commit("SET_SPECIAL_DIALOG", true);
													commit("SET_LOADING_USER_DETAILS", false);

													setTimeout(() => {
														let currentUrl = window.location.pathname;
														window.location.href = currentUrl;
													}, 5000);
												})
												.catch((errr) => {
													console.log("error", errr);
												});
										} else {
											commit(
												"SET_USER",
												localStorage.getItem("shifl.user_details")
											);
										}
									}
								}
							}
							//commit("SET_USER", res.data.success)
							resolve(res);
						}
					}
				})
				.catch((err) => {
					commit("SET_LOADING_USER_DETAILS", false);
					// console.log(err)
					reject(err);
				});
		});
	},
	fetchUser: ({ commit }) => {
		commit("SET_LOADING_USER_DETAILS", true);
		return new Promise((resolve, reject) => {
			axios
				.post("/details")
				.then((res) => {
					if (res) {
						if (res.status === 200) {
							if (res.data) {
								localStorage.setItem(
									"shifl.user_details",
									JSON.stringify(res.data.success)
								);
								commit("SET_USER", localStorage.getItem("shifl.user_details"));
								commit("SET_LOADING_USER_DETAILS", false);
								let userDetails = JSON.parse(
									localStorage.getItem("shifl.user_details")
								);
								let getCustomers =
									typeof userDetails.customers_api !== "undefined"
										? userDetails.customers_api
										: [];
								if (typeof getCustomers !== "undefined") {
									if (typeof userDetails.default_customer_id !== "undefined") {
										let findCustomer = _.find(getCustomers, {
											id: userDetails.default_customer_id,
										});

										if (
											typeof findCustomer == "undefined" &&
											getCustomers.length > 0
										) {
											axios
												.post("/v2/update-customer-preference", {
													customer_id: getCustomers[0].id,
												})
												.then(() => {
													commit("SET_SPECIAL_DIALOG", true);
													commit("SET_LOADING_USER_DETAILS", false);
													setTimeout(() => {
														let currentUrl = window.location.pathname;
														window.location.href = currentUrl;
													}, 6000);
												})
												.catch((errr) => {
													commit("SET_LOADING_USER_DETAILS", false);
													console.log("error", errr);
												});
										}
									}
								}
							}
							//commit("SET_USER", res.data.success)
							resolve(res);
						}
					}
				})
				.catch((err) => {
					commit("SET_LOADING_USER_DETAILS", false);
					// console.log(err)
					reject(err);
				});
		});
	},
	refreshToken: async ({ commit, dispatch }) => {
		return new Promise((resolve, reject) => {
			axios.defaults.withCredentials = true;

			commit("SET_IS_REFRESHING", true);
			//set default post data
			let extra = {
				withCredentials: true,
				credentials: "include",
			};
			//get refresh token if there is set
			let get_refresh_token = localStorage.getItem("shifl.refresh_token");
			let additionalData = {};

			//if not null pass as part of post data
			if (get_refresh_token !== null && get_refresh_token !== "")
				additionalData["refresh_token"] = get_refresh_token;

			//do refresh token request now
			axios
				.post(`/refresh-token`, additionalData, extra)
				.then((res) => {
					if (res.status === 200) {
						const expiresAt = new Date(
							new Date().getTime() + (res.data.expiresIn - 2) * 1000
						);
						localStorage.setItem("user_token", res.data.token);
						localStorage.setItem("expiresAt", expiresAt); // optional

						//set default token object
						let set_token_data = {
							token: res.data.token,
							expiresAt,
						};
						//replace refresh token also with new token
						if (typeof res.data.refresh_token !== "undefined") {
							localStorage.setItem(
								"shifl.refresh_token",
								res.data.refresh_token
							);
							set_token_data["refresh_token"] = res.data.refresh_token;
						}
						commit("SET_TOKEN", set_token_data);
						commit("SET_IS_REFRESHING", false);
						// fetch user
						dispatch("fetchUser");
					} else {
						commit("SET_IS_REFRESHING", false);
					}
					resolve(res);
				})
				.catch((error) => {
					commit("SET_IS_REFRESHING", false);
					reject(error);
				});
		});
	},
};
const mutations = {
	SET_LOADING_USER_DETAILS: (state, payload) => {
		state.loadingUserDetails = payload;
	},
	SET_SPECIAL_DIALOG: (state, payload) => {
		state.specialDialog = payload;
	},
	SET_CUSTOMER_PREFERENCE_LOADING: (state, payload) => {
		state.customerPreferenceLoading = payload;
	},
	SET_IS_REFRESHING: (state, payload) => {
		state.isRefreshing = payload;
	},
	SET_RESET_PASSWORD_GLOBAL_LOADING: (state, payload) => {
		state.resetPasswordGlobalLoading = payload;
	},
	SET_FORGET_PASSWORD_LOADING: (state, payload) => {
		state.forgetPasswordLoading = payload;
	},
	SET_RESET_PASSWORD_LOADING: (state, payload) => {
		state.resetPasswordLoading = payload;
	},
	SET_TOKEN: (state, payload) => {
		state.user_token = payload.token;
		state.expiresAt = payload.expiresAt;

		if (typeof payload.refresh_token !== "undefined")
			state.refresh_token = payload.refresh_token;
	},
	SET_USER: (state, payload) => {
		state.user = payload;
	},
	SET_NOTIFICATION_TOKEN_LOADING: (state, payload) => {
		state.notificationTokenLoading = payload;
	},
	SET_ERROR_MESSAGE: (state, payload) => {
		state.errors = payload;
	},
};

export default {
	state,
	getters,
	actions,
	mutations,
};
