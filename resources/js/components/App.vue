<template>

<div class="container"> 
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header" style="background-color: #e3f2fd;">
                    <nav class="navbar navbar-expand-md navbar-light " >
                        <router-link class="navbar-brand"  :to="'/home'">
                            Dashboard
                        </router-link>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <admin-nav v-if="!UserView && IsAdmin" />
                        <user-nav v-else />
                    </nav>
                </div>

                <div class="card-body">
                        <!-- <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div> -->
                    <!-- <ele-ele></ele-ele> -->
                    <router-view></router-view>
                        
                </div>
            </div>
        </div>
    </div>
</div>
    
</template>

<script>
// import EleEle from './ElectionElement.vue';
import AdminNav from './admin/nav.vue'
import UserNav from './user/nav.vue'
import { mapGetters } from 'vuex';

export default {
    name: "App",
    data: () => {
        return {
            electionList: 0,
            count: 1,
            isAdmin: false,
        }
    },
    components:{
        AdminNav,
        UserNav,
    },
    methods: {
        update() {
            axios.get('/api/is-admin')
            .then(res => {
                // console.log( res.data);
                console.log( res.data.message);
                this.electionList=res.data.message;
            })
            .catch(error => {
                console.log(this)
                console.log('Unable to fetch auth user');
            });
        }
    },
    beforeCreate(){
        this.$store.dispatch('fetchAuthUser');
        
    },
    computed: {
        ...mapGetters({
            UserName: 'UserName',
            UserEmail: 'UserEmail',
            IsAdmin: 'IsAdmin',
            UserView : 'UserView',
        }),
    },
}
</script>

<style>
#app{
    font-family: 'Kalam', cursive;
}

</style>
