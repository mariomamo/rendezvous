CREATE TABLE rendezvous (
    id varchar(200) UNIQUE NOT NULL,
    auth_code varchar(255) NOT NULL,
    one_shot boolean NOT NULL,
    exp datetime,
    data_type varchar(50) NOT NULL,
    data text NOT NULL,
    PRIMARY KEY(id)
);