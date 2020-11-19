<nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Ma BouTiquE de OUF!!!</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample04">
            <ul class="navbar-nav mr-auto">

                <?php if(connect()): // accès membre connecté mais NON ADMIN ?>

                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>profil.php">Mon compte</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>boutique.php">Accès à la boutique</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>panier.php">Mon Panier</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>connexion.php?action=deconnexion">Deconnexion</a>
                    </li>

                <?php else: // accès visiteur lambda NON CONNECTE ?>

                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>inscription.php">Créer votre compte</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>connexion.php">Identifiez-vous</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>boutique.php">Accès à la boutique</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>panier.php">Mon Panier</a>
                    </li>

                <?php endif; ?>

                <?php if(adminConnect()): // SI l'utlisateur a pour valeur '1' pour le statut dans la session donc dans la BDD, alors il est administrateur du site et nous lui donnons accès aux liens du BACKOFFICE ?>    

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BACK OFFICE</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">

                            <a class="dropdown-item" href="<?= URL ?>admin/gestion_boutique.php">Gestion boutique</a>

                            <a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion commande</a>

                            <a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion membre</a>
                            
                        </div>
                    </li>

                <?php endif; ?>

            </ul>
            <form class="form-inline my-2 my-md-0">
                <input class="form-control" type="text" placeholder="Search">
            </form>
        </div>
    </nav>

    <main class="container-fluid" style="min-height: 90vh;">