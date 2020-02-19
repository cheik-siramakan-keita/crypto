<?php
require_once("app/imports.php");

class UtilisateurDAO implements CRUD {
    public function create($utilisateur){
        $connect = new Connect;
        $authentificationdao = new AuthentificationDAO;
        //$type_de_connexion = parse_ini_file("conf/settings.ini", true)["type"]["nom"];
        $bdd = $connect->connexion();

        $requete = $bdd->prepare("INSERT INTO utilisateur(utilisateur_actif, role_id, specialisation_id, utilisateur_nom, utilisateur_prenom, utilisateur_email, utilisateur_telephone,
                                                            utilisateur_numero_de_rue, utilisateur_rue, utilisateur_ville, utilisateur_code_postal) 
                                    VALUES(:actif, :role, :specialisation, :nom, :prenom, :email, :telephone, :numero_de_rue, :rue, :ville, :code_postal)");
        $resultat = $requete->execute([
            "actif"=>$utilisateur->getActif(),
            "role"=>$utilisateur->getRole()->getId(),
            "specialisation"=>$utilisateur->getSpecialisation()->getId(),
            "nom"=>$utilisateur->getNom(),
            "prenom"=>$utilisateur->getPrenom(),
            "email"=>$utilisateur->getEmail(),
            "telephone"=>$utilisateur->getTelephone(),
            "numero_de_rue"=>$utilisateur->getNumeroderue(),
            "rue"=>$utilisateur->getRue(),
            "ville"=>$utilisateur->getVille(),
            "code_postal"=>$utilisateur->getCodepostal()
        ]);
        
        $requete = $bdd->prepare("SELECT utilisateur_id FROM utilisateur WHERE utilisateur_email=?");
        $requete->execute([$utilisateur->getEmail()]);
        $utilisateur->setId($requete->fetch()["utilisateur_id"]);
        $authentification = $utilisateur->getAuthentification();
        $authentification->setUtilisateur($utilisateur);
        $authentificationdao->create($authentification);

        return $resultat;
    }

    public function delete($option){
        $connect = new Connect;
        //$type_de_connexion = parse_ini_file("conf/settings.ini", true)["type"]["nom"];
        $bdd = $connect->connexion();

        $sql = "DELETE FROM utilisateur";

        switch ($option['option']) {
            case 'id':
                $requete = $bdd->prepare($sql." WHERE utilisateur_id=:id");
		        $requete->execute(["id"=>$option["valeur"]]);
                break;
            case 'actif':
                $requete = $bdd->prepare($sql." WHERE utilisateur_actif=:actif");
                $requete->execute(["actif"=>$option["valeur"]]);
                break;
            case 'role':
                $requete = $bdd->prepare($sql." WHERE role_id=:role");
                $requete->execute(["role"=>$option["valeur"]]);
                break;
            case 'specialisation':
                $requete = $bdd->prepare($sql." WHERE specialisation_id=:specialisation");
                $requete->execute(["specialisation"=>$option["valeur"]]);
                break;
            case 'nom':
                $requete = $bdd->prepare($sql." WHERE utilisateur_nom=:nom");
                $requete->execute(["nom"=>$option["valeur"]]);
                break;
            case 'prenom':
                $requete = $bdd->prepare($sql." WHERE utilisateur_prenom=:prenom");
                $requete->execute(["prenom"=>$option["valeur"]]);
                break;
            case 'email':
                $requete = $bdd->prepare($sql." WHERE utilisateur_email=:email");
                $requete->execute(["email"=>$option["valeur"]]);
                break;
            case 'telephone':
                $requete = $bdd->prepare($sql." WHERE utilisateur_telephone=:telephone");
                $requete->execute(["telephone"=>$option["valeur"]]);
                break;
            case 'numéro de rue':
                $requete = $bdd->prepare($sql." WHERE utilisateur_numero_de_rue=:numero_de_rue");
                $requete->execute(["numero_de_rue"=>$option["valeur"]]);
                break;
            case 'rue':
                $requete = $bdd->prepare($sql." WHERE utilisateur_rue=:rue");
                $requete->execute(["rue"=>$option["valeur"]]);
                break;
            case 'ville':
                $requete = $bdd->prepare($sql." WHERE utilisateur_ville=:ville");
                $requete->execute(["ville"=>$option["valeur"]]);
                break;
            case 'code postal':
                $requete = $bdd->prepare($sql." WHERE utilisateur_code_postale=:code_postal");
                $requete->execute(["code_postal"=>$option["valeur"]]);
                break;
            case 'date':
                $requete = $bdd->prepare($sql." WHERE utilisateur_date=:date");
                $requete->execute(["date"=>$option["valeur"]]);
                break;
            default:
                $requete = $bdd->prepare($sql);
                $requete->execute();
                break;
        }

		return $requete->fetch();
    }

