<template>
    <div class="invite-confirm">
        <div
            class="BG#FFF login-wrapper"
            v-if="!inviteConfirmProcess && (getUser == null || getUserToken == '')"
        >
            <img src="@/assets/images/logo.svg" alt="" class="logo" />

            <v-form ref="form" v-model="valid" @submit.prevent="submit">
                <h2 class="headLogIn">Sign Up to Start Shipping</h2>
                <v-container>
                    <v-row>
                        <v-col cols="12" class="name-wrapper">
                            <v-col cols="6" md="6">
                                <small>FIRST NAME</small>
                                <v-text-field
                                    placeholder="e.g. John"
                                    filled
                                    v-model="form.firstName"
                                    :rules="[rules.required]"
                                    required
                                    hide-details="auto"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6" md="6">
                                <small>LAST NAME</small>
                                <v-text-field
                                    placeholder="e.g. Smith"
                                    filled
                                    v-model="form.lastName"
                                    :rules="[rules.required]"
                                    required
                                    hide-details="auto"
                                ></v-text-field>
                            </v-col>
                        </v-col>
                        <v-col cols="12" md="12">
                            <small>EMAIL ADDRESS</small>
                            <v-text-field
                                placeholder="e.g. abcdefghij@email.com"
                                filled
                                v-model="form.email"
                                :rules="emailRules"
                                required
                                hide-details="auto"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12">
                            <small>PASSWORD</small>
                            <v-text-field
                                placeholder="Type your password"
                                filled
                                v-model="form.password"
                                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                :rules="[rules.required, rules.min]"
                                :type="show1 ? 'text' : 'password'"
                                name="input-10-1"
                                hint=""
                                @click:append="show1 = !show1"
                                hide-details="auto"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12">
                            <v-btn
                                class="btn-blue submitFormBtn"
                                text
                                type="submit"
                                :disabled="this.form.loading"
                            >
                                {{
                                    !getUserSignUpLoading
                                        ? "Sign Up"
                                        : "Signing Up..."
                                }}
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-form>
            <div class="snippet signup mt-5">
                <div class="stage">
                    <span class="ask-account-text"
                        >Already have an account?
                        <a class="sign-in-text" href="/login">
                            Sign In
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <div>
            <ul class="loading-search-wrapper" v-if="inviteConfirmProcess">
                <div class="loading-search">
                    <v-progress-circular
                        :size="30"
                        color="#0171a1"
                        indeterminate
                    >
                    </v-progress-circular>
                </div>
            </ul>
        </div>
        <ConfirmDialog :dialogData.sync="dialogAccept">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/question-blue.svg" alt="alert" />
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2>Joining a New Company?</h2>
            </template>

            <template v-slot:dialog_content>
                <p>
                    You have received an Invitation from
                    <span class="font-weight-bold"
                        >`{{ form.companyName }}.`</span
                    >
                    at <span class="font-weight-bold">{{ form.email }}</span
                    >. Do you want to join this new company with your existing
                    account?
                </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn
                    class="btn-blue"
                    @click="joinCompany"
                    :disabled="inviteConfirmProcess"
                >
                    <span>{{
                        inviteConfirmProcess ? "Joining..." : "Join Now"
                    }}</span>
                </v-btn>

                <v-btn class="btn-white" text @click="ignoreInvitation">
                    Ignore Invitation
                </v-btn>
            </template>
        </ConfirmDialog>
        <ConfirmDialog :dialogData.sync="dialogLogout">
            <template v-slot:dialog_icon>
                <div class="header-wrapper-close">
                    <img src="@/assets/icons/alert.svg" alt="alert" />
                </div>
            </template>

            <template v-slot:dialog_title>
                <h2>Log Out to Accept Invitation</h2>
            </template>

            <template v-slot:dialog_content>
                <p>
                    You are already logged in with a different account. To
                    accept an invitation, you need to log out first.
                </p>
            </template>

            <template v-slot:dialog_actions>
                <v-btn class="btn-blue" @click="userLogout">
                    <span>{{
                        logoutProcess ? "Logging out..." : "Log out"
                    }}</span>
                </v-btn>

                <v-btn class="btn-white" text @click="stayLoggedIn">
                    Stay Logged In
                </v-btn>
            </template>
        </ConfirmDialog>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import ConfirmDialog from "../components/Dialog/GlobalDialog/ConfirmDialog.vue";
import globalMethods from "../utils/globalMethods";

