<template>
  <div>
    <router-link
      class="btn btn-primary"
      :to="{ name: 'election list' }"
      v-if="IsAdmin"
    >
      Election List
    </router-link>
    <router-link class="btn btn-secondary" :to="{ name: 'result list' }">
      Result List
    </router-link>
    <p></p>
    <h1 class="display-2" align="center">Election Result</h1>
    <h3 class="text-secondary" align="center">({{ ele_name }})</h3>
    <br />
    <h3 class="text-muted mt-1 mb-3" align="center">
      <b> {{ winner }} </b>
      <span class="text-info" v-if="(winner != 'Draw' && winner!='No vote yet')"
        >- Winner</span
      >
    </h3>
    <br /><br />
    <div id="chart" align="center" v-if="isShow">
      <apexchart
        type="donut"
        :options="chartOptions"
        :series="series"
        width="500"
      ></apexchart>
    </div>
    <br />
    <h3 class="text-secondary" align="center">
      Total vote count - {{ total_vote }}
    </h3>
    <br />
    <div align="center" class="align-items-center">
      <div class="w-50 p-3" v-if="total_vote != 0">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Vote count</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in series" :key="item">
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ chartOptions.labels[index] }}</td>
              <td>
                {{ item }}
                {{ max == item ? "  (W)" : "" }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>


<script>
import { mapGetters } from "vuex";
import VueApexCharts from "vue-apexcharts";

export default {
  components: {
    apexchart: VueApexCharts,
  },
  computed: {
    ...mapGetters({
      IsAdmin: "IsAdmin",
    }),
  },
  data() {
    return {
      isShow: false,
      ele_name: "Election",
      max: 0,
      total_vote: 0,
      winner: "No vote yet",
      series: [44, 55, 41, 17, 15],
      chartOptions: {
        labels: ["Apple", "Mango", "Orange", "Watermelon", "Banana"],
        chart: {
          type: "donut",
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      },
    };
  },
  mounted() {
    axios
      .get("/api/result/" + this.$route.params.ele_id)
      .then((res) => {
        // this.eledata =res.data.data;
        console.log(res.data);
        this.process(res.data.data);
        // console.log(res.data.data[1].data.user_id);
        // console.log(res.data.data[1].data.attributes.name);
        // console.log(this.userdata);
      })
      .catch((error) => {
        this.$store.dispatch("redirectText", "Invaild Election id");
        console.log("Unable to fetch election result data");
        this.$router.push("/home");
      });
  },
  methods: {
    process(data) {
      this.ele_name = data.attributes.name;
      this.total_vote = data.attributes.total_voters_count;
      if (this.total_vote == 0) {
        return;
      }
      var nomdata = data.attributes.nominees.data;
      this.max = 0;
      this.series = [];

      console.log(this.chartOptions.labels);
      this.chartOptions.labels = [];

      for (let i = 0; i < nomdata.length; i++) {
        this.series.push(nomdata[i].data.voters_count);
        this.chartOptions.labels.push(nomdata[i].data.attributes.name);
        if (this.max < nomdata[i].data.voters_count) {
          this.max = nomdata[i].data.voters_count;
          this.winner = nomdata[i].data.attributes.name;
        } else if (this.max == nomdata[i].data.voters_count) {
          this.winner = "Draw";
        }
      }
      console.log(this.chartOptions.labels);
      this.isShow = true;
    },
  },
};
</script>



<style scoped>
</style>