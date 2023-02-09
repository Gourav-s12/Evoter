<template>
    <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="align-items-center " align="center" >
                        <img style="margin: 15px;border-radius:50%;" :src="nom.data.attributes.path"  alt="Profile Image" width="60" height="60" >
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ nom.data.attributes.name }}</h5>
                        <p class="card-text">{{ nom.data.attributes.description }}</p>
                        <!-- <p class="card-text">{{ nom.data.attributes.path }}</p> -->
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body" align="center">
                        <button class="btn btn-danger"  @click="deleteEvent()" :class="{ disabled: isDisable }">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            <p></p>
    </div>
        
</template>


<script>
export default {
    props: [
            'index',
            'nom',
            'isDisable'
        ],
    emits: ['delete'],
    
    computed:{
        isStarted(){
            // if(this.ele.data.attributes.start!=null && this.ele.data.attributes.end==null){
            //     return true;
            // }else{
            //     return false;
            // }
        }
    },
    methods: {
        deleteEvent(){
            this.$emit('delete',this.nom.data.nom_id);
        }
    }
}
</script>



<style scoped>

</style>