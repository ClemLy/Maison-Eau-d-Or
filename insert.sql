-- Insertion des images pour le carrousel des produits
-- INSERT INTO IMAGE (img_name, img_path) 
-- VALUES 
--     ('Guerlain Ideal', 'https://www.beautysuccess.fr/media/catalog/product/cache/02280392440d22bedc5c4ce4592badc4/4/3/4371782058-guerlain-l-homme-ideal-100ml-visuel_4.webp'),
--     ('Sumup Product 1', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/3f1302d3-be16-4ce7-b177-b4421213d687.jpeg'),
--     ('Sumup Product 2', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/7f0da4d2-b42d-4b0b-ac2f-a2bd78422b0b.jpeg'),
--     ('Sumup Product 3', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/e5972641-36bf-4b0a-aab4-62b7e5d5d17e.jpeg'),
--     ('Sumup Product 4', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/1200b8b3-272f-40e3-bc98-c928547e2ed1.jpeg'),
--     ('Sumup Product 5', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/9a09a44c-aa30-41e2-a9f7-ad6ad41a66a3.jpeg'),
--     ('Sumup Product 6', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/4f538ea5-703c-425d-88bc-3b01a935f18a.jpeg'),
--     ('Sumup Image 7', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/77f77e8d-ceb1-4544-bcdd-b216e52c0d1a.jpeg');


-- INSERT INTO CATEGORY (cat_name)
-- VALUES ('BestSellers'),
--        ('Nos Coffrets');

-- INSERT INTO PRODUCT (p_name, p_price, description, id_img, on_sale, is_star)
-- VALUES 
-- ('Guerlain Parfum', 150.00, 'Un parfum exceptionnel pour les amateurs de luxe.', 1, TRUE, TRUE),
-- ('Sumup Parfum 1', 80.00, 'Parfum léger et floral parfait pour toutes les occasions.', 2, FALSE, FALSE),
-- ('Sumup Parfum 2', 90.00, 'Un parfum frais avec des notes boisées.', 3, TRUE, FALSE),
-- ('Sumup Parfum 3', 100.00, 'Un parfum classique pour les amateurs de senteurs intemporelles.', 4, TRUE, FALSE),
-- ('Sumup Parfum 4', 120.00, 'Un parfum audacieux avec des notes orientales.', 5, FALSE, TRUE),
-- ('Sumup Parfum 5', 75.00, 'Un parfum délicat et apaisant pour un usage quotidien.', 6, FALSE, FALSE),
-- ('Sumup Parfum 6', 110.00, 'Un parfum sophistiqué avec des notes de cuir.', 7, TRUE, FALSE),
-- ('Sumup Parfum 7', 85.00, 'Un parfum rafraîchissant et énergisant.', 8, FALSE, FALSE);

-- INSERT INTO PRODUCT_CATEGORY (id_prod, id_cat)
-- VALUES 
-- (1, 1), 
-- (2, 1), 
-- (3, 1),
-- (4, 2),
-- (5, 2), 
-- (6, 1), 
-- (7, 2);


INSERT INTO IMAGE (img_name, img_path) 
VALUES 
    ('Guerlain Ideal', 'https://www.beautysuccess.fr/media/catalog/product/cache/02280392440d22bedc5c4ce4592badc4/4/3/4371782058-guerlain-l-homme-ideal-100ml-visuel_4.webp'),
    ('Sumup Product 1', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/3f1302d3-be16-4ce7-b177-b4421213d687.jpeg'),
    ('Sumup Product 2', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/7f0da4d2-b42d-4b0b-ac2f-a2bd78422b0b.jpeg'),
    ('Sumup Product 3', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/e5972641-36bf-4b0a-aab4-62b7e5d5d17e.jpeg'),
    ('Sumup Product 4', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/1200b8b3-272f-40e3-bc98-c928547e2ed1.jpeg'),
    ('Sumup Product 5', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/9a09a44c-aa30-41e2-a9f7-ad6ad41a66a3.jpeg'),
    ('Sumup Product 6', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/4f538ea5-703c-425d-88bc-3b01a935f18a.jpeg'),
    ('Sumup Image 7', 'https://cdn.sumup.store/2/th640/5d5cee579871afa9fc5ea0b02c0bf18e/77f77e8d-ceb1-4544-bcdd-b216e52c0d1a.jpeg');

INSERT INTO CATEGORY (cat_name)
VALUES 
    ('BestSellers'),
    ('Nos Coffrets');

INSERT INTO PRODUCT (p_name, p_price, description, on_sale, is_star)
VALUES 
    ('Guerlain Parfum', 150.00, 'Un parfum exceptionnel pour les amateurs de luxe.', TRUE, TRUE),
    ('Sumup Parfum 1', 80.00, 'Parfum léger et floral parfait pour toutes les occasions.', FALSE, FALSE),
    ('Sumup Parfum 2', 90.00, 'Un parfum frais avec des notes boisées.', TRUE, FALSE),
    ('Sumup Parfum 3', 100.00, 'Un parfum classique pour les amateurs de senteurs intemporelles.', TRUE, FALSE),
    ('Sumup Parfum 4', 120.00, 'Un parfum audacieux avec des notes orientales.', FALSE, TRUE),
    ('Sumup Parfum 5', 75.00, 'Un parfum délicat et apaisant pour un usage quotidien.', FALSE, FALSE),
    ('Sumup Parfum 6', 110.00, 'Un parfum sophistiqué avec des notes de cuir.', TRUE, FALSE),
    ('Sumup Parfum 7', 85.00, 'Un parfum rafraîchissant et énergisant.', FALSE, FALSE);

INSERT INTO PRODUCT_IMAGE (id_prod, id_img)
VALUES 
    (1, 1), 
    (2, 2), 
    (3, 3), 
    (4, 4), 
    (5, 5), 
    (6, 6), 
    (7, 7), 
    (8, 8);

INSERT INTO PRODUCT_CATEGORY (id_prod, id_cat)
VALUES 
    (1, 1), 
    (2, 1), 
    (3, 1), 
    (4, 2), 
    (5, 2), 
    (6, 1), 
    (7, 2),
    (8, 1);

