# Bing Chat Memory

## About

Normally, conversations are wiped when the user terminates a session with Bing Chat. This web app allows the user to instruct Bing Chat to save a conversation for future reference, the user can then create a new session in the future but still refer to previous information which Bing Chat will know about as it's stored on the page. I had this idea a little while ago, but I wasn't sure how to implement it until I came across "Indirect Prompt Injection Threats" on https://greshake.github.io/.


A video of this web app can be seen here:

[![Bing Chat YouTube video](https://img.youtube.com/vi/D57CaLXYXX0/0.jpg)](https://www.youtube.com/watch?v=D57CaLXYXX0)


https://youtu.be/D57CaLXYXX0

## Instructions

1. Find a hosting solution and deploy the .html, .php, .js and .css file onto it.
2. On your chosen hosting solution, create a new MySQL database, name the database whatever you want and then use the "create_table.sql" file or refer to its contents to create the table that this web app will rely on.
3. The PHP file expects the following environment variables to be set: 
    * DB_HOST
        * The URL for your database
    * DB_USERNAME
        * The username for the account the web app will be using to access the database
    * DB_PASSWORD
        * The password for the account the web app will be using to access the database
    * DB_NAME 
        * The name of your database
   * DELETE_PASSWORD
        * A password to protect the "delete" GET request function. The "delete" function wipes all messages from the database.
        * Example: https://mywebapp.com/bingmemory.php?delete=abc123

**NOTE: if you can't define environment variables, modify the .php file and remove the "getenv" function calls and replace them with hardcoded values**   

4. Go to e.g. https://mywebapp.com/bingmemory.html, open up Bing Chat and start chatting.
5. Instruct Bing to save unsaved messages by saying "SAVE!" and open the URL it returns. You can refresh the page to see your saved messages.
6. Once the messages have been saved, feel free to close Bing and leave the web app. When you return at a later time, simply reopen Bing on the web app, and it will pick up where you left off by reading the page for context.