  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add User </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">Add User</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
     <form id="userfrm" action="<?php echo base_url(); ?>admin/User/add" enctype="multipart/form-data" method="POST">
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
                  <input type="text" id="firstname" name="firstname" class="form-control">
                </div>
                <div class="form-group">
                  <label for="lastname">Last Name</label>
                  <input type="text" id="lastname" name="lastname" class="form-control">
                </div>
                <div class="form-group">
                  <label for="dob">Date Of Birth</label>
                  <input type="text" id="dob" name="dob" class="form-control">
                </div>
                <div class="form-group">
                  <label for="phonenumber">Phone Number</label>
                  <input type="text" id="phonenumber" name="phonenumber" class="form-control">
                </div>
				<div class="form-group">
                  <label for="countrycode">Country Code</label>
                  <input type="text" id="countrycode" name="countrycode" class="form-control">
                </div>
                <div class="form-group">
                  <label for="gender">Gender</label>
                  <select id="gender" name="gender" class="form-control">
					<option value=""></option>
					<option value="1">Male</option>
					<option value="2">Female</option>
				  </select>
                </div>
                <div class="form-group">
                  <label for="emailaddress">Email Address</label>
                  <input type="email" id="emailaddress" name="emailaddress" class="form-control">
                </div>
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" id="username" name="username" class="form-control">
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" id="password" name="password" class="form-control">
                </div>
				<div class="form-group">
                  <label for="account_type">Account Type</label>
                  <select id="account_type" name="account_type" class="form-control">
					<option value=""></option>
					<option value="0">Normal</option>
					<option value="1">Business</option>
					<option value="2">Premium</option>
				  </select>
                </div>
				
				<div class="form-group category">
                  <label for="businesscategory">Category</label>
                  <select id="businesscategory" name="businesscategory" class="form-control">
					<option value=""></option>
					<?php foreach( $allCategory as $category ) { ?>
						<option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
					<?php } ?>
				  </select>
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
            <input type="submit" value="Create new user" class="btn btn-success">
          </div>
        </div>
     </form>
    </section>
    <!-- /.content -->
  </div>