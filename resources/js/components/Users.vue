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

            <div>Users</div>
          </div>

          <div class="page-title-actions">
            <b-button v-b-modal.new-user-modal variant="primary">New User</b-button>
          </div>
        </div>
      </div>

      <!-- main -->
      <div class="row mb-5">
        <div class="col-lg-12">
          <users-table fetch-url="/users/records" :columns="['id', 'name', 'email', 'role']"></users-table>
        </div>
      </div>
    </div>

    <!-- New User Modal -->
    <b-modal
      id="new-user-modal"
      ref="modal"
      title="Create New User"
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
            name="role"
            id="input-role"
            :class="`form-control form-control-alternative ${ errors.role_id ? ' is-invalid' : '' }`"
            v-model="form.role_id"
            v-b-tooltip.hover.right
            title="not required"
          >
            <option :value="null">Select a role</option>

            <option
              v-for="role in $parent.roles"
              :key="role.id"
              :value="role.id"
            >{{ role.description }}</option>
          </select>

          <span class="invalid-feedback" role="alert" v-if="errors.role_id">
            <strong>{{ errors.role_id[0] }}</strong>
          </span>
        </div>

        <div :class="`form-group ${errors.password ? 'has-danger' : ''}`">
          <label class="form-control-label" for="input-password">
            <span class="text-danger">*</span> Password
          </label>

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
      </form>
    </b-modal>
  </div>
</template>

<script>
import UsersTable from "../components/UsersTable";

export default {
  components: { UsersTable },

  data() {
    return {
      form: {
        name: "",
        email: "",
        role_id: null,
        password: "",
      },
      errors: {},
    };
  },

  methods: {
    resetModal() {
      this.form = {
        name: "",
        email: "",
        role_id: null,
        password: "",
      };

      this.errors = {};
    },

    handleOk() {
      axios
        .post("/users", this.form)
        .then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("new-user-modal");
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
