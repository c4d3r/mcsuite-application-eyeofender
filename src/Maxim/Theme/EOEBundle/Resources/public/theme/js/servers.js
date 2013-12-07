var Globals = {
    servers: {
        closed: [],
        open: []
    },
    minigames: ["SearchAndDestroy" ,"SurvivalGames", "SimpleHungergames"],
    playeramount: 0,
    domains: [],
    containers: {
        servers_open: document.getElementById("servers-open"),
        servers_closed: document.getElementById("servers-closed"),
        total_players: document.getElementById("totalplayers")
    }
}

/*function convTime(seconds)
{
    var time = parseInt(Math.abs(seconds),10)
    var minutes = Math.floor(time / 60);
    var seconds = time % 60;
    var output = "";

    if(minutes > 0){
        output += minutes + (minutes > 1 ? " minutes" : " minute");
    }
    if(seconds > 0){
        if(minutes > 0){
            //add a space
            output += " ";
        }
        output += seconds + (seconds > 1 ? " seconds" : " second");
    }
    return output;
}*/
/*function detPercentage(bar_parent, bar, playercount, maxPlayers)
{
    var currPercentage = (playercount / maxPlayers * 100);

    var color = "#fff",
        classes = "",
        percentage = currPercentage;
    if(currPercentage == 0)
    {
        color = "#000";
        classes = "progress-bar-info";
        percentage = 0;
    }
    else if(currPercentage > 0 && currPercentage < 20) {
        percentage = 20;
        classes = "progress-bar-info";
    }
    else if(currPercentage >= 20 && currPercentage < 50)
    {
        classes = "progress-bar-info";
    }
    else if(currPercentage >= 50 && currPercentage <= 80)
    {
        classes = "progress-bar-success";
    }
    else if(currPercentage >= 100)
    {
        classes = "progress-bar-danger";
    }
    else if(currPercentage > 80 && currPercentage != 100)
    {
        classes = "progress-bar-warning";
    }
    bar_parent.setAttribute("class", "progress " + classes);
    bar.setAttribute("style", "width:" + percentage + "%;color:" + color);
}*/
function getPlayerProfileLink(players, max)
{
    var output = "";
    if(typeof players != 'undefined' && players.length > 0 && players[0] != ""){
        $.each(players, function(index){
            if(index < max){
                output += '<a href="/profile/' + players[index] + '"><img src="https://minotar.net/helm/' + players[index] + '/30" rel="tooltip" data-original-title="' + players[index] + '" alt="player ' + players[index] + '"/></a>';
            }
        });
    }
    else
    {
        output = "There are no players online";
    }
    return output;
}

function loadTotalPlayers(){
    $.post('/landing/stats/total', function (data) {
        data = jQuery.parseJSON(data);
        $("#total-players span").text(data.total);
        $.each(data['total_players'], function(i, item){
            //ON EACH REALM: console.log("test: " + item['total']);
            //$("#total-players-" + (i +1)).text(item['total']);
            $("#server-image-" + item['server_type'].toLowerCase()).text(item['total'] + " players");

        });
    });
}

function update(apiDomains, jsonServerUrl) {
    Globals.playeramount = 0;
    Globals["domains"] = [];
    Globals["servers"]["open"] = [];
    Globals["servers"]["closed"] = [];
    //remove all childs from closed and open servers
    removeChilds(Globals["containers"]["servers_open"]);
    removeChilds(Globals["containers"]["servers_closed"]);

    var done = false;
    setLoading(Globals["containers"]["total_players"]);
    //get domains
    $.getJSON(apiDomains)
        .done(function( data ) {
            Globals["domains"] = data;
        })
        .always(function() {
            //get all servers
            $.getJSON(jsonServerUrl)
                .done(function( data ) {
                })
                .always(function() {
                    //setLoading(Globals["containers"]["total_players"], true);
                });
        })
}
function setLoading(element) {


    //create element
    /**
     *  <div class="progress progress-info progress-striped">
     *      <div class="bar" style="width: 20%"></div>
     *  </div>
     */

    removeChilds(element);

    var progressContainer = document.createElement("div");
    progressContainer.setAttribute("class", "progress progress-info progress-striped");

    var barContainer = document.createElement("div");
    barContainer.setAttribute("class", "bar");
    barContainer.setAttribute("style", "width:100%;");

    var barText = document.createTextNode("Loading...");
    barContainer.appendChild(barText);

    progressContainer.appendChild(barContainer);

    element.appendChild(progressContainer);
}

