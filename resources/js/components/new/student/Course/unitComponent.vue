<template>
    <div>
        <!-- <div class="row no-gutters mb-2">
            <div class="col-md-3">
                <select class="form-control">
                    <option value="ps">Proposed Settings</option>
                    <option value="ps">Course Progress</option>
                </select>
            </div>
        </div> -->
        <div class="row no-gutters mb-1">

            <div class="col-md-4 text-left">
                <!-- dropdown button -->
                <div class="btn-group">
                    <button
                        type="button"
                        class="btn dropdown-toggle dropdown-toggle-split"
                        :class="[`btn-info`]"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span>
                            <i class="fas fa-file-pdf mr-1"></i> Generate PDF
                        </span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-left">
                        <a class="dropdown-item" type="button" :href="`/training_plan/${student.student_id}/${courseId}`" target="_blank">
                            <i class="fas fa-list mr-1" :class="[`text-info`]"></i>&nbsp; Training Plan
                        </a>
                        <a class="dropdown-item" type="button" :href="`/certificate_issuance/generate/progress_report/${student.student_id}/${course.id}`" target="_blank">
                            <i class="fas fa-tasks mr-1" :class="[`text-info`]"></i>&nbsp; Progress Report
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8 text-right">
                <!-- use proposed dates button -->
                <button @click="useProposedDates" v-if="instance.val != 'proposed_settings'" class="btn btn-warning"><i class="fas fa-sync-alt mr-1"></i> Use Proposed Dates</button>

                <!-- dropdown button -->
                <div class="btn-group">
                    <button
                        type="button"
                        class="btn dropdown-toggle dropdown-toggle-split"
                        :class="[instance.val == 'proposed_settings' ? `btn-${app_color}` : 'btn-info']"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <span v-if="instance.val == 'proposed_settings'">
                            <i class="fas fa-list mr-1"></i> {{instance.lable}}
                        </span>
                        <span v-else>
                            <i class="fas fa-poll mr-1"></i> {{instance.lable}}
                        </span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" type="button" @click="changeInstance('proposed_settings')">
                            <!-- <i class="far fa-address-card text-info"></i>&nbsp; Proposed Settings -->
                            <i class="fas fa-list text-warning mr-1"></i>&nbsp; Proposed Settings
                        </a>
                        <a class="dropdown-item" type="button" @click="changeInstance('course_progress')">
                            <!-- <i class="far fa-file-pdf text-danger"></i>&nbsp; Course Progress -->
                            <i class="fas fa-poll text-primary"></i>&nbsp; Course Progress
                        </a>
                    </div>
                </div>
                <!-- save button -->
                <button @click="saveChanges" class="btn btn-success"><i class="far fa-save mr-1"></i> Save Changes</button>
            </div>

        </div>
        <div class="table-responsive">
            <!-- proposed settings -->
            <table class="table table-striped" v-if="instance.val == 'proposed_settings'">
                <thead>
                    <tr>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">#</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">Code</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells" style="width:20%">Description</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">P. Start</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">P. End</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">Delivery Location</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">Delivery Mode</th>
                        <!-- <th :class="` table-header-${app_color}`" class="text-center">A. Start</th>
                        <th :class="` table-header-${app_color}`" class="text-center">A. End</th>
                        <th :class="` table-header-${app_color}`" class="text-center">Status</th> -->
                    </tr>
                </thead>
                <tbody v-if="units">
                    <tr v-for="(i,k) in units" :key="k">
                        <td class="p-2 cells align-middle text-center">{{k + 1}}</td>
                        <td class="p-2 cells align-middle text-center">{{i.course_unit_code}}</td>
                        <td class="p-2 cells align-middle text-center">{{i.unit.description}}</td>
                        <td class="p-2 cells align-middle text-center">
                            <date-picker
                                v-if="k == 0"
                                locale="en"
                                mode="date"
                                v-model="i.start_date"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                                @dayclick="autoColumn({label:'P. Start', variable: 'start_date', key: k})"
                            >
                            </date-picker>
                            <date-picker
                                v-else
                                locale="en"
                                mode="date"
                                v-model="i.start_date"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                            ></date-picker>
                        </td>
                        <td class="p-2 cells align-middle text-center">
                            <date-picker
                                v-if="k == 0"
                                locale="en"
                                mode="date"
                                v-model="i.end_date"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                                @dayclick="autoColumn({label:'P. End', variable: 'end_date', key: k})"
                            >
                            </date-picker>
                            
                            <date-picker
                                v-else
                                locale="en"
                                mode="date"
                                v-model="i.end_date"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                            ></date-picker>
                        </td>
                        <td class="p-2 cells align-middle text-center">
                            <select class="form-control" v-model="i.train_org_loc_id" style="font-size:12px" @change="autoColumn({label:'Delivery Location', variable: 'train_org_loc_id', key: k})" v-if="k == 0">
                                <option v-for="(ii,kk) in deliveryLocations" :key="kk" :value="ii.train_org_dlvr_loc_id">{{ii.train_org_dlvr_loc_name}}</option>
                            </select>
                            <select class="form-control" v-model="i.train_org_loc_id" style="font-size:12px" v-else>
                                <option v-for="(ii,kk) in deliveryLocations" :key="kk" :value="ii.train_org_dlvr_loc_id">{{ii.train_org_dlvr_loc_name}}</option>
                            </select>
                        </td>
                        <td class="p-2 cells align-middle text-center">
                            <select class="form-control" style="font-size:12px" v-model="i.delivery_mode_id" @change="autoColumn({label:'Delivery Mode', variable: 'delivery_mode_id', key: k})" v-if="k == 0">
                                <option v-for="(ii,kk) in slct_dlvr_mode" :key="kk" :value="kk">{{ii}}</option>
                            </select>
                            <select v-else class="form-control" style="font-size:12px" v-model="i.delivery_mode_id">
                                <option v-for="(ii,kk) in slct_dlvr_mode" :key="kk" :value="kk">{{ii}}</option>
                            </select>
                        </td>
                        <!-- <td class="p-3 cells text-center"></td>
                        <td class="p-3 cells text-center"></td>
                        <td class="p-3 cells text-center"></td> -->
                    </tr>
                    
                </tbody>
            </table>
            <!-- course progress -->
            <table class="table table-striped" v-if="instance.val == 'course_progress'">
                <thead>
                    <tr>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">#</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">Code</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells" style="width:20%">Description</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">A. Start</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">A. End</th>
                        <th :class="` table-header-${app_color}`" class="text-center py-1 cells">Outcome</th>
                        <!-- <th :class="` table-header-${app_color}`" class="text-center">A. Start</th>
                        <th :class="` table-header-${app_color}`" class="text-center">A. End</th>
                        <th :class="` table-header-${app_color}`" class="text-center">Status</th> -->
                    </tr>
                </thead>
                <tbody v-if="units">
                    <tr v-for="(i,k) in units" :key="k">
                        <td class="p-2 cells align-middle text-center">{{k + 1}}</td>
                        <td class="p-2 cells align-middle text-center">{{i.course_unit_code}}</td>
                        <td class="p-2 cells align-middle text-center">{{i.unit.description}}</td>
                        <td class="p-2 cells align-middle text-center">
                            <!-- <date-picker
                                locale="en"
                                mode="date"
                                v-model="i.actual_start"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                            ></date-picker> -->
                            <date-picker
                                v-if="k == 0"
                                locale="en"
                                mode="date"
                                v-model="i.actual_start"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                                @dayclick="autoColumn({label:'A. Start', variable: 'actual_start', key: k})"
                            >
                            </date-picker>
                            <date-picker
                                v-else
                                locale="en"
                                mode="date"
                                v-model="i.actual_start"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                            ></date-picker>
                        </td>
                        <td class="p-2 cells align-middle text-center">
                            <!-- <date-picker
                                locale="en"
                                mode="date"
                                v-model="i.actual_start"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                            ></date-picker> -->
                            <date-picker
                                v-if="k == 0"
                                locale="en"
                                mode="date"
                                v-model="i.actual_end"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                                @dayclick="autoColumn({label:'A. End', variable: 'actual_end', key: k})"
                            >
                            </date-picker>
                            <date-picker
                                v-else
                                locale="en"
                                mode="date"
                                v-model="i.actual_end"
                                :masks="{L:'DD/MM/YYYY'}"
                                :input-props='{
                                autocomplete:"off",
                                }'
                            ></date-picker>
                        </td>
                        <td class="p-2 cells align-middle text-center">
                            <select v-model="i.completion_status_id" class="form-control" style="font-size:12px" @change="autoColumn({label:'Outcome', variable: 'completion_status_id', key: k})" v-if="k == 0">
                                <option v-for="(ii, kk) in slct_outcome_status" :key="kk" :value="kk">{{ii}}</option>
                            </select>
                            <select v-model="i.completion_status_id" class="form-control" style="font-size:12px" v-else>
                                <option v-for="(ii, kk) in slct_outcome_status" :key="kk" :value="kk">{{ii}}</option>
                            </select>
                        </td>
                        <!-- <td class="p-3 cells text-center"></td>
                        <td class="p-3 cells text-center"></td>
                        <td class="p-3 cells text-center"></td> -->
                    </tr>
                    
                </tbody>
            </table>

        </div>
    </div>
