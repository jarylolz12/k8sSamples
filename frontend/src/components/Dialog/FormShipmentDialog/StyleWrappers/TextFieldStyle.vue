<template>
    <div :id="id">
    <slot name="content"></slot>
    </div>
</template>

<style lang="scss">
@import "./scss/TextFieldStyle.scss";
</style>

<script>
export default {
	name: 'TextFieldStyle',
    props: {
        id: {
            default: ''
        },
    }
}
</script>
