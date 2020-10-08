<template>
    <div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h1 class="m-0">Товары: {{ count }}</h1>
            <div class="top-btn">
                <button class="btn btn-sm" :class="create ? 'btn-outline-secondary' : 'btn-outline-primary'"
                        @click="showBtn()">{{ create ? 'Отмена' : 'Создать' }}
                </button>
            </div>
        </div>
        <div v-if="create" class="my-3 p-3 bg-white border rounded">
            <product-create-or-edit
                :data="null"
                :type="'new'"
                :categories="categories"
                @cancel="cancel"
                @update="getData">
            </product-create-or-edit>
        </div>
        <div class="d-flex align-items-center mt-4">
            <div class="col p-0">
                <input type="text"
                       name="name"
                       v-model="filters.name"
                       placeholder="Название товара"
                       class="form-control">
            </div>
            <div class="col category-select">
                <el-select v-model="filters.category"
                           clearable
                           placeholder="Категория">
                    <el-option
                        v-for="(item,index) in categories"
                        :key="index"
                        :label="item.value"
                        :value="item.key">
                    </el-option>
                </el-select>
            </div>
            <div class="col pl-0">
                <input type="number"
                       name="price_from"
                       v-model="filters.price_from"
                       placeholder="Цена от"
                       class="form-control">
            </div>
            <div class="col p-0">
                <input type="text"
                       name="price_to"
                       v-model="filters.price_to"
                       placeholder="Цена до"
                       class="form-control">
            </div>
            <div class="col-auto d-flex align-items-center p-0 ml-3">
                <el-switch
                    v-model="filters.deleted"
                    :active-value="1"
                    active-color="#13ce66"
                    inactive-color="#ff4949">
                </el-switch>
                <label class="m-0 pl-1 pt-1">Не удаленные</label>
            </div>
            <div class="col-auto d-flex align-items-center p-0 ml-3">
                <el-switch
                    v-model="filters.publish"
                    :active-value="1"
                    active-color="#13ce66"
                    inactive-color="#ff4949">
                </el-switch>
                <label class="m-0 pl-1 pt-1">Опубликованные</label>
            </div>
            <div class="col-auto d-flex align-items-center p-0 ml-3">
                <el-switch
                    v-model="filters.unpublish"
                    :active-value="1"
                    active-color="#13ce66"
                    inactive-color="#ff4949">
                </el-switch>
                <label class="m-0 pl-1 pt-1">Не опубликованные</label>
            </div>
        </div>
        <div v-if="!empty" class="mb-4">
            <product-preview
                v-for="item in data"
                :key="item.id"
                :def-item="item"
                :show-edit="current === item.id"
                :categories="categories"
                @edit-change="editChange"
                @delete="getData">
            </product-preview>
        </div>
        <p v-if="empty" class="m-0 text-danger">Товаров не найдено!</p>
    </div>
</template>

<script>
    import ProductPreview from './ProductPreview';
    import ProductCreateOrEdit from './ProductCreateOrEdit';
    import _ from 'lodash';

    export default {
        name: "products",
        props: {
            categories: {
                required: false
            }
        },
        components: {
            ProductPreview,
            ProductCreateOrEdit,
        },
        data() {
            return {
                count: 0,
                data: {},
                create: false,
                empty: false,
                current: '',
                filters: {
                    name: '',
                    deleted: false,
                    publish: false,
                    unpublish: false,
                    category: '',
                    price_from: '',
                    price_to: '',
                },
            }
        },
        watch: {
            filters: {
                handler: _.debounce(
                    function () {
                        this.getData();
                    }, 500
                ),
                deep: true
            },
        },
        methods: {
            getData() {
                axios.post('/products/get-data', this.filters).then(res => {
                    this.count = res.data.count;
                    this.data = res.data.data;
                    this.empty = this.data.length === 0;
                }).catch(err => console.log(err));
            },
            showBtn() {
                this.create = !this.create;
            },
            cancel() {
                this.ceate = false;
                this.current = '';
            },
            editChange(id) {
                this.create = false;
                this.current = id;
            },
        },
        mounted() {
            this.getData();
        },
    }
</script>

<style>
    .category-select .el-input__inner {
        height: 37px !important;
    }
</style>
