<template>
    <div :class="`pl-4 ${(isMobile) ? 'pr-4' : 'pr-6'} py-4 d-flex`" v-resize="onResize">
        <v-card
            class="no-customer-account-card"
        >
            <div class="d-flex">
                <div class="d-flex align-center justify-center">
                    <kenetic-icon :paddingTop="`${(isMobile) ? 0 : 2}`" :width="`${(isMobile) ? 60 : 40}`" :height="`${(isMobile) ? 60 : 40}`" iconName="warning" color="#D68A1A"/>
                </div>
                <div class="d-flex flex-column justify-center pl-3">
                    <v-card-title class="title">{{ "No associated company" }}</v-card-title>
                    <v-card-text class="main-text d-flex align-center">
                        <p :class="`d-flex align-center mb-0 text-left ${(isMobile) ? 'mobile' : ''}`">
                            {{ "It appears that your account is not associated with any company. Please contact your account manager or onboarding representative to get set up."
                            }}
                        </p>
                    </v-card-text>
                </div>
            </div>
        </v-card>
    </div>
</template>
<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
.no-customer-account-card {
    width: 100%;
    box-shadow: none !important;
    border: 1px solid #EBF2F5 !important;
    border-radius: 0px !important;
    .title {
        font-family: 'Inter', sans-serif !important;
        font-size: 14px !important;
        line-height: 14px !important;
        color: #4A4A4A;
        font-weight: 600;
        padding: 0px 0px 4px 0px;
    }

    & > div:first-child {
        padding: 16px;
    }

    .v-card__text {
        padding-left: 0px;
        padding-bottom: 0px;
        & > p {
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            font-size: 12px;
            line-height: 12px;
            /*color: #6d858f; */
            color: #4A4A4A;
            letter-spacing: 0px;

            &.mobile {
                line-height: 14px;
            }    
        }
        
    }
}

</style>
<script>
import KeneticIcon from '../components/Icons/KeneticIcon.vue'
export default {
    data: () => ({
        isMobile: false
    }),
    components: {
        KeneticIcon
    },
    methods: {
        onResize() {
            this.isMobile = (window.innerWidth > 821) ? false : true
        },    
    }
    
}
</script>