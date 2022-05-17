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
              <li class="breadcrumb-item active">Settings</li>
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
                          <h4 class="card-label"><i class="fa fa-cog text-primary"></i> Settings </h4>
                      </span>
                  </div>
                  
              </div>   
              
              <!-- /.card-header -->
              <div class="card-body">
				<span style="color:red;"><strong>Commission value is in %</strong></span>
                <table id="settings" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Setting Name</th>
                    <th>Setting Value</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                        <?php foreach($allSettings as $setting) { ?>
                             <tr>
                                <td><?php echo $setting['name']; ?></td>
                                <td><?php echo $setting['value']; ?></td>
                                <td>
                                  <a href="<?php echo base_url(); ?>admin/Setting/edit/<?php echo $setting['id']; ?>"><i class="fa fa-edit"></i></a>
                                </td>
                             </tr>

                        <?php } ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Setting Name</th>
                    <th>Setting Value</th>
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
      
    </section>
    <!-- /.content -->
  </div>