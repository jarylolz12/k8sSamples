<template>
    <default-field :field="field" :errors="errors" id="hide-account-manager-select-id" >
        <template slot="field">
            <div class="parent-selection">
                <div class="select-div">
                    <v-select v-model="custom_managers" :reduce="label => label.value" label="label" @search="search" :options="options"  @input="setSelected">
                    </v-select>
                </div>
                <div class="association-div">
                    <p>
                        {{
                            matched_office
                        }}
                    </p>
                </div>
                <div class="association-div-second">
                    <a v-if="link!=='' && custom_managers!==''" v-on:click.stop.prevent="visitLink" :href="link">
                        Assign to Shifl Office
                    </a>
                </div>
            </div>
        </template>
    </default-field>
</template>
<style>
    .parent-selection {
        display: flex;
        flex: 1;
        flex-direction: row;
    }
    .select-div {
        display: flex;
        flex: 4;
    }

    .select-div > div:first-child {
        width: 100% !important;
    }
    .association-div-second {
        display: flex;
        flex: 3;
        padding-left: 12px;
        line-height: 32px;
    }
    .association-div {
        display: flex;
        flex: 3;
        padding-left: 12px;
        line-height: 32px;
    }
</style>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import JQuery from 'jquery'
window.$ = JQuery

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],
    data() {
        return {
            token: null,
            placeholder: '----Select Here----',
            link: '',
            base_url: this.field.baseUrl || '',
            options: [],
            custom_managers: parseInt(this.field.custom_managers) || '',
            is_progress: false,
            matched_office: '',
            first_load: false

        };
    },
    mounted(){
        this.loadSelectedManager()
        this.link = `/administrator/resources/account-managers/${this.custom_managers}/edit?viaResource&viaResourceId&viaRelationship`
        var id = this.field.value;
        var resourceId = this.resourceId;

        //first load all account managers and put them in a variable container
        /*
        $.ajax({
            method: 'GET',
            url: '/custom/account-managers/index',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: (isNaN(resourceId)) ? null : resourceId
            }

        }).done( response => {

            let { results,related_account_manager_id } = response;

            if(results.length>0) {

                let accountManagers = results;
                let accountManagersOriginal = accountManagers;

                var createOption = '';
                var hasAccountManager = false;
                for(var x=0;x<results.length;x++) {

                    if (related_account_manager_id!=null) {

                        if(related_account_manager_id==results[x].id) {
                            hasAccountManager = true;
                            createOption +=`<option selected="selected" value="${results[x].id}">${results[x].text}</option>`;
                        } else {
                            createOption +=`<option value="${results[x].id}">${results[x].text}</option>`;
                        }

                    } else {
                            createOption +=`<option value="${results[x].id}">${results[x].text}</option>`;
                    }

                }

                $('#managers').html((!hasAccountManager) ? `<option value="" selected="selected">-----Select Here----</option>${createOption}` : `<option value="">-----Select Here----</option>${createOption}`);
            }
        });*/
    },
    methods: {
        visitLink() {
            window.open(this.link)
            //this.$router.push(this.link)
        },
        handleResponse(response) {
            return response.text().then(text => {
                const data = text && JSON.parse(text)
                return data
            })
        },
        setSelected(value) {
          this.matched_office = 'Loading associated office...'
          this.link = `/administrator/resources/account-managers/${value}/edit?viaResource&viaResourceId&viaRelationship`
          fetch(`${this.base_url}/custom/shifl-office/find-by-manager?id=${value}`)
          .then(this.handleResponse)
          .then( response => {
            let { result, status } = response
            if (status=='ok') {
                this.matched_office = result.name
            } else {
                this.matched_office = 'No associated office.'
            }
          }).catch(e => {
            console.log(e)
          })
        },
        search(search, loading) {
          //if (this.signal==null)
          //  this.signal = this.controller.signal
          loading(true)
          fetch(`${this.base_url}/custom/account-managers/search?query=${search}`)
          .then(this.handleResponse)
          .then( response => {
            loading(false)
            let { results, status } = response
            if (status=='ok') {
                this.options = results
            }
          }).catch(e => {
            loading(false)
            console.log(e)
          })
        },
        loadSelectedManager() {

          if (this.custom_managers!=='') {
            fetch(`${this.base_url}/custom/account-managers/find-by-id?id=${parseInt(this.custom_managers)}`).
            then(this.handleResponse)
              .then( response => {
                var { result, status } = response
                if (status=='ok') {
                  //e
                  this.options = [{
                    label: result.name,
                    value: result.id
                  }]
                  //this.options = result
                }
                this.first_load = true
            })
            .catch(e => {
                this.first_load = true
            })

            //s
            fetch(`${this.base_url}/custom/shifl-office/find-by-manager?id=${this.custom_managers}`)
            .then(this.handleResponse)
            .then( response => {
                var { result, status } = response
                if (status=='ok') {
                    this.matched_office = result.name
                } else {
                    this.matched_office = 'No associated office.'
                }
            }).catch(e => {
                console.log(e)
            })
          }
          

        },
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            
            //let account_manager_val = $('#managers').val();
            
            //if(account_manager_val.length>0) {
               // formData.append('account_manager_ids',account_manager_val);
              //  formData.append('managers',account_manager_val);
            //}
            formData.append('managers', this.custom_managers)
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            //console.log('handle change ' + value)
            //this.value = value;
        },
    },
}
</script>

<style>
.hide-account-manager-select {
    display: none;
}

</style>
