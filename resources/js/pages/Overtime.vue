<template>

    <div class="card">
            <div class="card-header pd-r-15 pd-t-10 pb-0">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-start align-items-center">
                        <h4 class="card-title mg-b-0 text-muted text-capitalize">Overtime {{testVal}}</h4>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                            <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
                        </button> 
                        <div class="dropdown-menu tx-13">
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="addDelete()">
                                <i class="mdi mdi-contrast-box me-2 tx-16"></i><span>Add/Delete</span>
                            </div>
                            <div class="dropdown-item cur-pointer dropdown-hover">
                                <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Update Fingerscan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pd-t-0 pd-b-0">
                <div class="d-xl-flex justify-content-between mt-1 mb-1">
                    <div class="pos-relative wd-lg-300 wd-100p">
                        <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search for name" v-model="search" @input="searchChange()" @keyup.enter="getOvertime()" title="Search by name, Crew or Section">
                            <i class="fe fe-search search-i text-muted"></i>
                        <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                    </div>
                    <div class="d-md-flex justify-content-end mt-xl-0 mt-lg-1 mt-md-1 mt-1">
                        <div class="wd-lg-200 wd-md-200 wd-100p me-1 my-md-0 my-1" v-if="permiss.ros_all == 1" >
                            <Multiselect v-model="dept" :searchable="false" :searchStart="true" :options="lkDept" @select="getOvertime()"/>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="wd-md-150 wd-100p">
                                <input type="date" class="form-control" v-model="datefr" @change="getOvertime()">
                            </div>
                            <div class="wd-md-150 wd-100p ms-1">
                                <input type="date" class="form-control" v-model="dateto" @change="getOvertime()">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive border" style="max-height: 72vh">
                    <table class="table main-table-reference text-nowrap mg-b-0">
                        <thead class="position-sticky" style="top: 0px; z-index: 1">
                            <tr>
                                <th class="border-start-0 px-1">No</th>
                                <th class="px-1 position-sticky border-start-0" style="left: -1px">Name and Surname</th>
                                <th class="px-1">Position</th>
                                <!-- <th class="px-1 border-start-0">Crew</th> -->
                                <th v-for="(col, colInx) in otHead" :key="colInx" class="text-center" style="padding: 7px 5px" :title="col.mont" :style="headDate(col.colid) == cdate ? 'background-color: yellow; color: blue; font-weight: bold' : ''">
                                    {{ col.colname }} 
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, rowInx) in otData" :key="rowInx">
                                <td class="px-1 text-center border-start-0">{{rowInx + 1}}</td>
                                <td class="px-1 position-sticky cur-pointer border-start-0 bg-white" style="left: -1px" title="Double click to see Roster details" @dblclick="rosDetail(row.userid)">{{row.fullname}}</td>
                                
                                
                                
                                <td class="px-1" :title="row.position">{{cutWord(row.position)}}</td>
                                
                                <!-- <td class="px-1 border-start-0 cur-pointer">{{row.crew}}</td> -->


                                <td v-for="(col, colInx) in otHead" :key="colInx" class="px-1 text-center" :title="code(row[col.colid])>0 ? mentDate(col.colid) +'  ('+ comm(row[col.colid]) +')':''"
                                    :style="code(row[col.colid])>0 ? 'background-color: #FFFFCC':'background-color: #F2F4F8'"

                                >   
                                    <div v-if="code(row[col.colid])>0">
                                        <div data-bs-toggle="dropdown" type="button">{{code(row[col.colid])}}</div>
                                        <div class="dropdown-menu tx-13">
                                            <div class="dropdown-item cur-pointer dropdown-hover ps-2 pe-0 py-1">
                                                <i class="mdi mdi-file-excel me-2"></i><span>Update</span>
                                            </div>
                                            <div class="dropdown-item cur-pointer dropdown-hover ps-2 pe-0 py-1">
                                                <i class="mdi mdi-file-excel me-2"></i><span>Delete</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="p-0">
                                        <button class="btn btn-sm p-0" style="width: 15px; height: 15px" data-bs-toggle="dropdown" title="Add" @click="test(col.colid)"></button>
                                        <div class="dropdown-menu tx-13">
                                            <h6 class="dropdown-header tx-11 tx-bold tx-inverse tx-spacing-1">{{testVal}}</h6>
                                            <div class="dropdown-item cur-pointer dropdown-hover ps-2 pe-0 py-1">
                                                <i class="mdi mdi-file-excel me-2"></i><span>Add</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between tx-13 pt-1 pd-b-6">
                    <div class="text-muted">{{fullname}}</div>
                    <div v-if="text" class="text-end me-1">
                        {{text}}<span class="fw-bold text-primary">{{count}}</span> days
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add Overtime -->
        <div class="modal fade effect-scale" id="addDelete" data-bs-backdrop="static" back data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">{{opt}} Overtime</h6>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body pt-2">  
                        
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="pos-relative wd-100p mb-1">
                                    <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search for name" v-model="search2" @input="searchChange2()" title="Search by name">
                                        <i class="fe fe-search search-i text-muted"></i>
                                    <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear2" @click="searchClear2()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                                </div>

                                <div class="table-responsive element border" style="height: 405px">
                                    <table class="table main-table-reference text-nowrap mg-b-0">
                                        <thead class="position-sticky" style="top: 0px; z-index: 1">
                                            <tr>
                                                <th class="border-0" style="padding: 5px 10px">
                                                    <label class="form-checkbox m-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <input type="checkbox" v-model="selectAll" @click="SelectAll()">
                                                            <i class="form-icon"></i>
                                                            <span v-if="selectAll" class="ms-2 cur-pointer mg-t-2" title="Unselect all">Unselect All</span>
                                                            <span v-else class="ms-2 cur-pointer mg-t-2" title="Select all">Select All</span>
                                                        </div>
                                                    </label>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-hover" v-for="(item, inx) in empList" :key="inx">
                                                <td class="border-0">
                                                    <label class="form-checkbox m-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <input type="checkbox" :value="item.userid" v-model="selected">
                                                            <i class="form-icon"></i>
                                                            <span class="ms-2 cur-pointer">{{item.name}}</span>
                                                        </div>
                                                    </label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 

                            <div class="col-lg-5">
                                <div class=" d-flex justify-content-start mt-md-2 mt-4">
                                    <label class="rdiobox cur-pointer"><input name="add" type="radio" value="add" checked v-model="opt"><span>Add</span></label>
                                    <label class="rdiobox cur-pointer ms-5"><input name="add" type="radio" value="delete" v-model="opt"><span>Delete</span></label>  
                                </div>
                                <div class="form-group mt-3">
                                    <label class="mb-0">Date From <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="otForm.datefr">
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">Date To <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="otForm.dateto">
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 pe-lg-1">
                                        <div class="form-group">
                                            <label class="mb-0">Start <span class="text-danger">*</span></label>
                                            <input v-if="opt=='add'" type="time" class="form-control" v-model="otForm.time_start">
                                            <input v-else type="time" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 ps-lg-1">
                                        <div class="form-group">
                                            <label class="mb-0">End <span class="text-danger">*</span></label>
                                            <input v-if="opt=='add'" type="time" class="form-control" v-model="otForm.time_end">
                                            <input v-else type="time" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">Type <span class="text-danger">*</span></label>
                                    <Multiselect v-if="opt=='add'" v-model="otForm.ot_type" :searchable="false" :searchStart="true" :options="lkType"/>
                                    <Multiselect v-else v-model="otForm.ot_type" disabled/>
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="otForm.dateto">
                                </div>
                                <button v-if="opt=='add'" type="button" class="btn btn-primary wd-100p" :class="addOTDis" @click="addOvertime()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                                <button v-else type="button" class="btn btn-danger wd-100p" :class="delOTDis" @click="delOvertime()"><i class="fe fe-trash-2"></i><span class="mx-1">Delete</span></button> 
                            </div>    
                        </div>
                        
                    </div>
                </div>
            </div>                                              
        </div>


        
