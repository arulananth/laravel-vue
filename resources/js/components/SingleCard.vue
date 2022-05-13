<template>
    <div>

                <div class="items__card__innercard">
                <p>{{card.title}}</p>
                <a @click="deleteCard(card.id,cindex)">X</a>
                </div>
                <div class="items__card__description">{{card.description}}</div>


    </div>
</template>

<script>
import axios from "axios";
export default {
  name:'SingleCard',
  props: ['card','listId','cindex'],
  data() {
  return {
    list_id: 0,
    cardIndex:0,
    cardData:{}
  }
  },
  created() {
       this.list_id = this.listId;
       this.cardIndex = this.cindex;
       this.cardData = this.card;
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
          let self=this;
          axios.delete('/api/delete-card/'+id)
                .then(function (response) {
                  console.log(response)
                  self.$emit('deletedIndex', {index:index,id:id,list_id:self.list_id});
                },function (error) {
                  console.log(error)
                });
      }
  }
};
</script>

