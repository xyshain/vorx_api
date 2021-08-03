<template>
  <div>
          <div class="row mb-2 d-flex justify-content-between">
            <div class="col-md-12">
                <a
                  href="/course-package"
                  v-bind:class="'btn btn-'+app_color+' btn-sm text-primary text-light'"
                >
                <i class="fas fa-long-arrow-alt-left"></i> Back
                </a>
            </div>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 :class="'m-0 font-weight-bold text-'+app_color">Course Package Details</h6>
            </div>
            <div class="card-body">
                <div>
                    <form @submit.prevent="update">
                    <div class="row mb-2 d-flex justify-content-between">
                      <div class="col-md-12">
                        <button type="submit" class="btn btn-success btn-sm d-block ml-auto">
                          <i class="far fa-save"></i> Save Package Info
                        </button>
                      </div>
                    </div>
                    <div v-bind:class="'horizontal-line-wrapper-'+app_color+' mb-2'">
                      <h6>Course Package</h6>
                    </div>
                    <div class="form-padding-left-right">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="name">Name:</label>
                            <input
                              type="text"
                              id="name"
                              class="form-control"
                              v-model="fields.name"
                              :class="[errors && errors.name ? 'border-danger' : '']"
                            />
                            <div v-if="errors && errors.name" class="badge badge-danger">{{ errors.name[0] }}</div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="shore_type">Shor Type:</label>
                            <select
                              id="shore_type"
                              class="form-control"
                              v-model="fields.shore_type"
                              :class="[errors && errors.shore_type ? 'border-danger' : '']"
                            >
                              <option value="ONSHORE">ONSHORE</option>
                              <option value="OFFSHORE">OFFSHORE</option>
                            </select>
                            <div
                              v-if="errors && errors.shore_type"
                              class="badge badge-danger"
                            >{{ errors.shore_type[0] }}</div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="is_active">Status:</label>
                            <div class="custom-control custom-switch my-2">
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                id="is_active"
                                v-model="fields.is_active"
                                value="0"
                              />
                              <label class="custom-control-label" for="is_active">Set as Active</label>
                            </div>
                          </div>
                          <!-- <div class="form-group">
                            <label for="location">State:</label>
                            <select
                              id="location"
                              class="form-control"
                              v-model="fields.location"
                              :class="[errors && errors.location ? 'border-danger' : '']"
                            >
                              <option v-for="(location, index) in state" :key="index"  :value="location.state_key">{{location.state_name}}</option>
                            </select>
                            <div
                              v-if="errors && errors.location"
                              class="badge badge-danger"
                            >{{ errors.location[0] }}</div>
                          </div> -->
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea
                              id="description"
                              class="form-control"
                              v-model="fields.description"
                              cols="30"
                              rows="5"
                              :class="[errors && errors.description ? 'border-danger' : '']"
                            ></textarea>
                            <div
                              v-if="errors && errors.description"
                              class="badge badge-danger"
                            >{{ errors.description[0] }}</div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <!-- <div class="form-group">
                            <label for="delivery_location_id">Delivery Location:</label>
                            <select
                              id="delivery_location_id"
                              class="form-control"
                              v-model="fields.delivery_location_id"
                              :class="[errors && errors.delivery_location_id ? 'border-danger' : '']"
                            >
                              <option v-for="(item, index) in dlvr_location" :key="index"  :value="item.id">{{item.train_org_dlvr_loc_name}}</option>
                            </select>
                            <div
                              v-if="errors && errors.date_created"
                              class="badge badge-danger"
                            >{{ errors.date_created[0] }}</div>
                          </div> -->
                          
                        </div>
                      </div>
                    </div>
                  </form>
                  <div v-bind:class="'horizontal-line-wrapper-'+app_color+' mb-2'">
                    <h6>Package Details</h6>
                  </div>
                  <table :class="'table header-'+app_color" width="100%">
                    <thead>
                      <tr>
                        <th width="5%" :class="'table-header-'+app_color"> - </th>
                        <th width="40%" :class="'table-header-'+app_color">Course</th>
                        <th :class="'table-header-'+app_color">Weeks</th>
                        <th :class="'table-header-'+app_color">Tuition<br>Fee</th>
                        <th :class="'table-header-'+app_color">Material<br>Fee</th>
                        <th :class="'table-header-'+app_color">Application<br>Fee</th>
                        <th :class="'text-center table-header-'+app_color">
                            Action
                        </th>
                      </tr>
                    </thead>
                    <!-- <tbody> -->
                    <draggable v-model="pckgs" tag="tbody" handle=".handle" @change="changeOrder">
                      <tr v-for="(pckg, index) in pckgs" :key="pckg.course_matrix_id">
                        <td class="text-center">
                          <i class="fa fa-align-justify handle pt-2"></i>
                        </td>
                        <td class="text-center">
                          {{pckg.course.detail.code}} - {{pckg.course.detail.name}} — {{pckg.course.location}}
                        </td>
                        <td class="text-center">
                          <input v-if="editedKey == index && editedCol == 'wk_duration'" type="number" class="form-control" v-model="pckg.wk_duration" v-on:keyup.enter="updateRow(pckg)">
                          <span v-else @dblclick="editCol('wk_duration', index, pckg)">
                            {{pckg.wk_duration ? pckg.wk_duration+' Weeks' : ' - '}}
                          </span>
                        </td>
                        <td class="text-center">
                          <input v-if="editedKey == index && editedCol == 'tuition_fee'" type="number" class="form-control" v-model="pckg.tuition_fee" v-on:keyup.enter="updateRow(pckg)">
                          <span v-else @dblclick="editCol('tuition_fee', index, pckg)">
                            {{pckg.tuition_fee | currencyFormat}}
                          </span>
                          
                        </td>
                        <td class="text-center">
                          <input v-if="editedKey == index && editedCol == 'material_fee'" type="number" class="form-control" v-model="pckg.material_fee" v-on:keyup.enter="updateRow(pckg)">
                          <span v-else @dblclick="editCol('material_fee', index, pckg)">
                            {{pckg.material_fee | currencyFormat}}
                          </span>
                          
                          
                        </td>
                        <td class="text-center">
                          <input v-if="editedKey == index && editedCol == 'application_fee'" type="number" class="form-control" v-model="pckg.application_fee" v-on:keyup.enter="updateRow(pckg)">
                          <span v-else @dblclick="editCol('application_fee', index, pckg)">
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
                              @click="removePackage(index);"
                            >
                              <i class="fas fa-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                    </draggable>
                    <!-- </tbody> -->
                    <tfoot>
                      <tr>
                        <td class="text-center"> - </td>
                        <td>
                          <select class="form-control" v-model="pckg.course_matrix_id">
                            <option v-for="(i,k) in courseMatrix" :key="k" :value="i.id">
                              {{i.detail.code}} - {{i.detail.name}} — {{i.location}}
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
                          <button @click="addDetail" class="btn btn-sm btn-success"><i class="fas fa-plus mr-1"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
            </div>
        </div>
  </div>
