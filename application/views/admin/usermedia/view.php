  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Media</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">View Media</li>
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
                    <label for="mediatype">Media Type</label>
                    <input type="text" id="mediatype" name="mediatype" class="form-control" value="<?php if($info[0]['media_type'] == '0') echo "Image"; else echo "Video"; ?>" disabled>
                  </div>
				  <?php if($info[0]['media_type'] == '0') { ?>
                  <div class="form-group">
                    <label for="mediaurl">URL</label>
                    <img src="<?php echo $info[0]['media_url']; ?>" height="200" width="200">
                  </div>
				  <?php }else { ?>
				  <div class="form-group">
						<video width="320" height="240" autoplay>
						  <source src="<?php echo $info[0]['media_url']; ?>" type="video/<?php echo $info[0]['media_extension']; ?>">
						Your browser does not support the video tag.
						</video>
				  </div>
				  <?php } ?>
                  <div class="form-group">
                    <label for="caption">Caption</label>
                    <input type="text" id="caption" name="caption" class="form-control" value="<?php echo $info[0]['caption']; ?>" disabled>
                  </div>
				  <div class="form-group">
                    <label for="likes">Likes</label>
                    <input type="text" id="likes" name="likes" class="form-control" value="<?php echo $info[0]['likes']; ?>" disabled>
                  </div>
				  <div class="form-group">
                    <label for="views">Views</label>
                    <input type="text" id="views" name="views" class="form-control" value="<?php echo $info[0]['views']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control" disabled>
                        <option value=""></option>
                        <option value="0" <?php 
                        if ($info[0]["status"] == "0") {
                            echo "selected";
                        }
                        ?>>Active</option>
                        <option value="1" <?php 
                        if ($info[0]["status"] == "1") {
                            echo "selected";
                        }
                        ?>>InActive</option>
                    </select>
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