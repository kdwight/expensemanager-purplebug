<template>
  <div class="main-card card shadow">
    <preloader />

    <div class="card-header border-0">
      <div class="col-4">
        <p class="mb-0">List of Categories</p>
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

        <tbody v-for="category in tableData" :key="category.id" v-else>
          <tr style="cursor: pointer" @click="edit(category)">
            <td>{{ category.name }}</td>
            <td>{{ category.description }}</td>
            <td>{{ category.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="card-footer py-4 d-flex justify-content-center">
      <pagination />
    </div>

    <!-- Edit Category Modal -->
    <b-modal id="edit-category-modal" ref="modal" @hidden="resetModal" @ok.prevent="handleOk">
      <template v-slot:modal-header>
        <h5>Edit Category</h5>
        <button class="btn btn-danger" @click.prevent="destroy">Delete Category</button>
      </template>

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
    },

    edit(category) {
      this.editId = category.id;
      this.form.name = category.name;
      this.form.description = category.description;

      this.$root.$emit("bv::show::modal", "edit-category-modal");
    },

    handleOk() {
      axios
        .put(`/categories/${this.editId}`, this.form)
        .then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("edit-category-modal");
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

    destroy() {
      confirm("Are you sure you want to delete this category?") &&
        axios.delete(`/categories/${this.editId}`).then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("edit-category-modal");
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