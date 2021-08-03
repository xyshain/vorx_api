<template>
<div>

  <form @submit.prevent>
    <div>
      <div class="row mb-3">
        <div class="col-md-12 pull-right text-right">
          <button class="btn btn-success" @click="saveTrainingDlvrLoc">
            <i class="far fa-save"></i> Save
          </button>
        </div>
      </div>
      <div class="clearfix"></div>
      <div v-bind:class="'horizontal-line-wrapper-'+app_color+' mb-2'" id="formsection">
        <h6>Training Location Details</h6>
      </div>
      <div class="form-padding-left-right">
        <div class="row">
          <!-- <div class="col-lg-12">
                                <div :class="['form-group', errors.training_organisation_id ? 'has-error' : '']" >
                                    <label for="training_organisation_id">Organisation Identifier <a data-toggle="tooltip" data-placement="right" title="Avetmiss required!"><i class="fas fa-info-circle"></i></a></label>
                                    <input 
                                        id="training_organisation_id" 
                                        name="training_organisation_id" 
                                        value=""  
                                        autofocus="autofocus" 
                                        class="form-control" 
                                        type="number" 
                                        readonly="true"
                                        v-model="training_dlvr_location.training_organisation_id">
                                    <span v-if="errors.training_organisation_id" :class="['badge badge-danger']">{{ errors.training_organisation_id[0] }}</span>
                                </div>
          </div>-->
          <div class="col-lg-6">
            <div :class="['form-group', errors.train_org_dlvr_loc_id ? 'has-error' : '']">
              <label for="train_org_dlvr_loc_id">Delivery Location Identifier</label>
              <!-- <input
                id="train_org_dlvr_loc_id"
                name="train_org_dlvr_loc_id"
                value
                autofocus="autofocus"
                class="form-control"
                disabled
                type="text"
                v-model="dlID"
              /> -->
              <span class="form-control">{{training_dlvr_location.train_org_dlvr_loc_id}}</span>
              <!-- <input
                id="train_org_dlvr_loc_id"
                name="train_org_dlvr_loc_id"
                value
                autofocus="autofocus"
                class="form-control"
                disabled
                type="text"
                v-model="training_dlvr_location.train_org_dlvr_loc_id"
              /> -->
              <span
                v-if="errors.train_org_dlvr_loc_id"
                :class="['badge badge-danger']"
              >{{ errors.train_org_dlvr_loc_id[0] }}</span>
            </div>
          </div>
          <div class="col-lg-6">
            <div :class="['form-group', errors.train_org_dlvr_loc_name ? 'has-error' : '']">
              <label for="train_org_dlvr_loc_name">Delivery Location Name *</label>
              <input
                id="train_org_dlvr_loc_name"
                name="train_org_dlvr_loc_name"
                value
                autofocus="autofocus"
                class="form-control"
                type="text"
                v-model="training_dlvr_location.train_org_dlvr_loc_name"
              />
              <span
                v-if="errors.train_org_dlvr_loc_name"
                :class="['badge badge-danger']"
              >{{ errors.train_org_dlvr_loc_name[0] }}</span>
            </div>
          </div>
          <!-- <div class="col-lg-6">
            <div :class="['form-group', errors.addr_flat_unit_detail ? 'has-error' : '']">
              <label for="addr_flat_unit_detail">Unit</label>
              <input
                id="addr_flat_unit_detail"
                name="addr_flat_unit_detail"
                value
                autofocus="autofocus"
                class="form-control"
                type="text"
                v-model="training_dlvr_location.addr_flat_unit_detail"
              />
              <span
                v-if="errors.addr_flat_unit_detail"
                :class="['badge badge-danger']"
              >{{ errors.addr_flat_unit_detail[0] }}</span>
            </div>
          </div> -->
          <!-- <div class="col-lg-6">
            <div :class="['form-group', errors.addr_building_property_name ? 'has-error' : '']">
              <label for="addr_building_property_name">Building Name</label>
              <input
                id="addr_building_property_name"
                name="addr_building_property_name"
                value
                autofocus="autofocus"
                class="form-control"
                type="text"
                v-model="training_dlvr_location.addr_building_property_name"
              />
              <span
                v-if="errors.addr_building_property_name"
                :class="['badge badge-danger']"
              >{{ errors.addr_building_property_name[0] }}</span>
            </div>
          </div> -->
          
          <!-- <div class="col-lg-6">
            <div :class="['form-group', errors.addr_street_num ? 'has-error' : '']">
              <label for="addr_street_num">Street Number</label>
              <input
                id="addr_street_num"
                name="addr_street_num"
                value
                autofocus="autofocus"
                class="form-control"
                type="text"
                v-model="training_dlvr_location.addr_street_num"
              />
              <span
                v-if="errors.addr_street_num"
                :class="['badge badge-danger']"
              >{{ errors.addr_street_num[0] }}</span>
            </div>
          </div> -->
          <!-- <div class="col-lg-6">
            <div :class="['form-group', errors.addr_street_name ? 'has-error' : '']">
              <label for="addr_street_name">Street Name</label>
              <input
                id="addr_street_name"
                name="addr_street_name"
                value
                autofocus="autofocus"
                class="form-control"
                type="text"
                v-model="training_dlvr_location.addr_street_name"
              />
              <span
                v-if="errors.addr_street_name"
                :class="['badge badge-danger']"
              >{{ errors.addr_street_name[0] }}</span>
            </div>
          </div> -->
          <div class="col-lg-6">
            <div :class="['form-group', errors.addr_location ? 'has-error' : '']">
              <label for="addr_location">Suburb, Locality or Town *</label>
              <!-- <label for="addr_location">Suburb, State Postcode</label> -->
              <input 
              id="addr_location" 
              name="addr_location" 
              value=""  
              autofocus="autofocus" 
              class="form-control" 
              type="text" 
              v-model="training_dlvr_location.addr_location">
              <!-- <multiselect
                v-model="training_dlvr_location.postcode"
                tag-placeholder="Add this as new tag"
                placeholder="Search suburb"
                label="postcode_name"
                track-by="id"
                :options="slct_postcode"
                :multiple="false"
                :taggable="false"
                @tag="addTag"
              ></multiselect> -->
              <span v-if="errors.addr_location" :class="['badge badge-danger']">{{ errors.addr_location[0] }}</span>
            </div>
          </div>
          <div class="col-lg-6">
              <div :class="['form-group', errors.state_id ? 'has-error' : '']" >
                  <label for="state_id">State Identifier *</label>
                  <!-- <input 
                      id="state_id" 
                      name="state_id" 
                      value=""  
                      autofocus="autofocus" 
                      class="form-control" 
                      type="text" 
                      v-model="training_dlvr_location.state_id"> -->
                  <select class="form-control" v-model="training_dlvr_location.state_id">
                    <option v-for="(i,k) in slct_state" :key="k" :value="i.value">{{i.state_name}}</option>
                  </select>
                  <span v-if="errors.state_id" :class="['badge badge-danger']">{{ errors.state_id[0] }}</span>
              </div>
          </div>
          <div class="col-lg-6">
              <div :class="['form-group', errors.postcode ? 'has-error' : '']" >
                  <label for="postcode">Postcode * <i class="h6">Note: If training delivery location is outside AU, put OSPC</i></label>
                  <input 
                      id="postcode" 
                      name="postcode" 
                      value=""  
                      autofocus="autofocus" 
                      class="form-control" 
                      type="text" 
                      v-model="training_dlvr_location.postcode">
                  <span v-if="errors.postcode" :class="['badge badge-danger']">{{ errors.postcode[0] }}</span>
              </div>
          </div>
          <div class="col-lg-6">
            <div :class="['form-group', errors.country_identifier ? 'has-error' : '']">
              <label for="country_identifier">Country *</label>
              <!-- <input 
                                        id="country_identifier" 
                                        name="country_identifier" 
                                        value=""  
                                        autofocus="autofocus" 
                                        class="form-control" 
                                        type="number" 
                                        v-model="training_dlvr_location.country_identifier">
              <span v-if="errors.country_identifier" :class="['badge badge-danger']">{{ errors.country_identifier[0] }}</span>-->
              <multiselect
                v-model="training_dlvr_location.country_id"
                tag-placeholder="Add this as new tag"
                placeholder="Search country"
                label="full_name"
                track-by="identifier"
                :options="slct_country_identifier"
                :multiple="false"
                :taggable="false"
                @tag="addTag"
              ></multiselect>
              <span
                v-if="errors.country_id"
                :class="['badge badge-danger']"
              >{{ errors.country_id[0] }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <hr>
  <v-client-table :class="'header-'+app_color" :data="deliveryLocList" :columns="columns" :options="options" ref="trainerTable">
      <div slot="afterLimit" class="ml-2">

      </div>
      <div class="btn-group" slot="actions" slot-scope="{row}">
          <a href="#formsection" class="btn btn-primary btn-sm" @click="edit(row)"> <i class="fas fa-edit"> </i></a>
          <a href="javascript:void(0)" class="btn btn-danger btn-sm text-white" @click="remove(row.id)"> <i class="fas fa-trash"> </i></a>
      </div>
  </v-client-table>
</div>
</template>
<script>
export default {
  data() {
    return {
      // table 
      deliveryLocList: [],
      columns: [
        "id",
        "train_org_dlvr_loc_id",
        "train_org_dlvr_loc_name",
        'full_location',
        // "addr_location",
        // "state_id",
        // "postcode",
        "country_name",
        "actions",
      ],
      options: {
        initialPage: 1,
        perPage: 10,
        highlightMatches: true,
        sortIcon: {
          base: "fas",
          up: "fa-sort-amount-up",
          down: "fa-sort-amount-down",
          is: "fa-sort",
        },
        headings: {
          id: "#",
          // training_organisation_id: 'Organisation Identifier',
          train_org_dlvr_loc_id: "Delivery Location ID",
          train_org_dlvr_loc_name: "Delivery Location Name",
          full_location: "Location",
          // addr_location: "Suburb",
          // state_id: "State",
          // postcode: "Postcode",
          country_name: "Country",
          actions: "Actions",
        },
        sortable: ["id", "name"],
        texts: {
          filter: "Search:",
          filterPlaceholder: "Search keywords",
        },
      },

      // form
      org_id : window.training_organisation_id,
      training_dlvr_location: {
        training_organisation_id: window.training_organisation_id,
        train_org_dlvr_loc_id: "",
        train_org_dlvr_loc_name: "",
        postcode: "",
        state_id: "",
        addr_location: "",
        country_id: "",
        edit: 0,
        addr_flat_unit_detail: '',
        addr_building_property_name: '',
        addr_street_name: '',
        addr_street_num: ''
      },
      postcode_val: [],
      slct_postcode: window.slct_postcode,
      slct_state: window.slct_state,
      country_val: [],
      slct_country_identifier: window.slct_country_identifier,
      organisation_id: window.organisation_id,
      csrf: "",
      errors: [],
      success: false,
      app_color: app_color,
    };
  },
  created() {
    this.fetchDeliveryLoc();
    this.refill();
  },
  computed : {
  },
  methods: {
    // table
    edit(item) {
      this.training_dlvr_location = item;
    },
    remove(id) {
      swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          let vm = this;
          if (result.value === true) {
            axios({
              method: "delete",
              url: `/organisation/${this.organisation_id}/delivery-location/delete/${id}`,
            })
              .then((res) => {
                console.log(res);
                let i = vm.deliveryLocList.map((item) => item.id).indexOf(id); // find index of your object
                vm.deliveryLocList.splice(i, 1); // remove it from array
                if (res.data.status == "success") {
                  Toast.fire({
                    position: "top-end",
                    type: "success",
                    title: "Data is deleted successfully",
                  });
                } else {
                  Toast.fire({
                    position: "top-end",
                    type: "error",
                    title: "Opss.. data was not deleted",
                  });
                }
              })
              .catch((err) => console.log(err));
          }
        });
    },
    fetchDeliveryLoc(page_url) {
      let vm = this;
      page_url =
        page_url ||
        `/organisation/${this.organisation_id}/delivery-location/list`;

      fetch(page_url)
        .then((res) => res.json())
        .then((res) => {
          this.deliveryLocList = res.deliveryLocList;
        })
        .catch((err) => console.log(err));
    },


    // form
    refill() {
      axios.get("training-delivery-location/generate").then((res) => {
        // console.log(res.data);
        // this.training_dlvr_location.train_org_dlvr_loc_id = res.data;
        this.training_dlvr_location.train_org_dlvr_loc_id = res.data;
      });
    },

    saveTrainingDlvrLoc() {
      swal.fire({
        title: "Please wait...",
        // html: '',// add html attribute if you want or remove
        allowOutsideClick: false,
        onBeforeOpen: () => {
          swal.showLoading();
        },
      });
      axios
        .post(
          `/organisation/${this.organisation_id}/delivery-location`,
          this.training_dlvr_location
        )
        .then((response) => {
          if (response.data.status == "errors") {
            // alert('error ghorl');
            this.errors = response.data.errors;
            Toast.fire({
              position: "top-end",
              type: "error",
              title: "Failed to add data",
            });
          } else {
            // alert('success ghorl');
            this.errors = [];
            this.training_dlvr_location = {
              training_organisation_id: window.training_organisation_id,
              train_org_dlvr_loc_id: response.data.dlID,
              train_org_dlvr_loc_name: "",
              postcode: "",
              state_id: "",
              addr_location: "",
              country_id: "",
              edit: 0,
              addr_flat_unit_detail: '',
              addr_building_property_name: '',
              addr_street_name: '',
              addr_street_num: ''
            };
              (this.postcode_val = []),
              // (this.training_dlvr_location.addr_flat_unit_detail = ""),
              // (this.training_dlvr_location.addr_building_property_name = ""),
              // (this.training_dlvr_location.addr_street_name = ""),
              // (this.training_dlvr_location.addr_street_num = "");
            this.country_val = [];
            this.success = true;
            Toast.fire({
              position: "top-end",
              type: "success",
              title: "Data is saved successfully",
            });
            this.fetchDeliveryLoc();
            this.refill();
          }
        })
        .catch((error) => {
          console.log(error);
          Toast.fire({
            position: "top-end",
            type: "error",
            title: "Failed to add data",
          });
        });
    },
    addTag(newTag) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor(Math.random() * 10000000),
      };
      this.slct_postcode.push(tag);
      this.training_dlvr_location.postcode.push(tag);
      // this.slct_state_identifier.push(tag)
      // this.training_dlvr_location.state_id.push(tag)
      this.slct_suburb.push(tag);
      this.slct_country_identifier.push(tag);
      this.training_dlvr_location.country_id.push(tag);
      // this.options.push(tag)
      // this.value.push(tag)
    },
  },
  watch : {
    'training_dlvr_location.postcode' : function (newVal) {
      console.log(this.training_dlvr_location.state_id);
      if(this.training_dlvr_location.state_id && [9, 10].indexOf(this.training_dlvr_location.state_id) != -1) {
        this.training_dlvr_location.postcode = 'OSPC';
      }else {
        this.training_dlvr_location.postcode = newVal;
      }
    },
    // 'dlID' : function (newVal) {
    //   this.training_dlvr_location.train_org_dlvr_loc_id = newVal;
    // }
  }
};
</script>