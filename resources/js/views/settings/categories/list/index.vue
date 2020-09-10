<template>
    <div>
        <services-filters />

        <services-table />

        <services-modals />
    </div>
</template>

<script>
    import Vue from 'vue';
    import ServicesFilters from './filters/index';
    import ServicesTable from './table/index';
    import ServicesModals from './modals/index';

    export default {
        name: 'Services',
        provide() {
            return {
                $_categories: new Vue(),
            };
        },
        components: {
            ServicesTable,
            ServicesModals,
            ServicesFilters,
        },
    };
</script>
