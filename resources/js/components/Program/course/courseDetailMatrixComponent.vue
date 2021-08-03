<template>
  <div>
    <button class="btn btn-success btn-sm d-table ml-auto" @click="addBlock">
      <i class="fas fa-plus"></i> Add
    </button>
    <div class="accordion mt-3" id="accordionMatrix">
      <div
        class="card card-default position-relative"
        v-for="(field, index) in fields"
        v-bind:id="'card-' + (index+1)"
        v-bind:key="index"
      >
        <div class="card-header position-relative" v-bind:id="'heading-' + (index+1)">
          <a
            class="collapse-link"
            data-toggle="collapse"
            v-bind:data-target="'#collapse-' + (index+1)"
            aria-expanded="true"
            v-bind:aria-controls="'collapse-' + (index+1)"
          >
            <h5 class="m-0">{{course_code}} - {{course_name}} {{ field.location }}</h5>
          </a>
          <div class="btn-group-vertical btn-options">
            <button @click.prevent="saveBlock(index);" class="opt-btn" title="Save">
              <i class="fas fa-save"></i>
            </button>
            <a
              href="javascript:void(0)"
              class="opt-btn"
              v-on:click="removeBlock(index);"
              title="Remove"
            >
              <i class="fas fa-trash"></i>
            </a>
          </div>
        </div>
        <div
          class="collapse show"
          v-bind:id="'collapse-' + (index+1)"
          v-bind:aria-labelledby="'heading-' + (index+1)"
          data-parent="#accordionMatrix"
        >
          <div class="card-body">
            <input type="hidden" v-model="field.course_code" />
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="cricos_code">CRICOS code:</label>
                  <input
                    type="text"
                    class="form-control"
                    name="cricos_code"
                    id="cricos_code"
                    v-model="field.cricos_code"
                    :class="[errors[index] && errors[index].cricos_code ? 'border-danger' : '']"
                  />
                  <div
                    v-if="errors[index] && errors[index].cricos_code"
                    class="badge badge-danger"
                  >{{ errors[index].cricos_code[0] }}</div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="wk_duration">Duration (Weeks):</label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="wl_duration"
                    id="wk_duration"
                    v-model="field.wk_duration"
                  />
                  <div
                    v-if="errors[index] && errors[index].wk_duration"
                    class="badge badge-danger"
                  >{{ errors[index].wk_duration[0] }}</div>
                </div>
              </div>
              <!-- <div class="col-lg-6">
                <div class="form-group">
                  <label for="wk_duration_package">
                    Duration (Weeks):
                    <small>When packaged w/ {{ org_name }}</small>
                  </label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="wk_duration_package"
                    id="wk_duration_package"
                    v-model="field.wk_duration_package"
                  />
                  <div
                    v-if="errors[index] && errors[index].wk_duration_package"
                    class="badge badge-danger"
                  >{{ errors[index].wk_duration_package[0] }}</div>
                </div>
              </div> -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="training_hours_weekly">
                    Training hours weekly:
                  </label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="training_hours_weekly"
                    id="training_hours_weekly"
                    v-model="field.training_hours_weekly"
                  />
                  <div
                    v-if="errors[index] && errors[index].training_hours_weekly"
                    class="badge badge-danger"
                  >{{ errors[index].training_hours_weekly[0] }}</div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="wk_duration_package">Location:</label>
                  <select
                    name="location"
                    id="location"
                    class="form-control"
                    v-model="field.location"
                    :class="[errors[index] && errors[index].location ? 'border-danger' : '']"
                  >
                    <!-- <option value="QLD">Queensland (QLD)</option>
                    <option value="VIC">Victoria (VIC)</option>-->
                    <option
                      v-for="(opt, optKy) in slct_option_state"
                      v-bind:key="optKy"
                      v-bind:value="optKy"
                    >{{opt}} ({{optKy}})</option>
                  </select>
                  <div
                    v-if="errors[index] && errors[index].location"
                    class="badge badge-danger"
                  >{{ errors[index].location[0] }}</div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="material_fees">Material Fees:</label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="material_fees"
                    id="material_fees"
                    v-model="field.material_fees"
                  />
                  <div
                    v-if="errors[index] && errors[index].material_fees"
                    class="badge badge-danger"
                  >{{ errors[index].material_fees[0] }}</div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="enrolment_fee">
                    Enrolment/Application Fees:
                    <!-- <small>applicable for both</small> -->
                  </label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="enrolment_fee"
                    id="enrolment_fee"
                    v-model="field.enrolment_fee"
                  />
                  <div
                    v-if="errors[index] && errors[index].enrolment_fee"
                    class="badge badge-danger"
                  >{{ errors[index].enrolment_fee[0] }}</div>
                </div>
              </div>
              <!-- <div class="col-lg-4">
                <div class="form-group">
                  <label for="material_fees">Concessional Fee:</label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="concessional_fee"
                    id="concessional_fee"
                    v-model="field.concessional_fee"
                    placeholder="N/A"
                  />
                  <div
                    v-if="errors[index] && errors[index].concessional_fee"
                    class="badge badge-danger"
                  >{{ errors[index].concessional_fee[0] }}</div>
                </div>
              </div> -->
              <!-- <div class="col-lg-4">
                <div class="form-group">
                  <label for="enrolment_fee">Non-concessional Fees:</label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="non_concessional_fee"
                    id="non_concessional_fee"
                    v-model="field.non_concessional_fee"
                    placeholder="N/A"
                  />
                  <div
                    v-if="errors[index] && errors[index].non_concessional_fee"
                    class="badge badge-danger"
                  >{{ errors[index].non_concessional_fee[0] }}</div>
                </div>
              </div> -->
              
              <!-- Onshore fee -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="oh_tuition_individual">Onshore Tuition fee:</label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="onshore_tuition_individual"
                    id="onshore_tuition_individual"
                    v-model="field.onshore_tuition_individual"
                  />
                  <div
                    v-if="errors[index] && errors[index].onshore_tuition_individual"
                    class="badge badge-danger"
                  >{{ errors[index].onshore_tuition_individual[0] }}</div>
                </div>
              </div>

              <!-- Offshore fee -->
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="os_tuition_individual">Offshore Tuition fees</label>
                  <input
                    type="number"
                    min="0"
                    class="form-control"
                    name="offshore_tuition_individual"
                    id="offshore_tuition_individual"
                    v-model="field.offshore_tuition_individual"
                  />
                  <div
                    v-if="errors[index] && errors[index].offshore_tuition_individual"
                    class="badge badge-danger"
                  >{{ errors[index].offshore_tuition_individual[0] }}</div>
                </div>
              </div>

            </div>
            <div v-bind:class="'horizontal-line-wrapper-'+app_color+' mb-2'">
              <h6>Package Offers</h6>
            </div>
            <div class="row px-3">
              <table :class="'table header-'+app_color" width="100%">
                    <thead>
                      <tr>
                        <th width="40%" :class="'table-header-'+app_color">Package</th>
                        <th :class="'table-header-'+app_color">Weeks</th>
                        <th :class="'table-header-'+app_color">Tuition<br>Fee</th>
                        <th :class="'table-header-'+app_color">Material<br>Fee</th>
                        <th :class="'table-header-'+app_color">Application<br>Fee</th>
                        <th :class="'text-center table-header-'+app_color">
                            Action
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(pckg, pckg_id) in field.package_detail" :key="pckg.course_matrix_id">
                        <td class="text-center">
                          {{pckg.package.name}} — {{pckg.package.shore_type}}
                        </td>
                        <td class="text-center">
                          
                          <input v-if="editedKey == pckg.id && editedCol == 'wk_duration'" type="number" class="form-control" v-model="pckg.wk_duration" v-on:keyup.enter="updateRow(pckg)">
                          <span v-else @dblclick="editCol('wk_duration', pckg.id, pckg)">
                            {{pckg.wk_duration ? pckg.wk_duration+' Weeks' : ' - '}}
                          </span>

                        </td>
                        <td class="text-center">
                          <input v-if="editedKey == pckg.id && editedCol == 'tuition_fee'" type="number" class="form-control" v-model="pckg.tuition_fee" v-on:keyup.enter="updateRow(pckg)">
                          <span v-else @dblclick="editCol('tuition_fee', pckg.id, pckg)">
                            {{pckg.tuition_fee | currencyFormat}}
                          </span>
                        </td>
                        <td class="text-center">
                          <input v-if="editedKey == pckg.id && editedCol == 'material_fee'" type="number" class="form-control" v-model="pckg.material_fee" v-on:keyup.enter="updateRow(pckg)">
                          <span v-else @dblclick="editCol('material_fee', pckg.id, pckg)">
                            {{pckg.material_fee | currencyFormat}}
                          </span>
                        </td>
                        <td class="text-center">
                          <input v-if="editedKey == pckg.id && editedCol == 'application_fee'" type="number" class="form-control" v-model="pckg.application_fee" v-on:keyup.enter="updateRow(pckg)">
                          <span v-else @dblclick="editCol('application_fee', pckg.id, pckg)">
                            {{pckg.application_fee | currencyFormat}}
                          </span>
                        </td>
                        <!-- <td>
                          <date-picker v-model="pckg.course_start_date" :formats='formats' :class="[errs[index] && errs[index].course_start_date ? 'border-danger' : '']"></date-picker>
                          <div v-if="errs[index] && errs[index].course_start_date" class="badge badge-danger">{{ errs[index].course_start_date[0]  }}</div>
                        </td>
                        <td>
                          <date-picker v-model="pckg.course_end_date" :formats='formats' :class="[errs[index] && errs[index].course_end_date ? 'border-danger' : '']"></date-picker>
                          <div v-if="errs[index] && errs[index].course_end_date" class="badge badge-danger">{{ errs[index].course_end_date[0]  }}</div>
                        </td>-->
                        <td class="text-center">
                          <div class="btn-group">
                            <!-- <a
                              href="javascript:void(0)"
                              class="btn btn-primary btn-sm"
                              @click.prevent="savePackage(index);"
                            >
                              <i class="fas fa-save"></i>
                            </a> -->
                            <a
                              href="javascript:void(0)"
                              class="btn btn-danger btn-sm"
                              @click="removePackage(index,pckg_id);"
                            >
                              <i class="fas fa-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>
                          <select class="form-control" v-model="pckg.course_package_id">
                            <option v-for="(i,k) in packages" :key="k" :value="i.id">
                              {{i.name}} — {{i.shore_type}}
                            </option>
                          </select>
                        </td>
                        <td class="text-center"> 
                          <input type="number" class="form-control" v-model="pckg.wk_duration">
                        </td>
                        <td class="text-center"> 
                          <input type="number" class="form-control" v-model="pckg.tuition_fee">
                        </td>
                        <td class="text-center"> 
                          <input type="number" class="form-control" v-model="pckg.material_fee">
                        </td>
                        <td class="text-center"> 
                          <input type="number" class="form-control" v-model="pckg.application_fee">
                        </td>
                        <td class="text-center">
                          <button @click="addDetail(index,field.id)" class="btn btn-sm btn-success"><i class="fas fa-plus mr-1"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "course-matrix-international",
  data() {
    return {
      course_code: window.course_code,
      course_name: window.course_name,
      slct_option_state: window.slct_option_state,
      org_name: window.org_name,
      fields: [],
      errors: [],
      app_color: app_color,
      pckgs: [],
      pckg: { 
        wk_duration : 0,
        tuition_fee : 0,
        material_fee : 0,
        application_fee : 0,
      },
      editedCol: null,
      editedRow: {},
      editedKey: null,

    };
  },
  created() {
    this.fetchData();
  },
  watch: {
    fields: function(value, index) {
      for (let i = 0; i < index; i++) {
        if (value[i].cricos_code != null) {
          this.errors[i].cricos_code = "";
        }
        if (value[i].location != null) {
          this.errors[i].location = "";
        }
        console.log(i);
      }
    }
  },
  computed: {
    packages() {
      // let pckgs = window.course_packages;
      // let p = []; 
      // console.log(this.field);
      // for (let index = 0; index < pckgs.length; index++) {
      //   let check = 0;
      //   const el = pckgs[index];
      //   for (let i = 0; i < this.pckgs.length; i++) {
      //     const element = this.pckgs[i];
      //     // console.log(element);
      //     if(el.id == element.course_package_id ) {
      //       check = 1;
      //     }
      //   }
      //   if(check == 0) {
      //     p.push(el);
      //   }
      // }
      // return p;
      return window.course_packages;
    }
  },
  methods: {
    updateRow(row) {
      let vm = this;
      swal.fire({
        title: "Update Column?",
        // text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
      })
      .then(result => {
        if (result.value) {
          axios.post('/course-package/detail/update', row)
          .then(res => {
            if(res.data.status == 'success') {
              Toast.fire({
                // position: 'top-end',
                type: "success",
                title: "Column updated successfully"
              });
              this.editedCol = null;
              this.editedRow = {};
              this.editedKey = null;
            }else {
              Toast.fire({
                // position: 'top-end',
                type: "error",
                title: "data must not be null..."
              });
            }
          })
        }
      });
   
    },
    editCol(col,key,row) {
      this.editedCol = col;
      this.editedRow = row;
      this.editedKey = key;
    },
    removePackage(i, ii) {
      swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      })
      .then(result => {
        if (result.value) {
          axios({
            method: "get",
            url: "/course-packages/destroy/" + this.fields[i].package_detail[ii].id
          }).then(response => {
            swal.fire(
              "Deleted!",
              "Course package has been deleted.",
              "success"
            );
            this.fields[i].package_detail.splice(ii, 1);
          });
        }
      });
    },
    addDetail(i,course_matrix_id) {
      if(!this.pckg.course_package_id) {
        Toast.fire({
          // position: 'top-end',
          type: "error",
          title: "please provide package to be added..."
        });
        return false;
      }
      this.pckg.course_matrix_id = course_matrix_id;
      axios.post(`/course-package/detail/store`, this.pckg)
      .then(res => {
        if(res.data.status == 'success') {
          Toast.fire({
            // position: 'top-end',
            type: "success",
            title: "Course Package Detail added successfully"
          });
          this.fields[i].package_detail.push(res.data.pckg);
          this.pckg = {
            wk_duration : 0,
            tuition_fee : 0,
            material_fee : 0,
            application_fee : 0,
          };
        }else if(res.data.status == 'error') {
          Toast.fire({
            type: "error",
            title: res.data.msg
          });
        }
      })
    },
    fetchData() {
      this.fields = matrix_details;
    },
    addBlock: function() {
      this.fields.push({});
    },
    saveBlock(index) {
      const Toast = swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000
      });
       swal.fire({
        title: 'Please wait...',
        // html: '',// add html attribute if you want or remove
        allowOutsideClick: false,
        onBeforeOpen: () => {
           swal.showLoading()
        },
      });
      if (this.fields[index].id != undefined) {
        this.fields[index].is_update = 1;
      }
      this.fields[index].course_code = this.course_code;
      let saveData = this.fields[index];
      axios({
        method: "put",
        url: "/course/matrix/store-update/",
        data: saveData
      })
        .then(response => {
          saveData = {};
          this.errors = [];
          Toast.fire({
            // position: 'top-end',
            type: "success",
            title: "Course Structure and Fees saved successfully"
          });
        })
        .catch(error => {
          // console.log(error);
          if (error.response.status === 422) {
            this.errors[index] = error.response.data.errors || {};
            this.errors.push({});
            Toast.fire({
              // position: 'top-end',
              type: "error",
              title: "Opss..Course Structure and Fees was not saved"
            });
          }
        });
    },
    removeBlock: function(index) {
      if (this.fields[index].id != undefined) {
        swal
          .fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
          })
          .then(result => {
            if (result.value) {
              axios({
                method: "get",
                url: "/course/matrix/destroy/" + matrix_details[index].id
              }).then(response => {
                swal.fire(
                  "Deleted!",
                  "Course Structure and Fees has been deleted.",
                  "success"
                );
                this.fields.splice(index, 1);
              });
            }
          });
      } else {
        this.fields.splice(index, 1);
      }
    }
  },
  filters : {
    currencyFormat(data) {
      return typeof parseInt(data) === 'number' && parseInt(data) != 0 && !isNaN(parseInt(data)) ? '$'+ numeral(parseFloat(data)).format('0,0') : ' - ';
    }
  }
};
</script>

