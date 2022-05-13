<template>
    <div>
        <div v-for="(card,index) in cards" :key="card.id" class="items__card">
                <div class="items__card__innercard">
                <p>{{card.title}}</p>
                <a @click="deleteCard(card.id,index)">X</a>
                </div>
                <div class="items__card__description">{{card.description}}</div>
        </div>

    </div>
</template>

<script>
import axios from "axios";
export default {
  name:'SingleCard',
  props: ['cards','listId'],
  data() {
  return {
    list_id: 0

  }
  },
   mounted() {
    this.$watch(
      "$refs.card.newCardCount",
      (new_value, old_value) => (this.list.push($refs.cols.newCard))
    );
  },
  methods:{
      deleteCard(id,index)
      {
          axios.delete('/api/delete-card/'+id)
                .then(function (response) {
                  console.log(response)
                   this.cards.splice(index,1)
                },function (error) {
                  console.log(error)
                });
      }
  }
};
</script>

