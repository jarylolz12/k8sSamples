import axios from "axios"
import _ from 'lodash'
import * as shipmentDetails from './shipmentDetails'
//import milestoneDataCheck from '../../helpers/milestoneData'
import store from '../'

const state = {
	milestones: [],
	milestones_loading: true,
	milestonesOtherData: [],
    milestonesOtherDataLoading: false
}

const getters = {
	getMilestonesData: state => state.milestones,
	getMilestonesLoading: state => state.milestones_loading,
}

const actions = {
	fetchMilestones: async ({
		commit,
	}, mbl_num) => {
		// await axios.get(`/milestones/${mbl_num}?standard=true`)
		await axios.get(`/events/${mbl_num}?standard=true`)
			.then(res => {
				if (res.status === 200) {
					if (res.data) {
						// MILESTONES DATA - containers
						let event_groups = {}					
						let new_event_groups = []					
						let getResponse = res.data.containers
						let getKeys = Object.keys(getResponse)				
						let lastFreeDayData = []
						let pickupAppointmentData = []
						let releasedAtTeminalData = []

						// ETA Logs
						let eta_data = res.data.eta_logs
						let eta_logs = []

						if (getKeys.length > 0) {
							getKeys.map(getKey => {
								// milestone
								if (typeof getResponse[getKey].milestones !== 'undefined' && 
									getResponse[getKey].milestones.length > 0) {

									var getData = getResponse[getKey].milestones

									getData.map(data => {
										let { ...otherData } = getResponse[getKey].milestones

										let extraData = Object.keys(otherData)
										let putData = []

										let isArray = false

										if (extraData.length > 0) {
											extraData.map (ed => {
												if (ed == '0') {
													isArray = true
													putData.push(otherData[ed])
												}
											})

											//if not an array
											if ( !isArray )
												putData.push(otherData)
										}									

										// check dates if not null
										let date = null

										// milestone raw dates
										// if (data.attributes.actual_at !== null) {
										// 	date = data.attributes.actual_at
										// } else {
										// 	if (data.attributes.actual_on !== null) {
										// 		date = data.attributes.actual_on
										// 	} else {
										// 		date = null
										// 	}
										// }

										if (data.attributes.timestamp !== null) {
											date = data.attributes.timestamp
										} else {
											date = null
										}

										if (typeof event_groups[data.attributes.event] == 'undefined' ) {
											event_groups[data.attributes.event] = {}

											event_groups[data.attributes.event] = {
												event: data.attributes.event,
												event_name: data.attributes.original_event,
												data: []
											}

											event_groups[data.attributes.event].data.push({
												container_num: getKey,
												date: date,
												data: putData
											})

										} else {
											// find duplicate container
											let findDuplicateEvent = _.find(event_groups[data.attributes.event].data, {
												container_num: getKey
											})

											if (typeof findDuplicateEvent == 'undefined') {
												event_groups[data.attributes.event].data.push({
													container_num: getKey,
													date: date,
													data: putData
												})	
											}
										}
									})
								}

								// last free day
								if(typeof getResponse[getKey].last_free_day !== 'undefined') {
									if (getResponse[getKey].last_free_day !== null) {
										lastFreeDayData.push({
											container_num: getKey,
											date: getResponse[getKey].last_free_day
										})
									} else {
										lastFreeDayData.push({
											container_num: null,
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
								if(typeof getResponse[getKey].pickup_appointment_at !== 'undefined') {
									if (getResponse[getKey].pickup_appointment_at !== "") {
										pickupAppointmentData.push({
											container_num: getKey,
											date: getResponse[getKey].pickup_appointment_at
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
								if(typeof getResponse[getKey].released_at_terminal !== 'undefined') {
									releasedAtTeminalData.push({
										container_num: getKey,
										data: getResponse[getKey].holds,
										releasedEvent: getResponse[getKey].released_at_terminal,
										date: null
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
							})
						}

						if (typeof eta_data !== 'undefined' && eta_data !== null && eta_data.length !== 'undefined' && eta_data.length !== 0) {
							if (eta_data.length == 1) {
								eta_logs.push(eta_data[0])
							} else if (eta_data.length > 1) {
								eta_logs.push(eta_data[eta_data.length - 1])
							}
						}

						let get_event_groups_data = Object.keys(event_groups)

						if (get_event_groups_data.length > 0) {
							get_event_groups_data.map (gegd => {
								let { data } = event_groups[gegd]

								let eventName = gegd.replace("container.transport.","")

								// find if there are dates that are null -> set completed to false if there are
								let isCompleted = false						
								let isReleased = false	
								let findDateNullIndex = _.findIndex(data, e => (e.date == null))
								let findReleasedFalseIndexInTerminal = _.findIndex(data, e => (e.releasedEvent == false))
								let findReleasedDataNull = _.findIndex(data, e => (typeof e.data !== 'undefined' && e.data !== null && e.data.length !== 'undefined' && e.data.length == 0)) 
								let className = ''

								if (findDateNullIndex === -1) {
									isCompleted = true
								} else {
									isCompleted = false
								}
								
								if (gegd == 'released_at_terminal') {
									if (findReleasedFalseIndexInTerminal === -1) {
										isReleased = true
										isCompleted = true
									} else {
										isReleased = false
										isCompleted = false
									}

									if (findReleasedDataNull === -1) {
										className = 'released has-data'
									} else {
										className = 'released has-null'
									}
								}

								new_event_groups.push({
									event: gegd,
									event_name: eventName,
									isCompleted: isCompleted,
									isReleased: isReleased,
									class: className,
									other_data: data
								})
							})
						}

						let newMilestoneData = []
						
						if (shipmentDetails.default.state !== 'undefined' && shipmentDetails.default.state !== null) {
							let { shipments_details } = shipmentDetails.default.state

							if (shipments_details !== 'undefined') {
								let bookingCreated = shipments_details.created_at !== 'undefined' 
													? shipments_details.created_at : null
								
								let bookingConfirmed = shipments_details.booking_confirmed == 1 
													? shipments_details.booking_confirmed_at : null
								
								let emptyGatedOut = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='empty_out')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='empty_out')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='empty_out')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false,
								}

								let fullGatedIn = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='full_in')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='full_in')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='full_in')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								let vesselLoaded = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='vessel_loaded')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='vessel_loaded')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='vessel_loaded')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								let released = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='released_at_terminal')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='released_at_terminal')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='released_at_terminal')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='released_at_terminal')).isCompleted : false,
									isReleased: (typeof _.find(new_event_groups, e => (e.event_name=='released_at_terminal')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='released_at_terminal')).isReleased : false,
									class: (typeof _.find(new_event_groups, e => (e.event_name=='released_at_terminal')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='released_at_terminal')).class : 'released',
								}

								let eta_updated = {
									data: eta_logs.length !== 0 ? eta_logs : null,
								}

								let transshipmentArrived = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='transshipment_arrived')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='transshipment_arrived')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='transshipment_arrived')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								let transshipmentDischarged = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='transshipment_discharged')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='transshipment_discharged')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='transshipment_discharged')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								let transshipmentLoaded = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='transshipment_loaded')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='transshipment_loaded')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='transshipment_loaded')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								let transshipmentDeparted = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='transshipment_departed')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='transshipment_departed')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='transshipment_departed')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								let vesselDischarged = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='vessel_discharged')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='vessel_discharged')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='vessel_discharged')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								let lastFreeDay = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='last_free_day')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='last_free_day')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='last_free_day')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='last_free_day')).isCompleted : false
								}

								let pickupAppointment = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='appointment')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='appointment')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='appointment')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='appointment')).isCompleted : false
								}

								let fullGatedOut = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='full_out')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='full_out')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='full_out')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								let emptyGatedIn = {
									data: (typeof _.find(new_event_groups, e => (e.event_name=='empty_in')) !== 'undefined') ? _.find(new_event_groups, e => (e.event_name=='empty_in')).other_data : null,
									isCompleted: (typeof _.find(new_event_groups, e => (e.event_name=='empty_in')) !== 'undefined') ? _.find(new_event_groups, e => (e.isCompleted==true)).isCompleted : false
								}

								newMilestoneData = [
									{
										event_name: 'Booking Created',
										event: 'booking_created',
										date: bookingCreated,
										isCompleted: bookingCreated !== null ? true : false,
										isShow: 'd-flex',
										refName: 'booking-created',
										className: 'booking-created'
									},
									{
										event_name: 'Booking Confirmed',
										event: 'booking_confirmed',
										date: bookingConfirmed,
										isCompleted: bookingConfirmed !== null ? true : false,
										isShow: 'd-flex',
										refName: 'booking-confirmed',
										className: 'booking-confirmed'
									},
									{
										event_name: 'Empty Gated Out',
										event: 'empty_out',
										date: null,
										data: emptyGatedOut.data,
										isCompleted: emptyGatedOut.isCompleted,
										// isShow: emptyGatedOut.data !== null ? 'd-flex' : 'd-none',
										isShow: 'd-flex',
										refName: 'empty_out',
										className: 'empty_out'
									},
									{
										event_name: 'Full Gated In',
										event: 'full_in',
										date: null,
										data: fullGatedIn.data,
										isCompleted: fullGatedIn.isCompleted,
										// isShow: fullGatedIn.data !== null ? 'd-flex' : 'd-none',
										isShow: 'd-flex',
										refName: 'full_in',
										className: 'full_in'
									},
									{
										event_name: 'Vessel Loaded',
										event: 'vessel_loaded',
										date: null,
										data: vesselLoaded.data,
										isCompleted: vesselLoaded.isCompleted,
										// isShow: vesselLoaded.data !== null ? 'd-flex' : 'd-none',
										isShow: 'd-flex',
										refName: 'departed',
										className: 'departed'
									},
									{
										event_name: 'Released',
										date: null,
										event: 'released',
										data: released.data,
										isReleased: released.data !== null ? released.isReleased : null,
										isCompleted: released.isCompleted,
										isShow: 'd-flex',
										refName: 'released',
										className: released.class
									},
									{
										event_name: 'ETA Updated',
										event: 'eta_logs',
										data: eta_updated.data,
										isShow: eta_updated.data !== null ? 'd-flex' : 'd-none',
										refName: 'eta_logs',
										className: 'eta_logs'
									},
									{
										event_name: 'Transshipment Arrived',
										event: 'transshipment_arrived',
										date: null,
										data: transshipmentArrived.data,
										isCompleted: transshipmentArrived.isCompleted,
										isShow: transshipmentArrived.data !== null ? 'd-flex' : 'd-none',
										refName: 'transshipment_arrived',
										className: 'check transshipment_arrived'
									},
									{
										event_name: 'Transshipment Discharged',
										event: 'transshipment_discharged',
										date: null,
										data: transshipmentDischarged.data,
										isCompleted: transshipmentDischarged.isCompleted,
										isShow: transshipmentDischarged.data !== null ? 'd-flex' : 'd-none',
										refName: 'transshipment_discharged',
										className: 'check transshipment_discharged'
									},
									{
										event_name: 'Transshipment Loaded',
										event: 'transshipment_loaded',
										date: null,
										data: transshipmentLoaded.data,
										isCompleted: transshipmentLoaded.isCompleted,
										isShow: transshipmentLoaded.data !== null ? 'd-flex' : 'd-none',
										refName: 'transshipment_loaded',
										className: 'check transshipment_loaded'
									},
									{
										event_name: 'Transshipment Departed',
										event: 'transshipment_departed',
										date: null,
										data: transshipmentDeparted.data,
										isCompleted: transshipmentDeparted.isCompleted,
										isShow: transshipmentDeparted.data !== null ? 'd-flex' : 'd-none',
										refName: 'transshipment_departed',
										className: 'check transshipment_departed'
									},
									{
										event_name: 'Discharged',
										event: 'vessel_discharged',
										date: null,
										data: vesselDischarged.data,
										isCompleted: vesselDischarged.isCompleted,
										// isShow: vesselDischarged.data !== null ? 'd-flex' : 'd-none',
										isShow: 'd-flex',
										refName: 'vessel_discharged',
										className: 'vessel_discharged'
									},
									{
										event_name: 'Last Free Day',
										event: 'last_free_day',
										date: null,
										data: lastFreeDay.data,
										isCompleted: lastFreeDay.isCompleted,
										isShow: 'd-flex',
										refName: 'last_free_day',
										className: 'last_free_day'
									},
									{
										event_name: 'Pickup Appointment',
										event: 'appointment',
										date: null,
										data: pickupAppointment.data,
										isCompleted: pickupAppointment.isCompleted,
										isShow: pickupAppointment.data !== null && pickupAppointment.data.length !== 0 ? 'd-flex' : 'd-none',
										refName: 'appointment',
										className: 'appointment'
									},
									{
										event_name: 'Full Gated Out',
										event: 'full_out',
										data: fullGatedOut.data,
										isCompleted: fullGatedOut.isCompleted,
										// isShow: fullGatedOut.data !== null ? 'd-flex' : 'd-none',
										isShow: 'd-flex',
										refName: 'full_out',
										className: 'full_out'
									},
									{
										event_name: 'Empty Gated In',
										event: 'empty_in',
										data: emptyGatedIn.data,
										isCompleted: emptyGatedIn.isCompleted,
										// isShow: emptyGatedIn.data !== null ? 'd-flex' : 'd-none',
										isShow: 'd-flex',
										refName: 'empty_in',
										className: 'empty_in'
									},
								]	
							}
						}

						console.log(newMilestoneData);

						// commit("SET_MILESTONES", new_event_groups)
						commit("SET_MILESTONES", newMilestoneData)
					}
				}
			})
			.catch(err => {
				return Promise.reject(err)
			})
	},
	fetchOtherMilestoneData: async ({
		commit
	}, mbl_num) => {
		await axios.get(`/terminal49/shipment/${mbl_num}`)
		.then(res => {
			if (res.status === 200) {
				let getResponse = res.data

				let event_groups = []

				if (getResponse !== 'undefined' && getResponse.length !== 0) {
					let containersLists = getResponse.containers
					let getKeys = Object.keys(containersLists)					

					if (getKeys !== null && getKeys.length > 0) {
						getKeys.map(getKey => {
							if (typeof event_groups[getKey] == 'undefined' ) {
								event_groups.push({
									container_num: getKey,
									holds: containersLists[getKey].holds,
									lastFreeDay: containersLists[getKey].last_free_day,
									relasedAtTerminal: containersLists[getKey].released_at_terminal
								})

							} else {
								// find duplicate container
								let findDuplicateEvent = _.find(event_groups[getKey], {
									container_num: getKey
								})

								if (typeof findDuplicateEvent == 'undefined') {
									event_groups.push({
										container_num: getKey,
										holds: containersLists[getKey].holds,
										lastFreeDay: containersLists[getKey].last_free_day,
										relasedAtTerminal: containersLists[getKey].released_at_terminal
									})
								}
							}
						})
					}
				}

				commit('SET_MILESTONES_OTHER_DATA', event_groups)
			}
		}).catch(err => {
			return Promise.reject(err)
		})
	},
	// 04/26/2022
	fetchMilestonesNewLatest: async ({
		commit,
	}, mbl_num) => {
        commit("SET_MILESTONES_LOADING", true)
        commit("SET_TRACKING_CONTAINER_DETAILS", [])
        commit("SET_MILESTONES_ATTIBUTES", null)
        let attempt = false
        async function proceed() {

            await axios.get(`/events/${mbl_num}?standard=true`)
            .then(async res => {
                if (res.status === 200) {
                    if (res.data) {
                        // MILESTONES DATA - containers
                        let getContainers = res.data.containers
                        let getEtaLogs = res.data.eta_logs

                        let getContainersKeys = Object.keys(getContainers)
                        let event_groups = {}               
                        let new_event_groups = []
                        let lastFreeDayData = []
                        let pickupAppointmentData = []
                        let releasedAtTeminalData = []
                        let trackingContainerDetails = []

                        let defaultMilestoneData = []

                        // MILESTONES
                        let fetchingMilestonesDone = false

                        let containerKeysLength = getContainersKeys.length
                        let containerKeysCounter = 0

                        // For milestones attributes
                        if (typeof res.data.attributes !== 'undefined') {
                            commit("SET_MILESTONES_ATTIBUTES", res.data.attributes)
                        }

                        if (getContainersKeys.length > 0) {
                            let milestones = await axios.get(`/milestones/${mbl_num}?standard=true`)

                            getContainersKeys.map(getKey => {
                                try {
                                    if (typeof milestones.data!=='undefined' && typeof milestones.data[getKey]!=='undefined') {
                                        milestones = milestones.data[getKey]
                                        if (typeof milestones.data!=='undefined') {

                                            if (typeof milestones.included!=='undefined') {
                                                milestones = milestones.data.concat(milestones.included)
                                            } else {
                                                milestones = milestones.data
                                            }
                                        } else {
                                            if (typeof milestones.included!=='undefined') {
                                                milestones = milestones.included
                                            } else {
                                                milestones = []
                                            }
                                        }
                                        getContainers[getKey].milestones = milestones
                                    } else
                                        getContainers[getKey].milestones = []
                                    

                                    if (typeof getContainers[getKey].milestones !== 'undefined' && 
                                        getContainers[getKey].milestones.length > 0) {

                                        var getData = getContainers[getKey].milestones

                                        getData.map(data => {
                                            let { ...otherData } = getContainers[getKey].milestones


                                            let extraData = Object.keys(otherData)
                                            let putData = []

                                            let isArray = false

                                            if (extraData.length > 0) {
                                                extraData.map (ed => {
                                                    if (ed == '0') {
                                                        isArray = true
                                                        putData.push(otherData[ed])
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

                                                event_groups[data.attributes.event] = {
                                                    event: data.attributes.event,
                                                    event_name: data.attributes.original_event,
                                                    data: [],
                                                    actual_on: data.attributes.actual_on,
                                                    actual_at: data.attributes.actual_at,
                                                    estimated: data.attributes.estimated_at
                                                }

                                                event_groups[data.attributes.event].data.push({
                                                    container_num: getKey,
                                                    date: date,
                                                    data: putData
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
                                                        data: putData
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
                            })                            
                        } else {
                            fetchingMilestonesDone = true
                        }

                        let t = setInterval(() => {
                            if (containerKeysCounter==containerKeysLength) {
                                fetchingMilestonesDone = true
                                clearInterval(t)
                            }
                        },200)

                        let tt = setInterval( () => {
                            if ( fetchingMilestonesDone ) {
                                let get_event_groups_data = Object.keys(event_groups)

                                if (get_event_groups_data.length > 0) {
                                    get_event_groups_data.map (gegd => {
                                        let { data, actual_at, actual_on, estimated_at } = event_groups[gegd]
                                        
                                        let eventName = gegd.replace("container.transport.","")

                                        // find if there are dates that are null -> set completed to false if there are
                                        let isCompleted = false 
                                        let findDateNullIndex = _.findIndex(data, e => (e.date == null))
                                        let findReleasedFalseIndexInTerminal = _.findIndex(data, e => (e.releasedEvent == false))

                                        let className = ''

                                        if (findDateNullIndex === -1) {
                                            isCompleted = true
                                        } else {
                                            isCompleted = false
                                        }
                                        
                                        if (gegd == 'released_at_terminal') {
                                            if (findReleasedFalseIndexInTerminal === -1) {
                                                isCompleted = true
                                            } else {
                                                isCompleted = false
                                            }

                                            // if (data !== null && data.length !== 0) {
                                            //     className = 'released has-data'
                                            // } else {
                                            //     className = 'released no-data'
                                            // }
                                        }

                                        new_event_groups.push({
                                            event: gegd,
                                            event_name: eventName,
                                            isCompleted: isCompleted,
                                            class: className,
                                            other_data: data,
                                            actual_at: (typeof actual_at!=='undefined') ? actual_at : null,
                                            actual_on:(typeof actual_on!=='undefined') ? actual_on : null,
                                            estimated_at: (typeof estimated_at!=='undefined') ? estimated_at : null
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
                                                className: 'check transshipment_arrived',
                                                date: transshipment_arrived.latestDate,
                                                data: transshipment_arrived.data,
                                                isCompleted: transshipment_arrived.isCompleted,
                                                isShow: transshipment_arrived.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'transshipment_arrived')
                                            },
                                            {
                                                event_name: 'Transshipment Discharged',
                                                event: 'transshipment_discharged',
                                                refName: 'transshipment_discharged',
                                                className: 'check transshipment_discharged',
                                                date: transshipment_discharged.latestDate,
                                                data: transshipment_discharged.data,
                                                isCompleted: transshipment_discharged.isCompleted,
                                                isShow: transshipment_discharged.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'transshipment_discharged')
                                            },
                                            {
                                                event_name: 'Transshipment Loaded',
                                                event: 'transshipment_loaded',
                                                refName: 'transshipment_loaded',
                                                className: 'check transshipment_loaded',
                                                date: transshipment_loaded.latestDate,
                                                data: transshipment_loaded.data,
                                                isCompleted: transshipment_loaded.isCompleted,
                                                isShow: transshipment_loaded.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'transshipment_loaded')
                                            },
                                            {
                                                event_name: 'Transshipment Departed',
                                                event: 'transshipment_departed',
                                                refName: 'transshipment_departed',
                                                className: 'check transshipment_departed',
                                                date: transshipment_departed.latestDate,
                                                data: transshipment_departed.data,
                                                isCompleted: transshipment_departed.isCompleted,
                                                isShow: transshipment_departed.data !== null ? 'd-flex' : 'd-none',
                                                extraData: methods.getExtraData(new_event_groups,'transshipment_departed')
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

                                            getEtaLogs.map((value) => {
                                                defaultMilestoneData.push({
                                                    event_name: 'ETA Updated',
                                                    event: 'eta_logs',
                                                    refName: 'eta_logs',
                                                    className: 'eta_logs',
                                                    date: value.created_at,
                                                    data: getEtaLogs,
                                                    log: value,
                                                    isCompleted: false,
                                                    isShow: 'd-flex',
                                                    extraData: value.extraData
                                                })
                                            })
                                        }
                                    }
                                }        
                                
                                // sort all milestones by date
                                let sortDates = _.orderBy(defaultMilestoneData, ['date'],['asc'])
                                let findDischargedEvent = _.findIndex(sortDates, e => (e.event == 'vessel_discharged'))
                                let findTerminalEvent = _.findIndex(sortDates, e => (e.event == 'released_at_terminal'))

                                let dischargedIndex = findDischargedEvent + 1
                                methods.move(sortDates, findTerminalEvent, dischargedIndex)   


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

const methods = {
	testing: () => {
		alert('testing')
	}
}
const mutations = {
	SET_MILESTONES: (state, payload) => {
		state.milestones = payload
	},
	SET_MILESTONES_LOADING: (state, payload) => {
		state.milestones_loading = payload
	},
	SET_MILESTONES_OTHER_DATA: (state, payload) => {
		state.milestonesOtherData = payload
	},
}

export default {
	state,
	getters,
	actions,
	mutations,
	methods
}