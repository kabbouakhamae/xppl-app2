<template>
    <div v-if="loading">
        <loading/>
    </div> 
    <div v-else>
        <div class="card">
            <div class="card-header pd-r-15 pd-t-10 pb-0">
                <div v-if="showList" class="d-flex justify-content-between">
                    <div class="d-flex justify-content-start align-items-center">
                        <h4 class="card-title mg-b-0 text-muted">Employee List</h4>
                    </div>
                    <div class="d-flex justify-content-start me-0">
                        <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                            <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
                        </button> 
                        <div class="dropdown-menu tx-13">
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="expCurrentEmp()">
                                <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Export Current Employee</span>
                            </div>
                            <div class="dropdown-item cur-pointer dropdown-hover" @click="expExEmp()">
                                <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Export Ex-Employee</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pd-t-0 pd-b-10">
                <div v-if="showList">
                    <div class="breadcrumb-header justify-content-between align-items-center mb-1 mt-1" >
                        <div>
                            <span class="d-block mg-b-10 text-muted tx-12">All Employee included current and ex-employee...</span>
                        </div>
                        <div class="d-flex my-xl-auto right-content align-items-center">
                            <div class="pos-relative wd-md-250 wd-100p me-1">
                                <input class="form-control" style="padding-left: 25px" type="text" :placeholder="$t('search')" v-model="search" @input="searchChange()" >
                                    <i class="fe fe-search search-i text-muted"></i>
                                <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                            </div>
                            <div class="wd-35">
                                <button type="button" class="btn btn-primary p-0 wd-35" title="Add new employee" @click="empNew()">
                                    <i class="mdi mdi-account-plus tx-18"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive userlist-table border-bottom">
                        <table class="table card-table text-nowrap mb-0">
                            <thead style="top: 0px; height: 35px; background-color: #5195D3">
                                <tr>
                                    <th style="letter-spacing: 0.5px; width: 10px; padding: 7px 6px" class="fw-bold text-white"><span>Image</span></th>
                                    <th style="letter-spacing: 0.5px; width: 50px; padding: 7px 10px" class="fw-bold text-white"><span>Name and Surname</span></th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">Position | Level</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">
                                        <div v-if="loginPermiss.emp_all == 1">Department | Section</div>
                                        <div v-else>Section</div>
                                    </th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">Country | Province</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">Phone Number</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">Employee ID</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">Scan ID</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">Food ID</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">User ID</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">Roster</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px" class="fw-bold text-white">Status</th>
                                    <th style="letter-spacing: 0.5px; padding: 7px 12px; width: 10%; right: -0.1px; z-index: 1; background-color: #5195D3" class="fw-bold text-white position-sticky">Tools</th>
                                </tr>
                            </thead>
                            <tbody class="tx-13">
                                <tr class="tr-hover" v-for="list in empData.data" :key="list.id">

                                    <td style="padding: 6px 6px 5px 2px" class="bg-white">
                                        <div class="pos-relative" @click="empPrev(list.id)">
                                            <img v-if="list.photo" alt="" class="rounded-circle avatar-md cur-pointer" :src="'assets/img/profile/'+ list.photo">                                   
                                            <img v-if="!list.photo && list.gender == 'Male'" alt="" class="rounded-circle avatar-md cur-pointer" src="assets/img/male.png">
                                            <img v-if="!list.photo && list.gender != 'Male'" alt="" class="rounded-circle avatar-md cur-pointer" src="assets/img/female.png">
                                            <span v-if="list.status == 'Current' || list.status == 'Temporary'" class="rounded-circle bg-success bad"></span>
                                        </div>
                                    </td>

                                    <td style="padding: 6px 12px 5px 10px" @dblclick="empEdit(list.id)" class="cur-pointer" title="Edit">
                                        {{ list.name }} {{ list.surname }} <br>
                                        <span class="laofont">{{ list.namelao }} {{ list.surnamelao }}</span>
                                    </td>

                                    <td style="padding: 6px 12px; ">{{ list.position }} <br> {{list.levels}}</td>

                                    <td style="padding: 6px 12px; ">
                                        <span v-if="loginPermiss.emp_all == 1">{{ list.department }}<br></span>
                                        {{list.section}}
                                    </td>

                                    <td style="padding: 6px 12px; ">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="wd-30 text-center">
                                                <img width="20" :src="'assets/img/flags/'+ list.country + '.png'" alt="">
                                            </div>
                                            {{ list.country }} <br>
                                        </div>
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="wd-30 text-center">
                                                <i v-if="list.province" class="mdi mdi-map-marker-radius tx-16 text-black-50"></i> 
                                            </div>
                                            {{ list.province}} 
                                        </div>
                                    </td>

                                    <td style="padding: 6px 12px; "> 
                                        <i v-if="list.phone" class="mdi mdi-phone-classic me-1 tx-14"></i> {{ list.phone}} 
                                    </td>

                                    <td style="padding: 6px 12px; ">
                                        <div v-if="list.empid">{{ list.empid }}</div>
                                    </td>

                                    <td style="padding: 6px 12px; ">                          
                                        {{ list.scanid }}
                                        <span v-if="list.scantimes == 'N/A'">/{{list.scantimes}}</span>
                                        <span v-else>/ {{list.scantimes}} times</span>
                                    </td>

                                    <td style="padding: 6px 12px; ">{{ list.foodid }}</td>
                                    <td style="padding: 6px 12px; ">{{ list.id }}</td>
                                    <td style="padding: 6px 12px; ">{{ list.roster }}</td>

                                    <td style="padding: 6px 12px; "> 
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="wd-30 text-center">
                                                <i v-if="list.status == 'Current' || list.status == 'Temporary'" class="fe fe-user-check"></i> 
                                                <i v-else class="fe fe-user-x"></i> 
                                            </div>
                                            {{ list.status }} <br>
                                        </div>
                                        <div class="d-flex justify-content-start align-items-center" v-if="list.status == 'Current' || list.status == 'Temporary'">
                                            <div class="wd-30 text-center">
                                                <i class="fe fe-clock"></i> 
                                            </div>
                                            <div class="mg-b-3">
                                                {{timeago(list.startdate)}}
                                            </div>
                                        </div>
                                    </td>

                                    <td style="padding: 0px 4px; vertical-align: middle; right: -0.1px" class="position-sticky bg-white">
                                        <div class="d-flex justify-content-start">
                                            <button class="btn btn-icon btn-i pos-relative p-0 m-0" style="height: 30px; width: 30px" title="Edit" @click="empEdit(list.id)">
                                                <i class="bx bx-edit text-info pos-absolute" style="left: 50%; transform: translateX(-50%); top: 50%"></i>
                                            </button> 
                                            <button class="btn btn-icon btn-i pos-relative p-0 m-0" style="height: 30px; width: 30px" title="Delete" @click="empDel(list.id)">
                                                <i class="bx bx-trash text-danger pos-absolute" style="left: 50%; transform: translateX(-50%); top: 50%"></i>
                                            </button> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination :pagination="empData" @paginate="getEmpData($event)" :offset="2"></pagination>
                </div> <!-- End employee list -->

                <!-- Add and Edit employee -->
                <div v-if="showAdd" class="mt-4">
                    <div class="row">
                        <div class="col-xxl-2">
                            <div style="height: 200px; width: 100%;">
                                <img class="cur-pointer" style="width: 160px; height: 200px; border: solid 1px #cccc;" title="Choose an Image" :src="photoPrev" @click="chooseImage">
                                <!-- <img class="cur-pointer" style="width: 160px; height: 200px; border: solid 1px #cccc;" title="Choose an Image" src="assets/img/no.jpg" @click="chooseImage"> -->
                                <input class="d-none" ref="fileInput" type="file" accept="image/*"  @change="onSeclected">
                            </div> 
                            <label class="rdiobox mt-4 cur-pointer"><input name="rdio" type="radio" value="Male" checked="" v-model="empForm.gender"><span>Male</span></label>
                            <label class="rdiobox mb-4 cur-pointer"><input name="rdio" type="radio" value="Female" v-model="empForm.gender"><span>Female</span></label>                         
                        </div>

                        <div class="col-xxl-10">
                            <!-- Row1 -->
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="mb-0">Name (Eng)  <span class=" text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="empForm.name">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="mb-0">Surname (Eng)</label>
                                        <input type="text" class="form-control" v-model="empForm.surname">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12">
                                    <div class="form-group">
                                        <label class="mb-0">Phone Number</label>
                                        <input type="text" class="form-control" v-model="empForm.phone">
                                    </div>
                                </div>
                            </div>

                            <!-- Row2 -->
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="mb-0">ຊື່ (ລາວ)  <span class=" text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="empForm.namelao">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="mb-0">ນາມສະກຸນ (ລາວ)</label>
                                        <input type="text" class="form-control" v-model="empForm.surnamelao">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label class="mb-0">Birthday</label>
                                        <input type="date" class="form-control" v-model="empForm.birthday">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-8 col-md-8 col-sm-8">
                                    <div class="form-group">
                                        <label class="mb-0">Email</label>
                                        <input type="eamil" class="form-control" v-model="empForm.email">
                                    </div>
                                </div>
                            </div>

                            <!-- Row3 -->
                            <h6 class="text-muted mt-2">Employee Address</h6>
                            <div class="row">
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-4">
                                    <div class="form-group">
                                        <label class="mb-0">Country  <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="empForm.country" :options="lkCountry"/>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-8 col-md-6 col-sm-8">
                                    <div class="form-group">
                                        <label class="mb-0">Province</label>
                                        <Multiselect v-if="empForm.country == 'Laos'" v-model="empForm.province" :searchable="true" :searchStart="true" :options="lkProv" @select="getDistrict()"/>
                                        <input v-else type="text" class="form-control" v-model="empForm.province">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="mb-0">District</label>
                                        <Multiselect v-if="empForm.country == 'Laos'" v-model="empForm.district" :searchable="true" :searchStart="true" :options="lkDist"/>
                                        <input v-else type="text" class="form-control" v-model="empForm.district">
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="mb-0">Village</label>
                                        <input type="text" class="form-control" v-model="empForm.village">
                                    </div>
                                </div>
                            </div>

                            <!-- Employee detail -->
                            <div v-if="showDetail">
                                <h6 class="text-muted mt-2">Employee detail</h6>
                                <!-- Row4 -->
                                <div class="row">
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Start Date  <span class=" text-danger">*</span></label>
                                            <input type="date" class="form-control" v-model="empForm.startdate">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">End Date</label>
                                            <input type="date" class="form-control" v-model="empForm.enddate">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label class="mb-0">Position  <span class=" text-danger">*</span></label> 
                                            <Multiselect v-model="empForm.position" searchable="true" searchStart="true" :options="lkPosition"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Contract Type</label> 
                                            <Multiselect v-model="empForm.contract" :options="lkContract"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Levels</label> 
                                            <Multiselect v-model="empForm.levels" :options="lkLevels"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row5 -->
                                <div class="row">
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Employee ID</label>
                                            <input type="text" class="form-control" v-model="empForm.empid">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Scan ID  <span class=" text-danger">*</span></label>
                                            <input type="number" class="form-control" v-model="empForm.scanid">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Food ID</label>
                                            <input type="text" class="form-control" v-model="empForm.foodid">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Roster  <span class=" text-danger">*</span></label> 
                                            <Multiselect v-model="empForm.roster" :options="lkRoster"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-6 col-md-4 col-sm-4 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Scan Times  <span class=" text-danger">*</span></label> 
                                            <Multiselect v-model="empForm.scantimes" :options="lkScantimes"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-6 col-md-4 col-sm-4 col-6">
                                        <div class="form-group">
                                            <label class="mb-0">Working hrs  <span class=" text-danger">*</span></label>
                                            <input type="number" class="form-control" v-model="empForm.hours">
                                        </div>
                                    </div>
                                </div>
                                <!-- Row6 -->
                                <div class="row">
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4">
                                        <div class="form-group">
                                            <label class="mb-0">Site  <span class=" text-danger">*</span></label> 
                                            <Multiselect v-model="empForm.site" :options="lkSite"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-9 col-md-8 col-sm-8">
                                        <div class="form-group">
                                            <label class="mb-0">Department  <span class=" text-danger">*</span></label> 
                                            <Multiselect v-model="empForm.dept" searchable="true" searchStart="true" :options="lkDept"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="mb-0">Section <span class=" text-danger">*</span></label> 
                                            <Multiselect v-model="empForm.section" searchable="true" searchStart="true" :options="lkSection"/>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="mb-0">Group</label> 
                                            <Multiselect v-model="empForm.crew" searchable="true" :options="lkCrew"/>
                                        </div>
                                    </div>
                                </div>
                            </div>  <!-- End of employee detail --> 
                        </div>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="panel panel-primary tabs-style-2 mt-4" v-if="showTab">
                        <div class="tab-menu-heading">
                            <div class="tabs-menu1">
                                <ul class="nav panel-tabs main-nav-line">
                                    <li><a href="#tab1" class="nav-link active" style="padding: 4px 18px" data-bs-toggle="tab">Employee Detail</a></li>
					                <li><a href="#tab2" class="nav-link" :class="actTab" style="padding: 4px 18px" data-bs-toggle="tab">Contact Person</a></li>
					                <li><a href="#tab3" class="nav-link" :class="actTab" style="padding: 4px 18px" data-bs-toggle="tab">Bank Acount</a></li>
					                <li><a href="#tab4" class="nav-link" :class="actTab" style="padding: 4px 18px" data-bs-toggle="tab">Personal Cards</a></li>
					                <li><a href="#tab5" class="nav-link" :class="actTab" style="padding: 4px 18px" data-bs-toggle="tab">Annual Leave</a></li>
					                <li><a href="#tab6" class="nav-link" :class="actTab" style="padding: 4px 18px" data-bs-toggle="tab">Document and File</a></li>
                                </ul>
                            </div>
                        </div>
                       
                       <!-- Details -->
                        <div class="panel-body tabs-menu-body main-content-body-right" style="padding: 6px 0px">
                            <div class="tab-content">
                                <div class="tab-pane overflow-auto active" id="tab1">
                                    <div class="table-responsive border" style="max-height: 270px">
                                        <table class="table main-table-reference text-nowrap mg-b-0">
                                            <thead class="position-sticky" style="top: 0px">
                                                <tr>
                                                    <th class="border-start-0">Site</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Status</th>
                                                    <th>Position</th>
                                                    <th>Department</th>
                                                    <th>Section</th>
                                                    <th>Group</th>
                                                    <th>Employee ID</th>
                                                    <th>Scan ID</th>
                                                    <th>Food ID</th>
                                                    <th>Roster</th>
                                                    <th>Scan Times</th>
                                                    <th>Hours</th>
                                                    <th>Levels</th>
                                                    <th>contract</th>
                                                    <th>Remarks</th>
                                                    <th class="border-end-0 position-sticky wd-80p" style="right: -0.1px">Tools</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <tr class="tr-hover" v-for="lst in detailData" :key="lst.id">
                                                    <td class="border-start-0"> {{ lst.site }} </td>
                                                    <td> {{ dateTime(lst.startdate) }} </td>
                                                    <td> {{ dateTime(lst.enddate) }} </td>
                                                    <td> {{ lst.status }} </td>
                                                    <td> {{ lst.position }} </td>
                                                    <td> {{ lst.department }} </td>
                                                    <td> {{ lst.section }} </td>
                                                    <td> {{ lst.crew }} </td>
                                                    <td> {{ lst.empid }} </td>
                                                    <td> {{ lst.scanid }} </td>
                                                    <td> {{ lst.foodid }} </td>
                                                    <td> {{ lst.roster }} </td>
                                                    <td> {{ lst.scantimes }} </td>
                                                    <td> {{ lst.working_hrs }} </td>
                                                    <td> {{ lst.levels }} </td>
                                                    <td> {{ lst.contract }} </td>
                                                    <td class="laofont"> {{ lst.remarks }} </td>
                                                    <td class="p-0 align-middle border-end-0 position-sticky bg-white" style="right: -0.1px"> 
                                                        <div class="d-flex justify-content-start ps-1">
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="detailEdit(lst.id)">
                                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                            </button> 
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="detailDel(lst.id)">
                                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                            </button> 
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="detailNew()" title="Create a new item">
                                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                            <span class="text-primary tx-13">New</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- contact person -->
                                <div class="tab-pane overflow-auto" :class="actTab" id="tab2">
                                    <div class="table-responsive border" style="max-height: 270px">
                                        <table class="table main-table-reference text-nowrap mg-b-0">
                                            <thead class="position-sticky" style="top: 0px">
                                                <tr>
                                                    <th class="border-start-0">contact Person</th>
                                                    <th>relate</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th class="border-end-0 wd-60p">Tools</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <tr class="tr-hover" v-for="lst in contData" :key="lst.id">
                                                    <td class="border-start-0"> {{ lst.name }} </td>
                                                    <td> {{ lst.relate }} </td>
                                                    <td> {{ lst.phone }} </td>
                                                    <td class="laofont"> {{ lst.address }} </td>
                                                    <td class="p-0 align-middle border-end-0 bg-white">
                                                        <div class="d-flex justify-content-start ps-1">
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="contactEdit(lst.id)">
                                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                            </button> 
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="contactDel(lst.id)">
                                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                            </button> 
                                                        </div>
                                                    </td>
                                                </tr>   
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="contactNew()" title="Create a new item">
                                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                            <span class="text-primary tx-13">New</span>
                                        </div>                                                                               
                                    </div>
                                </div>
                                
                                <!-- Bank Account -->
                                <div class="tab-pane overflow-auto" :class="actTab" id="tab3">
                                    <div class="table-responsive border" style="max-height: 270px">
                                        <table class="table main-table-reference text-nowrap mg-b-0">
                                            <thead class="position-sticky" style="top: 0px">
                                                <tr>
                                                    <th class="border-start-0">Bank Name</th>
                                                    <th>Branch</th>
                                                    <th>Account Name</th>
                                                    <th>Account No</th>
                                                    <th>Remarks</th>
                                                    <th class="border-end-0 wd-60p">Tools</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <tr class="tr-hover" v-for="lst in bankData" :key="lst.id">
                                                    <td class="border-start-0"> {{ lst.bank_name }} </td>
                                                    <td> {{ lst.branch }} </td>
                                                    <td> {{ lst.acct_name }} </td>
                                                    <td> {{ lst.acct_no }} </td>
                                                    <td class="laofont"> {{ lst.remarks }} </td>
                                                    <td class="p-0 align-middle border-end-0 bg-white">
                                                        <div class="d-flex justify-content-start ps-1">
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="bankEdit(lst.id)">
                                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                            </button> 
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="bankDel(lst.id)">
                                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                            </button> 
                                                        </div>
                                                    </td>
                                                </tr>                                                                                 
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="bankNew()" title="Create a new item">
                                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                            <span class="text-primary tx-13">New</span>
                                        </div> 
                                    </div>
                                </div>

                                <!-- Personal cards -->
                                <div class="tab-pane overflow-auto" :class="actTab" id="tab4">
                                    <div class="table-responsive border" style="max-height: 270px">
                                        <table class="table main-table-reference text-nowrap mg-b-0">
                                            <thead class="position-sticky" style="top: 0px">
                                                <tr>
                                                    <th class="border-start-0">Card Type</th>
                                                    <th>Card ID</th>
                                                    <th>Expire Date</th>
                                                    <th>Remarks</th>
                                                    <th class="border-end-0 wd-60p">Tools</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <tr class="tr-hover" v-for="lst in cardData" :key="lst.id">
                                                    <td class="border-start-0"> {{ lst.cardtype }} </td>
                                                    <td> {{ lst.cardid }} </td>
                                                    <td> {{ lst.expiredate }} </td>
                                                    <td class="laofont"> {{ lst.remarks }} </td>
                                                    <td class="p-0 align-middle border-end-0 bg-white">
                                                        <div class="d-flex justify-content-start ps-1">
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="cardEdit(lst.id)">
                                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                            </button> 
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="cardDel(lst.id)">
                                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                            </button> 
                                                        </div>
                                                    </td>
                                                </tr>                                                                                 
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="cardNew()" title="Create a new item">
                                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                            <span class="text-primary tx-13">New</span>
                                        </div> 
                                    </div>
                                </div>

                                <!-- Annual Leave -->
                                <div class="tab-pane overflow-auto" :class="actTab" id="tab5">
                                    <div class="table-responsive border" style="max-height: 270px">
                                        <table class="table main-table-reference text-nowrap mg-b-0">
                                            <thead class="position-sticky" style="top: 0px">
                                                <tr>
                                                    <th class="border-start-0">Year</th>
                                                    <th>Remain</th>
                                                    <th>Remarks</th>
                                                    <th class="border-end-0 wd-60p">Tools</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <tr class="tr-hover" v-for="lst in alData" :key="lst.id">
                                                    <td class="border-start-0"> {{ lst.years }} </td>
                                                    <td> {{ formatNumber2(lst.remain) }} </td>
                                                    <td class="laofont"> {{ lst.remarks }} </td>
                                                    <td class="p-0 align-middle border-end-0 bg-white">
                                                        <div class="d-flex justify-content-start ps-1">
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="alEdit(lst.id)">
                                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                            </button> 
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="alDel(lst.id)">
                                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                            </button> 
                                                        </div>
                                                    </td>
                                                </tr>                                                                                  
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="alNew()" title="Create a new item">
                                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                            <span class="text-primary tx-13">New</span>
                                        </div> 
                                    </div>
                                </div>

                                <!-- Document and Files -->
                                <div class="tab-pane overflow-auto" :class="actTab" id="tab6">
                                    <div class="table-responsive border" style="max-height: 270px">
                                        <table class="table main-table-reference text-nowrap mg-b-0">
                                            <thead class="position-sticky" style="top: 0px">
                                                <tr>
                                                    <th class="border-start-0">File Name</th>
                                                    <th class="border-end-0 wd-80p">Tools</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <tr class="tr-hover" v-for="lst in fileData" :key="lst.id">
                                                    <td class="border-start-0"> {{ lst.filename }} </td>
                                                    <td class="p-0 align-middle border-end-0 bg-white">
                                                        <div class="d-flex justify-content-start ps-1">
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Download file" @click="fileDownload(lst.filename)">
                                                                <i class="bx bxs-download text-primary" style="font-size: 16px"></i>
                                                            </button> 
                                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete file" @click="fileDel(lst.filename)">
                                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                            </button> 
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="fileNew()" title="Create a new item">
                                            <div><i class="fa fa-plus me-1 text-primary tx-10 mb-2"></i></div>
                                                <span class="text-primary tx-13">New</span>
                                            <input class="d-none" ref="fileInput2" type="file" multiple  @change="fileAdd()">
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- EndTabs -->

                    <div class="d-flex justify-content-end mt-2 pb-2">
                        <button v-if="btnEmpUpd" type="button" class="btn btn-primary" :class="btnEmpUpdShow" @click="empUpdate()"><i class="fe fe-save"></i> 
                            <span class="mx-1">Save</span>
                        </button>
                        <button v-if="btnEmpAdd" type="button" class="btn btn-primary" :class="btnEmpAddShow" @click="empAdd()"><i class="fe fe-plus"></i> 
                            <span class="mx-1">Add</span>
                        </button>
                        <button type="button" class="btn btn-secondary ms-1" @click="empCancel()"><i class="fe fe-x"></i> 
                            <span class="mx-1">Close</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Modal employee preview -->
            <div class="modal fade effect-scale" id="empPreview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="empPreviewLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl"> <!-- modal-dialog-centered  -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <!-- <h5 class="modal-title" id="staticBackdropLabel">Sign In</h5> -->
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                            <h6 class="modal-title text-muted">Employee Info.</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>

                        </div>
                        <div class="modal-body">
                            <h1>Preview</h1>
                            {{ lkCountry }}

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Insert Data</button>
                        </div>
                    </div>
                </div>                                              
            </div> <!-- End of modal employee preview -->

            <!-- Modal add edit detail -->
            <div class="modal fade effect-scale" id="detailAddEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detailAddEditLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-muted">Employee details</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <!-- Row1 -->
                            <div class="row">
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Site  <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="empForm.site" :options="lkSite"/>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Start Date  <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" v-model="empForm.startdate">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">End Date</label>
                                        <input type="date" class="form-control" v-model="empForm.enddate">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Status  <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="empForm.status" :options="lkStatus"/>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6">
                                    <div class="form-group">
                                        <label class="mb-0">Position  <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="empForm.position" searchable="true" searchStart="true" :options="lkPosition"/>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6">
                                    <div class="form-group">
                                        <label class="mb-0">Department  <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="empForm.dept" searchable="true" searchStart="true" :options="lkDept"/>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6">
                                    <div class="form-group">
                                        <label class="mb-0">Section <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="empForm.section" searchable="true" searchStart="true" :options="lkSection"/>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Group</label> 
                                        <Multiselect v-model="empForm.crew" searchable="true" :options="lkCrew"/>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Employee ID</label>
                                        <input type="text" class="form-control" v-model="empForm.empid">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Scan ID <span class=" text-danger">*</span></label>
                                        <input type="number" class="form-control" v-model="empForm.scanid">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Food ID</label>
                                        <input type="text" class="form-control" v-model="empForm.foodid">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Roster  <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="empForm.roster" :options="lkRoster"/>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Scan Times  <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="empForm.scantimes" :options="lkScantimes"/>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Working hrs  <span class=" text-danger">*</span></label>
                                        <input type="number" class="form-control" v-model="empForm.hours">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Levels</label> 
                                        <Multiselect v-model="empForm.levels" :options="lkLevels"/>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6">
                                    <div class="form-group">
                                        <label class="mb-0">Contact Type</label> 
                                        <Multiselect v-model="empForm.contract" :options="lkContract"/>
                                    </div>
                                </div>
                                <div class="col-xl-10">
                                    <div class="form-group">
                                        <label class="mb-0">Remarks</label>
                                        <input type="text" class="form-control" v-model="empForm.remarks">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button v-if="btnDetailUpd" type="button" class="btn btn-primary me-1" :class="btnDetailShow" @click="detailUpdate()"><i class="fe fe-save"></i> 
                                    <span class="mx-1">Save</span>
                                </button>
                                <button v-if="btnDetailAdd" type="button" class="btn btn-primary me-1" :class="btnDetailShow" @click="detailAdd()"><i class="fe fe-plus"></i> 
                                    <span class="mx-1">Add</span>
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                                    <span class="mx-1">Close</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div> <!-- End of modal add edit detail -->

            <!-- Modal contact -->
            <div class="modal fade effect-scale" id="contact" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="contactLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-muted">Contact Person</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-7 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="contForm.name">
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">relate <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="contForm.relate" searchable="true" searchStart="true" :options="lkRelate"/>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Phone  <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="contForm.phone">
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Address</label>
                                        <input type="text" class="form-control" v-model="contForm.address">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button v-if="btnContUpd" type="button" class="btn btn-primary me-1" :class="btnContAddShow" @click="contactUpdate()"><i class="fe fe-save"></i> 
                                    <span class="mx-1">Save</span>
                                </button>
                                <button v-if="btnContAdd" type="button" class="btn btn-primary me-1" :class="btnContAddShow" @click="contactAdd()"><i class="fe fe-plus"></i> 
                                    <span class="mx-1">Add</span>
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                                    <span class="mx-1">Close</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div> <!-- End of modal contact -->

            <!-- Modal bank info -->
            <div class="modal fade effect-scale" id="bank" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="bankLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-muted">Bank Account</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Bank Name <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="bankForm.bankname" searchable="true"  :options="lkBank"/>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Branch</label>
                                        <input type="text" class="form-control" v-model="bankForm.branch">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Account Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="bankForm.acctname">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Account No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="bankForm.acctno">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="mb-0">Remarks</label>
                                        <input type="text" class="form-control" v-model="bankForm.remarks">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button v-if="btnBankUpd" type="button" class="btn btn-primary me-1" :class="btnBankAddShow" @click="bankUpdate()"><i class="fe fe-save"></i> 
                                    <span class="mx-1">Save</span>
                                </button>
                                <button v-if="btnBankAdd" type="button" class="btn btn-primary me-1" :class="btnBankAddShow" @click="bankAdd()"><i class="fe fe-plus"></i> 
                                    <span class="mx-1">Add</span>
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                                    <span class="mx-1">Close</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div> <!-- End of modal contact -->

            <!-- Modal Personal Cards -->
            <div class="modal fade" id="cards" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cardLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-muted">Personal Cards</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Card Type <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="cardForm.cardtype" :options="lkCard"/>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Card ID <span class=" text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="cardForm.cardid">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Expire Date</label>
                                        <input type="date" class="form-control" v-model="cardForm.expire">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="mb-0">Remarks</label>
                                        <input type="text" class="form-control" v-model="cardForm.remarks">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button v-if="btnCardUpd" type="button" class="btn btn-primary me-1" :class="btnCardAddShow" @click="cardUpdate()"><i class="fe fe-save"></i> 
                                    <span class="mx-1">Save</span>
                                </button>
                                <button v-if="btnCardAdd" type="button" class="btn btn-primary me-1" :class="btnCardAddShow" @click="cardAdd()"><i class="fe fe-plus"></i> 
                                    <span class="mx-1">Add</span>
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                                    <span class="mx-1">Close</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div>

            <!-- Modal Annual Leave -->
            <div class="modal fade effect-scale" id="al" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="alLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-muted">Annual Leave</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Year <span class=" text-danger">*</span></label> 
                                        <Multiselect v-model="alForm.years" :options="lkYear"/>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        <label class="mb-0">Remain <span class=" text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="alForm.remain">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0">Remarks</label>
                                        <input type="text" class="form-control" v-model="alForm.remarks">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button v-if="btnAlUpd" type="button" class="btn btn-primary me-1" :class="btnAlAddShow" @click="alUpdate()"><i class="fe fe-save"></i> 
                                    <span class="mx-1">Save</span>
                                </button>
                                <button v-if="btnAlAdd" type="button" class="btn btn-primary me-1" :class="btnAlAddShow" @click="alAdd()"><i class="fe fe-plus"></i> 
                                    <span class="mx-1">Add</span>
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                                    <span class="mx-1">Close</span>
                                </button>
                            </div>
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
    name: 'XpplAppEmployee',

    data() {
        return {
            lkCountry: [],
            lkProv: [],
            lkDist: [],
            lkPosition: [],
            lkStatus: [],
            lkContract: [],
            lkLevels: [],
            lkRoster: [],
            lkScantimes: [],
            lkSite: [],
            lkDept: [],
            lkSection: [],
            lkCrew: [],
            lkRelate: [],
            lkBank: [],
            lkCard: [],
            lkYear: [],

            detailData: [],
            empData: [],
            contData: [],
            bankData: [],
            cardData: [],
            alData: [],
            fileData: [],
            loginPermiss: [],
            curEmpData: [],
            exEmpData: [],

            showList: true,
            showAdd: '',
            showDetail: '',
            showTab: '',
            search: '',
            btnClear: false,
            btnEmpUpd: '',
            btnEmpAdd: '',
            btnDetailUpd: '',
            btnDetailAdd: '',
            btnContUpd: '',
            btnContAdd: '',
            btnBankUpd: '',
            btnBankAdd: '',
            btnCardUpd: '',
            btnCardAdd: '',
            btnAlUpd: '',
            btnAlAdd: '',
            actTab: '',
            loginDept: '',

            photoPrev: window.location.origin + '/assets/img/no.jpg',
            empForm: { id: '', userid: '', gender: 'Male', name: '', surname: '', namelao: '', surnamelao: '', phone: '', birthday: '', email: '', country: null, province: null, district: '', village: '', startdate: '', enddate: '', position: null, status: 'Current', contract: null, levels: null, empid: '', scanid: '', foodid: '', roster: null, scantimes: null, hours: '9', site: null, dept: null, section: null, crew: null, photo: '', remarks: ''},
            contForm: { id: '', userid: '', name: '', relate: '', phone: '', address: '' },
            bankForm: { id: '', userid: '', bankname: '', branch: '', acctname: '', acctno: '', remarks: '' },
            cardForm: { id: '', userid: '', cardtype: '', cardid: '', expire: '', remarks: '' },
            alForm: { id: '', userid: '', years: '', remain: '0', remarks: '' },
            
            loading: false,
        };
    },

    mounted() {
        
    },

    computed:{
        btnEmpAddShow(){
            let f = this.empForm;
            if(f.name == '' || f.namelao == '' || f.country == null || f.startdate == '' || f.scanid == '' || f.roster == null || f.scantimes == null || f.hours == '' || f.site == null || f.dept == null || f.section == null || f.position == null){
                return 'disabled'
            } else {
                return ''
            }
        },
        
        btnEmpUpdShow(){
            if (this.empForm.name == '' || this.empForm.namelao == '' || this.empForm.country == null){
                return 'disabled'
            } else {
                return ''
            }
        },

        btnDetailShow(){
            let f = this.empForm;
            if (f.site == null || f.startdate == '' || f.status == null || f.scanid == '' || f.roster == null || f.scantimes == null || f.hours == '' || f.dept == null || f.position == null || f.section == null){
                return 'disabled'
            } else {
                return ''
            }
        },

        btnContAddShow(){
            if (this.contForm.name == '' || this.contForm.relate == null || this.contForm.phone == ''){
                return 'disabled'
            } else {
                return ''
            }
        },

        btnBankAddShow(){
            if (this.bankForm.bankname == null || this.bankForm.acctname == '' || this.bankForm.acctno == ''){
                return 'disabled'
            } else {
                return ''
            }
        },

        btnCardAddShow(){
            if (this.cardForm.cardtype == null || this.cardForm.cardid == ''){
                return 'disabled'
            } else {
                return ''
            }
        },

        btnAlAddShow(){
            if (this.alForm.years == null || this.alForm.remain == ''){
                return 'disabled'
            } else {
                return ''
            }
        }
    },

    methods: {    
        async getEmpData(page){
			const response = await axios.get(`/api/employee?page=${page}&dept=${this.loginDept}&permiss=${this.loginPermiss.emp_all}&search=${this.search}`)
            this.empData = response.data;
            
            if (response.data[0].photo){
                this.photoPrev = window.location.origin + '/assets/img/profile/' + response.data[0].photo
            } else {
                this.photoPrev = window.location.origin + '/assets/img/no.jpg'
            }
        },

        async getEmpOnload(){
            const emp = await axios.get(`/api/employee?page=${1}&dept=${this.loginDept}&permiss=${this.loginPermiss.emp_all}&search=${this.search}`)
            this.empData = emp.data;
            
            if (emp.data[0].photo){
                this.photoPrev = window.location.origin + '/assets/img/profile/' + emp.data[0].photo
            } else {
                this.photoPrev = window.location.origin + '/assets/img/no.jpg'
            }
        },

        empNew(){
            if(this.loginPermiss.emp_add == 0){
                this.$swal.fire({
                    text: "You don't have permission to add employee!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.showList = false;
                this.showAdd = true;
                this.showDetail = true;
                this.showTab = false;
                this.btnEmpUpd = false;
                this.btnEmpAdd = true;
                this.empFormClear();
                this.detailFormClear();
            }
        },

        empPrev(id){
            $('#empPreview').modal('show');

            this.$axios.get('/sanctum/csrf-cookie').then((response)=>{
                this.$axios.post(`/api/employee/preview/${id}`)
                .then((response)=>{
                    this.prevData = response.data;
                }).catch((error)=>{
                    console.log(error);
                })
            });
        },

       async empEdit(id){
            if(this.loginPermiss.emp_edit == 0){
                this.$swal.fire({
                    text: "You don't have permission to edit employee!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {

                this.empFormClear();
                const response = await axios.post(`/api/employee/edit/${id}`)
                    this.userid = response.data.id;

                    this.empForm.userid = response.data.id;
                    this.contForm.userid = response.data.id;
                    this.bankForm.userid = response.data.id;
                    this.cardForm.userid = response.data.id;
                    this.alForm.userid = response.data.id;

                    this.empForm.gender = response.data.gender;
                    this.empForm.name = response.data.name;
                    this.empForm.surname = response.data.surname;
                    this.empForm.phone = response.data.phone;
                    this.empForm.namelao = response.data.namelao;
                    this.empForm.surnamelao = response.data.surnamelao;
                    this.empForm.birthday = response.data.birthday;
                    this.empForm.email = response.data.email;
                    this.empForm.country = response.data.country;
                    this.empForm.province = response.data.province;
                    this.empForm.district = response.data.district;
                    this.empForm.village = response.data.village;

                    if (response.data.photo){
                        this.photoPrev = window.location.origin + '/assets/img/profile/' + response.data.photo
                    } else {
                        this.photoPrev = window.location.origin + '/assets/img/no.jpg'
                    }

                    this.getDistrict()
                    this.getDetail(id);
                    this.getContact(id);
                    this.getBank(id);
                    this.getCard(id);
                    this.getAl(id);
                    this.getFiles(id);

                    this.actTab = '';
                    this.showList = false;
                    this.showAdd = true;
                    this.showDetail = false;
                    this.showTab = true;
                    this.btnEmpAdd = false;
                    this.btnEmpUpd = true;

            }
        },

        empCancel(){ 
            this.showList = true;
            this.showAdd = false;
        },

        empAdd(){
            let fd = new FormData();
                fd.append('gender', this.empForm.gender);
                fd.append('name', this.empForm.name);
                fd.append('surname', this.empForm.surname);
                fd.append('namelao', this.empForm.namelao);
                fd.append('surnamelao', this.empForm.surnamelao);
                fd.append('phone', this.empForm.phone);
                fd.append('birthday', this.empForm.birthday);
                fd.append('email', this.empForm.email);
                fd.append('country', this.empForm.country);
                fd.append('province', this.empForm.province);
                fd.append('district', this.empForm.district);
                fd.append('village', this.empForm.village);
                fd.append('startdate', this.empForm.startdate);
                fd.append('enddate', this.empForm.enddate);
                fd.append('position', this.empForm.position);
                fd.append('status', this.empForm.status);
                fd.append('contact', this.empForm.contract);
                fd.append('levels', this.empForm.levels);
                fd.append('empid', this.empForm.empid);
                fd.append('scanid', this.empForm.scanid);
                fd.append('foodid', this.empForm.foodid);
                fd.append('roster', this.empForm.roster);
                fd.append('scantimes', this.empForm.scantimes);
                fd.append('hours', this.empForm.hours);
                fd.append('site', this.empForm.site);
                fd.append('dept', this.empForm.dept);
                fd.append('section', this.empForm.section);
                fd.append('crew', this.empForm.crew);
                fd.append('photo', this.empForm.photo);

            this.$axios.get("/sanctum/csrf-cookie").then((response)=>{
                this.$axios.post('/api/employee/add', fd, {headers:{"Content-Type": "multipart/form-date"}})
                .then((response)=>{
                    if(response.data.success){

                        this.getEmpData();
                        this.showAdd = false;
                        this.showList = true;

                    } else {
                        alert(response.data.message)
                    }
                }).catch((error)=>{
                    console.log(error);
                })
            });
        },

        empUpdate(){
            this.$swal.fire({
                text: "Do you want to save the changes?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fe fe-save"></i> <span class="font-weight-light">Save</span>',
                cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
                allowEnterKey: false,
                allowOutsideClick: false,
            }).then((result)=>{
                if(result.isConfirmed){
                    let fd = new FormData();
                        fd.append('id', this.userid);
                        fd.append('gender', this.empForm.gender);
                        fd.append('name', this.empForm.name);
                        fd.append('surname', this.empForm.surname);
                        fd.append('namelao', this.empForm.namelao);
                        fd.append('surnamelao', this.empForm.surnamelao);
                        fd.append('phone', this.empForm.phone);
                        fd.append('birthday', this.empForm.birthday);
                        fd.append('email', this.empForm.email);
                        fd.append('country', this.empForm.country);
                        fd.append('province', this.empForm.province);
                        fd.append('district', this.empForm.district);
                        fd.append('village', this.empForm.village);
                        fd.append('photo', this.empForm.photo);
                    
                    this.$axios.get("/sanctum/csrf-cookie").then((response)=>{
                        this.$axios.post('/api/employee/update', fd, {headers:{"Content-Type": "multipart/form-date"}})
                        .then((response)=>{

                            this.getEmpData();
                            this.showAdd = false;
                            this.showList = true;

                        }).catch((error)=>{
                            console.log(error);
                        })
                    });
                }
            });
        },

        empDel(id){
            if (this.loginPermiss.emp_del == 0){
                this.$swal.fire({
                    text: "You don't have permission to delete employee!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.$swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fe fe-trash-2"></i> <span class="font-weight-light">Delete</span>',
                    cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
                    confirmButtonColor: '#d33',
                    allowEnterKey: false,
                    allowOutsideClick: false,
                }).then((result)=>{
                    if(result.isConfirmed){
                        this.$axios.get("/sanctum/csrf-cookie").then((response)=>{
                            this.$axios.post(`/api/employee/delete/${id}`)
                            .then((response)=>{

                                this.search = '';
                                this.btnClear = false;
                                this.getEmpData();

                                this.$swal.fire({
                                    title: 'Deleted!',
                                    text: 'The employee has been deleted.',
                                    icon: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: true,
                                    timer: 1500
                                })
                            }).catch((error)=>{
                                console.log(error);
                            })
                        })
                    }
                });
            }
        },

        async getDetail(id){
            const response = await axios.post(`/api/detail/data/${id}`)
            this.detailData = response.data;
        },

        detailNew(){
            $('#detailAddEdit').modal('show');
            this.detailFormClear();
            this.btnDetailUpd = false;
            this.btnDetailAdd = true;

        },

        detailAdd(){
            this.$axios.post('/api/detail/add', this.empForm)
            .then((response)=>{
                if(response.data.success){

                    $('#detailAddEdit').modal('hide');
                    this.getDetail(this.empForm.userid);

                } else {
                    alert(response.data.message)
                }
            }).catch((error)=>{
                console.log(error);
            })
        },

        detailEdit(id){
            $('#detailAddEdit').modal('show');
            this.$axios.post(`/api/detail/edit/${id}`)
            .then((response)=>{

                this.detailFormClear();
                this.btnDetailAdd = false;
                this.btnDetailUpd = true;

                this.empForm.id = response.data.id;
                this.empForm.site = response.data.site;
                this.empForm.startdate = response.data.startdate;
                this.empForm.enddate = response.data.enddate;
                this.empForm.status = response.data.status;
                this.empForm.position = response.data.position;
                this.empForm.dept = response.data.department;
                this.empForm.section = response.data.section;
                this.empForm.crew = response.data.crew;
                this.empForm.empid = response.data.empid;
                this.empForm.scanid = response.data.scanid;
                this.empForm.foodid = response.data.foodid;
                this.empForm.roster = response.data.roster;
                this.empForm.scantimes = response.data.scantimes;
                this.empForm.hours = response.data.working_hrs;
                this.empForm.levels = response.data.levels;
                this.empForm.contract = response.data.contract;
                this.empForm.remarks = response.data.remarks;

            }).catch((error)=>{
                console.log(error);
            })
        },

        detailUpdate(){
            this.$axios.post('/api/detail/update', this.empForm)
            .then((response)=>{

                $('#detailAddEdit').modal('hide');
                this.getDetail(this.empForm.userid);

            }).catch((error)=>{
                console.log(error);
            })
        },

        detailDel(id){
            if (this.loginPermiss.detail_del == 0){
                this.$swal.fire({
                    text: "You don't have permission to delete!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
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
                        this.$axios.get("/sanctum/csrf-cookie").then((response)=>{
                            this.$axios.post(`/api/detail/delete/${id}`)
                            .then((response)=>{

                                this.getDetail(this.userid);

                            }).catch((error)=>{
                                console.log(error);
                            })
                        })
                    }
                });
            }

        },

        async getContact(id){
            const response = await axios.post(`/api/contact/data/${id}`)
            this.contData = response.data;
        },

        contactNew(){
            $('#contact').modal('show');
            this.contFormClear();
            this.getRelateLK();
            this.btnContUpd = false;
            this.btnContAdd = true;
        },

        contactAdd(){
            this.$axios.post('/api/contact/add', this.contForm)
            .then((res) => {
                 if(res.data.success){

                    $('#contact').modal('hide');
                    this.getContact(this.contForm.userid);

                } else {
                    alert(res.data.message)
                }
            }).catch ((err) =>{
                console.log(err);
            })
        },

        contactEdit(id){
            $('#contact').modal('show');
            this.$axios.post(`/api/contact/edit/${id}`)
            .then((res)=>{

                this.contFormClear();
                this.getRelateLK();
                this.btnContAdd = false;
                this.btnContUpd = true;

                this.contForm.id = res.data.id;
                this.contForm.name = res.data.name;
                this.contForm.relate = res.data.relate;
                this.contForm.phone = res.data.phone;
                this.contForm.address = res.data.address;

            }).catch((error)=>{
                console.log(error);
            })
        },

        contactUpdate(){
            this.$axios.post('/api/contact/update', this.contForm)
            .then((response)=>{

                $('#contact').modal('hide');
                this.getContact(this.contForm.userid);

            }).catch((error)=>{
                console.log(error);
            })
        },

        contactDel(id){
            if (this.loginPermiss.cont_del == 0){
                this.$swal.fire({
                    text: "You don't have permission to delete!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
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
                        this.$axios.post(`/api/contact/delete/${id}`)
                        .then((response)=>{

                            this.getContact(this.userid);

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        async getBank(id){
            const response = await axios.post(`/api/bank/data/${id}`)
            this.bankData = response.data;
        },

        bankNew(){
            $('#bank').modal('show');
            this.bankFormClear();
            this.getBankLK();
            this.btnBankUpd = false;
            this.btnBankAdd = true;
        },

        bankAdd(){
            this.$axios.post('/api/bank/add', this.bankForm)
            .then((res) => {
                 if(res.data.success){

                    $('#bank').modal('hide');
                    this.getBank(this.userid);

                } else {
                    alert(res.data.message)
                }
            }).catch ((err) => {
                console.log(err);
            })
        },

        bankEdit(id){
            $('#bank').modal('show');
            this.$axios.post(`/api/bank/edit/${id}`)
            .then((res)=>{

                this.bankFormClear();
                this.getBankLK();
                this.btnBankAdd = false;
                this.btnBankUpd = true;

                this.bankForm.id = res.data.id;
                this.bankForm.bankname = res.data.bank_name;
                this.bankForm.branch = res.data.branch;
                this.bankForm.acctname = res.data.acct_name;
                this.bankForm.acctno = res.data.acct_no;
                this.bankForm.remarks = res.data.remarks;

            }).catch((error)=>{
                console.log(error);
            })
        },

        bankUpdate(){
            this.$axios.post('/api/bank/update', this.bankForm)
            .then((response)=>{

                $('#bank').modal('hide');
                this.getBank(this.userid);

            }).catch((error)=>{
                console.log(error);
            })
        },

        bankDel(id){
            if (this.loginPermiss.bank_del == 0){
                this.$swal.fire({
                    text: "You don't have permission to delete!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
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
                        this.$axios.post(`/api/bank/delete/${id}`)
                        .then((response)=>{

                            this.getBank(this.userid);

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        async getCard(id){
            const response = await axios.post(`/api/card/data/${id}`)
            this.cardData = response.data;
        },

        cardNew(){
            $('#cards').modal('show');
            this.cardFormClear();
            this.getCardLK();
            this.btnCardUpd = false;
            this.btnCardAdd = true;
        },
        
        cardAdd(){
            this.$axios.post('/api/card/add', this.cardForm)
            .then((res) => {
                 if(res.data.success){
                    $('#cards').modal('hide');
                    this.getCard(this.cardForm.userid);
                } else {
                    alert(res.data.message)
                }
            }).catch ((err) =>{
                console.log(err);
            })
        },

        cardEdit(id){
            $('#cards').modal('show');
            this.$axios.post(`/api/card/edit/${id}`)
            .then((res)=>{

                this.cardFormClear();
                this.getCardLK();
                this.btnCardAdd = false;
                this.btnCardUpd = true;

                this.cardForm.id = res.data.id;
                this.cardForm.cardtype = res.data.cardtype;
                this.cardForm.cardid = res.data.cardid;
                this.cardForm.expire = res.data.expiredate;
                this.cardForm.remarks = res.data.remarks;

            }).catch((error)=>{
                console.log(error);
            })
        },

        cardUpdate(){
            this.$axios.post('/api/card/update', this.cardForm)
            .then((response)=>{
                $('#cards').modal('hide');
                this.getCard(this.cardForm.userid);
            }).catch((error)=>{
                console.log(error);
            })
        },

        cardDel(id){
            if (this.loginPermiss.card_del == 0){
                this.$swal.fire({
                    text: "You don't have permission to delete!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
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
                        this.$axios.post(`/api/card/delete/${id}`)
                        .then((response)=>{

                            this.getCard(this.cardForm.userid);

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        async getAl(id){
            const response = await axios.post(`/api/annual/data/${id}`)
            this.alData = response.data;
        },

        alNew(){
            $('#al').modal('show');
            this.alFormClear();
            this.getYearLK();
            this.btnAlUpd = false;
            this.btnAlAdd = true;
        },
        
        alAdd(){
            this.$axios.post('/api/annual/add', this.alForm)
            .then((res) => {
                if(res.data.success){

                    $('#al').modal('hide');
                    this.getAl(this.alForm.userid);

                } else {
                    alert(res.data.message)
                }
            }).catch ((err) =>{
                console.log(err);
            })
        },

        alEdit(id){
            $('#al').modal('show');
            this.$axios.post(`/api/annual/edit/${id}`)
            .then((res)=>{

                this.alFormClear();
                this.getYearLK();
                this.btnAlAdd = false;
                this.btnAlUpd = true;

                this.alForm.id = res.data.id;
                this.alForm.years = res.data.years;
                this.alForm.remain = res.data.remain;
                this.alForm.remarks = res.data.remarks;

            }).catch((error)=>{
                console.log(error);
            })
        },

        alUpdate(){
            this.$axios.post('/api/annual/update', this.alForm)
            .then((response)=>{

                $('#al').modal('hide');
                this.getAl(this.alForm.userid);

            }).catch((error)=>{
                console.log(error);
            })
        },

        alDel(id){
            if (this.loginPermiss.al_del == 0){
                this.$swal.fire({
                    text: "You don't have permission to delete!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
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
                        this.$axios.post(`/api/annual/delete/${id}`)
                        .then((response)=>{

                            this.getAl(this.alForm.userid);

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        async getFiles(id){
            const response = await axios.post(`/api/file/data/${id}`)
            this.fileData = response.data;
        },


        fileNew(){
            this.$refs.fileInput2.click();
        },

        fileAdd(){
            let fd = new FormData();
            for (let i = 0; i < this.$refs.fileInput2.files.length; i++){
                
                let file = this.$refs.fileInput2.files[i];
                    fd.append('files[' + i + ']', file);
                    fd.append('userid', this.empForm.userid);
            }
            this.$swal.fire({
                text: "Do you want to add selected file?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fe fe-plus"></i> <span class="font-weight-light">Add</span>',
                cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
                allowEnterKey: false,
                allowOutsideClick: false,
            }).then((result)=>{
                if(result.isConfirmed){
                    this.$axios.post('/api/file/add', fd, {headers:{"Content-Type": "multipart/form-date"}})
                    .then(response => {   

                            this.getFiles(this.empForm.userid);

                    }).catch (err => {
                        console.log(err);
                    });
                }
            });
        },
        
        fileDownload(file){
            const url = `/api/file/download/${file}`;
            window.location.href = url;
        },

        fileDel(file){
            if (this.loginPermiss.file_del == 0){
                this.$swal.fire({
                    text: "You don't have permission to delete!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
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
                        this.$axios.post(`api/file/delete/${file}`)
                        .then((response)=>{

                            this.getFiles(this.empForm.userid);

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        async searchChange(){
            if(this.search.length >0){
                this.btnClear = true;
                const data = await this.getEmpData();
            } else {
                this.btnClear = false;
                const data = await this.getEmpData();
            }
        },

        searchClear(){
            this.search = '';
            this.btnClear = false,
            this.getEmpData();
        },

        chooseImage(){
            this.$refs.fileInput.click()
        },

        onSeclected(event){
            this.empForm.photo = event.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(this.empForm.photo);
            reader.addEventListener("load", function(){
                this.photoPrev = reader.result;
            }.bind(this), false);
		},

        empFormClear(){
            this.lkDist =[];
            let f = this.empForm;
                f.gender = 'Male';
                f.name = '';
                f.surname = '';
                f.namelao = '';
                f.surnamelao = '';
                f.phone = '';
                f.birthday = '';
                f.email = '';
                f.country = null;
                f.province = null;
                f.district = '';
                f.village = '';
                f.photo = '';
                this.photoPrev = window.location.origin + '/assets/img/no.jpg'
        },

        detailFormClear(){
            let f = this.empForm;
                f.startdate = '';
                f.enddate = '';
                f.position = null;
                f.contract = null;
                f.levels = null;
                f.empid = '';
                f.scanid = '';
                f.foodid = '';
                f.roster = null;
                f.scantimes = null;
                f.hours = '9';
                f.site = null;
                f.dept = null;
                f.section = null;
                f.crew = null;
                f.remarks = '';
        },

        contFormClear(){
            let f = this.contForm;
                f.name = '';
                f.relate = null;
                f.phone = '';
                f.address = '';
        },

        bankFormClear(){
            let f = this.bankForm;
                f.bankname = '';
                f.branch = '';
                f.acctname = '';
                f.acctno = '';
                f.remarks = '';
        },

        cardFormClear(){
            let f = this.cardForm;
                f.cardtype = '';
                f.cardid = '';
                f.expire = '';
                f.remarks = '';
        },

        alFormClear(){
            let f = this.alForm;
                f.years = '';
                f.remarks = '';
        },

        async onLoad(){
            this.loading = true;

            const permiss = await axios.get('/api/permiss')
            this.loginPermiss = permiss.data;

            const profile = await axios.get('api/profile')
			this.loginDept = profile.data.department;

            this.getEmpOnload();

            this.loading = false;

            const country = await axios.get('/api/lookup/country')
            this.lkCountry = country.data;

            const prov = await axios.get('/api/lookup/province')
            this.lkProv = prov.data;

            const position = await axios.get('/api/lookup/position')
            this.lkPosition = position.data;

            const status = await axios.get('/api/lookup/status')
            this.lkStatus = status.data;

            const contract = await axios.get('/api/lookup/contract')
            this.lkContract = contract.data;
            
            const levels = await axios.get('/api/lookup/levels')
            this.lkLevels = levels.data;
            
            const roster = await axios.get('/api/lookup/roster')
            this.lkRoster = roster.data;
            
            const scantimes = await axios.get('/api/lookup/scantimes')
            this.lkScantimes = scantimes.data;
            
            const site = await axios.get('/api/lookup/site')
            this.lkSite = site.data;
            
            const dept = await axios.get('/api/lookup/dept')
            this.lkDept = dept.data;
            
            const section = await axios.get('/api/lookup/section')
            this.lkSection = section.data;
            
            const crew = await axios.get('/api/lookup/crew')
            this.lkCrew = crew.data;

           

        },

        async expCurrentEmp(){

            const curEmp = await axios.get(`/api/employee/currentemp?dept=${this.loginDept}`)
            this.curEmpData = curEmp.data;
 
            const data = this.curEmpData;
            const fileName = 'CurrentEmployee';
            const exportType = exportXlsFile.types.xls
            exportXlsFile({ data, fileName, exportType })
        },

        async expExEmp(){

            const exEmp = await axios.get(`/api/employee/exemp?dept=${this.loginDept}`)
            this.exEmpData = exEmp.data;
 
            const data = this.exEmpData;
            const fileName = 'Ex-Employee';
            const exportType = exportXlsFile.types.xls
            exportXlsFile({ data, fileName, exportType })
        },

        getDistrict(){
            this.$axios.post('/api/lookup/district', {prov: this.empForm.province})
            .then(res => this.lkDist = res.data)
        },

        getRelateLK(){
            this.$axios.get('/api/lookup/relate').then((res) => {
                this.lkRelate = res.data;
            })
        },

        getBankLK(){
            this.$axios.get('/api/lookup/bank').then((res) => {
                this.lkBank = res.data;
            })
        },

        getCardLK(){
            this.$axios.get('/api/lookup/card').then((res) => {
                this.lkCard = res.data;
            })
        },

        getYearLK(){
            this.$axios.get('/api/lookup/year').then((res) => {
                this.lkYear = res.data;
            })
        },

        formatNumber(value) {
			let val = (value / 1).toFixed(0).replace(",", ".");
			return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		},

        formatNumber2(value) {
			let val = (value / 1).toFixed(2).replace(",", ".");
			return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		},

        timeago(value){
            if(value){
                return moment(value).fromNow();
            }
        },

        dateTime(value) {
            if(value) {
                return moment(value).format('DD-MM-YYYY');
            }
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

</style>