Longpoll
========

A PHP / Jquery XHR Longpolling based chat application


I often keep complaining about how terrible Facebook's chat/messaging system is : it is unreliable, slow and clunky. This, coupled with the fact that I had no experience implementing a web-based chat system, motivated me to get my hands dirty and see what exactly goes into building a chat system (reliable or not).
 
The chat application is entirely barebones and contains absolutely no frills – no registration, no users, no smileys, no pretty interface etc., It also has a few known bugs that I will fix eventually. The application consists of 5 php scripts, and one javascript file (plus the usual stylesheet).
 
Developing the entire chat application, from start to finish, took me about 6 hours (should have taken lesser but more on that later). Below, I detail my experience and the technologies used.
 
Demo
-----

Video: http://youtu.be/TPcskQ4tmdM

An Introduction to the Chat Environment
----------------------------------------

(Index.php) The chat begins at index.php by requiring you to enter your name along with the name of the person you intend to chat with. These values are stored in cookies and used during the entire chat process to ensure delivery of the message to the intended recipient. If you visit index.php while already logged in, you will be redirected to chat.php.
 
(chat.php) Once logged in, you are directed to chat.php. This is where the magic happens. Consists of the chat box (where you see the conversation) and the message box (where you type out messages).
 
(logout.php) Unsets the cookies and logs you out.
 
A barebones chat system consists of two major actions - sending a message to a specific recipient and receiving the message on the intended recipient’s end. Additional frills of notifying you when the other user is typing etc. are minor modifications of the same mechanisms.
 
 
Sending a Message
------------------

Implementing the message sending was trivial. When the user types in the message and hits send, the contents of the message are sent to a server side script via an AJAX request. The server side script receives the message and enters it into a MySQL database along with the sender and the recipient’s name (picked up from the previously created cookies), a timestamp  and a ‘hasRead’ flag set to 0. The ‘hasRead’ flag keeps track of whether the message has been received by the recipient or not (a value of 0 implying it hasn’t been read).
 
Once the above has been completed, the server side script returns as a response the status of the transaction to the awaiting AJAX request. If the transaction was successful, your message is added to your chat box. In case of a failure, you are notified.
 
That’s really all there is to sending a message. I used jQuery for most of the javascript (failure alerts, AJAX requests etc.)
 
Receiving the Message
----------------------

Receiving the message is where the challenge lies in creating a robust chat application. There are two ways to implement this: PULL or PUSH.
 
In a PULL implementation, the client will occasionally ping the server checking if there are any new messages for it. If there are any, the server returns them, otherwise the server returns a blank response. Upon receiving a response, the client waits for a while and then repeats the same process. This is also known as a heartbeat mechanism.
 
It has certain drawbacks to it, however:
 
Firstly, continuously polling the server whether or not a message has been received is a waste of bandwidth. If you have your chat window open and walk away for 30min, the client will continue to poll the server for those 30min.
Secondly, there will be an inevitable time-gap from when the server receives the message intended for you, and you receive it. If the client is programmed to sleep for x seconds in between heartbeats,in a worst case, you will face a delay of x seconds. Reducing the value of x reduces the delay, but consequently increases the bandwidth consumption.
 
In a PUSH implementation, rather than the client polling the server continuously (Are we there yet? Are we there yet?...), the client informs the server that it would like to be notified when a message arrives (known as subscription) and then waits patiently. If and when the server receives a message for the client, it passes the message along (known as publishing). Thus, PUSH follows a subscribe/publish model. This does away with both the drawbacks of PULL implementation – wasted bandwidth and delay.
PUSH makes  for more robust and efficient applications, and so I decided to go with a PUSH implementation.
 
Comet
------

Comet is an umbrella term used to define the different methods to implement a PUSH mechanism in a web application. This includes hidden iframes and XHR Long Polling, among others. The hidden iframe implementation works by embedding a hidden html frame in your document that keeps a persistent connection open with a server side script. The server script returns javascript code that is then executed on the client’s end. This method can be susceptible to javascript injection by MITM attacks.
 
XHR Long Polling
-----------------

XHR Long Polling, or XMLHttpRequest Long Polling, works by issuing an AJAX request to the server quite like a PULL mechanism. However, unlike PULL, if the server does not have any new messages for the client, it does not return a blank response. It, instead, does not return any response at all, which keep the AJAX request alive. One of the first adopters of Long Polling was Google when they introduced Google chat within Gmail.
 
As soon as you log into my chat application, an AJAX request is issued to the server. This works as the ‘subscribe’ part of PUSH.
On receiving this request, the server checks the database for any new messages. If it does not find any outstanding message, it returns no response and continues checking the database until a new message arrives. At this point, the AJAX request is still awaiting a response. As soon as a message arrives, the server side script encodes the message (along with a timestamp and the sender’s name) as a JSON object and returns it as a response to the AJAX request. This is the ‘publish’ part of PUSH.
 
The Javascript receives the response, prints it to your chat window, and instantly issues another AJAX request to the server ensuring that there is always an AJAX request listening for incoming messages. An outstanding AJAX request does not consume bandwidth, and thus, if you do not receive any messages for 30min, you do not consume any bandwidth.
 
XHR Long Polling has a drawback though: HTTP/1.1 specifications require that a client not have more than 2 simultaneous open connections any given domain. Thus, in case your application needs to listen for more than one event, you leave no available connections for other activities. As an example, Gmail listens for incoming chats and incoming emails among other things.
Most browsers allow up to 8 simultaneous connections, however Internet Explorer (at least till IE8) followed this specification rigidly. This issue can, however, be mitigated by distributing the server-side scripts across domains. So, you could have all incoming chats handled by one domain, while incoming mails are handled by another (I believe sub domains work too).

Gotcha!
--------

Initially, instead of using cookies to remember your name and who you are chatting with, I was using session variables. Since I do not persist users across sessions, I had no good reason to use cookies.
 
However using sessions, I ran into an issue where a simple AJAX request of sending a message would take upwards of 30 seconds, when it should take no more than a few. It took me about two hours to zero-in on the issue and find a work around. PHP maintains session variables in a file (no surprise in that), however once it opens the file to read a value (using session_start() ), it keeps the file open for as long as the script that called it is executing. As soon as the Chat application begins, the script that listens for messages accesses the session and continues executing. This makes the session values inaccessible for the script that sends messages.
 
I initially figured I would have the same issue with cookies as well, since they are stored in files too, however, it seems PHP closes the cookie file as soon as it is done reading it. So cookies worked out well for me.
 
Next Step
----------

In my current implementation of the Chat, even though the client-server connection  is implemented as PUSH, the server-database is still following a PULL model. Since the server side script and the database reside on the same physical server, there is no cost of bandwidth, and since CPU cycles come cheap, the server can poll the database every 1 second at no significant cost.
 
However, scaling the application to many users would take its toll. Eventually, I’d like to look into implementing PUSH between the server and database as well.
 
Known Bugs
-----------
 
The UI renders with some bugs on IE. Didn’t invest the time for cross-browser compatible HTML/CSS.
 (I know there are more. I just can’t think of them right now)