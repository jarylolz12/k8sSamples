/** @format */
import axios from "axios";

const state = {
    notifications: [],
    shouldPushNotify: [],
    notification: null,
    hasNew: false,
    notificationsIsLoading: false
};

const getters = {
    getNotifications: (state) => state.notifications.sort((a,b)=> new Date(b.created_at) - new Date(a.created_at)),
    getHasNew: (state) => state.hasNew,
    getNotificationsIsLoading: (state) => state.notificationsIsLoading,
    getNotification: (state) => state.notification,
    getShouldPushNotify: (state) => state.shouldPushNotify,
};

const actions = {
    fetchNotifications: async ({commit}) => {
        return new Promise((resolve, reject) => {
            commit("SET_NOTIFICATIONS_IS_LOADING",true);
            const fetchPONotifications = axios.get(`${process.env.VUE_APP_PO_URL}/notifications`).then(res => addFromWhichMicroservice(res.data,'PO'))
            Promise.all([fetchPONotifications])
                .then(res => {
                    let allNotifications = [].concat(...res);
                    if (allNotifications.some(notification => notification.read_at === null)) commit("SET_HAS_NEW", true);
                    commit("SET_NOTIFICATIONS", allNotifications)
                    resolve()
                }).catch(() => {reject()})
                .finally(() => {
                    commit("SET_NOTIFICATIONS_IS_LOADING",false);
                })
        })
    },
    fetchPushedNotification: async ({commit, dispatch},payload) => {
        commit('PUSH_SHOULD_PUSH_NOTIFY', payload.id);
        dispatch('fetchNotification', payload);
        commit('SET_HAS_NEW', true);
    },
    fetchNotification({commit}, notification){
        return new Promise((resolve, reject) => {
            commit("SET_NOTIFICATIONS_IS_LOADING",true);
            axios.get(microserviceNotificationURLFactory(notification,"GET_NOTIFICATION")).then(res => {
                res.data.microservice = notification.microservice;
                commit('PUSH_NOTIFICATION', res.data);
                resolve()
            }).catch(() => {reject()})
                .finally(() => {
                    commit("SET_NOTIFICATIONS_IS_LOADING", false);
                })
        })
    },
    markAsRead: async ({commit},payload) => {
        commit("SET_NOTIFICATIONS_IS_LOADING",true);
        return new Promise((resolve, reject) => {
            axios.post(microserviceNotificationURLFactory(payload,"MARK_AS_READ"))
                .then(res => {
                    commit('MARK_AS_READ',res.data)
                    resolve()
                })
                .catch((e) => {reject(e)})
                .finally(() => {
                    commit("SET_NOTIFICATIONS_IS_LOADING",false);
                })
        })
    },
    markAllAsRead: async ({commit,dispatch}) => {
        axios.post(`${process.env.VUE_APP_PO_URL}/notifications/markAllAsRead`)
            .then(res => {
                commit('MARK_AS_READ',res.data)
                dispatch("fetchNotifications")
            })
    }
}

const mutations = {
    SET_NOTIFICATIONS: (state, payload) => {
        state.notifications = payload;
    },
    SET_NOTIFICATIONS_IS_LOADING: (state, payload) => {
        state.notificationsIsLoading = payload;
    },
    PUSH_NOTIFICATION: (state, payload) => {
        state.notifications.push(payload);
    },
    SET_HAS_NEW: (state, payload) => {
        state.hasNew = payload;
    },
    PUSH_SHOULD_PUSH_NOTIFY: (state, payload) => {
        state.shouldPushNotify.push(payload);
    },
    REMOVE_FROM_SHOULD_NOTIFY: (state, payload) => {
        state.shouldPushNotify = state.shouldPushNotify.filter(id => id !== payload);
    },
    MARK_AS_READ: (state, payload) => {
        const notif = state.notifications.find(n => n.id === payload.id);
        if (notif) notif.read_at = payload.read_at;
    },
}

function addFromWhichMicroservice(notifications,ms){
    notifications.map(notification => {
        notification.microservice = ms
        return notification
    })
    return notifications;
}

function microserviceNotificationURLFactory(notification,type){
    if (notification.microservice === "PO" && type === "GET_NOTIFICATION") return `${process.env.VUE_APP_PO_URL}/notifications/${notification.id}`;
    if (notification.microservice === "PO" && type === "MARK_AS_READ") return `${process.env.VUE_APP_PO_URL}/notifications/markAsRead/${notification.id}`;
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};