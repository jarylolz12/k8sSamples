<template>
    <ConfirmDialog :dialogData.sync="confirmDialog">
        <template v-slot:dialog_icon>
            <div class="header-wrapper-close">
                <img src="@/assets/icons/info-blue.svg" alt="alert" />
            </div>
        </template>

        <template v-slot:dialog_title>
            <h2>
                {{
                    isInvitedUserExists.isUserExists
                        ? "Confirm New User"
                        : "Invite New User"
                }}
            </h2>
        </template>

        <template v-slot:dialog_content>
            <p v-if="isInvitedUserExists.isUserExists">
                It seems <b>{{ email }}</b> already has a Shifl account. Are you
                sure to add this person as an user of your company?
            </p>

            <p v-if="!isInvitedUserExists.isUserExists">
                It seems <b>{{ email }}</b> doesn't have a Shifl account. Do you
                want to invite this person as an user of your company?
            </p>
        </template>

        <template v-slot:dialog_actions>
            <v-btn
                class="btn-blue"
                @click="sent"
                text
                :disabled="getSendUserInvitationForCompanyLoading"
            >
                <span v-if="!getSendUserInvitationForCompanyLoading">
                    {{
                        isInvitedUserExists.isUserExists
                            ? "Confirm User"
                            : "Sent Invitation"
                    }}
                </span>
                <span v-if="getSendUserInvitationForCompanyLoading">
                    {{
                        isInvitedUserExists.isUserExists
                            ? "User Adding..."
                            : "Invitation Sending..."
                    }}
                </span>
            </v-btn>
            <v-btn
                class="btn-white"
                text
                @click="close"
                :disabled="getSendUserInvitationForCompanyLoading"
            >
                Cancel
            </v-btn>
        </template>
    </ConfirmDialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import ConfirmDialog from "../../../components/Dialog/GlobalDialog/ConfirmDialog.vue";
import globalMethods from "../../../utils/globalMethods";

export default {
    name: "AddUserConfirmDialog",
    props: [
        "confirmDialog",
        "email",
        "isInvitedUserExists",
        "invitedById",
        "companyId",
        "group_id",
        "isWarehouseEmployee",
        "warehouseIds",
        "name"
    ],
    components: { ConfirmDialog },
    mounted() {
        //set current page
        this.$store.dispatch("page/setPage", "settings/users");
    },
    data: () => ({
        isMobile: false,
    }),
    watch: {},
    computed: {
        ...mapGetters({
            getSendUserInvitationForCompanyLoading:
                "customers/getSendUserInvitationForCompanyLoading",
            getUserInvitationForCompany:
                "customers/getUserInvitationForCompany",
        }),
    },
    methods: {
        ...mapActions({
            sendUserInvitationForCompany:
                "customers/sendUserInvitationForCompany",
            fetchUsersDetail: "fetchUsersDetail",
        }),
        ...globalMethods,
        onResize() {
            if (window.innerWidth < 769) {
                this.isMobile = true;
            } else {
                this.isMobile = false;
            }
        },
        close() {
            this.$emit("closeConfirmDialog");
        },
        async sent() {
            let bodyFormData = new FormData()
            let send_payload = {
                invitedToEmail: this.email,
                companyId: this.companyId,
                invitedById: this.invitedById,
                group_id:this.group_id,
                isWarehouseEmployee:0,
                name:this.name
            }
            if(this.isWarehouseEmployee){
                send_payload.isWarehouseEmployee = 1

            }
            let getPayloadKeys = Object.keys(send_payload)
					
			if (getPayloadKeys.length > 0) {
				getPayloadKeys.filter(gpk => {
					bodyFormData.append(gpk, send_payload[gpk])
				})
			}
            if (this.warehouseIds.length > 0 && this.isWarehouseEmployee == true) {
                let wareIds = this.warehouseIds.map(val => val.id)
				for (var i = 0; i < wareIds.length; i++) {
					bodyFormData.append(`warehouseIds[${[]}]`, wareIds[i]);
				}
			}
            
            setTimeout(async () => {
                try {
                    await this.sendUserInvitationForCompany(bodyFormData);
                } catch (e) {
                    this.notificationError(e);
                    console.log(e);
                } finally {
                    if (this.getUserInvitationForCompany.success) {
                        this.notificationMessage(
                            this.getUserInvitationForCompany.message
                        );
                        setTimeout(() => {
                            const params = {
                                customer_id: this.companyId,
                            };
                            this.fetchUsersDetail(params);
                        }, 1000);
                        this.$emit("closeConfirmDialog");
                    } else {
                        this.notificationError(
                            this.getUserInvitationForCompany.message
                        );
                    }
                }
            }, 200);
        },
    },
};
</script>

<style lang="scss">
@import "../../../assets/scss/buttons.scss";
</style>
