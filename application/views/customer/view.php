			<!-- Page header -->
			<div class="page-header page-header-light">
			  <div class="page-header-content header-elements-md-inline">
			    <div class="page-title d-flex">
			      <h4><span class="font-weight-semibold"><?=$this->lang->line('customer');?></span></h4>
			    </div>
			  </div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">

			  <div class="row">
			    <div class="col-xl-8">

			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <h5 class="card-title"><?=$this->lang->line('customer-information');?> </h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>

			        <div class="card-body">
			          <div class="row">
			            <div class="col-xl-4">
			              <a href="#" class="d-inline-block mb-3" onclick="return false;">
			                <img src="<?=base_url();?>global_assets/images/placeholders/placeholder.jpg" class="rounded-round"
			                  alt="" width="150" height="150">
			              </a>
			            </div>
			            <div class="col-xl-8">
			              <div class="row">
			                <label class="col-form-label col-lg-3">Customer Name</label>
			                <label class="col-form-label col-lg-9">: <?=$data_customer->e_customer_name;?></label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-3">Customer ID</label>
			                <label class="col-form-label col-lg-9">: <?=$data_customer->i_customer;?></label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-3">Company Name</label>
			                <label class="col-form-label col-lg-9">: <?=$data_customer->e_company_name;?></label>
			              </div>
			              <div class="row">
			                <label class="col-form-label col-lg-3">Address</label>
			                <label class="col-form-label col-lg-9">: <?=$data_customer->e_customer_address;?> <a href="#"
			                    id="edit" class="fas fa-edit"></a></label>
			                 <input type="hidden" id="address" value="<?=$data_customer->e_customer_address;?>">
			              </div>
			            </div>
			          </div>
			        </div>
			      </div>

			    </div>
			    <div class="col-xl-4">

			      <div class="card card-collapsed">
			        <div class="card-header header-elements-inline">
			          <h5 class="card-title">Maps </h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>

			        <div class="card-body map-container" id="maps">

			        </div>
			      </div>

			    </div>

			  </div>
                <div class="row">
                <div class="col-xl-12">

                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title"><?=$this->lang->line('location-list');?></h5>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3" style="display: flex">
                            <a href="#" id="add-location" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modal-add-new-location">
                                <span class="fas fa-plus"></span>
                            </a>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="table-location-list">
                            <thead>
                            <tr>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Keterangan</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                </div>

                </div>

                <div class="row">
                                <div class="col-xl-12">

                                  <div class="card">
                                    <div class="card-header header-elements-inline">
                                      <h5 class="card-title"><?=$this->lang->line('activity-list');?></h5>
                                      <div class="header-elements">
                                        <div class="list-icons">
                                          <a class="list-icons-item" data-action="collapse"></a>
                                        </div>
                                      </div>
                                    </div>

                                    <div class="card-body">
                                      <table class="table table-striped table-hover table-bordered" id="serverside">
                                        <thead>
                                          <tr>
                                            <th>Staff Name</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                            <th>Duration</th>
                                            <th>&nbsp;</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>

                                </div>

                              </div>

			</div>
			<!-- /content area -->


<!-- modal tambah lokasi koordinat -->
<div class="modal fade" id="modal-add-new-location" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= site_url('customer/create_new_location') ?>" method="post">
                <div class="modal-body">
                    <div class="col-12">
                        <input id="pac-input" class="form-control" type="text" style="width:300px !important; margin-top: 10px;">
                    </div>
                    <div class="form-row mb-3">
                        <div class="col">
                            <div id="map-location" style="width:100%; height: 400px; position: relative; overflow: hidden"></div>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col">
                            <input type="text" name="latitude" class="form-control" placeholder="latitude" required>
                        </div>
                        <div class="col">
                            <input type="text" name="longitude" class="form-control" placeholder="longitude" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Tagihan / Order / Kirim barang" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="i_customer" value="<?= $data_customer->i_customer ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .pac-container {
        z-index: 99999 !important;
    }
</style>