const tokenHelper = {
	resetToken
}

function resetToken(params) {
	let {
		store,
		err,
		start,
		limit,
		attempt,
		commit,
		callback,
		reject
	} = params

	if (typeof err.message!=='undefined') {
		if ( !attempt ){
			attempt = true
			let t = setInterval(() => {
				if ( !store.getters.getIsRefreshing )	{
					if ( start == limit) {
						store.dispatch('refreshToken').then(() => {
							attempt = false
							start = 0
							callback()
						}).catch(e => {
							console.log(e)
							commit("SET_CREATE_LOADING", false)
							reject('Token has been expired. Please reload the page.')
						})
					} else {
						start++
						callback(start)
						attempt = false
					}
					clearInterval(t)
				}
			},300)
		} else {
			commit("SET_CREATE_LOADING", false)
			reject('Token has been expired. Please reload the page.')
		}
	}
}

export default tokenHelper