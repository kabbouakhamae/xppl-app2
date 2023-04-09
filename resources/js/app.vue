<template>
  <div>
    <!-- Page -->
    <div class="page">
      <!-- main-sidebar -->
      <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
      <aside class="app-sidebar sidebar-scroll" v-if="isSignin">
        <div class="main-sidebar-header active">
          <a class="desktop-logo logo-light active" href="index.html">
            <img src="assets/img/brand/xppl3.jpg" class="main-logo" alt="logo" />
          </a>
          <!-- <a class="desktop-logo logo-dark active" href="index.html"><img src="assets/img/brand/PSG1.jpg" class="main-logo dark-theme" alt="logo"></a> -->
          <a class="logo-icon mobile-logo icon-light active" href="index.html">
            <img src="assets/img/brand/xppl3.jpg" class="logo-icon" alt="logo" />
          </a>
          <!-- <a class="logo-icon mobile-logo icon-dark active" href="index.html"><img src="assets/img/brand/favicon-white.png" class="logo-icon dark-theme" alt="logo"></a> -->
        </div>
        <div class="main-sidemenu">
          <div class="app-sidebar__user clearfix"></div>
          <ul class="side-menu">
            <li class="side-item side-item-category"></li>

            <!-- <li class="slide" @click="Resett(), ResetAct(), homeM='active', adm='', fuel='', prod='', safe='', clin='', sett=''">
              <router-link class="side-menu__item" :class="homeM" to="/">
                <span class="side-menu__label">
                  <i class="bx bx-home-circle tx-center tx-20 wd-25"></i>
                  <span class="mg-l-10 mg-t-4">{{ $t('home')}}</span>
                </span>
              </router-link>
            </li> -->

            <!-- /*
              ^ Dashboards menu 
            */-->
            <div>
              <li class="slide" :class="dash">
                <a class="side-menu__item" :class="dashM" @click="dash ? dash='':dash='is-expanded', HQPay='', adm='', fuel='', prod='', safe='', clin='', sett=''" href="#">
                  <span class="side-menu__label">
                    <i class="bx bx-home-circle tx-20 tx-center wd-25"></i>
                    <span class="mg-l-10 mg-t-2">{{ $t('home') }}</span>
                  </span>
                  <i class="angle fe fe-chevron-down mg-t-4"></i>
                </a>
                <ul class="slide-menu">
                  <li>
                    <router-link class="slide-item" :class="dashS1" @click="ResetAct(), dashS1='active', dashM='active'" to="/" >
                      {{ $t('dashEmp') }}
                    </router-link>
                  </li>
                  <!-- <li>
                    <router-link class="slide-item" :class="dashS2" @click="ResetAct(), dashS2='active', dashM='active'" to="/">
                      {{ $t('dashProd') }}
                    </router-link>
                  </li> -->
                  <!-- <li>
                    <router-link class="slide-item" :class="dashS3" @click="ResetAct(), dashS3='active', dashM='active'" to="/">
                      {{ $t('dashSell') }}
                    </router-link>
                  </li> -->
                  <!-- <li>
                    <router-link class="slide-item" :class="dashS4" @click="ResetAct(), dashS4='active', dashM='active'" to="/">
                      {{ $t('dashSafe') }}
                    </router-link>
                  </li> -->
                  <!-- <li>
                    <router-link class="slide-item" :class="dashS5" @click="ResetAct(), dashS5='active', dashM='active'" to="/">
                      {{ $t('dashClin') }}
                    </router-link>
                  </li> -->
                  <!-- <li>
                    <router-link class="slide-item" :class="dashS6" @click="ResetAct(), dashS6='active', dashM='active'" to="/">
                      {{ $t('dashFuel') }}
                    </router-link>
                  </li> -->
                </ul>
              </li>
            </div>

            <!-- /*
              ^ Admin Task menu 
            */ -->
            <li v-if="permiss.adm_acc == 1" class="slide" :class="adm">
              <a class="side-menu__item" :class="admM" @click="adm ? adm='':adm='is-expanded', HQPay='', dash='', fuel='', prod='', safe='', clin='', sett=''" href="#">
                <span class="side-menu__label"><i class="bx bx-group tx-20 tx-center wd-25"></i><span class="mg-l-10 mg-t-3">{{ $t('admin')}}</span></span><i class="angle fe fe-chevron-down mg-t-4"></i>
              </a>
              <ul class="slide-menu">
                <li><router-link class="slide-item" :class="admS1" @click="ResetAct(), admS1='active', admM='active'" to="/employee">{{ $t('employee')}}</router-link></li>
                <li>
                  <router-link
                    class="slide-item"
                    :class="admS2"
                    @click="ResetAct(), admS2='active', admM='active'"
                    to="/roster"
                  >{{ $t('roster')}}</router-link>
                </li>
                <li>
                  <router-link
                    class="slide-item"
                    :class="admS3"
                    @click="ResetAct(), admS3='active', admM='active'"
                    to="/fingerscan"
                  >{{ $t('scan')}}</router-link>
                </li>
                <li>
                  <router-link
                    class="slide-item"
                    :class="admS4"
                    @click="ResetAct(), admS4='active', admM='active'"
                    to="/overtime"
                  >{{ $t('OT')}}</router-link>
                </li>
                <li>
                  <router-link
                    class="slide-item"
                    :class="admS5"
                    @click="ResetAct(), admS5='active', admM='active'"
                    to="/annual"
                  >{{ $t('al')}}</router-link>
                </li>
                <li>
                  <router-link
                    class="slide-item"
                    :class="admS6"
                    @click="ResetAct(), admS6='active', admM='active'"
                    to="/leaveinfo"
                  >{{ $t('leave')}}</router-link>
                </li>
                <li>
                  <router-link
                    class="slide-item"
                    :class="admS7"
                    @click="ResetAct(), admS7='active', admM='active'"
                    to="/transport"
                  >{{ $t('trans')}}</router-link>
                </li>
              </ul>
            </li>

            <!-- /*
              ^ Payment Requisition menu 
            */-->
            <div v-if="permiss.hqpr_acc==1">
              <li class="slide" :class="HQPay">
                <a class="side-menu__item" :class="HQPayM" @click="HQPay ? HQPay='':HQPay='is-expanded', dash='', adm='', fuel='', prod='', safe='', clin='', sett=''" href="#">
                  <span class="side-menu__label">
                    <i class="bx bx-dollar-circle tx-20 tx-center wd-25"></i>
                    <span class="mg-l-10 mg-t-2">{{ $t('HQPayment') }}</span>
                  </span>
                  <i class="angle fe fe-chevron-down mg-t-4"></i>
                </a>
                <ul class="slide-menu">
                  <li>
                    <router-link class="slide-item" :class="HQPayS1" @click="ResetAct(), HQPayS1='active', HQPayM=''" to="/hqpayment" >
                      {{ $t('PRApproval') }}
                    </router-link>
                  </li>
                </ul>
              </li>
            </div>

            <!-- /*
              ^ Production menu 
            */-->
            <div v-if="permiss.prod_acc==1">
              <li
                class="slide"
                :class="prod"
              >
                <a class="side-menu__item" :class="proM" @click="prod?prod='':prod='is-expanded', HQPay='', dash='', adm='', fuel='', safe='', clin='', sett=''" href="#">
                  <span class="side-menu__label">
                    <i class="bx bx-line-chart tx-center tx-20 wd-25"></i>
                    <span class="mg-l-10 mg-t-4">{{ $t('production') }}</span>
                  </span>
                  <i class="angle fe fe-chevron-down mg-t-4"></i>
                </a>
                <ul class="slide-menu">
                  <li>
                    <router-link
                      class="slide-item"
                      :class="proS1"
                      @click="ResetAct(), proS1='active', proM='active'"
                      to="/prodentry"
                    >Data Entry</router-link>
                  </li>

                  <!-- <li><router-link class="slide-item" :class="fuelReportAct" @click="fuelReportClick()" to="/fuelreport">Report</router-link></li>
                  <li><router-link class="slide-item" :class="fuelLookupAct" @click="fuelLookup()" to="/fuellookup">Lookup</router-link></li>-->
                </ul>
              </li>
            </div>

            <!-- /*
              ^ Fuel Menu
            */-->
            <div v-if="permiss.fuel==1">
              <li
                class="slide"
                :class="fuel"
              >
                <a class="side-menu__item" :class="fueM" @click="fuel?fuel='':fuel='is-expanded', HQPay='', dash='', adm='', prod='', safe='', clin='', sett=''" href="#">
                  <span class="side-menu__label">
                    <i class="bx bx-gas-pump tx-center tx-20 wd-25"></i>
                    <span class="mg-l-10 mg-t-4">{{ $t('fuel')}}</span>
                  </span>
                  <i class="angle fe fe-chevron-down mg-t-4"></i>
                </a>
                <ul class="slide-menu">
                  <li>
                    <router-link
                      class="slide-item"
                      :class="fueS1"
                      @click="ResetAct(), fueS1='active', fueM='active'"
                      to="/fuelcons"
                    >{{ $t('reserve') }}</router-link>
                  </li>
                  <li>
                    <router-link
                      class="slide-item"
                      :class="fueS2"
                      @click="ResetAct(), fueS2='active', fueM='active'"
                      to="/fuelreport"
                    >{{ $t('report') }}</router-link>
                  </li>
                  <li>
                    <router-link
                      class="slide-item"
                      :class="fueS3"
                      @click="ResetAct(), fueS3='active', fueM='active'"
                      to="/fuellookup"
                    >{{ $t('lookup') }}</router-link>
                  </li>
                </ul>
              </li>
            </div>

            <!-- INVENTORY -->
            <!-- <div v-if="permiss.pr_acc == 1">
							<li class="slide" :class="inventExp" @click="Resett(), inventExp?inventExp='':inventExp='is-expanded'">
								<a class="side-menu__item" :class="inventM" href="#">
									<span class="side-menu__label"><i class="fa fa-cubes tx-center tx-17 wd-25"></i><span class="mg-l-10 mg-t-4">Inventory</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="prAct" @click="prClick()" to="/pr">PR Records</router-link></li>
									<li><router-link class="slide-item" :class="fuelReportAct" @click="fuelReportClick()" to="/fuelreport">Reservation</router-link></li>
									<li><router-link class="slide-item" :class="fuelReportAct" @click="fuelReportClick()" to="/fuelreport">Stock Entries</router-link></li>
									<li><router-link class="slide-item" :class="fuelReportAct" @click="fuelReportClick()" to="/fuelreport">Stock Balance</router-link></li>
									<li><router-link class="slide-item" :class="fuelLookupAct" @click="fuelLookup()" to="/fuellookup">Lookup</router-link></li>
								</ul>
							</li>
            </div>-->

            <!-- /*
              ^ Safety Menu
            */-->
            <div v-if="permiss.safety==1">
              <li
                class="slide"
                :class="safe"
              >
                <a class="side-menu__item" :class="safM" @click="safe?safe='':safe='is-expanded', HQPay='', dash='', adm='', fuel='', prod='', clin='', sett=''" href="#">
                  <span class="side-menu__label">
                    <!-- <i class="fa fa-medkit tx-16 tx-center wd-25"></i> -->
                    <i class="bx bx-hard-hat tx-20 tx-center wd-25"></i>
                    <span class="mg-l-10 mg-t-2">{{ $t('safety') }}</span>
                  </span>
                  <i class="angle fe fe-chevron-down mg-t-4"></i>
                </a>
                <ul class="slide-menu">
                  <li>
                    <router-link
                      class="slide-item"
                      :class="safS1"
                      @click="ResetAct(), safS1='active', safM='active'"
                      to="/increcord"
                    >{{ $t('incident') }}</router-link>
                  </li>
                  <!-- <li><router-link class="slide-item" :class="safS2" @click="ResetAct(), safS2='active', safM='active'" to="/increport">Report</router-link></li> -->
                  <li>
                    <router-link
                      class="slide-item"
                      :class="safS3"
                      @click="ResetAct(), safS3='active', safM='active'"
                      to="/inclookup"
                    >{{ $t('lookup') }}</router-link>
                  </li>
                  <!-- <li><router-link class="slide-item" :class="safS4" @click="ResetAct(), safS4='active', safM='active'" to="/safdocs">File Storage</router-link></li> -->
                </ul>
              </li>
            </div>

            <!-- /*
              ^ Clinic Menu
            */-->
            <div v-if="permiss.clinic_acc==1">
              <li class="slide" :class="clin">
                <a class="side-menu__item" :class="clinM" @click="clin?clin='':clin='is-expanded', HQPay='', dash='', adm='', fuel='', prod='', safe='', sett=''" href="#">
                  <span class="side-menu__label">
                    <i class="bx bx-clinic tx-20 tx-center wd-25"></i>
                    <span class="mg-l-10 mg-t-2">{{ $t('clinic') }}</span>
                  </span>
                  <i class="angle fe fe-chevron-down mg-t-4"></i>
                </a>
                <ul class="slide-menu">
                  <li>
                    <router-link class="slide-item" :class="clinS1" @click="ResetAct(), clinS1='active', clinM='active'" to="/treatment" >
                      {{ $t('treatment') }}
                    </router-link>
                  </li>
                  <li>
                    <router-link class="slide-item" :class="clinS2" @click="ResetAct(), clinS2='active', clinM='active'" to="/inclookup">
                      {{ $t('drugstore') }}
                    </router-link>
                  </li>
                  <li>
                    <router-link class="slide-item" :class="clinS3" @click="ResetAct(), clinS3='active', clinM='active'" to="/clilookup">
                      {{ $t('lookup') }}
                    </router-link>
                  </li>
                </ul>
              </li>
            </div>

            <!-- /*
              ^ Setting Menu
            */-->
            <li
              class="slide"
              :class="sett"
            >
              <a class="side-menu__item" :class="setM" @click="sett?sett='':sett='is-expanded', HQPay='', dash='', adm='', fuel='', prod='', safe='', clin=''" href="#">
                <span class="side-menu__label">
                  <i class="bx bx-cog tx-center tx-20 wd-25"></i>
                  <span class="mg-l-10 mg-t-5">{{ $t('setting') }}</span>
                </span>
                <i class="angle fe fe-chevron-down mg-t-4"></i>
              </a>
              <ul class="slide-menu">
                <li v-if="permiss.lk_add == 1">
                  <router-link class="slide-item" :class="setS1" @click="ResetAct(), setS1='active', setM='active'" to="/lookup">{{ $t('lookup') }}</router-link>
                </li>
                <li v-if="!!parseInt(permiss.permiss)">
                  <router-link class="slide-item" :class="setS2" @click="ResetAct(), setS2='active', setM='active'" to="/permiss">{{ $t('permiss') }}</router-link>
                </li>
              </ul>
            </li>

            <!-- /*
              ^ Macro Menu
            */-->
            <li v-if="permiss.mac_acc==1" class="slide" @click="ResetAct(), macroM='active', HQPay='', dash='', adm='', fuel='', prod='', safe='', clin='', sett=''">
              <router-link class="side-menu__item" :class="macroM" to="/macro">
                <span class="side-menu__label">
                  <i class="mdi mdi-file-excel tx-22 tx-center wd-25"></i>
                  <span class="mg-l-10 mg-t-4">{{ $t('macro') }}</span>
                </span>
              </router-link>
            </li>
          </ul>
        </div>
      </aside>

      <div :class="appClass">
        <!-- main-header -->
        <div class="main-header sticky side-header nav nav-item" v-if="isSignin">
          <div class="container-fluid ps-sm-0 p-2">
            <div class="main-header-left">
              <div class="app-sidebar__toggle" data-bs-toggle="sidebar">
                <a class="open-toggle" href="#">
                  <i class="header-icon fe fe-align-left"></i>
                </a>
                <a class="close-toggle" href="#">
                  <i class="header-icons fe fe-x"></i>
                </a>
              </div>
              <div class="text-muted text-capitalize" style="padding-top: 9px">
                <h5>{{ profile.department }}</h5>
              </div>
            </div>
            <div class="main-header-right">
              <ul class="nav nav-item navbar-nav-right ms-auto">
                <!-- <li class="nav-item full-screen fullscreen-button">
									<a class="new nav-link full-screen-link" href="#" title="Full screen"><svg
											xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
											fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round">
											<path
												d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
											</path>
										</svg>
									</a>
                </li>-->

                <li class="nav">
                  <div class="dropdown nav-itemd-none d-md-flex">
                    <a href="#" class="d-flex nav-item country-flag1" data-bs-toggle="dropdown" aria-expanded="false" title="ປ່ຽນພາສາ"
                    >
                      <span class="avatar country-Flag me-0 align-self-center bg-transparent">
                        <img v-if="$i18n.locale=='en'" src="assets/img/flags/us_flag.jpg" alt="img" />
                        <img v-else src="assets/img/flags/Laos.png" alt="img" />
                      </span>
                      <div class="my-auto">
                        <strong class="me-2 ms-2 my-auto">English</strong>
                      </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow" x-placement="bottom-end" style
                    >
                      <a href="#" class="dropdown-item d-flex" @click="Language('en')">
                        <span class="avatar me-3 align-self-center bg-transparent">
                          <img src="assets/img/flags/us_flag.jpg" alt="img" />
                        </span>
                        <div class="d-flex">
                          <span class="mt-2">English</span>
                        </div>
                      </a>
                      <a href="#" class="dropdown-item d-flex" @click="Language('la')">
                        <span class="avatar me-3 align-self-center bg-transparent">
                          <img src="assets/img/flags/Laos.png" alt="img" />
                        </span>
                        <div class="d-flex">
                          <span class="mt-2">Lao</span>
                        </div>
                      </a>
                    </div>
                  </div>
                </li>
                
                <li v-if="!!parseInt(permiss.hqpr_noti)" class="dropdown nav-itemd-none nav-item main-header-notification" id="drop">
                  <a class="new nav-link" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-bell bx-sm pos-relative text-muted"></i>
                    <span v-if="pendData.length > 0" class="badge rounded-circle badge-notifications pos-absolute tx-10" style="left: 24px; bottom: 20px; width: 17px; height: 17px; background: rgb(255, 62, 29);">{{ pendData.length }}</span>
                  </a>
                  <div v-if="pendData.length > 0" class="dropdown-menu">
                    <!-- <div class="menu-header-content bg-primary text-start">
                      <div class="d-flex">
                        <h6 class="dropdown-title mb-1 tx-15 text-white fw-semibold">Notifications
                        </h6>
                        <span class="badge rounded-pill bg-warning ms-auto my-auto float-end">Mark
                          All Read</span>
                      </div>
                      <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">You have
                        {{ pendData.length }} Notifications</p>
                    </div> -->
                      <div class="main-notification-list overflow-auto" style="max-height: 365px">
                        <div v-for="(lst) in pendData" :key="lst.id">
                          <a class="dropdown-item dropdown-hover d-flex p-3 border-bottom" href="#" @click="prevPayment(lst.id)">
                            <div v-if="lst.docs_type =='Payment Requisition'" class="avatar bg-pink rounded-circle tx-13">
                              {{ docType(lst.docs_type) }}
                            </div>
                            <div v-else-if="lst.docs_type =='Payment Voucher'" class="avatar bg-secondary rounded-circle tx-13">
                              {{ docType(lst.docs_type) }}
                            </div>
                            <div v-else-if="lst.docs_type =='Receipt Voucher'" class="avatar bg-info rounded-circle tx-13">
                              {{ docType(lst.docs_type) }}
                            </div>
                            <div v-else class="avatar bg-warning rounded-circle tx-13">
                              {{ docType(lst.docs_type) }}
                            </div>
                            <div class="ms-3">
                              <h5 class="notification-label mb-1">{{ lst.doc_no }}</h5>
                              <div class="notification-subtext">{{ numFormat(lst.amount) }} {{ lst.currency }}</div>
                            </div>
                            <div class="ms-auto">
                              <i class="las la-angle-right text-end text-muted"></i>
                            </div>
                          </a>
                        </div>
                      </div>
                    <div class="dropdown-footer">
                      <a href="/hqpayment">VIEW ALL</a>
                    </div>
                  </div>
                </li>
        
                <li class="dropdown main-profile-menu nav nav-item nav-link p-0">
                  <a class="profile-user d-flex" href>
                    <div class="d-flex align-items-center">
                      <span class="d-none d-sm-flex text-muted text-capitalize px-2">{{ profile.name }} {{ profile.surname }}</span>
                    </div>
                    <img alt v-if="profile.photo" :src="'assets/img/profile/'+ profile.photo" />
                    <img alt v-if="!profile.photo && profile.gender == 'Male'" src="assets/img/male.png"/>
                    <img alt v-if="!profile.photo && profile.gender != 'Male'" src="assets/img/female.png"/>
                  </a>

                  <div class="dropdown-menu">
                    <div class="main-header-profile bg-primary p-3">
                      <div class="d-flex wd-100p">
                        <div v-if="profile.photo" class="main-img-user"><img alt :src="'assets/img/profile/'+ profile.photo" /></div>
                        <div v-if="!profile.photo && profile.gender == 'Male'" class="main-img-user"><img alt src="assets/img/male.png" /></div>
                        <div v-if="!profile.photo && profile.gender != 'Male'" class="main-img-user"><img alt src="assets/img/female.png" /></div>
                        <div class="ms-3 my-auto">
                          <div class="d-flex">
                            <h6 class="text-capitalize text-white">{{ profile.name }}</h6>
                            <h6 class="text-capitalize text-white d-sm-none ms-2">{{ profile.surname }}</h6>
                          </div>
                          <span class="text-capitalize">Username: {{ profile.username }}</span>
                        </div>
                      </div>
                    </div>
                    <a class="dropdown-item disabled" href="#"><i class="bx bx-user-circle"></i>Profile</a>
                    <a class="dropdown-item disabled" href="#"><i class="bx bx-cog"></i> Edit Profile</a>
                    <a class="dropdown-item disabled" href="#"><i class="bx bxs-inbox"></i>Inbox</a>
                    <a class="dropdown-item disabled" href="#"><i class="bx bx-envelope"></i>Messages</a>
                    <a class="dropdown-item disabled" href="#"><i class="bx bx-slider-alt"></i> Account Settings</a>
                    <a class="dropdown-item cur-pointer" @click="signOut()"><i class="bx bx-log-out"></i> Sign out</a>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="container-fluid" style="padding: 10px 10px 0px 10px">
          <router-view></router-view>
        </div>
        <!-- <div class="container-fluid p-sm-2 p-0">
          <router-view></router-view>
        </div>-->
      </div>

      <!-- Footer opened -->
      <!-- <div class="main-footer ht-40" v-if="isSignin">
				<div class="container-fluid pd-t-0-f ht-100p">
					<span>Copyright © 2022 <a href="#">XPPL</a>. Designed by <a href="#">Chamlong</a> All rights reserved.</span>
				</div>
      </div>-->
    </div>

    <!-- Modal preview -->
    <div class="modal fade effect-scale" id="PRPreviewNotic" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header pb-1 bd-b-0">
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
                    <textarea class="form-control" style="height: 80px" placeholder="Comments" v-model="approved_note"></textarea>
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "XpplAppApp",

  data() {
    return {
      isSignin: false,
      appClass: "",

      homeM: "",
      macroM: "",

      //Dashboard
      dash: "",
      dashM: "",
      dashS1: "",
      dashS2: "",
      dashS3: "",
      dashS4: "",
      dashS5: "",
      dashS6: "",

      //HQ Payment Requisition
      HQPay: "",
      HQPayM: "",
      HQPayS1: "",
      HQPayS2: "",

      // ADMIN TASK
      adm: "",
      admM: "",
      admS1: "",
      admS2: "",
      admS3: "",
      admS4: "",
      admS5: "",
      admS6: "",
      admS7: "",

      // FUEL
      fuel: "",
      fueM: "",
      fueS1: "",
      fueS2: "",
      fueS3: "",

      // PRODUCTION
      prod: "",
      proM: "",
      proS1: "",

      // SAFETY
      safe: "",
      safM: "",
      safS1: "",
      safS2: "",
      safS3: "",

      // INVENTORY
      invExp: "",

      // SETTING
      sett: "",
      setM: "",
      setS1: "",
      setS2: "",

      // CLINIC
      clin: "",
      clinM: "",
      clinS1: "",
      clinS2: "",

      profile: [],
      permiss: [],
      pendData: [],
      pendFile: [],
      attFiles: [],

      prevData: "",
      approved_note: ""

    };
  },

  mounted() {},

  methods: {
    async onLoad() {
      const pendData = await this.getPending();
      const pendFile = await this.getPendingFile();
    },

    signOut() {
      this.$axios
        .post("api/signOut")
        .then(response => {
          if (response.data.success) {
            window.location.href = "/"; // ໄປໜ້າທຳອິດ
          } else {
            console.log(response.data.message);
          }
        })
        .cath(function(error) {
          console.error(error);
        });
    },

    ResetAct() {
      // this.homeM = "";
      this.macroM = "";

      this.dashM = "",
      this.dashS1 = "",
      this.dashS2 = "",
      this.dashS3 = "",
      this.dashS4 = "",
      this.dashS5 = "",
      this.dashS6 = "",

      this.HQPayM = "",
      this.HQPayS1 = "",
      this.HQPayS2 = "",

      this.admM = "";
      this.admS1 = "";
      this.admS2 = "";
      this.admS3 = "";
      this.admS4 = "";
      this.admS5 = "";
      this.admS6 = "";
      this.admS7 = "";

      this.fueM = "";
      this.fueS1 = "";
      this.fueS2 = "";
      this.fueS3 = "";

      this.clinM = "";
      this.clinS1 = "";
      this.clinS2 = "";
      this.clinS3 = "";

      this.safM = "";
      this.safS1 = "";
      this.safS2 = "";
      this.safS3 = "";

      this.proM = "";
      this.proS1 = "";
      this.proS2 = "";

      this.setM = "";
      this.setS1 = "";
      this.setS2 = "";
    },

    Language(lang) {
      this.$i18n.locale = lang;
      this.$axios.get(`/api/language?lang=${lang}`);
    },

    async getProfile() {
      const response = await axios.get("api/profile");
      this.profile = response.data;
    },

    async getPermiss() {
      const response = await axios.get("api/permiss");
      this.permiss = response.data;

      this.$i18n.locale = response.data.lang;
    },

    async menu() {
      const menu = await axios.get("/api/menu");
      this.menuAcc = menu.data;
    },

    async getPending(){
      const pend = await axios.get('/api/hqpayment/getpendingnotic')
      this.pendData = pend.data;
    },

    async getPendingFile(){
      const file = await axios.get('/api/hqpayment/getpendingnoticfile')
      this.pendFile = file.data;
    },

    prevPayment(id){
      let data = this.pendData.find((i) => i.id == id);
      this.prevData = data;

      this.attFiles = [];
      let file = this.pendFile.filter((i) => i.pr_id == id);
      this.attFiles = file;

      this.approved_note = "";
      $('#PRPreviewNotic').modal('show');
    },

    rejected_cf(id){
      $('#PRPreviewNotic').modal('hide');
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
            note: this.approved_note
          })
          .then(res => {
            this.getPending();
          })
        } else {
          $('#PRPreviewNotic').modal('show');
        }
      });
    },

    approved_cf(id, submit){
      $('#PRPreviewNotic').modal('hide');
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
            note: this.approved_note
          })
          .then(res => {
            this.getPending();
          })
        } else {
          $('#PRPreviewNotic').modal('show');
        }
      });
    },

    view(filename){
      window.open(window.location.origin+'/assets/HQPayment/'+ filename, "_blank")
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

    fileName(filename){
      if (filename){
        let txt1 = filename.split('-')[0]
        return filename.split(txt1 +'-')[1];
      };
    },

    docType(txt) {
      if (txt) {
        let txt1 = txt.split(" ")[1]
        return txt.substring(0, 1) + "" + txt1.substring(0, 1);
      }
    }

  },

  created() {
    // console.log("ສະຖານະ Login: "+window.Laravel.isLoggedin_laravel);
    // console.log(window.Laravel.user);

    if (window.Laravel.isLoggedin_laravel) {
      this.isSignin = true;
      this.appClass = "main-content app-content";

      this.homeAct = "active";
      this.getProfile();
      this.getPermiss();
      this.onLoad();
    } else {
      this.isSignin = false;
      this.appClass = "";
    }
  }
};
</script>

<style src="@vueform/multiselect/themes/default.css">
</style>

