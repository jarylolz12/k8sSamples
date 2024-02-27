import Vue from "vue";
import "./globalFilters"
import App from "./App.vue";
import store from "./store";
import './assets/css/main.css';
import './assets/scss/global.scss'
import router from './router'
import vuetify from './plugins/vuetify';
import vuetifyMoney from "./plugins/vuetify-money.js";
import 'izitoast/dist/css/iziToast.min.css';
import countryFlag from "./plugins/country-flag.js";
import vCalendar from './plugins/vc-calendar';
import bootstrapAxios from './configs/axios';
import poEcho from './configs/poEcho';
import { registerAccountingModule } from "./register-accounting-module";
import 'devextreme/dist/css/dx.light.css';

Vue.config.productionTip = false;
const currentUrl = window.location.href;
if(currentUrl.includes("shipment/request")){
	localStorage.removeItem("user_token")
	const urlArray = currentUrl.split("/").slice(-1);
	let lastElement = urlArray.pop();
	store.dispatch("page/setCurrentPath", { currentPathName: 'BookingRequestForm', token: lastElement})

} else {
	localStorage.removeItem('setBookingInviteToken')
}
bootstrapAxios();
window.poEcho = poEcho;

// function subscribeTokenRefresh(cb) {
// 	subscribers.push(cb);
// }

// function onRefreshed() {
// 	subscribers.map(cb => cb());
// }

// subscribers = [];
///

// console.log(axios.defaults.headers.common.Authorization)

registerAccountingModule();

new Vue({
	store,
	router,
	vuetify,
	vuetifyMoney,
	countryFlag,
	vCalendar,
	render: h => h(App)
}).$mount("#app");