/** @format */

import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth';
import shipment from './modules/shipment';
import page from './modules/page';
import shipmentDetails from './modules/shipmentDetails';
import masterSearch from './modules/masterSearch';
import scheduleOptions from './modules/scheduleOptions';
import milestones from './modules/milestones';
import salesOrders from './modules/salesOrders';
import products from './modules/products';
import category from './modules/category';
import warehouse from './modules/warehouse';
import inventory from './modules/inventory';
import suppliers from './modules/suppliers';
import statements from './modules/statements';
import inbound from './modules/inventories/inbound';
import outbound from './modules/inventories/outbound';
import locations from './modules/inventories/locations';
import invoice from './modules/invoice';
import paymentMethod from './modules/paymentMethod';
import users from './modules/users';
import storableUnit from './modules/inventories/storableUnit';
import reports from './modules/reports';
import productInventories from './modules/inventories/productInventories';
import trackingShipment from './modules/trackingShipment';
import documents from './modules/documents';
import customers from './modules/customers';
import notes from './modules/notes';
import shipmentProducts from './modules/shipmentProducts';
import warehouseCustomers from './modules/warehouseCustomers';
import paymentHistory from "./modules/paymentHistory";
import notifications from "@/store/modules/notifications";
import po from './modules/po';
import booking from './modules/booking';
import orders from "./modules/orders";
import poDetails from './modules/poDetails'
import drayage from "./modules/drayage";
import settings from "./modules/settings";
import createVuexReloadPlugin from "vuex-action-reload";
import employees from './modules/inventories/employees'
import mwms from './modules/mwms-mod/mwms'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {},
    mutations: {},
    actions: {},
    plugins: [
		createVuexReloadPlugin({
			actions: ["po/clearAllPosData", "salesOrders/clearAllSalesOrdersData"],
			condition: (mutation) => {
				if (
					mutation.type == "po/SET_ORDER_STATE_LOADING" ||
					mutation.type == "po/SET_PO_DRAFT_LOADING" ||
					mutation.type == "po/SET_PO_CREATE_LOADING" ||
					mutation.type == "po/SET_UPLOAD_PAYMENT_DOCUMENTS" ||
					mutation.type == "orders/SET_PRODUCTION_STATUS_LOADING" ||
					mutation.type == "salesOrders/SET_ORDER_STATE_LOADING" ||
					mutation.type == "salesOrders/SET_SO_DRAFT_LOADING" ||
					mutation.type == "salesOrders/SET_SO_CREATE_LOADING" ||
					mutation.type == "salesOrders/SET_REVIEW_PAYMENT_LOADING"
				)
					return true;
			},
		}),
	],
    modules: {
        auth,
        shipment,
        page,
        shipmentDetails,
        masterSearch,
        scheduleOptions,
        milestones,
        salesOrders,
        products,
        category,
        warehouse,
        inventory,
        suppliers,
        statements,
        inbound,
        outbound,
        locations,
        invoice,
        paymentMethod,
        users,
        storableUnit,
        reports,
        productInventories,
        trackingShipment,
        documents,
        customers,
        notes,
        shipmentProducts,
        warehouseCustomers,
        paymentHistory,
        po,
        booking,
        orders,
        poDetails,
		notifications,
		drayage,
		settings,
        employees,
        mwms
	}
});