export default {
    components: {
        ConfirmDialog,
    },
    data: () => ({
        valid: true,
        show1: false,
        inviteConfirmProcess: true,
        dialogAccept: false,
        dialogLogout: false,
        logoutProcess: false,
        form: {
            companyId: undefined,
            companyName: "",
            email: "",
            password: "",
            firstName: "",
            lastName: "",
            loading: false,
        },
        errors: [],
        rules: {
            required: (value) => !!value || "",
            min: (v) =>
                v.length >= 5 ||
                "Password length must be greater than 5 characters",
            emailMatch: () => `Please provide a valid email address`,
        },
        emailRules: [
            (v) => !!v || "",
            (v) =>
                !v ||
                /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                "E-mail must be valid",
        ],
        loginSuccess: false,
    }),
    computed: {
        ...mapGetters({
            getUserToken: "getUserToken",
            getUser: "getUser",
            getUserInviteConfirm: "customers/getUserInviteConfirm",
            getUserInviteConfirmLoading:
                "customers/getUserInviteConfirmLoading",
            getUserSignUp: "getUserSignUp",
            getUserSignUpLoading: "getUserSignUpLoading",
            getUserRegisterCompletedStatus: "getUserRegisterCompletedStatus",
        }),
    },
    methods: {
        ...globalMethods,
        ...mapActions({
            sendUserInviteConfirm: "customers/sendUserInviteConfirm",
            sendUserSignUp: "sendUserSignUpForInviteUser",
            fetchUser: 'fetchUser',
            logout: "logout",
            fetchUserRegisterCompletedStatus: "fetchUserRegisterCompletedStatus",
        }),
        sleep(ms) {
            return new Promise((resolve) => setTimeout(resolve, ms));
        },
        async submit() {
            this.$refs.form.validate();

            if (this.$refs.form.validate()) {
                //make sure to lock the sign up button to prevent multiple login clicks
                if (!this.form.loading) {
                    //set loading state
                    this.form.loading = true;
                    //attempt to sign up
                    try {
                        await this.sendUserSignUp({
                            email: this.form.email,
                            password: this.form.password,
                            re_password: this.form.password,
                            name: `${this.form.firstName} ${this.form.lastName}`,
                        });
                        if (this.getUserSignUp.success) {
                            this.notificationMessage("User has been created.");
                            await this.sendUserInviteConfirm({
                                invitedToEmail: this.form.email,
                                companyId: this.form.companyId,
                            });
                            this.$router.push('/login');
                        }
                    } catch (e) {
                        let message = "";
                        if (e.error.email && e.error.email[0]) {
                            message = e.error.email[0];
                        } else if (e.error.password && e.error.password[0]) {
                            message = e.error.password[0];
                        } else if (
                            e.error.re_password &&
                            e.error.re_password[0]
                        ) {
                            message = e.error.re_password[0];
                        } else {
                            message = e;
                        }
                        this.notificationError(message);
                    } finally {
                        this.form.loading = false;
                    }
                }
            }
        },
        ignoreInvitation() {
            this.dialogAccept = false;
            this.$router.push('/');
        },
        stayLoggedIn() {
            this.dialogLogout = false;
            this.$router.push('/');
        },
       async userLogout() {
            this.logoutProcess = true;
            var messageToPost = { logout: true };
            if (window.webkit != undefined) {
                window.webkit.messageHandlers.buttonClicked.postMessage(
                    messageToPost
                );
            }
            this.logoutProcess = false;
            this.dialogLogout = false;
            const fullPath = this.$route.fullPath;
            await this.logout();
            if(!this.getUserRegisterCompletedStatus?.isUserRegisterCompleted) {
                localStorage.setItem('redirectPath', fullPath);
                this.$router.push(fullPath);
            } else {
                localStorage.setItem('redirectPath', fullPath);
                this.$router.push('/login');
            }     
        },
        joinCompany() {
            this.inviteConfirmProcess = true;
            setTimeout(async () => {
                try {
                    await this.sendUserInviteConfirm({
                        invitedToEmail: this.form.email,
                        companyId: this.form.companyId,
                    });
                } catch (e) {
                    console.log(e);
                } finally {
                    if (this.getUserInviteConfirm.success) {
                        this.fetchUser();
                        // this.notificationError("Invitation has been confirmed. Please wait while we reload the page.");
                        this.notificationError("Invitation has been confirmed.");
                        this.$router.push('/login');
                    } else {
                        this.inviteConfirmProcess = false;
                    }
                }
            }, 1000);
        },
    },
    mounted() {},
    updated() {},
   async created() {
        this.form.email = this.$route.query.email;
        this.form.firstName = this.$route.query.firstName;
        this.form.lastName = this.$route.query.lastName;
        this.form.companyName = this.$route.query.companyName;
        this.form.companyId = this.$route.query.companyId ? Number(atob(this.$route.query.companyId)) : this.$route.query.companyId;
        let loggedUser = (typeof this.getUser =='string') ? JSON.parse(this.getUser) : this.getUser
        await this.fetchUserRegisterCompletedStatus({ email: this.form.email });

        if (this.getUserToken !== "" && loggedUser !== null) {
            this.inviteConfirmProcess = false;
            if (loggedUser.email !== this.form.email) {
                this.dialogLogout = true;
            } else {
                this.dialogAccept = true;
            }
            localStorage.setItem('redirectPath', this.$route.fullPath);
        } else {
            if (!(this.$route.query.email && this.$route.query.companyId)) {
                this.$router.push('/login');
            } else {
                if ((this.getUserToken == "" || this.getUser == null) && this.getUserRegisterCompletedStatus?.isUserRegisterCompleted) {
                    localStorage.setItem('redirectPath', this.$route.fullPath);
                    this.$router.push('/login');
                } else {
                    this.inviteConfirmProcess = false;
                }
            }
        }
    },
};
</script>
<style lang="scss">
@import "../assets/scss/pages_scss/inviteConfirm/InviteConfirm.scss";
</style>
