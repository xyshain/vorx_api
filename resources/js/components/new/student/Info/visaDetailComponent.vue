<template>
  <div class="portal-forms">
      <form @submit.prevent autocomplete="nope">
        <div>
          <div class="row">
              <div class="col-md-12 text-right mb-1">
              <button class="btn btn-success" :disabled="roles == 'Staff'? true : false " @click="saveChanges()">
                <i class="far fa-save"></i> Save Changes
              </button>
            </div>
          </div>
          <div class="row mb-2 no-gutters">
            <!-- personal information -->
            <div class="col-md-6">
                <div class="card m-1">
                    <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                        Residency & Visa Information
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nationality">Nationality</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="visa.nationality"
                                        id="nationality"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="passport_number">Passport No.</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="visa.passport_number"
                                    id="passport_number"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="issue_date">Issue Date <span style="font-size: 74%;opacity: 73%;">( MM/DD/YYYY )</span></label>
                                <date-picker
                                    locale="en"
                                    mode="date"
                                    v-model="visa.issue_date"
                                    :masks="{L:'DD/MM/YYYY'}"
                                    :input-props='{
                                    autocomplete:"off",
                                    }'
                                ></date-picker>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="expiry_date">Expiry Date <span style="font-size: 74%;opacity: 73%;">( MM/DD/YYYY )</span></label>
                                <date-picker
                                    locale="en"
                                    mode="date"
                                    v-model="visa.expiry_date"
                                    :masks="{L:'DD/MM/YYYY'}"
                                    :input-props='{
                                    autocomplete:"off",
                                    }'
                                ></date-picker>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- usi information -->
            <div class="col-md-6">
                <div class="card m-1">
                    <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                        If not Australian Citizen
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="singleName">Visa Type</label>
                                    <multiselect
                                    v-model="get_visa_type"
                                    :options="visa_types"
                                    @select="select_visa_type"
                                    :multiple="false"
                                    placeholder="Type to search"
                                    :close-on-select="true"
                                    track-by="id"
                                    label="value"
                                    >
                                    <span slot="noResult">Oops! No units found. Consider changing the search query.</span>
                                    </multiselect>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="expiry_date_visa_type">Expiry Date <span style="font-size: 74%;opacity: 73%;">( MM/DD/YYYY )</span></label>
                                <date-picker
                                    locale="en"
                                    mode="date"
                                    v-model="visa.expiry_date_visa_type"
                                    :masks="{L:'DD/MM/YYYY'}"
                                    :input-props='{
                                    autocomplete:"off",
                                    }'
                                ></date-picker>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="study_rights">Study Rights</label>
                                    <select id="study_rights" v-model="visa.study_rights" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="applied_for_au_residency">Applied for AU Permanent Residency</label>
                                    <select id="applied_for_au_residency" v-model="visa.applied_for_au_residency" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
    
        </div>
    </form>
  </div>
</template>
<script>
import moment from "moment";
export default {
  components: {
  },
  mounted() {
      
  },
  created() {

  },
  data() {
    return {
      app_color: app_color,
      add_on: add_on,
      roles : null,
      visa_types : window.visa_types,
      get_visa_type : {},
      
    };
  },
  computed : {
      visa() {
        let v = this.$store.state.visa;
        if(typeof v.visa_type !== 'object') {
            let vt = visa_types.filter(data => data.id == v.visa_type);
            v.visa_type = vt[0];
            this.get_visa_type = vt[0];
        }
        v.expiry_date = moment(v.expiry_date)._d;
        v.expiry_date_visa_type = moment(v.expiry_date_visa_type)._d;
        v.issue_date = moment(v.issue_date)._d;
        return v
      },
      student() {
          return this.$store.state.student;
      }
  },
  methods: {
    select_visa_type({id, value}) {
        this.visa.visa_type = {id:id, value:value};
        console.log(this.visa);
    },
    saveChanges() {
        swal.fire({
            title: "Saving Changes...",
            // html: '',// add html attribute if you want or remove
            allowOutsideClick: false,
            onBeforeOpen: () => {
            swal.showLoading();
            },
        });

        axios.post(`/student/domestic/${this.student.student_id}/visa_update`, {inputs:this.visa})
        .then(res => {
            if(res.data.status == 'updated') {
                swal.fire(
                    'Success!',
                    'Changes updates successfully',
                    'success',
                )
            }else {
                swal.fire(
                    'Opss...',
                    'There seems to be a problem',
                    'error',
                )
            }
        })
        .catch( err => {
            console.log(err)
            swal.fire(
                'Opss...',
                'There seems to be a problem',
                'error',
            )
        })
      },
    changeScoring() {
      let scorerange = [];
      if (this.englishTest == 1) {
        for (let index = 4; index <= 9; index++) {
          scorerange.push(index.toFixed(1));
          if (index != 9) {
            scorerange.push(index + 0.5);
          }
        }
        this.testScoreRange = scorerange;
      } else if (this.englishTest == 4) {
        let scorerange = [
          "10-28",
          "29-35",
          "36-41",
          "42-49",
          "50-57",
          "58-64",
          "65-72",
          "73-78",
          "79-82",
          "83-85",
          "86-90",
        ];
        this.testScoreRange = scorerange;
      } else {
        // this.testScoreRange = scorerange;
        // this.testScoreRange = scorerange;
        this.testScoreRange = scorerange;
      }
      this.testScore = "";
    },
  },
  watch: {
    // eng: function (newVal, oldVal) {
    //   console.log(newVal);
    //   this.changeScoring(newVal);
    //   this.testScore = window.englishData.score;
    // },
  },
};
</script>
<style scoped>
    
</style>
<style>
.tab-pane {
  border: 1px solid #e0dfdf;
  border-top: none;
  padding: 1.3rem;
  border-bottom-right-radius: 5px;
  border-bottom-left-radius: 5px;
  background-color: #ffffff;
}
.vc-text-base {
  display: block;
  width: 100%;
  height: calc(1.5em + 0.75rem + 2px);
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #6e707e;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #d1d3e2;
  border-radius: 0.35rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
</style>