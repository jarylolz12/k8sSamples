<template>
    <v-dialog 
        v-model="dialog" max-width="610" 
        content-class="assign-dialog" 
        :retain-focus="false" 
        v-resize="onResize" 
        scrollable
        @click:outside="close">

        <v-card>            
            <v-card-title>
                <span class="headline" v-if="editedIndex === -1">Assign Employee ({{ fromComponent }})</span>
                <span class="headline" v-if="editedIndex > -1">Edit Task ({{ fromComponent }})</span>
                <v-spacer></v-spacer>
                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="assign-info-wrapper">
                <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                    <div class="assign-info">
                        <div class="assign-second-column">
                            <div class="assign-employee-section-wrapper">
                                <div class="assign-employee-header mb-5">
                                    <v-row v-if="editedItem.length === 1">
                                        <v-col cols="6">
                                            <div class="assign-div">
                                                <div class="assign-title font-medium">ORDER ID</div>
                                                <div class="assign-data">{{ editedItem[0].order_id }}</div>
                                            </div>

                                            <div class="assign-div mt-1">
                                                <div class="assign-title font-medium">REFERENCE</div>
                                                <div class="assign-data">
                                                    {{ editedItem[0].ref_no !== null ? editedItem[0].ref_no : '--' }}
                                                </div>
                                            </div>

                                            <div class="assign-div mt-1">
                                                <div class="assign-title font-medium">TRUCK</div>
                                                <div class="assign-data">
                                                    {{ editedItem[0].name !== null ? editedItem[0].name : '--' }}
                                                </div>
                                            </div>
                                        </v-col>

                                        <v-col cols="6">
                                            <div class="assign-div">
                                                <div class="assign-title font-medium">NO OF SKU's</div>
                                                <div class="assign-data">{{ editedItem[0].no_of_sku }}</div>
                                            </div>

                                            <div class="assign-div mt-1">
                                                <div class="assign-title font-medium">CARTON</div>
                                                <div class="assign-data">{{ editedItem[0].no_of_cartons }}</div>
                                            </div>

                                            <div class="assign-div mt-1">
                                                <div class="assign-title font-medium">UNITS</div>
                                                <div class="assign-data">{{ editedItem[0].no_of_units }}</div>
                                            </div>
                                        </v-col>
                                    </v-row>

                                    <div v-if="editedItem.length > 1">
                                        <v-row>
                                            <v-col cols="6">
                                                <div class="assign-div">
                                                    <div class="assign-title font-medium">NO OF ORDERS</div>
                                                    <div class="assign-data">{{ editedItem.length }}</div>
                                                </div>

                                                <div class="assign-div mt-1">
                                                    <div class="assign-title font-medium">CARTON</div>
                                                    <div class="assign-data">{{ getTotalDisplay(editedItem, 'carton') }}</div>
                                                </div>
                                            </v-col>

                                            <v-col cols="6">
                                                <div class="assign-div">
                                                    <div class="assign-title font-medium">NO OF SKU's</div>
                                                    <div class="assign-data">{{ getTotalDisplay(editedItem, 'skus') }}</div>
                                                </div>

                                                <div class="assign-div mt-1">
                                                    <div class="assign-title font-medium">UNITS</div>
                                                    <div class="assign-data">{{ getTotalDisplay(editedItem, 'units') }}</div>
                                                </div>
                                            </v-col>
                                        </v-row>

                                        <v-row class="mt-1">
                                            <v-col cols="12" sm="12" class="pt-0">
                                                <div class="assign-div">
                                                    <div class="assign-title font-medium" style="width: 19%;">ORDER LIST</div>
                                                    <div class="assign-data" style="width: 81%;">
                                                        <span v-for="(item, index) in editedItem" :key="index">
                                                            <span>{{ item.order_id }}</span>
                                                            <span v-if="index+1 < editedItem.length">, </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </v-col>
                                        </v-row>
                                    </div>
                                </div>

                                <div class="assign-employee-tables-wrapper">
                                    <v-data-table
                                        :headers="headers"
                                        :items="assignEmployeeData"
                                        class="elevation-1 assign-employee-table"
                                        mobile-breakpoint="769"
                                        hide-default-footer>
                                        
                                        <template v-slot:item="{ item, index }">
                                            <tr class="assign-employee-row">
                                                <td class="assign-employee-col" v-show="item.isShow">
                                                    <p class="mb-0 px-4">{{ item.task }}</p>
                                                </td>

                                                <td class="assign-employee-col" v-show="item.isShow">
                                                    <v-select
                                                        :items="allEmployeesCopy"
                                                        v-model="item.employees"
                                                        spellcheck="false" 
                                                        class="select-product shrink employee"
                                                        item-text="name"
                                                        item-value="name"
                                                        placeholder="Select Warehouse Employee"
                                                        outlined
                                                        :menu-props="{ contentClass: 'product-lists-items assign-employee-lists', bottom: true, offsetY: true, closeOnContentClick: false }"
                                                        hide-details="auto"
                                                        multiple
                                                        small-chips
                                                        deletable-chips
                                                        return-object
                                                        @click="onSelectEmployee(index)">

                                                        <template v-slot:selection="{ item }">
                                                            <v-chip class="product-item-chip font-medium" close @click:close="removeChipEmployee(item, index)">
                                                                <span class="name">{{ item.name }}</span>
                                                            </v-chip>
                                                        </template>

                                                        <template v-slot:prepend-item>
                                                            <v-list-item class="search-input-field pa-0" v-if="allEmployeesCopy.length !== 0">
                                                                <v-text-field 
                                                                    v-model="searchEmployeeName" 
                                                                    placeholder="Search Contact"
                                                                    hide-details="auto"
                                                                    class="text-fields select-items product-search-contact-field"
                                                                    outlined
                                                                    prepend-inner-icon="mdi-magnify"
                                                                    @input="handleSearchEmployee">
                                                                </v-text-field>
                                                            </v-list-item>
                                                        </template>

                                                        <template v-slot:item="{ item, attrs, on }">
                                                            <v-list-item v-on="on" v-bind="attrs" #default="{ active }" :class="item.class">
                                                                <v-list-item-action class="mr-2 pa-0">
                                                                    <v-checkbox :input-value="active"></v-checkbox>
                                                                </v-list-item-action>
                                                                <v-list-item-content>
                                                                    <v-list-item-title>
                                                                        <p class="contact-name mb-0">{{ item.name }}</p>
                                                                    </v-list-item-title>
                                                                </v-list-item-content>
                                                            </v-list-item>
                                                        </template>

                                                        <template v-slot:append-item>
                                                            <v-list-item v-if="isAllOptionsHidden(allEmployeesCopy)">
                                                                <v-list-item-content>
                                                                    <v-list-item-title>
                                                                        <span style="font-size: 14px;">
                                                                            No available data
                                                                        </span>
                                                                    </v-list-item-title>
                                                                </v-list-item-content>
                                                            </v-list-item>
                                                        </template>

                                                        <template v-slot:no-data>
                                                            <div class="option-items loading" 
                                                                v-if="getWarehouseEmployeesLoading"
                                                                style="min-height: 100px; padding: 12px; display: flex; justify-content: center; align-items: center;">
                                                                <div class="sku-item">
                                                                    <v-progress-circular
                                                                        :size="40"
                                                                        color="#0171a1"
                                                                        indeterminate>
                                                                    </v-progress-circular>
                                                                </div>
                                                            </div>

                                                            <div class="option-items no-data" 
                                                                v-if="!getWarehouseEmployeesLoading"
                                                                style="min-height: 40px; padding: 12px; font-size: 14px; border-bottom: 1px solid #EBF2F5;">
                                                                <div class="sku-item">
                                                                    No available data
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </v-select>
                                                </td>

                                                <td class="assign-employee-col" v-show="item.isShow">
                                                    <v-tooltip right content-class="left-arrow">
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-btn
                                                                icon
                                                                class="btn remove-btn"
                                                                @click="clearItemRow(item)"
                                                                :disabled="item.employees.length === 0"
                                                                v-bind="attrs"
                                                                v-on="on">
                                                                <v-icon>mdi-close</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Clear All</span>
                                                    </v-tooltip>                                                    
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>

                                    <div class="preferences" v-if="editedItem.length > 1">
                                        <img src="@/assets/icons/info-blue.svg" width="18px" height="18px">
                                        <span style="color: #6D858F;">Preference of the assignments will be applicable to all order</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="receive-button-actions">
                <button class="btn-blue" text @click="assignAction" :disabled="getAssignWarehouseEmployeeLoading">
                    <span v-if="editedIndex === -1">
                        {{ getAssignWarehouseEmployeeLoading ? 'Assigning...' : 'Assign'}}
                    </span>
                    <span v-if="editedIndex > -1">
                        {{ getAssignWarehouseEmployeeLoading ? 'Saving...' : 'Save Changes'}}
                    </span>
                </button>

                <button class="btn-white" text @click="close" :disabled="getAssignWarehouseEmployeeLoading">
                    Cancel
                </button>
            </v-card-actions>

            <!-- Show Remove Assignment here -->
            <ConfirmDialog :dialogData.sync="dialogRemoveAssignment">
                <template v-slot:dialog_icon>
                    <div class="header-wrapper-close">
                        <img src="@/assets/icons/icon-delete.svg" alt="alert">
                    </div>
                </template>

                <template v-slot:dialog_title>
                    <h2> Remove Assignment? </h2>
                </template>

                <template v-slot:dialog_content>
                    <p> This employee <span class="font-italic">Sulthan Ahmad</span> has already started working on this task. Do you still want to remove it from this assignment? </p>
                    <p> <br/> They will still be able to continue and complete the assignment. </p>
                </template>

                <template v-slot:dialog_actions>
                    <v-btn class="btn-blue" text @click="closeRemoveAssignment">
                        Remove
                    </v-btn>

                    <v-btn class="btn-white" text @click="closeRemoveAssignment">
                        Cancel
                    </v-btn>
                </template>
            </ConfirmDialog>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import globalMethods from '../../../../utils/globalMethods'
