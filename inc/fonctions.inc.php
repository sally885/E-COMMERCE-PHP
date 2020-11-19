<?php 

// FONCTION INTERNAUTE CONNECTE
// Cette fonction de savoir si un utilisateur est connecté ou non au site
function connect()
{
    // SI l'indice 'user' dans la session n'est pas définit, cela veut dire que l'internaute n'est pas passé par la page connexion (c'est dans cette page que l'on crée l'indice 'user' dans la session), cela veut dire que l'internaute n'est pas connecté et n'est peut-être pas inscrit sur le site 
    if(!isset($_SESSION['user']))
    {
        return false;
    }
    else // SINON, l'indice 'user' est bien définit dans la session, donc l'internaute est bien connecté
    {
        return true;
    }
}

// FONCTION INTERNAUTE ADMINISTRATEUR
function adminConnect()
{
    // SI l'internaute est connecté et que l'indice 'statut' dans la session a pour valeur '1', cela veut dire que l'internaute est administrateur du site
    if(connect() && $_SESSION['user']['statut'] == 1)
    {
        return true;
    }
    else // SINON, le statut de l'utlisateur dans la session n'a pas pour valeur '1', donc l'internaute n'est pas administrateur et peut-être pas connecté
    {
        return false;
    }
}

// FONCTION CREATION DU PANIER DANS LA SESSION
// Les données du panier ne sont jamais conservés en BDD, beaucoup de panier n'aboutissent jamais
// Donc nous allons stocker les informations du panier directement dans le fichier de session de l'internaute
// Dans la session, nous définissons différents tableau ARRAY qui permettront de stocker par exemple toute les références des produits ajoutés au panier dans un ARRAY
function creationPanier()
{
    if(!isset($_SESSION['panier']))
    {
        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['photo'] = array();
        $_SESSION['panier']['reference'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}

// FONCTION AJOUTER PRODUIT DANS LA SESSION
function ajoutPanier($id_produit, $photo, $reference, $titre, $quantite, $prix)
{
    creationPanier(); // On contrôle si le panier est crée dans la session ou non ($_SESSION['panier])

    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if($positionProduit !== false)
    {
        $_SESSION['panier']['quantite'][$positionProduit] += $quantite;
    }
    else
    {
        //Les crochets vides [] permettent de générer des indices numériques dans le tableau ARRAY
        // $_SESSION['panier']['id_produit'][0] = 29;
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['photo'][] = $photo;
        $_SESSION['panier']['reference'][] = $reference;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
    }

    
}

// FONCTION MONTANT TOTAL PANIER
function montantTotal()
{
    $total = 0;
    // La boucle FOR tourne autant de fois qu'il y a d'id_produit dans la session, donc autant qu'il y a de produits dans le panier
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        //                          3                          50
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
        // 15 + 17 + 150
    }
    return round($total, 2); // on arrondi le total à 2 chiffres après la vrigule
}

// FONTION SUPRESSION PRODUIT PANIER
function suppProduit($id_produit)
{
     // On transmet à la fonction prédéfinie array_search(), l'id_produit du produit en rupture de stock
    // array_search() retourne l'indice du tableau ARRAY auquel se trouve l'id_produit a supprimer
    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

     // Si la valeur de $positionProduit est différente de FALSE, cela veut dire que l'id_produit a supprimer a bien été trouvé dans le panier de la session
    if($positionProduit !== false)
    {
        // array_splice() permet de supprimer des éléments d'un tableau ARRAY
        // on supprime chaque ligne dans les tableaux ARRAY du produit en rupture de stock
        // array_splice() ré-organise les tbaleaux ARRAY, c'est à dire que tout les élément aux indices inférieur remonttent aux indices supérieurs, le produit stocké à l'indice 3 du teablau ARRAY remonte à l'indice 2 du tableau ARRAY
        array_splice($_SESSION['panier']['id_produit'], $positionProduit, 1);
        array_splice($_SESSION['panier']['photo'], $positionProduit, 1);
        array_splice($_SESSION['panier']['reference'], $positionProduit, 1);
        array_splice($_SESSION['panier']['titre'], $positionProduit, 1);
        array_splice($_SESSION['panier']['quantite'], $positionProduit, 1);
        array_splice($_SESSION['panier']['prix'], $positionProduit, 1);
    }
}

/*
    array
    (
        [user] => ARRAY(infos de l'utilisateur connecté)

        [panier] => array(
                
                [id_produit] =>array(
                            0 => 15
                            1 => 40 
                        )

                [reference] => array(
                            0 => 12A45
                            1 => 46F56
                        )

                [photo] => array(
                            0 => http://localhost/PHP/09-boutique/photo/img.jpg
                            1 => http://localhost/PHP/09-boutique/photo/img3.jpg
                        )
        )
    )
*/
