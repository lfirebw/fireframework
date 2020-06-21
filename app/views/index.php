<div class="row">
	<div class="col-md-12">
		<h1>Welcome to BraveWolf Office <span class="badge badge-primary">V2.1.5</span></h1>
		<h4>Comming soon... Your dashboard</h4>
	</div>
</div>
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-12">
		<div class="card">
			<div class="card-header">
				<h3 class="greeting-text">Congratulations <span class="bestmember-name">Unknown</span>!</h3>
				<p class="mb-0">Best member of the month</p>
			</div>
			<div class="card-content">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-end">
						<div class="dashboard-content-left">
						<h1 class="text-primary font-large-2 text-bold-500"><span class="bestmember-hours">0</span>Hrs</h1>
						<p>You have done your task with more hours.</p>
						</div>
						<div class="dashboard-content-right">
						<img src="<?php echo URL_ASSETS ?>/assets/img/icon/cup.png" height="220" width="220" class="img-fluid" alt="Dashboard Ecommerce">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9 col-md-9 col-sm-12">
		<div class="row">
			<div class="col-xl-4 col-md-5 col-sm-12">
			<div class="card">
				<div class="card-content">
				<img class="card-img img-fluid" src="<?php echo URL_ASSETS ?>/assets/img/slider/07.jpg" alt="Card image">
				<div class="card-img-overlay overlay-dark bg-overlay d-flex justify-content-between flex-column">
					<div class="overlay-content">
					<h4 class="card-title mb-50">Task Manager</h4>
					<p class="card-text text-ellipsis">
						Manage your tasks everywhere...
					</p>
					</div>
					<div class="overlay-status">
					<!-- <p class="mb-25"><small>Last updated 3 mins ago</small></p> -->
					<a href="#" class="white">Check More </a>
					</div>
				</div>
				</div>
			</div>
			</div>
			<div class="col-xl-4 col-md-5 col-sm-12">
			<div class="card">
				<div class="card-content">
				<img class="card-img img-fluid" src="<?php echo URL_ASSETS ?>/assets/img/slider/08.jpg" alt="Card image">
				<div class="card-img-overlay overlay-dark d-flex justify-content-between flex-column">
					<div class="overlay-content">
					<p class="card-text text-ellipsis">
						Buy any items in our bw shop.
					</p>
					</div>
					<div class="overlay-status">
					<!-- <p class="mb-25"><small>Last updated 3 mins ago</small></p> -->
					<a href="#" class="btn btn-outline-info">Check More</a>
					</div>
				</div>
				</div>
			</div>
			</div>
			<div class="col-xl-4 col-md-5 col-sm-12">
			<div class="card">
				<div class="card-content">
				<img class="card-img img-fluid" src="<?php echo URL_ASSETS ?>/assets/img/slider/07.jpg" alt="Card image">
				<div class="card-img-overlay overlay-warning d-flex justify-content-between flex-column">
					<div class="overlay-content">
					<h4 class="card-title mb-50">Connect with your team</h4>
					<p class="card-text text-ellipsis">
					Share with your classmates at the office game.
					</p>
					</div>
					<div class="overlay-status">
					<!-- <p class="mb-25"><small>Last updated 3 mins ago</small></p> -->
					<a href="#" class="white">Check More </a>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>
	</div>

</div>
<div class="row">
	<div class="col-md-4 col-sm-6 w3-border w3-border-gray w3-round pt-2 pb-2">
		<div id="widget-semaforo"></div>
	</div>
	<div class="col-md-4 col-sm-6">
		<div class="card" id="bestweek">
			<div class="card-header">
				<h4 class="card-title h3">Best members of the week</h4>
			</div>
			<div class="card-content">
				<div class="card-body">
				<div class="media-list">
					<!-- dynamic content -->
					<h4 class="text-center">No there any members</h4>
				</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<JScript filename="library.js" />
<JScript filename="widget_semaforo.js" />

<script type="text/javascript">
	window.addEventListener('load',()=>{
		let _mes = (new Date()).getMonth() == 0 ?  1 : ((new Date()).getMonth());
		document.querySelector('#widget-semaforo').widgetSemaforo({maxMonth: 2,iduser:<?php echo $_iduser; ?>});
		JSpost(String.prototype.concat(_URLWEB_,'/api/hours/bestmember'),{month:_mes},(resp)=>{
			if(resp.code == 200 && Object.keys(resp.data).length > 0){
				let indexmes = "m" + _mes;

				document.querySelector('.bestmember-name').innerText = resp.data.user;
				document.querySelector('.bestmember-hours').innerText = resp.data[indexmes];
			}
		});
		JSpost(String.prototype.concat(_URLWEB_,'/api/semaforo/bestmembers'),{},(resp)=>{
			if(resp.code == 200 && Object.keys(resp.data).length > 0){
				let contenedor = $('#bestweek .card-body');
				contenedor.html('');
				for(let value of resp.data){
					contenedor.append(`
						<div class="media">
							<a class="pr-1" href="javascript:void(0)">
								<img src="${value.avatar}" alt="Generic placeholder image" height="64" width="64">
							</a>
							<div class="media-body">
								<h4 class="media-heading">${value.user}</h4>
							</div>
						</div>
					`)
				}
			}
		});
	});
</script>