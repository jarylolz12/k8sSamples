<template>
<div id="container-tracking-wrapper" :class="`${contentClass} table-wrapper`">
    <v-data-table 
        :headers="headers" 
        :items="containerItems"
        mobile-breakpoint="769"
        :page="1"
        :items-per-page="100"
        class="purchase-orders-table"
        hide-default-footer
        style="box-shadow: none !important"
        fixed-header
        hide-default-header
        ref="create-shipment-container-table">
        <template v-slot:header="{ props: { headers } }">
            <thead>
                <tr>
                    <th v-for="(item, index) in headers" 
                        :key="index"
                        role="column-header"
                        :aria-label="item.text"
                        scope="col"
                        :style="`text-align: ${item.textAlign}; color: #6D858F;width: ${item.width};`"
                        >
                        {{ item.text }}
                    </th>
                </tr>  
            </thead>
        </template>
        <template v-slot:[`item.cartons`]="{ item }">
            <div style="height: 40px;">
                <v-text-field
                :height="40"
                color="#002F44"
                width="200px"
                v-model="item.cartons"
                type="text"
                dense
                class="container-cartons"
                placeholder="Carton"
                outlined
                hide-details="auto">
                </v-text-field>
            </div>
        </template>
        <template v-slot:[`item.action`]="{ item }">
            <a @click.stop="removeContainer(item)" style="cursor: pointer; height: 30px; margin-top: 10px;" class="d-flex">
                <slot name="removeIcon"></slot>
            </a>
        </template>
        <template v-slot:[`item.size`]="{ item }">
            <v-select
                class="text-fields select-items select-items-general container-size"
                :items="sizes"
                height='40px'
                outlined
                v-model="item.size"
                hide-details="auto"
                :menu-props="{ bottom: true, offsetY: true }">
            </v-select>
        </template>
        <template v-slot:[`item.cbm`]="{ item }">
            <div style="width: 100%;">
                <v-text-field
                background-color="#ffffff"
                :height="40"
                color="#002F44"
                width="200px"
                v-model="item.cbm"
                type="text"
                dense
                class="container-volume container-cartons"
                placeholder="Volume"
                outlined
                hide-details="auto">
                </v-text-field>
            </div>
        </template>
        <template v-slot:[`item.kg`]="{ item }">
            <v-text-field
            background-color="#ffffff"
            :height="40"
            color="#002F44"
            width="200px"
            v-model="item.kg"
            type="text"
            dense
            class="container-weight container-cartons"
            placeholder="Weight"
            outlined
            hide-details="auto">
            </v-text-field>
        </template>
        <template v-slot:[`item.container_num`]="{ item }">
            <v-text-field
            background-color="#ffffff"
            :height="40"
            color="#002F44"
            width="200px"
            v-model="item.container_num"
            type="text"
            dense
            class="container-num"
            placeholder="Type container number"
            outlined
            hide-details="auto">
            </v-text-field>
        </template>
    </v-data-table>
    <div style="padding-left: 0px !important;" class="add-container-wrapper">
        <v-btn class="btn-white mr-4 shipment-header-button-btn" @click="addContainer">
            <span class="add-container-span">{{ "+ Add Container" }}</span>
        </v-btn>
    </div>
</div>
</template>

<style lang="scss">
@import "./scss/containersTable.scss";
@import "./scss/containersTrackingTable.scss";

</style>

<script>
export default {
    data: () => ({
        containerItems: []
    }),
    updated() {
        this.$emit('update:items', this.containerItems)
    },
    methods: {
        removeContainer(item) {
            let getIndex = this.containerItems.indexOf(item)
            this.containerItems.splice(getIndex, 1)
        },
        addContainer() {
            //add container
            this.containerItems.push({
                id: new Date().getTime(),
                container_num: '',
                size: '',
                cbm: null,
                kg: null,
                cartons: 0,
                seal_num: null
            })
        }
    },
    props: {
        headers: {
            default: []
        },
        sizes: {
            default: []
        },
        items: {
            default: []
        },
        contentClass: {
            default: 'container-wrapper'
        }
    },
    created() {
        this.containerItems = this.items
    }
}
</script>
