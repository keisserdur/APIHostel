@extends('layout')

@section('contenido')
	<h1>Login</h1>

	<form method="POST" action="login">
	
		{!! csrf_field() !!}

		<input type="email" name="email" placeholder="Email">
		{!! $errors->first('email', '<span class=error>:message</span>')!!}
		<input type="password" name="password" placeholder="Password">
		<input type="submit" value="Entrar">
	</form>
	<br>
@stop