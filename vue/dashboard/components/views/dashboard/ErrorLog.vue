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
                <select @change="search" v-model="filters.consumer">
                  <option value="0">All consumers</option>
                  <option v-for="consumer in consumerOptions" :value="consumer.id">{{ consumer.name }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <transition name="fade">
      <div class="box" v-if="loaded">
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
            <td>{{ error.timecreated }}</td>
            <td>{{ error.consumer }}</td>
            <td>{{ error.code }}</td>
            <td>{{ error.message }}</td>
          </tr>
          <tr v-if="errors.length === 0">
            <td colspan="4">
              <Message id="no-errors-message">
                <span v-if="filters.consumer == 0">
                  No errors have been logged yet.
                </span>
                <span v-else>
                  No errors for the selected consumer. Try choosing 'All consumers' from the dropdown above.
                </span>
              </Message>
            </td>
          </tr>

          </tbody>
        </table>

        <!-- Pagination bar -->
        <Pagination
            :itemsTotal="pagination.itemsTotal"
            :itemsPerPage="pagination.itemsPerPage"
            :currentPage="pagination.currentPage"
            :buttonsMax="5"
            :url="'errors'"
        ></Pagination>
      </div>
    </transition>

    <!-- Download button -->
    <a class="button is-light">Download</a>

  </div>
</template>

<script>
import Pagination from "vue-bulma-paginate";
import {ajax} from "../../../store";
import Message from "../../partials/Message";

export default {
  name: "ErrorLog",
  components: {
    Message,
    Pagination,
  },
  data() {
    return {
      filters: {
        consumer: 0,
      },
      errors: [],
      pagination: {
        itemsPerPage: 10, // This should match the value in external/errors_api.php
        currentPage: 1,
        itemsTotal: 0
      },
      consumerOptions: [],
      loaded: false
    }
  },
  watch: {
    $route(to, from) {
      if (to.query.page !== undefined) {
        this.pagination.currentPage = Number(to.query.page);
        this.search();
      }
    }
  },
  mounted() {
    if (this.$route.query.page !== undefined) {
      this.pagination.currentPage = Number(this.$route.query.page);
    }

    ajax('local_lti_get_consumer_options', {}).then(consumers => {
      this.consumerOptions = consumers;
    });

    this.search();
  },
  methods: {
    search() {
      ajax('local_lti_get_errors', {
        page: this.pagination.currentPage - 1,
        consumer: this.filters.consumer
      }).then(response => {
        this.errors = response.errors;
        this.pagination.itemsTotal = response.page_count;
        this.loaded = true;
      });
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

  #no-errors-message {
    margin: 2rem auto 0 auto;
    width: 50rem;
  }
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
