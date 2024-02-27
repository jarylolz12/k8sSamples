<template>
    <v-container>
        <div class="pa-3 mb-4">
            <h2>Integrations</h2>
            <p>Connect Shifl with your online services to streamline your work process. Get <br> started by pressing the ‘Integrate’ button under your relevant service.</p>
        </div>
        <v-layout row>
            <v-flex d-flex justify-center >
                <v-card width="325px">
                    <v-card-title primary class="title justify-center flex-column" >
                        <div class="delete-icon mt-3 mb-1">
                            <img src="../../../../assets/icons/amazon.svg" alt="">
                        </div>
                        <div>Amazon</div>
                    </v-card-title>                        
                    <v-card-text class="d-flex justify-center align-center flex-column">
                        <span>Connect to sync your inventory level,orders</span> 
                        <span>profit & performance data</span>
                        </v-card-text>
                    <v-card-actions class="justify-center">
                        <v-btn class="btn-blue" text>
                            <span>Integrate</span>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>

            <v-flex d-flex justify-center>
                <v-card width="325px">
                    <v-card-title primary class="title justify-center flex-column" >
                        <div class="delete-icon mt-3 mb-1">
                            <img src="../../../../assets/icons/quickbook.svg" alt="">
                        </div>
                        <div>Quickbooks</div>
                    </v-card-title>                        
                    <v-card-text class="d-flex justify-center align-center flex-column">
                        <span>Connect to sync your invoices & bills and</span> 
                        <span>manage your accounting entries</span></v-card-text>
                    <v-card-actions class="justify-center">
                        <v-btn class="btn-blue" disabled text>
                            <span>Coming Soon</span>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
 
            <v-flex d-flex justify-center>
                <v-card width="325px">
                    <v-card-title primary class="title justify-center flex-column"   >
                        <div class="delete-icon mt-3 mb-1">
                            <img src="../../../../assets/icons/digital-ocean.svg" alt="">
                        </div>
                        <div>Digital Ocean</div>
                    </v-card-title>                        
                    <v-card-text class="d-flex justify-center align-center flex-column">
                        <span>Connect to sync your marketing data and</span> 
                        <span>manage your business contacts</span>
                        </v-card-text>
                    <v-card-actions class="justify-center">
                        <v-btn class="btn-blue" disabled text>
                            <span>Coming Soon</span>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>

        </v-layout>
    </v-container>
</template>

<script>
import globalMethods from "../../../../utils/globalMethods";

export default {
    name: "IntegrationsDesktop",
	props: ["isMobile"],
    mounted() {
		//set current page
		this.$store.dispatch("page/setPage", "settings/integrations");
	},
    methods:{
        ...globalMethods,
    }
}
</script>

<style lang="scss">
    @import "../../../../assets/scss/pages_scss/settings/integrations.scss";
    @import "../../../../assets/scss/pages_scss/dialog/globalDialog.scss";
    @import "../../../../assets/scss/buttons.scss";
</style>