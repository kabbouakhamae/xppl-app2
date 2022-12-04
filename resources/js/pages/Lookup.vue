<template>
    <div class="row">
        <!-- General Lookup -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="breadcrumb-header justify-content-between align-items-center mb-1 mt-0" >
                        <div class="d-flex">
                            <h4 class="card-title text-muted mb-0 my-auto text-capitalize">General Lookup</h4>
                        </div>
                        <div class="d-flex my-xl-auto right-content align-items-center">
                            <div class="wd-xl-200 wd-lg-200 wd-md-200 wd-100p">
                                <Multiselect searchable="true" searchStart="true" :options="genCodeCate" v-model="genCodeForm.category" @select="genCateSelected()"/>
                            </div>
                        </div>
                    </div>  
                    <div class="table-responsive border mt-1">
                        <table class="table main-table-reference text-nowrap mb-1">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Description Lao</th>
                                    <th class="wd-60p">Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr class="tr-hover" v-for="lst in genCodeData" :key="lst.id">
                                    <td> {{ lst.code }} </td>
                                    <td> {{ lst.descr }} </td>
                                    <td class="laofont"> {{ lst.descr_lao }} </td>
                                    <td style="padding: 0px 4px; vertical-align: middle">
                                        <div class="d-flex justify-content-start">
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Edit this record" @click="genCodeEdit(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Delete this record" @click="genCodeDel(lst.id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td>
                                </tr>   
                                <div class="mt-1" style="margin-start: 11px">
                                    <a class="add-hover p-0" href="#" v-if="genCodeForm.category" @click="newGenLookup()" title="Add new record"><span class="tx-13 text-primary">Add...</span></a>                                                                                           
                                </div>                                                                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="breadcrumb-header justify-content-between align-items-center mb-1 mt-0" >
                        <div class="d-flex">
                            <h4 class="card-title text-muted mb-0 my-auto text-capitalize">Fuel Lookup</h4>
                        </div>
                        <div class="d-flex my-xl-auto right-content align-items-center">
                            <div class="wd-xl-200 wd-lg-200 wd-md-200 wd-100p">
                                <Multiselect searchable="true" searchStart="true" :options="fuelCodeCate" v-model="fuelCodeForm.category" @select="fuelCateSelected()"/>
                            </div>
                        </div>
                    </div>  

                    <div class="table-responsive border mt-1">
                        <table class="table main-table-reference text-nowrap mb-1">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th class="wd-60p">Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr class="tr-hover" v-for="lst in fuelCodeData" :key="lst.id">
                                    <td> {{ lst.dept }} </td>
                                    <td> {{ lst.code }} </td>
                                    <td> {{ lst.descr }} </td>
                                    <td style="padding: 0px 4px; vertical-align: middle">
                                        <div class="d-flex justify-content-start">
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Edit this record" @click="fuelCodeEdit(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Delete this record" @click="fuelCodeDel(lst.id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td>
                                </tr>   
                                <div class="mt-1" style="margin-start: 11px">
                                    <a class="add-hover p-0" href="#" v-if="fuelCodeForm.category" @click="newFuelLookup()" title="Add new record"><span class="tx-13 text-primary">Add...</span></a>                                                                                           
                                </div>                                                                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment and Work Order -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="breadcrumb-header justify-content-between align-items-center mb-1 mt-0" >
                        <div class="d-flex">
                            <h6 class="card-title text-muted mb-0 my-auto text-capitalize">Equipmemt and Work Order</h6>
                        </div>
                        <div class="d-flex my-xl-auto right-content align-items-center">
                            <div class="pos-relative wd-xl-200 wd-lg-200 wd-md-200 wd-100p">
                                <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search equip no" v-model="search" @input="searchChange()" >
                                    <i class="fe fe-search search-i text-muted"></i>
                                <button class="btn btn-icon btn-sm search-c text-muted" v-if="btnClear" @click="searchClear()"><i class="fe fe-x"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive border mt-1" style="max-height: 300px">
                        <table class="table main-table-reference text-nowrap mb-1">
                            <thead class="position-sticky" style="top: -1px">
                                <tr>
                                    <th>Equip No</th>
                                    <th>Equip Name</th>
                                    <th>Equip Descr</th>
                                    <th class="wd-60p">Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr class="tr-hover cur-pointer" v-for="(lst, key) in equipData" :key="lst.id" @click="equipSelected(key, lst.equip_no)" :style="key === equipSelectedRow ? 'background-color: #dadee7; border-left: 1.02px solid #0162e8; color: blue' : ''">
                                    <td> {{ lst.equip_no }} </td>
                                    <td> {{ lst.equip_name }} </td>
                                    <td> {{ lst.equip_descr }} </td>
                                    <td style="padding: 0px 4px; vertical-align: middle">
                                        <div class="d-flex justify-content-start">
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Edit this record" @click="equipEdit(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Delete this record" @click="equipDel(lst.id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td>
                                </tr>   
                                <div class="mt-1" style="margin-start: 11px">
                                    <a class="add-hover p-0" href="#" @click="newEquip()" title="Add new record"><span class="tx-13 text-primary">Add...</span></a>                                                                                           
                                </div>                                                                            
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive element border mt-3">
                        <table class="table main-table-reference text-nowrap mb-1">
                            <thead>
                                <tr>
                                    <th>Equip No</th>
                                    <th>Work Order</th>
                                    <th>Reserve No</th>
                                    <th>Date</th>
                                    <th class="wd-60p">Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr class="tr-hover" v-for="lst in workOrderData" :key="lst.id">
                                    <td> {{ lst.equip_no }} </td>
                                    <td> {{ lst.work_order }} </td>
                                    <td> {{ lst.reserve_no }} </td>
                                    <td> {{ lst.basic_date }} </td>
                                    <td style="padding: 0px 4px; vertical-align: middle">
                                        <div class="d-flex justify-content-start">
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Edit this record" @click="workOrderEdit(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Delete this record" @click="workOrderDel(lst.id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td>
                                </tr>   
                                <div class="mt-1" style="margin-start: 11px">
                                    <a class="add-hover p-0" href="#" v-if="workOrderForm.equip_no" @click="newWorkOrder()" title="Add new record"><span class="tx-13 text-primary">Add...</span></a>                                                                                           
                                </div>                                                                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- General Lookup Modal -->
    <div class="modal fade effect-scale" id="genLookup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="lookupLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header pb-1 bd-b-0">
                    <h6 class="text-muted main-content-label text-capitalize">General Lookup: {{genCodeForm.category}}</h6>
                </div>
                <div class="modal-body">  
                    <div class="form-group">
                        <label class="mb-0">Code  <span class=" text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="genCodeForm.code">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Description</label>
                        <input type="text" class="form-control" v-model="genCodeForm.descr">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Description Lao</label>
                        <input type="text" class="form-control laofont" v-model="genCodeForm.descr_lao">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Used  <span class=" text-danger">*</span></label>
                        <input type="number" class="form-control" v-model="genCodeForm.used">
                    </div>   
                    <div class="d-flex justify-content-end">
                        <button v-if="bntAddShow" type="button" class="btn btn-primary" :class="btnGenAddDis" @click="genCodeAdd()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                        <button v-else type="button" class="btn btn-primary" @click="genCodeUpd()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                        <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                    </div>
                </div>
            </div>
        </div>                                              
    </div>  

    <!-- Fuel Lookup Modal -->
    <div class="modal fade effect-scale" id="fuelLookup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="fuelLookupLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header pb-1 bd-b-0">
                    <h6 class="text-muted main-content-label text-capitalize">Fuel Lookup: {{fuelCodeForm.category}}</h6>
                </div>
                <div class="modal-body">  
                    <div class="form-group">
                        <label class="mb-0">Department</label> 
                        <Multiselect v-model="fuelCodeForm.dept" :options="lkDept"/>
                    </div>
                    <div class="form-group">
                        <label class="mb-0">Code  <span class=" text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="fuelCodeForm.code">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Description</label>
                        <input type="text" class="form-control" v-model="fuelCodeForm.descr">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Used  <span class=" text-danger">*</span></label>
                        <input type="number" class="form-control" v-model="fuelCodeForm.used">
                    </div>   
                    <div class="d-flex justify-content-end">
                        <button v-if="bntAddShow" type="button" class="btn btn-primary" :class="btnFuelAddDis" @click="fuelCodeAdd()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                        <button v-else type="button" class="btn btn-primary" @click="fuelCodeUpd()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                        <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                    </div>
                </div>
            </div>
        </div>                                              
    </div>

    <!-- Equip Modal -->
    <div class="modal fade effect-scale" id="equip" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="equipLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header pb-1">
                    <h6 class="text-muted main-content-label text-capitalize">Equipment Number</h6>
                </div>
                <div class="modal-body">  
                    <div class="form-group">
                        <label class="mb-0">Equipment No  <span class=" text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="equipForm.equip_no">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Equipment Name  <span class=" text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="equipForm.equip_name">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Equipment Description  <span class=" text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="equipForm.equip_descr">
                    </div>    
                    <div class="d-flex justify-content-end">
                        <button v-if="bntAddShow" type="button" class="btn btn-primary" :class="btnEquipAddDis" @click="equipAdd()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                        <button v-else type="button" class="btn btn-primary" @click="equipUpd()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                        <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                    </div>
                </div>
            </div>
        </div>                                              
    </div>

    <!-- Work Order Modal -->
    <div class="modal fade effect-scale" id="workOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="WorkOrderLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header pb-1">
                    <h6 class="text-muted main-content-label text-capitalize">Work Order Number</h6>
                </div>
                <div class="modal-body">    
                    <div class="form-group">
                        <label class="mb-0">Work Order  <span class=" text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="workOrderForm.work_order">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Reserve No</label>
                        <input type="text" class="form-control" v-model="workOrderForm.reserve_no">
                    </div>    
                    <div class="form-group">
                        <label class="mb-0">Basic Date  <span class=" text-danger">*</span></label>
                        <input type="date" class="form-control" v-model="workOrderForm.basic_date" @change="dateChanged()">
                    </div>    
                    <div class="d-flex justify-content-end">
                        <button v-if="bntAddShow" type="button" class="btn btn-primary" :class="btnWorkOrderAddDis" @click="workOrderAdd()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                        <button v-else type="button" class="btn btn-primary" @click="workOrderUpd()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                        <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                    </div>
                </div>
            </div>
        </div>                                              
    </div>

</template>

<script>
export default {
    name: 'XpplAppLookup',

    data() {
        return {
            bntAddShow: true,
            lkDept: [],
            genCodeCate: [],
            genCodeData: [],
            fuelCodeCate: [],
            fuelCodeData: [],
            equipData: [],
            workOrderData: [],
            genCodeForm: {id: '', category: '', code: '', descr: '', descr_lao: '', used: '1'},
            fuelCodeForm: {id: '', dept: '', category: '', code: '', descr: '', used: '1'},
            equipForm: {id: '', equip_no: '', equip_name: '', equip_descr: ''},
            workOrderForm: {id: '', equip_no: '', work_order: '', basic_date: '', reserve_no: '', year_mont: ''},
            search: '',
            btnClear: false,
            equipSelectedRow: '',
            
        };
    },

    mounted() {
        
    },

    computed:{
        btnGenAddDis(){
            if (this.genCodeForm.code == '' || this.genCodeForm.used == '') {
                return 'disabled';
            } else {
                return '';
            }
        },

        btnFuelAddDis(){
            if (this.fuelCodeForm.dept == null || this.fuelCodeForm.code == '' || this.fuelCodeForm.used == '') {
                return 'disabled';
            } else {
                return '';
            }
        },

        btnEquipAddDis(){
            if (this.equipForm.equip_no == '' || this.equipForm.equip_name == '' || this.equipForm.equip_descr == '') {
                return 'disabled';
            } else {
                return '';
            }
        },

        btnWorkOrderAddDis(){
            if (this.workOrderForm.equip_no == '' || this.workOrderForm.work_order == '' || this.workOrderForm.basic_date == '') {
                return 'disabled';
            } else {
                return '';
            }
        }
    },

    methods: {
        genCateSelected(){
            this.$axios.post('/api/lookupSett/genLookup', {
                cate: this.genCodeForm.category
            }).then(res => {
                this.genCodeData = res.data;
            })
        },

        newGenLookup(){
            this.genCodeFormClear();
            this.bntAddShow = true;
            $('#genLookup').modal('show');
        },

        genCodeAdd(){
            this.$axios.post('/api/lookupSett/genCodeAdd', this.genCodeForm)
            .then((res) => {
                 if(res.data.success){

                    $('#genLookup').modal('hide');
                    this.genCateSelected();

                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        genCodeEdit(id){
            this.bntAddShow = false;
            this.genCodeFormClear();
            $('#genLookup').modal('show');

            this.$axios.post(`/api/lookupSett/genCodeEdit/${id}`)
            .then((res)=>{

                this.genCodeForm.id = res.data.id;
                this.genCodeForm.category = res.data.category;
                this.genCodeForm.code = res.data.code;
                this.genCodeForm.descr = res.data.descr;
                this.genCodeForm.descr_lao = res.data.descr_lao;
                this.genCodeForm.used = res.data.used;

            }).catch((error)=>{
                console.log(error);
            })
        },

        genCodeUpd(){
            this.$axios.post('/api/lookupSett/genCodeUpd', this.genCodeForm)
            .then((res)=>{

                $('#genLookup').modal('hide');
                this.genCateSelected();

            }).catch((error)=>{
                console.log(error);
            })
        },

        genCodeDel(id){
            // if (this.loginPermiss.bank_del == 0){
            //     this.$swal.fire({
            //         text: "You don't have permission to delete!",
            //         icon: 'error',
            //         showCancelButton: false,
            //         showConfirmButton: false,
            //         allowOutsideClick: false,
            //         timerProgressBar: true,
            //         timer: 1500
            //     })
            // } else {
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
                        this.$axios.post(`/api/lookupSett/genCodeDel/${id}`)
                        .then((response)=>{

                            this.genCateSelected();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
        },

        fuelCateSelected(){
            this.$axios.post('/api/lookupSett/fuelLookup', {
                cate: this.fuelCodeForm.category
            }).then(res => {
                this.fuelCodeData = res.data;
            })
        },

        newFuelLookup(){
            this.fuelCodeFormClear();
            this.bntAddShow = true;
            $('#fuelLookup').modal('show');
        },

        fuelCodeAdd(){
            this.$axios.post('/api/lookupSett/fuelCodeAdd', this.fuelCodeForm)
            .then((res) => {
                 if(res.data.success){

                    $('#fuelLookup').modal('hide');
                    this.fuelCateSelected();

                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        fuelCodeEdit(id){
            this.bntAddShow = false;
            this.fuelCodeFormClear();
            $('#fuelLookup').modal('show');

            this.$axios.post(`/api/lookupSett/fuelCodeEdit/${id}`)
            .then((res)=>{

                this.fuelCodeForm.id = res.data.id;
                this.fuelCodeForm.dept = res.data.dept;
                this.fuelCodeForm.category = res.data.category;
                this.fuelCodeForm.code = res.data.code;
                this.fuelCodeForm.descr = res.data.descr;
                this.fuelCodeForm.used = res.data.used;

            }).catch((error)=>{
                console.log(error);
            })
        },

        fuelCodeUpd(){
            this.$axios.post('/api/lookupSett/fuelCodeUpd', this.fuelCodeForm)
            .then((res)=>{

                $('#fuelLookup').modal('hide');
                this.fuelCateSelected();

            }).catch((error)=>{
                console.log(error);
            })
        },

        fuelCodeDel(id){
            // if (this.loginPermiss.bank_del == 0){
            //     this.$swal.fire({
            //         text: "You don't have permission to delete!",
            //         icon: 'error',
            //         showCancelButton: false,
            //         showConfirmButton: false,
            //         allowOutsideClick: false,
            //         timerProgressBar: true,
            //         timer: 1500
            //     })
            // } else {
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
                        this.$axios.post(`/api/lookupSett/fuelCodeDel/${id}`)
                        .then((response)=>{

                            this.fuelCateSelected();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
        },

        async getEquip(){
			const res = await axios.get(`/api/lookupSett/equipment?search=${this.search}`)
            this.equipData = res.data;
        },

        async searchChange(){
            if(this.search.length >0){
                this.btnClear = true;
                const data = await this.getEquip();
            } else {
                this.btnClear = false;
                const data = await this.getEquip();
            }
        },

        equipSelected(key, equip_no){
            this.equipSelectedRow = key;
            this.workOrderForm.equip_no = equip_no;
            this.workOrder()
        },

        workOrder(){
            this.$axios.post('/api/lookupSett/workOrder', {
                    equip_no: this.workOrderForm.equip_no
            }).then(res => {
                this.workOrderData = res.data;
            })
        },

        newEquip(){
            this.equipFormClear();
            this.bntAddShow = true;
            $('#equip').modal('show');

        },

        equipAdd(){
            this.$axios.post('/api/lookupSett/equipAdd', this.equipForm)
            .then((res) => {
                 if(res.data.success){
                    
                    this.btnClear = true;
                    this.search = this.equipForm.equip_no;
                    $('#equip').modal('hide');  
                    this.getEquip();

                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        equipEdit(id){
            this.bntAddShow = false;
            this.equipFormClear();
            $('#equip').modal('show');

            this.$axios.post(`/api/lookupSett/equipEdit/${id}`)
            .then((res)=>{

                this.equipForm.id = res.data.id;
                this.equipForm.equip_no = res.data.equip_no;
                this.equipForm.equip_name = res.data.equip_name;
                this.equipForm.equip_descr = res.data.equip_descr;

            }).catch((error)=>{
                console.log(error);
            })
        },

        equipUpd(){
            this.$axios.post('/api/lookupSett/equipUpd', this.equipForm)
            .then((res)=>{

                $('#equip').modal('hide');
                this.btnClear = true;
                this.search = this.equipForm.equip_no;
                this.workOrderForm.equip_no = this.equipForm.equip_no;
                this.getEquip();
                this.workOrder();

            }).catch((error)=>{
                console.log(error);
            })
        },

        equipDel(id){
            // if (this.loginPermiss.bank_del == 0){
            //     this.$swal.fire({
            //         text: "You don't have permission to delete!",
            //         icon: 'error',
            //         showCancelButton: false,
            //         showConfirmButton: false,
            //         allowOutsideClick: false,
            //         timerProgressBar: true,
            //         timer: 1500
            //     })
            // } else {
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
                        this.$axios.post(`/api/lookupSett/equipDel/${id}`)
                        .then((response)=>{

                            this.getEquip();
                            this.workOrder();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
        },

        newWorkOrder(){
            this.workOrderFormClear();
            this.bntAddShow = true;
            $('#workOrder').modal('show');

        },

        workOrderAdd(){
            this.$axios.post('/api/lookupSett/workOrderAdd', this.workOrderForm)
            .then((res) => {
                 if(res.data.success){

                    $('#workOrder').modal('hide');
                    this.workOrder();

                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        workOrderEdit(id){
            this.bntAddShow = false;
            this.workOrderFormClear();
            $('#workOrder').modal('show');

            this.$axios.post(`/api/lookupSett/workOrderEdit/${id}`)
            .then((res)=>{

                this.workOrderForm.id = res.data.id;
                this.workOrderForm.equip_no = res.data.equip_no;
                this.workOrderForm.work_order = res.data.work_order;
                this.workOrderForm.basic_date = res.data.basic_date;
                this.workOrderForm.reserve_no = res.data.reserve_no;

            }).catch((error)=>{
                console.log(error);
            })
        },

        workOrderUpd(){
            this.$axios.post('/api/lookupSett/workOrderUpd', this.workOrderForm)
            .then((res)=>{

                $('#workOrder').modal('hide');
                this.workOrder();

            }).catch((error)=>{
                console.log(error);
            })
        },

        workOrderDel(id){
            // if (this.loginPermiss.bank_del == 0){
            //     this.$swal.fire({
            //         text: "You don't have permission to delete!",
            //         icon: 'error',
            //         showCancelButton: false,
            //         showConfirmButton: false,
            //         allowOutsideClick: false,
            //         timerProgressBar: true,
            //         timer: 1500
            //     })
            // } else {
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
                        this.$axios.post(`/api/lookupSett/workOrderDel/${id}`)
                        .then((response)=>{

                            this.workOrder();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
        },

        dateChanged(){
            this.workOrderForm.year_mont = moment(this.workOrderForm.basic_date).format('YYYYMM');
        },

        searchClear(){
            this.search = '';
            this.btnClear = false,
            this.getEquip();
        },

        genCodeFormClear(){
            this.genCodeForm.code = '';
            this.genCodeForm.descr = '';
            this.genCodeForm.descr_lao = '';
            this.genCodeForm.used = '1';
        },

        fuelCodeFormClear(){
            this.fuelCodeForm.dept = '';
            this.fuelCodeForm.code = '';
            this.fuelCodeForm.descr = '';
            this.fuelCodeForm.used = '1';
        },

        equipFormClear(){
            this.equipForm.equip_no = '';
            this.equipForm.equip_name = '';
            this.equipForm.equip_descr = '';
        },

        workOrderFormClear(){
            this.workOrderForm.work_order = '';
            this.workOrderForm.basic_date = '';
            this.workOrderForm.reserve_no = '';
            this.workOrderForm.year_mont = '';
        },

        async getLookup(){
            const genCode = await axios.get('/api/lookupSett/genCategory')
            this.genCodeCate = genCode.data;

            const fuelCode = await axios.get('/api/lookupSett/fuelCategory')
            this.fuelCodeCate = fuelCode.data;
            
            const depts = await axios.get('/api/lookup/depts')
            this.lkDept = depts.data;  
        },
    },

    created(){
        this.getLookup();
    },

     beforeRouteEnter(to, from, next){
        if(!window.Laravel.isLoggedin_laravel){
            window.location.href = "/signin";
        }
        next();
	},
};
</script>

<style>

</style>