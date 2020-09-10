<template>
  <div class="app-container">
    <positions-filters/>
    <!-- Note that row-key is necessary to get a correct row order. -->
    <positions-table/>

    <positions-modals/>
    <!-- $t is vue-i18n global function to translate lang (lang in @/lang)  -->
  </div>
</template>

<script>
  import Vue from 'vue';
  import PositionsTable from './table/index';
  import PositionsFilters from './filters/index';
  import PositionsModals from './modals/index';

  export default {
    name: 'Positions',
    provide() {
      return {
        $positionsList: new Vue(),
      };
    },
    components: {
      PositionsTable,
      PositionsFilters,
      PositionsModals,
    },
  };
</script>
<style>
  .sortable-ghost {
    opacity: .8;
    color: #fff !important;
    background: #42b983 !important;
  }
</style>

<style scoped>
  .drag-handler {
    width: 20px;
    height: 20px;
    cursor: pointer;
  }

  .show-d {
    margin-top: 25px;
    margin-top: 25px;
  }
</style>
