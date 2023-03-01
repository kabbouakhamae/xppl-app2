<template>
    <div v-if="loading">
        <loading/>
    </div> 
    <div v-else>
        <div class="card">
            <div v-if="view == 'data'">
                <div class="card-header pd-r-15 pd-t-10 pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-start align-items-center">
                            <h4 v-if="$i18n.locale=='en'" class="card-title mg-b-0 text-muted text-capitalize">Incident Records In {{year}}</h4>
                            <h4 v-else class="card-title mg-b-0 text-muted text-capitalize">ບັນທຶກເຫດການໃນປິ {{year}}</h4>
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
                            <input class="form-control" style="padding-left: 25px" type="text" :placeholder="$t('search')" v-model="search" @input="searchChanged()" :title="$t('search')">
                                <i class="fe fe-search search-i text-muted"></i>
                            <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                        </div>
                        <div class="d-flex justify-content-end mt-xl-0 mt-lg-0 mt-md-1 mt-1">
                            <div class="wd-md-150 wd-100p">
                                <Multiselect v-model="year" :searchStart="true" :options="lkYear" @select="selChanged()"/>
                            </div>
                            <div class="wd-35 ms-1">
                                <button type="button" class="btn btn-outline-primary p-0 border wd-35" title="Add new record" @click="newIncident(), btn =true">
                                    <i class="fa fa-plus tx-14"></i>
                                </button>
                            </div> 
                        </div>
                    </div>

                    <div class="table-responsive border-start border-end border-bottom" style="max-height: 78vh">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>
                                    <!-- <th class="position-sticky border-start-0" style="left: -0.1px">ID</th> -->
                                    <th class="border-start-0  px-1">{{ $t('no.') }}</th>
                                    <!-- <th>Inc No.</th> -->
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('incNo') }}
                                            <i v-if="filterMode =='inc_no'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_no'), filterName ='Incident Number'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_no'), filterName ='Incident Number'"></i>
                                        </div>
                                    </th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('date') }}
                                            <i v-if="filterMode =='inc_date'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_date'), filterName ='Incident Date'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_date'), filterName ='Incident Date'"></i>
                                        </div>
                                    </th>
                                    <th>{{ $t('incTitle') }}</th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('company') }}
                                            <i v-if="filterMode =='company'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('company'), filterName ='Company'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('company'), filterName ='Company'"></i>
                                        </div>
                                    </th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('dept') }}
                                            <i v-if="filterMode =='dept'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('dept'), filterName ='Department'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('dept'), filterName ='Department'"></i>
                                        </div>
                                    </th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('incGroup') }}
                                            <i v-if="filterMode =='inc_group'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_group'), filterName ='Incident Group'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_group'), filterName ='Incident Group'"></i>
                                        </div>
                                    </th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('incType') }}
                                            <i v-if="filterMode =='inc_type'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_type'), filterName ='Incident Type'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_type'), filterName ='Incident Type'"></i>
                                        </div>
                                    </th>
                                    <th>{{ $t('significant') }}</th>
                                    <th>{{ $t('alert24') }}</th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('injType') }}
                                            <i v-if="filterMode =='injury_type'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('injury_type'), filterName ='Injury Type'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('injury_type'), filterName ='Injury Type'"></i>
                                        </div>
                                    </th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('injGroup') }}
                                            <i v-if="filterMode =='injury_group'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('injury_group'), filterName ='Injury Group'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('injury_group'), filterName ='Injury Group'"></i>
                                        </div>
                                    </th>
                                    <th>{{ $t('bodyPart') }}</th>
                                    <th>{{ $t('actual') }}</th>
                                    <th>{{ $t('potential') }}</th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('rating') }}
                                            <i v-if="filterMode =='risk_rating'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('risk_rating'), filterName ='Risk Rating'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('risk_rating'), filterName ='Risk Rating'"></i>
                                        </div>
                                    </th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('invStatus') }}
                                            <i v-if="filterMode =='invest_status'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('invest_status'), filterName ='Investigate Status'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('invest_status'), filterName ='Investigate Status'"></i>
                                        </div>
                                    </th>
                                    <th>{{ $t('invBy') }}</th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">{{ $t('actStatus') }}
                                            <i v-if="filterMode =='action_status'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('action_status'), filterName ='Action Status'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('action_status'), filterName ='Action Status'"></i>
                                        </div>
                                    </th>
                                    <th>{{ $t('actDate') }}</th>
                                    <th>{{ $t('incManager') }}</th>
                                    <th>{{ $t('followUp') }}</th>
                                    <th>{{ $t('correctAct') }}</th>
                                    <th>{{ $t('incDescr') }}</th>
                                    <th class="border-end-0 position-sticky wd-60p" style="right: -0.1px">{{ $t('tools') }}</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr v-for="(row, inx) in incData" :key="row.id" class="tr-hover">
                                    <!-- <td class="border-start-0 position-sticky cur-pointer" style="left: -0.1px; background-color: #FFFFFF" title="Double click to see details" @click="preview(row.id)" >{{row.inc_id}}</td> -->
                                    <td class="border-start-0 tx-center" >{{inx +1}}</td>
                                    <th class="tx-center">{{row.inc_no}}</th>
                                    <td>{{dformat(row.inc_date)}}</td>
                                    <td class="bg-danger cur-pointer" v-if="row.risk_rating >=20" @click="preview(row.id, row.inc_id)" title="Preview">{{cutWord(row.inc_title)}}</td>
                                    <td class="cur-pointer" v-else @click="preview(row.id, row.inc_id)" title="Preview">{{cutWord(row.inc_title)}}</td>
                                    <!-- <td>{{cutWord(row.inc_title)}}</td> -->
                                    <td>{{row.company}}</td>
                                    <td>{{row.dept}}</td>
                                    <td>{{row.inc_group}}</td>
                                    <td>{{row.inc_type}}</td>
                                    <td class="tx-center">{{row.significant}}</td>
                                    <td class="tx-center">{{row.flash_alert}}</td>
                                    <td>{{row.injury_type}}</td>
                                    <td>{{row.injury_group}}</td>
                                    <td>{{ cutWord(row.injury_part) }}</td>
                                    <td>
                                        <div class="progress mg-t-2" style="width: 50px">
											<div v-if="row.actual_severity==5" class="progress-bar progress-bar-lg bg-danger-gradient text-start ps-2" :style="`width:${row.actual_severity/5*100}`+'%'"  role="progressbar" aria-valuemin="0" aria-valuemax="100">{{row.actual_severity}}</div>
											<div v-if="row.actual_severity==4" class="progress-bar progress-bar-lg bg-warning-gradient text-start ps-2" :style="`width:${row.actual_severity/5*100}`+'%'"  role="progressbar" aria-valuemin="0" aria-valuemax="100">{{row.actual_severity}}</div>
											<div v-if="row.actual_severity<4" class="progress-bar progress-bar-lg bg-secondary-gradient text-start ps-2" :style="`width:${row.actual_severity/5*100}`+'%'"  role="progressbar" aria-valuemin="0" aria-valuemax="100">{{row.actual_severity}}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress mg-t-2" style="width: 50px">
											<div v-if="row.potential_severity==5" class="progress-bar progress-bar-lg bg-danger-gradient text-start ps-2" :style="`width:${row.potential_severity/5*100}`+'%'"  role="progressbar" aria-valuemin="0" aria-valuemax="100">{{row.potential_severity}}</div>
											<div v-if="row.potential_severity==4" class="progress-bar progress-bar-lg bg-warning-gradient text-start ps-2" :style="`width:${row.potential_severity/5*100}`+'%'"  role="progressbar" aria-valuemin="0" aria-valuemax="100">{{row.potential_severity}}</div>
											<div v-if="row.potential_severity<4" class="progress-bar progress-bar-lg bg-secondary-gradient text-start ps-2" :style="`width:${row.potential_severity/5*100}`+'%'"  role="progressbar" aria-valuemin="0" aria-valuemax="100">{{row.potential_severity}}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress mg-t-2" style="width: 80px">
											<div v-if="row.risk_rating >=20" class="progress-bar progress-bar-lg bg-danger-gradient text-start ps-1" :style="`width:${row.risk_rating/25*100}`+'%'"  role="progressbar" aria-valuemin="0" aria-valuemax="100">{{row.risk_rating}}</div>
											<div v-else class="progress-bar progress-bar-lg bg-secondary-gradient text-start ps-1" :style="`width:${row.risk_rating/25*100}`+'%'"  role="progressbar" aria-valuemin="0" aria-valuemax="100">{{row.risk_rating}}</div>
                                        </div>
                                    </td>

                                    <td>{{row.invest_status}}</td>
                                    <td>{{row.invest_lead}}</td>
                                    <td>{{row.action_status}}</td>
                                    <td>{{dformat(row.action_duedate)}}</td>
                                    <td>{{row.inc_manager}}</td>
                                    <td>{{row.followup_by}}</td>
                                    <td>{{ cutWord(row.comments) }}</td>
                                    <td> {{ cutWord(row.inc_descr) }} </td>
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
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PREVIEW -->
            <div v-if="view =='prev'" >
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-start align-items-center">
                            <!-- <h4 class="card-title mg-b-0 text-muted text-capitalize">Incident <span class="text-danger">#{{prevData[0].inc_id}}</span></h4> -->
                        </div>
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover" @click="backMain(), view ='data'" title="Back to main">
                                <div><i class="fa fa-arrow-left me-1 text-primary tx-12"></i></div>
                                <span class="text-primary tx-13">Back</span>
                            </div>
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-3" @click="editIncident(this.prevData[0].id, this.prevData[0].inc_id), btn =false" title="Edit">
                                <div><i class="fa fa-edit me-1 text-primary tx-12"></i></div>
                                <span class="text-primary tx-13">Edit</span>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-header mt-3">
                        <!-- <h4 class="invoice-title">Incident {{prevData[0].inc_id}}</h4> -->
                        <h4 class="invoice-title">Incident {{prevData[0].inc_no}}</h4>
                        <div class="billed-from">
                            <h6>{{ $t('incTitle') }}</h6>
                            <span class="tx-15">{{prevData[0].inc_title}}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pd-t-20">
                    <div class="row">
                        <div class="col-xl-3">
                            <h6>Incident Information</h6>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('date') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{dformat3(prevData[0].inc_date)}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('company') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].company}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('dept') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].dept}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('incGroup') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].inc_group}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('incType') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].inc_type}}</span></span> 
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <h6>Significant and Risk Rating</h6>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('significant') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].significant}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('alert24') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].flash_alert}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('actual') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].act_seq}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('potential') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].pot_seq}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('rating') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].risk_rating}}</span></span> 
                            </div>
                        </div>

                        <div class="col-xl-3">
                            <h6>Injury Details</h6>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('injType') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].injury_type}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('injGroup') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].injury_group}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('bodyPart') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].injury_part}}</span></span> 
                            </div>
                            
                        </div>
                        <div class="col-xl-3">
                            <h6>Incident Status</h6>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('invStatus') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].invest_status}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('invby') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].invest_lead}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('actStatus') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].action_status}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('actDate') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{dformat3(prevData[0].action_duedate)}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('incManager') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].inc_manager}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">{{ $t('followUp') }}</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].followup_by}}</span></span> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 mg-t-20 bd-xl-r">
                            <h6 class="tx-bold">{{ $t('incDescr') }}</h6>
                            <div class="tx-14">
                                {{prevData[0].inc_descr}}
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <h6 class="tx-bold mg-t-20">{{ $t('correctAct') }}</h6>
                            <div class="tx-14">
                                {{prevData[0].comments}}
                            </div>
                        </div>
                    </div>
                    <h6 class="tx-bold mg-t-40">{{ $t('attached') }}</h6>
                    <div>
                        <div v-for="(lst, inx) in fileData" :key="inx">
                            <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                                <button class="btn btn-sm btn-icon btn-i p-0" title="Preview Image and PDF file" @click="PreviewFile(lst.file_name)">
                                    <i class="mdi mdi-file-find text-primary" style="font-size: 16px"></i>
                                </button> 
                                <button class="btn btn-sm btn-icon btn-i p-0 me-2" title="Download file" @click="downloadFile(lst.file_name)">
                                    <i class="bx bxs-download text-primary" style="font-size: 16px"></i>
                                </button> 
                                {{inx + 1}}. {{fileCut(lst.file_name)}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADD NEW/EDIT INCIDENT -->
            <div v-if="view =='new'">
                <div class="card-body">
                    <h4 v-if="attachMode" class="card-title mg-b-0 text-muted text-capitalize">Add new incident ID {{incForm.inc_id}}</h4>
                    <h4 v-else class="card-title mg-b-0 text-muted text-capitalize">Edit incident ID {{incForm.inc_id}}</h4>
                    <div class="row mt-2">
                            <div class="col-xl-1 col-lg-2">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('incNo') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="incForm.inc_no">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('date') }}  <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="incForm.inc_date">
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-7">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('incTitle') }}  <span class=" text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="incForm.inc_title">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('significant') }}  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.significant" :options="lkYesNo"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('alert24') }}  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.flash_alert" :options="lkYesNo"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('company') }} <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.company" :options="lkComp"/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-5">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('dept') }}</label> 
                                    <Multiselect v-if="incForm.company == 'XPPL'" v-model="incForm.dept" :searchable="true" :searchStart="true" :options="lkDept"/>
                                    <input v-else type="text" class="form-control" v-model="incForm.dept">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('incGroup') }}  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.inc_group" :searchable="true" :searchStart="true" :options="lkIncGroup"/>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-12">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('incType') }}  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.inc_type" :searchable="true" :searchStart="true" :options="lkIncType"/>
                                </div>
                            </div>
                            
                            <div class="col-xl-2 col-lg-4">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('injType') }}  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.inj_type" :searchable="true" :searchStart="true" :options="lkInjType"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('injGroup') }}  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.inj_group" :searchable="true" :searchStart="true" :options="lkInjGroup"/>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('bodyPart') }}</label>
                                    <input type="text" class="form-control" v-model="incForm.inj_part">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('actual') }} <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.act_seq" :options="lkActSeq"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('potential') }} <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.pot_seq" :options="lkPotSeq"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('invStatus') }} <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.invest_status" :options="lkInvest"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('invBy') }}</label>
                                    <input type="text" class="form-control" v-model="incForm.invest_lead">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('actStatus') }} <span class="text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.action_status" :options="lkAction"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('actDate') }}</label>
                                    <input type="date" class="form-control" v-model="incForm.action_duedate">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('incManager') }}</label>
                                    <input type="text" class="form-control" v-model="incForm.inc_manager">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('followUp') }}</label>
                                    <input type="text" class="form-control" v-model="incForm.followup_by">
                                </div>
                            </div>   
                            <div class="col-xl-6 col-12">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('incDescr') }} <span class=" text-danger">*</span></label>
                                    <textarea class="form-control" style="height: 165px" v-model="incForm.inc_descr"></textarea>
                                </div>
                            </div> 
                            <div class="col-xl-6 col-12">
                                <div class="form-group">
                                    <label class="mb-0">{{ $t('correctAct') }}</label>
                                    <textarea class="form-control" style="height: 165px" v-model="incForm.comments"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- ATTACHEMENTS -->
                        <div v-if="attachMode">
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover wd-100" @click="newFiles1()" title="Add new files">
                                <div><i class="mdi mdi-paperclip me-1 text-primary tx-16"></i></div>
                                    <span class="text-primary tx-bold tx-14">{{ $t('attached') }}</span>
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
                        </div>
                        <div v-else>
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover wd-100" @click="newFiles2()" title="Add new files">
                                <div><i class="mdi mdi-paperclip me-1 text-primary tx-16"></i></div>
                                    <span class="text-primary tx-bold tx-14">{{ $t('attached') }}</span>
                                <input class="d-none" ref="fileInput2" type="file" multiple @change="addFiles()">
                            </div>
                            <div v-for="(lst, inx) in fileData" :key="inx">
                                <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                                    <div class="d-flex justify-content-start ms-1">
                                        <button class="btn btn-sm btn-icon btn-i p-0" title="Preview Image and PDF file" @click="PreviewFile(lst.file_name)">
                                            <i class="mdi mdi-file-find text-primary" style="font-size: 16px"></i>
                                        </button> 
                                        <button class="btn btn-sm btn-icon btn-i p-0" title="Download file" @click="downloadFile(lst.file_name)">
                                            <i class="bx bxs-download text-primary" style="font-size: 16px"></i>
                                        </button> 
                                        <button class="btn btn-sm btn-icon btn-i p-0 me-2" title="Delete file" @click="delFile(lst.file_name)">
                                            <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                        </button> 
                                    </div>
                                    {{inx + 1}}. {{fileCut(lst.file_name)}} 
                                </div>
                            </div>
                        </div>

                    <div class="d-flex justify-content-end mt-2 pb-2">
                        <button v-if="btn" type="button" class="btn btn-primary" :class="incAddDis" @click="addIncident()">
                            <i class="fe fe-plus"></i><span class="mx-1">{{ $t('add') }}</span>
                        </button>
                        <button v-else type="button" class="btn btn-primary" :class="incAddDis" @click="updIncident()">
                            <i class="fe fe-save"></i><span class="mx-1">{{ $t('save') }}</span>
                        </button>
                        <button type="button" class="btn btn-secondary ms-1" @click="view ='data'"><i class="fe fe-x"></i> 
                            <span class="mx-1">{{ $t('close') }}</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL FILTER -->
        <div class="modal fade effect-scale" id="Filter" back data-bs-keyboard="false" tabindex="-1" aria-labelledby="positionLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">Filter by {{filterName}}</h6>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body pt-2">  
                        <div class="table-responsive element border" style="max-height: 410px; min-height: 200px">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px; z-index: 1">
                                    <tr>
                                        <th class="border-0" style="padding: 5px 10px">
                                            <label class="form-checkbox m-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <input type="checkbox" v-model="filterAll" @click="SelectAllItem()">
                                                    <i class="form-icon"></i>
                                                    <!-- <span class="ms-2 cur-pointer mg-t-2" title="Select/Unselect all">{{filterName}} List</span> -->
                                                    <span v-if="filterAll" class="ms-2 cur-pointer mg-t-2" title="Select/Unselect all">Unselect All</span>
                                                    <span v-else class="ms-2 cur-pointer mg-t-2" title="Select/Unselect all">Select All</span>
                                                
                                                </div>
                                            </label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-hover" v-for="(item, inx) in filterList" :key="inx">
                                        <td class="border-0">
                                            <label class="form-checkbox m-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <input type="checkbox" :value="item.colname" v-model="filterSelected">
                                                    <i class="form-icon"></i>
                                                    
                                                    <span v-if="filterBy == 'inc_date'" class="ms-2 cur-pointer">{{dformat(item.colname)}}</span>
                                                    <span v-else class="ms-2 cur-pointer">{{item.colname}}</span>

                                                </div>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button v-if="filterMode == filterBy" type="button" class="btn btn-secondary me-1" @click="getIncident()"><span class="px-3">Clear Filter</span></button> 
                            <button type="button" class="btn btn-primary" :class="FilterDis" @click="FilterResult()"><span class="px-3">Filter</span></button> 
                        </div>              
                    </div>
                </div>
            </div>                                              
        </div>
    </div>

        
