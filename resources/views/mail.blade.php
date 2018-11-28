<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		.text-center{
			text-align: center;
		}
		h1{
			color: blue;
		}
	</style>
</head>
<body>
	<h1 class="text-center"> Friend Request </h1>
	<h5 class="text-center" > {{$data['sender']['email']}} has sent a friend request to you !!!</h5>
	<div class="text-center">
		<form action="{{ $data['url'] }}" method="POST" id="form1">
		 	{!! csrf_field() !!}
		 	
		 	<input type="hidden" name="friendship_id" value="{{$data['friendship_id']}}" id="friendship_id">
		 	<input type="hidden" name="notification_id" value="{{$data['notification_id']}}" id="notification_id">
			<button type="submit" name="accept" value="1" >Accept</button>
			<button type="submit" name="accept" value="0" >Cancel</button>
		</form>
		
	</div>
</body>

</html>
