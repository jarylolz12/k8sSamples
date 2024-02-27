<template>
  <div class="flex border-b border-40" resource-id="resourceId">
    <div class="w-1/5 px-8 py-6 ">
      <label   class="inline-block text-80 pt-2 leading-tight">
        Telex
      </label>
    </div>
    <div class="py-6 px-8 w-4/5">
      <div class="flex items-center pt-2">
        <div class="w-3/6">
          <label class="inline-block text-80  leading-tight">
            Release Freight without Telex Released
          </label>
        </div>
        <div class="w-3/6">
          <input type="checkbox" class="checkbox mx-2" @click="tick('release_freight_without_telex_release')" :checked="telex_release.release_freight_without_telex_release==1" />
        </div>
      </div>

      <div class="flex items-center pt-2">
        <div class="w-3/6">
          <label class="inline-block text-80  leading-tight">
            Release Customs without Telex Released
          </label>
        </div>
        <div class="w-3/6">
          <input type="checkbox" class="checkbox mx-2" @click="tick('release_customs_without_telex_release')" :checked="telex_release.release_customs_without_telex_release==1" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],
  data() {
      return {
          telex_release: (this.field.value=='' || this.field.value==null) ?
              {
                  "release_freight_without_telex_release":0,
                  "release_customs_without_telex_release":0,
              } : JSON.parse(this.field.value)
      }
  },
  props: ['resourceName', 'resourceId', 'field'],

  methods: {

    tick(getKey) {
        console.log(getKey);
        this.telex_release[getKey] = (this.telex_release[getKey]==1) ? 0 : 1
    },
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
        this.value = (this.field.value=='' || this.field.value==null) ?
            {
                "release_freight_without_telex_release":0,
                "release_customs_without_telex_release":0,
            } : JSON.parse(this.field.value)
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
        formData.append(this.field.attribute, JSON.stringify(this.telex_release) || JSON.stringify({
            "release_freight_without_telex_release":0,
            "release_customs_without_telex_release":0,
        }))
    },
  },
}
</script>
