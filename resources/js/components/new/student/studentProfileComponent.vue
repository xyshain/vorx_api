<template>
    <div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-md-6">
                        <h6 :class="'m-0 font-weight-bold text-' + app_color">
                            Student Profile ( {{student.student_id}} - {{person.firstname}} {{person.middlename}} {{person.lastname}}) : {{student.student_type_id == 1 ? "International" : "Domestic"}}
                            <span>
                            </span>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- accordion -->
                        <div :id="`main-${app_color}`">
                            <!-- <div class="container"> -->
                                <div class="accordion" id="faq">
                                    <!-- Info -->
                                    <div class="mb-2 ofv card">
                                        <div class="card-header" :id="`faqhead0`">
                                            <a href="#" class="p-3 btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq0"
                                            aria-expanded="true" aria-controls="faq0">Information</a>
                                        </div>

                                        <div id="faq0" class="collapse" aria-labelledby="faqhead0" data-parent="#faq">
                                            <div class="card-body">
                                                <!-- tab -->
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" :class="`tab-theme-${app_color}`" id="person-tab" data-toggle="tab" href="#person" role="tab" aria-controls="person" aria-selected="true">
                                                            Personal
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" :class="`tab-theme-${app_color}`" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                                            Contact Details
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" :class="`tab-theme-${app_color}`" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                                                            Visa Details
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" :class="`tab-theme-${app_color}`" id="avetmiss-tab" data-toggle="tab" href="#avetmiss" role="tab" aria-controls="avetmiss" aria-selected="false">
                                                            Avetmiss Details
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content" id="myTabContent">
                                                    <!-- personal information -->
                                                    <div class="tab-pane fade show active" id="person" role="a" aria-labelledby="person-tab">
                                                        <personal-info></personal-info>
                                                    </div>
                                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                        <contact-detail></contact-detail>
                                                    </div>
                                                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                                        <visa-detail></visa-detail>
                                                    </div>
                                                    <div class="tab-pane fade" id="avetmiss" role="tabpanel" aria-labelledby="avetmiss-tab">
                                                        <avetmiss-detail></avetmiss-detail>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Offer Letter -->
                                    <div class="mb-2 ofv card">
                                        <div class="card-header" id="faqhead1">
                                            <a href="#" class="p-3 btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq1"
                                            aria-expanded="true" :aria-controls="`faq1`">Offer Letter</a>
                                        </div>

                                        <div id="faq1" class="collapse" aria-labelledby="faqhead1" data-parent="#faq">
                                            <div class="card-body">
                                                <!-- tab -->
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="ol0-tab" data-toggle="tab" href="#ol0" role="tab" aria-controls="ol0" aria-selected="true">
                                                            Offer Letter # 2
                                                        </a>
                                                    </li>
                                                    <!-- add new offer letter -->
                                                    <li class="nav-item">
                                                        <a class="nav-link point" id="ol-plus-tab" data-toggle="tooltip" title="Add New Offer Letter" data-placement="right">
                                                            <i class="fas fa-plus" :class="`text-${app_color}`"></i>
                                                        </a>
                                                    </li>
                                                    
                                                </ul>
                                                <div class="tab-content" id="myTabContent">
                                                    <!-- offer letter body -->
                                                    <div class="tab-pane fade show active" id="ol0" role="a" aria-labelledby="ol0-tab">
                                                        ++
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Course -->
                                    <div class="mb-2 ofv card">
                                        <div class="card-header" id="faqhead2">
                                            <a href="#" class="p-3 btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq2"
                                            aria-expanded="true" :aria-controls="`faq2`">Course</a>
                                        </div>

                                        <div id="faq2" class="collapse" aria-labelledby="faqhead2" data-parent="#faq">
                                            <div class="card-body pt-1 pb-2 px-2">
                                                <courses />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Notes -->
                                    <div class="mb-2 ofv card">
                                        <div class="card-header" id="faqhead3">
                                            <a href="#" class="p-3 btn btn-header-link collapsed" data-toggle="collapse" data-target="#faq3"
                                            aria-expanded="true" aria-controls="faq3">Notes / Attachments</a>
                                        </div>

                                        <div id="faq3" class="collapse" aria-labelledby="faqhead3" data-parent="#faq">
                                            <div class="card-body">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import Vue from 'vue'
import Vuex from 'vuex'
import moment from 'moment'

const store = new Vuex.Store({
    state : {
        firstLoad : 0,
        student : window.student,
        student_detail : window.student_details ? window.student_details : {},
        party: {},
        person: {},
        contact: {},
        visa: {},
        courses : [],
        courseCompletions: [],
    },
    mutations : {
        getCourses(state, payload) {
            state.courses = payload;
        },
        getCourseCompletions(state, payload) {
            state.courseCompletions = payload;
        }
    },
    actions : {
        async fetchCourses(state, payload) {
            var result = await axios.get(`/student-profile/course/${payload}`);
            state.commit('getCourses', result.data);
        },
        async fetchCourseCompletions(state, payload) {
            var result = await axios.post('/student-profile/fetch-course-units', {student_id:payload.student_id, course_code:payload.courseCode})
            state.commit('getCourseCompletions', result.data);
        }
    },
    getters : {},

})

import PersonalInfo from "./Info/personalComponent.vue";
import ContactDetail from "./Info/contactDetailComponent.vue";
import VisaDetail from "./Info/visaDetailComponent.vue";
import AvetmissDetail from "./Info/avetmissDetailComponent.vue";
import Courses from "./Course/courseComponent.vue";

export default {
   store,
   components: {
       PersonalInfo,
       ContactDetail,
       VisaDetail,
       AvetmissDetail,
       Courses,
   },
   data() {
       return {
           collapse: ['Information', 'Enrolment', 'Notes'],
           app_color: app_color,
       }
   },
   created() {
       if(!this.firstLoad) {
           this.firstLoading();
       }

   },
   computed: {
       person() {
           return this.$store.state.person;
       },
       student() {
           return this.$store.state.student;
       },
       firstLoad: {
           get() {
               return this.$store.state.firstLoad;
           },
           set() {
               return this.$store.state.firstLoad;
           }
           
       }
   },
   methods: {
       async firstLoading() {
            swal.fire({
                title: `Loading Details...`,
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    swal.showLoading();
                }
            })
            // assign data to state variables 
            this.$store.state.party = this.student ? this.student.party : {},
            this.$store.state.person = this.student ? this.student.party.person : {},
            this.$store.state.contact = this.student ? this.student.contact_detail : {},
            this.$store.state.visa = this.student ? this.student.visa_details : {},

            // Courses
            console.log(this.student.student_id)
            await this.$store.dispatch('fetchCourses', this.student.student_id);

           this.firstLoad = 1;
           swal.close();
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