<template>
    <div>
        <form @submit.prevent="formSubmit" id="server-form">
            <div class="row gx-2">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" placeholder="Enter Password" v-model="formData.password">
                        <span style="color:red" v-if="formError['password']"><span v-html="formError['password'][0]"></span></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password:</label>
                        <input type="password" class="form-control" placeholder="Enter Password" v-model="formData.password_confirmation">
                        <span style="color:red" v-if="formError['password_confirmation']"><span v-html="formError['password_confirmation'][0]"></span></span>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" :disabled="submit">Submit</button>
        </form>
    </div>
</template>

<script>
import Axios from 'axios';

    export default {
        props: ['user'],
        data(){
            return {
                formData: {},
                formError: {},
                submit: false,
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            formSubmit(){
                Axios.put(route('users.resetPassword', this.user.id), this.formData)
                .then(res => {
                    alert("Successfuly changed password");
                })
                .catch(err => {
                    if(err.response.status == 422){
                        this.formError = err.response.data.errors;
                    }
                })
                .then(res => {

                });
            }
        }
    }
</script>
