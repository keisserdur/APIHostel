@extends('layout')

@section('contenido')
	<h1>Visitas</h1>
	<table width="100%" border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>ID_Usuarios</th>
				<th>ID_Actividad</th>
				<th>Visto</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($visitas as $visita)

				<tr>
					<td>{{ $visita ->id_visita }}</td>
					<td>{{ $visita->user_id }}</td>
					<td>{{ $visita->actividad_id }}</td>
					<td>{{ $visita->visto }}</td>
				</tr>

			@endforeach

		</tbody>
	</table>
@stop