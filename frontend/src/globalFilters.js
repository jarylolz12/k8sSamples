import Vue from 'vue'
import moment from "moment";
Vue.filter('humanize',function (date) {
    return moment(date).fromNow()
})