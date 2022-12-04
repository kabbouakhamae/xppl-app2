<template>

    <div class="card">
            <div class="card-header pd-r-15 pd-t-10 pb-0">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-start align-items-center">
                        <h4 class="card-title mg-b-0 text-muted text-capitalize">Overtime </h4>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                            <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
                        </button> 
                        <div class="dropdown-menu tx-13">
                            <!-- <div class="dropdown-item cur-pointer dropdown-hover" @click="showCols()">
                                <i class="mdi mdi-eye me-2 tx-16"></i><span>Show Column</span>
                            </div> -->

                            <div class="dropdown-item cur-pointer dropdown-hover" @click="addDelete()">
                                <i class="mdi mdi-contrast-box me-2 tx-16"></i><span>Add/Delete</span>
                            </div>
                            <div class="dropdown-item cur-pointer dropdown-hover">
                                <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Export To Excel</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pd-t-0 pd-b-0">
                <div class="d-xl-flex justify-content-between mt-1 mb-1">
                    <div class="pos-relative wd-lg-300 wd-100p">
                        <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search for name" v-model="search" @input="searchChange()" @keyup.enter="getRoster()" title="Search by name, Crew or Section">
                            <i class="fe fe-search search-i text-muted"></i>
                        <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                    </div>
                    <div class="d-md-flex justify-content-end mt-xl-0 mt-lg-1 mt-md-1 mt-1">
                        <div class="wd-lg-200 wd-md-200 wd-100p me-1 my-md-0 my-1" v-if="permiss.ros_all == 1" >
                            <Multiselect v-model="dept" :searchable="false" :searchStart="true" :options="lkDept" @select="getRoster()"/>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="wd-md-150 wd-100p">
                                <input type="date" class="form-control" v-model="datefr" @change="getRoster()">
                            </div>
                            <div class="wd-md-150 wd-100p ms-1">
                                <input type="date" class="form-control" v-model="dateto" @change="getRoster()">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive border" style="max-height: 72vh">
                    <table class="table main-table-reference text-nowrap mg-b-0">
                        <thead class="position-sticky" style="top: 0px; z-index: 1">
                            <tr>
                                <!-- <th v-if="showCol.no" class="border-start-0 px-1">No</th>
                                <th v-if="showCol.crew" class="px-1 border-start-0">Crew</th>
                                <th class="px-1 position-sticky border-start-0" style="left: -1px">Name and Surname</th>
                                <th v-if="showCol.position" class="px-1">Position</th>
                                <th v-if="showCol.section" class="px-1">Section</th>
                                <th v-if="showCol.type" class="px-1">Type</th> -->
                                <!-- <th v-for="(col, colInx) in colData" :key="colInx" class="text-center" style="padding: 7px 5px" :title="col.mont" :style="headDate(col.colid) == cdate ? 'background-color: yellow; color: blue; font-weight: bold' : ''">
                                    {{ col.colname }} 
                                </th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="(row, rowInx) in rosData" :key="rowInx" @click="selectRow(rowInx, row.fullname, row.rtype)" :style="rowInx === rowSel && row.rtype == 'P' ? 'color: red; font-weight: 500' : rowInx === rowSel && row.rtype == 'A' ? 'color: blue; font-weight: 500' : ''" >
                                <td v-if="showCol.no" class="px-1 text-center border-start-0">{{rowInx + 1}}</td>
                                <td v-if="showCol.crew" class="px-1 border-start-0 cur-pointer" @dblclick="newCrew(row.id)">{{row.crew}}</td>
                                <td class="px-1 position-sticky cur-pointer border-start-0 bg-white" style="left: -1px" title="Double click to see Roster details" @dblclick="rosDetail(row.userid)">{{row.fullname}}</td>
                                <td v-if="showCol.position" class="px-1" :title="row.position">{{cutWord(row.position)}}</td>
                                <td v-if="showCol.section" class="px-1 cur-pointer" @dblclick="newSection(row.id)">{{row.section}}</td>
                                <td v-if="showCol.type" class="px-1 text-center">{{row.rtype}}</td>
                                <td v-for="(col, colInx) in colData" :key="colInx" class="px-1 text-center cur-pointer" :title="mentDate(col.colid) +'  '+ row[col.colid]" 
                                    :style="code(row[col.colid]) == 'W' || code(row[col.colid]) == 'WN' ? 'background-color: #F2F4F8':
                                            code(row[col.colid]) == 'W/2' ? 'background-color: #FFC000':
                                            code(row[col.colid]) == 'R' || code(row[col.colid]) == 'RW' || code(row[col.colid]) == 'OR' || code(row[col.colid]) == 'IR' ? 'background-color: #FFFFCC':
                                            code(row[col.colid]) == 'A' || code(row[col.colid]) == 'A/2' || code(row[col.colid]) == 'IA' || code(row[col.colid]) == 'OA' || code(row[col.colid]) == 'AA' || code(row[col.colid])== 'H' || code(row[col.colid]) == 'OH' || code(row[col.colid]) == 'IH' || code(row[col.colid]) == 'P' ? 'background-color: #FFFF99':
                                            code(row[col.colid]) == 'S' || code(row[col.colid]) == 'SS' || code(row[col.colid]) == 'IS' || code(row[col.colid]) == 'OS' ? 'background-color: #F2DCDB':
                                            code(row[col.colid]) == 'MR' ? 'background-color: #FF66FF':
                                            code(row[col.colid]) == 'BWB' || code(row[col.colid]) == 'WO' || code(row[col.colid]) == 'IW' || code(row[col.colid]) == 'OW' ? 'background-color: #C6E0B4':
                                            code(row[col.colid]) == 'T' || code(row[col.colid]) == 'T-' || code(row[col.colid]) == 'IT' || code(row[col.colid]) == 'OT' || code(row[col.colid]) == 'TT' ? 'background-color: #FF0000':''" 
                                    
                                    @dblclick="editRos(row.userid, col.colid, row.rtype, code(row[col.colid]), row.name, comments(row[col.colid]))"
                                    @click="countDay(rowInx, headDate1(col.colid))"
                                >
                                    {{ code(row[col.colid]) }}
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
                <!-- <div class="d-flex justify-content-between tx-13 pt-1 pd-b-6">
                    <div class="text-muted">{{fullname}} Type: {{type(rosForm.rtype)}}</div>
                    <div v-if="text" class="text-end me-1">
                        {{text}}<span class="fw-bold text-primary">{{count}}</span> days
                    </div>
                </div> -->
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
                                <div class="table-responsive element border" style="height: 334px">
                                    <table class="table main-table-reference text-nowrap mg-b-0">
                                        <thead class="position-sticky" style="top: 0px; z-index: 1">
                                            <tr>
                                                <th class="border-0" style="padding: 5px 10px">
                                                    <label class="form-checkbox m-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <input type="checkbox" v-model="selectAll" @click="select()">
                                                            <i class="form-icon"></i>
                                                            <span class="ms-2 cur-pointer mg-t-2" title="Select/Unselect all">Employee List</span>
                                                        </div>
                                                    </label>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-hover" v-for="(lst ) in empData" :key="lst.id">
                                                <td class="border-0">
                                                    <label class="form-checkbox m-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <input type="checkbox" :value="lst.id" v-model="selected">
                                                            <i class="form-icon"></i>
                                                            <span class="ms-2 cur-pointer">{{lst.name}}</span>
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
                                    <label class="rdiobox cur-pointer" @click="addMethodChanged()"><input name="add" type="radio" value="add" checked v-model="opt"><span>Add</span></label>
                                    <label class="rdiobox cur-pointer ms-5" @click="addMethodChanged()"><input name="add" type="radio" value="delete" v-model="opt"><span>Delete</span></label>  
                                </div>
                                <div class="form-group mt-3">
                                    <label class="mb-0">Date From <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="otForm.datefr">
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">Date To <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="otForm.dateto">
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">Start <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" v-model="otForm.time_start">
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">End <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" v-model="otForm.time_end">
                                </div>

                                <button v-if="opt == 'add'" type="button" class="btn btn-primary wd-100p" :class="addDelDis" @click="addOvertime()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                                <button v-else type="button" class="btn btn-danger wd-100p" :class="addDelDis" @click="delOvertime()"><i class="fe fe-trash-2"></i><span class="mx-1">Delete</span></button> 
                            </div>    
                        </div>
                        
                    </div>
                </div>
            </div>                                              
        </div>

        {{testData}}
        
