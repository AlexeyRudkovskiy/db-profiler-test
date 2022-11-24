<template>
    <div class="flex-none w-96 bg-zinc-50">
        <div class="p-4 bg-sky-600 text-white font-extrabold text-2xl">
            Transactions
        </div>
        <div class="space-y-4 py-4">
            <transaction-stack v-for="category in categories" :category="category" @click="showTransaction(category)"></transaction-stack>
        </div>
    </div>
    <div class="flex-auto">
        <div class="bg-sky-500 h-16 flex items-center">
            <form method="post" class="p-0 m-0 px-4">
                <input type="text" name="search" class="px-3 py-2 border bg-sky-600 text-xs border-none outline-none text-white rounded" />
            </form>
        </div>

        <!-- Test flow -->
        <div>
            <div data-tooltip="Hello world" class="
                    relative
                    before:absolute
                    before:content-[attr(data-tooltip)]
                    before:px-3
                    before:py-2
                    before:inline-block
                    before:rounded-lg
                    before:bg-slate-800
                    before:text-white
                    before:text-xs
                    before:left-1/2
                    before:-bottom-1
                    before:-translate-x-1/2
                    before:translate-y-full
                    before:hidden

                    hover:before:block

                    px-4
                    text-xs
                    bg-yellow-400
                    h-2
                " style="width: 200px"></div>
            <div data-tooltip="Hello world" class="
                    relative
                    before:absolute
                    before:content-[attr(data-tooltip)]
                    before:px-3
                    before:py-2
                    before:inline-block
                    before:rounded-lg
                    before:bg-slate-800
                    before:text-white
                    before:text-xs
                    before:left-1/2
                    before:-bottom-1
                    before:-translate-x-1/2
                    before:translate-y-full
                    before:hidden
                    before:w-max

                    hover:before:block

                    px-4
                    text-xs
                    bg-pink-400
                    h-2
                " style="width: 150px; left: 200px;"></div>
            <div data-tooltip="Duration: 0.05s" class="
                    relative
                    before:absolute
                    before:content-[attr(data-tooltip)]
                    before:px-3
                    before:py-2
                    before:inline-block
                    before:rounded-lg
                    before:bg-slate-800
                    before:text-white
                    before:text-xs
                    before:left-1/2
                    before:-bottom-1
                    before:-translate-x-1/2
                    before:translate-y-full
                    before:hidden
                    before:w-max

                    hover:before:block

                    px-4
                    text-xs
                    bg-blue-400
                    h-2
                " style="width: 50px; left: 350px;"></div>
        </div>

        <div id="simple"></div>
        <!-- End test flow -->
    </div>
</template>

<script>
import TransactionStack from './../Components/TransactionStack.vue'

export default {
    name: "Transactions",
    components: {
        TransactionStack,
    },
    data() {
        return {
            categories: [
                { category: 'First Category', queries: 10, latest_at: '2022-01-20 15:35' },
                { category: 'Second Category', queries: 10, latest_at: '2022-01-20 15:35' },
                { category: 'Third Category', queries: 10, latest_at: '2022-01-20 15:35' },
            ]
        }
    },
    methods: {
        showTransaction(category) {
            console.log(category.category, category.queries);
        }
    }
}
</script>

<style scoped>

</style>
