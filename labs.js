jQuery(document).ready(function($) {
    // Function to toggle the visibility of the form
    window.toggleForm = function() {
        var formContainer = $('#form-container');
        formContainer.toggle();
    };

    // Function to save or update lab information
    window.saveLab = function() {
        var id = $('#lab-id').val();
        var title = $('#lab-title').val();
        var description = $('#lab-description').val();
        var technology = $('#lab-technology').val();
        var level = $('#lab-level').val();
        var domain = $('#lab-domain').val();
        var duration = $('#lab-duration').val();
        var logo = $('#lab-logo')[0].files[0];

        var formData = new FormData();
        formData.append('id', id);
        formData.append('title', title);
        formData.append('description', description);
        formData.append('technology', technology);
        formData.append('level', level);
        formData.append('domain', domain);
        formData.append('duration', duration);
        if (logo) {
            formData.append('logo', logo);
        }

        $.ajax({
            url: '/wp-json/labs/v1/' + (id ? 'update' : 'add'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Lab saved successfully', response);
                toggleForm();
                loadLabsFromServer(); // Reload labs to reflect the new changes
            },
            error: function(error) {
                console.error('Error saving lab', error);
            }
        });
    };

    // Function to delete a lab
    window.deleteLab = function(id) {
        $.ajax({
            url: '/wp-json/labs/v1/delete',
            method: 'POST',
            data: { id: id },
            success: function(response) {
                console.log('Lab deleted successfully', response);
                $('div[data-id="' + id + '"]').remove(); // Remove the lab from the page
            },
            error: function(error) {
                console.error('Error deleting lab', error);
            }
        });
    };

    // Function to toggle the visibility of the filters
    window.toggleFilters = function() {
        $('.filters').toggle();
    };

    // Function to load labs from the server and add them to the page
    function loadLabsFromServer() {
        $.ajax({
            url: '/wp-json/labs/v1/get',
            method: 'GET',
            success: function(response) {
                $('#labs-list').empty(); // Clear the current labs
                response.forEach(function(lab) {
                    addLabToPage(lab);
                });
            },
            error: function(error) {
                console.error('Error loading labs', error);
            }
        });
    }

    // Function to add a single lab to the page
function addLabToPage(lab) {
    var labItem = $('<div>').addClass('lab-item').attr('data-id', lab.id).attr('data-tech', lab.technology).attr('data-level', lab.level).attr('data-domain', lab.domain);
    labItem.html(
        `<img src="${lab.logo}" alt="${lab.technology} logo">
        <div>
            <h3>${lab.title}</h3>
            <p>${lab.description}</p>
            <div class="details">
                <span>🟢 ${lab.technology}</span>
                <span>🟢 ${lab.level}</span>
                <span>🕒 ${lab.duration} mins</span>
            </div>
            <div class="menu">
                &#x22EE;
                <div class="menu-options" style="display: none;">
                    <div onclick="window.editLab(${lab.id})">Modifier</div>
                    <div onclick="window.deleteLab(${lab.id})">Supprimer</div>
                </div>
            </div>
        </div>`
    );
    $('#labs-list').append(labItem);
}

    // Call to load labs when the page is ready
    loadLabsFromServer();
});
