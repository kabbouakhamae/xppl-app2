<template>
    <div v-if="loading">
        <loading/>
    </div> 
    <div v-else>
        <div class="card">
            <div class="card-header pd-r-15 pd-t-10 pb-0">
                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-start align-items-center">
                        <h4 class="card-title mg-b-0 text-muted text-capitalize">Roster From {{date1(datefr)}} To {{date1(dateto)}}</h4>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                            <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
                        </button> 
                        <div class="dropdown-menu tx-13">
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="showCols()">
                                <i class="mdi mdi-eye me-2 tx-16"></i><span>Show Column</span>
                            </div>
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="breakInfo()">
                                <i class="mdi mdi-airplane-takeoff tx-15 me-2"></i><span>Roster Break Info</span>
                            </div>
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="shiftWork()">
                                <i class="mdi mdi-brightness-6 tx-15 me-2"></i><span>Shift Work Update</span>
                            </div>
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="addDelete()">
                                <i class="mdi mdi-contrast-box me-2 tx-16"></i><span>Add/Delete Roster</span>
                                <!-- <i class="mdi mdi-contrast me-2 tx-16"></i><span>Add/Delete</span> -->
                            </div>
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="getRosCode()">
                                <i class="mdi mdi-book-open-page-variant tx-15 me-2"></i><span>Roster Lookup Code</span>
                            </div>
                            <!-- <div class="dropdown-item cur-pointer dropdown-hover">
                                <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Export To Excel</span>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pd-t-0 pd-b-0">
                <div class="d-xl-flex justify-content-between mt-1 mb-1">
                    <div class="pos-relative wd-lg-300 wd-100p">
                        <input class="form-control" style="padding-left: 25px" type="text" placeholder="Search for name" v-model="search" @input="searchChange()" @keyup.enter="rosSearchName()" title="Search by name">
                            <i class="fe fe-search search-i text-muted"></i>
                        <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                    </div>
                    <div class="d-md-flex justify-content-end mt-xl-0 mt-lg-1 mt-md-1 mt-1">
                        <div class="wd-lg-200 wd-md-200 wd-100p me-1 my-md-0 my-1" v-if="permiss.ros_all == 1">
                            <Multiselect v-model="dept" :searchable="false" :searchStart="true" :options="lkDept" @select="refreshRoster()"/>
                        </div>
                        <div class="d-flex justify-content-end">
                            <div class="wd-md-150 wd-100p">
                                <input type="date" class="form-control" v-model="datefr">
                            </div>
                            <div class="wd-md-150 wd-100p ms-1">
                                <input type="date" class="form-control" v-model="dateto">
                            </div>
                            <div class="wd-35 ms-1">
                                <button type="button" class="btn btn-outline-primary p-0 border wd-35" title="Reload all" @click="getRoster()">
                                    <i class="mdi mdi-reload tx-18"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive border" style="max-height: 77vh">
                    <table class="table main-table-reference text-nowrap mg-b-0">
                        <thead class="position-sticky" style="top: 0px; z-index: 1">
                            <tr>
                                <th v-if="showCol.no" class="border-start-0 px-1">No</th>
                                <th class="px-1 position-sticky border-start-0" style="left: -1px">
                                    <div class="d-flex justify-content-between">Name and Surname
                                        <i v-if="nameFilter == 'true'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="rosFilterNames()"></i>
                                        <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="rosFilterNames()"></i>
                                    </div>
                                </th>
                                <th v-if="showCol.position" class="px-1 border-start-0">
                                    <div class="d-flex justify-content-between">Position
                                        <i v-if="filterMode == 'Position'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterByPosition()"></i>
                                        <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterByPosition()"></i>
                                    </div>
                                </th>
                                <th v-if="showCol.crew" class="px-1 border-start-0">
                                    <div class="d-flex justify-content-between">Crew
                                        <i v-if="filterMode == 'Crew'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterByGroup()"></i>
                                        <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterByGroup()"></i>
                                    </div>
                                </th>
                                <th v-if="showCol.section" class="px-1">Section</th>
                                <th v-if="showCol.type" class="px-1">Type</th>
                                <th v-for="(col, colInx) in colData" :key="colInx" class="text-center" style="padding: 7px 5px" :title="col.mont" :style="headDate(col.colid) == cdate ? 'background-color: yellow; color: blue; font-weight: bold' : ''">
                                    {{ col.colname }} 
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, rowInx) in rosData" :key="rowInx" @click="selectRow(row.userid, rowInx, row.fullname, row.rtype)" :style="rowInx === rowSel && row.rtype == 'P' ? 'color: red; font-weight: 500' : rowInx === rowSel && row.rtype == 'A' ? 'color: blue; font-weight: 500' : ''">
                                <td v-if="showCol.no" class="px-1 text-center border-start-0">{{rowInx + 1}}</td>
                                <td class="px-1 position-sticky cur-pointer border-start-0 bg-white" style="left: -1px;" title="Double click to see Roster details" @dblclick="rosDetail(row.userid)">
                                    <div v-if="!row.al">{{row.fullname}}</div>
                                    <div v-else class="d-flex justify-content-between">
                                        <span>{{row.fullname}}</span>
                                        <span class="ms-1" title="Annual Leave">{{formatNumber(row.al)}}</span>
                                    </div>
                                </td>
                                <td v-if="showCol.position" class="px-1" :title="row.position">{{cutWord(row.position)}}</td>
                                <td v-if="showCol.crew" class="px-1 border-start-0 cur-pointer" @dblclick="newCrew(row.id)">{{row.crew}}</td>
                                <td v-if="showCol.section" class="px-1 cur-pointer" @dblclick="newSection(row.id)">{{row.section}}</td>
                                <td v-if="showCol.type" class="px-1 text-center">{{row.rtype}}</td>

                                <td v-for="(col, colInx) in colData" :key="colInx" class="px-1 text-center align-middle" style="font-size: 12px" :title="comm(row[col.colid]) ? mentDate(col.colid) +'  '+ comm(row[col.colid]) : mentDate(col.colid)"
                                    :style="code(row[col.colid]) == 'W' || code(row[col.colid]) == 'WN' ? 'background-color: #F2F4F8':
                                            code(row[col.colid]) == 'W/2' ? 'background-color: #FFC000':
                                            code(row[col.colid]) == 'R' || code(row[col.colid]) == 'RW' || code(row[col.colid]) == 'OR' || code(row[col.colid]) == 'IR' ? 'background-color: #FFFFCC':
                                            code(row[col.colid]) == 'A' || code(row[col.colid]) == 'A/2' || code(row[col.colid]) == 'IA' || code(row[col.colid]) == 'OA' || code(row[col.colid]) == 'AA' || code(row[col.colid])== 'H' || code(row[col.colid])== 'HH' || code(row[col.colid]) == 'OH' || code(row[col.colid]) == 'IH' || code(row[col.colid]) == 'OP' || code(row[col.colid]) == 'P' ? 'background-color: #FFFF99':
                                            code(row[col.colid]) == 'S' || code(row[col.colid]) == 'SS' || code(row[col.colid]) == 'IS' || code(row[col.colid]) == 'OS' ? 'background-color: #F2DCDB':
                                            code(row[col.colid]) == 'MR' ? 'background-color: #FF66FF':
                                            code(row[col.colid]) == 'N5' || code(row[col.colid]) == 'N6' || code(row[col.colid]) == 'D4' ? 'background-color: #E0E0E0':
                                            code(row[col.colid]) == 'BWB' || code(row[col.colid]) == 'WO' || code(row[col.colid]) == 'IW' || code(row[col.colid]) == 'OW' ? 'background-color: #e8f2e1':
                                            code(row[col.colid]) == 'T' || code(row[col.colid]) == 'T-' || code(row[col.colid]) == 'IT' || code(row[col.colid]) == 'OT' || code(row[col.colid]) == 'TT' ? 'background-color: #FF0000':''" 
                                    @click="countDay(rowInx, headDate1(col.colid))"
                                >
                                    <div v-if="code(row[col.colid])" class="cur-pointer" @dblclick="UpdRoster_Range(col.colid, code(row[col.colid]), comments(row[col.colid]), colInx), optMode='Update'">
                                        <div v-if="comm(row[col.colid])" class="text-decoration-underline">
                                            {{code(row[col.colid])}}
                                        </div>
                                        <div v-else>
                                            {{code(row[col.colid])}}
                                        </div>
                                    </div>
                                    <div v-else class="p-0 cur-pointer">
                                        <button class="btn btn-sm p-0" style="width: 18px; height: 18px" title="Add"  @dblclick="UpdRoster_Range(col.colid, code(row[col.colid]), comments(row[col.colid]), colInx), optMode='Add'"></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between tx-13 pt-1 pd-b-6">
                    <div class="text-muted">{{fullname}} Type: {{type(rosForm.rtype)}}</div>
                    <div v-if="text" class="text-end me-1">
                        {{text}}<span class="fw-bold text-primary">{{count}}</span> days
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL UPDATE, DELETE, ADD ROSTER -->
        <div class="modal" id="modalUpdRoster" back data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">{{shortname(fullname)}}:
                            <span v-if="rosForm.rtype=='P'" class="tx-danger">{{type(rosForm.rtype)}}</span>
                            <span v-else class="tx-primary">{{type(rosForm.rtype)}}</span>
                        </h6>
                    </div>
                    <div class="modal-body pt-1">  
                        <div class="row">
                            <div class="col-5 pe-1">
                                <div class="form-group">
                                    <input type="date" class="form-control ps-2 pe-0" disabled v-model="rosForm.rdatefr">
                                </div>
                            </div>
                            <div class="col-5 ps-1 pe-1">
                                <div class="form-group">
                                    <input type="date" class="form-control ps-2 pe-1" v-model="rosForm.rdateto" @change="CountD()">
                                </div>
                            </div>
                            <div class="col-2 ps-1">
                                <div class="form-group">
                                    <input type="text" class="form-control tx-center px-0" v-model="rosForm.days">
                                </div>
                            </div>
                        </div>
                        <div v-if="optMode!='Delete'" class="form-group">
                            <Multiselect v-model="rosForm.rcode" searchable="true" searchStart="true" placeholder="Code" :options="lkCode"/>
                        </div>
                        <div v-if="optMode=='Update'" class="form-group">
                            <textarea class="form-control" style="height: 80px" v-model="rosForm.comment" placeholder="Comment"></textarea>
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
                                <button v-if="optMode=='Update'" type="button" class="btn btn-primary" :class="AddRosRangeDis" @click="UpdRoster_RangeCF()"><i class="fe fe-check-circle"></i><span class="mx-1">Update</span></button> 
                                <button v-else-if="optMode=='Delete'" type="button" class="btn btn-danger" :class="DelRosRangeDis" @click="DelRoster_RangeCF()"><i class="fe fe-trash-2"></i><span class="mx-1">Delete</span></button> 
                                <button v-else type="button" class="btn btn-primary" :class="AddRosRangeDis" @click="AddRoster_RangeCF()"><i class="fe fe-plus-circle"></i><span class="mx-1">Add</span></button> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Modal Detail -->
        <div class="modal fade effect-scale" id="detail" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">{{fullname}}</h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-secondary r-left" style="width: 55px" title="Roster details" @click="detailMode = 'roster'">Roster</button>
                            <button type="button" class="btn btn-sm btn-secondary rounded-0" style="width: 55px" title="Finger scan details" @click="detailMode = 'scan'">Scan</button>
                            <button type="button" class="btn btn-sm btn-secondary r-right" data-bs-dismiss="modal" style="width: 55px" title="Close">Close</button>
                            <!-- <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button> -->
                        </div>
                    </div>
                       
                    <div class="modal-body pt-0">     
                        <div class="table-responsive border" style="max-height: 700px">
                            <table v-if="detailMode == 'roster'" class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px">
                                    <tr>
                                        <th class="border-start-0">Date</th>
                                        <th>Plan</th>
                                        <th>Actual</th>
                                        <th>Time Period</th>
                                        <th>Updated At</th>
                                        <th>Updated By</th>
                                        <th>Actual note </th>
                                        <th class="border-end-0 wd-60p">Plan Note</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr class="tr-hover" v-for="lst in detailData" :key="lst.rdate">
                                        <td class="border-start-0 py-0 text-center border-bottom-0"> {{ date1(lst.rdate) }} </td>
                                        <td :style="lst.pcode == 'W' || lst.pcode == 'WN' ? 'background-color: #F2F4F8':
                                                    lst.pcode == 'W/2' ? 'background-color: #FFC000':
                                                    lst.pcode == 'R' || lst.pcode == 'RW' ? 'background-color: #FFFFCC':
                                                    lst.pcode == 'A' || lst.pcode == 'A/2' || lst.pcode == 'H' || lst.pcode == 'P' ? 'background-color: #FFFF99':
                                                    lst.pcode == 'S' ? 'background-color: #F2DCDB':
                                                    lst.pcode == 'MR' ? 'background-color: #FF66FF':
                                                    lst.pcode == 'T' || lst.pcode == 'T-' ? 'background-color: #FF0000':''" class="py-0 text-center border-bottom-0"> {{ lst.pcode }} 
                                        </td>
                                        <td :style="lst.acode == 'W' || lst.acode == 'WN' ? 'background-color: #F2F4F8':
                                                    lst.acode == 'W/2' ? 'background-color: #FFC000':
                                                    lst.acode == 'R' || lst.acode == 'RW' || lst.acode == 'OR' || lst.acode == 'IR' ? 'background-color: #FFFFCC':
                                                    lst.acode == 'A' || lst.acode == 'A/2' || lst.acode == 'IA' || lst.acode == 'OA' || lst.acode == 'AA' || lst.acode == 'H' || lst.acode == 'HH' || lst.acode == 'OH' || lst.acode == 'IH' || lst.acode == 'P' ? 'background-color: #FFFF99':
                                                    lst.acode == 'S' || lst.acode == 'SS' ? 'background-color: #F2DCDB':
                                                    lst.acode == 'MR' ? 'background-color: #FF66FF':
                                                    lst.acode == 'BWB' || lst.acode == 'WO' ? 'background-color: #e8f2e1':
                                                    lst.acode == 'N5' || lst.acode == 'N6' || lst.acode == 'D4' ? 'background-color: #E0E0E0':
                                                    lst.acode == 'T' || lst.acode == 'T-' || lst.acode == 'IT' || lst.acode == 'OT' || lst.acode == 'TT' ? 'background-color: #FF0000':''" class="py-0 text-center border-bottom-0">{{ lst.acode }}
                                        </td>
                                        <td class="py-0 text-center border-bottom-0"> {{ lst.working_time }} </td>
                                        <td class="py-0 text-muted border-bottom-0"> {{ date2(lst.updated_at) }} </td>
                                        <td class="py-0 text-muted border-bottom-0 text-capitalize"> {{ lst.updated_by }} </td>
                                        <td class="py-0 border-bottom-0"> {{ lst.anote }} </td>
                                        <td class="py-0 border-bottom-0"> {{ lst.pnote }} </td>
                                    </tr>                                                                                 
                                </tbody>
                            </table>
                            <table v-if="detailMode == 'scan'" class="table main-table-reference text-nowrap mg-b-0">
                                <thead>
                                    <tr>
                                        <th class="border-start-0">Date</th>
                                        <th>Plan</th>
                                        <th>Actual</th>
                                        <th>Time Period</th>
                                        <th>In1</th>
                                        <th>Out1</th>
                                        <th>In2</th>
                                        <th>Out2</th>
                                        <th>Timesheet</th>
                                        <th class="border-end-0 wd-60p">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr class="tr-hover" v-for="lst in scanData" :key="lst.date">
                                        <td class="border-start-0 py-0 text-center border-bottom-0"> {{ date1(lst.date) }} </td>
                                        <td :style="lst.plan == 'W' || lst.plan == 'WN' ? 'background-color: #F2F4F8':
                                                    lst.plan == 'W/2' ? 'background-color: #FFC000':
                                                    lst.plan == 'R' || lst.plan == 'RW' ? 'background-color: #FFFFCC':
                                                    lst.plan == 'A' || lst.plan == 'A/2' || lst.plan == 'H' || lst.plan == 'P' ? 'background-color: #FFFF99':
                                                    lst.plan == 'S' ? 'background-color: #F2DCDB':
                                                    lst.plan == 'MR' ? 'background-color: #FF66FF':
                                                    lst.plan == 'T' || lst.plan == 'T-' ? 'background-color: #FF0000':''" class="py-0 text-center border-bottom-0"> {{ lst.plan }} 
                                        </td>
                                        <td :style="lst.actual == 'W' || lst.actual == 'WN' ? 'background-color: #F2F4F8':
                                                    lst.actual == 'W/2' ? 'background-color: #FFC000':
                                                    lst.actual == 'R' || lst.actual == 'RW' || lst.actual == 'OR' || lst.actual == 'IR' ? 'background-color: #FFFFCC':
                                                    lst.actual == 'A' || lst.actual == 'A/2' || lst.actual == 'IA' || lst.actual == 'OA' || lst.actual == 'AA' || lst.actual == 'H' || lst.actual == 'HH' || lst.actual == 'OH' || lst.actual == 'IH' || lst.actual == 'P' ? 'background-color: #FFFF99':
                                                    lst.actual == 'S' || lst.actual == 'SS' ? 'background-color: #F2DCDB':
                                                    lst.actual == 'MR' ? 'background-color: #FF66FF':
                                                    lst.actual == 'BWB' || lst.actual == 'WO' ? 'background-color: #e8f2e1':
                                                    lst.actual == 'N5' || lst.actual == 'N6' || lst.actual == 'D4' ? 'background-color: #E0E0E0':
                                                    lst.actual == 'T' || lst.actual == 'T-' || lst.actual == 'IT' || lst.actual == 'OT' || lst.actual == 'TT' ? 'background-color: #FF0000':''" class="py-0 text-center border-bottom-0"
                                        > 
                                            {{ lst.actual }} 
                                        </td>
                                        <td class="py-0 text-center border-bottom-0"> {{ lst.period }} </td>
                                        <td class="py-0 text-center border-bottom-0"> {{ lst.in1 }} </td>
                                        <td class="py-0 text-center border-bottom-0"> {{ lst.out1 }} </td>
                                        <td class="py-0 text-center border-bottom-0"> {{ lst.in2 }} </td>
                                        <td class="py-0 text-center border-bottom-0"> {{ lst.out2 }} </td>
                                        <td :style="lst.timesheet == 'W' || lst.timesheet == 'WN' ? 'background-color: #F2F4F8':
                                                    lst.timesheet == 'W/2' ? 'background-color: #FFC000':
                                                    lst.timesheet == 'R' || lst.timesheet == 'RW' ? 'background-color: #FFFFCC':
                                                    lst.timesheet == 'A' || lst.timesheet == 'A/2' || lst.timesheet == 'H' || lst.timesheet == 'P' ? 'background-color: #FFFF99':
                                                    lst.timesheet == 'S' ? 'background-color: #F2DCDB':
                                                    lst.timesheet == 'MR' ? 'background-color: #FF66FF':
                                                    lst.timesheet == 'T' || lst.timesheet == 'T-' ? 'background-color: #FF0000':''" class="py-0 text-center border-bottom-0"> {{ lst.timesheet }} 
                                        </td>
                                        <td class="py-0 border-end-0 border-bottom-0"> {{ lst.remarks }} </td>
                                    </tr>                                                                                 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>  

        <!-- Modal Roster Add Delete -->        
        <div class="modal fade effect-scale" id="addDelete" data-bs-backdrop="static" back data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">{{addMethod}} Roster</h6>
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
                                <div class="table-responsive element border" style="height: 410px">
                                    <table class="table main-table-reference text-nowrap mg-b-0">
                                        <thead class="position-sticky" style="top: 0px; z-index: 1">
                                            <tr>
                                                <th class="border-0" style="padding: 5px 10px">
                                                    <label class="form-checkbox m-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <input type="checkbox" v-model="add_selectAll" @click="AddSelectAll_click()">
                                                            <i class="form-icon"></i>
                                                            <span class="ms-2 cur-pointer mg-t-2" title="Select/Unselect all">Employee List</span>
                                                        </div>
                                                    </label>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-hover" v-for="lst in empList" :key="lst.userid">
                                                <td class="border-0">
                                                    <label class="form-checkbox m-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <input type="checkbox" :value="lst.userid" v-model="add_selected">
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
                                    <label class="rdiobox cur-pointer" @click="addMethodChanged()"><input name="add" type="radio" value="add" checked v-model="addMethod"><span>Add</span></label>
                                    <label class="rdiobox cur-pointer ms-5" @click="addMethodChanged()"><input name="add" type="radio" value="delete" v-model="addMethod"><span>Delete</span></label>  
                                </div>
                                <div class="form-group mt-3">
                                    <label v-if="addMethod == 'add'" class="mb-0">Add Until Date <span class="text-danger">*</span></label>
                                    <label v-else class="mb-0">Delete From Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="addDelDate">
                                </div>
                                <button v-if="addMethod == 'add'" type="button" class="btn btn-primary wd-100p" :class="addDelDis" @click="addRos()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                                <button v-else type="button" class="btn btn-danger wd-100p" :class="addDelDis" @click="delRos()"><i class="fe fe-trash-2"></i><span class="mx-1">Delete</span></button> 
                            </div>    
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Modal Lookup Code -->
        <div class="modal fade effect-scale" id="lookupcode" back data-bs-keyboard="false" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                     <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">Roster Lookup Code</h6>
                        <button v-if="permiss.lk_ros != 1" aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        <button v-else type="button" class="btn btn-sm btn-rounded btn-primary" style="padding: 3.2px 11.2px height: 15px; width: 70px" title="Add new record" @click="newCode()"><i class="fe fe-plus"></i> 
                            <span class="mx-1">New</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">    
                        <div class="table-responsive element ht-md-700">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px">
                                    <tr>
                                        <th>Timesheet</th>
                                        <th>Actual</th>
                                        <th>Description Lao</th>
                                        <th>Description Eng</th>  
                                        <th v-if="permiss.lk_ros == 1">Time Period</th>      
                                        <th v-else class="wd-70p">Time Period</th>      
                                        <th v-if="permiss.lk_ros == 1" class="wd-70p">Actions</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr v-for="lst in rosCodeData" :key="lst.id">
                                        <td class="text-center">{{ lst.ref_code }}</td>
                                        <td class="text-center">{{ lst.rcode }}</td>
                                        <td class="laofont">{{ lst.descr_lao }}</td>
                                        <td> {{ lst.descr_eng }} </td>
                                        <td> {{ lst.working_time }} </td>
                                        <td v-if="permiss.lk_ros == 1" class="p-0 align-middle">
                                            <div class="d-flex justify-content-start">
                                                <button class="btn btn-icon btn-sm btn-i p-0" title="Edit this record" @click="rosCodeEdit(lst.id)">
                                                    <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                </button> 
                                                <button class="btn btn-icon btn-sm btn-i p-0" title="Delete this record" @click="rosCodeDel(lst.id)">
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
            </div>                                              
        </div>

        <!-- Lookup Code Roster Add/Edit -->
        <div class="modal fade effect-scale pd-t-100 bd-0 bg-black-5" id="rosCodeEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="rosCodeLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header pb-1">
                        <h6 class="text-muted main-content-label text-capitalize">Roster Code</h6>
                    </div>
                    <div class="modal-body">  
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="mb-0">Roster Code  <span class=" text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="rosCodeForm.rcode">
                                </div>   
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="mb-0">Timesheet Code  <span class=" text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="rosCodeForm.ref_code">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="mb-0">Description Lao <span class=" text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="rosCodeForm.descr_lao">
                        </div>    
                        <div class="form-group">
                            <label class="mb-0">Description Eng  <span class=" text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="rosCodeForm.descr_eng">
                        </div>  
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="mb-0">Working Time</label>
                                    <input type="text" class="form-control" v-model="rosCodeForm.working_time">
                                </div>   
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="mb-0">Used  <span class=" text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="rosCodeForm.used">
                                </div>
                            </div>
                        </div>  
                        <div class="d-flex justify-content-end">
                            <button v-if="bntAddShow" type="button" class="btn btn-primary" :class="btnRosAddDis" @click="rosCodeAdd()"><i class="fe fe-plus"></i><span class="mx-1">Add</span></button> 
                            <button v-else type="button" class="btn btn-primary" @click="rosCodeUpd()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                            <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i><span class="mx-1">Close</span></button>
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Modal Show Columns -->
        <div class="modal fade effect-scale bd-0" id="showCol" data-bs-keyboard="false" tabindex="-1" aria-labelledby="colShowLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="text-muted main-content-label text-capitalize">Show / Hide Columns</h6>
                    </div>
                    <div class="modal-body pt-0">  
                        <div class="d-flex justify-content-between mb-2">
                            <span>No</span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="no"  v-model="showCol.no" @change='$emit("input", $event.target.checked)'>
                                <label class="custom-control-label cur-pointer" for="no"></label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Position</span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="position"  v-model="showCol.position" @change='$emit("input", $event.target.checked)'>
                                <label class="custom-control-label cur-pointer" for="position"></label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Crew</span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="crew"  v-model="showCol.crew" @change='$emit("input", $event.target.checked)'>
                                <label class="custom-control-label cur-pointer" for="crew"></label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Section</span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="section"  v-model="showCol.section" @change='$emit("input", $event.target.checked)'>
                                <label class="custom-control-label cur-pointer" for="section"></label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Type</span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="type"  v-model="showCol.type" @change='$emit("input", $event.target.checked)'>
                                <label class="custom-control-label cur-pointer" for="type"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Add Crew Modal -->
        <div class="modal fade" id="addCrew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="crewLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="text-muted main-content-label text-capitalize">Add New Group</h6>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body"> 
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div class="wd-100p me-1">
                                    <Multiselect v-model="crewForm.crew" :searchable="true" :options="lkCrew" @keydown.enter="addCrew()"/>
                                </div>
                                <div class="wd-35">
                                    <button type="button" class="btn btn-primary wd-35 p-0" :class="addCrewDis" title="Add new group" @click="addCrew()">
                                        <i class="fa fa-plus"></i>
                                    </button>  
                                </div>
                            </div>
                        </div>                           
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Add Section Modal -->
        <div class="modal fade" id="addSection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sectLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="text-muted main-content-label text-capitalize">Add New Section</h6>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body"> 
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div class="wd-100p me-1">
                                    <Multiselect v-model="sectForm.section" :searchable="true" :searchStart="true" :options="lkSect" @keydown.enter="addSection()"/>
                                </div>
                                <div class="wd-35">
                                    <button type="button" class="btn btn-primary wd-35 p-0" :class="addSectDis" title="Add new section" @click="addSection()">
                                        <i class="fa fa-plus"></i>
                                    </button>  
                                </div>
                            </div>
                        </div>                           
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Add Work Shift Roster-->
        <div class="modal fade effect-scale" id="shiftWork" data-bs-backdrop="static" back data-bs-keyboard="false" tabindex="-1" aria-labelledby="shiftWorkLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">Shift Work Update</h6>
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
                                <div class="table-responsive element border" style="height: 410px">
                                    <table class="table main-table-reference text-nowrap mg-b-0">
                                        <thead class="position-sticky" style="top: 0px; z-index: 1">
                                            <tr>
                                                <th class="border-0" style="padding: 5px 10px">
                                                    <label class="form-checkbox m-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <input type="checkbox" v-model="add_selectAll" @click="AddSelectAll_click()">
                                                            <i class="form-icon"></i>
                                                            <span class="ms-2 cur-pointer mg-t-2" title="Select/Unselect all">Employee List</span>
                                                        </div>
                                                    </label>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-hover" v-for="lst in empList" :key="lst.userid">
                                                <td class="border-0">
                                                    <label class="form-checkbox m-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <input type="checkbox" :value="lst.userid" v-model="add_selected">
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
                                <div class="form-group mt-3">
                                    <label class="mb-0">Date From <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="rosForm.rdatefr">
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">Date To <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" v-model="rosForm.rdateto">
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">Roster Code <span class="text-danger">*</span></label> 
                                    <Multiselect v-model="rosForm.rcode" searchable="true" searchStart="true" :options="lkCode"/>
                                </div>
                                <div class="form-group">
                                    <label class="mb-0">Comments</label>
                                    <textarea class="form-control" style="height: 146px" v-model="rosForm.comment"></textarea>
                                </div> 
                                <button type="button" class="btn btn-primary wd-100p" :class="shiftWorkDis" @click="updShiftWork()"><i class="fe fe-save"></i><span class="mx-1">Save</span></button> 
                            </div>    
                        </div>
            
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Filter by selected employee names -->
        <div class="modal fade effect-scale" id="searchName" back data-bs-keyboard="false" tabindex="-1" aria-labelledby="searchNameLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">Filter by Names</h6>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body pt-2">  
                        
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
                                                    <input type="checkbox" v-model="selectAllName" @click="onSelectAllName()">
                                                    <i class="form-icon"></i>
                                                    <span class="ms-2 cur-pointer mg-t-2" title="Select/Unselect all">Employee List</span>
                                                </div>
                                            </label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-hover" v-for="lst in empList" :key="lst.userid">
                                        <td class="border-0">
                                            <label class="form-checkbox m-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <input type="checkbox" :value="lst.name" v-model="selectedName">
                                                    <i class="form-icon"></i>
                                                    <span class="ms-2 cur-pointer">{{lst.name}}</span>
                                                </div>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-primary" :class="rosFilterNamesDis" @click="filterNames(), nameFilter ='true'"><span class="px-3">OK</span></button> 
                            <!-- <button type="button" class="btn btn-primary" :class="rosFilterNamesDis" @click="filterNames()"><i class="fe fe-check-circle"></i><span class="mx-1">OK</span></button>  -->
                        </div>              
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Filter -->
        <div class="modal fade effect-scale" id="Filter" data-bs-backdrop="static" back data-bs-keyboard="false" tabindex="-1" aria-labelledby="positionLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">Filter by {{filterBy}}</h6>
                        <!-- <p class="tx-12 tx-gray-500 mb-0">Select one at least</p> -->
                        <!-- <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button> -->
                    </div>
                    <div class="modal-body pt-2">  
                        <div class="table-responsive element border" style="height: 410px">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px; z-index: 1">
                                    <tr>
                                        <th class="border-0" style="padding: 5px 10px">
                                            <label class="form-checkbox m-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <input type="checkbox" v-model="valueAll" @click="SelectAllValue()">
                                                    <i class="form-icon"></i>
                                                    <span class="ms-2 cur-pointer mg-t-2" title="Select/Unselect all">{{filterBy}} List</span>
                                                </div>
                                            </label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-hover" v-for="(lst, inx ) in filterList" :key="inx">
                                        <td class="border-0">
                                            <label class="form-checkbox m-0">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <input type="checkbox" :value="lst.colname" v-model="valueSelected">
                                                    <i class="form-icon"></i>
                                                    <span class="ms-2 cur-pointer">{{lst.colname}}</span>
                                                </div>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-primary" :class="FilterDis" @click="FilterResult()"><span class="px-3">OK</span></button> 
                        </div>              
                    </div>
                </div>
            </div>                                              
        </div>

        <!-- Modal Comming Break -->
        <div class="modal fade effect-scale" id="breakInfo" data-bs-keyboard="false" tabindex="-1" aria-labelledby="breakLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header pb-1 bd-b-0">
                        <h6 class="main-content-label text-capitalize">ລາຍຊື່ພະນັກງານທີ່ໃກ້ຈະອອກເມື່ອພັກອີກພາຍໃນ 5 ວັນຂ້າງໜ້າ</h6>
                        <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body pt-0">                                   
                        <div class="table-responsive" style="max-height: 700px">
                            <table class="table main-table-reference text-nowrap mg-b-0">
                                <thead class="position-sticky" style="top: 0px">
                                    <tr>
                                        <th class="border-start-0">Name</th>
                                        <th>Position</th>
                                        <th class="laofont py-0 align-middle" style="font-size: 13.5px">ຊື່ ແລະ ນາມສະກຸນ</th>
                                        <th class="laofont py-0 align-middle" style="font-size: 13.5px">ຕຳແໜ່ງ</th>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Notes</th>
                                        <th class="border-end-0 wd-60p">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr class="tr-hover" v-for="lst in breakData" :key="lst.fullname">
                                        <td>{{lst.fullname}}</td>
                                        <td>{{lst.position}}</td>
                                        <td class="laofont">{{lst.namelao}}</td>
                                        <td class="laofont">{{lst.position_lao}}</td>
                                        <td class="tx-center">{{date1(lst.rdate)}}</td>
                                        <td class="laofont">{{lst.ref_code}}</td>
                                        <td class="laofont">{{lst.notes}}</td>
                                        <td class="laofont">{{lst.remarks}}</td>
                                    </tr>                                                                                 
                                </tbody>
                            </table>
                        </div>
                        <!-- <p class="laofont mt-2 mb--0">R: ພັກຕາມຜຽນ; A: ພັກປະຈຳປີ; S: ພັກລາປ່ວຍ</p> -->
                        <p class="laofont mt-2 mb-0 font-italic tx-danger">ໝາຍເຫດ: ຫຼັງຈາກຂຽນໃບລາພັັກແລ້ວກະລຸນາອັບເດດໂຣສເຕີ</p>
                    </div>
                </div>
            </div>                                              
        </div>

        

    </div>
