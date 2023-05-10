DROP DATABASE IF EXISTS company;
CREATE DATABASE company;
USE company;
DROP TABLE IF EXISTS department;
DROP TABLE IF EXISTS employee;
USE company;

CREATE TABLE department(id INT PRIMARY KEY  AUTO_INCREMENT, name VARCHAR(45));
CREATE TABLE employee(id INT PRIMARY KEY AUTO_INCREMENT,
                      firstName VARCHAR(45), lastName VARCHAR(45), departmentId INT);

INSERT department VALUES
                      (NULL, 'Verkaufsstand'),
                      (NULL, 'Marketing');

INSERT INTO employee VALUES
                         (NULL, 'Peter','Pan',1),
                         (NULL, 'Petra', 'Pammmm',2),
                         (NULL, 'Carl', 'Charly',2),
                         (NULL, 'a', 'b',2),
                         (NULL, 'Tom', 'Tamtam',1);

ALTER TABLE employee
    ADD FOREIGN KEY (departmentId) REFERENCES department(id);