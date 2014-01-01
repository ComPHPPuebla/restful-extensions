PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE users (
    username VARCHAR(20) NOT NULL,
    password VARCHAR(20) NOT NULL,
    PRIMARY KEY(username)
);
COMMIT;

INSERT INTO users
SELECT
    'montealegreluis' AS username,
    'changeme' AS password
UNION
SELECT
    'michmendar',
    'letmein'
UNION
SELECT
    'johndoe',
    'secret';
