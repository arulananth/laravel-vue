<template>
<modal name="new-list-form" transition="pop-out" :width="modalWidth" :focus-trap="true" :height="400">

        <div class="container" style="max-width: 500px; text-align: left">
        <div class="alert alert-success" role="alert">
            <h2 class="alert-heading new-card-form__add">Add Column</h2>
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
import axios from "axios";
export default {
  name:'newListForm',
  props: [],
  data: () => ({
    newList:{},
    newListCount:0,
    form_list_errors:[],
    userForm: {
        name: ""

        },
    isSubmitted: false,
    modalWidth: MODAL_WIDTH
  }),
  validations: {
            userForm: {
                name: {
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
                let self=this;
                axios.post('/api/store-col', {title:this.userForm.name})
                .then(function (response) {
                  if(response && response.data && response.data.data)
                  {
                      self.newListCount++;
                      self.newList = response.data.data;
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
                })
                .catch(function (error) {
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

    }

  },
};
</script>

<style lang="scss">
$background_color: #404142;
$github_color: #dba226;
$facebook_color: #3880ff;
.box {
  background: white;
  overflow: hidden;
  width: 656px;
  height: 400px;
  border-radius: 2px;
  box-sizing: border-box;
  box-shadow: 0 0 40px black;
  color: #8b8c8d;
  font-size: 0;
  .box-part {
    display: inline-block;
    position: relative;
    vertical-align: top;
    box-sizing: border-box;
    height: 100%;
    width: 50%;
    &#bp-right {
      background: url('/static/panorama.jpg') no-repeat top left;
      border-left: 1px solid #eee;
    }
  }
  .box-messages {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
  }
  .box-error-message {
    position: relative;
    overflow: hidden;
    box-sizing: border-box;
    height: 0;
    line-height: 32px;
    padding: 0 12px;
    text-align: center;
    width: 100%;
    font-size: 11px;
    color: white;
    background: #f38181;
  }
  .partition {
    width: 100%;
    height: 100%;
    .partition-title {
      box-sizing: border-box;
      padding: 30px;
      width: 100%;
      text-align: center;
      letter-spacing: 1px;
      font-size: 20px;
      font-weight: 300;
    }
    .partition-form {
      padding: 0 20px;
      box-sizing: border-box;
    }
  }
  input[type='password'],
  input[type='text'] {
    display: block;
    box-sizing: border-box;
    margin-bottom: 4px;
    width: 100%;
    font-size: 12px;
    line-height: 2;
    border: 0;
    border-bottom: 1px solid #dddedf;
    padding: 4px 8px;
    font-family: inherit;
    transition: 0.5s all;
  }
  button {
    background: white;
    border-radius: 4px;
    box-sizing: border-box;
    padding: 10px;
    letter-spacing: 1px;
    font-family: 'Open Sans', sans-serif;
    font-weight: 400;
    min-width: 140px;
    margin-top: 8px;
    color: #8b8c8d;
    cursor: pointer;
    border: 1px solid #dddedf;
    text-transform: uppercase;
    transition: 0.1s all;
    font-size: 10px;
    &:hover {
      border-color: mix(#dddedf, black, 90%);
      color: mix(#8b8c8d, black, 80%);
    }
  }
  .large-btn {
    width: 100%;
    background: white;
    span {
      font-weight: 600;
    }
    &:hover {
      color: white !important;
    }
  }
  .button-set {
    margin-bottom: 8px;
  }
  #register-btn,
  #signin-btn {
    margin-left: 8px;
  }
  .facebook-btn {
    border-color: $facebook_color;
    color: $facebook_color;
    &:hover {
      border-color: $facebook_color;
      background: $facebook_color;
    }
  }
  .github-btn {
    border-color: $github_color;
    color: $github_color;
    &:hover {
      border-color: $github_color;
      background: $github_color;
    }
  }
  .autocomplete-fix {
    position: absolute;
    visibility: hidden;
    overflow: hidden;
    opacity: 0;
    width: 0;
    height: 0;
    left: 0;
    top: 0;
  }
}
.pop-out-enter-active,
.pop-out-leave-active {
  transition: all 0.5s;
}
.pop-out-enter,
.pop-out-leave-active {
  opacity: 0;
  transform: translateY(24px);
}
</style>
