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
					<div class="app-sidebar__user clearfix"></div>
					<ul class="side-menu">
						<li class="side-item side-item-category"></li>

						<li class="slide" @click="ResetExp(), ResetAct(), homeM='active'">
							<router-link class="side-menu__item" :class="homeM" to="/">
								<span class="side-menu__label"><i class="mdi mdi-home tx-center tx-22 wd-25"></i><span class="mg-l-10 mg-t-4"> {{ $t('home')}} </span></span>
							</router-link>
						</li>

						<!-- Admin Task -->
                        <li class="slide" :class="admExp" @click="ResetExp(), admExp?admExp='':admExp='is-expanded'">
							<a class="side-menu__item" :class="admM" href="#">
								<span class="side-menu__label"><i class="mdi mdi-deskphone tx-21 tx-center wd-25"></i><span class="mg-l-10 mg-t-3">{{ $t('admin')}}</span></span>
								<i class="angle fe fe-chevron-down mg-t-4"></i>
							</a>
							<ul class="slide-menu" >
								<li><router-link class="slide-item" :class="admS1" @click="ResetAct(), admS1='active', admM='active'" to="/employee">{{ $t('employee')}}</router-link></li>
								<li><router-link class="slide-item" :class="admS2" @click="ResetAct(), admS2='active', admM='active'" to="/roster">{{ $t('roster')}}</router-link></li>
								<li><router-link class="slide-item" :class="admS3" @click="ResetAct(), admS3='active', admM='active'" to="/fingerscan">{{ $t('scan')}}</router-link></li>			
								<li><router-link class="slide-item" :class="admS4" @click="ResetAct(), admS4='active', admM='active'" to="/overtime">{{ $t('OT')}}</router-link></li>			
								<li><router-link class="slide-item" :class="admS5" @click="ResetAct(), admS5='active', admM='active'" to="/annual">{{ $t('al')}}</router-link></li>			
								<li><router-link class="slide-item" :class="admS6" @click="ResetAct(), admS6='active', admM='active'" to="/leaveinfo">{{ $t('leave')}}</router-link></li>			
								<li><router-link class="slide-item" :class="admS7" @click="ResetAct(), admS7='active', admM='active'" to="/transport">{{ $t('trans')}}</router-link></li>			
							</ul>
						</li>

						<!-- FUEL -->
						<div v-if="permiss.fuel==1">
							<li class="slide" :class="fueExp" @click="ResetExp(), fueExp?fueExp='':fueExp='is-expanded'">
								<a class="side-menu__item" :class="fueM" href="#">
									<span class="side-menu__label"><i class="mdi mdi-gas-station tx-center tx-22 wd-25"></i><span class="mg-l-10 mg-t-4">{{ $t('fuel')}}</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="fueS1" @click="ResetAct(), fueS1='active', fueM='active'" to="/fuelcons">{{ $t('reserve') }}</router-link></li>
									<li><router-link class="slide-item" :class="fueS2" @click="ResetAct(), fueS2='active', fueM='active'" to="/fuelreport">{{ $t('report') }}</router-link></li>
									<li><router-link class="slide-item" :class="fueS3" @click="ResetAct(), fueS3='active', fueM='active'" to="/fuellookup">{{ $t('lookup') }}</router-link></li>
								</ul>
							</li>
						</div>

						<!-- INVENTORY -->
						<!-- <div v-if="permiss.pr_acc == 1">
							<li class="slide" :class="inventExp" @click="ResetExp(), inventExp?inventExp='':inventExp='is-expanded'">
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
						</div> -->

						<!-- PRODUCTION -->
						<div v-if="permiss.prod_acc==1">
							<li class="slide" :class="proExp" @click="ResetExp(), proExp?proExp='':proExp='is-expanded'">
								<a class="side-menu__item" :class="proM" href="#">
									<span class="side-menu__label"><i class="mdi mdi-chart-line tx-center tx-18 wd-25"></i><span class="mg-l-10 mg-t-4">{{ $t('production') }}</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="proS1" @click="ResetAct(), proS1='active', proM='active'" to="/prodentry">Data Entry</router-link></li>
									
									<!-- <li><router-link class="slide-item" :class="fuelReportAct" @click="fuelReportClick()" to="/fuelreport">Report</router-link></li>
									<li><router-link class="slide-item" :class="fuelLookupAct" @click="fuelLookup()" to="/fuellookup">Lookup</router-link></li> -->
								</ul>
							</li>
						</div>

						<!-- SAFETY -->
						<div v-if="permiss.safety==1">
							<li class="slide" :class="safExp" @click="ResetExp(), safExp?safExp='':safExp='is-expanded'">
								<a class="side-menu__item" :class="safM" href="#">
									<span class="side-menu__label"><i class="fa fa-medkit tx-16 tx-center wd-25"></i><span class="mg-l-10 mg-t-2">{{ $t('safety') }}</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="safS1" @click="ResetAct(), safS1='active', safM='active'" to="/increcord">{{ $t('incident') }}</router-link></li>
									<!-- <li><router-link class="slide-item" :class="safS2" @click="ResetAct(), safS2='active', safM='active'" to="/increport">Report</router-link></li> -->
									<li><router-link class="slide-item" :class="safS3" @click="ResetAct(), safS3='active', safM='active'" to="/inclookup">{{ $t('lookup') }}</router-link></li>
									<!-- <li><router-link class="slide-item" :class="safS4" @click="ResetAct(), safS4='active', safM='active'" to="/safdocs">File Storage</router-link></li> -->
								</ul>
							</li>
						</div>

						<!-- CLINIC -->
						<div v-if="permiss.clinic_acc==1">
							<li class="slide" :class="cliExp" @click="ResetExp(), cliExp?cliExp='':cliExp='is-expanded'">
								<a class="side-menu__item" :class="cliM" href="#">
									<span class="side-menu__label"><i class="fa fa-plus-square tx-17 tx-center wd-25"></i><span class="mg-l-10 mg-t-2">{{ $t('clinic') }}</span></span>
									<i class="angle fe fe-chevron-down mg-t-4"></i>
								</a>
								<ul class="slide-menu">
									<li><router-link class="slide-item" :class="cliS1" @click="ResetAct(), cliS1='active', cliM='active'" to="/treatment">{{ $t('treatment') }}</router-link></li>
									<li><router-link class="slide-item" :class="cliS2" @click="ResetAct(), cliS2='active', cliM='active'" to="/inclookup">{{ $t('drugstore') }}</router-link></li>
									<li><router-link class="slide-item" :class="cliS3" @click="ResetAct(), cliS3='active', cliM='active'" to="/safdocs">{{ $t('lookup') }}</router-link></li>
								</ul>
							</li>
						</div>

						<!-- SETTING -->
						<li class="slide" :class="setExp" @click="ResetExp(), setExp?setExp='':setExp='is-expanded'">
							<a class="side-menu__item" :class="setM" href="#">
								<span class="side-menu__label"><i class="typcn typcn-cog-outline tx-center tx-22 wd-25"></i><span class="mg-l-10 mg-t-5">{{ $t('setting') }}</span></span>
								<i class="angle fe fe-chevron-down mg-t-4"></i>
							</a>
							<ul class="slide-menu">
								<li v-if="permiss.lk_add==1"><router-link class="slide-item" :class="setS1" @click="ResetAct(), setS1='active', setM='active'" to="/lookup">{{ $t('lookup') }}</router-link></li>
								<li v-if="permiss.permiss==1"><router-link class="slide-item" :class="setS2" @click="ResetAct(), setS2='active', setM='active'" to="/permiss">{{ $t('permiss') }}</router-link></li>
							</ul>
						</li>

						<!-- MACRO -->
						<li v-if="permiss.mac_acc==1" class="slide" @click="ResetExp(), ResetAct(), macroM='active'">
							<router-link class="side-menu__item" :class="macroM" to="/macro">
								<span class="side-menu__label"><i class="mdi mdi-file-excel tx-22 tx-center wd-25"></i><span class="mg-l-10 mg-t-4">{{ $t('macro') }}</span></span>
							</router-link>
						</li>

					</ul>
				</div>
			</aside>



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

								<li class="nav me-3">
									<div class="dropdown nav-itemd-none d-md-flex">
										<a href="#" class="d-flex nav-item country-flag1" data-bs-toggle="dropdown" aria-expanded="false" title="ປ່ຽນພາສາ">
											<span class="avatar country-Flag me-0 align-self-center bg-transparent">
												<img v-if="$i18n.locale=='en'" src="assets/img/flags/us_flag.jpg" alt="img">
												<img v-else src="assets/img/flags/Laos.png" alt="img">
											</span>
											<div class="my-auto">
												<strong class="me-2 ms-2 my-auto">English</strong>
											</div>
										</a>
										<div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow" x-placement="bottom-end" style="">
											<a href="#" class="dropdown-item d-flex " @click="Language('en')">
												<span class="avatar  me-3 align-self-center bg-transparent"><img src="assets/img/flags/us_flag.jpg" alt="img"></span>
												<div class="d-flex">
													<span class="mt-2">English</span>
												</div>
											</a>
											<a href="#" class="dropdown-item d-flex" @click="Language('la')">
												<span class="avatar  me-3 align-self-center bg-transparent"><img src="assets/img/flags/Laos.png" alt="img"></span>
												<div class="d-flex">
													<span class="mt-2">Lao</span>
												</div>
											</a>
										</div>
									</div>
								</li>
								
								<!-- <li class=" nav-item lh-1 me-3">
									<select class="form-control" v-model="$i18n.locale" >
										<option value="en">English</option>
										<option value="la">Laos</option>
									</select>
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
			<!-- <div class="main-footer ht-40" v-if="isSignin">
				<div class="container-fluid pd-t-0-f ht-100p">
					<span>Copyright © 2022 <a href="#">XPPL</a>. Designed by <a href="#">Chamlong</a> All rights reserved.</span>
				</div>
			</div> -->


		</div>


    </div>