</template>

<script>
export default {
    name: 'XpplAppOvertime',

    data() {
        return {
            permiss: [],
            lkDept: [],
            
            lkType:[
                {value: 's', label: 'Start of Shift'},
                {value: 'e', label: 'End of Shift'},
                {value: 'n', label: 'At Noon'}
            ],

            otHead:[],
            otData:[],

            empList:[],

            selected:[],
		    selectAll:false,


            dept: '',
            datefr: '',
            dateto: '',
            search: '',
            search2: '',
            btnClear: '',
            btnClear2: '',

            otForm: {id: '', userid: '', datefr: '', dateto: '', time_start: '18:00', time_end: '21:00', note: '', remarks: '', ot_type:'e'},
            opt: 'add',
            
            inx: '',
            testData: [],

            testVal: ''

        };
    },

    mounted() {
        
    },

    computed: {
        addOTDis(){
            if (this.selected =='' || this.otForm.datefr =='' || this.otForm.dateto =='' || this.otForm.time_start =='' || this.otForm.time_end =='' || this.otForm.ot_type ==null || this.otForm.datefr > this.otForm.dateto){
                return 'disabled';
            } else {
                return '';
            } 
        },

        delOTDis(){
            if (this.selected =='' || this.otForm.datefr =='' || this.otForm.dateto =='' || this.otForm.datefr > this.otForm.dateto){
                return 'disabled';
            } else {
                return '';
            } 
        }
    },

    methods: {
        async onLoad(){
            // this.loading = true;
            const resPermiss = await axios.get('/api/permiss')
            this.permiss = resPermiss.data;

            const profile = await axios.get('api/profile')
			this.dept = profile.data.department;

            let today = new Date();
            this.datefr = moment(new Date(today.getFullYear(), today.getMonth() - 1, 15)).format('YYYY-MM-DD');
            this.dateto = moment(new Date(today.getFullYear(), today.getMonth() + 1, 0)).format('YYYY-MM-DD');

            this.cdate = moment(today).format('YYYY-MM-DD');

            const colhead = await axios.get(`/api/roster/datecol?datefr=${this.datefr}&dateto=${this.dateto}`)
            this.otHead = colhead.data;

            const ot = await axios.get(`/api/overtime/otsearch?dept=${this.dept}&datefr=${this.datefr}&dateto=${this.dateto}&search=${this.search}`)
            this.otData = ot.data;

            // this.loading = false;




            const dept = await axios.get('/api/lookup/depts')
            this.lkDept = dept.data;

            const test = await axios.get('/api/overtime/test')
            this.testData = test.data;


        },

        test(text){
            this.testVal = text;
        },

        async getOvertime(){
            // this.loading = true;

            if (this.dept != '' && this.datefr != '' && this.dateto != ''){
                const colhead = await axios.get(`/api/roster/datecol?datefr=${this.datefr}&dateto=${this.dateto}`)
                this.otHead = colhead.data;

                const ot = await axios.get(`/api/overtime/otsearch?dept=${this.dept}&datefr=${this.datefr}&dateto=${this.dateto}&search=${this.search}`)
                this.otData = ot.data;
            }
            // this.loading = false;
        },

        async searchChange(){
            if(this.search.length >0){
                this.btnClear = true;
                // const data = await this.getRoster();
            } else {
                this.btnClear = false;
                // const data = await this.getRoster();
            }
        },

        searchClear(){
            this.search = '';
            this.btnClear = false;
            this.getOvertime();
        },

        SelectAll(){
            this.selected = [];
            if (!this.selectAll) {
                for (let i in this.empList) {
                    this.selected.push(this.empList[i].userid);
                }
            }    
        },

        addDelete(){
            this.clearForm();
            this.selected = [];
            this.selectAll = false;
            this.opt = 'add';

            $('#addDelete').modal('show');
            this.nameList();
        },

        async nameList(){
            const names = await axios.get(`/api/lookup/emplist?dept=${this.dept}&search=${this.search2}`)
            this.empList = names.data;
        },

        async searchChange2(){
            if(this.search2.length >0){
                this.btnClear2 = true;
                const data = await this.nameList();
            } else {
                this.btnClear2 = false;
                const data = await this.nameList();
            }
        },

        searchClear2(){
            this.search2 = '';
            this.btnClear2 = false;
            this.nameList();
        },

        addOvertime(){
            this.$axios.post('/api/overtime/addovertime', {
                list: this.selected,
                datefr: this.otForm.datefr,
                dateto: this.otForm.dateto,
                start: this.otForm.time_start,
                end: this.otForm.time_end,
                type: this.otForm.ot_type
            }).then(res => {
                $('#addDelete').modal('hide');
                this.getOvertime();
            })
        },

        delOvertime(){
            $('#addDelete').modal('hide');
            this.$swal.fire({
                text: "Are you sure?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fe fe-trash-2"></i> <span class="font-weight-light">Delete</span>',
                cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
                confirmButtonColor: '#d33',
                allowEnterKey: false,
                allowOutsideClick: false,
            }).then((result)=>{
                if(result.isConfirmed){
                    this.$axios.post('/api/overtime/delovertime', {
                        list: this.selected,
                        datefr: this.otForm.datefr,
                        dateto: this.otForm.dateto
                    }).then(res => {
                        $('#addDelete').modal('hide');
                        this.getOvertime();
                    })
                } else {
                    $('#addDelete').modal('show');
                }
            });
        },

        clearForm(){
            let f = this.otForm;
            f.datefr='';
            f.dateto='';
            f.time_start='18:00';
            f.time_end='21:00';
            f.ot_type='e';
            this.search2='';
        },

        code(text){
            if (text) {
                return text.split("_")[0];
            }
        },

        mentDate(text){
            if (text) {
                return moment(text.split("c")[1]).format("DD/MM/YYYY");
            }
        },

        comm(text){
            if (text) {
                return text.split("_")[1];
            }
        },

        headDate(text){
            if (text) {
                return moment(text.split("c")[1]).format("YYYY-MM-DD");
            }
        },

        headDate1(text){
            if (text) {
                return moment(text.split("c")[1]).format("MM-DD-YYYY");
            }
        },

        comments(text){
            if (text) {
                return text.split("_")[1];
            }
        },

        date1(value){
            if (value) {
                return moment(value).format("DD-MM-YYYY");
            }
        },

        date2(value){
            if (value) {
                return moment(value).format("DD-MM-YYYY HH:ss");
            }
        },

        cutWord(text){
            if (!text == '') {
                if(text.length > 20){
                    return text.substring(0,20)+'...';
                } else {
                    return text;
                }
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