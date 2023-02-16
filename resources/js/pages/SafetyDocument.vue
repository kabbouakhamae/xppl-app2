<template>
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">Documents</h4>
                <!-- <i class="mdi mdi-dots-horizontal text-gray"></i> -->
            </div>
            <p class="tx-12 tx-gray-500 mb-0">Forms, Files and other Documents...</p>
        </div>
            
        <div class="card-body pt-2">
            <div class="row">
                <!-- <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3">
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
                </div> -->

                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3">
                    <div class="table-responsive element border" style="max-height: 500px">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px">
                                <tr>
                                    <th class="border-0">Category</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover cur-pointer" v-for="(lst, key) in cateData" :key="lst.id" @click="cateSelected(key, lst.category), btnNewFile ='true'" :style="key === cateRowSel ? 'background-color: #dadee7; color: blue' : ''">
                                    <td class="border-0">{{lst.category}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 pt-1 pb-2 wd-100" @click="newCate()" title="Create a new category">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">New</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-10 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3" >
                    <div class="table-responsive element border" style="max-height: 500px">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>  
                                    <th class="border-start-0">File Name</th>
                                    <th>Description</th>
                                    <th>Last Update</th>
                                    <th>Updated At</th>
                                    <th>Updated By</th>
                                    <th class="border-end-0 wd-80p">Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover" v-for="lst in fileData" :key="lst.id">
                                    <td class="border-start-0">{{filename(lst.file_name)}}</td>
                                    <td class="laofont">{{lst.file_descr}}</td>
                                    <td class="text-muted">{{lst.last_update}}</td>
                                    <td class="text-muted">{{lst.updated_at}}</td>
                                    <td class="text-muted text-capitalize">{{lst.updated_by}}</td>

                                    <td class="p-0 align-middle border-end-0">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Download file" @click="downloadFile(lst.file_name)">
                                                <i class="bx bxs-download text-primary" style="font-size: 16px"></i>
                                            </button> 

                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="editCode(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="delFile(lst.file_name)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td> 
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="btnNewFile" class="d-flex justify-content-start align-items-center cur-pointer add-hover pd-l-10 ht-30 wd-100" @click="newFile()" title="Create a new item">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">New</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Category -->
    <div class="modal fade effect-scale" id="category" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cateLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-1 bd-b-0">
                    <h6 class="main-content-label">Category</h6>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body pt-2">  
                    <div class="form-group">
                        <label class="mb-0">Code  <span class=" text-danger">*</span></label>
                        <input type="text" class="form-control laofont" v-model="cateForm.category">
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Description</label>
                        <textarea class="form-control laofont" style="height: 100px" v-model="cateForm.descr"></textarea>
                    </div>   
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" :class="addCodeDis" @click="addCate()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                        <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                    </div>
                </div>
            </div>
        </div>                                              
    </div>

    <!-- Modal File -->
    <div class="modal fade effect-scale" id="files" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="fileLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-1 bd-b-0">
                    <h6 class="main-content-label">{{fileForm.category}}</h6>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body mt-2">  


                    <!-- <div class="mt-1" style="margin-start: 11px">
                        <a class="add-hover p-0" href="#" @click="newFiles()" title="Add new file"><span class="tx-13">Add...</span></a>    
                        <input class="d-none" ref="fileInput" type="file" accept=".xlsm" @change="addFile()">
                    </div>  -->

                    <input type="file" @change="onSeclected" class="mb-3 cur-pointer" ref="inputFile">



                    <!-- <div class="form-group mt-4">
                        <label class="mb-0">File Name  <span class=" text-danger">*</span></label>
                        <input type="text" class="form-control laofont" v-model="fileForm.file_name">
                    </div>    -->


                    <div class="form-group">
                        <label class="mb-0">File Description</label>
                        <textarea class="form-control laofont" style="height: 100px" v-model="fileForm.file_descr"></textarea>
                    </div>   
                    <div class="form-group">
                        <label class="mb-0">Last Update</label>
                        <input type="date" class="form-control" v-model="fileForm.last_update">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" :class="addDocDis" @click="addFile()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                        <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                    </div>
                </div>
            </div>
        </div>                                              
    </div>


</template>

<script>
export default {
    name: 'XpplAppSafetyDocument',

    data() {
        return {
            cateData: [],
            fileData: [],

            cateForm: {id: '', category: '', descr: ''},
            fileForm: {id: '', category: '', file_name: '', file_descr: '', last_update: ''},

            cateRowSel: '',



        };
    },

    mounted() {
       
    },

    computed: {
        addDocDis(){
            if (this.fileForm.file_name == '' || this.fileForm.file_descr == ''){
                return 'disabled';
            } else {
                return '';
            }
        }
    },

    methods: {
        async onLoad(){

            const cate = await axios.get('/api/safety/safcategory')
            this.cateData = cate.data;

            const mn = await axios.get('/api/safety/getmin')
            this.cateSelected(0, mn.data);
            
        },

        getCategory(){
            this.$axios.get('/api/safety/safcategory')
            .then(res => {
                this.cateData = res.data;
            })
        },



        newCate(){
            // this.headForm.refuel_date = '';
            $('#category').modal('show');
        },

        addCate(){
            this.$axios.post('/api/safety/cateadd', this.cateForm)
            .then(res => {
                if(res.data.success){
                    $('#category').modal('hide');
                    
                    this.getCategory();
                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })

            
        },

        cateSelected(key, cate){
            this.cateRowSel = key;
            this.fileForm.category = cate;
            this.btnNewFile ='true';

            this.$axios.post('/api/safety/getfile', {
                cate: this.fileForm.category
            }).then(res => {
                this.fileData = res.data;
            })
        },

        newFile(){
            this.fileForm.file_name = '';
            this.$refs.inputFile.value = '';
            this.fileForm.file_descr = '';

            $('#files').modal('show');
        },

        // newFile(){
        //     this.$refs.fileInput.click();
        // },

        getFiles(){
            this.$axios.post('/api/safety/getfile', {
                cate: this.fileForm.category
            }).then(res => this.fileData = res.data)
        },

        onSeclected(event){
				// console.log(event.target.files[0]);

			this.fileForm.file_name = event.target.files[0];



				// let reader = new FileReader();
				// reader.readAsDataURL(this.imageProduct);
				// reader.addEventListener("load", function(){
				// 	this.imagePreview = reader.result;
				// }.bind(this), false);
		},

        addFile(){
            let fd = new FormData();
            fd.append('cate', this.fileForm.category);
            fd.append('file', this.fileForm.file_name);
            fd.append('descr', this.fileForm.file_descr);

            this.$axios.post('/api/safety/addfile', fd, {headers:{"Content-Type": "multipart/form-date"}})
                .then(response => {   
                    
                    $('#files').modal('hide');
                    
                    this.getFiles();

                }).catch (err => {
                    console.log(err);
                });

        },

        downloadFile(filename){
            const url = `/api/safety/downloaddoc/${filename}`;
            window.location.href = url;
        },

        delFile(filename){
            // if (this.loginPermiss.al_del == 0){
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
                        this.$axios.post(`/api/safety/deldoc/${filename}`)
                        .then((response)=>{

                            this.getFiles();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            // }
        },


        filename(text){
            if (text) {
                return text.split("-")[1];
            }

        },
        
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