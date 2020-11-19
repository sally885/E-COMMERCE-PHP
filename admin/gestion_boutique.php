<?php 
require_once('../inc/init.inc.php');

// SI l'internaute N'EST PAS ADMIN du site, il n'a rien à faire sur la page gestion_boutique, on le redirige vers la page connexion
if(!adminConnect())
{
    header('location: ' . URL . 'connexion.php');
}

// SUPPRESSION PRODUIT
// ON entre dans la condition IF seulement dans le cas où l'internaute à cliqué sur un lien suppression produit et par conséquent a transmit dans l'URL les paramètres 'action=suppresion'
if(isset($_GET['action']) && $_GET['action'] == "suppression")
{
    // echo "suppression produit";

    // Exo : réaliser le traitement SQL + PHP permettant de supprimer un produit avec une requete préparée (prepare()) en fonction de l'id_produit transmit dans l'URL

    $d = $bdd->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $d->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $d->execute();

    // On redéfinit la valeur de l'indice 'action' dans l'URL afin d'être redirigé vers l'affichage des produits
    $_GET['action'] = 'affichage';

    // message de validation de suppression
    $vd = "<p class='col-md-3 mx-auto bg-success text-center text-white p-3 rounded'>Le produit <strong>ID $_GET[id_produit]</strong> a bien été supprimé !</p>";
}

// ENREGISTREMENT PRODUIT
if($_POST)
{
    // TRAITEMENT DE LA PHOTO UPLODE
    $photoBdd = '';
    
    if(isset($_GET['action']) && $_GET['action'] == 'modification')
    {
        $photoBdd = $_POST['photo_actuelle'];
    }

    if(!empty($_FILES['photo']['name']))
    {
        // on renomme la photo en concaténant la référence saisie dans le formulaire et le nom de la photo recupérée dans $_FILES
        $nomPhoto = $_POST['reference'] . '-' . $_FILES['photo']['name'];
        // echo $nomPhoto;

        // On définit l'URL de la photo qui sera enregistré en BDD
        $photoBdd = URL . "photo/$nomPhoto";
        // echo $photoBdd; // http://localhost/PHP/09-boutique/photo/15A45-tee-shit-4.jpg

        // ON définit le chemin physique de la photo vers le dossier photo sur le serveur, ce qui nous permet de copier l'image dans le bon dossier
        $photoDossier = RACINE_SITE . "photo/$nomPhoto";
        // echo $photoDossier; // c:/xampp/htdocs/PHP/09-boutique/photo/15A45-tee-shit-4.jpg

        // copy() : fonction prédéfinie permettant de copier un fichier 
        // arguments :
        // 1. le nom temporaire de l'image accéssible dans $_FILES
        // 2. le chemin physique de la photo jusqu'au dossier photo sur le serveur
        copy($_FILES['photo']['tmp_name'], $photoDossier);
    }

    // SI l'indice 'action' est bien définit dans l'URL et qu'il a pour valeur 'ajout', alors on execute une requete d'insertion à la validation du formulaire
    if(isset($_GET['action']) && $_GET['action'] == 'ajout')
    {
        // INSERTION BDD PRODUIT
        $data = $bdd->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");

        $_GET['action'] = 'affichage'; // on redirige vers l'affichage des produits après l'insertion

        $v = "<p class='col-md-3 mx-auto bg-success text-center text-white p-3 rounded'>Le produit titre : <strong>$_POST[titre]</strong> référence <strong>$_POST[reference]</strong> a bien été enregistré !</p>";
    }
    else // SINON, dans l'URL il y a 'action=modification', alors on execute une requete de modification update
    {
        // UPDATE BDD PRODUIT
        $data = $bdd->prepare("UPDATE produit SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo = :photo, prix = :prix, stock = :stock WHERE id_produit = :id_produit");

        $data->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);

        $_GET['action'] = 'affichage'; // on redirige vers l'affichage des produits après la modification

        $v = "<p class='col-md-3 mx-auto bg-success text-center text-white p-3 rounded'>Le produit titre : <strong>$_POST[titre]</strong> référence <strong>$_POST[reference]</strong> a bien été modifié !</p>";
    }

    $data->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
    $data->bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
    $data->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
    $data->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
    $data->bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
    $data->bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
    $data->bindValue(':public', $_POST['public'], PDO::PARAM_STR);
    $data->bindValue(':photo', $photoBdd, PDO::PARAM_STR);
    $data->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
    $data->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);

    $data->execute();
}

