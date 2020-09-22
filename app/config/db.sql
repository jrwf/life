create table moneyAdd (
    moneyId int not null auto_increment primary key,
    amount decimal(9,2) not null default 0,
    category int (11),
    description text,
    addTime datetime,
    created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

create table moneySpend (
    moneyId int not null auto_increment primary key,
    amount decimal(9,2) not null default 0,
    category int (11),
    description text,
    spendTime datetime,
    created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE categoryAdd (
    categoryAddId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    categoryAdd VARCHAR (250),
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE categorySpend (
    categorySpendId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    categorySpend VARCHAR (250),
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
