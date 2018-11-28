var server = require('http').Server(),

    port = 8888,

    io = require('socket.io')(server),

    Redis = require('ioredis'),

    config = {
        port: 6379,
        host: '127.0.0.1',
        // host: '138.197.100.21',
        family: 4,          // 4(IPv4) or 6(IPv6)
        password: null,
        db: 0
    };

var redis = new Redis(config),
    subcriber = new Redis(config),
    publisher = new Redis(config);

var CryptoJS = require("crypto-js");

var _ = require('lodash');

var request = require('request');

var keygen = require("keygenerator");

var timeExpire = 600; // seconds

var deviceKey = ["timestamp", "emei", "lat_lon", "roll", "shake", "jump", "ip", "battery"];

subcriber.psubscribe('*');

subcriber.on('pmessage', function(subscribed, channel, message){

    console.log('----------------------------------------------------------------------------');
    console.log('subscribed: ', subscribed);
    console.log('----------------------------------------------------------------------------');
    console.log('channel: ', channel);
    console.log('----------------------------------------------------------------------------');
    console.log('message: ', message);
    console.log('----------------------------------------------------------------------------');

    // if(channel.indexOf('private-device-') > -1){
    //  message = JSON.parse(message);
    //  var deviceId = message.data.device.id,
    //      accessToken = message.data.accessToken;
    //  rd.set('accessToken-device-' + deviceId, accessToken);
    //  setKey(deviceId);
    //  // 10 minutes
    //  // setInterval(function(){
    //  //  checkKey(deviceId);
    //  // }, 1000*60*10);
    //  return false;
    // }

    // if(channel.indexOf('info-device-') > -1){
    //  var device_id = channel.replace('info-device-', '');
    //  rd.get('key-device-' + device_id).then(function(key){
    //      if(key){
    //          var info = decrypt(key, message);
    //          return info;
    //      }else{
    //          console.log(channel + ': Key expire! Please set a new key');
    //          return false;
    //      }
    //  }).then(function(info){
    //      if(info){
    //          console.log(info);
    //          var deviceValue = _.split(info, ',');
    //          var deviceObject = _.zipObject(deviceKey, deviceValue);

    //          console.log(deviceObject);

    //          saveInfo(device_id, deviceObject);

    //          io.emit(channel, deviceObject);
    //      }
    //  });
    // }

    if(channel.indexOf('info-device-') > -1){
        var imei = channel.slice(channel.lastIndexOf('-') + 1, channel.length);
        console.log('imei: ', imei);
        rd.get('key_device_' + imei, function (err, result){
            if(result){
                console.log('key: ', result);
                console.log('code: ', message);
                var info = decrypt(result, message);
                console.log('info: ', info);
            }else{
                console.log(err);
            }
        });
    }

    // Notification GEO fences
    if(channel.indexOf('alert-geo-fences.') > -1){
        message = JSON.parse(message);
        io.emit(channel, message.data);
        // io.to('room-pravite-'+message.data.notification.receiver).emit(channel,message.data);
    }

    // Send notification make friend
    if(channel.indexOf('friendship.') > -1){
        message = JSON.parse(message);
        io.emit(channel, message.data);
        // io.to('room-pravite-'+message.data.notification.receiver).emit(channel,message.data);
    }

    // Accept Friend
    if(channel.indexOf('acceptTrueFriend.') > -1){
        message = JSON.parse(message);
        io.emit(channel, message.data);
        // io.to('room-pravite-'+message.data.notification.receiver).emit(channel,message.data);
    }

    // Unfriend
    if(channel.indexOf('unfriend.') > -1){
        message = JSON.parse(message);
        io.emit(channel, message.data);
        // io.to('room-pravite-'+message.data.notification.receiver).emit(channel,message.data);
    }

    // Send message
    if(channel == 'send-message'){
        message = JSON.parse(message);
        for (var i=0; i<message.data.message.room.length; i++) {
            io.to('message-private-'+ message.data.message.room[i]).emit(channel, message.data);
            console.log('message-private-'+ message.data.message.room[i]);
        }

    }

    // Create conversation
    if(channel == 'create-conversation'){
        message = JSON.parse(message);
        for (var i=0; i<message.data.conversation.arrayUser.length; i++) {
            io.to('message-private-'+ message.data.conversation.arrayUser[i]).emit(channel, message.data);
            console.log('message-private-'+ message.data.conversation.arrayUser[i]);
        }

    }

    if(channel == 'add-member-conversation'){
        message = JSON.parse(message);
        // console.log(message);
        for (var i=0; i<message.data.conversation.arrayUser.length; i++) {
            io.to('message-private-'+ message.data.conversation.arrayUser[i]).emit(channel, message.data);
            console.log('message-private-'+ message.data.conversation.arrayUser[i]);
        }

    }

    if(channel == 'remove-member-conversation'){
        message = JSON.parse(message);
        for (var i=0; i<message.data.conversation.arrayUser.length; i++) {
            io.to('message-private-'+ message.data.conversation.arrayUser[i]).emit(channel, message.data);
            console.log('message-private-'+ message.data.conversation.arrayUser[i]);
        }

    }

    // Invitation
    if(channel.indexOf('company-invitation.') > -1){
        console.log('sent Invitation');
        message = JSON.parse(message);
        io.emit(channel, message.data);
        // io.to('room-pravite-'+message.data.notification.receiver).emit(channel,message.data);
    }

    // Device event
    if(channel.indexOf('device.key.') > -1){
        var channelArray = channel.split('.');
        if(channelArray.length == 3){
            var imei = channelArray[2];
            var key = keygen._();
            console.log('imei: ', imei);
            console.log('key: ', key);
            // push the key to redis
            // redis.set('device.key.' + imei, key);
            publisher.publish('device.key.response.' + imei, key);
        }
    }

    if(channel.indexOf('device.info.') > -1){
        var channelArray = channel.split('.');
        if(channelArray.length == 3){
            var imei = channelArray[2];
            console.log('imei: ', imei);
            console.log('data: ', message);
            // check key here
            // save info data to database
        }
    }

});


