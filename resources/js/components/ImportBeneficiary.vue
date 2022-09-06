<template>
    <div>
        <input class="field-input" type="file" @change="getSelectedFiles($event)" name="selected_files" id="selected_files" ref="selected_files"
            accept=".csv">
        
        <select placeholder="Search" v-model="importType">
            <option value="create">Create</option>
            <option value="update">Update</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary" @click="submitUpload">Import</button>
        <br> {{ importedBeneficiariesCount }} / {{ isEmpty(beneficiaries) ? 0 : beneficiaries.length - 1 }}
    </div>
</template>
<script>
import Axios from 'axios';
import isEmpty from 'lodash/isEmpty'
    export default {
        mounted() {
            // this.fileToImport('test');
            // this.$API.Beneficiary.getList();
        },
        data() {
            return {
                file: null,
                beneficiaries: [],
                importOptions: {},
                importedBeneficiariesCount: 0,
                importing: false,
                importingIterationsCount: 0,
                importRoute: route('import.beneficiary.data'),
                processedFilename: "",
                importedFilename: "",
                importType: "create",
            }
        },
        methods: {
            submitUpload() {
                let formData = new FormData();
                let file = this.file;
                formData.append('file', file);
                Axios.post(route('import.beneficiary.initialize'), formData)
                .then(res => {
                    this.fileToImport(res.data.filename, res.data.processed_filename);
                    this.importedFilename = res.data.filename; 
                    this.processedFilename = res.data.processed_filename; 
                })
                .catch(err => {

                })
                .then(err => {

                });
            },
            isEmpty(value){
                return isEmpty(value);
            },
            getSelectedFiles(event) {
                switch (event.target.files[0].type) {
                    case "application/vnd.ms-excel":
                    case "text/x-csv":
                    case "application/csv":
                    case "application/x-csv":
                    case "text/csv":
                    case "text/comma-separated-values":
                    case "text/x-comma-separated-values":
                    case "text/tab-separated-values":
                    this.file = event.target.files[0];
                        break;
                    default:
                        this.file = null;
                        document.getElementById("selected_files").value = "";
                        break;
                }
            },

            fileToImport(filename) {
                Axios.post(route('import.beneficiary.extract'), {
                    filename,
                })
                .then(res => {
                    this.beneficiaries = res.data;
                    this.importing = true;
                    this.importedBeneficiariesCount = 0;
                    this.importingIterationsCount = 0;
                    this.importToDatabase();
                })
                .catch(err => {})
                .then(res => {})
            },

            importToDatabase() {
                if (this.importedBeneficiariesCount >= (this.beneficiaries.length - 1)) {
                    this.importing = false;
                    window.location = '/files/processed/'+this.processedFilename + '.csv';
                    return false;
                }
                let beneficiaries = {};
                for (let index = 1 + (this.importingIterationsCount * 100); index <= (100 + (this.importingIterationsCount * 100)); index++) {
                    if (this.beneficiaries[index] == 0) {
                        break;
                    } else {
                        beneficiaries[index] = this.beneficiaries[index];
                    }
                }
                Axios.post(route('import.beneficiary.data'), {
                    columns: this.beneficiaries[0],
                    beneficiaries,
                    processed_filename: this.processedFilename,
                    importType: this.importType,
                })
                .then(res => {
                    this.importedBeneficiariesCount = this.importedBeneficiariesCount + res.data.count;
                    this.importingIterationsCount++;
                    this.importToDatabase();
                })
                .catch(err => {
                    if(err.response.status == 422){
                        this.importing = false;
                    }else{
                        this.importToDatabase();
                    }
                })
                .then(err => {

                });
            },
        }
    }
</script>
<style>
    .el-upload__input{
        display: none !important;
    }
</style>