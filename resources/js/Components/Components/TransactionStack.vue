<template>
<div class="px-4 stack">
    <div class="p-4 cursor-pointer bg-white rounded-lg border z-50 relative">
        <div class="flex items-center">
            <span class="flex-auto block font-medium text-lg">{{ category.category }}</span>
            <div class="flex-none">
                <span class="py-1 px-2 rounded-full text-xs text-white bg-sky-600">{{ category.queries }}</span>
            </div>
        </div>
        <span class="block text-sm text-sky-500">{{ category.latest_at }}</span>
    </div>
</div>
</template>

<script>
export default {
    name: "TransactionStack",
    props: [ 'category' ]
}
</script>

<style scoped>

</style>
