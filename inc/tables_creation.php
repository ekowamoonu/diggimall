<?php
   include('../conn/db_connection.php');//contains database class
   $conn=new DB_Connection();
    
   /*create sellers table*/ 
   $sellers="CREATE TABLE IF NOT EXISTS SELLERS(";
   $sellers.="SELLER_ID INT AUTO_INCREMENT,";
   $sellers.="SELLER_USERNAME VARCHAR(40) UNIQUE,";
   $sellers.="SELLER_PASSWORD VARCHAR(240),";
   $sellers.="SELLER_NAME VARCHAR(70),";
   $sellers.="SELLER_PHONE VARCHAR(20),";
   $sellers.="SELLER_WHATSAPP VARCHAR(20),";
   $sellers.="SELLER_EMAIL VARCHAR(60),";
   $sellers.="SELLER_HALL VARCHAR(60),";
   $sellers.="SELLER_LEVEL VARCHAR(22),";
   $sellers.="SELLER_PROFILE_PIC VARCHAR(130),";
   $sellers.="SELLER_ABOUT TEXT DEFAULT NULL,";
   $sellers.="MOBILE_MONEY_VENDOR VARCHAR(15) DEFAULT NULL,";
   $sellers.="MOBILE_MONEY_ACCOUNT VARCHAR(12) DEFAULT NULL,";
   $sellers.="BANK_NAME VARCHAR(20) DEFAULT NULL,";
   $sellers.="BANK_ACCOUNT_NAME VARCHAR(50) DEFAULT NULL,";
   $sellers.="BANK_ACCOUNT_NUMBER VARCHAR(50) DEFAULT NULL,";
   $sellers.="SELLER_TYPE VARCHAR(30),";
   $sellers.="SELLER_STATUS VARCHAR(20),";
   $sellers.="SELLER_ACCESS BOOLEAN DEFAULT 0,";
   $sellers.="AVAILABILITY BOOLEAN DEFAULT 0,";
   $sellers.="TAGLINE VARCHAR(200) DEFAULT NULL,";
   $sellers.="PRIMARY KEY(SELLER_ID)";
   $sellers.=");";

   $conn->run_table_query($sellers,"SELLERS");

   /*create buyers table*/ 
   $buyers="CREATE TABLE IF NOT EXISTS BUYERS(";
   $buyers.="BUYER_ID INT AUTO_INCREMENT,";
   $buyers.="BUYER_USERNAME VARCHAR(30) UNIQUE,";
   $buyers.="BUYER_NAME VARCHAR(50),";
   $buyers.="BUYER_PASSWORD VARCHAR(240),";
   $buyers.="BUYER_PHONE VARCHAR(15),";
   $buyers.="BUYER_WHATSAPP VARCHAR(15),";
   $buyers.="BUYER_EMAIL VARCHAR(45),";
   $buyers.="BUYER_HALL VARCHAR(60),";
   $buyers.="BUYER_ROOM_NUMBER VARCHAR(7) DEFAULT NULL,";
   $buyers.="BUYER_NUMBER_OF_ORDERS INT,";
   $buyers.="FCM_TOKEN TEXT,";
   $buyers.="PRIMARY KEY(BUYER_ID)";
   $buyers.=");";

   $conn->run_table_query($buyers,"BUYERS");


   /*create main category table*/ 
   $main_category="CREATE TABLE IF NOT EXISTS MAIN_CATEGORY(";
   $main_category.="MAIN_CATEGORY_ID INT AUTO_INCREMENT,";
   $main_category.="MAIN_CATEGORY_NAME VARCHAR(60),";
   $main_category.="COMMISSION_PERCENTAGE DECIMAL(3,2),";
   $main_category.="MAIN_CATEGORY_IMAGE VARCHAR(120) DEFAULT NULL,";
   $main_category.="PRIMARY KEY(MAIN_CATEGORY_ID)";
   $main_category.=");";

   $conn->run_table_query($main_category,"MAIN_CATEGORY");

   /*populate main category*/
   $main_insert="INSERT INTO MAIN_CATEGORY(MAIN_CATEGORY_NAME,COMMISSION_PERCENTAGE) VALUES('mens fashion',0),";
   $main_insert.="('womens fashion',0),";
   $main_insert.="('beauty and perfumes',0),";
   $main_insert.="('mobile phones and tablets',0),";
   $main_insert.="('computing',0),";
   $main_insert.="('hostel and living',0),";
   $main_insert.="('electronics and gadgets',0),";
   $main_insert.="('games and entertainment',0),";
   $main_insert.="('pharmaceuticals,health and fitness',0),";
   $main_insert.="('groceries and provisions',0),";
   $main_insert.="('meals',0)";

   $connection=mysqli_query(DB_Connection::$connection,$main_insert);
   echo $connection?"MAIN_CATEGORY POPULATED":mysqli_error(DB_Connection::$connection);


   /*create sub category table*/ 
   $sub_category="CREATE TABLE IF NOT EXISTS SUB_CATEGORY(";
   $sub_category.="SUB_CATEGORY_ID INT AUTO_INCREMENT,";
   $sub_category.="PARENT_CATEGORY_ID INT,";
   $sub_category.="SUB_CATEGORY_NAME VARCHAR(60),";
   $sub_category.="SUB_COMMISSION_PERCENTAGE DECIMAL(3,2),";
   $sub_category.="SUB_CATEGORY_IMAGE VARCHAR(120) DEFAULT NULL,";
   $sub_category.="PRIMARY KEY(SUB_CATEGORY_ID),";
   $sub_category.="FOREIGN KEY(PARENT_CATEGORY_ID) REFERENCES MAIN_CATEGORY(MAIN_CATEGORY_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $sub_category.=");";

   $conn->run_table_query($sub_category,"SUB_CATEGORY");


   /*populate sub category*/
    $sub_insert="INSERT INTO SUB_CATEGORY(PARENT_CATEGORY_ID,SUB_CATEGORY_NAME,SUB_COMMISSION_PERCENTAGE) VALUES(1,'mens accessories',0),";
    $sub_insert.="(1,'mens clothing',0),";
    $sub_insert.="(1,'mens shoes',0),";
    $sub_insert.="(2,'womens accessories',0),";
    $sub_insert.="(2,'womens clothing',0),";
    $sub_insert.="(2,'womens shoes',0),";
    $sub_insert.="(3,'fragrances and deodorants',0),";
    $sub_insert.="(3,'hair and haircare',0),";
    $sub_insert.="(3,'makeup',0),";
    $sub_insert.="(3,'men',0),";
    $sub_insert.="(3,'skin care',0),";
    $sub_insert.="(4,'mobile phone accessories',0),";
    $sub_insert.="(4,'phones',0),";
    $sub_insert.="(4,'tablets',0),";
    $sub_insert.="(5,'desktop',0),";
    $sub_insert.="(5,'laptops',0),";
    $sub_insert.="(5,'computing accessories',0),";
    $sub_insert.="(6,'domestic',0),";
    $sub_insert.="(6,'kitchen',0),";
    $sub_insert.="(7,'audio & video',0),";
    $sub_insert.="(7,'cameras',0),";
    $sub_insert.="(7,'cool televisions',0),";
    $sub_insert.="(8,'board games',0),";
    $sub_insert.="(8,'play station',0),";
    $sub_insert.="(8,'xbox one',0),";
    $sub_insert.="(9,'drugs',0),";
    $sub_insert.="(9,'fitness',0),";
    $sub_insert.="(10,'vegetables and fruits',0),";
    $sub_insert.="(10,'pastries',0),";
    $sub_insert.="(10,'provisions',0),";
    $sub_insert.="(11,'exotic',0),";
    $sub_insert.="(11,'local',0),";
    $sub_insert.="(11,'Night Market Meals',0)";

   $connection=mysqli_query(DB_Connection::$connection,$sub_insert);
   echo $connection?"SUB_CATEGORY POPULATED":mysqli_error(DB_Connection::$connection);

   /*create sub set table*/ 
   $sub_set="CREATE TABLE IF NOT EXISTS SUB_SET(";
   $sub_set.="SUB_SET_ID INT AUTO_INCREMENT,";
   $sub_set.="PARENT_SUB_CATEGORY_ID INT,";
   $sub_set.="SUB_SET_NAME VARCHAR(60),";
   $sub_set.="SUB_SET_COMMISSION_PERCENTAGE DECIMAL(3,2),";
   $sub_set.="PRIMARY KEY(SUB_SET_ID),";
   $sub_set.="FOREIGN KEY(PARENT_SUB_CATEGORY_ID) REFERENCES SUB_CATEGORY(SUB_CATEGORY_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $sub_set.=");";

   $conn->run_table_query($sub_set,"SUBSET");


   /*populate sub_set table*/
   $sub_set_insert="INSERT INTO SUB_SET(PARENT_SUB_CATEGORY_ID,SUB_SET_NAME,SUB_SET_COMMISSION_PERCENTAGE) VALUES";
   $sub_set_insert.="(1,'bags',0),";
   $sub_set_insert.="(1,'belts',0),";
   $sub_set_insert.="(1,'cufflinks',0),";
   $sub_set_insert.="(1,'eyeglasses',0),";
   $sub_set_insert.="(1,'caps and hats',0),";
   $sub_set_insert.="(1,'mens jewellery & watches',0),";
   $sub_set_insert.="(1,'ties',0),";
   $sub_set_insert.="(1,'wristband and bracelets',0),";
   $sub_set_insert.="(1,'socks',0),";
   $sub_set_insert.="(2,'coats & jackets',0),";
   $sub_set_insert.="(2,'mens underwear',0),";
   $sub_set_insert.="(2,'shorts',0),";
   $sub_set_insert.="(2,'formal wear',0),";
   $sub_set_insert.="(2,'sports wear and jerseys',0),";
   $sub_set_insert.="(2,'t-shirts & polos',0),";
   $sub_set_insert.="(2,'jeans',0),";
   $sub_set_insert.="(2,'vests',0),";
   $sub_set_insert.="(2,'african wear',0),";
   $sub_set_insert.="(2,'cardigans & jumpers',0),";
   $sub_set_insert.="(3,'boots & shoes',0),";
   $sub_set_insert.="(3,'slippers & sandals',0),";
   $sub_set_insert.="(4,'bags',0),";
   $sub_set_insert.="(4,'jewellery & bracellets',0),";
   $sub_set_insert.="(4,'womens watches',0),";
   $sub_set_insert.="(5,'african wear',0),";
   $sub_set_insert.="(5,'coats & jackets',0),";
   $sub_set_insert.="(5,'dresses',0),";
   $sub_set_insert.="(5,'jumpers & cardigans',0),";
   $sub_set_insert.="(5,'lingerie & nightwear',0),";
   $sub_set_insert.="(5,'formal wear',0),";
   $sub_set_insert.="(5,'skirts and shorts',0),";
   $sub_set_insert.="(5,'t-shirts and casual',0),";
   $sub_set_insert.="(5,'tops',0),";
   $sub_set_insert.="(5,'jeans and trousers',0),";
   $sub_set_insert.="(6,'ballerinas & flats',0),";
   $sub_set_insert.="(6,'heels',0),";
   $sub_set_insert.="(6,'sandals & slippers',0),";
   $sub_set_insert.="(6,'wedges',0),";
   $sub_set_insert.="(6,'ladies sneakers',0),";
   $sub_set_insert.="(7,'mens fragrances',0),";
   $sub_set_insert.="(7,'womens fragrances',0),";
   $sub_set_insert.="(7,'unisex',0),";
   $sub_set_insert.="(8,'extensions and wings',0),";
   $sub_set_insert.="(8,'hair products',0),";
   $sub_set_insert.="(8,'hair accessories',0),";
   $sub_set_insert.="(9,'cheeks',0),";
   $sub_set_insert.="(9,'eyes',0),";
   $sub_set_insert.="(9,'face',0),";
   $sub_set_insert.="(9,'lips',0),";
   $sub_set_insert.="(9,'makeup accessories',0),";
   $sub_set_insert.="(9,'manicure and pedicure',0),";
   $sub_set_insert.="(9,'maybelline shop',0),";
   $sub_set_insert.="(10,'shaving & hair accessories',0),";
   $sub_set_insert.="(11,'bath & shower',0),";
   $sub_set_insert.="(11,'facial and skin',0),";
   $sub_set_insert.="(11,'womens lotions and creams',0),";
   $sub_set_insert.="(11,'mens lotions and creams',0),";
   $sub_set_insert.="(11,'unisex',0),";
   $sub_set_insert.="(12,'batteries & adapters',0),";
   $sub_set_insert.="(12,'cases,covers & screens',0),";
   $sub_set_insert.="(12,'earpiece & headsets',0),";
   $sub_set_insert.="(12,'power banks',0),";
   $sub_set_insert.="(12,'others',0),";
   $sub_set_insert.="(13,'samsung',0),";
   $sub_set_insert.="(13,'HTC',0),";
   $sub_set_insert.="(13,'nokia',0),";
   $sub_set_insert.="(13,'iphone',0),";
   $sub_set_insert.="(13,'LG',0),";
   $sub_set_insert.="(13,'Sony Erricson',0),";
   $sub_set_insert.="(13,'Black Berry',0),";
   $sub_set_insert.="(13,'tecno',0),";
   $sub_set_insert.="(13,'huawei',0),";
   $sub_set_insert.="(13,'infinix',0),";
   $sub_set_insert.="(13,'others',0),";
   $sub_set_insert.="(14,'all brands',0),";
   $sub_set_insert.="(15,'dell',0),";
   $sub_set_insert.="(15,'acer',0),";
   $sub_set_insert.="(15,'toshiba',0),";
   $sub_set_insert.="(15,'lenovo',0),";
   $sub_set_insert.="(15,'hp',0),";
   $sub_set_insert.="(15,'imac',0),";
   $sub_set_insert.="(15,'others',0),";
   $sub_set_insert.="(16,'dell',0),";
   $sub_set_insert.="(16,'toshiba',0),";
   $sub_set_insert.="(16,'hp',0),";
   $sub_set_insert.="(16,'acer',0),";
   $sub_set_insert.="(16,'lenovo',0),";
   $sub_set_insert.="(16,'macbooks',0),";
   $sub_set_insert.="(16,'others',0),";
   $sub_set_insert.="(16,'toshiba',0),";
   $sub_set_insert.="(17,'peripherals and accessories',0),";
   $sub_set_insert.="(17,'spare and extra parts',0),";
   $sub_set_insert.="(18,'bathroom accessories',0),";
   $sub_set_insert.="(18,'room decor & furniture',0),";
   $sub_set_insert.="(18,'stationary',0),";
   $sub_set_insert.="(19,'knives',0),";
   $sub_set_insert.="(19,'Rice Cookers & Burners',0),";
   $sub_set_insert.="(19,'Fridges & Microwaves',0),";
   $sub_set_insert.="(19,'Other Domestic Gadgets',0),";
   $sub_set_insert.="(19,'Bowls, Cups & others',0),";
   $sub_set_insert.="(20,'home theatre systems',0),";
   $sub_set_insert.="(20,'speaker systems',0),";
   $sub_set_insert.="(20,'headphones, earphones & Cds',0),";
   $sub_set_insert.="(21,'Digital Cameras',0),";
   $sub_set_insert.="(22,'3D & Smart TVs',0),";
   $sub_set_insert.="(22,'Plasma TVs',0),";
   $sub_set_insert.="(22,'LED TVs',0),";
   $sub_set_insert.="(22,'Flat Panel TVs',0),";
   $sub_set_insert.="(23,'All Board Games',0),";
   $sub_set_insert.="(24,'ps 3',0),";
   $sub_set_insert.="(24,'ps 4',0),";
   $sub_set_insert.="(24,'ps CDs and Accessories',0),";
   $sub_set_insert.="(25,'XBox',0),";
   $sub_set_insert.="(25,'XBox accessories',0),";
   $sub_set_insert.="(25,'other consoles',0),";
   $sub_set_insert.="(26,'fitness & supplements',0),";
   $sub_set_insert.="(26,'sex & contraceptives',0),";
   $sub_set_insert.="(26,'others',0),";
   $sub_set_insert.="(27,'treadmill,machine & weight',0),";
   $sub_set_insert.="(27,'fitness accessories',0),";
   $sub_set_insert.="(28,'fresh vegetables',0),";
   $sub_set_insert.="(28,'fisheries & meats',0),";
   $sub_set_insert.="(28,'spices & others',0),";
   $sub_set_insert.="(28,'fruits',0),";
   $sub_set_insert.="(29,'cakes',0),";
   $sub_set_insert.="(29,'pies and doughnuts',0),";
   $sub_set_insert.="(29,'bread',0),";
   $sub_set_insert.="(29,'biscuits & chips',0),";
   $sub_set_insert.="(30,'Powered Milk',0),";
   $sub_set_insert.="(30,'Chocolate Based drinks',0),";
   $sub_set_insert.="(30,'other breakfast and soft items',0),";
   $sub_set_insert.="(30,'Rice Bags',0),";
   $sub_set_insert.="(30,'lunch and cookables',0),";
   $sub_set_insert.="(30,'softdrinks and beverages',0),";
   $sub_set_insert.="(30,'strong drinks',0),";
   $sub_set_insert.="(31,'Rice based meals',0),";
   $sub_set_insert.="(31,'Pizzas',0),";
   $sub_set_insert.="(32,'Stews',0),";
   $sub_set_insert.="(32,'Other Meals',0),";
   $sub_set_insert.="(33,'Foods',0)";

   $connection=mysqli_query(DB_Connection::$connection,$sub_set_insert);
   echo $connection?"SUB_SET POPULATED":mysqli_error(DB_Connection::$connection);



   /*create products table*/ 
   $products="CREATE TABLE IF NOT EXISTS PRODUCTS(";
   $products.="PRODUCT_ID INT AUTO_INCREMENT,";
   $products.="MAIN_CAT_ID INT,";
   $products.="SUB_CAT_ID INT,";
   $products.="SUB_S_ID INT,";
   $products.="SEL_ID INT,";
   $products.="PRODUCT_NAME VARCHAR(50),";
   $products.="PRODUCT_CODE VARCHAR(150),";
   $products.="PRODUCT_DESCRIPTION TEXT,";
   $products.="PRODUCT_PRICE DECIMAL(11,2),";
   $products.="AVAILABLE_STOCK INT DEFAULT 0,";
   $products.="PRE_ORDER BOOLEAN DEFAULT 0,";
   $products.="TOTAL_ORDERED_QUANTITY INT,";
   $products.="COMMISSION_ON_PRODUCT DECIMAL(3,2),";
   $products.="UPLOAD_DATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
   $products.="PRODUCT_AVAILABILITY BOOLEAN DEFAULT 1,";
   $products.="DISCOUNT DECIMAL(11,2) DEFAULT 0,";
   $products.="PRIMARY KEY(PRODUCT_ID),";
   $products.="FOREIGN KEY(MAIN_CAT_ID) REFERENCES MAIN_CATEGORY(MAIN_CATEGORY_ID) ON DELETE CASCADE ON UPDATE CASCADE,";
   $products.="FOREIGN KEY(SUB_CAT_ID) REFERENCES SUB_CATEGORY(SUB_CATEGORY_ID) ON DELETE CASCADE ON UPDATE CASCADE,";
   $products.="FOREIGN KEY(SUB_S_ID) REFERENCES SUB_SET(SUB_SET_ID) ON DELETE CASCADE ON UPDATE CASCADE,";
   $products.="FOREIGN KEY(SEL_ID) REFERENCES SELLERS(SELLER_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $products.=");";

   $conn->run_table_query($products,"PRODUCTS");


   /*create product small image table*/ 
   $pdt_small="CREATE TABLE IF NOT EXISTS PRODUCT_SMALL_IMAGE(";
   $pdt_small.="SMALL_IMAGE_ID INT AUTO_INCREMENT,";
   $pdt_small.="PDS_ID INT,";
   $pdt_small.="SMALL_IMAGE_FILE VARCHAR(130),";
   $pdt_small.="PRIMARY KEY(SMALL_IMAGE_ID),";
   $pdt_small.="FOREIGN KEY(PDS_ID) REFERENCES PRODUCTS(PRODUCT_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $pdt_small.=");";

   $conn->run_table_query($pdt_small,"SMALL PRODUCT IMAGE");

  
   /*create product large image table*/ 
   $pdt_large="CREATE TABLE IF NOT EXISTS PRODUCT_LARGE_IMAGE(";
   $pdt_large.="LARGE_IMAGE_ID INT AUTO_INCREMENT,";
   $pdt_large.="PDL_ID INT,";
   $pdt_large.="LARGE_IMAGE_FILE VARCHAR(130),";
   $pdt_large.="PRIMARY KEY(LARGE_IMAGE_ID),";
   $pdt_large.="FOREIGN KEY(PDL_ID) REFERENCES PRODUCTS(PRODUCT_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $pdt_large.=");";

   $conn->run_table_query($pdt_large,"LARGE PRODUCT IMAGE");


   /*create reviews table*/ 
   $reviews="CREATE TABLE IF NOT EXISTS REVIEWS(";
   $reviews.="REVIEW_ID INT AUTO_INCREMENT,";
   $reviews.="PDT_ID INT,";
   $reviews.="REVIEWER_ID INT,";
   $reviews.="REVIEW_CONTENT TEXT,";
   $reviews.="REVIEWER_RATING INT NOT NULL,";
   $reviews.="REVIEW_DATE TIMESTAMP,";
   $reviews.="REVIEW_APPROVAL BOOLEAN DEFAULT 1,";
   $reviews.="PRIMARY KEY(REVIEW_ID),";
   $reviews.="FOREIGN KEY(PDT_ID) REFERENCES PRODUCTS(PRODUCT_ID) ON DELETE CASCADE ON UPDATE CASCADE,";
   $reviews.="FOREIGN KEY(REVIEWER_ID) REFERENCES BUYERS(BUYER_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $reviews.=");";

   $conn->run_table_query($reviews,"REVIEWS");

   /*Wishlist*/ 
   $wishlist="CREATE TABLE IF NOT EXISTS WISHLIST(";
   $wishlist.="WISHLIST_ID INT AUTO_INCREMENT,";
   $wishlist.="PDCT_ID INT,";
   $wishlist.="CUSTOMER_ID INT,";
   $wishlist.="PRIMARY KEY(WISHLIST_ID),";
   $wishlist.="FOREIGN KEY(PDCT_ID) REFERENCES PRODUCTS(PRODUCT_ID) ON DELETE CASCADE ON UPDATE CASCADE,";
   $wishlist.="FOREIGN KEY(CUSTOMER_ID) REFERENCES BUYERS(BUYER_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $wishlist.=");";

   $conn->run_table_query($wishlist,"WISHLIST");

   /*followed shops*/
   $shops="CREATE TABLE IF NOT EXISTS FOLLOWED_SHOPS(";
   $shops.="FOLLOWED_SHOP_ID INT AUTO_INCREMENT,";
   $shops.="SHOP_ID INT,";
   $shops.="CSTMR_ID INT,";
   $shops.="PRIMARY KEY(FOLLOWED_SHOP_ID),";
   $shops.="FOREIGN KEY(SHOP_ID) REFERENCES SELLERS(SELLER_ID) ON DELETE CASCADE ON UPDATE CASCADE,";
   $shops.="FOREIGN KEY(CSTMR_ID) REFERENCES BUYERS(BUYER_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $shops.=");";

   $conn->run_table_query($shops,"FOLLOWED_SHOPS");

   /*Recently Viewed*/
   $recently="CREATE TABLE IF NOT EXISTS RECENTLY_VIEWED(";
   $recently.="RECENTLY_ID INT AUTO_INCREMENT,";
   $recently.="RCNT_PRODUCT_ID INT,";
   $recently.="CUSTMR_ID INT,";
   $recently.="VIEWED_DATE TIMESTAMP,";
   $recently.="PRIMARY KEY(RECENTLY_ID),";
   $recently.="FOREIGN KEY(RCNT_PRODUCT_ID) REFERENCES PRODUCTS(PRODUCT_ID) ON DELETE CASCADE ON UPDATE CASCADE,";
   $recently.="FOREIGN KEY(CUSTMR_ID) REFERENCES BUYERS(BUYER_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $recently.=");";

   $conn->run_table_query($recently,"RECENTLY_VIEWED");


   /*create country table*/ 
   $visitor="CREATE TABLE IF NOT EXISTS VISITORS(";
   $visitor.="VISITOR_ID INT AUTO_INCREMENT,";
   $visitor.="VISITOR_NAME VARCHAR(50),";
   $visitor.="PRIMARY KEY(VISITOR_ID)";
   $visitor.=");";

   $conn->run_table_query($visitor,"VISITOR");


   /*create bag items table*/ 
   $bag="CREATE TABLE IF NOT EXISTS BAG_ITEMS(";
   $bag.="BAG_ID INT AUTO_INCREMENT,";
   $bag.="VSTR_ID INT,";
   $bag.="PRDT_ID INT,";
   $bag.="ORDERED_AMOUNT INT,";
   $bag.="PRODUCT_PRICE DECIMAL(10,2),";
   $bag.="REQUIREMENTS TEXT DEFAULT NULL,";
   $bag.="PRIMARY KEY(BAG_ID),";
   $bag.="FOREIGN KEY(PRDT_ID) REFERENCES PRODUCTS(PRODUCT_ID) ON DELETE CASCADE ON UPDATE CASCADE,";
   $bag.="FOREIGN KEY(VSTR_ID) REFERENCES VISITORS(VISITOR_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $bag.=");";

   $conn->run_table_query($bag,"BAG ITEMS");



   /*create orders table*/ 
   $orders="CREATE TABLE IF NOT EXISTS ORDERS(";
   $orders.="ORDER_ID INT AUTO_INCREMENT,";
   $orders.="PT_ID INT,";
   $orders.="MC_ID INT,";
   $orders.="SC_ID INT,";
   $orders.="SS_ID INT,";
   $orders.="SLR_ID INT,";
   $orders.="CUSTOMER_ID INT,";
   $orders.="CUSTOMER_NAME VARCHAR(70),";
   $orders.="CUSTOMER_PHONE VARCHAR(20),";
   $orders.="CUSTOMER_HALL VARCHAR(20),";
   $orders.="ORDERED_QUANTITY INT,";
   $orders.="TOTAL_COST_OF_ORDER DECIMAL(11,2),";
   $orders.="SPECIAL_REQUEST TEXT,";
   $orders.="ORDER_DATE_FULL TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
   $orders.="ORDER_YEAR VARCHAR(10),";
   $orders.="ORDER_MONTH VARCHAR(10),";
   $orders.="ORDER_NUMBER_DATE VARCHAR(3),";
   $orders.="ORDER_WEEK_DAY VARCHAR(10),";
   $orders.="DELIVERY_STATUS VARCHAR(13),";
   $orders.="MODIFIED_DATE VARCHAR(200) DEFAULT NULL,";
   $orders.="PRIMARY KEY(ORDER_ID),";
   $orders.="FOREIGN KEY(PT_ID) REFERENCES PRODUCTS(PRODUCT_ID) ON DELETE SET NULL ON UPDATE SET NULL,";
   $orders.="FOREIGN KEY(MC_ID) REFERENCES MAIN_CATEGORY(MAIN_CATEGORY_ID) ON DELETE SET NULL ON UPDATE SET NULL,";
   $orders.="FOREIGN KEY(SC_ID) REFERENCES SUB_CATEGORY(SUB_CATEGORY_ID) ON DELETE SET NULL ON UPDATE SET NULL,";
   $orders.="FOREIGN KEY(SLR_ID) REFERENCES SELLERS(SELLER_ID) ON DELETE SET NULL ON UPDATE SET NULL,";
   $orders.="FOREIGN KEY(SS_ID) REFERENCES SUB_SET(SUB_SET_ID) ON DELETE SET NULL ON UPDATE SET NULL";
   $orders.=");";

   $conn->run_table_query($orders,"ORDERS");

   //create cart table for mobile app
   $cart="CREATE TABLE IF NOT EXISTS CART(";
   $cart.="CART_ID INT AUTO_INCREMENT,";
   $cart.="BUYER_ID INT NOT NULL,";
   $cart.="CHECKED_OUT INT,";
   $cart.="PRIMARY KEY(CART_ID),";
   $cart.="FOREIGN KEY(BUYER_ID) REFERENCES BUYERS(BUYER_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $cart.=");";

   $conn->run_table_query($cart,"CART");

   //create cart items table for mobile app
   $cart_item="CREATE TABLE IF NOT EXISTS CARTITEM(";
   $cart_item.="CART_ITEM_ID INT AUTO_INCREMENT,";
   $cart_item.="CART_ID INT NOT NULL,";
   $cart_item.="PRODUCT_ID INT NOT NULL,";
   $cart_item.="QUANTITY INT NOT NULL DEFAULT 1,";
   $cart_item.="SPECIAL_REQUEST TEXT,";
   $cart_item.="PRIMARY KEY(CART_ITEM_ID),";
   $cart_item.="FOREIGN KEY(CART_ID) REFERENCES CART(CART_ID) ON DELETE CASCADE ON UPDATE CASCADE";
   $cart_item.=");";

   $conn->run_table_query($cart_item,"CART ITEM");

   //create delivery agent table
   $delivery_agent="CREATE TABLE IF NOT EXISTS DELIVERYAGENT(";
   $delivery_agent.="DELIVERY_AGENT_ID INT AUTO_INCREMENT,";
   $delivery_agent.="NAME VARCHAR(255) NOT NULL,";
   $delivery_agent.="TELEPHONE VARCHAR(15) UNIQUE,";
   $delivery_agent.="QUANTITY INT NOT NULL DEFAULT 1,";
   $delivery_agent.="DATE_CREATED TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
   $delivery_agent.="FCM_TOKEN TEXT,";
   $delivery_agent.="PRIMARY KEY(DELIVERY_AGENT_ID)";
   $delivery_agent.=");";

   $conn->run_table_query($delivery_agent,"DELIVERY AGENT");

   //create promo table
   $promo="CREATE TABLE IF NOT EXISTS PROMO(";
   $promo.="PROMO_ID INT AUTO_INCREMENT,";
   $promo.="NETWORK VARCHAR(50) DEFAULT NULL,";
   $promo.="AIRTIME VARCHAR(100),";
   $promo.="UPLOAD_DATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
   $promo.="PRIMARY KEY(PROMO_ID)";
   $promo.=");";

   $conn->run_table_query($promo,"PROMO");

   $insert_promo=mysqli_query(DB_Connection::$connection,"INSERT INTO PROMO(NETWORK,AIRTIME) VALUES('tigo','***************'),('airtel','*************'),('vodafone','**************'),('mtn','**************')");
   if($insert_promo){echo "promo set";}
   else{echo mysqli_error(DB_Connection::$connection);}


   /*createa affiliates table*/
   $affiliate="CREATE TABLE IF NOT EXISTS AFFILIATES(";
   $affiliate.="AFFILIATE_ID INT AUTO_INCREMENT,";
   $affiliate.="AFFILIATE_DIGGI_ID VARCHAR(11),";
   $affiliate.="AFFILIATE_USERNAME VARCHAR(30) UNIQUE,";
   $affiliate.="AFFILIATE_NAME VARCHAR(50),";
   $affiliate.="AFFILIATE_PASSWORD VARCHAR(240),";
   $affiliate.="AFFILIATE_PHONE VARCHAR(15),";
   $affiliate.="AFFILIATE_WHATSAPP VARCHAR(15),";
   $affiliate.="AFFILIATE_EMAIL VARCHAR(45),";
   $affiliate.="AFFIL_MOBILE_MONEY_VENDOR VARCHAR(15) DEFAULT NULL,";
   $affiliate.="AFFIL_MOBILE_MONEY_ACCOUNT VARCHAR(12) DEFAULT NULL,";
   $affiliate.="AFFILIATE_HALL VARCHAR(60),";
   $affiliate.="AFFILIATE_ROOM_NUMBER VARCHAR(7) DEFAULT NULL,";
   $affiliate.="AFFILIATE_PROFILE_PHOTO VARCHAR(240) DEFAULT NULL,";
   $affiliate.="AFFILIATE_ACCESS BOOLEAN DEFAULT 0,";
   $affiliate.="PRIMARY KEY(AFFILIATE_ID)";
   $affiliate.=");";

   $conn->run_table_query($affiliate,"AFFILIATES");


   //create  affiliate id updater
   $ids="CREATE TABLE IF NOT EXISTS IDS(";
   $ids.="AFFILIATE_IDS INT";
   $ids.=");";

   $conn->run_table_query($ids,"AFFILIATE ID UPDATER");

   //insert one value
   $in_id="INSERT INTO IDS(AFFILIATE_IDS) VALUES(37)";
   $conn->run_table_query($in_id,"INITIAL ID");

   //alter buyers table
   $buyers_table_altering="ALTER TABLE BUYERS ";
   $buyers_table_altering.="ADD COLUMN REFEROR_ID VARCHAR(13) DEFAULT NULL";
   $run_buyers_alter=mysqli_query(DB_Connection::$connection,$buyers_table_altering);

   echo $run_buyers_alter?"<h1>BUYERS TABLE ALTERED</h1>":"<h1>failed</h1>".mysqli_error(DB_Connection::$connection);

   //alter orders table
   $orders_table_altering="ALTER TABLE ORDERS ";
   $orders_table_altering.="ADD COLUMN BUYER_REFEROR_ID VARCHAR(11) DEFAULT NULL";
   $run_orders_alter=mysqli_query(DB_Connection::$connection,$orders_table_altering);

   echo $run_orders_alter?"<h1>ORDERS TABLE ALTERED</h1>":"<h1>failed</h1>".mysqli_error(DB_Connection::$connection);

   //alter orders table add unique random number
   $orders_table_altering="ALTER TABLE ORDERS ";
   $orders_table_altering.="ADD COLUMN RANDOM_TOKEN VARCHAR(5) DEFAULT NULL";
   $run_orders_alter=mysqli_query(DB_Connection::$connection,$orders_table_altering);

   echo $run_orders_alter?"<h1>ORDERS TABLE ALTERED</h1>":"<h1>failed</h1>".mysqli_error(DB_Connection::$connection);


   /*add category images*/
   $mens_fashion=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='menfashion.png' WHERE MAIN_CATEGORY_ID=1");
   $womens_fashion=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='womenfashion.jpg' WHERE MAIN_CATEGORY_ID=2");
   $beautyandperfumes=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='beautyperfumes.jpg' WHERE MAIN_CATEGORY_ID=3");
   $mbt=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='mbt.jpg' WHERE MAIN_CATEGORY_ID=4");
   $computing=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='computing.jpg' WHERE MAIN_CATEGORY_ID=5");
   $hostelandliving=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='hostelliving.jpg' WHERE MAIN_CATEGORY_ID=6");
   $electronics=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='electronics.jpg' WHERE MAIN_CATEGORY_ID=7");
   $games=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='gamingconsoles.jpg' WHERE MAIN_CATEGORY_ID=8");
   $health=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='health.jpg' WHERE MAIN_CATEGORY_ID=9");
   $groceries=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='groceries.jpg' WHERE MAIN_CATEGORY_ID=10");
   $meals=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='meals.jpg' WHERE MAIN_CATEGORY_ID=11");
   $tickets=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='tickets.jpg' WHERE MAIN_CATEGORY_ID=12");
   $unisex=mysqli_query(DB_Connection::$connection,"UPDATE MAIN_CATEGORY SET MAIN_CATEGORY_IMAGE='unisex.jpg' WHERE MAIN_CATEGORY_ID=15");



   /*create hungry orders table*/ 
   // $hungry_orders="CREATE TABLE IF NOT EXISTS HUNGRY_ORDERS(";
   // $hungry_orders.="HUNGRY_ORDER_ID INT AUTO_INCREMENT,";
   // $hungry_orders.="HUNGRY_CUSTOMER_NAME VARCHAR(70),";
   // $hungry_orders.="HUNGRY_CUSTOMER_PHONE VARCHAR(20),";
   // $hungry_orders.="HUNGRY_CUSTOMER_HALL VARCHAR(20),";
   // $hungry_orders.="HUNGRY_ORDER_REQUEST TEXT,";
   // $hungry_orders.="FULL_ORDER_DATE TIMESTAMP DEFAULT CURRENT_TIMESTAMP,";
   // $hungry_orders.="HUNGRY_ORDER_YEAR VARCHAR(10),";
   // $hungry_orders.="HUNGRY_ORDER_MONTH VARCHAR(10),";
   // $hungry_orders.="HUNGRY_ORDER_NUMBER_DATE VARCHAR(3),";
   // $hungry_orders.="HUNGRY_ORDER_WEEK_DAY VARCHAR(10),";
   // $hungry_orders.="DELIVERY_STATUS VARCHAR(13),";
   // $hungry_orders.="PRIMARY KEY(HUNGRY_ORDER_ID)";
   // $hungry_orders.=");";

   // $conn->run_table_query($hungry_orders,"HUNGRY_ORDERS");
?>