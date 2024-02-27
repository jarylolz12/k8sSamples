/** @format */

import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store/index";

Vue.use(VueRouter);

const routes = [
	{
		path: "/",
		name: "Dashboard",
		component: () =>
			import(/* webpackChunkName: "/" */ "../views/Dashboard.vue"),
		meta: { requiresAuth: true },
		redirect: "/shipments",
	},
	{
		path: "/no-customer-account",
		name: "NoCustomerAccount",
		component: () =>
			import(/* webpackChunkName: "/" */ "../views/NoCustomerAccount.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/login",
		name: "Login",
		// component: Login
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "login" */ "../views/Login.vue"),
		meta: { requiresAuth: false },
	},
	{
		path: "/forgetPassword",
		name: "ForgetPassword",
		component: () =>
			import(
				/* webpackChunkName: "forgetPassword" */
				"../views/singIn/ForgetPassword.vue"
			),
		meta: { otherRequiresAuth: true },
	},
	{
		path: "/checkInbox",
		name: "CheckInbox",
		component: () =>
			import(
				/* webpackChunkName: "ckeckInbox" */
				"../views/singIn/CkeckInbox.vue"
			),
		meta: { otherRequiresAuth: true },
	},
	{
		path: "/change-password",
		name: "ResetPassword",
		component: () =>
			import(
				/* webpackChunkName: "resetPassword" */
				"../views/singIn/ResetPassword.vue"
			),
		meta: { otherRequiresAuth: true },
	},
	{
		path: "/passHasReset",
		name: "PasswordHasReset",
		component: () =>
			import(
				/* webpackChunkName: "passHasReset" */
				"../views/singIn/PasswordHasReset.vue"
			),
		meta: { otherRequiresAuth: true },
	},
	{
		path: "/about",
		name: "About",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/About.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/shipments",
		name: "Shipments",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		// component: () => import( /* webpackChunkName: "about" */ '../views/Shipments.vue'),
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/Shipment.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/drayage",
		name: "Drayage",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		// component: () => import( /* webpackChunkName: "about" */ '../views/Shipments.vue'),
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/Drayage.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/shipment/:id",
		name: "Shipment Details",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/ShipmentDetails.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/pos",
		name: "POs",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () => import(/* webpackChunkName: "about" */ "../views/PO.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/po-details/:id",
		name: "PO Details",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/PODetails.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/pos/item",
		name: "PoMobile",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */
				"../components/PosComponents/Mobile/PoMobile.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/sales-orders",
		name: "SalesOrders",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/SalesOrders.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/sales-orders-details/:id",
		name: "SalesOrders Details",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/SalesOrdersDetails.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/products",
		name: "Products",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/Products.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/products/manage-categories",
		name: "ManageCategories",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/Categories.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/inventory",
		name: "Inventory",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/Inventory.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/inventory/outbound-view/",
		name: "Inventory Outbound View",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */
				"../components/InventoryComponents/OutboundComponents/InventoryOutboundView.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/inventory/inbound-view/",
		name: "Inventory Inbound View",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */
				"../components/InventoryComponents/InboundComponents/InventoryInboundView.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/inventory/storable-unit-view/",
		name: "Inventory Storable View",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */
				"../components/InventoryComponents/StorableComponents/InventoryStorableView.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/warehouses/inventory",
		name: "InventoryMobile",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */
				"../components/InventoryComponents/Mobile/InventoryMobile.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/mwms",
		name: "MWMSystem",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/MWMSystem.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/contact",
		name: "Contact",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/Suppliers.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/contact/details",
		name: "SupplierCustomerDetailsPage",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */
				"../components/SupplierComponents/SupplierCustomerDetailsPage.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/reports",
		name: "Reports",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		// component: () => import(/* webpackChunkName: "about" */ '../views/Reports.vue'),
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/ReportsNew.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/billing",
		name: "Billing",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/Billing.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/settings",
		name: "Settings",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/Settings.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/settings/newusergroup",
		name: "AddUserGroup",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */ "../components/SettingsComponents/Users/AddUserGroup.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/ach-statements",
		name: "AchStatements",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/AchStatements.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/invite-confirm",
		name: "InviteConfirm",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "invite-confirm" */ "../views/InviteConfirm.vue"
			),
		meta: { requiresAuth: false },
	},
	{
		path: "/inventory/inventory-report",
		name: "InventoryReport",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/InventoryReport.vue"),
		meta: { requiresAuth: true },
	},
	{
		path: "/products/archived-products",
		name: "ArchivedProducts",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(/* webpackChunkName: "about" */ "../views/ArchivedProducts.vue"),
		meta: { requiresAuth: true },
	},
	// mwms pages
	{
		path: "/mwms/:id/receiving",
		name: "Receiving",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */ "../components/MWMS/ReceivingComponents/AllOrders.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/mwms/:id/receiving/:orders/confirm-receiving",
		name: "ConfirmReceiving",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */ "../components/MWMS/ReceivingComponents/ConfirmReceiving.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/mwms/:id/receiving/:orders/select-location",
		name: "SelectLocation",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */ "../components/MWMS/ReceivingComponents/SelectLocation.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/mwms/:id/receiving/:orders/:location/location-confirmed",
		name: "ConfirmedLocation",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */ "../components/MWMS/ReceivingComponents/ConfirmedLocation.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/mwms/:id/receiving/:orders/:location/receiving-info/:sku",
		name: "ReceivingInfo",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */ "../components/MWMS/ReceivingComponents/ReceivingInfo.vue"
			),
		meta: { requiresAuth: true },
	},
	{
		path: "/shipment/request/:token",
		name: "BookingRequestForm",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */ "../views/BookingRequest/BookingRequestForm.vue"
			),
		meta: { requiresAuth: false },
	},
	{
		path: "/booking-request-submitted",
		name: "BookingRequestSubmitted",
		// route level code-splitting
		// this generates a separate chunk (about.[hash].js) for this route
		// which is lazy-loaded when the route is visited.
		component: () =>
			import(
				/* webpackChunkName: "about" */ "../views/BookingRequest/BookingRequestSubmitted.vue"
			),
		meta: { requiresAuth: false },
	},
];

