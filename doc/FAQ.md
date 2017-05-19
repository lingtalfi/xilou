FAQ
==========
2017-05-19




Nomenclature
=================

L'interface principale est composée de trois parties:

- menu top (en haut)
- menu left (à gauche)
- main (à droite du menu left)







Se connecter
================


- Démarrer mamp ( cmd + space, puis taper mamp)
- Ouvrir l'adresse suivante dans le navigateur: http://zilu/ 
- L'écran de démarrage doit apparaître (voir screenshot {login})
- S'identifier avec:
    - Pseudo: root
    - Pass: root
    
- L'écran d'accueil doit apparaître (voir screenshot {accueil})
    
    
    
Voir la liste des commandes passées
==============================

- Cliquer sur l'onglet "Commande" (menutop)
- Dans la partie main, cliquer sur le selector "Choisissez une commande", puis choisir la commande à afficher.



Importer une nouvelle commande
==============================

- Cliquer sur l'onglet "Commande" (menutop)
- Cliquer sur le bouton "Importer un fichier excel +" (partie haute de main)
- Un popup apparaît et vous demande le nom de la commande et le chemin vers fichier excel  (voir screenshot {commandeNewImportDialog})
- Remplir les informations puis cliquer sur le bouton importer
    - le format du fichier excel doit être le même que le fichier situé ici: /doc/assets/C-2017-03-01.xlsx
- Si l'opération réussit, la commande s'importe et le système affiche la liste des commandes nouvellement importées (voir screenshot {commandeList}) 



Supprimer un produit d'une commande existante
============================================

- Cliquer sur l'onglet "Commande" (menutop)
- Dans la partie main, cliquer sur le selector "Choisissez une commande", puis choisir la commande à afficher. 
- Dans la liste qui s'affiche, checker les checkboxes que vous souhaitez supprimer
- Tout en bas de la liste, cliquer sur le selector "For all selected rows", un dialogue apparaît (voir screenshot {commandeDeleteRowsDialog})
- Dans le dialogue sélectionner l'option: "Supprimer les entrées"
- La page se rafraîchit instantanément et les entrées sélectionnées disparaissent



Ajouter un produit existant à une commande existante
============================================

- Dans le menu left, cliquer sur "Commande has article"
- La liste des liaisons commandes-articles apparaît
- Cliquer sur le bouton "+ Create a new item" situé en haut de cette liste
- Le formulaire d'ajout de liaison commandes-articles apparaît
- Remplissez les informations, en choisissant votre commande, et en vous assurant que l'article appartienne bien au fournisseur indiqué,
        c'est à dire que la liaison correspondante existe dans la table "Fournisseur has article",
        sinon cela ne fonctionnera pas.
        
        
        


D'autres opérations
=====================

Pour d'autres opérations, envoyer un email à lingtalfi@gmail.com. 




















