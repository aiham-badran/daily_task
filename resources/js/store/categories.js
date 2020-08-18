const category = {
  namespaced: true,
  state: {
    categories: {},
    countTaskInCate: {},
  },
  getters: {
    getCategories: (state) => state.categories,
    getCategoryById: (state) => (id) => {
      if (state.categories) return state.categories[id];
    },
    getCountTaskInCate: (state) => state.countTaskInCate,
  },
  mutations: {
    setCategories: (state, categories) => (state.categories = categories),
    setCountTaskInCate: (state, count) => (state.countTaskInCate = count),
  },
  actions: {
    fetchCategory: async ({ commit }) => {
      await axios
        .get("category")
        .then((response) => {
          return response.data;
        })
        .then((data) => {
          let theData = {},
            theCount = {};
          if (data.data) theData = data.data;
          if (data.counter) theCount = data.counter;
          commit("setCategories", theData);
          commit("setCountTaskInCate", theCount);
        })
        .catch((error) => {
          error.response;
        });
    },
  },
};

export default category;
