<template>
    <div>
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">Lookup Code</h4>
                    <!-- <i class="mdi mdi-dots-horizontal text-gray"></i> -->
                </div>
                <p class="tx-12 tx-gray-500 mb-0">Incident recode Lookup code...</p>
            </div>
            
            <div class="card-body pt-2">
                <div class="row">
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3">
                        <div class="table-responsive element border" style="max-height: 500px">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px">
                                    <tr>
                                        <th class="border-0">Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-hover cur-pointer" v-for="(lst, key) in cateData" :key="lst.id" @click="selectedCate(key, lst.category)" :style="key === cateRowSel ? 'background-color: #dadee7; color: blue' : ''">
                                        <td class="border-0">{{lst.category}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-xl-10 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3" >
                        <div class="table-responsive element border" style="max-height: 500px">
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
                                    <tr class="tr-hover" v-for="lst in codeData" :key="lst.id">
                                        <td class="border-start-0">{{lst.code}}</td>
                                        <td class="laofont">{{lst.descr}}</td>
                                        <td class="text-capitalize">{{!!parseInt(lst.active)}}</td>
                                        <td class="text-muted">{{dateTime2(lst.created_at)}}</td>
                                        <td class="text-muted text-capitalize">{{lst.created_by}}</td>
                                        <td class="text-muted">{{dateTime2(lst.updated_at)}}</td>
                                        <td class="text-muted text-capitalize">{{lst.updated_by}}</td>

                                        <td class="p-0 align-middle border-end-0">
                                            <div class="d-flex justify-content-start ms-1">
                                                <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="editCode(lst.id)">
                                                    <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                </button> 
                                                <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="delCode(lst.id)">
                                                    <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
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
                                <input type="text" class="form-control laofont" v-model="codeForm.descr">
                            </div>   
                            <div class="form-group">
                                <label class="mb-0">Active</label> 
                                <Multiselect v-model="codeForm.active" :options="lkActive"/>
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
    </div>
</template>

<script>
export default {
    name: 'XpplAppIncidentLookup',

    data() {
        return {
            cateData: [],
            codeData: [],
            codeForm: {id: '', category: '', code: '', descr: '', active: '1'},
            cateRowSel: '',

            btnNewCode: '',
            bntAddShow: true,

            lkActive:[
                {value: 1, label: 'True'},
                {value: 0, label: 'False'}
            ],
        };
    },

    mounted() {
        
    },
    computed: {
        addCodeDis(){
            if (this.codeForm.code == '' || this.codeForm.active == null){
                return 'disabled';
            } else {
                return '';
            }
        }
    },

    methods: {
        async onLoad(){
            // const profile = await axios.get('api/profile')
			// this.codeForm.dept = profile.data.department;

            const cate = await axios.get('/api/safety/category')
            this.cateData = cate.data;

            this.selectedCate(0, 'Action Status');

        },

        selectedCate(key, cate){
            this.cateRowSel = key;
            this.codeForm.category = cate;
            this.btnNewCode = true;

            this.refreshCode();
        },

        refreshCode(){
            this.$axios.post('/api/safety/code', {
                cate: this.codeForm.category
            })
            .then(res => this.codeData = res.data)
        },

        newCode(){
            this.codeFormClear();
            this.bntAddShow = true;
            $('#code').modal('show');
        },

        addCode(){
            this.$axios.post('/api/safety/addcode', this.codeForm)
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

            this.$axios.post(`/api/safety/editcode/${id}`)
            .then((res)=>{

                this.codeForm.id = res.data.id;
                this.codeForm.category = res.data.category;
                this.codeForm.code = res.data.code;
                this.codeForm.descr = res.data.descr;
                this.codeForm.active = res.data.active;

            })
        },

        updCode(){
            this.$axios.post('/api/safety/updcode', this.codeForm)
            .then((res)=>{

                $('#code').modal('hide');
                this.refreshCode();

            }).catch((error)=>{
                console.log(error);
            })
        },

        delCode(id){
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
                        this.$axios.post(`/api/safety/delcode/${id}`)
                        .then((response)=>{

                            this.refreshCode();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
        },

        codeFormClear(){
            let f = this.codeForm;
                f.code = '';
                f.descr = '';
        },

        dateTime2(value) {
            if(value) {
                return moment(value).format('DD-MM-YYYY HH:mm');
            }
        }
    },

    created() {
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

<style lang="scss" scoped>

</style>