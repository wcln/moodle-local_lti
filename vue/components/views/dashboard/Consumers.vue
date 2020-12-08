<template>
  <div>
    <h4 class="title is-4">Manage consumers</h4>
    <p class="mb-5">Click the edit icon inside a cell to change the value. Click <b>More details</b> to view all consumer
      information.</p>

    <div class="field is-grouped">
      <div class="field is-horizontal mr-5">
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
            <div class="control">
              <a class="button is-info">
                Search
              </a>
            </div>
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
        <tr>
          <td>Southeast Kootenay</td>
          <td>BC-SD05</td>
          <td>canadian950</td>
          <td>
            <input type="checkbox" checked>
          </td>
          <td>10:40AM November 30, 2020</td>
        </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script>
export default {
  name: "Consumers"
}
</script>

<style scoped>

</style>
