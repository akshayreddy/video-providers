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
            <h1>Local: Observer</h1>
            <video-player-container>
                <div id="observer"></div>
            </video-player-container>
        </div>

        {{-- FOR ATTACH VIDEO --}}
        <div class="col">
            <div class="col">
                <h1>Remote: Patient Thumbnamil</h1>
                <video-player-container>
                    <div id="patient-thumbnail"></div>
                </video-player-container>
            </div>
            <div class="col">
                <h1>Remote: Patient Main Video</h1>
                <video-player-container>
                    <div id="patient-main"></div>
                </video-player-container>
            </div>
        </div>

        </div>
    </div>
</div>

<script>
    // Initialize an OpenTok session
    const ZoomVideo = WebVideoSDK.default;
    console.log('zoom', ZoomVideo);

    let client = ZoomVideo.createClient()
    let stream;

    let token = '{{ $token }}';
    let sessionName = '{{ $sessionName }}';

    console.log(token);
    console.log(sessionName);
    console.log(client);

    client.init('en-US', 'Global', { patchJsMedia: true }).then(() => {
        client.join(sessionName, token, 'observer').then(() => {
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

    listners();


    function listners() {
        client.on('video-dimension-change', (payload) => {
            console.log('video-dimension-change', payload);
        });

        client.on('video-active-change', (payload) => {
            let user = client.getUser(payload.userId);
            console.log('video-active-change', user);

            if (payload.state === 'Inactive') {
                return;
            }

            if (user.displayName === 'observer' && user.bVideoOn === true) {
                stream.attachVideo(payload.userId, stream.getVideoMaxQuality())
                    .then((videoElement) => {
                        videoElement.style.width = "200px";
                        videoElement.style.height = "200px";
                        document.getElementById('observer').append(videoElement);
                    })
                    .catch((error) => {
                        console.log('participant attachVideo error', error);
                    });
            }

            if (user.displayName === 'patient' && user.bVideoOn === true) {

                console.log('patient id', user.userId);
                // attach video
                stream.attachVideo(user.userId, stream.getVideoMaxQuality())
                    .then((videoElement) => {
                        videoElement.style.width = "200px";
                        videoElement.style.height = "200px";
                        document.getElementById('patient-thumbnail').append(videoElement);
                        console.log('added video patient thumbnail');
                    })
                    .catch((error) => {
                        console.log('patient-thumbnail attachVideo error', error);
                    });

                stream.attachVideo(user.userId, stream.getVideoMaxQuality())
                    .then((videoElement) => {
                        videoElement.style.width = "200px";
                        videoElement.style.height = "200px";
                        document.getElementById('patient-main').append(videoElement);
                        console.log('added video patient main');
                    })
                    .catch((error) => {
                        console.log('patient-main attachVideo error', error);
                    });



            }
        });
    }



</script>

</body>
</html>
