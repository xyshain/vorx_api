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
            <!-- personal information -->
            <div class="col-md-6">
                <div class="card m-1">
                    <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                        Personal Information
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="prefix">Prefix</label>
                                <select id="studentPrefix" v-model="student.prefix" class="form-control">
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="firstname">First Name
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
                                    v-model="student.firstname"
                                    id="firstname"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="middlename">Middle Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="student.middlename"
                                    id="middlename"
                                />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="lastname">Last Name
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
                                <input type="text" class="form-control" v-model="student.lastname" id="lastname" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="gender">Gender
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
                                <select id="gender" v-model="student.gender" class="form-control">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="date_of_birth">
                                    Date of birth
                                    <span style="font-size: 74%;opacity: 73%;">( MM/DD/YYYY )</span>
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
                                <date-picker
                                    locale="en"
                                    mode="date"
                                    v-model="student.date_of_birth"
                                    :masks="{L:'DD/MM/YYYY'}"
                                    :input-props='{
                                    autocomplete:"off",
                                    }'
                                ></date-picker>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- usi information -->
            <div class="col-md-6">
                <div class="card m-1">
                    <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                        USI Information
                    </div>
                    <div class="card-body py-2 px-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="singleName">Single Name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="student.singleName"
                                        id="singleName"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="student.first_name"
                                        id="first_name"
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="family_name">Family Name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="student.family_name"
                                        id="family_name"
                                    />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                  <label for="first_name">Unique Student Identifier
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
                                  <div class="input-group mb-3">
                                    <input
                                      type="text"
                                      class="form-control"
                                      v-bind:name="'unique_student_id'"
                                      v-bind:id="'unique_student_id'"
                                      v-model="unique_student_id"
                                      aria-describedby="basic-addon2"
                                    />

                                    <div class="input-group-append">
                                      <button
                                        v-if="verified"
                                        class="btn btn-success btn-outline-secondary"
                                        @click="verifyUsiData"
                                        type="button"
                                      >Verified</button>
                                      <button
                                        v-else
                                        class="btn btn-outline-secondary"
                                        @click="verifyUsiData"
                                        type="button"
                                      >Verify</button>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- english test -->
            <div class="col-md-12">
                <div class="card m-1">
                    <div class="card-header py-1 px-3" :class="[`bg-${app_color}`, 'text-white']">
                        English Test
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label>English Test</label>
                                <select @change="changeScoring()" v-model="englishTest" class="form-control">
                                    <option
                                    v-for="(test) in english_test"
                                    :key="test.id"
                                    :value="test.id"
                                    >{{ test.name }}</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6" v-if="englishTest == 1 || englishTest == 4">
                                <div class="form-group">
                                <label>English Test Score</label>
                                <select v-model="testScore" class="form-control">
                                    <option v-for="(test) in testScoreRange" :key="test" :value="test">{{ test }}</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6" v-else-if="englishTest != '' && englishTest != 9">
                                <div class="form-group">
                                <label>English Test Score</label>
                                <input type="text" class="form-control" v-model="testScore" />
                                </div>
                            </div>

                            <div class="col-md-6" v-if=" englishTest != 9 || testScore != null">
                                <div v-if="testScore !== ''">
                                <div class="form-group">
                                    <label>English Test Date</label>
                                    <date-picker
                                    locale="en"
                                    v-model="testDate"
                                    :value="testDate"
                                    :masks="{L:'DD/MM/YYYY'}"
                                    :max-date="new Date()"
                                    ></date-picker>
                                </div>
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
      // unique_student_id : null,
      verified : null,
      
    };
  },
  computed : {
      student() {
        //   console.log(this.$store.state.party)
        let person = this.$store.state.person
        person.date_of_birth = moment(person.date_of_birth)._d
        return person
      },
      englishTest() {
          return this.$store.state.student.english_test
      },
      student_data() {
          return this.$store.state.student;
      },
      unique_student_id() {
          return this.$store.state.student_detail.unique_student_id;
      },
  },
  methods: {
    saveChanges() {
      const Toast = swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
      });
      swal.fire({
        title: "Saving Changes...",
        // html: '',// add html attribute if you want or remove
        allowOutsideClick: false,
        onBeforeOpen: () => {
          swal.showLoading();
        },
      });
      axios
        .put(`/student/${this.student_data.id}`, {
          prefix: this.student.prefix,
          firstname: this.student.firstname,
          middlename: this.student.middlename,
          lastname: this.student.lastname,
          gender: this.student.gender,
          date_of_birth: moment(this.student.date_of_birth).format(
              "YYYY-MM-DD"
          ),
          englishTest: this.englishTest,
          testScore: this.testScore,
          testDateL:
            this.testDate !== ""
              ? moment(this.testDate).format("DD/MM/YYYY")
              : "",
          //  USI Information
          singleName: this.student.singleName,
          first_name: this.student.first_name,
          family_name: this.student.family_name,
          unique_student_id: this.unique_student_id,
        })
        .then((res) => {
          // console.log(res);
          if (res.data.status == "updated") {
            console.log("success");
            Toast.fire({
              type: "success",
              title: "Updated Successfuly",
            });
          } else {
            console.log("error");
            Toast.fire({
              type: "error",
              title: "Failed to Update",
            });
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },
    changeScoring() {
      let scorerange = [];
      if (this.englishTest == 1) {
        for (let index = 4; index <= 9; index++) {
          scorerange.push(index.toFixed(1));
          if (index != 9) {
            scorerange.push(index + 0.5);
          }
        }
        this.testScoreRange = scorerange;
      } else if (this.englishTest == 4) {
        let scorerange = [
          "10-28",
          "29-35",
          "36-41",
          "42-49",
          "50-57",
          "58-64",
          "65-72",
          "73-78",
          "79-82",
          "83-85",
          "86-90",
        ];
        this.testScoreRange = scorerange;
      } else {
        // this.testScoreRange = scorerange;
        // this.testScoreRange = scorerange;
        this.testScoreRange = scorerange;
      }
      this.testScore = "";
    },
    // USI
    verifyUsiData() {
      let vm = this;

      let studentInfo = {
        student_info : vm.student,
      };
      let avetmiss_info = vm.formValues;
      console.log(studentInfo);
      let apiBaseUrl = "https://usiapi.vorx.com.au:8443/api/";
      let usiForm = {
        usi: "",
        dateOfBirth: "",
        orgCode: window.app_settings.training_organisation_id,
      };
      // console.log(avetmiss_info);

      if (vm.unique_student_id == "") {
        swal.fire({
          title: "Opss.. Unique Student ID must not be empty",
          type: "error",
          timer: 3000,
          showConfirmButton: false,
        });
      } else {
        usiForm.usi = vm.unique_student_id;
        if(studentInfo.student_info == undefined){
           if (studentInfo.student.lastname == null) {
              usiForm.singleName = studentInfo.student.firstname;
            } else {
              usiForm.firstName = studentInfo.student.firstname;
              usiForm.familyName = studentInfo.student.lastname;
            }
            usiForm.dateOfBirth = moment(
              studentInfo.student.date_of_birth
            ).format("YYYY-MM-DD");
        }else{
          if (studentInfo.student_info.singleName !== null ) {
            console.log(studentInfo.student_info);
            usiForm.singleName = studentInfo.student_info.singleName;
          } else {
            usiForm.firstName = studentInfo.student_info.first_name;
            usiForm.familyName = studentInfo.student_info.family_name;
          }
          usiForm.dateOfBirth = moment(
            studentInfo.student_info.date_of_birth
          ).format("YYYY-MM-DD");

        }

        let toast = swal.fire({
          position: "top-end",
          title: "Please wait",
          showConfirmButton: false,
          timer: 30000,
        });

        axios
          .post(`${apiBaseUrl}verify`, usiForm, {
            baseURL: window.location.host,
            headers: {
              Authorization: vm.getAuth(),
            },
          })
          .then((response) => {
            toast.dismiss === swal.DismissReason.timer;
            if (response.data.hasOwnProperty("errorInfo")) {
              console.log(response.data.errorInfo);
              let em = response.data.errorInfo;
              let rmessage = "";
              em.forEach((element) => {
                rmessage += "<li>" + element.message + "</li>";
              });

              swal.fire({
                type: "error",
                title: "Oops...",
                html: `<ul>${rmessage}</ul>`,
              });
            } else if (response.data.hasOwnProperty("code")) {
              let em = response.data.message;
              swal.fire({
                type: "error",
                title: "Oops...",
                html: em,
                footer: "<a href>Why do I have this issue?</a>",
              });
            } else {
              if (
                (response.data.firstName == "MATCH" &&
                  response.data.familyName == "MATCH" &&
                  response.data.dateOfBirth == "MATCH" &&
                  response.data.usistatus == "Valid") ||
                (response.data.singleName == "MATCH" &&
                  response.data.dateOfBirth == "MATCH" &&
                  response.data.usistatus == "Valid")
              ) {
                let sid = studentInfo.student_info == undefined ? studentInfo.student.student_id : studentInfo.student_id
                axios
                  .get(
                    `/usi/verify/success/${sid}/${usiForm.usi}`
                  )
                  .then((response) => {
                    vm.inputs["verified"] = 1;
                    // console.log(response);
                  })
                  .catch((err) => {
                    // console.log(err);
                  });
              }
              // console.log(response.data);
              swal.fire({
                title: "<strong>USI Result</strong>",
                type: "info",
                html: `<div class="info-message-wrapper">
              <div class="row">
                <div class="col-md-6 offset-md-3 text-left">
                  <span>First Name : ${response.data.firstName}</span>
                  <div class="clearfix"></div>
                  <span>Family Name : ${response.data.familyName}</span>
                  <div class="clearfix"></div>
                    <span>Single Name : ${response.data.singleName}</span>
                  <div class="clearfix"></div>
                    <span>Date of Birth : ${response.data.dateOfBirth}</span>
                  <div class="clearfix"></div>
                  <span>USI Status : ${response.data.usistatus}</span>
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>`,
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
                confirmButtonAriaLabel: "Thumbs up, great!",
              });
            }
            // console.log(response)
          })
          .catch((err) => {
            // if (usiForm.dateOfBirth != "") {
            //   usiForm.dateOfBirth = moment(vm.usiForm.dateOfBirth)._d;
            // }
            swal.fire({
              type: "error",
              title: "Oops...",
              html: `<ul>${err.response.data.message}</ul>`,
            });
          });
      }
    },
    getAuth() {
      let apiUser = "admin";
      let apiPass = "nimda321";
      let basicAuth;
      basicAuth = "Basic " + btoa(apiUser + ":" + apiPass);
      return basicAuth;
    },
  },
  watch: {
    // eng: function (newVal, oldVal) {
    //   console.log(newVal);
    //   this.changeScoring(newVal);
    //   this.testScore = window.englishData.score;
    // },
  },
};
</script>
<style scoped>
    
</style>
<style>
.tab-pane {
  border: 1px solid #e0dfdf;
  border-top: none;
  padding: 1.3rem;
  border-bottom-right-radius: 5px;
  border-bottom-left-radius: 5px;
  background-color: #ffffff;
}
.vc-text-base {
  display: block;
  width: 100%;
  height: calc(1.5em + 0.75rem + 2px);
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #6e707e;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #d1d3e2;
  border-radius: 0.35rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
</style>