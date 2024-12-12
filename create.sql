DROP TABLE IF EXISTS FAQ CASCADE;
DROP TABLE IF EXISTS A_PROPOS CASCADE;
DROP TABLE IF EXISTS SHOWCASE CASCADE;
DROP TABLE IF EXISTS PRODUCT_CATEGORY CASCADE;
DROP TABLE IF EXISTS PRODUCT_IMAGE CASCADE;
DROP TABLE IF EXISTS CATEGORY CASCADE;
DROP TABLE IF EXISTS CART CASCADE;
DROP TABLE IF EXISTS ORDER_PRODUCT CASCADE;
DROP TABLE IF EXISTS PRODUCT CASCADE;
DROP TABLE IF EXISTS ORDERS CASCADE;
DROP TABLE IF EXISTS ARTICLE CASCADE;
DROP TABLE IF EXISTS CAROUSEL CASCADE;
DROP TABLE IF EXISTS IMAGE CASCADE;
DROP TABLE IF EXISTS USERS CASCADE;

-- Create
CREATE TABLE USERS (
    id_user SERIAL PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(10),
    newsletter BOOLEAN NOT NULL DEFAULT FALSE,
    reset_token VARCHAR(255),
	reset_token_exp TIMESTAMP,
    activ_token VARCHAR(255),
    activ_exp TIMESTAMP,
    is_verified     BOOLEAN DEFAULT FALSE,
    remember_token  VARCHAR(255),
    create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE IMAGE (
    id_img SERIAL PRIMARY KEY,
    img_name VARCHAR(255) NOT NULL,
    img_path VARCHAR(255) NOT NULL
);

CREATE TABLE CAROUSEL (
    id_car SERIAL PRIMARY KEY,
    id_img INT NOT NULL,
    link_car VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_img) REFERENCES IMAGE(id_img) ON DELETE CASCADE
);

CREATE TABLE ARTICLE(
    id_art SERIAL PRIMARY KEY,
    id_img INT NOT NULL,
    art_title VARCHAR(255) NOT NULL,
    art_text TEXT NOT NULL,
    art_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_img) REFERENCES IMAGE(id_img) ON DELETE CASCADE
);

CREATE TABLE ORDERS (
    id_order SERIAL PRIMARY KEY,
    id_user INT NOT NULL,
    order_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    phone_number_order VARCHAR(10) NOT NULL,
    address_street VARCHAR(255) NOT NULL,
    address_city VARCHAR(255) NOT NULL,
    address_zip VARCHAR(5) NOT NULL,
    address_country VARCHAR(255) NOT NULL,
    statut_commande VARCHAR(255) CHECK (statut_commande IN ('En attente', 'En cours', 'Envoyé')) NOT NULL DEFAULT 'En attente',
    FOREIGN KEY (id_user) REFERENCES USERS(id_user) ON DELETE CASCADE
);

