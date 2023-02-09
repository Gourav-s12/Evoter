<template>
    <tr>

        <th scope="row">{{ index + 1 }}</th>
        <td>{{ ele.data.attributes.name }}</td>
        <td>
            <button class="btn btn-secondary" @click="stopEvent()"  :class="{ disabled: isDisable }" v-if="isStarted">
                Stop
            </button>
            <button class="btn btn-primary" @click="startEvent()" :class="{ disabled: isDisable }" v-else>
                Start
            </button>

        </td>
        <td>
            <router-link class="btn btn-warning"  :to="{ name: 'election nominee', params: { ele_id: ele.data.ele_id } }" :class="{ disabled: isDisable }">
            Edit
            </router-link>
        </td>
        <td>
            <button class="btn btn-danger"  @click="deleteEvent()" :class="{ disabled: isDisable }">
            Delete
            </button>
        </td>
        <td>
            <router-link class="btn btn-success"  :to="{ name: 'result page', params: { ele_id: ele.data.ele_id } }" :class="{ disabled: isDisable }">
            Result
            </router-link>
        </td>

    </tr>
</template>


<script>
export default {
    props: [
            'index',
            'ele',
            'isDisable'
        ],
    emits: ['delete','start','stop'],
    mounted(){
        // console.log(this.ele);
    },
    computed:{
        isStarted(){
            // if(this.ele.data.attributes.start!=null && this.ele.data.attributes.end==null){
            //     return 'Ongoing';
            // }else if( this.ele.data.attributes.start==null && this.ele.data.attributes.end==null ){
            //     return 'Not started';
            // }else{
            //     return 'Ended';
            // }
            if(this.ele.data.attributes.start!=null && this.ele.data.attributes.end==null){
                return true;
            }else{
                return false;
            }
        }
    },
    methods: {
        deleteEvent(){
            this.$emit('delete',this.ele.data.ele_id);
        },
        startEvent(){
            this.$emit('start',this.ele.data.ele_id,this.index);
        },
        stopEvent(){
            this.$emit('stop',this.ele.data.ele_id,this.index);
        }
    }
}
</script>



<style scoped>

</style>