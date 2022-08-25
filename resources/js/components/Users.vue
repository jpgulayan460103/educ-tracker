<template>
    <div class="container-fluid  px-4">
        <form @submit.prevent="formSubmit" id="server-form">
            <fieldset class="border p-2 my-2">
                <legend  class="w-auto">Add Users</legend>
                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Last Name" v-model="formData.last_name">
                            <span style="color:red" v-if="formError['last_name']">{{ formError['last_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" class="form-control" placeholder="Enter First Name" v-model="formData.first_name">
                            <span style="color:red" v-if="formError['first_name']">{{ formError['first_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">Middle Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.middle_name">
                            <span style="color:red" v-if="formError['middle_name']">{{ formError['middle_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ext_name">Ext Name:</label>
                            <select  class="form-control" placeholder="Enter Ext Name" v-model="formData.ext_name">
                                <option value="">NONE</option>
                                <option value="JR">JR</option>
                                <option value="SR">SR</option>
                                <option value="I">I</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                                <option value="VI">VI</option>
                            </select>
                            <span style="color:red" v-if="formError['ext_name']">{{ formError['ext_name'][0] }}</span>
                        </div>
                    </div>

                    <div class="row gx-2">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="username">Username/ID Number:</label>
                                <input type="text" class="form-control" placeholder="Enter ID Number" v-model="formData.username">
                                <span style="color:red" v-if="formError['username']">{{ formError['username'][0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="user_role">Role:</label>
                                <!-- <input type="text" class="form-control" placeholder="Enter ID Number" v-model="formData.user_role"> -->
                                <select class="form-control" placeholder="Enter Role" v-model="formData.user_role">
                                    <option value="Admin">Admin</option>
                                    <option value="Encoder">Encoder</option>
                                </select>
                                <span style="color:red" v-if="formError['user_role']">{{ formError['user_role'][0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="text" class="form-control" placeholder="Enter Password" v-model="formData.password">
                                <span style="color:red" v-if="formError['password']">{{ formError['password'][0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="user_role">SWAD Office:</label>
                                <select  class="form-control" placeholder="Enter SWAD Office" v-model="formData.swad_office_id">
                                    <option v-for="(swadOffice, key) in swadOffices" :key="key" :value="swadOffice.id">{{ swadOffice.name }}</option>
                                </select>
                                <span style="color:red" v-if="formError['swad_office_id']">{{ formError['swad_office_id'][0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" :disabled="submit">Submit</button>
            </fieldset>
        </form>
        <fieldset class="border p-2 my-2">
            <legend  class="w-auto">Users</legend>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Username/ID Number</th>
                            <th>FullName</th>
                            <th>Role</th>
                            <th>SWAD Office</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.key">
                            <td>{{ user.username }}</td>
                            <td>{{ user.full_name }}</td>
                            <td>{{ user.user_role }}</td>
                            <td>{{ user.swad_office ? user.swad_office.name : "" }}</td>
                            <td>
                                <a href="#" @click="editUser(user)">Edit</a> |
                                <a href="#" @click="resetPassword(user)">Reset Password</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</template>

<script>
import Axios from 'axios';
import uniqBy from 'lodash/uniqBy'
import isEmpty from 'lodash/isEmpty'
import debounce from 'lodash/debounce'
    export default {
        props: [
            'swadOffices'
        ],
        data(){
            return {
                formData: {
                    password: "dswd12345",
                },
                formError: {},
                submit: false,
                users: [],
                formType: "create",
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.getUsers();
        },
        methods: {
            formSubmit: debounce(function(){
                if(this.formType == "update"){
                    this.updateUser();
                }else{
                    this.createUser();
                }
            }, 500),

            createUser(){
                this.submit = true;
                this.formError = {}
                Axios.post(route('users.store'), this.formData)
                .then(res => {
                    this.submit = false;
                    this.getUsers();
                    alert("Successfuly saved.");
                    this.formData = {};
                    this.formError = {}
                })
                .catch(err => {
                    this.submit = false;
                    if(err.response.status == 422){
                        this.formError = err.response.data.errors;
                        alert("Please review submitted form.");
                    }
                })
                .then(res => {
                    this.submit = false;
                });
            },

            updateUser(){
                this.submit = true;
                this.formError = {}
                Axios.put(route('users.update', this.formData.id), this.formData)
                .then(res => {
                    this.submit = false;
                    this.getUsers();
                    alert("Successfuly saved.");
                    this.formData = {};
                    this.formError = {}
                })
                .catch(err => {
                    this.submit = false;
                    if(err.response.status == 422){
                        this.formError = err.response.data.errors;
                        alert("Please review submitted form.");
                    }
                })
                .then(res => {
                    this.submit = false;
                });
            },

            getUsers(){
                Axios.get(route('users.index'))
                .then(res => {
                    this.users = res.data.data;
                })
                .catch(res => {})
                .then(res => {})
            },

            editUser(user){
                this.formData = user;
                this.formType = "update";
            },

            resetPassword(user){
                Axios.put(route('users.resetPassword', user.id), {
                    password: "dswd12345",
                    password_confirmation: "dswd12345",
                })
                .then(res => {
                    this.getUsers();
                    alert("Successfuly reset to default password: dswd12345");
                })
                .catch(err => {

                })
                .then(res => {

                });
            }
        }
    }
</script>
