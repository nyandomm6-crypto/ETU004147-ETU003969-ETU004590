-BASE
    -create database sinistre
        -create table: (Miantra)
            -ville(id_ville,nom_ville)
            -categorie(id_categorie,nom_categorie)
            -produit(id_produit,id_categorie,prix_unitaire,nom_produit)
            -besoin(id_besoin,id_ville,id_produit,quantite_besoin,date_saisie)
            -don(id_don,id_ville,id_produit,quantite_don)
            -dispatch(id_dispatch,id_don,id_besoin,quantite_attribuee,date_dispatch)

        -inserer donnee de test (Miantra)
    
-DASHBOARD(Ando)
    -view : liste des villes
        -nom 
        -besoin
        -don

-Besoin(Tsanta)
    -BesoinModel
        -createBesoin

    -BesoinController
        -createBesoin
        -showFormBesoin : afficher le formulaire
    
    -View
        -formBesoin.php

-Produit(Miantra)
    -ProduitModel
        -getAllProduit
        -getProduitById
    

-Ville (Ando)
    -VilleModel
        -getAllVille
        -getVilleById

-Don(Miantra)
    -DonModel
        -createDon
    
    -DonController
        -createDon
        -showFormDon : afficher formulaire de don

    -view
        -formDon.php 

-Dispatch
    -DispatchModel

    -DispatchController

    -View
        