// echo '<pre>'; print_r($_POST); echo '</pre>';
// echo '<pre>'; print_r($_FILES); echo '</pre>';

require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');
?>

<!-- LIENS PRODUITS -->
<ul class="col-md-3 mx-auto list-group text-center mt-3">
    <li class="list-group-item bg-dark text-white">BACK OFFICE</li>
    <li class="list-group-item"><a href="?action=affichage" class="col-md-12 btn btn-primary p-2">AFFICHAGE PRODUITS</a></li>
    <li class="list-group-item"><a href="?action=ajout" class="col-md-12 btn btn-primary p-2">AJOUT PRODUIT</a></li>
</ul>

<?php
// SI l'indice 'action' est bien définit dans l'URL et qu'il a pour valeur 'affichage', cela veut dire que l'internaute a cliqué sur le lien 'AFFICHAGE PRODUITS' et par conséquent que les paramètres 'action=affichage' ont été transmit dans l'URL
if(isset($_GET['action']) && $_GET['action'] == 'affichage') 
{
    // AFFICHAGE DES PRODUITS
    echo '<h1 class="display-4 text-center my-4">Affichage des produits</h1>';

    // Affichage des messages utilisateurs
    if(isset($vd)) echo $vd;
    if(isset($v)) echo $v;

    $r = $bdd->query("SELECT * FROM produit");

    echo '<table class="table table-bordered text-center"><tr>';
    //          $i < 11 (11 colonne selectionnés via SELECT)
    for ($i = 0; $i < $r->columnCount(); $i++) {
        $c = $r->getColumnMeta($i);
        // echo '<pre>'; print_r($c); echo '</pre>';
        echo "<th>" . strtoupper($c['name']) . "</th>";
    }
    echo "<th>EDIT</th>";
    echo "<th>SUPP</th>";
    echo '</tr>';
    while ($p = $r->fetch(PDO::FETCH_ASSOC)) {
        // echo '<pre>'; print_r($p); echo '</pre>';
        echo '<tr>';
        foreach ($p as $k => $v) {
            if ($k == 'photo') {
                echo "<td><img src='$v' alt='' style='width: 150px;'></td>";
            } else {
                echo "<td class='align-middle'>$v</td>";
            }
        }
        echo "<td class='align-middle'><a href='?action=modification&id_produit=$p[id_produit]' class='btn btn-dark'><i class='far fa-edit'></i></a></td>";

        echo "<td class='align-middle'><a href='?action=suppression&id_produit=$p[id_produit]' class='btn btn-danger' onclick='return(confirm(\"En êtes vous certain ?\"));'><i class='far fa-trash-alt'></i></a></td>";

        echo '</tr>';
    }
    echo '</table>';
}
?>


