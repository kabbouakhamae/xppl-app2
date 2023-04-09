<template>
    <div>

            <div class="breadcrumb-header justify-content-between align-items-center mb-3 mt-0" >
                <div class="d-flex">
                    <h4 class="card-title text-muted mb-0 my-auto">Permission Setting</h4>
                </div>
                <div class="d-flex my-xl-auto right-content align-items-center wd-sm-300">         
                    <Multiselect class="me-1" placeholder="Select name" searchable="true" searchStart="true" v-model="selUser" @select="permissEdit()" :options="userList"/>
                    <div class="wd-35">
                        <button type="button" class="btn btn-primary wd-35 p-0" :class="btnSave" title="Save" @click="permissUpdate()">
                        <i class="fe fe-save tx-18"></i></button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div v-if="!!parseInt(permiss.gen_sett)" class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Permission</h6>
                            <div class="d-flex justify-content-between">
                                <span>Permission Access</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="permiss"  v-model="permissForm.permiss" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="permiss"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>General Permission</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="gen_sett"  v-model="permissForm.gen_sett" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="gen_sett"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>PR Permission</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="hqpr_sett"  v-model="permissForm.hqpr_sett" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="hqpr_sett"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Generals</h6>
                            <div class="d-flex justify-content-between">
                                <span>Excel Macro</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="mac"  v-model="permissForm.mac_acc" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="mac"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Admin Tasks</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="adm"  v-model="permissForm.adm_acc" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="adm"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Employee</h6>
                            <div class="d-flex justify-content-between">
                                <span>View All</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="empall"  v-model="permissForm.empAll" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="empall"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Add</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="empadd"  v-model="permissForm.empAdd" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="empadd"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Delete</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="empdel"  v-model="permissForm.empDel" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="empdel"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Edit</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="empedit"  v-model="permissForm.empEdit" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="empedit"></label>
                                </div>
                            </div>
                            <div v-if="permissForm.empEdit">
                                <h6 class="text-muted mt-4">Other Delete</h6>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Details</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="detaildel" v-model="permissForm.detailDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="detaildel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Contact Person</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="contdel" v-model="permissForm.contDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="contdel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Bank Account</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="bankdel" v-model="permissForm.bankDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="bankdel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Cards</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="carddel" v-model="permissForm.cardDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="carddel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Annual Leave</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="aldel" v-model="permissForm.alDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="aldel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Document and File</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="filedel" v-model="permissForm.fileDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="filedel"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
       
                <!-- Clinic -->
                <div v-if="!!parseInt(permiss.gen_sett)" class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Safety</h6>
                            <div class="d-flex justify-content-between mt-2">
                                <span>Access</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="safAcc"  v-model="permissForm.safety" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="safAcc"></label>
                                </div>
                            </div>
                            <div v-if="permissForm.safety">
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Add</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="safAdd"  v-model="permissForm.saf_add" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="safAdd"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Edit/Update</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="safEit"  v-model="permissForm.saf_edit" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="safEit"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Delete</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="safDel"  v-model="permissForm.saf_del" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="safDel"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Clinic</h6>
                            <div class="d-flex justify-content-between">
                                <span>Access</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="cliAcc"  v-model="permissForm.clinic_acc" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="cliAcc"></label>
                                </div>
                            </div>
                            <div v-if="permissForm.clinic_acc">
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Add</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="cliAdd" v-model="permissForm.clinic_add" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="cliAdd"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Edit/Update</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="cliEdit" v-model="permissForm.clinic_edit" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="cliEdit"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Delete</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="cliDel" v-model="permissForm.clinic_del" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="cliDel"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!!parseInt(permiss.gen_sett)" class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Roster</h6>
                            <div class="d-flex justify-content-between">
                                <span>Management</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="rosEdit" v-model="permissForm.rosEdit" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="rosEdit"></label>
                                </div>
                            </div>
                            <div v-if="permissForm.rosEdit">
                                <div class="d-flex justify-content-between mt-2">
                                    <span>All Department</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="rosAll" v-model="permissForm.rosAll" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="rosAll"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Fuels</h6>
                            <div class="d-flex justify-content-between">
                                <span>Access</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="fuel" v-model="permissForm.fuel" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="fuel"></label>
                                </div>
                            </div>
                            <div v-if="permissForm.fuel">
                                <div class="d-flex justify-content-between mt-2">
                                    <span>View All</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="fuelall" v-model="permissForm.fuelAll" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="fuelall"></label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
             
                <!-- HQ PR -->
                <div v-if="!!parseInt(permiss.hqpr_sett)" class="col-xl-3">
                    <div  class="card">
                        <div class="card-body">
                            <h6 class="card-title">Payment Requisition</h6>
                            <div class="d-flex justify-content-between">
                                <span>Access</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="hqpr_acc"  v-model="permissForm.hqpr_acc" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="hqpr_acc"></label>
                                </div>
                            </div>
                            <div v-if="permissForm.hqpr_acc">
                                <div class="d-flex justify-content-between mt-2">
                                    <span>View All</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="hqpr_all" v-model="permissForm.hqpr_all" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="hqpr_all"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Add</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="hqpr_add" v-model="permissForm.hqpr_add" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="hqpr_add"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Edit/Update</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="hqpr_edit" v-model="permissForm.hqpr_edit" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="hqpr_edit"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Delete</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="hqpr_del" v-model="permissForm.hqpr_del" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="hqpr_del"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Approval</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="hqpr_app" v-model="permissForm.hqpr_app" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="hqpr_app"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>Notification</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="hqpr_noti" v-model="permissForm.hqpr_noti" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="hqpr_noti"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
     
    </div>
    
