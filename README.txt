#README is intended for the php file "f2bserver_api.php", made by Hunter A. Santos
#IT 490
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 
					|
             HOW IT WORKS               |
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ |

Step 1)
User inputs desired search parameters, based on the location they wish to fly to, as well as where they will fly from in the Front-End(FE). Once the user has completed the desired search parameters, they will submit the parameters to the FE. The FE will then send a "Search Array", populated with the user's search parameters, to the Back-End(BE).

Step 2)
The BE will take the "Search Array" and, using the Skyscanner API database, will then search for any matching places that reference the parameters given in the "Search Array". The FE will be sending two requests for this task, accomodating for both origin and destination the user had inputed. Both created arrays for both origin and destination will be sent to the FE.

Step 3)
The FE will allow the user to choose the desired location that the Skyscanner API database can provide. Once both origin and destination have been chosen, the FE will update the "Search Array" and send it back to the BE.

Step 4)
The BE will now create a session key, using the parameters in the updated "Search Array", to use to initiate a search via the Skyscanner API for ticket prices and links. Once the session key is created, the link search can begin. All results from this search will be sent to the FE.

Step 4.1)
The user might incorporate additional parameters to narrow the search before creating the session key. These filters will be included in the creation of the session key before initiation the search for the links.

Step 4.2)
The user might also incorporate filters for when the link search is initiated, in order to narrow down the search results. These filters will also be incorporated into the link search when it is reinitialized.


_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ 
					|
	    FUNCTIONALITIES             |
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ |

The script, f2bserver_api.php, allows communication via RabbitMQ between Back-End and Front-End, as well as calling to a number of functions that allow the script to connect, request, and pull data from the SkyScanner API. This is all done in the span of three requests for each search session.

The user must fill out all parameters presented to them correctly in order to get an accurate and timely search session. Once they have initialed the search, the API may or may not provide the user one or more results for both origin and destination queries. Once the user inputs the correct PlaceId's for both origin and destination places, they click submit.

The BE will use this updated code to retrieve a session key, which will help retrieve an array with all the links and prices for the "origin to destination" query.

The script delegates tasks based on states given from the Front-End via strings in the search array called ["Types"], which include "getPlaces" and "getSession". Any other types that are not the said two will not work and will result in an error.


