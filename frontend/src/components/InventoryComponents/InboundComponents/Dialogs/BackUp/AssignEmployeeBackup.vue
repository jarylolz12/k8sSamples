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
                <span class="headline"> Assign Employee ({{ fromComponent }}) </span>
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
                                <div class="assign-employee-header mb-5" v-if="editedItem.length > 0">
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
                                                <td class="assign-employee-col">
                                                    <p class="mb-0 px-4">{{ item.task }}</p>
                                                </td>

                                                <td class="assign-employee-col">
                                                    <v-select
                                                        :items="warehouseEmployeeLists"
                                                        v-model="item.employees"
                                                        spellcheck="false" 
                                                        class="select-product shrink employee"
                                                        item-text="name"
                                                        item-value="id"
                                                        placeholder="Select Warehouse Employee"
                                                        outlined
                                                        :menu-props="{ contentClass: 'product-lists-items assign-employee-lists', bottom: true, offsetY: true, closeOnContentClick: false }"
                                                        hide-details="auto"
                                                        multiple
                                                        small-chips
                                                        deletable-chips>

                                                        <template v-slot:selection="{ item }">
                                                            <v-chip class="product-item-chip font-medium" close @click:close="removeChipEmployee(item, index)">
                                                                <span class="name">{{ item.name }}</span>
                                                            </v-chip>
                                                        </template>

                                                        <template v-slot:prepend-item>
                                                            <v-list-item class="search-input-field pa-0">
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
                                                            <v-list-item v-on="on" v-bind="attrs" #default="{ active }">
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
                                                    </v-select>
                                                </td>

                                                <td class="assign-employee-col">
                                                    <v-btn
                                                        icon
                                                        class="btn remove-btn"
                                                        @click="removeItemRow(item)"
                                                        :disabled="item.employees.length === 0">
                                                        <v-icon>mdi-close</v-icon>
                                                    </v-btn>
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>

                                    <div class="preferences" v-if="editedItem.length > 1">
                                        <img src="@/assets/icons/info-blue.svg" width="18px" height="18px">
                                        <span style="color: #6D858F;">Preference of the assignments will be applicable to all order</span>
                                    </div>

                                    <!-- <div class="add-row-wrapper">
                                        <button class="btn-white add-btn" @click.stop="addEmployeeRow" 
                                        :disabled="assignEmployeeData.length === 3 || checkIfThreeOptionsSelected || disabledButtonOnly">+ Add Employee (s)</button>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions class="receive-button-actions">
                <button class="btn-blue" text @click="assignAction">
                    Assign
                </button>

                <button class="btn-white" text @click="close">
                    Cancel
                </button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import globalMethods from '../../../../utils/globalMethods'
import _ from 'lodash'

