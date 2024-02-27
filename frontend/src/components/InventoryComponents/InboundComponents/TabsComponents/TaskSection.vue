<template>
    <div class="inbound-pallet-section task-tab">
        <v-data-table
            :headers="taskHeader"
            :items="taskData.tasks"
            item-key="id"
            class="elevation-1 inbound-view-pallets"
            :class="taskData.length == 0 ? 'no-data-table' : ''"
            mobile-breakpoint="769"
            hide-default-footer
            :items-per-page="500">

            <template v-slot:no-data>            
                <div class="no-data-wrapper" v-if="taskData.tasks.length == 0">
                    <div class="no-data-heading">
                        <p class="text-center mb-0"> No assigned tasks </p>
                    </div>
                </div>
            </template> 

            <template v-slot:[`item.tasktype`]="{ item }">
                <span class="text-capitalize">{{ removeUnderscore(item.tasktype) }}</span>
            </template>

            <template v-slot:[`item.task_employees`]="{ item }">
                <div class="warehouse-employees-wrapper" v-if="item.task_employees.length > 0">
                    <span v-for="(employee, i) in item.task_employees" :key="i">
                        {{ employee.name }}<span v-if="i+1 < item.task_employees.length">, </span>
                    </span>
                </div>

                <div v-else>--</div>
            </template>

            <template v-slot:[`item.updated_at`]="{ item }">
                <div class="updated-by-at-wrapper" v-if="item.task_employees.length !== 0">
                    <div class="mb-1">{{ item.updated_by }}</div>
                    <div style="color: #6D858F;">{{ formatDate(item.updated_at) }}</div>
                </div>

                <div v-else>--</div>
            </template>

            <template v-slot:[`item.status`]="{ item }">
                <div class="task-status-wrapper" v-if="item.task_employees.length !== 0">
                    <v-chip class="task-chip" :class="removeUnderscore(item.status) !== null ? removeUnderscore(item.status) : 'pending'">
                        {{ removeUnderscore(item.status) !== null ? removeUnderscore(item.status) : 'pending' }}
                    </v-chip>
                </div>

                <div v-else>--</div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="actions-wrapper" v-if="item.task_employees.length !== 0 && !isWarehouseConnected">
                    <button class="btn-white" @click="editTask(item)">
                        <img src="@/assets/icons/edit-blue.svg" alt="Edit">
                    </button>
                </div>
            </template>
        </v-data-table>

        <AssignEmployeeDialog 
            :dialogData.sync="showAssignEmployeeDialog"
            :editedItemData.sync="selectedInboundOrders"
            @close="closeAssignEmployee"
            :fromComponent="'Inbound'"
            :editedIndexData.sync="editedIndexAssign" /> 
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
import globalMethods from '../../../../utils/globalMethods'
import inventoryGlobalMethods from '../../../../utils/inventoryMethods/inventoryGlobalMethods'
import AssignEmployeeDialog from '../Dialogs/AssignEmployeeDialog.vue'
import _ from 'lodash'

export default {
    name: 'TaskSection',
    props: ['inboundProductsData', 'isMobile', 'isWarehouseConnected'],
    components: {
        AssignEmployeeDialog
    },
    data: () => ({
        taskHeader: [
            {
				text: 'Task',
				align: 'start',
				sortable: false,
				value: 'tasktype',
				fixed: true,
				width: "15%"
			},
            {
				text: 'Assigned Person',
				align: 'start',
				sortable: false,
				value: 'task_employees',
				fixed: true,
				width: "40%"
			},
            {
				text: 'Updated By & At',
				align: 'start',
				sortable: false,
				value: 'updated_at',
				fixed: true,
				width: "25%"
			},
            {
				text: 'Status',
				align: 'center',
				sortable: false,
				value: 'status',
				fixed: true,
				width: "15%"
			},
			{ 
				text: '', 
				align: 'end',
				value: 'actions', 
				sortable: false,
				fixed: true,
				width: "5%"
			},
        ],
        showAssignEmployeeDialog: false,
        selectedInboundOrders: [],
        editedIndexAssign: -1
    }),
    computed: {
        ...mapGetters({ }),
        singleInboundData() {
            let value = null

            if (typeof this.inboundProductsData !== 'undefined' && this.inboundProductsData !== null) {
                value = this.inboundProductsData
            }

            return value
        },
        taskData() {
            if (this.singleInboundData !== null) {
                let { data, no_of_sku, total_expected_cartons, total_expected_units } = this.singleInboundData

                data.no_of_sku = no_of_sku
                data.total_expected_cartons = total_expected_cartons,
                data.total_expected_units = total_expected_units

                return this.singleInboundData.data
            } else {
                return {}
            }
        }
    },
    methods: {
        ...mapActions({ }),
        ...globalMethods,
        ...inventoryGlobalMethods,
        removeUnderscore(val) {
            if (val !== '') {
                if (val.includes('_')) {
                    let converted = val.replace(/_+/g, ' ')
                    return converted
                } else {
                    return val
                }
            }
        },
        formatDate(date) {
            return this.formatTimeAndDateTogether(date)
        },
        editTask(item) {
            this.showAssignEmployeeDialog = true
            this.editedIndexAssign = _.findIndex(this.taskData.tasks, e => (e.id === item.id))

            item.order_id = this.taskData.order_id
            item.no_of_sku = this.taskData.no_of_sku
            item.ref_no = this.taskData.ref_no
            item.no_of_units = this.taskData.total_expected_units
            item.no_of_cartons = this.taskData.total_expected_cartons
            item.customer = this.taskData.warehouse_customer !== null ? this.taskData.warehouse_customer.company_name : ''
            item.name = this.taskData.name

            this.selectedInboundOrders.push(item)
        },
        closeAssignEmployee() {
            this.editedIndexAssign = -1
            this.showAssignEmployeeDialog = false
            this.selectedInboundOrders = []
        }
    },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "inventory");
    },
    updated() {}
}
</script>

<style lang="scss">
@import './scss/palletSection.scss';
</style>