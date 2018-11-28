<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body>
        <h1>New Users</h1>

        <ul>
            <li v-repeat="user: users">@{{ user }}</li>
        </ul>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.16/vue.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.min.js"></script>

        <script type="text/javascript">
            var socket = io('http://localhost:8888');
            new Vue({
                el: 'body',

                data: {
                    users: []
                },

                ready: function() {
                    socket.on('test-channel:UserSignedUp', function (data){
                        console.log(data);
                        this.users.push(data);
                    }.bind(this));
                }
            });
        </script>
    </body>
</html>
