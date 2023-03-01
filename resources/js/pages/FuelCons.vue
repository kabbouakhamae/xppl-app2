<template>
    <div class="card">
        <div class="card-body">
            <div class="breadcrumb-header justify-content-between align-items-center mb-1 mt-0" >
                <div>
                    <h4 v-if="$i18n.locale=='en'" class="card-title mg-b-0">Fuel Reservation</h4>
                    <h4 v-else class="card-title mg-b-0">ການຈອງນໍ້າມັນເຊື້ອໄຟ</h4>
                    <p v-if="$i18n.locale=='en'" class="tx-12 tx-gray-500 mb-0">Daily fuel reservation records...</p>
                    <p v-else class="tx-12 tx-gray-500 mb-0">ບັນທຶກການຈອງນໍ້າມັນປະຈຳວັນ...</p>
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
                                    <th class="border-0">{{$t('refueldate')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover cur-pointer" v-for="(lst, key) in dateList" :key="lst.id" @click="dateSelected(key, dateTime1(lst.refuel_date))" :style="key === dateRowSel ? 'background-color: #dadee7; color: blue' : ''">
                                    <td class="border-0">{{dateTime(lst.refuel_date)}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 pt-1 pb-2 wd-100" @click="dateNew()" title="Create a new date">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">{{ $t('newItem') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Header table -->
                <div class="col-xl-10 col-lg-9 col-md-8 col-sm-9 ps-sm-0 pt-sm-0 pt-3">
                    <div class="table-responsive element border ht-250">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr class="laofont">        
                                    <th v-if="permiss.fuel_all == 1" class="border-start-0">{{ $t('dept') }}</th>
                                    <th v-if="permiss.fuel_all == 1">{{ $t('deptcode') }}</th>
                                    <th v-else class="border-start-0">{{ $t('dept') }}</th>
                                    <th>{{ $t('costtype') }}</th>
                                    <th>{{ $t('costcode') }}</th>
                                    <th>{{ $t('location') }}</th> 
                                    <th>{{ $t('approveBy') }}</th>
                                    <th>{{ $t('createAt') }}</th>
                                    <th>{{ $t('createBy') }}</th>
                                    <th>{{ $t('updateAt') }}</th>
                                    <th>{{ $t('updateBy') }}</th>
                                    <th class="border-end-0 wd-80p position-sticky pe-1" style="right: -0.1px">{{$t('tools')}}
                                        <!-- <div class=" pos-relative">{{$t('tools')}}</div>
                                        <div class="d-flex justify-content-end">
                                            <div class="pos-absolute mg-t-7">
                                                <button type="button" class="btn btn-info btn-b btn-sm rounded-circle p-0 ht-25 wd-25" title="Add new record" @click="headNew()">
                                                    <i class="fe fe-plus"></i>
                                                </button>
                                            </div>
                                        </div> -->
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tr-hover cur-pointer" v-for="(lst, key) in headList" :key="lst.id" @click="headSelected(key, lst.id, lst.cost_type), btnTest='true'" :style="key === headRowSel ? 'background-color: #dadee7; color: blue' : ''">
                                    <td v-if="permiss.fuel_all == 1" class="border-start-0">{{lst.created_dept}}</td>
                                    <td v-if="permiss.fuel_all == 1">{{lst.cost_dept}}</td>
                                    <td v-else class="border-start-0">{{lst.cost_dept}}</td>
                                    <td>{{lst.cost_type}}</td>
                                    <td>{{lst.cost_no}}</td>
                                    <td class="laofont">{{lst.location}}</td>
                                    <td>{{lst.approved_by}}</td>
                                    <td class="text-muted">{{dateTime2(lst.created_at)}}</td>
                                    <td class="text-muted text-capitalize">{{lst.created_by}}</td>
                                    <td class="text-muted">{{dateTime2(lst.updated_at)}}</td>
                                    <td class="text-muted text-capitalize">{{lst.updated_by}}</td>
                                    <td class="p-0 align-middle border-end-0 position-sticky bg-white" style="right: -0.1px">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="headEdit(lst.id)">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="headDel(lst.id)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="headNewShow" class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="headNew()" title="Create a new item">
                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                            <span class="text-primary tx-13">{{ $t('newItem') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Detail table -->
            <div v-if="detailNewShow" class="table-responsive element border mt-4" style="max-height: 490px">
                <table class="table main-table-reference text-nowrap mg-b-0">
                    <thead class="position-sticky" style="top: 0px; z-index: 1">
                        <tr class="laofont">
                            <th class="border-start-0 px-1">{{$t('no.')}}</th>
                            <th>{{$t('equipNo')}}</th>
                            <th>{{$t('equipDescr')}}</th>
                            <th>{{$t('liter')}}</th>
                            <th>{{$t('SMU')}}</th>
                            <th>{{$t('driver')}}</th>
                            <th>{{$t('shift')}}</th>
                            <th>{{$t('material')}}</th>
                            <th>{{$t('workOrder')}}</th>
                            <th>{{$t('reserveNo')}}</th>
                            <th>{{$t('createAt')}}</th>
                            <th>{{$t('createBy')}}</th>
                            <th>{{$t('updateAt')}}</th>
                            <th>{{$t('updateBy')}}</th>
                            <th class="border-end-0 wd-80p position-sticky pe-1" style="right: -0.1px">{{$t('tools')}}
                                <!-- <div class=" pos-relative">{{$t('tools')}}</div>
                                <div class="d-flex justify-content-end">
                                    <div class="pos-absolute mg-t-7">
                                        <button type="button" class="btn btn-info btn-b btn-sm rounded-circle p-0 ht-25 wd-25" title="Add new record" @click="detailNew()">
                                            <i class="fe fe-plus"></i>
                                        </button>
                                    </div>
                                </div> -->
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="tr-hover" v-for="(lst, key) in detailList" :key="lst.id">
                            <td class="border-start-0 tx-center">{{ key + 1 }}</td>
                            <td>{{ lst.equip_no }}</td>
                            <td>{{ lst.equip_descr }}</td>
                            <td class="text-end">{{ parseFloat(lst.liter) }}</td>
                            <td class="text-end">{{ lst.smu }}</td>
                            <td class="text-capitalize">{{ lst.driver }}</td>
                            <td>{{ lst.work_shift }}</td>
                            <td>{{ lst.material_descr }}</td>
                            <td>{{ lst.work_order }}</td>
                            <td class="text-center">{{ lst.reserve_no }}</td> 
                            <td class="text-muted">{{dateTime2(lst.created_at)}}</td>
                            <td class="text-muted text-capitalize">{{lst.created_by}}</td>
                            <td class="text-muted">{{dateTime2(lst.updated_at)}}</td>
                            <td class="text-muted text-capitalize">{{lst.updated_by}}</td>
                            <td class="p-0 align-middle border-end-0 position-sticky bg-white" style="right: -0.1px">
                                <div class="d-flex justify-content-start ms-1">
                                    <button class="btn btn-icon btn-sm btn-i p-0" title="Edit" @click="detailEdit(lst.id)">
                                        <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                    </button> 
                                    <button class="btn btn-icon btn-sm btn-i p-0" title="Delete" @click="detailDel(lst.id)">
                                        <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                    </button> 
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="detailNew()" title="Create a new item">
                    <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                    <span class="text-primary tx-13">{{ $t('newItem') }}</span>
                </div>
            </div>

            <!-- Add Date Modal -->
            <div class="modal" id="date" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dateLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header pb-1 bd-b-0">
                            <h6 class="text-muted main-content-label text-capitalize">{{ $t('newDate') }}</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">                                
                            <div class="form-group">                              
                                <div class="d-flex justify-content-between">
                                    <div class="wd-100p me-1">
                                        <input type="date" class="form-control" v-model="headForm.refuel_date" @keydown.enter="dateAdd()">
                                    </div>
                                    <div class="wd-35">
                                        <button type="button" class="btn btn-primary wd-35 p-0" :class="dateAddDis" title="Add new date" @click="dateAdd()">
                                            <i class="fa fa-plus"></i>
                                        </button>  
                                    </div>
                                </div>                           
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div> 

            <!-- Modal Header -->
            <div class="modal" id="header" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="headLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 v-if="$i18n.locale=='en'" class="modal-title text-muted">Reservation Title</h6>
                            <h6 v-else class="modal-title text-muted">ຫົວຂໍ້ການຈອງ</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('plant') }} <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="headForm.plant" :options="lkPant"/>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('deptComp') }} <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="headForm.cost_dept" searchable="true" searchStart="true" @select="deptChanged()" :options="lkDept"/>
                                    </div>
                                </div>
                                <div class="col-sm-5 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('costtype') }} <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="headForm.cost_type" @select="typeChanged()" :options="lkType"/>
                                    </div>
                                </div>                                
                                <div class="col-sm-7 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('costcode') }} <span class=" text-danger">*</span></label>
                                        <Multiselect v-if="headForm.cost_type == 'WBS'" v-model="headForm.cost_no" :options="lkWbs"/>
                                        <cleave v-else-if="headForm.cost_type == 'Cost Center'" :options="options" disabled class="form-control" v-model="headForm.cost_no" />
                                        <cleave v-else :options="options"  class="form-control" v-model="headForm.cost_no" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('approveBy') }}</label> 
                                        <Multiselect v-model="headForm.approved_by" :options="lkApprove"/>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('location') }}</label>
                                        <input type="text" class="form-control" v-model="headForm.location">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button v-if="updShow" type="button" class="btn btn-primary me-1" :class="headAddDis" @click="headUpd()"><i class="fe fe-check-circle"></i> 
                                    <span class="mx-1">{{ $t('update') }}</span>
                                </button>
                                <button v-if="addShow" type="button" class="btn btn-primary me-1" :class="headAddDis" @click="headAdd()"><i class="fe fe-plus"></i> 
                                    <span class="mx-1">{{ $t('add') }}</span>
                                </button>
                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                                    <span class="mx-1">{{ $t('close') }}</span>
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div>

            <!-- Modal details -->
            <div class="modal" id="detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailLabel" aria-hidden="true" style="z-index: 1062">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 v-if="$i18n.locale=='en'" class="modal-title text-muted">Reservation Details</h6>
                            <h6 v-else class="modal-title text-muted">ລາຍລະອຽດການຈອງ</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('shift') }} <span class="text-danger">*</span></label> 
                                        <Multiselect v-model="detailForm.work_shift" :options="lkShift"/>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('equipNo') }}<span class="text-danger">*</span></label> 
                                        <input v-if="headForm.cost_type == 'IO'" type="text" class="form-control" v-model="detailForm.equip_no">
                                        <Multiselect v-else v-model="detailForm.equip_no" searchable="true" @select="equipChanged()" :options="lkEquipno"/>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-12">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('equipDescr') }}</label>
                                        <input v-if="headForm.cost_type == 'IO'" type="text" class="form-control" v-model="detailForm.equip_descr">
                                        <input v-else type="text" class="form-control" disabled v-model="detailForm.equip_descr">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('liter') }} <span class="text-danger">*</span></label>
                                        <cleave :options="options1" class="form-control" v-model="detailForm.liter"/>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('SMU') }}</label>
                                        <cleave :options="options" class="form-control" v-model="detailForm.smu"/>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-6">
                                    <div class="form-group">
                                        <label v-if="headForm.cost_type == 'IO'" class="mb-0">{{ $t('driver') }} <span class="text-danger">*</span></label>
                                        <label v-else class="mb-0">{{ $t('driver') }}</label>
                                        <input v-if="headForm.cost_type == 'IO'" type="text" class="form-control" v-model="detailForm.driver">
                                        <Multiselect v-else v-model="detailForm.driver" searchable="true" searchStart="true" title="Double click to add driver name" @dblclick="driverNew()" :options="lkDriver"/>                 
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6">
                                    <div class="form-group">
                                        <label v-if="headForm.cost_type == 'IO'" class="mb-0">Refuel By <span class="text-danger">*</span></label>
                                        <label v-else class="mb-0">Refuel By</label>
                                        <input type="text" class="form-control" v-model="detailForm.refuel_by">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-6">
                                    <div class="form-group">
                                        <label v-if="headForm.cost_type == 'IO'" class="mb-0">{{ $t('reserveNo') }}</label>
                                        <label v-else class="mb-0">{{ $t('reserveNo') }} <span class="text-danger">*</span></label>
                                        <cleave :options="options"  class="form-control" v-model="detailForm.reserve_no" title="After input the number then press Enter on keyboard to add" @keyup.enter="reserveNoAdd()"/>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6">
                                    <div class="form-group">
                                        <label v-if="headForm.cost_type == 'IO'" class="mb-0">{{ $t('workOrder') }}</label>
                                        <label v-else class="mb-0">{{ $t('workOrder') }} <span class="text-danger">*</span></label>
                                        <cleave :options="options"  class="form-control" v-model="detailForm.work_order"/>
                                    </div>
                                </div>
                                
                                <div class="col-lg-7 col-12">
                                    <div class="form-group">
                                        <label class="mb-0">{{ $t('material') }} <span class="text-danger">*</span></label> 
                                        <Multiselect v-model="detailForm.material" :options="lkMaterial"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button v-if="updShow" type="button" class="btn btn-primary me-1" :class="detailAddDis" @click="detailUpd()"><i class="fe fe-check-circle"></i> 
                                    <span class="mx-1">{{ $t('update') }}</span>
                                </button>
                                <button v-if="addShow" type="button" class="btn btn-primary me-1" :class="detailAddDis" @click="detailAdd()"><i class="fe fe-plus"></i> 
                                    <span class="mx-1">{{ $t('add') }}</span>
                                </button>
                                <button v-if="addShow" type="button" class="btn btn-purple me-1" :class="detailAddDis" @click="detailSave()"><i class="fe fe-save"></i> 
                                    <span class="mx-1">{{ $t('save') }}</span>
                                </button>
                                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                                    <span class="mx-1">{{ $t('close') }}</span>
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div>

            <div class="modal pd-t-100 bd-0 bg-black-7" id="driver" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dropoffLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header pb-1 bd-b-0">
                            <h6 class="text-muted">Add Driver Name</h6>
                        </div>
                        <div class="modal-body">                                
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <div class="wd-100p me-1">
                                        <input type="text" class="form-control" v-model="detailForm.driver" @keydown.enter="driverAdd()">
                                    </div>
                                    <div class="wd-35">
                                        <button type="button" class="btn btn-primary wd-35 p-0" :class="driverAddDis" title="Add driver name" @click="driverAdd()">
                                            <i class="fa fa-plus"></i>
                                        </button> 
                                    </div>
                                </div>                        
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
    name: 'XpplAppFuelCons',

    data() {
        return {
            permiss: [],
            dateList: [],
            headList: [],
            detailList: [],

            lkYear: [],
            lkMont: [],
            lkDept: [],
            lkType: [],
            lkEquipno: [],
            lkMaterial: [],
            lkApprove: [],
            lkWbs: [],
            lkPant: ['XPPL', 'XTPPL'],
            lkShift: ['DS', 'NS'],
            lkDriver: [],
            
            year: new Date().getFullYear(),
            mont: new Date().toLocaleString('en-us', { month: 'long' }),
            headForm: {id: '', plant: 'XPPL', refuel_date: '', created_dept: '', cost_dept: null, cost_type: null, cost_no: '', location: '', approved_by: null},
            detailForm: {id: '', head_id: '', work_shift: 'DS', equip_no: null, equip_descr: '', work_order: '', reserve_no: '', material: '40023205', smu: '', liter: '', driver: '', refuel_by: ''},
            
            dateRowSel: '',
            headRowSel: '',
            headNewShow: '',
            detailNewShow: '',
            addShow: '',
            updShow: '',



            options:{
                // prefix: '$',
                numeral: true,
                numeralPositiveOnly: true,
                noImmediatePrefix: true,
                rawValueTrimPrefix: true,
                numeralIntegerScale: 15,
                numeralDecimalScale: 0,
                numeralDecimalMark: '.',
                delimiter: ''
			},

            options1:{
                numeral: true,
                numeralPositiveOnly: true,
                noImmediatePrefix: true,
                rawValueTrimPrefix: true,
                numeralIntegerScale: 4,
                numeralDecimalScale: 2,
                numeralDecimalMark: '.',
                delimiter: ','
			},
        };
    },

    mounted() {
        
    },

    computed: {
        dateAddDis(){
            if (this.headForm.refuel_date == ''){
                return 'disabled';
            } else {
                return '';
            }
        },

        headAddDis(){
            if (this.headForm.plant == null || this.headForm.cost_dept == null || this.headForm.cost_type == null || this.headForm.cost_no == '' || this.headForm.cost_no == null){
                return 'disabled';
            } else {
                return '';
            }
        },
        detailAddDis(){
            if (this.headForm.cost_type == 'IO'){
                if (this.detailForm.equip_no == null || this.detailForm.equip_descr == '' || this.detailForm.liter == '' || this.detailForm.work_shift == null || this.detailForm.driver == '' || this.detailForm.refuel_by == '' || this.detailForm.material == null){
                    return 'disabled';
                } else {
                    return '';
                }
            } else {
                if (this.detailForm.equip_no == null || this.detailForm.liter == '' || this.detailForm.work_shift == null || this.detailForm.material == null || this.detailForm.reserve_no == '' || this.detailForm.work_order == ''){
                    return 'disabled';
                } else {
                    return '';
                }
            }
        },
        driverAddDis(){
            if (this.detailForm.driver == ''){
                return 'disabled'
            } else {
                return '';
            }
        }
    },

    methods: {
        async loginInfo(){
			const profile = await axios.get('api/profile')
			this.headForm.created_dept = profile.data.department;

            const perRes = await axios.get('/api/permiss')
            this.permiss = perRes.data;

            const mn = await axios.get('/api/fuel/getmin')
            this.dateSelected(0, mn.data);

		},

        getDate(){
            this.$axios.post('/api/fuel/date', {
                mont: this.mont,
                year: this.year
            }).then(res => this.dateList = res.data)
        },

        dateNew(){
            this.headForm.refuel_date = '';
            $('#date').modal('show');
        },

        dateAdd(){
            this.$axios.post('/api/fuel/dateadd', {date: this.headForm.refuel_date})
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
            this.headRowSel = '';
            this.detailList = '';
            this.headNewShow = true;
            this.detailForm.head_id = '';
            this.headForm.refuel_date = date; 

            this.$axios.post('/api/fuel/head', {
                date: this.headForm.refuel_date,
                dept: this.headForm.created_dept,
                permiss: this.permiss.fuel_all
            }).then(res => {
                this.headList = res.data;
            })
        },

        yearSelected(){
            this.getDate();
            this.dateRowSel = '';
            this.headNewShow = '';
            this.detailNewShow = '';
            this.headList = [];
            this.detailList = [];
        },

        montSelected(){
            this.getDate();
            this.dateRowSel = '';
            this.headNewShow = '';
            this.detailNewShow = '';
            this.headList = [];
            this.detailList = [];
        },

        headSelected(key, id, type){
            this.headRowSel = key;
            this.detailNewShow = true;
            this.detailForm.head_id = id;
            this.headForm.cost_type = type;
            this.getDetail()


        },

        headNew(){
            this.headFormClear();
            this.updShow = false;
            this.addShow = true;
            $('#header').modal('show');
        },

        headRefresh(){
            this.headRowSel = '';
            this.detailNewShow = false;
            this.detailList = [];

            this.$axios.post('/api/fuel/head', {
                date: this.headForm.refuel_date,
                dept: this.headForm.created_dept,
                permiss: this.permiss.fuel_all
            }).then(res => {
                this.headList = res.data;
            })
        },

        headAdd(){
            this.$axios.post('/api/fuel/headadd', this.headForm)
            .then(res => {
                if (res.data.success){
                    $('#header').modal('hide');
                    this.headRefresh();
                } else {
                    alert(res.data.message)
                }
            }).catch(err => {
                console.log(err);
            })
        },

        headEdit(id){
            this.headFormClear();
            this.addShow = false;
            this.updShow = true;
            $('#header').modal('show');

            this.$axios.post(`/api/fuel/headedit/${id}`)
            .then(res => {
                this.headForm.id = res.data.id;
                this.headForm.plant = res.data.plant;
                this.headForm.cost_dept = res.data.cost_dept;
                this.headForm.cost_type = res.data.cost_type;
                this.headForm.cost_no = res.data.cost_no;
                this.headForm.approved_by = res.data.approved_by;
                this.headForm.location = res.data.location;
            }).catch(err => {
                console.log(err);
            })
        },

        headUpd(){
            this.$axios.post('/api/fuel/headupd', this.headForm)
            .then((res)=>{
                $('#header').modal('hide');
                this.headRefresh();
            }).catch(err => {
                console.log(err);
            })
        },

        headDel(id){
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
                        this.$axios.post(`/api/fuel/headdel/${id}`)
                        .then((response)=>{

                            this.headRefresh();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            // }
        },

        getDetail(){
            this.$axios.post('/api/fuel/detail', {
                    head_id: this.detailForm.head_id
            }).then(res => {
                this.detailList = res.data;
            })
        },

        detailNew(){
            $('#detail').modal('show');
            this.detailFormClear();
            this.updShow = false;
            this.addShow = true;
            this.addShow =  true;
        },

        detailAdd(){
            this.$axios.post('/api/fuel/detailadd', this.detailForm)
            .then(res => {
                if (res.data.success){
                    this.detailFormClear();
                    this.getDetail();
                } else {
                    alert(res.data.message)
                }
            }).catch(err => {
                console.log(err);
            })
        },

        detailSave(){
            this.detailAdd();
            $('#detail').modal('hide');
        },

        detailEdit(id){
            this.detailFormClear();
            this.addShow = false;
            this.updShow = true;
            $('#detail').modal('show');

            this.$axios.post(`/api/fuel/detailedit/${id}`)
            .then(res => {
                let f = this.detailForm;
                    f.id = res.data.id;
                    f.work_shift = res.data.work_shift;
                    f.equip_no = res.data.equip_no;
                    f.equip_descr = res.data.equip_descr;
                    f.work_order = res.data.work_order;
                    f.reserve_no = res.data.reserve_no;
                    f.material = res.data.material;
                    f.smu = res.data.smu;
                    f.liter = res.data.liter;
                    f.driver = res.data.driver;
                    f.refuel_by = res.data.refuel_by;
            }).catch(err => {
                console.log(err);
            })
        },

        detailUpd(){
            this.$axios.post('/api/fuel/detailupd', this.detailForm)
            .then(res => {
                $('#detail').modal('hide');
                this.getDetail();
            }).catch(err => {
                console.log(err);
            })
        },

        detailDel(id){
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
                        this.$axios.post(`/api/fuel/detaildel/${id}`)
                        .then((response)=>{

                           this.getDetail();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            // }
        },
       
        deptChanged(){
            this.typeChanged();
        },

        typeChanged(){
            if (this.headForm.cost_type == 'WBS'){
                this.headForm.cost_no = '';
            } else {
                this.$axios.post('/api/lookup/costno', {dept: this.headForm.cost_dept})
                .then(res => {
                    this.headForm.cost_type = res.data.category;
                    this.headForm.cost_no = res.data.value;
                }) 
            }
        },

        async equipChanged(){
            const descr = await axios.post('/api/lookup/equipdescr', {equip_no: this.detailForm.equip_no})
            this.detailForm.equip_descr = descr.data.equip_descr;

            const orderno = await axios.post('/api/lookup/workorder', {
                equip_no: this.detailForm.equip_no, 
                year_mont: moment(this.headForm.refuel_date).format('YYYYMM')
            })
            this.detailForm.work_order = orderno.data.work_order;
            this.detailForm.reserve_no = orderno.data.reserve_no;
        },

        async lookup(){
            
            const year = await axios.get('/api/lookup/year')
            this.lkYear = year.data;

            const mont = await axios.get('/api/lookup/month')
            this.lkMonth = mont.data;

            const fdept = await axios.get('/api/lookup/fueldept')
            this.lkDept = fdept.data;

            const costtype = await axios.get('/api/lookup/costtype')
            this.lkType = costtype.data;

            const equipno = await axios.get('/api/lookup/equipno')
            this.lkEquipno = equipno.data;

            const material = await axios.get('/api/lookup/material')
            this.lkMaterial = material.data;

            const dept = await axios.post('/api/lookup/approve', {dept: this.headForm.created_dept})
            this.lkApprove = dept.data;

            const wbs = await axios.post('/api/lookup/wbs', {dept: this.headForm.created_dept})
            this.lkWbs = wbs.data;

            const driver = await axios.post('/api/lookup/driver', {dept: this.headForm.created_dept})
            this.lkDriver = driver.data;


        },

        headFormClear(){
            let f = this.headForm;
                f.plant = 'XPPL';
                f.cost_dept = null;
                f.cost_type = null;
                f.cost_no = null;
                f.location = '';
                f.approved_by = '';
        },

        detailFormClear(){
            let f = this.detailForm;
                f.work_shift = 'DS';
                f.equip_no = null;
                f.equip_descr = '';
                f.work_order = '';
                f.reserve_no = '';
                f.material = '40023205';
                f.smu = '';
                f.liter = '';
                f.driver = '';
                f.refuel_by = '';
        },

        reserveNoAdd(){
            this.$axios.post('/api/fuel/reserveadd', {
                reserveNo: this.detailForm.reserve_no,
                workOrder: this.detailForm.work_order
            })  
        },

        driverNew(){
            this.detailForm.driver = '';
            $('#driver').modal('show');
        },

        driverAdd(){
            this.$axios.post('/api/lookup/driveradd', {
                dept: this.headForm.created_dept,
                driver: this.detailForm.driver
            }).then(res => {
                $('#driver').modal('hide');
                this.lkDriver = res.data;
            })
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

        dateTime2(value) {
            if(value) {
                return moment(value).format('DD-MM-YYYY HH:mm');
            }
        }
    },

    created(){
        this.getDate();
        this.loginInfo();
        this.lookup();
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
    .form-control:disabled{
        background-color: white;
        opacity: 1;
    }

    .add-hover:hover{
        color: blue;
        font-weight: bold;
    }
</style>