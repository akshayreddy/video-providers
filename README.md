```
composer install
npm install
```



# Vonage

### how to use 
- once vonage is selected from dropdown, the session id will be logged in the console.
- in another tab paste `{base_url}/provider/vonage/join/{session_id}`
example: `https://video-providers.test/provider/vonage/join/2_MX40NzgxOTI0MX5-MTcwMjQ3NjYwNjY0M35EeWlFWk0vdW01cEo3RHFMSGNvWWJxNG5-UH5-`

### notes
- The client will need Api key, session ID and token to create rooms, twilio just required the token from   the server. I need to see if we can just use the tokens, I personally feel we should just be sending the token from our server, its okay to send the session Id too but having the api_key in the front end is something I don't like for security reasons.
- Session here means room
- streams are participants
- channels are the tracks
- openTok throws warning about deprecated events if in use, which is good if we made any updated to package.
- cant find a function on php sdk to get the session using session id.
- this will give us the streams `session.streams.map((s) => console.log(s));`
