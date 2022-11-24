<template>
    <div class="border" v-bind:class="batchItemClasses">
        <div class="p-3 flex items-center" v-bind:class="batchHeaderClasses">
            <eye-icon class="flex-none w-4 h-4 mr-3" v-if="item.id === query.id"></eye-icon>
            <span class="mr-2 text-sm">{{ item.name }}</span>
            <div class="ml-auto flex">
                <div class="group p-1.5 border rounded-tl rounded-bl cursor-pointer" @click="showQuery()" v-bind:class="batchShowItemClasses">
                    <arrow-top-right-on-square-icon class="w-4 h-4" v-bind:class="showQueryClasses"></arrow-top-right-on-square-icon>
                </div>
                <div class="group p-1.5 border border-l-0 cursor-pointer" @click="hideResults()" v-if="resultLoaded && showResults">
                    <arrows-pointing-in-icon class="w-4 h-4 group-hover:text-red-500"></arrows-pointing-in-icon>
                </div>
                <div class="group p-1.5 border border-l-0 cursor-pointer" @click="showQueryResults()" v-if="resultLoaded && !showResults">
                    <arrows-pointing-out-icon class="w-4 h-4 group-hover:text-red-500"></arrows-pointing-out-icon>
                </div>
                <div class="group p-1.5 border border-l-0 rounded-tr rounded-br cursor-pointer" v-bind:class="batchRunClasses" @click="executeQuery()">
                    <play-icon class="w-4 h-4 group-hover:text-green-500"></play-icon>
                </div>
            </div>
        </div>
        <div v-if="resultLoaded && showResults">
            <div class="p-5 m-5 text-center bg-amber-50 shadow-md rounded" v-if="results.length < 1">
                Query finished without any resuts
            </div>
            <div class="relative max-h-96 overflow-y-auto overflow-x-auto" v-if="results.length > 0">
                <table class="w-full text-sm text-left text-gray-500 table-auto">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b sticky top-0">
                    <tr>
                        <th scope="col" class="py-3 px-3 border-b" v-for="column in resultHeader">
                            {{ column }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-white border-b last:border-b-0" v-for="(row) in results">
                        <template v-for="(value, index) in row">
                            <td  class="py-4 px-3">
                                {{ value }}
                            </td>
                        </template>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script>
import { ArrowTopRightOnSquareIcon, PlayIcon, ArrowsPointingInIcon, ArrowsPointingOutIcon, EyeIcon } from '@heroicons/vue/24/outline'
import axios from 'axios'

export default {
    name: "BatchItem",
    props: [ 'item', 'query' ],
    components: {
        ArrowTopRightOnSquareIcon,
        PlayIcon,
        ArrowsPointingInIcon,
        ArrowsPointingOutIcon,
        EyeIcon
    },
    data() {
        return {
            resultLoaded: false,
            results: [],
            resultHeader: [],
            showResults: true
        }
    },
    methods: {
        showQuery() {
            this.$emit('show-query');
        },
        executeQuery() {
            axios.post(`/@profiler/queries/${this.item.id}`)
                .then(response => response.data)
                .then(response => this.results = response)
                .then(() => this.formatHeader())
                .finally(() => this.resultLoaded = true);
        },
        formatHeader() {
            this.resultHeader = Object.keys(this.results[0]);
        },
        hideResults() {
            this.showResults = false;
        },
        showQueryResults() {
            this.showResults = true;
        }
    },
    computed: {
        batchItemClasses() {
            return {
                'rounded': true,
                'mt-2': true,
                'mb-2': true,
                'first:mt-0': true,
                'last:mb-0': true,

                // 'mt-2': this.resultLoaded && this.showResults,
                // 'mb-2': this.resultLoaded && this.showResults,
                // 'first:mt-0': this.resultLoaded && this.showResults,
                // 'last:mb-0': this.resultLoaded && this.showResults,
                //
                // 'border-t': true,
                // 'border-b': this.resultLoaded && this.showResults,
                // 'last:border-b': true,
                //
                // 'first:rounded-tr': true,
                // 'first:rounded-tl': true,
                // 'last:rounded-br': true,
                // 'last:rounded-bl': true,
                //
                // 'rounded': this.resultLoaded && this.showResults
            }
        },
        batchHeaderClasses() {
            return {
                'bg-gray-50': this.resultLoaded && this.showResults
            };
        },
        batchShowItemClasses() {
            return {
                'cursor-not-allowed': this.item.id === this.query.id,
                'hover:bg-transparent': this.item.id === this.query.id,
                'hover:bg-gray-100': this.resultLoaded && this.showResults,
                'hover:bg-gray-50': !this.resultLoaded && this.showResults
            };
        },
        batchRunClasses() {
            return {
                'hover:bg-gray-100': this.resultLoaded && this.showResults,
                'hover:bg-gray-50': !this.resultLoaded && this.showResults
            }
        },
        showQueryClasses() {
            return {
                'group-hover:text-sky-600': this.item.id !== this.query.id
            }
        }
    }
}
</script>

<style scoped>

</style>