</template>

<script>
export default {
    name: 'XpplAppPermission',

    data() {
        return {
            permiss: [],
            userList: [],
            permissForm: {
                id: '',
                permiss: '',
                lkAdd: '',
                lkEdit: '',
                lkDel: '',
                empAll: '',
                empAdd: '',
                empEdit: '',
                empDel: '',
                detailDel: '',
                contDel: '',
                bankDel: '',
                cardDel: '',
                alDel: '',
                fileDel: '',
                fuel: '',
                fuelAll: '',
                rosAll: '',
                rosEdit: '',
                mac_acc: '',
                clinic_acc: '',
                clinic_add: '',
                clinic_edit: '',
                clinic_del: '',
                safety: '',
                saf_add: '',
                saf_edit: '',
                saf_del: '',
                adm_acc: '',
                gen_sett: "",
                hqpr_sett: "",
                hqpr_acc: "",
                hqpr_all: "",
                hqpr_add: "",
                hqpr_edit: "",
                hqpr_del: "",
                hqpr_app: "",
                hqpr_noti: "",
            },
            search: '',
            selectedRow: null,
            selUser: null,

            home: [],
            admin: [],
        };
    },

    mounted() {
        
    },
    computed:{
        btnSave(){
            if (this.selUser == null){
                return 'disabled';
            } else {
                return '';
            }
        }
    },

    methods: {
        async onLoad(){
            const homeRes = await axios.get('/api/permission/home')
            this.home = homeRes.data;

            const adminRes = await axios.get('/api/permission/admin')
            this.admin = adminRes.data;



        },

        async getPermiss(){
            const response = await axios.get('/api/permiss')
            this.permiss = response.data;
            // console.log(response);
        },

        getUsername(){
            this.$axios.get('/api/username')
            .then((res) => {
                this.userList = res.data;
            }).catch ((err) => {
                console.log(err);
            })
        },

        permissEdit(){
            this.resetForm();  
            this.$axios.post('/api/permissEdit', {
                id: this.selUser
            }).then((res) => {
            let f = this.permissForm;
                f.id = res.data.id;
                f.permiss = !!parseInt(res.data.permiss);
                f.lkAdd = !!parseInt(res.data.lk_add);
                f.lkEdit = !!parseInt(res.data.lk_edit);
                f.lkDel = !!parseInt(res.data.lk_del);
                f.empAll = !!parseInt(res.data.emp_all);
                f.empAdd = !!parseInt(res.data.emp_add);
                f.empEdit = !!parseInt(res.data.emp_edit);
                f.empDel = !!parseInt(res.data.emp_del);
                f.detailDel = !!parseInt(res.data.detail_del);
                f.contDel = !!parseInt(res.data.cont_del);
                f.bankDel = !!parseInt(res.data.bank_del);
                f.cardDel = !!parseInt(res.data.card_del);
                f.alDel = !!parseInt(res.data.al_del);
                f.fileDel = !!parseInt(res.data.file_del);
                f.fuel = !!parseInt(res.data.fuel);
                f.fuelAll = !!parseInt(res.data.fuel_all);
                f.rosAll = !!parseInt(res.data.ros_all);
                f.rosEdit = !!parseInt(res.data.ros_edit);
                f.mac_acc = !!parseInt(res.data.mac_acc);
                f.clinic_acc = !!parseInt(res.data.clinic_acc);
                f.clinic_add = !!parseInt(res.data.clinic_add);
                f.clinic_edit = !!parseInt(res.data.clinic_edit);
                f.clinic_del = !!parseInt(res.data.clinic_del);
                f.safety = !!parseInt(res.data.safety);
                f.saf_add = !!parseInt(res.data.saf_add);
                f.saf_edit = !!parseInt(res.data.saf_edit);
                f.saf_del = !!parseInt(res.data.saf_del);
                f.adm_acc = !!parseInt(res.data.adm_acc);
                f.hqpr_acc = !!parseInt(res.data.hqpr_acc);
                f.hqpr_all = !!parseInt(res.data.hqpr_all);
                f.hqpr_add = !!parseInt(res.data.hqpr_add);
                f.hqpr_edit = !!parseInt(res.data.hqpr_edit);
                f.hqpr_del = !!parseInt(res.data.hqpr_del);
                f.hqpr_app = !!parseInt(res.data.hqpr_app);
                f.hqpr_noti = !!parseInt(res.data.hqpr_noti);
                f.hqpr_sett = !!parseInt(res.data.hqpr_sett);
                f.gen_sett = !!parseInt(res.data.gen_sett);

            }).catch((err) => {
                console.log(err);
            })
        },

        permissUpdate(){
            this.$axios.post('api/permissUpdate', this.permissForm)
            .then((response) => {

                this.getPermiss();
                window.location.reload();

            }).catch ((err) => {
                console.log(err);
            })
        },

        resetForm(){
            let f = this.permissForm;
                f.permiss = '';
                f.lkAdd = '';
                f.lkEdit = '';
                f.lkDel = '';
                f.empAll = '';
                f.empAdd = '';
                f.empEdit = '';
                f.empDel = '';
                f.detailDel = '';
                f.contDel = '';
                f.bankDel = '';
                f.cardDel = '';
                f.alDel = '';
                f.fileDel = '';
                f.fuel = '';
                f.fuelAll = '';
                f.rosAll = '';
                f.rosEdit = '';
                f.mac_acc = '';
                f.clinic_acc = '';
                f.clinic_add = '';
                f.clinic_edit = '';
                f.clinic_del = '';
                f.safety = '';
                f.saf_add = '';
                f.saf_edit = '';
                f.saf_del = '';
                f.adm_acc = '';
                f.hqpr_acc = "";
                f.hqpr_all = "";
                f.hqpr_add = "";
                f.hqpr_edit = "";
                f.hqpr_del = "";
                f.hqpr_app = "";
                f.hqpr_noti = "";
                f.hqpr_sett = "";
                f.gen_sett = "";
        },
            
    },

    created(){
        this.getPermiss();
        this.getUsername();
        this.onLoad();
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