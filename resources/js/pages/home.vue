<template>
    <div v-if="loading">
        <loading/>
    </div> 
    <div v-else>
        <div class="row">
            <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                <div class="card bg-primary-gradient text-white ">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-users tx-40"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-0 text-center">
                                    <span class="text-white">Current Employee</span>
                                    <h2 class="text-white mb-0">{{ allemp }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                <div class="card bg-success-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-user-check tx-40"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-0 text-center">
                                    <span class="text-white">Employee Onsite</span>
                                    <h2 class="text-white mb-0">{{ onSite }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                <div class="card bg-danger-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-user-x tx-40"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-0 text-center">
                                    <span class="text-white">Employee Offsite</span>
                                    <h2 class="text-white mb-0">{{ offSite }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                <div class="card bg-warning-gradient text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="icon1 mt-2 text-center">
                                    <i class="fe fe-user tx-40"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-0 text-center">
                                    <span class="text-white">Users</span>
                                    <h2 class="text-white mb-0">{{ users }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
           <div class="col-xl-3 col-lg-5 col-md-6 col-12">
                <div class="card card-dashboard-eight">
                    <h6 class="card-title mb-0">Countries</h6>
                    <span class="d-block mg-b-10 text-muted tx-12">Current employee based by countries...</span>
                    <div class="list-group border-0" v-for="lst in countCounty" :key="lst.country" >
                        <div class="list-group-item border-top-0" id="br-t-0">
                            <i v-if="lst.country == 'Laos'" class="flag-icon flag-icon-la flag-icon-squared"></i>
                            <i v-else-if="lst.country == 'Thailand'" class="flag-icon flag-icon-th flag-icon-squared"></i>
                            <i v-else-if="lst.country == 'Philippines'" class="flag-icon flag-icon-ph flag-icon-squared"></i>
                            <i v-else-if="lst.country == 'Indonesia'" class="flag-icon flag-icon-id flag-icon-squared"></i>
                            <i v-else-if="lst.country == 'Vietnam'" class="flag-icon flag-icon-vn flag-icon-squared"></i>
                            <i v-else-if="lst.country == 'Australia'" class="flag-icon flag-icon-au flag-icon-squared"></i>
                            <i v-else-if="lst.country == 'China'" class="flag-icon flag-icon-cn flag-icon-squared"></i>
                            <i v-else-if="lst.country == 'Undefine'" class="flag-icon flag-icon-squared"></i>
                            <p>{{ lst.country }}</p><span>{{ lst.cnt }}</span>
                        </div>
                    </div>
                    <!-- <div class="mt-1 text-end">
                        {{ allemp }}
                    </div> -->
                </div>

                <div class="card card-dashboard-eight">
                    <h6 class="card-title mb-0">Departments</h6>
                    <span class="d-block mg-b-10 text-muted tx-12">Current employee based by departments...</span>
                    <div class="list-group border-0" v-for="lst in countDept" :key="lst.department" >
                        <div class="list-group-item border-top-0 py-1" id="br-t-0">
                            <p class="ms-0 tx-13">{{ lst.department }}</p><span>{{ lst.cnt }}</span>
                        </div>
                    </div>
                </div>

           </div>

           <div class="col-xl-3 col-lg-7 col-md-12 col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mg-b-0 tx-13">{{profile.department}}</h4>
                                <p class="tx-12 text-muted mb-2">Manpower on<span class="text-danger fw-bold ms-2">{{dateTime(date)}}</span></p>
                            </div>
                            <i class="mdi mdi-dots-horizontal text-gray cur-pointer" title="Select date" @click="changedDate()"></i>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="table-responsive element">
                            <table class="table mg-b-0 text-lg-nowrap">
                                <thead>
                                    <tr>
                                        <th class="px-0 text-start">Position</th>
                                        <th class="px-1 text-center">Total</th>
                                        <th class="px-1 text-center">Onsite</th>
                                        <th class="px-1 text-center">Offsite</th>
                                    </tr>
                                </thead>
                                <tbody class="tx-13">
                                    <tr v-for="lst in manpower" :key="lst.id">
                                        <td class="px-0 py-1 border-bottom">{{ lst.Position }}</td>
                                        <td class="px-1 py-1 text-center border-bottom">{{ lst.Total }} </td>
                                        <td class="px-1 py-1 text-center border-bottom">{{ lst.Onsite }} </td>
                                        <td class="px-1 py-1 text-center border-bottom">{{ lst.Offsite }} </td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 text-end fw-bold">Grand Total</td>
                                        <td class="px-1 py-2 text-center fw-bold">{{total}}</td>
                                        <td class="px-1 py-2 text-center fw-bold">{{onsite}}</td>
                                        <td class="px-1 py-2 text-center fw-bold">{{offsite}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal add Date -->
                <div class="modal fade effect-scale" id="date" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dateLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header pb-1 bd-b-0">
                                <h6 class="text-muted main-content-label text-capitalize">Select Date</h6>
                                <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">                                
                                <div class="form-group">                              
                                    <div class="d-flex justify-content-between">
                                        <input type="date" class="form-control" v-model="date" @change="selectedDate()">
                                    </div>                           
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
    name: 'XpplAppHome',

    data() {
        return {
            profile: [],
            countCounty: [],
            countDept: [],
            manpower: [],
            allemp: '',
            onSite: '',
            offSite: '',
            users: '',
            date: moment(new Date()).format('YYYY-MM-DD'),
            loading: false

        };
    },

    mounted() {
        
    },

    computed: {
        total(){
			return this.manpower.reduce((num, item) => num + parseInt(item.Total), 0)
		},
       
        onsite(){
			return this.manpower.reduce((num, item) => num + parseInt(item.Onsite), 0)
		},

        offsite(){
			return this.manpower.reduce((num, item) => num + parseInt(item.Offsite), 0)
		}
    },
    methods: {
        async onLoad(){
            this.loading = true;
            // this.date = moment(new Date()).format('YYYY-MM-DD')

            const response = await axios.get('api/profile')
			this.profile = response.data;

            const cntAllEmp = await axios.get('/api/home/allemp')
            this.allemp = cntAllEmp.data[0].cnt;

            const cntCountry = await axios.get('/api/home/countcountry')
            this.countCounty = cntCountry.data;

            const man = await axios.post('/api/home/manpower', {dept: this.profile.department, date: this.date})
            this.manpower = man.data;

            const cntEmpDept = await axios.get('/api/home/countdept')
            this.countDept = cntEmpDept.data;

            const onsite = await axios.get('/api/home/onsite')
            this.onSite = onsite.data[0].onsite;

            const offsite = await axios.get('/api/home/offsite')
            this.offSite = offsite.data[0].offsite;

            const user = await axios.get('/api/home/users')
            this.users = user.data[0].users;

            this.loading = false;
        },

        changedDate(){
            $('#date').modal('show')
        },

        selectedDate(){
            $('#date').modal('hide');
            this.$axios.post('/api/home/manpower', {
                dept: this.profile.department,
                date: this.date,
            }).then(res => this.manpower = res.data)
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
	}
};
</script>

<style>
    .base-image-input {
        display: block;
        width: 200px;
        height: 240px;
        cursor: pointer;
        background-size: cover;
        background-position: center center;
    }
    .emp-image {
        background: #F0F0F0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: gray;
    }
    .emp-image:hover {
        background: #E0E0E0;
        color: blueviolet ;
    }

    .bad{
        position: absolute;
        height: 12px;
        width: 12px;
        border: 2px solid white;
        right: 0px;
        bottom: 2px;
    }
    .cur-pointer{
        cursor: pointer;
    }

    .search-i{
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
    }
    .search-c:hover{
        background: #E9EBEC;
    }
    .search-c{ 
        position: absolute;
        z-index: 999;
        top: 50%;
        transform: translateY(-50%);
        right: 2px;
        width: 25px;
        height: 25px;
        color: gray;
        border-radius: 50px;
    }
    
    .tr-hover:hover{
        background: #f2f4f8; 
    }

	.btn-i:hover{
		background: #E9EBEC;
        color: blue;
	}
	.btn-i{
		border-radius: 50px;
	}

    .dropdown-hover:hover{
        color: blue;
        font-weight: 500;
    }

    .btn-add:hover{
        background: #DDE1E5;
        border-radius: 50px;
    }

    .main-table-reference>thead>tr>th, .main-table-reference>thead>tr>td{
        letter-spacing: 0.5px;
        padding: 7px 10px;
        border-right: 1px solid #e3e8f7;
        border-left: 1px solid #e3e8f7;
        border-top: none;
        border-bottom: none;
        background-color: #5195D3; /* #80B1F4 #5195D3*/
        color: white;
        /* color: #22252f; */
    }
    .main-table-reference>tbody>tr>th, .main-table-reference>tbody>tr>td{
        padding: 3px 10px;
    }

    .scroll-none::-webkit-scrollbar {
        display: none;
    }

    .modal-backdrop{
        opacity:0.5 !important;
    }

    .card{
        margin-bottom: 10px;
    }

    .page-link{
        height: 35px;
        width: 35px;
    }

    .btn{
        height: 35px;
        padding: 4px 14px;
    }
    .btn-sm{
        height: 25px;
        width: 25px;
    }

    .form-control{
        height: 35px;
        padding: 6px 5px 6px 12px;
    }

    .multiselect{
        min-height: 35px;
        font-size: 0.875rem;
        font-weight: 400;
        color: #4d5875;
        padding: 4px 0px 4px 12px;
        border: 1px solid #e1e5ef;
        border-radius: 3px;
    }

    .multiselect-search{
        border-radius: 3px;
        font-size: 0.875rem;
        font-weight: 400;   
        color: #4d5875;
        height: 30px;
        top: 50%;
        transform: translateY(-50%);
        padding-left: 0.75rem;
    }


</style>