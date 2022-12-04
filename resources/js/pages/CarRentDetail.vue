<template>
    <div class="card card-body">
        <div class="breadcrumb-header justify-content-between align-items-center mb-1 mt-0" >
            <div>
                <h4 class="card-title mg-b-0">Car Rental</h4>
                <p class="tx-12 tx-gray-500 mb-0">Contractor car rental records...</p>
            </div>

            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="wd-xl-150 wd-lg-150 wd-md-150 wd-70p">
                    <Multiselect :options="lkMonth" v-model="mont" @select="montSelected()"/>
                </div>
                <div class="ms-1" style="width: 120px">
                    <Multiselect :options="lkYear" v-model="year" @select="yearSelected()"/>
                </div>
            </div>
        </div> 

        <div class="row">
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-3">
                <div class="table-responsive element border ht-250">
                    <table class="table main-table-reference text-nowrap mg-b-0">
                        <thead class="position-sticky" style="top: 0px">
                            <tr>
                                <th class="border-0">Departed Date</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr class="tr-hover cur-pointer" v-for="(lst, key) in dateList" :key="lst.id" @click="dateSelected(key, dateTime1(lst.rent_date))" :style="key === dateRowSel ? 'background-color: #dadee7; color: blue' : ''">
                                <td class="border-0">{{dateTime(lst.rent_date)}}</td>
                            </tr>                                                                                          
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 pt-1 pb-2 wd-100" @click="newDate()" title="Create a new date">
                        <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                        <span class="text-primary tx-13">New</span>
                    </div>
                </div>            
            </div>

            <div class="col-xl-10 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3 ">
                <div class="table-responsive element border ht-250">
                    <table class="table main-table-reference text-nowrap mg-b-0" >
                        <thead class="position-sticky" style="top: 0px; z-index: 1">
                            <tr>
                                <th class="border-start-0">Return Date</th>
                                <th>Driver</th>
                                <th>Company</th>
                                <th>LV No</th>
                                <th>LV Name</th>
                                <th>Type of Car</th>
                                <th>Fuel <span class="text-capitalize">(Km/L)</span></th>
                                <th>From</th>
                                <th>Destination</th>
                                <th>Route</th>
                                <th>Route <span class="text-capitalize">(Km)</span></th>
                                <th>Trip</th>
                                <th>Total <span class="text-capitalize">(Km)</span></th>
                                <th>Total <span class="text-capitalize">(L)</span></th>
                                <th>Refuel <span class="text-capitalize">(L)</span></th>
                                <th>Price <span class="text-capitalize">(Kip/L)</span></th>
                                <th>Remarks</th>
                                <th class="border-end-0 wd-60p position-sticky" style="right: -0.1px">Tools</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr class="tr-hover cur-pointer" v-for="(lst, key) in headList" :key="lst.id" @click="headSelected(key, lst.id)" :style="key === selHeadRow ? 'background-color: #dadee7; color: blue' : ''">
                                <td class="border-start-0">{{dateTime(lst.return_date)}}</td>
                                <td>{{lst.driver}}</td>
                                <td>{{lst.company}}</td>
                                <td>{{lst.equip_no}}</td>
                                <td>{{lst.equip_descr}}</td>
                                <td>{{lst.car_type}}</td>
                                <td class="text-end">{{formatNumber(lst.fuel_per_km)}}</td>
                                <td>{{lst.depart_point}}</td>
                                <td>{{lst.destination}}</td>
                                <td>{{lst.route}}</td>
                                <td class="text-end">{{formatNumber(lst.route_km)}}</td>
                                <td class="text-center">{{lst.trip}}</td>
                                <td class="text-end">{{formatNumber(lst.total_km)}}</td>
                                <td class="text-end">{{formatNumber(lst.total_l)}}</td>
                                <td class="text-end">{{formatNumber(lst.refuel)}}</td>
                                <td class="text-end">{{formatNumber(lst.fuel_price)}}</td>
                                <td class="laofont">{{lst.remarks}}</td>
                                <td class="p-0 align-middle border-end-0 position-sticky bg-white" style="right: -0.1px">
                                    <div class="d-flex justify-content-start ms-1">
                                        <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="editHead(lst.id)">
                                            <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                        </button> 
                                        <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="delHead(lst.id)">
                                            <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                        </button> 
                                    </div>
                                </td>
                            </tr>     
                        </tbody>
                    </table>
                    <div v-if="btnHeadNew" class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="newHead()" title="Create a new item">
                        <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                        <span class="text-primary tx-13">New</span>
                    </div>
                </div>                       
            </div>
        </div>

        <div v-if="btnDetailNew" class="table-responsive element border mt-4">
            <table class="table main-table-reference text-nowrap mg-b-0">
                <thead>
                    <tr>
                        <th class="border-start-0">Other Paid</th>
                        <th>Amount <span class="text-capitalize">(Kip)</span></th>
                        <th>Qty</th>
                        <th>Total <span class="text-capitalize">(Kip)</span></th>
                        <th>Remarks</th>
                        <th class="border-end-0 wd-65p">Tools</th>
                    </tr>
                </thead>
                <tbody> 
                    <tr class="tr-hover" v-for="lst in detailList" :key="lst.id">
                        <td class="border-start-0">{{lst.paid_type}}</td>
                        <td class="text-end">{{formatNumber(lst.amount)}}</td>
                        <td class="text-end">{{ lst.qty }}</td>
                        <td class="text-end">{{ formatNumber(lst.total_amount) }}</td>
                        <td class="laofont">{{ lst.remarks }}</td>
                        <td class="p-0 align-middle border-end-0">
                            <div class="d-flex justify-content-start ms-1">
                                <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="editDetail(lst.id)">
                                    <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                </button> 
                                <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="delDetail(lst.id)">
                                    <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                </button> 
                            </div>
                        </td>
                    </tr>                                                                                            
                </tbody>
            </table>
            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="newDetail()" title="Create a new item">
                <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                <span class="text-primary tx-13">New</span>
            </div>
        </div>
    </div>

    <!-- Add Date Modal -->
    <div class="modal fade" id="date" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dateLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header pb-1 bd-b-0">
                    <h6 class="text-muted main-content-label text-capitalize">Add New Date</h6>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body"> 
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <div class="wd-100p me-1">
                                <input type="date" class="form-control" v-model="headForm.depart_date" @keydown.enter="addDate()">
                            </div>
                            <div class="wd-35">
                                <button type="button" class="btn btn-primary wd-35 p-0" :class="addDateDis" title="Add new date" @click="addDate()">
                                    <i class="fa fa-plus"></i>
                                </button>  
                            </div>
                        </div>
                    </div>                           
                </div>
            </div>
        </div>                                              
    </div>  

    <!-- Head Modal -->
    <div class="modal fade effect-scale" id="head" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="headLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-muted">Car Rental Detail</h6>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="form-group">
                                <label class="mb-0">Depart Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" disabled v-model="headForm.depart_date">
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="form-group">
                                <label class="mb-0">Return Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" v-model="headForm.return_date">
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="form-group">
                                <label class="mb-0">Driver <span class="text-danger">*</span></label> 
                                <Multiselect v-model="headForm.driver" :options="lkDriver"/>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="mb-0">Company <span class="text-danger">*</span></label> 
                                <Multiselect v-model="headForm.company" :options="lkComp"/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="form-group">
                                <label class="mb-0">LV No. <span class="text-danger">*</span></label> 
                                <Multiselect v-model="headForm.equip_no" searchable="true" :options="lkEquipNo" @select="carNoSelected()"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mb-0">LV Name</label>
                                <input type="text" class="form-control" disabled v-model="headForm.equip_descr">
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="form-group">
                                <label class="mb-0">Depart From <span class="text-danger">*</span></label> 
                                <Multiselect v-model="headForm.depart_point" :options="lkFrom"/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="form-group">
                                <label class="mb-0">Destination <span class="text-danger">*</span></label> 
                                <Multiselect v-model="headForm.destination" :options="lkDest"/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-8">
                            <div class="form-group">
                                <label class="mb-0">Type of Car <span class="text-danger">*</span></label> 
                                <Multiselect v-model="headForm.car_type" :options="lkCarType" @select="carTypeSelected()"/>
                            </div>
                        </div>
                        <div class="col-lg-2 col-4">
                            <div class="form-group">
                                <label class="mb-0">Km/L <span class="text-danger">*</span></label>
                                <cleave :options="options"  class="form-control" v-model="headForm.fuel_per_km"/>
                            </div>
                        </div>
                        <div class="col-lg-2 col-6">
                            <div class="form-group">
                                <label class="mb-0">Price (Kip/L)</label>
                                <cleave :options="options"  class="form-control" v-model="headForm.fuel_price"/>
                            </div>
                        </div>
                        <div class="col-lg-2 col-6">
                            <div class="form-group">
                                <label class="mb-0">Refuel (L)</label>
                                <cleave :options="options"  class="form-control" v-model="headForm.refuel"/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-8">
                            <div class="form-group">
                                <label class="mb-0">Route <span class="text-danger">*</span></label> 
                                <Multiselect v-model="headForm.route" :options="lkRoute" @select="routeSelected()"/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-4">
                            <div class="form-group">
                                <label class="mb-0">Route Km <span class="text-danger">*</span></label>
                                <cleave :options="options"  class="form-control" v-model="headForm.route_km"/>
                            </div>
                        </div>
                        <div class="col-lg-2 col-4">
                            <div class="form-group">
                                <label class="mb-0">Trip <span class="text-danger">*</span><span class="text-white">{{formatNumber(totalKm)}}</span></label>
                                <cleave :options="options"  class="form-control" v-model="headForm.trip"/>
                            </div>
                        </div>
                        <div class="col-lg-2 col-4">
                            <div class="form-group">
                                <label class="mb-0">Total (Km)</label>
                                <cleave :options="options"  class="form-control" disabled v-model="headForm.total_km"/>
                            </div>
                        </div>
                        <div class="col-lg-2 col-4">
                            <div class="form-group">
                                <label class="mb-0">Total (L) <span class="text-white">{{formatNumber(totalL)}}</span></label>
                                <cleave :options="options"  class="form-control" disabled v-model="headForm.total_l"/>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="mb-0">Remarks</label>
                                <input type="text" class="form-control laofont" v-model="headForm.remarks">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button v-if="btnUpdShow" type="button" class="btn btn-primary" :class="addHeadDis" @click="updHead()"><i class="fe fe-save"></i> 
                        <span class="mx-1">Save</span>
                    </button>
                    <button v-if="btnAddShow" type="button" class="btn btn-primary" :class="addHeadDis" @click="addHead()"><i class="fe fe-plus"></i> 
                        <span class="mx-1">Add</span>
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                        <span class="mx-1">Close</span>
                    </button>
                </div>
            </div>
        </div>                                              
    </div>

    <!-- Detail Modal -->
    <div class="modal fade effect-scale" id="detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title text-muted">Other Pays</h6>
                    <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="mb-0">Type of Pay <span class="text-danger">*</span></label> 
                                <Multiselect v-model="detailForm.paid_type" :options="lkPaidType"/>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="mb-0">Amount <span class="text-capitalize">(Kip) </span><span class="text-danger">*</span></label>
                                <cleave :options="options" class="form-control" v-model="detailForm.amount"/>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="mb-0">Qty <span class="text-danger">*</span></label>
                                <cleave :options="options" class="form-control" v-model="detailForm.qty"/>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label class="mb-0">Total <span class="text-capitalize">(Kip)</span></label>
                                <cleave :options="options" class="form-control" disabled v-model="detailForm.total_amount"/>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="mb-0">Remarks<span class="text-white">{{formatNumber(totalAmount)}}</span></label>
                                <input type="text" class="form-control laofont" v-model="detailForm.remarks">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button v-if="btnUpdShow" type="button" class="btn btn-primary" :class="addDetailDis" @click="updDetail()"><i class="fe fe-save"></i> 
                        <span class="mx-1">Save</span>
                    </button>
                    <button v-if="btnAddShow" type="button" class="btn btn-primary" :class="addDetailDis" @click="addDetail()"><i class="fe fe-plus"></i> 
                        <span class="mx-1">Add</span>
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                        <span class="mx-1">Close</span>
                    </button>
                </div>
            </div>
        </div>                                              
    </div>

