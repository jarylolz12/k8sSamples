import axios from "axios"
import store from '../'
const state = {
    createGroupType:'UsePredefinedGroup',
    UserAndUserGroupActveTab:0,
    addGroup:[],
    addGroupLoading:false,
    editGroupLoading:false,
    editGroup:[],
    deleteGroupLoading:false,
    groupDeleteWithoutMoveUserToOtherGroupLoading:false,
    groupUsers:[],
    groupUsersLoading:false ,
    groupWithUsersAndPermissions:[],
    groupWithUsersAndPermissionsLoading:false,
    removeUsersFromGroupLoading:false,
    addUsersInGroupLoading:false,
    addUsersInGroup:[],
    groupDeleteAndMoveUserToOtherGroupLoading:false,
    addGroupPermissions:[],
    addGroupPermissionsLoading:false,
    updateGroupPermissionsLoading:false,
    groupPermissions:[],
    groupPermissionsLoading:false,
    allDefaultGroupTemplates:[],
    allDefaultGroupTemplatesLoading:false,
    groupTemplatePermissions:[],
    groupTemplatePermissionsLoading:false,
    allModules:[],
    allModulesLoading:false,
    groupDetails:[],
    groupDetailsLoading:false


}
const getters = {
    getAddGroup : state => state.addGroup,
    getAddGroupLoading: state => state.addGroupLoading,
    getEditGroupLoading: state => state.editGroupLoading,
    getEditGroup: state => state.editGroup,
    getDeleteGroupLoading: state => state.deleteGroupLoading,
    getGroupUsersLoading: state => state.groupUsersLoading,
    getGroupUsers: state => state.groupUsers,
    getGroupWithUsersAndPermissions: state => state.groupWithUsersAndPermissions,
    getGroupWithUsersAndPermissionsLoading: state => state.groupWithUsersAndPermissionsLoading,
    getRemoveUsersFromGroupLoading: state => state.removeUsersFromGroupLoading,
    getAddUsersInGroupLoading: state => state.addUsersInGroupLoading,
    getAddUsersInGroup: state => state.addUsersInGroup,
    getGroupDeleteAndMoveUserToOtherGroupLoading: state => state.groupDeleteAndMoveUserToOtherGroupLoading,
    getGroupDeleteWithoutMoveUserToOtherGroupLoading: state => state.groupDeleteWithoutMoveUserToOtherGroupLoading,
    getAddGroupPermissionsLoading: state => state.addGroupPermissionsLoading,
    getAddGroupPermissions: state => state.addGroupPermissions,
    getUpdateGroupPermissionsLoading: state => state.updateGroupPermissionsLoading,
    getGroupPermissions: state => state.groupPermissions,
    getGroupPermissionsLoading: state => state.groupPermissionsLoading,
    getAllDefaultGroupTemplatesLoading: state => state.allDefaultGroupTemplatesLoading,
    getAllDefaultGroupTemplates: state => state.allDefaultGroupTemplates,
    getGroupTemplatePermissions: state => state.groupTemplatePermissions,
    getGroupTemplatePermissionsLoading: state => state.groupTemplatePermissionsLoading,
    getAllModules: state => state.allModules,
    getAllModulesLoading: state => state.allModulesLoading,
    getGroupDetails: state => state.groupDetails,
    getGroupDetailsLoading: state => state.groupDetailsLoading,
    getUserAndUserGroupActveTab: state => state.UserAndUserGroupActveTab
}
const poBaseUrl = process.env.VUE_APP_BASE_URL
const actions = {
    addGroupApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_ADD_GROUP_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.post(`${poBaseUrl}/groups`, item).then(response => {
                    // console.log("addGroup",response)
                    commit('SET_ADD_GROUP_LOADING', false)
                    resolve(response.data)
                    commit('SET_ADD_GROUP', response.data.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_ADD_GROUP_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.message !== 'undefined') {
                            commit("SET_ADD_GROUP_LOADING", false)
                            reject(err.message)
                        }
                    })
            }
            proceed()
        })
    },
    editGroupApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_EDIT_GROUP_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.post(`${poBaseUrl}/groups/${item.group_id}/update`, item).then(response => {
                    commit('SET_EDIT_GROUP_LOADING', false)
                    commit('SET_EDIT_GROUP',response.data.data)
                    resolve(response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_EDIT_GROUP_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_EDIT_GROUP_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    deleteGroupApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_DELETE_GROUP_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.post(`${poBaseUrl}/groups/${item.group_id}/delete`, item).then(response => {
                    commit('SET_DELETE_GROUP_LOADING', false)
                    resolve(response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_DELETE_GROUP_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_DELETE_GROUP_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    fetchGroupUsersApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_GROUP_USERS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.get(`${poBaseUrl}/groups/${item.group_id}/users?company_id=${item.company_id}`).then(response => {
                    // console.log("users",response)
                    commit('SET_GROUP_USERS_LOADING', false)
                    resolve(response.data)
                    commit('SET_GROUP_USERS', response.data.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_GROUP_USERS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_GROUP_USERS_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    fetchGroupWithUsersAndPermissions: async ({ commit }, company_id) => {
        let attempt = false
        commit('SET_GROUP_WITH_USERS_AND_PERMISSIONS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.get(`${poBaseUrl}/groups/${company_id}/company`).then(response => {
                    // console.log("response", response)
                    commit('SET_GROUP_WITH_USERS_AND_PERMISSIONS_LOADING', false)
                    resolve(response.data)
                    commit('SET_GROUP_WITH_USERS_AND_PERMISSIONS', response.data.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_GROUP_WITH_USERS_AND_PERMISSIONS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_GROUP_WITH_USERS_AND_PERMISSIONS_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    removeUsersFromGroupApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_REMOVE_USERS_FROM_GROUP_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.post(`${poBaseUrl}/groups/${item.group_id}/remove-users`,item).then(response => {
                    // console.log("remove user",response)
                    commit('SET_REMOVE_USERS_FROM_GROUP_LOADING', false)
                    resolve(response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_REMOVE_USERS_FROM_GROUP_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.message !== 'undefined') {
                            commit("SET_REMOVE_USERS_FROM_GROUP_LOADING", false)
                            reject(err.message)
                        }
                    })
            }
            proceed()
        })
    },
    addUsersInGroupApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_ADD_USERS_IN_GROUP_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.post(`${poBaseUrl}/groups/${item.group_id}/add-users`,item ).then(response => {
                    // console.log("add users", response)
                    commit('SET_ADD_USERS_IN_GROUP_LOADING', false)
                    resolve(response.data)
                    commit('SET_ADD_USERS_IN_GROUP',response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_ADD_USERS_IN_GROUP_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.message !== 'undefined') {
                            commit("SET_ADD_USERS_IN_GROUP_LOADING", false)
                            reject(err.message)
                        }
                    })
            }
            proceed()
        })
    },
    deleteGrouAndMoveUserToOtherGroupApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_GROUP_DELETE_AND_MOVE_USERS_TO_OTHERS_GROUPS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.post(`${poBaseUrl}/groups/${item.group_id}/delete-move`,item).then(response => {
                    commit('SET_GROUP_DELETE_AND_MOVE_USERS_TO_OTHERS_GROUPS_LOADING', false)
                    resolve(response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_GROUP_DELETE_AND_MOVE_USERS_TO_OTHERS_GROUPS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_GROUP_DELETE_AND_MOVE_USERS_TO_OTHERS_GROUPS_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    deleteGrouWithoutMoveUserToOtherGroupApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_GROUP_DELETE_WITHOUT_MOVE_USERS_TO_OTHERS_GROUPS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.post(`${poBaseUrl}/groups/${item.group_id}/delete`,item).then(response => {
                    commit('SET_GROUP_DELETE_WITHOUT_MOVE_USERS_TO_OTHERS_GROUPS_LOADING', false)
                    resolve(response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_GROUP_DELETE_WITHOUT_MOVE_USERS_TO_OTHERS_GROUPS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_GROUP_DELETE_WITHOUT_MOVE_USERS_TO_OTHERS_GROUPS_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    addGroupPermissionsApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_ADD_GROUP_PERMISSIONS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.post(`${poBaseUrl}/group-permissions`,item).then(response => {
                    commit('SET_ADD_GROUP_PERMISSIONS_LOADING', false)
                    resolve(response.data)
                    commit('SET_ADD_GROUP_PERMISSIONS',response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_ADD_GROUP_PERMISSIONS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.message !== 'undefined') {
                            commit("SET_ADD_GROUP_PERMISSIONS_LOADING", false)
                            reject(err.message)
                        }
                    })
            }
            proceed()
        })
    },
    updateGroupPermissionsApi: async ({ commit }, item) => {
        let attempt = false
        commit('SET_UPDATE_GROUP_PERMISSIONS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.put(`${poBaseUrl}/group-permissions`,item).then(response => {
                    commit('SET_UPDATE_GROUP_PERMISSIONS_LOADING', false)
                    resolve(response.data)
                    // commit('SET_UPDATE_GROUP_PERMISSIONS', response)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_UPDATE_GROUP_PERMISSIONS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.message !== 'undefined') {
                            commit("SET_UPDATE_GROUP_PERMISSIONS_LOADING", false)
                            reject(err.message)
                        }
                    })
            }
            proceed()
        })
    },
    fetchGroupPermissions: async ({ commit }, group_id) => {
        let attempt = false
        commit('SET_GROUP_PERMISSIONS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.get(`${poBaseUrl}/group-permissions/${group_id}`).then(response => {
                    // console.log("permissions", response)
                    commit('SET_GROUP_PERMISSIONS_LOADING', false)
                    resolve(response.data)
                    commit('SET_GROUP_PERMISSIONS', response.data.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_GROUP_PERMISSIONS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.message !== 'undefined') {
                            commit("SET_GROUP_PERMISSIONS_LOADING", false)
                            reject(err.message)
                        }
                    })
            }
            proceed()
        })
    },
    fetchAllDefaultGrouppermissions: async ({ commit }) => {
        let attempt = false
        commit('SET_ALL_DEFAULT_GROUP_TEMPLATES_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.get(`${poBaseUrl}/group-templates`).then(response => {
                    // console.log("fetchAllDefaultGrouppermissions",response.data)
                    commit('SET_ALL_DEFAULT_GROUP_TEMPLATES_LOADING', false)
                    resolve(response.data)
                    commit('SET_ALL_DEFAULT_GROUP_TEMPLATES', response.data.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_ALL_DEFAULT_GROUP_TEMPLATES_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_ALL_DEFAULT_GROUP_TEMPLATES_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    fetchGroupTemplatePermissions: async ({ commit }, group_template_id) => {
        let attempt = false
        commit('SET_GROUP_TEMPLATES_PERMISSIONS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.get(`${poBaseUrl}/group-permissions-templates/${group_template_id}`).then(response => {
                    // console.log("group_template_id response", response.data)
                    commit('SET_GROUP_TEMPLATES_PERMISSIONS_LOADING', false)
                    resolve(response.data)
                    commit('SET_GROUP_TEMPLATES_PERMISSIONS', response.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_GROUP_TEMPLATES_PERMISSIONS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_GROUP_TEMPLATES_PERMISSIONS_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    fetchAllModules: async ({ commit }) => {
        let attempt = false
        commit('SET_ALL_MODULES_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.get(`${poBaseUrl}/modules`).then(response => {
                    // console.log("Fetch_ALL_MODULES", response)
                    commit('SET_ALL_MODULES_LOADING', false)
                    resolve(response.data)
                    commit('SET_ALL_MODULES', response.data.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_ALL_MODULES_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_ALL_MODULES_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
    fetchGroupDetails: async ({ commit },group_id) => {
        let attempt = false
        commit('SET_GROUP_DETAILS_LOADING', true)
        return new Promise((resolve, reject) => {
            function proceed() {
                axios.get(`${poBaseUrl}/groups/${group_id}`).then(response => {
                    // console.log("SET_GROUP_DETAILS", response)
                    commit('SET_GROUP_DETAILS_LOADING', false)
                    resolve(response.data)
                    commit('SET_GROUP_DETAILS', response.data.data)
                })
                    .catch(err => {
                        if (typeof err.message !== 'undefined') {
                            if (!attempt) {
                                attempt = true
                                let t = setInterval(() => {
                                    if (!store.getters.getIsRefreshing) {
                                        proceed()
                                        clearInterval(t)
                                    }
                                }, 300)
                            } else {
                                commit("SET_GROUP_DETAILS_LOADING", false)
                                reject(err)
                            }
                        }

                        if (typeof err.error !== 'undefined') {
                            commit("SET_GROUP_DETAILS_LOADING", false)
                            reject(err.error)
                        }
                    })
            }
            proceed()
        })
    },
}

const mutations = {
    SET_ADD_GROUP_LOADING: (state, payload) => {
        state.addGroupLoading = payload
    },
    SET_ADD_GROUP: (state, payload) => {
        state.addGroup = payload
    },
    SET_EDIT_GROUP_LOADING: (state, payload) => {
        state.editGroupLoading = payload
    },
    SET_EDIT_GROUP: (state, payload) => {
        state.editGroup = payload
    },
    SET_DELETE_GROUP_LOADING: (state, payload) => {
        state.deleteGroupLoading = payload
    },
    SET_GROUP_USERS_LOADING: (state, payload) => {
        state.groupUsersLoading = payload
    },
    SET_GROUP_USERS: (state, payload) => {
        state.groupUsers = payload
    },
    SET_GROUP_WITH_USERS_AND_PERMISSIONS: (state, payload) => {
        state.groupWithUsersAndPermissions = payload
    },
    SET_GROUP_WITH_USERS_AND_PERMISSIONS_LOADING: (state, payload) => {
        state.groupWithUsersAndPermissionsLoading = payload
    },
    SET_REMOVE_USERS_FROM_GROUP_LOADING: (state, payload) => {
        state.removeUsersFromGroupLoading = payload
    },
    SET_ADD_USERS_IN_GROUP_LOADING: (state, payload) => {
        state.addUsersInGroupLoading = payload
    },
    SET_ADD_USERS_IN_GROUP: (state, payload) => {
    state.addUsersInGroup = payload
    },
    SET_GROUP_DELETE_AND_MOVE_USERS_TO_OTHERS_GROUPS_LOADING: (state, payload) => {
        state.groupDeleteAndMoveUserToOtherGroupLoading = payload
    },
    SET_GROUP_DELETE_WITHOUT_MOVE_USERS_TO_OTHERS_GROUPS_LOADING: (state, payload) => {
        state.groupDeleteWithoutMoveUserToOtherGroupLoading = payload
    },
    
    SET_ADD_GROUP_PERMISSIONS_LOADING: (state, payload) => {
        state.addGroupPermissionsLoading = payload
    },
    SET_ADD_GROUP_PERMISSIONS: (state, payload) => {
        state.addGroupPermissions = payload
    },
    SET_UPDATE_GROUP_PERMISSIONS_LOADING: (state, payload) => {
        state.updateGroupPermissionsLoading = payload
    },
    SET_GROUP_PERMISSIONS_LOADING: (state, payload) => {
        state.groupPermissionsLoading = payload
    },
    SET_GROUP_PERMISSIONS: (state, payload) => {
        state.groupPermissions = payload
    },
    SET_ALL_DEFAULT_GROUP_TEMPLATES: (state, payload) => {
        state.allDefaultGroupTemplates = payload
    },
    SET_ALL_DEFAULT_GROUP_TEMPLATES_LOADING: (state, payload) => {
        state.allDefaultGroupTemplatesLoading = payload
    },
    SET_GROUP_TEMPLATES_PERMISSIONS: (state, payload) => {
        state.groupTemplatePermissions = payload
    },
    SET_GROUP_TEMPLATES_PERMISSIONS_LOADING: (state, payload) => {
        state.groupTemplatePermissionsLoading = payload
    },
    SET_ALL_MODULES_LOADING: (state, payload) => {
        state.allModulesLoading = payload
    },
    SET_ALL_MODULES: (state, payload) => {
        state.allModules = payload
    },
    setGroupUserDataEmpty:(state,payload) =>{
        state.groupUsers = payload
    },
    setGroupPermissionsEmpty:(state,payload)=>{
        state.groupPermissions = payload
    },
    setGroupDetailsDataEmpty: (state, payload) => {
        state.groupDetails = payload
    },
    SET_GROUP_DETAILS_LOADING: (state, payload) => {
        state.groupDetailsLoading = payload
    },
    SET_GROUP_DETAILS: (state, payload) => {
        state.groupDetails = payload
    },
    setUserGroupAsASelectiveTab: (state, payload) => {
        state.UserAndUserGroupActveTab = payload
    },
    emptyAddUserGroupData: (state, payload) => {
        state.addGroup = payload
},
    
    

    
    
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}