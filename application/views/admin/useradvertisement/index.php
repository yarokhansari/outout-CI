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
              <li class="breadcrumb-item active"> User Advertisement</li>
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
                          <h4 class="card-label"><i class="fa fa-users text-primary"></i> User Advertisement </h4>
                      </span>
                  </div>
                 
              </div>   
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="useradvertisement" class="table table-bordered table-hover">
                  <thead>
					  <tr>
						<th>Sr No.</th>
						<th>Title</th>
						<th>Type</th>
						<th>Link</th>
						<th>Status</th>
					  </tr>
                  </thead>
                  <tbody>
						<?php $count = 0; ?>
						<?php if( !empty( $allAdvertisement ) ) { ?>
							<?php foreach( $allAdvertisement as $advertisement ) { $count++ ;?>
								<tr>
								  <td><?php echo $count; ?></td>
								  <td><?php echo $advertisement['title']; ?></td>
								  <td><?php if($advertisement['type'] == '0') echo "Image"; else echo "Video"; ?></td>
								  <td><a href="<?php echo $advertisement['link']; ?>" title="_blank">Advertisement Link</td>
								  <td><?php if( $advertisement['status'] != "0" ) echo "Active"; else echo "In Active"; ?></td>
								</tr>
							<?php } ?>
						<?php } ?>
                  </tbody>
                  <tfoot>
					  <tr>
						<th>Sr No.</th>
						<th>Title</th>
						<th>Type</th>
						<th>Link</th>
						<th>Status</th>
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