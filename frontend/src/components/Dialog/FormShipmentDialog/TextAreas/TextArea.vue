<template>
    <div :style="`margin-top: ${marginTop} !important; margin-bottom: ${marginBottom} !important; ${isMobile ? 'width: 100%;' : ''}`" :id="id" :class="`input-general-wrapper text-area-wrapper-custom ${contentClass}`">
        <slot name="label" v-bind:label="label"></slot>
        <div>
            <slot v-bind:mainContent="{ updateValue: updateValue, textColor: textColor, placeholderColor: placeholderColor, placeholderText: placeholderText, inputFontWeight: inputFontWeight, inputFontSize: inputFontSize, item: item }" name="input"></slot>
        </div>
    </div>
</template>
<style lang="scss">
@import "./scss/textArea.scss";
</style>
<script>
export default {
    props: {
        isMobile: {
            default: false
        },
        item: {
            default: null
        },
        id: {
            default: 'text-area-wrapper-custom'
        },
        contentClass: {
            default: ''
        },
        marginTop: {
            default: "40px"
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
        placeholderColor: {
            default: '#B4CFE0'
        },   
    },
    methods: {
        updateValue(value) {
            this.$emit('update:field', value)
        }
    },
}
</script>