<style lang="scss" scoped>
$white: #ffffff;
$grey: #e0dfdf;
$light-blue: #e0e9ff;
$green: #1cc88a;
$red: #e74a3b;
.tab-pane {
  border: 1px solid $grey;
  border-top: none;
  padding: 1.3rem;
  border-bottom-right-radius: 5px;
  border-bottom-left-radius: 5px;
  background-color: $white;
}
.card {
  border-radius: 0;
  overflow: initial;
  .card-header {
    background-color: $light-blue;
    border-bottom: 0;
    padding: 0 1.25rem;
    .collapse-link {
      cursor: default;
      &:hover,
      &[aria-expanded="true"] {
        h5 {
          opacity: 1;
        }
      }
      &[aria-expanded="true"] {
        h5:after {
          content: "\f0d7";
        }
      }
      &[aria-expanded="false"] {
        h5:after {
          content: "\f0d7";
          transform: rotate(180deg);
        }
      }
    }
    h5 {
      padding: 0.75rem 0;
      border-bottom: 1px solid $grey;
      opacity: 0.7;
      &:after {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        display: inline-block;
        margin-right: 10px;
        float: right;
      }
    }
    .btn-options {
      position: absolute;
      top: 1px;
      left: -10px;
      .opt-btn {
        margin: 1px 0;
        height: 20px;
        width: 20px;
        background-color: $white;
        font-size: 9px;
        border: 1px solid $grey;
        border-radius: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        outline: none;
        &:hover {
          color: $white !important;
          transform: scale(1.5);
        }
        &:first-child {
          color: #1cc88a;
          &:hover {
            background-color: $green;
          }
        }
        &:last-child {
          color: $red;
          &:hover {
            background-color: $red;
          }
        }
      }
    }
  }
  &:last-of-type {
    border-bottom: 1px solid #e3e6f0;
  }
  .confirm-delete {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgb(231, 74, 59, 0.6);
  }
}
</style>