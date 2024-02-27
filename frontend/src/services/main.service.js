import axios from 'axios';
import store from '@/store';

let mainService = class MainService {
	baseURL = '';
	headers;
	$http;

	constructor() {
		this.baseURL = process.env.VUE_APP_ACCOUNTING_URL;

		this.$http = axios.create({
			timeout: 1600000,
			baseURL: this.baseURL
		});

		this.$http.interceptors.request.use((config) => {
			/*  const token = 'Bearer ' + store.getters['accounting/getUserToken'];
      config.headers.Authorization = token; */
			return config;
		});

		this.$http.interceptors.response.use((response) => {
			return response;
		});
	}

	getJson(uri = '', data = {}) {
		if (Object.keys(data).length > 0) {
			uri = `${uri}?${this.getQueryString(data)}`;
		}

		return this.$http.get(uri);
	}

	getHeaders(additionalHeaders = {}) {
		if (store.getters.getUserToken) {
			this.headers = {
				authorization: `Bearer ${store.getters.getUserToken}`
			};
		}
	
		return {
			...this.headers,
			...additionalHeaders,
			'shifl-origin': process.env.VUE_APP_ACCOUNTING_SERVICE_ORIGIN,
			'shifl-origin-key': process.env.VUE_APP_ACCOUNTING_SERVICE_KEY,
			'instance-id': this.getCompanyInstanceId(),
		};
	}

	prepareUrl(url, params) {
		for (let index in params) {
			let identifier = ':' + index;
			url = url.replace(identifier, params[index]);
		}
		return url;
	}

	getQueryString(params) {
		return Object.keys(params)
			.map((k) => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
			.join('&');
	}

	post(uri = '', data = {}, additionalHeaders = {}) {
		return this.$http.post(uri, data, {
			headers: this.getHeaders(additionalHeaders)
		});
	}

	put(uri = '', data, additionalHeaders = {}) {
		return this.$http.put(uri, data, {
			headers: this.getHeaders(additionalHeaders)
		});
	}

	patch(uri = '', data, additionalHeaders = {}) {
		return this.$http.patch(uri, data, {
			headers: this.getHeaders(additionalHeaders)
		});
	}

	remove(uri = '', data = {}, additionalHeaders = {}) {
		return this.$http(uri, {
			method: 'DELETE',
			headers: this.getHeaders(additionalHeaders),
			data: data
		});
	}

	get(uri = '', data = {}, additionalHeaders = {}, options = {}) {
		if (Object.keys(data).length > 0) {
			uri = `${uri}?${this.getQueryString(data)}`;
		}

		return this.$http.get(uri, {
			...options,
			headers: this.getHeaders(additionalHeaders)
		});
	}

	getCompanyInstanceId(){
		let getUser = store.getters.getUser;
        getUser = typeof getUser === "string" ? JSON.parse(getUser) : getUser;
        return getUser?.default_customer_id;
	}
};

export default mainService;
