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
              <li class="breadcrumb-item active">Transaction</li>
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
                          <h4 class="card-label"><i class="fa fa-money-bill text-primary"></i> Transaction </h4>
                      </span>
                  </div>
                 
              </div>   
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="usermedia" class="table table-bordered table-hover">
                  <thead>
                    <tr>
					  <th>Sr No.</th>
					  <th>Date</th>
                      <th>Amount ( £ )</th>
                      <th>Payment Type</th>
					  <th>Transaction Type</th>
					  <th>Notes</th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php $count = 0 ; foreach($allTransaction as $transaction) { $count++; ?>
                             <tr>
								<td><?php echo $count; ?></td>
                                <td><?php echo date('d-m-Y',strtotime($transaction['payment_date'])); ?></td>
                                <td><?php echo $transaction['amount']; ?></td>
								<td><?php if( $transaction['payment_type'] == '0' ) echo "Wallet"; else echo "InApp Purchase"; ?></td>
								<td><?php if( $transaction['trans_type'] == '0' ) echo "Debit"; else echo "Credit"; ?></td>
								<td><?php echo $transaction['notes']; ?></td>
                             </tr>

                        <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
					  <th>Sr No.</th>
					  <th>Date</th>
                      <th>Amount ( £ )</th>
                      <th>Payment Type</th>
					  <th>Transaction Type</th>
					  <th>Notes</th>
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