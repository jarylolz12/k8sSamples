import moment from 'moment'

const inventoryGlobalMethods = {
    scrollTableToTop() {
        var table = this.$refs['my-table'];
        var wrapper = table.$el.querySelector('div.v-data-table__wrapper');
        
        this.$vuetify.goTo(table); // to table
        this.$vuetify.goTo(table, {container: wrapper}); // to header
    },
    formatDateOnly(date) {
        if (typeof date !== 'undefined' && date !== null) {
            return moment(date).format('MMM DD, YYYY')
        } else {
            return 'N/A'
        }
    },
    formatTimeOnly(time) {
        if (time !== '' && time !== null) {
            return moment(time, ["HH:mm"]).format("hh:mm A");
        } else {
            return ''
        }
    },
    // directly format date with time
    formatDateWithTimeAbbr(date) {
        if (typeof date !== 'undefined' && date !== null) {
            return moment(date).format('DD MMM YYYY h:mm A')
        } else {
            return 'N/A'
        }
    },
    formatTimeAndDateTogether(date) {
        if (date !== '' && date !== null) {
            return moment(date).format('hh:mmA, MMM DD, YYYY')
        } else {
            return '--'
        }
    },
    // format date and time separately and combined since both are optional
    formatDateWithTimeSeparated(date, time, showFirst) {
        let final_estimated_date_and_time = null;
        let final_estimated_time_and_date = null;
        let newTime = null;
        let newDate = null;

        if (showFirst === 'date-first') { // if date comes first
            if (date !== "" && date !== null && date !== 'null') {
                newDate = moment(date).format("DD MMM YYYY")
            } else {
                newDate = ''
            }
    
            if (time !== "" && time !== null && time !== 'null') {
                newTime = moment(time, ["HH:mm"]).format("hh:mm A")
            } else {
                newTime = ''
            }
        } else { // if time comes first
            if (date !== "" && date !== null && date !== 'null') {
                newDate = moment(date).format('MMM DD, YYYY')
            } else {
                newDate = ''
            }
    
            if (time !== "" && time !== null && time !== 'null') {
                newTime = moment(time, ["HH:mm"]).format("hh:mmA, ")
            } else {
                newTime = ''
            }
        }

        // date first then time
        final_estimated_date_and_time = newDate + ' ' + newTime
        final_estimated_date_and_time = (final_estimated_date_and_time !== '' && final_estimated_date_and_time !== ' ') ? final_estimated_date_and_time : '--'

        // time first then date
        final_estimated_time_and_date = newTime + ' ' + newDate
        final_estimated_time_and_date = (final_estimated_time_and_date !== '' && final_estimated_time_and_date !== ' ') ? final_estimated_time_and_date : '--'

        return showFirst === 'date-first' ? final_estimated_date_and_time : final_estimated_time_and_date
    },
    // get overshipped inbound / outbound
    isDataOvershipped(data) {
        if (data !== 'undefined' && data !== null) {
            if (data.is_undershipped !== 'undefined') {
                if (data.is_undershipped === 0) {
                    return ''
                } else if (data.is_undershipped === 1) {
                    return 'Overshipped'
                } else {
                    return 'Undershipped'
                }
            }
        }
    },
}

export default inventoryGlobalMethods;