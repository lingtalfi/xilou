Notes conception db
======================
2017-02-14



commande_has_articles
==========================

représente l'état d'un article en cours de commande.



prix_override
-----------------

zilu peut overrider le prix d'un fournisseur.
Par exemple, lorsqu'un produit est défectueux, zilu négocie
une réduction qui peut se traduire sous plusieurs formes:
    - remise d'un montant sur la prochaine commande
    - renvoi d'un produit 
    
C'est souvent le fournisseur qui décide la forme de la réduction.
Lorsqu'un renvoi de produit est négocié, il n'est pas possible de
mettre le prix à zéro, à cause des contrôles douaniers:
si un douanier soupçonne qu'il y a une réduction abusive (par exemple
une voiture à 1 euro), il peut mettre en place un contrôle douanier
aux frais de l'entreprise.

C'est pourquoi dans le cadre d'un renvoi de produit, une LÉGÈRE réduction
est appliquée sur un voire plusieurs produits, de manière à ce que le produit
passe la douane.

C'est ce qui explique que zilu ait besoin de modifier le prix d'un fournisseur:
par exemple un produit originellement facturé 15€ peut être facturé 13.50€
suite à une négociation réussie de type renvoi de produit.

    
    
    
    
    
article
========

représente un article physique, qui peut être livré par plusieurs fournisseurs (voir la
table fournisseur_has_article).


reference_lf
---------------
Pour l'instant, je n'utilise pas ce champ, je préfère faire des requêtes croisées
et n'optimiser qu'en cas de constation de performances trop faibles.




sav
============

Tous les champs sont liés à un produit à un instant t, 
donc on fige les données plutôt que de les collecter dynamiquement
(comme des stats ou un historique de commandes).