<template>
  <div>
    <h4 class="title is-4">Manage consumers</h4>
    <p class="mb-5">Click the edit icon inside a cell to change the value. Click <b>More details</b> to view all
      consumer
      information.</p>

    <div class="field is-horizontal ml-5">
      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">Sort by</label>
        </div>
        <div class="field-body">
          <div class="field">
            <div class="control">
              <div class="select">
                <select v-model="filters.sort">
                  <option value="date_oldest">Date added (oldest first)</option>
                  <option value="date_newest">Date added (newest first)</option>
                  <option value="alphabetical">Name (A - Z)</option>
                  <option value="alphabetical_reverse">Name reverse (Z - A)</option>
                  <option value="last_access">Last access</option>
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
              <input v-model="filters.keywords" class="input" type="text" placeholder="Search for consumers...">
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
              <a class="button is-primary" @click="search">
                Search
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="box">
      <EditableTable
          :pagination="true"
          :pagination-url="'consumers'"
          :items-per-page="this.pagination.itemsPerPage"
          :current-page="this.pagination.currentPage"
          :headings="this.tableData.headings"
          :expanded-headings="this.tableData.expandedHeadings"
          :rows="this.tableData.rows"
      ></EditableTable>
    </div>

    <!-- Download button -->
    <a class="button is-light">Download</a>

  </div>
</template>

<script>
import EditableTable from "../../partials/tables/editable/EditableTable";

export default {
  name: "Consumers",
  components: {
    EditableTable,
  },
  data() {
    return {
      filters: {
        keywords: "",
        sort: "date_oldest"
      },
      tableData: {
        headings: [
          'Name',
          'Consumer key',
          'Secret',
          'Enabled',
          'Last access'
        ],
        expandedHeadings: [
          'License category',
          'Paid?',
          'Unique users',
          'Admin contact name',
          'Admin contact email',
          'Admin contact phone',
          'Billing email',
          'Developer email',
          'Staff email format',
          'Student email format',
          'LMS'
        ],
        rows: [
          [
            {
              value: "Southeast Kootenay",
              type: "text"
            },
            {
              value: "BC-SD05",
              type: "text"
            },
            {
              value: "canadian950",
              type: "text"
            },
            {
              value: true,
              type: "checkbox"
            },
            {
              value: "10:40AM November 30, 2020",
              type: "text",
              editable: false
            },
            {
              value: "License 1",
              type: "text"
            },
            {
              value: true,
              type: "checkbox"
            },
            {
              value: "Test",
              type: "text"
            },
            {
              value: "Jerry Garcia",
              type: "text"
            },
            {
              value: "jerry@gratefuldead.com",
              type: "text"
            },
            {
              value: "250-718-9233",
              type: "text"
            },
            {
              value: "billing@example.com",
              type: "text"
            },
            {
              value: "dev@example.com",
              type: "text"
            },
            {
              value: "sd05.bc.ca",
              type: "text"
            },
            {
              value: "student.sd5.ca",
              type: "text"
            },
            {
              value: "https://eschool.sd23.bc.ca",
              type: "text"
            },
          ]
        ]
      },
      expanded: false,
      pagination: {
        itemsPerPage: 1,
        currentPage: 1
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
  },
  methods: {
    search() {
      // TODO make web service request
      // Use filters.sort, filters.keywords and pagination.currentPage to query API and update rows
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
