var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('ioredis');
var mysql   =     require("mysql");



var connection    =    mysql.createConnection({
      host              :   '51.254.94.152',
      user              :   'sportApp',
      password          :   'sport2017',
      database          :   'sportApp',
      port: 3306,
      socketPath: '/var/run/mysqld/mysqld.sock'
});
/*var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'groupe',
  database : 'sportApp'
});*/

function random (low, high) {
    return Math.floor(Math.random() * (high - low) + low);
}

server.listen(3004);

console.log("new client connected !! non ");
console.log(random(1,9000));

io.on('connection', function (socket) {
 
console.log("new client connected !! oui ",socket.id);

/*var  redisClient = redis.createClient(); 
redisClient.subscribe('message');

redisClient.on('message',function( message){
  console.log('hhh', message);
  socket.emit(message);
});*/

socket.on('message',function(message){
  console.log('message', message);
  console.log('message.time', message.time);

//connection.connect();
 
connection.query('INSERT INTO goals values ('+random(1,9000)+','+message.half_time_id+','+message.time+','+message.player+','+message.team+',null,null) ', function (error, results, fields) {
    if (error) throw error;
  console.log('The solution is: ', results);

  socket.broadcast.emit('reload',{'oneOrTwo':message.oneOrTwo,'half_time_id': message.half_time_id,'time':message.time,'player':message.player,'team':message.team,'idMatch':message.idMatch});
  
});

  
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