<template>
    <div :style="`margin-left: ${marginLeft}; margin-top:${marginTop}; margin-bottom:${marginBottom};width: ${width} !important;`" :class="`${contentClass} ${background} select-auto-complete-main-wrapper`">
        <div v-if="!noLabel" class="form-label">
            <span>{{ label }}</span>
            <span v-if="required" style="color:red !important; padding-left: 5px;">{{ "*" }}</span>
        </div>
        <div style="width: 100% !important;" :class="`select-items-wrapper ${isMobile ? 'select-items-wrapper-mobile' : ''} select-autocomplete`">
            <slot v-bind:mainContent="{ updateValue: updateValue, textColor: textColor, placeholderColor: placeholderColor, placeholderText: placeholderText, inputFontWeight: inputFontWeight, inputFontSize: inputFontSize, items: items, menuProps: menuProps, rules: rules, filter: filter, po: po, key: k, updateProducts: updateProducts, updateProductPurchaseOrder: updateProductPurchaseOrder, product: product, field: field, updateCustomValue: updateCustomValue, updateRole: updateRole }" name="input"></slot>
        </div>
    </div>
</template>
<style lang="scss">
@import "./scss/selectAutoComplete.scss";
</style>
<script>
import _ from 'lodash'

export default {
    mounted() {
    },
    computed: {
        classWidth() {
            let splitWidth = this.width.split('%')
            let finalClass = `w-${splitWidth[0]}`
            return finalClass
        }
    },
    props: {
        filter: {
            default: null
        },
        isMobile: {
            default: false
        },
        noLabel: {
            default: false
        },
        rules: {
            default: null
        },
        background: {
            default: 'not-selected'
        },
        contentClass: {
            default: ''
        },
        width: {
            default: '100%'
        },
        required: {
            default: false
        },
        id: {
            default: 'select-autocomplete-wrapper-custom'
        },
        menuProps: {
            default: null
        },
        marginLeft: {
            default: "0px",
        },
        marginTop: {
            default: "0px"
        },
        label: {
            default: ''
        },
        marginBottom: {
            default: "0px"
        },
        inputFontWeight: {
            default: 600
        },
        inputFontSize: {
            default: 10
        },
        field: {
            default: ''
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
        item: {
            default: null
        },
        items: {
            default: []
        },
        placeholderColor: {
            default: '#B4CFE0'
        },
        product: {
            default: null
        },
        po: {
            default: null
        },
        k: {
            default: 0
        }
    },
    methods: {
        updateProducts(po, k, value) {
            this.$emit('update:field', value)
            this.$emit('updateProducts', po, k)
        },
        updateProductPurchaseOrder(options, item, key, value) {
            this.$emit('update:field', value)
            this.$emit('updateProductPurchaseOrder', options, item, key)
        },
        updateCustomValue(item, value) {
            this.$emit('update:field', value)
            let isSupplier = (item.name === 'Add Consignee') ? false : true

            this.$emit('showSupplierModal', isSupplier)
        },
        fetchPurchaseOrderOptions(qry, type, entity_id, other_id) {
            //fetch purchase order options
            // we did not pass query here as we will fetch purchase order options
            // based on the current login customer
            return new Promise((resolve) => {
                this.$store.dispatch('po/fetchPurchaseOrderOptions',{
                    qry,
                    type,
                    entity_id,
                    other_id
                }).then(r => {
                    resolve(r)
                })
            })
        },
        async fetchBuyerSeller(params) {
            return new Promise((resolve) => {
                this.$store.dispatch('booking/fetchBuyerSeller', params).then(r => {
                    resolve(r)
                })
            })
        },
        async updateRole(value, options, role) {

            let type = ( role === 'consignee' ) ? 'SO' : 'PO'
            let main_id = (role === 'consignee') ? this.item.shipper : this.item.consignee
            //extract item
            let {
                item
            } = this
            
            this.fetchPurchaseOrderOptions('', type, main_id, value).then(newOptions => {
                let findEntity = _.find(newOptions, e => (e.id === value))

                //if found in the options list then prefill consignee details info
                if ( typeof findEntity!=='undefined' ) {
                    
                    //assign based on selected role
                    if ( role === 'consignee' ) {
                        item.consignee_details_info = findEntity.address
                    } else {
                        item.shipper_details_info = findEntity.address
                    }
                }

                this.$emit('update:item', item)
            }).catch(e => {
                console.log(e)
            })

            this.fetchBuyerSeller({
                id: value,
                role
            }).then( r => {
                if ( typeof r.details !=='undefined' ) {
                    if ( role === 'consignee' ) {
                        item.consignee_details_info = r.details.address
                    } else {
                        item.shipper_details_info = r.details.address
                    }
                }
                this.$emit('update:item', item)
            }).catch(e => {
                console.log('err', e)
            })

            this.$emit('update:field', value)   
        },
        updateValue(value) {
            this.$emit('update:field', value)
        
        }
    },
}
</script>