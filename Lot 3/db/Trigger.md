 1 = a emporter = 5.5%
 0 = sur place = 10%

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

3.3. after_ligne_insert

A chaque ajout d'une ligne de commande, détermine la somme des "totaux ligne HT" de la
commande liée, applique le taux de TVA et calcule le "total commande" TTC.

DELIMITER $$

CREATE OR REPLACE TRIGGER after_ligne_insert
AFTER INSERT ON ligne_de_commande
FOR EACH ROW
BEGIN
    DECLARE v_total_ht DOUBLE DEFAULT 0;
    DECLARE v_type_commande TINYINT DEFAULT 0;
    DECLARE v_tva_rate DOUBLE DEFAULT 0.10;

    SELECT COALESCE(SUM(total_ht), 0)
      INTO v_total_ht
      FROM ligne_de_commande
     WHERE idcommande = NEW.idcommande;

    SELECT type_commande
      INTO v_type_commande
      FROM commande
     WHERE idcommande = NEW.idcommande;

    IF v_type_commande = 1 THEN
        SET v_tva_rate = 0.055;
    ELSE
        SET v_tva_rate = 0.10;
    END IF;

    UPDATE commande
       SET montant_ttc = v_total_ht * (1 + v_tva_rate)
     WHERE idcommande = NEW.idcommande;
END $$

DELIMITER ;

3.4. after_ligne_update

A chaque modification d'une ligne de commande, détermine la somme des "totaux ligne HT" de la
commande liée, applique le taux de TVA et calcule le "total commande" TTC.

DELIMITER |
CREATE OR REPLACE TRIGGER after_ligne_update
AFTER UPDATE ON commande
FOR EACH ROW
BEGIN

IF new.typecommade = 1 THEN
     SET (select montant_ttc from commande where new.idcommande = idcommande) = v_total_prix_ht * (5.5/100)
     [ELSE SET (select montant_ttc from commande where new.idcommande = idcommande) = v_total_prix_ht * (10/100)]
END IF;

END |
DELIMITER ;