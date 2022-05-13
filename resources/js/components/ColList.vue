<template>
  <div class="main-top-section">
  <div class="items" v-for="(col,index) in list" :key="col.id">
        <div class="items__column1-text">
                    <a @click="addCardList(col.id)" class="add-card-a">+</a>
                    <p>{{col.title}}</p>
                    <a @click="deleteColList(col.id,index)" class="remove-card-a">X</a>
        </div>
        <single-card :listId=list_id :cards=col.cardlists></single-card>
   </div>

 <div class="add-menu" @click="addColList"><button>Add New</button></div>
 <div class="db-menu" @click="showexport"><button>EXPORT DB</button></div>
 <new-list-form ref="cols"></new-list-form>
 <new-card-form :listId="list_id" ref="card"></new-card-form>
</div>
</template>

<script>
import axios from "axios";
import SingleCard from './SingleCard.vue';
export default {
  props: [
    'list',

  ],
  data() {
  return {
    list_id:0

  }
  },
  computed: {

},
  components: {
   SingleCard
  },
  created() {

  },
  mounted() {
    this.$watch(
      "$refs.cols.newListCount",
      (new_value, old_value) => {
          this.list.push(this.$refs.cols.newList)

      });
   this.$watch(
      "$refs.card.newCardCount",
      (new_value, old_value) => {
          console.log(this.$refs.card.newCard)

      });
  },
  methods:{
      deleteColList(id,index)
      {

          axios.delete('/api/delete-col/'+id)
                .then(function (response) {
                  console.log(response)
                   this.list.splice(index,1)
            })
                .catch(function (error) {
                console.log(error)
                });
      },
      addCardList(list_id)
      {
        this.list_id=list_id;
        this.$modal.show('new-card-form')
      },
      addColList()
      {

          this.$modal.show('new-list-form')
      },
      showexport()
      {
      window.location.href="/export"
      }
  }
};
</script>

    SingleBoard
