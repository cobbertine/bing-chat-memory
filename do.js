

let getSavedMessagesRequest = null;

function getSavedMessagesResponse()
{
        // 4 means done
        if(getSavedMessagesRequest.readyState != 4)
        {
            return; // Leave this function temporarily.
        } 

        // 200 is OK
        if(getSavedMessagesRequest.status == 200) 
        {
            const savedMessagesElement = document.getElementById("saved_messages");
            const responseData = getSavedMessagesRequest.responseText;
            const responseLines = responseData.split("\n");

            for(let i = 0; i < responseLines.length; i++)
            {
                const messageElement = document.createElement("p");
                messageElement.className = "saved_message";
                const messageElementText = document.createTextNode(responseLines[i]);
                messageElement.appendChild(messageElementText);
                savedMessagesElement.appendChild(messageElement);
                console.log("Adding message... " + responseLines[i]);
            }
            
            console.log("All good. Any existing messages have now been added.");
        }
        else
        {
            console.error("Something went wrong getting messages...");
        }
}

function main()
{
    getSavedMessagesRequest = new XMLHttpRequest();
    getSavedMessagesRequest.onreadystatechange  = getSavedMessagesResponse;
    getSavedMessagesRequest.open("GET", "/bingmemory.php?read", true);
    getSavedMessagesRequest.send();
}



window.onload = main;