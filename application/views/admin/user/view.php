  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View User </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">View User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
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
                  <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo $getUser[0]['first_name']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo $getUser[0]['last_name']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="dob">Date Of Birth</label>
                    <input type="text" id="dob" name="dob" class="form-control" value="<?php echo date('d-m-Y',strtotime($getUser[0]['dob'])); ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-control" disabled>
                        <option value=""></option>
                        <option value="1" <?php 
                        if ($getUser[0]["gender"] == "1") {
                            echo "selected";
                        }
                        ?>>Male</option>
                        <option value="2" <?php 
                        if ($getUser[0]["gender"] == "2") {
                            echo "selected";
                        }
                        ?>>Female</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="phonenumber">Phone Number</label>
                    <input type="text" id="phonenumber" name="phonenumber" class="form-control" value="<?php echo $getUser[0]['phone_number']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="emailaddress">Email Address</label>
                    <input type="email" id="emailaddress" name="emailaddress" class="form-control" value="<?php echo $getUser[0]['email']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="emailaddress">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $getUser[0]['username']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="is_business">Account Type</label>
                    <select id="is_business" name="is_business" class="form-control" disabled>
                        <option value=""></option>
                        <option value="0" <?php 
                        if ($getUser[0]["is_business"] == "0") {
                            echo "selected";
                        }
                        ?>>Business Account</option>
                        <option  value="1" <?php 
                        if ($getUser[0]["is_business"] == "1") {
                            echo "selected";
                        }
                        ?>>Premium Account</option>
                        <option value="2" <?php 
                        if ($getUser[0]["is_business"] == "2") {
                            echo "selected";
                        }
                        ?>>Normal Account</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" value="<?php echo $getUser[0]['latitude']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" value="<?php echo $getUser[0]['longitude']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" value="<?php echo $getUser[0]['city']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="profile_image">Profile Image</label>
                    <img src="<?php echo $getUser[0]['profile_image']; ?>" title="<?php echo $getUser[0]['username']; ?>" height="200" width="200" />
                  </div>
				  <div class="form-group">
                    <label for="wallet">Wallet Balance</label>
                    <input type="text" id="wallet" name="wallet" class="form-control" value="<?php echo $getUser[0]['wallet']; ?>" disabled/>
                  </div>
                 
              </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
    </section>
    <!-- /.content -->
  </div>