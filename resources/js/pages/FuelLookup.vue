<template>

    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">Lookup Code</h4>
                <!-- <i class="mdi mdi-dots-horizontal text-gray"></i> -->
            </div>
            <p class="tx-12 tx-gray-500 mb-0">Fuel reservation Lookup code...</p>
        </div>
        
        <div class="card-body pt-2">
            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3">
                    <div class="table-responsive element border ht-300">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px">
                                <tr>
                                    <th class="border-0">Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover cur-pointer" v-for="(lst, key) in categoryList" :key="lst.id" @click="selectedCate(key, lst.category)" :style="key === cateRowSel ? 'background-color: #dadee7; color: blue' : ''">
                                    <td class="border-0">{{lst.category}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-xl-10 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3" >
                    <div class="table-responsive element border ht-300">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>  
                                    <th class="border-start-0">Code</th>
                                    <th>Description</th>
                                    <th>Active</th>
                                    <th>Created At</th>
                                    <th>Created By</th>
                                    <th>Updated At</th>
                                    <th>Updated By</th>
                                    <th class="border-end-0 wd-80p">Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover" v-for="lst in codeList" :key="lst.id">
                                    <td class="border-start-0">{{lst.code}}</td>
                                    <td>{{lst.descr}}</td>
                                    <td class="text-capitalize">{{!!parseInt(lst.used)}}</td>
                                    <td class="text-muted">{{dateTime2(lst.created_at)}}</td>
                                    <td class="text-muted text-capitalize">{{lst.created_by}}</td>
                                    <td class="text-muted">{{dateTime2(lst.updated_at)}}</td>
                                    <td class="text-muted text-capitalize">{{lst.updated_by}}</td>

                                    <td class="p-0 align-middle border-end-0">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="editCode(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td> 
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="btnNewCode" class="d-flex justify-content-start align-items-center cur-pointer add-hover pd-l-10 ht-30 wd-100" @click="newCode()" title="Create a new item">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">New</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade effect-scale" id="code" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="codeLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label">{{codeForm.category}}</h6>
                    </div>
                    <div class="modal-body pt-2">  
                        <div class="form-group">
                            <label class="mb-0">Code  <span class=" text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="codeForm.code">
                        </div>   
                        <div class="form-group">
                            <label class="mb-0">Description</label>
                            <input type="text" class="form-control" v-model="codeForm.descr">
                        </div>   
                        <div class="form-group">
                            <label class="mb-0">Active</label> 
                            <Multiselect v-model="codeForm.used" :options="lkUsed"/>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button v-if="bntAddShow" type="button" class="btn btn-primary" :class="addCodeDis" @click="addCode()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                            <button v-else type="button" class="btn btn-primary" :class="addCodeDis" @click="updCode()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                            <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>
    </div>

    <!-- Equipment and Work Order -->
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mg-b-0">Equipment And Work Order</h4>
                    <p class="tx-12 tx-gray-500 mb-0">Equipment and Work Order Lookup code...</p>
                </div>
                <div class="d-flex justify-content-start">
                    <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                        <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
                    </button> 
                    <div class="dropdown-menu tx-13">
                        <div class="dropdown-item cur-pointer dropdown-hover" @click="ExportExcel()">
                            <i class="far fa-file-excel me-2"></i><span>Import Form Excel</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body pt-0">
            <div class="d-flex justify-content-end align-items-center mt-xl-0 mt-lg-0 mt-sm-0 mt-2 mb-1 ">
                <div class="pos-relative wd-xl-200 wd-lg-200 wd-md-200 wd-100p">
                    <input class="form-control" style="padding-left: 25px" type="text" v-model="search" placeholder="Search for no..." @input="searchChange()" >
                        <i class="fe fe-search search-i text-muted"></i>
                    <button class="btn btn-icon btn-sm search-c text-muted" v-if="btnClear" @click="searchClear()"><i class="fe fe-x"></i></button>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-3 col-md-4 col-sm-3">
                    <!-- <div class="breadcrumb-header d-flex justify-content-between align-items-center mt-xl-0 mt-lg-0 mt-sm-0 mt-2 mb-1 ">
                        <h4 class="card-title text-capitalize text-muted mb-0">Equipments</h4>
                        <div class="pos-relative wd-xl-200 wd-lg-200 wd-md-200 wd-100p">
                            <input class="form-control form-control-sm pd-l-30 ht-30" type="text" placeholder="Search equip no..." v-model="search" @input="searchChange()" >
                                <i class="fe fe-search search-i text-muted"></i>
                            <button class="btn btn-icon btn-sm search-c text-muted" v-if="btnClear" @click="searchClear()"><i class="fe fe-x"></i></button>
                        </div>
                    </div> -->

                    <div class="table-responsive element border ht-300">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px">
                                <tr>
                                    <th class="border-start-0">Equip No</th>
                                    <th>Equip Name</th>
                                    <th>Equip Description</th>
                                    <th class="border-end-0 wd-80p">Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover cur-pointer" v-for="(lst, key) in equipList" :key="lst.id" @click="selectedEquip(key, lst.equip_no)" :style="key === equipRowSel ? 'background-color: #dadee7; color: blue' : ''">
                                    <td class="border-start-0">{{lst.equip_no}}</td>
                                    <td>{{lst.equip_name}}</td>
                                    <td class="laofont">{{lst.equip_descr}}</td>
                                    <td class="p-0 align-middle border-end-0">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="editEquip(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <!-- <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Delete" @click="headDel(lst.id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button>  -->
                                        </div>
                                    </td> 
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover pd-l-10 ht-30 wd-100" @click="newEquip()" title="Create a new item">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">New</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3" >
                    <!-- <div class="d-flex justify-content-start align-items-center ht-30 mb-1">
                        <h4 class="card-title text-capitalize text-muted mb-0">Work Order Number</h4>
                    </div> -->
                    <div class="table-responsive element border ht-300">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px">
                                <tr>  
                                    <th class="border-start-0">Equip No</th>
                                    <th>Work Order</th>
                                    <th>Reserve No</th>
                                    <th>Basic Date</th>
                                    <th>Year/Month</th>
                                    <th class="border-end-0 wd-80p">Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover" v-for="lst in workorderList" :key="lst.id">
                                    <td class="border-start-0">{{lst.equip_no}}</td>
                                    <td>{{lst.work_order}}</td>
                                    <td>{{lst.reserve_no}}</td>
                                    <td>{{dateTime(lst.basic_date)}}</td>
                                    <td>{{lst.year_mont}}</td>
                                    <td class="p-0 align-middle border-end-0">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Edit" @click="editWorkOrder(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <!-- <button class="btn btn-icon btn-sm btn-i wd-25 ht-25" title="Delete" @click="headDel(lst.id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button>  -->
                                        </div>
                                    </td> 
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="btnNewOrder" class="d-flex justify-content-start align-items-center cur-pointer add-hover pd-l-10 ht-30 wd-100" @click="newWorkOrder()" title="Create a new item">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">New</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Equipment -->
        <div class="modal fade effect-scale" id="equip" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="equipLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label">Equipments</h6>
                    </div>
                    <div class="modal-body pt-2">  
                        <div class="form-group">
                            <label class="mb-0">Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="equipForm.equip_no">
                        </div>   
                        <div class="form-group">
                            <label class="mb-0">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="equipForm.equip_name">
                        </div>   
                        <div class="form-group">
                            <label class="mb-0">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="equipForm.equip_descr">
                        </div>   
                        <div class="d-flex justify-content-end">
                            <button v-if="bntAddShow" type="button" class="btn btn-primary" :class="addEquipDis" @click="addEquip()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                            <button v-else type="button" class="btn btn-primary" :class="addEquipDis" @click="updEquip()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                            <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Modal Work order -->
        <div class="modal fade effect-scale" id="workOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="workOrderLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label">Work Order</h6>
                    </div>
                    <div class="modal-body pt-2">  
                        <div class="form-group">
                            <label class="mb-0">Work Order  <span class=" text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="workOrderForm.work_order">
                        </div>   
                        <div class="form-group">
                            <label class="mb-0">Reserve No</label>
                            <input type="text" class="form-control" v-model="workOrderForm.reserve_no">
                        </div>   
                        <div class="form-group">
                            <label class="mb-0">Basic Date</label>
                            <input type="date" class="form-control" v-model="workOrderForm.basic_date">
                        </div>   
                        <div class="d-flex justify-content-end">
                            <button v-if="bntAddShow" type="button" class="btn btn-primary" :class="addWorkOrderDis" @click="addWorkOrder()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                            <button v-else type="button" class="btn btn-primary" :class="addWorkOrderDis" @click="updWorkOrder()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                            <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>
    </div>

</template>

<script>
export default {
    name: 'XpplAppFuelLookup',

    data() {
        return {
            categoryList: [],
            codeList: [],
            equipList: [],
            workorderList: [],

            codeForm: {id: '', dept: '', category: '', code: '', descr: '', used: '1'},
            equipForm: {id: '', equip_no: '', equip_name: '', equip_descr: ''},
            workOrderForm: {id: '', equip_no: '', work_order: '', basic_date: '', reserve_no: '', year_mont: ''},

            cateRowSel: '',
            equipRowSel: '',

            btnNewCode: '',
            bntAddShow: true,
            btnNewOrder: '',

            search: '',
            btnClear: false,

            lkUsed:[
                {value: 1, label: 'True'},
                {value: 0, label: 'False'}
            ],
        };
    },

    mounted() {
        
    },

    computed: {
        addCodeDis(){
            if (this.codeForm.code == '' || this.codeForm.used == null){
                return 'disabled';
            } else {
                return '';
            }
        },

        addEquipDis(){
            if (this.equipForm.equip_no == '' || this.equipForm.equip_name == '' || this.equipForm.equip_descr == ''){
                return 'disabled';
            } else {
                return '';
            }
        },

        addWorkOrderDis(){
            if (this.workOrderForm.work_order == '' || this.workOrderForm.basic_date == '') {
                return 'disabled';
            } else {
                return '';
            }
        }
    },

    methods: {
        async onLoad(){
            const profile = await axios.get('api/profile')
			this.codeForm.dept = profile.data.department;

            const cate = await axios.get('/api/fuel/category')
            this.categoryList = cate.data;

            this.selectedCate(0, 'Approve By');
            // this.selectedEquip(350, 'XDT42');
        },

        selectedCate(key, cate){
            this.cateRowSel = key;
            this.codeForm.category = cate;
            this.btnNewCode = true;

            this.refreshCode();
        },

        refreshCode(){
            this.$axios.post('/api/fuel/code', {
                dept: this.codeForm.dept,
                cate: this.codeForm.category
            }).then(res => this.codeList = res.data)
        },

        newCode(){
            this.codeFormClear();
            this.bntAddShow = true;
            $('#code').modal('show');
        },

        addCode(){
            this.$axios.post('/api/lookupSett/fuelCodeAdd', this.codeForm)
            .then((res) => {
                 if(res.data.success){

                    $('#code').modal('hide');
                    this.refreshCode();

                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        editCode(id){
            this.bntAddShow = false;
            this.codeFormClear();
            $('#code').modal('show');

            this.$axios.post(`/api/lookupSett/fuelCodeEdit/${id}`)
            .then((res)=>{

                this.codeForm.id = res.data.id;
                this.codeForm.dept = res.data.dept;
                this.codeForm.category = res.data.category;
                this.codeForm.code = res.data.code;
                this.codeForm.descr = res.data.descr;
                this.codeForm.used = res.data.used;

            }).catch((error)=>{
                console.log(error);
            })
        },

        updCode(){
            this.$axios.post('/api/lookupSett/fuelCodeUpd', this.codeForm)
            .then((res)=>{

                $('#code').modal('hide');
                this.refreshCode();

            }).catch((error)=>{
                console.log(error);
            })
        },

        async getEquip(){
			const res = await axios.get(`/api/lookupSett/equipment?search=${this.search}`)
            this.equipList = res.data;
        },

        async searchChange(){
            if(this.search.length >0){
                this.btnClear = true;
                this.workorderList = '';
                this.btnNewOrder = '';
                this.equipRowSel = '';
                const data = await this.getEquip();
            } else {
                this.btnClear = false;
                const data = await this.getEquip();
            }
        },

        selectedEquip(key, equip_no){
            this.equipRowSel = key;
            this.btnNewOrder = true;
            this.workOrderForm.equip_no = equip_no;
            this.workOrder()
        },

        workOrder(){
            this.$axios.post('/api/lookupSett/workOrder', {
                    equip_no: this.workOrderForm.equip_no
            }).then(res => {
                this.workorderList = res.data;
            })
        },

        newEquip(){
            this.equipFormClear();
            this.bntAddShow = true;
            $('#equip').modal('show');

        },

        addEquip(){
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

        editEquip(id){
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

        updEquip(){
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

        newWorkOrder(){
            this.workOrderFormClear();
            this.bntAddShow = true;
            $('#workOrder').modal('show');

        },

        addWorkOrder(){
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

        editWorkOrder(id){
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

        updWorkOrder(){
            this.$axios.post('/api/lookupSett/workOrderUpd', this.workOrderForm)
            .then((res)=>{

                $('#workOrder').modal('hide');
                this.workOrder();

            }).catch((error)=>{
                console.log(error);
            })
        },



        searchClear(){
            this.search = '';
            this.btnClear = false;
            this.workorderList = '';
            this.btnNewOrder = '';
            this.equipRowSel = '';
            this.getEquip();
        },

        codeFormClear(){
            let f = this.codeForm;
                f.code = '';
                f.descr = '';
        },

        equipFormClear(){
            let f = this.equipForm;
                f.equip_no = '';
                f.equip_name = '';
                f.equip_descr = '';
        },

        workOrderFormClear(){
            let f = this.workOrderForm;
                f.work_order = '';
                f.reserve_no = '';
                f.basic_date = '';
        },

        dateTime(value) {
            if(value) {
                return moment(value).format('DD-MM-YYYY');
            }
        },

        dateTime1(value) {
            if(value) {
                return moment(value).format('YYYY-MM-DD');
            }
        },

        dateTime2(value) {
            if(value) {
                return moment(value).format('DD-MM-YYYY HH:mm');
            }
        }
    },

    created() {
        this.onLoad();
        this.getEquip();
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