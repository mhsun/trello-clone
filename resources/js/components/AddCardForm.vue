<template>
    <section>
        <input v-model="card.title" placeholder="Write title for the card here"
               style="margin-left: 15%; margin-bottom: 10px; margin-top: 5%; width: 70%" class="input-field"
               type="text" autofocus>

        <textarea v-model="card.description" placeholder="Write description here"
                  style="margin-left: 15%; width: 70%" class="input-field"></textarea>
        <br>
        <button @click="addCardToColumn" style="margin-left: 15%; width: 70%" class="input-field">Save</button>
    </section>
</template>

<script>
export default {
    name: "AddCardForm",
    props: {
        columnId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            card: {
                title: '',
                description: ''
            }
        }
    },
    methods: {
        addCardToColumn() {
            window.axios.post(`/ajax/columns/${this.columnId}/cards`, this.card)
                .then(response => {
                    this.$parent.$emit('cardAdded', response.data.data)
                    this.$modal.hide(this.$parent.name);
                })
                .catch(err => alert("Something went wrong! Try again later"))
                .finally(() => {
                    this.card.title = '';
                    this.card.description = '';
                })
        }
    }
}
</script>

<style scoped>

</style>