import _ from 'lodash'
import ConfirmDialog from '../../../Dialog/GlobalDialog/ConfirmDialog.vue'

export default {
    name: 'AssignEmployeeDialog',
    props: [
        'dialogData', 
        'editedItemData',
        'fromComponent',
        'editedIndexData'
    ],
    components: {
        ConfirmDialog
    },
    data: () => ({
        current_page: 1,
        isMobile: false,
        valid: true,
        headers: [
			{
				text: 'TASK',
				align: 'start',
				sortable: false,
				value: 'task',
				fixed: true,
				width: "20%"
			},
            {
				text: 'WAREHOUSE EMPLOYEE',
				align: 'start',
				sortable: false,
				value: 'warehouse_employee',
				fixed: true,
				width: "75%"
			},
            {
				text: '',
				align: 'end',
				sortable: false,
				value: 'actions',
				fixed: true,
				width: "5%"
			},
		],
        searchEmployeeName: '',
        assignEmployeeData: [
            {
                task: 'All Task',
                employees: [],
                value: 'all-task',
                isShow: true
            },
            {
                task: 'Receiving',
                employees: [],
                value: 'receiving',
                isShow: true
            },
            {
                task: 'Palletizing',
                employees: [],
                value: 'palletizing',
                isShow: true
            },
            {
                task: 'Put Away',
                employees: [],
                value: 'putting_away',
                isShow: true
            }
        ],
        allEmployees: [],
        allEmployeesCopy: [],
        checkIfThreeOptionsSelected: false,
        isAllOptionDisabled: false,
        disabledButtonOnly: false,
        dialogRemoveAssignment: false
    }),
    watch: {
        dialog(value, oldValue) {
            // this.warehouseEmployeeListsCopy = this.warehouseEmployeeLists
            this.allEmployees = _.cloneDeep(this.warehouseEmployeeLists)
            this.allEmployeesCopy = _.cloneDeep(this.warehouseEmployeeLists)
            this.allEmployees = this.allEmployees.map((rest)=> ({class: '', ...rest}))
            this.allEmployeesCopy = this.allEmployeesCopy.map((rest)=> ({class: '', ...rest}))

			if (!value) {
				this.assignEmployeeData = [
                    {
                        task: 'All Task',
                        employees: [],
                        value: 'all-task',
                        isShow: true
                    },
                    {
                        task: 'Receiving',
                        employees: [],
                        value: 'receiving',
                        isShow: true
                    },
                    {
                        task: 'Palletizing',
                        employees: [],
                        value: 'palletizing',
                        isShow: true
                    },
                    {
                        task: 'Put Away',
                        employees: [],
                        value: 'putting_away',
                        isShow: true
                    }
                ]
			} else if (value && !oldValue) {
				if (this.editedIndex === -1) {
					this.assignEmployeeData = [
                        {
                            task: 'All Task',
                            employees: [],
                            value: 'all-task',
                            isShow: true
                        },
                        {
                            task: 'Receiving',
                            employees: [],
                            value: 'receiving',
                            isShow: true
                        },
                        {
                            task: 'Palletizing',
                            employees: [],
                            value: 'palletizing',
                            isShow: true
                        },
                        {
                            task: 'Put Away',
                            employees: [],
                            value: 'putting_away',
                            isShow: true
                        }
                    ]
				} else {
					let employeeListsNames = []

                    if (this.editedItem.length === 1) {
                        let current_task = this.editedItem[0]

                        this.assignEmployeeData.forEach((item) => { 
                            item.value !== current_task.tasktype ? item.isShow = false : item.isShow = true
                        })

                        current_task.task_employees.map(v => {
                            employeeListsNames.push({ ...v })
                        })

                        let findCurrentTaskSelected = _.find(this.assignEmployeeData, e => current_task.tasktype === e.value)
                        if (findCurrentTaskSelected !== undefined) {
                            findCurrentTaskSelected.employees = employeeListsNames
                        }
                    }
				}
			}
        },
    },
    computed: {
        ...mapGetters({
            getUser: 'getUser',
            getWarehouseEmployees: 'employees/getWarehouseEmployees',
            getWarehouseEmployeesLoading: 'employees/getWarehouseEmployeesLoading',
            getAssignWarehouseEmployeeLoading: 'employees/getAssignWarehouseEmployeeLoading',
            getCurrentInboundTab: 'inbound/getCurrentInboundTab',
        }),
        dialog: {
            get() {
                return this.dialogData
            },
            set(value) {
                this.$emit('update:dialogData', value)
            }
        },
        editedItem: {
            get() {
                let value = this.editedItemData
                // remove duplicate items in array
                value = _.uniqBy(value, 'id')
                return value
            },
            set(value) {
                this.$emit('update:editedItemData', value)
            }
        },
        editedIndex: {
            get() {
                return this.editedIndexData
            },
            set(value) {
                this.$emit('update:editedIndexData', value)
            }
        },
        warehouseEmployeeLists() {
            let employees = []

            if (typeof this.getWarehouseEmployees !== 'undefined' && this.getWarehouseEmployees !== null &&
                this.getWarehouseEmployees.length > 0) {
                employees = this.getWarehouseEmployees.map(v => {
                    let { id, name, email } = v
                    return {
                        employee_id: id, name, email
                    }
                })
            }

            return employees
        },
        assignEmployeeTemplate() {
            if (this.editedItem.length !== 0) {
                // let inbound_id = this.editedItem[0].id - for single inbound only
                let inbounds = []
                let receiving = []
                let palletizing = []
                let putting_away = []

                this.editedItem.map(v => {
                    inbounds.push(v.id)
                })
                
                // if all tasks is not empty
                if (this.assignEmployeeData[0].employees.length > 0) {
                    this.assignEmployeeData[0].employees.map(v => {
                        receiving.push(v.employee_id)
                        palletizing.push(v.employee_id)
                        putting_away.push(v.employee_id)
                    })
                }

                // receiving
                if (this.assignEmployeeData[1].employees.length > 0) {
                    this.assignEmployeeData[1].employees.map(v => {
                        receiving.push(v.employee_id)
                    })
                }

                // palletizing
                if (this.assignEmployeeData[2].employees.length > 0) {
                    this.assignEmployeeData[2].employees.map(v => {
                        palletizing.push(v.employee_id)
                    })
                }

                // putting_away
                if (this.assignEmployeeData[3].employees.length > 0) {
                    this.assignEmployeeData[3].employees.map(v => {
                        putting_away.push(v.employee_id)
                    })
                }
                
                return {
                    inbounds,
                    receiving,
                    palletizing,
                    putting_away,
                    updated_by: (typeof this.getUser == 'string') ? JSON.parse(this.getUser).name : this.getUser.name
                }
            } else {
                return {}
            }
        },
        isWarehouse3PLProvider() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 6) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouseConnected() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.is_own !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.is_own === 0) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouseOwn() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 1) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
        isWarehouse3PL() {
            if (this.$store.state.warehouse.currentWarehouse !== null) {
                if (typeof this.$store.state.warehouse.currentWarehouse.warehouse_type_id !== 'undefined' && 
                    this.$store.state.warehouse.currentWarehouse.warehouse_type_id === 3) {
                    return true
                } else {
                    return false
                }
            } else {
                return false
            }
        },
    },
    methods: {
        ...mapActions({
            assignWarehouseEmployee: 'employees/assignWarehouseEmployee',
            editTaskEmployee: 'employees/editTaskEmployee',
            fetchSingleInbound: 'inbound/fetchSingleInbound',
            fetchPendingInbounds: 'inbound/fetchPendingInbounds',
            fetchFloorInbounds: 'inbound/fetchFloorInbounds',
            fetchPendingReceivingInbounds: 'inbound/fetchPendingReceivingInbounds',
            setCurrentInboundTab: 'inbound/setCurrentInboundTab',
        }),
        ...globalMethods,
        restrictValues(e) {
            if(e.key=='-' && e.keyCode==189 || e.key=='+' && e.keyCode==187 ) {
                e.preventDefault()
            }
        },
        onResize() {
            if (window.innerWidth < 1024) {
				this.isMobile = true;
			} else {
				this.isMobile = false;
			}
        },
        close() {
            this.$emit('close')
            this.assignEmployeeData = [
                {
                    task: 'All Task',
                    employees: [],
                    value: 'all-task',
                    isShow: true
                },
                {
                    task: 'Receiving',
                    employees: [],
                    value: 'receiving',
                    isShow: true
                },
                {
                    task: 'Palletizing',
                    employees: [],
                    value: 'palletizing',
                    isShow: true
                },
                {
                    task: 'Put Away',
                    employees: [],
                    value: 'putting_away',
                    isShow: true
                }
            ]
            this.warehouseEmployeeLists.forEach((item) => item.disabled = false)
            this.allEmployees = this.warehouseEmployeeLists
            this.allEmployeesCopy = this.warehouseEmployeeLists
            this.searchEmployeeName = ''
        },
        removeChipEmployee(item, index) {
            // this.openRemoveAssignment(item, index) - for checking removing assignment
            
            let indexLodash = _.findIndex(this.assignEmployeeData[index].employees, e => (e.employee_id == item.employee_id))            
            if (indexLodash > -1) {
				this.assignEmployeeData[index].employees.splice(indexLodash, 1)
			}

            let findEmployee = _.find(this.allEmployeesCopy, e => item.employee_id === e.employee_id)
            if (findEmployee !== undefined) {
                findEmployee.disabled = false
            }
        },
        clearItemRow(item) {
            this.allEmployeesCopy.forEach((item) => item.disabled = false)
            if (item.employees.length > 0) {
                item.employees = []
            }
        },
        getTotalDisplay(items, isFor) {
            let total = 0

            if (isFor === 'carton') {
                total = items.reduce((a, val) => a + val.no_of_cartons, 0)
            } else if (isFor === 'skus') {
                total = items.reduce((a, val) => a + val.no_of_sku, 0)
            } else if (isFor === 'units') {
                total = items.reduce((a, val) => a + val.no_of_units, 0)
            }

            return total
        },
        handleSearchEmployee() {
            if (this.searchEmployeeName !== '') {
                this.allEmployees = this.allEmployeesCopy.filter((c) => {
                    return c.name.toLowerCase().indexOf(this.searchEmployeeName.toLowerCase()) > -1
                })

                if (this.allEmployees !== this.allEmployeesCopy) {
                    let hideItems = this.allEmployeesCopy.filter(e => !this.allEmployees.some(o => o.name === e.name))
                    let showItems = this.allEmployeesCopy.filter(e => this.allEmployees.some(o => o.name === e.name))
                    
                    this.allEmployeesCopy.map(v => {
                        hideItems.map(a => { if (v.employee_id === a.employee_id) { v.class = 'hide-employee-list' } })
                        showItems.map(a => { if (v.employee_id === a.employee_id) { v.class = '' } })
                    })
                }
            } else {
                this.allEmployees = this.warehouseEmployeeLists;
                this.allEmployeesCopy = this.warehouseEmployeeLists;
                this.allEmployeesCopy.map(a => { a.class = '' })
            }
        },
        onSelectEmployee(index) {
            let allTasksEmployeeSelected = this.assignEmployeeData[0].employees

            if (allTasksEmployeeSelected.length > 0) {
                allTasksEmployeeSelected.map(v => {
                    let findEmployee = _.find(this.allEmployeesCopy, e => v.employee_id === e.employee_id)
                    if (findEmployee !== undefined && index !== 0) {
                        findEmployee.disabled = true
                    } else {
                        this.allEmployeesCopy.forEach((item) => item.disabled = false)
                    }
                })
            }
        },
        async callInboundAPIAccordingToStatus(status, i) {
            let warehouse = this.$store.state.warehouse.currentWarehouse
            let storeInboundTab = this.$store.state.inbound
            let dataWithPage = { id: warehouse.id, page: 1 } 

            try {
                // if (this.source) this.source.cancel("cancel_previous_request")
                // this.source = axios.CancelToken.source()
                // dataWithPage.cancelToken = this.source.token

                if (status === 'pending') { // call pending API
                    dataWithPage.page = storeInboundTab.pendingInboundPagination.current_page
                    await this.fetchPendingInbounds(dataWithPage)
                    storeInboundTab.pendingInboundPagination.old_page = storeInboundTab.pendingInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                    
                } else if (status === 'floor') { // call floor api here
                    dataWithPage.page = storeInboundTab.floorInboundPagination.current_page
                    await this.fetchFloorInbounds(dataWithPage)
                    storeInboundTab.floorInboundPagination.old_page = storeInboundTab.floorInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                    
                } else if (status === 'receive_pending') { // call pending receive api here
                    dataWithPage.page = storeInboundTab.pendingReceiveInboundPagination.current_page
                    await this.fetchPendingReceivingInbounds(dataWithPage)
                    storeInboundTab.pendingReceiveInboundPagination.old_page = storeInboundTab.pendingReceiveInboundPagination.current_page
                    this.setCurrentInboundTab(i)
                }
            } catch(e) {
                // if (e !== "cancel_previous_request") this.notificationError(e)
                this.notificationError(e)
            }
        },
        processAPIInboundFetch() {
            if (this.isWarehouse3PLProvider) {
                if (this.getCurrentInboundTab === 1) {
                    this.callInboundAPIAccordingToStatus('receive_pending', 1)
                } else if (this.getCurrentInboundTab === 2) {
                    this.callInboundAPIAccordingToStatus('floor', 2)
                }
            } else {
                if (this.isWarehouseOwn) {
                    if (this.getCurrentInboundTab === 0) {
                        this.callInboundAPIAccordingToStatus('pending', 0)
                    } else if (this.getCurrentInboundTab === 1) {
                        this.callInboundAPIAccordingToStatus('floor', 1)
                    }
                }
            }
        },
        async assignAction() {
            if (this.editedIndex === -1) {
                if (this.assignEmployeeTemplate.palletizing.length !== 0 || 
                    this.assignEmployeeTemplate.receiving.length !== 0 || 
                    this.assignEmployeeTemplate.putting_away.length !== 0) {

                    await this.assignWarehouseEmployee(this.assignEmployeeTemplate)
                    this.notificationMessage('Employee successfully assigned.')
                    this.close()
                    
                    let inbound_id = new URL(location.href).searchParams.get('inboundid')
                    let warehouse_id = new URL(location.href).searchParams.get('warehouseid')
                    
                    if (inbound_id !== null && warehouse_id !== null) {
                        let singlePayload = { wid: warehouse_id, iid: inbound_id }
                        await this.fetchSingleInbound(singlePayload)
                    }

                    this.processAPIInboundFetch()                    
                }
            } else {
                let task_id = this.editedItem[0].id
                let employees = []

                let getKeys = Object.keys(this.assignEmployeeTemplate)
                getKeys.map(v => {
                    if (v === this.editedItem[0].tasktype) {
                        employees = this.assignEmployeeTemplate[v]
                    }
                })

                let updated_by = (typeof this.getUser == 'string') ? JSON.parse(this.getUser).name : this.getUser.name
                let payload = { task_id, employees, updated_by }

                await this.editTaskEmployee(payload)
                this.notificationMessage('Task has been successfully updated.')
                let inbound_id = new URL(location.href).searchParams.get('inboundid')
                let warehouse_id = new URL(location.href).searchParams.get('warehouseid')

                let singlePayload = { wid: warehouse_id, iid: inbound_id }
                await this.fetchSingleInbound(singlePayload)
                this.close()
                this.processAPIInboundFetch()
            }
        },
        isAllOptionsHidden(items) {
            if (items.length !== 0) {
                let findEmployeeNotHidden = _.find(items, e => e.class === '')
                if (findEmployeeNotHidden !== undefined) {
                    return false
                } else {
                    return true
                }     
            } else {
                return false
            }
        },
        openRemoveAssignment() {
            this.dialogRemoveAssignment = true

            // add other functions here once finalized
        },
        closeRemoveAssignment() {
            this.dialogRemoveAssignment = false
        }
    },
    mounted() {},
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/inbound/assignEmployeeDialog.scss';

.v-tooltip__content.left-arrow {
    font-size: 14px;

    &::before {
        content: " ";
        position: absolute;
        top: 50%;
        right: 100%;
        margin-top: -10px;
        border-width: 10px;
        border-style: solid;
        border-top: solid 10px transparent;
        border-bottom: solid 10px transparent;
        border-left: solid 10px transparent;
        border-right: solid 10px transparent;
    }

    &.menuable__content__active {
        background-color: #4A4A4A !important;
        overflow: inherit !important;
        z-index: 20;
        opacity: 1 !important;
    }
}

.hide-employee-list {
    display: none;
}
</style>
