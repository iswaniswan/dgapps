			<!-- Page header -->
			<div class="page-header page-header-light">
			  <div class="page-header-content header-elements-md-inline">
			    <div class="page-title d-flex">
			      <h4><span class="font-weight-semibold"><?=$this->lang->line('sales-order');?></span></h4>
			    </div>
			  </div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">

			  <div class="row">
			    <div class="col-xl-12">

			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <h5 class="card-title">Sales Order No. <?= $header->i_spb; ?>
			            <?php if($header->i_promo){ echo ' | '.$header->i_promo; } ?></h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>

			        <div class="card-body">
			          <div class="row">
			            <div class="col-xl-6">
			              <div class="row">
			                <label class="col-form-label col-lg-12">
			                  <h4>Customer</h4>
			                </label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-2">Name</label>
			                <label class="col-form-label col-lg-10">: <?= $header->e_customer_name; ?></label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-2">Address</label>
			                <label class="col-form-label col-lg-10">: <?= $header->e_customer_address; ?></label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-2">City</label>
			                <label class="col-form-label col-lg-10">: </label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-2">Phone No</label>
			                <label class="col-form-label col-lg-10">: <?= $header->e_phone_number; ?></label>
			              </div>
			            </div>
			            <div class="col-xl-6">
			              <div class="row">
			                <label class="col-form-label col-lg-12">
			                  <h4>STAFF</h4>
			                </label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-2">Staff ID</label>
			                <label class="col-form-label col-lg-10">: <?= $header->i_staff; ?></label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-2">Name</label>
			                <label class="col-form-label col-lg-10">: <?= $header->e_name; ?></label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-2">Phone No</label>
			                <label class="col-form-label col-lg-10">: <?= $header->phone; ?></label>
			              </div>
			            </div>
			          </div>
			        </div>
			        <div class="card-body">
			          <div class="table-responsive">
			            <?php 
									if($header->f_spb_cancel == 't'){
										$status = 'Cancel';
									}elseif ($header->f_status_transfer == 'f') {
										$status = 'Pending';
									}elseif ($header->f_status_transfer == 't') {
										$status = 'Transfer';
									}
									?>
			            <div class="d-flex justify-content-between align-items-center">
			              <h4> Status: <?= $status; ?> </h4>
			              <h4> Product List </h4>
			              Created time: <?= date("d F Y H:i:s", strtotime($header->createdat)); ?>
			            </div>
			            <table class="table table-striped table-bordered">
			              <thead>
			                <tr>
			                  <th>No</th>
			                  <th>Product</th>
			                  <th>Product Name</th>
			                  <th>Price/Unit</th>
			                  <th>Order Qty</th>
			                  <th>Subtotal</th>
			                  <th>Comment</th>
			                </tr>
			              </thead>
			              <tbody>
			                <?php $no = 1; foreach ($detail as $row) { ?>
			                <tr>
			                  <td><?= $no; ?></td>
			                  <td><?= $row->i_product; ?></td>
			                  <td><?= $row->e_product_name; ?></td>
			                  <td><?= number_format($row->v_unit_price); ?></td>
			                  <td><?= number_format($row->n_order); ?></td>
			                  <td class="text-right"><?= number_format($row->n_order * $row->v_unit_price); ?></td>
			                  <td><?= $row->e_remark; ?></td>
			                </tr>
			                <?php 
			                $no++;
			            	} ?>
			                <tr>
			                  <td colspan="3">Comment</td>
			                  <td colspan="2">Sales Order Total</td>
			                  <td class="text-right"> <?= number_format($header->v_spb_gross); ?> </td>
			                  <td></td>
			                </tr>
			                <tr>
			                  <td colspan="3" rowspan="2"><textarea rows="3" cols="3" class="form-control"
			                      readonly><?= $header->e_remark; ?></textarea></td>
			                  <td colspan="2">Discount Total</td>
			                  <td class="text-right"> <?= number_format($header->v_spb_discounttotal); ?> </td>
			                  <td></td>
			                </tr>
			                <tr>
			                  <td colspan="2">Nett Total</td>
			                  <td class="text-right"> <?= number_format($header->v_spb_netto); ?> </td>
			                  <td></td>
			                </tr>
			              </tbody>
			            </table>
			            <br>
			            <div class="text-center">
			              <?php if ($header->f_status_transfer == 'f' && $header->f_spb_cancel == 'f') { ?>
			              <button type="submit" class="btn bg-danger-400"
			                onclick="cancel('<?= $header->i_spb; ?>','<?= $header->i_area; ?>');">Cancel Sales Order <i
			                  class="fa fa-trash ml-2"></i></button>
			              <?php } ?>
			            </div>
			          </div>
			        </div>
			      </div>

			    </div>

			  </div>

			</div>
			<!-- /content area -->