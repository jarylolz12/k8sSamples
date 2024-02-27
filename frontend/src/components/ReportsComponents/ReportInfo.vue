<template>
    <div class="reports shipment-report">
        <div class="subcontent-header">
            <p class="subcontent-title font-semi-bold" :class="!toggleData ? 'off' : ''">{{ reportTitle }}</p>
            <div class="d-flex align-center">
                <v-switch
                    v-model="toggle"
                    inset
                    :ripple="false"
                    hide-details="auto"
                    @change="updateStatusReport"
                    :disabled="getUpdateReportStatusLoading">
                </v-switch>

                <!-- <toggle-button 
                    v-model="toggle"
                    :labels="{checked: 'On', unchecked: 'Off'}" 
                    :width="68"
                    :height="30"
                    :margin="4"
                    :font-size="14"
                    :switch-color="{checked: '#fff', unchecked: '#819FB2'}"
                    :css-colors="true" 
                    @change="updateStatusReport" 
                    :disabled="getUpdateReportStatusLoading" /> -->

                <button class="btn-white font-medium" @click="sendReport" v-if="!isMobile" 
                    :disabled="currentItem.isSending">
                    {{ currentItem.isSending ? 'Sending...' : 'Send Now'}}
                </button>

                <v-menu bottom offset-y left content-class="outbound-lists-menu">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-2 btn-white" icon v-bind="attrs" v-on="on">
                            <v-icon size="20">mdi-dots-horizontal</v-icon>
                        </v-btn>
                    </template>

                    <v-list class="outbound-lists">
                        <v-list-item @click="editReport">
                            <v-list-item-title> 
                                <img src="@/assets/icons/edit-black.svg" class="mr-2" height="16px" width="16px">
                                Edit 
                            </v-list-item-title>
                        </v-list-item>

                        <v-list-item @click="downloadReport">
                            <v-list-item-title> 
                                <img src="@/assets/icons/download-black.svg" class="mr-2" height="16px" width="16px">
                                Download 
                            </v-list-item-title>
                        </v-list-item>

                        <v-list-item @click="sendReport" v-if="isMobile">
                            <v-list-item-title> 
                                <img src="@/assets/icons/reports-message.svg" class="mr-2" height="16px" width="16px">
                                Send Now 
                            </v-list-item-title>
                        </v-list-item>

                        <v-list-item @click="deleteReport">
                            <v-list-item-title class="cancel"> 
                                <img src="@/assets/icons/delete-po.svg" class="mr-2" height="16px" width="16px">
                                Delete 
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>
        </div>

        <div class="subcontent-body" v-if="toggleData">
            <div class="subcontent content-time mt-2">
                <img src="../../assets/icons/reports-clock.svg" height="21px" width="21px">
                <div class="content-chip font-semi-bold">
                    {{ frequency }} <span class="px-1" v-if="isMobile">-</span>
                </div>
                <p v-if="frequency === 'Daily'">{{ `Everyday at ${time}` }}</p>
                <p v-if="frequency === 'Weekly'">{{ `Every ${daysOfTheWeek} at ${time}` }}</p>
                <p v-if="frequency === 'Monthly'">{{ `Every ${daysInMonth} of the month at ${time}` }}</p>
                <p v-if="frequency === 'Yearly'">{{ `Every year on ${month} ${daysInMonth} at ${time}` }}</p>
            </div>

            <div class="subcontent content-settings">
                <img class="user-icon" src="@/assets/icons/reports-settings.svg" height="20px" width="20px">
                <p>{{ type }}</p>
            </div>

            <div class="subcontent" v-if="isCompanyReport">
                <img class="user-icon" src="@/assets/icons/reports-user.svg" height="20px" width="20px">
                <div class="content-emails">
                    <p class="user-emails" v-for="(email, i) in emails" :key="i"> 
                        {{ email }} 
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex"
// import { ToggleButton } from 'vue-js-toggle-button'

export default {
	name: "ReportsInfo",
    props: [
        'reportTitle',
        'toggleData',
        'frequency',
        'daysOfTheWeek',
        'daysInMonth',
        'month',
        'time',
        'type',
        'emails',
        'isCompanyReport',
        'isMobile',
        'currentItem'
    ],
	components: {
        // ToggleButton
	},
	data: () => ({ }),
	created() { },
	async mounted() { },
	computed: {
		...mapGetters({
            getSendReportScheduleLoading: "getSendReportScheduleLoading",
            getUpdateReportStatusLoading: 'getUpdateReportStatusLoading'
        }),
        toggle: {
            get() {
                let value = this.toggleData === 1 ? true : false
                return value
            },
            set(value) {
                let new_value = value === false ? 0 : 1
                this.$emit('update:toggleData', new_value)
            }
        }
	},
	methods: {
		...mapActions({ }),
        editReport() {
            this.$emit('editReport')
        },
        downloadReport() {
            this.$emit('downloadReport')
        },
        deleteReport() {
            this.$emit('deleteReport')
        },
        sendReport() {
            this.$emit('sendReport')
        },
        updateStatusReport() {
            this.$emit('updateStatusReport')
        }
	},
}
</script>

<style lang="scss">
</style>
