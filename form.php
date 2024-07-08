<?php
/* Template Name: Labs Submission Form */

get_header(); ?>

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
        <!-- Filter options for searching labs -->
        <div class="filter-container">
            <!-- Add filter groups here -->
        </div>
    </div>
    <div class="form-container" id="form-container" style="display: none;">
        <form id="lab-form" method="post" enctype="multipart/form-data">
            <input type="hidden" id="lab-id" name="lab-id">
            <div class="form-group">
                <label for="lab-title">Titre</label>
                <input type="text" id="lab-title" name="lab-title" placeholder="Entrez le titre du lab">
            </div>
            <div class="form-group">
                <label for="lab-description">Description</label>
                <textarea id="lab-description" name="lab-description" placeholder="Entrez la description du lab"></textarea>
            </div>
            <div class="form-group">
                <label for="lab-technology">Technologie</label>
                <select id="lab-technology" name="lab-technology">
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
                <select id="lab-level" name="lab-level">
                    <option value="">Sélectionner un Niveau</option>
                    <option value="Débutant">Débutant</option>
                    <option value="Intermédiaire">Intermédiaire</option>
                    <option value="Confirmé">Confirmé</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
            <div class="form-group">
                <label for="lab-domain">Domaine</label>
                <select id="lab-domain" name="lab-domain">
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
                <input type="number" id="lab-duration" name="lab-duration" placeholder="Entrez la durée du lab">
            </div>
            <div class="form-group">
                <label for="lab-logo">Logo</label>
                <input type="file" id="lab-logo" name="lab-logo" accept="image/*">
            </div>
            <div class="form-group">
                <button type="button" onclick="saveLab()">Enregistrer</button>
                <button type="button" onclick="toggleForm()">Annuler</button>
            </div>
        </form>
    </div>
    <div id="labs-list">
        <!-- Les laboratoires seront chargés ici -->
    </div>
</div>

<script>
function toggleForm() {
    var formContainer = document.getElementById('form-container');
    formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
}

function toggleFilters() {
    var filters = document.querySelector('.filters');
    filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
}

// Assurez-vous que saveLab() est bien défini dans vos scripts JS chargés via enqueue.
</script>

<?php get_footer(); ?>
