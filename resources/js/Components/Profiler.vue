<template>
    <div class="flex h-screen">
        <div class="flex-none w-16 bg-gray-800" v-show="false">
            <div class="h-16 flex items-center justify-center text-white text-2xl font-extrabold select-none">DB</div>

            <sidebar-menu-item title="Single Query" :is-active="currentPage === 'queries'" @click="page('queries')">
                <command-line-icon class="h-6 w-6 text-white"></command-line-icon>
            </sidebar-menu-item>
            <sidebar-menu-item title="Transactions" :is-active="currentPage === 'transactions'" @click="page('transactions')">
                <queue-list-icon class="h-6 w-6 text-white"></queue-list-icon>
            </sidebar-menu-item>
        </div>

        <transactions v-if="currentPage === 'transactions'"></transactions>
        <query v-if="currentPage === 'queries'"></query>
    </div>
</template>

<script>
import SidebarMenuItem from './Components/SidebarMenuItem.vue'
import Transactions from './pages/Transactions.vue'
import Query from './pages/Query.vue'
import { CommandLineIcon, QueueListIcon } from '@heroicons/vue/24/outline'

export default {
    name: "Profiler",
    components: {
        SidebarMenuItem,
        Transactions,
        Query,
        CommandLineIcon,
        QueueListIcon
    },
    data() {
        return {
            currentPage: 'queries'
        }
    },
    methods: {
        page(page) {
            this.currentPage = page;
        }
    }
}
</script>

<style scoped>

</style>
