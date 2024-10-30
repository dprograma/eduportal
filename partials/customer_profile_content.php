<div class="col-lg-9 col-md-8">
    <div class="mb-4">
        <h1 class="mb-0 h3">Profile</h1>
    </div>
    <?php if (isset($_GET['error'])): ?>
                     <div class="alert alert-<?= $_GET['type'] ?> alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                  <?php endif; ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-lg-5">
            <div class="mb-5">
                <h4 class="mb-1">Profile Picture</h4>
                <p class="mb-0 fs-6">Upload a picture to make your profile stand out and let people recognize your
                    comments and contributions easily!</p>
            </div>
            <div class="d-flex align-items-center">
                <img src="<?= file_exists($currentUser->profileimg) ? $currentUser->profileimg : 'assets/images/avatar/fallback.jpg'; ?>"
                    alt="avatar" class="avatar avatar-lg rounded-circle" />
                <div class="ms-4">
                    <h5 class="mb-0">Your photo</h5>
                    <small>Allowed *.jpeg, *.jpg, *.png, *.gif max size of 2 MB</small>
                </div>

            </div>
            <div class="col-lg-12">
                <label for="profileImgInput" class="form-label">Profile Image</label>
                <input type="file" form="updateInfoForm" class="form-control" id="profileImgInput" name="profileimg" />
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-lg-5">
            <div class="mb-5">
                <h4 class="mb-1">Account Information</h4>
                <p class="mb-0 fs-6">Personal information and address.</p>
            </div>
            <form class="row g-3 needs-validation">
                <div class="col-lg-6 col-md-12">
                    <label for="profileFirstNameInput" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="profileFirstNameInput" name="name"
                        value="<?= isset($currentUser->fullname) ? $currentUser->fullname : '' ?>" disabled />
                    <div class="invalid-feedback">Please enter full name.</div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <label for="profileUsernameInput" class="form-label">Username</label>
                    <input type="text" class="form-control" id="profileUsernameInput" name="username"
                        value="<?= isset($currentUser->username) ? $currentUser->username : '' ?>" disabled />
                    <div class="invalid-feedback">Please enter username.</div>
                </div>
                <div class="col-lg-6">
                    <label for="profileEmailInput" class="form-label">Email</label>
                    <input type="email" class="form-control" id="profileEmailInput" name="email"
                        value="<?= isset($currentUser->email) ? $currentUser->email : '' ?>" disabled />
                    <div class="invalid-feedback">Please enter email.</div>
                </div>
                <div class="col-lg-6">
                    <label for="profilePhoneInput" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="profilePhoneInput" name="phone"
                        value="<?= isset($currentUser->phone) ? $currentUser->phone : '' ?>" disabled />
                    <div class="invalid-feedback">Please enter phone.</div>
                </div>
                <p><span style="color:red;font-weight:bold;">*</span> Please contact customer service to update personal
                    information.</p>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-lg-5">
            <div class="mb-5">
                <h4 class="mb-1">Change Password</h4>
                <p class="mb-0 fs-6">Update your password to improve security.</p>
            </div>
            <form id="updateInfoForm" class="row g-3 needs-validation" enctype="multipart/form-data" method="post"
                novalidate>
                <div class="col-lg-6 col-md-12">
                    <label for="oldPasswordInput" class="form-label">Old password</label>
                    <input type="password" class="form-control" id="oldPasswordInput" name="old_password" />
                    <div class="invalid-feedback">Please enter your current password.</div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <label for="newPasswordInput" class="form-label">New password</label>
                    <input type="password" class="form-control" id="newPasswordInput" name="new_password" />
                    <div class="invalid-feedback">Please enter a new password.</div>
                </div>
                <div class="col-lg-6">
                    <label for="confirmNewPasswordInput" class="form-label">Confirm new password</label>
                    <input type="password" class="form-control" id="confirmNewPasswordInput"
                        name="confirm_new_password" />
                    <div class="invalid-feedback">Please confirm your new password.</div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="d-flex justify-content-center col-12 my-4">
            <button form="updateInfoForm" class="btn btn-primary me-2" name="profile" type="submit">Save Changes</button>
            <button form="updateInfoForm" class="btn btn-light" type="reset">Cancel</button>
        </div>
    </div>
</div>
</div>