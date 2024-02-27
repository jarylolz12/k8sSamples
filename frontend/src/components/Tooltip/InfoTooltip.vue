<template>
	<v-tooltip bottom :open-on-focus="false" :open-on-click="false" :open-on-hover="true" 
        :content-class="`top-arrow ${customClass}`">
        <template v-slot:activator="{ on, attrs }">
            <v-btn class="btn-black" v-bind="attrs" v-on="on">
                <slot name="tooltip_icon"></slot>
            </v-btn>
        </template>
        <span>
            <slot name="tooltip_info"></slot>
        </span>
    </v-tooltip>
</template>

<style lang="scss" scoped>
.v-tooltip__content {
	background-color: #4a4a4a !important;
	line-height: 25px;

	&.top-arrow {
		overflow: inherit !important;
		z-index: 20;
		opacity: 1 !important;

		&::before {
			content: " ";
            position: absolute;
            top: 0;
            left: 40%;
            margin-top: -8px;
            border-width: 12px;
            border-style: solid;
            border-top: #4a4a4a !important;
            border-bottom-color: #4a4a4a !important;
            border-right: solid 10px transparent;
            border-left: solid 10px transparent;
		}

        span {
            color: #fff;
            font-size: 12px;
            font-family: 'Inter-Regular', sans-serif;
        }

        &.hide-tooltip {
            display: none;
        }
	}
}
</style>

<script>
export default {
    name: 'InfoTooltip',
    props: ["customClass"],
    data: () => ({

    }),
    computed: {},
    methods: {},
    mounted() {},
    updated() {}
}
</script>