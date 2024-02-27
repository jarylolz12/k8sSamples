/** @format */
import axios from "axios";
import moment from "moment";

const state = {
    customerAdmins: [],
    customerAdminsLoading: false,
    usersLocalQuery: "",
    userExistsStatus: {},
    userExistsStatusLoading: false,
    userSignUp: {},
    userSignUpLoading: false,
    userRegisterCompletedStatus: {},
    userRegisterCompletedStatusLoading: false,
};

const getters = {
    getCustomerAdmins: (state) => state.customerAdmins,
    getCustomerAdminsLoading: (state) => state.customerAdminsLoading,
    getUsersLocalQuery: (state) => state.usersLocalQuery,
    getUserExistsStatus: (state) => state.userExistsStatus,
    getUserExistsStatusLoading: (state) => state.userExistsStatusLoading,
    getUserSignUp: (state) => state.userSignUp,
    getUserSignUpLoading: (state) => state.userSignUpLoading,
    getUserRegisterCompletedStatus: (state) => state.userRegisterCompletedStatus,
    getUserRegisterCompletedStatusLoading: (state) => state.userRegisterCompletedStatusLoading,
};

const actions = {
    fetchUsersDetail: async({ commit }, payload) => {
        commit("SET_CUSTOMER_ADMINS_LOADING", true);
        await axios
            .get(`customer-admins/${payload.customer_id}`)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let userDetail = res.data.data;
                        for (let i = 0; i < userDetail.length; i++) {
                            userDetail[i].created_at = moment
                                .utc(userDetail[i].created_at)
                                .format(" MMM DD, YYYY");
                            userDetail[i].updated_at = moment(
                                userDetail[i].updated_at
                            ).calendar(null, {
                                sameDay: "[Today]",
                                sameElse: "MMM DD, YYYY",
                            });
                        }
                        commit("SET_CUSTOMER_ADMINS", userDetail);
                    }
                }
                commit("SET_CUSTOMER_ADMINS_LOADING", false);
            })
            .catch((err) => {
                commit("SET_CUSTOMER_ADMINS_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            });
    },
    setUsersLocalQuery: ({ commit }, payload) => {
        commit("SET_USERS_LOCAL_QUERY", payload);
    },
    fetchUserExistsStatus: async({ commit }, payload) => {
        commit("SET_USER_EXISTS_STATUS_LOADING", true);
        await axios
            .get(`users/${payload.email}/exists`)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let userExistsData = res.data;
                        commit("SET_USER_EXISTS_STATUS", userExistsData);
                    }
                }
                commit("SET_USER_EXISTS_STATUS_LOADING", false);
            })
            .catch((err) => {
                commit("SET_USER_EXISTS_STATUS_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            });
    },
    fetchUserRegisterCompletedStatus: async({ commit }, payload) => {
        commit("SET_USER_REGISTER_COMPLETED_LOADING", true);
        await axios
            .get(`/users/user-register-completed/${payload.email}`)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let userExistsData = res.data;
                        commit("SET_USER_REGISTER_COMPLETED_STATUS", userExistsData);
                    }
                }
                commit("SET_USER_REGISTER_COMPLETED_LOADING", false);
            })
            .catch((err) => {
                commit("SET_USER_REGISTER_COMPLETED_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    return Promise.reject(err.message);
                }
            });
    },
    sendUserSignUp: async({ commit }, payload) => {
        commit("SET_USER_SIGN_UP_LOADING", true);
        await axios
            .post('register', payload)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let data = res.data;
                        commit("SET_USER_SIGN_UP", data);
                    }
                }
                commit("SET_USER_SIGN_UP_LOADING", false);
            })
            .catch((err) => {
                commit("SET_USER_SIGN_UP_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    if(err.response.data){
                        return Promise.reject(err.response.data);
                    } else {
                        return Promise.reject(err.message);
                    }
                }
            });
    },
    sendUserSignUpForInviteUser: async({ commit }, payload) => {
        commit("SET_USER_SIGN_UP_LOADING", true);
        await axios
            .post('register-invite-user', payload)
            .then((res) => {
                if (res.status === 200) {
                    if (typeof res.data !== "undefined") {
                        let data = res.data;
                        localStorage.removeItem('redirectPath');
                        commit("SET_USER_SIGN_UP", data);
                    }
                }
                commit("SET_USER_SIGN_UP_LOADING", false);
            })
            .catch((err) => {
                commit("SET_USER_SIGN_UP_LOADING", false);
                if (typeof err.error !== "undefined") {
                    return Promise.reject(err.error);
                } else {
                    if(err.response.data){
                        return Promise.reject(err.response.data);
                    } else {
                        return Promise.reject(err.message);
                    }
                }
            });
    }
};

const mutations = {
    SET_CUSTOMER_ADMINS: (state, payload) => {
        state.customerAdmins = payload;
    },
    SET_CUSTOMER_ADMINS_LOADING: (state, payload) => {
        state.customerAdminsLoading = payload;
    },
    SET_USERS_LOCAL_QUERY: (state, payload) => {
        state.usersLocalQuery = payload;
    },
    SET_USER_EXISTS_STATUS: (state, payload) => {
        state.userExistsStatus = payload;
    },
    SET_USER_EXISTS_STATUS_LOADING: (state, payload) => {
        state.userExistsStatusLoading = payload;
    },
    SET_USER_SIGN_UP: (state, payload) => {
        state.userSignUp = payload;
    },
    SET_USER_SIGN_UP_LOADING: (state, payload) => {
        state.userSignUpLoading = payload;
    },
    SET_USER_REGISTER_COMPLETED_STATUS: (state, payload) => {
        state.userRegisterCompletedStatus = payload;
    },
    SET_USER_REGISTER_COMPLETED_LOADING: (state, payload) => {
        state.userRegisterCompletedStatusLoading = payload;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};