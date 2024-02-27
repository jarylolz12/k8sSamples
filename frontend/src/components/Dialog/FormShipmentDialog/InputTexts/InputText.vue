<template>
    <div class="input-general-wrapper input-text-main-wrapper">
        <div v-if="!noLabel" :class="`form-label ${contentClassLabel}`">
            <span :style="`color: ${labelColor};`">{{ label }}</span>
        </div>
        <div>
            <slot v-bind:mainContent="{ updateValue: updateValue, textColor: textColor, placeholderColor: placeholderColor, placeholderText: placeholderText,inputFontWeight: inputFontWeight, inputFontSize: inputFontSize, field: field }" name="input"></slot>
        </div>
    </div>
</template>
<style scoped lang="scss">
@import "./scss/inputText.scss";
</style>
<script>
export default {
    props: {
        contentClassLabel: {
            default: ''
        },
        label: {
            default: ''
        },
        noLabel: {
            default: false
        },
        field: {
            default: ''
        },
        placeholderText: {
            default: ''
        },
        inputFontWeight: {
            default: 600
        },
        inputFontSize: {
            default: 10
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