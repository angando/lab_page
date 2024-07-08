<?php
/* Template Name: Labs */
get_header();
?>

<div class="header">
    <div class="header-left">
        <div class="main-title">
            <img src="https://teachmemore.fr/wp-content/uploads/2024/06/icons8-cloud-480.png" alt="Logo">
            Hands-on Labs
        </div>
        <?php if ( current_user_can('manage_options') ) : ?>
            <button class="add-lab" onclick="toggleForm()">+</button>
        <?php endif; ?>
    </div>
    <div class="search-bar-container">
        <div class="search-bar">
            <input type="text" id="search" placeholder="Browse the Labs Library...">
        </div>
        <button class="filter-toggle" onclick="toggleFilters()">Filters</button>
    </div>
</div>
<div class="content">
    <div class="filters">
        <div class="filter-container">
            <div class="filter-group">
                <label for="technology">Technologie</label>
                <select id="technology">
                    <option value="">Sélectionner une Technologie</option>
                    <option value="AWS">AWS</option>
                    <option value="Azure">Azure</option>
                    <option value="Google Cloud">Google Cloud</option>
                    <option value="Docker">Docker</option>
                    <option value="Kubernetes">Kubernetes</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="skill-level">Niveau</label>
                <select id="skill-level">
                    <option value="">Sélectionner un Niveau</option>
                    <option value="Débutant">Débutant</option>
                    <option value="Intermédiaire">Intermédiaire</option>
                    <option value="Confirmé">Confirmé</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="domain">Domaine</label>
                <select id="domain">
                    <option value="">Sélectionner un Domaine</option>
                    <option value="DevOps">DevOps</option>
                    <option value="Sécurité">Sécurité</option>
                    <option value="Administration Système">Administration Système</option>
                    <option value="Administration Réseau">Administration Réseau</option>
                    <option value="Cybersécurité">Cybersécurité</option>
                    <option value="Cloud">Cloud</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-container" id="form-container">
        <input type="hidden" id="lab-id">
        <div class="form-group">
            <label for="lab-title">Titre</label>
            <input type="text" id="lab-title" placeholder="Entrez le titre du lab">
        </div>
        <div class="form-group">
            <label for="lab-description">Description</label>
            <textarea id="lab-description" placeholder="Entrez la description du lab"></textarea>
        </div>
        <div class="form-group">
            <label for="lab-technology">Technologie</label>
            <select id="lab-technology">
                <option value="">Sélectionner une Technologie</option>
                <option value="AWS">AWS</option>
                <option value="Azure">Azure</option>
                <option value="Google Cloud">Google Cloud</option>
                <option value="Docker">Docker</option>
                <option value="Kubernetes">Kubernetes</option>
            </select>
        </div>
        <div class="form-group">
            <label for="lab-level">Niveau</label>
            <select id="lab-level">
                <option value="">Sélectionner un Niveau</option>
                <option value="Débutant">Débutant</option>
                <option value="Intermédiaire">Intermédiaire</option>
                <option value="Confirmé">Confirmé</option>
                <option value="Expert">Expert</option>
            </select>
        </div>
        <div class="form-group">
            <label for="lab-domain">Domaine</label>
            <select id="lab-domain">
                <option value="">Sélectionner un Domaine</option>
                <option value="DevOps">DevOps</option>
                <option value="Sécurité">Sécurité</option>
                <option value="Administration Système">Administration Système</option>
                <option value="Administration Réseau">Administration Réseau</option>
                <option value="Cybersécurité">Cybersécurité</option>
                <option value="Cloud">Cloud</option>
            </select>
        </div>
        <div class="form-group">
            <label for="lab-duration">Durée (en minutes)</label>
            <input type="number" id="lab-duration" placeholder="Entrez la durée du lab">
        </div>
        <div class="form-group">
            <label for="lab-logo">Logo</label>
            <input type="file" id="lab-logo" accept="image/*">
        </div>
        <div class="form-group">
            <button onclick="saveLab()">Enregistrer</button>
            <button onclick="toggleForm()">Annuler</button>
        </div>
    </div>
    <div id="labs-list">
        <!-- Les laboratoires seront chargés ici -->
    </div>
</div>

<?php get_footer(); ?>
