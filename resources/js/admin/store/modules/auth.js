const getDefaultState = () => {
    return {
        user: [],
    };
};
export default {
    namespaced: true,
    state: {
        user: false,
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
    },
    actions: {
        login({ commit }, { user }) {
            commit("SET_USER", user);
        },
        async authReload({ commit }) {
            let user = await axios
                .get(`/admin_vue/auth_user`)
                .then((res) => res.data);
            commit("SET_USER", user);
        },
    },
};
