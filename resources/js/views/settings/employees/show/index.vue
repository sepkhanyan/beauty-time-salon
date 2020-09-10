<template>
  <div class="app-container">
    <el-tabs v-model="activeName">
      <el-tab-pane label="Information" name="in">
        <information/>
      </el-tab-pane>
      <el-tab-pane label="Services" name="ser">
        Services
      </el-tab-pane>
      <el-tab-pane label="Schedule" name="sc">
        Schedule
      </el-tab-pane>
      <el-tab-pane label="Images" name="im">
        <employee-images/>
      </el-tab-pane>
      <el-tab-pane label="Settings" name="se">
        Settings
      </el-tab-pane>
      <el-tab-pane label="Salary" name="sa">
        Salary
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
  // import EmployeesResource from '@/api/employees';
  import Information from '@/views/settings/employees/show/tabs/information';
  import Vue from 'vue';
  import EmployeeImages from '@/views/settings/employees/show/tabs/images/index';
  // const employeesResource = new EmployeesResource();
  export default {
    name: 'EmployeeDetails',
    components: {EmployeeImages, Information},
    data() {
      return {
        employee: null,
        activeName: 'in',
      };
    },
    provide() {
      return {
        $employeeDetails: new Vue(),
      };
    },
    created() {},
    methods: {},
  };
</script>
