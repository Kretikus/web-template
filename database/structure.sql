-- Version 1

CREATE TABLE scheme_version (name VARCHAR(20) UNIQUE, version INT);
CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    login VARCHAR(255) NOT NULL,
                    firstname VARCHAR(255) NOT NULL,
                    surname VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    pwdHash VARCHAR(255) NOT NULL,
                    pwdSalt VARCHAR(255) NOT NULL,
                    permissions INT DEFAULT 0,
                    flags int DEFAULT 0
                    );
INSERT INTO scheme_version VALUES ('db', 1);

-- sha256
INSERT INTO users VALUES (0, 'roman', 'roman', 'himmes', 'roman@himmes.com',
                          '05f1f29dadc4e70a425321bf20bf2e5c9bcc6af73f732471cf62d43d390f0b65 ',
                          '1234567890', 1, 0);

-- Version 2


-- Version 255


-- EOF
