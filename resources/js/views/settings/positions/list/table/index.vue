<template>
  <div>
    <el-table ref="dragTable" v-loading="listLoading" :data="list" row-key="id" border fit highlight-current-row
              style="width: 100%">
      <el-table-column align="center" label="ID" width="75">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column max-width="200" :label="$t('table.title')">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.description')">
        <template slot-scope="scope">
          <span>{{ scope.row.description }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.employees')" width="150">
        <template slot-scope="scope">
          <el-button v-if="scope.row.employees" type="info" size="mini" @click="showEmployeesDialog(scope.row)">
            {{ scope.row.employees.length }}
          </el-button>
          <span v-else> 0 </span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.status')" class-name="status-col" width="150">
        <template slot-scope="{row}">
          <el-tag :type="row.status | statusFilter">
            {{ row.status }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.actions')" align="center" width="300" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button type="primary" size="mini" @click="showDialog(row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button v-if="row.status!='active'" size="mini" type="success"
                     @click="handleModifyStatus(row,'active')">
            {{ $t('table.active') }}
          </el-button>
          <el-button v-if="row.status!='disabled'" size="mini" @click="handleModifyStatus(row,'disabled')">
            {{ $t('table.disable') }}
          </el-button>
          <el-button v-if="row.status!='deleted'" size="mini" type="danger" @click="handleDelete(row)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <employees-dialog/>
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
  import PositionsResource from '@/api/positions';
  import Pagination from '@/components/Pagination';
  import Sortable from 'sortablejs';
  import EmployeesDialog from '@/views/settings/positions/list/modals/employees'; // Secondary package based on el-pagination

  const positionsResource = new PositionsResource();
  export default {
    name: 'DragTable',
    inject: ['$positionsList'],
    components: {EmployeesDialog, Pagination},
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
        listLoading: true,
        tableKey: 0,
        total: 0,
        listQuery: {
          page: 1,
          limit: 5,
          title: null,
        },
        oldList: [],
        newList: [],
        statusOptions: ['active', 'disabled', 'deleted'],
      };
    },
    created() {
      this.$positionsList.$on('search-position', this.handleFilter);
      this.$positionsList.$on('update-positions-list', this.listUpdate);
      this.$positionsList.$on('show-updated-position', this.showUpdatePosition);
      this.$positionsList.$on('change-updated-data', this.changeUpdated);
      this.getList();
    },
    methods: {
      async getList() {
        this.listLoading = true;
        const {data} = await positionsResource.list(this.listQuery);
        this.list = data.positions;
        this.total = data.total;
        this.listLoading = false;
        this.$nextTick(() => {
          this.setSort();
        });
      },
      listUpdate(position) {
        this.list.push(position);
      },
      changeUpdated(index) {
        this.list.splice(index, 1, this.list);
      },
      setSort() {
        const el = this.$refs.dragTable.$el.querySelectorAll('.el-table__body-wrapper > table > tbody')[0];
        this.sortable = Sortable.create(el, {
          ghostClass: 'sortable-ghost',
          setData: function(dataTransfer) {
            dataTransfer.setData('Text', '');
          },
          onEnd: evt => {
            const targetRow = this.list.splice(evt.oldIndex, 1)[0];
            this.list.splice(evt.newIndex, 0, targetRow);
            const tempIndex = this.newList.splice(evt.oldIndex, 1)[0];
            this.newList.splice(evt.newIndex, 0, tempIndex);
          },
        });
      },
      handleFilter(title) {
        this.listQuery.title = title;
        this.listQuery.page = 1;
        this.getList();
      },
      handleModifyStatus(row, status) {
        positionsResource.toggleStatus(row.id).then(response => {
          status = response.data.status;
        });
        this.$message({
          message: 'Successful operation',
          type: 'success',
        });
        row.status = status;
      },
      showDialog(row) {
        this.$positionsList.$emit('show-update-dialog', row);
      },
      showEmployeesDialog(row) {
        this.$positionsList.$emit('show-employees-dialog', row);
      },
      showUpdatePosition(temp) {
        for (const v of this.list) {
          if (v.id === temp.id) {
            const index = this.list.indexOf(v);
            this.$positionsList.$emit('change-updated', index);
            this.list.splice(index, 1, temp);
            break;
          }
        }
      },
        handleDelete(row) {
          positionsResource.destroy(row.id).then(response => {
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
