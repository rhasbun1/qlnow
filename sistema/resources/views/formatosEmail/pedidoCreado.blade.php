<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Logística QLSA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
 </head>
  <body>
  	<h3>QL-NOW INFORMA</h3>

  	<h4>{{$tipo}}<br><br>
 
        @component('mail::table')
        | Nº Pedido | Cliente |
        |:--:|:----:|
        @foreach($pedido as $item)
        | {{ $item->idPedido }} | {{ $item->emp_nombre }} |
        @endforeach
        @endcomponent

		
	</h4>
  </body>
</html>