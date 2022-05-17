  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>List Of Users </h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active"> Users</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                  <div class="card-title">
                      <span class="card-icon">
                          <h4 class="card-label"><i class="fa fa-users text-primary"></i> Users </h4>
                      </span>
                  </div>
                  <div class="card-toolbar">
                        <a href="<?php echo base_url('admin/User/add') ?>" class="btn btn-sm btn-primary font-weight-bold pull-right float-right">
                        <i class="fa fa-plus"></i> Add</a>
                  </div>
              </div>   
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="users" class="table table-bordered table-hover">
                  <thead>
                  <tr>
					<th>Sr No.</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Gender</th>
                    <th>Username</th>
                    <th>Account Type</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
						<?php $count = 0; ?>
                        <?php foreach( $allUsers as $user ) { $count++ ;?>
                            <tr>
							  <td><?php echo $count; ?></td>
                              <td><?php echo $user['first_name'] . " " . $user['last_name']; ?></td>
                              <td><?php echo $user['email']; ?></td>
                              <td><?php if($user['gender'] == '1') echo "Male"; else echo "Female"; ?></td>
                              <td><?php if( $user['username'] != "" ) echo $user['username']; else echo " - "; ?></td>
                              <td><?php if($user['account_type'] == '1') echo "Business Account"; else if( $user['account_type'] == '2' ) echo "Premium Account"; else echo "Normal Account"; ?></td>
                              <td>
                                <a href="<?php echo base_url(); ?>admin/User/view/<?php echo $user['id']; ?>" title="View User"><i class="fa fa-eye"></i></a>
                                <a href="<?php echo base_url(); ?>admin/User/edit/<?php echo $user['id']; ?>" title="Edit User"><i class="fa fa-edit"></i></a>
                                <a data-href="<?php echo base_url(); ?>admin/User/delete/<?php echo $user['id']; ?>" title="Delete User" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>
                                <a href="<?php echo base_url(); ?>admin/UserEvents/index/<?php echo $user['id']; ?>" title="View Events" target="_blank"><i class="fa fa-calendar"></i></a>
								<a href="<?php echo base_url(); ?>admin/UserFriends/index/<?php echo $user['id']; ?>" title="View Friends" target="_blank"><i class="fa fa-user"></i></a>
								<a href="<?php echo base_url(); ?>admin/UserAdvertisement/index/<?php echo $user['id']; ?>" title="View Advertisement" target="_blank"><i class="fas fa-ad"></i></a>
								<a href="<?php echo base_url(); ?>admin/UserMedia/index/<?php echo $user['id']; ?>" title="View Posts" target="_blank"><i class="fas fa-blog"></i></a>
								<a href="<?php echo base_url(); ?>admin/UserNotification/index/<?php echo $user['id']; ?>" title="View Notification" target="_blank"><i class="fas fa-bell"></i></a>
								<a href="<?php echo base_url(); ?>admin/UserTransaction/index/<?php echo $user['id']; ?>" title="View Transaction" target="_blank"><i class="fas fa-money-bill"></i></a>

							  </td>
                            </tr>
                        <?php } ?>
                  </tbody>
                  <tfoot>
                  <tr>
					<th>Sr No.</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Gender</th>
                    <th>Username</th>
                    <th>Account Type</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

           
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
       <!-- /.container-fluid -->
       <div class="modal modal-danger danger fade" id="confirm-delete">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Delete</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true"><i class="ki ki-close"></i></span>
                </button>
              </div>
              <div class="modal-body">
                <h5>Are you sure you want to delete this user?</h5>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold pull-left" data-dismiss="modal">Close</button>
                <a href="#" class="btn btn-danger font-weight-bold danger">Delete</a>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>