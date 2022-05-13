<template>
<div v-if="validationErrors">
                        <ul class="alert-danger">
                            <li class="invalid-feedback" v-for="(value, key, index) in validationErrors" :key="index">{{ value }}</li>
                        </ul>
                    </div>
</template>
<script>
export default {
         props: ['errors'],
         data(){
            return {

            }
        },
        computed: {
                validationErrors(){
                    let errors = Object.values(this.errors);
                    errors = errors.flat();
                    return errors;
                }
        }
 }

</script>
