jQuery(document).ready(function($) {
    console.log('Document ready');
    // Toggle the visibility of the form
    window.toggleForm = function() {
        $('#form-container').toggle();
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
            error: function() {
                console.error("Error retrieving labs.");
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
            error: function() {
                console.error("Error loading labs.");
                $('#labs-list').html('<p>Error loading labs.</p>');
            }
        });
    }

    loadLabsFromServer(); // Call this function to load labs when the page is ready
});
