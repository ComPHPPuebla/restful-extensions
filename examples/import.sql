PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE users (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(20) NOT NULL,
    password VARCHAR(20) NOT NULL
);
COMMIT;

INSERT INTO users
SELECT
    1 AS user_id,
    'montealegreluis' AS username,
    'changeme' AS password
UNION
SELECT
    2,
    'michmendar',
    'letmein'
UNION
SELECT
    3,
    'johndoe',
    'secret';
