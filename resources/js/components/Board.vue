<template>
    <div>
        <button @click="exportDB" style="float: right">Export Database</button>

        <section class="lists-container">
            <div class="list" v-for="(column, index) in columns" :key="index" :data-column-id="column.id">

                <h3 class="list-title">
                    {{ column.title }}
                    <button @click="deleteColumn(column.id)" class="del-btn text-danger">X</button>
                </h3>

                <draggable class="list-items" element="ul" :options="{animation: 300,group: 'cItem'}"
                           @update="reorderCards($event.from)"
                           @add="moveCardToAnotherColumn"
                           :data-column-id="column.id"
                >
                    <li v-for="(card, cardIndex) in column.cards"
                        :key="cardIndex"
                        :data-column-id="column.id"
                        :data-card-id="card.id"
                        @click="openCardForEdit(card)"
                    >
                        {{ card.title }}
                    </li>
                </draggable>

                <button @click="addNewCard(column.id)" class="add-card-btn btn">Add a card</button>
            </div>

            <div>
                <button v-if="showAddColumnBtn" @click="showAddColumnBtn = false" class="add-list-btn btn">Add a column
                </button>

                <div v-if="!showAddColumnBtn">
                    <input v-model="column.title" @keyup="addNewColumn($event)" class="input-field" type="text"
                           autofocus>
                    <button @click="showAddColumnBtn = true" class="cancel-btn">Cancel</button>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import AddCardForm from "./AddCardForm";
import EditCardForm from "./EditCardForm";

export default {
    components: {draggable, AddCardForm, EditCardForm},
    name: "Board",
    data() {
        return {
            columns: [],
            column: {
                title: ''
            },

            showAddColumnBtn: true,

            selectedColumnId: '',
            selectedCard: {},
        }
    },
    created() {
        this.getColumns();
    },
    methods: {
        getColumns() {
            window.axios.get('/ajax/columns')
                .then(response => {
                    this.columns = response.data.data;
                })
                .catch(err => alert("Couldn't fetch data!"));
        },

        deleteColumn(id) {
            window.axios.delete(`/ajax/columns/${id}`)
                .then(response => {
                    this.columns = this.columns.filter(column => column.id !== id);
                })
                .catch(err => alert("Something went wrong!"));
        },

        addNewColumn(event) {
            if (this.column.title.length > 0 && event.keyCode === 13) {
                window.axios.post(`/ajax/columns`, {title: this.column.title})
                    .then(response => {
                        this.columns.push(response.data.data);
                    })
                    .catch(err => alert("Something went wrong! Try again later"))
                    .finally(() => {
                        this.showAddColumnBtn = true;
                        this.column.title = '';
                    })
            }
        },

        addNewCard(id) {
            this.selectedColumnId = id;

            this.$modal.show(AddCardForm,
                {columnId: id},
                {},
                {
                    'cardAdded': data => {
                        this.pushAddedCardToColumn(data)
                    }
                }
            );
        },

        pushAddedCardToColumn(data) {
            this.columns.filter(column => column.id === this.selectedColumnId)[0].cards.push(data);
            this.selectedColumnId = null;
        },

        openCardForEdit(card) {
            this.selectedCard = card;

            this.$modal.show(EditCardForm,
                {selectedCard: card},
                {},
                {
                    'cardUpdated': data => {
                        const card = this.columns.filter(column => column.id === data.column_id)[0]
                            .cards.filter(card => card.id === data.id)[0];
                        Object.assign(card, data);
                    }
                }
            );
        },

        reorderCards(evt) {
            let idsWithUpdatedPositions = [];

            evt.querySelectorAll('li').forEach(item => {
                idsWithUpdatedPositions.push(parseInt(item.getAttribute('data-card-id')))
            })

            window.axios.post(`/ajax/columns/${evt.getAttribute('data-column-id')}/reorder`, {
                positions: idsWithUpdatedPositions
            }).catch(err => alert("Something went wrong! Try refreshing the page"))
        },

        moveCardToAnotherColumn(evt) {
            this.reorderCards(evt.from);
            this.reorderCards(evt.to);
        },

        exportDB() {
            location.assign('/ajax/export-db');
        }
    }
}
</script>

<style scoped>

</style>
