<html>
    <body>
        Welcome to bla bla aaaaa
        <br>
        Dear {{$user['name']}}
        <br>
        <br>
        @foreach ($product_orders as $product)
            <p>Name product: {{$product->product_order['name']}}</p>
            <p>Price product: {{$product->product_order['price']}} </p>
        @endforeach
        <br>
        <br>
        Amount: {{$transaction['amount']}}
    </body>
</html>