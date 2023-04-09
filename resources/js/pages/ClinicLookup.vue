<template>
    <div v-if="loading">
        <loading/>
    </div> 
    <div v-else>
        <div class="card mg-b-20">
            <div class="card-header pd-r-15 pd-t-10 pb-0">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-start align-items-center">
                        <h4 class="card-title mg-b-0 text-muted">Lookup Code</h4>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                            <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
                        </button> 
                        <div class="dropdown-menu tx-13">
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="ShowCol()">
                                <i class="mdi mdi-eye me-2 tx-16"></i><span>Show Column</span>
                            </div>
                            <!-- <div class="dropdown-item cur-pointer dropdown-hover" @click="exportIncident()">
                                <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Export All Incidents</span>
                            </div> -->
                        </div>
                    </div>
                </div>
                    <p class="tx-12 tx-gray-500 mb-0">Medical records and Medicine lookup code...</p>
            </div>

            <div class="card-body pt-2">
                <div class="row">
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3">
                        <div class="table-responsive element border" style="height: 75vh">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px">
                                    <tr>
                                        <th class="border-0">Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-hover cur-pointer" v-for="(i, inx) in cateData" :key="i.id" @click="CateSelected(inx, i.category)" :style="inx === cateSel ? 'background-color: #dadee7; color: blue' : ''">
                                        <td class="border-0">{{i.category}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="table" class="col-xl-10 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3" >
                        <div class="table-responsive element border pos-relative" style="height: 75vh">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px; z-index: 1">
                                    <tr>  
                                        <th class="border-start-0">{{codeForm.category}}</th>
                                        <th>Description Eng</th>
                                        <th>Description Lao</th>
                                        <th>Active</th>
                                        <th v-if="showCol.created_at">Created At</th>
                                        <th v-if="showCol.created_by">Created By</th>
                                        <th v-if="showCol.updated_at">Updated At</th>
                                        <th v-if="showCol.updated_by">Updated By</th>
                                        <th class="border-end-0 wd-80p">Tools</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-hover" v-for="(i, inx) in codeFilter" :key="inx">
                                        <td class="border-start-0">{{i.code}}</td>
                                        <td>{{i.descr_eng}}</td>
                                        <td>{{i.descr_lao}}</td>
                                        <td class="text-capitalize">{{!!parseInt(i.active)}}</td>
                                        <td v-if="showCol.created_at" class="text-muted">{{(i.created_at)}}</td>
                                        <td v-if="showCol.created_by" class="text-muted text-capitalize">{{i.created_by}}</td>
                                        <td v-if="showCol.updated_at" class="text-muted">{{(i.updated_at)}}</td>
                                        <td v-if="showCol.updated_by" class="text-muted text-capitalize">{{i.updated_by}}</td>

                                        <td class="p-0 align-middle border-end-0">
                                            <div class="d-flex justify-content-start ms-1">
                                                <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="EditCode(i.id), btn=''">
                                                    <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                </button> 
                                                <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="DelCode(i.id, inx)">
                                                    <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                </button> 
                                            </div>
                                        </td> 
                                    </tr>
                                </tbody>
                            </table>
                            <!-- <div class="d-flex justify-content-start align-items-center cur-pointer add-hover pd-l-10 ht-30 wd-100" @click="NewCode()" title="Create a new item">
                                <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                <span class="text-primary tx-13">New</span>
                            </div> -->
                        </div>
                        <div class="pos-absolute ms-2" style="margin-top: -32px">
                            <button type="button" class="btn btn-info btn-b btn-sm rounded-circle p-0 ht-25 wd-25" title="Add new record" @click="NewCode()">
                                <i class="fe fe-plus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Medicine Table -->
                    <div v-else class="col-xl-10 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3" >
                        <div class="table-responsive element border pos-relative" style="max-height: 75vh">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px; z-index: 1">
                                    <tr>  
                                        <th class="border-start-0">Medicine</th>
                                        <th>Medicine Lao</th>
                                        <th>Properties</th>
                                        <th>Unit</th>
                                        <th>Unit Lao</th>
                                        <th>Minimum</th>
                                        <th v-if="showCol.created_at">Created At</th>
                                        <th v-if="showCol.created_by">Created By</th>
                                        <th v-if="showCol.updated_at">Updated At</th>
                                        <th v-if="showCol.updated_by">Updated By</th>
                                        <th class="border-end-0 wd-80p">Tools</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-hover" v-for="(i, inx) in medicData" :key="inx">
                                        <td class="border-start-0">{{i.medicine}}</td>
                                        <td>{{i.medicine_lao}}</td>
                                        <td>{{i.properties}}</td>
                                        <td>{{i.unit}}</td>
                                        <td>{{i.unit_lao}}</td>
                                        <td>{{i.minimum}}</td>
                                        <td v-if="showCol.created_at" class="text-muted">{{(i.created_at)}}</td>
                                        <td v-if="showCol.created_by" class="text-muted text-capitalize">{{i.created_by}}</td>
                                        <td v-if="showCol.updated_at" class="text-muted">{{(i.updated_at)}}</td>
                                        <td v-if="showCol.updated_by" class="text-muted text-capitalize">{{i.updated_by}}</td>
                                        <td class="p-0 align-middle border-end-0">
                                            <div class="d-flex justify-content-start ms-1">
                                                <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="EditLKMedicine(i.id), btn=''">
                                                    <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                </button> 
                                                <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="DelLKMedicine(i.id, inx)">
                                                    <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                </button> 
                                            </div>
                                        </td> 
                                    </tr>
                                </tbody>
                            </table>
                            <!-- <div class="d-flex justify-content-start align-items-center cur-pointer add-hover pd-l-10 ht-30 wd-100" @click="NewMedicine()" title="Create a new item">
                                <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                <span class="text-primary tx-13">New</span>
                            </div> -->
                        </div>
                        <!-- <div class="d-flex justify-content-end"> -->
                            <div class="pos-absolute ms-2" style="margin-top: -32px">
                                <button type="button" class="btn btn-info btn-b btn-sm rounded-circle p-0 ht-25 wd-25" title="Add new record" @click="NewLKMedicine()">
                                    <i class="fe fe-plus"></i>
                                </button>
                            </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>

            <!-- Modal Lookup -->
            <div class="modal" id="LKCodeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header pb-1 bd-b-0">
                            <h6 class="main-content-label">{{codeForm.category}}</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body pt-2">  
                            <div class="form-group">
                                <label class="mb-0">Code  <span class=" text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="codeForm.code">
                            </div>   
                            <div class="form-group">
                                <label class="mb-0">Description</label>
                                <input type="text" class="form-control laofont" v-model="codeForm.descr_eng">
                            </div>   
                            <div class="form-group">
                                <label class="mb-0">Description Lao  <span class=" text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="codeForm.descr_lao">
                            </div>   
                            <div class="form-group">
                                <label class="mb-0">Active</label> 
                                <Multiselect v-model="codeForm.active" :options="lkActive"/>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button v-if="btn" type="button" class="btn btn-primary" :class="CodeFormDis" @click="AddCode()"><i class="fe fe-plus"></i><span class="mx-1">{{$t('add')}}</span></button> 
                                <button v-else type="button" class="btn btn-primary" :class="CodeFormDis" @click="UpdCode()"><i class="fe fe-check-circle"></i><span class="mx-1">{{$t('update')}}</span></button> 
                                <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">{{$t('cancel')}}</span></button>
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div>

            <!-- Modal Medicines -->
            <div class="modal" id="MedicineModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header pb-1 bd-b-0">
                            <h6 class="main-content-label">Medicines</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body pt-2">  
                            <div class="form-group">
                                <label class="mb-0">Medicine <span class=" text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="medicForm.medicine">
                            </div>   
                            <div class="form-group">
                                <label class="mb-0">Description Lao</label>
                                <input type="text" class="form-control" v-model="medicForm.medicine_lao">
                            </div>   
                            <div class="form-group">
                                <label class="mb-0">Properties</label>
                                <input type="text" class="form-control" v-model="medicForm.properties">
                            </div>  
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-0">Unit <span class=" text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="medicForm.unit">
                                    </div>   
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-0">Unit Lao</label>
                                        <input type="text" class="form-control" v-model="medicForm.unit_lao">
                                    </div>   
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-0">Minimum</label>
                                        <input type="number" class="form-control" v-model="medicForm.minimum">
                                    </div>   
                                </div>
                            </div> 
                            <div class="d-flex justify-content-end">
                                <button v-if="btn" type="button" class="btn btn-primary" :class="MedicFormDis" @click="AddLKMedicine()"><i class="fe fe-plus"></i><span class="mx-1">{{$t('add')}}</span></button> 
                                <button v-else type="button" class="btn btn-primary" :class="MedicFormDis" @click="UpdLKMedicine()"><i class="fe fe-check-circle"></i><span class="mx-1">{{$t('update')}}</span></button> 
                                <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">{{$t('cancel')}}</span></button>
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div>

            <!-- Modal Show Columns -->
            <div class="modal bd-0" id="ShowColumn" data-bs-keyboard="false" tabindex="-1" aria-labelledby="colShowLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header pb-1 bd-b-0">
                            <h6 class="text-muted main-content-label text-capitalize">Show/Hide Columns</h6>
                        </div>
                        <div class="modal-body pt-0">  
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{$t('createAt')}}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="no"  v-model="showCol.created_at" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="no"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{$t('createBy')}}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="position"  v-model="showCol.created_by" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="position"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{$t('updateAt')}}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="crew"  v-model="showCol.updated_at" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="crew"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{$t('updateBy')}}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="section"  v-model="showCol.updated_by" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="section"></label>
                                </div>
                            </div>

                            <!-- <div class="d-flex justify-content-between mb-2">
                                <span>Type</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="type"  v-model="showCol.type" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="type"></label>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>                                              
            </div>
        
        
        </div>
    </div>

