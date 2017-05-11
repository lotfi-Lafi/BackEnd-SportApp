var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('ioredis');
var mysql   =     require("mysql");


var pool    =    mysql.createPool({
      connectionLimit   :   100,
      host              :   'http://sportapp.astrolabs.io:3306',
      user              :   'sportApp',
      password          :   'sport2017',
      database          :   'sportApp',
      debug             :   false
});

/*var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'groupe',
  database : 'sportApp'
});*/

server.listen(3004);
console.log("new client connected !! non ");
socket.emit('reload',"reload 1");
io.on('connection', function (socket) {
 
console.log("new client connected !! oui ");
socket.emit('reload',"reload 2");
/*var  redisClient = redis.createClient(); 
redisClient.subscribe('message');

redisClient.on('message',function( message){
  console.log('hhh', message);
  socket.emit(message);
});*/

socket.on('message',function( message){
  console.log('hhh', message);
  console.log('message.time', message.time);

  pool.getConnection(function(err, connection) {
    console.log('connected as id ');

  connection.query(' INSERT INTO goals values (3,'+message.half_time_id+','+message.time+','+message.player+','+message.team+',null,null) ', function (error, results, fields) {
    connection.release();
 console.log('yesssssssss ==',results);
    if (error) throw error;
 
    });
  });

  socket.emit('reload',"reload 3");
});

 
/*  pool.query("INSERT INTO 'users'  VALUES (19,'test','testloool@gmail.com','$2y$10$slvshh9tx0rZPzSkusxj6eSXOhXp.wgV7ThWSTYDM/ht0b7Gq76aS','34534543543','TESTTEST','TUNISIE','SOUSSE','2017-02-28','EGREG','ADMIN','',NULL,NULL,'2017-04-12 11:48:34')",function(err,rows){
            connection.release();
            if(!err) {
              callback(true);
              console.log('ouiiii');
            }
        });
});
*/
 //socket.send('message from server !! ');

/*    pool.getConnection(function(err,connection){
        if (err) {
          callback(false);
          return;
        }
        console.log('nawres 2 ');
    connection.query("INSERT INTO 'users'  VALUES (19,'test','testloool@gmail.com','$2y$10$slvshh9tx0rZPzSkusxj6eSXOhXp.wgV7ThWSTYDM/ht0b7Gq76aS','34534543543','TESTTEST','TUNISIE','SOUSSE','2017-02-28','EGREG','ADMIN','',NULL,NULL,'2017-04-12 11:48:34')",function(err,rows){
            connection.release();
            if(!err) {
              callback(true);
              console.log('ouiiii');
            }
        });
     connection.on('error', function(err) {
              callback(false);
              console.log('nonnnn');
              return;
        });
    });
*/

});

/*var Redis = require('ioredis'),
redis = new Redis();



redis.psubscribe('*', function (error, count){

});
redis.on('pmessage',function(pattern, channel, message){
  console.log(channel, message);
});*/