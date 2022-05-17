  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>List of Category </h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">User Notifications</li>
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
                          <h4 class="card-label"><i class="fa fa-calender text-primary"></i> User Notifications </h4>
                      </span>
                  </div>
                 
              </div>   
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="usermedia" class="table table-bordered table-hover">
                  <thead>
                    <tr>
					  <th>Sr No.</th>
                      <th>Media</th>
                      <th>Description</th>
                      <th>Is Read?</th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php $count = 0 ; foreach($allNotifications as $notification) { $count++; ?>
                             <tr>
								<td><?php echo $count; ?></td>
                                <td><a href="<?php echo base_url(). "admin/UserMedia/view/" . $notification['media_id']; ?>" target="_blank">Open Link</a></td>
                                <td><?php echo $notification['description']; ?></td>
								<td><?php if( $notification['is_read'] == '0' ) echo "Pending"; else echo "Read"; ?></td>
                             </tr>

                        <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
					  <th>Sr No.</th>
                      <th>Media</th>
                      <th>Description</th>
                      <th>Is Read?</th>
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

    </section>
    <!-- /.content -->
  </div>