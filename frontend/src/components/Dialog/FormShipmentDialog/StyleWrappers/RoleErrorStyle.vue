<template>
    <div :style="customStyle" :id="id">
    <slot name="content"></slot>
    </div>
</template>

<style lang="scss">
@import "./scss/RoleErrorStyle.scss";
</style>

<script>
export default {
	name: 'RoleErrorStyle',
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
