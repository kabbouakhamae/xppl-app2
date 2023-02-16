<template>
    
    <div class="card">
            <div v-if="view=='list'">
                <div class="card-header pd-r-15 pd-t-10 pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-start align-items-center">
                            <h4 class="card-title mg-b-0 text-muted text-capitalize">Medical Records</h4>
                        </div>
                        <div class="d-flex justify-content-start">
                            <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                                <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
                            </button> 
                            <div class="dropdown-menu tx-13">
                                <div class="dropdown-item cur-pointer dropdown-hover" @click="exportIncident()">
                                    <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Export All Incidents</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pd-t-0 mt-1">                   
                    <!-- <div class="d-lg-flex justify-content-between mt-1 mb-1">
                        <div>
                            <span class="d-block mg-b-10 text-muted tx-12">All ...</span>
                        </div> -->
                        <div class="d-flex justify-content-end mt-xl-0 mt-lg-0 mt-md-1 mt-1">
                            <div class="pos-relative wd-lg-300 wd-100p">
                                <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search..." v-model="search" @input="searchChanged()" title="Search by Incident Number or Title">
                                    <i class="fe fe-search search-i text-muted"></i>
                                <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                            </div>

                            <div class="wd-35 ms-1">
                                <button type="button" class="btn btn-outline-primary p-0 border wd-35" title="Add new record" @click="NewName()">
                                    <i class="fa fa-plus tx-14"></i>
                                </button>
                            </div>
                        </div>
                    <!-- </div>  -->

                    <div class="table-responsive border-start border-end border-bottom mt-1" style="max-height: 73vh">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>
                                    <th class="border-start-0">No.</th>
                                    <!-- <th class="border-start-0">
                                        <div class="d-flex justify-content-between">
                                            <i v-if="filterMode =='inc_no'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_no'), filterName ='Incident Number'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_no'), filterName ='Incident Number'"></i>
                                        </div>
                                    </th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">Date
                                            <i v-if="filterMode =='inc_date'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_date'), filterName ='Incident Date'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_date'), filterName ='Incident Date'"></i>
                                        </div>
                                    </th> -->
                                    <th>gender</th>


                                    <th>name</th>

                                    <th>company</th>
                                    <th>department</th>
                                    <th>position</th>
                                    <th>phone</th>
                                    <th>employee id</th>
                                    <th>DOB</th>
                                    <th>address</th>
                                    <th>remarks</th>
                                    <th>created at</th>
                                    <th>created by</th>
                                    <th>updated at</th>
                                    <th>updated by</th>
      
                                    <th class="border-end-0 position-sticky wd-60p" style="right: -0.1px">Tools</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <!-- <tr v-for="(row, inx) in registerData" :key="row.id" class="tr-hover">
                                    <td class="border-start-0 tx-center" >{{inx +1}}</td>
                                    <th class="tx-center">{{row.inc_no}}</th>
                                    <td>{{dformat(row.inc_date)}}</td>
                                    <td class="laofont bg-danger cur-pointer" v-if="row.risk_rating >=20" @click="preview(row.id, row.inc_id)" title="Preview">{{cutWord(row.inc_title)}}</td>
                                    <td class="laofont cur-pointer" v-else @click="preview(row.id, row.inc_id)" title="Preview">{{cutWord(row.inc_title)}}</td>
                                    <td>{{row.company}}</td>
                                    <td>{{row.dept}}</td>
                                    <td>{{row.inc_group}}</td>
                                    <td>{{row.inc_type}}</td>
                                    <td>{{row.significant}}</td>
                                    <td>{{row.flash_alert}}</td>
                                    <td>{{row.injury_type}}</td>
                                    <td>{{row.injury_group}}</td>
                                    <td class="laofont">{{ cutWord(row.injury_part) }}</td>
                                    <td>{{row.actual_severity}}</td>
                                    <td>{{row.potential_severity}}</td>
                                    <td class="text-end bg-danger" v-if="row.risk_rating >= 20">{{row.risk_rating}}</td>
                                    <td class="text-end" v-else> {{row.risk_rating}} </td>
                                    <td>{{row.invest_status}}</td>
                                    <td>{{row.invest_lead}}</td>
                                    <td>{{row.action_status}}</td>
                                    <td>{{dformat(row.action_duedate)}}</td>
                                    <td>{{row.inc_manager}}</td>
                                    <td>{{row.followup_by}}</td>
                                    <td class="laofont">{{ cutWord(row.comments) }}</td>
                                    <td class="laofont"> {{ cutWord(row.inc_descr) }} </td>
                                    <td class="border-end-0 position-sticky p-0 align-middle" style="right: -0.1px; background-color: #FFFFFF">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Preview" @click="preview(row.id, row.inc_id)">
                                                <i class="bx bx-search text-primary" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="editIncident(row.id, row.inc_id), btn = false">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="delIncident(row.id, row.inc_id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td>
                                </tr>  -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- NEW REGISTER NAME -->
            <div v-if="view=='new'">
                <div class="card-body">
                    <h4 class="card-title mg-b-0 text-muted text-capitalize">Add new case ID {{registForm.userid}}</h4>
                    <div class="row mt-2">

                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">Gender <span class=" text-danger">*</span></label> 
                                <Multiselect v-model="registForm.gender" :options="lkGender"/>
                            </div>
                        </div> 

                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="registForm.name">
                            </div>
                        </div>

                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">Surname</label>
                                <input type="text" class="form-control" v-model="registForm.surname">
                            </div>
                        </div>

                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">Company <span class=" text-danger">*</span></label> 
                                <Multiselect v-model="registForm.gender" :options="lkGender"/>
                            </div>
                        </div> 
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">Department <span class=" text-danger">*</span></label> 
                                <Multiselect v-model="registForm.gender" :options="lkGender"/>
                            </div>
                        </div> 
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">Position</label> 
                                <Multiselect v-model="registForm.gender" :options="lkGender"/>
                            </div>
                        </div> 
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">Employee ID</label>
                                <input type="text" class="form-control" v-model="registForm.empid">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">Phone</label>
                                <input type="text" class="form-control" v-model="registForm.phne">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">DOB</label>
                                <input type="date" class="form-control" v-model="registForm.date_in">
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="mb-0">Address</label>
                                <input type="text" class="form-control" v-model="registForm.address">
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="mb-0">remarks</label>
                                <input type="text" class="form-control" v-model="registForm.remarks">
                            </div>
                        </div>


                    </div>

                        <!-- ATTACHEMENTS -->
                        <!-- <div v-if="attachMode">
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover wd-100" @click="newFiles1()" title="Add new files">
                                <div><i class="mdi mdi-paperclip me-1 text-primary tx-16"></i></div>
                                    <span class="text-primary tx-14">Attachments</span>
                                <input class="d-none" ref="fileInput1" type="file" multiple @change="addTmp()">
                            </div>
                            <div>
                                <div v-for="(lst, inx) in tmpFiles" :key="inx">
                                    <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Remove file" @click="delTmp(inx)">
                                                <i class="fe fe-x text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                        {{lst.name}} 
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div v-else>
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover wd-100" @click="newFiles2()" title="Add new files">
                                <div><i class="mdi mdi-paperclip me-1 text-primary tx-16"></i></div>
                                    <span class="text-primary tx-14">Attachments</span>
                                <input class="d-none" ref="fileInput2" type="file" multiple @change="addFiles()">
                            </div>
                            <div v-for="(lst, inx) in fileData" :key="inx">
                                <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                                    <div class="d-flex justify-content-start ms-1">
                                        <button class="btn btn-sm btn-icon btn-i p-0" title="Download file" @click="downloadFile(lst.file_name)">
                                            <i class="bx bxs-download text-primary" style="font-size: 16px"></i>
                                        </button> 
                                        <button class="btn btn-sm btn-icon btn-i p-0" title="Delete file" @click="delFile(lst.file_name)">
                                            <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                        </button> 
                                    </div>
                                    {{lst.file_name}} 
                                </div>
                            </div>
                        </div> -->

                    <div class="d-flex justify-content-end mt-2 pb-2">
                        <button v-if="btn" type="button" class="btn btn-primary" :class="incAddDis" @click="addIncident()">
                            <i class="fe fe-plus"></i><span class="mx-1">Add</span>
                        </button>
                        <button v-else type="button" class="btn btn-primary" :class="incAddDis" @click="updIncident()">
                            <i class="fe fe-save"></i><span class="mx-1">Save</span>
                        </button>
                        <button type="button" class="btn btn-secondary ms-1" @click="view ='list'"><i class="fe fe-x"></i> 
                            <span class="mx-1">Close</span>
                        </button>
                    </div>
                </div>
            </div>

            


            
        </div>
    


