<template>
    <div class="flex-none w-96 bg-zinc-50 overflow-y-auto">
        <div class="p-4 bg-sky-600 text-white font-extrabold text-2xl">
            Queries
        </div>
        <div v-for="(query, index) in queries"
             class="p-4 hover:bg-white cursor-pointer"
             v-bind:class="{ 'bg-white': isCurrentQuery(query) }"
             @click="showQuery(index)"
        >
            <div>{{ query.name }}</div>
            <div class="text-sm text-sky-500">{{ query.created_at }}</div>
        </div>
        <div v-if="queries.length < 1">
            <div class="text-lg text-black text-opacity-50 pt-16 text-center">
                Run first query to debug it
            </div>
        </div>
    </div>
    <div class="flex-auto overflow-y-auto show">
        <div class="bg-sky-500 h-16 flex items-center" v-if="query === null"></div>
        <div class="bg-sky-500 h-16 flex items-center" v-if="query !== null">
            <span class="text-2xl text-white ml-6">Examine Query</span>
        </div>

        <single-query :query="query" v-if="query !== null" @show-query="loadQuery($event)"></single-query>
    </div>
</template>

<script>
import axios from 'axios'
import SingleQuery from './queries/SingleQuery.vue'

export default {
    name: "Query",
    components: {
        SingleQuery
    },
    data() {
        return {
            queries: [],
            nextPage: null,
            query: null
        }
    },
    methods: {
        loadQueries() {
            axios.get('/@profiler/queries')
                .then(response => response.data)
                .then(response => {
                    this.queries = response.data;
                    this.nextPage = response.next_page;
                })
                .then(() => this.showQuery(0));
        },

        showQuery(index) {
            this.loadQueryById(this.queries[index].id);
        },
        loadQuery(id) {
            this.loadQueryById(id);
        },
        loadQueryById(id) {
            axios.get(`/@profiler/queries/${id}`)
                .then(response => response.data)
                .then(response => this.query = response);
        },
        isCurrentQuery(query) {
            return this.query !== null &&  query.id === this.query.id;
        }
    },
    mounted() {
        this.loadQueries();
    }
}
</script>

<style scoped>

</style>
