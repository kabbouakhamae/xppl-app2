<template>
    <div>

        <!-- <loading/> -->
        
    </div>

        <h1>Fuel Report</h1>
        <hr>
        <h1 class="laofont mt-5">ຢູ່ພາຍ​ໃຕ້​ການ​ພັດ​ທະ​ນາ!</h1>
        <h1>Under Development!</h1>

        

</template>

<script>
import loading from '../components/loading.vue';
export default {

    name: 'XpplAppFuelReport',

    data() {
        return {
            ProxiesList: [{id:1, ProxyIP:1}, {id:2, ProxyIP:2}, {id:3, ProxyIP:3}, {id:4, ProxyIP:4}],
            
            names: [],
            selectedRow: null,
            userid: '',
            key: '',
            active: false
        };
    },

    mounted() {
        
    },

    methods: {
    
        setBg(id) {
            this.selectedRow = id
        },

         getYearLK(){
            this.$axios.get('/api/lookup/year').then((res) => {
                this.names = res.data;
            })
        },

        rowSelected(key, userid){
            this.selectedRow = key;
            this.userid = userid;
            this.key = key;
        },
    },

     created(){
        this.getYearLK();
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