    public function read($option){
        $connect = new Connect;
        //$type_de_connexion = parse_ini_file("conf/settings.ini", true)["type"]["nom"];
        $bdd = $connect->connexion();

        $sql = 
        "
            SELECT utilisateur.utilisateur_id, utilisateur.utilisateur_actif, utilisateur.utilisateur_nom, utilisateur.utilisateur_prenom,
		            utilisateur.utilisateur_email, utilisateur.utilisateur_telephone, utilisateur.utilisateur_numero_de_rue, utilisateur.utilisateur_rue,
		            utilisateur.utilisateur_ville, utilisateur.utilisateur_code_postal, utilisateur.utilisateur_date, role.role_id, role.role_nom,
		            specialisation.specialisation_id, specialisation.specialisation_nom, authentification.authentification_mot_de_passe, 
		            authentification.authentification_cle_secrete
            FROM utilisateur
            JOIN role ON utilisateur.role_id=role.role_id
            JOIN specialisation ON utilisateur.specialisation_id = specialisation.specialisation_id
            JOIN authentification ON utilisateur.utilisateur_id = authentification.utilisateur_id
        ";

		switch ($option["option"]) {
			case 'id':
				$requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_id=:valeur");
				$requete->execute(["valeur" => $option["valeur"]]);
				break;
			case 'nom':
				$requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_nom=:valeur");
				$requete->execute(["valeur" => $option["valeur"]]);
                break;
            case 'prenom':
                $requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_prenom=:valeur");
                $requete->execute(["valeur" => $option["valeur"]]);
                break;
            case 'email':
                $requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_email=:valeur");
                $requete->execute(["valeur" => $option["valeur"]]);
                break;
            case 'telephone':
                $requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_telephone=:valeur");
                $requete->execute(["valeur" => $option["valeur"]]);
                break;
            case 'numéro de rue':
                $requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_numero_de_rue=:valeur");
                $requete->execute(["valeur" => $option["valeur"]]);
                break;
            case 'rue':
                $requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_rue=:valeur");
                $requete->execute(["valeur" => $option["valeur"]]);
                break;
            case 'ville':
                $requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_ville=:valeur");
                $requete->execute(["valeur" => $option["valeur"]]);
                break;
            case 'code postal':
                $requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_code_postal=:valeur");
                $requete->execute(["valeur" => $option["valeur"]]);
                break;
            case 'date':
                $requete = $bdd->prepare($sql." WHERE utilisateur.utilisateur_date=:valeur");
                $requete->execute(["valeur" => $option["valeur"]]);
                break;
			default:
				$requete = $bdd->prepare($sql);
				$requete->execute();
				break;
        }

		return $requete->fetch();
    }

    public function update($utilisateur){
        $connect = new Connect;
        //$type_de_connexion = parse_ini_file("conf/settings.ini", true)["type"]["nom"];
        $bdd = $connect->connexion();

        if($utilisateur->getRole()->getId() != 3){
            $utilisateur->getSpecialisation()->setId(1);
        }

        $requete = $bdd->prepare("UPDATE utilisateur SET utilisateur_actif=:actif, role_id=:role, 
                                        specialisation_id=:specialisation, utilisateur_nom=:nom, utilisateur_prenom=:prenom,
                                        utilisateur_email=:email, utilisateur_telephone=:telephone, utilisateur_numero_de_rue=:numero_de_rue,
                                        utilisateur_rue=:rue, utilisateur_ville=:ville, utilisateur_code_postal=:code_postal WHERE utilisateur_id=:id");
		$requete->execute([
            "id"=>$utilisateur->getRole()->getId(),
            "specialisation"=>$utilisateur->getSpecialisation()->getId(),
            "actif"=>$utilisateur->getActif(),
            "nom"=>$utilisateur->getNom(),
            "prenom"=>$utilisateur->getPrenom(),
            "email"=>$utilisateur->getEmail(),
            "telephone"=>$utilisateur->getTelephone(),
            "numero_de_rue"=>$utilisateur->getNumeroderue(),
            "rue"=>$utilisateur->getRue(),
            "ville"=>$utilisateur->getVille(),
            "code_postal"=>$utilisateur->getCodepostal()
        ]);

		return $requete->fetch();
    }
}
?>