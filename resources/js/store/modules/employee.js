import EmployeesResource from '@/api/employees';
const employeesResource = new EmployeesResource();

const state = {
  id: null,
  employee: null,
  name: '',
  phone_number: '',
  image: '',
  images: [],
  position: {},
  position_id: null,
};

const mutations = {
  SET_ID: (state, id) => {
    state.id = id;
  },
  SET_NAME: (state, name) => {
    state.name = name;
  },
  SET_PHONE_NUMBER: (state, phone_number) => {
    state.phone_number = phone_number;
  },
  SET_POSITION: (state, position) => {
    state.position = position;
  },
  SET_IMAGE: (state, image) => {
    state.image = image;
  },
  SET_IMAGES: (state, images) => {
    state.images = images;
  },
  SET_POSITION_ID: (state, position_id) => {
    state.position_id = position_id;
  },
};

const actions = {
  get({commit}, id) {
    return new Promise((resolve, reject) => {
      employeesResource.get(id)
        .then(response => {
          const {name, position, id, image, images, phone_number} = response.data;
          commit('SET_NAME', name);
          commit('SET_PHONE_NUMBER', phone_number);
          commit('SET_POSITION', position);
          commit('SET_ID', id);
          commit('SET_IMAGE', image);
          commit('SET_IMAGES', images);
          commit('SET_POSITION_ID', position.id);
          resolve(response);
        })
        .catch(error => {
          reject(error);
        });
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};

