<template>
    <div>
        <v-dialog v-model="dialogAdd" max-width="560px" content-class="add-user-dialog" :retain-focus="false" @click:outside="close">
        <v-card>
            <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
                <v-card-title>
                    <span class="headline">Add User</span>

                    <button icon dark class="btn-close" @click="close">
                        <v-icon>mdi-close</v-icon>
                    </button>
                </v-card-title>

                <v-card-text>
                    <div class="add-user-wrapper">
                        <div class="add-user-info">
                            <v-row no-gutters>
                                <v-col cols="12">
                                    <div class="card-name mb-3">
                                        <p class="card-title">Name</p>
                                        <v-text-field
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            dense
                                            class="text-fields select-items"
                                            placeholder="Enter name"
                                            outlined
                                            hide-details="auto">
                                        </v-text-field>
                                    </div>
                                </v-col>
                                <v-col cols="12">
                                    <div class="card-name mb-3">
                                        <p class="card-title">Email Address</p>
                                        <v-text-field
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            dense
                                            class="text-fields select-items"
                                            placeholder="Enter email address"
                                            outlined
                                            :rules="rules"
                                            hide-details="auto">
                                        </v-text-field>
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <div class="card-name mb-3">
                                        <p class="card-title">User group</p>
                                        <v-text-field
                                            height="40px"
                                            color="#002F44"
                                            width="200px"
                                            dense
                                            class="text-fields select-items"
                                            placeholder="Currect User Group"
                                            outlined
                                            background-color="#EBF2F5"
                                            disabled
                                            hide-details="auto">
                                        </v-text-field>
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                    </div>
                </v-card-text>

                <v-card-actions>
                    <button class="btn-blue"> 
                        <span>Add User</span>
                    </button>

                    <button class="btn-white" @click="close">
                        Cancel
                    </button>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
    </div>
</template>

<script>
    export default {
        props:['dialog'],
        data(){
            return {
                valid:false,
                rules: [
                    (v) => !!v || 'Input is required.'
                ],
            }
        },
        computed:{
            dialogAdd: {
                get() {
                    return this.dialog
                },
                set(value) {
                    this.$emit('update:dialog', value)
                }
            },
        },
        methods:{
            close() {
            this.$refs.form.resetValidation()
            this.$emit('close')
        },
        }
    }
</script>

<style lang="scss" scoped>
@import '../../../assets/scss/pages_scss/settings/userDialog.scss';
</style>