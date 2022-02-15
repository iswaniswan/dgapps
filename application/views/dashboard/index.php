			<!-- Page header -->
			<div class="page-header page-header-light">
			  <div class="page-header-content header-elements-md-inline">
			    <div class="page-title d-flex">
			      <h4><span class="font-weight-semibold"><?=$this->lang->line('dashboard');?></span></h4>
			      <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			    </div>
			    <div class="d-flex">
			      <input type="text" class="form-control mb-3 mb-md-0" id="range-from" placeholder=""
			        value="<?=date('d-m-Y', strtotime('-7 days', strtotime(date('Y-m-d'))));?>" readonly>&nbsp;
			      <input type="text" class="form-control" id="range-to" placeholder="Date to:" value="<?=date('d-m-Y');?>"
			        readonly>
			    </div>
			  </div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">

			  <!-- Main charts -->
			  <div class="row">
			    <div class="col-xl-6">
			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <h5 class="card-title"><?=$this->lang->line('sales-over-time');?></h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>
			        <div class="card-body">
			          <div class="chart-container">
			            <div class="chart has-fixed-height" id="area_values"></div>
			          </div>
			        </div>
			      </div>

			    </div>

			    <div class="col-xl-6">

			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <h5 class="card-title"><?=$this->lang->line('customer-overview');?></h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>

			        <div class="card-body">
			          <div class="chart-container">
			            <div class="chart has-fixed-height" id="columns_compositive_waterfall"></div>
			          </div>
			        </div>
			      </div>

			    </div>
			  </div>
			  <div class="row">
			    <div class="col-xl-6">
			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <h5 class="card-title">Sales Call & Effective Call</h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>
			        <div class="card-body">
			          <div class="text-center">
			            <select class="form-control" id="area_call">
			              <option value="na">National</option>
			              <option value="area">Area</option>
			              <option value="staff">Staff</option>
			            </select>
			          </div>
			          <br>
			          <div class="chart-container">
			            <div class="chart has-fixed-height" id="columns_basic"></div>
			          </div>
			        </div>
			      </div>

			    </div>
			    <div class="col-xl-6">
			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <h5 class="card-title"><?=$this->lang->line('attendance-report');?></h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>
			        <div class="card-body">
			          <div class="chart-container">
			            <div class="chart has-fixed-height" id="columns_stacked"></div>
			          </div>
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
			          <table class="table table-striped table-hover table-bordered" id="activitylist">
			            <thead>
			              <tr>
			                <th>Staff Name</th>
			                <th>Customer Name</th>
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
			  <!-- /main charts -->

			</div>
			<!-- /content area -->