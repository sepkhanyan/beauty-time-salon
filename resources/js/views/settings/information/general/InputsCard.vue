<template>
    <div>
        <el-card>
            <el-form ref="form" :rules="rules" :model="salon" label-position="top" label-width="100%">
                <el-form-item label="Company Type" prop="service_id">
                    <el-select v-model="salon.company_type_id"
                               value-key="id"
                               placeholder="Select type"
                               :loading="companyTypesLoading"
                    >
                        <el-option
                                v-for="type in companyTypes"
                                :key="type.id"
                                :label="type.title"
                                :value="type.id"
                        />
                    </el-select>
                </el-form-item>

                <el-form-item label="Lang of notifications" prop="notification_lang">
                    <el-select v-model="salon.notification_lang" value-key="id" placeholder="Select lang">
                        <el-option
                                v-for="item in langList"
                                :key="item"
                                :label="item"
                                :value="item"
                        />
                    </el-select>
                </el-form-item>

                <el-form-item label="Description" prop="description">
                    <el-input v-model="salon.description" type="textarea" />
                </el-form-item>

                <el-form-item label="Social net Link" prop="social">
                    <el-input v-model="salon.social" />
                </el-form-item>

                <el-form-item>
                    <el-button :loading="loading" type="primary" @click="updateData">Update</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import CompanyTypeResource from '@/api/company-type';
    import SalonResource from '@/api/salon';
    import { validURL } from '@/utils/validate';

    const companyTypeResource = new CompanyTypeResource();
    const salonResource = new SalonResource();

    export default {
        name: 'InputsCard',
        data() {
            const validateUrl = (rule, value, callback) => {
              if (!validURL(value)) {
                callback(new Error('Enter correct social net link.'));
              } else {
                callback();
              }
            };
            return {
                salon: {},
                companyTypesLoading: true,
                loading: false,
                rules: {
                    company_type_id: [{required: true, message: 'Company type is required', trigger: 'change'}],
                    social: [{trigger: 'blur', validator: validateUrl}],
                },
                langList: ['Russian', 'English', 'Armenian'],
                companyTypes: [],
                total: 0,
            };
        },
        computed: {
            ...mapGetters([
                'name',
                'email',
                'userSalon',
            ]),
        },
        created(){
            this.getCompanyTypes();
            this.getSalon();
        },
        methods: {
          getSalon(){
            this.salon = {
              company_type_id: this.userSalon.company_type_id,
              notification_lang: 'English',
              description: this.userSalon.description,
              social: this.userSalon.social,
              is_update: true,
            };
          },
            updateData(){
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        this.loading = true;
                        salonResource.update(this.userSalon.id, this.salon)
                          .then((response) => {
                            this.loading = false;
                            this.$notify({
                              title: 'Success',
                              message: 'Updated successfully',
                              type: 'success',
                              duration: 2000,
                            });
                            this.salon.company_type_id = response.data.salon.company_type_id;
                            this.salon.description = response.data.salon.description;
                            this.salon.social = response.data.salon.social;
                          });
                    }
                });
            },
          getCompanyTypes(){
              companyTypeResource.list()
                    .then((response) => {
                        this.companyTypes = response.data.types;
                        this.companyTypesLoading = false;
                        this.total = response.data.total;
                    });
            },
        },
    };
</script>

