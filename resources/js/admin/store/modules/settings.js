import axios from "axios";

export default {
    namespaced: true,
    state: {
        settings: null,
    },
    mutations: {
        SET_DATA(state, data) {
            state.settings = data;
        },
    },
    actions: {
        async getSettings({ commit }) {
            let data = await axios
                .get(`/admin_vue/setting`)
                .then((res) => res.data);
            
            commit("SET_DATA", data);
        },
    },
};
