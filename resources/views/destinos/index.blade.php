@extends('layout')

@section('contenido')
	<h1>Destinos</h1>
	<table width="100%" border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Image</th>
				<th>Description</th>
				<th>Address</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($destinos as $destino)

				<tr>
					<td>{{ $destino ->id_destino }}</td>
					<td>{{ $destino->name }}</td>
					<td>{{ $destino->image }}</td>
					<td>{{ $destino->description }}</td>
					<td>{{ $destino->addres }}</td>
				</tr>

			@endforeach

		</tbody>
	</table>
@stop