</template>

<script>
import FormMixin from "../../../mixins/FormMixin";
import moment from "moment";
export default {
  mixins: [FormMixin],
  name: "coursePackageInfo",
  data() {
    return {
      app_color: app_color,
      // courseMatrix: course_matrix,
      packageId: course_package.id,
      dlvr_location : window.dlvr_location,
      state: window.state,
      pckgs: [],
      pckg: {
        wk_duration : 0,
        tuition_fee : 0,
        material_fee : 0,
        application_fee : 0,
      },
      errs: [],
      // Form Attributes
      action: "/course-package/" + course_package.id,
      method: "put",

      formats: {
        title: "MMMM YYYY",
        weekdays: "W",
        navMonths: "MMM",
        input: ["L", "YYYY-MM-DD", "DD/MM/YYYY"],
        dayPopover: ""
      },
      editedCol: null,
      editedRow: {},
      editedKey: null,
    };
  },
  computed: {
    courseMatrix() {
      let wcm = course_matrix;
      let cm = []; 
      
      for (let index = 0; index < wcm.length; index++) {
        let check = 0;
        const el = wcm[index];
        for (let i = 0; i < this.pckgs.length; i++) {
          const element = this.pckgs[i];
          if(el.id == element.course_matrix_id) {
            check = 1;
          }
        }
        if(check == 0) {
          cm.push(el);
        }
      }
      return cm;
    }
  },
  watch: {
    fields: function(value) {
      if (value.name != null) {
        this.errors.name = "";
      }
      if (value.shore_type != null) {
        this.errors.shore_type = "";
      }
      // if (value.location != null) {
      //   this.errors.location = "";
      // }
    },
    'pckg.wk_duraiton' : function(newVal) {
        if(typeof parseFloat(newVal) === 'number'){
          this.pckg.wk_duraiton = newVal;
        }else {
          this.pckg.wk_duraiton = 0;
        }
    },
    'pckg.tuition_fee' : function(newVal) {
      if(typeof parseFloat(newVal) === 'number'){
        this.pckg.tuition_fee = newVal;
      }else {
        this.pckg.tuition_fee = 0;
      }
    },
    'pckg.material_fee' : function(newVal) {
      if(typeof parseFloat(newVal) === 'number'){
        this.pckg.material_fee = newVal;
      }else {
        this.pckg.material_fee = 0;
      }
    },
    'pckg.application_fee' : function(newVal) {
      if(typeof parseFloat(newVal) === 'number'){
        this.pckg.application_fee = newVal;
      }else {
        this.pckg.application_fee = 0;
      }
    },
  },
  created() {
    this.fetchData();
    console.log(window.course_matrix);
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
    changeOrder(e) {
      swal.fire({
          title: 'Changing Order...',
          // html: '',// add html attribute if you want or remove
          allowOutsideClick: false,
          onBeforeOpen: () => {
              swal.showLoading()
          },
      });

      axios.post('/course-package/change-order', this.pckgs)
      .then(res => {
        if(res.data.status == 'success') {
          Toast.fire({
            // position: 'top-end',
            type: "success",
            title: "Order successfully changed"
          });
        }else {
          Toast.fire({
            // position: 'top-end',
            type: "error",
            title: "there seems to be a problem..."
          });
        }
      })

    },
    addDetail() {
      if(!this.pckg.course_matrix_id) {
        Toast.fire({
          // position: 'top-end',
          type: "error",
          title: "please provide course to be added..."
        });
        return false;
      }
      this.pckg.course_package_id = this.packageId;
      axios.post(`/course-package/detail/store`, this.pckg)
      .then(res => {
        if(res.data.status == 'success') {
          Toast.fire({
            // position: 'top-end',
            type: "success",
            title: "Course Package Details added successfully"
          });
          this.pckgs.push(res.data.pckg);
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
      this.fields = course_package;
      this.fields.date_created = moment(course_package.date_created)._d;
      this.pckgs = package_details;

      // for (let i = 0; i < this.pckgs.length; i++) {
      //   this.pckgs[i].course_start_date = moment(
      //     package_details.course_start_date
      //   )._d;
      //   this.pckgs[i].course_end_date = moment(
      //     package_details.course_end_date
      //   )._d;
      // }
    },

    addPackage: function() {
      this.pckgs.push({});
    },

    savePackage(index) {
      if (this.pckgs[index].id != undefined) {
        this.pckgs[index].is_update = 1;
      }
      this.pckgs[index].course_package_id = this.packageId;
      let saveData = this.pckgs[index];
      console.log(saveData);
      axios({
        method: "put",
        url: "/course-packages/store-update/",
        data: saveData
      })
        .then(response => {
          saveData = {};
          Toast.fire({
            // position: 'top-end',
            type: "success",
            title: "Course Package Details saved successfully"
          });
        })
        .catch(error => {
          console.log(error);
          if (error.response.status === 422) {
            this.errs[index] = error.response.data.errors || {};
            this.errs.push({});
            Toast.fire({
              // position: 'top-end',
              type: "error",
              title: "Opss..Course Package Details was not saved"
            });
          }
        });
    },

    removePackage: function(index) {
      if (this.pckgs[index].id != undefined) {
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
                url: "/course-packages/destroy/" + package_details[index].id
              }).then(response => {
                swal.fire(
                  "Deleted!",
                  "Course matrix has been deleted.",
                  "success"
                );
                this.pckgs.splice(index, 1);
              });
            }
          });
      } else {
        this.pckgs.splice(index, 1);
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