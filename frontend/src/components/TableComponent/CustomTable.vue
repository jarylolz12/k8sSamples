<template>
    <div>
        <v-data-table
            v-bind="$attrs"
            v-on="$listeners"
            :search="search"
            hide-default-footer
            fixed-header
            show-select>

            <!-- pass through scoped slots -->
            <template
                v-for="(_, scopedSlotName) in $scopedSlots"
                v-slot:[scopedSlotName]="slotData">

                <slot :name="scopedSlotName" v-bind="slotData" />
            </template>

            <!-- pass through normal slots -->
            <template v-for="(_, slotName) in $slots" v-slot:[slotName]>
                <slot :name="slotName" />
            </template>
        </v-data-table>
    </div>
</template>

<script>
export default {
    name: "CustomTable",
    props: ["title"],
    data: () => ({
        search: ''
    }),
    methods: {},
};
</script>

<style lang="scss">

</style>