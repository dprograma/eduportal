<div class="col-lg-9 col-md-8">
    <div class="mb-4">
        <h1 class="mb-0 h3">Profile</h1>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-lg-5">
            <div class="mb-5">
                <h4 class="mb-1">Profile Picture</h4>
                <p class="mb-0 fs-6">Upload a picture to make your profile stand out and let people recognize your
                    comments and contributions easily!</p>
            </div>
            <div class="d-flex align-items-center">
                <img src="assets/images/avatar/avatar-1.jpg" alt="avatar" class="avatar avatar-lg rounded-circle" />
                <div class="ms-4">
                    <h5 class="mb-0">Your photo</h5>
                    <small>Allowed *.jpeg, *.jpg, *.png, *.gif max size of 4 MB</small>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-lg-5">
            <div class="mb-5">
                <h4 class="mb-1">Account Information</h4>
                <p class="mb-0 fs-6">Edit your personal information and address.</p>
            </div>
            <form class="row g-3 needs-validation" novalidate>
                <div class="col-lg-6 col-md-12">
                    <label for="profileFirstNameInput" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="profileFirstNameInput" value="Jitu" required />
                    <div class="invalid-feedback">Please enter firstname.</div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <label for="profileLastNameInput" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="profileLastNameInput" value="Chauhan" required />
                    <div class="invalid-feedback">Please enter lastname.</div>
                </div>
                <div class="col-lg-6">
                    <label for="profilePhoneInput" class="form-label">Phone</label>
                    <input type="text" class="form-control input-phone" id="profilePhoneInput"
                        placeholder="+1 4XX XXX XXXX" required />
                    <div class="invalid-feedback">Please enter phone.</div>
                </div>
                <div class="col-lg-6">
                    <label for="profileBirthdayInput" class="form-label">Birthday</label>
                    <input type="text" class="form-control input-date" id="profileBirthdayInput"
                        placeholder="dd/mm/yyyy" required />
                    <div class="invalid-feedback">Please enter birthday.</div>
                </div>
                <div class="col-lg-12">
                    <label for="profileAddressInput" class="form-label">Address Line</label>
                    <input type="text" class="form-control" id="profileAddressInput" required />
                    <div class="invalid-feedback">Please enter addredss.</div>
                </div>
                <div class="col-lg-3">
                    <label for="profileCountryInput" class="form-label">Country</label>
                    <select class="form-select" id="profileCountryInput" required>
                        <option selected disabled value="">Choose...</option>
                        <option selected value="">India</option>
                        <option value="Australia">Australia</option>
                        <option value="Canada">Canada</option>
                        <option value="Germany">Germany</option>
                    </select>
                    <div class="invalid-feedback">Please select state.</div>
                </div>
                <div class="col-lg-3">
                    <label for="profileStateInput" class="form-label">State / Region</label>
                    <select class="form-select" id="profileStateInput" required>
                        <option selected disabled value="">Choose...</option>
                        <option selected value="">Gujarat</option>
                        <option value="Rajasthan">Rajasthan</option>
                        <option value="Goa">Goa</option>
                        <option value="Maharashtra">Maharashtra</option>
                    </select>
                    <div class="invalid-feedback">Please select state / region.</div>
                </div>
                <div class="col-lg-3">
                    <label for="profileCityInput" class="form-label">State</label>
                    <select class="form-select" id="profileCityInput" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="Ahmedabad" selected="">Ahmedabad</option>
                        <option value="Surat">Surat</option>
                        <option value="Vapi">Vapi</option>
                        <option value="Rajkot">Rajkot</option>
                    </select>
                    <div class="invalid-feedback">Please select a valid city.</div>
                </div>
                <div class="col-lg-3">
                    <label for="profilezipInput" class="form-label">Zip/Code</label>
                    <input type="text" class="form-control" id="profilezipInput" required />
                    <div class="invalid-feedback">Please provide a zip.</div>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-primary me-2" type="submit">Save Changes</button>
                    <button class="btn btn-light" type="submit">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>