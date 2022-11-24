<template>
<div>
    <batch-item v-for="(row, index) in batch" :item="row" :query="query" @show-query="showQuery(row)"></batch-item>
</div>
</template>

<script>
import BatchItem from './BatchItem.vue'

export default {
    name: "Batch",
    components: {
        BatchItem
    },
    props: {
        'batch': {
            type: Array,
            default: []
        },
        'query': {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            resultLoaded: false,
            result: []
        };
    },
    methods: {
        showQuery(row) {
            if (row.id === this.query.id) {
                return ;
            }

            this.$emit('show-query', row);
        },
        executeQuery(row) {
            console.log(row);
        }
    }
}
</script>

<style scoped>

</style>
