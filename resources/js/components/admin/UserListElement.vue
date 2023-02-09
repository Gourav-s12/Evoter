<template>
    <tr>

        <th scope="row">{{ index + 1 }}</th>
        <td>{{ user.data.attributes.name }}</td>
        <td>{{ user.data.attributes.email }}</td>
        <td>
            <button class="btn btn-danger"  @click="deleteEvent()" :class="{ disabled: isDisable }">
            Delete
            </button>
        </td>
        
    </tr>
</template>


<script>
export default {
    props: [
            'index',
            'user',
            'isDisable'
        ],
    emits: ['delete'],
    mounted(){
        // console.log(this.ele);
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
    },
    methods: {
        deleteEvent(){
            // console.log("kol");
            this.$emit('delete',this.user.data.user_id);
        }
    }

}
</script>



<style scoped>

</style>