<template>
    <div>
        <router-link class="btn btn-primary"  :to="{name: 'create user'}">
            Create User
        </router-link>
        <router-link class="btn btn-secondary"  :to="{name: 'user list'}">
            User List
        </router-link>
        <p></p>
        <div class="alert alert-success mb-3" v-if="message!=null" role="alert">
            {{message}}
        </div>
        <p></p>
        <router-view @setmessage="setMessageEvent"></router-view>
    </div>
</template>


<script>
export default {
    data(){
        return {
            message: null,
        }
    },
    methods: {
        setMessageEvent(text){
            this.message= text;
            setTimeout(()=> { 
                this.message= null;
            }, 3000);
        }
    }
}
</script>



<style scoped>

</style>