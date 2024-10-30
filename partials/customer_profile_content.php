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
                <img src="<?= $currentUser->profileimg ?>" alt="avatar" class="avatar avatar-lg rounded-circle" />
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
            <form class="row g-3 needs-validation" enctype="multipart/form-data" method="post" novalidate>
                <div class="col-lg-6 col-md-12">
                    <label for="profileFirstNameInput" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="profileFirstNameInput" name="name" value="<?= $currentUser->fullname ?>" required />
                    <div class="invalid-feedback">Please enter firstname.</div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <label for="profileUsernameInput" class="form-label">Username</label>
                    <input type="text" class="form-control" id="profileUsernameInput" name="username" value="<?= $currentUser->username ?>" required />
                    <div class="invalid-feedback">Please enter username.</div>
                </div>
                <div class="col-lg-6">
                    <label for="profileEmailInput" class="form-label">Email</label>
                    <input type="email" class="form-control" id="profileEmailInput" name="email" value="<?= $currentUser->email ?>" required />
                    <div class="invalid-feedback">Please enter email.</div>
                </div>
                <div class="col-lg-6">
                    <label for="profilePhoneInput" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="profilePhoneInput" name="phone" placeholder="+1 4XX XXX XXXX" value="<?= $currentUser->phone ?>" required />
                    <div class="invalid-feedback">Please enter phone.</div>
                </div>
                <div class="col-lg-12">
                    <label for="profileImgInput" class="form-label">Profile Image</label>
                    <input type="file" class="form-control" id="profileImgInput" name="profileimg" />
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-primary me-2" name="profile" type="submit">Save Changes</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
