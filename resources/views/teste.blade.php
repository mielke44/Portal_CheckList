            <v-autocomplete
              v-model="select"
              :items="task"
              label="Select"
              item-text="name"
              item-value="id"
              multiple
            >
              <template
                slot="selection"
                slot-scope="data"
              >
                <v-chip
                  :selected="data.selected"
                  close
                  class="chip--select-multi"
                  @input="remove(data.item)"
                >{{ data.item.name }}</v-chip>
              </template>
              <template
                slot="item"
                slot-scope="data"
              >
                <template>
                  <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>
                  </v-list-tile-content>
                </template>
            </v-autocomplete>

<script>
    export default {
      data () {
        return {
          autoUpdate: true,
          friends: ['Sandra Adams', 'Britta Holt'],
          isUpdating: false,
          name: 'Midnight Crew',
          people: [
          ],
          title: 'The summer breeze'
        }
      },
  
      watch: {
        isUpdating (val) {
          if (val) {
            setTimeout(() => (this.isUpdating = false), 3000)
          }
        }
      },
      
      methods: {
        remove (item) {
          const index = this.friends.indexOf(item.name)
          if (index >= 0) this.friends.splice(index, 1)
        }
      }
    }
  </script>