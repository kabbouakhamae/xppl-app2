<template>
    <div class="card">
        <div class="card-header pd-r-15 pd-t-10 pb-0">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-start align-items-center">
                        <h4 class="card-title mg-b-0 text-muted text-capitalize">Production Data Entry </h4>
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
                                <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Export To Excel</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="card-body pd-t-0">
            <div class="d-lg-flex justify-content-between align-items-end mt-1 mb-1">
                    <!-- <div>
                        <button type="button" class="btn btn-sm btn-secondary " style="width: 55px" title="Roster details" @click="detailMode = 'roster'">Coal</button>
                        <button type="button" class="btn btn-sm btn-secondary rounded-0" style="width: 65px" title="Finger scan details" @click="detailMode = 'scan'">Waste</button>
                        <button type="button" class="btn btn-sm btn-secondary " data-bs-dismiss="modal" style="width: 87px" title="Close">Summary</button>
                    </div> -->
                    <div aria-label="Basic example" class="btn-group" role="group">
                        <button class="btn btn-secondary pd-x-25" type="button">Coal</button> 
                        <button class="btn btn-secondary pd-x-25" type="button">Waste</button> 
                        <button class="btn btn-secondary pd-x-25" type="button">Summary</button>
                    </div>


                    <div class="d-md-flex justify-content-end mt-xl-0 mt-lg-1 mt-md-1 mt-1">
                        <div class="d-flex justify-content-end">
                            <div class="wd-xl-150 wd-lg-150 wd-md-150 wd-70p">
                                <Multiselect :options="lkMonth" v-model="mont" @select="montSelected()"/>
                            </div>
                            <div class="ms-1" style="width: 120px">
                                <Multiselect :options="lkYear" v-model="year" @select="yearSelected()"/>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3">
                    <div class="table-responsive element border" style="height: 72vh">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px">
                                <tr>
                                    <th class="border-0">Mined Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover cur-pointer" v-for="(lst, key) in prodDate" :key="lst.id" @click="dateSelected(key, dateTime1(lst.MinedDate))" :style="key === dateRowSel ? 'background-color: #dadee7; color: blue' : ''">
                                    <td class="border-0">{{dateTime(lst.MinedDate)}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 pt-1 pb-2 wd-100" @click="dateNew()" title="Create a new date">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">New</span>
                        </div>
                    </div>
                </div>


                <div class="col-xl-10 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3">
                    <div class="table-responsive element border" style="height: 72vh">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>        
                                    <th class="border-start-0">No.</th>
                                    <th>Source</th>
                                    <th>Project</th>
                                    <th>Pit</th>
                                    <th>Block</th>
                                    <th>Shift</th>
                                    <th>Cont</th>
                                    <th>Bench</th>
                                    <th>Coal Block ID</th>
                                    <th>Spotted</th>
                                    <th>Coal Type</th>
                                    <th>Stockpile</th>
                                    <th>Truck No</th>
                                    <th>Total-Kg</th>
                                    <th>Truck-Kg</th>
                                    <th>Coal-Kg</th>
                                    <th>Ash_ad</th>
                                    <th>GCV_ar</th>
                                    <th>COG</th>
                                    <th class="border-end-0 wd-80p position-sticky" style="right: -0.1px">Tools</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover cur-pointer" v-for="lst in prodData" :key="lst.RecID">
                                    
                                    <td class="border-start-0">{{lst.no}}</td>
                                    <td>{{lst.Source}}</td>
                                    <td>{{lst.Project}}</td>
                                    <td>{{lst.Pit}}</td>
                                    <td>{{lst.PitStage}}</td>
                                    <td>{{lst.WorkShift}}</td>
                                    <td>{{lst.Cont}}</td>
                                    <td>{{lst.Bench}}</td>
                                    <td>{{lst.BlockID}}</td>
                                    <td>{{lst.Spotted}}</td>
                                    <td>{{lst.MinedCate}}</td>
                                    <td>{{lst.Stockpile}}</td>
                                    <td>{{lst.TruckNo}}</td>
                                    <td>{{num1(lst.TotalWeight_kg)}}</td>
                                    <td>{{num1(lst.TruckWeight_kg)}}</td>
                                    <td>{{num1(lst.CoalWeight_kg)}}</td>
                                    <td>{{num2(lst.Ash_ad)}}</td>
                                    <td>{{num1(lst.GCV_ar)}}</td>
                                    <td>{{lst.COG}}</td>

                                    
                                    <td class="p-0 align-middle border-end-0 position-sticky bg-white" style="right: -0.1px">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="prodEdit(lst.RecID)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="headDel(lst.RecID)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                        <!-- <div v-if="headNewShow" class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="headNew()" title="Create a new item">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">New</span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PRODUCTION ADD/EDIT -->
    <div class="modal fade effect-scale" id="production" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="prodLabel" aria-hidden="true" style="z-index: 1062">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-1 bd-b-0">
                    <h6 class="modal-title text-muted">Production</h6>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-6">
                            <div class="form-group">
                                <label class="mb-0">Source <span class="text-danger">*</span></label> 
                                <Multiselect v-model="coalForm.source" :options="lkSource"/>
                            </div>
                        </div>
                        <!-- <div class="col-lg-3 col-md-6 col-6">
                            <div class="form-group">
                                <label class="mb-0">Equipment No.<span class="text-danger">*</span></label> 
                                <input v-if="headForm.cost_type == 'IO'" type="text" class="form-control" v-model="coalForm.equip_no">
                                <Multiselect v-else v-model="coalForm.equip_no" searchable="true" @select="equipChanged()" :options="lkEquipno"/>
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-7 col-12">
                            <div class="form-group">
                                <label class="mb-0">Description</label>
                                <input v-if="headForm.cost_type == 'IO'" type="text" class="form-control laofont" v-model="coalForm.equip_descr">
                                <input v-else type="text" class="form-control laofont" disabled v-model="coalForm.equip_descr">
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-2 col-6">
                            <div class="form-group">
                                <label class="mb-0">Liter <span class="text-danger">*</span></label>
                                <cleave :options="options1" class="form-control" v-model="coalForm.liter"/>
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-3 col-md-6 col-6">
                            <div class="form-group">
                                <label class="mb-0">SMU</label>
                                <cleave :options="options" class="form-control" v-model="coalForm.smu"/>
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-4 col-md-6 col-6">
                            <div class="form-group">
                                <label v-if="headForm.cost_type == 'IO'" class="mb-0">Driver <span class="text-danger">*</span></label>
                                <label v-else class="mb-0">Driver</label>
                                <input v-if="headForm.cost_type == 'IO'" type="text" class="form-control" v-model="coalForm.driver">
                                <Multiselect v-else v-model="coalForm.driver" searchable="true" searchStart="true" title="Double click to add driver name" @dblclick="driverNew()" :options="lkDriver"/>                 
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-3 col-md-6 col-6">
                            <div class="form-group">
                                <label v-if="headForm.cost_type == 'IO'" class="mb-0">Refuel By <span class="text-danger">*</span></label>
                                <label v-else class="mb-0">Refuel By</label>
                                <input type="text" class="form-control" v-model="coalForm.refuel_by">
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-2 col-md-6 col-6">
                            <div class="form-group">
                                <label v-if="headForm.cost_type == 'IO'" class="mb-0">Reserve No</label>
                                <label v-else class="mb-0">Reserve No <span class="text-danger">*</span></label>
                                <cleave :options="options"  class="form-control" v-model="coalForm.reserve_no" title="After input the number then press Enter on keyboard to add" @keyup.enter="reserveNoAdd()"/>
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-3 col-md-6 col-6">
                            <div class="form-group">
                                <label v-if="headForm.cost_type == 'IO'" class="mb-0">Work Order</label>
                                <label v-else class="mb-0">Work Order <span class="text-danger">*</span></label>
                                <cleave :options="options"  class="form-control" v-model="coalForm.work_order"/>
                            </div>
                        </div> -->
                        
                        <!-- <div class="col-lg-7 col-12">
                            <div class="form-group">
                                <label class="mb-0">Material <span class="text-danger">*</span></label> 
                                <Multiselect v-model="coalForm.material" :options="lkMaterial"/>
                            </div>
                        </div> -->
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button v-if="updShow" type="button" class="btn btn-primary me-1" :class="detailAddDis" @click="detailUpd()"><i class="fe fe-save"></i> 
                            <span class="mx-1">Save</span>
                        </button>
                        <button v-if="addShow" type="button" class="btn btn-primary me-1" :class="detailAddDis" @click="detailAdd()"><i class="fe fe-plus"></i> 
                            <span class="mx-1">Add</span>
                        </button>
                        <button v-if="addShow" type="button" class="btn btn-purple me-1" :class="detailAddDis" @click="detailSave()"><i class="fe fe-save"></i> 
                            <span class="mx-1">Save</span>
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                            <span class="mx-1">Close</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>                                              
    </div>




</template>

<script>
export default {
    name: 'XpplAppProdEntry',

    data() {
        return {
            lkMonth: [],
            lkYear: [],
            lkSource: [],
            lkProj: [],
            lkPit: [],
            lkBlock: [],
            lkCont: [],
            lkBench: [],
            lkCut: [],
            lkSpot: [],
            lkCate: [],
            lkStp: [],
            lkTruck: [],
            lkCog: [],

            prodDate: [],
            prodData: [],

            year: new Date().getFullYear(),
            mont: new Date().toLocaleString('en-us', { month: 'long' }),

            dateRowSel: '',
            coalForm: {id: '', source: null, mined_date: '', project: null, pit: null, block: null, shift: null, cont: null, bench: null, block_id: null, spotted: '', mined_cate: null, stockpile: null, truck_no: null, gcv_ar: '', ash_ad: '', truck_weight_kg: '', total_weight_kg: '', coal_weight_kg: '', remarks: '', cog: ''}

        };
    },

    mounted() {
        
    },

    methods: {
        async onLoad(){

            const date = await axios.get(`/api/production/proddate?mon=${this.mont}&year=${this.year}`)
            this.prodDate =  date.data;

        
        },

        dateSelected(key, date){
            this.dateRowSel = key;
            // this.headRowSel = '';
            // this.detailList = '';
            // this.headNewShow = true;
            // this.coalForm.head_id = '';

            this.coalForm.mined_date = date; 

            this.$axios.get(`/api/production/proddata?date=${date}`)
                .then(res => {
                this.prodData = res.data;
            })
        },

        prodEdit(id){
            // this.headFormClear();
            this.addShow = false;
            this.updShow = true;
            this.coalForm.id = id;

            $('#production').modal('show');

            // this.$axios.post(`/api/fuel/headedit/${id}`)
            // .then(res => {
            //     this.headForm.id = res.data.id;
            //     this.headForm.plant = res.data.plant;
            //     this.headForm.cost_dept = res.data.cost_dept;
            //     this.headForm.cost_type = res.data.cost_type;
            //     this.headForm.cost_no = res.data.cost_no;
            //     this.headForm.approved_by = res.data.approved_by;
            //     this.headForm.location = res.data.location;
            // }).catch(err => {
            //     console.log(err);
            // })
        },

        prodUpd(){
            this.$axios.post('/api/fuel/headupd', this.headForm)
            .then((res)=>{
                $('#header').modal('hide');
                this.headRefresh();
            }).catch(err => {
                console.log(err);
            })
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

        num1(value) {
			let val = (value / 1).toFixed(0).replace(",", ".");
			return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		},

        num2(value) {
			let val = (value / 1).toFixed(2).replace(",", ".");
			return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		},


    },

    created(){
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