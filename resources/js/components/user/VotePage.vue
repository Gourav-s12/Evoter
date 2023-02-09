<template>
    <div>
        <p></p>
        <h3 class="display-3"  align="center">
            Voting Page
        </h3>

        <p></p><br>
        <small class="form-text text-muted">Think careful before you vote, you can not vote again or edit your vote</small>
        <p></p>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">...</th>
                <th scope="col">name</th>
                <th scope="col">Description</th>
                <th scope="col">Vote</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        
        <vote-element v-for="(item,index) in nomdata" :key="item.data.user_id" :nom="item" :index="index" :isDisable="isDisable || IsAdmin" @vote="voteEvent"></vote-element>

        <!-- <div v-for="fruit in fruits" :key="fruit">{{fruit}}</div>
        <div v-for="ele in eledata" :key="ele.data.ele_id">{{ele.data.attributes.name}}</div> -->
    </div>
</template>


<script>
import { mapGetters } from 'vuex';
import VoteElement from './VotePageElement.vue';

export default {
    components:{
        VoteElement,
    },
    data(){
        return {
            nomdata: [],
            isDisable: false,
        }
    },
    mounted(){
        // if(IsAdmin == true){
        //     this.$store.dispatch('redirectText','Admin can not vote in Election');
        //     // this.$emit('setmessage','Admin can not vote in Election');
        //     this.$router.push('/home');
        // }
        // axios.get('/api/canVote/'+this.$route.params.ele_id)
        //     .then(res => {
        //         console.log( res.data);
        //     })
        //     .catch(error => {
        //         this.$store.dispatch('redirectText','You already voted in that Elecion or Election is closed');
        //         // this.$emit('setmessage','You already voted in that Elecion');
        //         console.log('Unable to fetch election vote data');
        //         this.$router.push('/home');
        //     });

        console.log(this.$route.params.ele_id);

        axios.get('/api/election/'+this.$route.params.ele_id+'/nominee')
            .then(res => {
                this.nomdata =res.data.data;
                console.log( res.data.data);
            })
            .catch(error => {
                this.$store.dispatch('redirectText','Invaild Election id');
                // this.$emit('setmessage','Invaild Election id');
                console.log('Unable to fetch election vote data');
                this.$router.push('/home');
            });
    },
    methods: {
        voteEvent(id){
            this.isDisable=true;
            console.log(id);
            // this.isDisable=false;
            axios.post('/api/vote', 
            {
                data : {
                    type : 'Election',
                    attributes : {
                        election_id : this.$route.params.ele_id,
                        nominee_id : id,
                    }
                }     
             })
                .then(res=> { 
                    console.log( res.data.data.title);
            
                    this.$store.dispatch('redirectText',res.data.data.title);
                    this.isDisable=false;
                    this.$router.push('/home');
                })
                .catch(error => {
                    this.$store.dispatch('redirectText','you can not vote in this Election');
                    console.log('Unable to vote');
                    this.isDisable=false;
                    this.$router.push('/home');
                });
        }
    },
    computed: {
        ...mapGetters({
            IsAdmin : 'IsAdmin'
        }),
    },
}
</script>



<style scoped>

</style>