</template>
<script>
import moment from "moment";
export default {
    components: {
    },
    props: ['courseId','courseCode', 'deliveryLocations', 'courseCompletion'],
    mounted() {
      
    },
    created() {
        //   console.log(this.courseId);
        this.fetchCourseUnits();
    },
    data() {
        return {
            app_color: app_color,
            add_on: add_on,
            firstLoad: 0,
            roles : null,
            date : {
                start : null,
                end : null,
            },

            units: [],

            instance : {
                val : 'proposed_settings',
                lable : 'Proposed Settings',
            },
            // course : {},
            getAutoColumn : {},

            // selections
            slct_dlvr_mode : window.slct_dlvr_mode,
            slct_outcome_status : window.slct_outcome_status,
        };
    },
    computed : {
        student() {
            return this.$store.state.student
        },
        course: {
            get() {
                let courses = this.$store.state.courses;
                let c = courses.filter(data => data.id == this.courseId);

                let u = []
                // console.log(this.firstLoad)
                if(c[0].cc.details){
                    for (let i = 0; i < c[0].cc.details.length; i++) {
                        const el = c[0].cc.details[i];
                        if(this.firstLoad == 0) {
                            el.start_date = moment(el.start_date)._d
                            el.end_date = moment(el.end_date)._d
                            el.actual_start = moment(el.actual_start)._d
                            el.actual_end = moment(el.actual_end)._d
                        }
                        u.push(el)
                    }
                    // this.firstLoad = 1
                }
                this.units = u;

                return c[0];
            },
            set() {
            }
        },
        // units: {
        //     get() {
        //         console.log('test');
        //         let u = []
        //         if(this.courseCompletion.details){
        //             for (let i = 0; i < this.courseCompletion.details.length; i++) {
        //                 const el = this.courseCompletion.details[i];
        //                 if(this.firstLoad == 0) {
        //                     el.start_date = moment(el.start_date)._d
        //                     el.end_date = moment(el.end_date)._d
        //                     el.actual_start = moment(el.actual_start)._d
        //                     el.actual_end = moment(el.actual_end)._d
        //                 }
        //                 u.push(el)
        //             }
        //             this.firstLoad = 1
        //         }
        //         return u
        //     },
        //     set() {
                
        //     }
        // }
    },
    methods: {
        customFormatter(date) {
            return moment(date).format('DD/MM/YYYY');
        },
        useProposedDates() {

            swal.fire({
                title: `Use Proposed Dates to Actual Dates?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            })
            .then(result => {
                if (result.value) {
                    // alert(newDate)
                    // let u = [];
                    for (let i = 0; i < this.units.length; i++) {
                        const el = this.units[i];
                        el['actual_start'] = el['start_date'];
                        el['actual_end'] = el['end_date'];
                    }
                    // this.units = [];
                    // this.units = u;
                }
            });

        },
        autoColumn(data) {
            
            if(!this.units[data.key][data.variable]) {
                return false;
            }

            swal.fire({
                title: `Autofill Column (${data.label})?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            })
            .then(result => {
                if (result.value) {
                    // console.log(data)
                    let newDate = this.units[data.key][data.variable];
                    // alert(newDate)
                    // let u = [];
                    for (let i = 0; i < this.units.length; i++) {
                        const el = this.units[i];
                        el[data.variable] = newDate;
                    }
                }
            });
        },
        changeInstance(data) {
            switch (data) {
                case 'proposed_settings':
                    this.instance = {
                        val : data,
                        lable: 'Proposed Settings'
                    }
                    break;
                case 'course_progress':
                    this.instance = {
                        val : data,
                        lable: 'Course Progress'
                    }
                    break;
            }
        },
        fetchCourseUnits() {
            // axios.post('/student-profile/fetch-course-units', {student_id:this.student.student_id, course_code:this.courseCode})
            // .then(res => {
            //     this.course = res.data;
            // })
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
            
            axios.post(`/student-profile/course-unit`, this.course.cc)
            .then(res => {
                this.firstLoad = 0;
                if(res.data.status == 'updated') {
                    console.log('test - '+this.firstLoad);
                    // updated
                    swal.fire(
                        'Success!',
                        'Units updated successfully',
                        'success',
                    )
                    .then( cont => {
                        if(res.data.sc && res.data.sc.completion_date) {
                            this.$parent.fetchStudentCourses(1);
                        }
                        // this.courseCompletion = res.data.sc;
                        // this.courseCompletion.details = res.data.scd;
                    })
                }else {
                    swal.fire(
                        'Opss...',
                        'Saving was unsuccessfull',
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
    watch: {
    },
}
</script>
<style scoped>
    td.cells {
        font-size: 10px;
        line-height: 1rem;
    }
</style>