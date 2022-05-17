  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">

      <?php if ($this->session->flashdata('success')) { ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-check"></i> Success!</h4>
              <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-ban"></i> Alert!</h4>
              <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php } ?>

        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Song </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">Add Song</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <form id="songsfrm" action="<?php echo base_url(); ?>admin/Songs/add" enctype="multipart/form-data" method="POST">
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
                    <label for="title">Title <label class="serror">*</label></label>
                    <input type="text" id="title" name="title" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="songupload">Upload <label class="serror">*</label></label>
                    <input type="file" id="songupload" name="songupload" class="form-control">
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <a href="<?php echo base_url(); ?>admin/Songs" class="btn btn-secondary">Cancel</a>
            <input type="submit" value="Add Song" class="btn btn-success">
          </div>
        </div>
      </form>
    </section>
    <!-- /.content -->
  </div>