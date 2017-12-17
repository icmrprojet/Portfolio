<?php  
    define( 'MAIL_TO', /* >>>>> */'fayechartre6@gmail.com'/* <<<<< */ );  //ajouter votre courriel  
    define( 'MAIL_FROM', '' ); // valeur par défaut  
    define( 'MAIL_OBJECT', '' ); // valeur par défaut  
    define( 'MAIL_MESSAGE', 'Votre message' ); // valeur par défaut  

    $mailSent = false; // drapeau qui aiguille l'affichage du formulaire OU du récapitulatif  
    $errors = array(); // tableau des erreurs de saisie  
      
    if( filter_has_var( INPUT_POST, 'send' ) ) // le formulaire a été soumis avec le bouton [Envoyer]  
    {  
        $from = filter_input( INPUT_POST, 'from', FILTER_VALIDATE_EMAIL );  
        if( $from === NULL || $from === MAIL_FROM ) // si le courriel fourni est vide OU égale à la valeur par défaut  
        {  
            $errors[] = 'Vous devez renseigner votre adresse de courrier électronique.';  
        }  
        elseif( $from === false ) // si le courriel fourni n'est pas valide  
        {  
            $errors[] = 'L\'adresse de courrier électronique n\'est pas valide.';  
            $from = filter_input( INPUT_POST, 'from', FILTER_SANITIZE_EMAIL );  
        }  

        $object = filter_input( INPUT_POST, 'object', FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW );  
        if( $object === NULL OR $object === false OR empty( $object ) OR $object === MAIL_OBJECT ) // si l'objet fourni est vide, invalide ou égale à la valeur par défaut  
        {  
            $errors[] = 'Vous devez renseigner l\'objet.';  
        }  

 /* pas besoin de nettoyer le message.   
 / [http://www.phpsecure.info/v2/article/MailHeadersInject.php]  
 / Logiquement, les parties message, To: et Subject: pourraient servir aussi à injecter quelque chose,  mais la fonction mail()  
 / filtre bien les deux dernières, et la première est le message, et à partir du moment où on a sauté une ligne dans l'envoi du mail,  
 / c'est considéré comme du texte; le message ne saurait donc rester qu'un message.*/  
        $message = filter_input( INPUT_POST, 'message', FILTER_UNSAFE_RAW );  
        if( $message === NULL OR $message === false OR empty( $message ) OR $message === MAIL_MESSAGE ) // si le message fourni est vide ou égale à la valeur par défaut  
        {  
            $errors[] = 'Vous devez écrire un message.';  
        }  

        if( count( $errors ) === 0 ) // si il n'y a pas d'erreurs  
        {  
            if( mail( MAIL_TO, $object, $message, "From: $from\nReply-to: $from\n" ) ) // tentative d'envoi du message  
            {  
                $mailSent = true;  
            }  
            else // échec de l'envoi  
            {  
                $errors[] = 'Votre message n\'a pas été envoyé.';  
            }  
        }  
    }  
    else // le formulaire est affiché pour la première fois, avec les valeurs par défaut  
    {  
        $from = MAIL_FROM;  
        $object = MAIL_OBJECT;  
        $message = MAIL_MESSAGE;  
    }  
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "[http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd]">  
<html lang="fr" xmlns="[http://www.w3.org/1999/xhtml]" xml:lang="fr">  
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-108635690-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'UA-108635690-1');
    </script>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="http://www.unepageweb.fr" />
    <title>Contact Faye Chartré - Portfolio - Mes expériences, mes projets, mes passions</title>
    <meta name="robots" content="index, follow">
    <meta name="description" content="Contact Faye Chartré - Portfolio - Mes expériences, mes projets, mes passions">
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100" rel="stylesheet" />
</head>  
    <body>
        <div id="containForm">
            <header>
                <div>
                    <span class="photo"></span>
                    <h1 itemprop="name"><span>Faye</span> <span>Chartré</span>
                        <span itemprop="description">Web Developpement Front</span>
                    </h1>
                </div>
                <ul>
                    <li><a href="contact.php" class="on" target="_self"><img src="images/mail-512.png" width="37" class="pictoRs" alt="Contact"></a></li>
                    <li><a href="https://www.youtube.com/watch?v=4HGUlcqyn_A&feature=youtu.be" target="_blank" itemprop="url"><img src="images/youtuberouge.png" width="37" class="pictoRs" alt="yt"></a></li>
                </ul>
            </header>
            <div id="nav">
                <ul>
                    <li><a href="index.html" target="_self">Présentation</a></li>
                    <li><a href="comp.html" target="_self">Compétences</a></li>
                    <li><a href="parc.html" target="_self">Parcours</a></li>
                    <li><a href="port.html" target="_self">Videos</a></li>
                </ul>
            </div>

<?php  
    if( $mailSent === true ) // si le message a bien été envoyé, on affiche le récapitulatif  
    {  
?>  
        <div id="message">
        <p id="success">Votre message a bien été envoyé.</p>  
        <p><strong>Courriel pour la réponse :</strong><br /><?php echo( $from ); ?></p>  
        <p><strong>Objet :</strong><br /><?php echo( $object ); ?></p>  
        <p><strong>Message :</strong><br /><?php echo( nl2br( htmlspecialchars( $message ) ) ); ?></p>
        </div>

<?php  
    }  
    else // le formulaire est affiché pour la première fois ou le formulaire a été soumis mais contenait des erreurs  
    {  
        if( count( $errors ) !== 0 )  
        {  
            echo( "\t\t<ul>\n" );  
            foreach( $errors as $error )  
            {  
                echo( "\t\t\t<li>$error</li>\n" );  
            }  
            echo( "\t\t</ul>\n" );  
        }  
        else  
        {  
            echo( "\t\t<p id=\"welcome\"><em>Tous les champs sont obligatoires</em></p>\n" );  
        }  
?>  
        <form id='contact' method="post" action="<?php echo( $_SERVER['REQUEST_URI'] ); ?>"> 
            <h2>Contactez moi</h2> 
            <p>N'hésitez pas à me contacter pour toute question ou demande de prestation en remplissant les champs du fomulaire ci-dessous. Je réponds à toutes les demandes en moins de 48h:) 
            </p>
            <div class="info">
                <p>  
                    <label for="from">Votre adresse mail</label>  
                    <input type="text" name="from" id="from" value="<?php echo( $from ); ?>" placeholder="utilisateur@domaine.tld" />  
                </p>  
                <p>  
                    <label for="object">Objet</label>  
                    <input type="text" name="object" id="object" value="<?php echo( $object ); ?>" placeholder="Objet du message"/>  
                </p>   
                <p>  
                    <label for="message">Message</label>  
                    <textarea name="message" id="message" rows="10" cols="80"><?php echo( $message ); ?></textarea>  
                </p>  
                <p>  
                    <input type="reset" name="reset" value="Effacer" class="btn" />  
                    <input type="submit" name="send" value="Envoyer" class="btn" />  
                </p>
            </div>  
        </form>  
<?php  
    }  
?>
        </div>
        <div id="footer">
            <p>Publié le <time itemprop="datePublished" datetime="2017-12-17T09:32:00Z">17/12/2017<bR>@2017 fayechartre6@gmail.com - Tous droits réservés</p>
        </div>
    </body>


</html>