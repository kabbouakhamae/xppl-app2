<template>
    <div v-if="loading">
        <loading/>
    </div> 
    <div v-else>
        <div class="card">
            <div v-if="list">
                <div class="card-header pd-r-15 pd-t-10 pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex justify-content-start align-items-center">
                            <h4 class="card-title mg-b-0 text-muted">Medical Records</h4>
                        </div>
                        <div class="d-flex justify-content-start">
                            <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                                <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
                            </button> 
                            <div class="dropdown-menu rounded-5 box-shadow-pink tx-13">
                                <div class="dropdown-item cur-pointer dropdown-hover" @click="ShowCol()">
                                    <i class="mdi mdi-eye me-2 tx-16"></i><span>Show Column</span>
                                </div>
                                <div class="dropdown-item cur-pointer dropdown-hover" @click="exportIncident()">
                                    <i class="mdi mdi-file-excel me-2 tx-16"></i><span>Export All Incidents</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body pd-t-0 mt-1">                   
                    <div class="d-lg-flex justify-content-between mt-1 mb-1">
                        <div>
                            <span class="d-block mg-b-10 text-muted tx-12">List of all Patients both XPPL and Contractors...</span>
                        </div>
                        <div class="d-flex justify-content-end mt-xl-0 mt-lg-0 mt-md-1 mt-1">
                            <div class="pos-relative wd-lg-300 wd-100p">
                                <input class="form-control" style="padding-left: 25px" type="text" :placeholder="$t('search')" v-model="search" @input="SearchChange()" title="Search by Incident Number or Title">
                                    <i class="fe fe-search search-i text-muted"></i>
                                <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="SearchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
                            </div>
                            <div class="wd-35 ms-1">
                                <button type="button" class="btn btn-outline-primary p-0 border wd-35" title="Add new record" @click="NewPatient(), mode='nameBtn'">
                                    <i class="fa fa-plus tx-14"></i>
                                </button>
                            </div>
                        </div>
                    </div> 
                
                    <div class="table-responsive border-start border-end border-bottom mt-1" style="max-height: 78vh">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>
                                    <th class="border-start-0">{{$t('no.')}}</th>
                                    <!-- <th class="border-start-0">
                                        <div class="d-flex justify-content-between">
                                            <i v-if="filterMode =='inc_no'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_no'), filterName ='Incident Number'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_no'), filterName ='Incident Number'"></i>
                                        </div>
                                    </th>
                                    <th class="border-start-0">
                                        <div class="d-flex justify-content-between">Date
                                            <i v-if="filterMode =='inc_date'" class="mdi mdi-filter-remove tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_date'), filterName ='Incident Date'"></i>
                                            <i v-else class="mdi mdi-filter tx-10 ms-1 cur-pointer" title="Filter" @click="FilterList('inc_date'), filterName ='Incident Date'"></i>
                                        </div>
                                    </th> -->
                                    <th>{{$t('sex')}}</th>
                                    <th>{{$t('patientName')}}</th>
                                    <th>{{$t('surname')}}</th>
                                    <th>{{$t('company')}}</th>
                                    <th>{{$t('dept')}}</th>
                                    <th>{{$t('section')}}</th>
                                    <th>{{$t('position')}}</th>
                                    <th>{{$t('empid')}}</th>
                                    <th>{{$t('age')}}</th>
                                    <th>{{$t('phone')}}</th>
                                    <th>{{$t('bloodGroup')}}</th>
                                    <th>{{$t('disease')}}</th>
                                    <th>{{$t('remark')}}</th>
                                    <th v-if="showCol.created_at">{{$t('createAt')}}</th>
                                    <th v-if="showCol.created_by">{{$t('createBy')}}</th>
                                    <th v-if="showCol.updated_at">{{$t('updateAt')}}</th>
                                    <th v-if="showCol.updated_by">{{$t('updateBy')}}</th>
                                    <th class="border-end-0 position-sticky wd-60p" style="right: -0.1px">{{$t('tools')}}</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr v-for="(i, inx) in nameData" :key="inx" class="tr-hover">
                                    <td class="border-start-0 tx-center" >{{inx +1}}</td>
                                    <td>{{i.sex}}</td>
                                    <td class="cur-pointer" title="Click to Edit or Add more treatment" @click="EditPatient(i.ptid), mode=''">{{i.name}}</td>
                                    <td>{{i.surname}}</td>
                                    <td>{{i.company}}</td>
                                    <td>{{i.dept}}</td>
                                    <td>{{i.section}}</td>
                                    <td>{{i.position}}</td>
                                    <td>{{i.empid}}</td>
                                    <td>{{Age2(i.age, i.created_at)}}</td>
                                    <td>{{i.phone}}</td>
                                    <td>{{i.blood_group}}</td>
                                    <td>{{i.disease}}</td>
                                    <td>{{i.address}}</td>
                                    <td v-if="showCol.created_at" class="text-muted">{{DateFm3(i.created_at)}}</td>
                                    <td v-if="showCol.created_by" class="text-muted">{{i.created_by}}</td>
                                    <td v-if="showCol.updated_at" class="text-muted">{{DateFm3(i.updated_at)}}</td>
                                    <td v-if="showCol.updated_by" class="text-muted">{{i.updated_by}}</td>                                 
                                    <td class="border-end-0 position-sticky p-0 align-middle" style="right: -0.1px; background-color: #FFFFFF">
                                        <div class="d-flex justify-content-start ms-1">
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Preview">
                                                <i class="bx bx-search text-primary" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit or Add more treatment" @click="EditPatient(i.ptid), mode=''">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="DelPatient(i.ptid, inx)">
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
            
            <div v-else class="card-body">
                <!-- Patient name -->
                <div v-if="name" class=" mb-4">
                    <h4 class="card-title text-muted text-capitalize">{{$t('patientName')}}: <span class=" tx-primary">{{nameForm.name}}</span></h4>
                    <div class="row mt-2">
                         
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">{{$t('sex')}} <span class=" text-danger">*</span></label> 
                                <Multiselect v-if="$i18n.locale=='en'" v-model="nameForm.sex" :options="lkSex"/>
                                <Multiselect v-else v-model="nameForm.sex" :options="lkSexLao"/>
                            </div>
                        </div> 

                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">{{$t('patientName')}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="nameForm.name">
                            </div>
                        </div>

                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">{{$t('surname')}}</label>
                                <input type="text" class="form-control" v-model="nameForm.surname">
                            </div>
                        </div>

                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="mb-0">{{$t('position')}}</label> 
                                <Multiselect v-if="$i18n.locale=='en'" v-model="nameForm.position" :searchable="true" :searchStart="false" :options="lkPosit"/>
                                <Multiselect v-else v-model="nameForm.position" :searchable="true" :searchStart="false" :options="lkPositLao"/>
                            </div>
                        </div> 
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="mb-0">{{$t('company')}} <span class=" text-danger">*</span></label> 
                                <Multiselect v-if="$i18n.locale=='en'" v-model="nameForm.company" :searchable="true" :searchStart="true" :options="lkCom" @change="nameForm.dept=null, nameForm.section=null"/>
                                <Multiselect v-else v-model="nameForm.company" :searchable="true" :searchStart="true" :options="lkComLao" @change="nameForm.dept=null, nameForm.section=null"/>
                            </div>
                        </div> 
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label v-if="nameForm.company=='XPPL'" class="mb-0">{{$t('dept')}} <span class=" text-danger">*</span></label> 
                                <label v-else class="mb-0">{{$t('dept')}}</label> 
                                <input v-if="nameForm.company!='XPPL'" type="text" class="form-control" disabled>
                                <Multiselect v-if="nameForm.company=='XPPL' && $i18n.locale=='en'" v-model="nameForm.dept" :searchable="true" :searchStart="true" :options="lkDept"/>
                                <Multiselect v-if="nameForm.company=='XPPL' && $i18n.locale=='la'" v-model="nameForm.dept" :searchable="true" :searchStart="true" :options="lkDeptLao"/>
                            </div>
                        </div> 
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label v-if="nameForm.company=='XPPL'" class="mb-0">{{$t('section')}}</label> 
                                <label v-else class="mb-0">{{$t('section')}}</label> 
                                <input v-if="nameForm.company!='XPPL'" type="text" class="form-control" disabled>
                                <Multiselect v-if="nameForm.company=='XPPL' && $i18n.locale=='en'" v-model="nameForm.section" :searchable="true" :searchStart="false" :options="lkSection"/>
                                <Multiselect v-if="nameForm.company=='XPPL' && $i18n.locale=='la'" v-model="nameForm.section" :searchable="true" :searchStart="false" :options="lkSectionLao"/>
                            </div>
                        </div> 
                        <div class="col-xl-1">
                            <div class="form-group">
                                <label class="mb-0">{{$t('age')}}</label>
                                <input type="number" class="form-control" v-model="nameForm.age">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">{{$t('empid')}}</label>
                                <input type="text" class="form-control" v-model="nameForm.empid">
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="form-group">
                                <label class="mb-0">{{$t('phone')}}</label>
                                <input type="text" class="form-control" v-model="nameForm.phone">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">{{$t('bloodGroup')}}</label> 
                                <Multiselect v-if="$i18n.locale=='en'" v-model="nameForm.blood_group" :searchable="false" :searchStart="false" :options="lkBloodG"/>
                                <Multiselect v-else v-model="nameForm.blood_group" :searchable="false" :searchStart="false" :options="lkBloodGLao"/>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label class="mb-0">{{$t('disease')}}</label>
                                <input type="text" class="form-control" v-model="nameForm.disease">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label class="mb-0">{{$t('address')}}</label>
                                <input type="text" class="form-control" v-model="nameForm.address">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Treatment -->
                <div v-if="treat">
                    <!-- <h6 class="text-muted mt-2">Add Treatment</h6> -->
                    <h4 v-if="$i18n.locale=='en'" class="card-title text-muted text-capitalize">Medical Details</h4>
                    <h4 v-else class="card-title text-muted text-capitalize">ລາຍລະອຽດທາງການແພດ</h4>
                    <div class="row">
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">{{$t('date')}} <span class=" text-danger">*</span></label>
                                <input type="date" class="form-control" v-model="treatForm.date_in">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">{{$t('time')}}</label>
                                <input type="time" class="form-control" v-model="treatForm.time_in">
                            </div>
                        </div>
                        <div class="col-xl-2">
                            <div class="form-group">
                                <label class="mb-0">{{$t('medicalType')}} <span class=" text-danger">*</span></label> 
                                <Multiselect v-if="$i18n.locale=='en'" v-model="treatForm.medical_type" :searchable="false" :searchStart="false" :options="lkMedical"/>
                                <Multiselect v-else v-model="treatForm.medical_type" :searchable="false" :searchStart="false" :options="lkMedicalLao"/>
                            </div>
                        </div> 
                        <div class="col-xl-1">
                            <div class="form-group">
                                <label class="mb-0">{{$t('temp')}}</label>
                                <input type="text" class="form-control" v-model="treatForm.temp_c">
                            </div>
                        </div>
                        <div class="col-xl-1">
                            <div class="form-group">
                                <label class="mb-0">{{$t('blood')}}</label>
                                <input type="text" class="form-control" v-model="treatForm.bp">
                            </div>
                        </div>
                        <div class="col-xl-1">
                            <div class="form-group">
                                <label class="mb-0">{{$t('puls')}}</label>
                                <input type="text" class="form-control" v-model="treatForm.puls">
                            </div>
                        </div>
                        <div class="col-xl-1">
                            <div class="form-group">
                                <label class="mb-0">{{$t('ox')}}</label>
                                <input type="text" class="form-control" v-model="treatForm.ox">
                            </div>
                        </div>
                        <div class="col-xl-1">
                            <div class="form-group">
                                <label class="mb-0">{{$t('weight')}}</label>
                                <input type="text" class="form-control" v-model="treatForm.weight">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-12">
                            <div class="form-group">
                                <label class="mb-0">{{ $t('syndrome') }} <span class=" text-danger">*</span></label>
                                <textarea class="form-control" style="height: 107px" v-model="treatForm.syndrome"></textarea>
                            </div>
                        </div>  
                        <div class="col-xl-6">
                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="form-group">
                                        <label class="mb-0">{{$t('diagnosis')}} <span class=" text-danger">*</span></label> 
                                        <Multiselect v-if="$i18n.locale=='en'" v-model="treatForm.diagnosis" :searchable="true" :searchStart="false" :options="lkDiagnosis"/>
                                        <Multiselect v-else v-model="treatForm.diagnosis" :searchable="true" :searchStart="false" :options="lkDiagnosisLao"/>
                                    </div>
                                </div> 
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label class="mb-0">{{$t('doctor')}} <span class=" text-danger">*</span></label> 
                                        <Multiselect v-if="$i18n.locale=='en'" v-model="treatForm.doctor" :searchable="false" :searchStart="false" :options="lkDoctor"/>
                                        <Multiselect v-else v-model="treatForm.doctor" :searchable="false" :searchStart="false" :options="lkDoctorLao"/>
                                    </div>
                                </div> 
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label class="mb-0">{{$t('patientType')}} <span class=" text-danger">*</span></label> 
                                        <Multiselect v-if="$i18n.locale=='en'" v-model="treatForm.patient_type" :searchable="false" :searchStart="false" :options="lkPatient" @change="treatForm.transfer=null, treatForm.hospital=null"/>
                                        <Multiselect v-else v-model="treatForm.patient_type" :searchable="false" :searchStart="false" :options="lkPatientLao" @change="treatForm.transfer=null, treatForm.hospital=null"/>
                                    </div>
                                </div> 
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label class="mb-0">{{$t('transfer')}}</label> 
                                        <input v-if="treatForm.patient_type!='In Patient'" type="text" class=" form-control" disabled/>
                                        <Multiselect v-if="treatForm.patient_type=='In Patient' && $i18n.locale=='en'" v-model="treatForm.transfer" :searchable="false" :searchStart="false" :options="lkTransfer"/>
                                        <Multiselect v-if="treatForm.patient_type=='In Patient' && $i18n.locale=='la'" v-model="treatForm.transfer" :searchable="false" :searchStart="false" :options="lkTransferLao"/>
                                    </div>
                                </div> 
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label class="mb-0">{{$t('hospital')}}</label> 
                                        <input v-if="treatForm.patient_type!='In Patient'" type="text" class=" form-control" disabled/>
                                        <Multiselect v-if="treatForm.patient_type=='In Patient' && $i18n.locale=='en'" v-model="treatForm.hospital" :searchable="false" :searchStart="false" :options="lkHospital"/>
                                        <Multiselect v-if="treatForm.patient_type=='In Patient' && $i18n.locale=='la'" v-model="treatForm.hospital" :searchable="false" :searchStart="false" :options="lkHospitalLao"/>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                
                    <!-- Medications -->
                    <div v-if="medic">
                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ht-30 wd-150" @click="NewMedication()" title="Create a new item">
                            <div><i class="fa fa-plus-square me-1 text-primary tx-12"></i></div>
                            <span class="text-primary">{{$t('addMedications')}}</span>
                        </div>
                        <div v-if="medicaFilter.length>0" class="table-responsive border-0">
                            <table class="table mg-b-0 text-nowrap mt-2">
                                <thead>
                                    <tr>
                                        <th class="px-1">{{$t('no.')}}</th>
                                        <th class="px-1">{{$t('medicine')}}</th>
                                        <th class="px-1">{{$t('qty')}}</th>
                                        <th class="px-1">{{$t('unit')}}</th>
                                        <th class="px-1">{{$t('remark')}}</th>
                                        <th class="px-1 wd-60p">{{$t('tools')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="tx-13">
                                    <tr v-for="(i, inx) in medicaFilter" :key="inx">
                                        <th class="py-1 px-1 border-0">{{inx + 1}}</th>
                                        <td class="py-1 px-1 border-0">{{i.medicine}}</td>
                                        <td class="py-1 px-1 border-0">{{i.qty}}</td>
                                        <td class="py-1 px-1 border-0">{{i.unit}}</td>
                                        <td class="py-1 px-1 border-0">{{i.remark}}</td>
                                        <td class="p-0 border-0">
                                            <div class="d-flex justify-content-start ms-1">
                                                <button class="btn btn-sm btn-icon btn-i p-0" title="Delete record" @click="this.medicaFilter.splice(inx,1)">
                                                    <i class="fe fe-minus-circle text-danger" style="font-size: 16px"></i>
                                                </button> 
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Treatment Button -->
                    <div v-if="treatBtn">
                        <div class="d-flex justify-content-end mt-2 pb-2">
                            <button v-if="mode=='treatBtn'" type="button" class="btn btn-primary" :class="AddTreatmentDis" @click="AddTreatment()">
                                <i class="fe fe-plus"></i><span class="mx-1">{{$t('add')}}</span>
                            </button>
                            <button v-else type="button" class="btn btn-primary" :class="AddTreatmentDis" @click="UpdTreatment()">
                                <i class="fe fe-check-circle"></i><span class="mx-1">{{$t('update')}}</span>
                            </button>
                            <button type="button" class="btn btn-secondary ms-1" @click="TreatmentClose()"><i class="fe fe-x"></i> 
                                <span class="mx-1">{{$t('cancel')}}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Treatment History -->
                <div v-if="tab">
                    <h4 class="card-title text-muted text-capitalize mb-1">{{$t('treatmentInfo')}}</h4>
                    <div class="table-responsive border mt-0" style="max-height: 250px">
                        <table class="table main-table-reference text-nowrap mg-b-0">
                            <thead class="position-sticky" style="top: 0px; z-index: 1">
                                <tr>
                                    <th class="border-start-0">{{$t('no.')}}</th>
                                    <th>{{$t('date')}}</th>
                                    <th>{{$t('time')}}</th>
                                    <th>{{$t('medicalType')}}</th>
                                    <th>{{$t('temp')}}</th>
                                    <th>{{$t('blood')}}</th>
                                    <th>{{$t('puls')}}</th>
                                    <th>{{$t('ox')}}</th>
                                    <th>{{$t('weight')}}</th>
                                    <th>{{$t('syndrome')}}</th>
                                    <th>{{$t('diagnosis')}}</th>
                                    <th>{{$t('patientType')}}</th>
                                    <th>{{$t('transfer')}}</th>
                                    <th>{{$t('hospital')}}</th>
                                    <th>{{$t('doctor')}}</th>
                                    <th v-if="showCol.created_at">{{$t('createAt')}}</th>
                                    <th v-if="showCol.created_at">{{$t('createBy')}}</th>
                                    <th v-if="showCol.created_at">{{$t('updateAt')}}</th>
                                    <th v-if="showCol.created_at">{{$t('updateBy')}}</th>
                                    <th class="border-end-0 position-sticky wd-80p" style="right: -0.1px">{{$t('tools')}}</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr class="tr-hover cur-pointer" v-for="(i, inx) in treatFilter" :key="inx" @click="TreatmentSelected(inx, i.tmid)" :style="inx === treatmentSel ? 'background-color: #dadee7; color: blue' : ''">
                                    <td class="border-start-0 tx-center">{{inx+1}}</td>
                                    <td>{{DateFm2(i.date_in)}}</td>
                                    <td>{{TimeFm1(i.time_in)}}</td>
                                    <td>{{i.medical_type}}</td>
                                    <td>{{num(i.temp_c)}}</td>
                                    <td>{{i.blood_press}}</td>
                                    <td>{{num(i.puls)}}</td>
                                    <td>{{num(i.oxigen)}}</td>
                                    <td>{{num(i.weight)}}</td>
                                    <td>{{i.syndrome}}</td>
                                    <td>{{i.diagnosis}}</td>
                                    <td>{{i.patient_type}}</td>
                                    <td>{{i.transfer}}</td>
                                    <td>{{i.hospital}}</td>
                                    <td>{{i.doctor}}</td>
                                    <td v-if="showCol.created_at" class="text-muted">{{DateFm3(i.created_at)}}</td>
                                    <td v-if="showCol.created_by" class="text-muted">{{i.created_by}}</td>
                                    <td v-if="showCol.updated_at" class="text-muted">{{DateFm3(i.updated_at)}}</td>
                                    <td v-if="showCol.updated_by" class="text-muted">{{i.updated_by}}</td>
                                    <td class="p-0 align-middle border-end-0 position-sticky bg-white" style="right: -0.1px"> 
                                        <div class="d-flex justify-content-start ps-1">
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="EditTreatment(i.tmid), mode=''">
                                                <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                            </button> 
                                            <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="DelTreatment(i.tmid, inx)">
                                                <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                            </button> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="NewTreatment(), mode='treatBtn'" title="Create a new item">
                            <div><i class="fa fa-plus-circle me-1 tx-12 text-primary"></i></div>
                            <span class="text-primary tx-13">{{ $t('newItem') }}</span>
                        </div>
                    </div>
                    
                    <!-- Medicine List -->
                    <div v-if="mediList">
                        <h4 class="card-title text-muted text-capitalize mt-4 mb-1">{{$t('medicine')}}</h4>
                        <div class="table-responsive">
                            <table v-if="medicaFilter.length > 0" class="table mg-b-0 text-nowrap">
                                <thead>
                                    <tr>
                                        <th style="padding: 0px 4px 5px 10px">{{$t('no.')}}</th>
                                        <th class="px-2">{{$t('medicine')}}</th>
                                        <th class="px-2">{{$t('qty')}}</th>
                                        <th class="px-2">{{$t('unit')}}</th>
                                        <th class="px-2">{{$t('remark')}}</th>
                                        <th class="px-2 wd-60p">{{$t('tools')}}</th>
                                    </tr>
                                </thead>
                                <tbody class="tx-13">
                                    <tr v-for="(i, inx) in medicaFilter" :key="inx">
                                        <th class="py-1 ps-3 pe-0 border-0">{{inx + 1}}</th>
                                        <td class="py-1 px-2 border-0">{{i.medicine}}</td>
                                        <td class="py-1 px-2 border-0">{{num(i.qty)}}</td>
                                        <td class="py-1 px-2 border-0">{{i.unit}}</td>
                                        <td class="py-1 px-2 border-0">{{i.remark}}</td>
                                        <td class="p-0 align-middle border-0 position-sticky bg-white" style="right: -0.1px"> 
                                            <div v-if="i.id" class="d-flex justify-content-start ps-1">
                                                <button class="btn btn-sm btn-icon btn-i p-0" title="Edit" @click="EditMedicine(i.id), mode='u'">
                                                    <i class="bx bx-edit text-info" style="font-size: 16px"></i>
                                                </button> 
                                                <button class="btn btn-sm btn-icon btn-i p-0" title="Delete" @click="DelMadicine(i.id, inx)">
                                                    <i class="bx bx-trash text-danger" style="font-size: 16px"></i>
                                                </button> 
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-start align-items-center cur-pointer add-hover ms-2 ht-30 wd-100" @click="NewMedicine(), mode=''" title="Create a new item">
                                <div><i class="fa fa-plus-circle me-1 tx-12 text-primary"></i></div>
                                <span class="text-primary tx-13">{{ $t('newItem') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main button -->
                <div v-if="nameBtn">
                    <div class="d-flex justify-content-end mt-2 pb-2">
                        <button v-if="mode=='nameBtn'" type="button" class="btn btn-primary" :class="AddPatientDis" @click="AddPatient()">
                            <i class="fe fe-plus"></i><span class="mx-1">{{$t('add')}}</span>
                        </button>
                        <button v-else type="button" class="btn btn-primary" :class="UpdPatientDis" @click="UpdPatient()">
                            <i class="fe fe-save"></i><span class="mx-1">{{$t('save')}}</span>
                        </button>
                        <button type="button" class="btn btn-secondary ms-1" @click="MainClose()"><i class="fe fe-x"></i> 
                            <span class="mx-1">{{$t('close')}}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Medications -->
            <div class="modal" id="MedicationModal" data-bs-backdrop="static" back data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header pb-1 bd-b-0">
                            <h6 class="main-content-label text-capitalize">{{$t('medicine')}}</h6>
                            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body pt-1">  
                            <div class="form-group">
                                <Multiselect v-model="medicaForm.medicine" searchable="true" searchStart="true" :placeholder="$t('medicine')" :options="lkMedicine" @select="MedicineChange(this.medicaForm.medicine)"/>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="number" class="form-control" v-model="medicaForm.qty" :placeholder="$t('qty')">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="medicaForm.unit" :placeholder="$t('unit')">
                                        <!-- <Multiselect v-model="medicaForm.unit" searchable="true" searchStart="true" :placeholder="$t('unit')" :options="lkType"/> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" style="height: 80px" v-model="medicaForm.remark" :placeholder="$t('remark')"></textarea>
                            </div>  
                            <div class="d-flex justify-content-end">
                                <div v-if="medicBtn">
                                    <div v-if="mode=='u'">
                                        <button   button type="button" class="btn btn-primary" :class="AddMedicationDis" data-bs-dismiss="modal" @click="UpdMedicine()"><i class="fe fe-check-circle"></i><span class="mx-1">{{$t('update')}}</span></button>
                                    </div>
                                    <div v-else>
                                        <button type="button" class="btn btn-primary" :class="AddMedicationDis" @click="AddMedicine()"><i class="fe fe-plus"></i><span class="mx-1">{{$t('add')}}2</span></button> 
                                        <button type="button" class="btn btn-purple ms-1" :class="AddMedicationDis" @click="AddMedicine()" data-bs-dismiss="modal"><i class="fe fe-save"></i><span class="mx-1">{{$t('save')}}2</span></button> 
                                    </div>
                                </div>
                                <div v-else>
                                    <button type="button" class="btn btn-primary" :class="AddMedicationDis" @click="AddMedication()"><i class="fe fe-plus"></i><span class="mx-1">{{$t('add')}}</span></button> 
                                    <button type="button" class="btn btn-purple ms-1" :class="AddMedicationDis" @click="AddMedication()" data-bs-dismiss="modal"><i class="fe fe-save"></i><span class="mx-1">{{$t('save')}}</span></button> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                                              
            </div>

            <!-- Modal Show Columns -->
            <div class="modal bd-0" id="ShowColumn" data-bs-keyboard="false" tabindex="-1" aria-labelledby="colShowLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header pb-1 bd-b-0">
                            <h6 class="text-muted main-content-label text-capitalize">Show/Hide Columns</h6>
                        </div>
                        <div class="modal-body pt-0">  
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{$t('createAt')}}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="no"  v-model="showCol.created_at" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="no"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{$t('createBy')}}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="position"  v-model="showCol.created_by" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="position"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{$t('updateAt')}}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="crew"  v-model="showCol.updated_at" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="crew"></label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{$t('updateBy')}}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="section"  v-model="showCol.updated_by" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="section"></label>
                                </div>
                            </div>

                            <!-- <div class="d-flex justify-content-between mb-2">
                                <span>Type</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="type"  v-model="showCol.type" @change='$emit("input", $event.target.checked)'>
                                    <label class="custom-control-label cur-pointer" for="type"></label>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>                                              
            </div>
        </div>
    </div>
    
