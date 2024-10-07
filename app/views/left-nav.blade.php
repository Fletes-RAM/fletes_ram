@if (Sentry::check())
	@if ($currentUser->hasAccess('view-asistencia-list'))
		<li id="Menu6"><a href="{{ URL::route('asistencia.index') }}"><i class="fas fa-check"></i> Asistencia</a></li>
	@endif
	<li class="treeview" id="Menu0">
		<a href="#">
			<i class="fas fa-cogs"></i> <span>Configuración</span>
			<i class="fas fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			@if ($currentUser->hasAccess('catalogs-management'))
				<li class="treeview" id="Menu1">
					<a href="#">
						<i class="fas fa-book"></i> <span>Catálogos</span>
						<i class="fas fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu" id="Menu1-1">
						@if ($currentUser->hasAccess('view-tipounidad-list'))
							<li id="Menu1-1-1"><a href="{{ URL::route('listTipoUnidad') }}"><i class="fas fa-truck-moving"></i> Tipo de Unidad</a></li>
						@endif

						@if ($currentUser->hasAccess('view-rendimiento-list'))
							<li id="Menu1-1-2"><a href="{{ URL::route('listRendimiento') }}"><i class="fas fa-thermometer-half"></i> Rendimiento Comb.</a></li>
						@endif

						@if ($currentUser->hasAccess('view-rendimiento-list'))
							<li id="Menu1-1-3"><a href="{{ URL::route('listGasolinera') }}"><i class="fas fa-gas-pump"></i> Gasolineras</a></li>
						@endif

						@if ($currentUser->hasAccess('view-unidad-list'))
							<li id="Menu1-1-4"><a href="{{ URL::route('listUnidad') }}"><i class="fas fa-truck"></i> Unidades</a></li>
						@endif

						@if ($currentUser->hasAccess('view-codigo-list'))
							<li id="Menu1-1-5"><a href="{{ URL::route('listCodigo') }}"><i class="fas fa-map"></i> Municipios</a></li>
						@endif

						@if ($currentUser->hasAccess('view-banco-list'))
							<li id="Menu1-1-6"><a href="{{ URL::route('listBanco') }}"><i class="fas fa-piggy-bank"></i> Bancos</a></li>
						@endif

						@if ($currentUser->hasAccess('view-banco-list'))
							<li id="Menu1-1-7"><a href="{{ URL::route('listBancoCategoria') }}"><i class="fas fa-list"></i> Categoria Bancaria</a>	</li>
						@endif

						@if ($currentUser->hasAccess('view-banco-list'))
							<li id="Menu1-1-7"><a href="{{ URL::route('listBancoSubCategoria') }}"><i class="fas fa-list"></i> Subcategoria Bancaria</a>	</li>
						@endif

						@if ($currentUser->hasAccess('view-origen-list'))
							<li id="Menu1-1-7"><a href="{{ URL::route('catalogos.origen.index') }}"><i class="fas fa-check"></i> Origen Control Vehicular</a>	</li>
						@endif

					</ul>
				</li>
			@endif
			@if ($currentUser->hasAccess('view-cliente-list'))
				<li id="Menu2"><a href="{{ URL::route('listCliente') }}"><i class="fas fa-address-book"></i> Clientes</a></li>
			@endif
			@if ($currentUser->hasAccess('view-cat_proveedor-list'))
				<li id="Menu2"><a href="{{ URL::route('cat_proveedor.index') }}"><i class="fas fa-user-tie"></i> Proveedores</a></li>
			@endif
			@if ($currentUser->hasAccess('view-combustible-list'))
				<li id="Menu3"><a href="{{ URL::route('listCombustible') }}"><i class="fas fa-gas-pump"></i> Combustible</a></li>
			@endif
			@if ($currentUser->hasAccess('view-operador-list'))
				<li id="Menu4"><a href="{{ URL::route('listOperador') }}"><i class="fas fa-id-card"></i> Operadores</a></li>
			@endif
			@if ($currentUser->hasAccess('view-ruta-list'))
				<li id="Menu5"><a href="{{ URL::route('listRuta') }}"><i class="fas fa-road"></i> Rutas</a></li>
			@endif
			@if ($currentUser->hasAccess('view-ruta-list'))
				<li id="Menu8"><a href="{{ URL::route('listBancoPeriodo') }}"><i class="fas fa-calendar-alt"></i> Periodo Bancario</a></li>
			@endif
		</ul>
	</li>
	@if ($currentUser->hasAccess('view-banco-list'))
		<li id="Menu7"><a href="{{ URL::route('listBancoMov') }}"><i class="fas fa-money-bill-alt"></i> Movs Bancos</a></li>
	@endif
	@if ($currentUser->hasAccess('view-reporte-list'))
		<li id="Menu9"><a href="{{ URL::route('listReporte') }}"><i class="fas fa-chart-bar"></i> Reportes</a> </li>
	@endif
	@if ($currentUser->hasAccess('view-cotizacion-list'))
		<li id="Menu6"><a href="{{ URL::route('listCotizacion') }}"><i class="fas fa-money-check-alt"></i> Cotizaciones</a></li>
	@endif
    @if ($currentUser->hasAccess('view-dieselaut-list'))
		<li id="Menu6"><a href="{{ URL::route('dieselAutorizado.index') }}"><i class="fas fa-gas-pump"></i> Diesel Autorizado</a></li>
	@endif
    @if ($currentUser->hasAccess('view-reporte-list'))
		<li id="Menu6"><a href="{{ URL::route('sueldoAdmin.index') }}"><i class="fas fa-coins"></i> Sueldos Admin</a></li>
	@endif
	@if ($currentUser->hasAccess('view-proveedor-list'))
		<li class="treeview">
			<a href="#">
				<i class="fas fa-user-tie"></i> <span>Proveedores</span>
				<i class="fas fa-angle-left pull-right"></i>
			</a>
			<ul class="treeview-menu">
				<li> <a href="{{ URL::route('proveedor.index') }}"> <i class="fas fa-gas-pump"></i> Diesel</a> </li>
				@if ($currentUser->hasAccess('view-mantenimiento-list'))
					<li><a href="{{ URL::route('mantenimiento.index') }}"><i class="fas fa-file-invoice-dollar"></i> Refacciones</a></li>
				@endif
			</ul>
		</li>

	@endif
	@if ($currentUser->hasAccess('view-foraneo-list'))
		<li class="treeview">
			<a href="#">
				<i class="fas fa-users"></i> <span>Foraneos</span>
				<i class="fas fa-angle-left pull-right"></i>
			</a>
			<ul class="treeview-menu">
				<li> <a href="{{ URL::route('foraneo_operador.index') }}"> <i class="fas fa-users"></i> Operadores Foraneos</a> </li>
				<li><a href="{{ URL::route('foraneo.index') }}"><i class="fas fa-list-ol"></i> Movimientos</a></li>
			</ul>
		</li>
	@endif
		<li class="treeview">
			<a href="#">
				<i class="fas fa-file-invoice-dollar"></i> <span>Facturación</span>
				<i class="fas fa-angle-left pull-right"></i>
			</a>
			<ul class="treeview-menu">
				@if ($currentUser->hasAccess('view-factura-list'))
					<li><a href="{{ URL::route('factura.index') }}"><i class="fas fa-file-invoice-dollar"></i> Facturación Clientes</a></li>
				@endif
			</ul>
		</li>
	@if ($currentUser->hasAccess('menu-inventario'))
			<li class="treeview">
				<a href="#">
					<i class="fas fa-pallet"></i> <span>Inventario</span>
					<i class="fas fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					@if ($currentUser->hasAccess('view-materiales-list'))
							<li><a href="{{ URL::route('material.index') }}"><i class="fas fa-pallet"></i> Inventario Materiales</a></li>
					@endif
					@if ($currentUser->hasAccess('view-llantas-list'))
							<li><a href="{{ URL::route('llanta.index') }}"><i class = "fas fa-truck-monster"></i> Inventario de llantas</a></li>
					@endif
				</ul>
			</li>
	@endif
	@if ($currentUser->hasAccess('view-asignacion-list'))
		<li><a href="{{ URL::route('listAsignacion') }}"><i class="fas fa-user" data-fa-transform="shrink-10 up-3 left-5" data-fa-mask="fas fa-truck"></i> Asignación de Rutas</a></li>
	@endif
	@if ($currentUser->hasAccess('view-asignacion-list'))
		<li><a href="{{ URL::route('listCombustibleEsp') }}"><i class="fas fa-gas-pump"></i> Carga de Comb s/ Ruta</a></li>
	@endif
@endif