</template>

<script>
export default {
    name: 'XpplAppOvertime',

    data() {
        return {
            permiss: [],
            lkDept: [],
            lkCode: [],
            lkEmp: [],
            lkCrew: [],
            lkSect: [],
            empData: [],

            selected: [],
		    selectAll: false,

            dept: '',
            datefr: '',
            dateto: '',
            search: '',
            btnClear: '',
            search2: '',
            btnClear2: '',

            otForm: {id: '', userid: '', datefr: '', dateto: '', time_start: '18:00', time_end: '21:00', note: '', remarks: ''},
            opt: 'add',
            inx: '',

            testData: [],

        };
    },

    mounted() {
        
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
            this.dateto = moment(new Date(today.getFullYear(), today.getMonth() + 1, 15)).format('YYYY-MM-DD');

            this.cdate = moment(today).format('YYYY-MM-DD');

            // const colhead = await axios.get(`/api/roster/datecol?datefr=${this.datefr}&dateto=${this.dateto}`)
            // this.colData = colhead.data;

            // const roster = await axios.get(`/api/roster/roster?dept=${this.dept}&datefr=${this.datefr}&dateto=${this.dateto}&search=${this.search}`)
            // this.rosData = roster.data;

            // this.loading = false;

            const dept = await axios.get('/api/lookup/depts')
            this.lkDept = dept.data;

            const test = await axios.get('/api/overtime/test')
            this.testData = test.data;


        },

        select() {
            this.selected = [];
            if (!this.selectAll) {
                for (let i in this.empData) {
                    this.selected.push(this.empData[i].id);
                }
            }
        },

        addDelete(){
            // this.userids = [];
            // this.addDelDate = '';
            // this.addMethod = 'add'
            
            this.selected = [];
            this.selectAll = false;
            this.opt = 'add';

            $('#addDelete').modal('show');
            this.nameList();

        },

        async nameList(){
            const names = await axios.get(`/api/lookup/emplist?dept=${this.dept}&search=${this.search2}`)
            this.empData = names.data;
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
                end: this.otForm.time_end
            }).then(res => {
                $('#addDelete').modal('hide');
                // this.getRoster();
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
                        // this.getRoster();
                    })
                } else {
                    $('#addDelete').modal('show');
                }
            });
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