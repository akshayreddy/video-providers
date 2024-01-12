<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Conferencing Platforms</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS (Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h1>Video Conferencing Platforms</h1>

    <!-- Dropdown with options -->
    <div class="dropdown mt-3">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="platformDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Select a Platform
        </button>
        <ul class="dropdown-menu" aria-labelledby="platformDropdown">
            <li><a class="dropdown-item" href="#" data-platform="Vonage">Vonage</a></li>
            <li><a class="dropdown-item" href="#" data-platform="Amazon Chime">Amazon Chime</a></li>
            <li><a class="dropdown-item" href="#" data-platform="Zoom">Zoom</a></li>
            <li><a class="dropdown-item" href="#" data-platform="MS Azure">MS Azure</a></li>
        </ul>
    </div>
</div>

<script>
    // Add a click event listener to each dropdown item
    $(document).on('click', '.dropdown-item', function () {
        // Get the platform name from the 'data-platform' attribute
        const selectedPlatform = $(this).data('platform');

        // Log the selected platform to the console
        console.log('Selected Platform:', selectedPlatform);
    });
</script>

</body>
</html>
