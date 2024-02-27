<template>
    <span>
        <a v-on:click.stop.prevent="visitLink" v-if="office.name!=''" :href="link" class="no-underline dim text-primary font-bold">
            {{
                name
            }}
        </a>
    </span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
    data() {
    	return {
    		office: this.field.office || ''
    	}
    },
    methods: {
        visitLink() {
            this.$router.push(this.link)
        }
    },
    computed: {
        name() {
            return (typeof this.office.name!=='undefined' && this.office.name!==null && this.office.name!=='') ? this.office.name : ''
        },
        link() {
            return (typeof this.office.id!=='undefined' && this.office.id!==null && this.office.id!=='') ? `/resources/shifl-offices/${this.office.id}` : ''
        }
    },
    mounted() {
    }
}
</script>
