import { createStore } from "vuex";
import auth from "./modules/auth";
import settings from "./modules/settings";
import createPersistedState from "vuex-persistedstate";

const store = createStore({
    state: {
        authState: auth.state,
        settingData: settings.state,
    },
    getters: {
        getAuthUser(state) {
            return state.auth.user;
        },
        getSettings(state) {
            return state.settings.settings;
        },
    },
    mutations: {},
    actions: {},
    modules: {
        auth,
        settings,
    },
    plugins: [ createPersistedState()
        // createPersistedState({
        //     key: "authUser",
        //     paths: ["authState"],
        //     getItem: (key) => localStorage.get(key),
        //     setItem: (key, value) => localStorage.set(key, value),
        //     removeItem: (key) => localStorage.removeItem(key),
        // }),
        // createPersistedState({
        //     key: "settingData",
        //     paths: ["settingData"],
        //     getItem: (key) => localStorage.get(key),
        //     setItem: (key, value) => localStorage.set(key, value),
        //     removeItem: (key) => localStorage.removeItem(key),
        // }),
    ],
});

export default store;
