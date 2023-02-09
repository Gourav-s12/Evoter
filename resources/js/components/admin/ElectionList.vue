<template>
    <div>

        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Start/End</th>
                <th scope="col">edit</th>
                <th scope="col">Delete</th>
                <th scope="col">Result</th>
                </tr>
            </thead>
            <tbody>
                
                <election-element v-for="(item,index) in eledata" :key="item.data.ele_id" :ele="item" :index="index" :isDisable="isDisable" @delete="deleteEvent" @start="startEvent" @stop="stopEvent"></election-element>
            </tbody>
        </table>
        <!-- <div v-for="fruit in fruits" :key="fruit">{{fruit}}</div>
        <div v-for="ele in eledata" :key="ele.data.ele_id">{{ele.data.attributes.name}}</div> -->
    </div>
</template>


<script>
import ElectionElement from './ElectionListElement.vue';

export default {
    components:{
        ElectionElement,
    },
    data(){
        return {
            eledata: [],
            isDisable: false,
        }
    },
    mounted(){
        axios.get('/api/election-all')
            .then(res => {
                this.eledata =res.data.data;
                console.log( res.data);
                // console.log(res.data.data[1].data.ele_id);
                // console.log(res.data.data[1].data.attributes.name);
                // console.log(this.eledata);
            })
            .catch(error => {
                console.log('Unable to fetch election data');
            });
    },
    methods: {
        deleteEvent(id){
            this.isDisable=true;
            console.log(id);
            var url='/api/delete-election/'+String(id);
            axios.get(url)
                .then(res=> { 
                    console.log( res.data);
                    this.eledata = this.eledata.filter((ele)=>{
                        return (ele.data.ele_id != id);
                    });
                    this.$emit('setmessage',res.data.data.title);
                    this.isDisable=false;
                })
                .catch(error => {
                    console.log('Unable to delete election');
                    this.isDisable=false;
                });
        },
        startEvent(id,index){
            this.isDisable=true;
            console.log(id);
            var url='/api/start-election/'+String(id);
            axios.get(url)
                .then(res=> { 
                    console.log( res.data);
                    this.eledata[index] = res.data;
                    this.$emit('setmessage','Election Started');
                    this.isDisable=false;
                })
                .catch(error => {
                    console.log('Unable to start election');
                    this.isDisable=false;
                });
        },
        stopEvent(id,index){
            this.isDisable=true;
            console.log(id);
            var url='/api/stop-election/'+String(id);
            axios.get(url)
                .then(res=> { 
                    console.log( res.data);
                    this.eledata[index] = res.data;
                    this.$emit('setmessage','Election Stoped');
                    this.isDisable=false;
                })
                .catch(error => {
                    console.log('Unable to stop election');
                    this.isDisable=false;
                });
        }
    }
}
</script>



<style scoped>

</style>