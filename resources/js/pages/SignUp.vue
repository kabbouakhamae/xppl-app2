<template>
    <div>
        
        <div class=" d-flex align-items-center" style="height: 100vh">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                        <div class="card pd-20">
                            <h5>Sign Up</h5>
                            <div class="form-group multi-color mt-5">
                                <!-- <label class="mb-0">Name</label>  -->
                                <Multiselect :options="nameLK" placeholder="Full name" searchable ="true" searchStart="true" v-model="userid" />
                            </div>
                            
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Username" v-model="username">
                            </div>

                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="Password" v-model="password1">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="Confirm password" v-model="password2" @keyup.enter="signUp()">
                            </div>

                            <button class="btn btn-primary btn-block mt-4" @click="signUp()">Sign Up</button>
                            <div class="alert alert-warning mt-2 mb-0" role="alert" v-if="showErr">
                                <strong>Warning!</strong> {{ textErr }}
                            </div>
                            <p class=" text-muted mt-3">Already have an account? <router-link to="/signin"> &nbsp; Sign in</router-link></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'XpplAppSignup',

    data() {
        return {
            nameLK: []
            ,userid: ''
            ,username: ''
            ,password1: ''
            ,password2: ''
            ,showErr: false
            ,textErr: ''
            
        };
    },

    mounted() {
        
    },

    methods: {
        getNameLK(){
            this.$axios.get('api/lookup/name')
            .then((res)=>{
                this.nameLK = res.data;
            })
        },

        signUp(){
            if(this.userid == null || this.username == "" || this.password1 == ""){
                this.showErr = true;
                this.textErr = "Please fill out all required fields!";
            } else {

                if(this.password1 == this.password2)
                    {
                        this.showErr = false;
                        this.textErr = "";

                        /// ທຳການຍິງຂໍ້ມູນ ສົ່ງໄປ Back-end ໂດຍໃຊ້ axios
                        this.$axios.post("api/signUp",{   
                            userid: this.userid
                            ,username: this.username
                            ,password: this.password1
                        }).then((response) => {
                            
                            if(response.data.success){
                                this.$router.push('signin'); // ໄປໜ້າ Login
                            } else {
                                this.showErr = true;
                                this.textErr = response.data.message;
                            }
                        }).cath(function(error){
                            console.error(error);
                        });

                    } else {
                        this.showErr = true;
                        this.textErr = "Confirm password doesn't match!"
                    }
            }
        },
        
    },

    created(){
        this.getNameLK();
    },

    beforeRouteEnter(to, from, next){
        if(window.Laravel.isLoggedin_laravel){
            window.location.href = "/";
        }
        next();
	}
};
</script>

<style lang="scss" scoped>

</style>