/*
*   Author: Wiebe Ranzijn
*   Description: Json Filter & Search, with Ajax
*/

// --------------------------------------------------------------------- Main variables

window.addEventListener('DOMContentLoaded', (event) => {
    prepareSearchRequest()
})


// --------------------------------------------------------------------- Main variables

let search__input  = document.querySelector('.search__input')
let search__filter = document.querySelector('.search__filter')
let search__sort   = document.querySelector('.search__sort')
let list_container = document.querySelector('.list-container')
let url            = window.location

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
    )
}

// --------------------------------------------------------------------- Ajax request

const ajax = (url, parameters, method) => {
    if (method.toLowerCase() == 'get') {
        let string = ""
        for (let i in parameters) {
            let key = i
            let value = parameters[i];
            (key == 'sort') ? string += `${key}=${value}` : string += `${key}=${value}&`
        }
        parameters = string
    }

    new Promise((resolve, reject) => {
        let xmlhttp = new XMLHttpRequest()
        xmlhttp.onreadystatechange = () => {
            if(this.readyState == 4 || this.status == 200) {
                let serverResponse = xmlhttp.responseText
                console.log(xmlhttp.responseText)
                resolve(serverResponse)
            } else {
                reject(xmlhttp.status)
                console.error(`status: ${xmlhttp.status}`)
                console.error(xmlhttp.responseText)
                console.error("xmlhttp failed")
            }

            list_container.innerHTML = xmlhttp.responseText;
        }
        console.log(`${url}?${parameters}`)
        xmlhttp.open(method, `${url}?${parameters}`, true)
        xmlhttp.send()
    })
}

// --------------------------------------------------------------------- Add item to cookies for webshop

let shopCounter = document.querySelector('.shop-counter')

function addToCookies (value) {
    let expires = ""
    let date = new Date()
    date.setTime(date.getTime() + (12*24*60*60*1000)) // Keep the cookie for 12 days
    expires = "; expires=" + date.toUTCString()
    if (!document.cookie.length) document.cookie = `items=`+ JSON.stringify(['nothing']) + `${expires}; path=/`;
    console.log(getCookie("items"));
    if (value) {
        let cookieArray = getCookie("items");
        document.cookie = `items=` + JSON.stringify(cookieArray) + `${expires}; path=/`;
    }
}

// --------------------------------------------------------------------- Get cookie value

function getCookie (key) {
    var re = new RegExp(key + "=([^;]+)")
    var value = re.exec(document.cookie)
    return (value != null) ? unescape(value[1]) : null
  }

// --------------------------------------------------------------------- Event listeners

search__input.addEventListener('input', prepareSearchRequest)
search__filter.addEventListener('change', prepareSearchRequest)
search__sort.addEventListener('change', prepareSearchRequest)