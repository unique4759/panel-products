<template>
    <form :class="type === 'preview' ? 'p-3 mt-3 border-top bg-light' : ''">
        <div class="d-flex flex-column align-items-center col mb-3">
            <img v-if="!form.image" src="../../../images/non.png" alt="Превью товара"
                 class="mb-3 align-self-center rounded-circle">
            <img v-else :src="form.image" alt="Превью товара" class="mb-3 align-self-center rounded-circle image">
            <label for="image" class="d-none">Изображение</label>
            <input type="file"
                   id="image"
                   name="image"
                   @change="onFileChange"
                   accept="image/*">
        </div>
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
        <div class="row no-gutters align-items-center">
            <button class="btn btn-success btn-sm mr-1" @click="save" :disabled="form.categories.length === 1 || form.categories.length > 10">
                Сохранить
            </button>
            <button class="btn btn-sm btn-outline-dark mr-3" @click="cancel">
                Отмена
            </button>
            <span class="text-danger" v-if="form.categories.length === 1">Кол-во выбранных категорий должно составлять от 2 до 10</span>
            <span class="text-danger" v-if="form.categories.length > 10">Кол-во выбранных категорий не должно превышать 10</span>
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
                this.form.image = data['image'];
                this.form.publish = !!data['publish'];
                this.form.categories = data['categories'] ? this.getCategoriesIds(data['categories']) : [];
            },
            onFileChange(e) {
                let image = e.target.files[0];
                this.read(image);
            },
            read(img) {
                let reader = new FileReader();

                reader.readAsDataURL(img);
                reader.onload = e => {
                    this.form.image = e.target.result;
                };
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

    .image {
        width: 90px;
        height: 90px;
    }
</style>
