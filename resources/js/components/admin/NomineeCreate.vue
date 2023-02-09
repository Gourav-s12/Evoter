<template>
    <div>
        <div class="" align="center">
            <router-link class="btn btn-secondary"  :to="{ name: 'election nominee', params: { ele_id: this.$route.params.ele_id } }">
                List Nomineee
            </router-link>
        </div>
        <div class="form-group">
            <p class="h4">Create Nomineee</p>
        </div>
        <div class="form-group mb-2">
            <label for="exampleInputName1">Name</label>
            <input class="form-control" :class="{ 'is-invalid': error.name!=null }" type="text" placeholder="Default Nominee" v-model="name">
            <div class="invalid-feedback">
                {{ error.name }}
            </div>
        </div>
        <div class="form-group mb-2">
            <label for="exampleFormControlTextarea1">Description</label>
            <textarea class="form-control" :class="{ 'is-invalid': error.description!=null }" id="exampleFormControlTextarea1" rows="3" v-model="description"></textarea>
            <div class="invalid-feedback">
                {{ error.description }}
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="exampleFormControlFile1">Image</label>
            <input type="file" class="form-control-file" :class="{ 'is-invalid': error.iamge!=null }" id="imageFile1" ref="file" @change="onChangeFileUpload()">
            <small class="form-text text-muted">Image upload is optional</small>
            <div class="invalid-feedback">
                {{ error.iamge }}
            </div>
        </div>
        <button type="submit" class="btn btn-primary" @click="addNominee" :class="{ disabled: isDisable }">Submit</button>
    </div>
</template>


<script>
export default {
  data(){
    return {
        name: '',
        description: '',
        file: '',
        error: {
            name: null,
            description: null,
            image: null,
            },
        }
    },
    computed: {
        isDisable(){
            return (this.name == '' || this.description == ''); 
        }
    },
    methods:{
        addNominee(){
            console.log(this.name);
            console.log(this.file);

            // return ;
            axios.post('/api/election/'+this.$route.params.ele_id+'/create-nominee', 
            {
                data : {
                    type : 'Nominee',
                    attributes : {
                        name : this.name,
                        description : this.description,
                    }
                }
            })
            .then(res => {
                console.log(res.data);
                if(res.data.data.message == "unsuccessful"){
                    this.error.name = res.data.data.error.name ? res.data.data.error.name[0] : null ;
                    this.error.description = res.data.data.error.description ? res.data.data.error.description[0] : null ;
                    // console.log(res.data.data.error.name[0]);
                }else{
                    if(this.file != ''){
                        this.uploadImage(res.data.data[res.data.data.length-1].data.nom_id);
                    }else{
                        this.$emit('setmessage','Nominee created');
                        this.$router.push({ name: 'election nominee', params: { ele_id: this.$route.params.ele_id } }); //{ name: 'election' });
                    }
               }
            })
            .catch(error => {   
                this.$emit('setmessage','Eletion id not found');
                this.$router.push({ name: 'election nominee', params: { ele_id: this.$route.params.ele_id } }); //{ name: 'election' });
                console.log('Unable to create election');
            });
        },
        uploadImage(id) {
            console.log(id);
            let formData = new FormData();
            formData.append('image', this.file);
            console.log(formData);
            // return ;
            axios.post('/api/upload-image/'+id,
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
              }
)
            .then(res => {
                console.log(res.data);
                if(res.data.data.message == "unsuccessful"){
                    this.error.image = res.data.data.error.image ? res.data.data.error.image[0] : null ;
                }else{
                    this.$emit('setmessage','Nominee created');
                    this.$router.push({ name: 'election nominee', params: { ele_id: this.$route.params.ele_id } }); //{ name: 'election' });
                }
            })
            .catch(error => {   
                this.$emit('setmessage','Nominee id not found');
                this.$router.push({ name: 'election nominee', params: { ele_id: this.$route.params.ele_id } }); //{ name: 'election' });
                console.log('Unable to create election');
            });
        },
        onChangeFileUpload(){
            this.file = this.$refs.file.files[0];
      }
    }
}
</script>



<style scoped>

</style>