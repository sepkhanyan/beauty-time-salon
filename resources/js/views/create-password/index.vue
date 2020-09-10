<template>
    <div class="password-create-container">
            <el-form ref="passwordForm" :rules="rules" :model="passwordForm" class="password-create" auto-complete="on"
                     label-position="left">
                <h3 class="title">
                    Create Password
                </h3>

                <el-form-item prop="password">
                    <span class="svg-container">
                        <svg-icon icon-class="password"/>
                    </span>

                    <el-input
                            v-model="passwordForm.password"
                            :type="pwdType"
                            name="password"
                            auto-complete="off"
                            placeholder="Password"
                    />
                </el-form-item>

                <el-form-item prop="password_confirmation">
                    <span class="svg-container">
                        <svg-icon icon-class="password"/>
                    </span>

                    <el-input
                            v-model="passwordForm.password_confirmation"
                            :type="pwdType"
                            name="password"
                            auto-complete="off"
                            placeholder="Confirm Password"
                            @keyup.enter.native="createData"
                    />

                    <span v-if="pwdType === 'password'" class="show-pwd" @click="showPwd">
                        <svg-icon icon-class="eye"/>
                    </span>

                    <span v-else class="show-pwd" @click="showPwd">
                        <svg-icon icon-class="eye-open"/>
                    </span>
                </el-form-item>

                <el-form-item>
                    <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="createData">
                        Create
                    </el-button>
                </el-form-item>
            </el-form>
    </div>
</template>

<script>
    import UserResource from '@/api/user';
    import SalonResource from '@/api/salon';
    import { mapGetters } from 'vuex';

    const userResource = new UserResource();
    const salonResource = new SalonResource();

    export default {
        name: 'Login',
        data() {
            const validatePass = (rule, value, callback) => {
                if (!value) {
                    callback(new Error('Password confirmation is required.'));
                } else if (value.length < 6) {
                    callback(new Error('Password cannot be less than 6 digits.'));
                } else if (value !== this.passwordForm.password) {
                    callback(new Error('Password confirmation does not match.'));
                } else {
                    callback();
                }
            };
            return {
                passwordForm: {
                    password: '',
                    password_confirmation: '',
                    password_created: 0,
                },
                rules: {
                    password: [
                        {required: true, message: 'Password is required.', trigger: 'blur'},
                        {min: 6, message: 'Password cannot be less than 6 digits.', trigger: 'blur'},
                    ],
                    password_confirmation: [{required: true, trigger: 'blur', validator: validatePass}],
                },
                loading: false,
                pwdType: 'password',
                redirect: undefined,
                otherQuery: {},
            };
        },
        computed: {
            ...mapGetters([
                'userId',
                'userSalon',
            ]),
        },
        watch: {
            $route: {
                handler: function(route) {
                    const query = route.query;
                    if (query) {
                        this.redirect = query.redirect;
                        this.otherQuery = this.getOtherQuery(query);
                    }
                },
                immediate: true,
            },
        },
        methods: {
            showPwd() {
                if (this.pwdType === 'password') {
                    this.pwdType = '';
                } else {
                    this.pwdType = 'password';
                }
            },
            createData() {
                this.$refs['passwordForm'].validate((valid) => {
                    if (valid) {
                        this.passwordForm.password_created = 1;
                        this.loading = true;
                        userResource.update(this.userId, this.passwordForm).then((response) => {
                            var user = response.data;
                            salonResource.update(this.userSalon.id, {status: 'in review'}).then(() => {
                                this.$store.dispatch('user/getInfo', user)
                                    .then(() => {
                                        this.$notify({
                                            title: 'Success',
                                            message: 'Created successfully',
                                            type: 'success',
                                            duration: 2000,
                                        });
                                        this.$router.push({ path: this.redirect || '/', query: this.otherQuery }, onAbort => {});
                                        this.loading = false;
                                    });
                            });
                        });
                    }
                });
            },
            getOtherQuery(query) {
                return Object.keys(query).reduce((acc, cur) => {
                    if (cur !== 'redirect') {
                        acc[cur] = query[cur];
                    }
                    return acc;
                }, {});
            },
        },
    };
</script>

<style rel="stylesheet/scss" lang="scss">
    $bg: #2d3a4b;
    $light_gray: #eee;

    /* reset element-ui css */
    .password-create-container {
        .el-input {
            display: inline-block;
            height: 47px;
            width: 85%;

            input {
                background: transparent;
                border: 0px;
                -webkit-appearance: none;
                border-radius: 0px;
                padding: 12px 5px 12px 15px;
                height: 47px;

                &:-webkit-autofill {
                    -webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
                    -webkit-text-fill-color: #fff !important;
                }
            }
        }

        .el-form-item {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            color: #454545;
        }
    }

</style>

<style rel="stylesheet/scss" lang="scss" scoped>
    $bg: #2d3a4b;
    $dark_gray: #889aa4;
    $light_gray: #eee;
    .password-create-container {
        position: fixed;
        height: 100%;
        width: 100%;

        .password-create {
            position: absolute;
            left: 0;
            right: 0;
            width: 520px;
            max-width: 100%;
            padding: 35px 35px 15px 35px;
            margin: 120px auto;
        }

        .tips {
            font-size: 14px;
            color: #fff;
            margin-bottom: 10px;

            span {
                &:first-of-type {
                    margin-right: 16px;
                }
            }
        }

        .svg-container {
            padding: 6px 5px 6px 15px;
            color: $dark_gray;
            vertical-align: middle;
            width: 30px;
            display: inline-block;
        }

        .title {
            font-size: 26px;
            font-weight: 400;
            margin: 0px auto 40px auto;
            text-align: center;
            font-weight: bold;
        }

        .show-pwd {
            position: absolute;
            right: 10px;
            top: 7px;
            font-size: 16px;
            color: $dark_gray;
            cursor: pointer;
            user-select: none;
        }

        .set-language {
            color: #fff;
            position: absolute;
            top: 40px;
            right: 35px;
        }
    }
</style>
