<template>
    <div>
        <!-- <div class="alert alert-success" v-if="message!=null" role="alert">
            {{message}}
        </div> -->

        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                
                <user-element v-for="(item,index) in userdata" :key="item.data.user_id" :user="item" :index="index" :isDisable="isDisable" @delete="deleteEvent"></user-element>
            </tbody>
        </table>
        <!-- <div v-for="fruit in fruits" :key="fruit">{{fruit}}</div>
        <div v-for="ele in eledata" :key="ele.data.ele_id">{{ele.data.attributes.name}}</div> -->
    </div>
</template>


<script>
import UserElement from './UserListElement.vue';

export default {
    components:{
        UserElement,
    },
    data(){
        return {
            userdata: [],
            isDisable: false,
            // message: null,
        }
    },
    mounted(){
        axios.get('/api/list-account')
            .then(res => {
                this.userdata =res.data.data;
                console.log( res.data);
                // console.log(res.data.data[1].data.user_id);
                // console.log(res.data.data[1].data.attributes.name);
                // console.log(this.userdata);
            })
            .catch(error => {
                console.log('Unable to fetch user data');
            });
    },
    methods: {
        deleteEvent(id){
            this.isDisable=true;
            console.log(id);
            var url='/api/delete-account/'+String(id);
            axios.get(url)
                .then(res=> { 
                    console.log( res.data);
                    this.userdata = this.userdata.filter((user)=>{
                        return (user.data.user_id != id);
                    });
                    // this.message= res.data.massage;
                    // setTimeout(()=> { 
                    //     this.message= null;
                    // }, 3000);
                    this.$emit('setmessage',res.data.massage);
                    this.isDisable=false;
                })
                .catch(error => {
                    console.log('Unable to delete user');
                    this.isDisable=false;
                });
        }
    }
}
</script>



<style scoped>

</style>