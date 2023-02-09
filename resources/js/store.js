import Vue from 'vue';
import Vuex from 'vuex';

const state = {
    userName: null,
    userId: null,
    userEmail: null,
    isAdmin: false,
    userView: false,
    joinTime: null,
    redirectText: null,
};

const getters = {
    UserName: state => {
        return state.userName;
    },
    UserId: state => {
        return state.userId;
    },
    UserEmail: state => {
        return state.userEmail;
    },
    IsAdmin: state => {
        return state.isAdmin;
    },
    JoinTime: state => {
        return state.joinTime;
    },
    RedirectText: state => {
        return state.redirectText;
    },
    UserView: state => {
        return state.userView;
    }
};

const actions = {
    fetchAuthUser({commit, state}) {
        return axios.get('/api/user')
            .then(res => {
                // console.log( res.data);
                commit('setAuthUser', res.data);
                return state.isAdmin;
            })
            .catch(error => {
                console.log('Unable to fetch auth user');
                return state.isAdmin;
            });
    },
    redirectText({commit, state},payload) {
        commit('setRedirectText',payload);
        setTimeout(function() { 
            commit('setRedirectText', null); 
        }, 6000);
    },
    toogleView({commit, state}) {
        if(state.isAdmin == true){
            commit('setView');
        }else{
            console.log('Only an Admin change change view');
        }
    }
};

const mutations = {
    setAuthUser(state, user) {
        state.userName = user.name;
        state.userId = user.id;
        state.userEmail = user.email;
        state.isAdmin = user.is_admin;
        state.joinTime = user.created_at;
    },
    setRedirectText(state,text) {
        state.redirectText = text;
    },
    setView(state) {
        state.userView = !state.userView;
    }
};

Vue.use(Vuex);

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations
  })