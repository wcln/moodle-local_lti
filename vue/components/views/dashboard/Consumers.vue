<template>
  <div>
    <h4 class="title is-4">Manage consumers</h4>
    <p class="mb-5">Click on any cell below to edit consumer details. Click <b>Expand</b> to view all
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
                <select v-model="filters.sort" @change="search">
                  <option v-for="option in sortOptions" :value="option.value">{{ option.name }}</option>
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
              <input @keyup="search" v-model="filters.keywords" class="input" type="text" placeholder="Search for consumers...">
              <span class="icon is-left">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </span>
            </div>
          </div>
        </div>
      </div>
    </div>


    <transition name="fade">
      <div class="box" v-if="loaded">
        <EditableTable
            :key="tableKey"
            :pagination="true"
            :pagination-url="'consumers'"
            :items-per-page="pagination.itemsPerPage"
            :items-total="pagination.itemsTotal"
            :current-page="pagination.currentPage"
            :headings="tableData.headings"
            :expanded-headings="tableData.expandedHeadings"
            :rows="tableData.rows"
            :show-saved="showSaved"
            :deleting="deleting"
            add-text="Add consumer"
            @cellUpdated="updateConsumer"
            @addRow="addConsumer"
            @deleteRow="deleteConsumer"
            @undoDelete="undoDelete"
        ></EditableTable>
      </div>
    </transition>

    <!-- Download button -->
    <a class="button is-light">Download</a>

  </div>
</template>

<script>
import EditableTable from "../../partials/tables/editable/EditableTable";
import {ajax} from "../../../store";

export default {
  name: "Consumers",
  components: {
    EditableTable,
  },
  data() {
    return {
      filters: {
        keywords: "",
        sort: "date_desc"
      },
      sortOptions: [],
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
          []
        ]
      },
      expanded: false,
      pagination: {
        itemsPerPage: 10,
        itemsTotal: 1,
        currentPage: 1
      },
      showSaved: false,
      loaded: false,
      deleting: false,
      tableKey: 0 // This is used to ensure the table is reloaded every time new data is fetched
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

    ajax('local_lti_get_sort_options', {}).then(options => {
      this.sortOptions = options;
    });

    this.search();
  },
  methods: {
    search() {
      ajax('local_lti_get_consumers', {
        keywords: this.filters.keywords,
        sort: this.filters.sort,
        page: this.pagination.currentPage - 1
      }).then(response => {
        this.tableData.rows = response.consumers.map(consumer => {
          return {
            ...consumer.fields, consumerId: consumer.id
          };
        });
        this.pagination.itemsTotal = response.page_count;
        this.reloadTable();
        this.loaded = true;
      });
    },
    updateConsumer(args) {
      ajax('local_lti_update_consumer', {id: args.id, key: args.key, value: args.value}).then(response => {
        this.displaySavedMessage();
      });
    },
    addConsumer() {
      ajax('local_lti_create_consumer', {}).then(response => {
        this.filters.sort = 'date_desc'; // Sort by newly added so the new consumer is at the top
        this.search();
        // TODO focus on new consumer
      });
    },
    deleteConsumer(row) {
      this.deleting = true;
      setTimeout(() => {
        // If delete hasn't been cancelled
        if (this.deleting) {
          ajax('local_lti_delete_consumer', {id: row.consumerId}).then(response => {
            this.deleting = false;
            this.reloadTable();
            this.search();
            this.displaySavedMessage();
          });
        }
      }, 2500);
    },
    undoDelete() {
      this.deleting = false;
    },
    reloadTable() {
      this.tableKey += 1;
    },
    displaySavedMessage() {
      this.showSaved = true;
      setTimeout(() => {
        this.showSaved = false;
      }, 1000);
    }
  },
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

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
