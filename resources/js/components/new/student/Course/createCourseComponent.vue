<template>
  <modal
    name="size-modal-cc"
    transition="nice-modal-fade"
    classes="demo-modal-class"
    :min-width="200"
    :min-height="200"
    :pivot-y="0.1"
    :adaptive="true"
    :scrollable="true"
    :reset="true"
    width="50%"
    height="auto"
    @before-open="beforeOpen"
    @opened="opened"
    @closed="closed"
    @before-close="beforeClose"
  >
    <div class="size-modal-content">
        <h4 :class="['modal-header-'+app_color, '']"><i class="fas fa-graduation-cap mr-1"></i> <span class="">Create Course</span></h4>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="course_code">Course*</label>
                    <select :class="'form-control'" @change="checkCourseLocation" v-model="selected_course">
                        <option v-for="(i, k) in course_selection" :key="k" :value="i">{{i.code}} - {{i.name}}</option>
                        <option value="@@@@">Unit of Competency</option>
                    </select>
                    <div v-if="errors.course_code" class="badge badge-danger">{{ errors.course_code[0] }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="location">Location*</label>
                    <select v-model="course.location" @change="checkCourseFeeUnit" class="form-control" v-if="selected_course != '@@@@'">
                        <option v-for="(i, k) in course_locations" :key="k" :value="i.state_key">{{i.state_name}} ({{i.state_key}})</option>
                    </select>
                    <select v-model="course.location" class="form-control" v-else>
                        <option v-for="(i, k) in state_locations" :key="k" :value="i">{{i}} ({{k}})</option>
                    </select>
                    <div v-if="errors.location" class="badge badge-danger">{{ errors.location[0] }}</div>
                </div>
            </div>
            <div class="col-md-12" v-if="selected_course == '@@@@'">
                <div class="form-group">
                    <label for="uc_description">Unit of Competency Description:</label>
                    <input type="text" v-model="course.uc_description" class="form-control">
                    <div v-if="errors.uc_description" class="badge badge-danger">{{ errors.uc_description[0] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="eligibility">Eligibility*</label>
                    <select @change="checkCourseFeeUnit" v-model="course.eligibility" class="form-control">
                        <option value="E">Eligible</option>
                        <option value="NE">Not Eligible</option>
                    </select>
                    <div v-if="errors.eligibility" class="badge badge-danger">{{ errors.eligibility[0] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group" v-if="student.student_type_id == 2">
                    <label for="course_fee_type">Course Fee Type*</label>
                    <select @change="checkCourseFeeUnit" v-model="course.course_fee_type" class="form-control">
                        <option value="C">Concessional</option>
                        <option value="NC">Government Funded</option>
                        <option value="FF">Fee for Service</option>
                    </select>
                    <div v-if="errors.course_fee_type" class="badge badge-danger">{{ errors.course_fee_type[0] }}</div>
                </div>
                <div class="form-group" v-else>
                    <label for="shore_type">Shore Type*</label>
                    <select @change="checkCourseFeeUnit" v-model="course.shore_type" class="form-control">
                        <option value="ONSHORE">ON-SHORE</option>
                        <option value="OFFSHORE">OFF-SHORE</option>
                    </select>
                    <div v-if="errors.shore_type" class="badge badge-danger">{{ errors.shore_type[0] }}</div>
                </div>
                
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="course_fee">Course Fee:</label>
                    <input
                        type="number"
                        class="form-control"
                        v-model="course.course_fee"
                    />
                    <div v-if="errors.course_fee" class="badge badge-danger">{{ errors.course_fee[0] }}</div>
                </div>
            </div>
            
            <!-- class, start, end, status -->
            <div class="col-md-4">
                <div class="form-group">
                    <label for="class_id">Class</label>
                    <select v-model="course.class_id" class="form-control">
                        <option :value="null">No Class</option>
                    </select>
                    <div v-if="errors.class_id" class="badge badge-danger">{{ errors.class_id[0] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="start_date">Start Date* <span style="font-size: 74%; opacity: 0.73;">( DD/MM/YYYY )</span></label>
                    <date-picker
                        locale="en"
                        mode="date"
                        v-model="course.start_date"
                        :masks="{L:'DD/MM/YYYY'}"
                        :input-props='{
                        autocomplete:"off",
                        }'
                    ></date-picker>
                    <div v-if="errors.start_date" class="badge badge-danger">{{ errors.start_date[0] }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="end_date">End Date* <span style="font-size: 74%; opacity: 0.73;">( DD/MM/YYYY )</span></label>
                    <date-picker
                        locale="en"
                        mode="date"
                        v-model="course.end_date"
                        :masks="{L:'DD/MM/YYYY'}"
                        :input-props='{
                        autocomplete:"off",
                        }'
                    ></date-picker>
                    <div v-if="errors.end_date" class="badge badge-danger">{{ errors.end_date[0] }}</div>
                </div>
            </div>
        </div>
        <!-- table -->
        <div class="table-responsive table-height">
            <table class="table unit-table">
            <!-- <table class="table" v-if="po.po_date && po.po_type && po.purchase_order_source_id"> -->
                <thead>
                    <tr>
                        <th width="8%" class="text-center cell-head" :class="'table-header-'+app_color">#</th>
                        <th class="text-center cell-head" :class="'table-header-'+app_color">Unit</th>
                        <th class="text-center cell-head" :class="'table-header-'+app_color">Description</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="(i,k) in units" :key="k">
                        <td class="cell text-center">{{k + 1}}</td>
                        <td class="cell text-center">{{i.code}}</td>
                        <td class="cell text-uppercase">{{i.description}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr v-if="selected_course == '@@@@'">
                        <td class="text-center"> 
                            <button @click="addUnit" class="btn btn-sm btn-success"><i class="fas fa-plus"></i></button> 
                        </td>
                        <td colspan="2">
                            <select class="form-control" v-model="extra_unit">
                                <option class="text-uppercase" v-for="(i,k) in units_available" :key="k" :value="i">{{i.code}} - {{i.description}}</option>
                            </select>
                        </td>
                    </tr>
                    <!-- <tr v-if="details.length > 0" style="background-color:#e2e2e2 !important">
                        <td colspan="3" class="text-right">
                            <span class="h6 font-weight-bold mr-2">Total Amount:</span>
                        </td>
                        <td class="text-right">
                            <span class="h6 font-weight-bold">PHP {{total_amount | currencyFormat}}</span>
                        </td>
                        <td></td>
                    </tr> -->

                    <!-- adding units of course is unit of competency -->
                    <!-- <tr> -->
                        <!-- select course -->
                        <!-- <td class="text-center">
                            <multiselect 
                            v-model="select_unit"
                            :options="branch_products" 
                            :placeholder="`Select Unit`"
                            :searchable="true"
                            :custom-label="unitLable"
                            :close-on-select="true"
                            :multiple="false"
                            :allow-empty="false"
                            :selectLabel="''"
                            :selectedLabel="''"
                            :deselect-label="''"
                            ></multiselect>
                        </td> -->
                        
                        <!-- <td class="text-center">
                            <button class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i>
                            </button>
                        </td> -->
                    <!-- </tr> -->
                    <!-- <tr>
                        <td colspan="5"><i style="font-size:14px"><b>Notes:</b><br>
                        - <b>Quantity</b> & <b>Amount</b> must be <b>zero (0)</b> if product has no sale.<br>
                        - <b>Product Qunatity</b> must not be greater than <b>Product Inventory</b><br>
                        - Double click <b>Price</b> to update <b> Current Product Price</b> (Price must be different with the current one)</i></td>
                    </tr> -->
                </tfoot>
            </table>
        </div>
        
        <!-- footer -->
        <div class="row">
            <div class="col-md-12 text-right pb-1">
                <button type="button" :class="'btn btn-success'" @click="saveCourse"><i class="far fa-save"></i> Save</button>
                <button type="button" :class="'btn btn-danger'" @click="closeModal"><i class="fas fa-times"></i> Close</button>
            </div>
        </div>
    </div>
  </modal>
</template>
<script>
import moment from "moment";
export default {
  data() {
    return {
      app_color:app_color,
      errors: {},
      close: 0,
      firstLoad : 0,
      isEdit : null,
      selected_course : {},
      course : {
          course_fee : 0,
          class_id : null,
      },
      units: [],
      prospectus: {},
      course_selection : [],
      course_locations: [],
      state_locations: window.slct_state,
      extra_unit: {},
      unit_selection: [],
    };
  },
  computed: {
      student() {
        return this.$store.state.student
      },
      units_available() {
        let u = [];
        
        for (let i = 0; i < this.unit_selection.length; i++) {
            const element = this.unit_selection[i];
            let check = 0;
            for (let ii = 0; ii < this.units.length; ii++) {
                const el = this.units[ii];
                if(element.code == el.code) {
                    check = 1;
                }
            }
            if(check == 0) {
                u.push(element);
            }
        }

        return u;

      },
  },
  methods: {
    
    saveCourse() {
        this.errors = {};
        swal.fire({
            title: `Saving Course...`,
            allowOutsideClick: false,
            onBeforeOpen: () => {
                swal.showLoading()
            },
        });

        axios.post('/student-profile/course', {course:this.course, units:this.units})
        .then(res => {
            if(res.data.status == 'updated') {
                swal.fire(
                    'Horaay!',
                    'Course created successfully',
                    'success'
                )
                .then(cont => {
                    if(res.data == 'updated') {
                        this.$parent.fetchStudentCourses(1);
                        this.closeModal()
                    }
                })
                return res.data
            }else {
                this.errors = res.data.errors;
                swal.fire(
                    'Opss...',
                    res.data.msg,
                    'error'
                )
            }
        })
    },

    addUnit() {
        if(this.extra_unit.code) {
            this.units.push(this.extra_unit)
            this.extra_unit = {};
            Toast.fire({
              type: "success",
              title: `Unit added to the list`,
              position: 'bottom-right'
            });
        }else {
            swal.fire(
                'Opss...',
                'Please select unit',
                'error'
            )
        }
    },

    async fetchCourseSelection() {
        await axios.get('/fetch-course-selection')
        .then(res => {
            this.course_selection = res.data.course
            this.unit_selection = res.data.extra_units
        })
    },

    checkCourseFeeUnit() {

        this.checkCourseUnits();

        let fetch = 0;
        if(this.student.student_type_id == 2) {
            if(this.course.course_code && this.course.location && this.course.course_fee_type && this.course.eligibility) {
                fetch = 1;
            }
        }else {
            if(this.course.course_code && this.course.location && this.course.shore_type && this.course.eligibility) {
                fetch = 1;
            }
        }
        if(fetch == 1) {
            swal.fire({
                title: `Fetching Course Fee...`,
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    swal.showLoading()
                },
            });
            axios.post('/fetch-course-fee', {course:this.course, student_type: this.student.student_type_id})
            .then(res => {
                console.log(res.data.fee)
                this.course.course_fee = parseFloat(res.data.fee);
                swal.close();
            })    

        }
    },

    checkCourseUnits() {

        if(this.prospectus && this.prospectus.course_code == this.course.course_code) {
            return false;
        }

        if(this.course.course_code && this.course.course_code != '@@@@' && this.course.location) {

            swal.fire({
                title: `Fetching Course Units...`,
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    swal.showLoading()
                },
            });
            axios.post('/fetch-course-units', {course:this.course, student_type: this.student.student_type_id})
            .then(res => {
                this.units = res.data.units;
                this.prospectus = res.data.prospectus;
                swal.close();
            })  
        }
        
        if(this.course.course_code == '@@@@' && this.prospectus.course_code != this.course.course_code) {
            this.prospectus = {
                course_code : '@@@@'
            }
            this.units = [];
        }
    },

    checkCourseLocation() {
        if(this.selected_course == '@@@@'){
            this.course.course_code = '@@@@'
            this.course_locations = this.state_locations
        }else {
            this.course.course_code = this.selected_course.code
            this.course_locations = this.selected_course.states
        }
        this.location = null
        this.checkCourseFeeUnit();
    },

    closeModal() {
        this.close = 1;
        this.firstLoad = 0;
        this.$modal.hide('size-modal-cc');
    },
    async beforeOpen(e) {
        swal.fire({
            title: `Fetching Available Course...`,
            allowOutsideClick: false,
            onBeforeOpen: () => {
                swal.showLoading()
            },
        });

        await this.fetchCourseSelection();

        this.course = {
            course_fee : 0,
            class_id : null,
            student_id : this.student.student_id
        }
        this.selected_course = {};
        this.units = [];
        this.firstLoad = 1;
        this.close = 0;

        if(student.student_type_id == 2) {
            this.course.shore_type = 'ONSHORE';
        }else {
            this.course.course_fee_type = 'FF';
        }

        swal.close();
    },
    async beforeClose(e) {
        if(this.close == 0){
            e.stop()
            return false;
        }
        this.extra_unit = {};
        this.course = {
            course_fee : 0,
            class_id : null,
            student_id : this.student.student_id
        }
        this.selected_course = {};
        this.units = [];
    },
    opened(e) {

    },
    closed(e) {
    //   console.log("closed", e);
    }

  },
  watch: {
      
  },
  filters: {
    formatDate(data) {
      return data ? moment(data).format('MM/DD/YYYY') : '-';
    },
    filterMeasurement(data, measurement) {
      measurement = measurement.product.product_measurement_type
    //   console.log(measurement.product.product_measurement_type);
      let qty = typeof parseFloat(data) == 'number' ? numeral(parseFloat(data)).format('0,0.000') : '0.00';
      return `${qty} ${parseFloat(data) > 1 ? measurement.symbol_plural : measurement.symbol}`;
    },
    currencyFormat(data) {
      return typeof parseFloat(data) == 'number' ? ''+ numeral(parseFloat(data)).format('0,0.000') : '0.00';
    }
  }
};
</script>

<style>
.v--modal-box .size-modal-content {
    /* padding: 0 0.81rem !important; */
}
.v--modal-overlay .v--modal-box {
  overflow: visible !important;
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
.bpi-edited-row {
  background-color: #63a8c29e !important;
  color: #fff !important;
}
</style>

<style scoped>
    .form-group {
        margin: 0 !important;
    }
    .table-responsive {
        overflow: visible !important;
    }
    .pointer {
        cursor: pointer;
    }
    .sale-to-zero {
        background-color:wheat;
    }
    .edit-row {
        background-color: #63a8c29e !important;
        color: #fff !important;
    }
    .cell {
        line-height: 1.2rem;
        font-size:12px;
    }
    .cell-head {
        line-height: 1.5rem;
        font-size:14px;
    }
    .table-height {
        max-height: 300px !important;
        overflow: auto !important;
    }
    .unit-table td {
        font-size: 12px !important;
        /* vertical-align: inherit !important; */
        padding: 0.45rem !important;
    }
    .unit-table thead th { 
        position: sticky; 
        top: 0; ;
        border: none !important;
        font-size: 12px !important;
    }
</style>