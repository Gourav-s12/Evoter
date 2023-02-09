<template>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <router-link class="nav-link"  :to="'/voteList'">
                Vote List
                </router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link"  :to="'/resultList'">
                Result
                </router-link>
            </li>
            <li class="nav-item" v-if="IsAdmin">
                <a class="nav-link text-info" @click="toogleView" v-if="!UserView">Admin View</a>
                <a class="nav-link text-info" @click="toogleView" v-else>User View</a>
            </li>
        </ul>
    </div>
</template>


<script>
import { mapGetters } from 'vuex';
export default {

    computed: {
        ...mapGetters({
            IsAdmin : 'IsAdmin',
            UserView : 'UserView'
        }),
    },
    methods: {
        toogleView(){
            this.$store.dispatch('toogleView');
        }
    },
}

</script>



<style scoped>

</style>