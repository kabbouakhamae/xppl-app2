<template>
    <div>
        <div class="card">
            
                <div class="card-header pd-r-15 pd-t-10 pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-start align-items-center">
                            <h4 class="card-title mg-b-0 text-muted text-capitalize">Purchase Requisition Records In {{year}}</h4>
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
                <div class="card-body pd-t-0">
                    <div class="d-lg-flex justify-content-between mt-1 mb-1">
                        <div class="pos-relative wd-lg-300 wd-100p">
                            <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search..." v-model="search" @input="searchChanged()" title="Search by Incident ID or Title or Company or Dept. ">
                                <i class="fe fe-search search-i text-muted"></i>
                            <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                        </div>
                        <div class="d-flex justify-content-end mt-xl-0 mt-lg-0 mt-md-1 mt-1">
                            <div class="wd-md-150 wd-100p me-1">
                                <Multiselect v-model="year" :searchStart="true" :options="lkYear" @select="yearChanged()"/>
                            </div>
                            <div class="wd-35">
                                <button type="button" class="btn btn-outline-primary p-0 border wd-35" title="Add new record" @click="newPR()">
                                    <i class="fa fa-plus tx-14"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive border-start border-end border-bottom" style="max-height: 73vh">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>
                                    <th class="position-sticky border-start-0" style="left: -0.1px">PR ID</th>
                                    <th>PR No</th>
                                    <th>PR Date</th>
                                    <th>Plant</th>
                                    <th>Department</th>
                                    <th>Cost Type</th>
                                    <th>Cost No</th>
                                    <th>Locations</th>
                                    <th>Request By</th>
                                    <th>Approved By</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>created at</th>
                                    <th>created by</th>
                                    <th>Remarks</th>
                                    <th class="border-end-0 position-sticky wd-60p" style="right: -0.1px">Tools</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr v-for="row in prData" :key="row.id" class="tr-hover">
                                    <td class="border-start-0 position-sticky cur-pointer" style="left: -0.1px; background-color: #FFFFFF" title="Double click to see details" @click="preview(row.id)" >{{row.inc_id}}</td>
                                    <td>{{pr_id}}</td>

                                    <!-- <td class="border-end-0 position-sticky p-0 align-middle" style="right: -0.1px; background-color: #FFFFFF">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Preview" @click="preview(row.id, row.inc_id)">
                                                <i class="bx bx-search text-primary" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="editIncident(row.id, row.inc_id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="delIncident(row.id, row.inc_id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td> -->
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>

    <!-- MODAL PR -->
    <div class="modal fade effect-scale bd-0" id="PR" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="prLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-1 bd-b-0">
                    <h6 class="text-muted">Purchase Request<span class="text-danger"></span></h6>
                    <div class="d-flex justify-content-start align-items-center cur-pointer add-hover" @click="document()" title="Attache files">
                        <div><i class="mdi mdi-paperclip text-primary tx-16"></i></div>
                        <span class="text-primary tx-14">Attachments</span>
                    </div>
                    <div class="d-flex justify-content-start align-items-center cur-pointer add-hover" @click="back()" title="Back">
                        <div><i class="fa fa-arrow-left me-1 text-primary tx-12"></i></div>
                        <span class="text-primary tx-14">Back</span>
                    </div>
                </div>
                <div class="modal-body pt-1">                                   
                    <div>
                        <div class="row">
                            <div class="col-xl-2 col-lg-3">
                                <div class="form-group">
                                    <label class="mb-0">Incident Date  <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-9">
                                <div class="form-group">
                                    <label class="mb-0">Incident Title  <span class=" text-danger">*</span></label>
                                    <input type="text" class="form-control laofont">
                                </div>
                            </div>
                            <!-- <div class="col-xl-2 col-lg-3">
                                <div class="form-group">
                                    <label class="mb-0">Company <span class=" text-danger">*</span></label> 
                                    <Multiselect :options="lkComp"/>
                                </div>
                            </div> -->
                            <!-- <div class="col-xl-3 col-lg-5">
                                <div class="form-group">
                                    <label class="mb-0">Department</label> 
                                    <Multiselect v-if="incForm.company == 'XPPL'" :searchable="true" :searchStart="true" :options="lkDept"/>
                                    <input v-else type="text" class="form-control">
                                </div>
                            </div> -->
                        </div>
                    </div>
                    

                    <div class="d-flex justify-content-end">
                        <button v-if="btnShow" type="button" class="btn btn-primary" :class="incAddDis" @click="addIncident()">
                            <i class="fe fe-plus"></i><span class="mx-1">Add</span>
                        </button>
                        <button v-else type="button" class="btn btn-primary" :class="incAddDis" @click="updIncident()">
                            <i class="fe fe-save"></i><span class="mx-1">Save</span>
                        </button>
                        <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal">
                            <i class="fe fe-x"></i><span class="mx-1">Close</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>                                              
    </div>


</template>

<script>
export default {
    name: 'XpplAppPR',

    data() {
        return {

            lkYear: [],

            year: '',
            search: '',
            btnClear: false,

            prData: []
            
        };
    },

    mounted() {

    },

    methods: {
        async onLoad(){
            this.loading = true;

            // const profile = await axios.get('api/profile')
			// this.dept = profile.data.department;
            this.year = new Date().getFullYear();

            // const info = await axios.get(`/api/annual/info?dept=${this.dept}&year=${this.year}&search=${this.search}`)
            // this.infoData = info.data;



            // const dept = await axios.get('/api/lookup/dept')
            // this.lkDept = dept.data;

            this.getPRRecord();

            // this.loading = false; 

            const year = await axios.get('/api/lookup/year')
            this.lkYear = year.data;

            // const incGroup = await axios.get('/api/safety/incgroup')
            // this.lkIncGroup = incGroup.data;

            // const incType = await axios.get('/api/safety/inctype')
            // this.lkIncType = incType.data;

            // const injType = await axios.get('/api/safety/injtype')
            // this.lkInjType = injType.data;

            // const injGroup = await axios.get('/api/safety/injgroup')
            // this.lkInjGroup = injGroup.data;

            // const actSeq = await axios.get('/api/safety/actseq')
            // this.lkActSeq = actSeq.data;

            // const potSeq = await axios.get('/api/safety/potseq')
            // this.lkPotSeq = potSeq.data;

            // const invest = await axios.get('/api/safety/invest')
            // this.lkInvest = invest.data;

            // const action = await axios.get('/api/safety/action')
            // this.lkAction = action.data;

            // const yesNo = await axios.get('/api/safety/yesno')
            // this.lkYesNo = yesNo.data;

            // const comp = await axios.get('/api/safety/company')
            // this.lkComp = comp.data;




        },

        // async yearChanged(){
        //     this.loading = true; 
        //     const res = await axios.get(`/api/safety/increcord?year=${this.year}&search=${this.search}`)
        //     this.incData = res.data;
        //     this.loading = false; 
        // },

        getPRRecord(){
            this.$axios.get(`/api/inventory/prrecord?year=${this.year}&search=${this.search}`)
            .then(res => this.prData = res.data)
        },

        searchChanged(){
            if(this.search.length >0){
                this.btnClear = true;
                this.getPRRecord();
            } else {
                this.btnClear = false;
                this.getPRRecord();
            }
        },

        searchClear(){
            this.search = '';
            this.btnClear = false;
            this.getPRRecord();
        },

        newPR(){

            // this.btnShow = true;
            // this.data = true;
            // this.tmpFiles = [];
            // this.incFormClear();
            $('#PR').modal('show');

            // this.$axios.get('/api/safety/newincid')
            // .then(res => this.incForm.inc_id = res.data)

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
	}
};
</script>

<style lang="scss" scoped>

</style>