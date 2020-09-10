<template>
  <div class="app-container">
    <employees-filter/>
    <employees-modals/>
    <drag-table/>
  </div>
</template>

<script>
  import Vue from 'vue';
  import EmployeesFilter from '@/views/settings/employees/list/filter';
  import EmployeesModals from '@/views/settings/employees/list/modals';
  import DragTable from '@/views/settings/employees/list/table';

  export default {
    name: 'Employees',
    provide() {
      return {
        $employeesList: new Vue(),
      };
    },
    components: {DragTable, EmployeesModals, EmployeesFilter},
  };
</script>
