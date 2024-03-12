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
    <div id="box">
        <video autoplay muted id="test" src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"></video>
        {{-- <video autoplay src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"></video> --}}
    </div>
</div>

<script>

    let video = document.getElementById('test');
    let container = document.getElementById('box');
    let mediaStream = video.captureStream();

    console.log('mediaStream', mediaStream);

    let newVideo = document.createElement('video');
    newVideo.srcObject = mediaStream;
    newVideo.autoplay = true;
    container.append(newVideo);


</script>

</body>
</html>