export default {
    name: 'AssignEmployeeDialog',
    props: [
        'dialogData', 
        'editedItemData',
        'fromComponent',
        'currentWarehouseSelected'
    ],
    components: {},
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
                employees: []
            },
            {
                task: 'Receiving',
                employees: []
            },
            {
                task: 'Palletizing',
                employees: []
            },
            {
                task: 'Put Away',
                employees: []
            }
        ],
        // static data
        warehouseEmployeeLists: [
            {
                id: 1,
                name: 'Jeremiah Quinn'
            },
            {
                id: 2,
                name: 'Jaylah Whitehead'
            },
            {
                id: 3,
                name: 'Kasey Case'
            },
            {
                id: 4,
                name: 'Ariana Gibson'
            },
            {
                id: 5,
                name: 'Delilah Bailey'
            },
            {
                id: 6,
                name: 'Maverick Griffith'
            },
            {
                id: 7,
                name: 'Rohan Solomon'
            },
        ],
        warehouseEmployeeListsCopy: [],
        taskItemsLists: [
            {
                value: 'receiving',
                name: 'Receiving',
                disabled: false
            },
            {
                value: 'palletizing',
                name: 'Palletizing',
                disabled: false
            },
            {
                value: 'putting-away',
                name: 'Putting Away',
                disabled: false
            },
        ],
        checkIfThreeOptionsSelected: false,
        isAllOptionDisabled: false,
        disabledButtonOnly: false
    }),
    watch: { },
    computed: {
        ...mapGetters({ }),
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
                return this.editedItemData
            },
            set(value) {
                this.$emit('update:editedItemData', value)
            }
        },
        icon() {
            if (this.checkIfThreeOptionsSelected) return 'mdi-checkbox-marked'
            if (this.selectedSomeTasks) return 'mdi-minus-box'
            return 'mdi-checkbox-blank-outline'
        },
        assignEmployeeTemplate() {
            // if (this.editedItem.length !== 0) {
            //     if (this.editedItem.length === 1) {
            //         let { id } = this.editedItem[0]

            //         return {
            //             inbound_id: id,
            //             receiving: this.assignEmployeeData[]
            //         }
            //     }
            // }

            return {}
        }
    },
    methods: {
        ...mapActions({ 
            fetchWarehouseEmployees: 'employees/fetchWarehouseEmployees'
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
                    employees: []
                },
                {
                    task: 'Receiving',
                    employees: []
                },
                {
                    task: 'Palletizing',
                    employees: []
                },
                {
                    task: 'Put Away',
                    employees: []
                }
            ]
            // this.checkIfThreeOptionsSelected = false
            // this.disabledButtonOnly = false
            // this.isAllOptionDisabled = false
            // this.taskItemsLists.forEach((item) => item.disabled = false)
        },
        removeChipEmployee(item, index) {
            let indexLodash = _.findIndex(this.assignEmployeeData[index].employees, e => (e == item.id))            
            if (indexLodash > -1) {
				this.assignEmployeeData[index].employees.splice(indexLodash, 1)
			}
        },
        removeChipTasks(item, index) {
            let indexLodash = _.findIndex(this.assignEmployeeData[index].tasks, e => (e == item.value))
            if (indexLodash > -1) {
				this.assignEmployeeData[index].tasks.splice(indexLodash, 1)
			}
        },
        toggle(item, index) {
            if (item !== null) {
                if (this.assignEmployeeData[index].tasks.length > 0) {
                    this.assignEmployeeData[index].tasks = []
                    this.taskItemsLists.forEach((item) => item.disabled = false)
                    this.checkIfThreeOptionsSelected = false
                    this.disabledButtonOnly = false
                } else {
                    this.assignEmployeeData[index].tasks = this.taskItemsLists.slice()
                    this.taskItemsLists.forEach((item) => item.disabled = true)
                    this.checkIfThreeOptionsSelected = true
                    this.disabledButtonOnly = true
                }
            }
        },
        onSelect(index) {
            // get all count of tasks under assignEmployeeData array
            let totalCount = this.assignEmployeeData.reduce((counter, { tasks }) => { return counter += tasks.length }, 0)

            // get task count of a particular index
            let particularCountIndex = this.assignEmployeeData[index].tasks.length

            if (totalCount < 3) {
                if (particularCountIndex > 0) {
                    if (particularCountIndex < 3) {
                        this.isAllOptionDisabled = true

                    } else if (particularCountIndex === 3) {
                        this.isAllOptionDisabled = false
                        this.taskItemsLists.forEach((item) => item.disabled = true)
                        this.checkIfThreeOptionsSelected = true
                    }
                } else {
                    this.isAllOptionDisabled = false
                }
            } else {
                if (totalCount === 3 && particularCountIndex < 3) {
                    this.isAllOptionDisabled = true
                    this.disabledButtonOnly = true

                } else if (totalCount === 3 && particularCountIndex === 3) {
                    this.isAllOptionDisabled = false
                    this.taskItemsLists.forEach((item) => item.disabled = true)
                    this.checkIfThreeOptionsSelected = true
                    this.disabledButtonOnly = true

                } else {
                    this.isAllOptionDisabled = false
                    this.disabledButtonOnly = false
                    this.checkIfThreeOptionsSelected = false
                }
            }
            
        },
        disabledItems(item) {
            let findTask = _.find(this.assignEmployeeData, e => e.tasks.some(i => i === item.value))
            if (findTask !== 'undefined' && findTask !== undefined) {
                return findTask.tasks.map(v => { item.value === v })
            }
        },
        removeItemRow(item) {
            // let getIndex = this.assignEmployeeData.indexOf(item)
            // this.assignEmployeeData.splice(getIndex, 1)

            if (item.employees.length > 0) {
                item.employees = []
            }
        },
        addEmployeeRow() {
            // let getItem = this.assignEmployeeData

            this.assignEmployeeData.push({
                warehouse_employee: [],
                tasks: []
            })

            // this.assignEmployeeData = getItem
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
            if (!this.searchEmployeeName) {
                this.warehouseEmployeeListsCopy = this.warehouseEmployeeLists;
            }

            this.warehouseEmployeeListsCopy = this.warehouseEmployeeLists.filter(c => {
                return c.name.toLowerCase().indexOf(this.searchContactName.toLowerCase()) > -1
            })
        },
        assignAction() {
            console.log('assign action here');
        }
    },
    async mounted() {
        let checkWarehouseTypeId = this.currentWarehouseSelected !== null ? 
            this.currentWarehouseSelected.id : null

        await this.fetchWarehouseEmployees(checkWarehouseTypeId)
    },
    updated() {
        console.log(this.editedItem);
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/pages_scss/inventory/inbound/assignEmployeeDialog.scss';
</style>
