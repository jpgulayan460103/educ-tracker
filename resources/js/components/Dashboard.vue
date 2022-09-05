<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <select class="form-control" v-model="swad_office_id" placeholder="SWAD OFFICE">
                    <option value="">All SWAD Office</option>
                    <option v-for="(swadOffice, key) in allSwadOffices" :key="key" :value="swadOffice.id">{{ swadOffice.name }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" v-model="payout_id" placeholder="PAYOUT DATE">
                    <option value="">All Payout Date</option>
                    <option v-for="(payout, key) in allPayouts" :key="key" :value="payout.id">{{ payout.payout_date }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" v-model="by" placeholder="PAYOUT DATE">
                    <option value="swad">SWAD Office</option>
                    <option value="district">SWAD Office with District</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" type="button" @click="filterDashboard">View</button>
            </div>
        </div>
        <br>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center;" rowspan="2">SWAD Office</th>
                        <th style="text-align: center;" rowspan="2" v-if="by == 'district'">District</th>
                        <th style="text-align: center;" rowspan="2" v-else>Available Cash Balance</th>
                        <th style="text-align: center;" v-for="schoolLevel in schoolLevels" :key="schoolLevel.key" colspan="2">{{ schoolLevel.name }}</th>
                        <th style="text-align: center;" colspan="2">Grand Total</th>
                        <th style="text-align: center;" rowspan="2" v-if="by == 'swad'">Balance</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;" v-for="(tableHeader, key) in tableHeaders" :key="key">{{ tableHeader.label }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(swadOffice, key) in swadOffices">
                        <td style="text-align: center;">{{ swadOffice.name }}</td>
                        <td style="text-align: center;" v-if="by == 'district'">{{ swadOffice.district }}</td>
                        <td style="text-align: right;" v-else>{{ extractCashBalance(swadOffice.id) }}</td>
                        <td v-for="(tableHeader, key) in tableHeaders" :key="key" :class="tableHeader.prop">{{ extractData(tableHeader.prop, tableHeader.school_level_id, tableHeader.format, swadOffice.id, swadOffice.district) }}</td>
                        <th style="text-align: right;" v-if="by == 'swad'">{{ calculateBalance(swadOffice.id) }}</th>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Grand Total</th>
                        <td style="text-align: center;" v-if="by == 'district'"></td>
                        <td style="text-align: right;"  v-else>{{ extractCashBalance() }}</td>
                        <th v-for="(tableHeader, key) in tableHeaders" :key="key" :class="tableHeader.prop">{{ totalData(tableHeader.prop, tableHeader.school_level_id, tableHeader.format) }}</th>
                        <th style="text-align: right;" v-if="by == 'swad'">{{ calculateBalance() }}</th>
                    </tr>
                </tfoot>
            </table>
        </div> 
    </div>
</template>

<script>
import Axios from 'axios';
import isEmpty from 'lodash/isEmpty'
import map from 'lodash/map'
import route from '../../../vendor/tightenco/ziggy/src/js';

    export default {
        props: ['allSwadOffices','allPayouts'],
        data(){
            return {
                schoolLevels: [],
                tableHeaders: [],
                swadReports: [],
                swadOffices: [],
                swad_office_id: "",
                payout_id: "",
                schoolLevelReports: [],
                fundAllocations: [],
                by: "swad",
            }
        },
        async mounted() {
            await this.getSwadOffices();
            await this.getSchoolLevels();
            // await this.getAllocatedReport();
            await this.getReports();
        },
        methods: {
            async getSwadOffices(id = null){
                return Axios.get(route('swad-offices.index'), {
                    params: {
                        id
                    }
                })
                .then(res => {
                    this.swadOffices = res.data;
                })
                .catch(err => {})
                .then(res => {})
            },
            async getSwadOfficeDistricts(id = null){
                return Axios.get(route('swad-offices.districts'), {
                    params: {
                        id
                    }
                })
                .then(res => {
                    this.swadOffices = res.data;
                })
                .catch(err => {})
                .then(res => {})
            },
            async getSchoolLevels(id = null){
                return Axios.get(route('school-levels.index'), {
                    params: {
                        id
                    }
                })
                .then(res => {
                    this.tableHeaders = [];
                    this.schoolLevels = res.data;
                    for (let index = 0; index < this.schoolLevels.length; index++) {
                        const element = this.schoolLevels[index];
                        this.tableHeaders = [
                            ...this.tableHeaders,
                            {
                                label: 'SUM of Amount Granted',
                                prop: 'sum_amount_granted',
                                school_level_id: this.schoolLevels[index].id,
                                format: 'currency',
                            }
                        ];
                        this.tableHeaders = [
                            ...this.tableHeaders,
                            {
                                label: 'COUNT of Beneficiary',
                                prop: 'beneficiary_served',
                                school_level_id: this.schoolLevels[index].id,
                                format: 'float',
                            }
                        ]
                    }
                    this.tableHeaders = [
                        ...this.tableHeaders,
                        {
                            label: 'SUM of Amount Granted',
                            prop: 'sum_amount_granted',
                            format: 'currency',
                        }
                    ];
                    this.tableHeaders = [
                        ...this.tableHeaders,
                        {
                            label: 'COUNT of Beneficiary',
                            prop: 'beneficiary_served',
                            format: 'float',
                        }
                    ]
                })
                .catch(err => {})
                .then(res => {})
            },
            async getReports(payout_id = null, swad_office_id = null){
                return Axios.get(route('report'), {
                    params: {
                        payout_id,
                        swad_office_id,
                        by: this.by
                    }
                })
                .then(res => {
                    // this.schoolLevels = res.data;
                    // this.schoolLevelReports = res.data;
                    // console.log(this.schoolLevelReports);
                    // this.swadReports = map(this.schoolLevelReports, 'swad_offices').flat(1);
                    // this.swadReports = map(this.swadReports, 'swad_offices').flat(1);
                    this.swadReports = res.data.beneficiaries;
                    this.fundAllocations = res.data.fund_allocations;
                })
                .catch(err => {})
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
            async getAllocatedReport() {
                Axios.get(route('report.fund-allocation'))
                .then(res => {
                    this.fundAllocations = res.data;
                })
                .catch(err => {})
                .then(res => {})
                ;
            },
            extractData(prop, school_level_id, format, swad_office_id, swad_office_district = null){
                let value;
                if(school_level_id == null){
                    let swadOfficeReport;
                    if(this.by == "swad"){
                        swadOfficeReport = this.swadReports.filter(item => item.swad_office_id == swad_office_id);
                    }else{
                        swadOfficeReport = this.swadReports.filter(item => item.swad_office_id == swad_office_id && item.district == swad_office_district);
                    }
                    value = swadOfficeReport.reduce((sum, t) => {
                        return sum += parseFloat(t[prop]);
                    }, 0);;
                }else{
                    let swadOfficeReport;
                    if(this.by == "swad"){
                        swadOfficeReport = this.swadReports.filter(item => item.swad_office_id == swad_office_id && item.school_level_id == school_level_id);
                    }else{
                        swadOfficeReport = this.swadReports.filter(item => item.swad_office_id == swad_office_id && item.school_level_id == school_level_id && item.district == swad_office_district);
                    }
                    if(isEmpty(swadOfficeReport)){
                        value = 0;
                    }else{
                        value = parseFloat(swadOfficeReport[0][prop]);
                    }
                }

                if(format == "currency"){
                    return this.formatCurrency(parseFloat(value));
                }else{
                    return parseFloat(value);
                }
            },
            totalData(prop, school_level_id, format){
                let value;
                
                if(school_level_id == null){
                    value = this.swadReports.reduce((sum, t) => {
                        return sum += parseFloat(t[prop]);
                    }, 0);
                }else{
                    let swadReport = this.swadReports.filter(item => item.school_level_id == school_level_id);
                    value = swadReport.reduce((sum, t) => {
                        return sum += parseFloat(t[prop]);
                    }, 0);
                }

                if(format == "currency"){
                    return this.formatCurrency(parseFloat(value));
                }else{
                    return parseFloat(value);
                }
            },
            async filterDashboard(){
                if(this.by == "swad"){
                    await this.getSwadOffices(this.swad_office_id);
                }else{
                    await this.getSwadOfficeDistricts(this.swad_office_id);
                }
                await this.getReports(this.payout_id, this.swad_office_id);
                // await this.getSchoolLevels();
            },

            extractCashBalance(swad_office_id = null){
                let value = 0;
                if(swad_office_id == null){
                    value = this.fundAllocations.reduce((sum, t) => {
                        return sum += parseFloat(t.total_allocated_amount);
                    }, 0);
                }else{
                    let fundAllocation = this.fundAllocations.filter(item => item.swad_office_id == swad_office_id);
                    if(isEmpty(fundAllocation)){
                        value = 0;
                    }else{
                        value = fundAllocation[0].total_allocated_amount;
                    }
                }

                return this.formatCurrency(value);
            },

            calculateBalance(swad_office_id = null){
                let total_fund_allocation = 0;
                let total_amount_granted = 0;
                let balance = 0;
                if(swad_office_id == null){
                    total_fund_allocation = this.fundAllocations.reduce((sum, t) => {
                        return sum += parseFloat(t.total_allocated_amount);
                    }, 0);
                    total_amount_granted = this.swadReports.reduce((sum, t) => {
                        return sum += parseFloat(t.sum_amount_granted);
                    }, 0);
                }else{
                    let fundAllocation = this.fundAllocations.filter(item => item.swad_office_id == swad_office_id);
                    if(isEmpty(fundAllocation)){
                        total_fund_allocation = 0;
                    }else{
                        total_fund_allocation = fundAllocation[0].total_allocated_amount;
                    }

                    let swadReport = this.swadReports.filter(item => item.swad_office_id == swad_office_id);
                    if(isEmpty(swadReport)){
                        total_amount_granted = 0;
                    }else{
                        total_amount_granted = swadReport.reduce((sum, t) => {
                            return sum += parseFloat(t.sum_amount_granted);
                        }, 0);;
                    }
                }

                return this.formatCurrency(total_fund_allocation - total_amount_granted);
            }
        }
    }
</script>

<style scoped>
.sum_amount_granted{
    text-align: right;
}
.beneficiary_served{
    text-align: center;
}
.total_amount_granted{
    text-align: right;
}
.total_beneficiaries_served{
    text-align: center;
}
</style>
