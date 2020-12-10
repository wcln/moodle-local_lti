<template>
  <div>
    <h4 class="title is-4">Manage consumers</h4>
    <p class="mb-5">Click the edit icon inside a cell to change the value. Click <b>More details</b> to view all consumer
      information.</p>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Sort by</label>
        </div>
        <div class="field-body">
          <div class="field">
            <div class="control">
              <div class="select">
                <select>
                  <option>Date added (oldest first)</option>
                  <option>Date added (newest first)</option>
                  <option>Name (A - Z)</option>
                  <option>Name reverse (Z - A)</option>
                  <option>Last access</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Keywords</label>
        </div>
        <div class="field-body">
          <div class="field is-grouped">
            <div class="control has-icons-left">
              <input class="input" type="text" placeholder="Search for consumers...">
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


    <div class="box">
      <table class="table">
        <thead>
        <tr>
          <th>Name</th>
          <th>Consumer key</th>
          <th>Secret</th>
          <th>Enabled</th>
          <th>Last access</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="consumer in consumers">
          <td>{{consumer.name}}</td>
          <td>{{consumer.consumerKey}}</td>
          <td>{{consumer.secret}}</td>
          <td>
            <input type="checkbox" :checked="consumer.enabled">
          </td>
          <td>{{consumer.lastAccess}}</td>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination bar -->
    <Pagination
      :itemsTotal="consumers.length"
      :itemsPerPage="pagination.itemsPerPage"
      :currentPage="pagination.currentPage"
      :buttonsMax="5"
    ></Pagination>

    <!-- Download button -->
    <a class="button is-light">Download</a>

  </div>
</template>

<script>
import Pagination from "vue-bulma-paginate";

export default {
  name: "Consumers",
  components: {
    Pagination
  },
  data() {
    return {
      consumers: [
        {
          name: "Southeast Kootenay",
          consumerKey: "BC-SD05",
          secret: "canadian950",
          enabled: true,
          lastAccess: "10:40AM November 30, 2020"
        },
        {
          name: "Southeast Kootenay 2",
          consumerKey: "BC-SD06",
          secret: "hamburger",
          enabled: true,
          lastAccess: "11:12 October 22, 2020"
        },
        {
          name: "Southeast Kootenay 3",
          consumerKey: "BC-SD07",
          secret: "poutine",
          enabled: true,
          lastAccess: "9:35AM December 1, 2020"
        },
      ],
      pagination: {
        itemsPerPage: 1,
        currentPage:  1
      }
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
  }
</style>
