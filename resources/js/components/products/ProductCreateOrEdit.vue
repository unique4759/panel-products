<template>
    <form :class="type === 'preview' ? 'p-3 mt-3 border-top bg-light' : ''">
        <div class="row mb-3">
            <div class="col">
                <label for="product-name" class="d-none">Название</label>
                <input type="text"
                       id="product-name"
                       class="form-control"
                       placeholder="Название"
                       name="name"
                       v-model="form.name">
            </div>
            <div class="col">
                <el-select v-model="form.categories"
                           multiple
                           filterable
                           name="categories"
                           class="category-select w-100"
                           placeholder="Категории">
                    <el-option
                        v-for="(item,index) in categories"
                        :key="index"
                        :label="item.value"
                        :value="item.key">
                    </el-option>
                </el-select>
            </div>
            <div class="col">
                <label for="product-price" class="d-none">Цена</label>
                <input type="number"
                       id="product-price"
                       class="form-control"
                       placeholder="Цена"
                       name="price"
                       v-model="form.price">
            </div>
            <div class="col-auto">
                <el-switch
                    v-model="form.publish"
                    :active-value="true"
                    active-color="#13ce66"
                    inactive-color="#ff4949">
                </el-switch>
                <label class="m-0 pl-1 pt-1">Публикация</label><br>
            </div>
        </div>
        <div class="row no-gutters">
            <button class="btn btn-success btn-sm mr-1" @click="save">
                Сохранить
            </button>
            <button class="btn btn-sm btn-outline-dark" @click="cancel">
                Отмена
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        name: "product-create-or-edit",
        props: {
            data: {
                required: false,
            },
            type: {
                required: true,
            },
            categories: {
                required: true,
            }
        },
        data() {
            return {
                form: {
                    id: null,
                    name: '',
                    price: '',
                    publish: true,
                    image: '',
                    categories: [],
                },
                item: {},
            }
        },
        watch: {
            data(newVal, oldVal) {
                this.data = newVal;
            },
        },
        methods: {
            cancel() {
                this.$emit('cancel');
            },
            save() {
                let url = this.type === 'new' ? '/product/store' : '/product/update';

                axios.post(url, this.form).then(res => {

                    if (this.type === 'new') {
                        this.$emit('update');
                        this.$emit('cancel');
                    }
                    else {
                        this.$emit('saved', res);
                        this.$emit('cancel');
                    }
                }).catch(rej => console.log(rej));
            },
            getCategoriesIds(categories) {
                let listIds = [];

                categories.forEach((item) => {
                    listIds.push(item.id);
                });

                return listIds;
            },
            setData(data) {
                this.form.id = data['id'];
                this.form.name = data['name'];
                this.form.price = +data['price'];
                this.form.publish = !!data['publish'];
                this.form.categories = data['categories'] ? this.getCategoriesIds(data['categories']) : [];
            },
        },
        mounted() {
            if (this.type === 'preview') {
                this.setData(this.data);
            }
        },
    }
</script>

<style>
    .category-select .el-input__inner {
        height: 37px !important;
    }
</style>