CREATE TABLE PRODUCT (
    id_prod SERIAL PRIMARY KEY,
    p_name VARCHAR(255) NOT NULL,
    p_price FLOAT NOT NULL,
    description TEXT NOT NULL,
    on_sale BOOLEAN NOT NULL DEFAULT FALSE,
    is_star BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE CATEGORY (
  id_cat SERIAL PRIMARY KEY,
  cat_name VARCHAR(255) NOT NULL
);

CREATE TABLE PRODUCT_CATEGORY (
  id_prod INT NOT NULL,
  id_cat INT NOT NULL,
  PRIMARY KEY (id_prod, id_cat),
  FOREIGN KEY (id_prod) REFERENCES PRODUCT(id_prod) ON DELETE CASCADE,
  FOREIGN KEY (id_cat) REFERENCES CATEGORY(id_cat) ON DELETE CASCADE
);

CREATE TABLE PRODUCT_IMAGE(
    id_prod INT NOT NULL,
    id_img INT NOT NULL,
    PRIMARY KEY (id_prod, id_img),
    FOREIGN KEY (id_prod) REFERENCES PRODUCT(id_prod) ON DELETE CASCADE,
    FOREIGN KEY (id_img) REFERENCES IMAGE(id_img) ON DELETE CASCADE
);

CREATE TABLE ORDER_PRODUCT (
    id_order INT NOT NULL,
    id_prod INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    FOREIGN KEY (id_order) REFERENCES ORDERS(id_order) ON DELETE CASCADE,
    FOREIGN KEY (id_prod) REFERENCES PRODUCT(id_prod) ON DELETE CASCADE
);

CREATE TABLE CART (
    id_cart SERIAL PRIMARY KEY,
    id_user INT NOT NULL,
    id_prod INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES USERS(id_user) ON DELETE CASCADE, 
    FOREIGN KEY (id_prod) REFERENCES PRODUCT(id_prod) ON DELETE CASCADE
);

CREATE TABLE SHOWCASE (
    id_show SERIAL PRIMARY KEY,
    id_cat INT NOT NULL,
    FOREIGN KEY (id_cat) REFERENCES CATEGORY(id_cat) ON DELETE CASCADE
);

CREATE TABLE A_PROPOS (
    id_apropos SERIAL PRIMARY KEY,
    content TEXT NOT NULL
);

CREATE TABLE FAQ (
    id_faq SERIAL PRIMARY KEY,
    content TEXT NOT NULL
);

-- Faire un trigger qui supprime une category si elle n'est plus utilisée

CREATE OR REPLACE FUNCTION delete_category() RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM CATEGORY
    WHERE id_cat NOT IN (SELECT id_cat FROM PRODUCT_CATEGORY);
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER delete_category
AFTER DELETE ON PRODUCT_CATEGORY
FOR EACH ROW
EXECUTE FUNCTION delete_category();

-- Faire un trigger qui quand on passe le is_star d'un produit à true, passe tous les autres à false
CREATE OR REPLACE FUNCTION set_star() RETURNS TRIGGER AS $$
BEGIN
    -- Désactiver le statut "is_star" pour tous les autres produits
    UPDATE PRODUCT
    SET is_star = FALSE
    WHERE id_prod != NEW.id_prod AND is_star = TRUE;

    -- Retourner la nouvelle ligne
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Création du trigger
CREATE TRIGGER set_star
BEFORE UPDATE OF is_star ON PRODUCT
FOR EACH ROW
WHEN (NEW.is_star = TRUE) -- Exécuter seulement si on met "is_star" à TRUE
EXECUTE FUNCTION set_star();









-- Insert
INSERT INTO USERS (
    first_name, 
    last_name, 
    email, 
    password, 
    phone_number, 
    newsletter, 
    reset_token, 
    reset_token_exp, 
    activ_token, 
    activ_exp, 
    is_verified, 
    remember_token, 
    is_admin
) 
VALUES (
    'Admin',                          -- Prénom
    'Dupont',                         -- Nom de famille
    'clementin.ly@etu.univ-lehavre.fr',           -- Email
    '$2y$10$5S6V.W5rLI4.yiv9w9fnZuCjLCnbZpXWtyDh.FdQlGQ8cWhei2m9O',                     -- Mot de passe
    '0123456789',                     -- Numéro de téléphone (facultatif)
    FALSE,                            -- Inscription à la newsletter (facultatif)
    NULL,                             -- Token de réinitialisation (facultatif)
    NULL,                             -- Expiration du token de réinitialisation (facultatif)
    NULL,                             -- Token d'activation (facultatif)
    NULL,                             -- Expiration du token d'activation (facultatif)
    TRUE,                             -- Compte vérifié
    NULL,                             -- Token de se souvenir (facultatif)
    TRUE                              -- Administrateur (1 = admin)
);



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
    ('Guerlain Parfum', 149.99, 'Un parfum exceptionnel pour les amateurs de luxe.', TRUE, FALSE),
    ('Sumup Parfum 1', 79.99, 'Parfum léger et floral parfait pour toutes les occasions.', TRUE, FALSE),
    ('Sumup Parfum 2', 89.99, 'Un parfum frais avec des notes boisées.', TRUE, FALSE),
    ('Sumup Parfum 3', 99.99, 'Un parfum classique pour les amateurs de senteurs intemporelles.', TRUE, FALSE),
    ('Sumup Parfum 4', 119.99, 'Un parfum audacieux avec des notes orientales.', TRUE, FALSE),
    ('Sumup Parfum 5', 74.99, 'Un parfum délicat et apaisant pour un usage quotidien.', TRUE, FALSE),
    ('Sumup Parfum 6', 109.99, 'Un parfum sophistiqué avec des notes de cuir.', TRUE, FALSE),
    ('Sumup Parfum 7', 84.99, 'Un parfum rafraîchissant et énergisant.', TRUE, FALSE);

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

INSERT INTO A_PROPOS (content)
VALUES
    ('<h2>Notre Histoire</h2><p>amque non umbratis fallaciis res agebatur, sed qua palatium est extra muros, armatis omne circumdedit. ingressusque obscuro iam die, ablatis regiis indumentis Caesarem tunica texit et paludamento communi, eum post haec nihil passurum velut mandato principis iurandi crebritate confirmans et statim inquit exsurge et inopinum carpento privato inpositum ad Histriam duxit prope oppidum Polam, ubi quondam peremptum Constantini filium accepimus Crispum.</p><p><br></p><h2>Nos valeurs</h2><ul><li>amque non umbratis fallaciis</li><li>sed qua palatium</li><li>ngressusque obscuro iam</li></ul><p><br></p><h2>Contacts</h2><p>amque non umbratis fallaciis res agebatur, sed qua palatium est extra muros, armatis omne circumdedit. ingressusque obscuro iam die, ablatis regiis indumentis Caesarem tunica texit et paludamento communi, eum post haec nihil passurum velut mandato principis iurandi crebritate confirmans et statim inquit exsurge et inopinum carpento privato inpositum ad Histriam duxit prope oppidum Polam, <a href="https://www.facebook.com/maisoneaudor76" rel="noopener noreferrer" target="_blank">ubi quondam peremptum Constantini filium accepimus Crispum.</a></p>');


INSERT INTO FAQ (content)
VALUES
    ('<h3>Quels types de produits propose Maison Eau d`Or ?</h3><p>Maison Eau d`Or propose une large gamme de parfums ainsi que des produits de soin et de beauté sélectionnés avec soin pour répondre à toutes vos attentes.</p><p><br></p><h3>Où se situe la boutique ?</h3><p>La boutique est située au Havre, en Normandie au 123 Avenue René Coty.</p><p><br></p><h3>Quels sont les horaires d’ouverture ?</h3><p>Les horaires typiques sont du lundi au samedi, de 10h à 19h, mais cela peut varier selon la période.</p><p><br></p><h3>Faites-vous de la livraison à domicile ?</h3><p>Oui, nous proposons un service de livraison à domicile. Vous pouvez passer votre commande en ligne ou en magasin.</p>');