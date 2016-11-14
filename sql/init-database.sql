CREATE TABLE users
(
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR( 100 ) NOT NULL UNIQUE,
    state ENUM('0', '1') NOT NULL DEFAULT '0'
);

CREATE TABLE queries
(
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    value VARCHAR( 5000 ) NOT NULL
);

CREATE TABLE docs
(
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    value VARCHAR( 5000 ) NOT NULL
);

CREATE TABLE modelresults
(
    systyp INTEGER NOT NULL,
    qid INTEGER NOT NULL,
    rank INTEGER NOT NULL,
    did INTEGER NOT NULL,
    context VARCHAR( 5000 ) NOT NULL,
    PRIMARY KEY (qid, rank, systyp),
    FOREIGN KEY (qid) REFERENCES queries(id),
    FOREIGN KEY (did) REFERENCES docs(id)
);

CREATE TABLE evalresults
(
	uid INTEGER NOT NULL,
    systyp INTEGER NOT NULL,
    qid INTEGER NOT NULL,
    oldrank INTEGER NOT NULL,
    newrank INTEGER NOT NULL,
    did INTEGER NOT NULL,
    relevant ENUM('0', '1', '2') NOT NULL,
    PRIMARY KEY (uid, qid, oldrank, systyp),
    FOREIGN KEY (uid) REFERENCES users(id),
    FOREIGN KEY (qid) REFERENCES queries(id),
    FOREIGN KEY (did) REFERENCES docs(id)
);
