<template>
    <div class="h-16 w-16 flex items-center justify-center hover:bg-sky-700 cursor-pointer" v-bind:class="{ 'bg-sky-700': isActive }" :title="title">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "SidebarMenuItem",
    props: [ 'isActive', 'title' ],
}
</script>

<style scoped>

</style>
