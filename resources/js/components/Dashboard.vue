<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <select class="form-control" v-model="swad_office_id" placeholder="SWAD OFFICE">
                    <option value="">All SWAD Office</option>
                    <option v-for="(swadOffice, key) in allSwadOffices" :key="key" :value="swadOffice.id">{{ swadOffice.name }}</option>
                </select>
            </div>
            <div class="col-2">
                <select class="form-control" v-model="payout_id" placeholder="PAYOUT DATE">
                    <option value="">All Payout Date</option>
                    <option v-for="(payout, key) in allPayouts" :key="key" :value="payout.id">{{ payout.payout_date }}</option>
                </select>
            </div>
            <div class="col-2">
                <button class="btn btn-primary" type="button" @click="filterDashboard">View</button>
            </div>
        </div>
        <br>
        <br>
        <div class="table-responsive">
            <!-- <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center;" rowspan="2">Education Level</th>
                        <th style="text-align: center;" v-for="swadOffice in swadOffices" :key="swadOffice.key" colspan="2">{{ swadOffice.name }}</th>
                        <th style="text-align: center;" colspan="2">Grand Total</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;" v-for="(tableHeader, key) in tableHeaders" :key="key">{{ tableHeader.label }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(schoolLevel, key) in schoolLevels">
                        <td>{{ schoolLevel.name }}</td>
                        <td v-for="(tableHeader, key) in tableHeaders" :key="key" :class="tableHeader.prop">{{ extractData(tableHeader.prop, tableHeader.swad_office_id, tableHeader.format, schoolLevel.id) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Grand Total</th>
                        <th v-for="(tableHeader, key) in tableHeaders" :key="key" :class="tableHeader.prop">{{ totalData(tableHeader.prop, tableHeader.swad_office_id, tableHeader.format) }}</th>
                    </tr>
                </tfoot>
            </table> -->

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center;" rowspan="2">SWAD Office</th>
                        <th style="text-align: center;" rowspan="2">Available Cash Balance</th>
                        <th style="text-align: center;" v-for="schoolLevel in schoolLevels" :key="schoolLevel.key" colspan="2">{{ schoolLevel.name }}</th>
                        <th style="text-align: center;" colspan="2">Grand Total</th>
                        <th style="text-align: center;" rowspan="2">Balance</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;" v-for="(tableHeader, key) in tableHeaders" :key="key">{{ tableHeader.label }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(swadOffice, key) in swadOffices">
                        <td>{{ swadOffice.name }}</td>
                        <td style="text-align: right;">{{ extractCashBalance(swadOffice.id) }}</td>
                        <td v-for="(tableHeader, key) in tableHeaders" :key="key" :class="tableHeader.prop">{{ extractData(tableHeader.prop, tableHeader.school_level_id, tableHeader.format, swadOffice.id) }}</td>
                        <th style="text-align: right;">{{ calculateBalance(swadOffice.id) }}</th>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Grand Total</th>
                        <td style="text-align: right;">{{ extractCashBalance() }}</td>
                        <th v-for="(tableHeader, key) in tableHeaders" :key="key" :class="tableHeader.prop">{{ totalData(tableHeader.prop, tableHeader.school_level_id, tableHeader.format) }}</th>
                        <th style="text-align: right;">{{ calculateBalance() }}</th>
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
                    // this.tableHeaders = [];
                    this.swadOffices = res.data;
                    // for (let index = 0; index < this.swadOffices.length; index++) {
                    //     const element = this.swadOffices[index];
                    //     this.tableHeaders = [
                    //         ...this.tableHeaders,
                    //         {
                    //             label: 'SUM of Amount Granted',
                    //             prop: 'sum_amount_granted',
                    //             swad_office_id: this.swadOffices[index].id,
                    //             format: 'currency',
                    //         }
                    //     ];
                    //     this.tableHeaders = [
                    //         ...this.tableHeaders,
                    //         {
                    //             label: 'COUNT of Beneficiary',
                    //             prop: 'beneficiary_served',
                    //             swad_office_id: this.swadOffices[index].id,
                    //             format: 'float',
                    //         }
                    //     ]
                    // }
                    // this.tableHeaders = [
                    //     ...this.tableHeaders,
                    //     {
                    //         label: 'SUM of Amount Granted',
                    //         prop: 'total_amount_granted',
                    //         format: 'currency',
                    //     }
                    // ];
                    // this.tableHeaders = [
                    //     ...this.tableHeaders,
                    //     {
                    //         label: 'COUNT of Beneficiary',
                    //         prop: 'total_beneficiaries_served',
                    //         format: 'float',
                    //     }
                    // ]
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
                        swad_office_id
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
            extractData(prop, school_level_id, format, swad_office_id){
                let value;
                if(school_level_id == null){
                    let swadOfficeReport = this.swadReports.filter(item => item.swad_office_id == swad_office_id);
                    value = swadOfficeReport.reduce((sum, t) => {
                        return sum += parseFloat(t[prop]);
                    }, 0);;
                }else{
                    let swadOfficeReport = this.swadReports.filter(item => item.swad_office_id == swad_office_id && item.school_level_id == school_level_id);
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
                await this.getSwadOffices(this.swad_office_id);
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
