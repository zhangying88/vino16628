<?php
/**
 * Class Bouteille
 * Cette classe possède les fonctions de gestion des bouteilles dans le cellier et des bouteilles dans le catalogue complet.
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2019-01-21
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 */
class Cellier extends Modele {
	const TABLE = 'cellier';
    
     /**
	 * Cette méthode crée un cellier
	 * 
	 * @param Array $data Tableau des données représentants le cellier.
	 * 
	 */
	public function creerUnNouveauCellier($data)
	{
		$rows = Array();
		$allCelliers = $this->_db->query('SELECT nom FROM ' . self::TABLE);
		$nomExistant = false;
		//Vérifier si le nom entrer pour la création du nouveau
		//cellier est déjà existant
		if($allCelliers->num_rows)
		{

            while($row = $allCelliers->fetch_assoc())
            {
            	if(strtolower($data["nomCellier"]) == strtolower($row["nom"])){
            		$nomExistant = true;
            		break;
            	}else{
            		$nomExistant = false;
            	}
            }
		}

		if($nomExistant == false)
		{
			$requete = "INSERT INTO cellier(id_usager,nom) VALUES (".
			"'".$data["idUsager"]."',".
			"'".$data["nomCellier"]."')";
			$res = $this->_db->query($requete);			
			return $res;
		}
		else if($nomExistant == true){
			return false;

		}
	}   
	
	

 /**
	 * récupère tous les cellier d'un usager
	 * 
	 * @param int $id id d'usager !!!À VENIR
     * 
	 * @return Array $rows les informations de chaque cellier 
     * ///////////////////DOIT AJOUTER UN ID POUR SELECTIONNER LE CELLIER////////////////////
	 */
	public function getListeCellier($id) 
	{
		
		$rows = Array();
		$requete = 'SELECT * FROM cellier WHERE id_usager = ' . $id;		
		if(($res = $this->_db->query($requete)) ==	 true)
		{
			if($res->num_rows)
			{
				while($row = $res->fetch_assoc())
				{
					$rows[] = $row;
				}
			}
		}
		else 
			{
				throw new Exception("Erreur de requête sur la base de donnée", 1);
			}
		return $rows;
	}
    
    
    /**
	 * récupère le contenu d'un cellier
	 * 
	 * @param int $id_cellier id du cellier
     * 
	 * @return Array $rows 
	 */
	public function getContenuCellier($id_cellier) 
	{
		$rows = Array();
        $requete ='SELECT 
                        * 
                        FROM ' . self::TABLE . '_contenu 
                        WHERE id_cellier = ' . $id_cellier . ' 
                        ORDER BY date_ajout ASC';
    
      
		if(($res = $this->_db->query($requete)) == true)
		{
			if($res->num_rows)
			{
				while($row = $res->fetch_assoc())
				{
					$rows[] = $row;
				}
			}
		}
		else 
		{
			throw new Exception("Erreur de requête sur la base de donnée", 1);
		}
		return $rows;
	}
    
    /**
	 * récupère la derniere ajout dans la table cellier_contenu
     * 
	 * @return Array $rows donne du dernier ajout
	 */
	public function getDernAjout() 
	{
        $requete ='SELECT * FROM ' . self::TABLE . '_contenu WHERE id =(SELECT MAX(id) FROM ' . self::TABLE . '_contenu)';
    
      
		if(($res = $this->_db->query($requete)) == true)
		{
			if($res->num_rows)
			{
				$row = $res->fetch_assoc();
			}
		}
		else 
		{
			throw new Exception("Erreur de requête sur la base de donnée", 1);
		}
		return $row;
	}
    
    /**
	 * ajoute une bouteille dans le cellier
	 * 
	 * @param int $id ranger dans le contenu cellier a supprimer
	 * 
	 * @return Boolean Succès ou échec de l'ajout.
	 */
	public function supprimerBouteille($id)
	{
		$requete = 'DELETE FROM cellier_contenu WHERE id = ' . $id;
        $res = $this->_db->query($requete);
        
		return $res;
	}
    
    /**
	 * Supprime une bouteille dans le cellier
	 * 
	 * @param int $id ranger dans le contenu cellier a supprimer
	 * 
	 * @return Boolean Succès ou échec de l'ajout.
	 */
	public function ajouterBouteille($data)
	{
		$requete = 'INSERT INTO cellier_contenu (id_cellier, id_bouteille, date_ajout, garde_jusqua) VALUE
            (' . $data -> id_cellier . ',
            ' . $data -> id_bouteille . ',
            "' . $data -> date_ajout . '",
            "' . $data -> garde_jusqua . '")';
        
        $res = $this->_db->query($requete);
        
		return $res;
	}








    
    
}




?>