var input = document.getElementById("input");
var currentItem = -1;

input.addEventListener("input", function(e) {
    //close all open items;
    closeAllItems();

    //check if value is "" or null;
    if(!this.value) {return;}

    //create div that will contain items;
    var list = document.createElement("DIV");
    list.setAttribute("id", "autocomplete-list");
    list.setAttribute("class", "autocomplete-list");
    this.parentNode.appendChild(list);

    //check whitch items needed to be in that div;
    ////put them in that div;
    for(var i = 0; i < tracks.length; i++) {
        if(this.value.toLowerCase() == tracks[i].substr(0, this.value.length).toLowerCase()) {
            var item = document.createElement("DIV");
            item.innerHTML = "<strong>" + tracks[i].substr(0, this.value.length) + "</strong>" + tracks[i].substr(this.value.length);
            item.innerHTML += '<input type="hidden" value="' + tracks[i] + '">';
            //add eventlistener for each item, when click, put the name in the input;
            item.addEventListener("click", function(e) {
                input.value = this.getElementsByTagName("input")[0].value;
                closeAllItems();
            });
            list.appendChild(item);
        }
    }
});

//add eventlistener, keydown, when pressed upp or down or click, change active item;
input.addEventListener("keydown", function(e) {
    var items = document.getElementById("autocomplete-list")
    if(items) items = items.getElementsByTagName("div");
    if (e.keyCode == 40) { //down
        currentItem++;
        addActive(items);
        e.preventDefault();
    }else if (e.keyCode == 38) { //up
        currentItem--;
        addActive(items);
        e.preventDefault();
    }else if (e.keyCode == 13) { //enter
        if(currentItem >= 0) {
            items[currentItem].click();
            e.preventDefault();
        }
    }
});

//div id = autocomplete-list;

function closeAllItems() {
    var items = document.getElementById("autocomplete-list");
    if(!items) {return;}
    items.parentNode.removeChild(items);
    currentItem = -1;
}

function addActive(items) {
    removeActive(items);

    if(currentItem >= items.length) {currentItem = 0;}
    if(currentItem < 0) {currentItem = items.length - 1;}
    items[currentItem].classList.add("active");
}

function removeActive(items) {
    for(var i = 0; i < items.length; i++) {
        items[i].classList.remove("active");
    }
}