var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('ioredis');
var mysql   =     require("mysql");


var connection    =    mysql.createConnection({
      host              :   '127.0.0.1',
      user              :   'root',
      password          :   'groupe',
      database          :   'sportApp',
});

/*var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'groupe',
  database : 'sportApp'
});*/

server.listen(8890);
console.log("new client connected !! non ");

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

connection.connect();
 
connection.query('INSERT INTO goals values (6,'+message.half_time_id+','+message.time+','+message.player+','+message.team+',null,null) ', function (error, results, fields) {
    if (error) throw error;
  console.log('The solution is: ', results[0]);
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