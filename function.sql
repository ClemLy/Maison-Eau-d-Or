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

