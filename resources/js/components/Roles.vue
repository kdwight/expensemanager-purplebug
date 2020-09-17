<template>
  <div class="app-main__outer">
    <div class="app-main__inner">
      <!-- main-header -->
      <div class="app-page-title">
        <div class="page-title-wrapper">
          <div class="page-title-heading">
            <div class="page-title-icon">
              <i class="pe-7s-drawer icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>Roles</div>
          </div>

          <div class="page-title-actions">
            <b-button v-b-modal.new-role-modal variant="primary">New Role</b-button>
          </div>
        </div>
      </div>

      <!-- main -->
      <div class="row mb-5">
        <div class="col-lg-12">
          <roles-table fetch-url="/roles/records" :columns="['id', 'name', 'description']"></roles-table>
        </div>
      </div>
    </div>

    <!-- New Role Modal -->
    <b-modal
      id="new-role-modal"
      ref="modal"
      title="Create New Role"
      @show="resetModal"
      @hidden="resetModal"
      @ok.prevent="handleOk"
    >
      <form ref="form">
        <div :class="`form-group ${errors.name ? 'has-danger' : ''}`">
          <label class="form-control-label" for="input-name">
            <span class="text-danger">*</span> Name
          </label>

          <input
            type="text"
            name="name"
            id="input-name"
            :class="`form-control form-control-alternative ${ errors.name ? ' is-invalid' : '' }`"
            placeholder="Name"
            v-model="form.name"
          />

          <span class="invalid-feedback" role="alert" v-if="errors.name">
            <strong>{{ errors.name[0] }}</strong>
          </span>
        </div>

        <div :class="`form-group ${errors.description ? 'has-danger' : ''}`">
          <label class="form-control-label" for="input-description">
            <span class="text-danger">*</span> Description
          </label>

          <input
            type="text"
            name="description"
            id="input-description"
            :class="`form-control form-control-alternative ${ errors.description ? ' is-invalid' : '' }`"
            placeholder="Description"
            v-model="form.description"
          />

          <span class="invalid-feedback" role="alert" v-if="errors.description">
            <strong>{{ errors.description[0] }}</strong>
          </span>
        </div>
      </form>
    </b-modal>
  </div>
</template>

<script>
import RolesTable from "../components/RolesTable";

export default {
  components: { RolesTable },

  data() {
    return {
      form: {
        name: "",
        description: "",
      },

      errors: {},
    };
  },

  methods: {
    resetModal() {
      this.form = {
        name: "",
        description: "",
      };

      this.errors = {};
    },

    handleOk() {
      axios
        .post("/roles", this.form)
        .then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("new-role-modal");
          });
          fetchData();

          this.$bvToast.toast(`${data.success}`, {
            title: "Success",
            autoHideDelay: 3000,
            variant: "success",
          });
        })
        .catch(({ response }) => {
          this.errors = response.data.errors;
        });
    },
  },
};
</script>

<style>
</style>