const router = new VueRouter({
	mode: "history",
	routes,
});

router.beforeEach((to, from, next) => {
	if (to.matched.some((record) => record.meta.requiresAuth)) {
		//check token
		if (store.getters.getUserToken && store.getters.getExpiresAt) {
			let customers_api =
				typeof store.getters.getUser.customers_api == "undefined"
					? JSON.parse(store.getters.getUser).customers_api
					: store.getters.getUser.customers_api;

			if (typeof customers_api !== "undefined") {
				if (to.name !== "NoCustomerAccount") {
					if (customers_api.length > 0) {
						next();
					} else {
						next({
							name: "NoCustomerAccount",
						});
					}
				} else {
					if (customers_api.length > 0) {
						next({
							name: "Dashboard",
						});
					} else {
						next();
					}
				}
			} else {
				next();
			}
			if (from.name == "Login") {
				window.location.href = to.path;
			} else {
				//next();
			}
			//if token expired
			/*
            if (new Date(store.getters.getExpiresAt) <= new Date()) {
                // if token time is expired then try to refresh the token
                store
                .dispatch("refreshToken")
                .then(({ status }) => {
                    if (status === 200 || status == 204) {
                    console.log('refresh 200')
                    next();
                    } else {
                    next({
                        name: "Login"
                    })
                    }
                })
                .catch(() => {
                    next({
                        name: "Login"
                    })
                })
            } else {
                if (from.name=="Login") {
                    window.location.href = to.path
                } else {
                    next()  
                }
                
            }*/
		} else {
			next({
				name: "Login",
				query: {
					redirectTo: to.fullPath,
				},
			});
		}
	} else {
		if (to.matched.some((record) => !record.meta.requiresAuth)) {
			if (store.getters.getUserToken && store.getters.getExpiresAt) {
				if (
					to.name == "InviteConfirm" ||
					to.name == "BookingRequestForm" ||
					to.name == "BookingRequestSubmitted"
				) {
					next();
				} else {
					next({
						name: "Dashboard",
					});
				}
			} else {
				if (
					to.name == "BookingRequestForm" ||
					to.name == "BookingRequestSubmitted"
				) {
					next();
				}
				if (from.name == "Login") {
					window.location.href = to.path;
				} else {
					next();
				}
			}
		} else {
			//other routes
			if (store.getters.getUserToken && store.getters.getExpiresAt) {
				next({
					name: "Dashboard",
				});
			} else {
				if (from.name == "Login") {
					window.location.href = to.path;
				} else {
					next();
				}
			}
		}
	}
});

export default router;
