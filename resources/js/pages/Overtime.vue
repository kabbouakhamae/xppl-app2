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
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="RunUpdate_Scan()">
                                <i class="far fa-hand-pointer me-2 tx-16"></i><span>Run OT Fingerscan</span>
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

                <div class="table-responsive border" style="max-height: 77vh">
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
                            <tr v-for="(row, rowInx) in otData" :key="rowInx" @click="selectRow(row.userid, rowInx, row.fullname)" :style="rowInx === rowSel ? 'color: blue; font-weight: 500' : ''">
                                <td class="px-1 text-center border-start-0">{{rowInx + 1}}</td>
                                <td class="px-1 position-sticky cur-pointer border-start-0 bg-white" style="left: -1px" title="Click to see OT details" @click="OTDetail(row.userid)">{{row.fullname}}</td>
                                
                                <td class="px-1" :title="row.position">{{cutWord(row.position)}}</td>
                                
                                <!-- <td class="px-1 border-start-0 cur-pointer">{{row.crew}}</td> -->


                                <td v-for="(col, colInx) in otHead" :key="colInx" class="px-1 text-center" :title="code(row[col.colid]) ? mentDate(col.colid) +'  ('+ comm(row[col.colid]) +') '+ scan(row[col.colid]):''"
                                    :style="paid(row[col.colid])=='Y' ? 'background-color: #C8E6C9': 
                                            scan(row[col.colid])=='Waiting scan' ? 'background-color: #FFFF99': 
                                            scan(row[col.colid])=='OT' ? 'background-color: #FFC000': 
                                            scan(row[col.colid])=='Missed check out' || scan(row[col.colid])=='Early check out' ? 'background-color: #FF5722':''"
                                    @click="countDays(rowInx, headDate1(col.colid), colInx)"
                                >   
                                    <div v-if="row[col.colid]" class="cur-pointer" @dblclick="UpdOT_Range(col.colid, colInx), optMode='Update'">
                                        {{code(row[col.colid])}}
                                    </div>
                                    <div v-else class="p-0">
                                        <button class="btn btn-sm p-0" style="width: 15px; height: 15px" title="Add" @dblclick="AddOT_Range(col.colid, colInx), optMode='Add'"></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between tx-13 pt-1 pd-b-6">
                    <div class="d-flex justify-content-start">
                        <span class="tx-11 px-1 pd-t-1 border" style="background-color: #C8E6C9">Paid</span>
                        <span class="tx-11 px-1 pd-t-1 border ms-1" style="background-color: #FFC000">Waiting to pay</span>
                        <span class="tx-11 px-1 pd-t-1 border ms-1" style="background-color: #FFFF99">Waiting scan</span>
                        <span class="tx-11 px-1 pd-t-1 border ms-1" style="background-color: #FF5722">Missed scan</span>
                    </div>

                    <!-- <div class="text-muted">{{fullname}}</div> -->
                    <div v-if="countTxt" class="text-end me-1">
                        {{countTxt}}<span class="fw-bold text-primary">{{countNum}}</span>
                    </div>
                </div>

            </div>

        <!-- MODAL OVERTIME -->
        <div class="modal" id="modalOT" back data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">{{shortname(fullname)}}</h6>
                    </div>
                    <div class="modal-body pt-1">  
                        <div class="row">
                            <div class="col-5 pe-1">
                                <div class="form-group">
                                    <input type="date" class="form-control ps-2 pe-0" disabled v-model="otForm.datefr">
                                </div>
                            </div>
                            <div class="col-5 ps-1 pe-1">
                                <div class="form-group">
                                    <input type="date" class="form-control ps-2 pe-1" v-model="otForm.dateto" @change="CountD()">
                                </div>
                            </div>
                            <div class="col-2 ps-1">
                                <div class="form-group">
                                    <input type="text" class="form-control tx-center px-0" v-model="otForm.days">
                                </div>
                            </div>
                        </div>
                        <div v-if="optMode!='Delete'">
                            <div class="row">
                                <div class="col-6 pe-1">
                                    <div class="form-group">
                                        <input type="time" class="form-control" v-model="otForm.time_start" @change="TDiff()">
                                    </div>
                                </div>
                                <div class="col-6 ps-1">
                                    <div class="form-group">
                                        <input type="time" class="form-control" v-model="otForm.time_end" @change="TDiff()">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <Multiselect v-model="otForm.ot_type" searchable="true" searchStart="true" placeholder="Code" :options="lkType"/>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" style="height: 80px" v-model="otForm.remarks" placeholder="Comment"></textarea>
                            </div>  
                        </div>        
                        <div class="d-flex justify-content-between">
                            <div v-if="optMode !='Add'">
                                <label class="rdiobox cur-pointer my-0"><input name="opt1" type="radio" v-model="optMode" value="Update" checked><span class="ps-2">Update</span></label>
                                <label class="rdiobox cur-pointer my-0"><input name="opt1" type="radio" v-model="optMode" value="Delete"><span class="ps-2">Delete</span></label>  
                            </div>
                            <div v-else class="d-flex justify-content-center align-items-center">
                                <label class="rdiobox cur-pointer my-0"><input name="opt1" type="radio" v-model="optMode" value="Add" checked><span class="ps-2">Add</span></label>  
                            </div>
                            <div class="d-flex justify-content-end">
                                <button v-if="optMode=='Update'" type="button" class="btn btn-primary" :class="AddOTDis2" @click="UpdOT_RangeCF()"><i class="fe fe-check-circle"></i><span class="mx-1">Update</span></button> 
                                <button v-else-if="optMode=='Delete'" type="button" class="btn btn-danger" :class="DelOTDis2" @click="DelOT_RangeCF()"><i class="fe fe-trash-2"></i><span class="mx-1">Delete</span></button> 
                                <button v-else type="button" class="btn btn-primary" :class="AddOTDis2" @click="AddOT_RangeCF()"><i class="fe fe-plus-circle"></i><span class="mx-1">Add</span></button> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Modal Add Overtime -->
        <div class="modal" id="addDelete" back data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">{{opt}} Overtime</h6>
                    </div>
                    <div class="modal-body pt-2">  
                        
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="pos-relative wd-100p mb-1">
                                    <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search for name" v-model="search2" @input="searchChange2()" title="Search by name">
                                        <i class="fe fe-search search-i text-muted"></i>
                                    <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear2" @click="searchClear2()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                                </div>

                                <div class="table-responsive element border" style="height: 410px">
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
                                    <label class="rdiobox cur-pointer"><input name="add" type="radio" value="add" checked v-model="opt"><span class="ps-2">Add</span></label>
                                    <label class="rdiobox cur-pointer ms-5"><input name="add" type="radio" value="delete" v-model="opt"><span class="ps-2">Delete</span></label>  
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
                                    <Multiselect v-else disabled/>
                                </div>

                                <div class="form-group">
                                    <textarea v-if="opt=='add'" class="form-control" style="height: 60px" v-model="otForm.remarks" placeholder="Comment"></textarea>
                                    <textarea v-else class="form-control" style="height: 60px" placeholder="Comment" disabled></textarea>
                                </div> 

                                <button v-if="opt=='add'" type="button" class="btn btn-primary wd-100p" :class="addOTDis" @click="addOvertime()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                                <button v-else type="button" class="btn btn-danger wd-100p" :class="delOTDis" @click="delOvertime()"><i class="fe fe-trash-2"></i><span class="mx-1">Delete</span></button> 
                            </div>    
                        </div>
                        
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- MODAL OT DETAIL -->
        <div class="modal" id="modalOTDetail" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">{{fullname}}</h6>
                    </div>
                       
                    <div class="modal-body pt-0">     
                        <div class="table-responsive border" style="max-height: 700px">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px">
                                    <tr>
                                        <th class="border-start-0">Date</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Hour</th>
                                        <th>Pament</th>
                                        <th>Type</th>
                                        <th>Scan</th>
                                        <th>Note</th>
                                        <th class="border-end-0 wd-60p">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr class="tr-hover" v-for="lst in otDetail" :key="lst.id">
                                        <td class="border-start-0 py-0 text-center border-bottom-0">{{date1(lst.ot_date)}}</td>
                                        <td class="py-0 tx-center border-bottom-0">{{Tformat(lst.time_start)}}</td>
                                        <td class="py-0 tx-center border-bottom-0">{{Tformat(lst.time_end)}}</td>
                                        <td class="py-0 tx-center border-bottom-0">{{lst.hrs}} </td>
                                        <td class="py-0 tx-center border-bottom-0">{{lst.payment}}</td>
                                        <td class="py-0 tx-center border-bottom-0">{{lst.ot_type}}</td>
                                        <td class="py-0 tx-center border-bottom-0">{{Tformat(lst.scan_time)}}</td>
                                        <td class="py-0 border-bottom-0">{{lst.scan_note}}</td>
                                        <td class="border-end-0 py-0 border-bottom-0">{{lst.remarks}}</td>
                                    </tr>                                                                                 
                                </tbody>
                            </table>
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
                {value: 'S', label: 'Start of shift'},
                {value: 'E', label: 'End of shift'},
                {value: 'D', label: 'Noon'},
                {value: 'N', label: 'Midnight'}
            ],

            empList:[],
            otHead:[],
            otData:[],
            otDetail:[],

            selected:[],
		    selectAll:false,

            rowSel:'',
            fullname:'',

            dept:'',
            datefr:'',
            dateto:'',
            search:'',
            search2:'',
            btnClear:'',
            btnClear2:'',

            otForm: {id: '', userid: '', datefr: '', dateto: '', days:'', hrs:'', time_start: '', time_end: '', note: '', remarks: '', ot_type:null},
            opt: 'add',

            optMode:'',
            selDate:[],
            countTxt:'',
            countNum:'',
            
            inx: '',
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
        },

        AddOTDis2(){
            let f = this.otForm;
            if (f.dateto=='' || f.time_start=='' || f.time_end=='' || f.ot_type==null || f.datefr>f.dateto || f.time_start>=f.time_end){
                return 'disabled';
            } else { return ''}
        },

        DelOTDis2(){
            let f = this.otForm;
            if (f.dateto=='' || f.datefr>f.dateto){
                return 'disabled';
            } else { return ''}
        },
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


        },

        selectRow(id, inx, name){
            this.rowSel = inx;
            this.fullname = name;
            this.otForm.userid = id;
        },

        CountD(){
            let d1 = moment(this.otForm.datefr).format("MM-DD-YYYY");
            let d2 = moment(this.otForm.dateto).format("MM-DD-YYYY");

            let dat1 = new Date(d1);
            let dat2 = new Date(d2);

            let diff = dat2.getTime() - dat1.getTime();
            this.otForm.days = Math.ceil(diff / (1000 * 3600 * 24)) + 1;
        },

        TDiff(){
            let start = moment(this.otForm.time_start, 'HH:mm');
            let end = moment(this.otForm.time_end, 'HH:mm');
            
            if (start < end) {
                this.otForm.hrs = end.diff(start, 'hours', true);
            };
        },

        async UpdOT_Range(dt, inx){
            // if(this.permiss.ros_edit == 0){
            //     this.$swal.fire({
            //         text: "You don't have permission update!",
            //         icon: 'error',
            //         showCancelButton: false,
            //         showConfirmButton: false,
            //         allowOutsideClick: false,
            //         timerProgressBar: true,
            //         timer: 1500
            //     })
            // } else {
                this.clearForm();
                let date = moment(dt.split('c')[1]).format('YYYY-MM-DD');
                const otEdit = await axios.get(`/api/overtime/editot?userid=${this.otForm.userid}&date=${date}`)

                let list = otEdit.data;
                let t1 = list.time_start.split('.')[0];
                let t2 = list.time_end.split('.')[0];
                
                this.otForm.datefr = date;
                this.otForm.dateto = date;
                this.otForm.days = 1;
                this.otForm.time_start = t1;
                this.otForm.time_end = t2;
                this.otForm.ot_type = list.ot_type;
                this.otForm.remarks = list.remarks;

                this.colinx = inx;
                $('#modalOT').modal('show');


                    // let d1 = moment(this.otForm.datefr).format("MM-DD-YYYY");
                    // let d2 = moment(this.otForm.dateto).format("MM-DD-YYYY");

                    // let dat1 = new Date(d1);
                    // let dat2 = new Date(d2);

                    // let diff = dat2.getTime() - dat1.getTime();
                    // this.otForm.days = Math.ceil(diff / (1000 * 3600 * 24)) + 1;




                    // const start = moment(this.otForm.time_start, 'HH:mm');
                    // const end = moment(this.otForm.time_end, 'HH:mm');
                    // const difference = end.diff(start, 'hours', true);

                    // console.log(difference)


                // let t1 = new Date("1970-01-01T" +  txt.split('-')[0].substr(-5,5)).getTime();
                // this.otForm.time_start = moment(t1).format('HH:ss');
                
                // let t2 = new Date("1970-01-01T" +  txt.split('-')[1]).getTime();
                // this.otForm.time_end = moment(t2).format('HH:ss');

                // let item = this.otData.find((i)=>i.userid == 506).c20230120;
                // ແອັດຂໍ້ມູນເຂົ້າຟອມ
                // this.FormProduct.name = item.name;
                // this.FormProduct.amount = item.amount;
                // this.FormProduct.price_buy = item.price_buy;
                // this.FormProduct.price_sell = item.price_sell;                
            // }
        },

        async AddOT_Range(dt, inx){
            // if(this.permiss.ros_edit == 0){
            //     this.$swal.fire({
            //         text: "You don't have permission update!",
            //         icon: 'error',
            //         showCancelButton: false,
            //         showConfirmButton: false,
            //         allowOutsideClick: false,
            //         timerProgressBar: true,
            //         timer: 1500
            //     })
            // } else {
                this.clearForm();
                let date = moment(dt.split('c')[1]).format('YYYY-MM-DD');

                this.otForm.datefr = date;
                this.otForm.dateto = date;
                this.otForm.days = 1;
                this.colinx = inx;
                $('#modalOT').modal('show');
            // }
        },

        AddOT_RangeCF(){
            $('#modalOT').modal('hide');
            this.TDiff();
            let dateList = [];
            let n = this.colinx; //column index
            let txt = this.otForm.hrs +'_'+ this.otForm.time_start +'-'+ this.otForm.time_end +'_N_Waiting scan';

            for (let i = 0; i < this.otForm.days; i++){
                    let col = this.otHead[n].colid;
                    this.otData.find((i)=>i.userid == this.otForm.userid)[col] = txt;

                    let date = moment(col.split('c')[1]).format('YYYY-MM-DD');
                    dateList.push(date);
                    n = n + 1;
                }
            // add to DB
            this.$axios.post('/api/overtime/addotrange', {
                list: dateList,
                userid: this.otForm.userid,
                start: this.otForm.time_start,
                end: this.otForm.time_end,
                hrs: this.otForm.hrs,
                type: this.otForm.ot_type,
                comm: this.otForm.remarks
            }).then(res => {
                console.log('Update completed');
            });
        },

        UpdOT_RangeCF(){
            $('#modalOT').modal('hide');
            this.TDiff();
            let dateList = [];
            let n = this.colinx; //column index
            let txt = this.otForm.hrs +'_'+ this.otForm.time_start +'-'+ this.otForm.time_end +'_N_Waiting scan';

            for (let i = 0; i < this.otForm.days; i++){
                    let col = this.otHead[n].colid;
                    this.otData.find((i)=>i.userid == this.otForm.userid)[col] = txt;

                    let date = moment(col.split('c')[1]).format('YYYY-MM-DD');
                    dateList.push(date);
                    n = n + 1;
                };

            this.$axios.post('/api/overtime/updotrange', {
                list: dateList,
                userid: this.otForm.userid,
                start: this.otForm.time_start,
                end: this.otForm.time_end,
                hrs: this.otForm.hrs,
                type: this.otForm.ot_type,
                comm: this.otForm.remarks
            }).then(res => {
                console.log('Update completed');
            });
        },

        DelOT_RangeCF(){
            $('#modalOT').modal('hide');
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
                    
                    let dateList = [];
                    let n = this.colinx; //column index
                    for (let i = 0; i < this.otForm.days; i++) {
                            let col = this.otHead[n].colid;
                            this.otData.find((i)=>i.userid == this.otForm.userid)[col] = null;

                            let date = moment(col.split('c')[1]).format('YYYY-MM-DD');
                            dateList.push(date);
                            n = n + 1
                        }

                    //delete in database
                    this.$axios.post('/api/overtime/delotrange', {
                        list: dateList,
                        userid: this.otForm.userid
                    }).then(res => {
                        $('#modalOT').modal('hide');
                    })
                } else {
                    $('#modalOT').modal('show');
                }
            });
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

        OTDetail(id){
            this.$axios.post('/api/overtime/otdetail', {
                userid: id,
                datefr: this.datefr,
                dateto: this.dateto
            }).then(res => this.otDetail = res.data);
            $('#modalOTDetail').modal('show');
        },

        RunUpdate_Scan(){
            this.$axios.post('/api/overtime/runscan')
            .then(res => {
                this.getOvertime();
            });
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
                type: this.otForm.ot_type,
                comm: this.otForm.remarks
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
            f.time_end='22:00';
            f.ot_type='E';
            f.remarks='';
            this.search2='';
        },

        countDays(inx, date, ix){
            this.countTxt = '';

            this.selDate.push({
                inx: inx,
                d: date
            });

            let d1 = this.selDate[0].d;
            let d2 = this.selDate[1].d;

            let inx1 = this.selDate[0].inx;
            let inx2 = this.selDate[1].inx;

            if (inx1 == inx2 && d1 !='' && d2 != '' && moment(d2).format("YYYYMMDD") > moment(d1).format("YYYYMMDD")){
                let date1 = new Date(d1);
                let date2 = new Date(d2);

                let diff = date2.getTime() - date1.getTime();
                let TotalDays = Math.ceil(diff / (1000 * 3600 * 24)) + 1;

                let sum = 0;
                for (let i = 0; i < TotalDays; i++){

                    let col = this.otHead[ix].colid;

                    let txt = this.otData.find((i)=>i.userid == this.otForm.userid)[col];
                    let val = parseFloat(txt.split("_")[0]);
                    sum += val
                    // dataList.push({
                    //     column: col,
                    //     val: parseFloat(txt.split("_")[0])
                    // });
                    ix = ix - 1;
                };

                this.countTxt = 'From: ' + moment(date1).format("DD/MM/YYYY") +' To '+ moment(date2).format("DD/MM/YYYY") +': ';
                this.countNum = TotalDays +' d, '+ sum + ' hrs'

                // let sum = dataList.reduce((a, b) => a + b.val, 0); //working

                this.selDate = [];
            } else {
                this.selDate = [];
            }
        },




        code(text){
            if (text) {
                return parseFloat(text.split("_")[0]);
            }
        },

        paid(text){
            if (text) {
                return text.split("_")[2];
            }
        },

        scan(text){
            if (text) {
                return text.split("_")[3];
            }
        },

        mentDate(text){
            if (text) {
                return moment(text.split("c")[1]).format("DD/MM/YYYY");
            }
        },

        Tformat(text){
            if (text){
                return text.split(":00.")[0];
            };
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

        shortname(text){
            if (text) {
                return text.split(" ")[1];
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