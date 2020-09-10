<template>
  <div class="filter-container">
    <el-input v-model="listQuery.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item"
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
  import waves from '@/directive/waves/waves';
  export default {
    name: 'EmployeesFilter',
    inject: ['$employeesList'],
    directives: {waves},
    data() {
      return {
        listQuery: {
          page: 1,
          limit: 5,
          keyword: null,
          sort: '+id',
        },
      };
    },
    methods: {
      filter() {
        this.$employeesList.$emit('search-employees', this.listQuery.keyword);
      },
      create() {
        this.$employeesList.$emit('create-employees');
      },
    },
  };
</script>

<style scoped>

</style>
