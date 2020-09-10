<template>
    <div>
        <el-dialog :title="'Update category'" width="30%" :visible="visible" @update:visible="close">
            <el-form ref="dataForm" :rules="rules" :model="form" label-position="top"
                     style="width: 400px; margin-left:50px;">
                <el-form-item label="Category" prop="category">
                    <el-select v-model="form.category_id"
                               value-key="id"
                               placeholder="Select category"
                               :loading="categoriesLoading"
                               @change="handleChange"
                    >
                        <el-option v-for="item in categories"
                                   :key="item.id"
                                   :label="item.title"
                                   :value="item.id"
                        />
                    </el-select>
                </el-form-item>

                <el-form-item v-if="services.length" v-loading="servicesLoading" label="Services" prop="services">
                    <el-checkbox-group v-model="form.services">
                        <el-checkbox v-for="item in services"
                                     :key="item.service"
                                     :label="item.service"
                        />
                  </el-checkbox-group>
                </el-form-item>
            </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="close">
                    {{ $t('table.cancel') }}
                </el-button>

                <el-button type="primary" :loading="loading" @click="updateData">
                    {{ $t('table.confirm') }}
                </el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import CategoryResource from '@/api/category';
    import CategoryServicesResource from '@/api/category-service';
    import SalonCategoryResource from '@/api/salon-category';
    import {mapGetters} from 'vuex';

    const categoryResource = new CategoryResource();
    const categoryServicesResource = new CategoryServicesResource();
    const salonCategoryResource = new SalonCategoryResource();
    export default {
        name: 'UpdateCategoryModal',
        inject: ['$_categories'],
        props: {
          visible: {
              type: Boolean,
              default: false,
          },
          category: {
            type: Object,
            default: null,
          },
        },
        data(){
            return {
                loading: false,
                form: {
                  id: this.category.id,
                  salon_id: this.category.salon_id,
                  category_id: this.category.category_id,
                  services: this.category.services,
                },
                categories: [],
                services: [],
                categoriesLoading: false,
                servicesLoading: false,
                rules: {
                    category: [{required: true, message: 'Category is required', trigger: 'blur'}],
                },
            };
        },
      computed: {
        ...mapGetters([
          'userSalon',
        ]),
      },
      created() {
          this.getCategories();
          this.getServices({category_id: this.category.category_id});
      },
      methods: {
          close() {
              this.$emit('update:visible', false);
          },
          async getCategories(){
              this.categoriesLoading = true;
              const {data} = await categoryResource.list({company_type_id: this.userSalon.company_type_id});
              this.categoriesLoading = false;
              this.categories = data.categories;
          },
          async getServices(category){
              this.servicesLoading = true;
              const {data} = await categoryServicesResource.list(category);
              this.services = data.services;
              this.servicesLoading = false;
          },
          handleChange(value){
              this.form.services = [];
              this.getServices({category_id: value});
          },
          updateData(){
              this.loading = true;
              salonCategoryResource.update(this.form.id, this.form).then((response) => {
                  this.$_categories.$emit('update-salon-category', response.data.category);
                  this.close();
                  this.$notify({
                      title: 'Success',
                      message: 'Updated successfully',
                      type: 'success',
                      duration: 2000,
                  });
                  this.loading = false;
              });
          },
      },
    };
</script>

