			<!-- Page header -->
			<div class="page-header page-header-light">
			  <div class="page-header-content header-elements-md-inline">
			    <div class="page-title d-flex">
			      <h4><span class="font-weight-semibold"><?=$this->lang->line('geo-analytic');?></span></h4>
			    </div>
			  </div>
			  <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			    <div class="d-flex">
			      <div class="breadcrumb">
			        <a href="<?= base_url(); ?>geo-analytic" class="breadcrumb-item"> Indonesia</a>
			        <span class="breadcrumb-item active"><?= ucwords(strtolower($data_area->e_area_name)); ?></span>
			      </div>
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
			          <h5 class="card-title"><?= $data_area->e_area_name; ?></h5>
			          <div class="header-elements">
			            <div class="list-icons">
			              <a class="list-icons-item" data-action="collapse"></a>
			            </div>
			          </div>
			        </div>
			        <div id="container"></div>
			      </div>

			    </div>

			  </div>

			</div>
			<!-- /content area -->