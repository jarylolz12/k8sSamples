<template>
    <div class="control-section">
        <JqxGrid
            ref="myGrid"
            :width="width"
            :height="height"
            :source="dataAdapter"
            :columns="columns"
            :editable="true"
            :editmode="'click'"
            :selectionmode="'singlerow'"
            @cellendedit="myGridOnCellEndEdit($event)"
            @cellbeginedit="myGridOnCellBeginEdit($event)"
            :showtoolbar="true"
            :rendertoolbar="createButtonsContainers"
        >
        </JqxGrid>
    </div>
</template>

<script>
import JqxGrid from "jqwidgets-scripts/jqwidgets-vue/vue_jqxgrid.vue";
import JqxButton from "jqwidgets-scripts/jqwidgets-vue/vue_jqxbuttons.vue";

import { beverages } from './data.js'
//window.$ = jQuery


export default {
    name: "LinesGrid",
    components: {
        JqxGrid,
        JqxButton
    },
    props: ["services", "customers", "resourceId"],
    data() {
        return {
            items: [], //orderDataSource.slice(0),
            //rowSelection: null,
            beverages: beverages,
            customer: 0,
            width: '100%',
            height: '180px',
            dataAdapter: new jqx.dataAdapter(this.source),
            columns: [
                { text: 'Service', width: 250, datafield: 'ServiceId', displayfield: 'Service',     columntype: 'combobox',
                    createeditor: (row, value, editor) => {
                        editor.jqxComboBox({ source: this.servicesAdapter, displayMember: 'Name', valueMember: 'Id' });
                    }
                },
                { text: 'Description', datafield: 'Description' },
                { text: 'Quantity', datafield: 'Quantity', width: 80 },
                { text: 'UnitPrice', datafield: 'UnitPrice', width: 80 },
                {
                    text: 'Amount', width: 80, editable: false, datafield: 'Amount',
                    cellsrenderer: function (index, datafield, value, defaultvalue, column, rowdata) {
                        var Amount = parseFloat(rowdata.UnitPrice) * parseFloat(rowdata.Quantity);
                        return "<div style='margin: 4px;' class='jqx-right-align'>" + (isNaN(Amount) ? 0 : Amount) + "</div>";
                    }
                }
            ]
        };
    },
    beforeCreate: function () {
        const servicesObj = this.$options.propsData.services
        const obj2 = [];

        for(let  i = 0 ; i < servicesObj.length; i++){
                obj2.push({
                Id: [servicesObj[i].Id],
                Name: [servicesObj[i].Name]
            });
        }

        console.log(obj2);

        let extractedValues = Object.keys(obj2);
        console.log(extractedValues);

        const servicesSource = {
            datatype: 'array',
            datafields: [
                { name: 'Name', type: 'string' },
                { name: 'Id', type: 'string' }
            ],
            localdata: obj2
        };
        this.servicesAdapter = new jqx.dataAdapter(servicesSource, { autoBind: true });

        this.source = {
            datatype: 'json',
            datafields:
                [
                    { name: 'Service', value: 'ServiceId', values: { source: this.servicesAdapter.records, value: 'Id', name: 'Name' } },
                    /* { name: 'ServiceId', type: 'string' }, */
                    { name: 'Description', type: 'string' },
                    /* { name: 'type', type: 'string' }, */
                    { name: 'Quantity', type: 'int' },
                    { name: 'UnitPrice', type: 'int' }/* ,
                    { name: 'Amount', type: 'int' } */
                ],
                id: 'id',
                localdata: this.items
        };
        this.rowValues = '';
    },
    created() {
        //
    },
    beforeMount() {
       //
    },
    methods: {
        onBtHide() {
            /* $("button").click(function(){
                $("p").hide();
            }); */
            $("p").hide();
        },
        myGridOnCellBeginEdit: function (event) {
            let args = event.args;
            /* if (args.datafield === 'firstname') {
                this.rowValues = '';
            }
            this.rowValues += args.value.toString() + '    ';
            if (args.datafield === 'price') {
                this.$refs.cellBeginEditLog.innerHTML = 'Begin Row Edit: ' + (1 + args.rowindex) + ', Data: ' + this.rowValues;
            } */
        },
        myGridOnCellEndEdit: function (event) {
            let args = event.args;
            /* if (args.datafield === 'firstname') {
                this.rowValues = '';
            }
            this.rowValues += args.value.toString() + '    ';
            if (args.datafield === 'price') {
                this.$refs.cellEndEditLog.innerHTML = 'End Row Edit: ' + (1 + args.rowindex) + ', Data: ' + this.rowValues;
            } */
            console.log('CellEditEnd');
            const value = this.$refs.myGrid.getrows();
            this.$emit('clicked', value)
        },
        serviceValues() {
            let obj2 = this.getObjectTwo();
            let extractedValues = this.extractValues(obj2);
            console.log(extractedValues);

            return extractedValues;
        },
        getObjectTwo() {
            const obj2 = {}

            for(let  i = 0 ; i < this.services.length; i++){
                obj2[this.services[i].Id]=this.services[i].Name;
            }

            console.log(obj2);

            return obj2;
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
                //let datarow = [];
                console.log('Add service');
                this.$refs.myGrid.addrow(null, {});
            });
            deleteButton.addEventHandler('click', (event) => {
                let selectedrowindex = this.$refs.myGrid.getselectedrowindex();
                let rowscount = this.$refs.myGrid.getdatainformation().rowscount;
                let id = this.$refs.myGrid.getrowid(selectedrowindex);
                this.$refs.myGrid.deleterow(id);
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
