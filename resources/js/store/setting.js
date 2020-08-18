const setting = {
  namespaced: true,
  state: {
    setting: {},
  },
  getters: {
    getSetting: (state) => state.setting,
    getSet: (state) => "the data",
  },
  mutations: {
    setSetting: (state, setting) => (state.setting = setting),
  },
  actions: {
    fetchSetting: async ({ commit }) => {
      await axios
        .get("settings")
        .then((response) => {
          return response.data;
        })
        .then((data) => {
          let theData = {};
          if (data.data) theData = data.data;
          commit("setSetting", theData);
        })
        .catch((error) => {
          error.response;
        });
    },
  },
};

export default setting;
