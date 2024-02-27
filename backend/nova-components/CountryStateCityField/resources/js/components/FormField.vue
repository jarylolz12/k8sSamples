<template>
  <div>
      <default-field
          :field="country.field"
          :errors="errors"
          :show-help-text="showHelpText"
      >
          <template slot="field">
              <v-select
                  :value="country.value"
                  :options="country.list"
                  :reduce="country => country"
                  label="country"
                  @input="onSelectCountries"
                  :loading="country.loading"
              ></v-select>
          </template>
      </default-field>

      <default-field
          :field="state.field"
          :errors="errors"
          :show-help-text="showHelpText"
      >
          <template slot="field">
              <v-select
                  :value="state.value"
                  :options="state.list"
                  :reduce="state => state"
                  label="state"
                  @input="onSelectStates"
                  :loading="state.loading"
              ></v-select>
          </template>
      </default-field>

      <default-field
          :field="city.field"
          :errors="errors"
          :show-help-text="showHelpText"
      >
          <template slot="field">
              <v-select
                  :value="city.value"
                  :options="city.list"
                  :reduce="city => city"
                  label="city"
                  @input="onSelectCities"
                  :loading="city.loading"
              >
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          v-bind="attributes"
                          v-on="events"
                          @change="onChangeCity"
                      />
                  </template>
              </v-select>
          </template>
      </default-field>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import "vue-select/dist/vue-select.css";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  data() {
      return {
          country: {
              loading: false,
              list: [],
              value: this.field.defaultCountry || "",
              field: { ...this.field, name: "Country", required: true }
          },
          state: {
              loading: false,
              list: [],
              value: this.field.defaultState || "",
              field: { ...this.field, name: "State", required: true }
          },
          city: {
              loading: false,
              list: [],
              value: this.field.defaultCity || "",
              field: { ...this.field, name: "City", required: true }
          }
      };
  },

  mounted() {
      this.getCountries();
  },

  methods: {
      fill(formData) {
          formData.append(
              "country_state_city",
              JSON.stringify({
                  country: this.country.value,
                  state: this.state.value,
                  city: this.city.value
              })
          );
      },

      getCountries: async function() {
          this.country.loading = true;
          const { status, data } = await Nova.request().get("/countries");
          if (status === 200) {
              this.country.list = data;
          }
          this.country.loading = false;
      },

      onSelectCountries: async function(value) {
          this.state.loading = true;
          this.state.list = [];
          this.city.list = [];
          this.state.value = "";
          this.city.value = "";
          const { status, data } = await Nova.request().get(
              `/countries/${value}/states`
          );
          if (status === 200) {
              this.state.list = data;
          }
          this.country.value = value;
          this.state.loading = false;
      },

      onSelectStates: async function(value) {
          this.city.loading = true;
          const { status, data } = await Nova.request().get(
              `/countries/${this.country.value}/states/${value}/cities`
          );
          if (status === 200) {
              this.city.list = data;
          }
          this.state.value = value;
          this.city.loading = false;
      },

      onSelectCities: function(value) {
          this.city.value = value;
      },

      onChangeCity: function(e) {
          e.preventDefault();
          this.city.value = e.target.value;
      }
  }
};
</script>