</template>

<script>
export default {
    name: 'XpplAppCarRentDetail',

    data() {
        return {
            profile: [],
            permiss: [],
            lkMonth: [],
            lkYear: [],
            lkComp: [],
            lkCarType: [],
            lkEquipNo: [],
            lkDriver: [],
            lkFrom: [],
            lkDest: [],
            lkRoute: [],
            lkPaidType: [],

            dateList: [],
            headList: [],
            detailList: [],
            dateRowSel: '',
            selHeadRow: '',
            btnHeadNew: '',
            btnDetailNew: '',
            btnUpdShow: '',
            btnAddShow: '',


            year: new Date().getFullYear(),
            mont: new Date().toLocaleString('en-us', { month: 'long' }),
            headForm: {id: '', company: '', car_type: '', equip_no: '', equip_descr: '', depart_date: '', return_date: '', driver: '', depart_point: 'XPPL', destination: '', route: '', route_km: '', trip: '2', total_km: '', total_l: '0', fuel_per_km: '', fuel_price: '', refuel: '', remarks: ''},
            detailForm: {id: '', rent_id: '', paid_type: '', amount: '', qty: '1', total_amount: '', remarks: ''},

            options:{
                numeral: true,
                numeralPositiveOnly: true,
                noImmediatePrefix: true,
                rawValueTrimPrefix: true,
                numeralIntegerScale: 10,
                numeralDecimalScale: 2,
                numeralDecimalMark: '.',
                delimiter: ','
			},
        
        };
    },

    mounted() {
        
    },

    computed: {
        addDateDis(){
            if (this.headForm.depart_date == ''){
                return 'disabled';
            } else {
                return '';
            }
        },

        addHeadDis(){
            let f = this.headForm;
            if (f.company == null || f.return_date == '' || f.driver == null || f.equip_no == null || f.depart_point == null || f.destination == null || f.car_type == null || f.fuel_per_km == '' || f.route == null || f.route_km == '' || f.trip == ''){
                return 'disabled';
            } else {
                return ''
            }
        },
        
        addDetailDis(){
            let f = this.detailForm;
            if (f.paid_type == null || f.paid_type == '' || f.amount == '' || f.qty == ''){
                return 'disabled';
            } else {
                return '';
            }
        },

        totalKm(){
            return this.headForm.total_km = this.headForm.route_km * this.headForm.trip;
        },

        totalL(){
            return this.headForm.total_l = this.headForm.total_km / this.headForm.fuel_per_km;   
        },

        totalAmount(){
            return this.detailForm.total_amount = this.detailForm.amount * this.detailForm.qty;   
        }
    },

    methods: {
        getDate(){
            this.$axios.post('/api/carRent/date', {
                mont: this.mont,
                year: this.year
            }).then(res => this.dateList = res.data)
        },

        newDate(){
            this.headForm.depart_date = '';
            $('#date').modal('show');
        },

        addDate(){
            this.$axios.post('/api/carRent/addDate', this.headForm)
            .then((res) => {
                if(res.data.success){
                    $('#date').modal('hide');
                    this.getDate();
                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        dateSelected(key, date){
            this.dateRowSel = key;
            this.detailList = '';
            this.selHeadRow = '';
            this.btnHeadNew = true;
            this.headForm.depart_date = date; 
            this.getHead(); 
        },

        headSelected(key, id){
            this.selHeadRow = key;
            this.btnDetailNew = true;
            this.detailForm.rent_id = id;
            this.getDetail(); 
        },

        yearSelected(){
            this.getDate();
            this.dateRowSel = '';
            this.btnHeadNew = '';
            this.btnDetailNew = '';
            this.headList = '';
            this.detailList = '';
        },

        montSelected(){
            this.getDate();
            this.dateRowSel = '';
            this.btnHeadNew = '';
            this.btnDetailNew = '';
            this.headList = '';
            this.detailList = '';
        },

        carTypeSelected(){
            this.$axios.post('/api/carRent/fuelKm', {carType: this.headForm.car_type})
            .then(res => this.headForm.fuel_per_km = res.data.value)
        },

        carNoSelected(){
            this.$axios.post('/api/lookup/equipdescr', {equip_no: this.headForm.equip_no})
            .then(res => this.headForm.equip_descr = res.data.equip_descr)
        },

        routeSelected(){
            this.$axios.post('/api/carRent/routeKm', {route: this.headForm.route})
            .then(res => {
                this.headForm.route_km = res.data.value;
                this.headForm.total_km = res.data.value * this.headForm.trip;
            })
        },

        getHead(){
            this.$axios.post('/api/carRent/head', {date: this.headForm.depart_date})
            .then(res => this.headList = res.data)
        },

        newHead(){
            this.headFormClear();
            this.btnAddShow = true;
            this.btnUpdShow = false;
            $('#head').modal('show');
        },

        addHead(){
            this.$axios.post('/api/carRent/addHead', this.headForm)
            .then((res) => {
                if(res.data.success){
                    $('#head').modal('hide');
                    this.getHead();
                    this.detailList = '';
                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        editHead(id){
            this.headFormClear();
            this.btnUpdShow = true;
            this.btnAddShow = false;
            $('#head').modal('show');

            this.$axios.post(`/api/carRent/editHead/${id}`)
            .then((res)=>{
                let f = this.headForm;
                    f.id = res.data.id;
                    f.company = res.data.company;
                    f.car_type = res.data.car_type;
                    f.equip_no = res.data.equip_no;
                    f.equip_descr = res.data.equip_descr;
                    f.depart_date = moment(res.data.depart_date).format('YYYY-MM-DD'); 
                    f.return_date = moment(res.data.return_date).format('YYYY-MM-DD'); 
                    f.driver = res.data.driver; 
                    f.depart_point = res.data.depart_point;
                    f.destination = res.data.destination; 
                    f.route = res.data.route; 
                    f.route_km = res.data.route_km; 
                    f.trip = res.data.trip;
                    f.total_km = res.data.total_km;
                    f.total_l = res.data.total_l;
                    f.fuel_per_km = res.data.fuel_per_km;
                    f.fuel_price = res.data.fuel_price;
                    f.refuel = res.data.refuel; 
                    f.remarks = res.data.remarks;
            }).catch((error)=>{
                console.log(error);
            })
        },

        updHead(){
            this.$axios.post('/api/carRent/updHead', this.headForm)
            .then((res)=>{

                $('#head').modal('hide');
                this.getHead();

            }).catch((error)=>{
                console.log(error);
            })
        },

        delHead(id){
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
                    this.$axios.post(`/api/carRent/delHead/${id}`)
                    .then((response)=>{

                        this.getHead();

                    }).catch((error)=>{
                        console.log(error);
                    })
                }
            });
        },

        getDetail(){
            this.$axios.post('/api/carRent/detail', {rent_id: this.detailForm.rent_id})
            .then(res => this.detailList = res.data)
        },

        newDetail(){
            this.detailFormClear();
            this.btnAddShow = true;
            this.btnUpdShow = false;
            $('#detail').modal('show');
        },

        addDetail(){
            this.$axios.post('/api/carRent/addDetail', this.detailForm)
            .then((res) => {
                if(res.data.success){
                    $('#detail').modal('hide');
                    this.getDetail();
                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        editDetail(id){
            this.detailFormClear();
            this.btnUpdShow = true;
            this.btnAddShow = false;
            $('#detail').modal('show');

            this.$axios.post(`/api/carRent/editDetail/${id}`)
            .then((res)=>{
                let f = this.detailForm;
                    f.id = res.data.id;
                    f.paid_type = res.data.paid_type;
                    f.amount = res.data.amount;
                    f.qty = res.data.qty;
                    f.total_amount = res.data.total_amount;
                    f.remarks = res.data.remarks;
            }).catch((error)=>{
                console.log(error);
            })
        },

        updDetail(){
            this.$axios.post('/api/carRent/updDetail', this.detailForm)
            .then((res)=>{

                $('#detail').modal('hide');
                this.getDetail();

            }).catch((error)=>{
                console.log(error);
            })
        },

        delDetail(id){
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
                    this.$axios.post(`/api/carRent/delDetail/${id}`)
                    .then((response)=>{

                        this.getDetail();

                    }).catch((error)=>{
                        console.log(error);
                    })
                }
            });
        },

        async lookup(){
            const pro = await axios.get('api/profile')
			this.profile = pro.data;

            const per = await axios.get('/api/permiss')
            this.permiss = per.data;

            const year = await axios.get('/api/lookup/year')
            this.lkYear = year.data;

            const mont = await axios.get('/api/lookup/month')
            this.lkMonth = mont.data;

            const comp = await axios.get('/api/carRent/carComp')
            this.lkComp = comp.data;

            const carType = await axios.get('/api/carRent/carType')
            this.lkCarType = carType.data;

            const from = await axios.get('/api/carRent/departPoint')
            this.lkFrom = from.data;

            const dest = await axios.get('/api/carRent/destination')
            this.lkDest = dest.data;

            const route = await axios.get('/api/carRent/route')
            this.lkRoute = route.data;

            const carno = await axios.get('/api/carRent/equipNo')
            this.lkEquipNo = carno.data;

            const paid = await axios.get('/api/carRent/paidType')
            this.lkPaidType = paid.data;

            const driver = await axios.post('/api/lookup/driver', {dept: this.profile.department})
            this.lkDriver = driver.data;

        },

        headFormClear(){
            let f = this.headForm;
                f.company = '';
                f.car_type = '';
                f.equip_no = '';
                f.equip_descr = '';
                f.return_date = ''; 
                f.driver = ''; 
                f.destination = ''; 
                f.route = ''; 
                f.route_km = ''; 
                f.trip = '2';
                f.total_km = '';
                f.total_l = '';
                f.fuel_per_km = '';
                f.fuel_price = '';
                f.refuel = ''; 
                f.remarks = '';
        },

        detailFormClear(){
            let f = this.detailForm;
                f.paid_type = '';
                f.amount = '';
                f.total_amount = '';
                f.remarks = '';
        },

        formatNumber(value) {
			let val = (value / 1).toFixed(2).replace(",", ".");
			return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
    },

    created() {
        this.getDate();
        this.lookup();


    },

    beforeRouteEnter(to, from, next){
        if(!window.Laravel.isLoggedin_laravel){
            window.location.href = "/signin";
        }
        next();
	}
};
</script>

<style>

</style>