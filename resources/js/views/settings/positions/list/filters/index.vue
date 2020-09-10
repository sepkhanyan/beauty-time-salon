<template>
  <div class="filter-container">
    <el-input v-model="listQuery.title" :placeholder="$t('table.title')" style="width: 200px;" class="filter-item"
              @keyup.enter.native="filter"/>
    <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="filter">
      {{ $t('table.search') }}
    </el-button>
    <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit"
               @click="create">
      {{ $t('table.add') }}
    </el-button>
  </div>
</template>

<script>
  import waves from '@/directive/waves'; // Waves directive

  export default {
    name: 'Filters',
    inject: ['$positionsList'],
    directives: {waves},
    data() {
      return {
        listQuery: {
          page: 1,
          limit: 5,
          title: null,
          sort: '+id',
        },
      };
    },
    methods: {
      filter() {
        this.$positionsList.$emit('search-position', this.listQuery.title);
      },
      create() {
        this.$positionsList.$emit('create-position', this.listQuery.title);
      },
      showDialog() {
        this.$positionsList.$emit('show-dialog');
      },

    },
  };
</script>

<style scoped>

</style>
