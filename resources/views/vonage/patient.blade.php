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
            <h1>Patient</h1>
            <div id="patient" style="width: 500px; height: 500px;"></div>
        </div>
        <div class="col">
            <h1>Observer</h1>
            <div id="observer" style="width: 500px; height: 500px;"></div>
        </div>
    </div>

</div>

<script>
    // Initialize an OpenTok session
    const apiKey = "{{ $api_key }}"
    const sessionId = "{{ $session_id }}"
    const token = "{{ $token }}";
    const session = OT.initSession(apiKey, sessionId);

    // Handling all of our errors here by alerting them
    function handleError(error) {
        if (error) {
            alert(error.message);
        }
    }

    session.on('streamCreated', (event) => {
        console.log('streamCreated', event.stream.connection.data);

        if (event.stream.connection.data === 'observer') {
            session.subscribe(event.stream, 'observer', {
                insertMode: 'append',
                width: '100%',
                height: '100%'
            }, handleError);
        } else if (event.stream) {
            console.log('event.stream', event.stream);
            session.unsubscribe(event.stream);
        }

    });

    // Create a publisher
    const publisher = OT.initPublisher('patient', {
        insertMode: 'append',
        width: '100%',
        height: '100%'
    }, handleError);

    // Connect to the OpenTok session using the Vonage token
    session.connect(token, (error) => {
        if (error) {
            console.error('Error connecting to the session:', error.message);
        } else {
            session.publish(publisher, handleError);
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
