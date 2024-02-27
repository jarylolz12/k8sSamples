<template>
    <div class="tabBlock w-full">
        <div class="tabBlock-content">
            <StatementTable :type="item"/>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    $primary: #03a9f4;
    $primary-faint: mix($primary, #fff, 50%);
    $border: #d8d8d8;
    $var90: #3c4b5f;
    $var40: #eef1f4;

    .tabBlock {
        margin: 0;

        &-tabs {
            list-style: none;
            margin: 0;
            padding: 0;
            z-index: 999;
            border-bottom: 1px solid $var40;

            &::after {
                clear: both;
                content: "";
                display: table;
            }
        }

        &-tab {
            background-color: #fff;
            border-color: #ffffff;
            border-left-style: solid;
            border-width: 2px;
            color: $var90;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: 600;
            float: left;
            padding: 0.625rem 1.25rem;
            position: relative;
            transition: 0.1s ease-in-out;
            text-decoration: none;
            margin-right: 3px;

            &:last-of-type {
                border-right-style: solid;
            }

            &::after,
            &::before {
                content: "";
                display: block;
                height: 4px;
                position: absolute;
                transition: 0.1s ease-in-out;
            }

            &::before {
                background-color: #ffffff;
                left: -2px;
                right: -2px;
                top: -2px;
            }

            &::after {
                background-color: transparent;
                bottom: -2px;
                left: -2px;
                right: -2px;
            }

            &:focus,
            &:hover {
                color: $primary;
            }

            @media screen and (min-width: 700px) {
                padding-left: 2.5rem;
                padding-right: 2.5rem;
            }

            &.is-active {
                position: relative;
                color: $var90;
                z-index: 1;
                font-weight: 700;

                &::before {
                    background-color: #ffffff;
                }

                &::after {
                    background-color: #fff;
                    border-bottom: 1px solid $primary;
                }
            }

            svg {
                height: 1.2rem;
                width: 1.2rem;
                margin-right: 0.5rem;
                pointer-events: none;
                fill: currentcolor;
            }
        }

        &-content {
            background-color: #fff;
            padding: 1.25rem;

            a {
                color: dodgerblue;
                font-weight: 700;
                transition: color 200ms ease;

                &:focus,
                &:hover {
                    color: orangered;
                }
            }

            hr {
                margin: 1.5rem 0;
                border: 1px solid transparent;
                border-radius: 50%;
                border-top-color: $border;
            }
        }
    }
</style>

<script>
    import axios from 'axios';
    import StatementTable from './StatementTable.vue';

    export default {
        components: { StatementTable }
    }
</script>