<template>
    <div :class="`input-text-mobile-main-wrapper ${disabled ? 'input-disabled' : ''} ${contentClass}`">
        <v-text-field
            :height="40"
            :color="textColor"
            width="100%"
            type="text"
            :value="field"
            dense
            @keyup="updateValue"
            :placeholder="placeholderText"
            outlined
            :disabled="disabled"
            hide-details="auto">
        </v-text-field>
    </div>
</template>
<style lang="scss">
@import "./scss/inputTextMobile.scss";
</style>
<script>
export default {
    props: {
        contentClass: {
            default: ''
        },
        fieldName: {
            default: ''
        },
        static: {
            default: false
        },
        disabled: {
            default: false
        },
        placeholderText: {
            default: ''
        },
        field: {
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
        }
    },
    methods: {
        updateValue(event) {
            if ( !this.static ) {
                let value = event.target.value
                this.$emit('update:field', value)
            }
            
        }
    },
}
</script>