<!-- @format -->

<template>
  <v-dialog
    v-model="dialogEdit"
    max-width="560px"
    content-class="edit-profile-dialog"
    v-resize="onResize"
    :retain-focus="false"
    persistent
    scrollable
  >
    <v-card class="edit-profile-card">      
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
          <button icon dark class="btn-close" @click="close">
            <v-icon>mdi-close</v-icon>
          </button>
        </v-card-title>

        <v-card-text>
          <v-form ref="form" v-model="valid" action="#" @submit.prevent="">
            <v-row>
              <v-col cols="12" sm="12" md="12" class="pb-0">
                <label class="text-item-label">Company Name</label>
                <v-text-field
                  outlined
                  class="text-fields"
                  v-model="editedItem.company_name"
                  :rules="rules"
                  hide-details="auto"
                >
                </v-text-field>
              </v-col>

              <v-col cols="12" sm="12" md="12" class="pb-0">
                <label class="text-item-label">Phone Number</label>
                <vue-tel-input
                  v-model="editedItem.phone"
                  mode="international"
                  defaultCountry="us"
                  validCharactersOnly
                  :autoDefaultCountry="true"
                  :dropdownOptions="vueTelDropdownOptions"
                  :inputOptions="vueTelInputOptions"
                  :styleClasses="
                    isPhoneNumberEmpty && editedItem.phone === ''
                      ? 'is-error'
                      : ''
                  "
                >
                  <template v-slot:arrow-icon>
                    <v-icon class="ml-1">mdi-chevron-down</v-icon>
                  </template>
                </vue-tel-input>

                <span
                  class="error-message"
                  style="color: red; font-size: 12px;"
                  v-if="isPhoneNumberEmpty"
                >
                  {{ editedItem.phone === "" ? "Phone number is required." : "" }}
                </span>
              </v-col>

              <v-col cols="12" sm="12" md="12" class="pb-0">
                <label class="text-item-label">Email Address</label>
                <div class="tags-email-wrapper mb-1">
                  <vue-tags-input
                    hide-details="auto"
                    :rules="arrayNotEmptyRules"
                    allow-edit-tags
                    :tags="options"
                    :add-on-blur="true"
                    :add-on-key="[13, ',']"
                    :validation="tagsValidation"
                    v-model="emailAddress"
                    placeholder="e.g. name@email.com"
                    @tags-changed="
                      (newTags) => {
                        this.options = newTags;
                        this.tagsInput.touched = true;
                        this.tagsInput.hasError =
                          this.options.length > 0 ? false : true;
                        let el = this.documentProto.getElementsByClassName(
                          'ti-input'
                        )[0];
                        if (typeof el !== 'udnefined') {
                          if (this.tagsInput.hasError)
                            el.classList.add('ti-new-tag-input-error');
                          else el.classList.remove('ti-new-tag-input-error');
                        }
                      }
                    "
                  />
                </div>

                <div v-if="tagsInput.touched" class="v-text-field__details">
                  <div class="v-messages theme--light error--text" role="alert">
                    <div class="v-messages__wrapper">
                      <div
                        v-if="options.length > 0 && emailAddress !== ''"
                        class="v-messages__message"
                      >
                        {{ tagsInput.errorMessage }}
                      </div>

                      <div
                        v-if="options.length == 0 && emailAddress !== ''"
                        class="v-messages__message"
                      >
                        {{ tagsInput.errorMessage }}
                      </div>
                      <div
                        v-if="options.length == 0 && emailAddress == ''"
                        class="v-messages__message"
                      >
                        Please provide at least 1 valid email address.
                      </div>
                    </div>
                  </div>
                </div>

                <span style="color: #819FB2; font-size: 12px;">
                  Press the "Enter" or "," key in your keyboard to confirm the
                  email address
                </span>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <label class="text-item-label">Address</label>
                <v-textarea
                  height="76px"
                  class="text-fields"
                  outlined
                  name="input-7-4"
                  v-model="editedItem.address"
                  :rules="rules"
                  hide-details="auto"
                >
                </v-textarea>
              </v-col>
            </v-row>
            <div class="row">
              <div class="col-sm-12 mb-1">
                <div class="row">
                  <div class="col-sm-6 py-1">
                    <p class="edit-profile-title">COUNTRY</p>
                    <v-autocomplete
                      class="text-fields select-items"
                      v-model="editedItem.country"
                      :items="countries"
                      :disabled="getCountriesLoading"
                      :placeholder="
                        getCountriesLoading
                          ? 'Fetching countries...'
                          : 'Type country name'
                      "
                      @input="setSelectedCountry"
                      outlined
                      :rules="rules"
                      hide-details="auto"
                      autocomplete="off"
                    >
                    </v-autocomplete>
                  </div>

                  <div class="col-sm-6 py-1">
                    <p class="edit-profile-title">STATE</p>
                    <v-combobox
                      class="text-fields select-items"
                      outlined
                      :items="states"
                      item-text="name"
                      item-value="id"
                      :placeholder="
                        getStatesLoading ? 'Fetching states...' : 'Select state'
                      "
                      :disabled="getStatesLoading"
                      v-model="editedItem.state"
                      :rules="rules"
                      hide-details="auto"
                      autocomplete="off"
                      @change="setSelectedState"
                      @click="isStateClicked()"
                    >
                    </v-combobox>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="row mb-1">
                  <div class="col-sm-6 py-1">
                    <p class="edit-profile-title">CITY</p>
                    <v-combobox
                      class="text-fields select-items"
                      v-model="editedItem.city"
                      :items="cities"
                      item-text="name"
                      item-value="id"
                      :disabled="getCitiesLoading"
                      :placeholder="
                        getCitiesLoading ? 'Fetching cities...' : 'Select city'
                      "
                      @click="isCitiesClicked()"
                      outlined
                      :rules="rules"
                      hide-details="auto"
                    >
                      <template v-slot:no-data>
                        <div tabindex="-1" class="v-list-item theme--light">
                          <div class="v-list-item__content">
                            <div class="v-list-item__title">
                              No data available
                            </div>
                          </div>
                        </div>
                      </template>
                    </v-combobox>
                  </div>

                  <div class="col-sm-6 py-1">
                    <p class="edit-profile-title">ZIPCODE</p>
                    <v-text-field
                      color="#002F44"
                      width="200px"
                      dense
                      class="text-fields"
                      placeholder="Type zipcode"
                      outlined
                      type="number"
                      v-model="editedItem.zipcode"
                      :rules="rules"
                      hide-details="auto"
                      autocomplete="off"
                    >
                    </v-text-field>
                  </div>
                </div>
              </div>
            </div>
          </v-form>
        </v-card-text>

        <v-card-actions class="justify-start">
          <v-btn
            @click="updateCompanyProfile"
            class="text-capitalize btn-primary btn-blue"
            :disabled="getUpdateCustomerProfileInfoLoading"
          >
            <span>
              {{
                getUpdateCustomerProfileInfoLoading
                  ? "Saving..."
                  : "Update Profile"
              }}
            </span>
          </v-btn>
          <button class="btn-white" @click="close" :disabled="getUpdateCustomerProfileInfoLoading">Cancel</button>
      </v-card-actions>      
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { VueTelInput } from "vue-tel-input";
import "vue-tel-input/dist/vue-tel-input.css";
import VueTagsInput from "@johmun/vue-tags-input";
import jQuery from "jquery";
import globalMethods from "../../../utils/globalMethods";
import _ from "lodash";

