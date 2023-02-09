<template>
    <div>
        <div class="form-group">
            <p class="h4">Create Election</p>
        </div>
        <div class="form-group mb-3">
            <label for="exampleInputName1">Name</label>
            <input class="form-control" :class="{ 'is-invalid': error!=null }" type="text" placeholder="Default Election" v-model="name">
            <div class="invalid-feedback">
                {{ error}}
            </div>
        </div>
        <button type="submit" class="btn btn-primary" @click="addEletion" :class="{ disabled: isDisable }">Submit</button>
    </div>
</template>


<script>
export default {
  data(){
    return {
        name: '',
        error: null,
        }
    },
    computed: {
        isDisable(){
            return (this.name.length ==''); 
        }
    },
    methods:{
        addEletion(){
            console.log(this.name.length);
            axios.post('/api/create-election', 
            {
                data : {
                    type : 'Election',
                    attributes : {
                        name : this.name,
                    }
                }
             })
            .then(res => {
                //console.log(res.data);
                if(res.data.data.message == "unsuccessful"){
                    this.error = res.data.data.error.name[0];
                    console.log(res.data.data.error.name[0]);
                }else{
                    this.$emit('setmessage','Election created');
                    this.$router.push('/election'); //{ name: 'election' });
                }
            })
            .catch(error => {
                console.log(error);
                console.log('Unable to create election');
            });
        }
    }
}
</script>



<style scoped>

</style>