INSERT INTO IMAGE (img_name, img_path)
VALUES ('Guerlain', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/1200b8b3-272f-40e3-bc98-c928547e2ed1.jpeg');

INSERT INTO PRODUCT (p_name, p_price, description, id_img, on_sale, is_star)
VALUES 
('Guerlain Parfum', 150.00, 'Un parfum exceptionnel pour les amateurs de luxe.', 1, TRUE, TRUE);

INSERT INTO CATEGORY (cat_name)
VALUES ('BestSellers'),
       ('Nos Coffrets');
