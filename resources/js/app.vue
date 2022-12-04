<template>
    <div>
           
		<!-- Page -->
		<div class="page">
			<!-- main-sidebar -->
			<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
			<aside class="app-sidebar sidebar-scroll" v-if="isSignin">
				<div class="main-sidebar-header active">
					<a class="desktop-logo logo-light active" href="index.html"><img src="assets/img/brand/xppl3.jpg" class="main-logo" alt="logo"></a>
					<!-- <a class="desktop-logo logo-dark active" href="index.html"><img src="assets/img/brand/PSG1.jpg" class="main-logo dark-theme" alt="logo"></a> -->
					<a class="logo-icon mobile-logo icon-light active" href="index.html"><img src="assets/img/brand/xppl3.jpg" class="logo-icon" alt="logo"></a>
					<!-- <a class="logo-icon mobile-logo icon-dark active" href="index.html"><img src="assets/img/brand/favicon-white.png" class="logo-icon dark-theme" alt="logo"></a> -->
				</div>
				<div class="main-sidemenu">
					<div class="app-sidebar__user clearfix">
	
					</div>
					<ul class="side-menu">
						<li class="side-item side-item-category"></li>
						<li class="slide" @click="homeClick()">
							<router-link class="side-menu__item " :class="homeAct"  to="/">
								<span class="side-menu__label"><i class="mdi mdi-home tx-center tx-22 wd-25"></i><span class="mg-l-10 mg-t-4">Home</span></span>
							</router-link>
						</li>

						<!-- Admin Task -->
                        <li class="slide" :class="adminExp" @click="adminTask()">
							<a class="side-menu__item" :class="adminAct" href="#">
								<span class="side-menu__label"><i class="mdi mdi-deskphone tx-21 tx-center wd-25"></i><span class="mg-l-10 mg-t-3">Admin Task</span></span>
								<i class="angle fe fe-chevron-down mg-t-4"></i>
							</a>
							<ul class="slide-menu">
								<li><router-link class="slide-item" :class="employeeAct" @click="employee()" to="/employee">Employee</router-link></li>
								<li><router-link class="slide-item" :class="rosterAct" @click="roster()" to="/roster">Roster</router-link></li>
								<li><router-link class="slide-item" :class="fingerAct" @click="finger()" to="/fingerscan">Finger Scan</router-link></li>			
								<li><router-link class="slide-item" :class="overtimeAct" @click="overtime()" to="/overtime">Overtime</router-link></li>			
								<li><router-link class="slide-item" :class="annualAct" @click="annual()" to="/annual">Annual Leave</router-link></li>			
								<li><router-link class="slide-item" :class="leaveInfoAct" @click="leaveInfo()" to="/leaveinfo">Day Leave Info</router-link></li>			
								<li><router-link class="slide-item" :class="transportAct" @click="transport()" to="/transport">Daily Transport</router-link></li>			
							</ul>
						</li>

						<!-- FUEL -->
						<div v-if="!!parseInt(permiss.fuel)">
							<li class="slide" :class="fuelExp" @click="fuelClick()">
								<a class="side-menu__item" :class="fuelAct" href="#">
									<span class="side-menu__label"><i class="mdi mdi-gas-station tx-center tx-22 wd-25"></i><span class="mg-l-10 mg-t-4">Fuels</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="fuelConsAct" @click="fuelConsClick()" to="/fuelcons">Reservation</router-link></li>
									<li><router-link class="slide-item" :class="fuelReportAct" @click="fuelReportClick()" to="/fuelreport">Report</router-link></li>
									<li><router-link class="slide-item" :class="fuelLookupAct" @click="fuelLookup()" to="/fuellookup">Lookup</router-link></li>
								</ul>
							</li>
						</div>

						<!-- PRODUCTION -->
						<div v-if="permiss.prod_acc == 1">
							<li class="slide" :class="prodExp" @click="prodClick()">
								<a class="side-menu__item" :class="prodAct" href="#">
									<span class="side-menu__label"><i class="mdi mdi-chart-line tx-center tx-18 wd-25"></i><span class="mg-l-10 mg-t-4">Production</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="prodEntryAct" @click="prodEntryClick()" to="/prodentry">Data Entry</router-link></li>
									<!-- <li><router-link class="slide-item" :class="fuelReportAct" @click="fuelReportClick()" to="/fuelreport">Report</router-link></li>
									<li><router-link class="slide-item" :class="fuelLookupAct" @click="fuelLookup()" to="/fuellookup">Lookup</router-link></li> -->
								</ul>
							</li>
						</div>

						<!-- SAFETY -->
						<div v-if="!!parseInt(permiss.safety)">
							<li class="slide" :class="safetyExp" @click="safety()">
								<a class="side-menu__item" :class="safetyAct" href="#">
									<span class="side-menu__label"><i class="mdi mdi-bullhorn tx-22 tx-center wd-25"></i><span class="mg-l-10 mg-t-2">Safety</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="incRecordAct" @click="incRecord()" to="/increcord">Incidents</router-link></li>
									<li><router-link class="slide-item" :class="incReportAct" @click="incReport()" to="/increport">Report</router-link></li>
									<li><router-link class="slide-item" :class="incLookupAct" @click="incLookup()" to="/inclookup">Lookup</router-link></li>
								</ul>
							</li>
						</div>

						<!-- Addmin Car Rental -->
						<div v-if="!!parseInt(permiss.carAcc)">
							<li class="slide" :class="carRentExp" @click="carRent()">
								<a class="side-menu__item" :class="carRentAct" href="#">
									<span class="side-menu__label"><i class="mdi mdi-car tx-22 tx-center wd-25"></i><span class="mg-l-10 mg-t-4">Car Rentals</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="carRentDetalAct" @click="carRentDetail()" to="/carrentdetail">Rental Detail</router-link></li>
									<li><router-link class="slide-item" :class="carRentReportAct" @click="carRentReport()" to="/carrentreport">Report</router-link></li>
									<li><router-link class="slide-item" :class="carRentCodeAct" @click="carRentCode()" to="/carrentcode">Lookup</router-link></li>
								</ul>
							</li>
						</div>
						


						<!-- Setting -->
						<li class="slide" :class="settExp" @click="settClick()">
							<a class="side-menu__item" :class="settAct" href="#">
								<span class="side-menu__label"><i class="typcn typcn-cog-outline tx-center tx-22 wd-25"></i><span class="mg-l-10 mg-t-5">Setting</span></span>
								<i class="angle fe fe-chevron-down mg-t-4"></i>
							</a>
							<ul class="slide-menu">
								<li v-if="!!parseInt(permiss.lk_add)"><router-link class="slide-item" :class="lookupAct" @click="lookupClick()" to="/lookup">Lookup</router-link></li>
								<li v-if="!!parseInt(permiss.permiss)"><router-link class="slide-item" :class="permissAct" @click="permissClick()" to="/permiss">Permission</router-link></li>
							</ul>
						</li>

						<li v-if="!!parseInt(permiss.mac_acc)" class="slide " @click="macro()">
							<router-link class="side-menu__item" :class="macroAct" to="/macro">
								<span class="side-menu__label"><i class="mdi mdi-file-excel tx-22 tx-center wd-25"></i><span class="mg-l-10 mg-t-4">Excel Macro</span></span>
							</router-link>
						</li>

					</ul>
				</div>
			</aside>
			<!-- main-sidebar -->

			<!-- main-content -->
			<div :class="appClass">

				<!-- main-header -->
				<div class="main-header sticky side-header nav nav-item" v-if="isSignin">
					<div class="container-fluid ps-sm-0 p-2">
						<div class="main-header-left ">		
							<div class="app-sidebar__toggle" data-bs-toggle="sidebar">
								<a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
								<a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
							</div>
							<div class="text-muted text-capitalize" style="padding-top: 9px">
								<h5>{{ profile.department }}</h5>
							</div>
						</div>
						<div class="main-header-right">
							<ul class="nav nav-item  navbar-nav-right ms-auto">
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
								</li> -->

								<li class="dropdown main-profile-menu nav nav-item nav-link p-0">
									
										<a class="profile-user d-flex" href="">
											<div class="d-flex align-items-center">
												<span class="d-none d-sm-flex text-muted text-capitalize me-2">{{ profile.name }} {{ profile.surname }}</span>
											</div>
											<img alt="" v-if="profile.photo" :src="'assets/img/profile/'+ profile.photo">
											<img alt="" v-if="!profile.photo && profile.gender == 'Male'" src="assets/img/male.png">
											<img alt="" v-if="!profile.photo && profile.gender != 'Male'" src="assets/img/female.png">
										</a>

									
									
									<div class="dropdown-menu">
										<div class="main-header-profile bg-primary p-3">
											<div class="d-flex wd-100p">
												<div v-if="profile.photo" class="main-img-user"><img alt="" :src="'assets/img/profile/'+ profile.photo"></div>
												<div v-if="!profile.photo && profile.gender == 'Male'" class="main-img-user"><img alt="" src="assets/img/male.png"></div>
												<div v-if="!profile.photo && profile.gender != 'Male'" class="main-img-user"><img alt="" src="assets/img/female.png"></div>											
												<div class="ms-3 my-auto">
													<div class="d-flex">
														<h6 class="text-capitalize text-white">{{ profile.name }}</h6>
														<h6 class="text-capitalize text-white d-sm-none ms-2">{{ profile.surname }}</h6>
													</div>
													<span class="text-capitalize"> Username: {{ profile.username }}</span>
												</div>
											</div>
										</div>
										<a class="dropdown-item disabled" href="#"><i class="bx bx-user-circle"></i>Profile</a>
										<a class="dropdown-item disabled" href="#"><i class="bx bx-cog"></i> Edit Profile</a>
										<a class="dropdown-item disabled" href="#"><i class="bx bxs-inbox"></i>Inbox</a>
										<a class="dropdown-item disabled" href="#"><i class="bx bx-envelope"></i>Messages</a>
										<a class="dropdown-item disabled" href="#"><i class="bx bx-slider-alt"></i> Account
											Settings</a>
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
				</div> -->

			</div>


			<!-- Footer opened -->
			<div class="main-footer ht-40" v-if="isSignin">
				<div class="container-fluid pd-t-0-f ht-100p">
					<span>Copyright © 2022 <a href="#">XPPL</a>. Designed by <a href="#">Chamlong</a> All rights reserved.</span>
				</div>
			</div>
			<!-- Footer closed -->

		</div>
		<!-- End Page -->


    </div>
