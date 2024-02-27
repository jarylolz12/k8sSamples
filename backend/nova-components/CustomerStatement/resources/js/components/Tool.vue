<template>
    <div>
        <div class="flex items-center mb-3">
            <div class="flex-auto">
                <heading>Customer Statement</heading>
            </div>
            <div class="flex-end"> 
                <button style="height:2rem;" @click.prevent="$refs.AutomationSettingsModal.$data.open = true;" class="btn btn-default btn-primary" dusk="create-button">
                    Automation Settings
                </button>
            </div>
        </div>
        <Container />
        <AutomationSettingsModal ref="AutomationSettingsModal"/>
    </div>
</template>

<script>
import AutomationSettingsModal from './AutomationSettingsModal.vue';
import Container from './Container.vue';

export default {
    metaInfo() {
        return {
          title: 'Customer Statement',
        }
    },
    components : { 
        Container,
        AutomationSettingsModal
    }
}
</script>