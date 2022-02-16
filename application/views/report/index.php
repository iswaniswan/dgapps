			<!-- Page header -->
			<div class="page-header page-header-light">
			  <div class="page-header-content header-elements-md-inline">
			    <div class="page-title d-flex">
			      <h4><span class="font-weight-semibold"><?=$this->lang->line('report');?></span></h4>
			      <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			    </div>
			  </div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content">

			  <!-- Main charts -->
			  <div class="row">
			    <div class="col-xl-12">
			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <div class="form-group">
			            <h5 class="font-weight-semibold"><?=$this->lang->line('time-range');?></h5>
			            <div class="d-flex">
			              <input type="text" class="form-control mb-3 mb-md-0" id="range-from" placeholder=""
			                value="<?=date('d-m-Y', strtotime('-7 days', strtotime(date('Y-m-d'))));?>" readonly>&nbsp;
			              <input type="text" class="form-control" id="range-to" placeholder="Date to:"
			                value="<?=date('d-m-Y');?>" readonly>
			            </div>
			          </div>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>
			        <div class="card-body">
			          <div class="row">
			            <div class="col-md-4">

			            	<div class="mb-4">
				                <h6 class="font-weight-semibold">Download Sales Call Detail</h6>
				                <button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="calldetail_report"
				                  data-style="zoom-out">
				                  <span class="ladda-label">Download Sales Call Detail
				                    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
				                </button>
			              	</div>

			            	<div class="mb-4">
				                <h6 class="font-weight-semibold"><?=$this->lang->line('call');?></h6>
				                <button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="call_report"
				                  data-style="zoom-out">
				                  <span class="ladda-label">Download <?=$this->lang->line('call');?> (Tanpa RRKH)
				                    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
				                </button>
			              	</div>

							<div class="mb-4">
								<h6 class="font-weight-semibold"><?=$this->lang->line('attendance');?></h6>
								<button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="sfa_attendance"
							  data-style="zoom-out">
							  	<span class="ladda-label">Download <?=$this->lang->line('attendance');?>
							    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
								</button>
							</div>

			              

			              <div class="mb-3" hidden="">
			                <h6 class="font-weight-semibold"><?=$this->lang->line('documentation');?></h6>
			                <button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="documentation"
			                  data-style="zoom-out">
			                  <span class="ladda-label">Download <?=$this->lang->line('documentation');?>
			                    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
			                </button>
			              </div>
			            </div>

			            <div class="col-md-4">
			              <div class="mb-4">
			                <h6 class="font-weight-semibold"><?=$this->lang->line('sales-order');?></h6>
			                <button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="sales_order"
			                  data-style="zoom-out">
			                  <span class="ladda-label">Download <?=$this->lang->line('sales-order');?>
			                    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
			                </button>
			              </div>

			              <div class="mb-4">
			                <h6 class="font-weight-semibold"><?=$this->lang->line('customer');?></h6>
			                <button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="customer_report"
			                  data-style="zoom-out">
			                  <span class="ladda-label">Download <?=$this->lang->line('customer');?>
			                    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
			                </button>
			              </div>

			              <div class="mb-3" hidden="">
			                <h6 class="font-weight-semibold"><?=$this->lang->line('suggestion');?></h6>
			                <button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="suggestion"
			                  data-style="zoom-out">
			                  <span class="ladda-label">Download <?=$this->lang->line('suggestion');?>
			                    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
			                </button>
			              </div>

			              <div class="mb-4">
			                <h6 class="font-weight-semibold"><?=$this->lang->line('lastvisit');?></h6>
			                <button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="lastvisit"
			                  data-style="zoom-out">
			                  <span class="ladda-label">Download <?=$this->lang->line('lastvisit');?>
			                    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
			                </button>
			              </div>

			            </div>
			          </div>
			        </div>
			      </div>
			    </div>
			  </div>

			  <div class="row">
			    <div class="col-xl-12">
			      <div class="card">
			        <div class="card-header header-elements-inline">
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>
			        <div class="card-body">
			          <div class="row">
			            <div class="col-md-6">
			            	<div class="mb-4">
				                <h6 class="font-weight-semibold">Download Pencapaian Toko</h6>
				                <div class="d-flex">
				                	<?php 
				                	$lastyear =  date('Y', strtotime('-1 year'));
				                	$nextyear =  date('Y', strtotime('+1 year'));
				                	$now =  date('Y');
				                	?>
				                	<select id="tahun">
				                		<option value="<?= $lastyear?>"><?= $lastyear?></option>
				                		<option value="<?= $now?>" selected><?= $now?></option>
				                		<option value="<?= $nextyear?>"><?= $nextyear?></option>
					                </select>

				                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					                <button type="button" class="btn btn-primary btn-ladda btn-ladda-progress" data="targettoko_report"
					                  data-style="zoom-out">
					                  <span class="ladda-label">Download Pencapaian Toko
					                    <?=$this->lang->line('report');?> &nbsp; <i class="fas fa-download"></i></span>
					                </button>
					              </div>
			              	</div>

			            	
			            </div>
			          </div>
			        </div>
			      </div>
			    </div>
			  </div>
			  <!-- /main charts -->

			</div>
			<!-- /content area -->