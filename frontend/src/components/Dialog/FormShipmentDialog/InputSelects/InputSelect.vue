<template>
    <div :style="`${customStyles}`" :class="`${contentClass} input-general-select-wrapper ${noBorder ? 'no-border' : ''}`">
        <div v-if="noLabel" class="form-label">
            <span :style="`color: ${labelColor};`">{{ label }}</span>
        </div>
        <div class="autocomplete-wrapper" style="position: relative;">
            <v-autocomplete
                spellcheck="false"
                class="select-purchase-order-po select-purchase-order select-purchase-order-mobile"
                :items="typeOptions"
                :placeholder="placeholder"
                outlined
                item-text="name"
                item-value="id"
                :id="id"
                v-model="selectedValue"
                @change="updateValue"
                :menu-props="{ contentClass: 'po-lists-items', ...menuProps}"
                hide-details="auto"
                clearable
            >
                <template v-slot:item="{ item }">
                    <div class="d-flex flex-column" style="width: 100%;">
                        <div style="width: 100%; padding-bottom: 12px;" class="d-flex first-row justify-space-between">
                            <div>
                                {{
                                    item.name
                                }}
                            </div>
                        </div>
                    </div>
                </template>
            </v-autocomplete>
        </div>
    </div>
</template>
<style lang="scss">
@import "./scss/inputSelect.scss";
</style>
<script>
export default {
    props: {
        id: {
            default: ''
        },
        customStyles: {
            default: ''
        },
        typeOptions: {
            default: []
        },
        contentClass: {
            default: ''
        },
        placeholder: {
            default: ''
        },
        noLabel: {
            default: false
        },
        label: {
            default: ''
        },
        field: {
            default: ''
        },
        noBorder: {
            default: false
        },
        placeholderText: {
            default: ''
        },
        textColor: {
            default: '#4A4A4A'
        },
        labelColor: {
            default: '#819FB2'
        },
        placeholderColor: {
            default: '#B4CFE0'
        },
        selectedValue:{
          default: ''
        },
    },
    data: () => ({
        menuProps: {
            bottom: true,
            offsetY: true
        }
    }),
    methods: {
        updateValue(value) {
            this.$emit('update:field', value)
        }
    },
}
</script>