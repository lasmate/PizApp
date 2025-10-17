3.1. before_ligne_insert

A chaque ajout d'une ligne de commande, calcule le "total ligne HT" en multipliant la quantité par le
prix HT du produit. Le prix HT du produit provient de la table "produit".

DELIMITER |
CREATE OR REPLACE TRIGGER before_ligne_insert
BEFORE INSERT ON ligne_de_commande
FOR EACH ROW
BEGIN

DECLARE v_prix_ht double;

Select prixproduit INTO v_prix_ht from produit 
where new.idproduit = idproduit;

SET new.total_ht = v_prix_ht * new.quantite ;

END |
DELIMITER ;

3.2. before_ligne_update

A chaque modification d'une ligne de commande, met à jour le "total ligne HT" en multipliant la
quantité par le prix HT du produit. Le prix HT du produit provient de la table "produit".

DELIMITER |
CREATE OR REPLACE TRIGGER before_ligne_update
BEFORE UPDATE ON ligne_de_commande
FOR EACH ROW
BEGIN

DECLARE v_prix_ht double;

Select prixproduit INTO v_prix_ht from produit 
where new.idproduit = idproduit;

SET new.total_ht = v_prix_ht * new.quantite ;

END |
DELIMITER ;