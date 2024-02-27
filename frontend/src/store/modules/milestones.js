import axios from "axios"
import _ from 'lodash'
import * as shipmentDetails from './shipmentDetails'
import store from '../'

const state = {
	milestones: [],
	milestones_loading: true,
	milestonesOtherData: [],
    milestonesOtherDataLoading: false,
    milestones_new_loading: true,
    milestones_other_events: [],
    trackingContainerData: [],
    milestoneAttibutes: null
}

const getters = {
	getMilestonesData: state => state.milestones,
	getMilestonesLoading: state => state.milestones_loading,
    getMilestonesNewLoading: state => state.milestones_new_loading,
    getMilestonesOtherEvents: state => state.milestones_other_events,
    getTrackingContainerDetails: state => state.trackingContainerData,
    getMilestonesAttributes: state => state.milestoneAttibutes
}

const methods = {
	testing: () => {
		alert('testing')
	},
    getExtraData( data, event) {
        let findData = _.find(data, e => (e.eventname == event))
        if (typeof findData!=='undefined') {
            
            let {
                actual_on,
                actual_at,
                estimated_at
            } = findData

            return {
                actual_at,
                actual_on,
                estimated_at
            }

        } else {
            return {
                actual_at: null,
                actual_on: null,
                estimated_at: null
            }
        }
    },
    checkMilestoneData (data, event) {
        let event_data = {
            data: null,
            isCompleted: false,
            class: '',
            latestDate: null
        }

        if (typeof data !== 'undefined') {
            event_data = {
                data: (typeof _.find(data, e => (e.event_name == event)) !== 'undefined') ?
                    _.find(data, e => (e.event_name == event)).other_data : null,
                isCompleted: (typeof _.find(data, e => (e.event_name == event)) !== 'undefined') ? 
                    _.find(data, e => (e.event_name == event)).isCompleted : false,
                class: (typeof _.find(data, e => (e.event_name == event)) !== 'undefined') ? 
                    _.find(data, e => (e.event_name == event)).class : '',
                locode: (typeof _.find(data, e => (e.event_name == event)) !== 'undefined') ?
                    _.find(data, e => (e.event_name == event)).locode : null,
            }

            // get the events latest date
            if (event_data.data !== null) {
                let new_data = event_data.data
                let date = new_data.map(function(e) { return e.date; }).sort().reverse()[0]
                event_data.latestDate = date
            }
        }
        
        return event_data
    },
    move(array, fromIndex, toIndex){
        array.splice(toIndex, 0, array.splice(fromIndex, 1)[0]);
    },
    async getTerminal(code) {
        try {
           
            const centralUrl = 'https://us-central1-fifth-compiler-357712.cloudfunctions.net/nbl_function2/api';
            const res = await axios.get(`${centralUrl}/v1/locations/code/${code}`)
            if(res.status == 200){
                if(typeof res.data !== 'undefined'){
                    if (typeof res.data.name!=='undefined' && res.data.name!=='' && res.data.country.name!==null) {
                        let countryName = res.data.country.name;
                        let tmpLoc = countryName.split(',')
                        if(tmpLoc.length == 2){
                            countryName = tmpLoc[1] + ' '+ tmpLoc[0]
                        }

                        let location =  res.data.name + ', '+ countryName;
                        location = location.replace("(the)", "");
                        
                        let newLocation = location + ' ('+code+')'
                        return Promise.resolve(newLocation)    
                    }else{
                        return Promise.resolve(0)
                    }    
                }else{
                    return Promise.resolve(0)
                }
            }else{
                return Promise.resolve(0)
            }
        } catch (err) {
            return Promise.reject('not ok')
        }
    }
}

