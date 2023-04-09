<template>
  <div v-if="loading">
      <loading/>
  </div> 
  <div v-else>
    <div class="card">
      <div class="card-body">
        <div class="breadcrumb-header justify-content-between align-items-center mb-1 mt-0" >
          <div>
            <h4 class="card-title mg-b-0">PAYMENT REQUISITIONS</h4>
            <p class="tx-12 tx-gray-500 mb-0">Pending, rejected and approved payment requisitons...</p>
          </div>
          <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="pos-relative wd-md-150 wd-50p">
              <input class="form-control" style="padding-left: 25px" type="text" :placeholder="$t('search')" v-model="search" @input="searchInput()" >
                <i class="fe fe-search search-i text-muted"></i>
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

        <div class="panel panel-primary tabs-style-3 border-0 px-0">
          <div class="tab-menu-heading">
            <div class="tabs-menu ">
              <div class=" d-flex justify-content-between align-items-center">
                <ul class="nav panel-tabs">
                  <li @click="actTab = 'pen'"><a href="#tab1" class="active mb-0" data-bs-toggle="tab" style="padding: 6px 18px"><i class="fe fe-bell"></i> Pending</a></li>
                  <li @click="actTab = 'app'"><a href="#tab2" class="mb-0" data-bs-toggle="tab" style="padding: 6px 18px"><i class="fe fe-check-circle"></i> Approved</a></li>
                  <li @click="actTab = 'rej'"><a href="#tab3" class="mb-0" data-bs-toggle="tab" style="padding: 6px 18px"><i class="fe fe-slash"></i> Rejected</a></li>
                  <li @click="actTab = 'all'"><a href="#tab4" class="mb-0" data-bs-toggle="tab" style="padding: 6px 18px"><i class="fe fe-database"></i> All Data</a></li>
                </ul>
                  <div>
                    <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
                      <i class="mdi mdi-dots-vertical text-gray" style="font-size: 15px"></i>
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
          <div class="panel-body tabs-menu-body px-0">
            <div class="tab-content">
              <div class="tab-pane active" id="tab1">
                <p v-if="pendingData.length == 0">There are no records.</p>
                <div v-else class="table-responsive" style="max-height: 75vh">
                  <table class="table mg-b-0 text-nowrap">
                    <thead class="position-sticky bg-white" style="top: 0px; z-index: 1">
                      <tr>
                        <th class="px-2">No.</th>
                        <th class="px-2">Company</th>
                        <th v-if="permiss.hqpr_all == 1" class="px-2">Department</th>
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
                        <th v-if="showCol.approved_at" class="px-2 text-muted">approved at</th>
                        <th v-if="showCol.approved_by" class="px-2 text-muted">approved by</th>
                        <th v-if="showCol.approved_note" class="px-2">approved note</th>
                        <th class="px-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(lst, inx) in pendingData" :key="lst.id">
                        <td class="p-2 tx-center">{{ inx + 1 }}</td>
                        <td class="p-2 ">{{ lst.company }}</td>
                        <td v-if="permiss.hqpr_all == 1" class="p-2 ">{{ lst.dept }}</td>
                        <td class="p-2 ">{{ lst.doc_no }}</td>
                        <td class="p-2 ">{{ dateFormat1(lst.pr_date) }}</td>
                        <td class="p-2 ">{{ lst.docs_type }}</td>
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
                        <td v-if="showCol.approved_at" class="p-2 text-muted">{{ dateFormat2(lst.approved_at) }}</td>
                        <td v-if="showCol.approved_by" class="p-2 text-muted text-capitalize">{{ lst.approved_by }}</td>
                        <td v-if="showCol.approved_note" class="p-2">{{ cutWord(lst.approved_note) }}</td>
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
                              <a v-if="permiss.hqpr_edit == 1" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="editPayment(lst.id, lst.status), mode='upd'">
                                <i class="bx bx-edit-alt me-1"></i>Edit
                              </a>
                              <a v-if="permiss.hqpr_del == 1" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="delPayment(lst.id, lst.status)">
                                <i class="bx bx-trash me-1"></i>Delete
                              </a>
                              <div v-if="lst.status =='Pending' && permiss.hqpr_app == 1">
                                <a v-if="lst.submit_to == 'Approval'" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="approved(lst.id, lst.submit_to)">
                                  <i class="bx bx-check-circle me-1"></i>Approve
                                </a>
                                <a v-if="lst.submit_to == 'Verified'" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="approved(lst.id, lst.submit_to)">
                                  <i class="bx bx-check-double me-1"></i>Verified
                                </a>
                                <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="rejected(lst.id)">
                                  <i class="bx bx-block me-1"></i>Reject
                                </a>
                              </div>
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
                        <th v-if="permiss.hqpr_all == 1" class="px-2">Department</th>
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
                        <th v-if="showCol.approved_at" class="px-2 text-muted">approved at</th>
                        <th v-if="showCol.approved_by" class="px-2 text-muted">approved by</th>
                        <th v-if="showCol.approved_note" class="px-2">approved note</th>
                        <th class="px-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(lst, inx) in approveData" :key="lst.id">
                        <td class="p-2 tx-center">{{ inx + 1 }}</td>
                        <td class="p-2 ">{{ lst.company }}</td>
                        <td v-if="permiss.hqpr_all == 1" class="p-2 ">{{ lst.dept }}</td>
                        <td class="p-2 ">{{ lst.doc_no }}</td>
                        <td class="p-2 ">{{ dateFormat1(lst.pr_date) }}</td>
                        <td class="p-2 ">{{ lst.docs_type }}</td>
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
                        <td v-if="showCol.approved_at" class="p-2 text-muted">{{ dateFormat2(lst.approved_at) }}</td>
                        <td v-if="showCol.approved_by" class="p-2 text-muted text-capitalize">{{ lst.approved_by }}</td>
                        <td v-if="showCol.approved_note" class="p-2">{{ cutWord(lst.approved_note) }}</td>
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
                        <th v-if="permiss.hqpr_all == 1" class="px-2">Department</th>
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
                        <th v-if="showCol.approved_at" class="px-2 text-muted">approved at</th>
                        <th v-if="showCol.approved_by" class="px-2 text-muted">approved by</th>
                        <th v-if="showCol.approved_note" class="px-2">approved note</th>
                        <th class="px-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(lst, inx) in rejectData" :key="lst.id">
                        <td class="p-2 tx-center">{{ inx + 1 }}</td>
                        <td class="p-2 ">{{ lst.company }}</td>
                        <td v-if="permiss.hqpr_all == 1" class="p-2 ">{{ lst.dept }}</td>
                        <td class="p-2 ">{{ lst.doc_no }}</td>
                        <td class="p-2 ">{{ dateFormat1(lst.pr_date) }}</td>
                        <td class="p-2 ">{{ lst.docs_type }}</td>
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
                        <td v-if="showCol.approved_at" class="p-2 text-muted">{{ dateFormat2(lst.approved_at) }}</td>
                        <td v-if="showCol.approved_by" class="p-2 text-muted text-capitalize">{{ lst.approved_by }}</td>
                        <td v-if="showCol.approved_note" class="p-2">{{ cutWord(lst.approved_note) }}</td>
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
              <div class="tab-pane" id="tab4">
                <p v-if="allData.length == 0">There are no records.</p>
                <div v-else class="table-responsive" style="max-height: 75vh">
                  <table class="table mg-b-0 text-nowrap">
                    <thead class="position-sticky bg-white" style="top: 0px; z-index: 1">
                      <tr>
                        <th class="px-2">No.</th>
                        <th class="px-2">Company</th>
                        <th v-if="permiss.hqpr_all == 1" class="px-2">Department</th>
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
                        <th v-if="showCol.approved_at" class="px-2 text-muted">approved at</th>
                        <th v-if="showCol.approved_by" class="px-2 text-muted">approved by</th>
                        <th v-if="showCol.approved_note" class="px-2">approved note</th>
                        <th class="px-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(lst, inx) in allData" :key="lst.id">
                        <td class="p-2 tx-center">{{ inx + 1 }}</td>
                        <td class="p-2 ">{{ lst.company }}</td>
                        <td v-if="permiss.hqpr_all == 1" class="p-2 ">{{ lst.dept }}</td>
                        <td class="p-2 ">{{ lst.doc_no }}</td>
                        <td class="p-2 ">{{ dateFormat1(lst.pr_date) }}</td>
                        <td class="p-2 ">{{ lst.docs_type }}</td>
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
                        <td v-if="showCol.approved_at" class="p-2 text-muted">{{ dateFormat2(lst.approved_at) }}</td>
                        <td v-if="showCol.approved_by" class="p-2 text-muted text-capitalize">{{ lst.approved_by }}</td>
                        <td v-if="showCol.approved_note" class="p-2">{{ cutWord(lst.approved_note) }}</td>
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
                              <a v-if="permiss.hqpr_edit == 1" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="editPayment(lst.id, lst.status), mode='upd'">
                                <i class="bx bx-edit-alt me-1"></i>Edit
                              </a>
                              <a v-if="permiss.hqpr_del == 1" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="delPayment(lst.id, lst.status)">
                                <i class="bx bx-trash me-1"></i>Delete
                              </a>
                              <div v-if="lst.status =='Pending' && permiss.hqpr_app == 1">
                                <a v-if="lst.submit_to == 'Approval'" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="approved(lst.id, lst.submit_to)">
                                  <i class="bx bx-check-circle me-1"></i>Approve
                                </a>
                                <a v-if="lst.submit_to == 'Verified'" class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="approved(lst.id, lst.submit_to)">
                                  <i class="bx bx-check-double me-1"></i>Verified
                                </a>
                                <a class="dropdown-item dropdown-hover" href="javascript:void(0)" @click="rejected(lst.id)">
                                  <i class="bx bx-block me-1"></i>Reject
                                </a>
                              </div>
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


          <!-- <a class="nav-link pe-0" data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right">
            <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </a> -->

          <!--  sidebar-open -->
          <div class="sidebar sidebar-right sidebar-animate ps" :class="openForm">
            <div class="panel panel-primary card mb-0 box-shadow">
              <div class="tab-menu-heading border-0 p-3">
                <div class="card-title mb-0">Add Payment Requisition</div>
                <div class="card-options ms-auto">
                  <!-- <a href="#" class="sidebar-remove"><i class="fe fe-x"></i></a> -->
                  <a href="#" @click="openForm=''"><i class="fe fe-x"></i></a>
                </div>
              </div>
              <div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
                <div class="tab-content overflow-auto" style="padding: 5px 20px 0px 20px; height: 90vh">
                  <div class="form-group">
                    <label class="mb-0">Document No.</label>
                    <input type="text" class="form-control" placeholder="Enter Document number" v-model="PRForm.doc_no">
                  </div>
                  <div class="form-group">
                    <label class="mb-0">{{ $t('company') }}</label> 
                    <Multiselect placeholder="Select company" v-model="PRForm.company" :options="lkCompany"/>
                  </div>
                  <div class="form-group">
                    <label class="mb-0">{{ $t('date') }}</label>
                    <input type="date" class="form-control" v-model="PRForm.date">
                  </div>
                  <div class="form-group">
                    <label class="mb-0">Document Type</label> 
                    <Multiselect placeholder="Select document type" v-model="PRForm.docs_type" :options="lkDocsType"/>
                  </div>
                  <div class="form-group">
                    <label class="mb-0">Description</label> 
                    <textarea class="form-control" style="height: 120px" placeholder="Descriptions" v-model="PRForm.descr"></textarea>
                  </div>
                  <div class="form-group">
                    <label class="mb-0">Amount</label>
                    <cleave :options="options" placeholder="Enter amount" class="form-control" v-model="PRForm.amount"/>
                  </div>
                  <div class="form-group">
                    <label class="mb-0">Currency</label> 
                    <Multiselect placeholder="Select currency" v-model="PRForm.currency" :options="lkCurrency"/>
                  </div>
                  <div class="form-group">
                    <label class="mb-0">Submit To</label> 
                    <Multiselect placeholder="Select submit to" :options="lkSubmitTo" v-model="PRForm.submit_to"/>
                  </div>

                  <!-- ATTACHEMENTS -->
                  <div v-if="mode == 'add'">
                    <div class="d-flex justify-content-start align-items-center cur-pointer add-hover wd-100" @click="newFiles1()" title="Add new files">
                      <div><i class="mdi mdi-paperclip me-1 text-primary tx-16"></i></div>
                        <span class="text-primary tx-bold tx-14">{{ $t('attached') }}</span>
                      <input class="d-none" ref="fileInput1" type="file" multiple @change="addTmp()">
                    </div>
                    <div v-for="(lst, inx) in tmpFiles" :key="inx">
                      <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                        <div class="d-flex justify-content-start ms-1">
                          <button class="btn btn-sm btn-icon btn-i p-0" title="Remove file" @click="delTmp(inx)">
                            <i class="fe fe-minus-circle text-danger" style="font-size: 16px"></i>
                          </button> 
                        </div>
                        <span class="tx-11 pt-1">{{ lst.name }}</span>
                      </div>
                    </div>
                  </div>
                  <div v-if="mode == 'upd'">
                    <div class="d-flex justify-content-start align-items-center cur-pointer add-hover wd-100" @click="newMoreFile()" title="Add new files">
                      <div><i class="mdi mdi-paperclip me-1 text-primary tx-16"></i></div>
                        <span class="text-primary tx-bold tx-14">{{ $t('attached') }}</span>
                      <input class="d-none" ref="moreFile" type="file" multiple @change="addMoreFile()">
                    </div>
                    <div v-for="(lst, inx) in attFiles" :key="lst.id">
                      <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                        <div class="d-flex justify-content-start align-items-center ms-1">
                          <button class="btn btn-sm btn-icon btn-i p-0" title="Remove file" @click="delFile(lst.file_name, inx)">
                            <i class="fe fe-trash-2 text-danger" style="font-size: 16px"></i>
                          </button> 
                        </div>
                        <span class=" tx-11 pt-1">{{ fileName(lst.file_name) }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-4">
                    <div v-if="mode == 'add'" class="wd-50p pe-1">
                      <button type="button" class="btn btn-primary wd-100p" :class="PRFormDis" @click="addPayment()">
                        <i class="bx bx-plus"></i><span class="mx-1">{{$t('add')}}</span>
                      </button>
                    </div>
                    <div v-if="mode == 'upd'" class="wd-50p pe-1">
                      <button type="button" class="btn btn-primary wd-100p" :class="PRFormDis" @click="updPayment()">
                        <i class="bx bx-save"></i><span class="mx-1">{{$t('save')}}</span>
                      </button>
                    </div>
                    <div class="wd-50p ps-1">
                      <button type="button" class="btn btn-secondary wd-100p" @click="openForm=''"><i class="bx bx-x"></i> 
                        <span class="mx-1">{{$t('cancel')}}</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>

    <!-- Modal preview -->
    <div class="modal fade effect-scale" id="PRPreview" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header pb-1 bd-b-0">
            <!-- <h6 class="main-content-label text-capitalize">Shift Work Update</h6> -->
            <span v-if="prevData.status == 'Pending'" class="badge rounded-pill bg-warning-gradient tx-11 px-2 py-1">
              <i class="fe fe-bell"></i> {{ prevData.status }}
            </span>
            <span v-if="prevData.status == 'Approved'" class="badge rounded-pill bg-info-gradient tx-11 px-2 py-1">
              <i class="fe fe-check-circle"></i> {{ prevData.status }}
            </span>
            <span v-if="prevData.status == 'Verified'" class="badge rounded-pill bg-secondary-gradient tx-11 px-2 py-1">
              <i class="fe fe-check-circle"></i> {{ prevData.status }}
            </span>
            <span v-if="prevData.status == 'Rejected'" class="badge rounded-pill bg-danger-gradient tx-11 px-2 py-1">
              <i class="fe fe-slash"></i> {{ prevData.status }}
            </span>
            <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
              <span class="tx-24" aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body pt-2">
            <div class="row">
              <div class="col-lg-6 col-12">
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
                    <span class="tx-data">:<span class="ps-2">{{ prevData.dept }}</span></span> 
                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="d-flex justify-content-start">
                    <span class="tx-name">Date</span>
                    <span class="tx-data">:<span class="ps-2">{{ dateFormat1(prevData.pr_date) }}</span></span> 
                </div>
                <div class="d-flex justify-content-start">
                    <span class="tx-name">Document Type</span>
                    <span class="tx-data">:<span class="ps-2">{{ prevData.docs_type }}</span></span> 
                </div>
                <div class="d-flex justify-content-start">
                    <span class="tx-name">Amount</span>
                    <span class="tx-data">:<span class="ps-2 tx-danger tx-bold">{{ numFormat(prevData.amount) }}</span> <span>{{ prevData.currency}}</span></span> 
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-start">
              <span class="tx-name tx-bold py-0">Description</span>
              <span class="tx-data py-0">:</span> 
            </div>
            <div class="tx-data py-0">{{ prevData.descr }}</div> 
            <div class="row">
              <div class="col-lg-6 col-12">
                <h6 class="tx-bold mg-t-20 tx-13" style="color: #4D5875">{{ $t('attached') }}</h6>
                <div>
                  <div v-for="(lst, inx) in attFiles" :key="inx">
                    <div class="d-flex justify-content-start align-items-center tx-13 f-color">
                      <button class="btn btn-sm btn-icon btn-i p-0" title="Preview Image and PDF file" @click="view(lst.file_name)">
                        <i class="mdi mdi-file-find text-primary" style="font-size: 16px"></i>
                      </button> 
                      {{inx + 1}}. {{fileName(lst.file_name)}}
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="prevData.status == 'Pending' && permiss.hqpr_app == 1" class="col-lg-6 col-12">
                <h6 class="tx-bold mg-t-20 tx-13" style="color: #4D5875">{{ prevData.submit_to =='Approval' ? 'Approve' : 'Verified' }}/Reject Comments</h6>
                  <div class="form-group">
                    <textarea class="form-control" style="height: 80px" placeholder="Comments" v-model="PRForm.approved_note"></textarea>
                  </div>
                <div class="d-flex justify-content-end">
                  <button v-if="prevData.submit_to == 'Approval'" type="button" class="btn btn-primary" @click="approved_cf(prevData.id, prevData.submit_to)"><i class="fe fe-check-circle"></i> 
                    <span class="mx-1">Approve</span>
                  </button>
                  <button v-if="prevData.submit_to == 'Verified'" type="button" class="btn btn-primary ms-1" @click="approved_cf(prevData.id, prevData.submit_to)"><i class="fe fe-check-circle"></i> 
                    <span class="mx-1">Verified</span>
                  </button>
                  <button  type="button" class="btn btn-danger ms-1" @click="rejected_cf(prevData.id)"><i class="fe fe-slash"></i> 
                    <span class="mx-1">Reject</span>
                  </button>
                </div>
              </div>
              <div v-if="prevData.status != 'Pending'" class="col-lg-6 col-12">
                <h6 class="tx-bold mg-t-20 tx-13 mb-0" style="color: #4D5875">{{ prevData.status }} Comments</h6>
                <p class="tx-13 m-0">{{ prevData.approved_note }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal show columns -->
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
      lookupData: [],
      lkCompany: [],
      lkDocsType: [],
      lkSubmitTo: [],
      lkCurrency: [],
      lkYear: [],
      permiss: [],
      allFile: [],
      viewFile: [],
      tmpFiles: [],
      attFiles: [],
      allData: [],
      pendingData: [],
      approveData: [],
      rejectData: [],
      prevData: "",

      PRForm: {id: "", doc_no: "", company: null, dept: "", date: "", docs_type: null, descr: "", amount: "", currency: null, submit_to: null, approved_note: ""},
      showCol: {created_at: false, created_by: false, updated_at: false, updated_by: false, approved_at: false, approved_by: false, approved_note: false},

      year: "",
      dept: "",
      search: "",
      actTab: "pen",
      btnClear: false,
      loading: false,
      openForm: "",
      mode: "",

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
      
    };
  },

  mounted() {
    
  },

  computed: {
    PRFormDis(){
      if (this.PRForm.doc_no == "" || this.PRForm.company == null || this.PRForm.date == "" || this.PRForm.docs_type == null || this.PRForm.amount == "" || this.PRForm.currency == null || this.PRForm.submit_to == null) {
        return "disabled";
      } else {
        return "";
      }

      
    }
  },

  methods: {
    async onLoad(){
      this.loading = true;
      this.year = new Date().getFullYear();

      const pro = await axios.get('api/profile')
			this.dept = pro.data.department;

      const perRes = await axios.get('/api/permiss')
      this.permiss = perRes.data;

      const pend = await this.getPending();

      this.loading = false;

      const alld = await this.getAllData();
      const appr = await this.getApprove();
      const reje = await this.getReject();
      const file = await this.getAllFile();

      const year = await axios.get('/api/lookup/year')
      this.lkYear = year.data;


      console.log(this.permiss);

      const lookup = await axios.get('/api/hqpayment/lookup')
      this.lookupData = lookup.data;

     

    },

    async getAllData(){
      const all = await axios.get(`/api/hqpayment/getalldata?view=${this.permiss.hqpr_all}&dept=${this.dept}&year=${this.year}&search=${this.search}`)
      this.allData = all.data;
    },

    async getPending(){
      const pend = await axios.get(`/api/hqpayment/getpending?view=${this.permiss.hqpr_all}&dept=${this.dept}&year=${this.year}&search=${this.search}`)
      this.pendingData = pend.data;
    },

    async getApprove(){
      const approve = await axios.get(`/api/hqpayment/getapprove?view=${this.permiss.hqpr_all}&dept=${this.dept}&year=${this.year}&search=${this.search}`)
      this.approveData = approve.data;
    },

    async getReject(){
      const reject = await axios.get(`/api/hqpayment/getreject?view=${this.permiss.hqpr_all}&dept=${this.dept}&year=${this.year}&search=${this.search}`)
      this.rejectData = reject.data;
    },

    async getAllFile(){
      const allfile = await axios.get(`/api/hqpayment/getallfile?view=${this.permiss.hqpr_all}&dept=${this.dept}&year=${this.year}`)
      this.allFile = allfile.data;
    },

    viewFiles(id){
      let file = this.allFile.filter((i) => i.pr_id == id);
      this.viewFile = file;
    },

    view(filename){
      window.open(window.location.origin+'/assets/HQPayment/'+ filename, "_blank")
    },

    prevPayment(id){
      let data = this.allData.find((i) => i.id == id);
      this.prevData = data;

      this.attFiles = [];
      let file = this.allFile.filter((i) => i.pr_id == id);
      this.attFiles = file;

      this.PRForm.approved_note = "";
      $('#PRPreview').modal('show');
    },

    editPayment(id, status){
      if (this.permiss.hqpr_edit == 0){
      this.$swal.fire({
          text: "You don't have permission to Edit!",
          icon: 'error',
          showCancelButton:false,
          showConfirmButton:false,
          allowOutsideClick:false,
          timerProgressBar:true,
          timer: 1500
        })
      } else {
        if (status == "Pending") {
          this.PRFormClear();
          this.openForm = "sidebar-open";
          let data = this.allData.find((i) => i.id == id);
          this.PRForm.id = id;
          this.PRForm.doc_no = data.doc_no;
          this.PRForm.company = data.company;
          this.PRForm.date = moment(data.pr_date).format("YYYY-MM-DD");
          this.PRForm.docs_type = data.docs_type;
          this.PRForm.descr = data.descr;
          this.PRForm.amount = data.amount;
          this.PRForm.currency = data.currency;
          this.PRForm.submit_to = data.submit_to;

          let file = this.allFile.filter((i) => i.pr_id == id);//attached files
          this.attFiles = file;

        } else {
          alert(`Couldn't edit because this record was ${status}`);
        }
      }
    },

    updPayment(){
      this.$axios.post('/api/hqpayment/updpayment', this.PRForm)
      .then(res => {
        this.openForm = "";
        this.getAllData();
        this.getPending();
      })
    },

    delFile(file, inx){
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
              this.getAllFile();
            })
          } 
      });
    },

    rejected_cf(id){
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
      }).then((result) => {
        if(result.isConfirmed){
          this.$axios.post('/api/hqpayment/rejected', {
            id: id,
            note: this.PRForm.approved_note
          })
          .then(res => {
            this.getAllData();
            this.getPending();
            this.getApprove();
            this.getReject();
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
      }).then((result) => {
        if (result.isConfirmed) {
          this.$axios.post('/api/hqpayment/rejected', {
              id: id,
              note: result.value
          })
          .then(res => {
            this.getAllData();
            this.getPending();
            this.getApprove();
            this.getReject();
          })
        }
      })
    },

    approved_cf(id, submit){
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
            id: id,
            status: submit,
            note: this.PRForm.approved_note
          })
          .then(res => {
            this.getAllData();
            this.getPending();
            this.getApprove();
            this.getReject();
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
            this.getApprove();
            this.getReject();
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
        if (this.actTab == "pen") {
          this.getPending();
        } else if (this.actTab == "app") {
          this.getApprove();
        } else if (this.actTab == "rej") {
          this.getReject();
        } else {
          this.getAllData();
        }
      } else {
        this.btnClear = false;
      }
    },

    searchClear(){
      this.search = '';
      this.btnClear = false;
      this.getPending();
      this.getApprove();
      this.getReject();
      this.getAllData();
    },

    async newPayment(){
      if (!!!parseInt(this.permiss.hqpr_add)){
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
        this.PRFormClear();
        this.PRForm.dept = this.dept;
        this.openForm = "sidebar-open";
        let rand = Math.floor(Math.random() * 9000);
        let newid = String(rand).padStart(4, '0')

        const ck = await axios.get(`/api/hqpayment/checkid?id=${newid}`)
        if (ck.data.success){
          this.PRForm.id = newid;
        } else {
          alert(ck.data.message);
          this.openForm = "";
        }
      }
    },
   
    async addPayment(){
      const add = await axios.post('/api/hqpayment/addpayment', this.PRForm)
      if(add.data.success){
        // this.getIncident();

        // add attached file
        let fd = new FormData();
        for (let i = 0; i < this.tmpFiles.length; i++){ 
          let file = this.tmpFiles[i];
          fd.append('files[' + i + ']', file);
          fd.append('id', this.PRForm.id);
        }
        const doc = await axios.post('/api/hqpayment/addfiles', fd, {headers:{"Content-Type": "multipart/form-date"}})
        this.openForm = "";
        this.getPending();
        this.getAllData();
        this.getAllFile();
      } else {
        alert(add.data.message)
      };
    },

    delPayment(id, status){
      if (status == "Pending") {
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
        alert(`Couldn't delete because this record was ${status}`);
      }
    },

    PRFormClear(){
      this.PRForm.id = "";
      this.PRForm.company = "";
      this.PRForm.dept = "";
      this.PRForm.doc_no = "";
      this.PRForm.date = "";
      this.PRForm.docs_type = "";
      this.PRForm.descr = "";
      this.PRForm.amount = "";
      this.PRForm.currency = "";
      this.PRForm.submit_to = "";
      this.tmpFiles = [];
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
        this.getAllFile();
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
		}
  },

  created() {
    this.onLoad();
    this.getLookup();
  }
};
</script>

<style lang="scss" scoped>

</style>