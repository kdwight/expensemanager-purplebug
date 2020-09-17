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

            <div>Expenses</div>
          </div>

          <div class="page-title-actions">
            <b-button v-b-modal.new-expense-modal variant="primary">New Expense</b-button>
          </div>
        </div>
      </div>

      <!-- main -->
      <div class="row mb-5">
        <div class="col-lg-12">
          <expenses-table fetch-url="/expenses/records" :columns="['id', 'amount', 'entry_date']"></expenses-table>
        </div>
      </div>
    </div>

    <!-- New Expense Modal -->
    <b-modal
      id="new-expense-modal"
      ref="modal"
      title="Create New Expense"
      @show="resetModal"
      @hidden="resetModal"
      @ok.prevent="handleOk"
    >
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
            type="text"
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
import ExpensesTable from "../components/ExpensesTable";

export default {
  components: { ExpensesTable },

  data() {
    return {
      form: {
        category_id: null,
        amount: 0,
        entry_date: "",
      },
      errors: {},
    };
  },

  methods: {
    resetModal() {
      this.form = {
        category_id: null,
        amount: 0,
        entry_date: "",
      };

      this.errors = {};
    },

    handleOk() {
      axios
        .post("/expenses", this.form)
        .then(({ data }) => {
          this.$nextTick(() => {
            this.$bvModal.hide("new-expense-modal");
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
