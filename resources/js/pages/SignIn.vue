<template>
    <div>
        
        <div class=" d-flex align-items-center" style="height: 100vh">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-7 mx-auto">
                        <div class="card pd-20">
                            <h5>Sign In</h5>
                            <!-- <form class="form-group mt-5"> -->
                                <div class="form-group mt-5">
                                    <input class="form-control" type="text" placeholder="Username" v-model="username">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" placeholder="Password" v-model="password" @keyup.enter="signIn()">
                                </div>

                                <button class="btn btn-primary btn-block mt-4" @click="signIn()">Sign In</button>
                                <!-- <input type="submit" class="btn btn-primary w-100" value="Log In" @click="login()"> -->

                                <div class="alert alert-warning mt-2 mb-0" role="alert" v-if="showErr">
                                    <strong>Warning!</strong> {{ textErr }}
                                </div>
                                <p class=" text-muted mt-3">Don't have an account? <router-link to="/signup"> &nbsp; Sign up</router-link></p>

                            <!-- </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'XpplAppLogin',

    data() {
        return {
            username: '',
            password: '',
            showErr: false,
            textErr: ''
            
        };
    },

    mounted() {
        
    },

    methods: {
        signIn(){
            if(this.username != "" && this.password !=""){
                this.showErr = false;
                this.textErr = "";

                this.$axios.post("api/signIn",{   
                        username: this.username,
                        password: this.password
                    }).then((response) => {                           
                        if(response.data.success){
                            //this.$router.push('login'); // ໄປໜ້າ Login
                            window.location.href = "/";
                        } else {
                            this.showErr = true;
                            this.textErr = response.data.message;
                        }
                    }).cath(function(error){
                        console.error(error);
                    });
        } else {
                this.showErr = true;
                this.textErr = "Please fill out all required fields!";
            };
        },
        
    },
    
    beforeRouteEnter(to, from, next){
        if(window.Laravel.isLoggedin_laravel){
            window.location.href = "/";
        }

        next();
	}
};
</script>

<style>


</style>