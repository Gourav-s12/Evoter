<template>
    <div>
        <p class="text-muted" v-if="IsAdmin">Admin can not vote, only a voter can.</p>
        <p></p>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Vote</th>
                </tr>
            </thead>
            <tbody>
                
                <vote-element v-for="(item,index) in eledata" :key="item.data.ele_id" :ele="item" :index="index" :isDisable="IsAdmin"></vote-element>
            </tbody>
        </table>
        <!-- <div v-for="fruit in fruits" :key="fruit">{{fruit}}</div>
        <div v-for="ele in eledata" :key="ele.data.ele_id">{{ele.data.attributes.name}}</div> -->
    </div>
</template>


<script>
import VoteElement from './VoteListElement.vue';
import { mapGetters } from 'vuex';

export default {
    components:{
        VoteElement,
    },
    data(){
        return {
            eledata: [],
            message: null,
        }
    },
    mounted(){
        axios.get('/api/election-open')
            .then(res => {
                this.eledata =res.data.data;
                console.log( res.data);
                // console.log(res.data.data[1].data.user_id);
                // console.log(res.data.data[1].data.attributes.name);
                // console.log(this.userdata);
            })
            .catch(error => {
                console.log('Unable to fetch Election data');
            });
    },
    computed:{
        ...mapGetters({
            IsAdmin : 'IsAdmin'
        }),
    }
}
</script>



<style scoped>

</style>