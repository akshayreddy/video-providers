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

    <!-- OpenTok JS -->
    <script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Vonage</h1>

    <div class="row">
        <div class="col">
            <h1 id="patient-title">Patient</h1>
            <video id="patient" style="width: 500px; height: 500px;"></video>
        </div>
        <div>
            <h1>Duplicate stream method</h1>
            <video id="stream1" style="width: 500px; height: 500px;"></video>
            <video id="stream2" style="width: 500px; height: 500px;"></video>
        </div>
        <div class="col">
            <h1>Clone Method method</h1>
            <div id="clone1" style="width: 500px; height: 500px;"></div>
            <div id="clone2" style="width: 500px; height: 500px;"></div>
        </div>
    </div>

</div>

<script>
    // Initialize an OpenTok session
    const apiKey = "{{ $api_key }}"
    const sessionId = "{{ $session_id }}"
    const token = "{{ $token }}";
    const session = OT.initSession(apiKey, sessionId);

    // const patientDiv = document.createElement('div');
    // Set the ID of the div element
    // patientDiv.id = 'patient';

    // Set the style to hide the div
    // patientDiv.style.display = 'none';
    // patientDiv.style.width = '500px';
    // patientDiv.style.height = '500px';

    // Append the div element to the document body or another container
    // const patientTtitle = document.getElementById('patient-title');
    // patientTtitle.append(patientDiv);

    // Handling all of our errors here by alerting them
    function handleError(error) {
        if (error) {
            alert(error.message);
        }
    }

    session.on('streamCreated', (event) => {

        if (event.stream.connection.data === 'observer') {
            session.subscribe(event.stream, 'observer', {
                insertMode: 'append',
                width: '100%',
                height: '100%'
            }, handleError);
        }

        if (JSON.parse(event.stream.connection.data).type === 'sitter') {
            console.log('sitter event', event);

            const subscriber = session.subscribe(event.stream, {insertDefaultUI: false});
            subscriber.on('videoElementCreated', (event) => {
                console.log('videoElementCreated sitter event', event);

                // duplicate stream method
                const observer1 = document.getElementById('stream1')
                const observer2 = document.getElementById('stream2')
                console.log(event.element.srcObject.getTracks());
                const tracks = event.element.srcObject.getTracks();

                let stream1 = new MediaStream(tracks);
                observer1.srcObject = stream1
                observer1.play();

                tracks.forEach((track) => {
                    if (track.kind === 'video') {
                        let stream2 = new MediaStream([track]);
                        observer2.srcObject = stream2;
                        observer2.play();
                    }
                });

                // cloneNode method
                const observer3 = document.getElementById('clone1');
                observer3.appendChild(event.element);
                const observer4 = document.getElementById('clone2');
                clonedVideoElement = event.element.cloneNode(true);
                clonedVideoElement.id = 'new-id';
                clonedVideoElement.width = 500;
                clonedVideoElement.height = 500;
                observer4.appendChild(clonedVideoElement);
                clonedVideoElement.play();
            });


            // new subscribe method
            // const subscriber2 = session.subscribe(event.stream, {insertDefaultUI: false});
            // subscriber2.on('videoElementCreated', (event) => {
            //     const observer2 = document.getElementById('observer2')
            //     observer2.appendChild(event.element);
            // });

        } else if (event.stream) {
            console.log('event.stream', event.stream);
            session.unsubscribe(event.stream);
        }

    });

    // Create a publisher
    // const publisher = OT.initPublisher('stream1', {
    //     insertMode: 'append',
    //     width: '100%',
    //     height: '100%'
    // }, (error) => {
    //     console.log('publisher error', error);
    // });

    // const publisher = OT.initPublisher({
    //     width: '100%',
    //     height: '100%'
    // }, (error) => {
    //     console.log('publisher error');
    // });

    // const publisher = OT.initPublisher({
    //     insertDefaultUI: false,
    //     width: '100%',
    //     height: '100%'
    // }, (error) => {
    //     console.log('publisher with insertDefaultUI false error');
    // });

    const publisher = OT.initPublisher('stream1', (error) => {
        console.log("create publisher: this error is undefined", error);
    });

    // Connect to the OpenTok session using the Vonage token
    session.connect(token, (error) => {
        if (error) {
            console.error('Error connecting to the session:', error.message);
        } else {
            session.publish(publisher, (error) => {
                console.log('session publish: this error is null', error);
            });
        }
    });

    publisher.on('videoElementCreated', function(event) {
        console.log('videoElementCreated event.element', event.element);
        console.log(event);
        let videoSource = publisher.getVideoSource();
        // document.getElementById('patient').appendChild(event.element);
        console.log('videoSource', videoSource);
        const patientVideoElement = document.getElementById('patient');
        patientVideoElement.srcObject = new MediaStream([videoSource.track]);
        patientVideoElement.play();
    });

    publisher.on('streamCreated', (event) => {
        console.log('streamCreated', event);
    });

    console.log('publisher', publisher);

    // Connect to the OpenTok session using the Vonage token
    session.connect(token, (error) => {
        if (error) {
            console.error('Error connecting to the session:', error.message);
        } else {
            session.publish(publisher, () => {
                console.log('session publish error');
            });
            console.log('session', session);
        }
    });

    session.on("signal", function(event) {
      console.log("Signal sent from connection " + event.data);
      // Process the event.data property, if there is any data.
    });


</script>

</body>
</html>
