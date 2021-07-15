# Module prestashop privilege #

## Description ##

Permet de mettre en place un système de parrainage appelé `code privilège`.  
Les clients s'inscrivent avec un code privilège qui correspond au code d'un commercial. Le commercial touchera des commissions sur toutes les ventes effectuées avec son code privilège.  
Le code privilège donne droit à des réductions pour le client.  
Le commercial doit être placé (manuellement) dans le groupe `commerciaux`, après validation de son identité.  
Le client privilégié sera placé (manuellement) dans le groupe `clients privilégiés`, après vérification de son code privilège.  
Les clients peuvent aussi être des professionnels. Dans ce cas, ils seront placés (manuellement) dans le groupe `professionnels`, après vérification de leur code privilège.  


## Il reste à faire ##
- le ménage dans le php (beaucoup de fonctions inutiles)
- modifier le formulaire d'affichage (listing) des clients dans le BO pour afficher le champs code privilège
- modifier le formulaire de modification d'un client dans le BO pour permettre de modifier le code privilège
- faire la traduction du module
- ajouter une partie dans l'espace client pour afficher et modifier son code privilège (ou juste l'afficher)
- créer automatiquement les groupes "commerciaux", "professionnels", et "clients privilégiés" (à voir)
- faire la partie migration (pour passer d'une version à une autre avec sauvegarde du contenu de la BDD, sauvegarder le champ code provilège pour pouvoir le réinjecter post-migration)
- permettre de faire des requêtes pour savoir les ventes réalisées par les clients ayant un code privilège donné (le code privilège correspond à un commercial qui se vera attribuer une commission sur toutes les ventes faites avec son code privilège)
- permettre au commercial de suivre, depuis son espace client, la liste des ventes de ses clients et de connaître le montant de ses commissions
- trouver une icone pour le module


## Historique des versions ##
- v 1.0.0  
Modification du formulaire d'inscription pour ajouter le champ "code privilège"  
Il y a une partie configuration du module (qui ne sert à rien. Ça sert de modèle)  
Création du champ privilege_code dans la table customer lors de l'installation et suppression du champ lors de la désinstallation.
