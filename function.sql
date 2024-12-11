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
