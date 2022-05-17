  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit User </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">Edit User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <form id="userfrm" action="<?php echo base_url(); ?>admin/User/update" enctype="multipart/form-data" method="POST">
          <div class="row">
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">General</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
				  <input type="hidden" name="id" id="id" value="<?php echo $info[0]['id']; ?>" />
                  <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $info[0]['first_name']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo $info[0]['last_name']; ?>" >
                  </div>
                  <div class="form-group">
                    <label for="dob">Date Of Birth</label>
                    <input type="date" id="dob" name="dob" class="form-control" value="<?php echo $info[0]['dob']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-control custom-select">
                    <option selected disabled>Select one</option>
                    <option value="1" <?php 
                        if ($info[0]["gender"] == "1") {
                            echo "selected";
                        }
                        ?>>Male</option>
                        <option value="2" <?php
                        if ($info[0]["gender"] == "2") {
                            echo "selected";
                        }
                        ?>>Female</option>
                   </select>
                  </div>
                  <div class="form-group">
                    <label for="phonenumber">Phone Number</label>
                    <input type="text" id="phonenumber" name="phonenumber" class="form-control" value="<?php echo $info[0]['phone_number']; ?>">
                  </div>
                  <div class="form-group">
                    <label for="countrycode">Country Code</label>
					<input type="text" id="countrycode" name="countrycode" class="form-control" value="<?php echo $info[0]['country_code']; ?>">
                  </div>
                  
                
              </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <a href="<?php echo base_url(); ?>/admin/User" class="btn btn-secondary">Cancel</a>
              <input type="submit" value="Update" class="btn btn-success">
            </div>
          </div>
      </form>
    </section>
    <!-- /.content -->
  </div>