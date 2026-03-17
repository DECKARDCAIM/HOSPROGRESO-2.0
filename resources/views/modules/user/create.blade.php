@extends('layouts.panel')
@section('title', 'Crear Usuario')

@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <!-- Step Form -->
            <form class="js-step-form py-md-5"
                data-hs-step-form-options='{
              "progressSelector": "#addUserStepFormProgress",
              "stepsSelector": "#addUserStepFormContent",
              "endSelector": "#addUserFinishBtn",
              "isValidate": false
            }'>
                <div class="row justify-content-lg-center">
                    <div class="col-lg-8">
                        <!-- Step -->
                        <ul id="addUserStepFormProgress"
                            class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{
                    "targetSelector": "#addUserStepProfile"
                  }'>
                                    <span class="step-icon step-icon-soft-dark">1</span>
                                    <div class="step-content">
                                        <span class="step-title">Perfil</span>
                                    </div>
                                </a>
                            </li>

                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{
                    "targetSelector": "#addUserStepBillingAddress"
                  }'>
                                    <span class="step-icon step-icon-soft-dark">2</span>
                                    <div class="step-content">
                                        <span class="step-title">Ubicación</span>
                                    </div>
                                </a>
                            </li>

                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{
                    "targetSelector": "#addUserStepConfirmation"
                  }'>
                                    <span class="step-icon step-icon-soft-dark">3</span>
                                    <div class="step-content">
                                        <span class="step-title">Confirmación</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <!-- End Step -->

                        <!-- Content Step Form -->
                        <div id="addUserStepFormContent">
                            <!-- Card -->
                            <div id="addUserStepProfile" class="card card-lg active">
                                <!-- Body -->
                                <div class="card-body">
                                    <!-- Form -->
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">Avatar</label>

                                        <div class="col-sm-9">
                                            <div class="d-flex align-items-center">
                                                <!-- Avatar -->
                                                <label class="avatar avatar-xl avatar-circle avatar-uploader me-5"
                                                    for="avatarUploader">
                                                    <img id="avatarImg" class="avatar-img"
                                                        src="{{ asset('img/160x160/img1.jpg') }}" alt="Image Description">

                                                    <input type="file" class="js-file-attach avatar-uploader-input"
                                                        id="avatarUploader"
                                                        data-hs-file-attach-options='{
                                    "textTarget": "#avatarImg",
                                    "mode": "image",
                                    "targetAttr": "src",
                                    "resetTarget": ".js-file-attach-reset-img",
                                    "resetImg": "{{ asset('img/160x160/img1.jpg') }}",
                                    "allowTypes": [".png", ".jpeg", ".jpg"]
                                 }'>

                                                    <span class="avatar-uploader-trigger">
                                                        <i class="bi-pencil avatar-uploader-icon shadow-sm"></i>
                                                    </span>
                                                </label>
                                                <!-- End Avatar -->

                                                <button type="button"
                                                    class="js-file-attach-reset-img btn btn-white">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <div class="row mb-4">
                                        <label for="firstNameLabel" class="col-sm-3 col-form-label form-label">Full name <i
                                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Displayed on public forums, such as Front."></i></label>

                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control" name="firstName"
                                                    id="firstNameLabel" placeholder="Clarice" aria-label="Clarice">
                                                <input type="text" class="form-control" name="lastName"
                                                    id="lastNameLabel" placeholder="Boone" aria-label="Boone">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <div class="row mb-4">
                                        <label for="emailLabel" class="col-sm-3 col-form-label form-label">Email</label>

                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="email" id="emailLabel"
                                                placeholder="clarice@site.com" aria-label="clarice@site.com">
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <div class="js-add-field row mb-4"
                                        data-hs-add-field-options='{
                          "template": "#addPhoneFieldTemplate",
                          "container": "#addPhoneFieldContainer",
                          "defaultCreated": 0
                        }'>
                                        <label for="phoneLabel" class="col-sm-3 col-form-label form-label">Phone <span
                                                class="form-label-secondary">(Optional)</span></label>

                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="js-input-mask form-control" name="phone"
                                                    id="phoneLabel" placeholder="+x(xxx)xxx-xx-xx"
                                                    aria-label="+x(xxx)xxx-xx-xx"
                                                    data-hs-mask-options='{
                                 "mask": "+0(000)000-00-00"
                               }'>

                                                <!-- Select -->
                                                <div class="tom-select-custom tom-select-custom-end">
                                                    <select class="js-select form-select" autocomplete="off"
                                                        data-hs-tom-select-options='{
                                    "searchInDropdown": false,
                                    "hideSearch": true,
                                    "dropdownWidth": "8rem"
                                  }'>
                                                        <option value="Mobile" selected>Mobile</option>
                                                        <option value="Home">Home</option>
                                                        <option value="Work">Work</option>
                                                        <option value="Fax">Fax</option>
                                                        <option value="Direct">Direct</option>
                                                    </select>
                                                </div>
                                                <!-- End Select -->
                                            </div>

                                            <!-- Container For Input Field -->
                                            <div id="addPhoneFieldContainer"></div>

                                            <a class="js-create-field form-link" href="javascript:;">
                                                <i class="bi-plus"></i> Add phone
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Add Phone Input Field -->
                                    <div id="addAddressFieldTemplate" style="display: none;">
                                        <div class="input-group-add-field">
                                            <input type="text" class="form-control" data-name="addressLine"
                                                placeholder="Your address" aria-label="Your address">

                                            <a class="js-delete-field input-group-add-field-delete" href="javascript:;">
                                                <i class="bi-x-lg"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Add Phone Input Field -->

                                    <!-- Add Phone Input Field -->
                                    <div id="addPhoneFieldTemplate" class="input-group-add-field" style="display: none;">
                                        <div class="input-group input-group-sm-vertical align-items-center">
                                            <input type="text" class="js-input-mask form-control"
                                                data-name="additionlPhone" placeholder="+x(xxx)xxx-xx-xx"
                                                aria-label="+x(xxx)xxx-xx-xx"
                                                data-hs-mask-options='{
                               "mask": "+0(000)000-00-00"
                             }'>

                                            <div class="input-group-append">
                                                <!-- Select -->
                                                <div class="tom-select-custom tom-select-custom-end">
                                                    <select class="js-select-dynamic form-select" autocomplete="off"
                                                        data-name="phoneSelect"
                                                        data-hs-tom-select-options='{
                                    "searchInDropdown": false,
                                    "hideSearch": true,
                                    "dropdownWidth": "8rem"
                                  }'>
                                                        <option value="Mobile" selected>Mobile</option>
                                                        <option value="Home">Home</option>
                                                        <option value="Work">Work</option>
                                                        <option value="Fax">Fax</option>
                                                        <option value="Direct">Direct</option>
                                                    </select>
                                                </div>
                                                <!-- End Select -->
                                            </div>
                                        </div>

                                        <a class="js-delete-field input-group-add-field-delete" href="javascript:;">
                                            <i class="bi-x-lg"></i>
                                        </a>
                                    </div>
                                    <!-- End Add Phone Input Field -->

                                    <!-- Form -->
                                    <div class="row mb-4">
                                        <label for="organizationLabel"
                                            class="col-sm-3 col-form-label form-label">Organization</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="organization"
                                                id="organizationLabel" placeholder="Htmlstream" aria-label="Htmlstream">
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <div class="row mb-4">
                                        <label for="departmentLabel"
                                            class="col-sm-3 col-form-label form-label">Department</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="department"
                                                id="departmentLabel" placeholder="Human resources"
                                                aria-label="Human resources">
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label form-label">Account type</label>

                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <!-- Radio Check -->
                                                <label class="form-control" for="userAccountTypeRadio1">
                                                    <span class="form-check">
                                                        <input type="radio" class="form-check-input"
                                                            name="userAccountTypeRadio" id="userAccountTypeRadio1">
                                                        <span class="form-check-label">Individual</span>
                                                    </span>
                                                </label>
                                                <!-- End Radio Check -->

                                                <!-- Radio Check -->
                                                <label class="form-control" for="userAccountTypeRadio2">
                                                    <span class="form-check">
                                                        <input type="radio" class="form-check-input"
                                                            name="userAccountTypeRadio" id="userAccountTypeRadio2">
                                                        <span class="form-check-label">Company</span>
                                                    </span>
                                                </label>
                                                <!-- End Radio Check -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form -->
                                </div>
                                <!-- End Body -->

                                <!-- Footer -->
                                <div class="card-footer d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-primary"
                                        data-hs-step-form-next-options='{
                            "targetSelector": "#addUserStepBillingAddress"
                          }'>
                                        Next <i class="bi-chevron-right"></i>
                                    </button>
                                </div>
                                <!-- End Footer -->
                            </div>
                            <!-- End Card -->

                            <div id="addUserStepBillingAddress" class="card card-lg" style="display: none;">
                                <!-- Body -->
                                <div class="card-body">
                                    <!-- Form -->
                                    <div class="row mb-4">
                                        <label for="locationLabel"
                                            class="col-sm-3 col-form-label form-label">Location</label>

                                        <div class="col-sm-9">
                                            <!-- Select -->
                                            <div class="tom-select-custom mb-4">
                                                <select class="js-select form-select" id="locationLabel">
                                                    <option value="AF"
                                                        data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ asset('vendor/flag-icon-css/flags/1x1/af.svg') }}" alt="Afghanistan Flag" /><span class="text-truncate">Afghanistan</span></span>'>
                                                        Afghanistan</option>
                                                    <option value="AX"
                                                        data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ asset('vendor/flag-icon-css/flags/1x1/ax.svg') }}" alt="Aland Islands Flag" /><span class="text-truncate">Aland Islands</span></span>'>
                                                        Aland Islands</option>
                                                </select>
                                            </div>
                                            <!-- End Select -->

                                            <div class="mb-4">
                                                <input type="text" class="form-control" name="city" id="cityLabel"
                                                    placeholder="City" aria-label="City">
                                            </div>

                                            <input type="text" class="form-control" name="state" id="stateLabel"
                                                placeholder="State" aria-label="State">
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <div class="row mb-4">
                                        <label for="addressLine1Label" class="col-sm-3 col-form-label form-label">Address
                                            line 1</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="addressLine1"
                                                id="addressLine1Label" placeholder="Your address"
                                                aria-label="Your address">
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <div class="js-add-field row mb-4"
                                        data-hs-add-field-options='{
                          "template": "#addAddressFieldTemplate",
                          "container": "#addAddressFieldContainer",
                          "defaultCreated": 0
                        }'>
                                        <label for="addressLine2Label" class="col-sm-3 col-form-label form-label">Address
                                            line 2 <span class="form-label-secondary">(Optional)</span></label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="addressLine2"
                                                id="addressLine2Label" placeholder="Your address"
                                                aria-label="Your address">

                                            <!-- Container For Input Field -->
                                            <div id="addAddressFieldContainer"></div>

                                            <a href="javascript:;" class="js-create-field form-link">
                                                <i class="bi-plus"></i> Add address
                                            </a>
                                        </div>
                                    </div>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <div class="row">
                                        <label for="zipCodeLabel" class="col-sm-3 col-form-label form-label">Zip code <i
                                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="You can find your code in a postal address."></i></label>

                                        <div class="col-sm-9">
                                            <input type="text" class="js-input-mask form-control" name="zipCode"
                                                id="zipCodeLabel" placeholder="Your zip code" aria-label="Your zip code"
                                                data-hs-mask-options='{
                               "mask": "AA0 0AA"
                             }'>
                                        </div>
                                    </div>
                                    <!-- End Form -->
                                </div>
                                <!-- End Body -->

                                <!-- Footer -->
                                <div class="card-footer d-flex align-items-center">
                                    <button type="button" class="btn btn-ghost-secondary"
                                        data-hs-step-form-prev-options='{
                       "targetSelector": "#addUserStepProfile"
                     }'>
                                        <i class="bi-chevron-left"></i> Previous step
                                    </button>

                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary"
                                            data-hs-step-form-next-options='{
                              "targetSelector": "#addUserStepConfirmation"
                            }'>
                                            Next <i class="bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- End Footer -->
                            </div>

                            <div id="addUserStepConfirmation" class="card card-lg" style="display: none;">
                                <!-- Profile Cover -->
                                <div class="profile-cover">
                                    <div class="profile-cover-img-wrapper">
                                        <img class="profile-cover-img" src="{{ asset('img/1920x400/img1.jpg') }}"
                                            alt="Image Description">
                                    </div>
                                </div>
                                <!-- End Profile Cover -->

                                <!-- Avatar -->
                                <div class="avatar avatar-xxl avatar-circle avatar-border-lg profile-cover-avatar">
                                    <img class="avatar-img" src="{{ asset('img/160x160/img9.jpg') }}"
                                        alt="Image Description">
                                </div>
                                <!-- End Avatar -->

                                <!-- Body -->
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-6 text-sm-end">Full name:</dt>
                                        <dd class="col-sm-6">Ella Lauda</dd>

                                        <dt class="col-sm-6 text-sm-end">Email:</dt>
                                        <dd class="col-sm-6">ella@site.com</dd>

                                        <dt class="col-sm-6 text-sm-end">Phone:</dt>
                                        <dd class="col-sm-6">+1 (609) 972-22-22</dd>

                                        <dt class="col-sm-6 text-sm-end">Organization:</dt>
                                        <dd class="col-sm-6">Htmlstream</dd>

                                        <dt class="col-sm-6 text-sm-end">Department:</dt>
                                        <dd class="col-sm-6">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Account type:</dt>
                                        <dd class="col-sm-6">Individual</dd>

                                        <dt class="col-sm-6 text-sm-end">Country:</dt>
                                        <dd class="col-sm-6"><img class="avatar avatar-xss avatar-circle me-1"
                                                src="{{ asset('vendor/flag-icon-css/flags/1x1/gb.svg') }}"
                                                alt="Great Britain Flag"> United Kingdom
                                        </dd>

                                        <dt class="col-sm-6 text-sm-end">City:</dt>
                                        <dd class="col-sm-6">London</dd>

                                        <dt class="col-sm-6 text-sm-end">State:</dt>
                                        <dd class="col-sm-6">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Address line 1:</dt>
                                        <dd class="col-sm-6">45 Roker Terrace, Latheronwheel</dd>

                                        <dt class="col-sm-6 text-sm-end">Address line 2:</dt>
                                        <dd class="col-sm-6">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Zip code:</dt>
                                        <dd class="col-sm-6">KW5 8NW</dd>
                                    </dl>
                                    <!-- End Row -->
                                </div>
                                <!-- End Body -->

                                <!-- Footer -->
                                <div class="card-footer d-sm-flex align-items-sm-center">
                                    <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0"
                                        data-hs-step-form-prev-options='{
                       "targetSelector": "#addUserStepBillingAddress"
                     }'>
                                        <i class="bi-chevron-left"></i> Previous step
                                    </button>

                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-white me-2">Save in drafts</button>
                                        <button id="addUserFinishBtn" type="button" class="btn btn-primary">Add
                                            user</button>
                                    </div>
                                </div>
                                <!-- End Footer -->
                            </div>
                        </div>
                        <!-- End Content Step Form -->

                        <!-- Message Body -->
                        <div id="successMessageContent" style="display:none;">
                            <div class="text-center">
                                <img class="img-fluid mb-3" src="{{ asset('svg/illustrations/oc-hi-five.svg') }}"
                                    alt="Image Description" data-hs-theme-appearance="default" style="max-width: 15rem;">
                                <img class="img-fluid mb-3" src="{{ asset('svg/illustrations-light/oc-hi-five.svg') }}"
                                    alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 15rem;">

                                <div class="mb-4">
                                    <h2>Successful!</h2>
                                    <p>New <span class="fw-semibold text-dark">Ella Lauda</span> user has been successfully
                                        created.</p>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <a class="btn btn-white me-3" href="./users.html">
                                        <i class="bi-chevron-left ms-1"></i> Back to users
                                    </a>
                                    <a class="btn btn-primary" href="./users-add-user.html">
                                        <i class="bi-person-plus-fill me-1"></i> Add new user
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Message Body -->
                    </div>
                </div>
                <!-- End Row -->
            </form>
            <!-- End Step Form -->
        </div>
        <!-- End Content -->
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/hs-file-attach/dist/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-step-form/dist/hs-step-form.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-add-field/dist/hs-add-field.min.js') }}"></script>
    <script src="{{ asset('vendor/imask/dist/imask.min.js') }}"></script>
    <script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>

    <script>
        (function() {
            window.onload = function() {

                // INITIALIZATION OF FILE ATTACH
                // =======================================================
                new HSFileAttach('.js-file-attach')


                // INITIALIZATION OF STEP FORM
                // =======================================================
                new HSStepForm('.js-step-form', {
                    finish: () => {
                        document.getElementById("addUserStepFormProgress").style.display = 'none'
                        document.getElementById("addUserStepProfile").style.display = 'none'
                        document.getElementById("addUserStepBillingAddress").style.display = 'none'
                        document.getElementById("addUserStepConfirmation").style.display = 'none'
                        document.getElementById("successMessageContent").style.display = 'block'
                        scrollToTop('#header');
                        const formContainer = document.getElementById('formContainer')
                    },
                    onNextStep: function() {
                        scrollToTop()
                    },
                    onPrevStep: function() {
                        scrollToTop()
                    }
                })

                function scrollToTop(el = '.js-step-form') {
                    el = document.querySelector(el)
                    window.scrollTo({
                        top: (el.getBoundingClientRect().top + window.scrollY) - 30,
                        left: 0,
                        behavior: 'smooth'
                    })
                }


                // INITIALIZATION OF ADD FIELD
                // =======================================================
                new HSAddField('.js-add-field', {
                    addedField: field => {
                        HSCore.components.HSTomSelect.init(field.querySelector('.js-select-dynamic'))
                        HSCore.components.HSMask.init(field.querySelector('.js-input-mask'))
                    }
                })


                // INITIALIZATION OF SELECT
                // =======================================================
                HSCore.components.HSTomSelect.init('.js-select', {
                    render: {
                        'option': function(data, escape) {
                            return data.optionTemplate || `<div>${data.text}</div>>`
                        },
                        'item': function(data, escape) {
                            return data.optionTemplate || `<div>${data.text}</div>>`
                        }
                    }
                })


                // INITIALIZATION OF INPUT MASK
                // =======================================================
                HSCore.components.HSMask.init('.js-input-mask')
            }
        })()
    </script>
@endpush
