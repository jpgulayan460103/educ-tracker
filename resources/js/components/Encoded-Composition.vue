<template>
    <div class="container-fluid  px-4">
        <fieldset class="border p-2 my-2">
            <legend  class="w-auto">Encoded Beneficiaries</legend>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search" v-model="keyword" v-if="type != 'encoded_date' && type != 'status'">
                    <!-- <input type="text" class="form-control" placeholder="Search" v-model="keyword" v-else> -->
                    <!-- <select class="form-control" placeholder="Enter School Level" v-model="keyword" v-else>
                        <option v-for="(payout, key) in payouts.filter(item => item.is_active == 1)" :key="key" :value="payout.id">{{ payout.payout_date }}</option>
                    </select> -->
                    <date-picker v-model="keyword" format="MM/DD/YYYY" type="date" value-type="YYYY-MM-DD" style="width: 100%;" placeholder="MM/DD/YYYY"  v-if="type == 'encoded_date'"></date-picker>
                    <select class="form-control" placeholder="Enter School Level" v-model="keyword" v-if="type == 'status'">
                        <option value="Claimed">Claimed</option>
                        <option value="For Scheduled Payout">For Scheduled Payout</option>
                        <option value="No Requirements">No Requirements</option>
                        <option value="Not Eligible">Not Eligible</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" placeholder="Search" v-model="type">
                        <option value="control_number">Control Number</option>
                        <option value="beneficiary">Beneficiary Name</option>
                        <option value="encoded_date">Encoded Date</option>
                        <option value="status">Status</option>
                        <option value="client">Client Name</option>
                        <option value="father">Father Name</option>
                        <option value="mother">Mother Name</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 d-md-block">
                        <button type="button" class="btn btn-primary btn-block" @click="getBeneficiaries">Search</button>
                        <button type="button" class="btn btn-warning btn-block" @click="exportBeneficiaries" :disabled="exporting">Download {{ exportPercentage }}</button>
                    </div>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>SWAD Office</th>
                            <th>Control No.</th>
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
                            <th>Date Encoded</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody v-for="beneficiary in beneficiaries" :key="beneficiary.key">
                            <tr>
                                <td>{{ beneficiary.swad_office && beneficiary.swad_office.name }}</td>
                                <td>{{ beneficiary.control_number }}</td>
                                <td>{{ beneficiary.composition && beneficiary.composition.client && beneficiary.composition.client.full_name }}</td>
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
                                    <span>{{ beneficiary.composition && beneficiary.composition.father && beneficiary.composition.father.full_name }}</span>
                                </td>
                                <td>
                                    <span>{{ beneficiary.composition && beneficiary.composition.mother && beneficiary.composition.mother.full_name }}</span>
                                </td>
                                <td>{{ beneficiary.composition && beneficiary.composition.user && beneficiary.composition.user.full_name }}</td>
                                <td>{{ beneficiary.created_at }}</td>
                                <td>
                                    <a href="#" @click="viewBeneficiary(beneficiary)">View</a>
                                </td>
                            </tr>
                        </tbody>
                </table>
            </div>
        <pagination
            v-model="pagination.current_page"
            :records="pagination.total"
            :per-page="pagination.per_page"
            @paginate="getBeneficiaries"
            :options="{
                edgeNavigation: true,
            }"
        />
        </fieldset>
    </div>
</template>

<script>
import Axios from 'axios';
import cloneDeep from 'lodash/cloneDeep'
import debounce from 'lodash/debounce'
import Pagination from 'vue-pagination-2';
import DatePicker from 'vue2-datepicker';

    export default {
        props: ['payouts'],
        components: {
            Pagination,
            DatePicker
        },
        data(){
            return {
                beneficiaries: [],
                keyword: "",
                type: "control_number",
                pagination: {
                    current_page: 1,
                    total: 1,
                    per_page: 1,
                },
                exporting: false,
                exportedPage: 1,
                exportData: {},
                exportedFilename: "",
                exportPagination: {
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
            getBeneficiaries: debounce(function(page = null){
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
            }, 250),
            viewBeneficiary(beneficiary){
                window.open(route('encoding', beneficiary.composition.uuid),
                    'newwindow',
                    'location=yes,width=960,height=1080,scrollbars=yes,status=yes');
                return false; 
            },

            exportBeneficiaries(){
                if(this.exporting){
                    return false;
                }
                this.exporting = true;
                this.exportedPage = 1;
                this.exportData = cloneDeep({
                    keyword: this.keyword,
                    type: this.type,
                    export: 1,
                });
                this.createExport();
            },
            createExport(){
                Axios.post(route('export.beneficiary', 'create'), this.exportData)
                .then(res => {
                    this.exportedFilename = res.data.filename;
                    this.exportPagination = cloneDeep(res.data.pagination);
                    this.writeExport(this.exportedPage);
                })
                .catch(err => {
                })
                .then(err => {
                    // this.submit = false;
                });
            },
            
            writeExport(page){
                if (this.exportedPage > this.exportPagination.total_pages || this.exporting == false) {
                    this.downloadExportedFile();
                    return false;
                }
                this.exportData.page = page;
                this.exportData.filename = this.exportedFilename;
                Axios.post(route('export.beneficiary', 'write'), this.exportData)
                .then(res => {
                    this.exportedPage = parseInt(res.data.page);
                    this.exportedPage++;
                    this.writeExport(this.exportedPage);
                })
                .catch(err => {
                    this.writeExport(this.exportedPage);
                })
                .then(err => {
                    // this.submit = false;
                });
            },
            downloadExportedFile(){
                window.location = '/files/exported/'+this.exportedFilename + '.csv';
                this.exporting = false;
            },
        },
        computed: {
            exportPercentage(){
                if(this.exporting){
                    return (((this.exportedPage-1) / this.exportPagination.total_pages) * 100).toFixed(2) + "%";
                }
                return '';
            },
        }
    }
</script>
