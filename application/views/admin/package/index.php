  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>List of Packages </h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">Packages</li>
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
                          <h4 class="card-label"><i class="fa fa-money-bill text-primary"></i> Packages </h4>
                      </span>
                  </div>
                  <div class="card-toolbar">
                        <a href="<?php echo base_url('admin/Package/add') ?>" class="btn btn-sm btn-primary font-weight-bold pull-right float-right">
                        <i class="fa fa-plus"></i> Add</a>
                  </div>
              </div>   
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="packages" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Duration</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                        <?php foreach($allPackages as $package) { ?>
                             <tr>
                                <td><?php echo $package['name']; ?></td>
                                <td><?php echo $package['price']; ?></td>
                                <td><?php if($package['duration'] == '0') echo 'Per Month'; else echo 'Per Year'; ?></td>
                                <td>
                                  <a href="<?php echo base_url(); ?>admin/Package/edit/<?php echo $package['id']; ?>"><i class="fa fa-edit"></i></a>
                                  <a data-href="<?php echo base_url(); ?>admin/Package/delete/<?php echo $package['id']; ?>" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>
                                </td>
                             </tr>

                        <?php } ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Duration</th>
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
    </section>
    <!-- /.content -->
  </div>