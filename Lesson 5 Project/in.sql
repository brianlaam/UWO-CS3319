DROP DATABASE IF EXISTS flip5;
CREATE DATABASE flip5;
USE flip5;
CREATE TABLE product(prodid INT NOT NULL PRIMARY KEY AUTO_INCREMENT, oldprodid INT, quantityonhand INT, priceperunit FLOAT(8,2));
SELECT * FROM product;

-- make a stored procedure that creates LOTS of rows

DELIMITER $$
CREATE PROCEDURE prepare_data()
  BEGIN
    DECLARE i INT DEFAULT 100;
    DECLARE j INT DEFAULT 0;
    DECLARE cost FLOAT(8,2) DEFAULT 1.00;
    WHILE i < 100000 DO
      SET cost = RAND() * 1000;
      SET j = FLOOR(RAND() * 100);
      INSERT INTO product(oldprodid, quantityonhand,priceperunit) VALUES (i,j, cost);
      SET i = i + 1;
   END WHILE;
END$$
DELIMITER ;

-- make the stored procedure run
-- note: this will take a LONG time!
CALL prepare_data();
