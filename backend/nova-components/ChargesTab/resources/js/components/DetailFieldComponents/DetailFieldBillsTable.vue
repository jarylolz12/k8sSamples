<template>
    <div>
        <vue-good-table
         :columns="columns"
         :rows="rows"
         :sort-options="{
            enabled: false,
         }"
         styleClass="vgt-table striped bill-details-table"
        >
            <div slot="emptystate">
                <div class="text-center">{{tableEmptyStateText}}</div>
            </div>
            <template slot="table-row" slot-scope="props">
                <span v-tooltip="`Bill #${props.row.qbo_bill_num}`" class="qbo-ellipse-group" v-if="props.column.field == 'qbo_bill_num'">
                    {{
                        props.row.qbo_bill_num
                    }}
                </span>
                <span v-else-if="props.column.field == 'payment_status'">
                  <span class="block w-96 text-sm text-red" v-if="props.row.payment_status==0">Not Paid</span>
                  <span class="block w-96 text-sm text-red" v-if="props.row.payment_status==1">Pending</span>
                  <span class="block w-96 text-sm text-green" v-if="props.row.payment_status==2">Fully Paid</span>
                  <span class="block w-96 text-sm text-orange" v-if="props.row.payment_status==3">Partial - Pending Completion</span>
                </span>
                <span v-tooltip="props.row.paid_date" class="qbol-bill-paid-date" v-else-if="props.column.field == 'paid_date'">
                    {{
                        props.row.paid_date
                    }}
                </span>
                <span v-else-if="props.column.field == 'payment_cta'">
                  <button :disabled="props.row.realm_id!=='123146157027444'" v-if="props.row.payment_status==0 || props.row.payment_status==3 && typeof props.row.has_paylist=='undefined'" @click="pay(props)" class="btn btn-default bg-success text-white py-0 leading-3">{{ (props.row.paying) ? 'Paying': 'Pay' }}</button>
                  <span v-else class="block w-96 text-sm">No actions needed.</span>
                </span>
                <span v-else>
                  {{props.formattedRow[props.column.field]}}
                </span>
            </template>
        </vue-good-table>
    </div>
</template>

<script>
import { VueGoodTable } from 'vue-good-table';
import moment from 'moment';
import 'vue-good-table/dist/vue-good-table.css'

export default {
    name:"DetailFieldBillsTable",
    components:{
        VueGoodTable
    },
    props: ["tableData"],

    data(){
        return{
            columns:[
                { label: 'Date', field: 'qbo_bill_date'},
                { label: 'Bill #', field: 'qbo_bill_num'},
                { label: 'Due Date', field: 'qbo_due_date'},
                { label: 'Total', field: 'total_amount'},
                { label: 'Status', field: 'payment_status'},
                { label: 'Date Paid', field: 'paid_date'},
                { label: 'Actions', field: 'payment_cta'}
            ],
            rows:[],
            totalBillsAmount:0,
            tableEmptyStateText:'No bills created.'
        }
    },

    beforeCreate: function(){
        //
    },

    created: function(){
        //
    },

    mounted: function(){
        this.setTableData(this.tableData)
    },

    methods: {
        async pay({row: item}) {

            if (!item.paying) {

                item.paying = true
                
                let {
                    payment_status,
                    ...otherProps
                } = item
                payment_status = 1

                let payload = {
                    payment_status,
                    ...otherProps
                }

                try {
                    const response = await this.$store.dispatch('updateBill', payload)
                    item.paying = false
                    if (response.status=='ok') {
                        //move to pending status
                        item.payment_status = 1
                    }

                } catch(e) {
                    item.paying = false
                    console.log('An error occured', e)
                }    
            }

        },
        setTableData: function(payload){
            let rowData = [];
            let calculatedTotalBills = 0;
            if(payload.length > 0){
                payload.map(function(o){
                    let billSubTotal = 0;
                    o.bill.bill_items.forEach((item) => {
                        billSubTotal = billSubTotal + parseFloat(item.qbo_amount);
                    });

                    let row = {
                        qbo_bill_date: o.bill.qbo_bill_date ? moment.utc(String(o.bill.qbo_bill_date)).format('MMM DD, YYYY') : '',
                        qbo_bill_num: o.bill.qbo_bill_num,
                        qbo_due_date: o.bill.qbo_due_date ? moment.utc(String(o.bill.qbo_due_date)).format('MMM DD, YYYY') : '',
                        total_amount: parseFloat(o.bill.total_amount).toFixed(2),
                        id: o.bill.id,
                        qbo_bill_id: o.bill.qbo_bill_id,
                        qbo_vendor_name: o.bill.qbo_vendor_name,
                        realm_id: o.realm_id,
                        payment_status: o.bill.payment_status,
                        paying: false
                    }
                    calculatedTotalBills += billSubTotal;


                    rowData.push(row);
                });
            }


            if ( rowData.length > 0 ) {
                
                //additional rows here
                let additionalRows = {
                    payment_cta: ''
                }

                //map keys
                let additionalRowsKeys = Object.keys(additionalRows)
                
                //assigned new keys and values
                rowData.map(async (r,kk) => {
                    additionalRowsKeys.map(ar => {
                        r[ar] = additionalRows[ar]
                    })
                    r['paid_date'] = ''
                    await axios.get(`/custom/get-bill-qb-info/${r.qbo_bill_id}`).then(res => {
                        if (typeof res.data!=='undefined' && typeof res.data.bill_payment!=='undefined') {
                            rowData[kk].paid_date = moment.utc(String(res.data.bill_payment.MetaData.LastUpdatedTime)).format('LLL') 

                            //rowData[kk].paid_date = res.data.bill_payment.TxnDate
                            //console.log('bill payment ', txnDate)

                            if (typeof res.data.payment_status!=='undefined') {
                                rowData[kk].payment_status = res.data.payment_status
                                if (typeof res.data.has_paylist!=='undefined')
                                    rowData[kk].has_paylist = true
                            }
                        }
                    })
                })

            }

            this.totalBillsAmount = parseFloat(calculatedTotalBills).toFixed(2);
            this.$emit('getCompanyTotalBills', calculatedTotalBills);
            this.rows = rowData;
            this.tableEmptyStateText = "No bills created.";
        },
    },
    watch:{
        tableData(nVal,oVal){
            this.setTableData(nVal)
        }
    }
}
</script>
<style scoped>
.invoice-details-table .text-red {
    color: #ef4444;
}

.invoice-details-table .text-green {
    color: #22c55e;
}

.invoice-details-table .text-orange {
    color: #f97316;
}
.bill-pay-btn {
    background: green;
    padding: 16px;
    color: white;
}
</style>