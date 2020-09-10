<template>
  <div class="components-container">
    <el-dialog v-el-drag-dialog :visible.sync="dialogTableVisible" title="Employees List" @dragDialog="handleDrag">
      <el-table :data="gridData">
        <el-table-column property="employee" label="Employee" width="200" />
        <el-table-column property="position" label="Position" width="150" />
        <el-table-column :label="$t('table.status')" class-name="status-col">
          <template slot-scope="{row}">
            <el-tag :type="row.status | statusFilter">
              {{ row.status }}
            </el-tag>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
  </div>
</template>

<script>
  import elDragDialog from '@/directive/el-drag-dialog'; // base on element-ui
  export default {
    name: 'EmployeesDialog',
    inject: ['$positionsList'],
    directives: { elDragDialog },
    filters: {
      statusFilter(status) {
        const statusMap = {
          active: 'success',
          disabled: 'info',
          deleted: 'danger',
        };
        return statusMap[status];
      },
    },
    data() {
      return {
        dialogTableVisible: false,
        value: '',
        gridData: [],
      };
    },
    created() {
      this.$positionsList.$on('show-employees-dialog', this.getEmployeesData);
    },
    methods: {
      getEmployeesData(row) {
        const data = [];
        row.employees.forEach(function(item, index) {
          data.push({
            employee: item.name,
            position: row.title,
            status: item.status,
          });
        });
        this.gridData = data;
        this.dialogTableVisible = true;
      },
      // v-el-drag-dialog onDrag callback function
      handleDrag() {
        this.$refs.select.blur();
      },
    },
  };
</script>
