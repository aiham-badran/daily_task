const alert = {
  namespaced: true,
  state: {
    alerts: {},
  },
  getters: {
    getAlerts: (state) => state.alerts,
  },
  mutations: {
    setAlerts: (state, payload) => {
      Vue.set(state.alerts, payload.name, payload.value);
    },
    clearAlerts: (state) => (state.alerts = {}),
  },
  actions: {
    InitializeAlert: ({ commit }, alerts) => {
      //
      let [header, messages, status] = alerts;
      let theAlert = {};

      commit("clearAlerts");
      if (Array.isArray(messages)) {
        var theMessages;
        for (let message in messages) {
          if (Array.isArray(messages[message])) {
            for (let index in messages[message])
              theMessages = messages[message][index];
          } else {
            theMessages = messages[message];
          }
          theAlert = {
            name: message,
            value: {
              alertHeader: header,
              alertMessage: theMessages,
              alertStatus: status,
            },
          };
          commit("setAlerts", theAlert);
        }
      } else {
        theAlert = {
          name: 0,
          value: {
            alertHeader: header,
            alertMessage: messages,
            alertStatus: status,
          },
        };
        commit("setAlerts", theAlert);
      }
    },
  },
};

export default alert;
