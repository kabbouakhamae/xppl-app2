
import { createStore } from "vuex";

export default createStore({
    state: {
        name: '',
        dept: '',
        usertype: 'full'
    },
    mutations:{
        setName(state, name){
            state.name = name
        },
        setDept(state, dept){
            state.dept = dept
        },
        setUsertype(state, usertype){
            state.usertype = usertype
        }
    },
    getters:{
        name:state=>state.name,
        dept:state=>state.dept,
        usertype:state=>state.usertype
    }
});
