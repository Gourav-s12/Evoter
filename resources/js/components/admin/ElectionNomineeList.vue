<template>
    <div>
        <p></p>
        <div class="" align="center">
            <router-link class="btn btn-primary"  :to="{ name: 'election nominee create', params: { ele_id: this.$route.params.ele_id } }">
                Create Nomineee
            </router-link>
        </div>

        <p></p>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">...</th>
                <th scope="col">...</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        
        <nom-element v-for="(item,index) in nomdata" :key="item.data.user_id" :nom="item" :index="index" :isDisable="isDisable" @delete="deleteEvent"></nom-element>

        <!-- <div v-for="fruit in fruits" :key="fruit">{{fruit}}</div>
        <div v-for="ele in eledata" :key="ele.data.ele_id">{{ele.data.attributes.name}}</div> -->
    </div>
</template>


<script>
import NomElement from './NomineeListElement.vue';

export default {
    components:{
        NomElement,
    },
    data(){
        return {
            nomdata: [],
            isDisable: false,
        }
    },
    mounted(){
        console.log(this.$route.params.ele_id);
        axios.get('/api/election/'+this.$route.params.ele_id+'/nominee')
            .then(res => {
                this.nomdata =res.data.data;
                console.log( res.data.data);
            })
            .catch(error => {
                this.$emit('setmessage','Invaild Election id');
                this.$router.push('/election');
                console.log('Unable to fetch election data');
            });
    },
    methods: {
        deleteEvent(id){
            this.isDisable=true;
            console.log(id);
            var url='/api/delete-nominee/'+String(id);
            axios.get(url)
                .then(res=> { 
                    console.log( res.data.data.title);
                    this.nomdata = this.nomdata.filter((nom)=>{
                        return (nom.data.nom_id != id);
                    });
                    this.$emit('setmessage',res.data.data.title);
                    this.isDisable=false;
                })
                .catch(error => {
                    console.log('Unable to delete nominee');
                    this.isDisable=false;
                });
        }
    }
}
</script>



<style scoped>

</style>