<template>
<div>
    <div class="overflow-x-auto max-w-full shadow-lg" v-if="typeof query.explain.table !== 'undefined' && query.explain.table !== null">
        <table class="w-full text-sm text-left text-gray-500 rounded">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b sticky top-0">
            <tr>
                <th scope="col" class="py-3 px-3 border-b" v-for="column in explainTableHeader">
                    {{ column }}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr class="bg-white border-b last:border-b-0" v-for="row in query.explain.table">
                <td  class="py-4 px-3" v-for="(value, index) in row">
                    {{ value }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <pre class="text-sm mt-4">{{ JSON.stringify(query.explain.json, null, 2) }}</pre>

</div>
</template>

<script>
export default {
    name: "ExplainQuery",
    props: [ 'query' ],
    computed: {
        explainTableHeader() {
            if (typeof this.query.explain.table !== "undefined" && this.query.explain.table !== null) {
                return Object.keys(this.query.explain.table[0]);
            }

            return [];
        }
    }
}
</script>

<style scoped>

</style>
