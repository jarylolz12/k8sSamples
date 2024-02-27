<template>
    <div class="flex flex-col">
        <div class="flex flex-row">
            <div class="flex flex-col border-b border-40 mx-6 w-1/2">
                <div style="border-bottom: 1px solid #eef1f4;" class="flex">
                    <div class="w-full py-4">
                        <h4 class="font-bold text-80">Office From</h4>
                    </div>
                </div>
                <div v-for="from in froms" class="flex py-4">
                    <div v-if="from.type=='from'" class="w-1/2 content-center flex">
                        <h4 class="font-bold text-slate-800">
                            {{
                                from.label
                            }}
                        </h4>
                    </div>
                    <div v-if="from.type=='from'" class="w-1/2">
                        <div v-if="from.value!=='' && from.value!==null">
                            <div v-for="v in from.value">
                                {{
                                    v
                                }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col border-b border-40 mx-6 w-1/2">
                <div style="border-bottom: 1px solid #eef1f4;" class="flex">
                    <div class="w-full py-4">
                        <h4 class="font-bold text-80">Office To</h4>
                    </div>
                </div>
                <div v-for="to in tos" class="flex py-4">
                    <div v-if="to.type=='to'" class="w-1/2 flex content-center">
                        <h4 class="font-bold text-slate-800">
                            {{
                                to.label
                            }}
                        </h4>
                    </div>
                    <div v-if="to.type=='to'" class="w-1/2">
                        <div v-if="to.value!=='' && to.value!==null">
                            <div v-for="v in to.value">
                                {{
                                    v
                                }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div v-if="office_name=='Shifl USA'" class="flex mx-6">
            <div class="flex w-full flex-col">
                <div style="border-bottom: 1px solid #eef1f4;" class="flex">
                    <div class="w-full py-4">
                        <h4 class="font-bold text-80">Overall Reporting & Notification Emails</h4>
                    </div>
                </div>
                <div v-for="all in alls" class="flex py-4">
                    <div class="flex content-center" v-if="all.type=='all'" style="width: 20%;">
                        <h4 class="font-bold text-slate-800">
                            {{
                                all.label
                            }}
                        </h4>
                    </div>
                    <div v-if="all.type=='all'" style="width: 80%;">
                        <div v-if="all.value!=='' && all.value!==null">
                            <div v-for="v in all.value">
                                {{
                                    v
                                }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
import {
  mapActions,
  mapGetters
} from 'vuex'

import _ from 'lodash'
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    async mounted() {
        if ( this.resourceId!==null ) {
            try {
                let response = await this.fetchItem(this.resourceId)
                let items = response.data.items
                this.office_name = response.data.office.name
                if ( items.length > 0 ) {
                    items.map( item => {
                        let keys = Object.keys(item)
                        keys.map(k => {
                            if ( k!='id' && k!='updated_at' && k!='type' && k!='office_id' && k!='created_at' && k!='all_email_emails') {
                                let findFieldIndex = _.findIndex(this.fieldMaps,(e => (e.field == k && e.type == item.type)))

                                if ( findFieldIndex!=-1 ) {
                                    this.fieldMaps[findFieldIndex].value =  item[k]    
                                }
                                
                            }
                        })
                    })
                }

                this.tos = _.filter(this.fieldMaps,(e => e.type == 'to'))
                this.froms = _.filter(this.fieldMaps,(e => e.type == 'from'))
                this.alls =  _.filter(this.fieldMaps,(e => e.type == 'all'))


            } catch(e) {
                console.log('e', e)
            }
        }
    },
    methods: {
        ...mapActions({
            fetchItem: 'officeEmail/fetchItem',
        }),
        showValue(v) {
            
            let a = []
            let b = ''
            if ( value!='' && value!=null ) {
                
                v.map(v => {
                    a.push(v)
                    b +='<div>' + v + '</div>'
                })
                   
            }
            return b
        }
    },
    data:() => ({
        options: [],
        infos: [],
        froms: [],
        alls: [],
        tos: [],
        office_name: '',
        fieldMaps: [{
        label: 'Booking and Updates',
        value: '',
        type: 'from',
        field: 'booking_and_updated_emails',
        },
        {
            label: 'Booking Confirmed',
            value: '',
            type: 'from',
            field: 'booking_confirmation_emails',
        },
        {
            label: 'Agent Booking & Updates',
            value: '',
            type: 'from',
            field: 'agent_booking_updated_emails',
        },
        {
            label: 'Agent Booking Confirmed',
            value: '',
            type: 'from',
            field: 'agent_booking_confirmation_emails',
        },
        {
            label: 'Departure Notice',
            value: '',
            type: 'from',
            field: 'departure_notice_emails'
        },
        {
            label: 'Arrival Notice',
            value: '',
            type: 'from',
            field: 'arrival_notice_emails'
        },
        {
            label: 'Customer Entry Notice',
            value: '',
            type: 'from',
            field: 'customer_entry_notice_emails'
        },
        {
            label: 'Delivery Order',
            value: '',
            type: 'from',
            field: 'delivery_order_emails'
        },
        {
            label: 'ETA Alert',
            value: '',
            type: 'from',
            field: 'eta_alert_emails'
        },
        {
            label: 'ETA Alert(Trucker)',
            value: '',
            type: 'from',
            field: 'eta_alert_trucker_emails'
        },
        {
            label: 'Carrier ETA Discrepancy',
            value: '',
            type: 'from',
            field: 'carrier_eta_discrepancy_emails'
        },
        {
            label: 'Discharged Discrepancy',
            value: '',
            type: 'from',
            field: 'discharged_discrepancy_emails'
        },
        {
            label: 'ATA Discrepancy',
            value: '',
            type: 'from',
            field: 'ata_discrepancy_emails'
        },
        {
            label: 'Booking and Updates',
            value: '',
            type: 'to',
            field: 'booking_and_updated_emails'
        },
        {
            label: 'Booking Confirmed',
            value: '',
            type: 'to',
            field: 'booking_confirmation_emails'
        },
        {
            label: 'Agent Booking & Updates',
            value: '',
            type: 'to',
            field: 'agent_booking_updated_emails'
        },
        {
            label: 'Agent Booking Confirmed',
            value: '',
            type: 'to',
            field: 'agent_booking_confirmation_emails'
        },
        {
            label: 'Departure Notice',
            value: '',
            type: 'to',
            field: 'departure_notice_emails'
        },
        {
            label: 'Arrival Notice',
            value: '',
            type: 'to',
            field: 'arrival_notice_emails'
        },
        {
            label: 'Customer Entry Notice',
            value: '',
            type: 'to',
            field: 'customer_entry_notice_emails'
        },
        {
            label: 'Delivery Order',
            value: '',
            type: 'to',
            field: 'delivery_order_emails'
        },
        {
            label: 'ETA Alert',
            value: '',
            type: 'to',
            field: 'eta_alert_emails'
        },
        {
            label: 'ETA Alert(Trucker)',
            value: '',
            type: 'to',
            field: 'eta_alert_trucker_emails'
        },
        {
            label: 'Carrier ETA Discrepancy',
            value: '',
            type: 'to',
            field: 'carrier_eta_discrepancy_emails'
        },
        {
            label: 'Discharged Discrepancy',
            value: '',
            type: 'to',
            field: 'discharged_discrepancy_emails'
        },
        {
            label: 'ATA Discrepancy',
            value: '',
            type: 'to',
            field: 'ata_discrepancy_emails'
        },
        {
            label: 'Manual Tracking Report(Maintenance)',
            value: '',
            type: 'all',
            field: 'manual_tracking_report_emails'
        }
        ]
    })
}
</script>
