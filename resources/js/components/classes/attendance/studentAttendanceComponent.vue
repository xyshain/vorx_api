<template>
  <div class="app-modal">
      <add-attendance-modal/>
      <edit-attendance-modal/>
    <div class="row mb-2 d-flex justify-content-between">
      <div class="col-md-6">
        <a
          :href="'/classes/attendance/'+student_attendance.class_id"
          v-bind:class="'btn btn-'+app_color+' btn-sm text-'+app_color+' text-light'"
        >
          <i class="fas fa-long-arrow-alt-left"></i> Back
        </a>
      </div>
      <div class="col-md-6">
            <div class="float-right">
            <a type="button" v-bind:class="'btn btn-'+app_color+' btn-sm'" target="_blank" :href="'/attendance/pdf/'+this.student_attendance.id"><i class="fas fa-file-pdf"></i>  Generate Student Attendance</a>
            </div>
      </div>
    </div>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 :class="'m-0 font-weight-bold text-'+app_color">
          Student Attendance
          <span>
            ({{student_attendance.student.student_id}} - {{ student_attendance.student.party.name }})
            <a
              :href="'/student/'+student_attendance.student.id"
              :class="'btn btn-'+app_color+' text-primary btn-sm text-light'"
              style="padding: 0px 6px;"
            >
              <i class="fas fa-award fa-sm"></i>
            </a>
          </span>
        </h6>
      </div>
      <div class="card-body">
        <div :class="'horizontal-line-wrapper-'+app_color+' my-3'">
        <h6>Add New</h6>
        </div>
        <div class="row">
            <!-- <div class="col-md-6">
                <div class="form-group">
                    <label for="">Unit Code:</label>
                    <multiselect v-model="new_student_attendance.unit" 
                    :options="units" 
                    :custom-label="getUnitInfo"
                    placeholder="Select one" 
                    label="getUnitInfo" 
                    track-by="id"
                    ></multiselect>
                    <div v-if="new_student_attendance.errors">
                    <span v-if="new_student_attendance.errors.unit" :class="['badge badge-danger']">{{ new_student_attendance.errors.unit[0] }}</span>
                    </div>
                </div>
            </div> -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Date taken:</label>
                    <span style="font-size: 74%;opacity: 73%;">( DD/M/YYYY )</span>
                    <date-picker
                    locale="en"
                    v-model="admod.date_taken"
                    :masks="{L:'DD/MM/YYYY'}"
                    />
                    <div v-if="errors">
                    <span v-if="errors.date_taken" :class="['badge badge-danger']">{{ errors.date_taken[0] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="">Status:</label>
                  <select name="" id="" class="form-control" v-model="admod.status">
                      <option value="Present">Present</option>
                      <option value="Absent">Absent</option>
                      <option value="N/A">N/A</option>
                  </select>
                  <div v-if="errors">
                  <span v-if="errors.status" :class="['badge badge-danger']">{{ errors.status[0] }}</span>
                  </div>
              </div>
          </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Preferred hours</label>
                    <input type="number" class="form-control" v-model="admod.preferred_hours" placeholder="8">
                    <div v-if="errors"> 
                      <span v-if="errors.preferred_hours" :class="['badge badge-danger']">{{ errors.preferred_hours[0] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Actual hours:</label>
                    <input type="number" class="form-control" v-model="admod.actual_hours" placeholder="0">
                    <div v-if="errors">
                      <span v-if="errors.actual_hours" :class="['badge badge-danger']">{{ errors.actual_hours[0] }}</span>
                    </div>
                </div>
            </div>
          
      </div>
      <div v-if="typeof admod.id != 'undefined'">
          <button class="btn btn-success" @click="saveChanges()">
              <i class="far fa-save"></i> Update
          </button>
          <button class="btn btn-danger" @click="cancelEdit()">
              <i class="fa fa-window-close"></i> Cancel 
          </button>
      </div>
      <div v-else>
            <button class="btn btn-success" @click="saveChanges()">
              <i class="far fa-save"></i> Add
          </button>
      </div>
      <div class="clear-fix" style="height:20px;"></div>
      <div v-bind:class="'horizontal-line-wrapper-'+app_color+' mb-2'">
          <h6>Attendance Sheet</h6>
      </div>
        <v-client-table
          :class="'header-'+app_color"
          :data="attendance_sheet"
          :columns="columns"
          :options="options"
          ref="courseTable"
        >
        <div slot="date_taken" slot-scope="{row}">{{ row.date_taken | dateformat }}</div>
          <div slot="afterLimit" class="ml-2">
            <div class="btn-group">
              <!-- <a
                href="javascript:void(0)"
                @click="showAddAttendance"
                class="btn btn-success"
                slot="afterLimit"
              >
                <i class="fas fa-plus"></i> Add New
              </a> -->
            </div>
          </div>
          <div class="btn-group" slot="actions" slot-scope="{row}">
            <a href="javascript:void(0)" class="btn btn-primary btn-sm" @click="edit(row)">
              <i class="fas fa-edit"></i>
            </a>
            <a
              href="javascript:void(0)"
              class="btn btn-danger btn-sm text-white"
              @click="remove(row.id)"
            >
              <i class="fas fa-trash"></i>
            </a>
          </div>
        </v-client-table>
        
      </div>
    </div>
  </div>
</template>


<script>
import moment from "moment";
export default {
  name: "app-modal",
  data() {
    return {
      app_color:app_color,
      attendance_sheet:[],
      student_attendance:window.student_attendance,
      student_id:window.student_id,
      admod:{
      },
      errors:{},
      units:[],
        columns: ["id","date_taken","preferred_hours","actual_hours","status","actions"],
      options: {
        initialPage: 1,
        perPage: 10,
        highlightMatches: true,
        sortIcon: {
          base: "fas",
          up: "fa-sort-amount-up",
          down: "fa-sort-amount-down",
          is: "fa-sort"
        },
        headings: {
            
        },
        sortable: ["id", "code", "name"],
        rowClassCallback(row) {
          return (row.id = row.id);
        },
        columnClasses: { id: "class-is" },
        texts: {
          filter: "Search:",
          filterPlaceholder: "Search keywords"
        }
      }
    };
  },
  created() {
      this.getStudentAttendance();
      this.getUnits();
  },
  methods: {
    cancelEdit(){
      this.admod = {};
      this.errors = {};

    },
    saveChanges(){
            let dis = this;
            swal.fire({
            title: 'Please wait...',
            // html: '',// add html attribute if you want or remove
            allowOutsideClick: false,
            onBeforeOpen: () => {
                swal.showLoading()
                },
            });
        var actual_hours = this.admod.actual_hours==null?0:this.admod.actual_hours;
        var preferred_hours = this.admod.preferred_hours==null?8:this.admod.preferred_hours;
        
        if(actual_hours>preferred_hours){
            this.errors = {
                actual_hours:['Must be lesser than preferred hours.']
            }
            Toast.fire({
                type: "error",
                title: "Opss.. something went wrong",
                background: "#ffcdd2"
            });
        }else{
               if(typeof this.admod.id != 'undefined'){//store  
                    console.log('update');
                    this.admod.attendance_id = this.student_attendance.id;
                    axios.post('/attendance/update_student_attendance_detail',this.admod).then(
                            response=>{
                                console.log(response.data);
                            if(response.data.status=='error'){
                                dis.errors = response.data.errors;
                                Toast.fire({
                                type: "error",
                                title: "Opss.. something went wrong",
                                background: "#ffcdd2"
                                });
                            }else{
                                swal.fire(
                                "Succes!",
                                "Data updated successfully.",
                                "success"
                                );
                                // this.$modal.hide("edit-attendance-sheet");
                                this.getStudentAttendance();
                                this.admod = {};
                                this.errors ={};
                            }
                            }
                        ).catch(
                        e=>{
                            console.log(e);
                            Toast.fire({
                            type: "error",
                            title: "Opss.. something went wrong",
                            background: "#ffcdd2"
                            });
                        }
                    );
                    swal.close();
                }else{
                    console.log('store');
                    this.admod.attendance_id = this.student_attendance.id;
                    axios.post('/attendance/new_student_attendance_detail',this.admod).then(
                        response=>{
                            if(response.data.status == 'error'){
                                this.errors = response.data.errors;
                                Toast.fire({
                                    type: "error",
                                    title: "Opss.. something went wrong",
                                    background: "#ffcdd2"
                                });
                            }else{
                                Toast.fire({
                            // position: 'top-end',
                                type: "success",
                                title: "Data is saved successfully",
                                background: "#DCEDC8"
                                });
                                console.log(response.data.enrolment_pack);
                                // this.updateEnrolment(response.data.enrolment_pack);
                                this.getStudentAttendance();
                                this.admod = {};
                                this.errors ={};
                            }
                        }   
                    ).catch(
                        err=>{

                        }
                    );

                }
            }
        },
    getUnits(){
      let dis = this;
      axios.get(`/student/domestic/get_units/${this.student_id}/${this.student_attendance.course_code}`).then(
        response=>{
          response.data.forEach(function(units){
              dis.units.push(units);
          });
        }
      ).catch();
    },
    getUnitInfo({code,description}){
        return `${code} ${description}`;
    },
    showAddAttendance(){
        this.$modal.show('add-attendance-modal');
    },
    getStudentAttendance(){
        axios.get('/attendance/get_student_attendance/'+this.student_attendance.id).then(
            response=>{
                this.attendance_sheet = response.data;
            }
        );  
    },
    edit(row){
      row.date_taken = moment(row.date_taken)._d;
      console.log(row);
      this.admod = row;
      
      window.scroll(0,window.pageYOffset=150);
    },
    remove(id) {
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
            axios.post('/classes/delete_attendance_detail',{
              id:id
            }).then(
              response=>{
                swal.fire(
                  "Deleted!",
                  "Class has been deleted.",
                  "success"
                );
                this.getStudentAttendance();
              }
            ).catch();
          }
        });
    },
    generateAttendance(){
      window.href = "/attendance/pdf/"+this.student_attendance.id;
    }
  },
  filters:{
    dateformat: function(date) {
      if (!date) return "";
      date = moment(date).format("DD/MM/YYYY");
      return date;
    },
    timeformat: function(time){
        if (!time) return "";
        time = moment('1111-11-11 '+time).format("hh:mm A");
        return time;
    }
  }
};
</script>