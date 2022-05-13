<template>
  <div class="main-top-section">

  <div class="items" :data-id="col.id" v-for="(col,index) in list_col" :key="col.id">
        <div class="items__column1-text" :data-id="col.id">
                    <a @click="addCardList(col.id)" class="add-card-a">+</a>
                    <p>{{col.title}}</p>
                    <a @click="deleteColList(col.id,index)" class="remove-card-a">X</a>
        </div>
        <draggable   :list="col.cardlists"  group="people"  @change="reOrderCards">
        <div v-for="(card,cindex) in col.cardlists" :key="card.id" class="items__card">
        <single-card @deletedIndex="getDeletedIndex" :cindex=cindex :listId=list_id :card=card></single-card>
        </div>
        </draggable>
   </div>

 <div class="add-menu" @click="addColList"><button>Add New</button></div>
 <div class="db-menu" @click="showexport"><button>EXPORT DB</button></div>
 <new-list-form ref="cols"></new-list-form>
 <new-card-form :listId="list_id" ref="card"></new-card-form>
</div>
</template>

<script>
import draggable from 'vuedraggable';
import axios from "axios";
import SingleCard from './SingleCard.vue';
export default {
  props: [
    'list',

  ],
  data() {
  return {
    list_id:0,
    list_col:[],
    before_move:[],
    switchView:false
  }
  },
  computed: {

  },
  components: {
   SingleCard,
   draggable
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

      reOrderCards($event)
      {
          if($event.moved)
          {
            let list_id_data=this.list_col.find(c=>(c.id==$event.moved.element.list_id));
            let postData = list_id_data.cardlists.map(({id, orderRow, list_id}) => ({id, orderRow, list_id}));
            this.updateOrderRow(postData);
            this.switchView=false;
          }
          if($event.added)
          {
              this.onEndUpdate();
          }


      },
      onEndUpdate: function() {

            let postData  = [];
             this.list_col.forEach(function(val,index){
                 if(val.cardlists.length>0){

               val.cardlists.forEach(function(card,c){
                   postData.push({
                       orderRow:card.orderRow,
                       list_id:val.id,
                       id:card.id
                   })

               });
               }
             });
            this.updateOrderRow(postData);

      },
      updateOrderRow(postData)
      {
        if(postData.length==0) return false;
        let self = this;
            axios.post('/api/update-card',{data:postData})
                .then(function (response) {

            })
            .catch(function (error) {
                console.log(error)
            });
      },
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
