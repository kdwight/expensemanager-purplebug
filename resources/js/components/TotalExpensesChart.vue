<template>
  <div class="col">
    <div class="main-card mb-3 card">
      <div class="card-body">
        <h5 class="card-title">Overall Expenses Chart</h5>

        <canvas id="categoryChart"></canvas>
      </div>
    </div>
  </div>
</template>

<script>
import Chart from "chart.js";

export default {
  props: ["categories"],

  data() {
    return {
      //
    };
  },

  mounted() {
    const random_hex_color_code = () => {
      let n = (Math.random() * 0xfffff * 1000000).toString(16);
      return "#" + n.slice(0, 6);
    };

    var ctx = document.getElementById("categoryChart").getContext("2d");

    var categoryChart = new Chart(ctx, {
      type: "pie",
      data: {
        labels: this.categories.map((cat) => cat.description),
        datasets: [
          {
            label: "Total Amount per Expense Category",
            data: this.categories.map((cat) => Math.round(cat.amount_total)),
            backgroundColor: this.categories.map((cat) =>
              random_hex_color_code()
            ),
          },
        ],
      },
    });
  },
};
</script>