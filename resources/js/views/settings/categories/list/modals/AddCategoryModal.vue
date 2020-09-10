<template>
    <div>
        <el-dialog :title="'Add category'" width="30%" :visible="visible" @update:visible="close">
            <el-form ref="dataForm" :rules="rules" :model="form" label-position="top"
                     style="width: 400px; margin-left:50px;">
                <el-form-item label="Category" prop="category_id" :class="{customValidation: msg}">
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
                    <div v-if="msg" class="el-form-item__error">
                        {{ msg }}
                    </div>
                </el-form-item>

                <el-form-item v-if="services.length" v-loading="servicesLoading" label="Services" prop="services">
                    <el-checkbox-group v-model="form.services">
                        <el-checkbox v-for="item in services"
                                     :key="item.id"
                                     :label="item.service"
                        />
                  </el-checkbox-group>
                </el-form-item>
            </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="close">
                    {{ $t('table.cancel') }}
                </el-button>

                <el-button type="primary" :loading="loading" @click="addData">
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
        name: 'AddCategoryModal',
        inject: ['$_categories'],
        props: {
          visible: {
              type: Boolean,
              default: false,
          },
        },
        data(){
            return {
                loading: false,
                form: {
                  id: undefined,
                  salon_id: null,
                  category_id: null,
                  services: [],
                },
                msg: null,
                categories: [],
                services: [],
                categoriesLoading: false,
                servicesLoading: false,
                rules: {
                    category_id: [{required: true, message: 'Category is required', trigger: 'blur'}],
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
          addData(){
            this.$refs['dataForm'].validate((valid) => {
              if (valid) {
                  this.form.salon_id = this.userSalon.id;
                  this.loading = true;
                  salonCategoryResource.store(this.form).then((response) => {
                      this.$_categories.$emit('update-salon-categories-list', response.data.category);
                      this.close();
                      this.$notify({
                          title: 'Success',
                          message: 'Added successfully',
                          type: 'success',
                          duration: 2000,
                      });
                      this.loading = false;
                  })
                  .catch((error) => {
                      this.msg = error.response.data.errors['category_id'] ? error.response.data.errors['category_id'][0] : '';
                      this.loading = false;
                  });
              }
            });
          },
      },
    };
</script>

