The way the back end file "f2bserver_api.php" works is as follows:

1. Front end sends to backend the parameters that come from what the user is searching for (location, country, currency, locale, etc.)
2. Back end searches for the legitimate place based on the query that the user inputed, via the getPlace function. (If user searches "Orlando", the function will find the appropriate place, which would be "Orlando, Florida")
3. With the appropriate syntax now available, the function setSession is called. The function allows the API to create a session key so a query of links to ticekting sites is available based on what the user parameters entail.
4. With the session key available, it is returned to the getSession function, where the session is called, with the key, to provide the links needed to access the ticketing sites.
5. Finally this information is sent to the front end again via rabbitmq
