<template>
    <div class="container-fluid  px-4">
        <form @submit.prevent="formSubmit" id="server-form">
            <fieldset class="border p-2 my-2">
                <legend  class="w-auto">Add Allocations</legend>
                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="payout_id">Payout Date:</label>
                            <select class="form-control" placeholder="Enter Last Name" v-model="formData.payout_id">
                                <option v-for="(payout, key) in payouts" :key="key" :value="payout.id">{{ payout.payout_date }}</option>
                            </select>
                            <span style="color:red" v-if="formError['payout_id']">{{ formError['payout_id'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="swad_office_id">Swad Office:</label>
                            <select class="form-control" placeholder="Enter Swad Office" v-model="formData.swad_office_id">
                                <option v-for="(swadOffice, key) in swadOffices" :key="key" :value="swadOffice.id">{{ swadOffice.name }}</option>
                            </select>
                            <span style="color:red" v-if="formError['swad_office_id']">{{ formError['swad_office_id'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="school_level_id">School Level:</label>
                            <select class="form-control" placeholder="Enter Swad Office" v-model="formData.school_level_id">
                                <option v-for="(schoolLevel, key) in schoolLevels" :key="key" :value="schoolLevel.id">{{ schoolLevel.name }}</option>
                            </select>
                            <span style="color:red" v-if="formError['school_level_id']">{{ formError['school_level_id'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="allocated_amount">Amount:</label>
                            <input type="number" min="0" class="form-control" placeholder="Enter Amount" v-model="formData.allocated_amount">
                            <span style="color:red" v-if="formError['allocated_amount']">{{ formError['allocated_amount'][0] }}</span>
                        </div>
                    </div>
                    
                </div>
                <br>
                <button type="submit" class="btn btn-primary" :disabled="submit">Submit</button>
            </fieldset>

             <fieldset class="border p-2 my-2">
            <legend  class="w-auto">Users</legend>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payout Date</th>
                            <th>SWAD Office</th>
                            <th>School Level</th>
                            <th>Amount</th>
                            <th>Added by</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(allocation, key) in allocations" :key="key">
                            <td>{{ allocation.payout.payout_date }}</td>
                            <td>{{ allocation.swad_office.name }}</td>
                            <td>{{ allocation.school_level.name }}</td>
                            <td>{{ formatCurrency(allocation.allocated_amount) }}</td>
                            <td>{{ allocation.user.full_name }}</td>
                            <td>
                                <a href="#" @click="editAllocation(allocation)">Edit</a> |
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </fieldset>
        </form>
        
    </div>
</template>

<script>
import Axios from 'axios';
import uniqBy from 'lodash/uniqBy'
import isEmpty from 'lodash/isEmpty'
import debounce from 'lodash/debounce'
    export default {
        props: [
            'payouts',
            'swadOffices',
            'schoolLevels'
        ],
        data(){
            return {
                formData: {},
                formError: {},
                submit: false,
                allocations: [],
                formType: "create",
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.getAllocations();
        },
        methods: {
            formSubmit: debounce(function(params) {
                if(this.formType == "update"){
                    this.updateAllocation();
                }else{
                    this.createAllocation();
                }
            }, 500),

            createAllocation(){
                this.submit = true;
                this.formError = {};
                Axios.post(route('fund-allocations.store'), this.formData)
                .then(res => {
                    this.submit = false;
                    this.getAllocations();
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
                .then(res => {})
                ;
            },

            updateAllocation(){
                this.submit = true;
                this.formError = {};
                Axios.put(route('fund-allocations.update', this.formData.id), this.formData)
                .then(res => {
                    this.submit = false;
                    this.getAllocations();
                    alert("Successfuly saved.");
                    this.formData = {};
                    this.formError = {};
                    this.formType = "create";
                })
                .catch(err => {
                    this.submit = false;
                    if(err.response.status == 422){
                        this.formError = err.response.data.errors;
                        alert("Please review submitted form.");
                    }
                })
                .then(res => {})
                ;
            },

            getAllocations(){
                Axios.get(route('fund-allocations.index'))
                .then(res => {
                    this.allocations = res.data;
                })
                .catch(res => {})
                .then(res => {})
            },

            formatCurrency(value){
                if (typeof value !== "number") {
                    return value;
                }
                var formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                });
                return formatter.format(value).replace('$', '');
            },

            editAllocation(allocation){
                this.formType = "update";
                this.formData = allocation;
            }
        }
    }
</script>