</template>

<script>
export default {
    name: 'XpplAppMedicalRecord',

    data() {
        return {
            permiss:[],
            lkGender:['Male', 'Female'],


            search:'',


            registerData:[],
            medicalData:[],
            registForm:{userid:'', gender:null, name:'', surname:'', company:null, department:null, position:null, empid:'', phone:'', bod:'', address:'', remarks:''},
            medicalFrom:{recid:'', date_in:'', types:null, vital_signs:'', temp_c:'', bp:'', puls:'', ox:'', weight:'', syndrome:null, diagosis:'', remarks:''},
            medicatFrom:{medication:'', qty:'', unit:null, remarks:''},
            view:'new',
            

        };
    },

    mounted() {
        
    },

    methods: {
        async OnLoad(){
            const perm = await axios.get('/api/permiss')
            this.permiss = perm.data;
        },
        

        GetMedicalData(){
            this.$axios.get(`/api/clinic/registername?search=${this.search}`)
            .then(res => {
                this.registerData = res.data;
                // this.filterMode = '';
                // $('#Filter').modal('hide');
            })
        },

        NewName(){
            if(this.permiss.clinic_add==0){
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
                this.view='new';

                 this.$axios.get('/api/clinic/newid')
                .then(res => this.registForm.userid = res.data)
                
            }
        }


    },

    created(){
        this.OnLoad();
        this.GetMedicalData();
    }
};
</script>

<style lang="scss" scoped>

</style>