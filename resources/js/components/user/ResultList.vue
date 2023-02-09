<template>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Result</th>
                </tr>
            </thead>
            <tbody>
                
                <result-element v-for="(item,index) in eledata" :key="item.data.ele_id" :ele="item" :index="index"></result-element>
            </tbody>
        </table>
        <!-- <div v-for="fruit in fruits" :key="fruit">{{fruit}}</div>
        <div v-for="ele in eledata" :key="ele.data.ele_id">{{ele.data.attributes.name}}</div> -->
    </div>
</template>


<script>
import ResultElement from './ResultListElement.vue';

export default {
    components:{
        ResultElement,
    },
    data(){
        return {
            eledata: [],
        }
    },
    mounted(){
        axios.get('/api/election-close')
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
}
</script>



<style scoped>

</style>