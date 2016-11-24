<?php
/**
 * System messages translation for FolletXmasGifts application
 * @author  Stanislas BRODIN
 */
defined("BASEPATH") OR exit("No direct script access allowed");

// Champs génériques
$lang['yes'] = 'Oui';
$lang['no'] = 'Non';
$lang['home'] = 'Accueil';
$lang['back'] = 'Retour';
$lang['confirm'] = 'Valider';
$lang['user'] = 'Utilisateur';
$lang['users'] = 'Utilisateurs';
$lang['log_in'] = 'Connexion';
$lang['log_out'] = 'Déconnexion';
$lang['search'] = 'Recherche';
$lang['admin'] = 'Admin';
$lang['add'] = 'Ajouter';
$lang['edit'] = 'Editer';
$lang['link_to_home'] = 'Lien vers la page d\'accueil';
$lang['view'] = 'Voir';
$lang['contact'] = 'Contact';
$lang['filter_verb'] = 'Filtrer';
$lang['filters'] = 'Filtres';
$lang['show_hide'] = 'Afficher / Masquer';
$lang['password'] = 'Mot de passe';
$lang['password_confirmation'] = 'Confirmation du mot de passe';
$lang['new_password'] = 'Nouveau mot de passe';
$lang['new_password_confirmation'] = 'Confirmation du nouveau mot de passe';
$lang['email'] = 'Email';
$lang['cancel'] = 'Annuler';
$lang['year'] = 'Année';

// Champs spécifiques à l'application
$lang['application_title'] = 'FolletXmasGifts';

// Messages de succès
$lang['password_saved'] = 'Mot de passe enregistré, vous pouvez maintenant vous connecter.';
$lang['family_successful_creation'] = 'Famille créée avec succès !';
$lang['family_successful_edition'] = 'Famille éditée avec succès !';
$lang['user_successful_creation'] = 'Utilisateur créé avec succès !';
$lang['user_successful_edition'] = 'Utilisateur édité avec succès !';
$lang['link_successful_creation'] = '"Lien" créé avec succès !';
$lang['current_year_successful_edition'] = 'Année courante éditéé avec succès !';
$lang['draw_complete_successful'] = 'Année validée !';
$lang['draw_incomplete_successful'] = 'Année invalidée, tirage à revoir ou à revalider !';

// Messages d'information
$lang['no_correct_rights_log_again'] = 'Vous ne disposez pas des droits nécessaires pour accéder à cette partie du site.<br/><a href="%s" class="alert-link">Connectez-vous</a> à nouveau et réessayez.';
$lang['define_links_to_choose_year'] = 'Ajoutez des \'liens\' pour les visualiser et définir l\'année courante';

// Messages d'erreur
$lang['incorrect_login'] = 'Mauvaise paire email / mot de passe.';
$lang['error_same_user'] = 'Vous avez sélectionné 2 fois la même personne.';
$lang['duplicate_links'] = 'Le lien existe déjà en base.';

// Messages de contrôle des formulaires
$lang['required_field'] = 'Le champ "%s" est requis.';
$lang['must_match_field'] = 'Le champ "%s" doit correspondre au champ "%s".';
$lang['valid_email'] = 'Le champ "%s" n\'est pas un email valide.';
$lang['must_be_year_field'] = 'Le champ "%s" doit être une année supérieure ou égale à 2016.';

// Autres messages
$lang['no_draw_yet'] = 'Le tirage n\'a pas encore eu lieu';
$lang['you_offer_to'] = 'Cette année, vous offrez à %s !';

// Connexion
$lang['first_connection'] = 'Première connexion';
$lang['change_password_first_connection'] = 'Pour votre première connexion, veuillez définir un mot de passe';

// Admin
$lang['site_admin'] = 'Gestion du site';
$lang['users_admin'] = 'Gestion des utilisateurs';
$lang['families_admin'] = 'Gestion des "familles"';

// Gestion des utilisateurs
$lang['back_to_users_admin'] = 'Retour à la gestion des utilisateurs';
$lang['add_a_user'] = 'Ajouter un utilisateur';
$lang['index_user'] = 'Liste des utilisateurs';
$lang['first_name'] = 'Prénom';
$lang['last_name'] = 'Nom';
$lang['family'] = '"Famille"';
$lang['acl'] = 'Statut';
$lang['is_active'] = 'Actif ?';
$lang['last_connection'] = 'Dernière connexion';
$lang['isadmin'] = 'Admin ?';
$lang['add_user'] = 'Ajouter l\'utilisateur';
$lang['never_connected'] = 'Jamais connecté';
$lang['incorrect_email'] = "Le champ %s ne correspond pas à une adresse email valide";
$lang['edit_user'] = 'Mettre à jour';
$lang['activate_user'] = 'Activer';
$lang['deactivate_user'] = 'Désactiver';

// Gestion des familles
$lang['back_to_families_admin'] = 'Retour à la gestion des "familles"';
$lang['add_a_family'] = 'Ajouter une "famille"';
$lang['add_family'] = 'Ajouter la "famille"';
$lang['family_name'] = 'Nom de la "famille"';

// Gestion des offreurs
$lang['back_to_offersto_admin'] = 'Retour à la gestion des "liens"';
$lang['who_offers_to_who'] = 'Qui offre à qui ?';
$lang['change_current_year'] = 'Changer l\'année courante';
$lang['add_a_link'] = 'Ajouter un "lien"';
$lang['sender'] = 'Expéditeur';
$lang['receiver'] = 'Destinataire';
$lang['draw_complete'] = 'Tirage complet pour l\'année %d';
$lang['draw_incomplete'] = 'Tirage annulé pour l\'année %d';
