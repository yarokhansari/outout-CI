  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>List Of User Friends </h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active"> User Friends</li>
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
                          <h4 class="card-label"><i class="fa fa-users text-primary"></i> User Friends </h4>
                      </span>
                  </div>
                 
              </div>   
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="userfriends" class="table table-bordered table-hover">
                  <thead>
                  <tr>
					<th>Sr No.</th>
                    <th>Request Send To</th>
                    <th>Status</th>
                    <th>Follow</th>
                  </tr>
                  </thead>
                  <tbody>
						<?php $count = 0; ?>
                        <?php foreach( $allUsers as $user ) { $count++ ;?>
                            <tr>
							  <td><?php echo $count; ?></td>
                              <td><?php echo $user['first_name'] . " " . $user['last_name'];?></td>
                              <td><?php if($user['status'] == '0') echo "Pending"; else if( $user['status'] == '1' ) echo "Accepted"; else echo "Rejected"; ?></td>
                              <td><?php if($user['is_follow'] == '0') echo "Not Follow"; else echo "Follow"; ?></td>
                            </tr>
                        <?php } ?>
                  </tbody>
                  <tfoot>
                  <tr>
					<th>Sr No.</th>
                    <th>Request Send To</th>
                    <th>Status</th>
                    <th>Follow</th>
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
                <h5>Are you sure you want to delete this user friend?</h5>
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