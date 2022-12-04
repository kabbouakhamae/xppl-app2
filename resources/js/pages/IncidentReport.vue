<template>
    <div>

        <div class="card card-body">
            



            <!-- <MonthlyChart /> -->

            


    
        </div>


        


    </div>
</template>

<script>
import { defineComponent } from 'vue'
import { Bar } from 'vue3-chart-v2'

export default {
    name: 'XpplAppIncidentReport',
    extends: Bar,
    
    data() {
        return {
            prodData: []
        };
    },

    mounted() {
        
        // this.renderChart({
        //     labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        //     datasets: [
        //         { 
        //             label: 'GitHub Commits',
        //             backgroundColor: '#f87979',
        //             data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
        //         }
        //     ]
        // })
        
    },

    methods: {
        async onLoad(){
            const prod = await axios.get('/api/fingerscan/testprod')
            this.prodData = prod.data;
        }
    },

    created() {
        // this.onLoad();
    }
};
</script>

<style lang="scss" scoped>

</style>