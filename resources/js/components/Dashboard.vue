<template>
    <div class="container-fluid">
        <div class="table-responsive">
            <select v-model="swad_office_id" placeholder="SWAD OFFICE">
                <option value="">All SWAD Office</option>
                <option v-for="(swadOffice, key) in allSwadOffices" :key="key" :value="swadOffice.id">{{ swadOffice.name }}</option>
            </select>
            <select v-model="payout_id" placeholder="PAYOUT DATE">
                <option value="">All Payout Date</option>
                <option v-for="(payout, key) in allPayouts" :key="key" :value="payout.id">{{ payout.payout_date }}</option>
            </select>
            <button type="button" @click="filterDashboard">View</button>
            <table class="table table-bordered">
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
            </table>
        </div> 
    </div>
</template>

<script>
import Axios from 'axios';
import isEmpty from 'lodash/isEmpty'
import map from 'lodash/map'

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
            }
        },
        async mounted() {
            await this.getSwadOffices();
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
                    this.tableHeaders = [];
                    this.swadOffices = res.data;
                    for (let index = 0; index < this.swadOffices.length; index++) {
                        const element = this.swadOffices[index];
                        this.tableHeaders = [
                            ...this.tableHeaders,
                            {
                                label: 'SUM of Amount Granted',
                                prop: 'sum_amount_granted',
                                swad_office_id: this.swadOffices[index].id,
                                format: 'currency',
                            }
                        ];
                        this.tableHeaders = [
                            ...this.tableHeaders,
                            {
                                label: 'COUNT of Beneficiary',
                                prop: 'beneficiary_served',
                                swad_office_id: this.swadOffices[index].id,
                                format: 'float',
                            }
                        ]
                    }
                    this.tableHeaders = [
                        ...this.tableHeaders,
                        {
                            label: 'SUM of Amount Granted',
                            prop: 'total_amount_granted',
                            format: 'currency',
                        }
                    ];
                    this.tableHeaders = [
                        ...this.tableHeaders,
                        {
                            label: 'COUNT of Beneficiary',
                            prop: 'total_beneficiaries_served',
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
                    this.schoolLevels = res.data;
                    this.swadReports = map(this.schoolLevels, 'swad_offices').flat(1);
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
            extractData(prop, swad_office_id, format, school_level_id){
                let schoolLevel = this.schoolLevels.filter(item => item.id == school_level_id);
                let value;
                if(swad_office_id == null){
                    value = schoolLevel[0][prop];
                }else{
                    let swadOfficeReport = schoolLevel[0].swad_offices.filter(item => item.swad_office_id == swad_office_id);
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
            totalData(prop, swad_office_id, format){
                let value;
                
                if(swad_office_id == null){
                    value = this.schoolLevels.reduce((sum, t) => {
                        return sum += parseFloat(t[prop]);
                    }, 0);
                }else{
                    let swadReport = this.swadReports.filter(item => item.swad_office_id == swad_office_id);
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
