<template>
    <div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h1 class="m-0">Категории: {{ count }}</h1>
            <div class="top-btn">
                <button class="btn btn-sm" :class="create ? 'btn-outline-secondary' : 'btn-outline-primary'"
                        @click="showBtn()">{{ create ? 'Отмена' : 'Создать' }}
                </button>
            </div>
        </div>
        <div v-if="create" class="my-3 p-3 bg-white border rounded">
            <category-create-or-edit
                :data="null"
                :type="'new'"
                @cancel="cancel"
                @update="getData">
            </category-create-or-edit>
        </div>
        <div v-if="!empty" class="mb-4">
            <category-preview
                v-for="item in data"
                :key="item.id"
                :def-item="item"
                :show-edit="current === item.id"
                @edit-change="editChange"
                @delete="getData">
            </category-preview>
        </div>
        <p v-if="empty" class="m-0 text-danger">Категорий не найдено!</p>
    </div>
</template>

<script>
    import CategoryPreview from './CategoryPreview';
    import CategoryCreateOrEdit from './CategoryCreateOrEdit';

    export default {
        name: "categories",
        components: {
            CategoryCreateOrEdit,
            CategoryPreview,
        },
        data() {
            return {
                count: 0,
                data: {},
                create: false,
                empty: false,
                current: '',
            }
        },
        methods: {
            getData() {
                axios.post('/categories/get-data').then(res => {
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
