<template>
    <div class="mt-4 bg-white p-3 border" v-if="Object.keys(item).length !== 0">
        <div class="row align-items-center no-gutters position-relative">
            <div class="col d-flex align-items-center">
                <img v-if="!item.image" src="../../../images/non.png" alt="Превью товара"
                     class="rounded-circle image">
                <img v-else :src="item.image" alt="Превью товара" class="rounded-circle image">
                <p class="mb-0 ml-2">{{ item.name }} <span v-if="item.deleted" class="text-danger ml-3">удален</span></p>
            </div>
            <div v-if="!item.deleted" class="col-3 text-right">
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
                <button v-if="!item.deleted" class="btn btn-sm btn-outline-danger" title="Удалить"
                        @click="deleteCategory(item.id)">Удалить
                </button>
            </div>
        </div>
        <product-create-or-edit
            v-if="showEdit"
            :data="item"
            :type="'preview'"
            :categories="categories"
            @saved="saveItem"
            @cancel="cancel"
        ></product-create-or-edit>
    </div>
</template>

<script>
    import ProductCreateOrEdit from "./ProductCreateOrEdit";

    export default {
        components: {
            ProductCreateOrEdit,
        },
        name: "product-preview",
        props: {
            defItem: {
                required: true,
            },
            showEdit: {
                required: true,
            },
            categories: {
                required: true,
            }
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
                axios.post('/product/delete', {id: id}).then(res => {
                    this.$emit('delete');
                }).catch((err) => console.log(err));
            },
        },
        mounted() {
            this.item = this.defItem;
        },
    }
</script>

<style>
    .image {
        width: 40px;
        height: 40px;
    }
</style>

