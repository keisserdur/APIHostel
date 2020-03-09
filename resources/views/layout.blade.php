<!DOCTYPE html>
<html lang = "en">
	<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	<meta name="csrf-token" content="{{ csrf_token() }}">
		
	</head>
	<body>
		<header>
			<?php function activeMenu($url){

				return "hola";

				} ?>
			<nav>

				<a class ="{{ activeMenu('visitas') }}" href="{{ route('visitas.index') }}">Visitas</a>

				<a class ="{{ activeMenu('eventos') }}" href="{{ route('eventos.index') }}">Eventos</a>

				<a class ="{{ activeMenu('actividads') }}" href="{{ route('actividads.index') }}">Actividades</a>

				<a class ="{{ activeMenu('users') }}" href="{{ route('users.index') }}">Users</a>

				<a class ="{{ activeMenu('rutas') }}" href="{{ route('rutas.index') }}">Rutas</a>

				<a class ="{{ activeMenu('destinos') }}" href="{{ route('destinos.index') }}">Destinos</a>

				@if (auth()-> check())

					<a class ="{{ activeMenu('eventos') }}" href="{{ route('eventos.index') }}">Eventos</a>

					<a class ="{{ activeMenu('actividads') }}" href="{{ route('actividads.index') }}">Actividades</a>


					<a href="/logout">Cerrar sesión de {{ auth()->user()->name }}</a>
				@endif

				@if (auth()->guest())
					<a class="{{ activeMenu('login') }}" href="/login">Login</a>
				@endif

			</nav>
		</header>
		@yield('contenido')
	</body>
</html>