io.on('connection', function (socket) {

    console.log('new client connected!');

     socket.on('join', function (joinRoom) {
        socket.join(joinRoom); // We are using room of socket io
        console.log('a client has joined room '+ joinRoom);
      });
     socket.on('leave', function (leaveroom) {
        socket.leave(leaveroom); // We are using room of socket io
        console.log('a client has leave room '+ leaveroom);
      });

    socket.on('from angular', function (data) {
        console.log(data);
    });

    socket.on('disconnect', function (){
        console.log('disconnected!');
    });
});

server.listen(port, function (){
    console.log('Listening on Port ' + port);
});


// generate a key length 16
function generateKey (){
    var key = keygen._({
        length: 16
    });

    return key;
}

// set Key to redis then publish it
function setKey (deviceId){
    rd.exists('key-device-' + deviceId).then(function(existKey){
        if(existKey){
            console.log('setKey: key exist');
        }else{
            var key = generateKey();
            // encrypt(key, "1492162344681,352828071045296,0.000000/0.000000,0,0,0,192.168.1.119,50");
            console.log('key generated: ', key);

            rd.set('key-device-' + deviceId, key);
            rd.expire('key-device-' + deviceId, timeExpire); //10 minutes expire

            setTimeout(publishKey(key, deviceId), 1000);

        }
    });
}

function publishKey (key, deviceId){
    pub.publish('key-device-' + deviceId, key);
    console.log('Published key: ', key);
}

function checkKey (deviceId){
    rd.exists('key-device-' + deviceId).then(function(existKey){
        if(existKey){
            console.log('checkKey: key exist');
        }else{
            console.log('key expire');
            setKey(deviceId);
        }
    });
}

function encrypt(key, message){
    var ciphertext = CryptoJS.AES.encrypt(message, key, { mode: CryptoJS.mode.CBC,  padding: CryptoJS.pad.Pkcs7 });
    return ciphertext.toString();
}

function decrypt(key, message){
    try{
        console.log('decrypting...');
        var bytes  = CryptoJS.AES.decrypt(message, key, { mode: CryptoJS.mode.CBC,  padding: CryptoJS.pad.Pkcs7 });
        console.log('bytes: ', bytes);
        // var plaintext = bytes.toString(CryptoJS.enc.Utf8);
        // console.log('plaintext: ', plaintext);

        // return plaintext;
        var base64 = CryptoJS.enc.Base64.stringify(bytes);
        var parsedWordArray = CryptoJS.enc.Base64.parse(base64);
        var parsedStr = parsedWordArray.toString(CryptoJS.enc.Utf8);
        console.log('result: ', parsedStr);
        return 'OK';
    }catch(err){
        console.log(err);
    }
}

function saveInfo(imei, deviceObject){
    request.post({
        url:'http://localhost:8080/api/v1/saveDeviceInfo/' + imei,
        form: deviceObject
    }, function(err, response, body){
        if(err){
            console.error('save failed:', err);
        }
        console.log('statusCode:', response && response.statusCode);
        console.log('body:', body);
    });
}

function wordToByteArray(wordArray) {
    var byteArray = [], word, i, j;
    for (i = 0; i < wordArray.length; ++i) {
        word = wordArray[i];
        for (j = 3; j >= 0; --j) {
            byteArray.push((word >> 8 * j) & 0xFF);
        }
    }
    return byteArray;
}

function byteArrayToString(byteArray) {
    var str = "", i;
    for (i = 0; i < byteArray.length; ++i) {
        str += escape(String.fromCharCode(byteArray[i]));
    }
    return str;
}