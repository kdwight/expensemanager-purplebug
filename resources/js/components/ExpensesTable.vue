<template>
  <div class="main-card card shadow">
    <preloader />

    <div class="card-header border-0">
      <div class="col-4">
        <p class="mb-0">List of Expenses</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th class="table-head">Category</th>
            <th class="table-head" v-if="$auth.role_id == 1">Owner</th>
            <th class="table-head">Amount</th>
            <th class="table-head">Entry Date</th>
            <th class="table-head">Created At</th>
          </tr>
        </thead>

        <tbody v-if="tableData.length === 0">
          <tr>
            <td class="lead text-center" :colspan="columns.length + 1">No data found.</td>
          </tr>
        </tbody>

        <tbody v-for="expense in tableData" :key="expense.id" v-else>
          <tr style="cursor: pointer" @click="edit(expense)">
            <td>{{ expense.category.description }}</td>
            <td v-if="$auth.role_id == 1">
              <span class="text-info" v-if="$auth.id == expense.user.id">You</span>
              <span v-else>{{ expense.user.name }}</span>
            </td>
            <td>{{ expense.amount }}</td>
            <td>{{ expense.entry_date }}</td>
            <td>{{ expense.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="card-footer py-4 d-flex justify-content-center">
      <pagination />
    </div>

    <!-- Edit Expense Modal -->
    <b-modal id="edit-expense-modal" ref="modal" @hidden="resetModal" @ok.prevent="handleOk">
      <template v-slot:modal-header>
        <h5>Edit Expense</h5>
        <button class="btn btn-danger" @click.prevent="destroy">Delete Expense</button>
      </template>

      <form ref="form">
        <div :class="`form-group ${errors.category_id ? 'has-danger' : ''}`">
          <label class="form-control-label" for="input-category">
            <span class="text-danger">*</span> Expense Category
          </label>

          <select
            name="category"
            id="input-category"
            :class="`form-control form-control-alternative ${ errors.category_id ? ' is-invalid' : '' }`"
            v-model="form.category_id"
          >
            <option :value="null">Select an expense category</option>

            <option
              v-for="category in $root.categories"
              :key="category.id"
              :value="category.id"
            >{{ category.description }}</option>
          </select>

          <span class="invalid-feedback" role="alert" v-if="errors.category_id">
            <strong>{{ errors.category_id[0] }}</strong>
          </span>
        </div>

        <div :class="`form-group ${errors.amount ? 'has-danger' : ''}`">
          <label class="form-control-label" for="input-amount">
            <span class="text-danger">*</span> Amount
          </label>

          <input
            type="number"
            name="amount"
            id="input-amount"
            :class="`form-control form-control-alternative ${ errors.amount ? ' is-invalid' : '' }`"
            placeholder="Amount"
            v-model="form.amount"
          />

          <span class="invalid-feedback" role="alert" v-if="errors.amount">
            <strong>{{ errors.amount[0] }}</strong>
          </span>
        </div>

        <div>
          <label for="enter-date">Choose a date</label>
          <b-form-datepicker
            id="enter-date"
            class="mb-2"
            :state="errors.entry_date ? false : null"
            v-model="form.entry_date"
          ></b-form-datepicker>

          <span class="invalid-feedback" role="alert" v-if="errors.entry_date">
            <strong>{{ errors.entry_date[0] }}</strong>
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
        category_id: null,
        amount: 0,
        entry_date: "",
      },

      errors: {},
      editId: 0,
    };
  },

  methods: {
    resetModal() {
      this.form = {
        category_id: null,
        amount: 0,
        entry_date: "",
      };
    },

    edit(expense) {
      this.editId = expense.id;
      this.form.category_id = expense.category_id;
      this.form.amount = expense.amount;
      this.form.entry_date = expense.entry_date;

      this.$root.$emit("bv::show::modal", "edit-expense-modal");
    },

    handleOk() {
      axios
        .put(`/expenses/${this.editId}`, this.form)
        .then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("edit-expense-modal");
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
      confirm("Are you sure you want to delete this expense?") &&
        axios.delete(`/expenses/${this.editId}`).then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("edit-expense-modal");
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