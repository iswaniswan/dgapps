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