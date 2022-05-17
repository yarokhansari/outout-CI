  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
     
    

        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Rewards </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">Add Rewards</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
     <form id="userfrm" action="<?php echo base_url(); ?>admin/Rewards/add" enctype="multipart/form-data" method="POST">
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
                  <label for="firstname">Name</label>
                  <input type="text" id="name" name="name" class="form-control">
                </div>
                <div class="form-group">
                  <label for="lastname">Level</label>
                  <input type="text" id="level" name="level" class="form-control">
                </div>
                <div class="form-group">
                  <label for="dob">Points</label>
                  <input type="text" id="points" name="points" class="form-control">
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <a href="<?php echo base_url(); ?>/admin/Rewards/index" class="btn btn-secondary">Cancel</a>
            <input type="submit" value="Create new rewards" class="btn btn-success">
          </div>
        </div>
     </form>
    </section>
    <!-- /.content -->
  </div>