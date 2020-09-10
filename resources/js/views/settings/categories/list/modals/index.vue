<template>
    <div>
        <add-category-modal v-if="addCategoryModal"
                            :visible.sync="addCategoryModal"
        />

        <update-category-modal v-if="updateCategoryModal"
                               :visible.sync="updateCategoryModal"
                               :category="category"
        />
    </div>
</template>

<script>
    import AddCategoryModal from './AddCategoryModal';
    import UpdateCategoryModal from './UpdateCategoryModal';
    export default {
        name: 'ServicesModal',
        inject: ['$_categories'],
        components: {
          AddCategoryModal,
          UpdateCategoryModal,
        },
        data() {
            return {
                addCategoryModal: false,
                updateCategoryModal: false,
                category: null,
            };
        },
        created() {
            this.$_categories.$on('show-add-category-modal', this.showAddCategoryModal);
            this.$_categories.$on('show-update-category-modal', this.showUpdateCategoryModal);
        },
        methods: {
            showAddCategoryModal(bool = true) {
                this.addCategoryModal = bool;
            },
            showUpdateCategoryModal(category, bool = true) {
                this.category = category;
                this.updateCategoryModal = bool;
            },
        },
    };
</script>
