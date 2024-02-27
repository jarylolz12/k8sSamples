<template>
    <div>
        <div class="flex items-center mb-3">
            <div class="flex-auto">
                <heading>Container Buy Rates</heading>
            </div>
            <div class="flex-auto"> 
                <!-- <a href="#" class="float-right btn bg-danger flex text-white w-40 p-3 rounded justify-center" @click.prevent="generate()" v-if="index_rates">Generate</a> -->
                <a href="#" class="float-right btn bg-success flex text-white w-40 p-3 rounded justify-center mr-2" @click.prevent="$refs['ManualImportModal'].$data.open=true" v-if="index_rates">Manual Import</a>
                <a href="#" class="float-right btn bg-primary flex text-white w-40 p-3 rounded justify-center mr-2" @click.prevent="index_rates=(index_rates?false:true)">{{ index_rates ? 'Containers' : 'Index Rates' }}</a>
            </div>
        </div>
        <Container v-if="!index_rates"/>
        <IndexRates v-else/>
        <ManualImportModal ref="ManualImportModal" />
    </div>
</template>

<script>
import Container from './Container.vue';
import IndexRates from './IndexRates.vue';
import ManualImportModal from './ManualImportModal.vue';

export default {
    metaInfo() {
        return {
          title: 'Container Buy Rates',
        }
    },
    components : { Container, IndexRates, ManualImportModal },
    data(){
        return{
            index_rates: false
        }
    },
    methods: {
        generate(){
            let q = confirm('Do you want to empty the old data?');

            let url = '/nova-vendor/container-buy-rates/index-rates/generate';

            if( q ){
                url = '/nova-vendor/container-buy-rates/index-rates/generate?empty=1';
            }

            Nova.request().get(url).then(res => {
                if( res.data.success ){
                    Nova.$emit('index_rates_reload',1);
                }

                _this.$toasted.show(res.data.message, { type: ( res.data.success ? 'success' : 'error' ) });

            }).catch(err=>{
                _this.$toasted.show('Unable to process request!', { type: 'error' });
            }); 
        }
    }
}
</script>