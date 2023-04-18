<template>
  <div v-if="loading">
      <loading/>
  </div> 
  <div v-else>
    <div class="card">
      <div class="card-body">
        <div class="breadcrumb-header justify-content-between align-items-center mb-1 mt-0" >
          <div>
            <h4 class="card-title mg-b-0 text-muted">PAYMENT REQUISITIONS</h4>
            <p class="tx-12 tx-gray-500 mb-0">Pending, rejected and approved payment requisitons...</p>
          </div>
          <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pos-relative wd-md-150 wd-50p">
              <input class="form-control" style="padding-left: 25px" type="text" :placeholder="$t('search')" v-model="search" @input="searchInput()" @keydown.enter="searchEnter()" >
                <i class="fe fe-search search-i text-muted cur-pointer" title="Click to search" @click="searchInput()"></i>
              <button class="btn btn-icon btn-sm search-c text-muted p-0" v-if="btnClear" @click="searchClear()"><i class="fe fe-x" style="font-size: 14px"></i></button>
            </div>
            <div class="ms-1" style="width: 120px">
              <Multiselect :options="lkYear" v-model="year" @select="searchClear()"/>
            </div>
            <div class="wd-35 ms-1">
              <button type="button" class="btn btn-outline-primary p-0 border wd-35" title="Add new record" @click="newPayment(), mode='add'">
                <i class="fa fa-plus tx-14"></i>
              </button>
            </div> 
          </div>
        </div> 

        <div class="panel panel-primary tabs-style-3 border-0 px-0 pb-0">
          <div class="tab-menu-heading">
            <div class="tabs-menu ">
              <div class=" d-flex justify-content-between align-items-center">
                <ul class="nav panel-tabs">
                  <li @click="actTab = 'pen'"><a href="#tab1" class="active mb-0" data-bs-toggle="tab" style="padding: 6px 18px"><i class="fe fe-clock"></i> Pending</a></li>
                  <li @click="actTab = 'app'"><a href="#tab2" class="mb-0" data-bs-toggle="tab" style="padding: 6px 18px"><i class="fe fe-check-circle"></i> Approved</a></li>
                  <li @click="actTab = 'rej'"><a href="#tab3" class="mb-0" data-bs-toggle="tab" style="padding: 6px 18px"><i class="fe fe-x-circle"></i> Rejected</a></li>
                  <li @click="actTab = 'all'"><a href="#tab4" class="mb-0" data-bs-toggle="tab" style="padding: 6px 18px"><i class="fe fe-database"></i> All Data</a></li>
                </ul>
                  <div>
                    <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                      <i class="bx bx-dots-vertical-rounded text-gray"></i>
                    </button>
                    <div class="dropdown-menu rounded-5 box-shadow-pink tx-13">
                      <div class="dropdown-item cur-pointer dropdown-hover" @click="showCols()">
                        <i class="mdi mdi-eye me-2 tx-16"></i>
                        <span>Show/Hide Columns</span>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="panel-body tabs-menu-body px-0 pb-0">
            <div class="tab-content">
              <div class="tab-pane active" id="tab1">
                <p v-if="pendingData.length == 0">There are no records.</p>
                <div v-else class="table-responsive" style="max-height: 65vh">
                  <table class="table mg-b-0 text-nowrap">
                    <thead class="position-sticky bg-white" style="top: 0px; z-index: 1">
                      <tr>
                        <!-- <th class="px-2">No.</th>
                        <th class="px-2">Company</th> -->
                        <!-- <th v-if="permiss.hqpr_all == 1" class="px-2">User Department</th> -->
                        <!-- <th class="px-2">Department's Doc</th>
                        <th class="px-2">Document No.</th> -->
                        <!-- <td class="px-2">Date -->

                            <!-- <button class="btn btn-sm btn-icon px-0" style="width: 18px" data-bs-auto-close="outside" data-bs-toggle="dropdown" title="More Tools">
                              <i class="bx bx-dots-vertical-rounded text-dark"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <div class="form-group px-3 pt-4">
                                <label class="mb-0">Document No <span class=" text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" style="height: 28.36px" placeholder="Enter doc no..." v-model="PRForm.doc_no">
                              </div>
                              <div class="form-group px-3">
                                <label class="mb-0">Document No <span class=" text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" style="height: 28.36px" placeholder="Enter doc no..." v-model="PRForm.doc_no">
                              </div>
                              <button type="button" class="btn btn-primary wd-100p" :class="PRFormDis" @click="addPayment()">
                                <i class="bx bx-plus"></i><span class="mx-1">{{$t('add')}}</span>
                              </button>
                            </div> -->
                        <!-- </td> -->
                        <th class="px-2">ID</th>
                        <th class="px-2">Company</th>
                        <th class="px-2">Document's Dept.</th>
                        <th class="px-2">Document No.</th>
                        <th class="px-2">Date</th>
                        <th class="px-2">Document Type</th>
                        <th class="px-2">Description</th>
                        <th class="px-2 text-end">Amount</th>
                        <th class="px-2">Currency</th>
                        <th class="px-2">Submit To</th>
                        <th class="px-2">Status</th>
                        <th v-if="showCol.created_at" class="px-2 text-muted">created at</th>
                        <th v-if="showCol.created_by" class="px-2 text-muted">created by</th>
                        <th v-if="showCol.updated_at" class="px-2 text-muted">updated at</th>
                        <th v-if="showCol.updated_at" class="px-2 text-muted">updated by</th>
                        <th class="px-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(lst) in pendingData" :key="lst.id" class="cur-pointer" @dblclick="prevPaymentPend(lst.pr_id, lst.approved_seq)">
                        <!-- <td class="p-2 tx-center">{{ inx + 1 }}</td> -->
                        <td class="p-2 add-hover" @click="prevPaymentPend(lst.pr_id, lst.approved_seq)">{{ lst.pr_id}}</td>
                        
                        <td class="p-2">{{ lst.company }}</td>
                        <td class="p-2">{{ lst.doc_dept }}</td>
                        <td class="p-2">{{ lst.doc_no }}</td>
                        <td class="p-2">{{ dateFormat1(lst.date) }}</td>
                        <td class="p-2">{{ lst.doc_type }}</td>
                        <td class="p-2">{{ cutWord(lst.descr) }}</td>
                        <td class="p-2 text-end">{{ numFormat(lst.amount) }}</td>
                        <td class="p-2">{{ lst.currency }}</td>
                        <td class="p-2">{{ lst.submit_to }}</td>
                        <td class="py-0 px-2 align-middle">
                          <span v-if="lst.status == 'Pending'" class="badge rounded-pill bg-warning-gradient tx-11 px-2 py-1">
                            <i class="fe fe-clock"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Approved'" class="badge rounded-pill bg-info-gradient tx-11 px-2 py-1">
                            <i class="fe fe-check-circle"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Verified'" class="badge rounded-pill bg-secondary-gradient tx-11 px-2 py-1">
                            <i class="fe fe-check-circle"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Rejected'" class="badge rounded-pill bg-danger-gradient tx-11 px-2 py-1">
                            <i class="fe fe-x-circle"></i> {{ lst.status }}
                          </span>
                        </td>
                        <td v-if="showCol.created_at" class="p-2 text-muted">{{ dateFormat2(lst.created_at) }}</td>
                        <td v-if="showCol.created_by" class="p-2 text-muted text-capitalize">{{ lst.created_by }}</td>
                        <td v-if="showCol.updated_at" class="p-2 text-muted">{{ dateFormat2(lst.updated_at) }}</td>
                        <td v-if="showCol.updated_by" class="p-2 text-muted text-capitalize">{{ lst.updated_by }}</td>
                        <td class="py-0 px-2 align-middle">
                          <div class="d-inline-flex">
                            <!-- <button class="btn btn-sm btn-icon px-0" style="width: 18px" data-bs-toggle="dropdown" title="View attache file" @click="viewFiles(lst.pr_id)">
                              <i class="mdi mdi-paperclip text-dark " style="font-size: 16px;"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" v-for="i in viewFile" :key="i.id" @click="view(i.file_name)">
                                <i v-if="i.file_name" class="bx bx-file-find me-1"></i>{{ fileName(i.file_name) }}
                              </a>
                            </div> -->

                            <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown" title="More Tools">
                              <i class="bx bx-dots-vertical-rounded text-dark"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <div>
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="prevPaymentPend(lst.pr_id, lst.approved_seq)">
                                <div class="d-flex justify-content-start align-items-center">
                                  <i class="bx bx-search me-1"></i>
                                  <spn>Preview</spn>
                                </div>
                              </a>
                              </div>
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="editPayment(lst.pr_id, lst.status), mode='upd'">
                                <div class="d-flex justify-content-start align-items-center">
                                  <i class="bx bx-edit-alt me-1"></i>
                                  <span>Edit</span>
                                </div>
                              </a>
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="delPayment(lst.pr_id, lst.status)">
                                <div class="d-flex justify-content-start align-items-center">
                                  <i class="bx bx-trash me-1"></i>
                                  <span>Delete</span>
                                </div>
                              </a>
                              <!-- <a v-if="permiss.adm == 1" class="dropdown-item dropdown-hover" href="javascript:void(0)">
                                <div class="d-flex justify-content-start align-items-center tx-danger">
                                  <i class="bx bxs-edit me-1"></i>
                                  <div>Full Edit</div>
                                </div>
                              </a> -->


                              <!-- <div v-if="lst.status =='Pending' && permiss.hqpr_app == 1">
                                <a v-if="lst.submit_to == 'Approval'" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="approved(lst.pr_id, lst.submit_to)">
                                  <i class="bx bx-check-circle me-1"></i>Approve
                                </a>
                                <a v-if="lst.submit_to == 'Verified'" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="approved(lst.pr_id, lst.submit_to)">
                                  <i class="bx bx-check-double me-1"></i>Verified
                                </a>
                                <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="rejected(lst.id)">
                                  <i class="bx bx-block me-1"></i>Reject
                                </a>
                              </div> -->

                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab2">
                <p v-if="approveData.length == 0">There are no records.</p>
                <div v-else class="table-responsive" style="max-height: 75vh">
                  <table class="table mg-b-0 text-nowrap">
                    <thead class="position-sticky bg-white" style="top: 0px; z-index: 1">
                      <tr>
                        <th class="px-2">No.</th>
                        <th class="px-2">Company</th>
                        <!-- <th v-if="permiss.hqpr_all == 1" class="px-2">Department</th> -->
                        <th class="px-2">Department's Doc</th>
                        <th class="px-2">Document No.</th>
                        <th class="px-2">Date</th>
                        <th class="px-2">Document Type</th>
                        <th class="px-2">Description</th>
                        <th class="px-2 text-end">Amount</th>
                        <th class="px-2">Currency</th>
                        <th class="px-2">Submit To</th>
                        <th class="px-2">Status</th>
                        <th v-if="showCol.created_at" class="px-2 text-muted">created at</th>
                        <th v-if="showCol.created_by" class="px-2 text-muted">created by</th>
                        <th v-if="showCol.updated_at" class="px-2 text-muted">updated at</th>
                        <th v-if="showCol.updated_at" class="px-2 text-muted">updated by</th>
                        <th v-if="!showCol.approved_at" class="px-2 text-muted">approved at</th>
                        <th v-if="!showCol.approved_by" class="px-2 text-muted">approved by</th>
                        <th v-if="!showCol.approved_note" class="px-2">approved note</th>
                        <th class="px-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(lst, inx) in approveData" :key="lst.id" class="cur-pointer" @dblclick="prevPayment(lst.id)">
                        <td class="p-2 tx-center">{{ inx + 1 }}</td>
                        <td class="p-2 ">{{ lst.company }}</td>
                        <!-- <td v-if="permiss.hqpr_all == 1" class="p-2 ">{{ lst.dept }}</td> -->
                        <td class="p-2 ">{{ lst.doc_dept }}</td>
                        <td class="p-2 ">{{ lst.doc_no }}</td>
                        <td class="p-2 ">{{ dateFormat1(lst.date) }}</td>
                        <td class="p-2 ">{{ lst.doc_type }}</td>
                        <td class="p-2 ">{{ cutWord(lst.descr) }}</td>
                        <td class="p-2 text-end">{{ numFormat(lst.amount) }}</td>
                        <td class="p-2 ">{{ lst.currency }}</td>
                        <td class="p-2 ">{{ lst.submit_to }}</td>
                        <td class="py-0 px-2 align-middle">
                          <span v-if="lst.status == 'Pending'" class="badge rounded-pill bg-warning-gradient tx-11 px-2 py-1">
                            <i class="fe fe-bell"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Approved'" class="badge rounded-pill bg-info-gradient tx-11 px-2 py-1">
                            <i class="fe fe-check-circle"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Verified'" class="badge rounded-pill bg-secondary-gradient tx-11 px-2 py-1">
                            <i class="fe fe-check-circle"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Rejected'" class="badge rounded-pill bg-danger-gradient tx-11 px-2 py-1">
                            <i class="fe fe-slash"></i> {{ lst.status }}
                          </span>
                        </td>
                        <td v-if="showCol.created_at" class="p-2 text-muted">{{ dateFormat2(lst.created_at) }}</td>
                        <td v-if="showCol.created_by" class="p-2 text-muted text-capitalize">{{ lst.created_by }}</td>
                        <td v-if="showCol.updated_at" class="p-2 text-muted">{{ dateFormat2(lst.updated_at) }}</td>
                        <td v-if="showCol.updated_by" class="p-2 text-muted text-capitalize">{{ lst.updated_by }}</td>
                        <td v-if="!showCol.approved_at" class="p-2 text-muted">{{ dateFormat2(lst.approved_at) }}</td>
                        <td v-if="!showCol.approved_by" class="p-2 text-muted text-capitalize">{{ lst.approved_by }}</td>
                        <td v-if="!showCol.approved_note" class="p-2">{{ cutWord(lst.approved_note) }}</td>
                        <td class="py-0 px-2 align-middle">
                          <div class="d-inline-flex">
                            <button class="btn btn-sm btn-icon px-0" style="width: 18px" data-bs-toggle="dropdown" title="View attache file" @click="viewFiles(lst.id)">
                              <i class="mdi mdi-paperclip text-dark " style="font-size: 16px;"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" v-for="i in viewFile" :key="i.id" @click="view(i.file_name)">
                                <i v-if="i.file_name" class="bx bx-file-find me-1"></i>{{ fileName(i.file_name) }}
                              </a>
                            </div>
                            <button class="btn btn-sm btn-icon px-0" style="width: 18px" data-bs-toggle="dropdown" title="More Tools">
                              <i class="bx bx-dots-vertical-rounded text-dark"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="prevPayment(lst.id)">
                                <i class="bx bx-search me-1"></i>Preview
                              </a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab3">
                <p v-if="rejectData.length == 0">There are no records.</p>
                <div v-else class="table-responsive" style="max-height: 75vh">
                  <table class="table mg-b-0 text-nowrap">
                    <thead class="position-sticky bg-white" style="top: 0px; z-index: 1">
                      <tr>
                        <th class="px-2">No.</th>
                        <th class="px-2">Company</th>
                        <th class="px-2">Department's Doc</th>
                        <th class="px-2">Document No.</th>
                        <th class="px-2">Date</th>
                        <th class="px-2">Document Type</th>
                        <th class="px-2">Description</th>
                        <th class="px-2 text-end">Amount</th>
                        <th class="px-2">Currency</th>
                        <th class="px-2">Submit To</th>
                        <th class="px-2">Status</th>
                        <th v-if="showCol.created_at" class="px-2 text-muted">created at</th>
                        <th v-if="showCol.created_by" class="px-2 text-muted">created by</th>
                        <th v-if="showCol.updated_at" class="px-2 text-muted">updated at</th>
                        <th v-if="showCol.updated_at" class="px-2 text-muted">updated by</th>
                        <th v-if="!showCol.approved_at" class="px-2 text-muted">approved at</th>
                        <th v-if="!showCol.approved_by" class="px-2 text-muted">approved by</th>
                        <th v-if="!showCol.approved_note" class="px-2">approved note</th>
                        <th class="px-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(lst, inx) in rejectData" :key="lst.id" class="cur-pointer" @dblclick="prevPayment(lst.id)">
                        <td class="p-2 tx-center">{{ inx + 1 }}</td>
                        <td class="p-2 ">{{ lst.company }}</td>
                        <td class="p-2 ">{{ lst.doc_dept }}</td>
                        <td class="p-2 ">{{ lst.doc_no }}</td>
                        <td class="p-2 ">{{ dateFormat1(lst.date) }}</td>
                        <td class="p-2 ">{{ lst.doc_type }}</td>
                        <td class="p-2 ">{{ cutWord(lst.descr) }}</td>
                        <td class="p-2 text-end">{{ numFormat(lst.amount) }}</td>
                        <td class="p-2 ">{{ lst.currency }}</td>
                        <td class="p-2 ">{{ lst.submit_to }}</td>
                        <td class="py-0 px-2 align-middle">
                          <span v-if="lst.status == 'Pending'" class="badge rounded-pill bg-warning-gradient tx-11 px-2 py-1">
                            <i class="fe fe-bell"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Approved'" class="badge rounded-pill bg-info-gradient tx-11 px-2 py-1">
                            <i class="fe fe-check-circle"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Verified'" class="badge rounded-pill bg-secondary-gradient tx-11 px-2 py-1">
                            <i class="fe fe-check-circle"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Rejected'" class="badge rounded-pill bg-danger-gradient tx-11 px-2 py-1">
                            <i class="fe fe-slash"></i> {{ lst.status }}
                          </span>
                        </td>
                        <td v-if="showCol.created_at" class="p-2 text-muted">{{ dateFormat2(lst.created_at) }}</td>
                        <td v-if="showCol.created_by" class="p-2 text-muted text-capitalize">{{ lst.created_by }}</td>
                        <td v-if="showCol.updated_at" class="p-2 text-muted">{{ dateFormat2(lst.updated_at) }}</td>
                        <td v-if="showCol.updated_by" class="p-2 text-muted text-capitalize">{{ lst.updated_by }}</td>
                        <td v-if="!showCol.approved_at" class="p-2 text-muted">{{ dateFormat2(lst.approved_at) }}</td>
                        <td v-if="!showCol.approved_by" class="p-2 text-muted text-capitalize">{{ lst.approved_by }}</td>
                        <td v-if="!showCol.approved_note" class="p-2">{{ cutWord(lst.approved_note) }}</td>
                        <td class="py-0 px-2 align-middle">
                          <div class="d-inline-flex">
                            <button class="btn btn-sm btn-icon px-0" style="width: 18px" data-bs-toggle="dropdown" title="View attache file" @click="viewFiles(lst.id)">
                              <i class="mdi mdi-paperclip text-dark " style="font-size: 16px;"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" v-for="i in viewFile" :key="i.id" @click="view(i.file_name)">
                                <i v-if="i.file_name" class="bx bx-file-find me-1"></i>{{ fileName(i.file_name) }}
                              </a>
                            </div>
                            <button class="btn btn-sm btn-icon px-0" style="width: 18px" data-bs-toggle="dropdown" title="More Tools">
                              <i class="bx bx-dots-vertical-rounded text-dark"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="prevPayment(lst.id)">
                                <i class="bx bx-search me-1"></i>Preview
                              </a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /*
              ^ All Data Tab -->
              <div class="tab-pane" id="tab4">
                <p v-if="allData.length == 0">There are no records.</p>
                <div v-else class="table-responsive" style="max-height: 65vh">
                  <table class="table mg-b-0 text-nowrap">
                    <thead class="position-sticky bg-white" style="top: 0px; z-index: 1">
                      <tr>
                        <!-- <th class="px-2">No.</th> -->
                        <th class="px-2 position-sticky bg-white" style="left: 0px">ID</th>
                        <th class="px-2">Company</th>
                        <th class="px-2">Document's Dept.</th>
                        <th class="px-2">Document No.</th>
                        <th class="px-2">Date</th>
                        <th class="px-2">Document Type</th>
                        <th class="px-2">Description</th>
                        <th class="px-2 text-end">Amount</th>
                        <th class="px-2">Currency</th>
                        <th class="px-2">Submit To</th>
                        <th class="px-2">Status</th>
                        <th v-if="showCol.created_at" class="px-2 text-muted">created at</th>
                        <th v-if="showCol.created_by" class="px-2 text-muted">created by</th>
                        <th v-if="showCol.updated_at" class="px-2 text-muted">updated at</th>
                        <th v-if="showCol.updated_at" class="px-2 text-muted">updated by</th>
                        <th class="px-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(lst) in allData" :key="lst.id" class="cur-pointer" @dblclick="prevPayment(lst.pr_id)">
                        <!-- <td class="p-2 tx-center">{{ inx + 1 }}</td> -->
                        <td class="p-2 add-hover position-sticky bg-white" style="left: 0px" title="Clik to preview" @click="prevPayment(lst.pr_id)">{{ lst.pr_id}}</td>
                        <td class="p-2">{{ lst.company }}</td>
                        <td class="p-2">{{ lst.doc_dept }}</td>
                        <td class="p-2">{{ lst.doc_no }}</td>
                        <td class="p-2">{{ dateFormat1(lst.date) }}</td>
                        <td class="p-2">{{ lst.doc_type }}</td>
                        <td class="p-2">{{ cutWord(lst.descr) }}</td>
                        <td class="p-2 text-end">{{ numFormat(lst.amount) }}</td>
                        <td class="p-2">{{ lst.currency }}</td>
                        <td class="p-2">{{ lst.submit_to }}</td>
                        <td class="py-0 px-2 align-middle">
                          <span v-if="lst.status == 'Pending'" class="badge rounded-pill bg-warning-gradient tx-11 px-2 py-1">
                            <i class="fe fe-clock"></i> {{ lst.status }} <span class="fw-semibold">{{ lst.pend_cnt }}</span>
                          </span>
                          <span v-if="lst.status == 'Approved'" class="badge rounded-pill bg-info-gradient tx-11 px-2 py-1">
                            <i class="fe fe-check-circle"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Verified'" class="badge rounded-pill bg-secondary-gradient tx-11 px-2 py-1">
                            <i class="fe fe-check-circle"></i> {{ lst.status }}
                          </span>
                          <span v-if="lst.status == 'Rejected'" class="badge rounded-pill bg-danger-gradient tx-11 px-2 py-1">
                            <i class="fe fe-x-circle"></i> {{ lst.status }}
                          </span>
                        </td>
                        <td v-if="showCol.created_at" class="p-2 text-muted">{{ dateFormat2(lst.created_at) }}</td>
                        <td v-if="showCol.created_by" class="p-2 text-muted text-capitalize">{{ lst.created_by }}</td>
                        <td v-if="showCol.updated_at" class="p-2 text-muted">{{ dateFormat2(lst.updated_at) }}</td>
                        <td v-if="showCol.updated_by" class="p-2 text-muted text-capitalize">{{ lst.updated_by }}</td>
                        <td class="py-0 px-2 align-middle">
                          <div class="d-inline-flex">
                            <!-- <button class="btn btn-sm btn-icon px-0" style="width: 18px" data-bs-toggle="dropdown" title="View attache file" @click="viewFiles(lst.pr_id)">
                              <i class="mdi mdi-paperclip text-dark " style="font-size: 16px;"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" v-for="i in viewFile" :key="i.id" @click="view(i.file_name)">
                                <i v-if="i.file_name" class="bx bx-file-find me-1"></i>{{ fileName(i.file_name) }}
                              </a>
                            </div> -->
                            <button class="btn btn-sm btn-icon btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                              <i class="bx bx-dots-vertical-rounded text-dark"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end m-0 rounded-5 box-shadow-pink">
                              <div>
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="prevPayment(lst.pr_id)">
                                <div class="d-flex justify-content-start align-items-center">
                                  <i class="bx bx-search me-1"></i>
                                  <spn>Preview</spn>
                                </div>
                              </a>
                              </div>
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="editPayment(lst.pr_id, lst.status), mode='upd'">
                                <div class="d-flex justify-content-start align-items-center">
                                  <i class="bx bx-edit-alt me-1"></i>
                                  <span>Edit</span>
                                </div>
                              </a>
                              <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="delPayment(lst.pr_id, lst.approved_seq)">
                                <div class="d-flex justify-content-start align-items-center">
                                  <i class="bx bx-trash me-1"></i>
                                  <span>Delete</span>
                                </div>
                              </a>
                              <!-- <a v-if="permiss.adm == 1" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="editPayment(lst.pr_id, lst.status), mode='upd'">
                                <div class="d-flex justify-content-start align-items-center tx-danger">
                                  <i class="bx bxs-edit me-1"></i>
                                  <div>Full Edit</div>
                                </div>
                              </a> -->

                              <!-- <div v-if="lst.status =='Pending' && permiss.hqpr_app == 1">
                                <a v-if="lst.submit_to == 'Approval'" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="approved(lst.pr_id, lst.submit_to)">
                                  <i class="bx bx-check-circle me-1"></i>Approve
                                </a>
                                <a v-if="lst.submit_to == 'Verified'" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="approved(lst.pr_id, lst.submit_to)">
                                  <i class="bx bx-check-double me-1"></i>Verified
                                </a>
                                <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="rejected(lst.id)">
                                  <i class="bx bx-block me-1"></i>Reject
                                </a>
                              </div> -->

                            </div>
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
      </div>
    </div>

    <!-- /* 
    ^ Modal Add/Edit */-->
    <div class="modal effect-scale" id="modal-add-edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header pb-1 bd-b-0">
            <h6 class="main-content-label text-capitalize text-muted">Payment Requisition <span v-if="PRForm.id" class="tx-primary">ID: {{PRForm.id}}</span></h6>
            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span class="tx-24" aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pt-0">
            <div class="row">
              <div class="col-lg-3 col-12">
                <div class="form-group">
                  <label class="mb-0">Document No <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" placeholder="Enter doc no..." v-model="PRForm.doc_no">
                </div>
              </div>
              <div class="col-xl-6 col-12">
                <div class="form-group">
                  <label class="mb-0">Document Type <span class="text-danger">*</span></label> 
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="PRForm.doc_type" :options="lkDocsType"/>
                </div>
              </div>
              <div class="col-lg-3 col-12">
                <div class="form-group">
                  <label class="mb-0">Date <span class=" text-danger">*</span></label>
                  <input type="date" class="form-control" placeholder="Enter doc no..." v-model="PRForm.date">
                </div>
              </div>
              <div class="col-xl-3 col-12">
                <div class="form-group">
                  <label class="mb-0">{{ $t('company') }} <span class="text-danger">*</span></label> 
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="PRForm.company" :options="lkCompany"/>
                </div>
              </div>
              <div class="col-xl-6 col-12">
                <div class="form-group">
                  <label class="mb-0">Department's Doc <span class="text-danger">*</span></label> 
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="PRForm.doc_dept" :options="lkDept"/>
                </div>
              </div>
              <div class="col-xl-3 col-12">
                <div class="form-group">
                  <label class="mb-0">Submit To <span class="text-danger">*</span></label> 
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="PRForm.submit_to" :options="lkSubmitTo"/>
                </div>
              </div>
              <div class="col-lg-3 col-12">
                <div class="form-group">
                  <label class="mb-0">Amount <span class=" text-danger">*</span></label>
                  <cleave :options="options" placeholder="Enter amount" class="form-control" v-model="PRForm.amount"/>
                </div>
              </div>
              <div class="col-xl-4 col-12">
                <div class="form-group">
                  <label class="mb-0">Currency <span class="text-danger">*</span></label> 
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="PRForm.currency" :options="lkCurrency"/>
                </div>
              </div>
              <div class="col-lg-5 col-12">
                <div class="form-group">
                  <label class="mb-0">Requested By <span class=" text-danger">*</span></label>
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="PRForm.requested_by" :options="lkName"/>
                </div>
              </div>

              <div v-if="permiss.adm == 1 && mode == 'upd'" class="col-lg-3 col-12">
                <div class="form-group">
                  <label class="mb-0">Status <span class=" text-danger">*</span></label>
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="PRForm.status" :options="lkStatus"/>
                </div>
              </div>
              <div v-if="permiss.adm == 1 && mode == 'upd'" class="col-lg-4 col-12">
                <div class="form-group">
                  <label class="mb-0">Approved Level <span class=" text-danger">*</span></label>
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="PRForm.approved_seq" :options="lkLevel"/>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label class="mb-0">Description</label> 
                  <textarea class="form-control" placeholder="Descriptions" v-model="PRForm.descr"></textarea>
                </div>
              </div>
            </div>

            <div class="panel panel-primary tabs-style-2">
              <div class="tab-menu-heading">
                <div class="tabs-menu1">
                  <ul class="nav panel-tabs main-nav-line">
                    <li><a href="#tab5" class="nav-link active" style="padding: 4px 18px" data-bs-toggle="tab">Attachments</a></li>
                    <li><a href="#tab6" class="nav-link" style="padding: 4px 18px" data-bs-toggle="tab">Release Strategy</a></li>
                  </ul>
                </div>
              </div>
              <div class="panel-body tabs-menu-body main-content-body-right" style="padding: 6px 0px">
                <div class="tab-content">

                  <div class="tab-pane active" id="tab5">
                    <div v-if="mode == 'add'">
                      <div v-for="(lst, inx) in tmpFiles" :key="inx">
                        <div class=" d-flex justify-content-start align-items-center">
                          <i class="fe fe-x text-danger cur-pointer" @click="delTmp(inx)" title="Remove"></i>
                          <div class="tx-13 ms-2">{{ lst.name }}</div>
                        </div>
                      </div>
                      <div class="d-flex justify-content-start align-items-center pb-0">
                        <i class="bx bxs-plus-circle tx-primary tx-15 cur-pointer" @click="newFiles1()" title="Add new files"></i>
                        <label class="mb-0 ps-1 cur-pointer add-hover" style="padding-top: 2px" @click="newFiles1()" title="Add new files">Add</label> 
                        <input class="d-none" ref="fileInput1" type="file" multiple @change="addTmp()">
                      </div>
                    </div>   
                    <div v-if="mode == 'upd'">
                      <div v-for="(lst, inx) in attFiles" :key="lst.id">
                        <div class=" d-flex justify-content-start align-items-center">
                          <i class="mdi mdi-file-find text-primary tx-15 cur-pointer" @click="view(lst.file_name)" title="Preview file"></i>
                          <i class="bx bx-trash text-danger tx-15 cur-pointer" @click="delFile(lst.file_name, inx)" title="Delete file"></i>
                          <div class="tx-13 ms-2">{{ fileName(lst.file_name) }}</div>
                        </div>
                      </div>
                      <div class="d-flex justify-content-start align-items-center pb-0">
                        <i class="bx bxs-plus-circle tx-primary tx-15 cur-pointer" @click="newMoreFile()" title="Add new files"></i>
                        <label class="mb-0 ps-1 cur-pointer add-hover" style="padding-top: 2px" @click="newMoreFile()" title="Add new files">Add</label> 
                        <input class="d-none" ref="moreFile" type="file" multiple @change="addMoreFile()">
                      </div>
                    </div> 
                  </div>
                  
                  <div class="tab-pane" id="tab6">
                    <div v-if="mode == 'add'">
                      <div v-for="(lst, inx) in tmpName" :key="inx">
                        <div class=" d-flex justify-content-start ">
                          <div class=" d-flex justify-content-start align-items-center wd-100">
                            <i class="fe fe-x text-danger cur-pointer" @click="delTmpName(inx)" title="Remove"></i>
                            <div class="tx-13 ms-2">L{{ inx + 1 }}. {{ lst.to_do }}</div>
                          </div>
                          <div class="tx-13">{{ lst.approved_by }}</div>
                        </div>
                      </div>
                    </div>
                    <div v-if="mode == 'upd'">
                      <div class="table-responsive">
                        <table v-if="apprName.length > 0" class="table mg-b-0 text-nowrap">
                          <thead>
                            <tr>
                              <th style="padding: 0px 8px 0px 0px">Level</th>
                              <th class="py-0 px-1">To Do</th>
                              <th class="py-0 px-1">Name</th>
                              <th class="py-0 px-1">Date</th>
                              <th class="py-0 px-1">Comments</th>
                              <th class="py-0 px-1">Status</th>
                              <th class="py-0 ps-2 pe-0 wd-60p position-sticky bg-white" style="right: 0px; z-index: 1">Tools</th>
                            </tr>
                          </thead>
                          <tbody class="tx-13">
                            <tr v-for="(lst, inx) in apprName" :key="inx" style="height: 19.5px">
                              <td v-if="lst.id != null" class="py-0 px-0 border-0">L{{ lst.approved_seq }}</td>
                              <td v-if="lst.id == null" class="py-0 ps-1 pe-0 border-0">{{ lst.approved_seq }}</td>
                              <td class="py-0 px-1 border-0">{{ lst.to_do }}</td>
                              <td v-if="test == lst.approved_seq && permiss.appr == 1" class="py-0 ps-0 pe-2 border-0 tx-primary">{{ lst.approved_by }}</td>
                              <td v-else class="py-0 px-1 border-0">{{ lst.approved_by }}</td>
                              <td class="py-0 px-1 border-0">{{ dateFormat2(lst.approved_at) }}</td>
                              <td class="py-0 px-1 border-0">
                                <div v-if="seeMore" class="d-flex justify-content-start">
                                  <div>{{ cutWord2(lst.approved_note) }}</div>
                                  <i v-if="txtLength(lst.approved_note) > 35" class="bx bx-dots-horizontal-rounded cur-pointer add-hover mg-t-3" title="See more" @click="seeMore_click()"></i>
                                </div>
                                <div v-else>{{ lst.approved_note }}</div>
                              </td>
                              <td class="py-0 px-1 border-0"> 
                                <div class="d-flex justify-content-start align-items-center">
                                  <i v-if="lst.approved_status =='Pending'" class="bx bxs-time-five tx-warning tx-15" :title="lst.approvd_status"></i>
                                  <i v-if="lst.approved_status =='Approved' || lst.approved_status =='Verified'" class='bx bxs-check-circle tx-success tx-15'></i>
                                  <i v-if="lst.approved_status =='Rejected'" class="bx bxs-x-circle tx-danger tx-15" :title="lst.approvd_status"></i>
                                  <div class="ms-1">{{ lst.approved_status }}</div>
                                </div>
                              </td>
                              <td class="py-0 ps-2 pe-0 align-middle border-0 position-sticky bg-white" style="right: 0px">

                                <div v-if="lst.approved_status !='Pending' && lst.id != null && permiss.adm == 1" class="d-flex justify-content-start align-items-center">
                                  <i class="bx bx-edit tx-info tx-15 cur-pointer" @click="editApprovedName(lst.id)" title="Edit in database"></i>
                                  <i class="bx bx-trash tx-danger tx-15 cur-pointer" @click="delApproveName(lst.id, inx)" title="Delete from database"></i>
                                </div>

                                <div v-if="lst.approved_status =='Pending'" class="d-flex justify-content-start align-items-center">
                                  <i v-if="lst.id" class="bx bx-edit tx-info tx-15 cur-pointer" @click="editApprovedName(lst.id)" title="Edit in database"></i>
                                  <i class="bx bx-trash tx-danger tx-15 cur-pointer" @click="delApproveName(lst.id, inx)" title="Delete from database"></i>
                                </div>
                                <div v-if="lst.id == null">
                                  <i class="fe fe-x text-danger cur-pointer" @click="delApprName(inx)" title="Remove"></i>
                                </div>

                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="d-flex justify-content-start align-items-center pb-0">
                      <i class="bx bxs-plus-circle tx-primary tx-15 cur-pointer" @click="newApproveName()" title="Add new release strategy"></i>
                      <label class="mb-0 ps-1 cur-pointer add-hover" style="padding-top: 2px" @click="newApproveName()" title="Add new release strategy">Add</label> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
              <button v-if="mode == 'add'" type="button" class="btn btn-primary" :class="addPRFormDis" @click="addPayment()">
                <i class="fe fe-plus"></i><span class="mx-1">{{$t('add')}}</span>
              </button>
              <button v-if="mode == 'upd'" type="button" class="btn btn-primary" :class="updPRFormDis" @click="updPayment()">
                <i class="fe fe-save"></i><span class="mx-1">{{$t('save')}}</span>
              </button>
              <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"><i class="fe fe-x"></i> 
                <span class="mx-1">{{$t('cancel')}}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- /* 
    ^ Modal Approve List */ -->
    <div class="modal effect-scale" id="modal-approve" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header pb-1 bd-b-0">
            <h6 class="text-muted">Release Strategy</h6>
            <button aria-label="Close" class="close" type="button" @click="onCloseAppr1()"><span class="tx-24" aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pt-0">
            <div class="row">
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label class="mb-0">To Do <span class=" text-danger">*</span></label>
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="approvedForm.to_do" :options="lkSubmitTo"/>
                </div>
              </div>
              <div class="col-md-8 col-12">
                <div class="form-group">
                  <label class="mb-0">Name <span class=" text-danger">*</span></label>
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="approvedForm.approved_by" :options="lkName"/>
                </div>
              </div>
            </div>
            <div v-if="mode =='add'">
              <div v-for="(lst, inx) in tmpName" :key="inx">
                <div class=" d-flex justify-content-start ">
                  <div class="d-flex justify-content-start align-items-center wd-100">
                    <i class="fe fe-x text-danger cur-pointer" @click="delTmpName(inx)" title="Remove"></i>
                    <div class="tx-13 ms-2">L{{ inx + 1 }}. {{ lst.to_do }}</div>
                  </div>
                  <div class="tx-13">{{ lst.approved_by }}</div>
                </div>
              </div>
            </div>
            <div v-if="mode =='upd'">
              <div v-for="(lst, inx) in apprName" :key="inx">
                <div class="d-flex justify-content-start ">
                  <div class="d-flex justify-content-start align-items-center wd-100">
                    <i v-if="lst.status =='new'" class="fe fe-x text-danger cur-pointer" @click="delApprName(inx)" title="Remove"></i>
                    <div v-if="lst.status =='new'" class="tx-13 ms-2">L{{ inx + 1 }}. {{ lst.to_do }}</div>
                    <div v-if="lst.status !='new'" class="tx-13">L{{ inx + 1 }}. {{ lst.to_do }}</div>
                  </div>
                  <div class="tx-13">{{ lst.approved_by }}</div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
              <button v-if="mode == 'upd'" type="button" class="btn btn-primary" :class="apprFormDis" @click="addMoreName()">
                <i class="bx bx-add-to-queue"></i><span class="mx-1">Add</span>
              </button>
              <button v-if="mode == 'upd'" type="button" class="btn btn-purple ms-1" :class="apprFormDis" @click="addMoreName2()">
                <i class="bx bx-save"></i><span class="mx-1">Save</span>
              </button>
              <button v-if="mode == 'add'" type="button" class="btn btn-primary" :class="apprFormDis" @click="addTmpName()">
                <i class="bx bx-add-to-queue"></i><span class="mx-1">Add</span>
              </button>
              <button v-if="mode == 'add'" type="button" class="btn btn-purple ms-1" :class="apprFormDis" @click="onCloseAppr2()">
                <i class="bx bx-save"></i><span class="mx-1">{{$t('save')}}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- /* 
    ^ Modal Approved Edit -->
    <div class="modal effect-scale" id="modal-edit-name" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header pb-1 bd-b-0">
            <h6 class="text-muted">Release Strategy</h6>
            <button aria-label="Close" class="close" type="button" @click="onCloseApprEdit()"><span class="tx-24" aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body pt-0">
            <div class="row">
              <div class="col-md-4 col-12">
                <div class="form-group">
                  <label class="mb-0">To Do <span class=" text-danger">*</span></label>
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="approvedForm.to_do" :options="lkSubmitTo"/>
                </div>
              </div>
              <div class="col-md-8 col-12">
                <div class="form-group">
                  <label class="mb-0">Name <span class=" text-danger">*</span></label>
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="approvedForm.approved_by" :options="lkName"/>
                </div>
              </div>
              <div v-if="permiss.adm == 1" class="col-md-4 col-12">
                <div class="form-group">
                  <label class="mb-0">Approved Date</label>
                  <input type="date" class="form-control" v-model="approvedForm.approved_at">
                </div>
              </div>
              <div v-if="permiss.adm == 1" class="col-md-8 col-12">
                <div class="form-group">
                  <label class="mb-0">Approved Status <span class=" text-danger">*</span></label>
                  <Multiselect placeholder="Select" searchable="true" searchStart="true" v-model="approvedForm.approved_status" :options="lkStatus"/>
                </div>
              </div>
              <div v-if="permiss.adm == 1" class="col-12">
                <div class="form-group">
                  <label class="mb-0">Approved Note</label>
                  <textarea class="form-control" style="height: 100px" placeholder="Comments" v-model="approvedForm.approved_note"></textarea>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
              <button type="button" class="btn btn-primary" :class="updApprFormDis" @click="updApprovedName()">
                <i class="fe fe-save"></i><span class="mx-1">Save</span>
              </button>
              <button type="button" class="btn btn-secondary ms-1" :class="updApprFormDis" @click="onCloseApprEdit()">
                <i class="fe fe-x"></i><span class="mx-1">Cancel</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- /*
    ^ Modal preview payment */-->
    <div class="modal effect-scale" id="PRPreview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header pb-1 bd-b-0">
            <span v-if="prevData.status == 'Pending'" class="badge rounded-pill bg-warning-gradient tx-11 px-2 py-1">
              <i class="fe fe-clock"></i> {{ prevData.status }}
            </span>
            <span v-if="prevData.status == 'Approved'" class="badge rounded-pill bg-info-gradient tx-11 px-2 py-1">
              <i class="fe fe-check-circle"></i> {{ prevData.status }}
            </span>
            <span v-if="prevData.status == 'Verified'" class="badge rounded-pill bg-secondary-gradient tx-11 px-2 py-1">
              <i class="fe fe-check-circle"></i> {{ prevData.status }}
            </span>
            <span v-if="prevData.status == 'Rejected'" class="badge rounded-pill bg-danger-gradient tx-11 px-2 py-1">
              <i class="fe fe-x-circle"></i> {{ prevData.status }}
            </span>
            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
              <span class="tx-24" aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body pt-2">
            <div class="row">
              <div class="col-lg-6 col-12">
                <div class="d-flex justify-content-start">
                  <span class="tx-name">ID</span>
                  <span class="tx-data">:<span class="ps-2">{{ prevData.pr_id }}</span></span> 
                </div>
                <div class="d-flex justify-content-start">
                  <span class="tx-name">Document No.</span>
                  <span class="tx-data">:<span class="ps-2">{{ prevData.doc_no }}</span></span> 
                </div>
                <div class="d-flex justify-content-start">
                  <span class="tx-name">Company</span>
                  <span class="tx-data">:<span class="ps-2">{{ prevData.company }}</span></span> 
                </div>
                <div class="d-flex justify-content-start">
                  <span class="tx-name">Department</span>
                  <span class="tx-data">:<span class="ps-2">{{ prevData.doc_dept }}</span></span> 
                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="d-flex justify-content-start">
                  <span class="tx-name">Date</span>
                  <span class="tx-data">:<span class="ps-2">{{ dateFormat1(prevData.date) }}</span></span> 
                </div>
                <div class="d-flex justify-content-start">
                  <span class="tx-name">Document Type</span>
                  <span class="tx-data">:<span class="ps-2">{{ prevData.doc_type }}</span></span> 
                </div>
                <div class="d-flex justify-content-start">
                  <span class="tx-name">Amount</span>
                  <span class="tx-data">:<span class="ps-2 tx-danger tx-bold">{{ numFormat(prevData.amount) }}</span> <span>{{ prevData.currency}}</span></span> 
                </div>
                <div class="d-flex justify-content-start">
                  <span class="tx-name">Requested By</span>
                  <span class="tx-data">:<span class="ps-2">{{prevData.requested_by}}</span></span> 
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-start">
              <span class="tx-name tx-bold py-0">Description</span>
            </div>
            <div class="tx-data py-0">{{ prevData.descr }}</div> 
            
            <h6 class="tx-bold mg-t-20 tx-13 mb-0" style="color: #4D5875">{{ $t('attached') }}</h6>
            <div>
              <div v-for="(lst, inx) in attFiles" :key="inx">
                <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                  <!-- <button class="btn btn-sm btn-icon btn-i p-0" title="Preview Image and PDF file" @click="view(lst.file_name)"> -->
                    <i class="mdi mdi-file-find text-primary cur-pointer tx-15 me-1" @click="view(lst.file_name)"></i>
                  <!-- </button>  -->
                  <span class="add-hover cur-pointer" title="View" @click="view(lst.file_name)">{{inx + 1}}. {{fileName(lst.file_name)}}</span>
                </div>
              </div>
            </div>
            
            <!-- <h6 class="tx-bold mg-t-20 tx-13" style="color: #4D5875">Release Strategy</h6> -->
            <div class="table-responsive mg-t-20">
                <table v-if="apprName.length > 0" class="table mg-b-0 text-nowrap">
                  <thead>
                    <tr>
                      <th style="padding: 0px 8px 0px 0px">Level</th>
                      <th class="py-0 px-1">To Do</th>
                      <th class="py-0 px-1">Name</th>
                      <th class="py-0 px-1">Date</th>
                      <th class="py-0 px-1">Comments</th>
                      <th class="py-0 ps-2 pe-0 wd-60p position-sticky bg-white" style="right: 0px; z-index: 1">Status</th>
                    </tr>
                  </thead>
                  <tbody class="tx-13">
                    <tr v-for="(lst, inx) in apprName" :key="inx">
                      <td class="py-0 px-0 border-0">L{{ lst.approved_seq }}</td>
                      <td class="py-0 px-1 border-0">{{ lst.to_do }}</td>
                      <td v-if="test == lst.approved_seq && permiss.appr == 1" class="py-0 ps-0 pe-2 border-0 tx-primary">{{ lst.approved_by }}</td>
                      <td v-else class="py-0 px-1 border-0">{{ lst.approved_by }}</td>
                      <td class="py-0 px-1 border-0">{{ dateFormat2(lst.approved_at) }}</td>
                      <td class="py-0 px-1 border-0">
                        <div v-if="seeMore" class="d-flex justify-content-start">
                          <div>{{ cutWord2(lst.approved_note) }}</div>
                          <i v-if="txtLength(lst.approved_note) > 35" class="bx bx-dots-horizontal-rounded cur-pointer add-hover mg-t-3" title="See more" @click="seeMore_click()"></i>
                        </div>
                        <div v-else>{{ lst.approved_note }}</div>
                      </td>
                      <td class="py-0 ps-2 pe-0 align-middle border-0 position-sticky bg-white" style="right: 0px"> 
                        <div class="d-flex justify-content-start align-items-center">
                          <i v-if="lst.approved_status =='Pending'" class="bx bxs-time-five tx-warning tx-15" :title="lst.approvd_status"></i>
                          <i v-if="lst.approved_status =='Approved' || lst.approved_status =='Verified'" class='bx bxs-check-circle tx-success tx-15'></i>
                          <i v-if="lst.approved_status =='Rejected'" class="bx bxs-x-circle tx-danger tx-15" :title="lst.approvd_status"></i>
                          <div class="ms-1">{{ lst.approved_status }}</div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-if="test != null && permiss.appr == 1">
                <h6 class="tx-bold mg-t-20 tx-13" style="color: #4D5875">{{ prevData.submit_to =='Approval' ? 'Approve' : 'Verified' }}/Reject Comments</h6>
                  <div class="form-group">
                    <textarea class="form-control" placeholder="Comments" v-model="approvedForm.approved_note"></textarea>
                  </div>
                <div class="d-flex justify-content-end">
                  <button v-if="prevData.submit_to == 'Approval'" type="button" class="btn btn-primary" @click="approved_cf(prevData.pr_id, prevData.to_do, prevData.approved_seq, prevData.id)"><i class="fe fe-check-circle"></i> 
                    <span class="mx-1">Approve</span>
                  </button>
                  <button v-if="prevData.submit_to == 'Verified'" type="button" class="btn btn-primary ms-1" @click="approved_cf(prevData.pr_id, prevData.to_do, prevData.approved_seq, prevData.id)"><i class="fe fe-check-circle"></i> 
                    <span class="mx-1">Verified</span>
                  </button>
                  <button  type="button" class="btn btn-danger ms-1" @click="rejected_cf(prevData.pr_id, prevData.approved_seq, prevData.id)"><i class="fe fe-slash"></i> 
                    <span class="mx-1">Reject</span>
                  </button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <!-- /* 
    ^ Modal show columns */-->
    <div class="modal fade effect-scale bd-0" id="showColumn" data-bs-keyboard="false" tabindex="-1" aria-labelledby="colShowLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header pb-1 bd-b-0">
            <h6 class="text-muted main-content-label text-capitalize">Show / Hide Columns</h6>
          </div>
          <div class="modal-body pt-0">
            <div class="d-flex justify-content-between mb-2">
              <span>Created At</span>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="created_at" v-model="showCol.created_at" @change='$emit("input", $event.target.checked)'/>
                <label class="custom-control-label cur-pointer" for="created_at"></label>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Created By</span>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="created_by" v-model="showCol.created_by" @change='$emit("input", $event.target.checked)'/>
                <label class="custom-control-label cur-pointer" for="created_by"></label>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Updated At</span>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="updated_at" v-model="showCol.updated_at" @change='$emit("input", $event.target.checked)'/>
                <label class="custom-control-label cur-pointer" for="updated_at"></label>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Updated By</span>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="updated_by" v-model="showCol.updated_by" @change='$emit("input", $event.target.checked)'/>
                <label class="custom-control-label cur-pointer" for="updated_by"></label>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Approved At</span>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="approved_at" v-model="showCol.approved_at" @change='$emit("input", $event.target.checked)'/>
                <label class="custom-control-label cur-pointer" for="approved_at"></label>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Approved By</span>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="approved_by" v-model="showCol.approved_by" @change='$emit("input", $event.target.checked)'/>
                <label class="custom-control-label cur-pointer" for="approved_by"></label>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Approved Note</span>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="approved_note" v-model="showCol.approved_note" @change='$emit("input", $event.target.checked)'/>
                <label class="custom-control-label cur-pointer" for="approved_note"></label>
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
  name: 'XpplAppHQPaymentRequisition',

  data() {
    return {
      profile: [],
      lookupData: [],
      lkCompany: [],
      lkDocsType: [],
      lkSubmitTo: [],
      lkCurrency: [],
      lkDept: [],
      lkName: [],
      lkYear: [],
      lkStatus: [],
      lkLevel: [],

      permiss: [],
      allFile: [],
      viewFile: [],
      tmpFiles: [],
      attFiles: [],
      allData: [],
      pendingData: [],
      approveData: [],
      rejectData: [],
      apprData: [],
      tmpName: [],
      apprName: [],
      prevData:'',

      PRForm: {id: "", doc_no: "", company: null, doc_dept: "", date: "", doc_type: null, descr: "", amount: "", currency: null, submit_to: null, requested_by: null, status: null, approved_seq: null},
      approvedForm: {id: '', to_do: null, approved_by: null, approved_at: '', approved_status: null, approved_note: ""},
      showCol: {created_at: false, created_by: false, updated_at: false, updated_by: false, approved_at: false, approved_by: false, approved_note: false},

      year: '',
      dept: '',
      search: '',
      actTab: 'pen',
      btnClear: false,
      loading: false,
      mode: '',

      options: {
        numeral: true,
        numeralPositiveOnly: true,
        noImmediatePrefix: true,
        rawValueTrimPrefix: true,
        numeralIntegerScale: 15,
        numeralDecimalScale: 2,
        numeralDecimalMark: '.',
        delimiter: ','
			},
      
      test: '',
      seeMore: true
    };
  },

  mounted() {
    
  },

  computed: {
    addPRFormDis(){
      if (this.PRForm.doc_no == "" || this.PRForm.company == null || this.PRForm.doc_dept == null || this.PRForm.date == "" || this.PRForm.doc_type == null || this.PRForm.amount == "" || this.PRForm.currency == null || this.PRForm.submit_to == null || this.PRForm.requested_by == null || this.tmpName.length == 0) {
        return "disabled";
      } else {
        return "";
      }
    },

    updPRFormDis(){
      if (this.PRForm.doc_no == "" || this.PRForm.company == null || this.PRForm.doc_dept == null || this.PRForm.date == "" || this.PRForm.doc_type == null || this.PRForm.amount == "" || this.PRForm.currency == null || this.PRForm.submit_to == null || this.PRForm.requested_by == null || this.PRForm.status == null || this.PRForm.approved_seq == null || this.apprName.length == 0) {
        return "disabled";
      } else {
        return "";
      }
    },
    
    apprFormDis(){
      if (this.approvedForm.to_do == null || this.approvedForm.approved_by == null) {
        return  "disabled";
      } else {
        return "";
      }
    },

    updApprFormDis(){
      if (this.approvedForm.to_do == null || this.approvedForm.approved_by == null || this.approvedForm.approved_status == null) {
        return  "disabled";
      } else {
        return "";
      }
    }
  },

  methods: {
    async onLoad(){
      this.loading = true;
      this.year = new Date().getFullYear();

      // const pro = await axios.get('api/profile')
			// this.dept = pro.data.department;

      const prof = await axios.get("api/profile");
      this.profile = prof.data;

      const perm = await axios.get('/api/hqpayment/prpermission')
      this.permiss = perm.data;

      const pend = await this.getPending();

      this.loading = false;

      const alld = await this.getAllData();
      const appr = await this.getApproved();
      const reje = await this.getRejected();
      const file = await this.getAllFiles();
      const name = await this.getApprovedList();

      const nameList = await axios.get("/api/hqpayment/namelist")
      this.lkName = nameList.data;

      const year = await axios.get('/api/lookup/year')
      this.lkYear = year.data;


      


      const lookup = await axios.get('/api/hqpayment/lookup')
      this.lookupData = lookup.data;


    },

    async getAllData(){
      const allData = await axios.post('/api/hqpayment/getalldata', {
        comp: this.permiss.company,
        dept: this.permiss.dept,
        year: this.year,
        search: this.search
      });
      this.allData = allData.data;
    },

    async getPending(){
      const pend = await axios.post('/api/hqpayment/getpending', {
        appr: this.permiss.appr,
        fullname: this.profile.name +' '+ this.profile.surname,
        comp: this.permiss.company,
        dept: this.permiss.dept,
        year: this.year,
        search: this.search
      })
      this.pendingData = pend.data;
    },

    async getApproved(){
      const approve = await axios.post('/api/hqpayment/getapproved', {
        comp: this.permiss.company,
        dept: this.permiss.dept,
        year: this.year,
        search: this.search
      })
      this.approveData = approve.data;
    },

    async getRejected(){
      const reject = await axios.post('/api/hqpayment/getrejected', {
        comp: this.permiss.company,
        dept: this.permiss.dept,
        year: this.year,
        search: this.search
      })
      this.rejectData = reject.data;
    },

    async getAllFiles(){
      const allfile = await axios.post('/api/hqpayment/getallfile', {
        user: this.permiss.user_type,
        comp: this.permiss.company,
        dept: this.permiss.dept,
        year: this.year
      });
      this.allFile = allfile.data;
    },

    async getApprovedList(){
      const appName = await axios.post('/api/hqpayment/getapprovelist', {
        user: this.permiss.user_type,
        comp: this.permiss.company,
        dept: this.permiss.dept,
        year: this.year
      });
      this.apprData = appName.data;
    },

    viewFiles(id){
      let file = this.allFile.filter((i) => i.pr_id == id);
      this.viewFile = file;
    },

    view(filename){
      window.open(window.location.origin+'/assets/HQPayment/'+ filename, "_blank")
    },

    seeMore_click(){
      this.seeMore = false;
    },

    newApproveName(){
      $('#modal-add-edit').modal('hide');
      this.tmpNameShow = false
      this.approvedForm.to_do = null
      this.approvedForm.approved_by = null
      $("#modal-approve").modal("show");
    },

    onCloseAppr1(){
      $('#modal-approve').modal('hide');
      $('#modal-add-edit').modal('show');
    },

    onCloseAppr2(){
      if (this.tmpName.find((i) => i.approved_by == this.approvedForm.approved_by)) {
        alert("Duplicate name!");
      } else {
        this.tmpName.push({
          'to_do': this.approvedForm.to_do,
          'approved_by': this.approvedForm.approved_by
        });
        this.approvedForm.approved_by = null;
        $('#modal-approve').modal('hide');
        $('#modal-add-edit').modal('show');
      }
    },

    addTmpName(){
      if (this.tmpName.find((i) => i.approved_by == this.approvedForm.approved_by)) {
        alert("Duplicate name!");
      } else {
        this.tmpName.push({
          'to_do': this.approvedForm.to_do,
          'approved_by': this.approvedForm.approved_by,
        });
        this.approvedForm.approved_by = null;
      }
    },

    delTmpName(inx){
      this.tmpName.splice(inx, 1);
    },

    delApprName(inx){
      this.apprName.splice(inx, 1);
    },

    addMoreName(){
      if (this.apprName.find((i) => i.approved_by == this.approvedForm.approved_by)) {
        alert("Duplicate name!");
      } else {
        this.apprName.push({
          'to_do': this.approvedForm.to_do,
          'approved_by': this.approvedForm.approved_by,
          'status': 'new'
        });
        this.approvedForm.approved_by = null;
      }
    },

    addMoreName2(){
      if (this.apprName.find((i) => i.approved_by == this.approvedForm.approved_by)) {
        alert("Duplicate name!");
      } else {
        this.apprName.push({
          'to_do': this.approvedForm.to_do,
          'approved_by': this.approvedForm.approved_by,
          'status': 'new'
        });
        this.approvedForm.approved_by = null;
        $("#modal-approve").modal("hide");
        $("#modal-add-edit").modal("show");
      }
    },

    delApproveName(lst_id, inx){
      $("#modal-add-edit").modal("hide");
      this.$swal.fire({
          text: "Do you want to delete this name?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: '<i class="fe fe-trash-2"></i> <span class="font-weight-light">Delete</span>',
          cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
          confirmButtonColor: '#d33',
          allowEnterKey: false,
          allowOutsideClick: false,
      }).then((result)=>{
          if(result.isConfirmed){
            this.apprName.splice(inx, 1)
            this.$axios.post('/api/hqpayment/delapprovename', {
              id: lst_id,
              appr: this.apprName
            })
            .then(res => {
              this.getApprovedList();
            })
          } 
          $("#modal-add-edit").modal("show");
      });
    },

    prevPayment(id, seq){
      let data = this.allData.find((i) => i.pr_id == id);
      this.prevData = data;

      this.attFiles = [];
      let file = this.allFile.filter((i) => i.pr_id == id);
      this.attFiles = file;

      this.apprName = [];
      let appr = this.apprData.filter((i) => i.pr_id == id);
      this.apprName = appr;

      this.test = seq;
      this.seeMore = true;

      this.approvedForm.approved_note = "";
      $('#PRPreview').modal('show');
    },

    prevPaymentPend(id, seq){
      let data = this.pendingData.find((i) => i.pr_id == id);
      this.prevData = data;

      this.attFiles = [];
      let file = this.allFile.filter((i) => i.pr_id == id);
      this.attFiles = file;

      this.apprName = [];
      let appr = this.apprData.filter((i) => i.pr_id == id);
      this.apprName = appr;

      this.test = seq;
      this.seeMore = true;

      this.approvedForm.approved_note = "";
      $('#PRPreview').modal('show');
    },

    onCloseApprEdit(){
      $('#modal-edit-name').modal('hide');
      $('#modal-add-edit').modal('show');
    },

    editApprovedName(id){

      let data = this.apprData.find((i) => i.id == id);
      this.approvedForm.id = id;
      this.approvedForm.to_do = data.to_do;
      this.approvedForm.approved_by = data.approved_by;
      this.approvedForm.approved_note = data.approved_note;
      this.approvedForm.approved_status = data.approved_status;

      if (data.approved_at){
        this.approvedForm.approved_at = moment(data.approved_at).format("YYYY-MM-DD");
      } else {
        this.approvedForm.approved_at = '';
      }

      $('#modal-add-edit').modal('hide');
      $('#modal-edit-name').modal('show');
    },

    updApprovedName(){
      $('#modal-edit-name').modal('hide');
      this.$swal.fire({
          text: "Do you want to save?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: '<i class="fe fe-save"></i> <span class="font-weight-light">Save</span>',
          cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
          allowEnterKey: false,
          allowOutsideClick: false,
      }).then((result)=>{
          if(result.isConfirmed){
            
              this.$axios.post('/api/hqpayment/updapprovename', this.approvedForm)
              .then(res => {
                
                $('#modal-add-edit').modal('show');
                let f = this.apprName.find((i) => i.id == this.approvedForm.id);
                f.to_do = this.approvedForm.to_do;
                f.approved_by = this.approvedForm.approved_by;
                f.approved_at = this.approvedForm.approved_at;
                f.approved_note = this.approvedForm.approved_note;
                f.approved_status = this.approvedForm.approved_status;

                this.getApprovedList();
              })
            
          } else {
            $('#modal-edit-name').modal('show');
          }
      });
    },

    editPayment(id, status){
      if (this.permiss.adm != 1 && !!!parseInt(this.permiss.edit)){
        this.$swal.fire({
          text: "You are not authorized to Edit!",
          icon: 'error',
          showConfirmButton:false,
          showCloseButton: true,
          allowEnterKey: false
        })
      } else {
        if (this.permiss.adm == 1 || status == "Pending") {
          this.PRFormClear();
          this.seeMore = true;

          let data = this.allData.find((i) => i.pr_id == id);
          this.PRForm.id = id;
          this.PRForm.doc_no = data.doc_no;
          this.PRForm.company = data.company;
          this.PRForm.doc_dept = data.doc_dept;
          this.PRForm.date = moment(data.date).format("YYYY-MM-DD");
          this.PRForm.doc_type = data.doc_type;
          this.PRForm.descr = data.descr;
          this.PRForm.amount = data.amount;
          this.PRForm.currency = data.currency;
          this.PRForm.submit_to = data.submit_to;
          this.PRForm.requested_by = data.requested_by;
          this.PRForm.status = data.status;
          this.PRForm.approved_seq = data.approved_seq;

          let file = this.allFile.filter((i) => i.pr_id == id);//attached files
          this.attFiles = file;

          let name = this.apprData.filter((i) => i.pr_id == id);//approve name
          this.apprName = name;

          $("#modal-add-edit").modal("show");
        } else {
          this.$swal.fire({
            icon: 'error',
            text: `This record could not be changed; it has been ${status}`,
            showConfirmButton:false,
            showCloseButton: true,
            allowEnterKey: false
          })
        }
      }
    },

    updPayment(){
      $("#modal-add-edit").modal("hide");
      this.$swal.fire({
          text: "Do you want to save?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: '<i class="fe fe-save"></i> <span class="font-weight-light">Save</span>',
          cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
          allowEnterKey: false,
          allowOutsideClick: false,
      }).then((result)=>{
          if(result.isConfirmed){
            this.$axios.post('/api/hqpayment/updpayment', {
              data: this.PRForm,
              appr: this.apprName
            })
            .then(res => {
              this.getAllData();
              this.getPending();
              this.getApprovedList();
            })
          } else {
            $("#modal-add-edit").modal("show");
          }
      });
    },

    delFile(file, inx){
      $("#modal-add-edit").modal("hide");
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
            this.$axios.post(`api/hqpayment/delfile/${file}`)
            .then(res => {
              this.attFiles.splice(inx,1)
              this.getAllFiles();
            })
          } 
          $("#modal-add-edit").modal("show");
      });
    },

    rejected_cf(prid, seq, id){
      $('#PRPreview').modal('hide');
      this.$swal.fire({
        text: "Do you want to reject this requested?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fe fe-slash"></i> <span class="font-weight-light">Yes, reject</span></span>',
        cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">No, cancel</span>',
        confirmButtonColor: '#d33',
        allowEnterKey: false,
        allowOutsideClick: false,
      })
      .then((result) => {
        if(result.isConfirmed){
          this.$axios.post('/api/hqpayment/rejected', {
            pr_id: prid,
            appr_seq: seq,
            appr_id: id,
            note: this.approvedForm.approved_note
          })
          .then(res => {
            this.getAllData();
            this.getPending();
            this.getApproved();
            this.getRejected();
            this.getApprovedList();
          })
        } else {
          $('#PRPreview').modal('show');
        }
      });
    },

    rejected(id){
      this.$swal.fire({
        text: "Are you sure? you want to reject this requested",
        showCancelButton: true,
        confirmButtonText: '<i class="fe fe-slash"></i> <span class="font-weight-light">Reject</span>',
        cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">Cancel</span>',
        confirmButtonColor: '#d33',
        allowEnterKey: false,
        allowOutsideClick: false,
        input: 'textarea',
        inputAttributes: {
          autocapitalize: 'off',
          placeholder: "Reject comments"
        },
      })
      .then((result) => {
        if (result.isConfirmed) {
          this.$axios.post('/api/hqpayment/rejected', {
              id: id,
              note: result.value
          })
          .then(res => {
            this.getAllData();
            this.getPending();
            this.getApproved();
            this.getRejected();
            this.getApprovedList();
          })
        }
      })
    },

    approved_cf(prid, submit, seq, id){
      $('#PRPreview').modal('hide');
      this.$swal.fire({
        text: `Do you want to ${submit == "Approval" ? "approve" : "verify"} this requested?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `<i class="fe fe-check-circle"></i> <span class="font-weight-light">Yes, ${submit == "Approval" ? "approve" : "verify"}</span></span>`,
        cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">No, cancel</span>',
        allowEnterKey: false,
        allowOutsideClick: false,
      }).then((result) => {
        if(result.isConfirmed){
          this.$axios.post('/api/hqpayment/approved', {
            pr_id: prid,
            to_do: submit,
            appr_seq: seq,
            appr_id: id,
            note: this.approvedForm.approved_note
          })
          .then(res => {
            this.getAllData();
            this.getPending();
            this.getApproved();
            this.getRejected();
            this.getApprovedList();
          })
        } else {
          $('#PRPreview').modal('show');
        }
      });
    },

    approved(id, submit){
      this.$swal.fire({
        text: `Are you sure? you want to ${submit == "Approval" ? "approve" : "verify"} this requested.`,
        showCancelButton: true,
        confirmButtonText: `<i class="fe fe-check-circle"></i> <span class="font-weight-light">Yes, ${submit == "Approval" ? "approve" : "verify"}</span>`,
        cancelButtonText: '<i class="fe fe-x"></i> <span class="font-weight-light">No, cancel</span>',
        allowEnterKey: false,
        allowOutsideClick: false,
        input: 'textarea',
        inputAttributes: {
          autocapitalize: 'off',
          placeholder: `${submit == "Approval" ? "Approve" : "Verify"} comments`
        },
      }).then((result) => {
        if (result.isConfirmed) {
          this.$axios.post('api/hqpayment/approved', {
              id: id,
              status: submit,
              note: result.value
          })
          .then(res => {
            this.getAllData();
            this.getPending();
            this.getApproved();
            this.getRejected();
            this.getApprovedList();
          })
        }
      })
    },

    showCols(){
      $('#showColumn').modal('show');
    },

    searchInput(){
      if(this.search.length >0){
        this.btnClear = true;
      } else {
        this.btnClear = false;
      }
    },

    searchEnter(){
      if(this.search.length >0){
        this.btnClear = true;
        if (this.actTab == "pen") {
          this.getPending();
        } else if (this.actTab == "app") {
          this.getApproved();
        } else if (this.actTab == "rej") {
          this.getRejected();
        } else {
          this.getAllData();
        }
      } else {
        this.btnClear = false;
        this.getPending();
        this.getApproved();
        this.getRejected();
        this.getAllData();
      }
    },

    searchClear(){
      this.search = '';
      this.btnClear = false;
      this.getPending();
      this.getApproved();
      this.getRejected();
      this.getAllData();
    },

    async newPayment(){
      if (this.permiss.adm != 1 && !!!parseInt(this.permiss.new)){
        this.$swal.fire({
          text: "You are not authorized to Add!",
          icon: 'error',
          showConfirmButton:false,
          showCloseButton: true,
          allowEnterKey: false
        })
      } else {
        this.PRFormClear();
        this.PRForm.doc_dept = this.dept;

        // let rand = Math.floor(Math.random() * 90000);
        // let newid = String(rand).padStart(5, '0')
        // const ck = await axios.get(`/api/hqpayment/checkid?id=${newid}`)
        // if (ck.data.success){
        //   this.PRForm.id = newid;

          $("#modal-add-edit").modal("show");

        // } else {
        //   alert(ck.data.message);
        // }
      }
    },
   
    async addPayment(){
      $("#modal-add-edit").modal("hide");
      const add = await axios.post('/api/hqpayment/addpayment', this.PRForm)
      if(add.data.success){
        let fd = new FormData();
        for (let i = 0; i < this.tmpFiles.length; i++){ 
          let file = this.tmpFiles[i];
          fd.append('files[' + i + ']', file);
          fd.append('id', add.data.id);
        }
        const doc = await axios.post('/api/hqpayment/addfiles', fd, {headers:{"Content-Type": "multipart/form-date"}})
        const name = await axios.post("/api/hqpayment/addapprovename", {
          list: this.tmpName,
          id: add.data.id
        })
      
        this.getPending();
        this.getAllData();
        this.getAllFiles();
        this.getApprovedList();

      } else {
        alert(add.data.message)
      };
    },

    delPayment(id, seq){
      if (this.permiss.adm != 1 && !!!parseInt(this.permiss.del)){
        this.$swal.fire({
          text: "You are not authorized to Delete!",
          icon: 'error',
          showConfirmButton:false,
          showCloseButton: true,
          allowEnterKey: false
        })
      } else {
        if (seq != 0 && seq == 1){
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
              this.$axios.post(`/api/hqpayment/delpayment/${id}`)
              .then((response)=>{
                this.getAllData();
                this.getPending();
              }).catch((error)=>{
                console.log(error);
              })
            }
          })
        } else {
          this.$swal.fire({
            icon: 'error',
            text: "This record could not be deleted!",
            showConfirmButton:false,
            showCloseButton: true,
            allowEnterKey: false
          })
        }
      }
    },

    PRFormClear(){
      this.PRForm.id = "";
      this.PRForm.company = null;
      this.PRForm.doc_dept = null;
      this.PRForm.doc_no = "";
      this.PRForm.date = "";
      this.PRForm.doc_type = null;
      this.PRForm.descr = "";
      this.PRForm.amount = "";
      this.PRForm.currency = null;
      this.PRForm.submit_to = null;
      this.PRForm.requested_by = null;
      this.tmpFiles = [];
      this.tmpName = [];
    },

    newMoreFile(){
      this.$refs.moreFile.click();
    },

    addMoreFile(){
      let fd = new FormData();
      for (let i = 0; i < this.$refs.moreFile.files.length; i++){
        let file = this.$refs.moreFile.files[i];
        fd.append('files[' + i + ']', file);
        fd.append('id', this.PRForm.id);
        this.attFiles.push({'file_name': this.PRForm.id +'-'+ this.$refs.moreFile.files[i].name}); //Show in the form
      }
      this.$axios.post('/api/hqpayment/addfiles', fd, {headers:{"Content-Type": "multipart/form-date"}})
      .then(res => {
        this.getAllFiles();
      }) // Add in to db
    },

    newFiles1(){
      this.$refs.fileInput1.click();
    },

    addTmp(){
      for (let i = 0; i < this.$refs.fileInput1.files.length; i++){
        this.tmpFiles.push(this.$refs.fileInput1.files[i]);
      }
    },

    delTmp(inx){
      this.tmpFiles.splice(inx,1);
    },
    
    getLookup(){
      this.$axios.get('/api/hqpayment/lookup')
      .then(res => {
        this.lookupData = res.data;

        let data = [
          {cate:'Company', descr: 'code', name:'lkCompany'},
          {cate:'Document Type', descr: 'code', name:'lkDocsType'},
          {cate:'Submit To', descr: 'code', name:'lkSubmitTo'},
          {cate:'Currency', descr: 'descr_eng', name:'lkCurrency'},
          {cate:'Department', descr: 'code', name:'lkDept'},
          {cate:'Status', descr: 'code', name:'lkStatus'},
          {cate:'Approve Level', descr: 'descr_eng', name:'lkLevel'},
        ];

          for (let j = 0; j < data.length; j++){
            let cateName = data[j].cate;
            let colDescr = data[j].descr;
            let lkName = data[j].name;

            let item  = this.lookupData.filter((i)=>i.category == [cateName]);
            for (let i = 0; i < item.length; i++){
              this[lkName].push({
                value: item[i].code,
                label: item[i][colDescr]
              });
            };
          };
      });
    },

    numFormat(value) {
			let val = (value / 1).toFixed(2).replace(",", ".");
			return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		},

    dateFormat1(value) {
      if(value) {
        return moment(value).format('DD-MM-YYYY');
      }
    },

    dateFormat2(value) {
      if(value) {
        return moment(value).format('DD-MM-YYYY HH:mm');
      }
    },

    fileName(filename){
      if (filename){
        let txt1 = filename.split('-')[0]
          return filename.split(txt1 +'-')[1];
      };
    },

    cutWord(text){
      if (!text == "") {
        if(text.length > 25){
          return text.substring(0,25) + '...';
        } else {
          return text;
        }
      }
		},

    cutWord2(text){
      if (!text == "") {
        if(text.length > 35){
          return text.substring(0, 35);
        } else {
          return text;
        }
      }
		},

    txtLength(text){
      if (!text == "") {
        return text.length;
      }
		}
  },

  created() {
    this.onLoad();
    this.getLookup();
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
  .tabs-style-2 li .nav-link.active{
    box-shadow: inset 0 0px 0px;
    border-top: 1px solid #e2e8f5;
  }
</style>