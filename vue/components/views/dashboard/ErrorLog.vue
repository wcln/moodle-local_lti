<template>
  <div>
    <h4 class="title is-4">Error log</h4>

    <div class="field is-horizontal ml-5">
      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Consumer</label>
        </div>
        <div class="field-body">
          <div class="field">
            <div class="control">
              <div class="select">
                <select>
                  <option>West Kootenary</option>
                  <option>East Kootenay</option>
                  <option>Kelowna</option>
                  <option>Vernon</option>
                  <option selected>All consumers</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="field is-horizontal ml-5">
        <div class="field-label">
          <label class="label">Keywords</label>
        </div>
        <div class="field-body">
          <div class="field is-grouped">
            <div class="control has-icons-left">
              <input class="input" type="text" placeholder="Search the error log...">
              <span class="icon is-left">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </span>
            </div>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <!-- Left empty for spacing -->
        </div>
        <div class="field-body">
          <div class="field">
            <div class="control">
              <a class="button is-primary">
                Search
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div class="box">
      <table class="table is-hoverable">
        <thead>
        <tr>
          <th>Date</th>
          <th>Consumer</th>
          <th>Error code</th>
          <th>Message</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="error in errors">
          <td>{{error.date}}</td>
          <td>{{error.consumer}}</td>
          <td>{{error.code}}</td>
          <td>{{error.message}}</td>
        </tr>
        </tbody>
      </table>

      <!-- Pagination bar -->
      <Pagination
          :itemsTotal="errors.length"
          :itemsPerPage="pagination.itemsPerPage"
          :currentPage="pagination.currentPage"
          :buttonsMax="5"
          :url="'errors'"
      ></Pagination>
    </div>

    <!-- Download button -->
    <a class="button is-light">Download</a>

  </div>
</template>

<script>
import Pagination from "vue-bulma-paginate";

export default {
  name: "ErrorLog",
  components: {
    Pagination,
  },
  data() {
    return {
      errors: [
        {
          date: "10:40AM November 30, 2020",
          consumer: "Southeast Kootenay",
          code: "300",
          message: "An error occurred while rendering the LTI book. Ensure that the ID you provided is correct.",
        },
        {
          date: "8:32AM November 30, 2020",
          consumer: "Vernon",
          code: "400",
          message: "LTI type parameter is missing from the launch URL.",
        },
        {
          date: "7:22AM November 30, 2020",
          consumer: "Southeast Kootenay",
          code: "500",
          message: "The request could not be verified.",
        },
        {
          date: "7:21AM November 30, 2020",
          consumer: "Delta",
          code: "600",
          message: "The previous session could not be recovered.",
        },
      ],
      pagination: {
        itemsPerPage: 1,
        currentPage:  1
      }
    }
  },
  watch: {
    $route(to, from) {
      if (to.query.page !== undefined) {
        this.pagination.currentPage = Number(to.query.page);
      }
    }
  },
  mounted() {
    if (this.$route.query.page !== undefined) {
      this.pagination.currentPage = Number(this.$route.query.page);
    }
  }
}
</script>

<style scoped lang="scss">
#lti-dashboard-app {
  .pagination {
    .pagination-link {
      height: 2rem;
      padding: .2em .1em;
      min-width: 2em;
    }
  }

  .field .label {
    white-space: nowrap;
  }
}
</style>
