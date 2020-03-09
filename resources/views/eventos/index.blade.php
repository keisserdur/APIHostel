@extends('layout')

@section('contenido')
	<h1>Eventos</h1>
	<table width="100%" border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Description</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($eventos as $evento)

				<tr>
					<td>{{ $evento ->id_evento }}</td>
					<td>{{ $evento->name }}</td>
					<td>{{ $evento->description }}</td>
					<td>{{ $evento->date }}</td>
				</tr>
			
			@endforeach

		</tbody>
	</table>
@stop