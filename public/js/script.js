/*
*   Author: Wiebe Ranzijn
*   Description: Json Filter & Search, with Ajax
*/

// --------------------------------------------------------------------- Main variables

let search__input  = document.querySelector('.search__input');
let search__filter = document.querySelector('.search__filter');
let search__sort   = document.querySelector('.search__sort');
let list_container = document.querySelector('.list-container');
let url            = window.location;

// --------------------------------------------------------------------- Get all search querys

function prepareSearchRequest () {
    let answer = ajax(
        url,
        {
            'search' : search__input.value,
            'filter' : search__filter.value,
            'sort'   : search__sort.value
        },
        'GET'
    );
}

// --------------------------------------------------------------------- Ajax request

const ajax = (url, parameters, method) => {
    if (method.toLowerCase() == 'get') {
        let string = "";
        for (let i in parameters) {
            let key = i;
            let value = parameters[i];
            (key == 'sort') ? string += `${key}=${value}` : string += `${key}=${value}&`;
        };
        parameters = string;
    }

    new Promise((resolve, reject) => {
        const xmlhttp = new XMLHttpRequest()
        xmlhttp.onreadystatechange = () => {
            if (xmlhttp.status === 200) {
                let serverResponse = xmlhttp.responseText
                console.log(xmlhttp.responseText)
                resolve(serverResponse)
            } else {
                reject(xmlhttp.status);
                console.error(xmlhttp.status)
                console.error(xmlhttp.responseText)
                console.error("xmlhttp failed")
            }

            list_container.innerHTML = xmlhttp.responseText;
        }
        console.log(`${url}?${parameters}`)
        xmlhttp.open(method, `${url}?${parameters}`, true)
        xmlhttp.send();
    })
}

// --------------------------------------------------------------------- Event listeners

search__input.addEventListener('input', prepareSearchRequest);
search__filter.addEventListener('change', prepareSearchRequest);
search__sort.addEventListener('change', prepareSearchRequest);