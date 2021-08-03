<template>
  <div>
    <div class="row mb-3">
      <div class="col-md-12 pull-right text-right">
        <button class="btn btn-success" @click="callApi(apiBaseUrl)">
          <i class="far fa-save"></i> Submit
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 :class="'m-0 font-weight-bold text-'+app_color">USI Locate</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for>Single Name *</label>
                  <input
                    type="text"
                    v-model="usiform.personalDetails.singleName"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for>First Name *</label>
                  <input
                    type="text"
                    v-model="usiform.personalDetails.firstName"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for>Family Name *</label>
                  <input
                    type="text"
                    v-model="usiform.personalDetails.familyName"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for>Gender</label>
                  <!-- <input type="text" v-model="usiform.personalDetails.gender" class="form-control" /> -->
                  <select v-model="usiform.personalDetails.gender" class="form-control">
                    <option v-for="(item, index) in gender" :key="index" :value="item">{{item}}</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for>Date of Birth</label>
                  <date-picker
                    v-model="usiform.personalDetails.dateOfBirth"
                    :max-date="new Date()"
                    :masks="{L:'DD/MM/YYYY'}"
                  ></date-picker>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for>Town City of Birth</label>
                  <input
                    type="text"
                    v-model="usiform.personalDetails.townCityOfBirth"
                    class="form-control"
                  />
                </div>
              </div>
              <div class="col-md-12">
                <div class="custom-control custom-switch my-0">
                    <input type="checkbox" class="custom-control-input" v-model="moreInfo" id="moreInfo">
                    <!-- <input type="checkbox" class="custom-control-input" v-else @change="isReport(row.student_id, row.report_avetmiss)" :id="`report_avetmiss-${row._id}`"> -->
                    <label class="custom-control-label" for="moreInfo">More Info</label>
                </div>
              </div>
              <div  v-if="moreInfo" class="col-md-6">
                <div class="form-group">
                  <label for>Email Address</label>
                  <input
                    type="text"
                    v-model="usiform.contactDetails.emailAddress"
                    class="form-control"
                  />
                </div>
              </div>
              
              <div v-if="moreInfo" class="col-md-6">
                <div class="form-group">
                  <label for="usi">Country of Recidence</label>
                  <!-- <input type="text" class="form-control" /> -->
                  <select v-model="usiform.contactDetails.CountryOfResidenceCode" class="form-control" >
                    <option :value="country.countryCode" v-for="(country,index) in CountryOfResidenceCode" :key="index" >
                      {{ country.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div v-if="moreInfo"  class="col-md-6">
                <div class="form-group">
                  <label for>Address</label>
                  <input
                    type="text"
                    v-model="usiform.contactDetails.nationalAddress.address1"
                    class="form-control"
                  />
                </div>
              </div>
              <div v-if="moreInfo"  class="col-md-6">
                <div class="form-group">
                  <label for>Post Code</label>
                  <input
                    type="text"
                    v-model="usiform.contactDetails.nationalAddress.postCode"
                    class="form-control"
                  />
                </div>
              </div>
              <div v-if="moreInfo"  class="col-md-6">
                <div class="form-group">
                  <label for>Suburb / Town /City</label>
                  <input
                    type="text"
                    v-model="usiform.contactDetails.nationalAddress.suburbTownCity"
                    class="form-control"
                  />
                </div>
              </div>
              <div v-if="moreInfo"  class="col-md-6">
                <div class="form-group">
                  <label for="usi">State</label>
                  <!-- <input type="text" class="form-control" /> -->
                  <select v-model="usiform.contactDetails.nationalAddress.state" class="form-control" >
                    <option value=""></option>
                    <option value="ACT">ACT</option>
                    <option value="NSW">NSW</option>
                    <option value="NT">NT</option>
                    <option value="QLD">QLD</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="VIC">VIC</option>
                    <option value="WA">WA</option>
                  </select>
                </div>
              </div>
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
  props: ["training_org"],
  data() {
    return {
      app_color: app_color,
      gender: ["M", "F"],
      //apiBaseUrl: "http://localhost:8080/api/",
      apiBaseUrl: "https://usiapi.vorx.com.au:8443/api/",
      moreInfo: false,
      CountryOfResidenceCode: [],
      usiform: {
        personalDetails: {
          firstName: "",
          familyName: "",
          singleName: "",
          gender: "",
          dateOfBirth: "",
          townCityOfBirth: ""
        },
        contactDetails: {
          countryOfResidenceCode: "",
          emailAddress: "",
          internationalAddress: "",
          nationalAddress: {
            address1: "",
            postCode: "",
            state: "NSW",
            suburbTownCity: ""
          },
          phone: {
            home: "",
            mobile: ""
          }
        },
        orgCode: this.training_org.training_organisation_id,
        // orgCode: "VA1803",
        userReference: `${this.training_org.training_organisation_id}Creation${this.training_org.student_id_prefix}`
      }
    };
  },
  created(){
    let vm = this;
    let endPoint = this.apiBaseUrl;
    axios
      .post(
        `${endPoint}countries`,
        { orgCode: this.usiform.orgCode },
        {
          headers: {
            Authorization: vm.getAuth()
          }
        }
      )
      .then(response => {
        // console.log(response.data);
        vm.CountryOfResidenceCode = response.data.countries.countryDetails;
      })
      .catch(error => {
        console.log(error);
      });
  },
  methods: {
    getCountries() {
      let vm = this;
      let endPoint = this.apiBaseUrl;
      axios
        .post(
          `${endPoint}countries`,
          { orgCode: this.usiForm.orgCode },
          {
            headers: {
              Authorization: vm.getAuth()
            }
          }
        )
        .then(response => {
          // console.log(response.data);
          vm.CountryOfResidenceCode = response.data.countries.countryDetails;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getAuth() {
      let apiUser = "admin";
      let apiPass = "nimda321";
      let basicAuth;
      basicAuth = "Basic " + btoa(apiUser + ":" + apiPass);
      return basicAuth;
    },
    callApi(endPoint) {
      let vm = this;
      let toast = swal.fire({
        position: "top-end",
        title: "Please wait",
        showConfirmButton: false,
        onBeforeOpen: () => {
          swal.showLoading();
        }
      });

    
       vm.usiform.personalDetails.dateOfBirth = moment(
        vm.usiform.personalDetails.dateOfBirth
      ).format("YYYY-MM-DD");

      if(vm.usiform.personalDetails.singleName == ""){
         delete vm.usiform.personalDetails.singleName;
      }else{
        delete vm.usiform.personalDetails.firstName;
        delete vm.usiform.personalDetails.familyName;
      }

      if(this.moreInfo == false){
        vm.usiform.contactDetails = { 
          emailAddress : ''
         }
      }


      axios
        .post(`${endPoint}locate`, vm.usiform, {
          headers: {
            Authorization: vm.getAuth()
          }
        })
        .then(response => {
          this.usiform.personalDetails.dateOfBirth = moment(
            this.usiform.personalDetails.dateOfBirth
          )._d;
          if (response.data.hasOwnProperty("errorInfo")) {
            console.log(response.data.errorInfo);
            let em = response.data.errorInfo;
            let rmessage = "";
            em.forEach(element => {
              rmessage += "<li>" + element.message + "</li>";
            });

            swal.fire({
              type: "error",
              title: "Oops...",
              html: `<ul>${rmessage}</ul>`
            });
          } else if (response.data.hasOwnProperty("code")) {
            let em = response.data.message;
            swal.fire({
              type: "error",
              title: response.data.title,
              html: em
            });
          } else if (response.data.hasOwnProperty("errors")) {
            if (response.data.result == "NO_MATCH") {
              swal.fire({
                title: "<strong>Update Personal Details</strong>",
                type: "info",
                html: `<div class="info-message-wrapper">
              <div class="row">
                <div class="col-md-6 offset-md-3 text-left">
                <div class="clearfix"></div>
                  <span>Message : ${response.data.contactDetailsMessage}</span>
                  <div class="clearfix"></div>
                  <span>Result : ${response.data.result}</span>
                  <div class="clearfix"></div>
                  <span>USI : ${response.data.usi}</span>
                </div>
              </div>
            </div>`,
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
                confirmButtonAriaLabel: "Thumbs up, great!"
              });
            } else {
              swal.fire({
                title: "<strong>Locate</strong>",
                type: "info",
                html: `<div class="info-message-wrapper">
              <div class="row">
                <div class="col-md-6 offset-md-3 text-left">
                <div class="clearfix"></div>
                  <span>Message : ${response.data.contactDetailsMessage}</span>
                  <div class="clearfix"></div>
                  <span>Result : ${response.data.result}</span>
                  <div class="clearfix"></div>
                  <span>USI : ${response.data.usi}</span>
                </div>
              </div>
            </div>`,
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
                confirmButtonAriaLabel: "Thumbs up, great!"
              });
            }
          } else {
            swal.fire({
              title: "<strong>Locate</strong>",
              type: "info",
              html: `<div class="info-message-wrapper">
              <div class="row">
                <div class="col-md-6 offset-md-3 text-left">
                <div class="clearfix"></div>
                  <span>Message : ${response.data.contactDetailsMessage}</span>
                  <div class="clearfix"></div>
                  <span>Result : ${response.data.result}</span>
                  <div class="clearfix"></div>
                  <span>USI : ${response.data.usi}</span>
                </div>
              </div>
            </div>`,
              showCloseButton: false,
              showCancelButton: false,
              focusConfirm: false,
              confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
              confirmButtonAriaLabel: "Thumbs up, great!"
            });
          }
            this.usiform.personalDetails.singleName = "";
          /* this.usiform = {
              personalDetails: {
                firstName: "",
                familyName: "",
                singleName: "",
                gender: "",
                dateOfBirth: "",
                townCityOfBirth: ""
              },
              contactDetails: {
                countryOfResidenceCode: "",
                emailAddress: "",
                internationalAddress: "",
                nationalAddress: {
                  address1: "",
                  postCode: "",
                  state: "NSW",
                  suburbTownCity: ""
                },
                phone: {
                  home: "",
                  mobile: ""
                }
              }
          } */

        })
        .catch(error => {
          swal.fire({
            type: "error",
            title: error.response.data.message,
            html: em
          });
        });
    }
  }
};
</script>