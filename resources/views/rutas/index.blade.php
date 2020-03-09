@extends('layout')

@section('contenido')
	<h1>Rutas</h1>
	<table width="100%" border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Distancia</th>
				<th>Duracion</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($rutas as $ruta)

				<tr>
					<td>{{ $ruta ->id_ruta }}</td>
					<td>{{ $ruta->name }}</td>
					<td>{{ $ruta->distancia }}</td>
					<td>{{ $ruta->duracion }}</td>
				</tr>

			@endforeach

		</tbody>
	</table>
@stop