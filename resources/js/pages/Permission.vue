<template>
    
        <div v-if="!!parseInt(loginPermiss.permiss)">
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
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Permission</h6>
                            <div class="d-flex justify-content-between">
                                <span>Access</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="permiss"  v-model="permissData.permiss" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="permiss"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Department Tools</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Geology</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="geo"  v-model="permissData.geo" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="geo"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Mining</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="mining"  v-model="permissData.mining" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="mining"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Safey</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="safety"  v-model="permissData.safety" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="safety"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Generals</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>View all Annual Leave</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="alall"  v-model="permissData.alAll" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="alall"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>View all Transport</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="tranall"  v-model="permissData.tranAll" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="tranall"></label>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Employee</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>View All</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="empall"  v-model="permissData.empAll" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="empall"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Add</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="empadd"  v-model="permissData.empAdd" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="empadd"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Delete</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="empdel"  v-model="permissData.empDel" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="empdel"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Edit</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="empedit"  v-model="permissData.empEdit" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="empedit"></label>
                                </div>
                            </div>
                            <div v-if="permissData.empEdit">
                                <h6 class="text-muted mt-4">Other Delete</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Details</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="detaildel" v-model="permissData.detailDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="detaildel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Contact Person</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="contdel" v-model="permissData.contDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="contdel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Bank Account</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="bankdel" v-model="permissData.bankDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="bankdel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Cards</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="carddel" v-model="permissData.cardDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="carddel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Annual Leave</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="aldel" v-model="permissData.alDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="aldel"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Document and File</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="filedel" v-model="permissData.fileDel" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="filedel"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Fuels</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Access</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="fuel" v-model="permissData.fuel" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="fuel"></label>
                                </div>
                            </div>
                            <div v-if="permissData.fuel">
                                <div class="d-flex justify-content-between">
                                    <span>View All</span>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="fuelall" v-model="permissData.fuelAll" @change='$emit("input", $event.target.checked)'>
                                        <label class="custom-control-label cur-pointer" for="fuelall"></label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Roster</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>All Department</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="rosAll" v-model="permissData.rosAll" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="rosAll"></label>
                                </div>
                            </div>
  
                            <div class="d-flex justify-content-between">
                                <span>Management</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="rosEdit" v-model="permissData.rosEdit" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="rosEdit"></label>
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
            loginPermiss: [],
            userList: [],
            permissData: {
                id: '',
                permiss: '',
                safety: '',
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
            this.loginPermiss = response.data;
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
            let dt = this.permissData;
                dt.id = res.data.id;
                dt.permiss = !!parseInt(res.data.permiss);
                dt.safety = !!parseInt(res.data.safety);
                dt.lkAdd = !!parseInt(res.data.lk_add);
                dt.lkEdit = !!parseInt(res.data.lk_edit);
                dt.lkDel = !!parseInt(res.data.lk_del);
                dt.empAll = !!parseInt(res.data.emp_all);
                dt.empAdd = !!parseInt(res.data.emp_add);
                dt.empEdit = !!parseInt(res.data.emp_edit);
                dt.empDel = !!parseInt(res.data.emp_del);
                dt.detailDel = !!parseInt(res.data.detail_del);
                dt.contDel = !!parseInt(res.data.cont_del);
                dt.bankDel = !!parseInt(res.data.bank_del);
                dt.cardDel = !!parseInt(res.data.card_del);
                dt.alDel = !!parseInt(res.data.al_del);
                dt.fileDel = !!parseInt(res.data.file_del);
                dt.fuel = !!parseInt(res.data.fuel);
                dt.fuelAll = !!parseInt(res.data.fuel_all);
                dt.rosAll = !!parseInt(res.data.ros_all);
                dt.rosEdit = !!parseInt(res.data.ros_edit);

            }).catch((err) => {
                console.log(err);
            })
        },

        permissUpdate(){
            this.$axios.post('api/permissUpdate', this.permissData)
            .then((response) => {

                this.getPermiss();
                window.location.reload();

            }).catch ((err) => {
                console.log(err);
            })
        },

        resetForm(){
            let dt = this.permissData;
                dt.permiss = '';
                dt.safety = '';
                dt.lkAdd = '';
                dt.lkEdit = '';
                dt.lkDel = '';
                dt.empAll = '';
                dt.empAdd = '';
                dt.empEdit = '';
                dt.empDel = '';
                dt.detailDel = '';
                dt.contDel = '';
                dt.bankDel = '';
                dt.cardDel = '';
                dt.alDel = '';
                dt.fileDel = '';
                dt.fuel = '';
                dt.fuelAll = '';
                dt.rosAll = '';
                dt.rosEdit = '';
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