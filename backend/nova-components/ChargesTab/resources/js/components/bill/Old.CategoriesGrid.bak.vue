<template>
    <div class="control-section">
        <JqxGrid
            ref="categoryGrid"
            :width="width"
            :height="height"
            :columnsresize="true"
            :source="dataAdapter"
            :columns="columns"
            :editable="true"
            :editmode="'click'"
            :selectionmode="'singlerow'"
            @cellendedit="categoryGridOnCellEndEdit($event)"
            @cellbeginedit="categoryGridOnCellBeginEdit($event)"
            :showtoolbar="true"
            :rendertoolbar="createButtonsContainers"
        >
        </JqxGrid>
    </div>
</template>

<script>
import JqxGrid from "jqwidgets-scripts/jqwidgets-vue/vue_jqxgrid.vue";
import JqxButton from "jqwidgets-scripts/jqwidgets-vue/vue_jqxbuttons.vue";

export default {
    name: "CategoriesGrid",
    components: {
        JqxGrid,
        JqxButton
    },
    props: ["gridCustomers", "resourceId", 'expenseAccounts'],
    data() {
        return {
            items: [],
            customer: 0,
            width: '100%',
            height: '180px',
            dataAdapter: new jqx.dataAdapter(this.source),
            columns: [
                // {
                //     text: 'Category', width:250, dataField: 'CategoryId', displayField: 'Category', columnType: 'combobox',
                //     createeditor: (row, value, editor) => {
                //         editor.jqxComboBox({
                //             source: this.expAccountsAdapter,
                //             displayMember: 'Name',
                //             valueMember: 'Id',
                //         });
                //      }
                // },
                {
                    text: 'Category', width:250, dataField: 'CategoryId', displayField: 'Category', columnType: 'combobox',
                    createeditor: (row, value, editor) => {
                        editor.jqxComboBox({
                            source: this.expAccountsAdapter,
                            displayMember: 'Name',
                            valueMember: 'Id',
                        });
                     }
                },
                { text: 'Description', datafield: 'Description', width:180 },
                { text: 'Amount', datafield: 'Amount', width:80},
                { text: 'Billable', datafield: 'Billable', columntype: 'checkbox', width: 67 },
                { text: 'Tax', datafield: 'Tax', columntype: 'checkbox', width: 67 },
                {
                    text: 'Customer/Project', dataField: 'CustomerId', displayField: 'Customer', columnType: 'combobox',
                    createeditor: (row, value, editor) => {
                        editor.jqxComboBox({ 
                            source: this.gridCustAdapter,
                            displayMember: 'DisplayName',
                            valueMember: 'Id'
                        });
                     }
                },
                // {
                //     text: 'Customer/Project', dataField: 'CustomerId', displayField: 'Customer', columnType: 'combobox',
                //     createeditor: (row, value, editor) => {
                //         editor.jqxComboBox({ 
                //             source: this.gridCustAdapter,
                //             displayMember: 'DisplayName',
                //             valueMember: 'Id'
                //         });
                //      }
                // },
            ],
        };
    },
    beforeCreate: function () {
        const expAccounts = this.$options.propsData.expenseAccounts;
        const expAccountsSource = {
            datatype: 'array',
            datafields: [
                { name: 'Name', type: 'string' },
                { name: 'Id', type: 'string' }
            ],
            localdata: expAccounts
        };
        
        this.expAccountsAdapter = new jqx.dataAdapter(expAccountsSource, { autoBind: true });

        const gridCusts = this.$options.propsData.gridCustomers;
        const gridCustSource = {
            datatype: 'array',
            datafields: [
                { name: 'DisplayName', type: 'string' },
                { name: 'Id', type: 'string' }
            ],
            localdata: gridCusts
        };
        this.gridCustAdapter = new jqx.dataAdapter(gridCustSource, { autoBind: true });

        this.source = {
            datatype: 'array',
            datafields: [
                // name - determines the field's name.
                // value - the field's value in the data source.
                // values - specifies the field's values.
                // values.source - specifies the foreign source. The expected value is an array.
                // values.value - specifies the field's value in the foreign source.
                // values.name - specifies the field's name in the foreign source.
                // When the adapter is loaded, each record will have a field called 'Country'. The 'Country' for each record comes from the countriesAdapter where the record's 'countryCode' from gridAdapter matches to the 'value' from countriesAdapter.
                { name: 'Category', values: { source: this.expAccountsAdapter.records, value: 'Id', name: 'Name' } },
                { text: 'Description', type: 'string' },
                { text: 'Amount', type: 'number' },
                { text: 'Billable', type: 'bool' },
                { text: 'Tax', type: 'bool' },
                { text: 'Customer', values: { source: this.gridCustAdapter.records, value: 'Id', name: 'DisplayName' } },
            ],
            id: 'id',
            localdata: this.items
        };


    },
    created() {
        //
    },
    beforeMount() {
       //
    },
    methods: {
        categoryGridOnCellBeginEdit: function(e){
            console.log(e)
        },
        categoryGridOnCellEndEdit: function(e){
            const value = this.$refs.categoryGrid.getrows();
            this.$emit('clicked', value)
        },
        mapCustomers() {
            const obj = {}

            for(let  i = 0 ; i < customers.length; i++){
                let name = customers[i].ID
                obj[customers[i].ID]=customers[i].Name;
            }

            var customerValues = this.extractValues(obj);
        },
        createButtonsContainers: function (statusbar) {
            let buttonsContainer = document.createElement('div');
            buttonsContainer.style.cssText = 'overflow: hidden; position: relative; margin: 5px;';
            let addButtonContainer = document.createElement('div');
            let deleteButtonContainer = document.createElement('div');
            /* let reloadButtonContainer = document.createElement('div');
            let searchButtonContainer = document.createElement('div'); */
            addButtonContainer.id = 'addButton';
            deleteButtonContainer.id = 'deleteButton';
            /* reloadButtonContainer.id = 'reloadButton';
            searchButtonContainer.id = 'searchButton'; */
            addButtonContainer.style.cssText = 'float: left; margin-left: 5px;';
            deleteButtonContainer.style.cssText = 'float: left; margin-left: 5px;';
            /* reloadButtonContainer.style.cssText = 'float: left; margin-left: 5px;';
            searchButtonContainer.style.cssText = 'float: left; margin-left: 5px;'; */
            buttonsContainer.appendChild(addButtonContainer);
            buttonsContainer.appendChild(deleteButtonContainer);
           /*  buttonsContainer.appendChild(reloadButtonContainer);
            buttonsContainer.appendChild(searchButtonContainer); */
            statusbar[0].appendChild(buttonsContainer);
        },
        createButtons: function () {
            let addButtonOptions = {
                width: 60, height: 25, value: 'Add'
                /* imgSrc: '/images/images/add.png',
                imgPosition: 'center', textPosition: 'center',
                textImageRelation: 'imageBeforeText' */
            }
            let addButton = jqwidgets.createInstance('#addButton', 'jqxButton', addButtonOptions);
            let deleteButtonOptions = {
                width: 60, height: 25, value: 'Delete'
                /* imgSrc: '/images/images/close.png',
                imgPosition: 'center', textPosition: 'center',
                textImageRelation: 'imageBeforeText' */
            }
            let deleteButton = jqwidgets.createInstance('#deleteButton', 'jqxButton', deleteButtonOptions);
            addButton.addEventHandler('click', (event) => {
                this.$refs.categoryGrid.addrow(null, {});
            });
            deleteButton.addEventHandler('click', (event) => {
                let selectedrowindex = this.$refs.categoryGrid.getselectedrowindex();
                let rowscount = this.$refs.categoryGrid.getdatainformation().rowscount;
                let id = this.$refs.categoryGrid.getrowid(selectedrowindex);
                this.$refs.categoryGrid.deleterow(id);
            });
        }

    },
    mounted() {
        //this.$refs.addSaveButton.focus()
        /* this.gridApi = this.gridOptions.api;
        this.gridColumnApi = this.gridOptions.columnApi; */
        //this.changeStyle();
        this.createButtons();
        
    }
};
</script>

<style scoped>
@import "~jqwidgets-scripts/jqwidgets/styles/jqx.base.css";
</style>
