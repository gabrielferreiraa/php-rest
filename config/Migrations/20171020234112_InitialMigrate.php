<?php
use Migrations\AbstractMigration;

class InitialMigrate extends AbstractMigration
{
    public function up()
    {

        /* Creating companies table */
        $SQL = "CREATE TABLE companies
                (  id serial NOT NULL, 
                   name CHARACTER VARYING(255) NOT NULL, 
                   cnpj CHARACTER VARYING(14) NOT NULL, 
                   created TIMESTAMP without time ZONE, 
                   modified TIMESTAMP without time ZONE, 
                   CONSTRAINT pk_companies PRIMARY KEY (id));";
        $this->execute($SQL);

        /* Creating orders table */
        $SQL = "CREATE TABLE orders
                (  id serial NOT NULL, 
                   company_id INTEGER NOT NULL, 
                   created TIMESTAMP without TIME ZONE, 
                   modified TIMESTAMP without TIME ZONE, 
                   CONSTRAINT pk_orders PRIMARY KEY (id),
                   CONSTRAINT orders_fk1 FOREIGN KEY (company_id)
                        REFERENCES companies (id) MATCH SIMPLE);";
        $this->execute($SQL);

        /* Creating users table */
        $SQL = "CREATE TABLE users
                (  id serial NOT NULL, 
                   email CHARACTER VARYING (45) NOT NULL, 
                   password CHARACTER VARYING (255) NOT NULL,
                   token CHARACTER VARYING (255),
                   created TIMESTAMP without TIME ZONE, 
                   modified TIMESTAMP without TIME ZONE, 
                   CONSTRAINT pk_users PRIMARY KEY (id));";
        $this->execute($SQL);

        /* Creating products table */
        $SQL = "CREATE TABLE products
                (  id serial NOT NULL, 
                   name CHARACTER VARYING(255) NOT NULL, 
                   created TIMESTAMP without TIME ZONE, 
                   modified TIMESTAMP without TIME ZONE, 
                   CONSTRAINT pk_products PRIMARY KEY (id));";
        $this->execute($SQL);

        /* Creating order_products table */
        $SQL = "CREATE TABLE order_products
                (  id serial NOT NULL, 
                   order_id INTEGER NOT NULL, 
                   product_id INTEGER NOT NULL, 
                   created TIMESTAMP without TIME ZONE, 
                   modified TIMESTAMP without TIME ZONE, 
                   CONSTRAINT pk_order_products PRIMARY KEY (id),
                   CONSTRAINT order_products_fk1 FOREIGN KEY (order_id)
                        REFERENCES orders (id) MATCH SIMPLE,
                    CONSTRAINT orders__products_fk2 FOREIGN KEY (product_id)
                        REFERENCES products (id) MATCH SIMPLE);";
        $this->execute($SQL);
    }

    public function down()
    {
        $SQL = "
            DROP TABLE order_products;
            DROP TABLE products;
            DROP TABLE orders;
            DROP TABLE users;
            DROP TABLE companies;
        ";
        $this->execute($SQL);
    }
}
