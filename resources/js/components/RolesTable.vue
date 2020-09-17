<template>
  <div class="main-card card shadow">
    <preloader />

    <div class="card-header border-0">
      <div class="col-4">
        <p class="mb-0">List of Roles</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th class="table-head">Name</th>
            <th class="table-head">Description</th>
            <th class="table-head">Created At</th>
          </tr>
        </thead>

        <tbody v-if="tableData.length === 0">
          <tr>
            <td class="lead text-center" :colspan="columns.length + 1">No data found.</td>
          </tr>
        </tbody>

        <tbody v-for="role in tableData" :key="role.id" v-else>
          <tr style="cursor: pointer" @click="edit(role)">
            <td>{{ role.name }}</td>
            <td>{{ role.description }}</td>
            <td>{{ role.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="card-footer py-4 d-flex justify-content-center">
      <pagination />
    </div>

    <!-- Edit Role Modal -->
    <b-modal id="edit-role-modal" ref="modal" @hidden="resetModal" @ok.prevent="handleOk">
      <template v-slot:modal-header>
        <h5>Edit Role</h5>
        <button class="btn btn-danger" @click.prevent="destroy">Delete Role</button>
      </template>

      <form ref="form">
        <p class="text-danger pb-1" v-if="errors.unauthorized">
          <strong>Warning!</strong>
          {{ errors.unauthorized[0] }}
        </p>

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
import DataTable from "../mixins/DataTables";
import Preloader from "./Preloader";
import Pagination from "./Pagination";

export default {
  mixins: [DataTable],
  components: { Preloader, Pagination },

  data() {
    return {
      form: {
        name: "",
        description: "",
      },

      errors: {},
      editId: 0,
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

    edit(role) {
      if (role.id == 1) {
        this.$bvToast.toast("Unauthorized!", {
          title: "Warning",
          autoHideDelay: 3000,
          variant: "warning",
        });

        return;
      }

      this.editId = role.id;
      this.form.name = role.name;
      this.form.description = role.description;

      this.$root.$emit("bv::show::modal", "edit-role-modal");
    },

    handleOk() {
      axios
        .put(`/roles/${this.editId}`, this.form)
        .then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("edit-role-modal");
          });
          this.errors = {};

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

    destroy() {
      confirm("Are you sure you want to delete this role?") &&
        axios.delete(`/roles/${this.editId}`).then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("edit-role-modal");
          });

          fetchData();

          this.$bvToast.toast(`${data.success}`, {
            title: "Success",
            autoHideDelay: 3000,
            variant: "success",
          });
        });
    },
  },
};
</script>

<style>
</style>