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
                            <input type="checkbox" id="client_middle_name_check" v-model="formData.client.has_middle_name" @change="noMiddleName($event,'client')"/>
                            <label for="client_middle_name_check"><span style="font-size: 80%;">No Middle Name</span></label>
                            
                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.client.middle_name" :disabled="formData.client.has_middle_name">
                            <span style="color:red" v-if="formError['client.middle_name']">{{ formError['client.middle_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ext_name">Ext Name:</label>
                            <select  class="form-control" placeholder="Enter Ext Name" v-model="formData.client.ext_name">
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
                            <span style="color:red" v-if="formError['client.ext_name']">{{ formError['client.ext_name'][0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="relationship_beneficiary">Relationship to Beneficiary:</label>
                            <select class="form-control" placeholder="Enter Relationship to Beneficiary" v-model="formData.client.relationship_beneficiary">
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Grandfather/Grandmother">Grandfather/Grandmother</option>
                                <option value="Brother/Sister">Brother/Sister</option>
                                <option value="Guardian">Guardian</option>
                            </select>
                            <!-- <input type="text" class="form-control" placeholder="Enter Relationship to Beneficiary" v-model="formData.client.relationship_beneficiary"> -->
                            <span style="color:red" v-if="formError['client.relationship_beneficiary']">{{ formError['client.relationship_beneficiary'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="street_number">Street/House No.:</label>
                            <input type="text" class="form-control" placeholder="Enter Street/House No." v-model="formData.client.street_number">
                            <span style="color:red" v-if="formError['client.street_number']">{{ formError['client.street_number'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="province">Province:</label>
                            <select class="form-control" placeholder="Enter Province" v-model="formData.client.province" @change="populateCities">
                                <option v-for="(province, key) in provinces" :key="key" :value="province.province_psgc">{{ province.province_name }}</option>
                            </select>
                            <span style="color:red" v-if="formError['client.province']">{{ formError['client.province'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="city">City:</label>
                            <select class="form-control" placeholder="Enter City" v-model="formData.client.city" @change="populateBarangay">
                                <option v-for="(city, key) in cities" :key="key" :value="city.city_psgc">{{ city.city_name }}</option>
                            </select>
                            <span style="color:red" v-if="formError['client.city']">{{ formError['client.city'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="brgy">Barangay:</label>
                            <select class="form-control" placeholder="Enter Barangay" v-model="formData.client.brgy" @change="setPsgcId">
                                <option v-for="(brgy, key) in brgys" :key="key" :value="brgy.brgy_psgc">{{ brgy.brgy_name }}</option>
                            </select>
                            <span style="color:red" v-if="formError['client.brgy']">{{ formError['client.brgy'][0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="row gx-2">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cellphone_number">Cellphone Number:</label>
                            <input type="text" class="form-control" placeholder="Enter Cellphone Number" v-model="formData.client.cellphone_number">
                            <span style="color:red" v-if="formError['client.cellphone_number']">{{ formError['client.cellphone_number'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="birth_date">Birthday:</label>
                            <date-picker @change="calcClientAge" v-model="formData.client.birth_date" format="MM/DD/YYYY" type="date" value-type="YYYY-MM-DD" style="width: 100%;" placeholder="MM/DD/YYYY"></date-picker>
                            <span style="color:red" v-if="formError['client.birth_date']">{{ formError['client.birth_date'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="age">Age:</label>
                            <input type="text" class="form-control" placeholder="Enter Age" v-model="formData.client.age" readonly>
                            <span style="color:red" v-if="formError['client.age']">{{ formError['client.age'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select class="form-control" placeholder="Enter Gender" v-model="formData.client.gender">
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                            <span style="color:red" v-if="formError['client.gender']">{{ formError['client.gender'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="occupation">Occupation:</label>
                            <input type="text" class="form-control" placeholder="Enter Occupation" v-model="formData.client.occupation">
                            <span style="color:red" v-if="formError['client.occupation']">{{ formError['client.occupation'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="monthly_salary">Monthly Salary:</label>
                            <input type="text" class="form-control" placeholder="Enter Monthly Salary" v-model="formData.client.monthly_salary">
                            <span style="color:red" v-if="formError['client.monthly_salary']">{{ formError['client.monthly_salary'][0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sector_id">Sector:</label>
                            <select class="form-control" placeholder="Enter Sector" v-model="formData.client.sector_id" @change="handleChangeSector">
                                <option v-for="(sector, key) in sectors" :key="key" :value="sector.id">{{ sector.name }}</option>
                            </select>
                            <span style="color:red" v-if="formError[`client.sector_id`]">{{ formError[`client.sector_id`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="formData.client.sector_name == 'Others' && formData.client.sector_name != null">
                        <div class="form-group">
                            <label for="sector_other_id">Other Sector:</label>
                            <select class="form-control" placeholder="Enter Other Sector" v-model="formData.client.sector_other_id">
                                <option value="">NONE</option>
                                <option v-for="(sector, key) in sectorOthers" :key="key" :value="sector.id">{{ sector.name }}</option>
                            </select>
                            <span style="color:red" v-if="formError[`client.sector_other_id`]">{{ formError[`client.sector_other_id`][0] }}</span>
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
                            <input type="checkbox" id="father_middle_name_check" v-model="formData.father.has_middle_name" @change="noMiddleName($event,'father')"/>
                            <label for="father_middle_name_check"><span style="font-size: 80%;">No Middle Name</span></label>

                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.father.middle_name" :disabled="formData.father.has_middle_name">
                            <span style="color:red" v-if="formError['father.middle_name']">{{ formError['father.middle_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="ext_name">Ext Name:</label>
                            <select  class="form-control" placeholder="Enter Ext Name" v-model="formData.father.ext_name">
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
                            <span style="color:red" v-if="formError['father.ext_name']">{{ formError['father.ext_name'][0] }}</span>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="birth_date">Birthday:</label>
                            <date-picker v-model="formData.father.birth_date" format="MM/DD/YYYY" type="date" value-type="YYYY-MM-DD" style="width: 100%;" placeholder="MM/DD/YYYY"></date-picker>
                            <span style="color:red" v-if="formError['father.birth_date']">{{ formError['father.birth_date'][0] }}</span>
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
                            <input type="checkbox" id="mother_middle_name_check" v-model="formData.mother.has_middle_name" @change="noMiddleName($event,'mother')"/>
                            <label for="mother_middle_name_check"><span style="font-size: 80%;">No Middle Name</span></label>

                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.mother.middle_name" :disabled="formData.mother.has_middle_name">
                            <span style="color:red" v-if="formError['mother.middle_name']">{{ formError['mother.middle_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="ext_name">Ext Name:</label>
                            <select  class="form-control" placeholder="Enter Ext Name" v-model="formData.mother.ext_name">
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
                            <span style="color:red" v-if="formError['mother.ext_name']">{{ formError['mother.ext_name'][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="birth_date">Birthday:</label>
                            <date-picker v-model="formData.mother.birth_date" format="MM/DD/YYYY" type="date" value-type="YYYY-MM-DD" style="width: 100%;" placeholder="MM/DD/YYYY"></date-picker>
                            <span style="color:red" v-if="formError['mother.birth_date']">{{ formError['mother.birth_date'][0] }}</span>
                        </div>
                    </div>
                </div>
            </fieldset>
            <hr>
            <h2>Beneficiaries</h2>
            <span style="color:red" v-if="formError['beneficiary']">{{ formError['beneficiary'][0] }}</span>
            <fieldset class="border p-2 my-2" v-for="(beneficiary, key) in formData.beneficiaries" :key="key">
                <legend  class="w-auto">Student's {{ key + 1 }} Information <button type="button" class="btn btn-danger" @click="removeStudent(key)">Remove Student</button></legend>
                
                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="last_name">Student {{ key + 1 }} Last Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Last Name" v-model="formData.beneficiaries[key].last_name">
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.last_name`]">{{ formError[`beneficiaries.${key}.last_name`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name">Student {{ key + 1 }} First Name:</label>
                            <input type="text" class="form-control" placeholder="Enter First Name" v-model="formData.beneficiaries[key].first_name">
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.first_name`]">{{ formError[`beneficiaries.${key}.first_name`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">Student {{ key + 1 }} Middle Name:</label>
                            <input type="checkbox" :id="`beneficiaries_${key}_middle_name_check`" v-model="formData.beneficiaries[key].has_middle_name" @change="noMiddleName($event,'beneficiaries', key)"/>
                            <label :for="`beneficiaries_${key}_middle_name_check`"><span style="font-size: 80%;">No Middle Name</span></label>

                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.beneficiaries[key].middle_name" :disabled="formData.beneficiaries[key].has_middle_name">
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.middle_name`]">{{ formError[`beneficiaries.${key}.middle_name`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="ext_name">Ext Name:</label>
                            <select  class="form-control" placeholder="Enter Ext Name" v-model="formData.beneficiaries[key].ext_name">
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
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.ext_name`]">{{ formError[`beneficiaries.${key}.ext_name`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="birth_date">Birthday:</label>
                            <date-picker v-model="formData.beneficiaries[key].birth_date" format="MM/DD/YYYY" type="date" value-type="YYYY-MM-DD" style="width: 100%;" placeholder="MM/DD/YYYY"></date-picker>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.birth_date`]">{{ formError[`beneficiaries.${key}.birth_date`][0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="row gx-2">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select class="form-control" placeholder="Enter Gender" v-model="formData.beneficiaries[key].gender">
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.gender`]">{{ formError[`beneficiaries.${key}.gender`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="school_level_id">Student {{ key + 1 }} School Level:</label>
                            <select class="form-control" placeholder="Enter School Level" v-model="formData.beneficiaries[key].school_level_id">
                                <option v-for="(schoolLevel, key) in schoolLevels" :key="key" :value="schoolLevel.id">{{ schoolLevel.name }}</option>
                            </select>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.school_level_id`]">{{ formError[`beneficiaries.${key}.school_level_id`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="school_name">Student {{ key + 1 }} School Name:</label>
                            <input type="text" class="form-control" placeholder="Enter School Name" v-model="formData.beneficiaries[key].school_name">
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.school_name`]">{{ formError[`beneficiaries.${key}.school_name`][0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="row gx-2">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Claim Status:</label>
                            <select class="form-control" placeholder="Enter School Level" v-model="formData.beneficiaries[key].status">
                                <option value="Claimed">Claimed</option>
                                <option value="For Scheduled Payout">For Scheduled Payout</option>
                                <option value="No Requirements">No Requirements</option>
                                <option value="Not Eligible">Not Eligible</option>
                            </select>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.status`]">{{ formError[`beneficiaries.${key}.status`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="payout_id">Status Date:</label>
                            <select class="form-control" placeholder="Enter School Level" v-model="formData.beneficiaries[key].payout_id">
                                <option value="">NONE</option>
                                <option v-for="(payout, key) in payouts" :key="key" :value="payout.id">{{ payout.payout_date }}</option>
                            </select>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.payout_id`]">{{ formError[`beneficiaries.${key}.payout_id`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="text" class="form-control" :value="beneficiaryAmount(formData.beneficiaries[key].school_level_id, formData.beneficiaries[key].status)" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <input type="text" class="form-control" placeholder="Enter Remarks" v-model="formData.beneficiaries[key].remarks" />
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.remarks`]">{{ formError[`beneficiaries.${key}.remarks`][0] }}</span>
                        </div>
                    </div>
                </div>
            </fieldset>
            <h1 style="text-align: center;">
                <button type="button" class="btn btn-warning" @click="addStudent">Add Student</button>
            </h1>
            <button type="submit" class="btn btn-primary" :disabled="submit">Submit</button>
        </form>
    </div>
</template>

<script>
import Axios from 'axios';
import DatePicker from 'vue2-datepicker';
import uniqBy from 'lodash/uniqBy'
import isEmpty from 'lodash/isEmpty'
import debounce from 'lodash/debounce'

    export default {
        components: { DatePicker },
        props: ['psgcs', 'schoolLevels', 'sectors', 'payouts', 'sectorOthers'],
        data(){
            return {
                formData:{
                    client: {
                        has_middle_name: false,
                    },
                    father: {
                        has_middle_name: false,
                    },
                    mother: {
                        has_middle_name: false,
                    },
                    beneficiaries: [{
                        has_middle_name: false,
                    }],
                },
                formError:{
                    client: {},
                    father: {},
                    mother: {},
                    beneficiaries: [],
                },
                provinces: [],
                cities: [],
                brgys: [],
                submit: false,
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.provinces = uniqBy(this.psgcs, 'province_psgc');
        },
        methods: {
            formSubmit: debounce(function(){
                this.submit = true;
                this.formError = {
                    client: {},
                    father: {},
                    mother: {},
                    beneficiaries: [],
                }
                for (let index = 0; index < this.formData.beneficiaries.length; index++) {
                    this.formData.beneficiaries[index].swad_office_id = this.formData.client.swad_office_id;
                }
                Axios.post(route('family-composition.store'), this.formData)
                .then(res => {
                    this.submit = false;
                    alert("Successfuly saved.");
                    this.formData = {
                        client: {
                            has_middle_name: false,
                        },
                        father: {
                            has_middle_name: false,
                        },
                        mother: {
                            has_middle_name: false,
                        },
                        beneficiaries: [{
                            has_middle_name: false,
                        }],
                    };
                    this.formError = {
                        client: {},
                        father: {},
                        mother: {},
                        beneficiaries: [],
                    }
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
            }, 500),
            calcClientAge(){
                this.formData.client.age = this.getAge(this.formData.client.birth_date);
            },
            getAge(dateString) {
                var today = new Date();
                var birthDate = new Date(dateString);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            },
            populateCities(){
                let cities = this.psgcs.filter(item => item.province_psgc === this.formData.client.province);
                this.cities = uniqBy(cities, 'city_psgc');
                this.formData.client.brgy = null;
            },
            populateBarangay(){
                let brgys = this.psgcs.filter(item => item.city_psgc === this.formData.client.city);
                this.brgys = uniqBy(brgys, 'brgy_psgc');
            },
            setPsgcId(){
                let psgcs = this.psgcs.filter(item => item.brgy_psgc === this.formData.client.brgy);
                this.formData.client.psgc_id = psgcs[0].id;
                this.formData.client.swad_office_id = psgcs[0].swad_office_id;
            },
            addStudent(){
                this.formData.beneficiaries = [...this.formData.beneficiaries, {has_middle_name: false}];
            },
            removeStudent(index){
                if (index > -1) { // only splice array when item is found
                    this.formData.beneficiaries.splice(index, 1); // 2nd parameter means remove one item only
                }
                if(isEmpty(this.formData.beneficiaries)){
                    this.addStudent();
                }
            },
            noMiddleName(e, field, index = null){
                if(e.target.checked){
                    if(index != null){
                        this.formData[field][index]['middle_name'] = "";
                    }else{
                        this.formData[field]['middle_name'] = "";
                    }
                }
            },

            beneficiaryAmount(school_level_id = null, status = null){
                let school_level = this.schoolLevels.filter(item => item.id == school_level_id);
                if(status == "Claimed" && school_level_id != null && status != null){
                    return school_level[0].amount;
                }else{
                    return 0;
                }
            },
            handleChangeSector(){
                let sector = this.sectors.filter(item => item.id == this.formData.client.sector_id);
                this.formData.client.sector_name = sector[0].name;
                if(this.formData.client.sector_name != "Others"){
                    this.formData.client.sector_other_id = null;
                }
            }
        }
    }
</script>
