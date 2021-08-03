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
            <div class="row no-gutters">
                <!-- Language and Cultural Diversity -->
                <div class="col-md-6">
                    <div class="card m-1">
                        <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                            Language and Cultural Diversity
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="country">Country of Birth
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
                                        <multiselect
                                        v-model="avetmiss.country"
                                        :options="slct_country"
                                        :multiple="false"
                                        placeholder="Type to search"
                                        :close-on-select="true"
                                        track-by="identifier"
                                        label="full_name"
                                        >
                                            <span slot="noResult">Oops! No units found. Consider changing the search query.</span>
                                        </multiselect> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="language_id">Language Identifier
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
                                        <select v-model="avetmiss.language_id" class="form-control">
                                            <option v-for="(i, k) in slct_lang_identifer" :key="i" :value="i">{{k}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="indigenous_status_id">Indigenous Status
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
                                        <select v-model="avetmiss.indigenous_status_id" class="form-control">
                                            <option v-for="(i, k) in slct_indigenous_status" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="location">State
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
                                        <select v-model="avetmiss.location" class="form-control">
                                            <option v-for="(i, k) in slct_state" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Schooling -->
                <div class="col-md-6">
                    <div class="card m-1">
                        <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                            Schooling & Employment
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="highest_school_level_completed_id">School Level Completed
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
                                        <select v-model="avetmiss.highest_school_level_completed_id" class="form-control">
                                            <option v-for="(i, k) in slct_schl_lvl_completed" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="at_school_flag">At School
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
                                        <select v-model="avetmiss.at_school_flag" class="form-control">
                                            <option value="Y">YES</option>
                                            <option value="N">NO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="year_completed">Year Completed
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
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="avetmiss.year_completed"
                                        id="year_completed"
                                    />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="institute">Institute</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="avetmiss.institute"
                                        id="institute"
                                    />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="labour_force_status_id">Labour Force Status
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
                                        <select v-model="avetmiss.labour_force_status_id" class="form-control">
                                            <option v-for="(i, k) in slct_labour_force_status" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters my-2">
                <!-- Previous Qualifications Achieved -->
                <div class="col-md-6">
                    <div class="card m-1">
                        <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                           Previous Qualifications Achieved
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="prior_educational_achievement_flag">Prior Education Achievement
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
                                        <select v-model="avetmiss.prior_educational_achievement_flag" class="form-control">
                                            <option value="Y">YES</option>
                                            <option value="N">NO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="prior_educational_achievement_ids">Prior Education Achievement Identifier
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
                                        <multiselect
                                        :class="'multiselect-color-'+app_color"
                                        v-model="avetmiss.prior_educational_achievement_ids"
                                        :options="slct_educ_achievement"
                                        :multiple="true"
                                        placeholder="Type to search"
                                        :close-on-select="false"
                                        track-by="id"
                                        label="value"
                                        >
                                        <span slot="noResult">Oops! No units found. Consider changing the search query.</span>
                                        </multiselect>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Disability  -->
                <div class="col-md-6">
                    <div class="card m-1">
                        <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                            Disability
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="disability_flag">Disability
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
                                        <select v-model="avetmiss.disability_flag" class="form-control">
                                            <option value="Y">YES</option>
                                            <option value="N">NO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="disability_ids">Disability Identifier
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
                                        <multiselect
                                        :class="'multiselect-color-'+app_color"
                                        v-model="avetmiss.disability_ids"
                                        :options="slct_disability"
                                        :multiple="true"
                                        placeholder="Type to search"
                                        :close-on-select="false"
                                        track-by="id"
                                        label="value"
                                        >
                                        <span slot="noResult">Oops! No units found. Consider changing the search query.</span>
                                        </multiselect>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters my-2">
                <!-- Survey Contact & Statistical Area  -->
                <div class="col-md-12">
                    <div class="card m-1">
                        <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                            Survey Contact & Statistical Area
                        </div>
                        <div class="card-body py-2 px-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="survey_contact_status">Survey Contact Status
                                        </label>
                                        <select v-model="avetmiss.survey_contact_status" class="form-control">
                                            <option v-for="(i, k) in slct_survey_contact_status" :key="k" :value="k">{{i}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="statistical_area_level_1_id">Statistical Area Level 1</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="avetmiss.statistical_area_level_1_id"
                                            id="emer_address"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="statistical_area_level_2_id">Statistical Area Level 2</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="avetmiss.statistical_area_level_2_id"
                                            id="emer_telephone"
                                        />
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
        // selections
        slct_educ_achievement : window.slct_educ_achievement,
        slct_disability : window.slct_disability,
        slct_country : window.slct_country,
        slc_funding_type : window.slc_funding_type,
        slct_study_reason : window.slct_study_reason,
        slct_state : window.slct_state,
        slct_schl_lvl_completed : window.slct_schl_lvl_completed,
        slct_indigenous_status : window.slct_indigenous_status,
        slct_labour_force_status : window.slct_labour_force_status,
        slct_lang_identifer : window.slct_lang_identifer,
        slct_survey_contact_status : window.slct_survey_contact_status,
    };
  },
  computed : {
      avetmiss() {
          return this.$store.state.student_detail;
      },
      contact() {
        let con = this.$store.state.contact
        if(con.state){
            // console.log('sulod?')
            let post_sub_state = `${con.postcode} - ${con.addr_suburb}, ${con.state.state_key}`
            let pss = addr_suburbs.filter(data => data.value == post_sub_state);
            this.post_sub_state = pss[0];
            con.addr_suburb = pss[0];
        }
        return con
      },
      student() {
        //   console.log(this.$store.state.party)
        return this.$store.state.student
      }
  },
  methods: {
    saveChanges() {
        swal.fire({
            title: "Saving Changes...",
            // html: '',// add html attribute if you want or remove
            allowOutsideClick: false,
            onBeforeOpen: () => {
            swal.showLoading();
            },
        });

        axios.post(`/student/domestic/${student.id}/avetmiss-update`, {inputs:this.avetmiss})
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

  },
  watch: {},
};
</script>
<style scoped>
    
</style>
<style>
</style>