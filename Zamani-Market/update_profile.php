<div class="modal fade" id="editProfileModal" tabindex="-1">

  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="update_profile.php">

        <div class="modal-body">

          <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control"
                   value="<?php echo $user['full_name']; ?>" required>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo $user['email']; ?>" required>
          </div>

          <div class="mb-3">
            <label>Phone Number</label>
            <input type="text" name="phone_number" class="form-control"
                   value="<?php echo $user['phone_number']; ?>">
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="update_profile" class="btn btn-success">
            Save Changes
          </button>
        </div>

      </form>

    </div>
  </div>

</div>