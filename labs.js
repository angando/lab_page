jQuery(document).ready(function($) {
    console.log('Document ready');

    // Toggle the visibility of the form
    window.toggleForm = function() {
        $('#form-container').toggle();
    };

    // Toggle the visibility of the filter container
    window.toggleFilters = function() {
        var filterContainer = document.getElementById('filter-container');
        filterContainer.style.display = filterContainer.style.display === 'none' ? 'block' : 'none';
    };

    // Handle the search functionality when the search button is clicked
    window.handleSearch = function() {
        var searchTerm = $('#search').val();
        console.log('Search term:', searchTerm);
        if (!searchTerm) {
            console.log("Please enter a term to search or clearing search will show all labs.");
            loadLabsFromServer(); // Reload all labs if search is cleared or empty
            return;
        }

        // Fetch data from the server using the search term
        $.ajax({
            url: '/wp-json/labs/v1/get',
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                console.log('Labs received:', response);
                $('#labs-list').empty(); // Clear existing labs
                if (response && response.length > 0) {
                    response.forEach(function(lab) {
                        addLabToPage(lab);
                    });
                } else {
                    console.log("No labs found matching your criteria.");
                    $('#labs-list').html('<p>No labs found.</p>'); // Display not found message
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error retrieving labs:", textStatus, errorThrown);
                $('#labs-list').html('<p>Error retrieving labs.</p>'); // Display error message
            }
        });
    };

    // Function to add a single lab to the page
    function addLabToPage(lab) {
        var logoURL = lab.logo ? lab.logo : 'https://example.com/path/to/default-image.png'; // Fallback image URL
        var labItem = $('<div>').addClass('lab-item').attr('data-id', lab.id);
        labItem.html(`
            <img src="${logoURL}" alt="${lab.technology} logo">
            <div>
                <h3>${lab.title}</h3>
                <p>${lab.description}</p>
                <div class="details">
                    <span> 🟢 ${lab.technology}</span>
                    <span> 🟢 ${lab.level}</span>
                    <span> 🕒 ${lab.duration} mins</span>
                </div>
            </div>
        `);
        $('#labs-list').append(labItem);
    }

    // Load labs initially to populate the page
    function loadLabsFromServer() {
        $.ajax({
            url: '/wp-json/labs/v1/get',
            method: 'GET',
            success: function(response) {
                console.log('Labs loaded from server:', response);
                $('#labs-list').empty(); // Clear existing labs
                response.forEach(function(lab) {
                    addLabToPage(lab);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error loading labs:", textStatus, errorThrown);
                $('#labs-list').html('<p>Error loading labs.</p>');
            }
        });
    }

    window.filterLabs = function() {
        var technology = document.getElementById('technology').value;
        var level = document.getElementById('skill-level').value;
        var domain = document.getElementById('domain').value;

        fetch(`/wp-json/labs/v1/get?technology=${technology}&level=${level}&domain=${domain}`)
            .then(response => response.json())
            .then(response => {
                console.log('Labs received:', response);
                var labsList = document.getElementById('labs-list');
                labsList.innerHTML = ''; // Clear existing labs
                if (response && response.length > 0) {
                    response.forEach(function(lab) {
                        addLabToPage(lab);
                    });
                } else {
                    console.log("No labs found matching your criteria.");
                    labsList.innerHTML = '<p>No labs found.</p>'; // Display not found message
                }
            })
            .catch(error => {
                console.error("Error retrieving labs:", error);
                document.getElementById('labs-list').innerHTML = '<p>Error retrieving labs.</p>'; // Display error message
            });
    };

    // Initial call to load all labs when the page is ready
    loadLabsFromServer();
});
