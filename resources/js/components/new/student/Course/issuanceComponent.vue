<template>
    <div class="row no-gutter">
        <div class="col-md-4">
            <!-- issuance card -->
            <div class="col-md-12">
                <h5 class="font-weight-bold">Issuance List <a v-if="course.cc && course.cc.partial_completion == 1" @click="createIssuance" class="add text-success"><i class="fas fa-plus-circle"></i></a></h5>
                <a v-if="issuance_list.details">
                    <div @click="selectIssuance(i)" :class="['card text-left card-'+app_color+' mb-2', selected_issue == i.id ? 'selected' : '']" v-for="(i,k) in issuance_list.details" :key="k">
                        <div class="card-body">
                            <div class="row no-gutter">
                                <div class="col-md-12">
                                    <h4 class="card-title m-0" v-if="i.is_cert == 1">
                                        Certificate # {{issuance_list.manual_cert_num}} <i :class="`text-success`" class="fas fa-paper-plane" data-toggle="tooltip" title="Sent" data-placement="top"></i>
                                    </h4>
                                    <h4 class="card-title m-0" v-else>
                                        SOA # {{i.soa_number}} <i :class="`text-success`" class="fas fa-paper-plane" data-toggle="tooltip" title="Sent" data-placement="top"></i>
                                    </h4>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <span class="mr-1" data-toggle="tooltip" title="Completed Units" data-placement="top"><i class="fas fa-book mr-1"></i>: {{i.units.length}} </span> 
                                    <span class="ml-2 mr-1" data-toggle="tooltip" title="Issued Date" data-placement="top"><i class="far fa-calendar-alt mr-1"></i>: {{i.release_date | formatDate}} </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div :class="'card text-left card-'+app_color+' mb-2'">
                        <div class="card-body">
                            <div class="row no-gutter">
                                <div class="col-md-12">
                                    <h4 class="card-title m-0">
                                        SOA # CSOA1828 <i :class="`text-success`" class="fas fa-paper-plane" data-toggle="tooltip" title="Sent" data-placement="top"></i>
                                    </h4>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <span class="mr-1" data-toggle="tooltip" title="Completed Units" data-placement="top"><i class="fas fa-book mr-1"></i>: 5 </span> 
                                    <span class="ml-2 mr-1" data-toggle="tooltip" title="Issued Date" data-placement="top"><i class="far fa-calendar-alt mr-1"></i>: 01/01/2021 </span>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div v-else :class="'card text-left card-'+app_color">
                        <div class="card-body">
                            <h4 class="card-title">{{item.process}} <span class="h6"><i class="fas fa-unlock" v-if="item.is_locked == 0"></i><i class="fas fa-lock" v-else></i></span> </h4>
                            <p class="card-text mb-0">{{item.dateFrom | formatDate}} - {{item.dateTo | formatDate}}</p>
                            <p v-if="['',null].indexOf(item.remarks) == -1" class="font-weight-bold font-italic card-text mb-0 notes">Note: {{item.remarks}}</p>
                        </div>
                    </div> -->
                </a>
            </div>

        </div>


        <div class="col-md-8">
            <!-- issuance detail -->
            <div class="card" v-if="issuance.id">
                <div class="card-body px-0">
                    <div class="row no-gutter">
                        <div class="col-md-6">
                            <!-- <button class="btn btn-danger btn-sm"><i class="fas fa-trash mr-1"></i> Delete Issuance</button> -->
                            <span class="font-weight-bold h5"><i class="fas fa-book mr-1"></i> {{issuance.units.length}} Units</span>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group">
                                <button
                                    type="button"
                                    class="btn btn-sm dropdown-toggle dropdown-toggle-split"
                                    :class="[`btn-info`]"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <span>
                                        <i class="fas fa-file-pdf mr-1"></i> Options
                                    </span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a v-if="issuance.is_cert == 1" class="dropdown-item" type="button" :href="`/student-profile/generate-issuance-pdf/${issuance.id}`" target="_blank">
                                        <i class="fas fa-certificate" :class="[`text-info`]" ></i>&nbsp; View Certificate & Record of results
                                    </a>
                                    <a v-if="issuance.is_cert != 1" class="dropdown-item" type="button" :href="`/student-profile/generate-issuance-pdf/${issuance.id}`" target="_blank">
                                        <i class="fas fa-certificate" :class="[`text-info`]"></i>&nbsp; View Statement of attainment
                                    </a>
                                    <a class="dropdown-item" type="button" target="_blank">
                                        <i class="fas fa-paper-plane" :class="[`text-warning`]"></i>&nbsp; Send Issuance
                                    </a>
                                    <a class="dropdown-item" type="button" target="_blank">
                                        <i class="fas fa-trash" :class="[`text-danger`]"></i>&nbsp; Delete Issuance
                                    </a>
                                </div>
                            </div>
                            <button @click="saveChanges" class="btn btn-success btn-sm"><i class="fas fa-save mr-1"></i> Save Changes</button>
                        </div>

                        <!-- inputs -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="manual_issuance_number">Issuance # * <span style="font-size: 74%; opacity: 0.73;">({{issuance.is_cert == 1 ? 'Certificate' : 'SOA'}})</span></label>
                                <input type="text" v-model="issuance.manual_issuance_number" class="form-control">
                                <div v-if="errors.manual_issuance_number" class="badge badge-danger">{{ errors.manual_issuance_number[0] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="issued_date">Issued Date * <span style="font-size: 74%; opacity: 0.73;">( DD/MM/YYYY )</span></label>
                                <date-picker
                                    locale="en"
                                    mode="date"
                                    v-model="issuance.release_date"
                                    :masks="{L:'DD/MM/YYYY'}"
                                    :input-props='{
                                    autocomplete:"off",
                                    }'
                                ></date-picker>
                                <div v-if="errors.release_date" class="badge badge-danger">{{ errors.release_date[0] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="reissued_date">Reissued Date <span style="font-size: 74%; opacity: 0.73;">( DD/MM/YYYY )</span></label>
                                <date-picker
                                    locale="en"
                                    mode="date"
                                    v-model="issuance.reissued_date"
                                    :masks="{L:'DD/MM/YYYY'}"
                                    :input-props='{
                                    autocomplete:"off",
                                    }'
                                ></date-picker>
                                <div v-if="errors.reissued_date" class="badge badge-danger">{{ errors.reissued_date[0] }}</div>
                            </div>
                        </div>

                        <!-- units details table -->
                        <div class="table-responsive iss-table-height">
                            <table class="table iss-table table-striped">
                                <thead>
                                    <th :class="` table-header-${app_color}`" class="text-center py-2 cells" >#</th>
                                    <th :class="` table-header-${app_color}`" class="text-center py-2 cells" >Unit Code</th>
                                    <th :class="` table-header-${app_color}`" class="text-center py-2 cells" >Unit Title</th>
                                    <th :class="` table-header-${app_color}`" class="text-center py-2 cells" v-if="issuance.is_cert == 1">Year Enroled</th>
                                    <th :class="` table-header-${app_color}`" class="text-center py-2 cells" v-if="issuance.is_cert == 1">Result</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(ii,kk) in issuance.units" :key="kk">
                                        <td class="p-2 cells align-middle text-center">{{kk + 1}}</td>
                                        <td class="p-2 cells align-middle text-center">{{ii.code}}</td>
                                        <td class="p-2 cells align-middle text-center">{{ii.description}}</td>
                                        <td class="p-2 cells align-middle text-center" v-if="issuance.is_cert == 1">{{ii.dates.end | getYear}}</td>
                                        <td class="p-2 cells align-middle text-center" v-if="issuance.is_cert == 1">{{ii.status | getResult}}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    data() {
        return {
            app_color : app_color,
            issuance : {},
            errors : {},
            // issuance_list : {},
            selected_issue: null,
            course_unit_count : 0,

        }
    },
    created() {
        //   console.log(this.courseId);
    },
    computed: {
        course : {
            get() {
                let courses = this.$store.state.courses;
                let c = courses.filter(data => data.id == this.courseId);
                console.log(c[0].cc.certificate);
                // this.issuance_list = c[0].cc.certificate;
                this.course_unit_count = c[0].cc.details.length
                return c[0];

            },
            set() {

            }
        },
        issuance_list () {
            let courses = this.$store.state.courses;
            let c = courses.filter(data => data.id == this.courseId);
            
            for (let i = 0; i < c[0].cc.certificate.details.length; i++) {
                const element = c[0].cc.certificate.details[i];
                element.release_date = element.release_date ? moment(element.release_date)._d : null; 
                element.reissued_date = element.reissued_date ? moment(element.reissued_date)._d : null; 
                if(c[0].cc.details.length <= element.units.length) {
                    element['is_cert'] = 1;
                }else {
                    element['is_cert'] = 0;
                }
                
            }

            return c[0].cc.certificate;
        }
    },
    methods : {

        createIssuance() {
            swal.fire({
                title: `Create new Issuance?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            })
            .then(result => {
                if (result.value) {
                    axios.post('/student-profile/issuance', {course: this.course})
                    .then(res => {
                        if(res.data.status == 'success') {
                            swal.fire(
                                'Success!',
                                'Issuance created successfully',
                                'success'
                            )
                        }else {
                            swal.fire(
                                'Opss...',
                                'Issuance was not created successfully',
                                'error'
                            )
                        }
                    })
                }
            });
        },

        saveChanges() {
            // console.log(this.issuance.release_date);

            if(this.issuance.release_date === null || this.issuance.manual_issuance_number == '') {
                swal.fire(
                    'Opss...',
                    'Please fill all required inputs',
                    'error'
                )
                return false;
            }

            swal.fire({
            title: "Saving issuance...",
                // html: '',// add html attribute if you want or remove
                allowOutsideClick: false,
                onBeforeOpen: () => {
                swal.showLoading();
                },
            });

            axios.post('/student-profile/issuance', this.issuance)
            .then(res => {
                if(res.data.status == 'success') {
                    swal.fire(
                        'Success!',
                        'Issuance saved successfully',
                        'success'
                    )
                }else {
                    swal.fire(
                        'Opss...',
                        'Issuance was not saved successfully',
                        'error'
                    )
                }
            })

        },
        selectIssuance(item) {

            swal.fire({
            title: "Loading issuance...",
                // html: '',// add html attribute if you want or remove
                allowOutsideClick: false,
                onBeforeOpen: () => {
                swal.showLoading();
                },
            });

            this.issuance = item;
            this.selected_issue = item.id;
            // this.issuance.release_date = moment(item.release_date).format('DD/MM/YYYY');
            this.issuance.manual_issuance_number = this.issuance.is_cert == 1 ? this.issuance_list.manual_cert_num : item.soa_number

            swal.close();

        },
    },
    filters: {
        formatDate(data) {
            return data ? moment(data).format('MM/DD/YYYY') : '--/--/----'
        },
        getYear(data) {
            return data ? moment(data).format('YYYY') : '----'
        },
        getResult(data) {
            switch (data) {
                case 4:
                    return 'CT'
                    break;
                case 3:
                    return 'C'
                    break;
                case 6:
                    return 'RPL'
                    break;
            
                default:
                    break;
            }
        }
    }

}
</script>
<style scoped>
    .iss-table-height {
        max-height: 300px !important;
        overflow: auto !important;
    }
    .iss-table thead th {
        position: sticky;
        top: 0;
        border: none !important
    }

    table.table tr th{
        width: 130px;
        
    } 
    th{
        background-color: #fff;
        color: #fff;
        font-size: 12px !important;
        line-height: 1rem;
        border:1px solid #fff !important;
    }
    td{
        border-right-color: #fff !important;
        border-left-color: #fff !important;
    }
    .plist {
        max-height: 600px;
        overflow: auto;
    }
    .card.text-left {
        margin-bottom:10px;
        cursor: pointer;
        border: 1px solid #e3e6f0 !important;
        
    }
    .card-details {
        line-height: 1.3rem;
        font-size: 12px;
    }
    /* .card.text-left:hover {
        background-color: #024B67;
        color: #fff;
    }
    .selected {
        background-color: #024B67;
        color: #fff;
    } */
    .close {
        color: red;
        text-align: right;
    }
    .add {
        cursor: pointer;
        font-size: 20px;
    }
    .notes {
        width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    td.cells {
        font-size: 10px;
        line-height: 1rem;
    }
</style>