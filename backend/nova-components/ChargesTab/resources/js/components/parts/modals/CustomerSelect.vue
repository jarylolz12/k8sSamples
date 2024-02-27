<template>
    <div>
        <input id="hidCust" type="hidden" :value="itemC">
        <JqxComboBox
            ref="myComboBox"
            @select="myComboBoxOnSelect($event)"
            :width="150"
            :height="25"
            :source="dataAdapter"
            :selectedIndex="-1"
            :displayMember="'FullyQualifiedName'"
            :valueMember="'Id'"
            :placeHolder="'Search here..'"
            :remoteAutoComplete="true"
            :search="search"
            :remoteAutoCompleteDelay="200"
            :theme="'material'"
        >
        </JqxComboBox>
        <!-- @change="onChange($event)" @bindingComplete="onBindingComplete($event)" @change="onChange($event)"
            @close="onClose($event)" -->
        <!-- <div ref="selectionlog" style="font-size: 12px; font-family: Verdana"></div> -->
    </div>
</template>

<script>
import JqxComboBox from "jqwidgets-scripts/jqwidgets-vue/vue_jqxcombobox.vue";

export default {
    name: "CustomerSelect",
    components: {
        JqxComboBox
    },
    props: ["services", "customers", "resourceId"],
    data() {
        return {
            itemC: 'Type Here',
            dataAdapter: new jqx.dataAdapter(this.source, {
                formatData: (data) => {
                    if (this.$refs.myComboBox.searchString() != undefined) {
                        data.name_startsWith = this.$refs.myComboBox.searchString();
                        return data;
                    }
                    /* if (this.$refs.myComboBox.search != undefined) {
                        data.values = this.$refs.myComboBox.search;
                        let search = this.$refs.myComboBox.search;
                        let source = this.$refs.myComboBox.source;
                        console.log(search);
                        console.log(source);
                        return data;
                    } */
                }
            }),
            search: () => {
                this.dataAdapter.dataBind();
            }
        };
    },
    beforeCreate: function () {
        /* const customersObj = this.$options.propsData.customers
        const obj2 = [{
                Id: [0],
                Name: ['Type Here']
            }];

        for(let  i = 0 ; i < customersObj.length; i++){
            obj2.push({
                Id: [customersObj[i].Id],
                Name: [customersObj[i].FullyQualifiedName]
            });
        } */

        this.source = {
            datatype: 'json',
            datafields: [
                { name: 'Id' },
                { name: 'FullyQualifiedName' }
            ],
            url: "/custom/qbo/customers-search"/* ,
            data:  *//* {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                username: "jqwidgets"
            } */
        };
    },
    created() {
        //
    },
    beforeMount() {
       //
    },
    methods: {
        myComboBoxOnSelect: function (event) {
            /* console.log(event.args.item); */
            if (event.args) {
                let item = event.args.item;
                if (item) {
                    /* let valueElement = document.createElement('div');
                    valueElement.innerHTML = 'Value: ' + item.value;
                    let labelElement = document.createElement('div');
                    labelElement.innerHTML = 'Label: ' + item.label;
                    let selectionLog = this.$refs.selectionlog;
                    selectionLog.innerHTML = '';
                    selectionLog.appendChild(labelElement);
                    selectionLog.appendChild(valueElement); */
                    this.$emit('clicked', item.value)
                }
            }
        },
        onBindingComplete: function (event) {
            //console.log(event);
        },
        onChange: function (event) {
            /* let search = this.$refs.myComboBox.search;
            let source = this.$refs.myComboBox.source;
            console.log(search);
            console.log(source); */
            //console.log(this.itemC);
            //axios.get("/custom/invoices/by-shipment/" + this.resourceId)
            /* axios.get("/custom/qbo/customers-search/" + this.itemC)
            .then(response => {
                // JSON responses are automatically parsed.

                const customersObj = response.data
                let obj2 = [];

                let customerSource = this.$refs.customersComboBox.source._source;

                for(let  i = 0 ; i < customersObj.length; i++){
                    obj2.push({
                        Id: [customersObj[i].Id],
                        Name: [customersObj[i].FullyQualifiedName]
                    });
                }

                customerSource.data = obj2; */

               /*  let customerAdapter = new jqx.dataAdapter(customerSource); */
               /* let customerAdapter = new jqx.dataAdapter(customerSource, {
                beforeLoadComplete: (records) => {
                        let filteredRecords = new Array();
                        for (let i = 0; i < customersObj.length; i++) {
                            filteredRecords.push(records[i]);
                        }
                        return filteredRecords;
                    }
                });
                this.$refs.customersComboBox.setOptions({ source: customerAdapter}); */

/* if (event.args) {
    this.$refs.ordersComboBox.setOptions({ disabled: false, selectedIndex: -1 });
    let value = event.args.item.value;
    let ordersSource = this.$refs.ordersComboBox.source._source;
    ordersSource.data = { CustomerID: value };
    let ordersAdapter = new jqx.dataAdapter(ordersSource, {
        beforeLoadComplete: (records) => {
            let filteredRecords = new Array();
            for (let i = 0; i < records.length; i++) {
                if (records[i].CustomerID == value)
                    filteredRecords.push(records[i]);
            }
            return filteredRecords;
        }
    });
    this.$refs.ordersComboBox.setOptions({ source: ordersAdapter, autoDropDownHeight: ordersAdapter.records.length > 10 ? false : true });
} */

/* if (event.args) {
    let index = this.$refs.ordersComboBox.selectedIndex;
    if (index !== -1) {
        let record = this.ordersAdapter.records[index];
        let detailsSource =
            {
                dataType: 'json',
                dataFields: [
                    { name: 'CustomerID' },
                    { name: 'OrderID' },
                    { name: 'ShipCity' },
                    { name: 'OrderDate' },
                    { name: 'ShipName' },
                    { name: 'ShipCountry' },
                    { name: 'ShipAddress' }
                ],
                localData: record
            };
        let detailsAdapter = new jqx.dataAdapter(detailsSource);
        let options = {
            source: detailsAdapter,
            width: 500,
            columns: [
                { text: 'Order Date', dataField: 'OrderDate', cellsFormat: 'd' },
                { text: 'Ship Country', dataField: 'ShipCountry' },
                { text: 'Ship Address', dataField: 'ShipAddress' },
                { text: 'Ship Name', dataField: 'ShipName' }
            ]
        };
        jqwidgets.createInstance('#orderInfo', 'jqxDataTable', options);
    }
} */

                /* this.source = {
                    datatype: 'json',
                    datafields: [
                        { name: 'Id' },
                        { name: 'Name' }
                    ],
                    id: 'id',
                    localdata: obj2
                };

                this.dataAdapter = new jqx.dataAdapter(this.source) */

            /* })
            .catch(e => {
                console.log(e)
                if (response.data.message == 'Unauthenticated.' || response.message == 'Unauthenticated.'){
                    location.reload(true);
                }
            }); */
        },
        onClose: function (event) {
            /* let searched = this.$refs.myComboBox.search;
            console.log(searched); */
            /* console.log(event.args)
            if (event.args) {
                let item = event.args.item;
                if (item) {
                    let valueElement = document.createElement('div');
                    valueElement.innerHTML = 'Value: ' + item.value;
                    let labelElement = document.createElement('div');
                    labelElement.innerHTML = 'Label: ' + item.label;
                    let selectionLog = this.$refs.selectionlog;
                    selectionLog.innerHTML = '';
                    selectionLog.appendChild(labelElement);
                    selectionLog.appendChild(valueElement);
                    this.$emit('clicked', item.value)
                }
            } */
        }
    },
    mounted() {
        //
    }
};
</script>

<style scoped>
    @import "~jqwidgets-scripts/jqwidgets/styles/jqx.base.css";
    @import "~jqwidgets-scripts/jqwidgets/styles/jqx.material.css";

    .jqx-listbox{
        position: relative !important;
        top: 13px !important;
    }

    .jqx-listbox-material{
        position: relative !important;
        top: 13px !important;
    }

</style>
