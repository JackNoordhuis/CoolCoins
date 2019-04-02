-- #!mysql
-- #{coolcoins
-- #    {init
-- #        {players
CREATE TABLE IF NOT EXISTS players (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) UNIQUE NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    updated_at INT UNSIGNED NOT NULL
);
-- #        }
-- #        {accounts
CREATE TABLE IF NOT EXISTS accounts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner VARCHAR(255) UNIQUE NOT NULL,
    owner_id INT UNSIGNED NOT NULL,
    owner_type TINYINT UNSIGNED NOT NULL,
    balance INT,
    created_at INT UNSIGNED NOT NULL,
    updated_at INT UNSIGNED NOT NULL
);
-- #        }
-- #    }
-- #    {save
-- #        {player
INSERT INTO players (username, created_at, updated_at)
VALUES (:username, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())
ON DUPLICATE KEY UPDATE updated_at = UNIX_TIMESTAMP();
-- #        }
-- #        {account
INSERT INTO accounts (owner, owner_id, owner_type, balance, created_at, updated_at)
VALUES (:owner, :owner_id, :type, :balance, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())
ON DUPLICATE KEY UPDATE balance = :balance, updated_at = UNIX_TIMESTAMP();
-- #        }
-- #    }
-- #    {read
-- #        {account
-- #            {bal
SELECT balance FROM accounts WHERE owner = :owner;
-- #            }
-- #            {all
SELECT * FROM accounts WHERE owner = :owner;
-- #            }
-- #        }
-- #    }
-- #}