</template>

<script>
import exportXlsFile from "export-from-json";

export default {
    name: 'XpplAppIncidentRecord',

    data() {
        return {
            lkYear: [],
            lkIncGroup: [],
            lkIncType: [],
            lkInjType: [],
            lkInjGroup: [],
            lkActSeq: [],
            lkPotSeq: [],
            lkInvest: [],
            lkAction: [],
            lkYesNo: [],
            lkComp: [],
            lkDept: [],

            incData: [],
            prevData: [],
            tmpFiles: [],
            fileData: [],
            incForm: {id: '', inc_id: '', inc_no: '', inc_date: '', inc_type: null, inc_title: '', inc_descr: '', flash_alert: null, significant: null, inj_type: null, inj_group: null, inj_part: '', act_seq: null, pot_seq: null, invest_status: null, invest_lead: '', action_status: null,  action_duedate: '', inc_group: null, inc_manager: '', company: null, dept: '', followup_by: '', comments: ''},

            year: '',
            search: '',
            btnClear: false,

            loading: false,
            exIncData: [],

            filterSelected: [],
            filterAll: false,
            filterList: [],
            filterBy: '',
            filterMode: '',

            view: 'data',
            attachMode: ''
            
        };
    },

    mounted() {
        
    },

    computed: {
        incAddDis(){
            let f = this.incForm;
            if (f.inc_no == '' || f.inc_date == '' || f.inc_title == '' || f.company == null || f.inc_group == null || f.inc_type == null || f.significant == null || f.flash_alert == null || f.inj_type == null || f.inj_group == null || f.act_seq == null || f.pot_seq == null || f.invest_status == null || f.action_status == null || f.inc_descr == '') {
                return 'disabled';
            } else {
                return '';
            }
        },

        FilterDis(){
            if (this.filterSelected == ''){
                return 'disabled';
            } else {
                return '';
            }
        }
    },

    methods: {
        async onLoad(){
            this.loading = true;

            // const profile = await axios.get('api/profile')
			// this.dept = profile.data.department;
            this.year = new Date().getFullYear();

            // const info = await axios.get(`/api/annual/info?dept=${this.dept}&year=${this.year}&search=${this.search}`)
            // this.infoData = info.data;

            const dept = await axios.get('/api/lookup/dept')
            this.lkDept = dept.data;

            this.getIncident();

            this.loading = false; 

            const year = await axios.get('/api/lookup/year')
            this.lkYear = year.data;

            const incGroup = await axios.get('/api/safety/incgroup')
            this.lkIncGroup = incGroup.data;

            const incType = await axios.get('/api/safety/inctype')
            this.lkIncType = incType.data;

            const injType = await axios.get('/api/safety/injtype')
            this.lkInjType = injType.data;

            const injGroup = await axios.get('/api/safety/injgroup')
            this.lkInjGroup = injGroup.data;

            const actSeq = await axios.get('/api/safety/actseq')
            this.lkActSeq = actSeq.data;

            const potSeq = await axios.get('/api/safety/potseq')
            this.lkPotSeq = potSeq.data;

            const invest = await axios.get('/api/safety/invest')
            this.lkInvest = invest.data;

            const action = await axios.get('/api/safety/action')
            this.lkAction = action.data;

            const yesNo = await axios.get('/api/safety/yesno')
            this.lkYesNo = yesNo.data;

            const comp = await axios.get('/api/safety/company')
            this.lkComp = comp.data;




        },

        async FilterList(col){
            this.filterSelected = [];
            this.filterAll = false;
            this.filterBy = col;

            const res = await axios.get(`/api/safety/filterlist?filter=${col}&year=${this.year}`)
            this.filterList = res.data;
            $('#Filter').modal('show');
        },

        SelectAllItem(){
            this.filterSelected = [];
            if (!this.filterAll) {
                for (let i in this.filterList) {
                    this.filterSelected.push(this.filterList[i].colname);
                }
            }    
        },

        async FilterResult(){
            this.search = '';
            this.btnClear = false;
            this.filterMode = this.filterBy;
            
            $('#Filter').modal('hide');
            const res = await axios.post('/api/safety/filterresult', {
                    values: "'" + this.filterSelected.join("','") + "'",
                    year: this.year,
                    filter: this.filterBy    
                });
            this.incData = res.data;    
        },

        async selChanged(){
            this.loading = true; 
            const res = await axios.get(`/api/safety/increcord?year=${this.year}&search=${this.search}`)
            this.incData = res.data;
            this.filterMode = '';
            this.loading = false; 
        },

        getIncident(){
            this.$axios.get(`/api/safety/increcord?year=${this.year}&search=${this.search}`)
            .then(res => {
                this.incData = res.data;
                this.filterMode = '';
                $('#Filter').modal('hide');
            })
        },

        searchChanged(){
            if(this.search.length >0){
                this.btnClear = true;
                this.getIncident();
            } else {
                this.btnClear = false;
                this.getIncident();
            }
        },

        searchClear(){
            this.search = '';
            this.btnClear = false;
            this.getIncident();
        },

        async preview(id, incid){
            this.view = 'prev'

            const incdata = await axios.get(`/api/safety/preview?id=${id}`)
            this.prevData = incdata.data;

            const files = await axios.post(`/api/safety/editfiles/${incid}`)
            this.fileData = files.data;

        },

        backMain(){
            this.main = true;
        },

        newIncident(){
            this.view = 'new';
            this.attachMode = true;
            this.tmpFiles = [];
            this.incFormClear();

            this.$axios.get('/api/safety/newincid')
            .then(res => this.incForm.inc_id = res.data)
        },

        async addIncident(){
            const inc = await axios.post('/api/safety/addincident', this.incForm)
            if(inc.data.success){
                this.getIncident();

                // add attached file
                let fd = new FormData();
                for (let i = 0; i < this.tmpFiles.length; i++){
                    
                    let file = this.tmpFiles[i];
                        fd.append('files[' + i + ']', file);
                        fd.append('incid', this.incForm.inc_id);
                }
                const doc = await axios.post('/api/safety/addfiles', fd, {headers:{"Content-Type": "multipart/form-date"}})
                this.view = 'data';
            } else {
                alert(inc.data.message)
            };
        },

        async editIncident(id, incid){
            this.incFormClear();
            this.view = 'new';
            this.attachMode = false;

            const inc = await axios.post(`/api/safety/editincident/${id}`)
            let f = this.incForm;
                f.id = inc.data.id;
                f.inc_no = inc.data.inc_no;
                f.inc_id = inc.data.inc_id;
                f.inc_date = moment(inc.data.inc_date).format('YYYY-MM-DD');
                f.inc_title = inc.data.inc_title;
                f.inc_group = inc.data.inc_group;
                f.inc_type = inc.data.inc_type;
                f.inc_descr = inc.data.inc_descr;
                f.flash_alert = inc.data.flash_alert;
                f.significant = inc.data.significant;
                f.inj_type = inc.data.injury_type;
                f.inj_group = inc.data.injury_group;
                f.inj_part = inc.data.injury_part;
                f.act_seq = inc.data.actual_severity;
                f.pot_seq = inc.data.potential_severity;
                f.invest_status = inc.data.invest_status;
                f.invest_lead = inc.data.invest_lead;
                f.action_status = inc.data.action_status;
                f.action_duedate = inc.data.action_duedate;
                f.inc_manager = inc.data.inc_manager;
                f.company = inc.data.company;
                f.dept = inc.data.dept;
                f.followup_by = inc.data.followup_by;
                f.comments = inc.data.comments;

            const files = await axios.post(`/api/safety/editfiles/${incid}`)
            this.fileData = files.data;

        },

        updIncident(){
            this.$axios.post('/api/safety/updincident', this.incForm)
            .then((response)=>{
                this.getIncident();
                this.view = 'data';
            }).catch((error)=>{
                console.log(error);
            })
        },

        delIncident(id, incid){
            // if (this.loginPermiss.cont_del == 0){
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
                        this.$axios.post(`/api/safety/delincident?id=${id}&incid=${incid}`)
                        .then((response)=>{

                            this.getIncident();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            // }
        },

        incFormClear(){
            let f = this.incForm;
                f.inc_no = '';
                f.inc_date = '';
                f.inc_type = null;
                f.inc_title = '';
                f.inc_descr = '';
                f.flash_alert = null;
                f.significant = null;
                f.inj_type = null;
                f.inj_group = null;
                f.inj_part = '';
                f.act_seq = null;
                f.pot_seq = null;
                f.invest_status = null;
                f.invest_lead = '';
                f.action_status = null;
                f.action_duedate = '';
                f.inc_group = null;
                f.inc_manager = '';
                f.company = null;
                f.dept = null;
                f.followup_by = '';
                f.comments = '';
        },

        document(){
            this.data = false;
        },

        back(){
            this.data = true;
        },

        getFiles(incid){
            this.$axios.post(`/api/safety/editfiles/${incid}`)
            .then(res => this.fileData = res.data);
        },

        newFiles1(){
            this.$refs.fileInput1.click();
        },
        newFiles2(){
            this.$refs.fileInput2.click();
        },

        addTmp(){
            for (let i = 0; i < this.$refs.fileInput1.files.length; i++){
                this.tmpFiles.push(this.$refs.fileInput1.files[i]);
            }
        },

        delTmp(inx){
            this.tmpFiles.splice(inx,1);
            console.log(index);
        },

        addFiles(){
            let fd = new FormData();
            for (let i = 0; i < this.$refs.fileInput2.files.length; i++){
                
                let file = this.$refs.fileInput2.files[i];
                    fd.append('files[' + i + ']', file);
                    fd.append('incid', this.incForm.inc_id);
            }
            this.$axios.post('/api/safety/addfiles', fd, {headers:{"Content-Type": "multipart/form-date"}})
            .then(res => {   
                this.getFiles(this.incForm.inc_id);
            })
        },

        downloadFile(file){
            const url = `/api/safety/downloadfile/${file}`;
            window.location.href = url;
        },

        PreviewFile(filename){
            window.open(window.location.origin+'/assets/safety/'+ filename, "_blank")
        },

        fileCut(text){
            if (text){
                let txt1 = text.split('-')[0]
                return text.split(txt1 +'-')[1];
            };
        },

        delFile(file){
            // if (this.loginPermiss.file_del == 0){
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

                $('#incident').modal('hide');
                this.$swal.fire({
                    text: "Do you want to delete this file?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fe fe-trash-2"></i> <span class="font-weight-light">Delete</span>',
                    cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
                    confirmButtonColor: '#d33',
                    allowEnterKey: false,
                    allowOutsideClick: false,
                }).then((result)=>{
                    if(result.isConfirmed){
                        this.$axios.post(`api/safety/delfile/${file}`)
                        .then(res => {
                            this.getFiles(this.incForm.inc_id);
                        })
                    } 
                    $('#incident').modal('show');
                });
            // }
        },
        
        async exportIncident(){

            const exInc = await axios.get('/api/safety/exincident')
            this.exIncData = exInc.data;
 
            const data = this.exIncData;
            const fileName = 'AllIncidents';
            const exportType = exportXlsFile.types.xls
            exportXlsFile({ data, fileName, exportType })
        },


        dformat(value){
            if(value) {
                return moment(value).format('DD-MM-YYYY');
            }
        },
        dformat2(value){
            if(value) {
                return moment(value).format('YYYY-MM-DD');
            }
        },
        dformat3(value){
            if(value) {
                return moment(value).format('MMMM DD, YYYY');
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
	}
};
</script>

<style>
    .safety{
        font-size: 13px;
        color: #4d5875;
        padding: 3px 0px;
        margin: 0px;
        border-top: 1px dotted #d0d7e8;


    }
    .bd-top-dot{
        border-top: 1px dotted #d0d7e8;
        /* border-top: none; */
    }
    .bd-bot-dot{
        border-bottom: 1px dotted #d0d7e8;
        /* border-bottom: none; */
    }
    .tx-name{
        font-size: 13px;
        color: #4d5875;
        padding: 3px 0px;
        width: 120px;
    }
    .tx-data{
        font-size: 13px;
        color: #031b4e;
        padding: 5px 0px;
    }
    .f-color{
        color: #031b4e;
    }
</style>