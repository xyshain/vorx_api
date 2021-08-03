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
          <div class="row mb-2 no-gutters">
            <!-- Residential Address -->
            <div class="col-md-12">
                <div class="card m-1">
                    <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                        Residential Address
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="addr_flat_unit_detail">Unit</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="contact.addr_flat_unit_detail"
                                        id="addr_flat_unit_detail"
                                    />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="addr_building_property_name">Building Name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="contact.addr_building_property_name"
                                        id="addr_building_property_name"
                                    />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="addr_street_num">Street Number
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
                                        v-model="contact.addr_street_num"
                                        id="addr_street_num"
                                    />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="addr_street_name">Street Name
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
                                        v-model="contact.addr_street_name"
                                        id="addr_street_name"
                                    />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <!-- multiselect -->
                                <div class="form-group">
                                    <label for="addr_suburb">
                                    Postcode - Suburb, State
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
                                    v-model="post_sub_state"
                                    :options="addr_suburbs"
                                    @select="select_addr_suburb"
                                    :multiple="false"
                                    placeholder="Type to search"
                                    :close-on-select="true"
                                    track-by="id"
                                    label="value"
                                    >
                                    <span slot="noResult">Oops! No units found. Consider changing the search query.</span>
                                    </multiselect>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="home_country_res_addr">
                                        Overseas Address <span style="font-size: 74%; opacity: 0.73;">( <i>If not currently in Australia</i> )</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="contact.home_country_res_addr"
                                        id="home_country_res_addr"
                                    />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="addr_postal_delivery_box">
                                        Postal Delivery Box <span style="font-size: 74%; opacity: 0.73;">( <i>If different from above</i> )</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="contact.addr_postal_delivery_box"
                                        id="addr_postal_delivery_box"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters">
            <!-- Contact Details -->
            <div class="col-md-6">
                <div class="card m-1">
                    <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                        Contact Details
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="phone_home">Telephone Home No.</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.phone_home"
                                    id="phone_home"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="phone_work">Telephone Work No.</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.phone_work"
                                    id="phone_work"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="phone_mobile">Telephone Mobile No.</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.phone_mobile"
                                    id="phone_mobile"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="email">Email Address</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.email"
                                    id="email"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="email_at">Second Email Address</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.email_at"
                                    id="email_at"
                                />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Emergency Contact Details -->
            <div class="col-md-6">
                <div class="card m-1">
                    <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                        Emergency Contact Details
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="emer_name">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.emer_name"
                                    id="emer_name"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="emer_address">Address</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.emer_address"
                                    id="emer_address"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="emer_telephone">Telephone No.</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.emer_telephone"
                                    id="emer_telephone"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="emer_relationship">Relationship</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="contact.emer_relationship"
                                    id="emer_relationship"
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
      //englishTest: "",
      eng: "",
      testScore: "",
      testDate: "",
      testScoreRange: [],
      english_test: window.english_tests,
      roles : null,
      addr_suburbs: window.addr_suburbs,
      post_sub_state: null,
      
    };
  },
  computed : {
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

        axios.post(`/student/domestic/${student.id}/contact-update`, {inputs:this.contact})
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
      select_addr_suburb({id, value, state}) {
        //   let splt_1 = value.split(' - ');
        //   let splt_2 = splt_1[1].split(', ');
        //   this.contact.postcode = splt_1[0];
        //   this.contact.addr_suburb = splt_2[0];
        //   this.contact.state_short = splt_2[1];
        //   this.contact.state = state,
        //   this.contact.state_id = state.id;

          this.contact.addr_suburb = {id : id, value : value};

      }

  },
  watch: {},
};
</script>
<style scoped>
    
</style>
<style>
</style>