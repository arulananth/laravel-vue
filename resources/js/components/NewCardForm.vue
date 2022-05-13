<template>
<modal name="new-card-form" transition="pop-out" :width="modalWidth" :focus-trap="true" :height="400">

    <div class="container" style="max-width: 500px; text-align: left">
        <div class="alert alert-success" role="alert">
            <h2 class="alert-heading new-card-form__add">Add Card</h2>
        </div>
        <form @submit.prevent="handleSubmit">
            <register-validation-error :errors="form_list_errors"></register-validation-error>
            <div class="add-form">
                <div class="form-group add-form__group">
                    <label for="name">Title</label>
                    <input type="text" v-model="userForm.name" id="name" name="name" class="form-control"
                        :class="{ 'is-invalid': isSubmitted && $v.userForm.name.$error }" />
                    <div v-if="isSubmitted && !$v.userForm.name.required" class="invalid-feedback">Name field is required</div>
                </div>
                <div class="form-group add-form__group">
                    <label for="name">Description</label>
                    <input type="text" v-model="userForm.description" id="description" name="description" class="form-control"
                        :class="{ 'is-invalid': isSubmitted && $v.userForm.name.$error }" />
                    <div v-if="isSubmitted && !$v.userForm.name.required" class="invalid-feedback">Description field is required</div>
                </div>
                <div class="form-group add-form__btn">
                    <button class="btn btn-danger btn-block">Save</button>
                </div>
            </div>
        </form>

    </div>


</modal>
</template>

<script>

const MODAL_WIDTH = 500;
import {
        required,
        email,
        minLength,
        sameAs
    } from "vuelidate/lib/validators";
export default {
  name:'newCardForm',
  props: ["listId"],
  data: () => ({
    newCard:{},
    form_list_errors:[],
    newCardCount:0,
    userForm: {
        name: "",
        description:""
        },
    isSubmitted: false,
    modalWidth: MODAL_WIDTH
  }),
  validations: {
            userForm: {
                name: {
                    required
                },
                description: {
                    required
                }
            }
        },
   created() {
    this.modalWidth =
      window.innerWidth < MODAL_WIDTH ? MODAL_WIDTH / 2 : MODAL_WIDTH

  },
  methods: {
    handleSubmit() {
                this.isSubmitted = true;
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }
                this.userForm.title=this.userForm.name;
                this.userForm.list_id=this.listId;
                let self =  this;
                axios.post('/api/store-card', this.userForm)
                .then(function (response) {
                  if(response && response.data && response.data.data)
                  {
                    self.newCard =  response.data.data;
                    self.newCardCount++;
                            self.$notify({

                            title: 'Success!',
                            text: 'New card created!',
                            type:'success'
                            });
                  }
                  else
                  {
                      console.log(response.data)
                      self.$notify({

                            title: 'Error!',
                            text: 'Try Again!',
                            type:'success'
                            });

                  }
                }).catch(error=> {
                   if(error && error.response && error.response.data && error.response.data.errors)
                   self.form_list_errors =  error.response.data.errors;
                   else
                   self.$notify({
                            group: 'vue',
                            title: 'Error!',
                            text: 'Try Again!',
                            type:'success'
                            });
                });
            },

  },
};
</script>


