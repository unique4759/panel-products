<template>
    <div class="mt-4 bg-white p-3 border" v-if="Object.keys(item).length !== 0">
        <div class="row align-items-center no-gutters position-relative">
            <div class="col">
                <p class="mb-0">{{ item.name }}</p>
            </div>
            <div class="col-3 text-right">
                <button v-if="!showEdit"
                        class="btn btn-outline-success btn-sm"
                        title="Редактировать"
                        @click="editData(item.id)">Редактировать
                </button>
                <button v-if="showEdit"
                        class="btn btn-sm btn-outline-dark"
                        title="Не редактировать"
                        @click="cancel()">Не редактировать
                </button>
                <button class="btn btn-sm btn-outline-danger" title="Удалить"
                        @click="deleteCategory(item.id)">Удалить
                </button>
            </div>
        </div>
        <category-create-or-edit
            v-if="showEdit"
            :data="item"
            :type="'preview'"
            @saved="saveItem"
            @cancel="cancel"
        ></category-create-or-edit>
    </div>
</template>

<script>
    import CategoryCreateOrEdit from "./CategoryCreateOrEdit";

    export default {
        components: {
            CategoryCreateOrEdit,
        },
        name: "category-preview",
        props: {
            defItem: {
                required: true,
            },
            showEdit: {
                required: true,
            },
        },
        data() {
            return {
                item: {},
            }
        },
        methods: {
            saveItem(data) {
                this.item = data;
            },
            editData(id) {
                this.$emit('edit-change', id);
            },
            cancel() {
                this.$emit('edit-change', '');
            },
            deleteCategory(id) {
                axios.post('/category/delete', {id: id}).then(res => {
                    this.$emit('delete');
                }).catch((err) => {
                    console.log(err);
                    swal.fire({
                        icon: 'warning',
                        title: 'Категория используется в товаре!',
                        showCancelButton: false,
                        confirmButtonText: 'Закрыть',
                    });
                });
            },
        },
        mounted() {
            this.item = this.defItem;
        },
    }
</script>

