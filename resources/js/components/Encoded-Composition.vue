<template>
    <div class="container-fluid  px-4">
        <fieldset class="border p-2 my-2">
            <legend  class="w-auto">Encoded Beneficiaries</legend>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search" v-model="keyword">
                </div>
                <div class="col-md-2">
                    <select class="form-control" placeholder="Search" v-model="type">
                        <option value="client">Client Name</option>
                        <option value="beneficiary">Beneficiary Name</option>
                        <option value="father">Father Name</option>
                        <option value="mother">Mother Name</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary" @click="getBeneficiaries">Search</button>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>SWAD Office</th>
                            <th>Client Name</th>
                            <th>Beneficiary Name</th>
                            <th>Address</th>
                            <th>Educational Level</th>
                            <th>Amount Granted</th>
                            <th>Status</th>
                            <th>Status Date</th>
                            <th>Remarks</th>
                            <th>Father's Name</th>
                            <th>Mother's Name</th>
                            <th>Encoded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody v-for="beneficiary in beneficiaries" :key="beneficiary.key">
                            <tr>
                                <td>{{ beneficiary.swad_office.name }}</td>
                                <td>{{ beneficiary.composition.client.full_name }}</td>
                                <td>{{ beneficiary.full_name }}</td>
                                <td>
                                    <span>{{ beneficiary.composition.client.psgc.brgy_name }}</span>,
                                    <span>{{ beneficiary.composition.client.psgc.city_name }}</span>,
                                    <span>{{ beneficiary.composition.client.psgc.province_name }}</span>
                                </td>
                                <td>{{ beneficiary.school_level.name }}</td>
                                <td>{{ beneficiary.status == "Claimed" ? beneficiary.school_level.amount : 0 }}</td>
                                <td>{{ beneficiary.status }}</td>
                                <td>{{ beneficiary.payout ? beneficiary.payout.payout_date : "" }}</td>
                                <td>{{ beneficiary.remarks }}</td>
                                <td>
                                    <span>{{ beneficiary.composition.father.full_name }}</span>
                                </td>
                                <td>
                                    <span>{{ beneficiary.composition.mother.full_name }}</span>
                                </td>
                                <td>{{ beneficiary.composition.user.full_name }}</td>
                                <td>
                                    <a href="#" @click="viewBeneficiary(beneficiary)">View</a>
                                </td>
                            </tr>
                        </tbody>
                </table>
            </div>
        <pagination v-model="pagination.current_page" :records="pagination.total" :per-page="pagination.per_page" @paginate="getBeneficiaries"/>
        </fieldset>
    </div>
</template>

<script>
import Axios from 'axios';
import Pagination from 'vue-pagination-2';
    export default {
        components: {
            Pagination
        },
        data(){
            return {
                beneficiaries: [],
                keyword: "",
                type: "client",
                pagination: {
                    current_page: 1,
                    total: 1,
                    per_page: 1,
                },
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.getBeneficiaries();
        },
        methods: {
            getBeneficiaries(page = null){
                Axios.get(route('beneficiaries.index'), {
                    params: {
                        keyword: this.keyword,
                        type: this.type,
                        page,
                    }
                })
                .then(res => {
                    this.beneficiaries = res.data.data;
                    this.pagination = res.data.meta.pagination;
                })
                .catch(err => {})
                .then(res => {})
            },
            viewBeneficiary(beneficiary){
                window.open(route('encoding', beneficiary.composition.uuid),
                    'newwindow',
                    'location=yes,width=960,height=1080,scrollbars=yes,status=yes');
                return false; 
            }
        }
    }
</script>
