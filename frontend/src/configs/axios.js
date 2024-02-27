import axios from "axios";
import store from "@/store";
import _ from 'lodash';

export default function () {
    axios.defaults.baseURL = process.env.VUE_APP_BASE_URL
// axios.defaults.baseURL = "http://db505f5cfa37.ngrok.io/api"
    axios.defaults.headers.common.Accept = "application/json"

    // let token = '';
    const currentPath = store.getters['page/getCurrentPath']
    if(_.get(currentPath, 'currentPathName') == 'BookingRequestForm'){
        axios.post(`generate-invite-token`, {
            tokenKey: _.get(currentPath, 'token')
        })
            .then(response => {
                localStorage.setItem('setBookingInviteToken', response.data.data)
            })
            .catch(error => {
                console.log('error', error)
            });
        axios.interceptors.request.use(function (config) {
            const token =  "Bearer " + localStorage.getItem('setBookingInviteToken');
            config.headers.Authorization = token;
            return config;
        });
    } else {
        localStorage.removeItem('setBookingInviteToken')
        axios.interceptors.request.use(function (config) {
            const token = "Bearer " + store.getters.getUserToken
            config.headers.Authorization = token;
            return config;
        });
    }

// refresh token by using interceptors

    let isRefreshing = false;
// let subscribers = [];

    axios.interceptors.response.use(
        response => {
            return response;
        },
        async (err) => {
            // console.log(err)
            if (err.message === 'cancel_previous_request' || err.message == undefined){
                return Promise.reject(err); 
            }
            const {
                config,
                response: { status, data }
            } = err;

            const originalRequest = config;

            if (store.getters.getUserToken === '') {

                //store.dispatch("logout")
                return Promise.reject(err);
            }

            if (originalRequest.url.includes("refresh-token")) {

                store.dispatch("logout")
                return Promise.reject(err);
            }

            if ( status == 500 || status == 400) {
                return Promise.reject(data)
            }


            if (status === 401 && data.message.toLowerCase() === "unauthenticated.") {

                if (!isRefreshing) {
                    isRefreshing = true;
                    store
                        .dispatch("refreshToken")
                        .then(({ status }) => {
                            if (status === 200 || status == 204) {
                                isRefreshing = false;
                            }
                            let access_token = (localStorage.getItem('user_token')!=='' && localStorage.getItem('user_token')!==null) ? localStorage.getItem('user_token') : ''
                            originalRequest.headers.Authorization = `Bearer ${access_token}`
                            //originalRequest.headers.Authorization = "Bearer " + store.getters.getUserToken
                            return axios(originalRequest)
                            // subscribers = [];
                        })
                        .catch(error => {
                            return Promise.reject(error);
                        });
                }
                // const requestSubscribers = new Promise(resolve => {
                // 	subscribeTokenRefresh(() => {
                // 		resolve(axios(originalRequest));
                // 	});
                // });

                // onRefreshed();

                // return requestSubscribers;

            }
        }
    );
}