-- create the databases
CREATE DATABASE IF NOT EXISTS startrackerdb;

-- create the users for each database
CREATE USER 'strtrckr_user'@'%' IDENTIFIED BY 'pass@strtrckr';
GRANT CREATE, ALTER, INDEX, LOCK TABLES, REFERENCES, UPDATE, DELETE, DROP, SELECT, INSERT ON `projectone`.* TO 'projectoneuser'@'%';

FLUSH PRIVILEGES;
