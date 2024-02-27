<template>
   <v-dialog
      v-model="dialog2"
      max-width="960px"
  >
      <v-card>
      <v-card-title>
          Add Storable Unit
      </v-card-title>
      <v-card-text>
          <div>
              <!-- <Search 
                  placeholder="Search Locations"
                  className="search custom-search inventory-tab-search"
                  :inputData.sync="search" /> -->
              <v-btn dark color="primary" class="btn-white btn-filters mr-2">
                  <img src="@/assets/icons/filters.svg" class="mr-1"> Filters
              </v-btn>
          </div>
          <v-data-table
              :headers="headersStorable"
              :items="itemStorable"
              class="elevation-1 add-products-outbound-table"
              mobile-breakpoint="769"
              hide-default-footer
              fixed-header
              :expanded.sync="expanded"
              single-expand
              show-expand
              :item-class="itemRowBackground">

              <template v-slot:[`item.label`]="{ item }">
                  <div class="name-wrapper" v-if="!isMobile">
                      <p>{{ item.label !== '' ? item.label : '--'}}</p>
                  </div>
              </template>

              <template v-slot:expanded-item="{ headers, item }">
                  <td :colspan="headers.length">
                      <div class="expanded-item-info">
                          <div class="expanded-header-wrapper">
                              <div class="expanded-header-content">
                                  <div class="header-title w-80">SKU</div>
                                  <div class="header-title w-10">CARTON</div>
                                  <div class="header-title w-10">UNIT</div>
                              </div>
                          </div>

                          <div class="expanded-body-wrapper">
                              <div class="expanded-body-content" v-for="(v, index) in item.products" :key="index">
                                  <div class="header-data w-80">
                                      {{ v.sku }}
                                  </div>
                                  <div class="header-data w-10">
                                      {{ v.carton }}
                                  </div>
                                  <div class="header-data w-10">
                                      {{ v.units }}
                                  </div>
                              </div>
                          </div>
                      </div>
                  </td>
              </template>

              <template v-slot:[`item.type`]="{ item }">
                  <div class="name-wrapper" v-if="!isMobile">
                      <p>{{ item.type !== '' ? item.type : '--'}}</p>
                  </div>
              </template>

              <template v-slot:[`item.dimension`]="{ item }">
                  <div class="name-wrapper" v-if="!isMobile">
                      <p>{{ item.dimension !== '' ? item.dimension : '--'}}</p>
                  </div>
              </template>

              <template v-slot:[`item.weight`]="{ item }">
                  <div class="name-wrapper" v-if="!isMobile">
                      <p>{{ item.weight !== '' ? item.weight : '--'}}</p>
                  </div>
              </template>

              <template v-slot:[`item.carton`]="{ item }">
                  <div class="name-wrapper" v-if="!isMobile">
                      <p>{{ item.carton !== '' ? item.carton : '--'}}</p>
                  </div>
              </template>

              <template v-slot:[`item.no_sku`]="{ item }">
                  <div class="name-wrapper" v-if="!isMobile">
                      <p>{{ item.no_sku !== '' ? item.no_sku : '--'}}</p>
                  </div>
              </template>
          </v-data-table>
      </v-card-text>
      <v-card-actions>
          <v-btn
          text
          @click="dialog2 = false"
          >
          Add Storable Unit
          </v-btn>
          <v-btn
          text
          @click="dialog2 = false"
          >
          Cancel
          </v-btn>
      </v-card-actions>
      </v-card>
  </v-dialog>
</template>

<script>

export default {
    name: 'StorableOutboundDialog'
    }
</script>

<style lang="scss">
</style>
