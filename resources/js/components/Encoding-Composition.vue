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
                            <span style="color:red" v-if="formError['client.ext_name']">{{ formError['client.middle_name'][0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="relationship_beneficiary">Relationship to Beneficiary:</label>
                            <input type="text" class="form-control" placeholder="Enter Relationship to Beneficiary" v-model="formData.client.relationship_beneficiary">
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
                            <span style="color:red" v-if="formError['father.ext_name']">{{ formError['father.middle_name'][0] }}</span>
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
                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.mother.middle_name">
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
                            <span style="color:red" v-if="formError['mother.ext_name']">{{ formError['mother.middle_name'][0] }}</span>
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
                            <input type="text" class="form-control" placeholder="Enter Middle Name" v-model="formData.beneficiaries[key].middle_name">
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
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.ext_name`]">{{ formError[`beneficiaries.${key}.middle_name`][0] }}</span>
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
                            <label for="gender">Student {{ key + 1 }} Gender:</label>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="school_name">Student {{ key + 1 }} School Name:</label>
                            <input type="text" class="form-control" placeholder="Enter School Name" v-model="formData.beneficiaries[key].school_name">
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.school_name`]">{{ formError[`beneficiaries.${key}.school_name`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Claim Status:</label>
                            <select class="form-control" placeholder="Enter School Level" v-model="formData.beneficiaries[key].status">
                                <option value="Claimed">Claimed</option>
                                <option value="For Scheduled Payout">For Scheduled Payout</option>
                                <option value="No Requirements">No Requirements</option>
                            </select>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.status`]">{{ formError[`beneficiaries.${key}.status`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status_date">Status Date:</label>
                            <date-picker v-model="formData.beneficiaries[key].status_date" format="MM/DD/YYYY" type="date" value-type="YYYY-MM-DD" style="width: 100%;" placeholder="MM/DD/YYYY"></date-picker>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.status_date`]">{{ formError[`beneficiaries.${key}.status_date`][0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="row gx-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sector_id">Student {{ key + 1 }} Sector:</label>
                            <select class="form-control" placeholder="Enter Sector" v-model="formData.beneficiaries[key].sector_id">
                                <option v-for="(sector, key) in sectors" :key="key" :value="sector.id">{{ sector.name }}</option>
                            </select>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.sector_id`]">{{ formError[`beneficiaries.${key}.sector_id`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sector_others">Sector Others:</label>
                            <input type="text" class="form-control" placeholder="Enter School Name" v-model="formData.beneficiaries[key].sector_others">
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.sector_others`]">{{ formError[`beneficiaries.${key}.sector_others`][0] }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <textarea type="text" class="form-control" placeholder="Enter Remarks" v-model="formData.beneficiaries[key].remarks">
                            </textarea>
                            <span style="color:red" v-if="formError[`beneficiaries.${key}.remarks`]">{{ formError[`beneficiaries.${key}.remarks`][0] }}</span>
                        </div>
                    </div>
                </div>
            </fieldset>
            <h1 style="text-align: center;">
                <button type="button" class="btn btn-warning" @click="addStudent">Add Student</button>
            </h1>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>

<script>
import Axios from 'axios';
import DatePicker from 'vue2-datepicker';
import uniqBy from 'lodash/uniqBy'
import isEmpty from 'lodash/isEmpty'

    export default {
        components: { DatePicker },
        props: ['psgcs', 'schoolLevels', 'sectors'],
        data(){
            return {
                formData:{
                    client: {},
                    father: {},
                    mother: {},
                    beneficiaries: [{},{},{}],
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
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.provinces = uniqBy(this.psgcs, 'province_psgc');
        },
        methods: {
            formSubmit(){
                Axios.post(route('family-composition.store'), this.formData)
                .then(res => {

                })
                .catch(err => {
                    this.formError = err.response.data.errors;
                })
                .then(res => {})
                ;
            },
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
            },
            addStudent(){
                this.formData.beneficiaries = [...this.formData.beneficiaries, {}];
            },
            removeStudent(index){
                if (index > -1) { // only splice array when item is found
                    this.formData.beneficiaries.splice(index, 1); // 2nd parameter means remove one item only
                }
                if(isEmpty(this.formData.beneficiaries)){
                    this.addStudent();
                }
            }
        }
    }
</script>
