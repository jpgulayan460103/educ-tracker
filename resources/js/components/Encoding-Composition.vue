<template>
    <div class="container-fluid  px-4">
        <form @submit.prevent="formSubmit" id="server-form">

            <fieldset class="border p-2 my-2">
                <legend  class="w-auto">Client's Information</legend>
                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Last Name" v-model="formData.client.last_name">
                            <span style="color:red" v-if="formError['client.last_name']">{{ formError['client.last_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" class="form-control" placeholder="Enter First Name" v-model="formData.client.first_name">
                            <span style="color:red" v-if="formError['client.first_name']">{{ formError['client.first_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">Middle Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.client.middle_name">
                            <span style="color:red" v-if="formError['client.middle_name']">{{ formError['client.middle_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ext_name">Ext Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Ext Name" v-model="formData.client.ext_name">
                            <span style="color:red" v-if="formError['client.ext_name']">{{ formError['client.middle_name'][0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="row gx-2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cellphone_number">Cellphone Number:</label>
                            <input type="text" class="form-control" placeholder="Enter Cellphone Number" v-model="formData.client.cellphone_number">
                            <span style="color:red" v-if="formError['client.cellphone_number']">{{ formError['client.cellphone_number'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="relationship_beneficiaries">Relationship to the beneficiaries:</label>
                            <input type="text" class="form-control" placeholder="Enter Relationship to the beneficiaries" v-model="formData.client.relationship_beneficiaries">
                            <span style="color:red" v-if="formError['client.relationship_beneficiaries']">{{ formError['client.relationship_beneficiaries'][0] }}</span>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border p-2 my-2">
                <legend  class="w-auto">Father's Information</legend>

                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="last_name">Father Last Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Last Name" v-model="formData.father.last_name">
                            <span style="color:red" v-if="formError['father.last_name']">{{ formError['father.last_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">Father First Name:</label>
                            <input type="text" class="form-control" placeholder="Enter First Name" v-model="formData.father.first_name">
                            <span style="color:red" v-if="formError['father.first_name']">{{ formError['father.first_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">Father Middle Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.father.middle_name">
                            <span style="color:red" v-if="formError['father.middle_name']">{{ formError['father.middle_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ext_name">Father Ext Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Ext Name" v-model="formData.father.ext_name">
                            <span style="color:red" v-if="formError['father.ext_name']">{{ formError['father.middle_name'][0] }}</span>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="border p-2 my-2">
                <legend  class="w-auto">Mother's Information</legend>
                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="last_name">Mother Last Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Last Name" v-model="formData.mother.last_name">
                            <span style="color:red" v-if="formError['mother.last_name']">{{ formError['mother.last_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">Mother First Name:</label>
                            <input type="text" class="form-control" placeholder="Enter First Name" v-model="formData.mother.first_name">
                            <span style="color:red" v-if="formError['mother.first_name']">{{ formError['mother.first_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">Mother Middle Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.mother.middle_name">
                            <span style="color:red" v-if="formError['mother.middle_name']">{{ formError['mother.middle_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ext_name">Mother Ext Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Ext Name" v-model="formData.mother.ext_name">
                            <span style="color:red" v-if="formError['mother.ext_name']">{{ formError['mother.middle_name'][0] }}</span>
                        </div>
                    </div>
                </div>
            </fieldset>
            


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>

<script>
import Axios from 'axios';

    export default {
        data(){
            return {
                formData:{
                    client: {},
                    father: {},
                    mother: {},
                    beneficiaries: [],
                },
                formError:{
                    client: {},
                    father: {},
                    mother: {},
                    beneficiaries: [],
                },
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            formSubmit(){
                Axios.post(route('family-composition.store'))
                .then(res => {

                })
                .catch(err => {
                    this.formError = err.response.data.errors;
                })
                .then(res => {})
                ;
            }
        }
    }
</script>
