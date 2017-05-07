var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('ioredis');




server.listen(8890);
io.on('connection', function (socket) {
 
console.log("new client connected");
var  redisClient = redis.createClient(); 
redisClient.subscribe('message');

redisClient.on('message',function(channel, message){
  console.log(channel, message);
  socket.emit(channel, message);
});

 //socket.send('message from server !! ');


 
});


/*var Redis = require('ioredis'),
redis = new Redis();



redis.psubscribe('*', function (error, count){

});
redis.on('pmessage',function(pattern, channel, message){
  console.log(channel, message);
});*/