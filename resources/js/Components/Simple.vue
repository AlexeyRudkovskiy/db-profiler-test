<template>
<div class="group inline-flex px-4 py-2 bg-gray-600 rounded-lg text-white select-none hover:bg-gray-500" (click)="increment">
    <span class="flex-none">Hello world</span>
    <span class="flex-none p-1 px-2 text-xs bg-gray-500 rounded-full ml-4 group-hover:bg-gray-400">{{ counter }}</span>
</div>
</template>

<script>
export default {
    name: "Simple",
    data() {
        return {
            counter: 10
        }
    },
    methods: {
        increment() {
            this.counter++;
        }
    }
}
</script>

<style scoped>

</style>
