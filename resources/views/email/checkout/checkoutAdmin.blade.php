<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

Following reports has been purchased by {{ $user['name'] }} ({{ $order->user['email']}}) with order id={{$order['id']}}
<br/>Below are the items details.<br/><br/>

<div>
       <table border="1">
           <tr>
               <th>Item</th>
               <th>Report Id</th>
               <th>Report Name</th>
               <th>Price</th>
           </tr>

        @foreach( $carts as $cart)
                <tr>
                    <td>{!! $cart->product['id'] !!}</td>
                    <td></td>
                    <td>{!! $cart->product['name'] !!}</td>
                    <td>${!! $cart->product['price'] !!}</td>
                </tr>
        @endforeach
        </table>
    <br/>
    <br/>
	<table border="1" width="50%">
		<tbody>
      <tr>
        <td colspan="4">
          <span class="pull-right">Report Quantity</span>
        </td>
        <td>{{$sum['sumQty']}}</td>
      </tr>
      <tr>
        <td colspan="4">
          <span class="pull-right">Sub Total</span>
        </td>
        <td>{{$sum['amount']}}</td>
      </tr>
      <tr>
        <td colspan="4">
          <span class="pull-right">Subtotal (with Discount)</span>
        </td>
        <td></td>
      </tr>
      <tr>
        <td colspan="4">
          <span class="pull-right">Taxes</span>
        </td>
        <td></td>
      </tr>
      <tr>
        <td colspan="4">
          <span class="pull-right">Total (Cad)</span>
        </td>
        <td></td>
      </tr>
		</tbody>
	</table>
    <br/>
    <br/>
</div>

</body>
</html>