export default {
  name: "EditCompanyInfoDialog",
  props: ["editDialog", "editedItemData"],
  components: {
    VueTelInput,
    VueTagsInput,
  },
  data: () => ({
    valid: true,
    options: [],
    value: [],
    isMobile: false,
    rules: [(v) => !!v || "Input is required."],
    vueTelDropdownOptions: {
      showDialCodeInSelection: true,
      showDialCodeInList: true,
      showFlags: true,
      showSearchBox: true,
    },
    vueTelInputOptions: {
      autocomplete: false,
      placeholder: "Type phone number",
      styleClasses: "tel-input-class",
      required: true,
    },
    countryCode: null,
    numberRules: [
      (v) => !!v || "Input is required.",
      (v) => /^(?=.*[0-9])[- +()0-9]+$/.test(v) || "Letters are not allowed.",
    ],
    tagsValidation: [
      {
        classes: "t-new-tag-input-text-error",
        rule: /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/,
        disableAdd: true,
      },
    ],
    documentProto: document,
    tagsInput: {
      touched: false,
      hasError: false,
      errorMessage:
        'Please confirm the entered email address by pressing the "Enter" or "," key in your keyboard.',
    },
    emailAddress: "",
    arrayNotEmptyRules: [
      (v) => !!v || "Email is required",
      () =>
        this.optionsFiltered.length > 0 ||
        "Make sure to supply at least 1 email.",
    ],
    isPhoneNumberEmpty: false,
    disabledSaveButton: false,
  }),
  computed: {
    ...mapGetters({
      getCountries: "warehouse/getCountries",
      getCountriesLoading: "warehouse/getCountriesLoading",
      getStates: "warehouse/getStates",
      getStatesLoading: "warehouse/getStatesLoading",
      getCities: "warehouse/getCities",
      getCitiesLoading: "warehouse/getCitiesLoading",
      getUpdateCustomerProfileInfoLoading:
        "customers/getUpdateCustomerProfileInfoLoading",
    }),
    countries() {
      return typeof this.getCountries !== "undefined" &&
        this.getCountries !== null &&
        this.getCountries.length !== 0
        ? this.getCountries
        : [];
    },
    states() {
      return typeof this.getStates !== "undefined" &&
        this.getStates !== null &&
        this.getStates.length !== 0
        ? this.getStates
        : [];
    },
    cities() {
      return typeof this.getCities !== "undefined" &&
        this.getCities !== null &&
        this.getCities.length !== 0
        ? this.getCities
        : [];
    },

    formTitle() {
      return "Edit Company Information";
    },
    dialogEdit: {
      get() {
        return this.editDialog;
      },
      set(value) {
        this.$emit("update:editDialog", value);
      },
    },
    editedItem: {
      get() {
		return this.editedItemData;
      },
      set(value) {
        this.$emit("update:editedItemData", value);
      },
    },
    optionsFiltered: {
      get() {
        let validEmailAddress = [];

        if (
          this.editedItem.emails !== null &&
          this.editedItem.emails.length > 0
        ) {
          this.editedItem.emails.map((wea) => {
            if (!_.isEmpty(wea) && wea?.email) {
              validEmailAddress.push({ text: wea, tiClasses: ["ti-valid"] });
            }
          });
        }
        return validEmailAddress;
      },
      set(value) {
        this.options = value;
      },
    },
  },
  mounted() {
    this.setSelectedCountry(this.editedItem.country);
    this.setSelectedState(this.editedItem.state);
  },
  methods: {
    ...mapActions({
      fetchCountries: "warehouse/fetchCountries",
      fetchStates: "warehouse/fetchStates",
      fetchCities: "warehouse/fetchCities",
      updateCustomerInfo: "customers/updateCustomerInfo",
    }),
    ...globalMethods,
    async setSelectedCountry(value) {
      if (value !== "" && value !== null) {
        try {
          await this.fetchStates(value);
        } catch (e) {
          console.log(e);
        }
      }
    },
    async setSelectedState(value) {
      if (value !== "" && value !== null) {
        let payload = {
          country: this.editedItem.country,
          states: value,
        };

        try {
          await this.fetchCities(payload);
        } catch (e) {
          console.log(e);
        }
      }
    },
    countrySelected(val) {
      this.countryCode = val.dialCode;
      console.log(val);
    },
    isStateClicked() {
      if (!this.editedItem.country || this.editedItem.country == "") {
        this.notificationErrorCustom("Please select a country first.");
      }
    },
    isCitiesClicked() {
      if (!this.editedItem.country || this.editedItem.country == "") {
        this.notificationErrorCustom("Please select a country first.");
      } else {
        if (!this.editedItem.state || this.editedItem.state == "") {
          this.notificationErrorCustom("Please select a state first.");
        }
      }
    },
    generateErrorMessage() {
      this.tagsInput.hasError = this.options.length > 0 ? false : true;
      if (this.tagsInput.hasError)
        jQuery(".ti-input").addClass("ti-new-tag-input-error");
      else jQuery(".ti-input").removeClass("ti-new-tag-input-error");

      this.isPhoneNumberEmpty = this.editedItem.phone === "" ? true : false;
    },
    onResize() {
      if (window.innerWidth < 769) {
        this.isMobile = true;
      } else {
        this.isMobile = false;
      }
    },
    close() {
      this.$refs.form.resetValidation();
      this.$emit("close", false);
      this.isPhoneNumberEmpty = false;
      this.disabledSaveButton = false;
    },
    editCustomerProfileInfoTemplate() {
      let {
        id,
        company_name,
        emails,
        phone,
        address,
        country,
        city,
        state,
        zipcode,
      } = this.editedItem;

      return {
        id,
        company_name,
        emails,
        phone,
        address,
        country,
        state,
        city,
        zipcode,
      };
    },
    async updateCompanyProfile() {
      if (!this.tagsInput.touched) this.tagsInput.touched = true;

      this.$refs.form.validate();
      this.tagsInput.hasError = this.options.length > 0 ? false : true;

      this.generateErrorMessage();

      setTimeout(async () => {
        if (this.$refs.form.validate()) {
          if (!this.tagsInput.hasError && !this.isPhoneNumberEmpty) {
            try {
              jQuery(".ti-new-tag-input").trigger(
                jQuery.Event("keyup", { keyCode: 13, which: 13 })
              );
              let finalEmailAddress =
                this.options.length > 0
                  ? this.options.map((o) => {
                      return { email: o.text };
                    })
                  : [];

              let editCompanyInfo = this.editCustomerProfileInfoTemplate();
              editCompanyInfo.id = this.editedItem.id;
              editCompanyInfo.company_name = this.editedItem.company_name;
              editCompanyInfo.phone = this.editedItem.phone;
              editCompanyInfo.emails = finalEmailAddress;
              editCompanyInfo.address = this.editedItem.address;
              editCompanyInfo.country = this.editedItem.country;
              editCompanyInfo.state = this.editedItem.state;
              editCompanyInfo.city = this.editedItem.city;
              editCompanyInfo.zipcode = this.editedItem.zipcode;

              await this.updateCustomerInfo(editCompanyInfo);
              this.notificationMessage("Company Information has been updated.");
              setTimeout(() => {
				this.$store.dispatch('checkCurrentAccount');
              }, 1000);

              this.close();
            } catch (e) {
              this.notificationError(e);
              console.log(e);
            }
          }
        }
      }, 200);
    },
    setToDefault() {
      this.$emit("setToDefault");
      this.tagsInput.touched = false;
      this.options = [];
    },
  },
  watch: {
    tagsInput(value) {
      if (value.hasError) {
        jQuery(".ti-input").addClass("ti-new-tag-input-error");
      } else {
        jQuery(".ti-input").removeClass("ti-new-tag-input-error");
      }
    },
    dialogEdit(value, oldValue) {
      this.tagsInput.hasError = false;
      this.tagsInput.touched = false;

      jQuery(".ti-input").removeClass("ti-new-tag-input-error");
      if (typeof el !== "undefined") {
        let el = document.getElementsByClassName("ti-input")[0];
        el.classList.remove("ti-new-tag-input-error");
      }

      if (!value) {
        this.options = [];
        this.emailAddress = "";
      } else if (value && !oldValue) {
        let validEmailAddress = [];
        if (
          this.editedItem.emails !== null &&
          this.editedItem.emails.length > 0
        ) {
          this.editedItem.emails.map((wea) => {
            if (!_.isEmpty(wea) && wea?.email) {
              validEmailAddress.push({
                text: wea.email,
                tiClasses: ["ti-valid"],
              });
            }
          });
        }
        this.options = validEmailAddress;
      }
    },
  },
  updated() {
    if (typeof this.$refs.form !== "undefined") {
      if (typeof this.$refs.form.resetValidation() !== "undefined") {
        this.$refs.form.resetValidation();
      }
    }
  },
};
</script>

<style lang="scss">
@import "../../../assets/scss/pages_scss/dialog/globalDialog.scss";
@import "../../../assets/scss/pages_scss/settings/editCompanyInfoDialog.scss";

.v-autocomplete__content.v-menu__content {
  border-radius: 4px !important;
}
</style>
