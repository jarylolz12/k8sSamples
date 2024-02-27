import axios from "axios"

const state = {
    statements: [],
    statementsLoading: false,
}

const getters = {
    getAchStatements: state => state.statements,
    getAchStatementsLoading: state => state.statementsLoading,
}

// const poBaseUrl = "https://beta.shifl.com/api"

const actions = {
    fetchAchStatements: async ({
		commit
	}) => {
		commit("SET_STATEMENTS_LOADING", true)

		await axios.get(`/statements`)
		.then(res => {
			if (res.status === 200) {
				if (res.data) {
					commit("SET_STATEMENTS_LOADING", false)
					commit("SET_STATEMENTS", res.data)
				}
			}
		})
		.catch(err => {
			commit("SET_STATEMENTS_LOADING", false)
			if (typeof err.error !== 'undefined') {
				return Promise.reject(err.error)
			} else {
				if (typeof err.message !== 'undefined') {
					return Promise.reject('Token has been expired. Please reload the page.')
				}
			}
		})
	},
}

const mutations = {
    SET_STATEMENTS: (state, payload) => {
		state.statements = payload
	},
	SET_STATEMENTS_LOADING: (state, payload) => {
		state.statementsLoading = payload
	},
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}
