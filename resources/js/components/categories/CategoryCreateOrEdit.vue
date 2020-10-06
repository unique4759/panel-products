<template>
    <form :class="type === 'preview' ? 'p-3 mt-3 border-top bg-light' : ''">
        <div class="form-group">
            <label for="category-name" class="d-none">Название</label>
            <input type="text"
                   id="category-name"
                   class="form-control"
                   placeholder="Название*"
                   name="name"
                   v-model="form.name">
        </div>
        <div class="form-group mb-0">
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
        name: "category-create-or-edit",
        props: {
            data: {
                required: false,
            },
            type: {
                required: false,
            },
        },
        data() {
            return {
                form: {
                    id: null,
                    name: '',
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
                let url = this.type === 'new' ? '/category/store' : '/category/update';

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
            setData(data) {
                this.form.id = data['id'];
                this.form.name = data['name'];
            },
        },
        mounted() {
            if (this.type === 'preview') {
                this.setData(this.data);
            }
        },
    }
</script>