const actions = {
    setMilestonesNewLoading: ({ commit }, dataState) => {
        commit("SET_MILESTONES_NEW_LOADING", dataState)
    },
    setMilestonesOtherEvents: ( { commit }, payload) => {
        commit("SET_MILESTONES_OTHER_EVENTS", payload)
    },
    fetchMilestones: async ({
		commit,
	}, mbl_num) => {
        commit("SET_MILESTONES_LOADING", true)
        commit("SET_TRACKING_CONTAINER_DETAILS", [])
        commit("SET_MILESTONES_ATTIBUTES", null)
        let id = shipmentDetails.default.state.shipments_details.id;
        let attempt = false
        async function proceed() {

            await axios.get(`/events/${mbl_num}?standard=true&id=${id}`)
            .then(async res => {
                if (res.status === 200) {
                    if (res.data) {
                        let allResponseData = res.data
                        let getContainers = allResponseData.containers
                        let getEtaLogs = allResponseData.eta_logs

                        let getContainersKeys = Object.keys(getContainers)
                        let event_groups = {}               
                        let new_event_groups = []
                        let lastFreeDayData = []
                        let pickupAppointmentData = []
                        let releasedAtTeminalData = []
                        let trackingContainerDetails = []
                        let perDiemData = []

                        let defaultMilestoneData = []

                        // MILESTONES
                        let fetchingMilestonesDone = false

                        let containerKeysLength = getContainersKeys.length
                        let containerKeysCounter = 0
                        let locode = [] 

                        // For milestones attributes
                        if (typeof allResponseData.attributes !== 'undefined') {
                            commit("SET_MILESTONES_ATTIBUTES", allResponseData.attributes)
                        }

                        if (getContainersKeys.length > 0) {
                            let milestones = await axios.get(`/milestones/${mbl_num}?standard=true`)

                            getContainersKeys.map(getKey => {
                                try {
                                    if (typeof milestones.data !== 'undefined' && 
                                        typeof milestones.data[getKey] !== 'undefined') {

                                        let milestoneLists = milestones.data[getKey]

                                        if (typeof milestoneLists.data !== 'undefined') {
                                            if (typeof milestoneLists.included !== 'undefined') {
                                                milestoneLists = milestoneLists.data.concat(milestoneLists.included)
                                            } else {
                                                milestoneLists = milestoneLists.data
                                            }
                                        } else {
                                            if (typeof milestoneLists.included !== 'undefined') {
                                                milestoneLists = milestoneLists.included
                                            } else {
                                                milestoneLists = []
                                            }
                                        }
                                        getContainers[getKey].milestones = milestoneLists

                                    } else
                                        getContainers[getKey].milestones = []
                                    

                                    if (typeof getContainers[getKey].milestones !== 'undefined' && 
                                        getContainers[getKey].milestones.length > 0) {

                                        var getData = getContainers[getKey].milestones

                                        getData.map(data => {
                                            let { ...otherData } = getContainers[getKey].milestones

                                            let extraData = Object.keys(otherData)
                                            let putData = []
                                            let locationCode = []  
                                            locode['transshipment_loaded'] = []
                                            locode['transshipment_arrived'] = []
                                            locode['transshipment_discharged'] = []
                                            locode['transshipment_departed'] = []


                                            let isArray = false

                                            if (extraData.length > 0) {
                                                extraData.map (ed => {
                                                    if (ed == '0') {
                                                        isArray = true
                                                        putData.push(otherData[ed])
                                                    }

                                                    let attrName = otherData[ed].attributes.event
                                                    attrName = attrName.replace("container.transport.","")
                                                    otherData[ed].attributes.event = attrName
                                                  
                                                    if(attrName == 'transshipment_loaded' || attrName == 'transshipment_arrived' || attrName == 'transshipment_discharged' || attrName == 'transshipment_departed'){
                                                        let temp = {event: otherData[ed].attributes.event, locode: otherData[ed].attributes.location_locode}
                                                        locode[attrName].push(temp)
                                                    }
                                                })

                                                //if not an array
                                                if ( !isArray )
                                                    putData.push(otherData)
                                            }                                   

                                            // check dates if not null
                                            let date = null

                                            if (data.attributes.timestamp !== null) {
                                                date = data.attributes.timestamp
                                            } else {
                                                date = null
                                            }

                                            if (typeof event_groups[data.attributes.event] == 'undefined' ) {
                                                event_groups[data.attributes.event] = {}

                                                locationCode = []    
                                                if(typeof locode[data.attributes.event] !== 'undefined'){
                                                    let attr = locode[data.attributes.event];
                                                    let getLockey = Object.keys(attr)
                                                    if(getLockey.length > 0){
                                                        getLockey.map (ed => {
                                                            let code = attr[ed].locode;
                                                            methods.getTerminal(code).then( res => {
                                                                locode[data.attributes.event][ed].location = res
                                                                locationCode.push(res)
                                                            }).catch(e => {
                                                                console.log(e)
                                                            })
                                                        })
                                                    }

                                                }

                                                event_groups[data.attributes.event] = {
                                                    event: data.attributes.event,
                                                    event_name: data.attributes.original_event,
                                                    data: [],
                                                    actual_on: data.attributes.actual_on,
                                                    actual_at: data.attributes.actual_at,
                                                    estimated: data.attributes.estimated_at,
                                                    locode: locationCode
                                                }

                                                event_groups[data.attributes.event].data.push({
                                                    container_num: getKey,
                                                    date: date,
                                                    data: putData,
                                                    locode: locationCode 
                                                })

                                            } else {
                                                // find duplicate container
                                                let findDuplicateEvent = _.find(event_groups[data.attributes.event].data, {
                                                    container_num: getKey
                                                })

                                                event_groups[data.attributes.event].actual_on = data.attributes.actual_on
                                                event_groups[data.attributes.event].actual_at = data.attributes.actual_at
                                                event_groups[data.attributes.event].estimated_at = data.attributes.estimated_at

                                                if (typeof findDuplicateEvent == 'undefined') {
                                                    event_groups[data.attributes.event].data.push({
                                                        container_num: getKey,
                                                        date: date,
                                                        data: putData,
                                                        locode: locationCode
                                                    })  
                                                }
                                            }
                                        })
                                    }
                                    containerKeysCounter++

                                } catch(e) {
                                    getContainers[getKey].milestone = []
                                    containerKeysCounter++
                                }

                                // last free day
                                if(typeof getContainers[getKey].last_free_day !== 'undefined') {
                                    if (getContainers[getKey].last_free_day !== null) {
                                        lastFreeDayData.push({
                                            container_num: getKey,
                                            date: getContainers[getKey].last_free_day
                                        })
                                    } else {
                                        lastFreeDayData.push({
                                            container_num: getKey,
                                            date: null
                                        })
                                    }
                                                                        
                                    if (typeof event_groups['last_free_day'] == 'undefined' ) {
                                        event_groups['last_free_day'] = {}

                                        event_groups['last_free_day'] = {
                                            event: 'last_free_day',
                                            event_name: 'last_free_day',
                                            data: lastFreeDayData
                                        }
                                    }                                   
                                }

                                // appointment
                                if(typeof getContainers[getKey].pickup_appointment_at !== 'undefined') {
                                    if (getContainers[getKey].pickup_appointment_at !== "") {
                                        pickupAppointmentData.push({
                                            container_num: getKey,
                                            date: getContainers[getKey].pickup_appointment_at
                                        })
                                    }

                                    if (typeof event_groups['appointment'] == 'undefined' ) {
                                        event_groups['appointment'] = {}

                                        event_groups['appointment'] = {
                                            event: 'appointment',
                                            event_name: 'appointment',
                                            data: pickupAppointmentData
                                        }
                                    }
                                }

                                // released
                                if(typeof getContainers[getKey].released_at_terminal !== 'undefined') {
                                    releasedAtTeminalData.push({
                                        container_num: getKey,
                                        data: getContainers[getKey].holds !== null ? getContainers[getKey].holds : [],
                                        releasedEvent: getContainers[getKey].released_at_terminal,
                                        date: null,
                                        fees: getContainers[getKey].fees !== null ? getContainers[getKey].fees : [],
                                    })

                                    if (typeof event_groups['released_at_terminal'] == 'undefined' ) {
                                        event_groups['released_at_terminal'] = {}

                                        event_groups['released_at_terminal'] = {
                                            event: 'released_at_terminal',
                                            event_name: 'released_at_terminal',
                                            data: releasedAtTeminalData
                                        }
                                    }
                                }   

                                // container details
                                if(typeof getContainers[getKey].container_details !== 'undefined') {
                                    trackingContainerDetails.push({
                                        container_num: getKey,
                                        ...getContainers[getKey].container_details
                                    })
                                }
                                //add per diem date
                                if(typeof getContainers[getKey].per_diem_date !== 'undefined') {
                                    if (getContainers[getKey].per_diem_date !== "") {
                                        perDiemData.push({
                                            container_num: getKey,
                                            date: getContainers[getKey].per_diem_date
                                        })
                                    }

                                    if (typeof event_groups['per_diem_date'] == 'undefined' ) {
                                        event_groups['per_diem_date'] = {}

                                        event_groups['per_diem_date'] = {
                                            event: 'per_diem_date',
                                            event_name: 'per_diem_date',
                                            data: perDiemData
                                        }
                                    }
                                }
                                //end per diem date
                            })                   
                        } else {
                            let milestones = await axios.get(`/milestones/${mbl_num}?standard=true`)

                            if (typeof milestones !== 'undefined' && 
                                milestones.data !== 'undefined' && 
                                milestones.data.length === 0) {

                                if (shipmentDetails.default.state !== 'undefined' && 
                                    shipmentDetails.default.state !== null) {
                                    let { shipments_details } = shipmentDetails.default.state
                                    let finalType = null

                                    if (shipments_details.schedules_group_bookings !== 'undefined' && 
                                        shipments_details.schedules_group_bookings !== '' && 
                                        shipments_details.schedules_group_bookings !== null) {

                                        let schedulesGroupBookings = Array.isArray(shipments_details.schedules_group_bookings) 
                                                                        ? shipments_details.schedules_group_bookings 
                                                                        : JSON.parse(shipments_details.schedules_group_bookings);

                                        finalType = (typeof _.find(schedulesGroupBookings, e => 
                                            (e.is_confirmed === 1)) !== 'undefined')
                                        ? _.find(schedulesGroupBookings, e => (e.is_confirmed === 1))
                                        : null
                                    }

                                    if (finalType !== null && finalType.type === 'FCL') { 
                                        // check if shipment is FCL, proceed, else fetching milestones done
                                        if (shipments_details.containers_group_untracked !== null) {
                                            // PARSE CONTAINERS GROUP UNTRACKED
                                            shipments_details.containers_group_untracked =
                                            typeof shipments_details.containers_group_untracked !== 'undefined' &&
                                                shipments_details.containers_group_untracked !== '' &&
                                                shipments_details.containers_group_untracked !== null &&
                                                JSON.parse(shipments_details.containers_group_untracked.length) > 0
                                                ? JSON.parse(shipments_details.containers_group_untracked)
                                                : []
    
                                            let containerLists = shipments_details.containers_group_untracked.containers
    
                                            if (containerLists.length > 0) {
                                                containerKeysLength = containerLists.length
    
                                                containerLists.map(v => {
                                                    let eventsInitital = [
                                                        'empty_out', 
                                                        'full_in', 
                                                        'vessel_discharged', 
                                                        'released_at_terminal', 
                                                        'last_free_day', 
                                                        'full_out', 
                                                        'empty_in', 
                                                        'appointment',
                                                        'per_diem_date'
                                                    ]
    
                                                    if (eventsInitital.length > 0) {
                                                        eventsInitital.map(eventsName => {
                                                            let event_name_capitalize = ''
                                                            let date = null
                                                            let putData = []
                                                            let locode = []
    
                                                            if (eventsName === 'empty_out') {
                                                                event_name_capitalize = 'Empty Gated Out'
                                                                date = v.empty_out !== null ? v.empty_out : null
                                                            }
    
                                                            if (eventsName === 'full_in') {
                                                                event_name_capitalize = 'Full Gated In'
                                                                date = v.pod_full_in_at !== null ? v.pod_full_in_at : null
                                                            }
    
                                                            // if (eventsName === 'vessel_loaded') { // optional if to put
                                                            //     event_name_capitalize = 'Vessel Loaded'
                                                            //     date = null
                                                            //     putData = null
                                                            // }
    
                                                            if (eventsName === 'appointment') {
                                                                event_name_capitalize = 'Pickup Appointment'
                                                                date = typeof v.pickup_appointment_at !== 'undefined' && v.pickup_appointment_at !== null ? v.pickup_appointment_at : null
                                                            }

                                                            if (eventsName === 'per_diem_date') {
                                                                event_name_capitalize = 'Per Diem Date'
                                                                date = typeof v.per_diem_date !== 'undefined' && v.per_diem_date !== null ? v.per_diem_date : null
                                                            }
    
                                                            if (eventsName === 'vessel_discharged') {
                                                                event_name_capitalize = 'Discharged'
                                                                date = v.pod_discharged_at !== null ? v.pod_discharged_at : null
                                                            }
    
                                                            if (eventsName === 'full_out') {
                                                                event_name_capitalize = 'Full Gated Out'
                                                                date = v.pod_full_out_at !== null ? v.pod_full_out_at : null
                                                            }
    
                                                            if (eventsName === 'empty_in') {
                                                                event_name_capitalize = 'Empty Gated In'
                                                                date = v.pod_empty_returned_at !== null ? v.pod_empty_returned_at : null
                                                            }
    
                                                            if (eventsName === 'last_free_day') {
                                                                event_name_capitalize = 'Last Free Day'
    
                                                                lastFreeDayData.push({
                                                                    container_num: v.container_num,
                                                                    date: v.pickup_lfd !== null ? v.pickup_lfd : null
                                                                })
                                                                                                    
                                                                if (typeof event_groups['last_free_day'] == 'undefined' ) {
                                                                    event_groups['last_free_day'] = {}
                            
                                                                    event_groups['last_free_day'] = {
                                                                        event: 'last_free_day',
                                                                        event_name: 'last_free_day',
                                                                        data: lastFreeDayData
                                                                    }
                                                                } 
                                                            }
    
                                                            if (eventsName === 'released_at_terminal') {
                                                                event_name_capitalize = 'Released At Terminal'
    
                                                                releasedAtTeminalData.push({
                                                                    container_num: v.container_num,
                                                                    data: v.holds_at_pod_terminal,
                                                                    releasedEvent: typeof v.available_for_pickup !== 'undefined' && v.available_for_pickup ? true : false,
                                                                    date: null,
                                                                    fees: v.fees_at_pod_terminal,
                                                                })
    
                                                                if (typeof event_groups['released_at_terminal'] == 'undefined' ) {
                                                                    event_groups['released_at_terminal'] = {}
                            
                                                                    event_groups['released_at_terminal'] = {
                                                                        event: 'released_at_terminal',
                                                                        event_name: 'released_at_terminal',
                                                                        data: releasedAtTeminalData
                                                                    }
                                                                }
                                                            }
                                                                                                        
                                                            if (typeof event_groups[eventsName] == 'undefined' ) {
                                                                if (eventsName !== 'released_at_terminal' && 
                                                                    eventsName !== 'last_free_day') {
                                                                    event_groups[eventsName] = {}
                        
                                                                    event_groups[eventsName] = {
                                                                        event: eventsName,
                                                                        event_name: event_name_capitalize,
                                                                        data: [],
                                                                        locode: []
                                                                    }
                            
                                                                    if (date !== null) {
                                                                        event_groups[eventsName].data.push({
                                                                            container_num: v.container_num,
                                                                            date: date,
                                                                            data: putData,
                                                                            locode: locode
                                                                        })
                                                                    }
                                                                }         
                                                            } else {
                                                                // find duplicate container
                                                                let findDuplicateEvent = _.find(event_groups[eventsName].data, {
                                                                    container_num: v.container_num
                                                                })
                        
                                                                if (typeof findDuplicateEvent == 'undefined') {
                                                                    if (eventsName !== 'released_at_terminal' && 
                                                                        eventsName !== 'last_free_day') {
                                                                        if (date !== null) {
                                                                            event_groups[eventsName].data.push({
                                                                                container_num: v.container_num,
                                                                                date: date,
                                                                                data: putData,
                                                                                locode:locode
                                                                            })
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }) 
                                                    }
                                                    
                                                    containerKeysCounter++
                                                })
                                            } else {
                                                fetchingMilestonesDone = true
                                            }
                                        } else {
                                            fetchingMilestonesDone = true
                                        }
                                    } else {
                                        fetchingMilestonesDone = true
                                    }
                                }
                            } else {
                                fetchingMilestonesDone = true
                            }
                        }                        

                        let t = setInterval(() => {
                            if (containerKeysCounter == containerKeysLength) {
                                fetchingMilestonesDone = true
                                clearInterval(t)
                            }
                        }, 200)

                        let tt = setInterval( () => {
                            if ( fetchingMilestonesDone ) {
                                let get_event_groups_data = Object.keys(event_groups)

                                if (get_event_groups_data.length > 0) {
                                    get_event_groups_data.map (gegd => {
                                        let { data, actual_at, actual_on, estimated_at, locode } = event_groups[gegd]
                                        
                                        let eventName = gegd.replace("container.transport.","")

                                        // find if there are dates that are null -> set completed to false if there are
                                        let isCompleted = false 
                                        let findDateNullIndex = -1

                                        if (data !== null && data.length > 0) {
                                            findDateNullIndex = _.findIndex(data, e => (e.date == null))
                                            if (findDateNullIndex === -1) {
                                                isCompleted = true
                                            } else {
                                                isCompleted = false
                                            }
                                        } else {
                                            isCompleted = false
                                        }

                                        let findReleasedFalseIndexInTerminal = _.findIndex(data, e => (e.releasedEvent == false))

                                        let className = ''                                        
                                        
                                        if (gegd == 'released_at_terminal') {
                                            if (findReleasedFalseIndexInTerminal === -1) {
                                                isCompleted = true
                                            } else {
                                                isCompleted = false
                                            }
                                        }

                                        new_event_groups.push({
                                            event: gegd,
                                            event_name: eventName,
                                            isCompleted: isCompleted,
                                            class: className,
                                            other_data: data,
                                            actual_at: (typeof actual_at!=='undefined') ? actual_at : null,
                                            actual_on:(typeof actual_on!=='undefined') ? actual_on : null,
                                            estimated_at: (typeof estimated_at!=='undefined') ? estimated_at : null,
                                            locode: locode
                                        })

                                    })
                                }

                                if (shipmentDetails.default.state !== 'undefined' && shipmentDetails.default.state !== null) {
                                    let { shipments_details } = shipmentDetails.default.state

                                    if (shipments_details !== 'undefined') {
                                        let bookingCreated = shipments_details.created_at !== 'undefined' 
                                                            ? shipments_details.created_at : null

                                        let bookingConfirmed = shipments_details.booking_confirmed == 1 
                                                            ? shipments_details.booking_confirmed_at : null

                                        let is_tracking = shipments_details.is_tracking_shipment

                                        let empty_out = methods.checkMilestoneData(new_event_groups, 'empty_out')
                                        let full_in = methods.checkMilestoneData(new_event_groups, 'full_in')
                                        let vessel_loaded = methods.checkMilestoneData(new_event_groups, 'vessel_loaded')
                                        let released_at_terminal = methods.checkMilestoneData(new_event_groups, 'released_at_terminal')
                                        let transshipment_arrived = methods.checkMilestoneData(new_event_groups, 'transshipment_arrived')
                                        let transshipment_discharged = methods.checkMilestoneData(new_event_groups, 'transshipment_discharged')
                                        let transshipment_loaded = methods.checkMilestoneData(new_event_groups, 'transshipment_loaded')
                                        let transshipment_departed = methods.checkMilestoneData(new_event_groups, 'transshipment_departed')
                                        let vessel_discharged = methods.checkMilestoneData(new_event_groups, 'vessel_discharged')
                                        let last_free_day = methods.checkMilestoneData(new_event_groups, 'last_free_day')
                                        let appointment = methods.checkMilestoneData(new_event_groups, 'appointment')
                                        let full_out = methods.checkMilestoneData(new_event_groups, 'full_out')
                                        let empty_in = methods.checkMilestoneData(new_event_groups, 'empty_in')
                                        
                                        let rail_loaded = methods.checkMilestoneData(new_event_groups, 'rail_loaded')
                                        let rail_arrived = methods.checkMilestoneData(new_event_groups, 'rail_arrived')
                                        let rail_departed = methods.checkMilestoneData(new_event_groups, 'rail_departed')
                                        let rail_unloaded = methods.checkMilestoneData(new_event_groups, 'rail_unloaded')

                                        let per_diem_date = methods.checkMilestoneData(new_event_groups, 'per_diem_date')
                                        
                                        defaultMilestoneData = [
                                            {
                                                event_name: 'Booking Created',
                                                event: 'booking_created',
                                                refName: 'booking_created',
                                                className: 'booking_created',
                                                date: bookingCreated,
                                                isCompleted: bookingCreated !== null ? true : false,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'booking_created')

                                            },
                                            {
                                                event_name: 'Booking Confirmed',
                                                event: 'booking_confirmed',
                                                refName: 'booking_confirmed',
                                                className: 'booking_confirmed',
                                                date: bookingConfirmed,
                                                isCompleted: bookingConfirmed !== null ? true : false,
                                                // isShow: 'd-flex',
                                                isShow: is_tracking === 1 ? 'd-none' : 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'booking_confirmed')
                                            },
                                            {
                                                event_name: 'Empty Gated Out',
                                                event: 'empty_out',
                                                refName: 'empty_out',
                                                className: 'empty_out',
                                                date: empty_out.latestDate,
                                                data: empty_out.data,
                                                isCompleted: empty_out.isCompleted,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'empty_out')
                                            },
                                            {
                                                event_name: 'Full Gated In',
                                                event: 'full_in',
                                                refName: 'full_in',
                                                className: 'full_in',
                                                date: full_in.latestDate,
                                                data: full_in.data,
                                                isCompleted: full_in.isCompleted,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'full_in')
                                            },
                                            {
                                                event_name: 'Vessel Loaded',
                                                event: 'vessel_loaded',
                                                refName: 'vessel_loaded',
                                                className: 'vessel_loaded',
                                                date: vessel_loaded.latestDate,
                                                data: vessel_loaded.data,
                                                isCompleted: vessel_loaded.isCompleted,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'vessel_loaded')
                                            },
                                            {
                                                event_name: 'Transshipment Arrived',
                                                event: 'transshipment_arrived',
                                                refName: 'transshipment_arrived',
                                                className: 'transshipment_arrived',
                                                date: transshipment_arrived.latestDate,
                                                data: transshipment_arrived.data,
                                                isCompleted: transshipment_arrived.isCompleted,
                                                isShow: transshipment_arrived.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'transshipment_arrived'),
                                                locode: transshipment_arrived.locode
                                            },
                                            {
                                                event_name: 'Transshipment Discharged',
                                                event: 'transshipment_discharged',
                                                refName: 'transshipment_discharged',
                                                className: 'transshipment_discharged',
                                                date: transshipment_discharged.latestDate,
                                                data: transshipment_discharged.data,
                                                isCompleted: transshipment_discharged.isCompleted,
                                                isShow: transshipment_discharged.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'transshipment_discharged'),
                                                locode: transshipment_discharged.locode
                                            },
                                            {
                                                event_name: 'Transshipment Loaded',
                                                event: 'transshipment_loaded',
                                                refName: 'transshipment_loaded',
                                                className: 'transshipment_loaded',
                                                date: transshipment_loaded.latestDate,
                                                data: transshipment_loaded.data,
                                                isCompleted: transshipment_loaded.isCompleted,
                                                isShow: transshipment_loaded.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'transshipment_loaded'),
                                                locode: transshipment_loaded.locode
                                            },
                                            {
                                                event_name: 'Transshipment Departed',
                                                event: 'transshipment_departed',
                                                refName: 'transshipment_departed',
                                                className: 'transshipment_departed',
                                                date: transshipment_departed.latestDate,
                                                data: transshipment_departed.data,
                                                isCompleted: transshipment_departed.isCompleted,
                                                isShow: transshipment_departed.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'transshipment_departed'),
                                                locode: transshipment_departed.locode
                                            },
                                            {
                                                event_name: 'Discharged',
                                                event: 'vessel_discharged',
                                                refName: 'vessel_discharged',
                                                className: 'vessel_discharged',
                                                date: vessel_discharged.latestDate,
                                                data: vessel_discharged.data,
                                                isCompleted: vessel_discharged.isCompleted,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'vessel_discharged')
                                            },                                    
                                            {
                                                event_name: 'Released At Terminal',
                                                event: 'released_at_terminal',
                                                refName: 'released_at_terminal',
                                                className: `released_at_terminal ${released_at_terminal.class}`,
                                                date: released_at_terminal.latestDate,
                                                data: released_at_terminal.data,
                                                isCompleted: released_at_terminal.isCompleted,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'released_at_terminal')
                                            },
                                            {
                                                event_name: 'Last Free Day',
                                                event: 'last_free_day',
                                                refName: 'last_free_day',
                                                className: 'last_free_day',
                                                date: last_free_day.latestDate,
                                                data: last_free_day.data,
                                                isCompleted: last_free_day.isCompleted,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'last_free_day')
                                            },
                                            {
                                                event_name: 'Pickup Appointment',
                                                event: 'appointment',
                                                refName: 'appointment',
                                                className: 'appointment',
                                                date: appointment.latestDate,
                                                data: appointment.data,
                                                isCompleted: appointment.isCompleted,
                                                isShow: appointment.data !== null && appointment.data.length !== 0 ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'appointment')
                                            },
                                            {
                                                event_name: 'Full Gated Out',
                                                event: 'full_out',
                                                refName: 'full_out',
                                                className: 'full_out',
                                                date: full_out.latestDate,
                                                data: full_out.data,
                                                isCompleted: full_out.isCompleted,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'full_out')
                                            },
                                            {
                                                event_name: 'Empty Gated In',
                                                event: 'empty_in',
                                                refName: 'empty_in',
                                                className: 'empty_in',
                                                date: empty_in.latestDate,
                                                data: empty_in.data,
                                                isCompleted: empty_in.isCompleted,
                                                isShow: 'd-flex',
                                                extraData: methods.getExtraData(new_event_groups,'empty_in')
                                            },
                                            {
                                                event_name: 'Rail Loaded',
                                                event: 'rail_loaded',
                                                refName: 'rail_loaded',
                                                className: 'rail_loaded',
                                                date: rail_loaded.latestDate,
                                                data: rail_loaded.data,
                                                isCompleted: rail_loaded.isCompleted,
                                                isShow: rail_loaded.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups, 'rail_loaded')
                                            },
                                            {
                                                event_name: 'Rail Arrived',
                                                event: 'rail_arrived',
                                                refName: 'rail_arrived',
                                                className: 'rail_arrived',
                                                date: rail_arrived.latestDate,
                                                data: rail_arrived.data,
                                                isCompleted: rail_arrived.isCompleted,
                                                isShow: rail_arrived.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups, 'rail_arrived')
                                            },
                                            {
                                                event_name: 'Rail Departed',
                                                event: 'rail_departed',
                                                refName: 'rail_departed',
                                                className: 'rail_departed',
                                                date: rail_departed.latestDate,
                                                data: rail_departed.data,
                                                isCompleted: rail_departed.isCompleted,
                                                isShow: rail_departed.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups, 'rail_departed')
                                            },
                                            {
                                                event_name: 'Rail Unloaded',
                                                event: 'rail_unloaded',
                                                refName: 'rail_unloaded',
                                                className: 'rail_unloaded',
                                                date: rail_unloaded.latestDate,
                                                data: rail_unloaded.data,
                                                isCompleted: rail_unloaded.isCompleted,
                                                isShow: rail_unloaded.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups, 'rail_unloaded')
                                            },
                                            {
                                                event_name: 'Per Diem Date',
                                                event: 'per_diem_date',
                                                refName: 'per_diem_date',
                                                className: 'per_diem_date',
                                                date: per_diem_date.latestDate,
                                                data: per_diem_date.data,
                                                isCompleted: per_diem_date.isCompleted,
                                                isShow: per_diem_date.data !== null && per_diem_date.data.length !== 0 ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'per_diem_date')
                                            }
                                        ]

                                        let except_these_events = []

                                        defaultMilestoneData.map(dmd => {
                                            except_these_events.push(dmd.event)
                                        })

                                        commit("SET_MILESTONES_OTHER_EVENTS", except_these_events)


                                        // ETA logs
                                        if (typeof getEtaLogs !== 'undefined' && 
                                            getEtaLogs !== null && 
                                            getEtaLogs.length !== 'undefined' && 
                                            getEtaLogs.length !== 0) {

                                            let latestEta = getEtaLogs[getEtaLogs.length - 1]
                                            latestEta.event_name = 'ETA Updated'
                                            latestEta.event = 'eta_logs'
                                            latestEta.refName = 'eta_logs'
                                            latestEta.className = 'eta_logs'
                                            latestEta.date = latestEta.created_at
                                            latestEta.isCompleted = false
                                            latestEta.isShow = 'd-flex'
                                            latestEta.data = latestEta
                                            latestEta.log = latestEta
                                            latestEta.otherEtaData = []

                                            // getEtaLogs.map((value) => {
                                            //     defaultMilestoneData.push({
                                            //         id: value.id,
                                            //         event_name: 'ETA Updated',
                                            //         event: 'eta_logs',
                                            //         refName: 'eta_logs',
                                            //         className: 'eta_logs',
                                            //         date: value.created_at,
                                            //         data: getEtaLogs,
                                            //         log: value,
                                            //         isCompleted: false,
                                            //         isShow: 'd-flex',
                                            //         extraData: value.extraData
                                            //     })
                                            // })

                                            getEtaLogs.map(value => {
                                                if (value.id !== latestEta.id) {
                                                    latestEta.otherEtaData.push({
                                                        id: value.id,
                                                        event_name: 'ETA Updated',
                                                        event: 'eta_logs',
                                                        refName: 'eta_logs',
                                                        className: 'eta_logs',
                                                        date: value.created_at,
                                                        data: getEtaLogs,
                                                        log: value,
                                                        isCompleted: false,
                                                        isShow: 'd-none'
                                                    })
                                                }
                                            })

                                            defaultMilestoneData.push(latestEta)
                                        }
                                    }
                                }        
                                
                                // sort all milestones by date
                                let sortDates = _.orderBy(defaultMilestoneData, ['date'],['asc'])
                                let findDischargedEvent = _.findIndex(sortDates, e => (e.event == 'vessel_discharged'))
                                let findTerminalEvent = _.findIndex(sortDates, e => (e.event == 'released_at_terminal'))
                                let findLastFDEvent = _.findIndex(sortDates, e => (e.event == 'last_free_day'))

                                let dischargedIndex = findDischargedEvent + 1
                                methods.move(sortDates, findTerminalEvent, dischargedIndex)   

                                //start changes on rails milestone Jan 27
                                let findRailLoadData = _.find(sortDates, e => (e.event == 'rail_loaded'))
                                let findRailDepartedData = _.find(sortDates, e => (e.event == 'rail_departed'))
                                let findRailArrivedData = _.find(sortDates, e => (e.event=='rail_arrived'))
                                let findRailUnloadedData = _.find(sortDates, e => (e.event=='rail_unloaded'))
                                
                                if(findRailLoadData.isShow == 'd-flex' || findRailDepartedData.isShow == 'd-flex' || findRailArrivedData.isShow == 'd-flex' || 
                                    findRailUnloadedData.isShow == 'd-flex'){
                                    // move the last free day after the full gate out / full_out only if there's a rails
                                    let findFullOutEvent = _.findIndex(sortDates, e => (e.event == 'full_out'))
                                    methods.move(sortDates, findFullOutEvent, findLastFDEvent + 1)   
                                }else{
                                     // move the last free day after the released at terminal event
                                    let lfdIndex = findLastFDEvent + 1
                                    methods.move(sortDates, findTerminalEvent, lfdIndex)
                                }
                                //end changes on rails milestone Jan 27


                                // move the last free day after the released at terminal event
                                // let lfdIndex = findLastFDEvent + 1
                                // methods.move(sortDates, findTerminalEvent, lfdIndex)


                                // find gated out data and if it's completed, set released at terminal to complete
                                let findTerminalEventData = _.find(sortDates, e => (e.event=='released_at_terminal'))
                                let findFullGatedOutEventData = _.find(sortDates, e => (e.event=='full_out'))

                                if (typeof findFullGatedOutEventData.isCompleted !== 'undefined') {
                                    if (findFullGatedOutEventData.isCompleted) {
                                        if (typeof findTerminalEventData !== 'undefined') {
                                            if (findTerminalEventData.isCompleted == false) {  
                                                findTerminalEventData.isCompleted = true

                                                findTerminalEventData.data.map(val => {
                                                    val.releasedEvent = true
                                                })
                                            }
                                        }
                                    }
                                }                               

                                // FOR TRACKING CONTAINERS
                                if (typeof trackingContainerDetails !== 'undefined' && 
                                    trackingContainerDetails !== null && 
                                    trackingContainerDetails.length !== 'undefined' && 
                                    trackingContainerDetails.length !== 0) {

                                    commit("SET_TRACKING_CONTAINER_DETAILS", trackingContainerDetails)                                    
                                }

                                commit("SET_MILESTONES", sortDates)
                                commit("SET_MILESTONES_NEW_LOADING", false)
                                commit("SET_MILESTONES_LOADING", false)
                                clearInterval(tt)
                            }
                                
                        },200)
                    }
                }
            })
            .catch(err => {
                if (typeof err.message!=='undefined') {
                    if ( !attempt ){
                        attempt = true
                        let t =setInterval(() => {
                            if ( !store.getters.getIsRefreshing )   {
                                proceed()
                                clearInterval(t)
                            }
                        },300)
                    } else {
                        commit('SET_MILESTONES_LOADING', false)
                        return Promise.reject('Token has been expired. Please reload the page.')
                    }
                } else {
                    commit('SET_MILESTONES_LOADING', false)
                    return Promise.reject(err)
                }

                /*
                commit("SET_MILESTONES_LOADING", false)
                return Promise.reject(err)
                */
            })
        }
        proceed()

		
	},
}

const mutations = {
    SET_MILESTONES_OTHER_EVENTS: (state, payload) => {
        state.milestones_other_events = payload
    },
	SET_MILESTONES: (state, payload) => {
		state.milestones = payload
	},
	SET_MILESTONES_LOADING: (state, payload) => {
		state.milestones_loading = payload
	},
    SET_MILESTONES_NEW_LOADING: (state, payload) => {
        state.milestones_new_loading = payload
    },
    SET_TRACKING_CONTAINER_DETAILS: (state, payload) => {
		state.trackingContainerData = payload
	},
    SET_MILESTONES_ATTIBUTES: (state, payload) => {
		state.milestoneAttibutes = payload
	},
}

export default {
	state,
	getters,
	actions,
	mutations,
	methods
}