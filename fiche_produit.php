<?php
require_once('inc/init.inc.php');
/*
    1. Réaliser le traitement SQL + PHP permettant de selectionnés les données du produit par rapport à l'id_produit transmis dans l'URL
    2. Faites en sorte que si l'id_produit dans l'URL n'est pas définit ou sa valeur est vide, de re-diriger vers la page boutique
    3. Si la requête de selection ne retourne aucun produit de la BDD, faites en sorte de re-diriger vers la page boutique
    4. Afficher les détails de produit dans l'affichage HTML, dans les div ci-dessous
*/

// SI l'indice 'id_produit' est bien définit dans l'URL et que sa valeur n'est pas vide, cela veut dire que l'internaute a cliqué sur un lien 'en savoir plus' et par conséquent transmit les paramètre ex: 'id_produit=29'
if(isset($_GET['id_produit']) && !empty($_GET['id_produit']))
{
    $r = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $r->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $r->execute();

    if($r->rowCount())
    {
        $p = $r->fetch(PDO::FETCH_ASSOC);
        //echo '<pre>'; print_r($p); echo '</pre>';
    }
    else
    {
        header('location: boutique.php');
    }
}
else
{
    header('location: boutique.php');
}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>

<!-- Page Content -->
<div class="container">

<div class="row">

    <!-- EXO : afficher la liste des categories stockees en BDD, chaque lien de catégorie renvoir vers la page boutique à la bonne catégorie -->

  <div class="col-lg-3">

  <?php
        $d = $bdd->query("SELECT DISTINCT categorie FROM produit");
    ?>


    <h1 class="my-4 text-center">Venez voir</h1>
            <div class="list-group text-center">
                <li class="list-group-item bg-dark text-white">CATEGORIES</li>
                <?php while($c = $d->fetch(PDO::FETCH_ASSOC)):
                {
                //echo '<pre>'; print_r($c); echo '</pre>';
                }
                ?>
            <a href="boutique.php?categorie=<?= $c['categorie'] ?>" class="list-group-item text-dark"><?= $c['categorie'] ?></a>

                <?php endwhile; ?>

            </div>

  </div>
  <!-- /.col-lg-3 -->

  <div class="col-lg-9">

    <div class="card mt-4">
      <img class="card-img-top img-fluid" src="<?= $p['photo'] ?>" alt="<?= $p['photo']?>">
      <div class="card-body">

        <h3 class="card-title"><?= $p['titre'] ?></h3>

        <h4><?= $p['prix'] ?>€</h4>

        <p class="card-text"><?= $p['description'] ?></p>

        <p class="card-text">Catégorie: <a href="boutique.php?categorie=<?= $p['categorie'] ?>"><?= $p['categorie'] ?></a></p>

        <p class="card-text">Référence: <?= $p['reference'] ?></p>

        <p class="card-text">Couleur: <?= $p['couleur'] ?></p>

        <p class="card-text">Taille<?= $p['taille'] ?></p>

        <p class="card-text">Public: <?= $p['public'] ?></p>

        <?php if($p['stock'] <= 10 && $p['stock'] !=0): // SI le stock du produit est inferieur ou égal à 10?>

            <p class="card-text font-italic text-danger">Attention ! Il ne reste que <?= $p['stock'] ?> exemplaire(s) en stock.</p>

        <?php elseif($p['stock'] > 10): ?>

            <p class="card-text font-italic text-success">En stock !</p>

        <?php endif ?>

        <hr>

        <?php if($p['stock'] > 0): ?>

            <form method="post" action="panier.php" class="form-inline">
                <input type="hidden" id="id_produit" name="id_produit" value="<?= $p['id_produit'] ?>">
                <div class="form-group">
                    <select class="form-control" id="quantite" name="quantite">

                    <?php for($i = 1; $i <= $p['stock'] && $i <= 30; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>

                    <?php endfor; ?>
                    </select>
                </div>
                <input type="submit" class="btn btn-dark ml-2" name="ajout_panier" value="AJOUTER AU PANIER">

            </form>

        <?php else: ?>

            <p class="card-text font-sire-italic text-danger">Rupture de stock</p>

        <?php endif; ?>
      </div>
    </div>
    <!-- /.card -->

    <div class="card card-outline-secondary my-4">
      <div class="card-header">
        Dernier avis 
      </div>
      <div class="card-body">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <a href="#" class="btn btn-success">Leave a Review</a>
      </div>
    </div>
    <!-- /.card -->

  </div>
  <!-- /.col-lg-9 -->

</div>

</div>
<!-- /.container -->


<?php
require_once('inc/footer.inc.php');