</template>

<script>
export default {
    name: 'XpplAppApp',

    data() {
        return {
            isSignin: false,
            appClass: '',

			homeM:'',
			macroM: '',

		// ADMIN TASK
			admExp:'',
			admM:'',
			admS1:'',
			admS2:'',
			admS3:'',
			admS4:'',
			admS5:'',
			admS6:'',
			admS7:'',

		// FUEL
			fueExp:'',
			fueM:'',
			fueS1:'',
			fueS2:'',
			fueS3:'',

		// PRODUCTION
			proExp:'', 
			proM:'', 
			proS1:'',

		// SAFETY
			safExp: '',
			safM: '',
			safS1: '',
			safS2: '',
			safS3: '',

		// INVENTORY
			invExp: '',




		// SETTING
			setExp: '',
			setM: '',
			setS1: '',
			setS2: '',
		
		// CLINIC
			cliExp:'',
			cliM:'',
			cliS1:'',
			cliS2:'',


			profile: [],
			permiss: [],


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

		ResetExp(){
			this.admExp='';
			this.setExp='';
			this.safExp='';
			this.proExp='';
			this.invExp='';
			this.fueExp='';
			this.cliExp=''
		},

		ResetAct(){
			this.homeM='';
			this.macroM='';

			this.admM='';
			this.admS1='';
			this.admS2='';
			this.admS3='';
			this.admS4='';
			this.admS5='';
			this.admS6='';
			this.admS7='';

			this.fueM='';
			this.fueS1='';
			this.fueS2='';
			this.fueS3='';

			this.cliM='';
			this.cliS1='';
			this.cliS2='';
			this.cliS3='';

			this.safM='';
			this.safS1='';
			this.safS2='';
			this.safS3='';
			
			this.proM='';
			this.proS1='';
			this.proS2='';

			this.setM='';
			this.setS1='';
			this.setS2='';



			


		},

		Language(lang){
			this.$i18n.locale= lang;
			this.$axios.get(`/api/language?lang=${lang}`)
			// .then(res => {
			// 	this.getPermiss()
			// });
		},

		async getProfile(){
			const response = await axios.get('api/profile')
			this.profile = response.data;
		},

		async getPermiss(){
			const response = await axios.get('api/permiss')
			this.permiss = response.data;

			// console.log("Lang:", this.permiss);
			this.$i18n.locale= response.data.lang;
		},

		async menu(){
			const menu = await axios.get('/api/menu')
			this.menuAcc = menu.data;
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

