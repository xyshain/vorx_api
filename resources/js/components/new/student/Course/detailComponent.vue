<template>
  <div class="portal-forms">
      <form @submit.prevent autocomplete="nope">
        <div>
            <div class="row">
                <div class="col-md-6 mb-1">
                    <button class="btn btn-danger btn-sm" :disabled="roles == 'Staff'? true : false ">
                    <i class="fas fa-trash-alt"></i> Delete Course
                    </button>
                </div>
                <div class="col-md-6 text-right mb-1">
                    <button class="btn btn-success btn-sm" :disabled="roles == 'Staff'? true : false " @click="saveChanges()">
                    <i class="far fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
            <div class="row no-gutters my-2">
                <!-- Details -->
                <div class="col-md-12">
                    <div class="card m-1">
                        <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                            Details
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="location">Location
                                        </label>
                                        <select v-model="course.location" class="form-control">
                                            <option v-for="(i, k) in slct_state" :key="k" :value="i.state_key">{{i.state_name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="eligibility">Eligibility</label>
                                        <select v-model="course.eligibility" class="form-control">
                                            <option value="E">Eligible</option>
                                            <option value="NE">Not Eligible</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="course_fee_type">Course Fee Type</label>
                                        <select v-model="course.course_fee_type" class="form-control">
                                            <option value="C">Concessional</option>
                                            <option value="NC">Government Funded</option>
                                            <option value="FF">Fee for Service</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="course_fee">Course Fee</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="course.course_fee"
                                        />
                                    </div>
                                </div>
                                <!-- class, start, end, status -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="course_class">Class</label>
                                        <select v-model="course.class_id" class="form-control">
                                            <option :value="null">No Class</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="course_class">Start Date <span style="font-size: 74%; opacity: 0.73;">( DD/MM/YYYY )</span></label>
                                        <date-picker
                                            locale="en"
                                            mode="date"
                                            v-model="course.start_date"
                                            :masks="{L:'DD/MM/YYYY'}"
                                            :input-props='{
                                            autocomplete:"off",
                                            }'
                                        ></date-picker>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="course_class">End Date <span style="font-size: 74%; opacity: 0.73;">( DD/MM/YYYY )</span></label>
                                        <date-picker
                                            locale="en"
                                            mode="date"
                                            v-model="course.end_date"
                                            :masks="{L:'DD/MM/YYYY'}"
                                            :input-props='{
                                            autocomplete:"off",
                                            }'
                                        ></date-picker>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="course_class">Status</label>
                                        <select v-model="course.status_id" class="form-control">
                                            <option v-for="(i, k) in slct_student_status" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- aiss date, agent, remarks -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="course_class">AISS <span style="font-size: 74%; opacity: 0.73;">( DD/MM/YYYY )</span></label>
                                        <date-picker
                                            locale="en"
                                            mode="date"
                                            v-model="course.aiss_date"
                                            :masks="{L:'DD/MM/YYYY'}"
                                            :input-props='{
                                            autocomplete:"off",
                                            }'
                                        ></date-picker>
                                    </div>
                                </div>
                                <div class="col-md-3" v-if="course.course">
                                    <div class="form-group">
                                        <label for="course_class">Agent</label>
                                        <select v-model="course.agent_id" class="form-control">
                                            <option :value="null"> No Agent</option>
                                            <option v-for="(i, k) in slct_agents" :key="k" :value="i.agent_detail.id">{{i.agent_detail.agent_name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div :class="course.course ? 'col-md-6': 'col-md-9'">
                                    <div class="form-group">
                                        <label for="course_class">Remarks</label>
                                        <input type="text" class="form-control" v-model="course.remarks">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters">
                <!-- Funding -->
                <div class="col-md-6">
                    <div class="card m-1">
                        <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                            Funding
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Funding Template</label>
                                        <select v-model="course.detail.funding_type_detail" @change="getFundTemplate(course.detail.funding_type_detail)" class="form-control">
                                            <option :value="null">No Template</option>
                                            <option v-for="(i, k) in slct_funding_type" :key="k" :value="i">{{i.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Funding Source National
                                            <a
                                                href="#"
                                                data-toggle="tooltip"
                                                :class="'a-'+app_color"
                                                title="Avetmiss required!"
                                                data-placement="right"
                                            >
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </label>
                                        <select :disabled="course.detail.funding_type ? true : false" v-model="course.detail.funding_source_national" class="form-control">
                                            <option v-for="(i, k) in slct_funding_source_national" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Funding Source State
                                            <a
                                                href="#"
                                                data-toggle="tooltip"
                                                :class="'a-'+app_color"
                                                title="Avetmiss required!"
                                                data-placement="right"
                                            >
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </label>
                                        <select :disabled="course.detail.funding_type ? true : false" v-model="course.detail.funding_source_state_training_authority" class="form-control">
                                            <option v-for="(i, k) in slct_funding_source_state" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Specific Funding</label>
                                        <select :disabled="course.detail.funding_type ? true : false" v-model="course.detail.specific_funding_id" class="form-control">
                                            <option v-for="(i, k) in slct_specific_fund" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Training Contract</label>
                                        <input :disabled="course.detail.funding_type ? true : false" type="text" v-model="course.detail.training_contract_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Purchasing Contract</label>
                                        <input :disabled="course.detail.funding_type ? true : false" type="text" v-model="course.detail.purchasing_contract_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="course_class">Purchasing Contract Schedule</label>
                                        <input :disabled="course.detail.funding_type ? true : false" type="text" v-model="course.detail.purchasing_contract_schedule_id" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Enrolment -->
                <div class="col-md-6">
                    <div class="card m-1">
                        <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                            Enrolment
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Commencing Course
                                            <a
                                                href="#"
                                                data-toggle="tooltip"
                                                :class="'a-'+app_color"
                                                title="Avetmiss required!"
                                                data-placement="right"
                                            >
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </label>
                                        <select v-model="course.detail.commence_prg_identifier" class="form-control">
                                            <option v-for="(i, k) in slct_com_course" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Client ID Apprenticeships</label>
                                        <input type="text" v-model="course.detail.client_id_apprenticeships" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Study Reason
                                            <a
                                                href="#"
                                                data-toggle="tooltip"
                                                :class="'a-'+app_color"
                                                title="Avetmiss required!"
                                                data-placement="right"
                                            >
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </label>
                                        <select v-model="course.detail.study_reason_id" class="form-control">
                                            <option v-for="(i, k) in slct_study_reason" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Associated Course</label>
                                        <input type="text" v-model="course.detail.associated_course_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Predominant Delivery
                                            <a
                                                href="#"
                                                data-toggle="tooltip"
                                                :class="'a-'+app_color"
                                                title="Avetmiss required!"
                                                data-placement="right"
                                            >
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </label>
                                        <select v-model="course.detail.predominant_delivery_mode" class="form-control">
                                            <option v-for="(i, k) in slct_predom_dlvr_mode" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="course_class">Full-time Learning Option</label>
                                        <input type="text" v-model="course.detail.full_time_leaning_option" class="form-control">
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
  props: ['courseId'],
  mounted() {
      
  },
  created() {
    //   console.log(this.courseId);
    this.fetchFundingType();
  },
  data() {
    return {
        app_color: app_color,
        add_on: add_on,
        roles : null,
        funding_type : {},
        // selections
        slct_state : window.slct_state,
        slct_student_status : window.slct_student_status,
        slct_agents : [],
        slct_predom_dlvr_mode : window.slct_predom_dlvr_mode,
        slct_funding_source_state : window.slct_funding_source_state,
        slct_funding_source_national : window.slct_funding_source_national,
        slct_com_course : window.slct_com_course,
        slct_study_reason : window.slct_study_reason,
        slct_specific_fund : window.slct_specific_fund,
        slct_funding_type : [],
    };
  },
  computed : {
      course() {
        let courses = this.$store.state.courses;
        let c = courses.filter(data => data.id == this.courseId);
        c[0].start_date = c[0].start_date ? moment(c[0].start_date)._d : null
        c[0].end_date = c[0].end_date ? moment(c[0].end_date)._d : null
        c[0].aiss_date = c[0].aiss_date ? moment(c[0].aiss_date)._d : null

        // get agent selection
        if(c[0].course) {
            this.slct_agents = c[0].course.agent_commission_setting_mains ? c[0].course.agent_commission_setting_mains : [];
        }

        // get course locations
        // console.log(c[0])
        this.slct_state = c[0].states

        return c[0]; 
      },
      student() {
        //   console.log(this.$store.state.party)
        return this.$store.state.student
      }
  },
  methods: {
    getFundTemplate(data) {
        if(data) {
            this.course.detail.specific_funding_id = data.specific_funding_code
            this.course.detail.funding_source_state_training_authority = data.state_funding_code
            this.course.detail.funding_source_national = data.national_funding_code
            this.course.detail.training_contract_id = data.training_contract
            this.course.detail.purchasing_contract_id = data.purchasing_contract
            this.course.detail.purchasing_contract_schedule_id = data.purchasing_schedule
            this.course.detail.funding_type = data.id
        }else {
            this.course.detail.funding_type = null
        }
    },
    fetchFundingType(load = 0) {
        if(load == 1) {
            swal.fire({
                title: "Fetching Funding Templates...",
                // html: '',// add html attribute if you want or remove
                allowOutsideClick: false,
                onBeforeOpen: () => {
                swal.showLoading();
                },
            });
        }
        axios.get(`/student-profile/fetch-funding/${this.course.location}`)
        .then( res => {
            this.slct_funding_type = res.data
        })


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

        axios.post(`/student-profile/course`, this.course)
        .then(res => {
            if(res.data.status == 'updated') {
                // swal.fire(
                //     'Success!',
                //     'Course Details updates successfully',
                //     'success',
                // )
                Toast.fire({
                    type: "success",
                    title: `Course Details updates successfully`,
                    position: 'bottom-right'
                });
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

  },
  watch: {},
};
</script>
<style scoped>
    
</style>
<style>
</style>