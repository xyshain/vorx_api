<template>
  <div>
    <create-offer-letter></create-offer-letter>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 :class="'m-0 font-weight-bold text-'+app_color">Student List</h6>
      </div>
      <div class="card-body">
        <div>
          <div slot="beforeTable" >
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="">From:</label>
                      <date-picker
                      :max-date="filter_fields.to"
                      locale="en"
                      v-model="filter_fields.from"
                      :masks="{L:'DD/MM/YYYY'}"
                      />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="">To:</label>
                      <date-picker
                      :min-date="filter_fields.from"
                      locale="en"
                      v-model="filter_fields.to"
                      :masks="{L:'DD/MM/YYYY'}"
                      />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="">Course:</label>
                    <select name="" id="" class="form-control" v-model="filter_fields.get_course">
                        <option :value="key" v-for="(itm,key) in courses" :key="key">
                            <span v-if="key != '*'">{{key}} - {{itm}}</span>
                            <span v-else>{{itm}}</span>
                        </option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="">Location:</label>
                      <select v-model="filter_fields.location" class="form-control">
                        <option value="*">All</option>
                        <option value="QLD">QLD</option>
                        <option value="VIC">VIC</option>
                      </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group ">
                    <label for="">Student type:</label>
                      <select name="" id="" class="form-control" v-model="filter_fields.student_type" @change="filter_status()">
                          <option value="*">All Student Type</option>
                          <option value="2">Domestic</option>
                          <option value="1">International</option>
                      </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="">Status:</label>
                    <select name="" id="" class="form-control" v-model="filter_fields.get_status">
                        <option :value="itm.key" v-for="itm in statuses" :key="itm.key">
                            <span>{{itm.value}}  </span>
                        </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          <v-client-table 
            :data="student_list" 
            :columns="columns" 
            :options="options" 
            ref="studentList"
            :class="'header-'+app_color"          
            >
              <div slot="afterLimit" class="ml-2">
                <div class="btn-group">
                  <a
                    href="javascript:void(0)"
                    @click="showCreateOfferLetter"
                    class="btn btn-success"
                    slot="afterLimit"
                  >
                    <i class="fas fa-plus"></i> Add Student
                  </a>
                </div>
              </div>
              <div slot="beforeFilter" class="md3">
                <strong>Search:</strong>
                <input type="text" class="form-control" @keyup.enter="getStudents(true)" placeholder="Student Name/ID"
                   data-toggle="tooltip" data-placement="right" title="Press enter to search"
                   v-model="search"
                >
              </div>
              <div slot="beforeFilter" style="margin-left:40px;">
                <span class="fas fa-building"></span> : Domestic, <span class="fas fa-plane"></span> : International
              </div>
            <div slot="student" slot-scope="{row}">
              <span>
                <span v-if="row.type.id == 2" class="fas fa-building"></span>
                <span v-else class="fa fa-plane"></span>
              {{row.student_id}} - {{row.party.name}}
              </span>
            </div>
            <template class="btn-group" slot="report" slot-scope="{row,index}">
              <div class="custom-control custom-switch my-0">
                  <input type="checkbox" class="custom-control-input" v-model="row.report_avetmiss" @change="isReport(row,index-1)" :id="`report_avetmiss-${row._id}`">
                  <label class="custom-control-label" :for="`report_avetmiss-${row._id}`"></label>
              </div>
            </template>
            <div class="btn-group" slot="funded_course" slot-scope="{row}">
              <ul v-if="row.funded_course.length > 0" style="list-style-type:none;padding:0;">
                <li v-for="(fc,index) in row.funded_course" :key="index"> 
                  <u v-if="fc.course_code == filter_fields.get_course">
                    <strong>{{fc.course_code}}({{fc.course?fc.course.name:''}})</strong>
                  </u>
                  <template v-else>
                    <strong v-if="fc.course_code == '@@@@'">Unit of Competency</strong>
                    <strong v-else>{{fc.course_code}}({{fc.course?fc.course.name:''}})</strong>
                  </template>
                  <ul>
                    <li>
                      {{fc.status.description}}, {{fc.start_date | dateformat}} - {{fc.end_date | dateformat}}
                    </li>
                  </ul>
                </li>
              </ul>
              <div v-else>
                No Course
              </div>
            </div>
            <div class="btn-group" slot="status" slot-scope="{row}">
              {{row.latest_funded_course.status.description}}
            </div>
            <div class="btn-group" slot="actions" slot-scope="{row}">
              <a href="javascript:void(0)" class="btn btn-primary btn-sm" @click="showStudent(row)">
                <i class="fas fa-edit"></i>
              </a>
              <a
                v-if="urole != 'Staff'"
                href="javascript:void(0)"
                class="btn btn-danger btn-sm text-white"
                @click="deleteStudent(row.id)"
              >
                <i class="fas fa-trash"></i>
              </a>
            </div>
          </v-client-table>
          
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {mapMutations,mapActions} from 'vuex'
import moment from 'moment'
import CreateOfferLetter from "./createOfferLetterComponent.vue";
export default {
  name: "app-modal",
  mounted() {
    // console.log("hello");
  },
  components: {
    CreateOfferLetter,
  },
  props : ['urole'],
  data() {
    return {
      student_list: [],
      app_color: app_color,
      search:null,
      statuses:student_status,
      courses:courses,
      columns: ["student","funded_course", "report", "actions"],
      options: {
        initialPage: 1,
        perPage: 10,
        pagination: {chunk: 5},
        highlightMatches: true,
        sortIcon: {
          base: "fas",
          up: "fa-sort-amount-up",
          down: "fa-sort-amount-down",
          is: "fa-sort",
        },
        headings: {
          type: "Student Type",
          report: "Report",
          funded_course:"Courses",
          // application_type: "Application Type",
          actions: "Actions",
        },
        sortable: ["student_id", "name", "type","latest_course"],
        rowClassCallback(row) {
          return (row.id = row.id);
        },
        columnClasses: { id: "class-is" },
        filterable:false,
        // texts: {
        //   filter: "Search:",
        //   filterPlaceholder: "Search keywords",
        // },
      },
    };
  },
  watch:{
    filter_fields:{
      handler(data){
        if(data.from!=null && data.to !=null){
          if(data.from > data.to){
              swal.fire({
              type: "error",
              title: 'Start Date must be less than End Date.',
              });
              return false;
          }else{
            this.getStudents();
          }
        }else{
          this.getStudents();
        }
      },
      deep:true
    }
  },
  filters:{
    dateformat: function(date) {
      if (!date) return "";
      date = moment(date).format("DD/MM/YYYY");
      return date;
    },
  },
  mounted(){

  },
  created() {
    var date = new Date();
    var firstDay = this.filter_fields.from ==null ? new Date(date.getFullYear(), date.getMonth(), 1) : new Date(this.filter_fields.from);
    var lastDay = this.filter_fields.to ==null ? new Date(date.getFullYear(), date.getMonth() + 1, 0): new Date(this.filter_fields.to);

    this.filter_fields.from = firstDay;
    this.filter_fields.to = lastDay;
    
    this.search = this.filter_fields.search
    
    this.filter_status();
    this.getStudents();
  },
  computed:{
    filter_fields(){
      return this.$store.state.student_filter_fields
    },
  },
  methods: {
    ...mapActions(['addStudentFilterFields']),
    filter_status() {
        // console.log(window.student_status);
        // const st = window.student_status;
        let st = [];
        console.log(this.statuses);
        let count = 0; 
        if(this.filter_fields.student_type == '1'){
            console.log('int');
            Object.entries(window.student_status).forEach((el) => {
                let stats = ['1','2','4','5','7'];
                if(stats.indexOf(el[0]) !== -1){
                    // console.log(el[0]);
                    // this.statuses;
                    st[count] = {
                        key: el[0],
                        value: el[1],
                    };
                    // delete this.statuses[el[0]];
                    count++;
                }
            });
        }else{
            console.log('dom');
            Object.entries(window.student_status).forEach((el) => {
                let stats = ['1','3','4','5','7'];
                if(stats.indexOf(el[0]) !== -1){
                    // console.log(el[0]);
                    st[count] = {
                        key: el[0],
                        value: el[1],
                    };
                    // delete this.statuses[el[0]];
                    count++;
                }
            });
        }
        st[count] = {
            key: '*',
            value: 'All Status',
        };
        this.statuses = st;
        // console.log(st);
        console.log(this.statuses);
    },
    getStudents(load=true){
      let datas = this.filter_fields;
      if(load == true){
        swal.fire({
          title: "Loading Please Wait..",
          // html: '',// add html attribute if you want or remove
          allowOutsideClick: false,
          onBeforeOpen: () => {
            swal.showLoading();
          },
        });
      }
      if(this.search != null && this.search != ''){
        datas.search = this.search;
      }else{
        datas.search =''
      }
      console.log('datas');
      console.log(datas);
      axios.get('/student/getstudents',{
        params:datas
      }).then(
        response=>{
          // console.log(response.data);
          this.student_list = response.data;
          swal.close();
        }
      );
    },
    isReport(row,idx) {
      let update_changes = 1;
      this.student_list[idx].report_avetmiss = !this.student_list[idx].report_avetmiss
      if(row.report_avetmiss == false) {
        if(this.urole=='Staff'){
          swal.fire({
            title: "Unauthorized action",
            type: "error",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Yes!",
          })
          .then((result) => {
            this.student_list[idx].report_avetmiss = true
          })
        }else{  
          swal
          .fire({
            title: "Are you sure?",
            text: "this student will not be included in the reporting!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes!",
          })
          .then((result) => {
            let vm = this;
            if (result.value == true) {
              vm.updateReport(row,idx);
            }else{
              this.student_list[idx].report_avetmiss = true
            }
          });
        }
        
      }else{
        this.updateReport(row,idx);
      }
    },

    updateReport(row,idx) {
      axios({
          method: "get",
          url: `/student/details/report-avetmiss/${row.student_id}/${row.report_avetmiss}`,
        })
        .then((res) => {

          let vm = this;
          console.log(res.data.status);
          if(res.data.status == 'success') {
            Toast.fire({
              type: "success",
              title: "Report status is updated",
              position: "bottom-end",
            });
          }else if (res.data.status == 'error') {
            let errors = res.data.errors
            let html = '<ul style="margin-left: 10% !important;">';
            this.student_list[idx].report_avetmiss = res.data.value == 1 ? true : false;
            if(typeof errors.data !== 'undefined'){
              errors.data.forEach(v => {
                  html += '<li style="text-align:left !important; color: #ff5757 !important;">'+v+'</li>';
              })
            }

            if(typeof errors.courses !== 'undefined'){
              for (var key in errors.courses) {
                  if (errors.courses.hasOwnProperty(key)) {
                      // console.log(key + " -> " + errors.courses[key]);
                      if(key == '@@@@') {
                        html += '<li style="text-align:left !important; color: #ff5757 !important;"><b>Unit of Competency:</b><ul>';
                      }else{
                        html += '<li style="text-align:left !important; color: #ff5757 !important;"><b>'+key+':</b><ul>';
                      }
                      errors.courses[key].forEach(vv => {
                        html += '<li style="text-align:left !important; color: #ff5757 !important;">'+vv+'</li>';
                      })
                      html += '</ul>';
                  }
              }
            }

            html += '</ul>';
      
            swal.fire({
              type: "error",
              title: "Update required fields for Avetmiss",
              html: html,
            });

          }else if (res.data.status == 'warning') {
            // accepted but has errors in other courses
          }
        })
        .catch((err) => console.log(err));
    },

    showCreateOfferLetter() {
      this.$modal.show("size-modal");
    },
    fetchStudents(page_url) {
      let vm = this;
      page_url = page_url || "/student/list";
      fetch(page_url)
        .then((res) => res.json())
        .then((res) => {
          // console.log(res);
          this.student_list = res.data;
        })
        .catch((err) => console.log(err));
    },
    deleteStudent(student) {
      let dis = this;
      const Toast = swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
      });

      swal
        .fire({
          title: "Are you sure ?",
          text: "You wont be able to revert this!",
          type: "warning",
          width: "25%",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          if (result.value) {
            swal.fire({
              title: "Loading Please Wait..",
              // html: '',// add html attribute if you want or remove
              allowOutsideClick: false,
              onBeforeOpen: () => {
                swal.showLoading();
              },
            });
            axios
              .delete(`/student/${student}`)
              .then((response) => {
                // this.$refs.table.refresh();
                // this.fetchStudents();
                dis.getStudents(false);
                Toast.fire({
                  type: "success",
                  title: "You deleted successfuly",
                });
              })
              .catch((error) => {
                Toast.fire({
                  type: "error",
                  title: "Deletion failed",
                });
              });
          }
        });
    },
    // showStudent(student) {
    //   window.location.href = "student/" + student;
    // },
    showStudent(row) {
      // let i = this.student_list.map(item => item._id).indexOf(_id);
      // console.log(_id.type);
      // let capStudent = this.student_list[_id];
      // console.log(capStudent);
      // console.log(row);
      this.addStudentFilterFields(this.filter_fields);
      let dom = "Domestic";
      if (row.type.id === 2) {
        // alert('domestic student ni ghorl');
        window.location.href = "student/domestic/" + row.id;
      } else {
        // alert('international student ni ghorl');
        window.location.href = "student/" + row.id;
      }
    },
    searchStudent: _.debounce(function (e) {
      this.search = e.target.value;
      this.url = `/student/search/${this.search}`;
      let vm = this;
      if (this.search.length > 2) {
        fetch(`/student/search/${this.search}`)
          .then((res) => res.json())
          .then((res) => {
            this.student_list = res.data;
            vm.makePagination(res.meta, res.links);
          })
          .catch((err) => {
            console.log(err);
          });
      } else if (this.search.length == 0) {
        this.fetchStudents();
      }
    }, 300),
  },
};
</script>
<style>
.table #VueTables_th--student{
  width:35% !important;
}
.table #VueTables_th--funded_course{
  width:50% !important;
}
</style>