<template>
    <div :style="`${isMobile ? 'width: 100% !important;' : ''}`" class="containers-table-wrapper">
        <v-data-table 
            :headers="headers" 
            :items="items"
            mobile-breakpoint="100"
            :page="1"
            :items-per-page="100"
            hide-default-footer
            style="box-shadow: none !important"
            fixed-header
            hide-default-header>
            <template v-slot:header="{ props: { headers } }">
                <thead>
                    <tr>
                        <th v-for="(item, index) in headers" 
                            :key="index"
                            role="column-header"
                            :aria-label="item.text"
                            scope="col"
                            :placeholder="0"
                            :style="`text-transform: uppercase !important;text-align: ${item.textAlign}; color: #6D858F;width: ${item.width};} !important;} !important; height: 36px !important; background-color: ${headerBackground}; box-shadow: none !important;`"
                            >
                            {{ item.text }}
                        </th>
                    </tr>
                </thead>
            </template>
            <template v-slot:[`item.plus`]="{ item }">
                <a @click.stop="add(item)" class="operator">
                    <generic-icon color="#0171A1" :marginLeft="0" iconName="add"></generic-icon>
                </a>
            </template>
            <template v-slot:[`item.how_many`]="{ item }">
                <div class="how-many-wrapper" style="width: 100%; text-align: center;">
                    <v-text-field
                        height="40px"
                        color="#002F44"
                        width="200px"
                        v-model="item.how_many"
                        type="number"
                        dense
                        placeholder="0"
                        outlined
                        hide-details="auto">
                    </v-text-field>
                </div>
            </template>
            <template v-slot:[`item.minus`]="{ item }">
                <a @click.stop="subtract(item)" class="operator">
                   <generic-icon color="#0171A1" :marginLeft="0" iconName="subtract"></generic-icon>
                </a>
            </template>
            <template v-slot:[`item.size`]="{ item }">
                <div class="checkbox-wrapper-create checkbox-wrapper-desktop checkbox-wrapper-notify-party">
                    <label :class="`${isMobile ? 'd-flex flex-row align-items' : ''} ${item.checked ? 'checked': ''}`" style="position: relative;">
                        <generic-icon :marginLeft="0" :iconName="`${(item.checked) ? 'checked' : 'not-checked'}`"></generic-icon>
                        <input @click.prevent="toggleSize(item)" style="position: absolute; opacity: 0;" type="checkbox" :checked="item.checked" class="" />
                        <span style="color: #4A4A4A; padding-left: 12px !important;">
                            {{ item.size }}
                        </span>
                    </label>
                </div>
            </template>
        </v-data-table>
    </div>
</template>
<style lang="scss">
@import "./scss/containersTable.scss";
</style>
<script>
import GenericIcon from '../../../Icons/GenericIcon'
export default {
    methods: {
        toggleSize(item) {
            let findIndex = this.items.indexOf(item)
            this.items[findIndex].checked = !item.checked
            this.$emit('update:items',this.items)
        },
        add(item) {
            let how_many = isNaN(item.how_many) ? 0 : parseInt(item.how_many)
            let findIndex = this.items.indexOf(item)
            this.items[findIndex].how_many = how_many + 1

            //check if how many is greater than 0
            if ( this.items[findIndex].how_many > 0 )
                this.items[findIndex].checked = true

            this.$emit('update:items',this.items)
        },
        subtract(item) {
            let how_many = isNaN(item.how_many) ? 0 : parseInt(item.how_many)

            if ( how_many > 0 ) {
                let findIndex = this.items.indexOf(item)
                this.items[findIndex].how_many = how_many - 1

                //uncheck if how many is 0
                if ( this.items[findIndex].how_many == 0 )
                    this.items[findIndex].checked = false

                this.$emit('update:items',this.items)
            }
            
        }
    },
    components: {
        GenericIcon
    },
    props: {
        headerBackground: {
            default: ''
        },
        isMobile: {
            default: false
        },
        textColor: {
            default: ''
        },
        headers: {
            default: []
        },
        items: {
            default: []
        }
    }
}
</script>
