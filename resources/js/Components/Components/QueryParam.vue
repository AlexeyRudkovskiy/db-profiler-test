<template>
<span class="px-1.5 py-0.5 inline-block text-xs rounded shadow text-shadow" v-bind:class="queryParamClasses">{{ queryParamValue }}</span>
</template>

<script>
export default {
    name: "QueryParam",
    props: [ 'param' ],
    computed: {
        queryParamClasses() {
            return {
                // String
                'bg-amber-100': this.param.type === 'string',
                'text-amber-900': this.param.type === 'string',

                // Numeric
                'bg-sky-100': this.param.type === 'numeric',
                'text-sky-900': this.param.type === 'string',

                // Boolean
                'bg-red-100': this.param.type === 'boolean',
                'text-red-900': this.param.type === 'string',
            };
        },
        queryParamValue() {
            if (this.param.type === 'string') {
                return `'${this.param.value}'`;
            } else if (this.param.type === 'boolean') {
                return this.param.value === 1 ? 'true' : 'false';
            }

            return this.param.value;
        }
    }
}
</script>

<style scoped>

</style>
