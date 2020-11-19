<?php 
require_once('inc/init.inc.php');

if(isset($_POST['ajout_panier']))
{
    //echo '<pre>'; print_r($_POST); echo '</pre>';

    // On selectionne en BDD toute les données du produit qui vient d'être ajouté au panier
    // Cela nous permet d'avoir accès aux données du produit stockés dans le panier (référence, prix, photo etc..)
    $r = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $r->bindValue(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
    $r->execute();

    $p = $r->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>'; print_r($p); echo '</pre>';

    // On ajoute dans la session un produit à la validation du formulaire dans le fichier fiche_produit.php
    ajoutPanier($p['id_produit'], $p['photo'], $p['reference'], $p['titre'], $_POST['quantite'], $p['prix']);
    
}

if(isset($_GET['action']) && $_GET['action'] == 'supression')
{
    $positionProduit = array_search($_GET['id_produit'], $_SESSION['panier']['id_produit']);

    $vd = "<div class='bg-success col-md-5 mx-auto text-center text-white rounded p-2 mb-2'>Le produit titre <strong>" . $_SESSION['panier']['titre'][$positionProduit] . "</strong> référence <strong>" . $_SESSION['panier']['reference'][$positionProduit] . "</strong> a bien été retiré du panier;</div>";
    
    suppProduit($_GET['id_produit']); 
}

// CONTROLE STOCK PRODUIT
// Si l'indice 'payer' est bien définit, cela veut que l'internaute a cliqué sur le bouton 'VALIDER LE PAIEMENT' et donc par conséquent que l'attribut name 'payer' a été détecté
if(isset($_POST['payer']))
{
    // La boucle FOR tourne autant de fois qu'il y a d'id_produit dans la session, donc autant qu'il y a de produits dans le panier
    $error = '';
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        $r = $bdd->query("SELECT stock FROM produit WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]); // 29
        $s = $r->fetch(PDO::FETCH_ASSOC);
        //echo '<pre>'; print_r($s); echo '</pre>';
        
        // SI la quantite du stock du produit en BDD est inférireur à la quantité dans la session, c'est à dire la quantité commandée par l'internaute, alors on entre dans la condition IF
        if($s['stock'] < $_SESSION['panier']['quantite'][$i])
        {
            $error .= "<div class='bg-danger col-md-3 mx-auto text-center text-white rounded p-2 mb-2'>Stock restant du produit : <strong>$s[stock]</strong></div>";

            $error .= "<div class='bg-success col-md-3 mx-auto text-center text-white rounded p-2 mb-2'>Quantitée demandée du produit : <strong>" . $_SESSION['panier']['quantite'][$i] . "</strong></div>";

            // SI le stock en BDD est superieur à 0 mais inferieur à la quantité demandée par l'internaute, alors on entre dans la condition IF
            if($s['stock'] > 0)
            {
                $error .= "<div class='bg-danger col-md-3 mx-auto text-center text-white rounded p-2 mb-2'>La quantitée du produit : <strong>" . $_SESSION['panier']['titre'][$i] . "</strong> référence <strong>" . $_SESSION['panier']['reference'][$i] . "</strong>à été modifiée car notre stock est insufisant, vériefiez vos achats</div>";

                $_SESSION['panier']['quantite'][$i] = $a['stock'];
            }
            else
            {
                $error .= "<div class='bg-danger col-md-3 mx-auto text-center text-white rounded p-2 mb-2'>La quantitée du produit : <strong>" . $_SESSION['panier']['titre'][$i] . "</strong> référence <strong>" . $_SESSION['panier']['reference'][$i] . "</strong>à été supprimé car notre stock est rupture de stock, vérifier vos acahts.</div>";

                suppProduit($_SESSION['panier']['id_produit'][$i]);
                $i--;
            }

            $e = true;

            
        }
    }

    if(!isset($e))
    {
        //ENREGISTREMENT DE LA COMMANDE
        $r = $bdd->exec("INSERT INTO commande (id_membre, montant, date_enregistrement) VALUES (" . $_SESSION['user']['id_membre'] . ", " . montantTotal() .", NOW())");

        $idCommande = $bdd->lastInsertId();

        for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
        {
            $r = $bdd->exec("INSERT INTO details_commande (id_commande, id_produit, quantite, prix) VALUES ($idCommande, " . $_SESSION['panier']['id_produit'][$i] . ", " . $_SESSION['panier']['quantite'][$i] . ", " . $_SESSION['panier']['prix'][$i] . ")");

            //DEPRECIATION DES STOCK
            $r = $bdd->exec("UPDATE produit SET stock = stock - " . $_SESSION['panier']['quantite'][$i] . " WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);
        }
        unset($_SESSION['panier']); //On supprime les élément du panier dans la session après la validation du panier et l'insertion dans les tables 'commande' et 'détails_commande'

        $_SESSION['num_cmd'] = $idCommande;
        header('location: validation_cmd.php'); // Après la validation du panier on redirige l'internaute
    }
}


//echo '<pre>'; print_r($_SESSION); echo '</pre>';

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>

<h1 class="display-4 text-center my-4">Mon Panier</h1>

<?php 
if(isset($error)) echo $error;
if(isset($vd)) echo $vd;
?>

<table class="col-md-8 mx-auto table table-bordered text-center">
    
    <tr>
        <th>PHOTO</th>
        <th>REFERENCE</th>
        <th>TITRE</th>
        <th>QUANTITE</th>
        <th>PRIX unitaire</th>
        <th>PRIX total/produit</th>
        <th>SUPP</th>
    </tr>

    <?php if(empty($_SESSION['panier']['id_produit'])): // Si l'indice 'id_produit' dans le panier de la session est vide ou non définit, on entre dans la condition IF ?>

            <tr><td colspan="7" class="text-danger">Aucun produit dans le panier</td></tr>

        </table>

    <?php else: // Sinon des id_produit sont bien définit dans le panier de la session, on entre dans la condition ELSE et on affiche le contenu du panier ?>

        <!-- La boucle FOR tourne autant de fois qu'il y a d'id_produit dans la session, donc autant qu'il y a de produits dans le panier -->
        <?php for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++): ?>

            <tr>
                <!-- Pour chaque tour de boucle FOR, nous allons crochter aux indices numériques des différents ARRAY dans la session afin d'afficher la photo, le titre, la référence etc.. des produits ajoutés dans le panier -->
                <td><a href="fiche_produit.php?id_produit=<?= $_SESSION['panier']['id_produit'][$i]; ?>"><img src="<?= $_SESSION['panier']['photo'][$i]; ?>" alt="<?= $_SESSION['panier']['titre'][$i]; ?>" style="width: 100px;"></a></td>

                <td><?= $_SESSION['panier']['reference'][$i]; ?></td>

                <td><?= $_SESSION['panier']['titre'][$i]; ?></td>

                <td><?= $_SESSION['panier']['quantite'][$i]; ?></td>

                <td><?= $_SESSION['panier']['prix'][$i]; ?>€</td>

                <td><?= $_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i]; ?>€</td>

                <td><a href="?action=supression&id_produit=<?= $_SESSION['panier']['id_produit'][$i] ?>" class='btn btn-danger'><i class='far fa-trash-alt'></i></a></td>
            </tr>
            
        <?php endfor; ?>

            <tr>
                <th>MONTANT TOTAL</th>
                <td colspan="4"></td>
                <th><?= montantTotal(); ?>€</th>
                <td></td>
            </tr>    

</table>

    <?php if(connect()): // Si l'internaute est connécté, il peut valider la paiement ?>

        <form action="" method="post" class="col-md-8 mx-auto pl-0">
            <input type="submit" name="payer" value="VALIDER LE PAIEMENT" class="btn btn-success">
        </form>

    <?php else: // Sinon l'internaute n'est pas connecté, on le renvoi vers la page connexion ?>

        <a href="<?= URL ?>connexion.php" class="offset-md-2 btn btn-success mb-3">IDENTIFIEZ-VOUS POUR VALIDER LA COMMANDE</a>
        
    <?php endif; ?>

<?php endif; ?>

<?php 
require_once('inc/footer.inc.php');