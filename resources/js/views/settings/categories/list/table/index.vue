<template>
    <div>
        <el-table
            ref="dragTable"
            :key="tableKey"
            v-loading="listLoading"
            :data="list"
            border
            fit
            highlight-current-row
            style="width: 100%;"
            row-key="sort_id"
      >
            <el-table-column :label="$t('table.id')" prop="id" sortable align="center" width="100">
                <template slot-scope="{row}">
                    <span>{{ row.category_id }}</span>
                </template>
            </el-table-column>

            <el-table-column :label="$t('table.title')">
                <template slot-scope="{row}">
                    <span class="link-type" @click="handleShow(row)">{{ row.category.title }}</span>
                </template>
            </el-table-column>

          <el-table-column :label="$t('table.servicesNumber')" align="center">
            <template slot-scope="{row}">
              <span class="link-type">{{ row.services.length }}</span>
            </template>
          </el-table-column>

          <el-table-column :label="$t('table.actions')" align="center" class-name="small-padding">
            <template slot-scope="{row}">
              <el-button type="primary" @click="handleShow(row)">
                {{ $t('table.edit') }}
              </el-button>

<!--              <el-button :loading="loading === row.id"-->
<!--                         type="danger" @click="handleDelete(row)">-->
<!--                {{ $t('table.delete') }}-->
<!--              </el-button>-->
            </template>
          </el-table-column>
      </el-table>

      <pagination
        v-show="total>0"
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="getCategories"
      />
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import SalonCategoryResource from '@/api/salon-category';
    import Pagination from '@/components/Pagination/index';
    import Sortable from 'sortablejs';

    const salonCategoryResource = new SalonCategoryResource();
    export default {
        name: 'ServicesTable',
        components: {Pagination},
        inject: ['$_categories'],
        data(){
            return {
                list: [],
                oldList: [],
                newList: [],
                tableKey: 0,
                total: 0,
                listLoading: true,
                loading: null,
                listQuery: {
                    page: 1,
                    limit: 20,
                    title: '',
                    sort: '+id',
                    salon_id: null,
                },
            };
        },
        computed: {
            ...mapGetters([
                'userSalon',
            ]),
        },
        created(){
            this.getCategories();
            this.$_categories.$on('search-category', this.handleFilter);
            this.$_categories.$on('update-salon-categories-list', this.updateList);
            this.$_categories.$on('update-salon-category', this.salonCategoryUpdate);
        },
        methods: {
            async getCategories(){
              this.listQuery.salon_id = this.userSalon.id;
              const {data} = await salonCategoryResource.list(this.listQuery);
              this.list = data.categories;
              this.total = data.total;
              this.listLoading = false;
              this.oldList = this.list.map(v => v.id);
              this.newList = this.oldList.slice();
              this.$nextTick(() => {
                this.setSort();
              });
            },
            updateList(category){
                this.list.push(category);
                this.oldList = this.list.map(v => v.id);
                this.newList = this.oldList.slice();
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
                        salonCategoryResource.sort(this.newList);
              },
            });
          },
          handleFilter(title) {
            this.listQuery.title = title;
            this.listQuery.page = 1;
            this.getCategories();
          },
          handleShow(row){
              this.$router.push({path: '/services/' + row.id });
          },
          salonCategoryUpdate(category) {
            for (const v of this.list) {
              if (v.id === category.id) {
                const index = this.list.indexOf(v);
                this.list.splice(index, 1, category);
                break;
              }
            }
          },
          // handleDelete(row) {
          //   if (row.id) {
          //     this.loading = row.id;
          //     salonCategoryResource.destroy(row.id).then(() => {
          //       this.$notify({
          //         title: 'Success',
          //         message: 'Deleted successfully',
          //         type: 'success',
          //         duration: 2000,
          //       });
          //       this.loading = null;
          //       const index = this.list.indexOf(row);
          //       this.list.splice(index, 1);
          //     });
          //   }
          // },
        },
    };
</script>
