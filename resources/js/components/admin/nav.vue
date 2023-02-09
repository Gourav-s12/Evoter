<template>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <router-link class="nav-link"  :to="'/election'">
                Elections
                </router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link"  :to="'/user'">
                User Account
                </router-link>
            </li>
            <li class="nav-item">
                <a class="nav-link text-info" @click="toogleView" v-if="!UserView">Admin View</a>
                <a class="nav-link text-info" @click="toogleView" v-else>User View</a>
            </li>
        </ul>
    </div>
</template>


<script>
import { mapGetters } from 'vuex';

export default {
    methods: {
        toogleView(){
            this.$store.dispatch('toogleView');
        }
    },
    computed: {
        ...mapGetters({
            UserView : 'UserView',
        }),
    },
}
</script>



<style scoped>

</style>