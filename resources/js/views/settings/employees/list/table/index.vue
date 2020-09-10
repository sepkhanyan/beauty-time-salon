<template>
  <div>
    <el-table ref="dragTable" v-loading="listLoading" :data="list" row-key="id" border fit highlight-current-row
              style="width: 100%">
      <el-table-column align="center" label="ID" width="65px">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="200" :label="$t('table.name')">
        <template slot-scope="scope">
          <span class="link-type" @click="showEmployee(scope.row)">{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.position')" min-width="150px">
        <template slot-scope="scope">
          <span class="link-type" @click="showEmployee(scope.row)">{{ scope.row.position.title }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.phone')" width="150">
        <template slot-scope="scope">
          <span class="link-type" @click="showEmployee(scope.row)">{{ scope.row.phone_number }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.status')" class-name="status-col" width="100">
        <template slot-scope="{row}">
          <el-tag :type="row.status | statusFilter">
            {{ row.status }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" class-name="small-padding fixed-width" width="200">
        <template slot-scope="{row}">
          <el-button type="primary" size="mini" class="link-type" @click="showEmployee(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="row.status!='active'" size="mini" type="success"
                     @click="handleModifyStatus(row,'active')">
            {{ $t('table.active') }}
          </el-button>
          <el-button v-if="row.status!='disabled'" size="mini" @click="handleModifyStatus(row,'disabled')">
            {{ $t('table.disable') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />
  </div>
</template>

<script>
  import EmployeesResource from '@/api/employees';
  import Pagination from '@/components/Pagination';
  import Sortable from 'sortablejs'; // Secondary package based on el-pagination

  const employeesResource = new EmployeesResource();
  export default {
    name: 'DragTable',
    inject: ['$employeesList'],
    components: {Pagination},
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
        list: [],
        positions: null,
        listLoading: true,
        tableKey: 0,
        total: 0,
        listQuery: {
          page: 1,
          limit: 5,
          name: null,
        },
        oldList: [],
        newList: [],
        statusOptions: ['active', 'disabled', 'deleted'],
      };
    },
    created() {
      this.$employeesList.$on('search-position', this.handleFilter);
      this.$employeesList.$on('update-employees-list', this.listUpdate);
      this.$employeesList.$on('show-updated-employees', this.showUpdateEmployees);
      this.$employeesList.$on('change-updated-data', this.changeUpdated);
      this.getList();
    },
    methods: {
      async getList() {
        this.listLoading = true;
        const {data} = await employeesResource.list(this.listQuery);
        this.list = data.employees;
        this.total = data.total;
        console.log(this.list);
        this.$employeesList.$emit('get-positions-list', data.positions);
        this.listLoading = false;
        this.$nextTick(() => {
          this.setSort();
        });
      },
      listUpdate(employees) {
        this.list.push(employees);
      },
      changeUpdated(index) {
        this.list.splice(index, 1, this.list);
      },
      setSort() {
        const el = this.$refs.dragTable.$el.querySelectorAll('.el-table__body-wrapper > table > tbody')[0];
        this.sortable = Sortable.create(el, {
          ghostClass: 'sortable-ghost', // Class name for the drop placeholder,
          setData: function(dataTransfer) {
            // to avoid Firefox bug
            // Detail see : https://github.com/RubaXa/Sortable/issues/1012
            dataTransfer.setData('Text', '');
          },
          onEnd: evt => {
            const targetRow = this.list.splice(evt.oldIndex, 1)[0];
            this.list.splice(evt.newIndex, 0, targetRow);
            // for show the changes, you can delete in you code
            const tempIndex = this.newList.splice(evt.oldIndex, 1)[0];
            this.newList.splice(evt.newIndex, 0, tempIndex);
          },
        });
      },
      handleFilter(title) {
        this.listQuery.name = name;
        this.listQuery.page = 1;
        this.getList();
      },
      showEmployee(row) {
        // this.$store.dispatch('user/setEmployee', row);
        this.$router.push({path: '/employees/' + row.id});
      },
      handleModifyStatus(row, status) {
        console.log(row.id);
        employeesResource.toggleStatus(row.id).then(response => {
          console.log(response.data);
          status = response.data.status;
        });
        this.$message({
          message: 'Successful operation',
          type: 'success',
        });
        row.status = status;
      },
      showDialog(row) {
        this.$employeesList.$emit('show-update-dialog', row);
      },
      showUpdateEmployees(temp) {
        for (const v of this.list) {
          if (v.id === temp.id) {
            const index = this.list.indexOf(v);
            this.$employeesList.$emit('change-updated', index);
            this.list.splice(index, 1, temp);
            break;
          }
        }
      },
      handleDelete(row) {
        employeesResource.destroy(row.id).then(response => {
          const deleted = response.data.id;
          for (const v of this.list) {
            if (v.id === deleted) {
              const index = this.list.indexOf(v);
              this.list.splice(index, 1);
              break;
            }
          }
        });
        this.$notify({
          title: 'Success',
          message: 'Deleted successfully',
          type: 'success',
          duration: 2000,
        });
        const index = this.list.indexOf(row);
        this.list.splice(index, 1);
      },
    },
  };
</script>

<style scoped>

</style>