<?php 
// SI l'indice 'action' est bien définit dans l'URL et qu'il a pour valeur 'ajout', cela veut dire que l'internaute a cliqué sur le lien 'AJOUT PRODUIT' et par conséquent que les paramètres 'action=ajout' ont été transmit dans l'URL
if(isset($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')): 

    // SI l'indice 'id_produit' est bien définit dans l'URL et que sa valeur est différente de vide; alors on entre dans la condition IF
    if(isset($_GET['id_produit']) && !empty($_GET['id_produit']))
    {
        // ON selectionne tout en BDD à condition que l'id_produit soit égal à l'id_produit dans l'URL
        // on selectionne toute les données en BDD du produit que l'on souhaite modifier
        $r = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit"); // 29
        $r->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
        $r->execute();

        // Si la requete SELECT retourne 1 résultat, le produit est connu en BDD; on entre dans la condition IF
        if($r->rowCount())
        {
            // Tableau ARRAY contenant toute les données du produit à modifier
            $pa = $r->fetch(PDO::FETCH_ASSOC);
            echo '<pre>'; print_r($pa); echo '</pre>'; 
        }
        else // Sinon l'id_produit de l'URL n'est pas connu en BDD, on redirige vers l'affichage des produits 
        {
            header('location:' . URL . 'admin/gestion_boutique.php?action=affichage');
        }
    }
    elseif($_GET['action'] == 'modification' && (!isset($_GET['id_produit']) || empty($_GET['id_produit']))) // Sinon l'indice 'id_produit' n'est pas définit dans l'URL ou sa valeur est vide, on entre dans la condition else, on redirige vers l'affichage des produits
    {
        header('location:' . URL . 'admin/gestion_boutique.php?action=affichage');
    }

    // on stock la reférence du produit selectionnés en BDD dans la variable $reference afin de l'affecter à l'attribut 'value' du champ reference comme valeur par défaut
    $reference = (isset($pa['reference'])) ? $pa['reference'] : '';
    $categorie = (isset($pa['categorie'])) ? $pa['categorie'] : '';
    $titre = (isset($pa['titre'])) ? $pa['titre'] : '';
    $description = (isset($pa['description'])) ? $pa['description'] : '';
    $couleur = (isset($pa['couleur'])) ? $pa['couleur'] : '';
    $taille = (isset($pa['taille'])) ? $pa['taille'] : '';
    $public = (isset($pa['public'])) ? $pa['public'] : '';
    $photo = (isset($pa['photo'])) ? $pa['photo'] : '';
    $prix = (isset($pa['prix'])) ? $pa['prix'] : '';
    $stock = (isset($pa['stock'])) ? $pa['stock'] : '';

 ?>
    <!-- 
        On va crocheter à l'indice 'action' dans l'URL afin de modifier le titre en fonction d'un 'ajout' ou d'une 'modification' de produit
        ucfirst() : fonction prédéfinie permettant d'afficher la première lettre d'une chaine de caractères en majuscule
    -->
    <h1 class="display-4 text-center my-4"><?= ucfirst($_GET['action']) ?> produit</h1>

    <!-- enctype="multipart/form-data" : si le formulaire contient un upload de fichier, il ne faut oublier l'attribut 'enctype' et la valeur 'multipart/form-data' qui permettent de stocker les informations du fichier uploadé directement dans la superglobale $_FILES (type, nom, extension, nom temporaire) -->
    <form method="post" class="col-md-6 mx-auto" enctype="multipart/form-data">
        <div class="form-group">
            <label for="reference">Référence</label>
            <input type="text" class="form-control" id="reference" name="reference" placeholder="ex : 14T25" value="<?= $reference ?>">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="categorie">Catégorie</label>
                <input type="text" class="form-control" id="categorie" name="categorie" value="<?= $categorie ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" value="<?= $titre ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="description">Déscription</label>
            <textarea type="text" class="form-control" id="description" name="description" rows="5"><?= $description ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="couleur">Couleur</label>
                <input type="text" class="form-control" id="couleur" name="couleur" value="<?= $couleur ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="taille">Taille</label>
                <select id="taille" name="taille" class="form-control">
                    <option value="s" <?php if($taille == 's') echo 'selected'; ?>>S</option>
                    <option value="m" <?php if($taille == 'm') echo 'selected'; ?>>M</option>
                    <option value="l" <?php if($taille == 'l') echo 'selected'; ?>>L</option>
                    <option value="xl" <?php if($taille == 'xl') echo 'selected'; ?>>XL</option>
                    <!-- SI la taille dans la BDD est 'xl' alors on affecte l'attribut 'selected' à la balise <option></option>, afin que l'option reste selectionné en cas de modification -->
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="public">Public</label>
                <select id="public" name="public" class="form-control">
                    <option value="homme" <?php if($public == 'homme') echo 'selected'; ?>>Homme</option>
                    <option value="femme" <?php if($public == 'femme') echo 'selected'; ?>>Femme</option>
                    <option value="mixte" <?php if($public == 'mixte') echo 'selected'; ?>>Mixte</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
             <!-- Un champ de type 'file' ne pas avoir d'attribue 'value', c'est pourquoi nous définissons un champ de type 'hidden' ci-dessous afin de récupérer l'URL de la photo en cas de modification -->

        </div>

         <!-- ON déclare un champ type hidden afin de récupérer l'URL de l'image pour la renvoyer dans la BDD si l'internaute en cas de modification ne souhaite pas modifier l'image -->

        <input type="hidden" id="photo_actuelle" name="photo_actuelle" value="<?= $photo ?>">

        <!-- Affichage de la photo actuelle du produit en cas de modification -->
        <?php if(!empty($photo)): ?>

            <div class="text-center">
                <em>Vous pouvez uploader une nouvelle image si vous souhaitez la changer</em><br>
                <img src="<?= $photo ?>" alt="<?= $titre ?>" style="width: 200px;">
            </div>

        <?php endif; ?>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="prix">Prix</label>
                <input type="text" class="form-control" id="prix" name="prix" value="<?= $prix ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="stock">Stock</label>
                <input type="text" class="form-control" id="stock" name="stock" value="<?= $stock ?>"> 
            </div>
        </div>
        <button type="submit" class="btn btn-dark mb-3"><?= strtoupper($_GET['action']) ?> PRODUIT</button>
    </form>

<?php 
endif;
require_once('../inc/footer.inc.php');