-BASE
    -create database sinistre
        -create table:
            -ville(id_ville,nom_ville)
            -categorie(id_categorie,nom_categorie)
            -porduit(id_produit,id_categorie,prix_unitaire,nom_produit)
            -besoin(id_besoin,id_ville,id_produit,quantite_besoin,date_saisie)
            -don(id_don,id_ville,id_produit,quantite_don)
            -dispatch(id_dispatch,id_don,id_besoin,quantite_attribuee,date_dispatch)

        -inserer donnee de test
    
-DASHBOARD
    -liste des villes
        -nom 
        -besoin
        -don

-Besoin
    -BesoinModel
        -createBesoin
    
    -View
        -formBesoin.php
