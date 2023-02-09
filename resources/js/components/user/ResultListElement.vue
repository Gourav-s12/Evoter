<template>
    <tr>

        <th scope="row">{{ index + 1 }}</th>
        <td>{{ ele.data.attributes.name }}</td>
        <td>
            <router-link class="btn btn-success"  :to="{ name: 'result page', params: { ele_id: ele.data.ele_id } }">
            Result
            </router-link>
        </td>
        
    </tr>
</template>


<script>
export default {
  props: [
            'index',
            'ele'
        ],
    mounted(){
        console.log(this.ele);
    },
    computed:{
        // startEnd(){
        //     if(this.ele.data.attributes.start!=null && this.ele.data.attributes.end==null){
        //         return 'Ongoing';
        //     }else if( this.ele.data.attributes.start==null && this.ele.data.attributes.end==null ){
        //         return 'Not started';
        //     }else{
        //         return 'Ended';
        //     }
        // }
    }
}
</script>



<style scoped>

</style>