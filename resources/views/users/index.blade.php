@extends('layout')

@section('contenido')
	<h1>Usuarios</h1>
	<table width="100%" border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>email</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)

				<tr>
					<td>{{ $user->id }}</td>
					<td>{{ $user->name }}</td>
					<td>{{ $user->email }}</td>
				</tr>

			@endforeach

		</tbody>
	</table>
@stop