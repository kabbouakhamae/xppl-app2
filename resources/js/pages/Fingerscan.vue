<template>
  <div v-if="loading">
    <loading />
  </div>
  <div v-else>
    <div class="card">
      <div class="card-header pd-r-15 pd-t-10 pb-0">
        <div class="d-flex justify-content-between">
          <div class="d-flex justify-content-start align-items-center">
            <h4
              class="card-title mg-b-0 text-muted text-capitalize"
            >Finger Scan on {{mont(date)}} {{year(date)}}</h4>
          </div>
          <div class="d-flex justify-content-start">
            <button class="btn btn-icon btn-sm btn-i p-0" data-bs-toggle="dropdown" title="Tools">
              <i class="mdi mdi-dots-horizontal text-gray" style="font-size: 15px"></i>
            </button>
            <div class="dropdown-menu rounded-5 box-shadow-pink tx-13">
              <div class="dropdown-item cur-pointer dropdown-hover" @click="getRosCode()">
                <i class="mdi mdi-book-open-page-variant tx-15 me-2"></i>
                <span>Lookup Code</span>
              </div>
              <div class="dropdown-item cur-pointer dropdown-hover">
                <i class="mdi mdi-file-excel me-2 tx-16"></i>
                <span>Import AC Log</span>
              </div>
              <div class="dropdown-item cur-pointer dropdown-hover">
                <i class="mdi mdi-file-excel me-2 tx-16"></i>
                <span>Export To Excel</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body pd-t-0">
        <div class="d-lg-flex justify-content-between mt-1 mb-1">
          <div class="pos-relative wd-lg-300 wd-100p">
            <input
              class="form-control"
              style="padding-left: 25px"
              type="text"
              placeholder="Search for name"
              v-model="search"
              @input="inputSearch()"
              @keyup.enter="searchChange()"
              title="Search by name"
            />
            <i class="fe fe-search search-i text-muted"></i>
            <button
              class="btn btn-icon btn-sm search-c text-muted p-0"
              v-if="btnClear"
              @click="searchClear()"
            >
              <i class="fe fe-x" style="font-size: 14px"></i>
            </button>
          </div>
          <div class="d-md-flex justify-content-end mt-xl-0 mt-lg-1 mt-md-1 mt-1">
            <div class="wd-lg-200 wd-md-200 wd-100p me-1 my-md-0 my-1">
              <Multiselect
                v-model="dept"
                :searchable="false"
                :searchStart="true"
                :options="lkDept"
                @select="getScan()"
              />
            </div>
            <div class="d-flex justify-content-end">
              <div class="wd-md-150 wd-100p">
                <input type="date" class="form-control" v-model="datefr" @change="getScan()" />
              </div>
              <div class="wd-md-150 wd-100p ms-1">
                <input type="date" class="form-control" v-model="dateto" @change="getScan()" />
              </div>
            </div>
          </div>

          <!-- OLD -->
          <!-- <div class="d-flex justify-content-end mt-xl-0 mt-lg-0 mt-md-1 mt-1">
                        <div class="wd-lg-200 wd-md-200 wd-100p me-1">
                            <Multiselect v-model="dept" :searchable="false" :searchStart="true" :options="lkDept" @select="getScan()"/>
                        </div>
                        <div class="wd-md-150 wd-60p">
                            <input type="date" class="form-control" v-model="date" @change="getScan()">
                        </div>
          </div>-->
        </div>
        <div class="table-responsive border" style="max-height: 78vh">
          <table class="table main-table-reference text-nowrap mg-b-0">
            <thead class="position-sticky" style="top: 0px; z-index: 1">
              <tr>
                <th class="border-start-0 px-1">No</th>
                <th class="px-1 position-sticky" style="left: -1px">Name and Surname</th>
                <th class="px-1">Position</th>
                <th class="px-1">Scan</th>

                <th
                  v-for="(col, colInx) in colData"
                  :key="colInx"
                  class="text-center"
                  style="padding: 7px 6px"
                  :title="col.mont"
                  :style="headDate(col.colid) == cdate ? 'background-color: yellow; color: blue; font-weight: bold' : ''"
                >{{ col.colname }}</th>
              </tr>
            </thead>
            <tbody class="tx-13">
              <tr
                v-for="(row, rowInx) in scanData"
                :key="rowInx"
                @click="selectRow(rowInx, row.name)"
              >
                <td class="px-1 text-center border-start-0">{{rowInx + 1}}</td>
                <td
                  class="px-1 position-sticky cur-pointer bg-white"
                  style="padding: 3px 4px; left: -1px"
                  title="Click to see details"
                  @click="prvDetail(row.id, row.fullname)"
                >{{row.fullname}}</td>
                <td class="px-1">{{row.position}}</td>
                <td class="px-1 text-center">{{row.scan}}</td>
                <td
                  v-for="(col, colInx) in colData"
                  :key="colInx"
                  class="text-center px-1"
                  :title="mentDate(col.colid) +'  '+ row[col.colid]"
                  :style="code(row[col.colid]) == 'W' ? 'background-color: #F2F4F8':
                                            code(row[col.colid]) == 'W/2' ? 'background-color: #FFC000':
                                            code(row[col.colid]) == 'R' || code(row[col.colid]) == 'RW' ? 'background-color: #FFFFCC':
                                            code(row[col.colid]) == 'A' || code(row[col.colid]) == 'A/2' || code(row[col.colid])== 'H' || code(row[col.colid]) == 'P' ? 'background-color: #FFFF99':
                                            code(row[col.colid]) == 'S' ? 'background-color: #F2DCDB':
                                            code(row[col.colid]) == 'MR' ? 'background-color: #FF66FF':
                                            code(row[col.colid]) == 'BWB' || code(row[col.colid]) == 'WO' ? 'background-color: #e8f2e1':
                                            code(row[col.colid]) == 'WN' ? 'background-color: #E0E0E0':
                                            code(row[col.colid]) == 'T' || code(row[col.colid]) == 'T-' ? 'background-color: #FF0000':''"
                >{{ code(row[col.colid]) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Modal Detail -->
        <div
          class="modal"
          id="detail"
          data-bs-keyboard="false"
          tabindex="-1"
          aria-labelledby="detailLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header pb-1 bd-b-0">
                <h6 class="main-content-label text-capitalize">{{fullname}}</h6>
                <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                  <span class="tx-24" aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body pt-0">
                <div class="table-responsive element border">
                  <table class="table main-table-reference text-nowrap mg-b-0">
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
                      <tr class="tr-hover" v-for="(lst, inx) in detailData" :key="inx">
                        <td
                          class="border-start-0 py-0 text-center border-bottom-0 cur-pointer"
                          @click="prvAcLog(lst.id, lst.date)"
                          title="Click to see Ac Log"
                        >{{ dateTime(lst.date) }}</td>
                        <td
                          :style="lst.plan == 'W' || lst.plan == 'WN' ? 'background-color: #F2F4F8':
                                                            lst.plan == 'W/2' ? 'background-color: #FFC000':
                                                            lst.plan == 'R' || lst.plan == 'RW' ? 'background-color: #FFFFCC':
                                                            lst.plan == 'A' || lst.plan == 'A/2' || lst.plan == 'H' || lst.plan == 'P' ? 'background-color: #FFFF99':
                                                            lst.plan == 'S' ? 'background-color: #F2DCDB':
                                                            lst.plan == 'MR' ? 'background-color: #FF66FF':
                                                            lst.plan == 'T' || lst.plan == 'T-' ? 'background-color: #FF0000':''"
                          class="py-0 text-center border-bottom-0"
                        >{{ lst.plan }}</td>
                        <td
                          :style="lst.actual == 'W' || lst.actual == 'WN' ? 'background-color: #F2F4F8':
                                                            lst.actual == 'W/2' ? 'background-color: #FFC000':
                                                            lst.actual == 'R' || lst.actual == 'RW' || lst.actual == 'OR' || lst.actual == 'IR' ? 'background-color: #FFFFCC':
                                                            lst.actual == 'A' || lst.actual == 'A/2' || lst.actual == 'IA' || lst.actual == 'OA' || lst.actual == 'AA' || lst.actual == 'H' || lst.actual == 'OH' || lst.actual == 'IH' || lst.actual == 'P' ? 'background-color: #FFFF99':
                                                            lst.actual == 'S' || lst.actual == 'SS' ? 'background-color: #F2DCDB':
                                                            lst.actual == 'MR' ? 'background-color: #FF66FF':
                                                            lst.actual == 'BWB' || lst.actual == 'WO' ? 'background-color: #e8f2e1':
                                                            lst.actual == 'N5' || lst.actual == 'N6' || lst.actual == 'D4' ? 'background-color: #E0E0E0':
                                                            lst.actual == 'T' || lst.actual == 'T-' || lst.actual == 'IT' || lst.actual == 'OT' || lst.actual == 'TT' ? 'background-color: #FF0000':''"
                          class="py-0 text-center border-bottom-0 cur-pointer"
                          @click="editRos(lst.id, lst.date, lst.actual, lst.remarks, inx)"
                          title="Click to update Roster"
                        >{{ lst.actual }}</td>
                        <td class="py-0 text-center border-bottom-0">{{ lst.period }}</td>
                        <td class="py-0 text-center border-bottom-0">{{ lst.in1 }}</td>
                        <td class="py-0 text-center border-bottom-0">{{ lst.out1 }}</td>
                        <td class="py-0 text-center border-bottom-0">{{ lst.in2 }}</td>
                        <td class="py-0 text-center border-bottom-0">{{ lst.out2 }}</td>
                        <td
                          :style="lst.timesheet == 'W' ? 'background-color: #F2F4F8':
                                                            lst.timesheet == 'W/2' ? 'background-color: #FFC000':
                                                            lst.timesheet == 'R' || lst.timesheet == 'RW' ? 'background-color: #FFFFCC':
                                                            lst.timesheet == 'A' || lst.timesheet == 'A/2' || lst.timesheet == 'H' || lst.timesheet == 'P' ? 'background-color: #FFFF99':
                                                            lst.timesheet == 'S' ? 'background-color: #F2DCDB':
                                                            lst.timesheet == 'MR' ? 'background-color: #FF66FF':
                                                            lst.timesheet == 'WN' ? 'background-color: #E0E0E0':
                                                            lst.timesheet == 'T' || lst.timesheet == 'T-' ? 'background-color: #FF0000':''"
                          class="py-0 text-center border-bottom-0"
                        >{{ lst.timesheet }}</td>
                        <td class="py-0 laofont border-end-0 border-bottom-0">{{ lst.remarks }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal AC Log -->
        <div
          class="modal pd-t-100 bd-0 bg-black-5"
          id="aclog"
          data-bs-keyboard="false"
          tabindex="-1"
          aria-labelledby="aclogLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header pb-1 bd-b-0">
                <h6 class="text-muted">
                  AC Log On
                  <span class="text-danger">{{dateTime(scanDate)}}</span>
                </h6>
              </div>
              <div class="modal-body pt-0">
                <div class="table-responsive element border">
                  <table class="table main-table-reference text-nowrap mg-b-0">
                    <thead>
                      <tr>
                        <th class="border-start-0">No</th>
                        <th>Date Time</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th class="border-end-0">Sensor ID</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="tr-hover" v-for="(lst, inx) in acLogFilter" :key="inx">
                        <td class="py-0 text-center border-bottom-0 border-start-0">{{ inx + 1 }}</td>
                        <td class="py-0 text-center border-bottom-0">{{dateTime2(lst.check_time)}}</td>
                        <td class="py-0 text-center border-bottom-0">{{dateTime(lst.scan_date)}}</td>
                        <td class="py-0 text-center border-bottom-0">{{tFormat(lst.scan_time)}}</td>
                        <td class="py-0 border-end-0 border-bottom-0">{{lst.sensor_id}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL UPDATE, DELETE, ADD ROSTER -->
        <div
          class="modal pd-t-100 bd-0 bg-black-5"
          id="modalUpdRoster"
          back
          data-bs-keyboard="false"
          tabindex="-1"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header pb-1 bd-b-0">
                <h6 class="main-content-label text-capitalize">
                  Update
                  <!-- <span v-if="rosForm.rtype=='P'" class="tx-danger">{{type(rosForm.rtype)}}</span>
                  <span v-else class="tx-primary">{{type(rosForm.rtype)}}</span>-->
                </h6>
              </div>
              <div class="modal-body pt-1">
                <div class="row">
                  <div class="col-5 pe-1">
                    <div class="form-group">
                      <input
                        type="date"
                        class="form-control ps-2 pe-0"
                        disabled
                        v-model="rosForm.rdatefr"
                      />
                    </div>
                  </div>
                  <div class="col-5 ps-1 pe-1">
                    <div class="form-group">
                      <input
                        type="date"
                        class="form-control ps-2 pe-1"
                        v-model="rosForm.rdateto"
                        @change="CountD()"
                      />
                    </div>
                  </div>
                  <div class="col-2 ps-1">
                    <div class="form-group">
                      <input type="text" class="form-control tx-center px-0" v-model="rosForm.days" />
                    </div>
                  </div>
                </div>
                <div v-if="optMode!='Delete'" class="form-group">
                  <Multiselect
                    v-model="rosForm.rcode"
                    searchable="true"
                    searchStart="true"
                    placeholder="Code"
                    :options="lkCode"
                  />
                </div>
                <div class="form-group">
                  <textarea
                    class="form-control laofont"
                    style="height: 80px"
                    v-model="rosForm.comment"
                    placeholder="Comment"
                  ></textarea>
                </div>
                <!-- <div class="d-flex justify-content-between"> -->
                <!-- <div> -->
                <!-- <label class="rdiobox cur-pointer my-0"><input name="opt1" type="radio" v-model="optMode" value="Update" checked><span class="ps-2">Update</span></label> -->
                <!-- <label class="rdiobox cur-pointer my-0"><input name="opt1" type="radio" v-model="optMode" value="Delete"><span class="ps-2">Delete</span></label>   -->
                <!-- </div> -->
                <!-- <div v-else class="d-flex justify-content-center align-items-center">
                                        <label class="rdiobox cur-pointer my-0"><input name="opt1" type="radio" v-model="optMode" value="Add" checked><span class="ps-2">Add</span></label>  
                </div>-->
                <div class="d-flex justify-content-end">
                  <button
                    type="button"
                    class="btn btn-primary"
                    :class="AddRosRangeDis"
                    @click="UpdRoster_RangeCF()"
                  >
                    <i class="fe fe-check-circle"></i>
                    <span class="mx-1">Update</span>
                  </button>
                  <!-- <button v-else-if="optMode=='Delete'" type="button" class="btn btn-danger" :class="DelRosRangeDis" @click="DelRoster_RangeCF()"><i class="fe fe-trash-2"></i><span class="mx-1">Delete</span></button>  -->
                  <!-- <button v-else type="button" class="btn btn-primary" :class="AddRosRangeDis" @click="AddRoster_RangeCF()"><i class="fe fe-plus-circle"></i><span class="mx-1">Add</span></button>  -->
                </div>
                <!-- </div> -->
              </div>
            </div>
          </div>
        </div>

        <!-- Lookup code -->
        <div
          class="modal fade effect-scale"
          id="lookupcode"
          back
          data-bs-keyboard="false"
          tabindex="-1"
          aria-labelledby="addLabel"
          aria-hidden="true"
        >
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header pb-1 bd-b-0">
                <h6 class="main-content-label text-capitalize">Roster Lookup Code</h6>
                <button
                  v-if="permiss.lk_ros != 1"
                  aria-label="Close"
                  class="close"
                  data-bs-dismiss="modal"
                  type="button"
                >
                  <span class="tx-24" aria-hidden="true">×</span>
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
                        <th class="wd-70p">Time Period</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="lst in rosCodeData" :key="lst.id">
                        <td class="text-center">{{ lst.ref_code }}</td>
                        <td class="text-center">{{ lst.rcode }}</td>
                        <td class="laofont">{{ lst.descr_lao }}</td>
                        <td>{{ lst.descr_eng }}</td>
                        <td>{{ lst.working_time }}</td>
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
  </div>
</template>

<script>
export default {
  name: "XpplAppFingerscan",

  data() {
    return {
      permiss: [],
      lkCode: [],
      lkDept: [],
      colData: [],
      scanData: [],
      detailData: [],
      acLogData: [],
      dept: "",
      datefr: "",
      dateto: "",
      search: "",
      btnClear: false,
      scanDate: "",
      loading: false,
      cdate: "",
      rosForm: {
        userid: "",
        rtype: "A",
        rdatefr: "",
        rdateto: "",
        rcode: null,
        comment: "",
        days: "1"
      },
      updMethod: "update",
      rosCodeData: [],
      rowIndex: "",
      acLogFilter: []
    };
  },

  mounted() {},

  computed: {
    AddRosRangeDis() {
      if (
        this.rosForm.rdateto == "" ||
        this.rosForm.days == "" ||
        this.rosForm.rcode == null ||
        this.rosForm.rdatefr > this.rosForm.rdateto
      ) {
        return "disabled";
      } else {
        return "";
      }
    }
  },

  methods: {
    async onLoad() {
      this.loading = true;

      const resPermiss = await axios.get("/api/permiss");
      this.permiss = resPermiss.data;

      const profile = await axios.get("api/profile");
      this.dept = profile.data.department;

      this.cdate = moment(new Date()).format("YYYY-MM-DD");

      // OLD FUNCTION
      // this.date = moment(new Date()).format('YYYY-MM-DD');
      // const colhead = await axios.get(`/api/fingerscan/datecol?date=${this.date}`)
      // this.colData = colhead.data;

      // const scan = await axios.get(`/api/fingerscan/summary?dept=${this.dept}&date=${this.date}&search=${this.search}`)
      // this.scanData = scan.data;

      let today = new Date();
      this.datefr = moment(
        new Date(today.getFullYear(), today.getMonth() - 1)
      ).format("YYYY-MM-26");
      this.dateto = moment(
        new Date(today.getFullYear(), today.getMonth() + 1, 0)
      ).format("YYYY-MM-25");

      const data = await this.getScan();

      this.loading = false;

      const depts = await axios.get("/api/lookup/depts");
      this.lkDept = depts.data;

      const rcode = await axios.get("/api/roster/rcode");
      this.lkCode = rcode.data;
    },

    async getScan() {
      this.loading = true;
      if (this.dept != "" && this.date != "") {
        const colhead = await axios.get(
          `/api/fingerscan/datecol?datefr=${this.datefr}&dateto=${this.dateto}`
        );
        this.colData = colhead.data;

        const scan = await axios.get(
          `/api/fingerscan/summary?dept=${this.dept}&datefr=${this.datefr}&dateto=${this.dateto}&search=${this.search}`
        );
        this.scanData = scan.data;

        const aclog = await axios.post(
          `/api/fingerscan/aclog?dept=${this.dept}&datefr=${this.datefr}&dateto=${this.dateto}`
        );
        this.acLogData = aclog.data;
      }
      this.loading = false;
    },

    async searchChange() {
      if (this.search.length > 0) {
        this.btnClear = true;
        const data = await this.getScan();
      } else {
        this.btnClear = false;
        const data = await this.getScan();
      }
    },

    inputSearch() {
      if (this.search.length > 0) {
        this.btnClear = true;
      } else {
        this.btnClear = false;
      }
    },

    searchClear() {
      this.search = "";
      (this.btnClear = false), this.getScan();
    },

    prvDetail(id, name) {
      this.fullname = name;

      this.$axios
        .post("/api/fingerscan/detail", {
          dept: this.dept,
          datefr: this.datefr,
          dateto: this.dateto,
          userid: id
        })
        .then(res => {
          this.detailData = res.data;
          $("#detail").modal("show");
        });
    },

    prvAcLog(id, date) {
      this.scanDate = date;
      let item = this.acLogData.filter(
        i =>
          i.userid == id &&
          moment(i.scan_date).format("YYYYMMDD") ==
            moment(date).format("YYYYMMDD")
      );
      this.acLogFilter = item;

      //  this.$axios.post('/api/fingerscan/aclog', {
      //      userid: id,
      //     date: date
      // }).then(res => {
      //     this.acLogData = res.data;
      // })
      $("#aclog").modal("show");
    },

    editRos(id, date, code, comm, inx) {
      if (this.permiss.ros_edit == 0) {
        this.$swal.fire({
          text: "You don't have permission update!",
          icon: "error",
          showCancelButton: false,
          showConfirmButton: false,
          allowOutsideClick: false,
          timerProgressBar: true,
          timer: 1500
        });
      } else {
        this.rowIndex = inx;
        this.rosForm.userid = id;
        this.rosForm.rdatefr = date;
        this.rosForm.rdateto = date;
        this.rosForm.rcode = code;
        this.rosForm.comment = comm;
        this.rosForm.days = "1";
        $("#modalUpdRoster").modal("show");
      }
    },

    UpdRoster_RangeCF() {
      // $('#modalUpdRoster').modal('hide');
      // let dateList = [];
      // let n = this.rowIndex; //column index
      // for (let i = 0; i < this.rosForm.days; i++){

      //         let row = this.detailData[n].date;
      //         // this.detailData.find((i)=>i.userid == this.rosForm.userid && i.rtype == this.rosForm.rtype)[col] = this.rosForm.rcode;
      //         // this.rosData.find((i)=>i.userid == this.rosForm.userid && i.rtype == this.rosForm.rtype)[col] = this.rosForm.rcode;

      //         let date = moment(row).format('YYYY/MM/DD');
      //         dateList.push(date);
      //         n = n + 1

      //     }

      // this.$axios.post('/api/roster/updrosterrange', {
      //     list: dateList,
      //     userid: this.rosForm.userid,
      //     type: this.rosForm.rtype,
      //     code: this.rosForm.rcode,
      //     comm: this.rosForm.comment
      // }).then(res => {
      //     console.log('Update completed');
      //     this.prvDetail(this.rosForm.userid, this.fullname);
      // })

      const test = [
        { id: 1, name: "a" },
        { id: 2, name: "b" },
        { id: 3, name: "c" },
        { id: 4, name: "d1" },
        { id: 4, name: "d2" }
      ];

      const result = test.filter(obj => obj.id === 4);

      console.log("DATA: ", result);
    },

    CountD() {
      let d1 = moment(this.rosForm.rdatefr).format("MM-DD-YYYY");
      let d2 = moment(this.rosForm.rdateto).format("MM-DD-YYYY");

      let dat1 = new Date(d1);
      let dat2 = new Date(d2);

      let ddiff = dat2.getTime() - dat1.getTime();
      this.rosForm.days = Math.ceil(ddiff / (1000 * 3600 * 24)) + 1;
    },

    updRosRangeRefresh() {
      $("#roster").modal("hide");
      $("#detail").modal("hide");
      this.updRosRange();
      this.getScan();
    },

    getRosCode() {
      $("#lookupcode").modal("show");

      this.$axios
        .get("/api/roster/rosCode")
        .then(res => (this.rosCodeData = res.data));
    },

    code(text) {
      if (text) {
        return text.split("_")[0];
      }
    },

    headDate(text) {
      if (text) {
        return moment(text.split("c")[1]).format("YYYY-MM-DD");
      }
    },

    mentDate(text) {
      if (text) {
        return moment(text.split("c")[1]).format("DD/MM/YYYY");
      }
    },

    comments(text) {
      if (text) {
        return text.split("_")[1];
      }
    },

    shortname(text) {
      if (text) {
        return text.split(" ")[0];
      }
    },

    tFormat(text) {
      if (text) {
        return text.split(":00.")[0];
      }
    },

    type(value) {
      if (value == "P") {
        return "Plan";
      } else {
        return "Actual";
      }
    },

    mont(value) {
      if (value) {
        return moment(value).format("MMMM");
      }
    },

    year(value) {
      if (value) {
        return moment(value).format("YYYY");
      }
    },

    dateTime(value) {
      if (value) {
        return moment(value).format("DD-MM-YYYY");
      }
    },

    dateTime2(value) {
      if (value) {
        return moment(value).format("DD-MM-YYYY HH:mm:ss");
      }
    }
  },

  created() {
    this.onLoad();
  },

  beforeRouteEnter(to, from, next) {
    if (!window.Laravel.isLoggedin_laravel) {
      window.location.href = "/signin";
    }
    next();
  }
};
</script>

<style>
</style>