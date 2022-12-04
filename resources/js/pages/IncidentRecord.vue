<template>
    <div v-if="loading">
        <loading/>
    </div> 
    <div v-else>
        <div class="card">
            <div v-if="main">
                <div class="card-header pd-r-15 pd-t-10 pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-start align-items-center">
                            <h4 class="card-title mg-b-0 text-muted text-capitalize">Incident Records In {{year}}</h4>
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
                            <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search..." v-model="search" @input="searchChanged()" title="Search by name">
                                <i class="fe fe-search search-i text-muted"></i>
                            <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                        </div>
                        <div class="d-flex justify-content-end mt-xl-0 mt-lg-0 mt-md-1 mt-1">
                            <div class="wd-md-150 wd-100p me-1">
                                <Multiselect v-model="year" :searchStart="true" :options="lkYear" @select="selChanged()"/>
                            </div>
                            <div class="wd-35">
                                <button type="button" class="btn btn-outline-primary p-0 border wd-35" title="Add new record" @click="newIncident()">
                                    <i class="fa fa-plus tx-14"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive border-start border-end border-bottom" style="max-height: 73vh">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>
                                    <th class="position-sticky border-start-0" style="left: -0.1px">ID</th>
                                    <th>Date</th>
                                    <th>Incident Title</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>Incident Group</th>
                                    <th>Incident Type</th>
                                    <th>Significant</th>
                                    <th>Flash Alert 24h</th>
                                    <th>Injury Type</th>
                                    <th>Injury Group</th>
                                    <th>Injury Part</th>
                                    <th>Actual Severity</th>
                                    <th>Potential Severity</th>
                                    <th>Risk Rating</th>
                                    <th>invested status</th>
                                    <th>invested Lead By</th>
                                    <th>Action Status</th>
                                    <th>Action Due Date</th>
                                    <th>Incident Manager</th>
                                    <th>Follow up By</th>
                                    <th>Comments</th>
                                    <th>Description</th>
                                    <th class="border-end-0 position-sticky wd-60p" style="right: -0.1px">Tools</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr v-for="row in incData" :key="row.id" class="tr-hover">
                                    <td class="border-start-0 position-sticky cur-pointer" style="left: -0.1px; background-color: #FFFFFF" title="Double click to see details" @click="preview(row.id)" >{{row.inc_id}}</td>
                                    <td>{{dformat(row.inc_date)}}</td>
                                    <td class="laofont">{{cutWord(row.inc_title)}}</td>
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
                                    <td>{{row.risk_rating}}</td>
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
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="editIncident(row.id, row.inc_id)">
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

            <!-- Preview -->
            <div v-else >

                <div class="card-header">
                    
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-start align-items-center">
                            <!-- <h4 class="card-title mg-b-0 text-muted text-capitalize">Incident <span class="text-danger">#{{prevData[0].inc_id}}</span></h4> -->
                        </div>
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover" @click="backMain()" title="Back to main">
                                <div><i class="fa fa-arrow-left me-1 text-primary tx-12"></i></div>
                                <span class="text-primary tx-13">Back</span>
                            </div>
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-3" @click="editIncident(this.prevData[0].id, this.prevData[0].inc_id)" title="Edit">
                                <div><i class="fa fa-edit me-1 text-primary tx-12"></i></div>
                                <span class="text-primary tx-13">Edit</span>
                            </div>
                        </div>
                    </div>

                <div class="invoice-header mt-3">
                    <h4 class="invoice-title">Incident {{prevData[0].inc_id}}</h4>
                    <div class="billed-from">
                        <h6>Incident Title</h6>
                        <span class="laofont tx-14">{{prevData[0].inc_title}}</span>
                    </div>
                </div>

                </div>

                <div class="card-body pd-t-20">

                    <div class="row">
                        <div class="col-xl-3">
                            <h6>Incident Information</h6>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Incident Date</span>
                                <span class="tx-data">:<span class="ps-2">{{dformat3(prevData[0].inc_date)}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Company</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].company}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Department</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].dept}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Incident Group</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].inc_group}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Incident Type</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].inc_type}}</span></span> 
                            </div>
                        </div>
                        
                        <div class="col-xl-3">
                            <h6>Significant and Risk Rating</h6>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Significant</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].significant}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Flash Alert 24h</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].flash_alert}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Actual Severity</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].act_seq}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Potential Severity</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].pot_seq}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Risk Rating</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].risk_rating}}</span></span> 
                            </div>
                        </div>

                        <div class="col-xl-3">
                            <h6>Injury Details</h6>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Injury Type</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].inj_type}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Body Group</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].injury_group}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Body Part</span>
                                <span class="tx-data">:<span class="ps-2 laofont">{{prevData[0].injury_part}}</span></span> 
                            </div>
                            
                        </div>
                        <div class="col-xl-3">
                            <h6>Incident Status</h6>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Investigate Status</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].invest_status}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Investigate Lead By</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].invest_lead}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Action Status</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].action_status}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Action Due Date</span>
                                <span class="tx-data">:<span class="ps-2">{{dformat3(prevData[0].action_duedate)}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Incident Manager</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].inc_manager}}</span></span> 
                            </div>
                            <div class="d-flex justify-content-start">
                                <span class="tx-name">Follow up By</span>
                                <span class="tx-data">:<span class="ps-2">{{prevData[0].followup_by}}</span></span> 
                            </div>
                        </div>
     
                    </div>

                    <h6 class="mg-t-40">Corrective Action</h6>
                    <div class="laofont tx-13 f-color">
                        {{prevData[0].comments}}
                    </div>
                    <h6 class="mg-t-20">Incident Description</h6>
                    <div class="laofont tx-13 f-color">
                        {{prevData[0].inc_descr}}
                    </div>

                    <h6 class="mg-t-40">Attachments</h6>
                    <div>
                        <div v-for="(lst, inx) in fileData" :key="inx">
                            <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                                <button class="btn btn-sm btn-icon btn-i p-0 me-1" title="Download file" @click="downloadFile(lst.file_name)">
                                    <i class="bx bxs-download text-primary" style="font-size: 16px"></i>
                                </button> 
                                {{lst.file_name}} 
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade effect-scale bd-0" id="incident" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="incidentLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header pb-1 bd-b-0">
                    <h6 class="text-muted">Incident <span class="text-danger">{{incForm.inc_id}}</span></h6>
                    <div v-if="data" class="d-flex justify-content-start align-items-center cur-pointer add-hover" @click="document()" title="Attache files">
                        <div><i class="mdi mdi-paperclip text-primary tx-16"></i></div>
                        <span class="text-primary tx-14">Attachments</span>
                    </div>
                    <div v-else class="d-flex justify-content-start align-items-center cur-pointer add-hover" @click="back()" title="Back">
                        <div><i class="fa fa-arrow-left me-1 text-primary tx-12"></i></div>
                        <span class="text-primary tx-14">Back</span>
                    </div>
                </div>
                <div class="modal-body pt-1">                                   
                    <div v-if="data">
                        <div class="row">
                            <div class="col-xl-2 col-lg-3">
                                <div class="form-group">
                                    <label class="mb-0">Incident Date  <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="incForm.inc_date">
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-9">
                                <div class="form-group">
                                    <label class="mb-0">Incident Title  <span class=" text-danger">*</span></label>
                                    <input type="text" class="form-control laofont" v-model="incForm.inc_title">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3">
                                <div class="form-group">
                                    <label class="mb-0">Company <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.company" :options="lkComp"/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-5">
                                <div class="form-group">
                                    <label class="mb-0">Department</label> 
                                    <Multiselect v-if="incForm.company == 'XPPL'" v-model="incForm.dept" :searchable="true" :searchStart="true" :options="lkDept"/>
                                    <input v-else type="text" class="form-control" v-model="incForm.dept">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4">
                                <div class="form-group">
                                    <label class="mb-0">Incident Group  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.inc_group" :searchable="true" :searchStart="true" :options="lkIncGroup"/>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-12">
                                <div class="form-group">
                                    <label class="mb-0">Incident Type  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.inc_type" :searchable="true" :searchStart="true" :options="lkIncType"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">Significant  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.significant" :options="lkYesNo"/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">Flash Alert 24h  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.flash_alert" :options="lkYesNo"/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4">
                                <div class="form-group">
                                    <label class="mb-0">Injury Type  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.inj_type" :searchable="true" :searchStart="true" :options="lkInjType"/>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4">
                                <div class="form-group">
                                    <label class="mb-0">Injury Body Group  <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.inj_group" :searchable="true" :searchStart="true" :options="lkInjGroup"/>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-4">
                                <div class="form-group">
                                    <label class="mb-0">Injury Body Part</label>
                                    <input type="text" class="form-control laofont" v-model="incForm.inj_part">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">Actual Severity <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.act_seq" :options="lkActSeq"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">Potential Severity <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.pot_seq" :options="lkPotSeq"/>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">Investigate Status <span class=" text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.invest_status" :options="lkInvest"/>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">Investigate Lead By</label>
                                    <input type="text" class="form-control" v-model="incForm.invest_lead">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">Action Status <span class="text-danger">*</span></label> 
                                    <Multiselect v-model="incForm.action_status" :options="lkAction"/>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-6">
                                <div class="form-group">
                                    <label class="mb-0">Action Due Date</label>
                                    <input type="date" class="form-control" v-model="incForm.action_duedate">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6">
                                <div class="form-group">
                                    <label class="mb-0">Incident Manager</label>
                                    <input type="text" class="form-control" v-model="incForm.inc_manager">
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-6">
                                <div class="form-group">
                                    <label class="mb-0">Follow Up By</label>
                                    <input type="text" class="form-control" v-model="incForm.followup_by">
                                </div>
                            </div>   
                            <div class="col-xl-4 col-lg-6 col-12">
                                <div class="form-group">
                                    <label class="mb-0">Corrective Action</label>
                                    <textarea class="form-control laofont" style="height: 150px" v-model="incForm.comments"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-6 col-12">
                                <div class="form-group">
                                    <label class="mb-0">Incident Description <span class=" text-danger">*</span></label>
                                    <textarea class="form-control laofont" style="height: 150px" v-model="incForm.inc_descr"></textarea>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="btnShow">
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover wd-100" @click="newFiles()" title="Add new files">
                                <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                    <span class="text-primary tx-13">Add</span>
                                <input class="d-none" ref="fileInput" type="file" multiple  @change="addTmp()">
                            </div>

                            <div class="table-responsive border-start border-end border-bottom mb-2" style="max-height: 600px">
                                <table class="table main-table-reference text-nowrap mg-b-0">
                                    <thead class="position-sticky" style="top: 0px">
                                        <tr>
                                            <th class="border-start-0 text-center wd-15">No</th>
                                            <th>File Name</th>
                                            <th class="border-end-0 wd-60p">Tools</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <tr class="tr-hover" v-for="(lst, inx) in tmpFiles" :key="inx">
                                            <td class="border-start-0 text-center">{{inx + 1}}</td>
                                            <td>{{lst.name}}</td>
                                            <td class="p-0 align-middle border-end-0">
                                                <div class="d-flex justify-content-start ms-1">
                                                    <button class="btn btn-sm btn-icon btn-i p-0" title="Remove file" @click="delTmp(inx)">
                                                        <i class="fe fe-x text-danger" style="font-size: 16px"></i>
                                                    </button> 
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-else >
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover wd-100" @click="newFiles()" title="Add new files">
                                <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                    <span class="text-primary tx-13">Add</span>
                                <input class="d-none" ref="fileInput" type="file" multiple  @change="addFiles()">
                            </div>

                            <div class="table-responsive mb-2" style="max-height: 600px">     
                                <table class="table main-table-reference text-nowrap mg-b-0">
                                    <thead class="position-sticky" style="top: 0px">
                                        <tr>
                                            <th class="text-center wd-15">No</th>
                                            <th>File Name</th>
                                            <th class="wd-60p">Tools</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <tr class="tr-hover" v-for="(lst, inx) in fileData" :key="lst.id">
                                            <td class="text-center">{{inx + 1}}</td>
                                            <td>{{lst.file_name}}</td>
                                            <td class="p-0 align-middle">
                                                <div class="d-flex justify-content-start ms-1">
                                                    <button class="btn btn-sm btn-icon btn-i p-0" title="Download file" @click="downloadFile(lst.file_name)">
                                                        <i class="bx bxs-download text-primary" style="font-size: 16px"></i>
                                                    </button> 
                                                    <button class="btn btn-sm btn-icon btn-i p-0" title="Delete file" @click="delFile(lst.file_name)">
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
            incForm: {id: '', inc_id: '', inc_date: '', inc_type: null, inc_title: '', inc_descr: '', flash_alert: null, significant: null, inj_type: null, inj_group: null, inj_part: '', act_seq: null, pot_seq: null, invest_status: null, invest_lead: '', action_status: null,  action_duedate: '', inc_group: null, inc_manager: '', company: null, dept: '', followup_by: '', comments: ''},

            year: '',
            search: '',
            btnClear: false,
            main: true,
            data: '',
            btnShow: '',
            addShow: '',
            loading: false,
            exIncData: [],
            
        };
    },

    mounted() {
        
    },

    computed: {
        incAddDis(){
            let f = this.incForm;
            if (f.inc_date == '' || f.inc_title == '' || f.company == null || f.inc_group == null || f.inc_type == null || f.significant == null || f.flash_alert == null || f.inj_type == null || f.inj_group == null || f.act_seq == null || f.pot_seq == null || f.invest_status == null || f.action_status == null || f.inc_descr == '') {
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

        async selChanged(){
            this.loading = true; 
            const res = await axios.get(`/api/safety/increcord?year=${this.year}&search=${this.search}`)
            this.incData = res.data;
            this.loading = false; 
        },

        getIncident(){
            this.$axios.get(`/api/safety/increcord?year=${this.year}&search=${this.search}`)
            .then(res => this.incData = res.data)
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
            this.main = false;

            const incdata = await axios.get(`/api/safety/preview?id=${id}`)
            this.prevData = incdata.data;

            const files = await axios.post(`/api/safety/editfiles/${incid}`)
            this.fileData = files.data;

        },

        backMain(){
            this.main = true;
        },

        newIncident(){

            this.btnShow = true;
            this.data = true;
            this.tmpFiles = [];
            this.incFormClear();
            $('#incident').modal('show');

            this.$axios.get('/api/safety/newincid')
            .then(res => this.incForm.inc_id = res.data)
        },

        async addIncident(){
            const inc = await axios.post('/api/safety/addincident', this.incForm)
            if(inc.data.success){

                $('#incident').modal('hide');
                this.getIncident();

                // add attached file
                let fd = new FormData();
                for (let i = 0; i < this.tmpFiles.length; i++){
                    
                    let file = this.tmpFiles[i];
                        fd.append('files[' + i + ']', file);
                        fd.append('incid', this.incForm.inc_id);
                }
                const doc = await axios.post('/api/safety/addfiles', fd, {headers:{"Content-Type": "multipart/form-date"}})

            } else {
                alert(inc.data.message)
            };
        },

        async editIncident(id, incid){
            this.incFormClear();
            this.btnShow = false;
            this.data = true;
            this.main = true;
            $('#incident').modal('show');

            const inc = await axios.post(`/api/safety/editincident/${id}`)
            let f = this.incForm;
                f.id = inc.data.id;
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

                $('#incident').modal('hide');
                this.getIncident();

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

        newFiles(){
            this.$refs.fileInput.click();
        },

        addTmp(){
            for (let i = 0; i < this.$refs.fileInput.files.length; i++){
                this.tmpFiles.push(this.$refs.fileInput.files[i]);
            }
        },

        delTmp(inx){
            this.tmpFiles.splice(inx,1);
            console.log(index);
        },

        addFiles(){
            let fd = new FormData();
            for (let i = 0; i < this.$refs.fileInput.files.length; i++){
                
                let file = this.$refs.fileInput.files[i];
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
                            this.data = false;
                            this.getFiles(this.incForm.inc_id);
                            // $('#incident').modal('show');
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