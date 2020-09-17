<template>
  <div class="main-card card shadow">
    <preloader />

    <div class="card-header border-0">
      <div class="col-4">
        <p class="mb-0">List of Users</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th class="table-head">Name</th>
            <th class="table-head">Email</th>
            <th class="table-head">Role</th>
            <th class="table-head">Created At</th>
          </tr>
        </thead>

        <tbody v-if="tableData.length === 0">
          <tr>
            <td class="lead text-center" :colspan="columns.length + 1">No data found.</td>
          </tr>
        </tbody>

        <tbody v-for="user in tableData" :key="user.id" v-else>
          <tr style="cursor: pointer" @click="edit(user)">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>
              <div v-if="user.role">{{ user.role.name }}</div>
            </td>
            <td>{{ user.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="card-footer py-4 d-flex justify-content-center">
      <pagination />
    </div>

    <!-- Edit User Modal -->
    <b-modal id="edit-user-modal" ref="modal" @hidden="resetModal" @ok.prevent="handleOk">
      <template v-slot:modal-header>
        <h5>Edit User</h5>
        <button class="btn btn-danger" @click.prevent="destroy">Delete User</button>
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

        <div :class="`form-group ${errors.email ? 'has-danger' : ''}`">
          <label class="form-control-label" for="input-email">
            <span class="text-danger">*</span> Email
          </label>

          <input
            type="email"
            name="email"
            id="input-email"
            :class="`form-control form-control-alternative ${ errors.email ? ' is-invalid' : '' }`"
            placeholder="Email"
            v-model="form.email"
          />

          <span class="invalid-feedback" role="alert" v-if="errors.email">
            <strong>{{ errors.email[0] }}</strong>
          </span>
        </div>

        <div :class="`form-group ${errors.role_id ? 'has-danger' : ''}`">
          <label class="form-control-label" for="input-role">Role</label>

          <select
            v-b-tooltip.hover.right
            title="not required"
            name="role"
            id="input-role"
            :class="`form-control form-control-alternative ${ errors.role_id ? ' is-invalid' : '' }`"
            v-model="form.role_id"
          >
            <option :value="null">Select a role</option>

            <option
              v-for="role in $root.roles"
              :key="role.id"
              :value="role.id"
            >{{ role.description }}</option>
          </select>

          <span class="invalid-feedback" role="alert" v-if="errors.role_id">
            <strong>{{ errors.role_id[0] }}</strong>
          </span>
        </div>

        <div :class="`form-group ${errors.password ? 'has-danger' : ''}`">
          <label class="form-control-label" for="input-password">Password</label>

          <input
            type="password"
            name="password"
            id="input-password"
            :class="`form-control form-control-alternative ${ errors.password ? ' is-invalid' : '' }`"
            placeholder="Password"
            v-model="form.password"
          />

          <span class="invalid-feedback" role="alert" v-if="errors.password">
            <strong>{{ errors.password[0] }}</strong>
          </span>
        </div>

        <div :class="`form-group `">
          <label class="form-control-label" for="input-password-confirmation">Password Confirmation</label>

          <input
            type="password"
            name="password_confirmation"
            id="input-password-confirmation"
            :class="`form-control form-control-alternative`"
            placeholder="Password Confirmation"
            v-model="form.password_confirmation"
          />
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
        email: "",
        role_id: null,
        password: "",
        password_confirmation: "",
      },

      errors: {},
      editId: 0,
    };
  },

  methods: {
    resetModal() {
      this.form = {
        name: "",
        email: "",
        role_id: null,
        password: "",
        password_confirmation: "",
      };
    },

    edit(user) {
      if (user.role_id == 1) {
        this.$bvToast.toast("Unauthorized!", {
          title: "Warning",
          autoHideDelay: 3000,
          variant: "warning",
        });

        return;
      }

      this.editId = user.id;
      this.form.name = user.name;
      this.form.email = user.email;
      this.form.role_id = user.role_id;

      this.$root.$emit("bv::show::modal", "edit-user-modal");
    },

    handleOk() {
      axios
        .put(`/users/${this.editId}`, this.form)
        .then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("edit-user-modal");
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
      confirm("Are you sure you want to delete this user?") &&
        axios.delete(`/users/${this.editId}`).then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("edit-user-modal");
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