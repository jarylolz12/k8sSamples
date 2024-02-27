<template>
    <div class="flex border-b border-40 -mx-6 px-6">
        <div class="w-1/4 py-6">
          <h4 class="font-normal text-80">{{ field.name }}</h4>
        </div>
        <div class="w-3/4 py-4 break-words flex flex-col">
          <div class="flex flex-row py-2">
            <label class="w-1/4 content-center flex flex-wrap">
              Billing Notes
            </label>
            <div class="w-1/2">
                <div class="flex flex-row" v-if="field.billing_notes==''">
                    <font-awesome-icon color="red" icon="fa-solid fa-info-circle" />
                    <div class="pl-2 italic">
                        {{
                            "No billing note added yet."
                        }}
                    </div>
                </div>
                <div class="flex italic" v-else:>
                    {{
                        field.billing_notes
                    }}
                </div>
            </div>
          </div>
        </div>
      </div>
</template>
<script>
import {
  mapActions,
  mapGetters
} from 'vuex'

import { faInfoCircle } from '@fortawesome/free-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    methods: {
        ...mapActions({
            fetchCustomerItem: 'customer/fetchItem'
        })
    },
    components: {
        FontAwesomeIcon,
    },
    created() {
        library.add(faInfoCircle)
    },
    data:() => ({
        loaded: false
    }),
    computed: {
        ...mapGetters({
            getItem: 'customer/getItem'
        })
    }
}
</script>