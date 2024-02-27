/** @format */

import iziToast from "izitoast";

const globalMethods = {
    notifyAccount: (payload) => {
        iziToast.info({
            // class: 'categories-toast',
            class: "product-toast",
            // title: 'Error',
            message: payload.notification.body,
            displayMode: 1,
            position: "bottomRight",
            timeout: 10000,
        });

        /*
        iziToast.show({
            theme: 'light',
            icon: 'icon-person',
            title: 'Account Changes',
            message: payload.notification.body,
            position: 'bottomRight',
            progressBarColor: 'rgb(0, 255, 184)',
        })*/
    },
    notificationMessage: (message) => {
        // iziToast.success({
        //     class: 'categories-toast',
        //     title: 'Success',
        //     message: `${message}`,
        //     displayMode: 1,
        //     position: 'topRight',
        //     timeout: 3000,
        // });
        iziToast.success({
            theme: "dark",
            message: message,
            backgroundColor: "#16B442",
            messageColor: "#ffffff",
            iconColor: "#ffffff",
            progressBarColor: "#ADEEBD",
            displayMode: 1,
            position: "bottomCenter",
            timeout: 3000,
        });
    },
    notificationGlobal: (params) => {
        let {
            className,
            title,
            message,
            position,
            icon,
            timeout,
            is_success,
        } = params;

        if (is_success) {
            iziToast.success({
                class: className,
                title,
                message,
                displayMode: 1,
                position,
                timeout,
                icon,
            });
        } else {
            iziToast.info({
                class: className,
                title,
                message,
                displayMode: 1,
                position,
                timeout,
            });
        }
    },
    notificationError: (error) => {
        iziToast.info({
            // class: 'categories-toast',
            class: "product-toast",
            // title: 'Error',
            message: error,
            displayMode: 1,
            position: "bottomCenter",
            timeout: 3000,
        });
    },
    notificationErrorCustom: (error) => {
        iziToast.info({
            class: "product-toast",
            // title: 'Error',
            message: error,
            displayMode: 1,
            position: "bottomCenter",
            timeout: 3000,
        });
    },
    notificationCustom: (message) => {
        iziToast.info({
            class: "product-toast",
            // title: 'Error',
            message: message,
            displayMode: 1,
            position: "bottomCenter",
            timeout: 3000,
        });
    },
    warningToast: (message) => {
        iziToast.warning({
            class: "warning-toast",
            message: message,
            displayMode: 1,
            position: "bottomCenter",
            timeout: 3000,
        });
    },
    isScriptLoaded: (url) => {
        var scripts = document.getElementsByTagName("script");
        for (var i = scripts.length; i--;) {
            if (scripts[i].src == url) return true;
        }
        return false;
    },
    addCommaToNum: (num) => {
        if (num !== null) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    },
    notificationMessageCustomSuccess(message) {
        iziToast.success({
            theme: "dark",
            message: message,
            backgroundColor: "#16B442",
            messageColor: "#ffffff",
            iconColor: "#ffffff",
            progressBarColor: "#ADEEBD",
            displayMode: 1,
            position: "bottomCenter",
            timeout: 3000,
        });
    },
    isNumeric(n) {
        return typeof n === "number" && !isNaN(n);
    },
    twoDecimalFormat(n) {
        return parseFloat(Number(n).toFixed(2));
    },
    currencyNumberFormat(n) {
        const formatter = new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: "USD",
            minimumFractionDigits: 2,
        });
        return formatter.format(n);
    },
    getCustomerId() {
        return typeof this.getUser === "string" ?
            JSON.parse(this.getUser).customer.id :
            this.getUser.customer.id;
    },
    defaultCustomerId() {
        return typeof this.getUser === "string" ?
            JSON.parse(this.getUser).default_customer_id :
            this.getUser.default_customer_id;
    },
    VCreditCardNumberFormate(placeHolder, rawItem) {
        if (rawItem.card_number !== undefined) {
            return rawItem.card_number.replace(/(.{4})/g, "$1  ").trim();
        } else {
            return placeHolder;
        }
    },
    VCreditCardExpirationFormate(rawItem, isTwoDigitsYear) {
        if (rawItem.expiration !== undefined) {
            const monthYearArray = rawItem.expiration.split("/");
            const month =
                monthYearArray[0] < 10 ? "0" + monthYearArray[0] : monthYearArray[0];
            const year = monthYearArray[1];
            return month + "/" + year;
        } else {
            return isTwoDigitsYear ? "MM/YY" : "MM/YYYY";
        }
    },
    ccNumberFormat(ccNumber, replacement) {
        return replacement + ccNumber.substring(0 + replacement.length);
    },
    achRouteFormat(routing, replacement) {
        return replacement + routing.substring(0 + replacement.length);
    },
    achAccountFormat(account, replacement) {
        return replacement + account.substring(0 + replacement.length);
    },
    nNumberDecimalFormat(num, decimalPoint) {
        return parseFloat(Number(num).toFixed(decimalPoint));
    },
    amountNotationSign(amount, notation) {
        let returnVal = "$ " + amount;
        if (notation == "in-percentage") {
            returnVal = amount + " %";
        }
        return returnVal;
    },
    countDecimals(value) {
		if (Math.floor(value) === value) return 0;
		return value.toString().split(".")[1].length || 0;
	},
};

export const getErrorMessage = (error) => {
    let msg = error;
    if (typeof error === "string") {
        msg = error;
    } else if (error.response && error.response.data) {
        return getErrorMessage(error.response.data);
    } else if (error.errorDesc) {
        msg = error.errorDesc;
    } else if (error.message) {
        msg = error.message;
    }
    return msg;
};

export const apiErrorMessage = (message) => {
    const msg = getErrorMessage(message);
    globalMethods.notificationError(msg);
};

export function getFilenameFromAxiosResponse(response) {
    let contentDisposition = response.headers["content-disposition"];
    return contentDisposition
        .split(";")[1]
        .split("=")[1]
        .replace('"', "")
        .replace('"', "");
}

export const debounce = (fn, delay) => {
    let timeoutID = null;
    return function() {
        clearTimeout(timeoutID);
        const args = arguments;
        const that = this;
        timeoutID = setTimeout(function() {
            fn.apply(that, args);
        }, delay);
    };
};

export default globalMethods;