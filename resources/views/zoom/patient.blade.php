<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Conferencing Platform</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS (Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Zoom JS -->
    <script src="https://source.zoom.us/videosdk/zoom-video-1.10.7.min.js"></script>

</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Zoom Observer</h1>

    <div class="row" >
        <div class="col">
            <h1>Local: Patient</h1>
            <video-player-container id="patient" style="width: 100%; height: auto;">
            </video-player-container>
        </div>
        <div class="col">
            <h1>Remote: Observer</h1>
            <video-player-container id="observer" style="width: 100%; height: auto;">
            </video-player-container>
        </div>
    </div>
</div>

<script>
    // Initialize an OpenTok session
    const ZoomVideo = WebVideoSDK.default;
    console.log('zoom', ZoomVideo);

    const client = ZoomVideo.createClient()
    let stream;

    let token = '{{ $token }}';
    let sessionName = '{{ $sessionName }}';

    console.log(token);
    console.log(sessionName);
    console.log(client);

    client.init('en-US', 'Global', { patchJsMedia: true }).then(() => {
        client.join(sessionName, token, 'patient').then(() => {
            stream = client.getMediaStream()
            stream.startVideo()
                .then((videoElement) => {
                    console.log('start video successful');
                })
                .catch((error) => {
                    console.log('start video error', error);
                });
        });
    });

    // listners()

    function listners() {

        client.on('user-added', (payload) => {
            console.log('user-added', payload);
        });

        client.on('video-active-change', (payload) => {
            let user = client.getUser(payload.userId);
            console.log('video-active-change', user);

            if (payload.state === 'Inactive' && !user) {
                return;
            }

            if (user.displayName === 'patient') {
                stream.attachVideo(payload.userId, stream.getVideoMaxQuality())
                    .then((videoElement) => {
                        document.getElementById('patient').append(videoElement);
                    })
                    .catch((error) => {
                        console.log('patient attachVideo error', error);
                    });
            }

            if (user.displayName === 'observer') {
                stream.attachVideo(payload.userId, stream.getVideoMaxQuality())
                    .then((videoElement) => {
                        document.getElementById('observer').append(videoElement);
                    })
                    .catch((error) => {
                        console.log('observer attachVideo error', error);
                    });
            }

        });
    }

</script>

</body>
</html>
