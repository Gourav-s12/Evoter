<template>
    <div>
        <div class="alert alert-danger mt-1 mb-1" v-if="RedirectText!=null" role="alert">
            <b>{{ RedirectText }}</b>
        </div>
        <div>
            <h2 style="text-align:center">
                <b><span>{{
                        IsAdmin ? 'Hello Admin' : 'Voter Profile'
                    }}</span></b>
            </h2>

            <div class="card pt-3 ">
                <div class="align-items-center">
                    <img :src="path"  alt="Profile Image" style="width:60%;max-height:330px;border-radius:50%;">
                    <br>
                    <div class="mb-2">
                        <input type="file" class="form-control-file" id="imageFile1" ref="file" @change="onChangeFileUpload()">              
                    </div>
                    <button type="submit" class="btn btn-primary" @click="editImage">Edit image</button>
                </div>

            <h1>{{ UserName }}</h1>
            <p class="title">{{ UserEmail }}</p>
            <p>
                Joined at {{ JoinTime ? JoinTime.substring(0, 10) : '' }}
            </p>
            <div style="margin: 24px 0;">
                <div class="card text-white bg-secondary mb-3" style="max-width: 90%;">
                    <div class="card-header">
                        Guide
                    </div>
                    <div class="card-body">
                       <h5 class="card-title">Page Info</h5>
                        <p class="card-text">
                            <span v-if="IsAdmin">Go to <span class="text-warning">Election</span> to create and manage election and nominee.
                            <br> Go to <span class="text-danger">User Account</span> to manage user account. 
                            <br> Go to <span class="text-info">Admin/User view</span> to see what a user can see.</span>
                            <span v-else>Go to <span class="text-warning">Election</span> to vote on a election. 
                            <br> Go to <span class="text-info">Result</span> to see the result of a election. </span>
                        </p>
                    </div>
                </div>
            </div>
                <p>
                    <button type="button" class="btn btn-dark" @click="deleteUser()">Delete profile</button>
                </p>
            </div>
        </div>
    </div>
</template>


<script>
import { mapGetters } from 'vuex'; 

export default {
  data(){
    return {
        message: 'hello',
        file: '',
        path: 'http://127.0.0.1:8000/default_profile_image.png',
        }
    },
    computed: {
        ...mapGetters({
            UserName: 'UserName',
            UserEmail: 'UserEmail',
            UserId: 'UserId',
            JoinTime: 'JoinTime',
            IsAdmin: 'IsAdmin',
            RedirectText: 'RedirectText',
        }),
    },
    mounted() {
        axios.get('/api/user-image')
        .then(res => {
            // console.log( res.data);
            this.path = res.data.data.title;
        })
        .catch(error => {
            console.log('Unable to get user image');
        });
    },
    methods: {
        deleteUser(){
            axios.get('/api/delete-this-user')
            .then(res => {
                // console.log( res.data);
                console.log( res.data);
                // this.$router.push('/');
                window.location.href = "/";
            })
            .catch(error => {
                console.log('Unable to delete this user');
            });
        },
        editImage(){
            if(this.file == ''){
                return ;
            }
            let formData = new FormData();
            formData.append('image', this.file);
            console.log(formData);
            // return ;
            axios.post('/api/user-image',
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                this.path = res.data.data.title;
            })
            .catch(error => {   
                this.$store.dispatch("redirectText", "Unable to edit image");
                this.$router.push("/home");
            });
        },
        onChangeFileUpload(){
            this.file = this.$refs.file.files[0];
        }
    }
}
</script>



<style scoped>
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 500px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}

button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 90%;
  font-size: 18px;
}

a {
  text-decoration: none;
  font-size: 22px;
  color: black;
}

button:hover, a:hover {
  opacity: 0.7;
}
</style>