</template>

<script>
export default {
    name: 'XpplAppApp',

    data() {
        return {
            isSignin: false,
            appClass: '',

			homeAct: '',
			lookupAct: '',
			permissAct: '',

			adminAct: '',
			employeeAct: '',
			rosterAct: '',
			fingerAct: '',
			overtimeAct: '',
			annualAct: '',
			leaveInfoAct: '',
			transportAct: '',

			fuelAct: '',
			fuelConsAct: '',
			fuelReportAct: '',
			fuelLookupAct: '',

			carRentAct: '',
			carRentDetailAct: '',
			carRentReportAct: '',
			carRentCodeAct: '',

			safetyAct: '',
			incRecordAct: '',
			incReportAct: '',
			incLookupAct: '',

			prodAct: '',


			adminExp: '',
			fuelExp: '',
			carRentExp: '',
			settExp: '',
			safetyExp: '',
			prodExp: '',

			profile: [],
			permiss: []
        };
    },

    mounted() {
        
    },

    methods: {
        signOut(){
            this.$axios.post("api/signOut").then((response) => {
                if(response.data.success){
                    window.location.href = "/" // ໄປໜ້າທຳອິດ
                } else {
                    console.log(response.data.message);
                }
            }).cath(function(error){
                console.error(error);
            });
        },

		homeClick(){
			this.clearAct();
			this.homeAct = 'active';
		},

		employee(){
			this.clearAct();
			this.adminAct = 'active';
			this.employeeAct = 'active';
		},
		
		roster(){
			this.clearAct();
			this.adminAct = 'active';
			this.rosterAct = 'active';
		},

		finger(){
			this.clearAct();
			this.adminAct = 'active';
			this.fingerAct = 'active';
		},

		overtime(){
			this.clearAct();
			this.adminAct = 'active';
			this.overtimeAct = 'active';
		},
		
		annual(){
			this.clearAct();
			this.adminAct = 'active';
			this.annualAct = 'active';
		},

		leaveInfo(){
			this.clearAct();
			this.adminAct = 'active';
			this.leaveInfoAct = 'active';
		},

		transport(){
			this.clearAct();
			this.adminAct = 'active';
			this.transportAct = 'active';
		},

		lookupClick(){
			this.clearAct();
			this.settAct = 'active';
			this.lookupAct = 'active';
		},

		permissClick(){
			this.clearAct();
			this.settAct = 'active';
			this.permissAct = 'active';
		},

		fuelConsClick(){
			this.clearAct();
			this.fuelAct = 'active';
			this.fuelConsAct = 'active';
		},

		fuelReportClick(){
			this.clearAct();
			this.fuelAct = 'active';
			this.fuelReportAct = 'active';
		},

		fuelLookup(){
			this.clearAct();
			this.fuelAct = 'active';
			this.fuelLookupAct = 'active';
		},

		carRentDetail(){
			this.clearAct();
			this.carRentAct = 'active';
			this.carRentDetailAct = 'active';
		},

		carRentReport(){
			this.clearAct();
			this.carRentAct = 'active';
			this.carRentReportAct = 'active';
		},

		carRentCode(){
			this.clearAct();
			this.carRentAct = 'active';
			this.carRentCodeAct = 'active';
		},

		incRecord(){
			this.clearAct();
			this.safetyAct = 'active';
			this.incRecordAct = 'active';
		},

		incReport(){
			this.clearAct();
			this.safetyAct = 'active';
			this.incReportAct = 'active';
		},

		incLookup(){
			this.clearAct();
			this.safetyAct = 'active';
			this.incLookupAct = 'active';
		},

		prodEntryClick(){
			this.clearAct();
			this.prodAct = 'active';
			this.prodEntryAct = 'active';
		},

		fuelClick(){
			if (this.fuelExp){
				this.fuelExp = '';
			} else {
				this.settExp = '';
				this.adminExp = '';
				this.carRentExp = '';
				this.safetyExp = '',
				this.prodExp = '';
				this.fuelExp = 'is-expanded';
			}
		},

		adminTask(){
			if (this.adminExp){
				this.adminExp = '';
			} else {
				this.settExp = '';
				this.fuelExp = '';
				this.carRentExp = '';
				this.safetyExp = '',
				this.prodExp = '';
				this.adminExp = 'is-expanded';
			}
		},

		carRent(){
			if (this.carRentExp){
				this.carRentExp = '';
			} else {
				this.settExp = '';
				this.fuelExp = '';
				this.adminExp = '';
				this.safetyExp = '',
				this.prodExp = '';
				this.carRentExp = 'is-expanded';
			}
		},

		settClick(){
			if (this.settExp){
				this.settExp = '';
			} else {
				this.adminExp = '';
				this.fuelExp = '';
				this.carRentExp = '';
				this.safetyExp = '',
				this.prodExp = '';
				this.settExp = 'is-expanded';
			}
		},

		safety(){
			if (this.safetyExp){
				this.safetyExp = '';
			} else {
				this.settExp = '';
				this.fuelExp = '';
				this.adminExp = '';
				this.carRentExp = '';
				this.prodExp = '';
				this.safetyExp = 'is-expanded';
			}
		},

		prodClick(){
			if (this.prodExp){
				this.prodExp = '';
			} else {
				this.settExp = '';
				this.fuelExp = '';
				this.adminExp = '';
				this.carRentExp = '';
				this.safetyExp = '';
				this.prodExp = 'is-expanded';
			}
		},

		macro(){
			this.clearAct();
			this.macroAct = 'active';
		},

		clearAct(){
			this.homeAct ='';
		
			this.adminAct = '';
			this.employeeAct = '';
			this.rosterAct = '';
			this.fingerAct = '';
			this.overtimeAct = '';
			this.annualAct = '';
			this.leaveInfoAct = '';
			this.transportAct = '';

			this.lookupAct = '';
			this.permissAct = '';
			this.settAct = '';

			this.fuelAct = '';
			this.fuelConsAct = '';
			this.fuelReportAct = '';
			this.fuelLookupAct = '';

			this.carRentAct = '';
			this.carRentDetailAct = '';
			this.carRentReportAct = '';
			this.carRentCodeAct = '';
			this.macroAct = '';

			this.safetyAct = '';
			this.incRecordAct = '';
			this.incReportAct = '';
			this.incLookupAct = '';

			this.prodAct = '';
			this.prodEntryAct = '';

			this.fuelExp = '';
			this.adminExp = '';
			this.settExp = '';
			this.carRentExp = '';
			this.safetyExp = '';
			this.prodExp = '';
		},

		async getProfile(){
			const response = await axios.get('api/profile')
			this.profile = response.data;
		},

		async getPermiss(){
			const response = await axios.get('api/permiss')
			this.permiss = response.data;
		},

    },

    created(){

        // console.log("ສະຖານະ Login: "+window.Laravel.isLoggedin_laravel);
		// console.log(window.Laravel.user);

        if(window.Laravel.isLoggedin_laravel){
            this.isSignin = true;
            this.appClass = "main-content app-content";
			this.homeAct = 'active';
			this.getProfile();
			this.getPermiss();
        } else {
			this.isSignin = false;
            this.appClass = '';
        }
		
  }
};
</script>

<style src="@vueform/multiselect/themes/default.css">

</style>

