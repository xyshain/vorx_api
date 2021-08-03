<template>
    <div>
        <create-course></create-course>
        <div class="row no-gutters">
            <!-- <div class="col-md-12 text-right mb-2">
                <button class="btn btn-sm btn-success"> <i class="fas fa-plus"></i> Course</button>
            </div> -->
            <div class="col-md-12 ">
                <!-- add new course -->
                <div class="card background-success px-2 text-center mt-1 mb-2">
                    <span class="point" @click="cc"><i class="fas fa-plus-circle mr-1"></i> Add new Course</span>
                </div>
            </div>
            <div class="col-md-12">
                <!-- accordion -->
                <div :id="`main-${app_color}`">
                    <!-- <div class="container"> -->
                        <div class="accordion" :id="`Cor`">
                            <!-- Course -->
                            <div class="mb-2 ofv card" v-for="(item,key) in courses" :key="key">
                                <div class="card-header" :id="`Corhead${key}`">
                                    <a href="#" class="p-3 btn btn-header-link collapsed" data-toggle="collapse" :data-target="`#Cor${key}`"
                                    aria-expanded="true" :aria-controls="`Cor${key}`">
                                        <span v-if="item.course_code != '@@@@'">
                                            {{item.course.code}} - {{item.course.name}} ({{item.status.description}})
                                        </span>
                                        <span v-else>
                                            Unit of Competency {{item.uc_description ? `- ${item.uc_description}` : ''}} ({{item.status.description}})
                                        </span>
                                    </a>
                                </div>

                                <div :id="`Cor${key}`" class="collapse" :aria-labelledby="`Corhead${key}`" :data-parent="`#Cor`">
                                    <div class="px-2 py-3">
                                        <!-- tab -->
                                        <ul class="nav nav-tabs" :id="`myTab${key}`" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" :class="`tab-theme-${app_color}`" :id="`cd-tab${key}`" data-toggle="tab" :href="`#cd${key}`" role="tab" :aria-controls="`cd${key}`" aria-selected="true">
                                                   <i class="fas fa-info-circle"></i> Details
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" :class="`tab-theme-${app_color}`" :id="`cu-tab${key}`" data-toggle="tab" :href="`#cu${key}`" role="tab" :aria-controls="`cu${key}`" aria-selected="true">
                                                    <i class="fas fa-stream"></i> Units
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" :class="`tab-theme-${app_color}`" :id="`cp-tab${key}`" data-toggle="tab" :href="`#cp${key}`" role="tab" :aria-controls="`cp${key}`" aria-selected="true">
                                                    <i class="fas fa-hand-holding-usd"></i> Payments
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" :class="`tab-theme-${app_color}`" :id="`ci-tab${key}`" data-toggle="tab" :href="`#ci${key}`" role="tab" :aria-controls="`ci${key}`" aria-selected="true">
                                                    <i class="fas fa-certificate"></i> Issuance
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" :class="`tab-theme-${app_color}`" :id="`cw-tab${key}`" data-toggle="tab" :href="`#cw${key}`" role="tab" :aria-controls="`cw${key}`" aria-selected="true">
                                                    <i class="fas fa-book"></i> Workbook
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" :id="`myTab${key}Content`">
                                            <!-- Course Details -->
                                            <div class="tab-pane fade show active" :id="`cd${key}`" role="a" :aria-labelledby="`cd-tab${key}`">
                                                <course-detail :courseId="item.id"></course-detail>
                                            </div>
                                            <!-- Course Units -->
                                            <div class="tab-pane fade" :id="`cu${key}`" role="a" :aria-labelledby="`cu-tab${key}`">
                                                <course-unit :courseId="item.id" :courseCompletion="item.cc" :courseCode="item.course_code" :deliveryLocations="item.delivery_locations"></course-unit>
                                            </div>
                                            <!-- Course Payments -->
                                            <div class="tab-pane fade" :id="`cp${key}`" role="a" :aria-labelledby="`cp-tab${key}`">
                                                course payments
                                            </div>
                                            <!-- Course Issuance -->
                                            <div class="tab-pane fade" :id="`ci${key}`" role="a" :aria-labelledby="`ci-tab${key}`">
                                                <course-issuance :courseId="item.id"></course-issuance>
                                            </div>
                                            <!-- Course Workbook -->
                                            <div class="tab-pane fade" :id="`cw${key}`" role="a" :aria-labelledby="`cw-tab${key}`">
                                                course workbook
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment'
import CourseDetail from "./detailComponent.vue";
import CourseUnit from "./unitComponent.vue";
import CourseIssuance from "./issuanceComponent.vue";
import CreateCourse from "./createCourseComponent.vue";
export default {
   components: {
       CourseDetail,
       CourseUnit,
       CourseIssuance,
       CreateCourse,
   },
   data() {
       return {
           app_color: app_color
       }
   },
   created() {

   },
   computed: {
       student() {
           return this.$store.state.student;
       },
       courses() {
           return this.$store.state.courses;
       }
   },
   methods: {
       cc() {
           this.$modal.show('size-modal-cc');
       },
       async fetchStudentCourses(load = 0) {
           if(load == 1) {
               swal.fire({
                    title: `Fetching Courses...`,
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        swal.showLoading()
                    },
                });
           }
           // Courses
            await this.$store.dispatch('fetchCourses', this.student.student_id);
            
            if(load == 1) {
               swal.close();
            }
       }
   }
}
</script>
<style scoped>
    /* tab - navs */
    a.nav-link {
        padding: 0px 10px !important;
    }

    .point {
        cursor: pointer;
    }

    .ofv {
        overflow: visible !important;
    }
</style>

<style>
    .portal-forms .card {
        height: 100% !important;
        border: none !important;
    }
    .portal-forms .card-body {
        background: #eef0f791 !important;
    }
    .portal-forms .form-group {
        margin-bottom: .5rem;
    }
</style>