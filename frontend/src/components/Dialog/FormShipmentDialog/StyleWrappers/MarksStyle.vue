<template>
    <div :style="customStyle" :id="id">
    <slot name="content"></slot>
    </div>
</template>

<style lang="scss">
@import "./scss/MarksStyle.scss";
</style>

<script>
export default {
	name: 'MarksStyle',
    props: {
        id: {
            default: ''
        },
        customStyle: {
            default: ''
        }
    }
}
</script>
