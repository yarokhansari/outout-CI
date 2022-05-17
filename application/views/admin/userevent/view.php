  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Event </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="breadcrumb-item active">View Event</li>
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
                    <label for="eventname">Name</label>
                    <input type="text" class="form-control" value="<?php echo $eventDetails[0]['event_name']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="lastname">Date</label>
                    <input type="text" class="form-control" value="<?php echo date('d.m.Y',strtotime($eventDetails[0]['event_date'])); ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" value="<?php echo $eventDetails[0]['event_city']; ?>" disabled>
                  </div>
				  
				   <?php 
					if( !empty($userdetails) ) { ?>
					   <div class="form-group">
						 <label for="invites">Invited Person</label>
						 <?php foreach ( $userdetails as $user ) { ?>
							<input type="text" class="form-control" value="<?php echo $user['first_name'] . " " . $user['last_name']; ?>" disabled /><br/>
						 <?php } ?>
					  </div>
                  <?php } ?>
                  <div class="form-group">
                    <label for="eventtype">Type</label>
                    <input type="text" class="form-control" value="<?php if($eventDetails[0]['event_type'] == "0") echo "Public"; else echo "Private"; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="price">Rate</label>
                    <input type="text" class="form-control" value="<?php echo $eventDetails[0]['symbol'] . " " . $eventDetails[0]['price']; ?>" disabled>
                  </div>
                  <div class="form-group">
                    <label for="additional_info">Additional Information</label>
                    <textarea class="form-control" disabled><?php echo $eventDetails[0]['additional_info']; ?></textarea>
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