</template>

<script>
export default {
    name: 'XpplAppClinicLookup',

    data() {
        return {
            permiss:[],
            cateData:[],
            codeData:[],
            codeFilter:[],
            codeForm:{id:'', category:'', code:'', descr_eng:'', descr_lao:'', active:'1'},
            medicData:[],
            medicForm:{id:'', medicine:'', medicine_lao:'', properties:'', unit:'', unit_lao:'', minimum:''},

            showCol: {created_at:false, created_by:false, updated_at:false, updated_by:false},

            cateSel:'',
            lkActive:[
                {value: 1, label: 'True'},
                {value: 0, label: 'False'}
            ],

            btn:true,
            table:true,
            loading: false
        };
    },

    mounted() {
        
    },

    computed: {
        CodeFormDis(){
            if (this.codeForm.code=='' || this.codeForm.descr_lao=='' || this.codeForm.active==null){
                return 'disabled';
            } else {
                return '';
            }
        },

        MedicFormDis(){
            if (this.medicForm.medicine=='' || this.medicForm.unit==''){
                return 'disabled';
            } else {
                return '';
            }
        }
    },

    methods: {
        async OnLoad(){
            this.loading = true;
            const cate = await axios.get('/api/clinic/lkcategory')
            this.cateData = cate.data;

            const data = await this.GetLookup();
            this.CateSelected(0, 'Blood Group');

            this.loading = false;

            this.GetLKMedicine();
            const profile = await axios.get('/api/profile')
            this.permiss = profile.data;
        },

        async GetLookup(){
            const code = await axios.get('/api/clinic/lkcode')
            this.codeData = code.data;
        },

        async GetLKMedicine(){
            const medic = await axios.get('/api/clinic/lkmedicine')
            this.medicData = medic.data;
        },

        ShowCol(){
            $('#ShowColumn').modal('show');
        },

        CateSelected(inx, cate){
            this.cateSel = inx;

            if (cate=='Medicine'){
                this.table = '';
            } else {
                this.table = true;
                this.codeForm.category = cate;
                let item = this.codeData.filter((i) => i.category == cate);
                this.codeFilter = item;
            }
        },

        NewCode(){
            if (this.permiss.clinic_add==0){
                this.$swal.fire({
                    text: "You don't have permission to add!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.btn = true;
                this.CodeFromClear();
                $('#LKCodeModal').modal('show');
            }
        },

        async AddCode(){
            const response = await axios.post('/api/clinic/addlkcode', this.codeForm)
            if(response.data.success){
                $('#LKCodeModal').modal('hide');
                
                const data = await this.GetLookup();
                let item = this.codeData.filter((i) => i.category ==  this.codeForm.category);
                this.codeFilter = item;
            } else {
                alert(response.data.message)
            }
        },

        EditCode(id){
            if(this.permiss.clinic_edit==0){
                this.$swal.fire({
                    text: "You don't have permission to update!",
                    icon: 'error',
                    showCancelButton:false,
                    showConfirmButton:false,
                    allowOutsideClick:false,
                    timerProgressBar:true,
                    timer: 1500
                })
            } else {
                this.btn = '';
                let item = this.codeFilter.find((i) => i.id == id);
                this.codeForm.id = item.id;
                this.codeForm.code = item.code;
                this.codeForm.descr_eng = item.descr_eng;
                this.codeForm.descr_lao = item.descr_lao;
                this.codeForm.active = item.active;
                $('#LKCodeModal').modal('show');
            }
        },

        UpdCode(){
            this.$axios.post('/api/clinic/updlkcode', this.codeForm)
            .then((res) => {
                $('#LKCodeModal').modal('hide');
                let f = this.codeForm;
                let item = this.codeFilter.find((i) => i.id == f.id);
                item.category = f.category;
                item.code = f.code;
                item.descr_eng = f.descr_eng;
                item.descr_lao = f.descr_lao;
                item.active = f.active;
                this.GetLookup();
            });
        },

        DelCode(id, inx){
            if (this.permiss.clinic_del==0){
                this.$swal.fire({
                    text: "You don't have permission to delete!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.$swal.fire({
                    text: "Do you want to delete this record?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fe fe-trash-2"></i> <span class="font-weight-light">Delete</span>',
                    cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
                    confirmButtonColor: '#d33',
                    allowEnterKey: false,
                    allowOutsideClick: false,
                }).then((result)=>{
                    if(result.isConfirmed){
                        this.$axios.post(`/api/clinic/dellkcode/${id}`)
                        .then((response)=>{

                            this.codeFilter.splice(inx,1)
                            this.GetLookup();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        NewLKMedicine(){
            if (this.permiss.clinic_add==0){
                this.$swal.fire({
                    text: "You don't have permission to add!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.btn = true;
                this.MedicFormClear();
                $('#MedicineModal').modal('show');
            }
        },

        AddLKMedicine(){
            this.$axios.post('/api/clinic/addlkmedicine', this.medicForm)
            .then((response) => {
                if(response.data.success){
                    $('#MedicineModal').modal('hide');
                    
                    this.GetLKMedicine();

                } else {
                    alert(response.data.message)
                }
            })
        },

        EditLKMedicine(id){
            if(this.permiss.clinic_edit==0){
                this.$swal.fire({
                    text: "You don't have permission to update!",
                    icon: 'error',
                    showCancelButton:false,
                    showConfirmButton:false,
                    allowOutsideClick:false,
                    timerProgressBar:true,
                    timer: 1500
                })
            } else {
                this.btn = '';
                let item = this.medicData.find((i) => i.id == id);

                this.medicForm.id = item.id;
                this.medicForm.medicine = item.medicine;
                this.medicForm.medicine_lao = item.medicine_lao;
                this.medicForm.properties = item.properties;
                this.medicForm.unit = item.unit;
                this.medicForm.unit_lao = item.unit_lao;
                this.medicForm.minimum = item.minimum;

                $('#MedicineModal').modal('show');
            }
        },

        UpdLKMedicine(){
            this.$axios.post('/api/clinic/updlkmedicine', this.medicForm)
            .then((res) => {
                $('#MedicineModal').modal('hide');
                this.GetLKMedicine();
            });
        },

        DelLKMedicine(id, inx){
            if (this.permiss.clinic_del==0){
                this.$swal.fire({
                    text: "You don't have permission to delete!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.$swal.fire({
                    text: "Do you want to delete this record?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fe fe-trash-2"></i> <span class="font-weight-light">Delete</span>',
                    cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
                    confirmButtonColor: '#d33',
                    allowEnterKey: false,
                    allowOutsideClick: false,
                }).then((result)=>{
                    if(result.isConfirmed){
                        this.$axios.post(`/api/clinic/dellkmedicine/${id}`)
                        .then((response)=>{

                            this.GetLKMedicine();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        CodeFromClear(){
            this.codeForm.id = '';
            this.codeForm.code = '';
            this.codeForm.descr_eng = '';
            this.codeForm.descr_lao = '';
            this.codeForm.active = true;
        },

        MedicFormClear(){
            this.medicForm.id = '';
            this.medicForm.medicine = '';
            this.medicForm.medicine_lao = '';
            this.medicForm.properties = '';
            this.medicForm.unit = '';
            this.medicForm.unit_lao = '';
            this.medicForm.minimum = '';
        }
    },

    created() {
        this.OnLoad();
    },

    beforeRouteEnter(to, from, next){
        if(!window.Laravel.isLoggedin_laravel){
            window.location.href = "/signin";
        }
        next();
	},
};
</script>

<style lang="scss" scoped>

</style>