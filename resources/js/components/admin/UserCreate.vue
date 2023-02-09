<template>
    <div>
        <div class="form-group">
            <p class="h4">Create User</p>
        </div>
        <div class="form-group mb-2">
            <label for="exampleInputName1">Name</label>
            <input class="form-control" :class="{ 'is-invalid': error.name!=null }" type="text" placeholder="Default User" v-model="name">
            <div class="invalid-feedback">
                {{ error.name }}
            </div>
        </div>
        <div class="form-group mb-2">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" :class="{ 'is-invalid': error.email!=null }" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" v-model="email">
            <small id="emailHelp" class="form-text text-muted">We'll never share email with anyone else.</small>
            <div class="invalid-feedback">
                {{ error.email }}
            </div>
        </div>
        <div class="form-group mb-2">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" :class="{ 'is-invalid': error.password!=null }" id="exampleInputPassword1" placeholder="Password" v-model="password">
            <div class="invalid-feedback">
                {{ error.password }}
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="exampleInputConfirmPassword1">Confirm Password</label>
            <input type="password" class="form-control" id="exampleInputConfirmPassword1" placeholder="Confirm Password" v-model="c_password">
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" v-model="is_admin" :value="0" checked>
            <label class="form-check-label" for="exampleRadios1">
                Voter Profile
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" v-model="is_admin" :value="1" >
            <label class="form-check-label" for="exampleRadios2">
                Admin Profile
            </label>
        </div>
        <small id="redioHelp" class="form-text text-muted mb-3">After create you can control voter profile but you can not control admin profile.</small>
        <br>
        <button type="submit" class="btn btn-primary mt-2" @click="addUser">Submit</button>
    </div>
</template>


<script>
export default {
  data(){
    return {
        name: '',
        email: '',
        password: '',
        c_password: '',
        is_admin: 0,
        error: {
            name: null,
            email: null,
            password: null
            },
        }
    },
    computed: {
        isDisable(){
            return (
                this.name.length <= 2 || this.password != this.c_password 
                || this.password.length < 6 || this.email != ''
                ); 
        }
    },
    methods:{
        addUser(){
            // console.log(this.is_admin);
            axios.post('/api/create-account', 
            {
                name : this.name,
                email : this.email,
                password : this.password,
                password_confirmation : this.c_password,
                is_admin : this.is_admin ==1 ? true :false,
                    
             })
            .then(res => {
                //console.log(res.data);
                if(res.data.data.message == "unsuccessful"){
                    this.error.name = res.data.data.error.name ? res.data.data.error.name[0] : null ;
                    this.error.email = res.data.data.error.email ? res.data.data.error.email[0] : null ;
                    this.error.password = res.data.data.error.password ? res.data.data.error.password[0] : null ;
                    console.log(res.data.data.error);
                }else{
                    this.$emit('setmessage','User created');
                    this.$router.push('/user'); //{ name: 'election' });
                }
            })
            .catch(error => {
                console.log(error);
                console.log('Unable to create account');
            });
        }
    }
}
</script>



<style scoped>

</style>