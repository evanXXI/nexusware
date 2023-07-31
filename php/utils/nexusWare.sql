CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(60) NOT NULL,
    lastname VARCHAR(60) NOT NULL,
    birthdate DATE NOT NULL, 
    admin TINYINT(1) NOT NULL DEFAULT 0,
    street_number VARCHAR(11) NOT NULL,
    street_name VARCHAR(60) NOT NULL,
    zip_code VARCHAR(60) NOT NULL,
    country VARCHAR(60) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    pwd VARCHAR(60) NOT NULL,

    PRIMARY KEY (id)
);

CREATE TABLE messages (
    id INT(11) NOT NULL AUTO_INCREMENT,
    object VARCHAR(255) NOT NULL,
    content VARCHAR(765) NOT NULL,
    sending_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    recipient_id INT(11) DEFAULT NULL,
    user_id INT(11) NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (recipient_id) REFERENCES users(id)
);

CREATE TABLE wishlists (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(60),
    user_id INT(11) NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE categories (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(60) NOT NULL,
    image VARCHAR(255),
    
    PRIMARY KEY (id)
);
 
CREATE TABLE products (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(60) NOT NULL,
    description MEDIUMTEXT NOT NULL,
    price DECIMAL(5, 2) NOT NULL,
    stock INT(5) NOT NULL,
    sold_units INT(5) NOT NULL DEFAULT 0,
    adding_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    wireless TINYINT(1) NOT NULL,
    image VARCHAR(255),
    category_id INT(11) NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE orders (
    order_id INT(11) NOT NULL AUTO_INCREMENT,
    order_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(4,2) NOT NULL DEFAULT 0,
    order_status TINYTEXT,
    user_id INT(11) NOT NULL,

    PRIMARY KEY (order_id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE contains (
    wishlist_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,

    FOREIGN KEY (wishlist_id) REFERENCES wishlists(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE isPartOf(
    order_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    product_qty INT(3) NOT NULL,

    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);