function removeChilds(element) {
    while(element.firstChild) {
        element.removeChild(element.firstChild);
    }
}

function updateServer(server, status) {

    var ip_s = server['ip'].replace(/\./g,'\\.');

    var wrapperContainer = document.createElement("div");
    wrapperContainer.id = "server-" + ip_s;
    wrapperContainer.setAttribute("class", "server");

    var detailsContainer = document.createElement("div");
    detailsContainer.setAttribute("class", "server-details server-details-tiny well");
    detailsContainer.setAttribute("style", "height:45px;width:92% !important");

    var labelSpan = document.createElement("span");
    labelSpan.setAttribute("class", "label label-info ip");
    var domainname = (Globals["domains"][server['ip']] !== undefined) ? Globals["domains"][server['ip']] : server['ip'];
    var labelText = document.createTextNode(domainname);

    var timeContainer = document.createElement("span");
    timeContainer.setAttribute("class", "server-next label label-inverse");
    timeContainer.setAttribute("rel", "tooltip");
    var timeText = document.createTextNode(convTime(server['currentTime']));

    var progressContainer = document.createElement("div"),
        bar               = document.createElement("div");

    progressContainer.setAttribute("class", "progress-bar progress-bar-info");
    bar.setAttribute("class", "bar");
    bar.setAttribute("rel", "tooltip");
    bar.setAttribute("style", "width: 20%");

    var playerText = document.createTextNode(server["players"].length + " / " + server["maxPlayers"]);

    bar.appendChild(playerText);
    detPercentage(progressContainer, bar, server["players"].length, server["maxPlayers"]);

    var containerTemp = document.createElement("div");


    labelSpan.appendChild(labelText);
    timeContainer.appendChild(timeText);

    containerTemp.appendChild(labelSpan);
    containerTemp.appendChild(timeContainer);


    progressContainer.appendChild(bar);

    // stats
    detailsContainer.appendChild(containerTemp);

    // progressbar
    detailsContainer.appendChild(progressContainer);

    // wrapper
    wrapperContainer.appendChild(detailsContainer);

    // status
    status.appendChild(wrapperContainer);
}
function totalPlayersServer() {
    removeChilds(Globals["containers"]["total_players"]);

    var playerHeader = document.createElement("h4");
    playerHeader.setAttribute("style", "margin:0px;");

    var playerHeaderText = document.createTextNode("Total Players");
    playerHeader.appendChild(playerHeaderText);

    var playerAmount = document.createElement("span");
    playerAmount.setAttribute("class", "hub-ip");
    playerAmount.setAttribute("style", "margin-left:5px;");

    var playerAmountText = document.createTextNode(Globals.playeramount);
    playerAmount.appendChild(playerAmountText);
    playerHeader.appendChild(playerAmount);

    Globals["containers"]["total_players"].appendChild(playerHeader);
}

function createServerHeader(text, status) {
    var headerContainer = document.createElement("div");
    if(status == "open") {
        headerContainer.setAttribute("class", "alert alert-success");
    } else {
        headerContainer.setAttribute("class", "alert alert-error");
        headerContainer.setAttribute("style", "clear:both;");
    }
    var headerTextStrong = document.createElement("strong");

    var headerText = document.createTextNode(text);
    headerTextStrong.appendChild(headerText);
    headerContainer.appendChild(headerTextStrong);

    if(status == "open") {
        Globals["containers"]["servers_open"].appendChild(headerContainer);
    } else {
        Globals["containers"]["servers_closed"].appendChild(headerContainer);
    }

}
function jsonServerFeed(data) {

    for(var i = 0; i < data.length; i++) {
        Globals.playeramount += data[i]["players"].length;
        if(data[i].currentTime > 0) {
            Globals["servers"]["closed"].push(data[i]);
        } else {
            Globals["servers"]["open"].push(data[i]);
        }
    }

    //sort open servers
    Globals["servers"]["open"].reverse();

    /*
        DOM CREATE OPEN SER
     */
    createServerHeader("Available servers", "open");
    for(i = 0; i < Globals["servers"]["open"].length; i++) {
        updateServer(Globals["servers"]["open"][i], Globals["containers"]["servers_open"]);
    }

    createServerHeader("Servers in progress", "closed");
    for(i = 0; i < Globals["servers"]["closed"].length; i++) {
        updateServer(Globals["servers"]["closed"][i], Globals["containers"]["servers_closed"]);
    }

    totalPlayersServer();
}