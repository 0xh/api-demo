<!DOCTYPE html>
<html>
<head>
	<title>
		
	</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="text-center">
		<div>
			<p>{{$owner}} has invited you to join his company.</p>
		</div>
		<div>
			
		</div>
	</div>
	<div class="text-center">
		<form action="{{$url}}" method="POST" id='confirm'>
			{!! csrf_field() !!}
			<input type="hidden" name="email" value="{{$email}}">
			<input type="hidden" name="company_id" value="{{$company_id}}">
			<button type="submit" name="accept" value="1" class="btn btn-primary">Accept</button>
			<button type="submit" name="accept" value="0" class="btn btn-default">Cancel</button>
		</form>
	</div>
</body>
</html>