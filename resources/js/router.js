import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
import ElectionAdmin from './components/admin/Election'
import Home from './components/home'
import EleList from './components/admin/ElectionList'
import EleCreate from './components/admin/ElectionCreate'
import User from './components/admin/User.vue'
import UserList from './components/admin/UserList.vue'
import UserCreate from './components/admin/UserCreate.vue'
import VoteList from './components/user/VoteList.vue'
import ResultList from './components/user/ResultList.vue'
import EleNomList from './components/admin/ElectionNomineeList.vue'
import NomCreate from './components/admin/NomineeCreate.vue'
import VotePage from './components/user/VotePage.vue'
import ResultPage from './components/user/ResultPage.vue'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',

    routes: [
        {
            path: '/home', name: 'home', component: Home,
            meta: { title: 'Home page', AdminAuth: false }
        },
        {
            path: '/election', name: 'election', component: ElectionAdmin,
            meta: { AdminAuth: true },
            children: [

                {
                    path: 'create',
                    component: EleCreate,
                    name: 'create election',
                    meta: { AdminAuth: true }
                },
    
                {
                    path: '', 
                    component: EleList, 
                    name: 'election list',
                    meta: { AdminAuth: true }
                },
                {
                    path: 'nomineeList/:ele_id',
                    component: EleNomList,
                    name: 'election nominee',
                    meta: { AdminAuth: true }
                },
                {
                    path: 'nomineeCreate/:ele_id',
                    component: NomCreate,
                    name: 'election nominee create',
                    meta: { AdminAuth: true }
                }
            ]
        },
        {
            path: '/user', name: 'user', component: User,
            meta: { AdminAuth: true },
            children: [

                {
                    path: 'create',
                    component: UserCreate,
                    name: 'create user',
                    meta: { AdminAuth: true }
                },
    
                {
                    path: '', 
                    component: UserList, 
                    name: 'user list',
                    meta: { AdminAuth: true }
                },
            ]
        },
        {
            path: '/voteList', name: 'vote list', component: VoteList,
            meta: { AdminAuth: false },
        },
        {
            path: '/vote/:ele_id', name: 'vote page', component: VotePage,
            meta: { AdminAuth: false },
        },
        {
            path: '/resultList', name: 'result list', component: ResultList,
            meta: { AdminAuth: false },
        },
        ,
        {
            path: '/result/:ele_id', name: 'result page', component: ResultPage,
            meta: { AdminAuth: false },
        },
        {
            path: '*',
            beforeEnter(to, from, next) {
                store.dispatch('redirectText','Invaild URL  '+to.fullPath);
        
                next('/home');
              }
        
        }
      
    ]
});

router.beforeEach(async(to, _, next) => {
    // console.log(to.meta.AdminAuth+"  "+store.getters.IsAdmin);
    // console.log(store.getters.IsAdmin);
    // if (to.meta.AdminAuth && !store.getters.IsAdmin && to.fullPath!='/home') {
    //     store.dispatch('redirectText','Only an Admin can visit  '+to.fullPath);
    //     next('/home');
    // } else if (!to.meta.AdminAuth && store.getters.IsAdmin && to.fullPath!='/home') {
    //     store.dispatch('redirectText','Only a Voter can visit  '+to.fullPath);
    //     next('/home');
    // } else {
    //     next();
    // }
    console.log(to.meta.AdminAuth);
    if(to.meta.AdminAuth && store.getters.UserName == null){
        await store.dispatch('fetchAuthUser');
    }
    if (to.meta.AdminAuth && !store.getters.IsAdmin && to.fullPath!='/home') {
        store.dispatch('redirectText','Only an Admin can visit  '+to.fullPath);
        next('/home');
    }else{
        next();
    }
});

export default router;