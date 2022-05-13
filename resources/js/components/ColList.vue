<template>
  <div class="main-top-section">
  <div class="items" v-for="(col,index) in list_col" :key="col.id">
        <div class="items__column1-text">
                    <a @click="addCardList(col.id)" class="add-card-a">+</a>
                    <p>{{col.title}}</p>
                    <a @click="deleteColList(col.id,index)" class="remove-card-a">X</a>
        </div>
        <div v-for="(card,cindex) in col.cardlists" :key="card.id" class="items__card">
        <single-card @deletedIndex="getDeletedIndex" :cindex=cindex :listId=list_id :card=card></single-card>
        </div>
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
    list_id:0,
    list_col:[]
  }
  },
  computed: {

  },
  components: {
   SingleCard
  },
  created() {
       this.list_col = this.list;
  },
  mounted() {
    this.$watch(
      "$refs.cols.newListCount",
      (new_value, old_value) => {
          this.list_col.push(this.$refs.cols.newList)
          this.$modal.hide('new-list-form')
      });
   this.$watch(
      "$refs.card.newCardCount",
      (new_value, old_value) => {
       let card=this.$refs.card.newCard;
       let findListIndex=this.list_col.findIndex((c)=>(c.id==card.list_id));
       this.list_col[findListIndex]['cardlists'].push(card)
       this.$modal.hide('new-card-form');
      });
  },
  methods:{
      getDeletedIndex(deletedData)
      {
         if(deletedData)
         {
          let findListIndex=this.list_col.findIndex((c)=>(c.id==deletedData.list_id));
          this.list_col[findListIndex]['cardlists'].splice(deletedData.index,1)
         }
      },
      deleteColList(id,index)
      {
          let self = this;
          axios.delete('/api/delete-col/'+id)
                .then(function (response) {
                  console.log(response)
                  self.list_col.splice(index,1)
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