</template>

<script>
export default {
    name: 'XpplAppRoster',

    data() {
        return {
            permiss: [],
            lkDept: [],
            lkCode: [],
            lkEmp: [],
            lkCrew: [],
            lkSect: [],
            colData: [],
            rosData: [],
            detailData: [],
            rosCodeData: [],
            dept: '',
            datefr: '',
            dateto: '',
            search: '',
            btnClear: '',
            rosForm: {userid: '', rtype: '', rdatefr: '', rdateto: '', days:'', rcode: null, comment: ''},
            rosCodeForm: {id: '', rcode: '', ref_code: '', descr_lao: '', descr_eng: '', working_time: '', used: '1'},
            rowSel: '',
            loading: false,
            cdate: '',

            addMethod: 'add',
            addDelDate: '',
            bntAddShow: '',
            fullname: '',
            selDate: [],
            count: '',
            text: '',
            crewForm: {id: '', crew: null},
            sectForm: {id: '', section: null},
            showCol: {no: false, crew: false, position: true, section: false, type: false},
            
            filterMode: '',

            selectedName: [],
		    selectAllName: false,

            add_selected: [],
            add_selectAll: false,

            valueSelected: [],
            valueAll: false, 
            filterList: [],

            filterBy: '',

            empList: [],
            search2: '',
            btnClear2: '',
            breakData: [],
            detailMode: '',
            date: '',
            scanData: [],

            colinx:'',
            optMode:'',

        };
    },

    mounted() {

    },

    computed: {
        addDelDis(){
            if (this.addDelDate == '' || this.add_selected == ''){
                return 'disabled';
            } else {
                return '';
            }
        },

        addCrewDis(){
            if (this.crewForm.crew == null){
                return 'disabled';
            } else {
                return '';
            }
        },

        addSectDis(){
            if (this.sectForm.section == null){
                return 'disabled';
            } else {
                return '';
            }
        },

        shiftWorkDis(){
            if (this.rosForm.rdatefr == '' || this.rosForm.rdateto == '' || this.rosForm.rcode == null || this.add_selected == '' || this.rosForm.rdatefr > this.rosForm.rdateto){
                return 'disabled';
            } else {
                return '';
            }
        },

        rosFilterNamesDis(){
            if (this.selectedName == ''){
                return 'disabled';
            } else {
                return '';
            }
        },

        FilterDis(){
            if (this.valueSelected == ''){
                return 'disabled';
            } else {
                return '';
            }
        },

        AddRosRangeDis(){
            if (this.rosForm.rdateto =='' || this.rosForm.days =='' || this.rosForm.rcode ==null || this.rosForm.rdatefr > this.rosForm.rdateto){
                return 'disabled';
            } else {
                return '';
            }
        },

        DelRosRangeDis(){
            if (this.rosForm.rdateto =='' || this.rosForm.days =='' || this.rosForm.rdatefr > this.rosForm.rdateto){
                return 'disabled';
            } else {
                return '';
            }
        }
    },

    methods: {
        async onLoad(){
            this.loading = true;
            const resPermiss = await axios.get('/api/permiss')
            this.permiss = resPermiss.data;

            const profile = await axios.get('api/profile')
			this.dept = profile.data.department;

            let today = new Date();
            this.datefr = moment(new Date(today.getFullYear(), today.getMonth() - 1, 15)).format('YYYY-MM-DD');
            this.dateto = moment(new Date(today.getFullYear(), today.getMonth() + 1, 15)).format('YYYY-MM-DD');

            this.cdate = moment(today).format('YYYY-MM-DD');

            const colhead = await axios.get(`/api/roster/datecol?datefr=${this.datefr}&dateto=${this.dateto}`)
            this.colData = colhead.data;

            const roster = await axios.get(`/api/roster/roster?dept=${this.dept}&datefr=${this.datefr}&dateto=${this.dateto}&search=${this.search}`)
            this.rosData = roster.data;

            this.loading = false;

            const dept = await axios.get('/api/lookup/depts')
            this.lkDept = dept.data;

            const rcode = await axios.get('/api/roster/rcode')
            this.lkCode = rcode.data;

            const section = await axios.get('/api/lookup/section')
            this.lkSect = section.data;
            
            const crew = await axios.get('/api/lookup/crew')
            this.lkCrew = crew.data;

            this.date = moment(new Date()).format('YYYY-MM-DD');

        },

        refreshRoster(){
            if (this.filterMode == 'name') {
                this.rosSearchName();
            } else if (this.filterMode == 'names') {
                this.filterNames();
            } else if (this.filterMode == 'Crew' || this.filterMode == 'Position') {
                this.FilterResult();
            } else {
                this.getRoster();
            }
        },

        async getRoster(){
            this.loading = true;
            this.rowSel = '';
            this.nameFilter = '';
            this.groupFilter = '';
            this.filterMode = ''

            if (this.dept != '' && this.datefr != '' && this.dateto != ''){
                const resCol = await axios.get(`/api/roster/datecol?datefr=${this.datefr}&dateto=${this.dateto}`)
                this.colData = resCol.data;

                const resRos = await axios.get(`/api/roster/roster?dept=${this.dept}&datefr=${this.datefr}&dateto=${this.dateto}&search=${this.search}`)
                this.rosData = resRos.data;
            }
            this.loading = false;
        },

        CountD(){
            let d1 = moment(this.rosForm.rdatefr).format("MM-DD-YYYY");
            let d2 = moment(this.rosForm.rdateto).format("MM-DD-YYYY");

            let dat1 = new Date(d1);
            let dat2 = new Date(d2);

            let ddiff = dat2.getTime() - dat1.getTime();
            this.rosForm.days = Math.ceil(ddiff / (1000 * 3600 * 24)) + 1;
        },

        UpdRoster_Range(date, code, comm, ix){
            if(this.permiss.ros_edit == 0){
                this.$swal.fire({
                    text: "You don't have permission update!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                let d = moment(date.split('c')[1]).format('YYYY-MM-DD');
                this.rosForm.rdatefr = d;
                this.rosForm.rdateto = d;
                this.rosForm.rcode = code;
                this.rosForm.comment = comm;
                this.rosForm.days = 1;
                this.colinx = ix;
                $('#modalUpdRoster').modal('show');
            }
        },

        UpdRoster_RangeCF(){
            $('#modalUpdRoster').modal('hide');
            let dateList = [];
            let n = this.colinx; //column index
            for (let i = 0; i < this.rosForm.days; i++){
                    let col = this.colData[n].colid;
                    this.rosData.find((i)=>i.userid == this.rosForm.userid && i.rtype == this.rosForm.rtype)[col] = this.rosForm.rcode;
                    this.rosData.find((i)=>i.userid == this.rosForm.userid && i.rtype == this.rosForm.rtype)[col] = this.rosForm.rcode;

                    let date = moment(col.split('c')[1]).format('YYYY-MM-DD');
                    dateList.push(date);
                    n = n + 1
                }

            this.$axios.post('/api/roster/updrosterrange', {
                list: dateList,
                userid: this.rosForm.userid,
                type: this.rosForm.rtype,
                code: this.rosForm.rcode,
                comm: this.rosForm.comment
            }).then(res => {
                console.log('Update completed');
            })
        },

        DelRoster_RangeCF(){
            $('#modalUpdRoster').modal('hide');
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
                    for (let i = 0; i < this.rosForm.days; i++) {
                            let col = this.colData[n].colid;
                            this.rosData.find((i)=>i.userid == this.rosForm.userid && i.rtype == 'P')[col] = null;
                            this.rosData.find((i)=>i.userid == this.rosForm.userid && i.rtype == 'A')[col] = null;

                            let date = moment(col.split('c')[1]).format('YYYY-MM-DD');
                            dateList.push(date);
                            n = n + 1
                        }

                    //delete in database
                    this.$axios.post('/api/roster/delrosterrange', {
                        list: dateList,
                        userid: this.rosForm.userid
                    }).then(res => {
                        $('#modalUpdRoster').modal('hide');
                    })
                } else {
                    $('#modalUpdRoster').modal('show');
                }
            });
        },

        AddRoster_RangeCF(){
            $('#modalUpdRoster').modal('hide');
            let dateList = [];
            let n = this.colinx; //column index
            for (let i = 0; i < this.rosForm.days; i++){
                    let col = this.colData[n].colid;
                    this.rosData.find((i)=>i.userid == this.rosForm.userid && i.rtype == 'P')[col] = this.rosForm.rcode;
                    this.rosData.find((i)=>i.userid == this.rosForm.userid && i.rtype == 'A')[col] = this.rosForm.rcode;

                    let date = moment(col.split('c')[1]).format('YYYY-MM-DD');
                    dateList.push(date);
                    n = n + 1
                }

            this.$axios.post('/api/roster/addrosterrange', {
                list: dateList,
                userid: this.rosForm.userid,
                code: this.rosForm.rcode
            }).then(res => {
                console.log('Add completed');
            })
        },

        FilterByPosition(){
            this.filterBy = 'Position'
            this.FilterList();
        },

        FilterByGroup(){
            this.filterBy = 'Crew'
            this.FilterList();
        },

        FilterList(){
            this.valueSelected = [];
            this.valueAll = false;

            this.$axios.get(`/api/roster/filterlist?dept=${this.dept}&filter=${this.filterBy}`)
            .then(res => this.filterList = res.data)
            $('#Filter').modal('show');
        },

        SelectAllValue() {
            this.valueSelected = [];
            if (!this.valueAll) {
                for (let i in this.filterList) {
                    this.valueSelected.push(this.filterList[i].colname);
                }
            }
        },

        async FilterResult(){
            this.rowSel = '';
            this.search = '';
            this.btnClear = false;
            
            $('#Filter').modal('hide');

            let fd = new FormData();
                fd.append('values', this.valueSelected.toString());
                fd.append('datefr', this.datefr);
                fd.append('dateto', this.dateto);
                fd.append('dept', this.dept);
                fd.append('filter', this.filterBy);

            if (this.dept != '' && this.datefr != '' && this.dateto != ''){
                const resCol = await axios.get(`/api/roster/datecol?datefr=${this.datefr}&dateto=${this.dateto}`)
                this.colData = resCol.data;

                const resRos = await axios.post('/api/roster/filterresult', fd, {headers:{"Content-Type": "multipart/form-date"}})
                this.rosData = resRos.data;    

                this.filterMode = this.filterBy;
                this.nameFilter = '';
            }
        },

        async rosSearchName(){
            this.rowSel = '';
            this.nameFilter = '';
            this.groupFilter = '';
            this.filterMode = 'name'

            if (this.dept != '' && this.datefr != '' && this.dateto != ''){
                const resCol = await axios.get(`/api/roster/datecol?datefr=${this.datefr}&dateto=${this.dateto}`)
                this.colData = resCol.data;

                const resRos = await axios.get(`/api/roster/roster?dept=${this.dept}&datefr=${this.datefr}&dateto=${this.dateto}&search=${this.search}`)
                this.rosData = resRos.data;
            }
        },

        async filterNames(){
            this.rowSel = '';
            this.search = '';
            this.btnClear = false;
            
            $('#searchName').modal('hide');

            let fd = new FormData();
                fd.append('names', this.selectedName.toString());
                fd.append('datefr', this.datefr);
                fd.append('dateto', this.dateto);
                fd.append('dept', this.dept);

            if (this.dept != '' && this.datefr != '' && this.dateto != ''){
                const resCol = await axios.get(`/api/roster/datecol?datefr=${this.datefr}&dateto=${this.dateto}`)
                this.colData = resCol.data;

                const resRos = await axios.post('/api/roster/rostermultiname', fd, {headers:{"Content-Type": "multipart/form-date"}})
                this.rosData = resRos.data;    
                this.filterMode = 'names'
            }
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

        inputSearch(){
            if(this.search.length >0){
                this.btnClear = true;
            } else {
                this.btnClear = false;
            }
        },

        searchClear(){
            this.search = '';
            this.btnClear = false;
            this.getRoster();
        },

        selectRow(id, inx, name, type){
            this.rowSel = inx;
            this.fullname = name;
            this.rosForm.userid = id;
            this.rosForm.rtype = type;
        },

        async rosDetail(id){
            this.detailMode = 'roster';
            const rosDet = await axios.post('/api/roster/rosdetail', {
                dept: this.dept,
                datefr: this.datefr,
                dateto: this.dateto,
                userid: id
            });
         
            this.detailData = rosDet.data;
            $('#detail').modal('show');

            const scanDet = await axios.post('/api/fingerscan/detail', {
                dept: this.dept,
                datefr: moment(new Date(new Date().getFullYear(), new Date().getMonth() - 1)).format('YYYY-MM-26'),
                dateto: moment(new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0)).format('YYYY-MM-25'),
                userid: id
            });
            this.scanData = scanDet.data;
        },

        addDelete(){
            if (this.permiss.ros_edit == 0){
                this.$swal.fire({
                    text: "You don't have permission to update!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.addMethod = 'add'
                this.addDelDate = '';
                this.add_selected = [];
                this.add_selectAll = false;
                this.search2 = '';
                this.nameList();
                $('#addDelete').modal('show');
            }
        },

        addMethodChanged(){
            this.add_selected = [];
            this.addDelDate = '';
        },

        addRos(){
            this.$axios.post('/api/roster/addros', {
                list: this.add_selected,
                date: this.addDelDate
            }).then(res => {
                $('#addDelete').modal('hide');
                this.getRoster();
            })
        },

        delRos(){
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
                    this.$axios.post('/api/roster/delros', {
                        list: this.add_selected,
                        date: this.addDelDate
                    }).then(res => {
                        $('#addDelete').modal('hide');
                        this.getRoster();
                    })
                } else {
                    $('#addDelete').modal('show');
                }
            });
        },

        getRosCode(){
            $('#lookupcode').modal('show');

            this.$axios.get('/api/roster/rosCode')
            .then(res => this.rosCodeData = res.data)
        },

        newCode(){
            this.rosCodeFormClear();
            this.bntAddShow = true;
            $('#rosCodeEdit').modal('show');
        },

        rosCodeAdd(){
            this.$axios.post('/api/roster/rosCodeAdd', this.rosCodeForm)
            .then((res) => {
                if(res.data.success){
                    $('#rosCodeEdit').modal('hide');
                    this.getRosCode();
                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        rosCodeEdit(id){
            this.rosCodeFormClear();
            this.bntAddShow = false;
            $('#rosCodeEdit').modal('show');

            this.$axios.post(`/api/roster/rosCodeEdit/${id}`)
            .then((res)=>{

                this.rosCodeForm.id = res.data.id;
                this.rosCodeForm.rcode = res.data.rcode;
                this.rosCodeForm.ref_code = res.data.ref_code;
                this.rosCodeForm.descr_lao = res.data.descr_lao;
                this.rosCodeForm.descr_eng = res.data.descr_eng;
                this.rosCodeForm.working_time = res.data.working_time;
                this.rosCodeForm.used = res.data.used;

            }).catch((error)=>{
                console.log(error);
            })
        },

        rosCodeUpd(){
            this.$axios.post('/api/roster/rosCodeUpd', this.rosCodeForm)
            .then((res)=>{

                $('#rosCodeEdit').modal('hide');
                this.getRosCode();

            }).catch((error)=>{
                console.log(error);
            })
        },

        rosCodeDel(id){
            $('#lookupcode').modal('hide');
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
                    this.$axios.post(`/api/roster/rosCodeDel/${id}`)
                    .then((response)=>{
                        this.getRosCode();
                    }).catch((error)=>{
                        console.log(error);
                    })
                }
                $('#lookupcode').modal('show');
            });
        },

        rosCodeFormClear(){
            let f = this.rosCodeForm;
                f.rcode = '';
                f.ref_code = '';
                f.descr_lao = '';
                f.descr_eng = '';
                f.working_time = '';
                f.used = '1';
        },

        countDay(inx, value){
            this.text = '';
            this.selDate.push({
                inx: inx,
                d: value
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

                this.text = 'From: ' + moment(date1).format("DD/MM/YYYY") +' To '+ moment(date2).format("DD/MM/YYYY") +': ';
                this.count = TotalDays;
                this.selDate = [];
            } else {
                this.selDate = [];
            }
        },

        newCrew(id){
            this.crewForm.id = id;
            this.crewForm.crew = null;
            $('#addCrew').modal('show');
        },

        addCrew(){
            this.$axios.post('/api/roster/addcrew', this.crewForm)
            .then(res => {
                $('#addCrew').modal('hide');
                // this.getRoster();
            })
        },

        newSection(id){
            this.sectForm.id = id;
            this.sectForm.section = null;
            $('#addSection').modal('show');
        },

        addSection(){
            this.$axios.post('/api/roster/addsection', this.sectForm)
            .then(res => {
                $('#addSection').modal('hide');
                // this.getRoster();
            })
        },

        showCols(){
            $('#showCol').modal('show');
        },

        shiftWork(){
            if (this.permiss.ros_edit == 0){
                this.$swal.fire({
                    text: "You don't have permission to update!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.selectedName = [];
                this.selectAllName = false;
                this.rosForm.rcode = '';
                this.rosForm.rdatefr = '';
                this.rosForm.comment = '';
                this.search2 = '';
                this.nameList();
                $('#shiftWork').modal('show');
            }
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

        AddSelectAll_click() {
            this.add_selected = [];
            if (!this.add_selectAll) {
                for (let i in this.empList) {
                    this.add_selected.push(this.empList[i].userid);
                }
            }
        },

        onSelectAllName() {
            this.selectedName = [];
            if (!this.selectAllName) {
                for (let i in this.empList) {
                    this.selectedName.push(this.empList[i].name);
                }
            }
        },

        updShiftWork(){
            this.$axios.post('/api/roster/updshiftwork', {
                list: this.selected,
                type: this.rosForm.rtype,
                datefr: this.rosForm.rdatefr,
                dateto: this.rosForm.rdateto,
                code: this.rosForm.rcode,
                comment: this.rosForm.comment
            }).then(res => {
                $('#shiftWork').modal('hide');
                this.getRoster();
            })
        },

        rosFilterNames(){
            this.selectedName = [];
            this.selectAllName = false;
            this.search2 = '';
            this.nameList();
            $('#searchName').modal('show');
        },

        padding(value){
             if (value) {
                return value.padStart(4, "0");
            }
        },

        breakInfo(){
            this.$axios.get(`/api/roster/breakinfo?dept=${this.dept}`)
            .then(res => {
                this.breakData = res.data;
                $('#breakInfo').modal('show');
            })
        },

        code(text){
            if (text) {
                return text.split("_")[0];
            }
        },

        comm(text){
            if (text) {
                return text.split("_")[1];
            }
        },

        mentDate(text){
            if (text) {
                return moment(text.split("c")[1]).format("DD/MM/YYYY");
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

        type(value){
            if (value == 'P'){
                return 'Plan'
            } else {
                return 'Actual'
            }
        },

        shortname(text){
            if (text) {
                return text.split(" ")[1];
            }
        },

        formatNumber(value) {
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

<style>
    .r-left{
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
    }
    .r-right{
        border-top-right-radius: 50px;
        border-bottom-right-radius: 50px;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }
</style>