<template>
    <div class="container-fluid  px-4">
        <fieldset class="border p-2 my-2">
            <legend  class="w-auto">Encoded</legend>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Beneficiary Name</th>
                        <th>Address</th>
                        <th>Educational Level</th>
                        <th>Sector</th>
                        <th>Others</th>
                        <th>Amount Granted</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Father's Name</th>
                        <th>Mother's Name</th>
                    </tr>
                </thead>
                <tbody v-for="beneficiary in beneficiaries" :key="beneficiary.key">
                        <tr>
                            <td>{{ beneficiary.composition.client.full_name }}</td>
                            <td>{{ beneficiary.full_name }}</td>
                            <td>
                                <span>{{ beneficiary.composition.client.psgc.brgy_name }}</span>,
                                <span>{{ beneficiary.composition.client.psgc.city_name }}</span>,
                                <span>{{ beneficiary.composition.client.psgc.province_name }}</span>
                            </td>
                            <td>{{ beneficiary.school_level.name }}</td>
                            <td>{{ beneficiary.sector.name }}</td>
                            <td>{{ beneficiary.sector_others }}</td>
                            <td>{{ beneficiary.school_level.amount }}</td>
                            <td>{{ beneficiary.status }}</td>
                            <td>{{ beneficiary.status_date }}</td>
                            <td>
                                <span>{{ beneficiary.composition.father.full_name }}</span>
                            </td>
                            <td>
                                <span>{{ beneficiary.composition.mother.full_name }}</span>
                            </td>
                        </tr>
                    </tbody>
            </table>
        </fieldset>
    </div>
</template>

<script>
import Axios from 'axios';

    export default {
        data(){
            return {
                beneficiaries: [],
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.getBeneficiaries();
        },
        methods: {
            getBeneficiaries(){
                Axios.get(route('beneficiaries.index'))
                .then(res => {
                    this.beneficiaries = res.data.data;
                })
                .catch(err => {})
                .then(res => {})
            }
        }
    }
</script>
