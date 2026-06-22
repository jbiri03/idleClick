//CONNECT TO DATABASE
const { DatabaseSync } = require('node:sqlite');

//CREATE DATABASE
const database = new DatabaseSync('test.db');

//INSERT
const insert = database.prepare('INSERT INTO data (id, username) VALUES (?, ?)');
 

//TEST INSERT
// insert.run(6, 'hello');
// insert.run(7, 'world');
 

//TEST WITH SELECT * and RETURN * IN CONSOLE
const query = database.prepare('SELECT * FROM data ORDER BY id');
console.log(query.all());
 