</template>

<script>
export default {
    name: 'XpplAppTreatment',

    data() {
        return {
            permiss:[],
            lkSex:['Male', 'Female'],
            lkSexLao:[{value:'Male', label:'ຊາຍ'},{value:'Female', label:'ຍິງ'}],
            
            lkCom:[],
            lkComLao:[],
            lkDept:[],
            lkDeptLao:[],
            lkPosit:[],
            lkPositLao:[],
            lkDiagnosis:[],
            lkDiagnosisLao:[],
            lkPatient:[],
            lkPatientLao:[],
            lkMedical:[],
            lkMedicalLao:[],
            lkHospital:[],
            lkHospitalLao:[],
            lkTransfer:[],
            lkTransferLao:[],
            lkBloodG:[],
            lkBloodGLao:[],
            lkSection:[],
            lkSectionLao:[],
            lkMedicine:[],
            lkDoctor:[],
            lkDoctorLao:[],

            lookup:[],

            search:'',
            btnClear:false,


            nameData:[],
            treatData:[],
            treatFilter:[],
            medicaData:[],
            medicaFilter:[],

            nameForm: {ptid:'', sex:null, name:'', surname:'', company:null, dept:null, section:null, position:null, empid:'', phone:'', age:'', blood_group:'', disease:'', address:''},
            treatForm: {tmid:'', date_in:'', time_in:'', medical_type:null, temp_c:'', bp:'', puls:'', ox:'', weight:'', syndrome:null, diagnosis:'', doctor:null, patient_type:null, transfer:null, hospital:null},
            medicaForm: {id:'', tmid:'', medicine:null, qty:'', unit:'', remark:''},
            showCol: {created_at:false, created_by:false, updated_at:false, updated_by:false},

            treatmentSel:'',
            list:true,
            name:'',
            treat:'',
            medic:'',
            tab:'',
            nameBtn:'',
            treatBtn:'',
            medicBtn:'',
            mediList:'',
            loading: false

        };
    },

    mounted() {
    },

    computed: {
        AddPatientDis(){
            let fn = this.nameForm;
            let ft = this.treatForm;
            if (fn.company!='XPPL') {
                if (fn.sex==null || fn.name=='' || fn.company==null || ft.date_in=='' || ft.medical_type==null || ft.syndrome=='' || ft.diagnosis==null || ft.patient_type==null) {
                    return 'disabled';
                } else {
                    return '';
                }
            } else {
                if (fn.sex==null || fn.name=='' || fn.company==null || fn.dept==null || ft.date_in=='' || ft.medical_type==null || ft.syndrome=='' || ft.diagnosis==null || ft.patient_type==null) {
                    return 'disabled';
                } else {
                    return '';
                }
            }
        },

        UpdPatientDis(){
            let f = this.nameForm;
            if (f.company!='XPPL') {
                if (f.sex==null || f.name=='' || f.company==null) {
                    return 'disabled';
                } else {
                    return '';
                }
            } else {
                if (f.sex==null || f.name=='' || f.company==null || f.dept==null) {
                    return 'disabled';
                } else {
                    return '';
                }
            }
        },

        AddMedicationDis(){
            if (this.medicaForm.medicine==null || this.medicaForm.qty=='' || this.medicaForm.unit=='') {
                return 'disabled';
            } else {
                return '';
            }
        },
        
        AddTreatmentDis(){
            let f = this.treatForm;
            if (f.date_in=='' || f.medical_type==null || f.syndrome=='' || f.diagnosis==null || f.doctor==null || f.patient_type==null) {
                return 'disabled';
            } else {
                return '';
            }
        },
    },

    methods: {
        async OnLoad(){
            this.loading = true;

            const paList = await axios.get(`/api/clinic/patientlist?search=${this.search}`)
            this.nameData = paList.data;

            this.loading = false;

            const treatList = await axios.get('/api/clinic/treatmentdata')
            this.treatData = treatList.data;

            const medicaList = await axios.get('/api/clinic/medicationdata')
            this.medicaData = medicaList.data;

            const perm = await axios.get('/api/permiss')
            this.permiss = perm.data;

        },

        async PatientList(){
            const paList = await axios.get('/api/clinic/patientlist')
            this.nameData = paList.data;
        },

        async Treatments(){
            const treatList = await axios.get('/api/clinic/treatmentdata')
            this.treatData = treatList.data;
        },

        async Medications(){
            const medicaList = await axios.get('/api/clinic/medicationdata')
            this.medicaData = medicaList.data;
        },

        async OnSearching(){

            const paList = await axios.get(`/api/clinic/patientlist?search=${this.search}`)
            this.nameData = paList.data;


        
            // var PATTERN = 'bedroom',
            // filtered = myArray.filter(function (str) { return str.indexOf(PATTERN) === -1; });

            //Working
            // var PATTERN = /bedroom/,
            // filtered = myArray.filter(function (str) { return PATTERN.test(str); });

            // var myArray = ["bedroomone", "bedroomonetwo", "bathroom"];
            // var PATTERN = 'bedroom',
            // filtered = myArray.filter(function (str) { return str.includes(PATTERN); });


            // console.log("====>", myArray);

        },
        
        async SearchChange(){
            if(this.search.length > 0){
                this.btnClear = true;
                const data = await this.OnSearching();
            } else {
                this.btnClear = false;
                const data = await this.OnSearching();
            }
        },

        SearchClear(){
            this.search='';
            this.btnClear=false;
            this.OnSearching();
        },

        ShowCol(){
            $('#ShowColumn').modal('show');
        },

        NewPatient(){
            if(this.permiss.clinic_add==0){
                this.$swal.fire({
                    text: "You don't have permission to add!",
                    icon: 'error',
                    showCancelButton:false,
                    showConfirmButton:false,
                    allowOutsideClick:false,
                    timerProgressBar:true,
                    timer: 1500
                })
            } else {

                let ran = Math.floor(Math.random() * 9000);
                let newid = String(ran).padStart(4, '0')

                let check = this.nameData.find((i) => i.ptid == newid);
                if (check){
                    alert("ລະຫັດຊ້ຳກັນ ກະລຸນາຄິກ OK ແລ້ວຄິກ Add ໃໝ່ຈົນກ່ວາຈະສຳເລັດ!")
                } else {
                    this.list='';
                    this.tab='';
                    this.treatBtn='';
                    this.name=true;
                    this.treat=true;
                    this.medic=true;
                    this.nameBtn=true;
                    this.medicaFilter=[];

                    this.NameFormClear()
                    this.TreatFormClear()
                    this.MedicaFormClear()

                    this.nameForm.ptid = newid;

                    let ran2 = Math.floor(Math.random() * 9000)
                    let newtm = String(ran2).padStart(4, '0');
                    this.treatForm.tmid = newid +'-'+ newtm;
                }
            }
        },

        AddPatient(){
            this.$axios.post('/api/clinic/addpatient',{
                name: this.nameForm,
                treat: this.treatForm,
                medic: this.medicaFilter
            })
            .then((response) => {
                if(response.data.success){                
                    this.name='';
                    this.treat='';
                    this.medic='';
                    this.nameBtn='';
                    this.list=true;
                    this.PatientList();
                    this.Treatments();
                    this.Medications();
                 } else {
                    alert(response.data.message)
                }
            });
        },

        EditPatient(id){
            this.list='';
            this.treat='';
            this.medic='';
            this.name=true;
            this.tab=true;
            this.nameBtn=true;

            this.treatmentSel='';
            this.treatFilter=[],
            this.medicaFilter=[];
            this.NameFormClear();

            // Patient name data
            let item = this.nameData.find((i) => i.ptid == id);
            let f = this.nameForm;
            f.ptid = id;
            f.sex = item.sex;
            f.name = item.name;
            f.surname = item.surname;
            f.position = item.position;
            f.company = item.company;
            f.dept = item.dept;
            f.section = item.section;
            f.empid = item.empid;
            f.phone = item.phone;
            f.blood_group = item.blood_group;
            f.disease = item.disease;
            f.address = item.address;

            if (item.age){
                f.age = this.Age2(item.age, item.created_at);
            } else {
                f.age = null;
            }

            // Treatment name data
            let treat = this.treatData.filter((i) => i.ptid == id);
            this.treatFilter = treat;
            
        },

        UpdPatient(){
            if(this.permiss.clinic_edit==0){
                this.$swal.fire({
                    text: "You don't have permission to update!",
                    icon: 'error',
                    showCancelButton:false,
                    showConfirmButton:false,
                    allowOutsideClick:false,
                    timerProgressBar:true,
                    timer: 1500
                })
            } else {
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
                        this.$axios.post('/api/clinic/updpatient', this.nameForm)
                        .then((res) => {
                            this.name='';
                            this.tab='';
                            this.nameBtn='';
                            this.list=true;
                            this.PatientList();
                        })
                    }
                });
            }
        },

        DelPatient(id, inx){
            if (this.permiss.clinic_del==0){
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
                        this.$axios.post(`/api/clinic/delpatient/${id}`)
                        .then((response)=>{
                            this.nameData.splice(inx,1);
                            this.PatientList();
                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        MainClose(){
            this.name='';
            this.treat='';
            this.medic='';
            this.mediList='';
            this.tab='';
            this.nameBtn='';
            this.list=true;
        },

        NewTreatment(){
            if (this.permiss.clinic_add==0){
                this.$swal.fire({
                    text: "You don't have permission to add!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {

                let ran = Math.floor(Math.random() * 9000);
                let newtm = String(ran).padStart(4, '0')
                let fnid = this.nameForm.ptid +'-'+ newtm;

                let check = this.treatData.find((i) => i.ptid == fnid);
                if (check){
                    alert("ລະຫັດຊ້ຳກັນ ກະລຸນາຄິກ OK ແລ້ວຄິກ ເພີ່ມ ອີກໃໝ່ຈົນກ່ວາຈະສຳເລັດ!")
                } else {
                    this.list='';
                    this.name='';
                    this.tab='';
                    this.nameBtn='';
                    this.treatmentSel='';
                    this.mediList='';
                    this.treat=true;
                    this.medic=true;
                    this.treatBtn=true;

                    this.TreatFormClear();
                    this.medicaFilter=[];
                    this.treatForm.tmid = fnid;
                }
            }
        },

        AddTreatment(){
            this.$axios.post('/api/clinic/addtreatment',{
                id: this.nameForm.ptid,
                treat: this.treatForm,
                medic: this.medicaFilter
            })
            .then((response) => {
                if(response.data.success){

                    this.treat='';
                    this.medic='';
                    this.name=true;
                    this.tab=true;
                    this.nameBtn=true;

                    let f = this.treatForm;
                    this.treatFilter.push({
                        tmid: f.tmid, 
                        date_in: f.date_in, 
                        time_in: f.time_in, 
                        medical_type: f.medical_type, 
                        temp_c: f.temp_c, 
                        blood_press: f.bp, 
                        puls: f.puls, 
                        oxigen: f.ox, 
                        weight: f.weight, 
                        syndrome: f.syndrome, 
                        diagnosis: f.diagnosis, 
                        doctor: f.doctor, 
                        patient_type: f.patient_type, 
                        transfer: f.transfer, 
                        hospital: f.hospital
                    });

                    this.Treatments();
                    this.Medications();

                } else {
                    alert(response.data.message)
                }
            });
        },

        EditTreatment(id){
            if(this.permiss.clinic_edit==0){
                this.$swal.fire({
                    text: "You don't have permission to update!",
                    icon: 'error',
                    showCancelButton:false,
                    showConfirmButton:false,
                    allowOutsideClick:false,
                    timerProgressBar:true,
                    timer: 1500
                })
            } else {
                this.TreatFormClear();
                this.name='';
                this.tab='';
                this.nameBtn='';
                this.medic='';
                this.treat=true;
                this.treatBtn=true;

                let item = this.treatData.find((i) => i.tmid == id);
                let f = this.treatForm;
                f.tmid = id;
                f.date_in = moment(item.date_in).format('YYYY-MM-DD');
                f.medical_type = item.medical_type;
                f.temp_c = item.temp_c;
                f.bp = item.blood_press;
                f.puls = item.puls;
                f.ox = item.oxigen;
                f.weight = item.weight;
                f.syndrome = item.syndrome;
                f.diagnosis = item.diagnosis;
                f.doctor = item.doctor;
                f.patient_type = item.patient_type;
                f.transfer = item.transfer;
                f.hospital = item.hospital;

                if (item.time_in){
                    f.time_in = item.time_in.split('.')[0];
                };
            };
        },

        UpdTreatment(){
            this.$axios.post('/api/clinic/updtreatment', this.treatForm)
            .then((res) => {

                this.treat='';
                this.nameBtn='';
                this.name=true;
                this.tab=true;
                this.nameBtn=true;

                let f = this.treatForm;
                let item = this.treatFilter.find((i) => i.tmid == f.tmid);
                item.date_in = f.date_in;
                item.time_in = f.time_in;
                item.medical_type = f.medical_type;
                item.temp_c = f.temp_c;
                item.blood_press = f.bp;
                item.puls = f.puls;
                item.oxigen = f.ox;
                item.weight = f.weight;
                item.syndrome = f.syndrome;
                item.diagnosis = f.diagnosis;
                item.doctor = f.doctor;
                item.patient_type = f.patient_type;
                item.transfer = f.transfer;
                item.hospital = f.hospital;

                this.Treatments();
            });
        },

        DelTreatment(id, inx){
            if (this.permiss.clinic_del==0){
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
                        this.$axios.post(`/api/clinic/deltreatment/${id}`)
                        .then((response)=>{

                            this.mediList='';
                            this.treatFilter.splice(inx,1)
                            this.Treatments();

                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                });
            }
        },

        TreatmentClose(){
            this.list=''; 
            this.treat='';
            this.medic='';
            this.mediList='';
            this.tab=true;
            this.name=true;
            this.nameBtn=true;
        },

        NewMedication(){
            this.medicBtn = '';
            this.MedicaFormClear();
            $('#MedicationModal').modal('show');
        },

        MedicineChange(name){
            let item  = this.lookup.find((i)=>i.code == name);
            this.medicaForm.unit = item.descr_lao;
        },

        AddMedication(){
            let fd = this.medicaForm;
            if(this.medicaFilter.find((i)=>i.medicine==fd.medicine)){
                let q = this.medicaFilter.find((i)=>i.medicine==fd.medicine).qty;
                this.medicaFilter.find((i)=>i.medicine==fd.medicine).qty = q + fd.qty;
            } else {
                this.medicaFilter.push({
                    medicine: fd.medicine,
                    qty: fd.qty, 
                    unit: fd.unit, 
                    remark: fd.remark
                });
            };
            this.MedicaFormClear();
        },

        NewMedicine(){
            if (this.permiss.clinic_add==0){
                this.$swal.fire({
                    text: "You don't have permission to add!",
                    icon: 'error',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    timer: 1500
                })
            } else {
                this.medicBtn = true;
                this.MedicaFormClear();
                $('#MedicationModal').modal('show');
            }
        },

        async AddMedicine(){
            let f = this.medicaForm;
            if(this.medicaFilter.find((i) => i.medicine == f.medicine)){
                alert('Duplicate Medicine name');
            } else {
                // this.medicaFilter.push({
                //     tmid: f.tmid,
                //     medicine: f.medicine,
                //     qty: f.qty, 
                //     unit: f.unit, 
                //     remark: f.remark
                // });
                // this.MedicaFormClear();
                const res = await axios.post('/api/clinic/addmedicine', this.medicaForm);
                const data = await this.Medications();

                let item = this.medicaData.filter((i) => i.tmid == this.medicaForm.tmid);
                this.medicaFilter = item;
            };
        },

        EditMedicine(id){
            if(this.permiss.clinic_edit==0){
                this.$swal.fire({
                    text: "You don't have permission to update!",
                    icon: 'error',
                    showCancelButton:false,
                    showConfirmButton:false,
                    allowOutsideClick:false,
                    timerProgressBar:true,
                    timer: 1500
                })
            } else {
                this.MedicaFormClear();
                this.medicBtn = true;

                let item = this.medicaFilter.find((i) => i.id == id);
                this.medicaForm.id = item.id;
                this.medicaForm.medicine = item.medicine;
                this.medicaForm.qty = item.qty;
                this.medicaForm.unit = item.unit;
                this.medicaForm.remark = item.remark;
                $('#MedicationModal').modal('show');
            }
        },

        async UpdMedicine(){
            const res = await axios.post('/api/clinic/updmedicine', this.medicaForm);
            const data = await this.Medications();

            let item = this.medicaData.filter((i) => i.tmid == this.medicaForm.tmid);
            this.medicaFilter = item;
        },

        DelMadicine(id, inx){
            if (this.permiss.clinic_del==0){
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
                        this.$axios.post(`/api/clinic/delmedicine/${id}`)
                        .then((response)=>{
                            this.medicaFilter.splice(inx,1)
                            this.Medications();
                        }).catch((error)=>{
                            console.log(error);
                        })
                    }
                })
            }
        },

        TreatmentSelected(inx, id){
            this.treatmentSel = inx;
            this.mediList = true;
            this.medicaForm.tmid = id;
            let item = this.medicaData.filter((i) => i.tmid == id);
            this.medicaFilter = item;
        },

        DateFm1(text){
            if (text) {
                return moment(text).format("YYYY-MM-DD");
            }
        },

        DateFm2(text){
            if (text) {
                return moment(text).format("DD-MM-YYYY");
            }
        },

        DateFm3(text){
            if (text) {
                return moment(text).format("DD-MM-YYYY HH:ss");
            }
        },

        TimeFm1(text){
            if (text){
                return text.split(":00.")[0];
            };
        },

        num(text){
            if (text){
                return parseFloat(text);
            };
        },

        Age(dob){
            if (dob) {
                let d1 = moment(dob).format("MM-DD-YYYY");
                let date = new Date(d1);

                var diff = Date.now() - date.getTime();
                var age = new Date(diff); 
                return Math.abs(age.getUTCFullYear() - 1970);
            }
        },

        Age2(val, yy){
            if (val){
                let cd = yy.substring(0,4);
                let d1 = moment(cd).format("MM-DD-YYYY");
                let date = new Date(d1);

                let diff = Date.now() - date.getTime();
                let age = new Date(diff); 
                return Math.abs(age.getUTCFullYear() - 1970) + parseInt(val);
            }
        },

        NameFormClear(){
            let f = this.nameForm;
            f.sex='';
            f.name='';
            f.surname='';
            f.position=null;
            f.company=null;
            f.dept=null;
            f.section=null;
            f.empid='';
            f.phone='';
            f.age='';
            f.blood_group=null;
            f.disease='';
            f.address='';
        },

        TreatFormClear(){
            let f = this.treatForm;
            f.date_in='';
            f.time_in='';
            f.medical_type=null;
            f.temp_c='';
            f.bp='';
            f.puls='';
            f.ox='';
            f.weight='';
            f.syndrome='';
            f.diagnosis=null;
            f.doctor=null;
            f.patient_type=null;
            f.transfer=null;
            f.hospital=null;
        },

        MedicaFormClear(){
            let f = this.medicaForm;
            f.medicine=null;
            f.qty='';
            f.unit='';
            f.remark=''
        },

        Lookup(){
            this.$axios.get('/api/clinic/lookup')
            .then((res) =>  {
                this.lookup = res.data;

                let data = [
                    {cate:'Company', col:'code', name:'lkCom'},
                    {cate:'Company', col:'descr_lao', name:'lkComLao'},
                    {cate:'Department', col:'code', name:'lkDept'},
                    {cate:'Department', col:'descr_lao', name:'lkDeptLao'},
                    {cate:'Position', col:'code', name:'lkPosit'},
                    {cate:'Position', col:'descr_lao', name:'lkPositLao'},
                    {cate:'Diagnosis', col:'code', name:'lkDiagnosis'},
                    {cate:'Diagnosis', col:'descr_lao', name:'lkDiagnosisLao'},
                    {cate:'Medical Type', col:'code', name:'lkMedical'},
                    {cate:'Medical Type', col:'descr_lao', name:'lkMedicalLao'},
                    {cate:'Patient Type', col:'code', name:'lkPatient'},
                    {cate:'Patient Type', col:'descr_lao', name:'lkPatientLao'},
                    {cate:'Hospital', col:'code', name:'lkHospital'},
                    {cate:'Hospital', col:'descr_lao', name:'lkHospitalLao'},
                    {cate:'Transfer', col:'code', name:'lkTransfer'},
                    {cate:'Transfer', col:'descr_lao', name:'lkTransferLao'},
                    {cate:'Blood Group', col:'code', name:'lkBloodG'},
                    {cate:'Blood Group', col:'descr_lao', name:'lkBloodGLao'},
                    {cate:'Section', col:'code', name:'lkSection'},
                    {cate:'Section', col:'descr_lao', name:'lkSectionLao'},
                    {cate:'Medicine', col:'code', name:'lkMedicine'},
                    {cate:'Doctor', col:'code', name:'lkDoctor'},
                    {cate:'Doctor', col:'descr_lao', name:'lkDoctorLao'},

                ];

                for (let j = 0; j < data.length; j++){
                    
                    let cateName = data[j].cate;
                    let colName = data[j].col;
                    let lkName = data[j].name;

                    let item  = this.lookup.filter((i)=>i.category == [cateName]);
                    for (let i = 0; i < item.length; i++){
                        
                        this[lkName].push({
                            value: item[i].code,
                            label: item[i][colName]
                        });
                    };
                };
            });
        },
    },

    created(){
        this.OnLoad();
        this.Lookup();
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