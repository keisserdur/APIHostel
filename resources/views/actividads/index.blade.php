@extends('layout')

@section('contenido')
	<h1>Actividades</h1>
	<table width="100%" border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Type</th>
				<th>Description</th>
				<th>Address</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($actividads as $actividad)

				<tr>
					<td>{{ $actividad ->id_actividad }}</td>
					<td>{{ $actividad->name }}</td>
					<td>{{ $actividad->type }}</td>
					<td>{{ $actividad->description }}</td>
					<td>{{ $actividad->address }}</td>
				</tr>

			@endforeach

		</tbody>
	</table>
@stop